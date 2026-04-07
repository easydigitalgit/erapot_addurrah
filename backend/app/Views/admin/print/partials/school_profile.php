<div style="font-family: Arial, sans-serif; padding-top: 10px;">
    <h3 class="text-center font-bold uppercase" style="margin-bottom: 30px; font-size: 14pt; text-decoration: underline;">RAPOR PESERTA DIDIK</h3>

    <?php
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

    <table class="tbl-bio" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <tr>
            <td width="30%" style="padding: 10px 0;">Nama Sekolah</td>
            <td width="3%" style="padding: 10px 0;">:</td>
            <td width="67%" style="padding: 10px 0; font-weight: bold;"><?= esc($sekolah['nama_sekolah'] ?? '-') ?></td>
        </tr>
        <tr>
            <td style="padding: 10px 0;">NPSN</td>
            <td style="padding: 10px 0;">:</td>
            <td style="padding: 10px 0;"><?= esc($sekolah['npsn'] ?? '-') ?></td>
        </tr>
        <tr>
            <td style="padding: 10px 0; vertical-align: top;">Alamat Sekolah</td>
            <td style="padding: 10px 0; vertical-align: top;">:</td>
            <td style="padding: 10px 0; line-height: 1.5;">
                <?= $alamat_full ?><br>
                Telp/Fax: <?= esc($sekolah['no_telp'] ?? '-') ?>
            </td>
        </tr>
        <tr>
            <td style="padding: 10px 0;">Kelurahan/Desa</td>
            <td style="padding: 10px 0;">:</td>
            <td style="padding: 10px 0;"><?= esc(strtoupper($nama_desa ?: '-')) ?></td>
        </tr>
        <tr>
            <td style="padding: 10px 0;">Kecamatan</td>
            <td style="padding: 10px 0;">:</td>
            <td style="padding: 10px 0;"><?= esc(strtoupper($nama_kecamatan ?: '-')) ?></td>
        </tr>
        <tr>
            <td style="padding: 10px 0;">Kabupaten/Kota</td>
            <td style="padding: 10px 0;">:</td>
            <td style="padding: 10px 0;"><?= esc(strtoupper($nama_kabupaten ?: '-')) ?></td>
        </tr>
        <tr>
            <td style="padding: 10px 0;">Provinsi</td>
            <td style="padding: 10px 0;">:</td>
            <td style="padding: 10px 0;"><?= esc(strtoupper($nama_provinsi ?: '-')) ?></td>
        </tr>
        <tr>
            <td style="padding: 10px 0;">Website</td>
            <td style="padding: 10px 0;">:</td>
            <td style="padding: 10px 0;"><?= esc($sekolah['website'] ?? '-') ?></td>
        </tr>
        <tr>
            <td style="padding: 10px 0;">Email</td>
            <td style="padding: 10px 0;">:</td>
            <td style="padding: 10px 0;"><?= esc($sekolah['email'] ?? '-') ?></td>
        </tr>
    </table>
</div>

<div class="page-break"></div>