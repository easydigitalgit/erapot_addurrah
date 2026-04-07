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

            // 3. Ambil Data Sekolah
            $sekolah = $this->db->table('sekolah')->get()->getRowArray();

            $data = [
                'title'    => 'Validasi Rapor Digital',
                'status'   => 'verified',
                'siswa'    => $siswa,
                'ta'       => $ta,
                'kategori' => $kategori,
                'sekolah'  => $sekolah,
                'waktu'    => date('d-m-Y H:i:s')
            ];

            return view('public/validasi_rapor', $data);

        } catch (\Exception $e) {
            return "Terjadi kesalahan saat memproses validasi: " . $e->getMessage();
        }
    }
}
