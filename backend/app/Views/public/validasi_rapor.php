<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Verifikasi Rapor Digital - <?= esc($title) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: <?= esc($sekolah['warna_primary'] ?? '#1F7A4D') ?>;
            --secondary-color: <?= esc($sekolah['warna_secondary'] ?? '#E8F5E9') ?>;
            --text-color: #2D3436;
            --verified-color: #27AE60;
            --shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            margin: 0; padding: 0;
            min-height: 100vh;
            display: flex; justify-content: center; align-items: center;
        }

        .container {
            width: 100%; max-width: 500px;
            background: white; border-radius: 24px;
            box-shadow: var(--shadow); overflow: hidden;
            border: 1px solid rgba(255,255,255,0.3);
            margin: 20px;
        }

        .header {
            background: var(--primary-color);
            padding: 40px 20px; text-align: center; color: white;
            position: relative;
        }

        .logo-box {
            background: white; width: 80px; height: 80px;
            border-radius: 50%; margin: 0 auto 15px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .logo-box img { max-width: 60px; }

        .status-badge {
            background: var(--verified-color);
            color: white; border-radius: 50px;
            display: inline-flex; align-items: center;
            padding: 8px 16px; margin-top: 15px;
            font-weight: 600; font-size: 0.9rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .content { padding: 30px 25px; }

        .info-row {
            display: flex; justify-content: space-between;
            padding: 12px 0; border-bottom: 1px solid #f1f2f6;
        }

        .info-label { color: #888; font-size: 0.85rem; }
        .info-value { font-weight: 600; color: var(--text-color); }

        .footer {
            background: #FAFAFA; padding: 20px; text-align: center;
            font-size: 0.8rem; color: #999; border-top: 1px solid #EEE;
        }

        .verified-icon {
            font-size: 3rem; color: var(--verified-color);
            margin-bottom: 20px; display: block;
        }

        .school-info { text-align: center; margin-bottom: 25px; }
        .school-name { font-weight: 700; font-size: 1.1rem; color: var(--primary-color); }
        .school-sub { font-size: 0.8rem; opacity: 0.8; }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="logo-box">
             <img src="<?= base_url('uploads/logo/' . $sekolah['logo']) ?>" onerror="this.src='https://cdn-icons-png.flaticon.com/512/167/167707.png'" alt="Logo SMPIT AD DURRAH">
        </div>
        <div class="status-badge">
            <i class="fas fa-check-circle mr-2"></i> &nbsp; LAPOR TERVERIFIKASI ASLI
        </div>
    </div>

    <div class="content">
        <div class="school-info">
            <div class="school-name"><?= esc($sekolah['nama_sekolah']) ?></div>
            <div class="school-sub"><?= esc($sekolah['alamat']) ?></div>
        </div>

        <div class="info-row">
            <div class="info-label">Nama Siswa</div>
            <div class="info-value"><?= esc($siswa['nama_lengkap']) ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">NISN / NIS</div>
            <div class="info-value"><?= esc($siswa['nisn'] ?? '-') ?> / <?= esc($siswa['nis'] ?? '-') ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Kelas / Rombel</div>
            <div class="info-value"><?= esc($siswa['tingkat'] ?? '-') ?> / <?= esc($siswa['nama_rombel'] ?? '-') ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Tahun Ajaran</div>
            <div class="info-value"><?= esc($ta['tahun'] ?? '-') ?> (<?= esc($ta['semester'] ?? '-') ?>)</div>
        </div>
        <div class="info-row">
            <div class="info-label">Jenis Rapor</div>
            <div class="info-value"><?= esc($kategori) ?></div>
        </div>
    </div>

    <div class="footer">
        <p>Sistem Rapor Digital SMPIT AD DURRAH</p>
        <p><i class="fas fa-clock"></i> Verifikasi dilakukan pada: <?= $waktu ?> WIB</p>
    </div>
</div>

</body>
</html>
