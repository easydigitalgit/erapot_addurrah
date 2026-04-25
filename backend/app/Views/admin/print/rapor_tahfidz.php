    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>Rapor Tahfidz - <?= esc($siswa['nama_lengkap']) ?></title>
        <style>
            <?php
            $p_color = !empty($color['warna_primary']) ? $color['warna_primary'] : '#10b981';
            $s_color = !empty($color['warna_secondary']) ? $color['warna_secondary'] : '#ecfdf5';
            ?>
            @page {
                footer: _blank; 
                /* Watermark Teks di Lapisan Paling Dasar (Behind Logo) */
                background-image: url("data:image/svg+xml;base64,<?= $watermark_svg ?>");
                background-image-resize: 0;
            }

            body {
                font-family: 'Times New Roman', serif;
                font-size: 11pt;
                line-height: 1.4;
                color: #000;
                margin: 0;
                padding: 30px;
                position: relative;
            }

            .text-center {
                text-align: center;
            }

            .text-right {
                text-align: right;
            }

            .font-bold {
                font-weight: bold;
            }

            .uppercase {
                text-transform: uppercase;
            }

            .underline {
                text-decoration: underline;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 10px;
                background-color: transparent !important;
            }

            .tbl-border th,
            .tbl-border td {
                border: 1px solid <?= $p_color ?>;
                padding: 6px;
                background-color: transparent !important;
            }

            .bg-gray {
                background-color: <?= $s_color ?> !important;
            }

            .header {
                margin-bottom: 15px;
                border-bottom: 4px double <?= $p_color ?>;
                padding-bottom: 8px;
            }

            .header-text h1 {
                font-size: 18pt;
                margin: 0;
                font-weight: 800;
                color: <?= $p_color ?>;
            }

            .header-text h2 {
                font-size: 14pt;
                margin: 2px 0;
            }

            .header-text p {
                font-size: 10pt;
                margin: 2px 0;
            }

            .section-title {
                font-size: 12pt;
                font-weight: bold;
                padding: 5px 10px;
                margin-top: 15px;
                background: <?= $p_color ?>;
                color: #fff;
                display: inline-block;
                border-radius: 5px 5px 0 0;
            }

            .footer-ttd {
                margin-top: 40px;
            }

            .ttd-box {
                width: 33.3%;
                float: left;
                text-align: center;
                font-size: 11pt;
            }

            .ttd-space {
                height: 80px;
            }

            .score-box {
                border: 2px solid <?= $p_color ?>;
                padding: 10px;
                margin-top: 15px;
                width: 60%;
            }

            .score-box td {
                padding: 4px;
            }
        </style>
    </head>

    <body>
        <!-- Logo Watermark -->
        <watermarkimage src="<?= $logo_path ?>" alpha="0.15" size="120,120" />

        <div class="header">
            <div class="header-text text-center">
                <h1>LAPORAN HASIL CAPAIAN TAHFIDZ AL-QUR'AN</h1>
                <h2><?= esc($sekolah['nama_sekolah'] ?? 'SMPIT AD DURRAH') ?></h2>
                <p><?= esc($alamat_sekolah) ?></p>
            </div>
        </div>

        <table style="margin-bottom: 15px;">
            <tr>
                <td width="15%">Nama Siswa</td>
                <td width="2%">:</td>
                <td width="40%" class="font-bold uppercase"><?= esc($siswa['nama_lengkap']) ?></td>
                <td width="15%">Kelas</td>
                <td width="2%">:</td>
                <td><?= esc($siswa['tingkat'] ?? '-') ?> - <?= esc($siswa['nama_rombel'] ?? '-') ?></td>
            </tr>
            <tr>
                <td>NIS/NISN</td>
                <td>:</td>
                <td><?= esc($siswa['nis']) ?> / <?= esc($siswa['nisn'] ?? '-') ?></td>
                <td>Semester</td>
                <td>:</td>
                <td><?= esc($semester) ?></td>
            </tr>
            <tr>
                <td>Tahun Pelajaran</td>
                <td>:</td>
                <td><?= esc($tahun_ajaran) ?></td>
                <td>Tanggal</td>
                <td>:</td>
                <td><?= esc($tanggal_rapor) ?></td>
            </tr>
        </table>

        <div class="section-title">A. MATERI HAFALAN (<?= esc($juz_info['nama_juz'] ?? 'JUZ 30') ?>)</div>
        <table class="tbl-border">
            <thead>
                <tr class="bg-gray font-bold text-center">
                    <th width="5%">No</th>
                    <th width="28%">Nama Surah</th>
                    <th width="17%">Nilai</th>
                    <th width="5%">No</th>
                    <th width="28%">Nama Surah</th>
                    <th width="17%">Nilai</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $count = count($surahList);
                $half = ceil($count / 2);
                for ($i = 0; $i < $half; $i++):
                    $s1 = $surahList[$i];
                    $s2 = isset($surahList[$i + $half]) ? $surahList[$i + $half] : null;

                    $key1 = ($s1['surah_id'] ?? '0') . '|' . ($s1['ayat'] ?? 'Semua');
                    $v1 = $setoranMap[$key1] ?? '-';

                    $v2 = '';
                    if ($s2) {
                        $key2 = ($s2['surah_id'] ?? '0') . '|' . ($s2['ayat'] ?? 'Semua');
                        $v2 = $setoranMap[$key2] ?? '-';
                    }
                ?>
                    <tr>
                        <td class="text-center"><?= $i + 1 ?></td>
                        <td style="font-size: 10pt;"><?= esc($s1['display']) ?></td>
                        <td class="text-center font-bold" style="background-color: <?= $v1 != '-' ? '#f8f9fa' : 'transparent' ?>;"><?= $v1 ?></td>

                        <?php if ($s2): ?>
                            <td class="text-center"><?= $i + $half + 1 ?></td>
                            <td style="font-size: 10pt;"><?= esc($s2['display']) ?></td>
                            <td class="text-center font-bold" style="background-color: <?= $v2 != '-' ? '#f8f9fa' : 'transparent' ?>;"><?= $v2 ?></td>
                        <?php else: ?>
                            <td class="bg-gray"></td>
                            <td class="bg-gray"></td>
                            <td class="bg-gray"></td>
                        <?php endif; ?>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>

        <div class="section-title">B. RINGKASAN PENILAIAN AKHIR</div>

        <table class="tbl-border" style="margin-bottom: 20px;">
            <thead>
                <tr class="bg-gray font-bold text-center">
                    <th colspan="4" style="padding: 10px; font-size: 12pt;">INDEKS HAFALAN KUMULATIF</th>
                </tr>
                <tr class="bg-gray font-bold text-center">
                    <th width="25%">Angka</th>
                    <th width="25%">Huruf</th>
                    <th width="25%">Derajat</th>
                    <th width="25%">Taqdir</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center" style="font-size: 12pt; height: 40px;">
                    <td class="font-bold"><?= number_format($nilai['nilai_setoran'] ?? 0, 1) ?></td>
                    <td><?= $metrics_setoran['huruf'] ?></td>
                    <td><?= $metrics_setoran['derajat'] ?></td>
                    <td><?= $metrics_setoran['taqdir'] ?></td>
                </tr>
            </tbody>
        </table>

        <table class="tbl-border">
            <thead>
                <tr class="bg-gray font-bold text-center">
                    <th colspan="4" style="padding: 10px; font-size: 12pt;">HASIL EVALUASI TEORI</th>
                </tr>
                <tr class="bg-gray font-bold text-center">
                    <th width="25%">Angka</th>
                    <th width="25%">Huruf</th>
                    <th width="25%">Derajat</th>
                    <th width="25%">Taqdir</th>
                </tr>
            </thead>
            <tbody>
                <tr class="text-center" style="font-size: 12pt; height: 40px;">
                    <td class="font-bold"><?= number_format($nilai['nilai_teori'] ?? 0, 1) ?></td>
                    <td><?= $metrics_teori['huruf'] ?></td>
                    <td><?= $metrics_teori['derajat'] ?></td>
                    <td><?= $metrics_teori['taqdir'] ?></td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 15px;">
            <p class="font-bold" style="font-size: 8pt; margin-bottom: 5px;">Kriteria Predikat (Taqdir):</p>
            <table style="width: 100%; border: none; font-size: 8pt;">
                <tr>
                    <td width="20%">90-100 : Mumtaz (A)</td>
                    <td width="20%">80-89 : Jayyid Jiddan (B)</td>
                    <td width="20%">70-79 : Jayyid (C)</td>
                    <td width="20%">60-69 : Maqbul (D)</td>
                    <td width="20%">&lt; 60 : Mardud (E)</td>
                </tr>
            </table>
        </div>

        <?php
        // Prioritas nama orang tua (Ayah -> Ibu -> Wali)
        $nama_ortu = '................................';
        if (!empty($siswa['nama_ayah']) && trim($siswa['nama_ayah']) !== '-') $nama_ortu = $siswa['nama_ayah'];
        elseif (!empty($siswa['nama_ibu']) && trim($siswa['nama_ibu']) !== '-') $nama_ortu = $siswa['nama_ibu'];
        elseif (!empty($siswa['nama_wali']) && trim($siswa['nama_wali']) !== '-') $nama_ortu = $siswa['nama_wali'];
        ?>

        <table style="width: 100%; margin-top: 30px; border: none; font-size: 10pt; table-layout: fixed;">
            <tr>
                <!-- KOLOM KIRI: ORANG TUA -->
                <td style="width: 35%; text-align: center; vertical-align: top; border: none; padding: 0;">
                    <br>
                    Mengetahui,<br>Orang Tua/Wali
                    <div style="height: 60px;" align="center">
                        <br><br><br>
                    </div>
                    ( <?= esc($nama_ortu) ?> )
                </td>

                <!-- KOLOM TENGAH: QR CODE VALIDASI -->
                <td style="width: 30%; text-align: center; vertical-align: middle; border: none; padding: 0;">
                    <div style="text-align: center; margin-top: 10px;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?= urlencode($link_verifikasi) ?>" style="width: 80px; height: 80px;">
                        <br>
                        <span style="font-size: 7pt; color: #666; font-style: italic;">Scan untuk Validasi</span>
                    </div>
                </td>

                <!-- KOLOM KANAN: PEMBINA TAHFIZ -->
                <td style="width: 35%; text-align: center; vertical-align: top; border: none; padding: 0;">
                    <?php
                    // AMBIL NAMA PEMBINA TAHFIZ
                    $nama_pembina = $guru_tahfidz ?? '................................';
                    ?>
                    <?= esc($lokasi_ttd) ?>, <?= esc($tanggal_rapor ?? date('d F Y')) ?><br>
                    Pembina Tahfiz
                    <br><br><br><br><br>
                    <strong style="text-decoration: underline;">( <?= esc($nama_pembina) ?> )</strong>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center; vertical-align: top; padding-top: 30px; border: none;">
                    Mengetahui,<br>Kepala SMPS IT Ad Durrah
                    <br><br>

                    <div style="height: 80px;" align="center">
                        <?php if (!empty($kepsek['ttd_digital']) && file_exists(FCPATH . 'assets/uploads/ttd/' . $kepsek['ttd_digital'])): ?>
                            <img src="<?= base_url('assets/uploads/ttd/' . $kepsek['ttd_digital']) ?>" style="height: 80px;">
                        <?php else: ?>
                            <br><br><br><br>
                        <?php endif; ?>
                    </div>

                    <br>
                    <strong class="uppercase" style="text-decoration: underline;"><?= esc($kepsek['nama_lengkap'] ?? '................................') ?></strong><br>
                    <?= !empty($kepsek['nuptk']) ? 'NUPTK. ' . $kepsek['nuptk'] : (!empty($kepsek['niy']) ? 'NIY. ' . $kepsek['niy'] : 'NIP/NIY. -') ?>
                </td>
            </tr>
        </table>
    </body>

    </html>