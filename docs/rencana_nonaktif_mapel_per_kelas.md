# Rencana Fitur Nonaktifkan Mata Pelajaran Per Kelas (Rombel)

## 1. Latar Belakang
Sebelumnya, sistem hanya mendukung penonaktifkan mata pelajaran secara global. Namun, sekolah membutuhkan fleksibilitas agar suatu mata pelajaran bisa dinonaktifkan di kelas tertentu saja (misalnya kelas A saja), sedangkan di kelas lainnya tetap aktif dan muncul di rapor. Pilihan kelas yang dinonaktifkan harus mendukung multi-select (lebih dari satu kelas).

## 2. Desain Database
Untuk mendukung relasi one-to-many (satu mapel bisa dinonaktifkan di banyak kelas), dibuat tabel baru bernama `mapel_nonaktif_rombel`.

### Migration File: `App\Database\Migrations\2026-06-01-000001_MapelNonaktifRombel.php`
```php
<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MapelNonaktifRombel extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'mapel_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'rombel_id' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'null'    => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('mapel_id');
        $this->forge->addKey('rombel_id');
        $this->forge->createTable('mapel_nonaktif_rombel');
    }

    public function down()
    {
        $this->forge->dropTable('mapel_nonaktif_rombel');
    }
}
```

### Opsi Alternatif: Migrasi Manual (Untuk VPS/Staging jika Spark Migrate bermasalah)

Jika `php spark migrate` mengalami kegagalan/kendala di server VPS, gunakan salah satu metode alternatif berikut untuk membuat tabel database:

#### Opsi A: Eksekusi SQL Mentah
Jalankan query SQL berikut langsung di database manager VPS Anda (seperti phpMyAdmin, DBeaver, dll.):
```sql
CREATE TABLE IF NOT EXISTS `mapel_nonaktif_rombel` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `mapel_id` INT NOT NULL,
    `rombel_id` INT NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    KEY `mapel_id` (`mapel_id`),
    KEY `rombel_id` (`rombel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```

#### Opsi B: Skrip PHP Pembantu (Otomatis membaca `.env`)
Jalankan perintah berikut pada terminal VPS Anda di dalam folder `backend/`:
```bash
php migrate_db_nonaktif.php
```
*Skrip [migrate_db_nonaktif.php](file:///d:/xampp/htdocs/erapoteasy/backend/migrate_db_nonaktif.php) ini akan mem-parsing file `.env` setempat untuk memuat kredensial database dan membuat tabel via PDO.*

## 3. Perubahan Backend (PHP/CodeIgniter)

### MataPelajaranController.php
1. **`index()`**:
   - Ambil daftar Rombel aktif di Tahun Ajaran saat ini dan kirim ke view sebagai `$rombelList`.
   - Lakukan query ke `mapel_nonaktif_rombel` digabung dengan `rombel` untuk memetakan kelas-kelas mana saja yang menonaktifkan masing-masing mata pelajaran. Masukkan detail ini ke array `formattedMapel` sebagai `deactivated_rombel`.
2. **`update($id)`**:
   - Tangkap input array `deactivated_rombels` (ID Rombel yang dicentang untuk dinonaktifkan).
   - Hapus semua catatan penonaktifkan lama untuk `mapel_id` tersebut di `mapel_nonaktif_rombel`.
   - Masukkan catatan baru untuk setiap ID Rombel yang dipilih ke tabel `mapel_nonaktif_rombel`.

### Penyaringan Rapor PDF
Pada cetak rapor (Admin, Wali Kelas, dan Orang Tua), saring mata pelajaran berdasarkan status nonaktif per kelas:
- **`PreviewRaporController::printPDF`**
- **`CetakRaporController::printPDF`**
- **`AkademikController::generateRaporPDF`**

Sebelum menyaring mata pelajaran, lakukan query ke `mapel_nonaktif_rombel`:
```php
$deactivatedMapelIds = $db->table('mapel_nonaktif_rombel')
    ->where('rombel_id', $rombelId)
    ->findColumn('mapel_id') ?? [];
```
Di dalam loop penyaringan `$jadwal_mapel`, lewati mata pelajaran jika ID-nya ada di `$deactivatedMapelIds`:
```php
if (in_array($m['id'], $deactivatedMapelIds)) {
    continue;
}
```

## 4. Perubahan User Interface (UI)

### views/admin/mata-pelajaran.php
- Di dalam tag `<script>` bagian bawah, tambahkan variabel rombel global:
  ```html
  const dbRombelList = <?= !empty($rombelList) ? json_encode($rombelList) : '[]' ?>;
  ```
- Di dalam `editMapelModal`, tambahkan area checkbox kelas di bawah dropdown Status:
  ```html
  <div id="edit_rombel_deactivate_section" class="col-span-2 mt-2 hidden">
      <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">Nonaktifkan di Kelas:</label>
      <div id="edit_rombel_deactivate_container" class="grid grid-cols-2 sm:grid-cols-3 gap-3 bg-gray-50 dark:bg-slate-900/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700">
          <!-- Checkbox rombel dirender secara dinamis oleh JS -->
      </div>
  </div>
  ```

### public/assets/js/Admin/mata-pelajaran.js
- Inisialisasi daftar rombel:
  ```javascript
  window.rombelList = typeof dbRombelList !== 'undefined' ? dbRombelList : [];
  ```
- Di dalam `showEditMapelModal(id)`:
  - Render checkbox rombel secara dinamis ke `#edit_rombel_deactivate_container` dan beri tanda `checked` pada kelas yang sebelumnya sudah dinonaktifkan untuk mapel tersebut.
  - Tambahkan event listener pada select `#edit_status`: jika nilainya `'Aktif'`, tampilkan area `#edit_rombel_deactivate_section`. Jika `'Non-aktif'` (nonaktif global), sembunyikan area tersebut karena seluruh kelas otomatis tidak akan mendapatkan mapel ini.
- Di dalam `populateMapel()` (tabel utama):
  - Jika mata pelajaran aktif secara global namun dinonaktifkan di beberapa kelas, ubah badge status menjadi warna kuning/oranye bertuliskan **Aktif Sebagian**.
  - Tampilkan teks kecil merah di bawah badge status bertuliskan: `Nonaktif di: VII-A, VII-B` agar pengguna langsung tahu pembatasan kelasnya.
