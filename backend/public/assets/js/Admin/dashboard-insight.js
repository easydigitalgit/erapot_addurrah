// ==========================================
// SABUK PENGAMAN (FALLBACK)
// ==========================================
const LANG = window.LANG || {
    js_avg_value: 'Rata-rata Nilai',
    js_from: 'Dari',
    js_active_students: 'siswa aktif',
    js_out_of: 'dari',
    students: 'siswa',
    js_of_total: '% dari total siswa',
    js_err_fetch: 'Gagal mengambil data:'
};

const defaultConfig = {
  school_name: 'SMPIT Ad Durrah',
  app_title: 'Rapor Digital',
  academic_year: '2024/2025'
};

let config = { ...defaultConfig };

document.addEventListener('click', function(e) {
  if (e.target.tagName === 'A' && e.target.closest('.submenu')) {
    if (window.innerWidth <= 1024) {
      closeMobileSidebar();
    }
  }
});

function openMobileSidebar() {
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebar-overlay');
  if(sidebar) sidebar.classList.add('mobile-open');
  if(overlay) overlay.classList.add('active');
  document.body.style.overflow = 'hidden';
}

function closeMobileSidebar() {
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebar-overlay');
  if(sidebar) sidebar.classList.remove('mobile-open');
  if(overlay) overlay.classList.remove('active');
  document.body.style.overflow = '';
}

function updateUI() {
  const schoolName = config.school_name || defaultConfig.school_name;
  const appTitle = config.app_title || defaultConfig.app_title;
  
  const elSidebarSchool = document.getElementById('sidebar-school-name');
  if (elSidebarSchool) elSidebarSchool.textContent = schoolName;

  const elSidebarApp = document.getElementById('sidebar-app-title');
  if (elSidebarApp) elSidebarApp.textContent = appTitle;

  const elHeaderSchool = document.getElementById('header-school-name');
  if (elHeaderSchool) elHeaderSchool.textContent = schoolName;
}

updateUI();

// ==========================================
// MESIN CHART & AJAX DINAMIS
// ==========================================
let levelChartInstance = null;
let statusChartInstance = null;
let trendChartInstance = null;

function hexToRgbA(hex, alpha) {
    let c;
    if(/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)){
        c = hex.substring(1).split('');
        if(c.length === 3){
            c = [c[0], c[0], c[1], c[1], c[2], c[2]];
        }
        c = '0x' + c.join('');
        return 'rgba('+[(c>>16)&255, (c>>8)&255, c&255].join(',')+','+alpha+')';
    }
    return `rgba(16, 185, 129, ${alpha})`; 
}

function toggleTrendMode(mode, clickedButton) {
    const allButtons = document.querySelectorAll('.trend-btn');
    allButtons.forEach(btn => {
        btn.classList.remove('active', `bg-[${themePrimary}]`, 'text-white', 'border-transparent');
        btn.classList.add('bg-transparent', 'border-transparent', 'text-gray-600', 'dark:text-slate-400');
    });

    clickedButton.classList.add('active', `bg-[${themePrimary}]`, 'text-white', 'border-transparent');
    clickedButton.classList.remove('bg-transparent', 'text-gray-600', 'dark:text-slate-400');

    fetchDashboardData(); 
}

function fetchDashboardData() {
    document.querySelectorAll('.loader-overlay').forEach(el => el.classList.remove('hidden'));

    const thn = document.getElementById('filter_tahun').value;
    const sem = document.getElementById('filter_semester').value;
    const tkt = document.getElementById('filter_tingkat').value;
    const rmb = document.getElementById('filter_rombel').value;

    fetch(`${BASE_URL}/admin/dashboard-insight/get-data?tahun=${thn}&semester=${sem}&tingkat=${tkt}&rombel_id=${rmb}`, {
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(response => response.json())
    .then(res => {
        if(res.status === 'success') {
            updateStatCards(res.stats);
            renderCharts(res.level_chart, res.trend_chart, res.stats);
            
            // ---> MENGISI REKOMENDASI DINAMIS <---
            const recomGoodEl = document.getElementById('ui_recom_good');
            const recomWarnEl = document.getElementById('ui_recom_warn');
            
            if (recomGoodEl && res.recommendations) {
                recomGoodEl.textContent = res.recommendations.good;
            }
            if (recomWarnEl && res.recommendations) {
                recomWarnEl.textContent = res.recommendations.warning;
            }
            
        } else {
            console.error(LANG.js_err_fetch, res.message);
        }
    })
    .catch(err => console.error('Network error:', err))
    .finally(() => {
        document.querySelectorAll('.loader-overlay').forEach(el => el.classList.add('hidden'));
    });
}

function updateStatCards(stats) {
    // Akademik
    document.getElementById('ui_avg_sekolah').textContent = stats.avg_sekolah;
    document.getElementById('ui_avg_desc').textContent = `${LANG.js_from} ${stats.total_siswa} ${LANG.js_active_students}`;

    document.getElementById('ui_tuntas_pct').textContent = stats.tuntas_pct + '%';
    document.getElementById('ui_tuntas_desc').textContent = `${stats.tuntas} ${LANG.js_out_of} ${stats.total_siswa} ${LANG.students}`;

    document.getElementById('ui_bimbingan_total').textContent = stats.bimbingan;
    document.getElementById('ui_bimbingan_desc').textContent = `${stats.bimbingan_pct}${LANG.js_of_total}`;

    document.getElementById('ui_dist_tuntas').textContent = `${stats.tuntas} ${LANG.students}`;
    document.getElementById('ui_dist_bimbingan').textContent = `${stats.bimbingan} ${LANG.students}`;
    document.getElementById('ui_dist_remedial').textContent = `${stats.remedial} ${LANG.students}`;

    // Tahfidz, Karakter, Absensi
    const tahfidzEl = document.getElementById('ui_tahfidz_achieve');
    const tahfidzBar = document.getElementById('ui_tahfidz_bar');
    if (tahfidzEl) tahfidzEl.textContent = stats.tahfidz_pct + '%';
    if (tahfidzBar) tahfidzBar.style.width = stats.tahfidz_pct + '%';

    const charEl = document.getElementById('ui_char_excellent');
    const charBar = document.getElementById('ui_char_bar');
    const charPct = stats.total_siswa > 0 ? (stats.char_excellent / stats.total_siswa * 100) : 0;
    if (charEl) charEl.textContent = `${stats.char_excellent} ${LANG.students}`;
    if (charBar) charBar.style.width = charPct + '%';

    const attendEl = document.getElementById('ui_attendance_rate');
    const attendBar = document.getElementById('ui_attendance_bar');
    if (attendEl) attendEl.textContent = stats.attendance_rate + '%';
    if (attendBar) attendBar.style.width = stats.attendance_rate + '%';

    const notesEl = document.getElementById('ui_special_notes');
    const notesBar = document.getElementById('ui_notes_bar');
    const notesPct = stats.total_siswa > 0 ? (stats.special_notes / stats.total_siswa * 100) : 0;
    if (notesEl) notesEl.textContent = `${stats.special_notes} ${LANG.students}`;
    if (notesBar) notesBar.style.width = notesPct + '%';
}

function renderCharts(levelData, trendData, stats) {
    const mainColor = typeof themePrimary !== 'undefined' ? themePrimary : '#10b981';

    if (levelChartInstance) levelChartInstance.destroy();
    const levelCtx = document.getElementById('levelChart');
    if (levelCtx) {
        levelChartInstance = new Chart(levelCtx, {
            type: 'bar',
            data: {
                labels: levelData.labels,
                datasets: [{
                    label: LANG.js_avg_value,
                    data: levelData.data,
                    backgroundColor: hexToRgbA(mainColor, 0.8),
                    borderColor: hexToRgbA(mainColor, 1),
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: false, min: 0, max: 100, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    if (statusChartInstance) statusChartInstance.destroy();
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        statusChartInstance = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: [LANG.status_complete, LANG.status_guide, LANG.status_remedial],
                datasets: [{
                    data: [stats.tuntas, stats.bimbingan, stats.remedial],
                    backgroundColor: [ hexToRgbA(mainColor, 1), hexToRgbA(mainColor, 0.5), 'rgba(156, 163, 175, 0.7)' ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '70%'
            }
        });
    }

    if (trendChartInstance) trendChartInstance.destroy();
    const trendCtx = document.getElementById('trendChart');
    if (trendCtx) {
        trendChartInstance = new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: trendData.labels,
                datasets: [{
                    label: LANG.js_avg_value,
                    data: trendData.data,
                    borderColor: hexToRgbA(mainColor, 1),
                    backgroundColor: hexToRgbA(mainColor, 0.1),
                    borderWidth: 3,
                    fill: true, tension: 0.4, pointRadius: 5,
                    pointBackgroundColor: hexToRgbA(mainColor, 1),
                    pointBorderColor: '#fff', pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: false, min: 0, max: 100, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    fetchDashboardData();
});