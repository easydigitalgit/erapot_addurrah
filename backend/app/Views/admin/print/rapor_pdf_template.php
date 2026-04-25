<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Rapor Lengkap</title>
     <?php
    // Logo Sekolah Base64 dari User (PNG)
    $logo_sekolah_base64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAADbQAAAmxCAYAAAAzDJ9VAAAABGdBTUEAALGOfPtRkwAAACBjSFJNAACHDwAAjA8AAP1SAACBQAAAfXkAAOmLAAA85QAAGcxzPIV3AAAKL2lDQ1BJQ0MgUHJvZmlsZQAASMedlndUVNcWh8+9d3qhzTDSGXqTLjCA9C4gHQRRGGYGGMoAwwxNbIioQEQREQFFkKCAAaOhSKyIYiEoqGAPSBBQYjCKqKhkRtZKfHl57+Xl98e939pn73P32XuftS4AJE8fLi8FlgIgmSfgB3o401eFR9Cx/QAGeIABpgAwWempvkHuwUAkLzcXerrICfyL3gwBSPy+ZejpT6eD/0/SrFS+AADIX8TmbE46S8T5Ik7KFKSK7TMipsYkihlGiZkvSlDEcmKOW+Sln30W2VHM7GQeW8TinFPZyWwx94h4e4aQI2LER8QFGVxOpohvi1gzSZjMFfFbcWwyh5kOAIoktgs4rHgRm4iYxA8OdBHxcgBwpLgvOOYLFnCyBOJDuaSkZvO5cfECui5Lj25qbc2ge3IykzgCgaE/k5XI5LPpLemJqUxeNgCLZ/4sGXFt6aIiW5paW1oamhmZflGo/7r4NyXu7SK9CvjcM4jW94ftr/xS6gBgzIpqs+sPW8x+ADq2AiB3/w+b5iEA';
    ?>
    <style>
        /* 1. SETTING HALAMAN & FONT UTAMA */
        .page-break { page-break-before: always; }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 30mm 20mm;
            position: relative;
        }

        /* Watermark Logo Sekolah */
        body::before {
            content: "";
            position: fixed;
            top: 50%;
            left: 50%;
            width: 500px;
            height: 500px;
            margin-left: -250px;
            margin-top: -250px;
            background-image: url('<?= $logo_sekolah_base64 ?? '' ?>');
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            opacity: 0.08;
            z-index: -1000;
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
            DINAS PENDIDIKAN<br><?= esc($sekolah['kabupaten_nama'] ?? 'KOTA MEDAN') ?>
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
                <td><?= $siswa['diterima_dikelas'] ?? '-' ?></td>
            </tr>
            <tr>
                <td></td><td>b. Pada tanggal</td><td>:</td>
                <td>
                    <?php 
                        if (!empty($siswa['tgl_diterima']) && $siswa['tgl_diterima'] != '0000-00-00') {
                            $bulan = [
                                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                                '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                            ];
                            $split = explode('-', $siswa['tgl_diterima']);
                            echo (isset($split[2]) ? $split[2] : '') . ' ' . ($bulan[$split[1] ?? ''] ?? '') . ' ' . ($split[0] ?? '');
                        } else {
                            echo '-';
                        }
                    ?>
                </td>
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
                    <?= esc($sekolah['kabupaten_nama'] ?? ($sekolah['kabupaten'] ?? 'Lhokseumawe')) ?>, <?= $tanggal_rapor ?><br>
                    Kepala Sekolah,
                    <div style="height: 120px; position: relative; margin-top: 5px; margin-bottom: 5px;">
                        <?php if (!empty($kepsek['ttd_digital']) && file_exists(FCPATH . 'assets/uploads/ttd/' . $kepsek['ttd_digital'])): ?>
                            <img src="<?= base_url('assets/uploads/ttd/' . $kepsek['ttd_digital']) ?>" style="height: 100px;">
                        <?php else: ?>
                            <br><br><br><br><br>
                        <?php endif; ?>
                    </div>
                    <strong><?= esc($kepsek['nama_lengkap'] ?? '................................') ?></strong><br>
                    <?= !empty($kepsek['nuptk']) ? 'NUPTK. '.$kepsek['nuptk'] : (!empty($kepsek['niy']) ? 'NIY. '.$kepsek['niy'] : 'NIP/NIY. -') ?>
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

        <!-- Tahfidz & Tahsin section removed as it has its own report module -->

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

        <table style="width: 100%; margin-top: 40px; border: none; font-size: 10pt;">
            <tr>
                <td style="width: 28%; text-align: center; vertical-align: top;">
                    Mengetahui,<br>Orang Tua/Wali
                    <br><br><br><br><br><br><br>
                    ( ................................ )
                </td>
                <td style="width: 32%; text-align: center; vertical-align: top;">
                    Diverifikasi Oleh,<br>Wali Kelas
                    <br><br><br><br><br><br><br>
                    <strong>( <?= esc($siswa['wali_kelas'] ?? '................................') ?> )</strong>
                </td>
                <td style="width: 40%; text-align: center; vertical-align: top;">
                    <?= esc($sekolah['kabupaten_nama'] ?? ($sekolah['kabupaten'] ?? 'Lhokseumawe')) ?>, <?= $tanggal_rapor ?><br>
                    Kepala Sekolah,
                    <div style="height: 120px; position: relative; margin-top: 5px; margin-bottom: 5px;">
                        <?php if (!empty($kepsek['ttd_digital']) && file_exists(FCPATH . 'assets/uploads/ttd/' . $kepsek['ttd_digital'])): ?>
                            <img src="<?= base_url('assets/uploads/ttd/' . $kepsek['ttd_digital']) ?>" style="height: 100px;">
                        <?php else: ?>
                            <br><br><br><br><br>
                        <?php endif; ?>
                    </div>
                    <strong><?= esc($kepsek['nama_lengkap'] ?? '................................') ?></strong><br>
                    <?= !empty($kepsek['nuptk']) ? 'NUPTK. '.$kepsek['nuptk'] : (!empty($kepsek['niy']) ? 'NIY. '.$kepsek['niy'] : 'NIP/NIY. -') ?>
                </td>
            </tr>
        </table>
    </div> </body>
</html>