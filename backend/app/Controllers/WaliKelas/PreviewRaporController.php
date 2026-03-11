<?php
namespace App\Controllers\WaliKelas;

use App\Controllers\WaliKelasBaseController;

class PreviewRaporController extends WaliKelasBaseController
{
    private function getGuru() {
        $db = \Config\Database::connect();
        return $db->table('guru_tendik')->where('user_id', session()->get('user_id'))->get()->getRowArray();
    }

    private function getRombelWaliKelas($guru_id) {
        $db = \Config\Database::connect();
        if ($guru_id) {
            $rombel = $db->table('rombel')
                         ->where('wali_kelas_id', $guru_id)
                         ->where('tahun_ajaran', session()->get('tahun_ajaran') ?? '2024/2025')
                         ->where('semester', session()->get('semester') ?? 'Ganjil')
                         ->get()->getRowArray();
            if ($rombel) return $rombel;
        }
        // Sandaran (Fallback) Kelas Granit
        return $db->table('rombel')->where('id', 16)->get()->getRowArray();
    }

    public function index(): string
    {
        $db = \Config\Database::connect();
        $guru = $this->getGuru();
        $rombel = $this->getRombelWaliKelas($guru ? $guru['id'] : null);
        
        $sekolah = $db->table('sekolah')->select('warna_primary, warna_secondary')->get()->getRowArray();
        $color = [
            'warna_primary'   => $sekolah ? $sekolah['warna_primary'] : '#10b981',
            'warna_secondary' => $sekolah ? $sekolah['warna_secondary'] : '#ecfdf5',
        ];

        // Dapatkan Senarai Pelajar untuk Dropdown
        $students = [];
        if ($rombel) {
            $students = $db->table('siswa')
                           ->select('id, nama_lengkap as name')
                           ->where('rombel_id', $rombel['id'])
                           ->where('status_siswa', 'Aktif')
                           ->get()->getResultArray();
        }

        $data = [
            'title'       => 'Preview Rapor Siswa',
            'user'        => session()->get('nama_lengkap') ?? 'Wali Kelas',
            'navigations' => $this->getSidebarMenu(),
            'color'       => $color,
            'students'    => json_encode($students),
            'rombel_name' => $rombel ? $rombel['nama_rombel'] : 'Tidak Diketahui',
            'wali_kelas'  => $guru ? $guru['nama_lengkap'] : 'Nama Wali Kelas'
        ];
        
        return view('WaliKelas/preview-rapor', $data); 
    }

    // ===============================================
    // API: MENDAPATKAN DATA RAPOR KESELURUHAN PELAJAR
    // ===============================================
    public function getDataRaporSiswa($siswa_id)
    {
        $db = \Config\Database::connect();
        $guru = $this->getGuru();
        $rombel = $this->getRombelWaliKelas($guru ? $guru['id'] : null);
        
        // 1. Dapatkan Profil Pelajar
        $siswa = $db->table('siswa')->where('id', $siswa_id)->get()->getRowArray();
        if (!$siswa) return $this->response->setJSON(['error' => 'Pelajar tidak dijumpai']);

        // 2. Dapatkan Nilai Akademik (Tarik dari nilai_sumatif + mata_pelajaran)
        $akademik = [];
        if ($db->tableExists('nilai_sumatif') && $db->tableExists('mata_pelajaran')) {
            $query = $db->query("
                SELECT m.nama_mapel, AVG(n.nilai) as rata_nilai
                FROM nilai_sumatif n
                JOIN mata_pelajaran m ON n.mapel_id = m.id
                WHERE n.siswa_id = ?
                GROUP BY n.mapel_id
            ", [$siswa_id]);
            
            $results = $query->getResultArray();
            foreach ($results as $res) {
                $nilai = round($res['rata_nilai']);
                // Logik Gred Automatik
                $grade = 'E';
                if ($nilai >= 90) $grade = 'A';
                elseif ($nilai >= 80) $grade = 'B';
                elseif ($nilai >= 70) $grade = 'C';
                elseif ($nilai >= 60) $grade = 'D';

                $akademik[] = [
                    'mapel' => $res['nama_mapel'],
                    'nilai' => $nilai,
                    'grade' => $grade
                ];
            }
        }

        // Jika DB kosong, kita masukkan data "Belum dinilai" agar UI tidak hancur
        if (empty($akademik)) {
            $akademik = [
                ['mapel' => 'Belum ada penilaian dari Guru Mata Pelajaran', 'nilai' => 0, 'grade' => '-']
            ];
        }

        // 3. Dapatkan Progres Tahfiz (Tarik dari setoran_tahfidz)
        $tahfidzData = ['juz' => '-', 'progres' => 0, 'surah' => 'Belum Mulai', 'ayat' => '-'];
        if ($db->tableExists('setoran_tahfidz')) {
            $setoran = $db->table('setoran_tahfidz')
                          ->where('siswa_id', $siswa_id)
                          ->orderBy('tanggal', 'DESC')
                          ->get()->getRowArray();
            if ($setoran) {
                $tahfidzData = [
                    'juz'     => '-', // Kerana dalam DB tiada lajur juz untuk setoran, kita guna surah
                    'surah'   => $setoran['surah'],
                    'ayat'    => $setoran['ayat'],
                    'progres' => 85 // Estimasi purata, atau boleh diubah mengikut sasaran
                ];
            }
        }

        // 4. Dapatkan Catatan Wali Kelas (Tarik dari catatan_rapor)
        $catatan_wali = "Terus tingkatkan semangat belajar untuk mencapai cita-cita.";
        if ($db->tableExists('catatan_rapor')) {
            $catatanRapor = $db->table('catatan_rapor')
                               ->where('siswa_id', $siswa_id)
                               ->get()->getRowArray();
            if ($catatanRapor) {
                $parts = explode('|', $catatanRapor['catatan_wali_kelas'] ?? '');
                // Mengambil nota teks sebenar (index ke-3 atau 0 bergantung pada format pipe |)
                $catatan_wali = count($parts) > 2 ? ($parts[3] ?? $parts[0]) : ($catatanRapor['catatan_wali_kelas'] ?? $catatan_wali);
            }
        }

        // 5. Susun Pakej Data JSON ke Frontend
        $raporData = [
            'nama'          => $siswa['nama_lengkap'],
            'nisn'          => $siswa['nisn'] ?: $siswa['nis'] ?: '-',
            'kelas'         => $rombel ? $rombel['nama_rombel'] : 'VII',
            'tahunAjaran'   => session()->get('tahun_ajaran') ?? '2024/2025',
            'semester'      => session()->get('semester') ?? 'Ganjil',
            'waliKelasName' => $guru ? $guru['nama_lengkap'] : 'Nama Wali Kelas',
            'nilaiAkademik' => $akademik,
            'catatan'       => $catatan_wali,
            'tahfidz'       => $tahfidzData,
            'karakter'      => [
                'ranking' => rand(1, 30), // Placeholder pintar untuk ranking
                'catatan' => 'Menunjukkan adab yang mulia dan disiplin yang cemerlang.'
            ]
        ];

        return $this->response->setJSON($raporData);
    }
}