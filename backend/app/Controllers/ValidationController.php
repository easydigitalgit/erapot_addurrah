<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ValidationController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Halaman Validasi Publik untuk Scan QR Code Rapor
     * format token: base64_encode(siswa_id . '|' . ta_id . '|' . kategori)
     */
    public function rapor($token = null)
    {
        if (!$token) {
            return "Token validasi tidak ditemukan.";
        }

        try {
            // URL-Safe Base64 Decode: Kembalikan karakter agar bisa dibaca sistem
            $decoded = base64_decode(strtr($token, '-_,', '+/='));
            $parts = explode('|', $decoded);

            if (count($parts) < 3) {
                return "Format token tidak valid.";
            }

            $siswa_id = $parts[0];
            $ta_id    = $parts[1];
            $kategori = str_replace('_', ' ', $parts[2]);

            // 1. Ambil Data Siswa & Rombel
            $siswa = $this->db->table('siswa s')
                ->select('s.*, r.nama_rombel, r.tingkat')
                ->join('rombel r', 'r.id = s.rombel_id', 'left')
                ->where('s.id', $siswa_id)
                ->get()->getRowArray();

            if (!$siswa) {
                return "Data siswa tidak terdaftar dalam sistem kami.";
            }

            // 2. Ambil Data Tahun Ajaran
            $ta = $this->db->table('tahun_ajaran')->where('id', $ta_id)->get()->getRowArray();

            // 3. Ambil Data Sekolah (JOIN ke Tabel Wilayah agar tidak tampil kode angka)
            $sekolah = $this->db->table('sekolah s')
                ->select('s.*, p.nama as provinsi_nama, k.nama as kabupaten_nama, kc.nama as kecamatan_nama')
                ->join('propinsi p', 'p.kode = s.provinsi', 'left')
                ->join('kabupaten k', 'k.kode = s.kabupaten', 'left')
                ->join('kecamatan kc', 'kc.kode = s.kecamatan', 'left')
                ->get()->getRowArray();

            $data = [
                'title'    => 'Validasi Rapor Digital',
                'status'   => 'verified',
                'siswa'    => $siswa,
                'ta'       => $ta,
                'kategori' => $kategori,
                'sekolah'  => [
                    'nama_sekolah' => $this->titleCaseWithRoman($sekolah['nama_sekolah']),
                    'alamat'       => $this->titleCaseWithRoman($sekolah['alamat']),
                    'kecamatan_nama' => $this->titleCaseWithRoman($sekolah['kecamatan_nama']),
                    'kabupaten_nama' => $this->titleCaseWithRoman($sekolah['kabupaten_nama']),
                    'provinsi_nama'  => $this->titleCaseWithRoman($sekolah['provinsi_nama']),
                    'kode_pos'       => $sekolah['kode_pos'],
                    'logo'           => $sekolah['logo'],
                    'warna_primary'  => $sekolah['warna_primary'] ?? '#10b981',
                    'warna_secondary' => $sekolah['warna_secondary'] ?? '#ecfdf5',
                ],
                'waktu'    => $this->getIndoDate(date('Y-m-d H:i:s')) . ' WIB'
            ];

            return view('public/validasi_rapor', $data);

        } catch (\Exception $e) {
            return "Terjadi kesalahan saat memproses validasi: " . $e->getMessage();
        }
    }

    /**
     * Helper cerdas untuk menaruh format Title Case dengan tetap menjaga Angka Romawi tetap kapital.
     */
    private function titleCaseWithRoman($text)
    {
        if (empty($text)) return "";
        
        $words = explode(' ', strtolower($text));
        $romans = ['i', 'ii', 'iii', 'iv', 'v', 'vi', 'vii', 'viii', 'ix', 'x', 'xi', 'xii'];
        
        foreach ($words as &$word) {
            if (in_array($word, $romans)) {
                $word = strtoupper($word);
            } else {
                $word = ucwords($word);
            }
        }
        
        return implode(' ', $words);
    }

    /**
     * Helper untuk format tanggal Indonesia
     */
    private function getIndoDate($datetime)
    {
        if (!$datetime || $datetime == '0000-00-00 00:00:00') return "-";
        
        $hari = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
        ];
        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $timestamp = strtotime($datetime);
        $nama_hari = $hari[date('l', $timestamp)];
        $tgl       = date('d', $timestamp);
        $nama_bulan = $bulan[(int)date('m', $timestamp)];
        $thn       = date('Y', $timestamp);
        $jam       = date('H:i:s', $timestamp);

        return "$nama_hari, $tgl $nama_bulan $thn - $jam";
    }
}
