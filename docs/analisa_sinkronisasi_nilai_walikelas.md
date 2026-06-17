# Analisis Kelayakan Fitur Sinkronisasi Nilai Massal di Dashboard Wali Kelas

## 1. Latar Belakang & Masalah Saat Ini

Sistem E-Rapor ini menggunakan tabel `nilai_rapor` sebagai sumber data utama untuk mencetak rapor siswa (baik Rapor Akademik maupun Rapor Lengkap). Tabel ini berisi kompilasi nilai akhir (`nilai_akhir`), predikat (`predikat`), serta deskripsi capaian kompetensi (`deskripsi_tertinggi`, `deskripsi_terendah`) untuk setiap mata pelajaran.

Data di tabel `nilai_rapor` tidak terupdate otomatis ketika guru mata pelajaran mengisi nilai harian/sumatif. Data tersebut baru terisi/terkalkulasi setelah proses **Sinkronisasi** dilakukan. 

Saat ini:
- Fitur sinkronisasi nilai rapor dilakukan oleh masing-masing **Guru Mata Pelajaran** via `App\Controllers\GuruMapel\NilaiRaporController::syncNilai()` secara individual per mata pelajaran.
- **Wali Kelas tidak memiliki tombol sinkronisasi**. Wali Kelas harus menunggu setiap Guru Mapel masuk ke akun masing-masing dan mengeklik tombol "Sinkronisasikan!" untuk mata pelajaran mereka.
- Jika ada perbaikan nilai dari Guru Mapel tetapi mereka lupa mengeklik sinkron kembali, atau jika Guru Mapel lupa melakukan sinkronisasi awal, nilai pada rapor yang dicetak oleh Wali Kelas akan kosong atau tidak sesuai (out of sync).

---

## 2. Analisis Kelayakan Teknis (Feasibility Study)

**Apakah di dashboard Wali Kelas bisa melakukan sinkronisasi nilai secara massal untuk seluruh mata pelajaran di kelas tersebut?**

**Jawabannya: SANGAT BISA.**

Berikut adalah rincian analisis teknisnya:

### A. Kemandirian Logika Sinkronisasi
Fungsi sinkronisasi nilai di `NilaiRaporController::syncNilai()` membutuhkan input parameter berupa:
1. `tahun_ajaran_id` (Tahun Ajaran Aktif)
2. `rombel_id` (ID Kelas/Rombel)
3. `mapel_id` (ID Mata Pelajaran)
4. `kategori` (Jenis Rapor: "Tengah Semester" atau "Akhir Semester")

Logika di dalam fungsi tersebut sepenuhnya bersifat database-driven:
- Mengambil bobot nilai dari `setting_bobot_nilai`.
- Mengambil aturan predikat dari `setting_aturan_nilai`.
- Mengambil deskripsi kompetensi dari `master_lm`.
- Mengalkulasi nilai formatif & sumatif seluruh siswa aktif di rombel tersebut, lalu melakukan upsert (insert/update) ke tabel `nilai_rapor`.

Karena logika ini **tidak bergantung pada session Guru Mapel tertentu** (hanya memerlukan ID rombel, mapel, dan tahun ajaran), maka logika ini bisa dipanggil secara aman oleh akun dengan role **Wali Kelas** (yang memiliki akses ke `rombel_id` kelas perwaliannya).

### B. Daftar Mata Pelajaran Rombel
Untuk melakukan sinkronisasi massal, sistem perlu mengetahui daftar mata pelajaran yang diajarkan pada rombel tersebut. Wali Kelas sudah memiliki akses ke data ini. Seperti yang diimplementasikan pada `App\Controllers\WaliKelas\ProgresNilaiController::index()`, daftar mata pelajaran rombel dapat diperoleh dari:
1. **Kurikulum Rombel (`rombel.kurikulum_id` -> `mata_pelajaran`)** sebagai acuan utama Kurikulum Merdeka.
2. **Jadwal Pelajaran (`jadwal_pelajaran`)** sebagai fallback kedua.
3. **Nilai Eksisting (`nilai_sumatif`)** sebagai fallback ketiga.

Dengan daftar `mapel_id` ini, sistem dapat melakukan perulangan (loop) untuk mensinkronisasi nilai setiap mata pelajaran satu per satu dalam satu kali klik.

---

## 3. Rencana Implementasi (Proposed Workflow)

Untuk mewujudkan fitur ini, kita dapat melakukan modifikasi minimal pada backend dan frontend dengan langkah-balik berikut:

### Langkah 1: Refaktorisasi Logika Sinkronisasi (Opsional tapi Direkomendasikan)
Agar tidak terjadi duplikasi kode (DRY - Don't Repeat Yourself), logika perhitungan dan upsert nilai dari `App\Controllers\GuruMapel\NilaiRaporController::syncNilai()` dapat dipindahkan ke:
- **Helper baru atau model** (misal: `App\Models\NilaiRaporModel::executeSync($ta_id, $rombel_id, $mapel_id, $kategori)`)
- Sehingga, baik `NilaiRaporController` (Guru Mapel) maupun `PreviewRaporController`/`ProgresNilaiController` (Wali Kelas) cukup memanggil fungsi yang sama.

*Alternatif Cepat:* Jika ingin menghindari perombakan struktur file, kita bisa menyalin logika kalkulasi tersebut ke dalam controller Wali Kelas.

### Langkah 2: Pembuatan Endpoint AJAX di Controller Wali Kelas
Menambahkan method baru di `App\Controllers\WaliKelas\PreviewRaporController.php` (atau `ProgresNilaiController.php`), misalnya `syncSemuaMapel()`:
```php
public function syncSemuaMapel()
{
    $db = \Config\Database::connect();
    $guru = $this->getGuru();
    $ta_id = $this->request->getPost('tahun_ajaran_id');
    $rombel_id = $this->request->getPost('rombel_id');
    $kategori = $this->request->getPost('kategori'); // "Tengah Semester" atau "Akhir Semester"

    if (!$rombel_id || !$ta_id || !$kategori) {
        return $this->response->setJSON(['status' => 'error', 'message' => 'Parameter tidak lengkap.']);
    }

    // 1. Ambil daftar mapel aktif di rombel ini (sama seperti di ProgresNilaiController)
    $list_mapel = $this->getMapelByRombel($rombel_id); 

    $db->transBegin();
    try {
        foreach ($list_mapel as $mapel) {
            // Jalankan kalkulasi sinkronisasi untuk $mapel['mapel_id']
            $this->_executeSyncSingleMapel($ta_id, $rombel_id, $mapel['mapel_id'], $kategori);
        }
        $db->transCommit();
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Berhasil sinkronisasi seluruh mata pelajaran untuk kelas ini!'
        ]);
    } catch (\Throwable $e) {
        $db->transRollback();
        return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
```

### Langkah 3: Penambahan Route Baru
Daftarkan route POST di `backend/app/Config/Routes.php` di dalam grup `'wali'`:
```php
$routes->post('preview-rapor/sync-semua', 'PreviewRaporController::syncSemuaMapel');
```

### Langkah 4: Modifikasi UI Dashboard Wali Kelas
Tambahkan tombol **"Sinkronkan Semua Nilai"** pada halaman:
- `backend/app/Views/WaliKelas/preview-rapor.php` (di samping tombol Cetak Massal), atau
- `backend/app/Views/WaliKelas/progres-nilai.php`.

Ketika tombol diklik, JavaScript akan mengirimkan request AJAX menggunakan SweetAlert2 dengan animasi loading.

---

## 4. Analisis Dampak & Performansi (Trade-off)

| Aspek | Detail Analisis & Mitigasi |
| :--- | :--- |
| **Beban Server (CPU/RAM)** | **Dampak:** Sinkronisasi 1 kelas (misal: 10 mapel x 30 siswa = 300 data) memerlukan proses pembacaan nilai mentah formatif/sumatif dan melakukan operasi penyimpanan (upsert). Jika tidak dioptimasi, ini bisa memicu memory limit/timeout.<br>**Mitigasi:** <br>1. Jalankan semua query di dalam blok **Database Transaction** (`transBegin` / `transCommit`) agar eksekusi query ke DB berlangsung sangat cepat (kurang dari 2 detik).<br>2. Gunakan batching query jika diperlukan, namun untuk skala 1 kelas (di bawah 500 records), query standar CI4 sudah sangat memadai. |
| **Integritas Data** | **Keuntungan:** Mengurangi risiko kesalahan cetak rapor akibat ada mata pelajaran yang belum disinkronkan oleh Guru Mapel.<br>**Catatan:** Jika Guru Mapel belum menginputkan nilai sama sekali, maka hasil sinkronisasi untuk mapel tersebut akan tetap kosong/nol. Wali Kelas tetap perlu memantau progres pengisian nilai di menu "Progres Nilai". |
| **Pengalaman Pengguna (UX)** | **Keuntungan:** Wali Kelas memiliki kendali penuh atas kesiapan data sebelum melakukan cetak rapor massal, menghemat waktu komunikasi dengan Guru Mapel hanya untuk meminta klik tombol sinkron. |

---

## 5. Kesimpulan & Rekomendasi

**Fitur Sinkronisasi Nilai Massal di dashboard Wali Kelas sangat direkomendasikan untuk diimplementasikan.** 

Fitur ini secara teknis aman dilakukan, tidak merusak struktur database yang ada, dan akan sangat mempermudah alur kerja Wali Kelas menjelang pembagian rapor. Implementasi dapat dilakukan dalam waktu singkat dengan memanfaatkan logika kalkulasi nilai yang sudah ada di `NilaiRaporController`.

---
**Status Analisis**: Selesai
**Penyusun**: Antigravity (AI Coding Assistant)
