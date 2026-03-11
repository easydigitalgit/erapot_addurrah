<div style="text-align: center; margin-top: 50px; font-family: Arial, sans-serif;">
    <img src="<?= FCPATH ?>assets/images/garuda.png" width="100" style="margin-bottom: 20px;" onerror="this.style.display='none'">
    
    <h1 style="font-size: 24pt; font-weight: bold; margin-bottom: 5px;">RAPOR PESERTA DIDIK</h1>
    <h2 style="font-size: 18pt; font-weight: normal; margin-bottom: 40px;">SEKOLAH MENENGAH PERTAMA (SMP)</h2>
    
    <img src="<?= FCPATH ?>uploads/logo/<?= esc($sekolah['logo'] ?? 'default_logo.png') ?>" width="150" style="margin-bottom: 40px;" onerror="this.style.display='none'">
    
    <div style="margin-top: 50px;">
        <p style="font-size: 14pt; margin-bottom: 5px;">Nama Peserta Didik:</p>
        <div style="border: 2px solid #333; padding: 10px 20px; display: inline-block; min-width: 300px; font-size: 16pt; font-weight: bold; text-transform: uppercase;">
            <?= esc($siswa['nama_lengkap']) ?>
        </div>
    </div>
    
    <div style="margin-top: 20px;">
        <p style="font-size: 14pt; margin-bottom: 5px;">NIS / NISN:</p>
        <p style="font-size: 14pt; font-weight: bold;"><?= esc($siswa['nis']) ?> / <?= esc($siswa['nisn'] ?? '-') ?></p>
    </div>
    
    <div style="margin-top: 80px;">
        <h2 style="font-size: 20pt; font-weight: bold; text-transform: uppercase; margin-bottom: 5px;"><?= esc($sekolah['nama_sekolah'] ?? 'KEMENTERIAN PENDIDIKAN') ?></h2>
        <p style="font-size: 14pt;"><?= esc($sekolah['kabupaten'] ?? 'REPUBLIK INDONESIA') ?></p>
    </div>
</div>

<div style="page-break-after: always;"></div>