# Analisa Proses Penilaian dan Pencetakan Rapor: Admin vs Wali Kelas

Dokumen ini menganalisa proses pengolahan nilai dan pencetakan rapor pada sistem E-Rapor Easy, membandingkan alur kerja antara dashboard Admin dan Wali Kelas.

## 1. Perbedaan Dashboard Penilaian & Monitoring

### Dashboard Wali Kelas (`ProgresNilaiController`)
- **Cakupan Terbatas**: Wali Kelas hanya dapat melihat data siswa dan nilai pada kelas (rombel) yang ia ampu.
- **Visualisasi Detail**: Fokus pada persebaran nilai per siswa dalam satu kelas untuk mendeteksi siswa yang "Aman", "Rawan", atau "Kritis".
- **Sumber Data**: Menghitung progres berdasarkan keberadaan data di tabel `nilai_sumatif`.

### Dashboard Admin (`MonitoringNilaiSiswaController`)
- **Cakupan Global**: Admin dapat memantau seluruh kelas, mata pelajaran, dan guru dalam satu dashboard.
- **Filter Fleksibel**: Memiliki filter Tahun Ajaran, Kelas, dan Mata Pelajaran yang lebih luas.
- **Statistik Sekolah**: Memberikan gambaran rata-rata progres pengisian nilai di tingkat sekolah.

---

## 2. Perbedaan Proses Pencetakan Rapor

Secara fungsional, keduanya memiliki fitur cetak yang hampir identik, namun terdapat perbedaan teknis yang signifikan:

| Fitur | Wali Kelas (`PreviewRaporController`) | Admin (`CetakRaporController`) |
|-------|-----------------------------------|----------------------------|
| **Akses Siswa** | Terbatas pada anggota rombel yang diampu. | Seluruh siswa di sekolah. |
| **Tanda Tangan** | Fokus pada upload TTD Wali Kelas sendiri. | Fokus pada upload TTD Kepala Sekolah. |
| **Jenis Rapor** | Mendukung Rapor Lengkap, Akademik, Karakter, dan Tahfidz. | Sama dengan Wali Kelas. |
| **Logic Code** | Menggunakan logika `printPDF` dan `getDeskripsiDinamis` yang disalin secara lokal. | Menggunakan logika serupa namun berada di file controller yang berbeda. |

---

## 3. Potensi Perbedaan Nilai pada Mapel & Siswa yang Sama

Meskipun keduanya mengambil data dari database yang sama, terdapat **kemungkinan perbedaan hasil** yang disebabkan oleh faktor-faktor berikut:

### A. Duplikasi Logika Kode (Redundansi)
Logika untuk menghitung deskripsi (`getDeskripsiDinamis`) dan mengurutkan mata pelajaran didefinisikan secara terpisah di masing-masing controller (`Admin` dan `WaliKelas`).
- **Risiko**: Jika ada perubahan rumus perhitungan atau logika filter (misal: pengecualian mapel Tahfidz) di satu file namun lupa diperbarui di file lainnya, maka hasil cetak Admin dan Wali Kelas akan berbeda.

### B. Logic Fallback Mata Pelajaran
Sistem menggunakan logika "Triple Fallback" untuk menentukan daftar mata pelajaran:
1. Mencari di `jadwal_pelajaran`.
2. Mencari di `guru_mapel`.
3. Mencari di `mata_pelajaran` secara umum.
Jika data di tabel-tabel ini tidak sinkron (misal: di `jadwal_pelajaran` ada tapi di `guru_mapel` tidak), dan salah satu controller memiliki bug kecil dalam urutan join-nya, daftar mapel yang muncul bisa berbeda.

### C. Penanganan Parameter Tahun Ajaran (TA)
- Admin seringkali menggunakan ID Tahun Ajaran (`id`) sebagai filter utama.
- Wali Kelas terkadang menggunakan string Tahun Ajaran (`tahun`) dari session.
Ketidaksesuaian cara query (misal: filter `id_tahun_ajaran` vs `tahun_ajaran`) dapat menyebabkan data yang ditarik tidak sinkron jika mapping di database memiliki inkonsistensi.

### D. Perbedaan Cache atau Timing
Jika guru mata pelajaran melakukan update nilai sesaat setelah Wali Kelas melakukan preview, namun sesaat sebelum Admin mencetak, maka nilai yang tercetak akan berbeda. Hal ini krusial jika sistem tidak menggunakan mekanisme "Locking" (Validasi) yang ketat.

---

## Rekomendasi
Untuk menjamin 100% konsistensi:
1. **Sentralisasi Logika**: Pindahkan fungsi `printPDF` dan `getDeskripsiDinamis` ke dalam sebuah **Service** atau **Library** agar Admin dan Wali Kelas memanggil fungsi yang benar-benar sama.
2. **Sinkronisasi Parameter**: Pastikan semua controller menggunakan parameter yang seragam (disarankan selalu menggunakan `id` untuk foreign key).
3. **Mekanisme Locking**: Pastikan Admin melakukan "Validasi/Kunci Nilai" sebelum cetak massal dilakukan agar tidak ada perubahan data di tengah proses pencetakan.

---
*Dibuat oleh: Antigravity AI*
