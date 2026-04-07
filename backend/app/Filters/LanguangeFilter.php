<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class LanguangeFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // 1. Kita tangkap semua kemungkinan nama session yang biasa dipakai untuk bahasa
        $locale = $session->get('lang') ?? $session->get('locale') ?? $session->get('language') ?? $session->get('bahasa');

        // 2. Jika di session tidak ada, coba ambil dari Database User Preference
        if (!$locale) {
            $userId = $session->get('id') ?? $session->get('user_id');
            if ($userId) {
                $db = \Config\Database::connect();
                $prefs = $db->table('user_preferences')->where('user_id', $userId)->get()->getRowArray();
                
                if ($prefs && !empty($prefs['bahasa'])) {
                    $locale = $prefs['bahasa'];
                    $session->set('lang', $locale); // Simpan ke session agar tidak meload database terus
                }
            }
        }

        // 3. Fallback ke bahasa Indonesia jika semuanya kosong
        if (!$locale) {
            $locale = 'id';
        }

        // 4. TERAPKAN BAHASA SECARA GLOBAL KE SELURUH SISTEM CODEIGNITER!
        // Menggunakan Services langsung agar terhindar dari error RequestInterface
        Services::language()->setLocale($locale);
        
        // Memastikan config bawaan App juga ikut berubah secara runtime
        config('App')->defaultLocale = $locale;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Jangan lakukan apa-apa di sini
    }
}