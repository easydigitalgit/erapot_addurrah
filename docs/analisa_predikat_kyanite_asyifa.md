# Analisa Komprehensif: Status "Perlu Bimbingan" pada Nilai 89.1

## Kasus
- **Nama Siswa**: Asyifa Naurah Feirus
- **Kelas**: Kyanite
- **Mata Pelajaran**: Matematika
- **Nilai Akhir Rapor**: 89.1
- **Status yang Muncul**: PERLU BIMBINGAN (Padahal siswa lain dengan nilai lebih rendah seperti 78.1 berstatus BAIK).

---

## Temuan Teknis

Berdasarkan hasil investigasi pada database dan logika kode di `NilaiRaporController.php`, ditemukan penyebab utama masalah ini.

### 1. Logika Penentuan Predikat di Kode (NilaiRaporController.php)
Sistem menggunakan tabel `setting_aturan_nilai` untuk menentukan status/predikat. Kode melakukan perulangan untuk mengecek apakah nilai siswa masuk ke dalam rentang `nilai_min` dan `nilai_max` yang ada di database.

```php
foreach ($aturanPredikat as $aturan) {
    if ($nilai_akhir >= $aturan['nilai_min'] && $nilai_akhir <= $aturan['nilai_max']) {
        $predikat = $aturan['deskripsi_predikat']; 
        break;
    }
}
// Jika tidak ada rentang yang cocok, sistem memberikan fallback:
if ($predikat === '-') {
    $predikat = 'Perlu Bimbingan';
}
```

### 2. Data di Tabel `setting_aturan_nilai`
Berikut adalah data aturan nilai yang saat ini tersimpan di database:

| Predikat | Deskripsi | Nilai Min | Nilai Max |
| :--- | :--- | :--- | :--- |
| **A** | Sangat Baik | 90 | 99 |
| **B** | Baik | 75 | **89** |
| **C** | Kurang | 66 | 84 |
| **D** | Sangat Kurang | 0 | 74 |

### 3. Analisis Nilai Asyifa (89.1)
Nilai Asyifa adalah **89.1**. Mari kita simulasikan pengecekan sistem:
1. Apakah 89.1 berada di antara **90** dan **99**? **TIDAK** (Terlalu rendah untuk A).
2. Apakah 89.1 berada di antara **75** dan **89**? **TIDAK** (89.1 lebih besar dari 89).
3. Apakah 89.1 berada di antara **66** dan **84**? **TIDAK**.
4. Apakah 89.1 berada di antara **0** dan **74**? **TIDAK**.

Karena nilai **89.1** tidak masuk ke dalam rentang manapun yang didefinisikan di database (ada celah/gap antara 89 dan 90), maka sistem memberikan predikat default yaitu **"Perlu Bimbingan"**.

---

## Kenapa Siswa Lain Berstatus "Baik"?
Siswa lain memiliki nilai yang pas masuk dalam rentang **75 - 89**:
- **Afika Harumi (82.0)**: Masuk rentang 75-89 (BAIK).
- **Afiqah Azzahra (80.4)**: Masuk rentang 75-89 (BAIK).
- **Assyfa Rahima (78.1)**: Masuk rentang 75-89 (BAIK).

Sedangkan Asyifa "terhukum" karena nilainya terlalu bagus (melewati 89) tapi belum mencapai 90.

---

## Masalah Tambahan: Overlap (Tumpang Tindih)
Ditemukan juga masalah tumpang tindih pada rentang nilai:
- Predikat **B (Baik)**: 75 - 89
- Predikat **C (Kurang)**: 66 - 84
Siswa dengan nilai **75 sampai 84** secara teknis masuk ke kedua kategori. Namun karena sistem mengecek dari nilai tertinggi dulu (89), maka mereka mendapatkan "BAIK".

---

## Rekomendasi Perbaikan

Untuk memperbaiki masalah ini, pengaturan di menu **Setting Aturan Nilai** (atau tabel `setting_aturan_nilai`) perlu diperbarui agar tidak ada celah di antara angka. 

### Opsi Perbaikan 1 (Update Database):
Ubah `nilai_max` untuk predikat B menjadi **89.9** atau ubah `nilai_min` predikat A menjadi **89.1**.
*Sangat disarankan untuk menggunakan angka desimal atau memastikan angka Max di satu level bertemu dengan angka Min di level atasnya.*

### Opsi Perbaikan 2 (Update Logika Kode):
Mengubah pengecekan agar tidak menggunakan `BETWEEN` yang kaku, melainkan menggunakan batas bawah saja secara berurutan.

---

## Solusi yang Diimplementasikan

Untuk mengatasi masalah celah (gap) nilai desimal ini tanpa harus mengubah data di seluruh database, telah dilakukan perubahan logika pada sistem penentuan predikat di `NilaiRaporController.php`.

### Perubahan Logika (Rounding Down)
Sistem sekarang melakukan **pembulatan ke bawah (floor)** pada nilai akhir sebelum dibandingkan dengan rentang nilai di database. 

**Contoh Simulasi Baru (Asyifa - 89.1):**
1. Nilai Akhir: **89.1**
2. Proses Internal: `floor(89.1)` = **89**
3. Pengecekan Database:
   - Apakah **89** masuk rentang 90-99 (A)? Tidak.
   - Apakah **89** masuk rentang 75-89 (B)? **YA!**
4. Hasil Akhir: Predikat berubah menjadi **BAIK**.

### Manfaat Solusi Ini:
1. **Mengakomodasi Nilai Desimal**: Nilai seperti 89.1 hingga 89.9 akan tetap dianggap sebagai bagian dari kategori "89" (Baik), sehingga tidak ada lagi siswa yang jatuh ke "celah kosong".
2. **Tanpa Mengubah Data DB**: Tidak perlu melakukan update massal pada tabel `setting_aturan_nilai`, sehingga pengaturan yang sudah ada tetap konsisten.
3. **Aman untuk Client**: Logika ini memastikan tidak ada siswa yang dirugikan (status turun) karena pembulatan selalu dilakukan ke bawah untuk menyesuaikan dengan batas atas kategori sebelumnya.

---
**Status Terakhir**: Masalah telah diperbaiki dan sudah diterapkan pada sistem.
