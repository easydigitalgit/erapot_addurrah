<div style="text-align: center; margin-bottom: 20px; padding-top: 20px; font-family: Arial, sans-serif;">

    <?php
    $garudaPath = FCPATH . 'assets/images/garuda-500.png';
    if (file_exists($garudaPath)):
    ?>
        <img src="<?= base_url('assets/images/garuda-500.png') ?>" width="130" style="margin-bottom: 30px;" alt="Garuda">
    <?php else: ?>
        <div style="height: 130px; margin-bottom: 30px;"></div>
    <?php endif; ?>

    <div style="font-size: 18pt; font-weight: bold; margin-bottom: 10px; letter-spacing: 3px;">LAPORAN</div>
    <div style="font-size: 16pt; font-weight: bold; margin-bottom: 5px;">HASIL PENCAPAIAN KOMPETENSI PESERTA DIDIK</div>
    <div style="font-size: 12pt; font-weight: bold; margin-bottom: 50px; text-transform: uppercase;">
        <?= esc($sekolah['nama_sekolah'] ?? 'SMPIT AD DURRAH') ?>
    </div>

    <div style="margin: 40px 0;">
        <?php
        $logoSekolah = FCPATH . 'uploads/logo/' . ($sekolah['logo'] ?? 'none.png');
        if (!file_exists($logoSekolah)) {
            $logoSekolah = FCPATH . 'assets/images/default_logo.png';
        }
        if (file_exists($logoSekolah)):
        ?>
            <img src="<?= $logoSekolah ?>" width="300" style="margin-bottom: 50px;" alt="Logo Sekolah">
        <?php endif; ?>
    </div>

    <div style="margin-bottom: 10px; font-weight: bold; font-size: 11pt;">Nama Peserta Didik:</div>
    <div style="border: 2px solid #000; padding: 12px; width: 80%; margin: 0 auto; font-weight: bold; font-size: 16pt; background: rgba(255,255,255,0.8); text-transform: uppercase;">
        <?= esc($siswa['nama_lengkap']) ?>
    </div>

    <div style="margin-top: 30px; margin-bottom: 10px; font-weight: bold; font-size: 11pt;">NIS / NISN:</div>
    <div style="border: 2px solid #000; padding: 10px; width: 80%; margin: 0 auto; font-weight: bold; font-size: 14pt; background: rgba(255,255,255,0.8);">
        <?= esc($siswa['nis']) ?> / <?= esc($siswa['nisn'] ?? '-') ?>
    </div>

    <div style="margin-top: 80px; font-weight: bold; font-size: 13pt; text-transform: uppercase; line-height: 1.5;">
        DINAS PENDIDIKAN<br>
        <?= esc($sekolah['kabupaten_nama'] ?? ($sekolah['kabupaten'] ?? 'KOTA LHOKSEUMAWE')) ?>
    </div>

</div>

<div style="page-break-after: always;"></div>