# Dokumentasi Perbaikan Kalkulasi Nilai Rapor (Filter Rombel)

## 1. Latar Belakang Masalah
Ditemukan ketidaksesuaian nilai pada dashboard "Kalkulasi & Sinkronisasi Nilai Rapor" untuk kelas **Zamrud** (dan kelas-kelas lain dengan jumlah pertemuan berbeda). 
- **Gejala**: Nilai formatif yang diupload (misal: 95) tampil sebagai setengahnya (47.5).
- **Penyebab**: Fungsi `getData()` di `NilaiRaporController` mengambil data nilai formatif secara global (per mata pelajaran dan tahun ajaran), sehingga pembagi (divisor) rata-rata menggunakan jumlah pertemuan terbanyak dari kelas lain, bukan kelas yang bersangkutan.

## 2. Analisis Teknis
Dalam file `NilaiRaporController.php`:
- Query untuk `$qFormatif` tidak menyertakan filter `rombel_id`.
- Fungsi `_getPembagiDinamis($formatifs)` menghitung `max_nh_pert` dan `max_uh_pert` dari seluruh data yang diambil.
- Jika Kelas A punya 2 LM (Pertemuan) dan Kelas B punya 1 LM, maka Kelas B akan ikut dibagi 2.

## 3. Rencana Perbaikan (Plan)

### A. Penambahan Filter Rombel pada Preview (`getData`)
Menambahkan filter `rombel_id` pada pengambilan data formatif dan sumatif agar kalkulasi bersifat lokal per kelas.

```php
// File: app/Controllers/GuruMapel/NilaiRaporController.php
// Fungsi: getData()

$qFormatif = $db->table('nilai_formatif')
    ->where('mapel_id', $mapel_id)
    ->where('tahun_ajaran_id', $ta_id)
    ->where('rombel_id', $rombel_id); // Tambahkan baris ini (Hanya untuk formatif karena tabel sumatif tidak memiliki kolom rombel_id)
```

### B. Implementasi "Smart Cleaner" pada Pembagi Dinamis
Mengubah logika `_getPembagiDinamis` agar hanya menghitung pertemuan (LM) sebagai pembagi jika terdapat setidaknya satu data nilai yang valid (di atas 0). Data bernilai 0, null, atau kosong akan diabaikan dalam penentuan progres kelas.

```php
// File: app/Controllers/GuruMapel/NilaiRaporController.php
// Fungsi: _getPembagiDinamis()

foreach ($formatifs as $f) {
    $nilai = (float)($f['nilai_angka'] ?? 0);
    if ($nilai > 0) { // Hanya hitung progres jika ada nilai > 0
        // ... update max_nh_pert / max_uh_pert ...
    }
}
```

### C. Validasi Konsistensi pada Fungsi Sinkronisasi (`syncNilai`)
Memastikan fungsi sinkronisasi yang menulis ke database juga menggunakan filter yang sama ketatnya. (Berdasarkan audit, filter ini sudah ada namun akan dipastikan kembali konsistensinya).

### C. Pencegahan Regresi
- Memastikan query `nilai_sumatif` juga difilter berdasarkan `rombel_id` untuk meningkatkan performa query (meskipun kalkulasi sumatif saat ini sudah benar karena dihitung per siswa).
- Menjaga logika "Smart Algorithm" (Dynamic Divisor) tetap berjalan agar siswa tidak dipenalti jika guru belum selesai memberikan materi di kelas tersebut.

## 4. Langkah Verifikasi
1. **Refresh Dashboard**: Membuka kembali menu Nilai Rapor untuk kelas Zamrud.
2. **Cek Nilai**: Memastikan "Rata NH" dan "Rata UH" kembali ke nilai asli (misal: 95).
3. **Simulasi Sinkronisasi**: Menekan tombol "Sinkronisasi Kelas Ini" dan memastikan status berubah menjadi "Disinkronisasi" dengan nilai akhir yang benar (misal: 94).

## 5. Dampak dan Risiko
- **Dampak**: Memperbaiki tampilan preview dan memastikan data di database konsisten dengan upload Excel.
- **Risiko**: Rendah. Perubahan hanya bersifat penambahan filter `where` pada query yang sudah ada. Tidak mengubah struktur database atau menghapus data.

---
*Dibuat oleh Antigravity AI - 2026-05-07*
