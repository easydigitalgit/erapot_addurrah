<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Rapor Lengkap</title>
    <style>
        /* 1. SETTING HALAMAN & FONT UTAMA */
        .page-break { page-break-before: always; }
        
     body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.3;
            color: #000;
            
            /* ============================================================ */
            /* WATERMARK JURUS TERAKHIR: SUPER SAMAR (3%) */
            /* ============================================================ */
            
            /* Saya pecah coding warnanya:
               fill="#DAA520" (Warna Emas)
               fill-opacity="0.03" (Transparansi 3% - Sangat Tipis)
               Ini biasanya lebih ampuh dibaca oleh PDF Engine
            */
            /* Copy baris ini dan masukkan ke dalam body { } */
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMjAiIGhlaWdodD0iMTgiPjx0ZXh0IHg9IjUwJSIgeT0iMTMiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSI5IiBmb250LXdlaWdodD0iYm9sZCIgZmlsbD0iI0RBQTUyMCIgZmlsbC1vcGFjaXR5PSIwLjE2IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5TTVAgSVQgQUQgRFVSUkFIPC90ZXh0Pjwvc3ZnPg==');
            
            background-repeat: repeat;
            background-position: center top;

            /* Area Bersih (Margin) Tetap Ada */
            padding: 30mm 20mm; 
            
            background-origin: content-box;
            background-clip: content-box;
            
            margin: 0; 
        }

        /* 2. HILANGKAN JARAK BAWAAN PARAGRAF */
        p, h1, h2, h3, h4, h5, h6 {
            margin: 0;
            padding: 0;
        }

        /* 3. SETTING TABEL SUPAYA RAPAT */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            background-color: transparent !important; /* Agar background pattern kelihatan */
        }
        
        /* Setting Jarak (Padding) di dalam sel tabel */
        td, th {
            padding: 4px 5px; /* Atas-Bawah 4px, Kiri-Kanan 5px (Lebih padat) */
            vertical-align: top;
        }

        /* Helper Classes */
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-justify { text-align: justify; } /* Bikin teks lurus rata kanan-kiri */
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .underline { text-decoration: underline; }

        /* 4. STYLE TABEL GARIS (NILAI) */
        .tbl-border, .tbl-border th, .tbl-border td {
            border: 1px solid #000;
        }
        .tbl-border th { 
            background-color: rgba(255, 255, 255, 0.8) !important; /* Putih sedikit transparan */
            font-weight: bold; 
            text-align: center;
            padding: 8px 5px; /* Header sedikit lebih lega */
        }

        /* 5. STYLE BIODATA (TITIK DUA LURUS) */
        .tbl-bio td {
            padding: 3px 5px; /* Biodata lebih rapat lagi */
        }

        /* 6. STYLE HEADER KOP SURAT */
        .header-wrapper {
            text-align: center;
            margin-bottom: 20px;
            padding-top: 20px;
        }
        .header-title { font-size: 14pt; font-weight: bold; margin-bottom: 5px; }
        .header-subtitle { font-size: 12pt; font-weight: bold; }
        .header-school { font-size: 18pt; font-weight: bold; color: #DAA520; margin: 10px 0; display: block; }
        
        /* 7. TANDA TANGAN */
        .ttd-wrapper { margin-top: 30px; }
        .ttd-space { height: 60px; } /* Ruang tanda tangan */

        /* 8. DESKRIPSI NILAI */
        .deskripsi-text {
            font-size: 10pt;
            text-align: justify; /* KUNCI: Supaya lurus rata kanan kiri */
            line-height: 1.2;    /* Jarak antar baris di deskripsi diperkecil */
        }
    </style>
</head>
<body>

    <div class="header-wrapper">
        <img src="<?= $logo_garuda ?>" width="100" style="margin-bottom: 15px;">
        
        <div class="header-title">LAPORAN</div>
        <div class="header-title">HASIL PENCAPAIAN KOMPETENSI PESERTA DIDIK</div>
        <div class="header-subtitle">SMPS IT AD DURRAH</div>

        <div style="margin: 20px 0;">
            <span style="font-size: 14pt; font-weight: bold;">SMP SWASTA ISLAM TERPADU</span><br>
            <span class="header-school">AD DURRAH</span>
        </div>

        <img src="<?= $logo_sekolah ?>" width="160" style="margin-bottom: 30px;">

        <div style="margin-bottom: 10px; font-weight: bold;">Nama Peserta Didik:</div>
        <div style="border: 1px solid #000; padding: 10px; width: 70%; margin: 0 auto; font-weight: bold; font-size: 14pt; background: rgba(255,255,255,0.5);">
            <?= $siswa['nama_lengkap'] ?>
        </div>

        <div style="margin-top: 20px; margin-bottom: 10px; font-weight: bold;">NIS / NISN:</div>
        <div style="border: 1px solid #000; padding: 10px; width: 70%; margin: 0 auto; font-weight: bold; font-size: 14pt; background: rgba(255,255,255,0.5);">
            <?= $siswa['nis'] ?> / <?= $siswa['nisn'] ?? '-' ?>
        </div>

        <div style="margin-top: 60px; font-weight: bold; font-size: 12pt;">
            DINAS PENDIDIKAN<br>KOTA MEDAN
        </div>
    </div>

    <div class="page-break">
        <h3 class="text-center font-bold" style="margin-bottom: 20px;">KETERANGAN TENTANG DIRI PESERTA DIDIK</h3>
        
        <table class="tbl-bio">
            <tr>
                <td width="5%">1.</td>
                <td width="35%">Nama Peserta Didik (Lengkap)</td>
                <td width="2%">:</td>
                <td class="font-bold uppercase"><?= $siswa['nama_lengkap'] ?></td>
            </tr>
            <tr>
                <td>2.</td><td>Nomor Induk / NISN</td><td>:</td>
                <td><?= $siswa['nis'] ?> / <?= $siswa['nisn'] ?></td>
            </tr>
            <tr>
                <td>3.</td><td>Tempat, Tanggal Lahir</td><td>:</td>
                <td><?= $siswa['tempat_lahir'] ?>, <?= $siswa['tanggal_lahir'] ?></td>
            </tr>
            <tr>
                <td>4.</td><td>Jenis Kelamin</td><td>:</td>
                <td><?= $siswa['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
            </tr>
            <tr>
                <td>5.</td><td>Agama</td><td>:</td>
                <td><?= $siswa['agama'] ?? 'Islam' ?></td>
            </tr>
            <tr>
                <td>6.</td><td>Alamat Peserta Didik</td><td>:</td>
                <td><?= $siswa['alamat_siswa'] ?></td>
            </tr>
            <tr>
                <td></td><td>Nomor Telepon</td><td>:</td>
                <td><?= $siswa['no_telp'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>7.</td><td>Diterima di sekolah ini</td><td></td><td></td>
            </tr>
            <tr>
                <td></td><td>a. Di kelas</td><td>:</td>
                <td>VII (Tujuh)</td>
            </tr>
            <tr>
                <td></td><td>b. Pada tanggal</td><td>:</td>
                <td>15 Juli 2023</td>
            </tr>
            <tr>
                <td>8.</td><td>Nama Orang Tua</td><td></td><td></td>
            </tr>
            <tr>
                <td></td><td>a. Ayah</td><td>:</td>
                <td><?= $siswa['nama_ayah'] ?? '-' ?></td>
            </tr>
            <tr>
                <td></td><td>b. Ibu</td><td>:</td>
                <td><?= $siswa['nama_ibu'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>9.</td><td>Pekerjaan Orang Tua</td><td></td><td></td>
            </tr>
            <tr>
                <td></td><td>a. Ayah</td><td>:</td>
                <td><?= $siswa['pekerjaan_ayah'] ?? '-' ?></td>
            </tr>
            <tr>
                <td></td><td>b. Ibu</td><td>:</td>
                <td><?= $siswa['pekerjaan_ibu'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>10.</td><td>Alamat Orang Tua</td><td>:</td>
                <td><?= $siswa['alamat_orangtua'] ?? $siswa['alamat_siswa'] ?></td>
            </tr>
            <tr>
                <td>11.</td><td>Nama Wali Peserta Didik</td><td>:</td>
                <td><?= $siswa['nama_wali'] ?? '-' ?></td>
            </tr>
        </table>

        <table style="margin-top: 40px;">
            <tr>
                <td width="40%" class="text-center">
                    <div style="width: 113px; height: 151px; border: 1px solid #000; margin: 0 auto; background: #fff;">
                        <?php if(file_exists($foto_siswa)): ?>
                             <img src="<?= $foto_siswa ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                             <div style="padding-top: 40px; font-size: 10pt;">PAS FOTO<br>3 x 4</div>
                        <?php endif; ?>
                    </div>
                </td>
                <td width="20%"></td>
                <td width="40%" class="text-left">
                    <p>Medan, <?= $tanggal_rapor ?></p>
                    <p>Kepala SMPS IT Ad Durrah,</p>
                    <div class="ttd-space"></div>
                    <p class="font-bold underline">Muhammad Taufiq, S.Pd, Gr</p>
                    <p>NIP. -</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="page-break">
        <div class="text-center font-bold" style="font-size: 12pt; margin-bottom: 15px;">
            HASIL PENCAPAIAN KOMPETENSI PESERTA DIDIK
        </div>

        <table class="tbl-bio" style="margin-bottom: 10px;">
            <tr>
                <td width="18%">Nama Siswa</td><td width="2%">:</td><td width="40%" class="font-bold"><?= $siswa['nama_lengkap'] ?></td>
                <td width="15%">Kelas</td><td width="2%">:</td><td width="23%"><?= $siswa['nama_rombel'] ?></td>
            </tr>
            <tr>
                <td>NIS/NISN</td><td>:</td><td><?= $siswa['nis'] ?> / <?= $siswa['nisn'] ?? '-' ?></td>
                <td>Fase</td><td>:</td><td>D</td>
            </tr>
            <tr>
                <td>Nama Sekolah</td><td>:</td><td>SMPS IT Ad Durrah</td>
                <td>Semester</td><td>:</td><td><?= $semester ?></td>
            </tr>
            <tr>
                <td>Alamat</td><td>:</td><td>Jl. Selamat II No.7, Medan</td>
                <td>Thn Pelajaran</td><td>:</td><td><?= $tahun_ajaran ?></td>
            </tr>
        </table>

        <table class="tbl-border">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="25%">Mata Pelajaran</th>
                    <th width="8%">Nilai<br>Akhir</th>
                    <th width="62%">Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($nilai)): ?>
                    <tr><td colspan="4" class="text-center">Data nilai belum tersedia.</td></tr>
                <?php else: ?>
                    <?php foreach($nilai as $i => $n): ?>
                    <tr>
                        <td class="text-center"><?= $i + 1 ?></td>
                        <td><?= $n['nama_mapel'] ?></td>
                        <td class="text-center font-bold" style="font-size: 11pt;"><?= $n['nilai_angka'] ?></td>
                        <td class="deskripsi-text">
                            <?= $n['catatan'] ?? "<b>" . $siswa['nama_lengkap'] . "</b> menunjukkan pemahaman yang baik dalam memahami materi pelajaran " . $n['nama_mapel'] . "." ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <h4 style="margin-top: 15px; margin-bottom: 5px;">Kegiatan Ekstrakurikuler</h4>
        <table class="tbl-border">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="40%">Nama Kegiatan</th>
                    <th width="15%">Predikat</th>
                    <th width="40%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($ekskul)): ?>
                    <tr><td colspan="4" class="text-center" style="padding: 10px;">Belum ada data kegiatan ekstrakurikuler.</td></tr>
                <?php else: ?>
                    <?php foreach($ekskul as $ix => $eks): ?>
                    <tr>
                        <td class="text-center"><?= $ix + 1 ?></td>
                        <td><?= $eks['kegiatan'] ?></td>
                        <td class="text-center font-bold"><?= $eks['predikat'] ?></td>
                        <td class="deskripsi-text"><?= $eks['keterangan'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <h4 style="margin-top: 15px; margin-bottom: 5px;">Pencapaian Tahfidz & Tahsin</h4>
        <table class="tbl-border">
            <thead>
                <tr>
                    <th width="30%">Aspek Penilaian</th>
                    <th width="15%">Predikat</th>
                    <th width="55%">Deskripsi Perkembangan</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($tahfidz)): ?>
                    <tr><td colspan="3" class="text-center" style="padding: 10px;">Belum ada data pencapaian tahfidz/tahsin semester ini.</td></tr>
                <?php else: ?>
                <tr>
                    <td class="font-bold">Hafalan (Ziyadah & Muroja'ah)</td>
                    <td class="text-center font-bold" style="font-size: 11pt;"><?= $tahfidz['predikat'] ?? '-' ?></td>
                    <td class="deskripsi-text"><?= $tahfidz['deskripsi_hafalan'] ?? "Ananda telah mengikuti program tahfidz dengan baik." ?></td>
                </tr>
                <tr>
                    <td class="font-bold">Tahsin (Tajwid & Makhroj)</td>
                    <td class="text-center font-bold" style="font-size: 11pt;"><?= $tahfidz['predikat'] ?? '-' ?></td>
                    <td class="deskripsi-text"><?= $tahfidz['deskripsi_tahsin'] ?? "Bacaan Al-Qur'an ananda sesuai dengan kaidah tajwid." ?></td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h4 style="margin-top: 15px; margin-bottom: 5px;">Ketidakhadiran</h4>
        <table class="tbl-border" style="width: 50%;">
            <tr>
                <td width="60%">Sakit</td>
                <td width="40%" class="text-center"><?= $absen['sakit'] ?> hari</td>
            </tr>
            <tr>
                <td>Izin</td>
                <td class="text-center"><?= $absen['izin'] ?> hari</td>
            </tr>
            <tr>
                <td>Tanpa Keterangan</td>
                <td class="text-center"><?= $absen['alpha'] ?> hari</td>
            </tr>
        </table>
        
        <?php if(!empty($catatan_wali)): ?>
        <h4 style="margin-top: 15px; margin-bottom: 5px;">Catatan Wali Kelas</h4>
        <div style="border: 1px solid #000; padding: 8px; min-height: 40px; font-style: italic; background: rgba(255,255,255,0.7);">
            "<?= $catatan_wali ?>"
        </div>
        <?php endif; ?>

        <?php if(!empty($status_kenaikan)): ?>
        <div style="margin-top: 15px; border: 1px solid #000; padding: 10px; text-align: center; background: rgba(255,255,255,0.7);">
            <p style="margin:0; font-size: 10pt;">Keputusan:</p>
            <h3 style="margin:5px 0 0 0;"><?= $status_kenaikan ?></h3>
        </div>
        <?php endif; ?>

        <table class="ttd-wrapper" style="margin-top: 30px;">
            <tr>
                <td width="33%">
                    <p>Mengetahui,</p>
                    <p>Orang Tua/Wali,</p>
                    <div style="height: 60px;"></div>
                    <p class="font-bold underline">( ........................... )</p>
                </td>
                <td width="33%"></td>
                <td width="33%">
                    <p>Medan, <?= $tanggal_rapor ?></p>
                    <p>Wali Kelas,</p>
                    <div style="height: 60px;"></div>
                    <p class="font-bold underline"><?= $siswa['wali_kelas'] ?? '...........................' ?></p>
                    <p>NIP. -</p>
                </td>
            </tr>
            <tr>
                <td colspan="3" class="text-center" style="padding-top: 15px;">
                    <p>Mengetahui,</p>
                    <p>Kepala SMPS IT Ad Durrah</p>
                    <div style="height: 60px;"></div>
                    <p class="font-bold underline">Muhammad Taufiq, S.Pd, Gr</p>
                    <p>NIP. -</p>
                </td>
            </tr>
        </table>
    </div> </body>
</html>