<?php

namespace App\Services;

class AuditLogService
{
    /**
     * Catat log aktivitas ke tabel audit_logs
     *
     * @param string $action CREATE | UPDATE | DELETE | RESTORE | IMPORT | LOGIN
     * @param string $tabel Nama tabel sasaran (siswa, nilai_formatif, nilai_sumatif, dll)
     * @param int|null $recordId ID data yang diubah
     * @param string $description Deskripsi ringkas aktivitas
     * @param array|null $oldData Data sebelum diubah (opsional)
     * @param array|null $newData Data setelah diubah (opsional)
     * @return bool
     */
    public static function log(string $action, string $tabel, ?int $recordId = null, string $description = '', ?array $oldData = null, ?array $newData = null): bool
    {
        try {
            $db = \Config\Database::connect();
            
            $userId = null;
            $role   = 'System/CLI';
            $userName = 'System';
            $ip     = '127.0.0.1';
            $agent  = 'CLI/Internal';

            // Jika dalam konteks HTTP Web Request
            if (!is_cli()) {
                $session = session();
                $request = service('request');

                $userId   = $session->get('id') ?? $session->get('user_id') ?? null;
                $role     = $session->get('role') ?? $session->get('level') ?? 'User';
                $userName = $session->get('nama_lengkap') ?? $session->get('username') ?? "User #{$userId}";
                $ip       = $request->getIPAddress();
                $agent    = (string)$request->getUserAgent();
            }

            $payload = [
                'user_id'     => $userId,
                'role'        => substr((string)$role, 0, 50),
                'tabel'       => substr((string)$tabel, 0, 50),
                'record_id'   => $recordId,
                'action'      => strtoupper($action),
                'description' => $description,
                'old_data'    => $oldData ? json_encode($oldData, JSON_UNESCAPED_UNICODE) : null,
                'new_data'    => $newData ? json_encode($newData, JSON_UNESCAPED_UNICODE) : null,
                'ip_address'  => substr($ip, 0, 45),
                'user_agent'  => substr($agent, 0, 255),
                'created_at'  => date('Y-m-d H:i:s')
            ];

            $db->table('audit_logs')->insert($payload);

            // Jika berkaitan dengan nilai, catat juga ke riwayat_perubahan_nilai untuk kemudahan audit guru
            if (in_array($tabel, ['nilai_formatif', 'nilai_sumatif', 'nilai_rapor', 'nilai_kolektif'])) {
                $db->table('riwayat_perubahan_nilai')->insert([
                    'user'       => $userName,
                    'aksi'       => $action . ' ' . $tabel,
                    'detail'     => $description . ($oldData && $newData ? " | Old: " . json_encode($oldData) . " -> New: " . json_encode($newData) : ""),
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            return true;
        } catch (\Throwable $e) {
            log_message('error', 'AuditLogService error: ' . $e->getMessage());
            return false;
        }
    }
}

