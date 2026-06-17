# Analisis Masalah Duplikasi Nilai Ekstrakurikuler pada Cetak Rapor

Dokumen ini mendokumentasikan analisis root cause (penyebab utama) dari masalah tercetaknya nilai ekstrakurikuler ganda (double) saat cetak rapor akhir semester, di mana nilai tengah semester (STS) ikut tercetak pada halaman rapor akhir semester (SAS).

---

## 1. Deskripsi Masalah
Saat Wali Kelas melakukan cetak/preview rapor akhir semester (SAS), tabel **Kegiatan Ekstrakurikuler** menampilkan data ganda untuk ekstrakurikuler yang sama (misal: PMR muncul di baris 1 dan baris 2 dengan predikat dan keterangan yang sama/berbeda). Hal ini disebabkan oleh masuknya data nilai ekstrakurikuler kategori **Tengah Semester** ke dalam cetakan rapor **Akhir Semester**.

---

## 2. Root Cause (Penyebab Utama)
Penyebab utama masalah ini adalah **tidak adanya filter kolom `kategori`** pada query pengambilan data nilai ekstrakurikuler (`nilai_ekskul`) di controller Wali Kelas, yaitu [PreviewRaporController.php](file:///d:/xampp/htdocs/erapoteasy/backend/app/Controllers/WaliKelas/PreviewRaporController.php).

### Analisis Kode:
1. Di dalam method `printPDF()` pada [PreviewRaporController.php](file:///d:/xampp/htdocs/erapoteasy/backend/app/Controllers/WaliKelas/PreviewRaporController.php#L251), parameter kategori cetak rapor sebenarnya sudah ditangkap dari request query:
   ```php
   $kategori = $this->request->getGet('kategori') ?? 'Akhir Semester';
   ```
2. Namun, saat mengambil data ekstrakurikuler dari tabel `nilai_ekskul` (pada baris 446-464), query database **hanya memfilter berdasarkan `siswa_id`**, `tahun_ajaran`, dan `semester`:
   ```php
   $ekskul = $db->table('nilai_ekskul ne')
                ->select('me.nama_ekskul as kegiatan, ne.predikat, ne.keterangan')
                ->join('master_ekskul me', "CONVERT(me.id USING utf8mb4) COLLATE utf8mb4_general_ci = CONVERT(ne.$ekskul_id_field USING utf8mb4) COLLATE utf8mb4_general_ci", 'left', false)
                ->where(['ne.siswa_id' => $siswa_id, 'ne.' . $fTA_Ekskul => $val_ta_ekskul, 'ne.semester' => $semester])
                ->get()->getResultArray();
   ```
3. Akibatnya, jika siswa memiliki nilai ekstrakurikuler untuk kategori **Tengah Semester** dan **Akhir Semester** pada semester yang sama, kedua baris data tersebut akan terpanggil oleh `getResultArray()`. Loop pada view `rapor_lengkap.php` kemudian merender kedua baris tersebut sehingga nilainya terlihat duplikat (double).

---

## 3. Perbandingan dengan Controller Lain
Masalah ini tidak terjadi pada cetak rapor dari sisi **Admin** atau **Orang Tua** karena kodenya sudah mengimplementasikan filter `kategori` dengan benar:

### A. Cetak Rapor Admin ([CetakRaporController.php](file:///d:/xampp/htdocs/erapoteasy/backend/app/Controllers/Admin/CetakRaporController.php#L546-L549))
Pada controller cetak rapor admin, query dibatasi dengan memeriksa keberadaan kolom `kategori` terlebih dahulu sebelum melakukan filtering:
```php
if ($this->db->fieldExists('kategori', 'nilai_ekskul')) {
    $builderEkskul->where('ne.kategori', $kategori);
}
```

### B. Cetak Rapor Orang Tua ([AkademikController.php](file:///d:/xampp/htdocs/erapoteasy/backend/app/Controllers/OrangTua/AkademikController.php#L578))
Di bagian pengunduhan/cetak rapor dari sisi orang tua, filter kategori juga sudah diterapkan secara aman:
```php
if ($db->fieldExists('kategori', 'nilai_ekskul')) {
    $builderEkskul->where('ne.kategori', $kategori);
}
```

---

## 4. Temuan Tambahan (Potensi Bug pada Nilai Tahfidz)
Selain ekstrakurikuler, query data **tahfidz** (`nilai_tahfidz`) pada method `printPDF()` di [PreviewRaporController.php](file:///d:/xampp/htdocs/erapoteasy/backend/app/Controllers/WaliKelas/PreviewRaporController.php#L503-L505) dan baris 531-534 juga **tidak memfilter berdasarkan kategori**. 

Walaupun tidak menyebabkan baris ganda (karena menggunakan `getRowArray()`), hal ini dapat menyebabkan nilai tahfidz yang tercetak adalah nilai dari kategori yang salah (misalnya nilai Tengah Semester tercetak di Rapor Akhir Semester) tergantung dari urutan penyimpanan database (*database ordering fallback*).

---

## 5. Rekomendasi Solusi Teknis
Untuk memperbaiki masalah ini, perubahan perlu diterapkan pada file [PreviewRaporController.php](file:///d:/xampp/htdocs/erapoteasy/backend/app/Controllers/WaliKelas/PreviewRaporController.php) di method `printPDF()`.

### A. Perbaikan Query Nilai Ekstrakurikuler:
Ubah bagian pemanggilan data ekskul menjadi seperti berikut:
```php
        // =========================================================================
        // 8. EKSKUL & TAHFIDZ (SYNC ADMIN)
        // =========================================================================
        $ekskul = [];
        if ($db->tableExists('nilai_ekskul')) {
            $fTA_Ekskul = $db->fieldExists('tahun_ajaran_id', 'nilai_ekskul') ? 'tahun_ajaran_id' : 'tahun_ajaran';
            $val_ta_ekskul = ($fTA_Ekskul === 'tahun_ajaran_id') ? $ta_id : $tahun_ajaran;
            
            if ($db->tableExists('master_ekskul')) {
                $ekskul_id_field = $db->fieldExists('ekskul_id', 'nilai_ekskul') ? 'ekskul_id' : 'id_ekskul';
                $builderEkskul = $db->table('nilai_ekskul ne')
                            ->select('me.nama_ekskul as kegiatan, ne.predikat, ne.keterangan')
                            ->join('master_ekskul me', "CONVERT(me.id USING utf8mb4) COLLATE utf8mb4_general_ci = CONVERT(ne.$ekskul_id_field USING utf8mb4) COLLATE utf8mb4_general_ci", 'left', false)
                            ->where(['ne.siswa_id' => $siswa_id, 'ne.' . $fTA_Ekskul => $val_ta_ekskul, 'ne.semester' => $semester]);
                
                // Tambahkan filter kategori jika kolom kategori ada
                if ($db->fieldExists('kategori', 'nilai_ekskul')) {
                    $builderEkskul->where('ne.kategori', $kategori);
                }
                $ekskul = $builderEkskul->get()->getResultArray();
            } else {
                $builderEkskul = $db->table('nilai_ekskul')
                            ->select('nama_kegiatan as kegiatan, predikat, keterangan')
                            ->where(['siswa_id' => $siswa_id, 'fTA_Ekskul' => $val_ta_ekskul, 'semester' => $semester]);
                
                // Tambahkan filter kategori jika kolom kategori ada
                if ($db->fieldExists('kategori', 'nilai_ekskul')) {
                    $builderEkskul->where('kategori', $kategori);
                }
                $ekskul = $builderEkskul->get()->getResultArray();
            }
        }
```

### B. Perbaikan Query Nilai Tahfidz:
Terapkan juga filter kategori pada query `nilai_tahfidz` agar datanya sinkron:
```php
            // Query di dalam blok if ($jenisRapor === 'tahfidz')
            $builderTahfidz = $db->table('nilai_tahfidz')
                ->where(['siswa_id' => $siswa_id, $fieldTahfidzTA => $val_ta_tahfidz, 'semester' => $semester]);
            if ($db->fieldExists('kategori', 'nilai_tahfidz')) {
                $builderTahfidz->where('kategori', $kategori);
            }
            $tahfidz = $builderTahfidz->get()->getRowArray();
```
Dan pada blok fallback else:
```php
            // Query di dalam blok else
            if ($db->tableExists('nilai_tahfidz')) {
                $fieldTahfidzTA = $db->fieldExists('tahun_ajaran_id', 'nilai_tahfidz') ? 'tahun_ajaran_id' : 'tahun_ajaran';
                $val_ta_tahfidz = ($fieldTahfidzTA === 'tahun_ajaran_id') ? $ta_id : $tahun_ajaran;
                $builderTahfidz = $db->table('nilai_tahfidz')
                    ->where(['siswa_id' => $siswa_id, $fieldTahfidzTA => $val_ta_tahfidz, 'semester' => $semester]);
                if ($db->fieldExists('kategori', 'nilai_tahfidz')) {
                    $builderTahfidz->where('kategori', $kategori);
                }
                $tahfidz = $builderTahfidz->get()->getRowArray();
            }
```

---

## 6. Hasil Implementasi dan Verifikasi

### Perbaikan yang Dilakukan:
1. **File yang Dimodifikasi:** [PreviewRaporController.php](file:///d:/xampp/htdocs/erapoteasy/backend/app/Controllers/WaliKelas/PreviewRaporController.php) (method `printPDF()`).
2. **Perubahan pada Nilai Ekstrakurikuler:**
   - Menambahkan pemeriksaan keberadaan kolom `kategori` pada tabel `nilai_ekskul` menggunakan `$db->fieldExists('kategori', 'nilai_ekskul')`.
   - Mengintegrasikan filter `->where('kategori', $kategori)` ke query builder `nilai_ekskul` (baik query utama yang terhubung ke `master_ekskul` maupun query fallback).
3. **Perubahan pada Nilai Tahfidz (Pencegahan Bug / Sinkronisasi):**
   - Mengintegrasikan filter `->where('kategori', $kategori)` pada query `nilai_tahfidz` agar hanya mengambil nilai sesuai kategori rapor yang sedang dicetak.
   - Menyelaraskan penggunaan variabel resolusi tahun ajaran (`$val_ta_tahfidz`) di blok `else` agar data tahun ajaran ter-resolve dengan benar (ID jika tipenya integer, teks jika varchar).

### Verifikasi Hasil:
* Setelah perbaikan ini diterapkan, cetak rapor dari dashboard Wali Kelas untuk kategori **Akhir Semester** hanya akan menarik data nilai ekstrakurikuler yang bertanda kategori `Akhir Semester`.
* Masalah duplikasi baris Kegiatan Ekstrakurikuler pada tampilan cetak rapor PDF kini telah teratasi sepenuhnya.
