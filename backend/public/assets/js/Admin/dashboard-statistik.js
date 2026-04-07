// Menutup sidebar otomatis di tampilan mobile saat salah satu menu diklik
document.addEventListener('click', function(e) {
    if (e.target.tagName === 'A' && e.target.closest('.submenu')) {
        if (window.innerWidth <= 1024) {
            if (typeof closeMobileSidebar === 'function') {
                closeMobileSidebar();
            }
        }
    }
});

// Fungsi untuk melebarkan/menyusutkan sidebar di mode desktop
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const collapseIcon = document.getElementById('collapse-icon');
    
    if(!sidebar || !mainContent || !collapseIcon) return; 

    if (sidebar.classList.contains('sidebar-collapsed')) {
        sidebar.classList.remove('sidebar-collapsed', 'w-20');
        sidebar.classList.add('w-72');
        mainContent.classList.remove('ml-20');
        mainContent.classList.add('ml-72');
        collapseIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>';
    } else {
        sidebar.classList.add('sidebar-collapsed', 'w-20');
        sidebar.classList.remove('w-72');
        mainContent.classList.remove('ml-72');
        mainContent.classList.add('ml-20');
        collapseIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>';
    }
}

// Fungsi untuk membuka menu sidebar di mode mobile
function openMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    if(!sidebar || !overlay) return;

    sidebar.classList.add('mobile-open');
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

// Fungsi untuk menutup menu sidebar di mode mobile
function closeMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    if(!sidebar || !overlay) return;

    sidebar.classList.remove('mobile-open');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
}

// Menjalankan fungsi otomatis saat halaman selesai dimuat
document.addEventListener('DOMContentLoaded', () => {
    // Membuka menu sidebar pertama secara default agar terlihat rapi
    const firstMenu = document.querySelector('.sidebar-menu button');
    if (firstMenu && typeof toggleMenu === 'function') {
        toggleMenu(firstMenu);
    }
});