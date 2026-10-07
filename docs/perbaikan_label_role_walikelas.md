# Analisa & Dokumentasi Perbaikan Penentuan Role Wali Kelas Saat Login

## 1. Deskripsi Masalah
Salah seorang guru/wali kelas (**Ibu Tika Yeardila, M.Pd**) melaporkan kendala pada aplikasi e-Rapor:
> *"Izin pak, saya tika yeardila wali kelas carnelian yg sblumnya saya sbg wali kelas biduri, saat saya buka aplikasi raport, saya msih berstatus wali kelas biduri pak, smntra utk tahun ini sya wali kelas 7 carnelian"*

Meskipun pada data master rombel tahun ajaran aktif (**2026/2027 Ganjil**) Ibu Tika sudah diatur oleh Admin sebagai Wali Kelas **7 Carnelian**, sistem saat login dan tampilan di navbar tetap memunculkan status **Wali Kelas Biduri** (kelas pada tahun ajaran sebelumnya).

---

## 2. Hasil Investigasi & Penyebab Masalah (Root Cause)

### A. Kondisi Data di Database
1. **Tahun Ajaran Aktif:**
   * ID `10` : `2026/2027 Ganjil` (Status: `Aktif`).
2. **Data Guru:**
   * Nama: `Tika Yeardila, M.Pd` (ID Guru: `44`, User ID: `123`).
3. **Data Rombel yang Terhubung dengan ID Guru 44:**
   * Rombel ID `13` : Kelas `Biduri` (Tingkat VIII) pada Tahun Ajaran `2025/2026 Genap` (ID TA: `9` - Status: `Arsip`).
   * Rombel ID `80` : Kelas `Carnelian` (Tingkat VII) pada Tahun Ajaran `2026/2027 Ganjil` (ID TA: `10` - Status: `Aktif`).

### B. Penyebab Masalah pada Kode
Pada file `app/Controllers/Auth/LoginController.php` (fungsi `mapRolesForLogin`):
```php
// KODE LAMA:
$rombelModel = new \App\Models\Admin\RombelModel();
$rombel = $rombelModel->where('wali_kelas_id', $guru['id'])->first();
```
Query di atas mengambil record pertama (`first()`) yang ditemukan di tabel `rombel` yang memiliki `wali_kelas_id = 44` **tanpa filter Tahun Ajaran Aktif** (`id_tahun_ajaran = 10`) dan **tanpa sorting Tahun Ajaran terbaru**.

Karena record kelas **Biduri** (ID: 13) dibuat lebih dulu dibandingkan kelas **Carnelian** (ID: 80), database selalu mengembalikan record pertama yaitu kelas lama (Biduri). Hal ini menyebabkan:
1. Pilihan Role pada modal login berlabel `"Wali Kelas Biduri"`.
2. Session `role_label` tersimpan sebagai `"Wali Kelas Biduri"`.
3. Header profil di navbar menampilkan `"Wali Kelas Biduri"`.

---

## 3. Langkah & Detail Perbaikan

### A. Pembaruan Logika di `LoginController.php`
Logika query diubah agar:
1. Memeriksa dan memprioritaskan rombel pada **Tahun Ajaran yang berstatus `Aktif`**.
2. Jika tidak ditemukan pada TA aktif, sistem mengurutkan berdasarkan **Tahun Ajaran terbaru** (`ORDER BY id_tahun_ajaran DESC`).

**Kode Baru di `app/Controllers/Auth/LoginController.php`:**
```php
$guruModel = new \App\Models\Admin\GuruTendikModel();
$guru = $guruModel->where('user_id', $userId)->first();
if ($guru) {
    $db = \Config\Database::connect();
    $taAktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();
    $idTaAktif = $taAktif ? $taAktif['id'] : null;

    $rombel = null;
    if ($idTaAktif) {
        $rombel = $db->table('rombel')
                     ->where('wali_kelas_id', $guru['id'])
                     ->where('id_tahun_ajaran', $idTaAktif)
                     ->get()->getRowArray();
    }
    if (!$rombel) {
        $rombel = $db->table('rombel')
                     ->where('wali_kelas_id', $guru['id'])
                     ->orderBy('id_tahun_ajaran', 'DESC')
                     ->get()->getRowArray();
    }

    if ($rombel) {
        $roles[] = [
            'role_id'      => 3,
            'key'          => 'wali_kelas',
            'label'        => 'Wali Kelas ' . $rombel['nama_rombel'],
            'redirect_url' => base_url('/wali/ringkasan-kelas')
        ];
        $hasWaliKelasRole = true;
    }
}
```

### B. Pembaruan Logika di `TahfidzController.php` (WaliKelas)
Pada method `index()` di `app/Controllers/WaliKelas/TahfidzController.php`, query pengambilan rombel wali kelas juga diselaraskan menggunakan `guru_tendik.id` dan memfilter `id_tahun_ajaran` aktif.

---

## 4. Hasil Pengujian (Testing)
Pengujian dilakukan terhadap akun user Ibu Tika Yeardila (User ID `123`, Guru ID `44`):
1. **Pengecekan Query Rombel:**
   * Dihubungkan ke Tahun Ajaran Aktif (ID: `10` / 2026/2027 Ganjil).
   * Rombel terpilih: `Carnelian` (ID: `80`, Tingkat: `VII`).
2. **Output Role Label:**
   * Menghasilkan label: **`Wali Kelas Carnelian`**.
3. **Hasil:** Berhasil diperbaiki dan sinkron dengan penetapan tahun ajaran aktif.
