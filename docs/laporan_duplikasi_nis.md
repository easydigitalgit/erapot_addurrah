# Laporan Duplikasi NIS (Nomor Induk Siswa)
Tanggal: 2026-04-29

Dokumen ini berisi daftar siswa yang memiliki NIS (Nomor Induk Siswa) ganda di database. Duplikasi NIS ini menyebabkan kesalahan pada sistem penilaian (import nilai), di mana nilai siswa yang satu dapat menimpa nilai siswa lainnya.

## Daftar Duplikasi NIS Ditemukan

| No | NIS | Jumlah | Daftar Siswa (Nama & ID) |
|----|-----|--------|--------------------------|
| 1 | 09.25.0046 | 2 | FAHRIAN HAIKAL GINTING (ID: 12) | Faiz Pintara Al-Hafidz (ID: 423) |
| 2 | 09.25.0065 | 2 | KHADAFFI ANWAR (ID: 16) | KHANSA FAIRUZ ZHAFIRAH S (ID: 424) |
| 3 | 09.25.0067 | 2 | KHANZA AYA DIYYA (ID: 74) | KHAYLA AZKA RINANTI (ID: 425) |
| 4 | 09.25.0071 | 2 | MUHAMMAD RABBANI ATTHORIQ (ID: 22) | MUHAMMAD HABIBSYAH SYUKUR (ID: 429) |
| 5 | 09.25.0078 | 2 | MHD JUSTICIO PRADIPTO (ID: 51) | MUHAMMAD AFKHAR AYUB (ID: 427) |
| 6 | 09.25.0084 | 2 | MUHAMMAD IMAM ASHARI (ID: 21) | MHD. HAFIZ FADLI (ID: 426) |
| 7 | 09.25.0087 | 2 | MUHAMMAD RAIHAN RASYIDDIN (ID: 54) | MYSHA ZAHIRA SACHI (ID: 430) |
| 8 | 09.25.0107 | 2 | Safana Al Humaira (ID: 431) | RUMAISHA HUURUN NAASHIRAH DAMANIK (ID: 102) |
| 9 | 09.25.0116 | 2 | SUCI RAMADHANI (ID: 84) | SUCI ZAIRANI (ID: 432) |
| 10 | 09.25.0132 | 2 | MUHAMMAD HABIB SYARIF MAULANA (ID: 428) | MUHAMMAD RIZQI FA'IQ ANANDA (ID: 25) |

## Analisis Masalah (Root Cause)
Pada sistem penilaian (Import Kolektif/Formatif), database melakukan pencarian data siswa menggunakan query:
`WHERE nis = 'NOMOR_NIS' AND rombel_id = 'ID_KELAS'`

Jika satu NIS dimiliki oleh dua siswa dalam rombel yang sama, sistem hanya akan mengambil **satu siswa pertama** yang ditemukan. Akibatnya:
1. Siswa kedua tidak akan pernah mendapatkan nilai dari proses import.
2. Nilai milik siswa kedua akan **menimpa** (overwrite) nilai milik siswa pertama.

## Rekomendasi
Mohon kepada pihak sekolah/admin untuk:
1. Melakukan pengecekan pada berkas fisik siswa terkait NIS yang benar.
2. Melakukan update NIS pada salah satu siswa di setiap pasangan ganda di atas agar setiap siswa memiliki NIS yang unik (unique).
3. Setelah NIS diperbaiki, harap melakukan sinkronisasi ulang atau import ulang nilai untuk memastikan nilai masuk ke siswa yang tepat.

---
*Laporan ini dihasilkan secara otomatis oleh Sistem Analisa Antigravity AI.*
