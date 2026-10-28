# PANDUAN DAN TOOL PENCABUTAN HAK AKSES ADMIN DARI WALI KELAS

- **Aplikasi:** eRapor SMP IT Addurrah  
- **Database:** `raporsmpit`  
- **File Skrip SQL:** [`docs/pencabutan_akses_admin_walikelas.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/pencabutan_akses_admin_walikelas.sql)  
- **Tanggal Pembuatan:** 28 Oktober 2026  

---

## 1. LATAR BELAKANG & TUJUAN

Di aplikasi eRapor SMP IT Addurrah, hak akses pengguna dikelola pada dua level:
1. **Tabel `users` (Kolom `role_id`):** Menentukan peran dasar pengguna (`1 = Super Admin`, `2 = Guru`, `3 = Wali Kelas`).
2. **Tabel `user_roles`:** Menampung hak akses multi-role (misal seorang Guru dapat sekaligus berperan sebagai Wali Kelas dan Guru Tahfidz).

Ditemukan bahwa beberapa guru yang ditugaskan sebagai Wali Kelas memiliki hak akses `Super Admin` (role_id 1), baik pada tabel `users` maupun tabel `user_roles`. Hal ini berisiko membuka akses ke menu konfigurasi sistem, pengaturan tahun ajaran, dan manipulasi data global sekolah yang seharusnya terbatas bagi administrator utama.

Dokumen ini menyediakan **tool query SQL** untuk menormalkan hak akses seluruh Wali Kelas secara otomatis dan aman.

---

## 2. DAFTAR WALI KELAS YANG TERDAMPAK (AUDIT 28 OKTOBER 2026)

Dari total **15 Wali Kelas aktif**, terdapat **5 Wali Kelas** yang terdeteksi memiliki akses Admin:

| User ID | Username | Nama Wali Kelas | Rombel Diampu | Status Role Saat Ini |
|:-------:|:---------|:----------------|:--------------|:---------------------|
| **78**  | `1234567` | Ary Laksamana Putra | VII Opal | `users.role_id = 1` |
| **111** | `12345` | Septian Syahputra, A.Md. Kom | VII Zamrud | `users.role_id = 1` & `user_roles = 1` |
| **123** | `4544770671130023` | Tika Yeardila, M.Pd | VII Carnelian | `users.role_id = 1` & `user_roles = 1` |
| **125** | `yasirfauzans.pd.i` | Yasir Fauzan S.Pd.I | VIII Topaz | `users.role_id = 1` & `user_roles = 1` |
| **126** | `4537774675230243` | Damiati, S.Pd, Gr | VII Ruby | `users.role_id = 1` & `user_roles = 1` |

*(10 wali kelas lainnya sudah normal dengan `users.role_id = 2` dan tidak memiliki role admin).*

---

## 3. PROSEDUR DAN QUERY SQL

### Langkah 1: Pratinjau Akun Terdampak (Read-Only)
Jalankan query ini terlebih dahulu untuk memverifikasi akun wali kelas yang masih memegang role Admin:

```sql
SELECT 
    u.id AS user_id, 
    u.username, 
    gt.nama_lengkap AS nama_guru, 
    u.role_id AS default_role_id,
    CONCAT(r.tingkat, ' ', r.nama_rombel) AS rombel_diampu,
    ta.tahun AS tahun_ajaran,
    ur.id AS user_roles_id,
    ur.role_id AS multi_role_id
FROM guru_tendik gt
JOIN users u ON u.id = gt.user_id
JOIN rombel r ON r.wali_kelas_id = gt.id
JOIN tahun_ajaran ta ON ta.id = r.id_tahun_ajaran
LEFT JOIN user_roles ur ON ur.user_id = u.id AND ur.role_id = 1
WHERE u.role_id = 1 OR ur.id IS NOT NULL;
```

---

### Langkah 2: Eksekusi Pencabutan Akses Admin & Standarisasi Role

Skrip ini dibungkus dalam transaksi aman (`START TRANSACTION` & `COMMIT`) untuk menjamin konsistensi data:

```sql
START TRANSACTION;

-- A. Hapus entri Super Admin (role_id = 1) dari tabel `user_roles` untuk semua Wali Kelas
DELETE ur 
FROM user_roles ur
JOIN guru_tendik gt ON gt.user_id = ur.user_id
JOIN rombel r ON r.wali_kelas_id = gt.id
WHERE ur.role_id = 1;

-- B. Kembalikan default role_id di tabel `users` menjadi 2 (Guru)
UPDATE users u
JOIN guru_tendik gt ON gt.user_id = u.id
JOIN rombel r ON r.wali_kelas_id = gt.id
SET u.role_id = 2
WHERE u.role_id = 1;

-- C. Pastikan setiap Wali Kelas memiliki role Guru (role_id = 2) di tabel `user_roles`
INSERT INTO user_roles (user_id, role_id)
SELECT DISTINCT u.id, 2
FROM guru_tendik gt
JOIN users u ON u.id = gt.user_id
JOIN rombel r ON r.wali_kelas_id = gt.id
WHERE NOT EXISTS (
    SELECT 1 FROM user_roles ur2 
    WHERE ur2.user_id = u.id AND ur2.role_id = 2
);

-- D. Pastikan setiap Wali Kelas memiliki role Wali Kelas (role_id = 3) di tabel `user_roles`
INSERT INTO user_roles (user_id, role_id)
SELECT DISTINCT u.id, 3
FROM guru_tendik gt
JOIN users u ON u.id = gt.user_id
JOIN rombel r ON r.wali_kelas_id = gt.id
WHERE NOT EXISTS (
    SELECT 1 FROM user_roles ur3 
    WHERE ur3.user_id = u.id AND ur3.role_id = 3
);

COMMIT;
```

> [!TIP]
> **Pengecualian Akun IT Khusus:**  
> Jika akun **Septian Syahputra, A.Md. Kom (User ID: 111)** sengaja diberi akses Super Admin karena bertindak sebagai Tim IT sekolah, tambahkan filter pengecualian pada langkah A dan B:
> ```sql
> WHERE ur.role_id = 1 AND ur.user_id != 111;
> ```
> dan
> ```sql
> WHERE u.role_id = 1 AND u.id != 111;
> ```

---

### Langkah 3: Verifikasi Pasca-Eksekusi

Jalankan query audit berikut:

```sql
SELECT 
    u.id AS user_id, 
    u.username, 
    gt.nama_lengkap AS nama_guru, 
    u.role_id AS default_role_id,
    ur.role_id AS role_id_user_roles
FROM guru_tendik gt
JOIN users u ON u.id = gt.user_id
JOIN rombel r ON r.wali_kelas_id = gt.id
LEFT JOIN user_roles ur ON ur.user_id = u.id AND ur.role_id = 1
WHERE u.role_id = 1 OR ur.role_id = 1;
```

**Kriteria Keberhasilan:**  
Query menghasilkan **0 baris (Empty set)**, menandakan tidak ada lagi Wali Kelas yang memiliki hak akses Super Admin.

---

## 4. CARA MENJALANKAN SKRIP

Anda dapat mengeksekusi langsung melalui command line MySQL atau HeidiSQL / phpMyAdmin:

```powershell
# Melalui terminal PowerShell / CMD:
mysql -u dev -p12345678 raporsmpit < "d:\laragon\htdocs\erapoteasy\docs\pencabutan_akses_admin_walikelas.sql"
```
Atau buka file [`docs/pencabutan_akses_admin_walikelas.sql`](file:///d:/laragon/htdocs/erapoteasy/docs/pencabutan_akses_admin_walikelas.sql) di HeidiSQL / Navicat / phpMyAdmin lalu tekan tombol **Execute / Run**.
