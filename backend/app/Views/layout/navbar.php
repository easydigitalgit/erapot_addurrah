<?php
use CodeIgniter\I18n\Time;

$sekolah = function_exists('get_identitas_sekolah') ? get_identitas_sekolah() : [];

$db = \Config\Database::connect();
$ta_aktif = $db->table('tahun_ajaran')->where('status', 'Aktif')->get()->getRowArray();

$nama_sekolah = $sekolah['nama_sekolah'] ?? lang('Navbar.title_default');
$sub_judul    = lang('Navbar.subtitle');

$tahun_ajar   = $ta_aktif['tahun'] ?? date('Y').'/'.(date('Y')+1);
$semester     = $ta_aktif['semester'] ?? 'Ganjil';

$logo_db = $sekolah['logo'] ?? 'default_logo.png';
$logo_url = ($logo_db !== 'default_logo.png' && $logo_db !== '') ? base_url('uploads/logo/' . $logo_db) : null;

// ========================================================
// LOGIKA CERDAS MENCARI NAMA LENGKAP USER
// ========================================================
$userId = session()->get('id');
$roleKey = session()->get('role_key');
$namaUser = session()->get('nama_lengkap');

// Jika nama lengkap di session kosong, kita cari manual ke database sesuai Role
if (empty($namaUser)) {
    if ($roleKey === 'guru_mapel' || $roleKey === 'wali_kelas') {
        $profil = $db->table('guru_tendik')->select('nama_lengkap')->where('user_id', $userId)->get()->getRowArray();
        $namaUser = $profil ? $profil['nama_lengkap'] : '';
    } elseif ($roleKey === 'siswa') {
        $profil = $db->table('siswa')->select('nama_lengkap')->where('user_id', $userId)->get()->getRowArray();
        $namaUser = $profil ? $profil['nama_lengkap'] : '';
    }
    
    // KHUSUS ADMIN atau jika profil di atas tidak ditemukan
    if (empty($namaUser)) {
        // Ambil semua data dari tabel users (tanpa dibatasi select username saja)
        $profil = $db->table('users')->where('id', $userId)->get()->getRowArray();
        
        if ($profil) {
            // Cek apakah tabel users punya kolom nama_lengkap dan ada isinya
            // Jika tidak ada, baru fallback ke username
            $namaUser = !empty($profil['nama_lengkap']) ? $profil['nama_lengkap'] : ($profil['username'] ?? 'Pengguna');
        } else {
            $namaUser = 'Pengguna';
        }
    }
}

// Fallback final jika semua gagal
if (empty($namaUser)) {
    $namaUser = 'Pengguna';
}

// Inisialisasi Avatar
$inisial    = strtoupper(substr($namaUser, 0, 2));
$fotoProfil = session()->get('foto_profil');

if ($fotoProfil && file_exists(FCPATH . 'assets/uploads/avatars/' . $fotoProfil)) {
    $avatarUrl = base_url('assets/uploads/avatars/' . $fotoProfil);
} else {
    $avatarUrl = "https://ui-avatars.com/api/?name={$inisial}&background=1F7A4D&color=fff&size=100&bold=true";
}
// ========================================================
// ========================================================
// LOGIKA MENGAMBIL NOTIFIKASI DARI DATABASE
// ========================================================
$notifikasiList = [];
$unreadCount = 0;

if ($userId && file_exists(APPPATH . 'Models/NotifikasiModel.php')) {
    $notifModel = new \App\Models\NotifikasiModel();
    $unreadCount = $notifModel->countUnread($userId);
    $notifikasiList = $notifModel->getLatest($userId, 5); 
}
?>
<header id="header" class="bg-white/80 dark:bg-slate-800 dark:border-slate-700 backdrop-blur-md border-b border-gray-200 sticky top-0 z-40 w-full shadow-sm transition-colors">
    <div class="flex items-center justify-between px-4 md:px-6 lg:px-8 py-3 lg:py-4">
        
        <div class="flex items-center gap-3">
            <button onclick="openMobileSidebar()" class="lg:hidden flex items-center justify-center w-10 h-10 rounded-xl bg-gray-50 dark:bg-slate-700 text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors border border-gray-200 dark:border-slate-600 shadow-sm focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?? '#10b981' ?>]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" /></svg>
            </button>
            
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl <?= $logo_url ? 'bg-white border-gray-200' : 'bg-['.($color['warna_secondary'] ?? '#ecfdf5').'] border-['.($color['warna_primary'] ?? '#10b981').']' ?> border flex items-center justify-center shadow-sm overflow-hidden shrink-0 dark:bg-slate-800 dark:border-slate-700 transition-colors">
                    <?php if ($logo_url): ?>
                        <img src="<?= $logo_url ?>" alt="Logo Sekolah" class="w-full h-full object-cover">
                    <?php else: ?>
                        <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?? '#10b981' ?>]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    <?php endif; ?>
                </div>
                
                <div class="hidden sm:block">
                    <h2 class="font-bold dark:text-white text-gray-800 text-sm md:text-base leading-none transition-colors"><?= esc($nama_sekolah) ?></h2>
                    <p class="text-[10px] md:text-xs text-gray-500 dark:text-slate-400 font-medium mt-1 transition-colors"><?= esc($sub_judul) ?></p>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3 md:gap-4 lg:gap-6">
            <div class="hidden md:flex items-center gap-2 px-3 py-1.5 lg:px-4 lg:py-2 bg-gradient-to-r from-[<?= $color['warna_primary'] ?? '#10b981' ?>] to-[<?= $color['warna_primary'] ?? '#10b981' ?>] rounded-xl shadow-md transition-colors">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                <span class="text-xs lg:text-sm font-bold text-white" id="navbar-academic-year"><?= lang('Navbar.academic_year') ?> <?= esc($tahun_ajar) ?></span> 
                <span class="px-2 py-0.5 text-[10px] lg:text-xs bg-white text-[<?= $color['warna_primary'] ?? '#10b981' ?>] rounded-full font-black uppercase tracking-wider" id="navbar-academic-semester"><?= esc($semester) ?></span>
            </div>
            
            <div class="relative" id="notification-container">
                <button onclick="toggleNotificationPopup()" class="relative p-2 dark:bg-slate-700 dark:border-slate-600 rounded-xl bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-600 dark:text-slate-300 transition-colors cursor-pointer outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    
                    <?php if($unreadCount > 0): ?>
                        <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white dark:border-slate-700 animate-pulse transition-colors"></span> 
                    <?php endif; ?>
                </button> 

                <div id="notification-popup" class="hidden absolute right-0 mt-3 w-72 md:w-80 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-gray-100 dark:border-slate-700 z-50 overflow-hidden transition-all transform origin-top-right">
                    <div class="px-4 py-3 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-gray-50/50 dark:bg-slate-700/50 transition-colors">
                        <h3 class="font-bold text-gray-800 dark:text-white text-sm"><?= lang('Navbar.notification') ?></h3>
                        <?php if($unreadCount > 0): ?>
                            <span class="bg-[<?= $color['warna_primary'] ?? '#10b981' ?>] text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm"><?= $unreadCount ?> <?= lang('Navbar.new_badge') ?></span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="max-h-72 overflow-y-auto custom-scrollbar">
                        <?php if(empty($notifikasiList)): ?>
                            <div class="px-4 py-8 text-center">
                                <svg class="w-10 h-10 mx-auto text-gray-300 dark:text-slate-600 mb-2 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p class="text-sm text-gray-500 dark:text-slate-400 transition-colors"><?= lang('Navbar.empty_notif') ?></p>
                            </div>
                        <?php else: ?>
                            <?php foreach($notifikasiList as $notif): ?>
                                <?php 
                                    $iconColor = 'text-blue-600 dark:text-blue-400'; $bgColor = 'bg-blue-100 dark:bg-blue-900/30'; $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                                    if ($notif['tipe'] == 'success') {
                                        $iconColor = 'text-emerald-600 dark:text-emerald-400'; $bgColor = 'bg-emerald-100 dark:bg-emerald-900/30'; $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                                    } elseif ($notif['tipe'] == 'warning') {
                                        $iconColor = 'text-amber-600 dark:text-amber-400'; $bgColor = 'bg-amber-100 dark:bg-amber-900/30'; $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>';
                                    } elseif ($notif['tipe'] == 'error') {
                                        $iconColor = 'text-red-600 dark:text-red-400'; $bgColor = 'bg-red-100 dark:bg-red-900/30'; $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                                    }
                                    
                                    $link = empty($notif['link']) ? '#' : base_url($notif['link']);
                                    $waktu = Time::parse($notif['created_at'])->humanize();
                                ?>
                                <a href="<?= $link ?>" class="block px-4 py-3 border-b border-gray-50 dark:border-slate-700 <?= $notif['is_read'] ? 'bg-white dark:bg-slate-800' : 'bg-blue-50/30 dark:bg-slate-700/50' ?> hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">
                                    <div class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded-full <?= $bgColor ?> flex items-center justify-center shrink-0 transition-colors">
                                            <svg class="w-4 h-4 <?= $iconColor ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $iconSvg ?></svg>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-800 dark:text-white leading-tight transition-colors <?= $notif['is_read'] ? 'font-medium' : 'font-bold' ?>"><?= esc($notif['judul']) ?></p>
                                            <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5 line-clamp-2 transition-colors"><?= esc($notif['pesan']) ?></p>
                                            <p class="text-[10px] <?= $notif['is_read'] ? 'text-gray-400 dark:text-slate-500' : 'text-blue-500 dark:text-blue-400 font-bold' ?> mt-1 transition-colors"><?= $waktu ?></p>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                    <div class="px-4 py-2 text-center border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800 hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">
                        <a href="<?= base_url('notifikasi') ?>" class="text-xs font-semibold text-[<?= $color['warna_primary'] ?? '#10b981' ?>] hover:underline"><?= lang('Navbar.see_all') ?></a>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3 pl-3 lg:pl-4 border-l border-gray-200 dark:border-slate-700 transition-colors">
                <div class="text-right hidden sm:block">
                    <p class="text-xs md:text-sm font-bold dark:text-white text-gray-800 capitalize leading-tight transition-colors">
                        <?= esc($namaUser) ?> 
                    </p>                  
                     <p class="text-[10px] text-gray-500 dark:text-slate-400 font-semibold uppercase tracking-wider mt-0.5 transition-colors">
                        <?= session()->get('role_label') ? session()->get('role_label') : lang('Navbar.guest'); ?>
                    </p>
                </div>
                
                <div class="w-9 h-9 md:w-10 md:h-10 rounded-xl bg-[<?= $color['warna_primary'] ?? '#10b981' ?>] flex items-center justify-center text-white font-black text-sm shadow-md ring-2 ring-white dark:ring-slate-800 transition-colors">
                    <img id="navbarAvatar" class="rounded-xl w-full h-full object-cover bg-white dark:bg-slate-800" src="<?= $avatarUrl ?>" alt="Avatar" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?= $inisial ?>&background=1F7A4D&color=fff&size=100&bold=true';">
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    function toggleNotificationPopup() {
        const popup = document.getElementById('notification-popup');
        popup.classList.toggle('hidden');
    }

    document.addEventListener('click', function(event) {
        const container = document.getElementById('notification-container');
        const popup = document.getElementById('notification-popup');
        
        if (container && !container.contains(event.target)) {
            if (!popup.classList.contains('hidden')) {
                popup.classList.add('hidden');
            }
        }
    });
</script>

<?php if (session()->get('trigger_auto_backup') && session()->get('role_key') === 'admin'): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ubah bagian ini di navbar.php
        fetch('<?= base_url('admin/backup/run-pseudo-cron') ?>', {
            method: 'GET',
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(response => response.json())
        .then(data => {
            // --- PASANG CCTV DI SINI ---
            console.log("STATUS PSEUDO-CRON: ", data); 
            // ---------------------------
        
            if (data.status === 'ok' && data.notif_created) {
                const notifBtn = document.querySelector('#notification-container button');
                if (notifBtn && !notifBtn.querySelector('.bg-red-500')) {
                    notifBtn.innerHTML += '<span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white dark:border-slate-700 animate-pulse transition-colors"></span>';
                }
            }
        })
        .catch(error => console.error('ERROR PSEUDO-CRON:', error)); // Ubah log jadi error agar jelas
    });
</script>
<?php 
    // Hapus session agar mesin tidak berjalan berulang-ulang setiap kali Admin pindah halaman
    session()->remove('trigger_auto_backup'); 
?>
<?php endif; ?>