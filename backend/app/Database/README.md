# 📚 Panduan Migration & Seeding Rapor Digital

Dokumentasi ini berisi langkah-langkah untuk melakukan inisialisasi database dari nol menggunakan fitur Migration dan Seeder pada CodeIgniter 4.

## ⚙️ Persiapan (Hanya Sekali)
Pastikan kamu berada di direktori utama project (yang sejajar dengan file `spark`).
```bash
cd path/to/raporsmpit/backend
```

## 🚀 1. Mengeksekusi Migration (Membuat Tabel)
Perintah ini akan membaca semua file di folder `app/Database/Migrations/` dan merubahnya menjadi tabel fisik di database.

```bash
php spark migrate
```
> **Catatan:** Jika ingin mereset/menghapus semua tabel, gunakan perintah `php spark migrate:rollback`.

## 🌱 2. Mengeksekusi Seeder (Mengisi Data)
Perintah ini akan mengeksekusi file `DatabaseSeeder.php` yang bertugas sebagai "Master" untuk memanggil semua seeder (RefJuz, RefSurah, Propinsi, Kecamatan, dll) sekaligus.

```bash
php spark db:seed DatabaseSeeder
```

## 💡 Cara Menambah Data Wilayah Besar (Misal: Kecamatan)
Jika memiliki file `.sql` berukuran besar (ratusan/ribuan baris):
1. Hapus bagian `CREATE TABLE` pada file `.sql` tersebut.
2. Sisakan hanya bagian `INSERT INTO ...`
3. Simpan file SQL tersebut di dalam folder `app/Database/Seeds/`
4. Buat Seeder baru yang menggunakan `file_get_contents()` dan `$this->db->connID->exec()` untuk mengeksekusinya tanpa membebani RAM server.