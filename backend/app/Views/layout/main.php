<?php
// KUNCI PERBAIKAN: Deteksi Tema dari Session atau Database sebelum HTML dimuat
$db = \Config\Database::connect();
$userId = session()->get('id') ?? session()->get('user_id');
$themePref = session()->get('theme');
$locale = session()->get('locale') ?? 'id'; // Simpan locale di variabel

if (!$themePref && $userId) {
    if ($db->tableExists('user_preferences')) {
        $prefRow = $db->table('user_preferences')->where('user_id', $userId)->get()->getRowArray();
        if ($prefRow) {
            $themePref = $prefRow['theme'];
            session()->set('theme', $themePref);
            session()->set('locale', $prefRow['bahasa']);
            $locale = $prefRow['bahasa']; // Update locale jika dari database
        }
    }
}
$themePref = $themePref ?: 'light';

// Tentukan arah teks (RTL untuk Arab, LTR untuk lainnya)
$textDirection = ($locale === 'ar') ? 'rtl' : 'ltr';
?>
<!doctype html>
<html lang="<?= $locale ?>" dir="<?= $textDirection ?>" class="h-full <?= $themePref === 'dark' ? 'dark' : '' ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - Rapor Digital SMPIT</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // KUNCI PERBAIKAN: Menginstruksikan Tailwind untuk membaca class 'dark' di tag HTML
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .modal {
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
        }

        :root {
            --warna-primary: <?= $color['warna_primary'] ?? '#10b981' ?>;
            --warna-secondary: <?= $color['warna_secondary'] ?? '#f8fafc' ?>;
        }

        #sidebar-overlay {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 99998 !important;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        #sidebar-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        #sidebar {
            z-index: 99999 !important;
            transition: transform 0.3s ease-in-out;
        }

        #sidebar.mobile-open {
            transform: translateX(0) !important;
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>

<body class="h-full bg-gray-50 dark:bg-slate-900 overflow-x-hidden relative transition-colors duration-300">
    <div id="app" class="h-full flex overflow-x-hidden w-full min-w-0 relative">
        <div id="sidebar-overlay" onclick="closeMobileSidebar()"></div>

        <?= $this->include('layout/sidebar') ?>

        <main id="main-content" class="flex-1 lg:ml-72 min-h-screen transition-all duration-300 flex flex-col min-w-0 relative z-10">
            <?= $this->include('layout/navbar') ?>

            <div class="p-4 md:p-6 lg:p-8 flex-1 w-full max-w-full overflow-x-hidden">
                <?= $this->renderSection('content') ?>
            </div>

            <?= $this->include('layout/footer') ?>
        </main>
    </div>

    <?= $this->renderSection('modals') ?>
    <?= $this->renderSection('scripts') ?>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const collapseIcon = document.getElementById('collapse-icon');
            const sidebarTexts = document.querySelectorAll('.sidebar-text');
            const appTitle = document.getElementById('sidebar-app-title');
            const schoolName = document.getElementById('sidebar-school-name');

            if (window.innerWidth >= 1024) {
                if (sidebar.classList.contains('lg:w-72')) {
                    sidebar.classList.remove('lg:w-72');
                    sidebar.classList.add('lg:w-20');
                    if (mainContent) {
                        mainContent.classList.remove('lg:ml-72');
                        mainContent.classList.add('lg:ml-20');
                    }
                    sidebarTexts.forEach(text => text.classList.add('hidden'));
                    if (appTitle) appTitle.classList.add('hidden');
                    if (schoolName) schoolName.classList.add('hidden');
                    if (collapseIcon) collapseIcon.classList.add('rotate-180');
                } else {
                    sidebar.classList.remove('lg:w-20');
                    sidebar.classList.add('lg:w-72');
                    if (mainContent) {
                        mainContent.classList.remove('lg:ml-20');
                        mainContent.classList.add('lg:ml-72');
                    }
                    sidebarTexts.forEach(text => text.classList.remove('hidden'));
                    if (appTitle) appTitle.classList.remove('hidden');
                    if (schoolName) schoolName.classList.remove('hidden');
                    if (collapseIcon) collapseIcon.classList.remove('rotate-180');
                }
            }
        }

        function openMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (sidebar) {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0', 'mobile-open');
            }
            if (overlay) overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (sidebar) {
                sidebar.classList.remove('translate-x-0', 'mobile-open');
                sidebar.classList.add('-translate-x-full');
            }
            if (overlay) overlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    </script>

    <?php if (session()->get('isLoggedIn')): ?>
        <?php
        // Ambil ID Audit Log Hak Akses TERAKHIR saat halaman ini dimuat
        $dbRadar = \Config\Database::connect();
        $role_id_radar = session()->get('role_id');
        $initLog = $dbRadar->table('audit_logs')
            ->where('action', 'UPDATE_PERMISSION')
            ->like('description', 'Role ID: ' . $role_id_radar)
            ->orderBy('id', 'DESC')
            ->get()->getRowArray();
        $initialAuditId = $initLog ? $initLog['id'] : 0;
        ?>
        <script>
            (function() {
                // Simpan ID Database saat ini
                const INITIAL_AUDIT_ID = <?= $initialAuditId ?>;
                const BASE_API_URL = "<?= rtrim(base_url(), '/') ?>";

                // Ping ke server setiap 5 detik
                setInterval(() => {
                    fetch(`${BASE_API_URL}/api/check-rbac-update?last_id=${INITIAL_AUDIT_ID}`, {
                            headers: {
                                "X-Requested-With": "XMLHttpRequest"
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.reload === true) {
                                console.warn("Sistem Mendeteksi Perubahan Hak Akses! Memuat Ulang...");

                                // Efek blur layar agar pengguna tahu sedang ada sinkronisasi
                                document.body.style.opacity = '0.4';
                                document.body.style.pointerEvents = 'none';

                                // Eksekusi Hard-Reload menembus cache browser
                                window.location.href = window.location.pathname + '?v=' + new Date().getTime();
                            }
                        })
                        .catch(err => {
                            /* Silent error agar console bersih jika koneksi putus */ });
                }, 5000);
            })();
        </script>
    <?php endif; ?>
</body>

</html>