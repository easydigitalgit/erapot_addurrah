<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Rapor Karakter - <?= esc($siswa['nama_lengkap']) ?></title>
    <style>
        /* 1. SETTING HALAMAN & FONT UTAMA */
        .page-break { page-break-before: always; }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.3;
            color: #000;
            
            /* WATERMARK SUPER SAMAR (3%) */
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMjAiIGhlaWdodD0iMTgiPjx0ZXh0IHg9IjUwJSIgeT0iMTMiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSI5IiBmb250LXdlaWdodD0iYm9sZCIgZmlsbD0iI0RBQTUyMCIgZmlsbC1vcGFjaXR5PSIwLjE2IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5TTVAgSVQgQUQgRFVSUkFIPC90ZXh0Pjwvc3ZnPg==');
            background-repeat: repeat;
            background-position: center top;
            padding: 30mm 20mm; 
            background-origin: content-box;
            background-clip: content-box;
            margin: 0; 
        }

        p, h1, h2, h3, h4, h5, h6 { margin: 0; padding: 0; }

        table { width: 100%; border-collapse: collapse; background-color: transparent !important; }
        td, th { padding: 4px 5px; vertical-align: top; }

        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-justify { text-align: justify; } 
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .underline { text-decoration: underline; }

        /* STYLE TABEL GARIS */
        .tbl-border, .tbl-border th, .tbl-border td { border: 1px solid #000; }
        .tbl-border th { 
            background-color: rgba(255, 255, 255, 0.8) !important; 
            font-weight: bold; 
            text-align: center;
            padding: 8px 5px; 
        }

        /* STYLE BIODATA */
        .tbl-bio td { padding: 3px 5px; }
        
        .ttd-wrapper { margin-top: 30px; }
        .ttd-space { height: 60px; } 

        .deskripsi-text { font-size: 10pt; text-align: justify; line-height: 1.2; }
    </style>
</head>
<body>

    <div class="text-center font-bold" style="font-size: 14pt; margin-bottom: 5px; text-decoration: underline;">
        LAPORAN KARAKTER DAN PENGEMBANGAN DIRI
    </div>
    <div class="text-center font-bold" style="font-size: 11pt; margin-bottom: 20px;">
        <?= esc(strtoupper($sekolah['nama_sekolah'] ?? 'SMPIT AD DURRAH')) ?>
    </div>

    <table class="tbl-bio" style="margin-bottom: 25px;">
        <tr>
            <td width="18%">Nama Siswa</td><td width="2%">:</td><td width="40%" class="font-bold uppercase"><?= esc($siswa['nama_lengkap']) ?></td>
            <td width="15%">Kelas</td><td width="2%">:</td><td width="23%"><?= esc($siswa['tingkat']) ?> - <?= esc($siswa['nama_rombel']) ?></td>
        </tr>
        <tr>
            <td>NIS/NISN</td><td>:</td><td><?= esc($siswa['nis']) ?> / <?= esc($siswa['nisn'] ?? '-') ?></td>
            <td>Fase</td><td>:</td><td>D</td>
        </tr>
        <tr>
            <td>Semester</td><td>:</td><td><?= esc($semester) ?></td>
            <td>Thn Pelajaran</td><td>:</td><td><?= esc($tahun_ajaran) ?></td>
        </tr>
    </table>

    <h4 style="margin-bottom: 5px;">A. Perkembangan Karakter</h4>
    <table class="tbl-border">
        <tr>
            <th width="25%">Sikap Spiritual</th>
            <td class="deskripsi-text" style="padding: 10px;">
                Selalu bersyukur dan berdoa sebelum melakukan kegiatan. Selalu menjalankan ibadah dengan baik dan menjaga toleransi beragama dalam lingkungan sekolah.
            </td>
        </tr>
        <tr>
            <th>Sikap Sosial</th>
            <td class="deskripsi-text" style="padding: 10px;">
                Memiliki sikap santun, disiplin, dan peduli terhadap sesama. Selalu menghargai pendapat orang lain dan mampu bekerja sama dengan sangat baik dalam diskusi tim.
            </td>
        </tr>
    </table>

    <h4 style="margin-top: 20px; margin-bottom: 5px;">B. Kegiatan Ekstrakurikuler</h4>
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
                    <td><?= esc($eks['kegiatan']) ?></td>
                    <td class="text-center font-bold"><?= esc($eks['predikat']) ?></td>
                    <td class="deskripsi-text"><?= esc($eks['keterangan']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <h4 style="margin-top: 20px; margin-bottom: 5px;">C. Pencapaian Tahfidz & Tahsin</h4>
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
                <td class="text-center font-bold" style="font-size: 11pt;"><?= esc($tahfidz['predikat_hafalan'] ?? ($tahfidz['predikat'] ?? '-')) ?></td>
                <td class="deskripsi-text"><?= esc($tahfidz['deskripsi_hafalan'] ?? "Ananda telah mengikuti program tahfidz dengan baik.") ?></td>
            </tr>
            <tr>
                <td class="font-bold">Tahsin (Tajwid & Makhroj)</td>
                <td class="text-center font-bold" style="font-size: 11pt;"><?= esc($tahfidz['predikat_tahsin'] ?? ($tahfidz['predikat'] ?? '-')) ?></td>
                <td class="deskripsi-text"><?= esc($tahfidz['deskripsi_tahsin'] ?? "Bacaan Al-Qur'an ananda sesuai dengan kaidah tajwid.") ?></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h4 style="margin-top: 20px; margin-bottom: 5px;">D. Ketidakhadiran</h4>
    <table class="tbl-border" style="width: 50%;">
        <tr>
            <td width="60%">Sakit</td>
            <td width="40%" class="text-center"><?= esc($absen['sakit']) ?> hari</td>
        </tr>
        <tr>
            <td>Izin</td>
            <td class="text-center"><?= esc($absen['izin']) ?> hari</td>
        </tr>
        <tr>
            <td>Tanpa Keterangan</td>
            <td class="text-center"><?= esc($absen['alpha']) ?> hari</td>
        </tr>
    </table>
    
    <?php if(!empty($catatan['catatan_wali_kelas'])): ?>
    <h4 style="margin-top: 20px; margin-bottom: 5px;">E. Catatan Wali Kelas</h4>
    <div style="border: 1px solid #000; padding: 10px; min-height: 40px; font-style: italic; background: rgba(255,255,255,0.7);">
        "<?= esc($catatan['catatan_wali_kelas']) ?>"
    </div>
    <?php endif; ?>

    <?php if($semester === 'Genap'): ?>
    <div style="margin-top: 20px; border: 1px solid #000; padding: 10px; text-align: center; background: rgba(255,255,255,0.7);">
        <p style="margin:0; font-size: 10pt;">Berdasarkan hasil pencapaian di atas, peserta didik ditetapkan:</p>
        <h3 style="margin:5px 0 0 0;"><?= esc($catatan['status_kenaikan'] ?? 'NAIK KE KELAS: ...') ?></h3>
    </div>
    <?php endif; ?>

    <?php if($opt_ttd): ?>
    <table class="ttd-wrapper" style="margin-top: 30px;">
        <tr>
            <td width="33%" class="text-center">
                <p>Mengetahui,</p>
                <p>Orang Tua/Wali,</p>
                <div style="height: 60px;"></div>
                <p class="font-bold underline">( ........................... )</p>
            </td>
            <td width="33%"></td>
            <td width="33%" class="text-center">
                <p><?= esc($sekolah['kabupaten'] ?? 'Lhokseumawe') ?>, <?= esc($tanggal_rapor) ?></p>
                <p>Wali Kelas,</p>
                <div style="height: 60px;"></div>
                <p class="font-bold underline"><?= esc($siswa['wali_kelas'] ?? 'Belum Diatur') ?></p>
                <p>NIP/NUPTK. <?= esc($siswa['wali_nuptk'] ?? '-') ?></p>
            </td>
        </tr>
        <tr>
            <td colspan="3" class="text-center" style="padding-top: 15px;">
                <p>Mengetahui,</p>
                <p>Kepala <?= esc($sekolah['nama_sekolah'] ?? 'SMPS IT Ad Durrah') ?></p>
                <div style="height: 60px;"></div>
                <p class="font-bold underline"><?= esc($kepsek['nama_lengkap'] ?? 'Belum Diatur') ?></p>
                <p>NIP/NUPTK. <?= esc($kepsek['nuptk'] ?? ($kepsek['nik'] ?? '-')) ?></p>
            </td>
        </tr>
    </table>
    <?php endif; ?>

</body>
</html>