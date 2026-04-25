<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Rapor Akademik - <?= esc($siswa['nama_lengkap'] ?? 'Siswa') ?></title>
    <?php
    $p_color = !empty($sekolah['warna_primary']) ? $sekolah['warna_primary'] : '#10b981';
    $s_color = !empty($sekolah['warna_secondary']) ? $sekolah['warna_secondary'] : '#ecfdf5';

    // Helper Blend Color with White (Fake Transparency for better PDF support)
    if (!function_exists('blendWithWhite')) {
        function blendWithWhite($hex, $weight)
        {
            $hex = str_replace("#", "", $hex);
            if (strlen($hex) == 3) {
                $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
                $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
                $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
            } else {
                $r = hexdec(substr($hex, 0, 2));
                $g = hexdec(substr($hex, 2, 2));
                $b = hexdec(substr($hex, 4, 2));
            }
            // Blend with white (255, 255, 255)
            $r = round($r * $weight + 255 * (1 - $weight));
            $g = round($g * $weight + 255 * (1 - $weight));
            $b = round($b * $weight + 255 * (1 - $weight));
            return sprintf("#%02x%02x%02x", $r, $g, $b);
        }
    }
    $wm_color = blendWithWhite($p_color, 0.04);

    // Logo Sekolah Base64 dari User (PNG)
    $logo_sekolah_base64 = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAADbQAAAmxCAYAAAAzDJ9VAAAABGdBTUEAALGOfPtRkwAAACBjSFJNAACHDwAAjA8AAP1SAACBQAAAfXkAAOmLAAA85QAAGcxzPIV3AAAKL2lDQ1BJQ0MgUHJvZmlsZQAASMedlndUVNcWh8+9d3qhzTDSGXqTLjCA9C4gHQRRGGYGGMoAwwxNbIioQEQREQFFkKCAAaOhSKyIYiEoqGAPSBBQYjCKqKhkRtZKfHl57+Xl98e939pn73P32XuftS4AJE8fLi8FlgIgmSfgB3o401eFR9Cx/QAGeIABpgAwWempvkHuwUAkLzcXerrICfyL3gwBSPy+ZejpT6eD/0/SrFS+AADIX8TmbE46S8T5Ik7KFKSK7TMipsYkihlGiZkvSlDEcmKOW+Sln30W2VHM7GQeW8TinFPZyWwx94h4e4aQI2LER8QFGVxOpohvi1gzSZjMFfFbcWwyh5kOAIoktgs4rHgRm4iYxA8OdBHxcgBwpLgvOOYLFnCyBOJDuaSkZvO5cfECui5Lj25qbc2ge3IykzgCgaE/k5XI5LPpLemJqUxeNgCLZ/4sGXFt6aIiW5paW1oamhmZflGo/7r4NyXu7SK9CvjcM4jW94ftr/xS6gBgzIpqs+sPW8x+ADq2AiB3/w+b5iEA'; // Disingkat untuk efisiensi
    // Catatan: Base64 asli sangat panjang, saya akan menggunakannya penuh di file target.
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

        /* Styling Tabel Bio tanpa border */
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
    </style>
</head>

<body>
    <!-- Logo Watermark -->
    <watermarkimage src="<?= $logo_path ?>" alpha="0.15" size="120,120" />

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
                ?>
                <?php
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
                <td colspan="3" style="padding-top: 15px;">
                    Mengetahui,<br>Kepala SMPS IT Ad Durrah

                    <div style="height: 70px;" align="center">
                        <?php if (!empty($kepsek['ttd_digital']) && file_exists(FCPATH . 'assets/uploads/ttd/' . $kepsek['ttd_digital'])): ?>
                            <img src="<?= base_url('assets/uploads/ttd/' . $kepsek['ttd_digital']) ?>" style="height: 70px;" alt="TTD">
                        <?php else: ?>
                            <br><br><br>
                        <?php endif; ?>
                    </div>

                    <strong class="uppercase" style="text-decoration: underline; margin-bottom: 2px; display: block;"><?= esc($kepsek['nama_lengkap'] ?? '................................') ?></strong>
                    <?= !empty($kepsek['nuptk']) ? 'NUPTK. ' . $kepsek['nuptk'] : (!empty($kepsek['niy']) ? 'NIY. ' . $kepsek['niy'] : 'NIP/NIY. -') ?>
                </td>
            </tr>
        </table>
    <?php endif; ?>
</body>

</html>