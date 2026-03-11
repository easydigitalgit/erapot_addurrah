<?php
$uri = service('uri');
$currentSegment = $uri->getSegment(2) ? $uri->getSegment(2) : 'dashboard';
$nav_items = $navigations ?? $sidebar_menu ?? []; 
$sekolahData = function_exists('get_identitas_sekolah') ? get_identitas_sekolah() : [];

$app_name       = 'Rapor Digital';
$app_sub        = $sekolahData['nama_sekolah'] ?? 'SMPIT Ad Durrah';
$warna_primary  = $color['warna_primary'] ?? '#10b981';
$warna_secondary= $color['warna_secondary'] ?? '#ecfdf5';

$logo_db = $sekolahData['logo'] ?? 'default_logo.png';
$logo_url = ($logo_db !== 'default_logo.png' && $logo_db !== '') ? base_url('uploads/logo/' . $logo_db) : null;
?>

<style>
    :root {
        --app-primary: <?= $warna_primary ?>;
        --app-secondary: <?= $warna_secondary ?>;
    }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .text-dynamic-primary { color: var(--app-primary) !important; }
    .bg-dynamic-gradient { background-color: var(--app-primary) !important; }
    .sidebar-active { background-color: var(--app-secondary) !important; color: var(--app-primary) !important; font-weight: 600; }
    .sidebar-item:hover { background-color: var(--app-secondary) !important; color: var(--app-primary) !important; }
    .border-dynamic-left { border-left-color: var(--app-primary) !important; }
</style>

<aside id="sidebar" class="w-72 lg:w-72 bg-white dark:bg-slate-800 border-r border-gray-200 dark:border-slate-700 flex flex-col h-full fixed left-0 top-0 pointer-events-auto shadow-xl lg:shadow-sm transition-transform duration-300 -translate-x-full lg:translate-x-0">
    
    <div class="p-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl <?= $logo_url ? 'bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-600' : 'bg-dynamic-gradient' ?> flex items-center justify-center shadow-lg shrink-0 overflow-hidden">
                <?php if ($logo_url): ?>
                    <img src="<?= $logo_url ?>" alt="Logo Sekolah" class="w-full h-full object-cover">
                <?php else: ?>
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                <?php endif; ?>
            </div>
            
            <div class="sidebar-text overflow-hidden">
                <h1 id="sidebar-app-title" class="font-bold text-gray-800 dark:text-white text-sm md:text-base leading-tight truncate"><?= $app_name ?></h1>
                <p id="sidebar-school-name" class="text-[10px] md:text-xs font-medium truncate text-dynamic-primary"><?= $app_sub ?></p>
            </div>
        </div>
        <button onclick="closeMobileSidebar()" class="lg:hidden p-2 text-gray-400 dark:text-slate-400 hover:text-red-500 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto p-4 space-y-1 no-scrollbar">
        <?php if (!empty($nav_items)) : ?>
            <?php foreach ($nav_items as $key => $menu) : ?>
                <?php if ($key === 'akun_saya') continue; 
                $isActiveParent = false;
                if (!empty($menu['submenu'])) {
                    foreach ($menu['submenu'] as $sub) {
                        if (strpos(current_url(), base_url($sub['url'])) === 0) {
                            $isActiveParent = true; break;
                        }
                    }
                }
                ?>
                <div class="sidebar-menu">
                    <?php if(empty($menu['submenu'])): ?>
                         <a href="<?= base_url($menu['url']) ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-colors <?= (current_url() == base_url($menu['url'])) ? 'sidebar-active' : 'text-gray-700 dark:text-slate-300' ?>">
                            <span class="<?= (current_url() == base_url($menu['url'])) ? 'text-dynamic-primary' : 'text-dynamic-primary' ?>"><?= $menu['icon'] ?></span>
                            <span class="sidebar-text font-medium text-sm"><?= $menu['label'] ?></span>
                        </a>
                    <?php else: ?>
                        <button onclick="toggleMenu(this)" class="sidebar-item w-full flex items-center justify-between px-4 py-3 rounded-xl transition-colors group <?= $isActiveParent ? 'sidebar-active' : 'text-gray-700 dark:text-slate-300' ?>">
                            <div class="flex items-center gap-3">
                                <span class="text-dynamic-primary"><?= $menu['icon'] ?></span>
                                <span class="sidebar-text font-medium text-sm"><?= $menu['label'] ?></span>
                            </div>
                            <svg class="menu-arrow w-4 h-4 sidebar-text transition-transform duration-200 <?= $isActiveParent ? 'rotate-180 text-dynamic-primary' : 'text-gray-400 dark:text-slate-500' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div class="submenu pl-11 space-y-1 overflow-hidden transition-all duration-300 <?= $isActiveParent ? 'mt-1' : '' ?>" style="<?= $isActiveParent ? 'max-height: ' . (count($menu['submenu']) * 50) . 'px;' : 'max-height: 0px;' ?>">
                            <?php foreach ($menu['submenu'] as $sub) : ?>
                                <?php $isSubActive = (current_url() == base_url($sub['url'])); ?>
                                <a href="<?= base_url($sub['url']) ?>" class="block py-2 px-4 text-[13px] rounded-lg transition-colors <?= $isSubActive ? 'sidebar-active border-l-2 border-dynamic-left' : 'text-gray-600 dark:text-slate-400 hover:text-dynamic-primary sidebar-item' ?>">
                                    <?= $sub['label'] ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="pt-4 border-t border-gray-100 dark:border-slate-700 mt-4">
            <?php if (isset($nav_items['akun_saya'])) : 
                $akunData = $nav_items['akun_saya'];
                $isAkunActive = (current_url() == base_url($akunData['url']));
            ?>
                <a href="<?= base_url($akunData['url']) ?>" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl transition-colors <?= $isAkunActive ? 'sidebar-active' : 'text-gray-700 dark:text-slate-300' ?>">
                    <span class="text-dynamic-primary"><?= $akunData['icon'] ?></span>
                    <span class="sidebar-text font-medium text-sm"><?= $akunData['label'] ?></span>
                </a>
            <?php endif; ?>
            <a href="<?= base_url('/logout') ?>" class="flex items-center gap-3 px-4 py-3 rounded-xl text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-slate-700 transition-colors mt-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                <span class="sidebar-text font-medium text-sm"><?= lang('Sidebar.logout') ?></span>
            </a>
        </div>
    </nav>

    <div class="p-4 border-t border-gray-100 dark:border-slate-700 hidden lg:block">
        <button onclick="toggleSidebar()" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-gray-50 dark:bg-slate-700 hover:bg-gray-100 dark:hover:bg-slate-600 text-gray-600 dark:text-slate-300 transition-colors">
            <svg id="collapse-icon" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" /></svg>
            <span class="sidebar-text text-xs font-bold uppercase tracking-wider"><?= lang('Sidebar.close_menu') ?></span>
        </button>
    </div>
</aside>

<script>
function toggleMenu(button) {
    const wrapper = button.closest('.sidebar-menu');
    const submenu = wrapper.querySelector('.submenu');
    const arrow = button.querySelector('.menu-arrow');
    if (!submenu) return;

    if (!submenu.style.maxHeight || submenu.style.maxHeight === '0px') {
        submenu.style.maxHeight = submenu.scrollHeight + "px";
        submenu.classList.add('mt-1');
        if (arrow) arrow.classList.add('rotate-180');
        button.classList.add('sidebar-active');
        button.classList.remove('text-gray-700', 'dark:text-slate-300');
    } else {
        submenu.style.maxHeight = '0px';
        submenu.classList.remove('mt-1');
        if (arrow) arrow.classList.remove('rotate-180');
        button.classList.remove('sidebar-active');
        button.classList.add('text-gray-700', 'dark:text-slate-300');
    }
}
</script>