<?php

namespace App\Services;

class NilaiRaporSyncService
{
    /**
     * Hitung & Sinkronkan Nilai Rapor untuk satu Siswa & Mapel
     */
    public static function syncStudent(int $siswaId, int $mapelId, int $rombelId, int $taId, string $kategori = 'Tengah Semester'): bool
    {
        try {
            $db = \Config\Database::connect();

            // 1. Ambil Data Mapel & KKM
            $mapel = $db->table('mata_pelajaran')->where('id', $mapelId)->get()->getRowArray();
            $kkm = $mapel['kkm'] ?? 75;

            // 2. Ambil Bobot Nilai dari setting_bobot_nilai
            $katKey = (stripos($kategori, 'tengah') !== false) ? 'tengah_semester' : 'akhir_semester';
            $bobotRows = $db->table('setting_bobot_nilai')->where('kategori', $katKey)->get()->getResultArray();
            $bobotMap = [];
            foreach ($bobotRows as $b) {
                $bobotMap[strtolower($b['sub_kategori'])] = (float)$b['bobot'];
            }
            $bobot_tugas = $bobotMap['nh'] ?? 35;
            $bobot_uh    = $bobotMap['uh'] ?? 35;
            $bobot_sum   = $bobotMap['sts'] ?? ($bobotMap['sas'] ?? 30);

            // 3. Ambil Nilai Formatif
            $katForm = (stripos($kategori, 'tengah') !== false) ? 'Tengah' : 'Akhir';
            $formQuery = $db->table('nilai_formatif')
                ->where('siswa_id', $siswaId)
                ->where('mapel_id', $mapelId)
                ->where('tahun_ajaran_id', $taId)
                ->where('deleted_at', null);

            if ($db->fieldExists('kategori', 'nilai_formatif')) {
                $formQuery->groupStart()
                    ->where('kategori', $kategori)
                    ->orWhere('kategori', $katForm)
                    ->orWhere('kategori', '')
                    ->orWhere('kategori', null)
                    ->groupEnd();
            }

            $listFormatif = $formQuery->get()->getResultArray();

            $sum_tugas = 0; $cnt_tugas = 0;
            $sum_uh    = 0; $cnt_uh    = 0;
            $lm_scores = [];

            foreach ($listFormatif as $f) {
                $val = (float)$f['nilai_angka'];
                $jenis = strtolower($f['jenis_penilaian'] ?? '');
                $pert  = (int)($f['pertemuan'] ?? 1);

                if (strpos($jenis, 'tugas') !== false || strpos($jenis, 'harian') !== false || strpos($jenis, 'nh') !== false) {
                    $sum_tugas += $val;
                    $cnt_tugas++;
                } elseif (strpos($jenis, 'ulangan') !== false || strpos($jenis, 'uh') !== false) {
                    $sum_uh += $val;
                    $cnt_uh++;
                }

                if (!isset($lm_scores[$pert])) {
                    $lm_scores[$pert] = ['sum' => 0, 'count' => 0];
                }
                $lm_scores[$pert]['sum'] += $val;
                $lm_scores[$pert]['count']++;
            }

            $avg_tugas = $cnt_tugas > 0 ? ($sum_tugas / $cnt_tugas) : 0;
            $avg_uh    = $cnt_uh > 0 ? ($sum_uh / $cnt_uh) : 0;
            $rata_formatif = round(($avg_tugas + $avg_uh) / 2, 2);

            // 4. Ambil Nilai Sumatif
            $sumQuery = $db->table('nilai_sumatif')
                ->where('siswa_id', $siswaId)
                ->where('mapel_id', $mapelId)
                ->where('tahun_ajaran_id', $taId)
                ->where('deleted_at', null);

            if (stripos($kategori, 'tengah') !== false) {
                $sumQuery->where('jenis_sumatif', 'pts');
            } else {
                $sumQuery->whereIn('jenis_sumatif', ['pas', 'sas']);
            }

            $listSumatif = $sumQuery->get()->getResultArray();
            $sum_sumatif = 0; $cnt_sumatif = 0;
            foreach ($listSumatif as $s) {
                $sum_sumatif += (float)$s['nilai'];
                $cnt_sumatif++;
            }
            $rata_sumatif = $cnt_sumatif > 0 ? round($sum_sumatif / $cnt_sumatif, 2) : 0;

            // Jika tidak ada formatif dan sumatif, jangan buat nilai rapor
            if ($cnt_tugas === 0 && $cnt_uh === 0 && $cnt_sumatif === 0) {
                return false;
            }

            // 5. Hitung Nilai Akhir
            $total_bobot = $bobot_tugas + $bobot_uh + $bobot_sum;
            if ($total_bobot > 0) {
                $nilai_akhir = round((($avg_tugas * $bobot_tugas) + ($avg_uh * $bobot_uh) + ($rata_sumatif * $bobot_sum)) / $total_bobot, 2);
            } else {
                $nilai_akhir = round(($rata_formatif + $rata_sumatif) / 2, 2);
            }

            // 6. Tentukan Predikat dari setting_aturan_nilai
            $aturan = $db->table('setting_aturan_nilai')->where('is_active', 1)->get()->getResultArray();
            $predikat = 'C';
            if (!empty($aturan)) {
                foreach ($aturan as $a) {
                    if ($nilai_akhir >= (float)$a['nilai_min'] && $nilai_akhir <= (float)$a['nilai_max']) {
                        $predikat = $a['predikat'];
                        break;
                    }
                }
            } else {
                if ($nilai_akhir >= 90) $predikat = 'A';
                elseif ($nilai_akhir >= 80) $predikat = 'B';
                elseif ($nilai_akhir >= 70) $predikat = 'C';
                else $predikat = 'D';
            }

            // 7. Ambil Deskripsi dari Master LM
            $rombel = $db->table('rombel')->where('id', $rombelId)->get()->getRowArray();
            $tingkat = $rombel ? trim((string)$rombel['tingkat']) : '7';
            $tingkatClean = preg_replace('/[^0-9]/', '', $tingkat);
            if (empty($tingkatClean)) {
                if (stripos($tingkat, 'vii') !== false) $tingkatClean = '7';
                elseif (stripos($tingkat, 'viii') !== false) $tingkatClean = '8';
                elseif (stripos($tingkat, 'ix') !== false) $tingkatClean = '9';
            }

            $allLMs = $db->table('master_lm')
                ->where('mapel_id', $mapelId)
                ->groupStart()
                    ->where('tingkat', $tingkat)
                    ->orWhere('tingkat', $tingkatClean)
                ->groupEnd()
                ->where('deleted_at', null)
                ->get()->getResultArray();

            $max_pert = null; $min_pert = null;
            $max_score = -1; $min_score = 101;
            foreach ($lm_scores as $pert => $ds) {
                $avg = $ds['count'] > 0 ? ($ds['sum'] / $ds['count']) : 0;
                if ($avg > $max_score) { $max_score = $avg; $max_pert = $pert; }
                if ($avg < $min_score) { $min_score = $avg; $min_pert = $pert; }
            }

            $deskripsi_tertinggi = '';
            $deskripsi_terendah  = '';

            foreach ($allLMs as $lm) {
                $numLm = (int)preg_replace('/[^0-9]/', '', $lm['kode_lm']);
                $materi = trim(lcfirst($lm['deskripsi_lm'] ?? ''));
                if (!empty($materi)) {
                    if ($numLm === $max_pert) {
                        $deskripsi_tertinggi = "Menunjukkan penguasaan yang sangat baik dalam " . $materi;
                    }
                    if ($numLm === $min_pert && $min_score < 75) {
                        $deskripsi_terendah = "Perlu pendampingan lebih lanjut dalam " . $materi;
                    }
                }
            }

            $deskripsi = $deskripsi_tertinggi . ($deskripsi_terendah ? ". " . $deskripsi_terendah : "");

            // 8. Simpan / Perbarui nilai_rapor
            $payloadRapor = [
                'siswa_id'        => $siswaId,
                'rombel_id'       => $rombelId,
                'tahun_ajaran_id' => $taId,
                'mapel_id'        => $mapelId,
                'kategori'        => $kategori,
                'rata_formatif'   => $rata_formatif,
                'rata_sumatif'    => $rata_sumatif,
                'nilai_akhir'     => $nilai_akhir,
                'predikat'        => $predikat,
                'deskripsi'       => $deskripsi,
                'updated_at'      => date('Y-m-d H:i:s')
            ];

            if ($db->fieldExists('deskripsi_tertinggi', 'nilai_rapor')) {
                $payloadRapor['deskripsi_tertinggi'] = $deskripsi_tertinggi;
            }
            if ($db->fieldExists('deskripsi_terendah', 'nilai_rapor')) {
                $payloadRapor['deskripsi_terendah'] = $deskripsi_terendah;
            }

            $existRapor = $db->table('nilai_rapor')->where([
                'siswa_id'        => $siswaId,
                'mapel_id'        => $mapelId,
                'tahun_ajaran_id' => $taId,
                'kategori'        => $kategori
            ])->get()->getRowArray();

            if ($existRapor) {
                $db->table('nilai_rapor')->where('id', $existRapor['id'])->update($payloadRapor);
            } else {
                $payloadRapor['created_at'] = date('Y-m-d H:i:s');
                $db->table('nilai_rapor')->insert($payloadRapor);
            }

            return true;
        } catch (\Throwable $e) {
            log_message('error', "NilaiRaporSyncService::syncStudent failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Sinkronkan satu Rombel & Mapel secara kolektif
     */
    public static function syncRombel(int $rombelId, int $mapelId, int $taId, string $kategori = 'Tengah Semester'): int
    {
        $db = \Config\Database::connect();
        $siswas = $db->table('anggota_rombel')
            ->select('siswa_id')
            ->where('rombel_id', $rombelId)
            ->where('tahun_ajaran_id', $taId)
            ->where('deleted_at', null)
            ->get()->getResultArray();

        $count = 0;
        foreach ($siswas as $s) {
            if (self::syncStudent((int)$s['siswa_id'], $mapelId, $rombelId, $taId, $kategori)) {
                $count++;
            }
        }
        return $count;
    }
}

