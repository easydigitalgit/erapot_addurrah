# Analisis Bug: Tanda Tangan Tetap Terprint Meskipun Opsi Dinonaktifkan

Dokumen ini menjelaskan hasil analisis mengenai masalah pada Cetak Rapor di dashboard Wali Kelas (dan dashboard Admin) di mana tanda tangan digital tetap tercetak pada file PDF rapor meskipun opsi **"Tampilkan Tanda Tangan"** telah di-uncheck (dinonaktifkan).

---

## 1. Deskripsi Masalah
Pada halaman pratinjau rapor wali kelas (`/wali/preview-rapor`), terdapat bagian **Pilihan Tambahan** yang memungkinkan wali kelas memilih elemen-elemen yang akan ditampilkan pada dokumen PDF rapor, salah satunya adalah checkbox **Tampilkan Tanda Tangan** (`#checkTTD`).

Ketika wali kelas **menghilangkan centang (uncheck)** pada pilihan tersebut, data parameter `ttd=0` dikirim melalui URL ke backend. Namun, saat dokumen PDF rapor di-generate, tabel dan gambar tanda tangan digital Wali Kelas serta Kepala Sekolah **tetap muncul (tercetak)** pada dokumen PDF hasil unduhan maupun pratinjau.

---

## 2. Analisis Penyebab Masalah (Root Cause)

### A. Aliran Data dari Frontend ke Backend
Berdasarkan analisis file Javascript frontend [[preview-rapor.js](file:///d:/xampp/htdocs/erapoteasy/backend/public/assets/js/WaliKelas/preview-rapor.js#L268-L301)]:
```javascript
const optTtd = document.getElementById('checkTTD').checked ? 1 : 0;
...
const pdfUrl = `${API_URL}/printPDF/${siswaId}/${actionType}?jenis_rapor=${jenisRapor}&cover=${optCover}&ttd=${optTtd}&qr=${optQr}&ta=${taId}&tgl_rapor=${tglRapor}&tempat=${tempat}&kategori=${kategori}`;
```
Ketika checkbox di-uncheck, `optTtd` bernilai `0` dan URL request yang dikirimkan ke server menyertakan parameter `&ttd=0`.

---

### B. Evaluasi Kondisional di Backend (Controller)
Masalah utama terletak pada penanganan parameter `ttd` di dalam controller backend. 

1. **Dashboard Wali Kelas**: [[PreviewRaporController.php](file:///d:/xampp/htdocs/erapoteasy/backend/app/Controllers/WaliKelas/PreviewRaporController.php#L180-L182)]
2. **Dashboard Admin**: [[CetakRaporController.php](file:///d:/xampp/htdocs/erapoteasy/backend/app/Controllers/Admin/CetakRaporController.php#L293-L296)]

Di kedua file controller tersebut, variabel `$optTtd` dideklarasikan seperti berikut:
```php
$jenisRapor = $this->request->getGet('jenis_rapor') ?? 'lengkap';
$optCover   = $this->request->getGet('cover') === '1';
$optTtd     = $this->request->getGet('ttd') === '1' || true; // <-- PENYEBAB UTAMA
$optQr      = $this->request->getGet('qr') === '1';
```

#### Detail Kesalahan Logika:
Ekspresi `$this->request->getGet('ttd') === '1' || true` menggunakan operator logika OR (`||`) dengan nilai literal `true`. Berdasarkan aturan evaluasi logika PHP:
- `(Apapun Kondisinya) || true` akan **selalu menghasilkan nilai `true`**.
- Walaupun parameter `ttd` bernilai `'0'`, hasil evaluasi tetap dipaksa menjadi `true`.

Akibatnya, variabel `$optTtd` selalu bernilai `true` saat di-pass ke view rapor.

---

### C. Efek pada View Template Rapor
Di dalam view template cetak rapor (seperti [[rapor_lengkap.php](file:///d:/xampp/htdocs/erapoteasy/backend/app/Views/admin/print/rapor_lengkap.php#L506)], [[rapor_akademik.php](file:///d:/xampp/htdocs/erapoteasy/backend/app/Views/admin/print/rapor_akademik.php#L337)], dan [[rapor_karakter.php](file:///d:/xampp/htdocs/erapoteasy/backend/app/Views/admin/print/rapor_karakter.php#L364)]), pengecekan dilakukan menggunakan variabel `opt_ttd`:

```php
<?php if (!empty($opt_ttd)): ?>
    <table style="width: 100%; margin-top: 15px; border: none; font-size: 10pt; table-layout: fixed; page-break-inside: avoid;">
        <!-- Menampilkan data tanda tangan orang tua, QR Code, dan tanda tangan digital wali kelas -->
    </table>
<?php endif; ?>
```
Karena data `'opt_ttd' => $optTtd` yang dikirim dari controller selalu bernilai `true`, kondisi `!empty($opt_ttd)` selalu bernilai benar. Akibatnya, seluruh tabel tanda tangan beserta gambar tanda tangan digital (`img` tanda tangan) selalu dirender dan dicetak.

---

## 3. Solusi Perbaikan

Untuk memperbaiki bug ini, penentuan nilai `$optTtd` di controller harus mengecek apakah parameter `ttd` secara eksplisit bernilai `'0'` (di-uncheck) atau tidak. Jika parameter `ttd` tidak dikirim (default), maka tanda tangan tetap ditampilkan (`true`).

### Rekomendasi Kode Baru:
Ubah baris penentuan `$optTtd` pada controller menjadi:
```php
$optTtd = $this->request->getGet('ttd') !== '0';
```

### Analisis Logika Kode Baru:
- **Checkbox Dicentang (`ttd=1`)**: `getGet('ttd') !== '0'` bernilai `true` (Tanda tangan ditampilkan).
- **Checkbox Tidak Dicentang (`ttd=0`)**: `getGet('ttd') !== '0'` bernilai `false` (Tanda tangan disembunyikan/tidak dicetak).
- **Parameter Tidak Dikirim (`ttd=null`)**: `getGet('ttd') !== '0'` bernilai `true` (Default tampil, aman untuk integrasi route lain).

### Lokasi File yang Perlu Diperbaiki:
1. **Wali Kelas**: [PreviewRaporController.php](file:///d:/xampp/htdocs/erapoteasy/backend/app/Controllers/WaliKelas/PreviewRaporController.php#L181)
2. **Admin**: [CetakRaporController.php](file:///d:/xampp/htdocs/erapoteasy/backend/app/Controllers/Admin/CetakRaporController.php#L295)

---

## 4. Hasil Implementasi Perbaikan

Perbaikan telah berhasil diimplementasikan di kedua file controller pada tanggal **17 Juni 2026**:

1. **Wali Kelas**: Di [[PreviewRaporController.php](file:///d:/xampp/htdocs/erapoteasy/backend/app/Controllers/WaliKelas/PreviewRaporController.php#L181)], baris kode:
   ```diff
   - $optTtd     = $this->request->getGet('ttd') === '1' || true;
   + $optTtd     = $this->request->getGet('ttd') !== '0';
   ```
2. **Admin**: Di [[CetakRaporController.php](file:///d:/xampp/htdocs/erapoteasy/backend/app/Controllers/Admin/CetakRaporController.php#L295)], baris kode:
   ```diff
   - $optTtd     = $this->request->getGet('ttd') === '1' || true;
   + $optTtd     = $this->request->getGet('ttd') !== '0';
   ```

### Hasil Pengujian & Verifikasi:
- **Checkbox Dinonaktifkan (`ttd=0`)**: Parameter `ttd=0` dikirim ke server. Evaluasi `$optTtd = $this->request->getGet('ttd') !== '0';` bernilai `false`. Seluruh tabel tanda tangan beserta tanda tangan digital disembunyikan/tidak dicetak dari PDF rapor.
- **Checkbox Aktif (`ttd=1`)**: Parameter `ttd=1` dikirim ke server. Evaluasi `$optTtd` bernilai `true`. Tabel tanda tangan dicetak normal.
- **Parameter Kosong (`ttd=null`)**: Evaluasi `$optTtd` bernilai `true`. Tabel tanda tangan dicetak normal sebagai perilaku default yang aman.
