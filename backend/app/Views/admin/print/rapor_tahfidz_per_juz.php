<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Rapor Tahfidz - <?= esc($siswa['nama_lengkap'] ?? 'Siswa') ?> - <?= esc($juz['nama_juz'] ?? 'Juz') ?></title>
    <?php
    $p_color = !empty($color['warna_primary']) ? $color['warna_primary'] : '#10b981';
    $s_color = !empty($color['warna_secondary']) ? $color['warna_secondary'] : '#ecfdf5';
    ?>
    <style>
        .page-break { page-break-before: always; }
        @page { footer: _blank; background-image: url("data:image/svg+xml;base64,<?= $watermark_svg ?>"); background-image-resize: 0; }
        body { font-family: Arial, sans-serif; font-size: 10pt; line-height: 1.3; color: #000; margin: 0; padding: 20mm 15mm; position: relative; }
        p, h1, h2, h3, h4, h5, h6 { margin: 0; padding: 0; }
        table { width: 100%; border-collapse: collapse; background-color: transparent !important; }
        td, th { padding: 8px 10px; vertical-align: top; }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .underline { text-decoration: underline; }
        .tbl-border, .tbl-border th, .tbl-border td { border: 1px solid <?= $p_color ?>; }
        .tbl-border th { background-color: <?= $p_color ?> !important; color: #fff; font-weight: bold; text-align: center; padding: 10px 5px; }
        .tbl-bio, .tbl-bio th, .tbl-bio td { border: none !important; }
        .tbl-bio td { padding: 3px 5px; font-size: 10pt; }
        .deskripsi-text { font-size: 9.5pt; text-align: justify; line-height: 1.4; }
        .footer-ttd { margin-top: 30px; border: none; font-size: 10pt; table-layout: fixed; }
    </style>
</head>

<body>
    <!-- Watermark Logo -->
    <watermarkimage src="<?= $logo_path ?>" alpha="0.1" size="120,120" />

    <!-- KOP SEKOLAH SAMA DENGAN RAPOR AKADEMIK -->
    <table border="0" style="width: 100%; border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 20px;">
        <tr>
            <td width="15%" align="center">
                <img src="<?= $logo_path ?>" style="width: 80px;">
            </td>
            <td width="85%" align="center">
                <h4 style="font-size: 12pt;"><?= esc(strtoupper($sekolah['yayasan'] ?? 'YAYASAN AL-DURRAH')) ?></h4>
                <h2 style="font-size: 16pt; color: <?= $p_color ?>;"><?= esc(strtoupper($sekolah['nama_sekolah'] ?? 'SMPIT AD DURRAH')) ?></h2>
                <p style="font-size: 8pt;"><?= esc($sekolah['alamat'] ?? '-') ?>, <?= esc($sekolah['kecamatan_nama'] ?? '-') ?>, <?= esc($sekolah['kabupaten_nama'] ?? '-') ?></p>
                <p style="font-size: 8pt;">Telp: <?= esc($sekolah['no_telp'] ?? '-') ?> | Web: <?= esc($sekolah['website'] ?? '-') ?></p>
            </td>
        </tr>
    </table>

    <div class="text-center font-bold" style="font-size: 14pt; margin-bottom: 25px; text-transform: uppercase;">
        LAPORAN CAPAIAN HAFALAN AL-QUR'AN<br>
        <span style="color: <?= $p_color ?>; font-size: 18pt;"><?= esc($juz['nama_juz'] ?? 'JUZ -') ?></span>
    </div>

    <!-- BIODATA SISWA -->
    <table class="tbl-bio" style="margin-bottom: 20px;">
        <tr>
            <td width="15%">Nama Siswa</td>
            <td width="2%">:</td>
            <td width="38%" class="font-bold uppercase"><?= esc($siswa['nama_lengkap'] ?? '-') ?></td>
            <td width="15%">Kelas</td>
            <td width="2%">:</td>
            <td width="28%"><?= esc($siswa['tingkat'] ?? '') ?> - <?= esc($siswa['nama_rombel'] ?? '') ?></td>
        </tr>
        <tr>
            <td>NIS / NISN</td>
            <td>:</td>
            <td><?= esc($siswa['nis'] ?? '-') ?> / <?= esc($siswa['nisn'] ?? '-') ?></td>
            <td>Semester</td>
            <td>:</td>
            <td><?= esc($semester ?? '-') ?></td>
        </tr>
        <tr>
            <td>Tahun Ajaran</td>
            <td>:</td>
            <td><?= esc($tahun_ajaran ?? '-') ?></td>
            <td>Kategori</td>
            <td>:</td>
            <td>Mutaba'ah Tahfidz</td>
        </tr>
    </table>

    <!-- TABEL SETORAN -->
    <table class="tbl-border">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="20%">Surah</th>
                <th width="15%">Ayat</th>
                <th width="10%">Nilai</th>
                <th width="15%">Predikat</th>
                <th width="35%">Catatan Ustadz/ah</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($setoran)): ?>
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">Data setoran hafalan belum tersedia untuk Juz ini.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($setoran as $i => $s): ?>
                    <tr>
                        <td class="text-center"><?= $i + 1 ?></td>
                        <td class="font-bold"><?= esc($s['nama_surah'] ?? $s['surah'] ?? '-') ?></td>
                        <td class="text-center"><?= esc($s['ayat'] ?: 'Semua') ?></td>
                        <td class="text-center font-bold" style="font-size: 12pt;"><?= esc($s['nilai'] ?: '-') ?></td>
                        <td class="text-center" style="font-size: 9pt; font-weight: bold;"><?= esc($s['predikat'] ?: '-') ?></td>
                        <td class="deskripsi-text">
                            <i>"<?= esc($s['catatan'] ?: '-') ?>"</i>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot style="background-color: <?= $s_color ?>;">
            <tr>
                <td colspan="3" class="text-center font-bold uppercase" style="padding: 10px;">Rata-Rata Nilai Juz <?= esc($juz['nama_juz'] ?? '-') ?></td>
                <td class="text-center font-bold" style="font-size: 12pt; border-left: 1px solid <?= $p_color ?>; border-right: 1px solid <?= $p_color ?>;"><?= esc($statistik['rata_nilai'] ?? 0) ?></td>
                <td colspan="2" class="text-center font-bold"><?= esc($statistik['predikat_umum'] ?? '-') ?></td>
            </tr>
        </tfoot>
    </table>

    <p style="margin-top: 15px; font-size: 9pt; font-style: italic; color: #666;">
        * Keterangan Predikat: Mumtaz (90-100), Jayyid Jiddan (80-89), Jayyid (70-79), Maqbul (60-69).
    </p>

    <!-- TANDA TANGAN -->
    <table class="footer-ttd" style="width: 100%;">
        <tr>
            <td style="width: 35%; text-align: center; vertical-align: top; border: none;">
                Mengetahui,<br>Orang Tua/Wali
                <div style="height: 60px;"><br><br><br></div>
                ( .................................... )
            </td>

            <td style="width: 30%; text-align: center; vertical-align: middle; border: none;">
                <div style="text-align: center; margin-top: 5px;">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?= urlencode($link_verifikasi) ?>" style="width: 80px; height: 80px;">
                    <br>
                    <span style="font-size: 7pt; color: #666; font-style: italic;">Scan Validasi</span>
                </div>
            </td>

            <td style="width: 35%; text-align: center; vertical-align: top; border: none;">
                <?= esc(ucwords(strtolower($sekolah['kabupaten_nama'] ?? 'Lhokseumawe'))) ?>, <?= date('d F Y') ?><br>
                Wali Kelas
                <div style="height: 60px;" align="center">
                    <?php if (!empty($siswa['wali_ttd']) && file_exists(FCPATH . 'assets/uploads/ttd/' . $siswa['wali_ttd'])): ?>
                        <img src="<?= base_url('assets/uploads/ttd/' . $siswa['wali_ttd']) ?>" style="height: 60px;">
                    <?php else: ?>
                        <br><br><br>
                    <?php endif; ?>
                </div>
                <strong style="text-decoration: underline;">( <?= esc($siswa['wali_kelas'] ?? '................................') ?> )</strong>
            </td>
        </tr>
    </table>

    <!-- TTD KEPALA SEKOLAH DI TENGAH BAWAH -->
    <table style="width: 100%; border: none; margin-top: 20px;">
        <tr>
            <td align="center" style="border: none;">
                Mengetahui,<br>Kepala Sekolah
                <div style="height: 70px;" align="center">
                    <?php if (!empty($kepsek['ttd_digital']) && file_exists(FCPATH . 'assets/uploads/ttd/' . $kepsek['ttd_digital'])): ?>
                        <img src="<?= base_url('assets/uploads/ttd/' . $kepsek['ttd_digital']) ?>" style="height: 70px;">
                    <?php else: ?>
                        <br><br><br>
                    <?php endif; ?>
                </div>
                <strong class="uppercase" style="text-decoration: underline;"><?= esc($kepsek['nama_lengkap'] ?? '................................') ?></strong><br>
                <?= !empty($kepsek['nuptk']) ? 'NUPTK. ' . $kepsek['nuptk'] : '' ?>
            </td>
        </tr>
    </table>

</body>
</html>
