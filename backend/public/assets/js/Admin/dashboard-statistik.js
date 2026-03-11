const defaultConfig = {
    school_name: 'SMPIT Ad Durrah',
    app_title: 'Rapor Digital',
    // Ambil tahun ajaran dinamis dari script PHP
    academic_year: typeof window.DYNAMIC_YEAR !== 'undefined' ? window.DYNAMIC_YEAR : '2024/2025',
    primary_color: '#1F7A4D',
    secondary_color: '#E6F4EC',
    text_color: '#1F2937',
    background_color: '#F9FAFB',
    accent_color: '#34A853'
};

let config = { ...defaultConfig };
    
document.addEventListener('click', function(e) {
    if (e.target.tagName === 'A' && e.target.closest('.submenu')) {
        if (window.innerWidth <= 1024) {
            closeMobileSidebar();
        }
    }
});

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

function openMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    if(!sidebar || !overlay) return;

    sidebar.classList.add('mobile-open');
    overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    if(!sidebar || !overlay) return;

    sidebar.classList.remove('mobile-open');
    overlay.classList.remove('active');
    document.body.style.overflow = '';
}

function updateUI() {
    const schoolName = config.school_name || defaultConfig.school_name;
    const appTitle = config.app_title || defaultConfig.app_title;
    const academicYear = config.academic_year || defaultConfig.academic_year;
    
    const els = {
        'sidebar-school-name': schoolName,
        'sidebar-app-title': appTitle,
        'header-school-name': schoolName,
        'header-academic-year': academicYear,
        'welcome-school-name': schoolName,
        // Target utama kita untuk mengubah Card
        'card-academic-year': academicYear 
    };

    for (const [id, value] of Object.entries(els)) {
        const element = document.getElementById(id);
        if (element) element.textContent = value;
    }
}

async function onConfigChange(newConfig) {
    config = { ...config, ...newConfig };
    updateUI();
}

function mapToCapabilities(cfg) {
    const setAndApply = (key, value) => {
        cfg[key] = value;
        if (window.elementSdk) window.elementSdk.setConfig({ [key]: value });
    };

    return {
        recolorables: [
            { get: () => cfg.background_color || defaultConfig.background_color, set: (v) => setAndApply('background_color', v) },
            { get: () => cfg.secondary_color || defaultConfig.secondary_color, set: (v) => setAndApply('secondary_color', v) },
            { get: () => cfg.text_color || defaultConfig.text_color, set: (v) => setAndApply('text_color', v) },
            { get: () => cfg.primary_color || defaultConfig.primary_color, set: (v) => setAndApply('primary_color', v) },
            { get: () => cfg.accent_color || defaultConfig.accent_color, set: (v) => setAndApply('accent_color', v) }
        ],
        borderables: [],
        fontEditable: undefined,
        fontSizeable: undefined
    };
}

function mapToEditPanelValues(cfg) {
    return new Map([
        ['school_name', cfg.school_name || defaultConfig.school_name],
        ['app_title', cfg.app_title || defaultConfig.app_title],
        ['academic_year', cfg.academic_year || defaultConfig.academic_year]
    ]);
}

if (window.elementSdk) {
    window.elementSdk.init({
        defaultConfig,
        onConfigChange,
        mapToCapabilities,
        mapToEditPanelValues
    });
}

document.addEventListener('DOMContentLoaded', () => {
    updateUI();
    const firstMenu = document.querySelector('.sidebar-menu button');
    if (firstMenu && typeof toggleMenu === 'function') {
        toggleMenu(firstMenu);
    }
});