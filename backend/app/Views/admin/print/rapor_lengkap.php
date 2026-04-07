<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Rapor Lengkap - <?= esc($siswa['nama_lengkap'] ?? 'Siswa') ?></title>
    <?php
    $p_color = !empty($sekolah['warna_primary']) ? $sekolah['warna_primary'] : '#10b981';
    $s_color = !empty($sekolah['warna_secondary']) ? $sekolah['warna_secondary'] : '#ecfdf5';

    // Logo Sekolah Base64 dari User (PNG)
    $logo_sekolah_base64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAADbQAAAmxCAYAAAAzDJ9VAAAABGdBTUEAALGOfPtRkwAAACBjSFJNAACHDwAAjA8AAP1SAACBQAAAfXkAAOmLAAA85QAAGcxzPIV3AAAKL2lDQ1BJQ0MgUHJvZmlsZQAASMedlndUVNcWh8+9d3qhzTDSGXqTLjCA9C4gHQRRGGYGGMoAwwxNbIioQEQREQFFkKCAAaOhSKyIYiEoqGAPSBBQYjCKqKhkRtZKfHl57+Xl98e939pn73P32XuftS4AJE8fLi8FlgIgmSfgB3o401eFR9Cx/QAGeIABpgAwWempvkHuwUAkLzcXerrICfyL3gwBSPy+ZejpT6eD/0/SrFS+AADIX8TmbE46S8T5Ik7KFKSK7TMipsYkihlGiZkvSlDEcmKOW+Sln30W2VHM7GQeW8TinFPZyWwx94h4e4aQI2LER8QFGVxOpohvi1gzSZjMFfFbcWwyh5kOAIoktgs4rHgRm4iYxA8OdBHxcgBwpLgvOOYLFnCyBOJDuaSkZvO5cfECui5Lj25qbc2ge3IykzgCgaE/k5XI5LPpLemJqUxeNgCLZ/4sGXFt6aIiW5paW1oamhmZflGo/7r4NyXu7SK9CvjcM4jW94ftr/xS6gBgzIpqs+sPW8x+ADq2AiB3/w+b5iEA';

    ?>
    <style>
        .page-break {
            page-break-before: always;
        }

        @page {
            footer: _blank;
            /* Watermark Teks di Lapisan Paling Dasar (Behind Logo) */
            background-image: url("data:image/svg+xml;base64,<?= $watermark_svg ?>");
            background-image-resize: 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.3;
            color: #000;
            margin: 0;
            padding: 20mm 15mm;
            position: relative;
        }


        p,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: transparent !important;
        }

        td,
        th {
            padding: 6px 8px;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-justify {
            text-align: justify;
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

        .tbl-border,
        .tbl-border th,
        .tbl-border td {
            border: 1px solid <?= $p_color ?>;
        }

        .tbl-border th {
            background-color: <?= $p_color ?> !important;
            color: #fff;
            font-weight: bold;
            text-align: center;
            padding: 10px 5px;
        }

        .tbl-bio,
        .tbl-bio th,
        .tbl-bio td {
            border: none !important;
        }

        .tbl-bio td {
            padding: 3px 5px;
            font-size: 10pt;
        }

        .deskripsi-text {
            font-size: 9.5pt;
            text-align: justify;
            line-height: 1.4;
        }

        .kenaikan-box {
            margin-top: 20px;
            padding: 15px;
            border: 2px solid <?= $p_color ?>;
            background-color: <?= $s_color ?>;
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

    <?php if (!empty($opt_cover)): ?>
        <?= view('admin/print/partials/rapor_cover', ['siswa' => $siswa, 'sekolah' => $sekolah]) ?>

        <!-- Logo Watermark Muncul Setelah Cover (Sudah Muncul di Profil Sekolah) -->
        <watermarkimage src="<?= $logo_path ?>" alpha="0.15" size="120,120" />

        <?= view('admin/print/partials/school_profile', ['sekolah' => $sekolah]) ?>
    <?php else: ?>
        <watermarkimage src="<?= $logo_path ?>" alpha="0.15" size="120,120" />
    <?php endif; ?>

    <h3 class="text-center font-bold uppercase" style="margin-bottom: 20px; font-size: 14pt; text-decoration: underline;">Keterangan Tentang Diri Peserta Didik</h3>

    <table class="tbl-bio">
        <tr>
            <td width="5%">1.</td>
            <td width="35%">Nama Peserta Didik (Lengkap)</td>
            <td width="2%">:</td>
            <td class="font-bold uppercase"><?= esc($siswa['nama_lengkap'] ?? '-') ?></td>
        </tr>
        <tr>
            <td>2.</td>
            <td>Nomor Induk / NISN</td>
            <td>:</td>
            <td><?= esc($siswa['nis'] ?? '-') ?> / <?= esc($siswa['nisn'] ?? '-') ?></td>
        </tr>
        <tr>
            <td>3.</td>
            <td>Tempat, Tanggal Lahir</td>
            <td>:</td>
            <td><?= esc($siswa['tempat_lahir'] ?? '-') ?>, <?= esc($siswa['tanggal_lahir'] ?? '-') ?></td>
        </tr>
        <tr>
            <td>4.</td>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td><?= (isset($siswa['jenis_kelamin']) && $siswa['jenis_kelamin'] == 'L') ? 'Laki-laki' : 'Perempuan' ?></td>
        </tr>
        <tr>
            <td>5.</td>
            <td>Agama</td>
            <td>:</td>
            <td><?= esc($siswa['agama'] ?? 'Islam') ?></td>
        </tr>
        <tr>
            <td>6.</td>
            <td>Alamat Peserta Didik</td>
            <td>:</td>
            <td><?= esc($siswa['alamat_siswa'] ?? '-') ?></td>
        </tr>
        <tr>
            <td></td>
            <td>Nomor Telepon</td>
            <td>:</td>
            <td><?= esc($siswa['no_hp'] ?? '-') ?></td>
        </tr>
        <tr>
            <td>7.</td>
            <td>Diterima di sekolah ini</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>a. Di kelas</td>
            <td>:</td>
            <td><?= esc($siswa['tingkat'] ?? '-') ?> (<?= esc($siswa['nama_rombel'] ?? '-') ?>)</td>
        </tr>
        <tr>
            <td></td>
            <td>b. Pada tanggal</td>
            <td>:</td>
            <td><?= esc($siswa['tgl_diterima'] ?? '-') ?></td>
        </tr>
        <tr>
            <td>8.</td>
            <td>Nama Orang Tua</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>a. Ayah</td>
            <td>:</td>
            <td><?= esc($siswa['nama_ayah'] ?? '-') ?></td>
        </tr>
        <tr>
            <td></td>
            <td>b. Ibu</td>
            <td>:</td>
            <td><?= esc($siswa['nama_ibu'] ?? '-') ?></td>
        </tr>
        <tr>
            <td>9.</td>
            <td>Pekerjaan Orang Tua</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td>a. Ayah</td>
            <td>:</td>
            <td><?= esc($siswa['pekerjaan_ayah'] ?? '-') ?></td>
        </tr>
        <tr>
            <td></td>
            <td>b. Ibu</td>
            <td>:</td>
            <td><?= esc($siswa['pekerjaan_ibu'] ?? '-') ?></td>
        </tr>
        <tr>
            <td>10.</td>
            <td>Alamat Orang Tua</td>
            <td>:</td>
            <td><?= esc($siswa['alamat_orangtua'] ?? ($siswa['alamat_siswa'] ?? '-')) ?></td>
        </tr>
        <tr>
            <td>11.</td>
            <td>Nama Wali Peserta Didik</td>
            <td>:</td>
            <td><?= esc($siswa['nama_wali'] ?? '-') ?></td>
        </tr>
    </table>

    <table style="width: 100%; margin-top: 50px; border: none; font-size: 10pt;">
        <tr>
            <td width="40%" style="text-align: center; vertical-align: top; border: none;">
                <div style="width: 113px; height: 151px; border: 1px solid #000; margin: 0 auto; background: #fff; overflow: hidden;">
                    <?php
                    $foto_path = FCPATH . 'uploads/siswa/' . ($siswa['foto_siswa'] ?? 'none.jpg');
                    if (file_exists($foto_path) && !is_dir($foto_path) && !empty($siswa['foto_siswa']) && $siswa['foto_siswa'] !== 'null'): ?>
                        <img src="<?= base_url('uploads/siswa/' . $siswa['foto_siswa']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <div style="padding-top: 40px; font-size: 10pt; color: #000;">PAS FOTO<br>3 x 4</div>
                    <?php endif; ?>
                </div>
            </td>
            <td width="20%" style="border: none;"></td>
            <td width="40%" style="text-align: center; vertical-align: top; border: none;">
                <?= esc(strtoupper($sekolah['kabupaten_nama'] ?? ($sekolah['kabupaten'] ?? 'Lhokseumawe'))) ?>, <?= esc($tanggal_rapor ?? date('d F Y')) ?><br>
                Kepala Sekolah,
                <br><br>

                <div style="height: 80px;" align="center">
                    <?php if (!empty($kepsek['ttd_digital']) && file_exists(FCPATH . 'assets/uploads/ttd/' . $kepsek['ttd_digital'])): ?>
                        <img src="<?= base_url('assets/uploads/ttd/' . $kepsek['ttd_digital']) ?>" style="height: 80px;">
                    <?php else: ?>
                        <br><br><br><br>
                    <?php endif; ?>
                </div>

                <br>
                <strong class="uppercase" style="text-decoration: underline;"><?= esc($kepsek['nama_lengkap'] ?? 'Belum Diatur') ?></strong><br>
                <?= !empty($kepsek['nuptk']) ? 'NUPTK. ' . $kepsek['nuptk'] : (!empty($kepsek['niy']) ? 'NIY. ' . $kepsek['niy'] : 'NIP/NIY. -') ?>
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    <div class="text-center font-bold" style="font-size: 12pt; margin-bottom: 20px; text-transform: uppercase;">
        HASIL PENCAPAIAN KOMPETENSI PESERTA DIDIK
    </div>

    <table class="tbl-bio" style="margin-bottom: 15px;">
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
            <td>Fase</td>
            <td>:</td>
            <td>D</td>
        </tr>
        <tr>
            <td>Nama Sekolah</td>
            <td>:</td>
            <td><?= esc($sekolah['nama_sekolah'] ?? 'SMPIT AD DURRAH') ?></td>
            <td>Semester</td>
            <td>:</td>
            <td><?= esc($semester ?? '-') ?> <?= (!empty($kategori) && $kategori !== 'Akhir Semester') ? ' - ' . esc($kategori) : '' ?></td>
        </tr>
        <tr>
            <td>Alamat Sekolah</td>
            <td>:</td>
            <td style="font-size: 10pt; line-height: 1.4; vertical-align: top; text-align: justify;"> <?php
                                                                                                        // =========================================================================
                                                                                                        // FIX TOTAL: AMBIL DATA LANGSUNG DARI DATABASE MENGGUNAKAN ID & KODE
                                                                                                        // =========================================================================
                                                                                                        $db = \Config\Database::connect();

                                                                                                        // 1. Tarik Nama Desa
                                                                                                        $nama_desa = '';
                                                                                                        if (!empty($sekolah['desa_id'])) {
                                                                                                            $desa = $db->table('desa')->where('id', $sekolah['desa_id'])->orWhere('kode', $sekolah['desa_id'])->get()->getRowArray();
                                                                                                            if ($desa) $nama_desa = $desa['nama'];
                                                                                                        }

                                                                                                        // 2. Tarik Nama Kecamatan
                                                                                                        $nama_kecamatan = '';
                                                                                                        if (!empty($sekolah['kecamatan'])) {
                                                                                                            $kec = $db->table('kecamatan')->where('kode', $sekolah['kecamatan'])->orWhere('id', $sekolah['kecamatan'])->get()->getRowArray();
                                                                                                            if ($kec) $nama_kecamatan = $kec['nama'];
                                                                                                        }

                                                                                                        // 3. Tarik Nama Kabupaten
                                                                                                        $nama_kabupaten = '';
                                                                                                        if (!empty($sekolah['kabupaten'])) {
                                                                                                            $kab = $db->table('kabupaten')->where('kode', $sekolah['kabupaten'])->orWhere('id', $sekolah['kabupaten'])->get()->getRowArray();
                                                                                                            if ($kab) $nama_kabupaten = $kab['nama'];
                                                                                                        }

                                                                                                        // 4. Tarik Nama Provinsi
                                                                                                        $nama_provinsi = '';
                                                                                                        if (!empty($sekolah['provinsi'])) {
                                                                                                            $tbl_prov = $db->tableExists('propinsi') ? 'propinsi' : 'provinsi';
                                                                                                            $prov = $db->table($tbl_prov)->where('kode', $sekolah['provinsi'])->orWhere('id', $sekolah['provinsi'])->get()->getRowArray();
                                                                                                            if ($prov) $nama_provinsi = $prov['nama'];
                                                                                                        }

                                                                                                        // Rangkai Alamat Lengkap
                                                                                                        $alamat_full = esc($sekolah['alamat'] ?? '-');
                                                                                                        if (!empty($nama_desa)) $alamat_full .= ', Kel/Desa ' . esc(ucwords(strtolower($nama_desa)));
                                                                                                        if (!empty($nama_kecamatan)) $alamat_full .= ', Kec. ' . esc(ucwords(strtolower($nama_kecamatan)));
                                                                                                        if (!empty($nama_kabupaten)) $alamat_full .= ', ' . esc(ucwords(strtolower($nama_kabupaten)));
                                                                                                        if (!empty($nama_provinsi)) $alamat_full .= ', ' . esc(ucwords(strtolower($nama_provinsi)));
                                                                                                        if (!empty($sekolah['kode_pos'])) $alamat_full .= ' ' . esc($sekolah['kode_pos']);
                                                                                                        ?>
                <?= $alamat_full ?><br>
            </td>
            <td>Tahun Pelajaran</td>
            <td>:</td>
            <td><?= esc($tahun_ajaran ?? '-') ?></td>
        </tr>
    </table>

    <table class="tbl-border">
        <thead>
            <tr>
                <th width="5%" style="vertical-align: middle;">No.</th>
                <th width="25%" style="vertical-align: middle;">Mata Pelajaran</th>
                <th width="12%" style="vertical-align: middle;">Nilai Akhir</th>
                <th width="58%" style="vertical-align: middle;">Deskripsi Kompetensi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($nilai)): ?>
                <tr>
                    <td colspan="4" class="text-center" style="padding: 20px;">Data nilai belum tersedia.</td>
                </tr>
            <?php else: ?>
                <?php
                $total_nilai = 0;
                $mapel_terisi = 0;
                foreach ($nilai as $i => $n):
                    if ($n['nilai_akhir'] !== '-') {
                        $total_nilai += (float) $n['nilai_akhir'];
                        $mapel_terisi++;
                    }
                endforeach;
                foreach ($nilai as $i => $n):
                ?>
                    <tr>
                        <td class="text-center" style="vertical-align: middle; border-bottom: 1px solid <?= $p_color ?>;"><?= $i + 1 ?></td>
                        <td class="font-bold" style="vertical-align: middle; border-bottom: 1px solid <?= $p_color ?>;"><?= esc($n['nama_mapel']) ?></td>
                        <td class="text-center font-bold" style="font-size: 13pt; vertical-align: middle; border-left: 1px solid <?= $p_color ?>; border-right: 1px solid <?= $p_color ?>; border-bottom: 1px solid <?= $p_color ?>;">
                            <?= esc($n['nilai_akhir'] ?: '-') ?>
                        </td>
                        <td class="deskripsi-text" style="padding: 8px 6px; vertical-align: middle; border-bottom: 1px solid <?= $p_color ?>;">
                            <div><?= esc($n['deskripsi'] ?? '-') ?></div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <?php if (!empty($nilai) && $mapel_terisi > 0): ?>
            <tfoot style="background-color: <?= $s_color ?>;">
                <tr>
                    <td colspan="2" class="text-center font-bold uppercase" style="padding: 10px;">Rata-Rata Nilai</td>
                    <td class="text-center font-bold" style="font-size: 13pt; border-left: 1px solid <?= $p_color ?>; border-right: 1px solid <?= $p_color ?>;"><?= round($total_nilai / $mapel_terisi, 1) ?></td>
                    <td></td>
                </tr>
            </tfoot>
        <?php endif; ?>
    </table>
    <h4 style="margin-top: 15px; margin-bottom: 5px;">Kegiatan Ekstrakurikuler</h4>
    <table class="tbl-border">
        <thead>
            <tr>
                <th width="5%" style="vertical-align: middle;">No</th>
                <th width="35%" style="vertical-align: middle;">Nama Kegiatan</th>
                <th width="15%" style="vertical-align: middle;">Predikat</th>
                <th width="45%" style="vertical-align: middle;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($ekskul)): ?>
                <tr>
                    <td colspan="4" class="text-center" style="padding: 10px; vertical-align: middle;">Siswa tidak mengikuti ekskul apapun</td>
                </tr>
            <?php else: ?>
                <?php foreach ($ekskul as $ix => $eks): ?>
                    <tr>
                        <td class="text-center" style="vertical-align: middle;"><?= $ix + 1 ?></td>
                        <td class="font-bold" style="vertical-align: middle;"><?= esc($eks['kegiatan'] ?? $eks['nama_kegiatan'] ?? '-') ?></td>
                        <td class="text-center font-bold" style="vertical-align: middle; font-size: 13pt;"><?= esc($eks['predikat'] ?? '-') ?></td>
                        <td class="deskripsi-text" style="vertical-align: middle; padding: 8px 6px;">
                            <div style="display: block; width: 100%;">
                                <?= esc($eks['keterangan'] ?? $eks['deskripsi'] ?? '-') ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <h4 style="margin-top: 15px; margin-bottom: 5px;">Ketidakhadiran</h4>
    <table class="tbl-border" style="width: 50%;">
        <tr>
            <td width="60%" style="padding: 6px 10px;">Sakit</td>
            <td width="40%" class="text-center"><?= esc($absen['sakit'] ?? 0) ?> hari</td>
        </tr>
        <tr>
            <td style="padding: 6px 10px;">Izin</td>
            <td class="text-center"><?= esc($absen['izin'] ?? 0) ?> hari</td>
        </tr>
        <tr>
            <td style="padding: 6px 10px;">Tanpa Keterangan</td>
            <td class="text-center"><?= esc($absen['alpha'] ?? 0) ?> hari</td>
        </tr>
    </table>

    <?php if (!empty($catatan['catatan_wali_kelas'])): ?>
        <h4 style="margin-top: 15px; margin-bottom: 5px;">Catatan Wali Kelas</h4>
        <div style="border: 1px solid <?= $p_color ?>; padding: 12px; min-height: 40px; font-style: italic; background: <?= $s_color ?>; border-radius: 4px;">
            "<?= esc($catatan['catatan_wali_kelas']) ?>"
        </div>
    <?php endif; ?>

    <?php if (!empty($catatan['status_kenaikan'])): ?>
        <div class="kenaikan-box">
            <p style="margin:0; font-size: 10pt; font-weight: bold;">Keputusan:</p>
            <p style="margin:0; font-size: 10pt;">Berdasarkan hasil pencapaian di atas, peserta didik ditetapkan:</p>
            <h3 style="margin:8px 0 0 0; font-size: 14pt; text-transform: uppercase; color: <?= $p_color ?>;"><?= esc($catatan['status_kenaikan']) ?></h3>
        </div>
    <?php endif; ?>

    <?php
    // Prioritas nama orang tua
    $nama_ortu = '................................';
    if (!empty($siswa['nama_ayah']) && trim($siswa['nama_ayah']) !== '-') $nama_ortu = $siswa['nama_ayah'];
    elseif (!empty($siswa['nama_ibu']) && trim($siswa['nama_ibu']) !== '-') $nama_ortu = $siswa['nama_ibu'];
    elseif (!empty($siswa['nama_wali']) && trim($siswa['nama_wali']) !== '-') $nama_ortu = $siswa['nama_wali'];
    ?>

    <?php if (!empty($opt_ttd)): ?>
        <table style="width: 100%; margin-top: 15px; border: none; font-size: 10pt; table-layout: fixed;">
            <tr>
                <!-- KOLOM KIRI: ORANG TUA -->
                <td style="width: 35%; text-align: center; vertical-align: top; border: none; padding: 0;">
                    Mengetahui,<br>Orang Tua/Wali
                    <div style="height: 60px;" align="center">
                        <br><br><br>
                    </div>
                    ( <?= esc($nama_ortu) ?> )
                </td>

                <!-- KOLOM TENGAH: QR CODE VALIDASI -->
                <td style="width: 30%; text-align: center; vertical-align: middle; border: none; padding: 0;">
                    <div style="text-align: center; margin-top: 5px;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?= urlencode($link_verifikasi) ?>" style="width: 80px; height: 80px;">
                        <br>
                        <span style="font-size: 7pt; color: #666; font-style: italic;">Scan untuk Validasi</span>
                    </div>
                </td>

                <!-- KOLOM KANAN: WALI KELAS -->
                <td style="width: 35%; text-align: center; vertical-align: top; border: none; padding: 0;">
                    <?= esc(ucwords(strtolower($sekolah['kabupaten_nama'] ?? ($sekolah['kabupaten'] ?? 'Lhokseumawe')))) ?>, <?= esc($tanggal_rapor ?? date('d F Y')) ?><br>
                    Wali Kelas

                    <div style="height: 60px;" align="center">
                        <?php if (!empty($siswa['wali_ttd']) && file_exists(FCPATH . 'assets/uploads/ttd/' . $siswa['wali_ttd'])): ?>
                            <img src="<?= base_url('assets/uploads/ttd/' . $siswa['wali_ttd']) ?>" style="height: 60px;">
                        <?php else: ?>
                            <br><br><br>
                        <?php endif; ?>
                    </div>

                    <strong style="text-decoration: underline;">( <?= esc($siswa['wali_kelas'] ?? '................................') ?> )</strong><br>
                    <?= !empty($siswa['wali_nuptk']) ? 'NUPTK. ' . $siswa['wali_nuptk'] : '' ?>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center; vertical-align: top; padding-top: 15px; border: none;">
                    Mengetahui,<br>Kepala SMPS IT Ad Durrah

                    <div style="height: 70px;" align="center">
                        <?php if (!empty($kepsek['ttd_digital']) && file_exists(FCPATH . 'assets/uploads/ttd/' . $kepsek['ttd_digital'])): ?>
                            <img src="<?= base_url('assets/uploads/ttd/' . $kepsek['ttd_digital']) ?>" style="height: 70px;">
                        <?php else: ?>
                            <br><br><br>
                        <?php endif; ?>
                    </div>

                    <strong class="uppercase" style="text-decoration: underline;"><?= esc($kepsek['nama_lengkap'] ?? '................................') ?></strong><br>
                    <?= !empty($kepsek['nuptk']) ? 'NUPTK. ' . $kepsek['nuptk'] : (!empty($kepsek['niy']) ? 'NIY. ' . $kepsek['niy'] : 'NIP/NIY. -') ?>
                </td>
            </tr>
        </table>
    <?php endif; ?>

</body>

</html>