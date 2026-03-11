<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Rapor Akademik - <?= esc($siswa['nama_lengkap']) ?></title>
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

        table { 
            width: 100%; 
            border-collapse: collapse; 
            background-color: transparent !important; 
        }
        
        td, th {
            padding: 4px 5px; 
            vertical-align: top;
        }

        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-justify { text-align: justify; } 
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .underline { text-decoration: underline; }

        /* STYLE TABEL GARIS (NILAI) */
        .tbl-border, .tbl-border th, .tbl-border td {
            border: 1px solid #000;
        }
        .tbl-border th { 
            background-color: rgba(255, 255, 255, 0.8) !important; 
            font-weight: bold; 
            text-align: center;
            padding: 8px 5px; 
        }

        /* STYLE BIODATA */
        .tbl-bio td {
            padding: 3px 5px; 
        }
        
        .ttd-wrapper { margin-top: 40px; }
        .ttd-space { height: 70px; } 

        .deskripsi-text {
            font-size: 10pt;
            text-align: justify; 
            line-height: 1.2;    
        }
    </style>
</head>
<body>

    <div class="text-center font-bold" style="font-size: 14pt; margin-bottom: 5px; text-decoration: underline;">
        LAPORAN CAPAIAN KOMPETENSI AKADEMIK
    </div>
    <div class="text-center font-bold" style="font-size: 11pt; margin-bottom: 20px;">
        <?= esc(strtoupper($sekolah['nama_sekolah'] ?? 'SMPIT AD DURRAH')) ?>
    </div>

    <table class="tbl-bio" style="margin-bottom: 15px;">
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

    <table class="tbl-border">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="25%">Mata Pelajaran</th>
                <th width="8%">KKM</th>
                <th width="10%">Nilai<br>Akhir</th>
                <th width="8%">Predikat</th>
                <th width="44%">Deskripsi Kompetensi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($nilai)): ?>
                <tr><td colspan="6" class="text-center" style="padding: 15px;">Data nilai akademik belum tersedia.</td></tr>
            <?php else: ?>
                <?php 
                $total_nilai = 0;
                foreach($nilai as $i => $n): 
                    $total_nilai += $n['nilai_akhir'];
                ?>
                <tr>
                    <td class="text-center"><?= $i + 1 ?></td>
                    <td class="font-bold"><?= esc($n['nama_mapel']) ?></td>
                    <td class="text-center"><?= esc($n['kkm']) ?></td>
                    <td class="text-center font-bold" style="font-size: 11pt;"><?= esc($n['nilai_akhir']) ?></td>
                    <td class="text-center font-bold"><?= esc($n['predikat']) ?></td>
                    <td class="deskripsi-text">
                        <?= esc($n['deskripsi']) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <?php if(!empty($nilai)): ?>
        <tfoot style="background-color: rgba(255, 255, 255, 0.8);">
            <tr>
                <td colspan="3" class="text-center font-bold uppercase">Rata-Rata Nilai</td>
                <td class="text-center font-bold" style="font-size: 12pt;"><?= round($total_nilai / count($nilai), 1) ?></td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
        <?php endif; ?>
    </table>

    <?php if($opt_ttd): ?>
    <table class="ttd-wrapper">
        <tr>
            <td width="33%"></td>
            <td width="34%"></td>
            <td width="33%" class="text-left">
                <p><?= esc($sekolah['kabupaten'] ?? 'Lhokseumawe') ?>, <?= esc($tanggal_rapor) ?></p>
                <p>Wali Kelas,</p>
                <div class="ttd-space"></div>
                <p class="font-bold underline"><?= esc($siswa['wali_kelas'] ?? 'Belum Diatur') ?></p>
                <p>NIP/NUPTK. <?= esc($siswa['wali_nuptk'] ?? '-') ?></p>
            </td>
        </tr>
    </table>
    <?php endif; ?>

</body>
</html>