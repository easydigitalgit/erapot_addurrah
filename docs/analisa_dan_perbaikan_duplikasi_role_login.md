# Analisa dan Perbaikan Duplikasi Role pada Login (Multi-Role Modal)

## 1. Deskripsi Masalah
Saat pengguna (khususnya guru yang juga bertugas sebagai wali kelas, contohnya akun Bu Anggana Ayu Marina / Wali Kelas IX Biduri) melakukan login ke aplikasi, modal **"Pilih Akses Masuk"** menampilkan pilihan peran yang berulang berkali-kali secara bergantian:
- **Guru Mapel** *(Masuk sebagai Guru Mapel)*
- **Wali Kelas Biduri** *(Masuk sebagai Wali Kelas Biduri)*
- **Guru Mapel** *(Masuk sebagai Guru Mapel)*
- **Wali Kelas Biduri** *(Masuk sebagai Wali Kelas Biduri)*
- **Guru Mapel** *(Masuk sebagai Guru Mapel)*
- **Wali Kelas Biduri** *(Masuk sebagai Wali Kelas Biduri)*

---

## 2. Akar Masalah (Root Cause Analysis)

### A. Bug Update Massal pada `GuruTendikController.php` (Akar Data Duplikat)
Pada fungsi update data guru ([`backend/app/Controllers/Admin/GuruTendikController.php`](file:///d:/laragon/htdocs/erapoteasy/backend/app/Controllers/Admin/GuruTendikController.php#L448)), terdapat kode:
```php
$db->table('user_roles')->where('user_id', $existing['user_id'])->update(['role_id' => $roleId]);
```
- Seorang guru bisa memiliki beberapa baris role di tabel `user_roles` (misalnya: Guru `role_id=2`, Wali Kelas `role_id=3`, Tahfidz `role_id=6`).
- Ketika data guru disimpan/diedit melalui panel Admin Guru & Tendik, perintah di atas **mengubah seluruh baris** hak akses milik `user_id` tersebut menjadi satu nilai `role_id` yang sama (misal `2`).
- Hal ini menyebabkan baris yang semula berbeda berubah menjadi **baris duplikat yang identik** (`user_id=120, role_id=2` sebanyak 3 baris).

### B. Kurangnya Pengecekan Keunikan di `LoginController.php` (Pemicu Tampilan Berulang)
Pada saat proses login berlangsung ([`backend/app/Controllers/Auth/LoginController.php`](file:///d:/laragon/htdocs/erapoteasy/backend/app/Controllers/Auth/LoginController.php#L205)):
1. `$userModel->getUserRoles($user['id'])` mengambil seluruh baris dari tabel `user_roles` tanpa klausa `DISTINCT`, sehingga menerima 3 baris `role_id = 2`.
2. Di method `mapRolesForLogin($userId, $dbRoles)`:
   ```php
   if ($rid == 2) {
       $roles[] = ['role_id' => 2, 'key' => 'guru', 'label' => 'Guru Mapel', ...];

       // Cek apakah guru adalah wali kelas
       if ($rombel) {
           $roles[] = ['role_id' => 3, 'key' => 'wali_kelas', 'label' => 'Wali Kelas ' . $rombel['nama_rombel'], ...];
           $hasWaliKelasRole = true;
       }
   }
   ```
   Di dalam blok `$rid == 2`, tidak ada validasi apakah role Wali Kelas sudah pernah dimasukkan (`!$hasWaliKelasRole`).
3. Akibatnya, pada setiap iterasi baris `role_id = 2`:
   - Iterasi 1: Menambahkan `Guru Mapel` dan `Wali Kelas Biduri`.
   - Iterasi 2: Menambahkan `Guru Mapel` dan `Wali Kelas Biduri`.
   - Iterasi 3: Menambahkan `Guru Mapel` dan `Wali Kelas Biduri`.
4. Muncul total 6 opsi di frontend.

### C. Tidak Adanya Constraint Unik di Database
Pada tabel `user_roles`, index `(user_id, role_id)` semula dibuat sebagai index biasa (`INDEX`/`KEY`), bukan `UNIQUE KEY`. Sehingga database mengizinkan baris duplikat tersimpan tanpa error constraint.

---

## 3. Rincian Perubahan Kode yang Diterapkan

1. **[`backend/app/Controllers/Auth/LoginController.php`](file:///d:/laragon/htdocs/erapoteasy/backend/app/Controllers/Auth/LoginController.php)**:
   - Menambahkan deduplikasi awal pada `$dbRoles` berdasarkan `role_id`.
   - Menambahkan pengecekan `if ($guru && !$hasWaliKelasRole)` di penanganan `$rid == 2`.
   - Menambahkan filter akhir berbasis `role['key']` agar tidak ada peran yang memiliki key sama muncul lebih dari 1 kali.

2. **[`backend/app/Models/Admin/UserModel.php`](file:///d:/laragon/htdocs/erapoteasy/backend/app/Models/Admin/UserModel.php)**:
   - Menambahkan method `->distinct()` pada query `getUserRoles()`.

3. **[`backend/app/Controllers/Admin/GuruTendikController.php`](file:///d:/laragon/htdocs/erapoteasy/backend/app/Controllers/Admin/GuruTendikController.php)**:
   - Menghapus perintah update massal pada `user_roles`.
   - Menggantinya dengan update `users.role_id = $roleId` dan melakukan pengecekan `countAllResults()` sebelum insert ke `user_roles` agar tidak menduplikasi data atau merusak role lain.

4. **[`backend/app/Controllers/Admin/UsersController.php`](file:///d:/laragon/htdocs/erapoteasy/backend/app/Controllers/Admin/UsersController.php)**:
   - Menambahkan sanitasi `array_values(array_unique(array_filter(array_map('intval', $roleIds))))` pada fungsi `updateInlineRoles()`.

---

## 4. Query SQL untuk Membersihkan Sisa Role Duplikat

Jika Anda perlu menjalankan pembersihan ini di database server lain (misalnya server staging/production), jalankan langkah-langkah query SQL berikut secara berurutan:

### Langkah 1: Cek Daftar User yang Memiliki Role Duplikat
```sql
SELECT 
    ur.user_id, 
    u.username, 
    u.email, 
    ur.role_id, 
    r.role_name, 
    COUNT(*) AS total_duplikat
FROM user_roles ur
JOIN users u ON u.id = ur.user_id
JOIN roles r ON r.id = ur.role_id
GROUP BY ur.user_id, ur.role_id
HAVING total_duplikat > 1;
```

### Langkah 2: Hapus Baris Duplikat (Menyimpan Baris dengan ID Terkecil)
Gunakan self-join `DELETE` berikut untuk menghapus duplikat dan mempertahankan satu baris unik:
```sql
DELETE ur1 
FROM user_roles ur1 
INNER JOIN user_roles ur2 
WHERE ur1.id > ur2.id 
  AND ur1.user_id = ur2.user_id 
  AND ur1.role_id = ur2.role_id;
```

### Langkah 3: Tambahkan Constraint UNIQUE agar Duplikat Tidak Pernah Terjadi Lagi
Setelah data bersih, kunci tabel dengan constraint unik `(user_id, role_id)`:
```sql
ALTER TABLE user_roles ADD UNIQUE KEY uq_user_role (user_id, role_id);
```

### Langkah 4: Verifikasi Akhir
Pastikan query pengecekan di bawah mengembalikan `0 rows` (kosong):
```sql
SELECT user_id, role_id, COUNT(*) AS total
FROM user_roles
GROUP BY user_id, role_id
HAVING total > 1;
```

---

## 5. Hasil Verifikasi

Pengujian dilakukan langsung menggunakan data Bu Anggana Ayu Marina (User ID 120):
- **Sebelum Perbaikan**: Terdeteksi 3 baris `role_id = 2` dan menghasilkan 6 opsi login (Guru Mapel dan Wali Kelas Biduri berselang-seling).
- **Setelah Perbaikan**: Tabel `user_roles` telah bersih dari duplikat (24 baris duplikat terhapus di seluruh database). Modal login hanya memunculkan **2 opsi bersih**:
  1. **Guru Mapel** (`/guru/dashboard`)
  2. **Wali Kelas Biduri** (`/wali/ringkasan-kelas`)
