# PANDUAN PENGGUNAAN CLI TOOL: SINKRONISASI SEMUA NILAI RAPOR

- **Aplikasi:** eRapor SMP IT Addurrah  
- **Skrip Utama:** [`sync_semua_nilai.php`](file:///d:/laragon/htdocs/erapoteasy/sync_semua_nilai.php)  
- **CodeIgniter Spark Command:** [`backend/app/Commands/SyncNilai.php`](file:///d:/laragon/htdocs/erapoteasy/backend/app/Commands/SyncNilai.php)  
- **Tanggal Rilis:** 28 Oktober 2026  

---

## 1. LATAR BELAKANG & TUJUAN

Pada tampilan web Dashboard Wali Kelas (menu *Progres Nilai*), terdapat tombol **"Sinkronkan Semua Nilai"**. Namun, tombol tersebut memiliki keterbatasan:
1. **Harus login satu per satu** ke akun setiap wali kelas.
2. **Hanya memproses 1 rombel** milik wali kelas yang sedang login.
3. Berisiko *timeout* di browser jika rombel dan jumlah mata pelajaran sangat banyak.

CLI Tool ini dibuat untuk memungkinkan administrator atau teknisi sekolah menjalankan proses **kalkulasi dan sinkronisasi seluruh nilai rapor untuk semua rombel di sekolah sekaligus** melalui baris perintah (*terminal / command line*) secara otomatis, cepat, dan aman.

---

## 2. CARA PENGGUNAAN

### Opsi 1: Menggunakan Script Standalone (Rekomendasi & Tercepat)

Buka terminal PowerShell atau CMD di root project (`d:\laragon\htdocs\erapoteasy`), lalu jalankan:

```powershell
# 1. Sinkronkan seluruh kelas untuk Tengah Semester (STS) - Default:
php sync_semua_nilai.php

# 2. Sinkronkan seluruh kelas untuk Akhir Semester (SAS / PAS):
php sync_semua_nilai.php --kategori="Akhir Semester"

# 3. Sinkronkan 1 kelas spesifik saja (berdasarkan ID Rombel, misal ID 77 = VII Amazonite):
php sync_semua_nilai.php --rombel=77

# 4. Sinkronkan 1 mata pelajaran spesifik saja (misal ID Mapel 5):
php sync_semua_nilai.php --mapel=5

# 5. Kombinasi kelas dan kategori:
php sync_semua_nilai.php --rombel=72 --kategori="Tengah Semester"
```

---

### Opsi 2: Menggunakan CodeIgniter Spark

Bagi yang terbiasa menggunakan perintah `spark` CodeIgniter 4:

```powershell
# Dari direktori root:
php backend/spark sync:nilai

# Atau dari dalam folder backend:
cd backend
php spark sync:nilai
php spark sync:nilai --kategori="Akhir Semester"
php spark sync:nilai --rombel=77
```

---

## 3. LOGIKA KALKULASI & INTEGRITAS DATA

CLI tool ini menjalankan logika kalkulasi yang **identik 100%** dengan tombol web wali kelas ([`ProgresNilaiController::syncSemuaMapel`](file:///d:/laragon/htdocs/erapoteasy/backend/app/Controllers/WaliKelas/ProgresNilaiController.php#L238)) dan guru mapel:

1. **Deteksi Rombel & Siswa:**
   - Menelusuri seluruh rombel aktif di Tahun Ajaran berjalan (2026/2027 Ganjil).
   - Mengambil siswa aktif baik melalui kolom `siswa.rombel_id` maupun tabel `anggota_rombel`.

2. **Daftar Mata Pelajaran (3 Lapis Fallback):**
   - Lapis 1: Berdasarkan kurikulum rombel di tabel `mata_pelajaran`.
   - Lapis 2: Berdasarkan jadwal kelas di tabel `jadwal_pelajaran`.
   - Lapis 3: Berdasarkan nilai riil siswa yang sudah terinput di `nilai_sumatif`.

3. **Bobot & Pembagi Dinamis:**
   - Membaca konfigurasi bobot resmi dari tabel `setting_bobot_nilai`:
     - **Tengah Semester:** NH (35%), UH (35%), STS (30%).
     - **Akhir Semester:** NH (35%), UH (35%), STS (15%), SAS (15%).
   - Menghitung pembagi pertemuan dinamis (*fair divider*) agar siswa tidak dirugikan jika guru baru mengisi sebagian pertemuan.

4. **Predikat & Deskripsi Capaian LM:**
   - Menentukan predikat (A, B, C, D) berdasarkan tabel `setting_aturan_nilai`.
   - Menentukan deskripsi tertinggi dan terendah berdasarkan materi di `master_lm`.

5. **Proteksi Database Transaksional (ACID):**
   - Setiap rombel diproses dalam satu blok transaksi database (`START TRANSACTION` & `COMMIT`). Jika terjadi kendala pada satu mata pelajaran, perubahan di rombel tersebut otomatis di-*rollback* sehingga tidak ada data nilai yang korup atau setengah tersimpan.

---

## 4. CONTOH OUTPUT TERMINAL

```text
==============================================================================
      eRapor SMP IT Ad-Durrah -- CLI Tool Sinkronisasi Semua Nilai           
==============================================================================
  Tahun Ajaran : 2026/2027 (Ganjil) [ID: 10]
  Kategori     : Tengah Semester
  Bobot STS    : NH = 35%, UH = 35%, STS = 30%
==============================================================================

[ 1/15] Memproses Kelas: VII Amazonite          (Wali: Adhitya Prayoga, S.Pd, Gr)...
     [OK] 26 Siswa | 11 Mapel | 286 Rekor Nilai Rapor berhasil disinkronkan.

[ 2/15] Memproses Kelas: VII Carnelian          (Wali: Tika Yeardila, M.Pd)...
     [OK] 33 Siswa | 11 Mapel | 363 Rekor Nilai Rapor berhasil disinkronkan.

...

==============================================================================
                         SINKRONISASI SELESAI                                
==============================================================================
  Total Rombel Diproses   : 15 dari 15 kelas
  Total Siswa Terdampak   : 425 siswa
  Total Rekor Nilai Rapor : 4.675 nilai ter-update/insert
  Waktu Eksekusi          : 4.82 detik
==============================================================================
```

