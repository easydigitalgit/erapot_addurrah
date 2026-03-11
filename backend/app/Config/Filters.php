<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;
// Hapus use CodeIgniter\Filters\Cors; karena kita pakai Custom Filter buatan sendiri di atas

class Filters extends BaseFilters
{
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        // Arahkan 'cors' ke file yang baru kita buat di langkah 1
        'cors'          => \App\Filters\Cors::class, 
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,        
        'performance'   => PerformanceMetrics::class,
        'role'          => \App\Filters\RoleFilter::class,
        'auth'          => \App\Filters\AuthFilter::class,
        'permission'    => \App\Filters\PermissionFilter::class,    
    ];

    public array $required = [
        'before' => [
            'forcehttps',
            'pagecache',
        ],
        'after' => [
            'pagecache',
            'performance',
            'toolbar',
        ],
    ];

    public array $globals = [
        'before' => [
            // Tambahkan 'cors' di sini agar jalan di SEMUA request
            'cors', 
            // 'honeypot',
            // 'csrf',
            // 'invalidchars',
        ],
        'after' => [
            // 'honeypot',
            // 'secureheaders',
        ],
    ];

    public array $methods = [];

    public array $filters = [];
}