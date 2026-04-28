<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Rapor Karakter - <?= esc($siswa['nama_lengkap'] ?? 'Siswa') ?></title>
    <?php
    $p_color = !empty($color['warna_primary']) ? $color['warna_primary'] : '#10b981';
    $s_color = !empty($color['warna_secondary']) ? $color['warna_secondary'] : '#ecfdf5';

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

        /* Styling Border */
        .tbl-border {
            border: 1px solid <?= $p_color ?>;
        }

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

        /* Tabel Biodata Tanpa Border */
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
            text-align: center;
        }

        /* Container TTD */
        .ttd-container {
            width: 100%;
            margin-top: 15px;
            font-size: 10pt;
            border: none;
        }

        .ttd-container td {
            border: none !important;
            vertical-align: top;
            text-align: center;
        }

        .ttd-box {
            height: 90px;
            position: relative;
            margin: 10px auto;
        }
    </style>
</head>

<body>
    <!-- Logo Watermark -->
    <watermarkimage src="<?= $logo_path ?>" alpha="0.15" size="120,120" />

    <div class="text-center font-bold" style="font-size: 12pt; margin-bottom: 20px; text-transform: uppercase;">
        LAPORAN KARAKTER DAN PENGEMBANGAN DIRI
    </div>

    <table class="tbl-bio" style="margin-bottom: 25px;">
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
            <td>
                <?php
                // ==========================================
                // LOGIKA PENCARIAN NAMA ALAMAT (Desa, Kec, Kab)
                // ==========================================
                $db = \Config\Database::connect();

                // 1. Cari Nama Desa/Kelurahan
                $nama_desa = '';
                if (!empty($sekolah['desa_nama'])) {
                    $nama_desa = $sekolah['desa_nama'];
                } elseif (!empty($sekolah['desa_id'])) {
                    $desa = $db->table('desa')->where('id', $sekolah['desa_id'])->orWhere('kode', $sekolah['desa_id'])->get()->getRowArray();
                    $nama_desa = $desa ? $desa['nama'] : '';
                }

                // 2. Cari Nama Kecamatan
                $nama_kecamatan = '';
                if (!empty($sekolah['kecamatan_nama'])) {
                    $nama_kecamatan = $sekolah['kecamatan_nama'];
                } elseif (!empty($sekolah['kecamatan'])) {
                    $kecamatan = $db->table('kecamatan')->where('id', $sekolah['kecamatan'])->orWhere('kode', $sekolah['kecamatan'])->get()->getRowArray();
                    $nama_kecamatan = $kecamatan ? $kecamatan['nama'] : '';
                }

                // 3. Cari Nama Kabupaten
                $nama_kabupaten = '';
                if (!empty($sekolah['kabupaten_nama'])) {
                    $nama_kabupaten = $sekolah['kabupaten_nama'];
                } elseif (!empty($sekolah['kabupaten'])) {
                    $kabupaten = $db->table('kabupaten')->where('id', $sekolah['kabupaten'])->orWhere('kode', $sekolah['kabupaten'])->get()->getRowArray();
                    $nama_kabupaten = $kabupaten ? $kabupaten['nama'] : '';
                }

                // 4. Cari Nama Provinsi
                $nama_provinsi = '';
                if (!empty($sekolah['provinsi'])) {
                    $tbl_prov = $db->tableExists('propinsi') ? 'propinsi' : 'provinsi';
                    $prov = $db->table($tbl_prov)->where('kode', $sekolah['provinsi'])->orWhere('id', $sekolah['provinsi'])->get()->getRowArray();
                    if ($prov) $nama_provinsi = $prov['nama'];
                }

                // Pastikan jika nilai akhirnya masih berupa angka kode (gagal terjemah), kita kosongkan saja / default
                if (is_numeric(str_replace('.', '', $nama_desa))) $nama_desa = '';
                if (is_numeric(str_replace('.', '', $nama_kecamatan))) $nama_kecamatan = '';
                if (is_numeric(str_replace('.', '', $nama_kabupaten))) $nama_kabupaten = 'Medan';

                // Rangkai Alamat Lengkap
                $alamat_sekolah_full = esc(ucwords(strtolower($sekolah['alamat'] ?? '-')));
                if (!empty($nama_desa)) $alamat_sekolah_full .= ', Kel/Desa ' . esc(ucwords(strtolower($nama_desa)));
                if (!empty($nama_kecamatan)) $alamat_sekolah_full .= ', Kec. ' . esc(ucwords(strtolower($nama_kecamatan)));
                if (!empty($nama_kabupaten)) $alamat_sekolah_full .= ', ' . esc(ucwords(strtolower($nama_kabupaten)));
                if (!empty($nama_provinsi)) $alamat_sekolah_full .= ', ' . esc(ucwords(strtolower($nama_provinsi)));
                if (!empty($sekolah['kode_pos'])) $alamat_sekolah_full .= ' ' . esc($sekolah['kode_pos']);

                echo $alamat_sekolah_full;
                ?>
            </td>
            <td>Tahun Pelajaran</td>
            <td>:</td>
            <td><?= esc($tahun_ajaran ?? '-') ?></td>
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
            <?php if (empty($ekskul)): ?>
                <tr>
                    <td colspan="4" class="text-center" style="padding: 10px;">Belum ada data kegiatan ekstrakurikuler.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($ekskul as $ix => $eks): ?>
                    <tr>
                        <td class="text-center"><?= $ix + 1 ?></td>
                        <td class="font-bold"><?= esc($eks['kegiatan'] ?? $eks['nama_kegiatan'] ?? '-') ?></td>
                        <td class="text-center font-bold"><?= esc($eks['predikat'] ?? '-') ?></td>
                        <td class="deskripsi-text"><?= esc($eks['keterangan'] ?? $eks['deskripsi'] ?? '-') ?></td>
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
            <?php if (empty($tahfidz)): ?>
                <tr>
                    <td colspan="3" class="text-center" style="padding: 10px;">Belum ada data pencapaian tahfidz/tahsin semester ini.</td>
                </tr>
            <?php else: ?>
                <tr>
                    <td class="font-bold">Hafalan (Ziyadah & Muroja'ah)</td>
                    <td class="text-center font-bold" style="font-size: 13pt; vertical-align: middle;"><?= esc($tahfidz['predikat_hafalan'] ?? ($tahfidz['predikat'] ?? '-')) ?></td>
                    <td class="deskripsi-text" style="padding: 10px;"><?= esc($tahfidz['deskripsi_hafalan'] ?? "Ananda telah mengikuti program tahfidz dengan baik.") ?></td>
                </tr>
                <tr>
                    <td class="font-bold">Tahsin (Tajwid & Makhroj)</td>
                    <td class="text-center font-bold" style="font-size: 13pt; vertical-align: middle;"><?= esc($tahfidz['predikat_tahsin'] ?? ($tahfidz['predikat'] ?? '-')) ?></td>
                    <td class="deskripsi-text" style="padding: 10px;"><?= esc($tahfidz['deskripsi_tahsin'] ?? "Bacaan Al-Qur'an ananda sesuai dengan kaidah tajwid.") ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h4 style="margin-top: 20px; margin-bottom: 5px;">D. Ketidakhadiran</h4>
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
        <h4 style="margin-top: 20px; margin-bottom: 5px;">E. Catatan Wali Kelas</h4>
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
        <table class="ttd-container" style="table-layout: fixed;">
            <tr>
                <!-- KOLOM KIRI: ORANG TUA -->
                <td style="width: 35%;">
                    Mengetahui,<br>Orang Tua/Wali
                    <div style="height: 60px;" align="center">
                        <br><br><br>
                    </div>
                    ( <?= esc($nama_ortu) ?> )
                </td>

                <!-- KOLOM TENGAH: QR CODE VALIDASI -->
                <td style="width: 30%; vertical-align: middle;">
                    <div style="text-align: center; margin-top: 5px;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=<?= urlencode($link_verifikasi) ?>" style="width: 80px; height: 80px;">
                        <br>
                        <span style="font-size: 7pt; color: #666; font-style: italic;">Scan untuk Validasi</span>
                    </div>
                </td>

                <!-- KOLOM KANAN: WALI KELAS -->
                <td style="width: 35%;">
                    <?= esc(ucwords(strtolower($sekolah['kabupaten_nama'] ?? ($sekolah['kabupaten'] ?? 'Lhokseumawe')))) ?>, <?= esc($tanggal_rapor ?? date('d F Y')) ?><br>
                    Wali Kelas
                    <div style="height: 60px;" align="center">
                        <?php if (!empty($siswa['wali_ttd']) && file_exists(FCPATH . 'assets/uploads/ttd/' . $siswa['wali_ttd'])): ?>
                            <img src="<?= FCPATH . 'assets/uploads/ttd/' . $siswa['wali_ttd'] ?>" style="height: 60px;">
                        <?php else: ?>
                            <br><br><br>
                        <?php endif; ?>
                    </div>
                    <strong style="text-decoration: underline;">( <?= esc($siswa['wali_kelas'] ?? '................................') ?> )</strong><br>
                    <?= !empty($siswa['wali_nuptk']) ? 'NUPTK. ' . $siswa['wali_nuptk'] : '' ?>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="padding-top: 15px;">
                    Mengetahui,<br>Kepala SMPS IT Ad Durrah

                    <div class="ttd-box" style="margin: 0 auto; height: 70px;">
                        <?php if (!empty($kepsek['ttd_digital']) && file_exists(FCPATH . 'assets/uploads/ttd/' . $kepsek['ttd_digital'])): ?>
                            <img src="<?= FCPATH . 'assets/uploads/ttd/' . $kepsek['ttd_digital'] ?>" style="max-height: 70px; position: absolute; left: 50%; transform: translateX(-50%);" alt="TTD">
                        <?php else: ?>
                            <br><br><br>
                        <?php endif; ?>
                    </div>

                    <br>
                    <strong class="uppercase" style="text-decoration: underline; margin-bottom: 2px; display: block;"><?= esc($kepsek['nama_lengkap'] ?? '................................') ?></strong>
                    <?= !empty($kepsek['nuptk']) ? 'NUPTK. ' . $kepsek['nuptk'] : (!empty($kepsek['niy']) ? 'NIY. ' . $kepsek['niy'] : 'NIP/NIY. -') ?>
                </td>
            </tr>
        </table>
    <?php endif; ?>

</body>

</html>