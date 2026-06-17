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

Sesuai dengan kebutuhan klien:
- **Tabel tanda tangan (nama wali kelas, nama kepala sekolah, tanggal cetak, dan garis pembatas) harus tetap dicetak**.
- **Hanya gambar (image) tanda tangan digital Wali Kelas saja** yang dikosongkan (tidak dicetak) ketika opsi "Tampilkan Tanda Tangan" dinonaktifkan.
- **Tanda tangan Kepala Sekolah** tetap dicetak seperti biasa (tidak disembunyikan oleh checkbox tersebut).

Oleh karena itu, perbaikan dilakukan pada level **Controller** dan **View**:

### A. Perbaikan pada Controller
Agar parameter `ttd` (khusus Wali Kelas) dan `qr` (khusus Barcode Kepala Sekolah) di-handle dengan benar (bernilai `true` secara default jika tidak dikirim, tetapi bernilai `false` jika di-uncheck atau bernilai `0`):
1. **Wali Kelas**: [[PreviewRaporController.php](file:///d:/xampp/htdocs/erapoteasy/backend/app/Controllers/WaliKelas/PreviewRaporController.php#L181)]
2. **Admin**: [[CetakRaporController.php](file:///d:/xampp/htdocs/erapoteasy/backend/app/Controllers/Admin/CetakRaporController.php#L295)]

**Perubahan Kode:**
```php
$optTtd     = $this->request->getGet('ttd') !== '0';
$optQr      = $this->request->getGet('qr') !== '0';
```

### B. Perbaikan pada View Template
Modifikasi dilakukan pada 3 file template rapor:
1. [rapor_lengkap.php](file:///d:/xampp/htdocs/erapoteasy/backend/app/Views/admin/print/rapor_lengkap.php#L506)
2. [rapor_akademik.php](file:///d:/xampp/htdocs/erapoteasy/backend/app/Views/admin/print/rapor_akademik.php#L337)
3. [rapor_karakter.php](file:///d:/xampp/htdocs/erapoteasy/backend/app/Views/admin/print/rapor_karakter.php#L364)

**Perubahan Struktur View:**
- Wrapper `<?php if (!empty($opt_ttd)): ?>` yang sebelumnya membungkus seluruh tabel `.ttd-container` **dihapus**, sehingga struktur tanda tangan selalu dicetak.
- Tag `<img>` untuk **tanda tangan digital Wali Kelas** dibungkus dengan pengecekan `$opt_ttd` (hanya dirender jika opsi aktif):
  ```php
  <div style="height: 60px;" align="center">
      <?php if (!empty($opt_ttd) && !empty($siswa['wali_ttd']) && file_exists(FCPATH . 'assets/uploads/ttd/' . $siswa['wali_ttd'])): ?>
          <img src="<?= FCPATH . 'assets/uploads/ttd/' . $siswa['wali_ttd'] ?>" style="height: 60px;">
      <?php else: ?>
          <br><br><br>
      <?php endif; ?>
  </div>
  ```
- Tag `<img>` untuk **tanda tangan digital Kepala Sekolah** tetap dirender seperti semula tanpa pengecekan `$opt_ttd` (mencetak gambar tanda tangan secara unconditional selama berkas fisiknya ada):
  ```php
  <div style="height: 70px;" align="center">
      <?php if (!empty($kepsek['ttd_digital']) && file_exists(FCPATH . 'assets/uploads/ttd/' . $kepsek['ttd_digital'])): ?>
          <img src="<?= FCPATH . 'assets/uploads/ttd/' . $kepsek['ttd_digital'] ?>" style="height: 70px;">
      <?php else: ?>
          <br><br><br>
      <?php endif; ?>
  </div>
  ```
- Kolom QR Code dibungkus dengan pengecekan `$opt_qr` (hanya dirender jika opsi aktif):
  ```php
  <td style="width: 30%; vertical-align: middle;">
      <?php if (!empty($opt_qr)): ?>
          <div style="text-align: center; margin-top: 5px;">
              <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?= urlencode($link_verifikasi) ?>" style="width: 80px; height: 80px;">
              <br>
              <span style="font-size: 7pt; color: #666; font-style: italic;">Scan untuk Validasi</span>
          </div>
      <?php endif; ?>
  </td>
  ```

---

## 4. Hasil Implementasi Perbaikan

Perbaikan telah berhasil diimplementasikan di seluruh controller dan view terkait pada tanggal **17 Juni 2026**.

### Hasil Pengujian & Verifikasi:
- **Opsi "Tampilkan Tanda Tangan" Dinonaktifkan (`ttd=0`)**:
  * Dokumen PDF tetap menampilkan nama wali kelas, kepala sekolah, dan tanggal cetak (misal: *Kota Medan, 17 Juni 2026*).
  * Gambar tanda tangan digital **Wali Kelas kosong / tidak dicetak** (hanya berupa ruang kosong `height` 60px).
  * Gambar tanda tangan digital **Kepala Sekolah tetap tercetak** seperti biasa (selama gambarnya telah diunggah di sistem).
- **Opsi "Tampilkan Tanda Tangan" Aktif (`ttd=1`)**:
  * Gambar tanda tangan digital Wali Kelas dan Kepala Sekolah **tercetak dengan normal**.
- **Opsi "Tampilkan Barcode Validasi" Dinonaktifkan (`qr=0`)**:
  * Kolom tengah (QR Code) **kosong/bersih** tanpa barcode, menjaga tata letak tetap simetris.
- **Opsi "Tampilkan Barcode Validasi" Aktif (`qr=1`)**:
  * QR Code Validasi **tercetak dengan normal** di bagian tengah tanda tangan.
