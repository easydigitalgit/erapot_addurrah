/**
 * File: public/assets/js/WaliKelas/absensi-kelas.js
 */

// ==========================================
// KONFIGURASI GLOBAL & STATE
// ==========================================
let currentPage = 'absensi'; 
let absensiData = { students: [], attendance: [] };

// FUNGSI HELPER: Memaksa Waktu Selalu Menggunakan Zona Indonesia (WIB)
function getIndoDate(dateString = null) {
    if (dateString) {
        return new Date(dateString);
    }
    return new Date(new Date().toLocaleString('en-US', { timeZone: 'Asia/Jakarta' }));
}

// STATE BULAN AKTIF
let activeMonth = getIndoDate(); 
activeMonth.setDate(1); 
const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

const API_URL = (typeof BASE_URL !== 'undefined' ? BASE_URL : '') + '/wali/absensi';

document.addEventListener('DOMContentLoaded', function() { loadAbsensiFromDB(); });

function changeMonth(offset) {
    activeMonth.setMonth(activeMonth.getMonth() + offset);
    renderAbsensiKelas(); 
}

function getFilteredAttendance() {
    return absensiData.attendance.filter(att => {
        const d = getIndoDate(att.date);
        return d.getMonth() === activeMonth.getMonth() && d.getFullYear() === activeMonth.getFullYear();
    });
}

// ==========================================
// FETCH API & RENDER UTAMA
// ==========================================
async function loadAbsensiFromDB() {
    try {
        const response = await fetch(API_URL + '/get-data');
        if (!response.ok) throw new Error('Network error');
        const data = await response.json();
        
        absensiData.students = data.students || [];
        absensiData.attendance = data.attendance || [];
        
        renderAbsensiKelas();
    } catch (error) {
        console.error("Kesalahan server:", error);
        document.getElementById('mainContent').innerHTML = `
            <div class="text-center py-20 text-red-500 font-bold bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-200 dark:border-red-800 mx-4">
                <p>${LANG.err_server_load}</p>
            </div>`;
    }
}

function renderAbsensiKelas() {
    const mainContent = document.getElementById('mainContent');
    if (!mainContent) return; 

    const stats = calculateAbsensiStats();
    const config = window.sekolahConfig || {};
    
    const bgPrimaryLight = `color-mix(in srgb, var(--warna-primary) 15%, transparent)`;
    const bgHadirLight = `color-mix(in srgb, var(--warna-hadir) 15%, transparent)`;
    const bgSakitLight = `color-mix(in srgb, var(--warna-sakit) 15%, transparent)`;
    const bgAlphaLight = `color-mix(in srgb, var(--warna-alpha) 15%, transparent)`;

    let btnTambahData = '';
    if (typeof CAN_CREATE !== 'undefined' && CAN_CREATE) {
        btnTambahData = `
        <button onclick="openAbsensiForm()" class="flex-1 lg:flex-none px-6 py-3 text-white font-bold rounded-2xl transition-all shadow-lg shadow-[var(--warna-primary)]/30 hover:-translate-y-0.5 flex items-center justify-center gap-2 bg-tema">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            ${LANG.btn_add_data}
        </button>`;
    }

    mainContent.innerHTML = `
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">${LANG.page_title}</h1>
            <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">${LANG.page_subtitle_1} <span class="font-bold text-tema">${config.class_name}</span></p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 rounded-full opacity-5 group-hover:scale-150 transition-transform duration-700 ease-out bg-tema"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 dark:text-slate-500 mb-1 tracking-widest uppercase">${LANG.stat_total_student}</p>
                        <p class="text-4xl font-black text-gray-800 dark:text-white tracking-tight">${absensiData.students.length}</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-inner text-tema transition-transform group-hover:rotate-6" style="background-color: ${bgPrimaryLight};">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 rounded-full opacity-5 group-hover:scale-150 transition-transform duration-700 ease-out" style="background-color: var(--warna-hadir);"></div>
                <div class="flex items-center justify-between relative z-10 mb-3">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 dark:text-slate-500 mb-1 tracking-widest uppercase">${LANG.stat_attendance}</p>
                        <p class="text-4xl font-black tracking-tight" style="color: var(--warna-hadir);">${stats.hadirPercent}%</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-inner transition-transform group-hover:rotate-6" style="background-color: ${bgHadirLight}; color: var(--warna-hadir);">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-2 relative z-10 overflow-hidden shadow-inner">
                    <div class="h-full rounded-full transition-all duration-1000 relative" style="width: ${stats.hadirPercent}%; background-color: var(--warna-hadir);"></div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 rounded-full opacity-5 group-hover:scale-150 transition-transform duration-700 ease-out" style="background-color: var(--warna-sakit);"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 dark:text-slate-500 mb-1 tracking-widest uppercase">${LANG.stat_sick}</p>
                        <p class="text-4xl font-black tracking-tight" style="color: var(--warna-sakit);">${stats.sakit}</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-inner transition-transform group-hover:rotate-6" style="background-color: ${bgSakitLight}; color: var(--warna-sakit);">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 rounded-full opacity-5 group-hover:scale-150 transition-transform duration-700 ease-out" style="background-color: var(--warna-alpha);"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 dark:text-slate-500 mb-1 tracking-widest uppercase">${LANG.stat_permission_alpha}</p>
                        <p class="text-4xl font-black tracking-tight" style="color: var(--warna-alpha);">${stats.izin + stats.alpha}</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-inner transition-transform group-hover:rotate-6" style="background-color: ${bgAlphaLight}; color: var(--warna-alpha);">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l-2 2"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-5 sm:p-6 mb-6">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-5">
                <div class="flex-1 w-full">
                    <h2 class="font-black text-gray-800 dark:text-white text-2xl flex items-center gap-3 text-tema tracking-tight">
                        <div class="p-2 rounded-xl bg-tema-light dark:bg-slate-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        ${LANG.board_title}
                    </h2>
                </div>
                <div class="flex flex-wrap gap-3 w-full lg:w-auto justify-end">
                    <button onclick="downloadTemplate()" class="flex-1 lg:flex-none px-4 py-3 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800 font-bold rounded-2xl transition-all hover:bg-blue-100 dark:hover:bg-blue-900/40 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Template
                    </button>
                    
                    <label class="flex-1 lg:flex-none px-4 py-3 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800 font-bold rounded-2xl transition-all hover:bg-amber-100 dark:hover:bg-amber-900/40 flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Import CSV
                        <input type="file" id="importCsvFile" accept=".csv,.xls,.xlsx" class="hidden" onchange="importDataAbsensi(event)">
                    </label>

                    <button onclick="exportAbsensi()" class="flex-1 lg:flex-none px-4 py-3 bg-white dark:bg-slate-800 border-2 font-bold rounded-2xl transition-all hover:bg-gray-50 dark:hover:bg-slate-700 flex items-center justify-center gap-2 border-tema text-tema">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        ${LANG.btn_export_csv}
                    </button>
                    ${btnTambahData}
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden mb-8">
            <div class="overflow-x-auto pb-4 custom-scrollbar">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50/80 dark:bg-slate-900/50 backdrop-blur-sm border-b-2 border-gray-200 dark:border-slate-700">
                            <th class="px-6 py-5 text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest min-w-[280px] sticky left-0 z-20 bg-gray-50/95 dark:bg-slate-900/95 backdrop-blur-md">${LANG.th_student_name}</th>
                            ${generateDateHeaders()}
                            <th class="px-4 py-5 text-center"><div class="inline-flex px-3 py-1 rounded-lg shadow-sm" style="background-color: ${bgHadirLight}; color: var(--warna-hadir);"><span class="text-xs font-black">H</span></div></th>
                            <th class="px-4 py-5 text-center"><div class="inline-flex px-3 py-1 rounded-lg shadow-sm" style="background-color: ${bgSakitLight}; color: var(--warna-sakit);"><span class="text-xs font-black">S</span></div></th>
                            <th class="px-4 py-5 text-center"><div class="inline-flex px-3 py-1 rounded-lg shadow-sm" style="background-color: color-mix(in srgb, var(--warna-izin) 15%, transparent); color: var(--warna-izin);"><span class="text-xs font-black">I</span></div></th>
                            <th class="px-4 py-5 text-center"><div class="inline-flex px-3 py-1 rounded-lg shadow-sm" style="background-color: ${bgAlphaLight}; color: var(--warna-alpha);"><span class="text-xs font-black">A</span></div></th>
                            <th class="px-8 py-5 text-left text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest min-w-[240px] border-l border-gray-200 dark:border-slate-700">${LANG.th_percentage}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100/80 dark:divide-slate-700/50" id="absensiTableBody">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 relative overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    
                    <div class="flex items-center gap-3">
                        <h3 class="font-black text-gray-800 dark:text-white text-lg flex items-center gap-2 text-tema">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"/></svg>
                            ${monthNames[activeMonth.getMonth()]} ${activeMonth.getFullYear()}
                        </h3>
                        <div class="flex items-center bg-gray-100 dark:bg-slate-700 rounded-lg p-1 shadow-inner">
                            <button onclick="changeMonth(-1)" class="p-1.5 rounded-md hover:bg-white dark:hover:bg-slate-600 hover:shadow text-gray-500 dark:text-slate-400 transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg></button>
                            <button onclick="changeMonth(1)" class="p-1.5 rounded-md hover:bg-white dark:hover:bg-slate-600 hover:shadow text-gray-500 dark:text-slate-400 transition-all"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg></button>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 text-[10px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest">
                        <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full" style="background-color: var(--warna-hadir);"></span> ${LANG.cal_safe}</div>
                        <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full" style="background-color: var(--warna-alpha);"></span> ${LANG.cal_problem}</div>
                        <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-gray-200 dark:bg-slate-600"></span> ${LANG.cal_empty}</div>
                    </div>
                </div>
                <div class="grid grid-cols-7 gap-2 sm:gap-3 text-center" id="miniCalendarContainer"></div>
                <p class="text-[11px] font-medium text-gray-400 dark:text-slate-500 mt-4 text-center tracking-wide">${LANG.cal_help_text}</p>
            </div>

            <div class="lg:col-span-1 bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 bg-gradient-to-b from-white dark:from-slate-800 to-gray-50/50 dark:to-slate-900/50">
                <h3 class="font-black text-gray-800 dark:text-white text-lg mb-6 flex items-center gap-2 text-tema">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    ${LANG.eval_title}
                </h3>
                <div class="space-y-4" id="evaluationContainer"></div>
            </div>
        </div>
    `;
    
    renderAbsensiTable();
    generateMiniCalendar();
    generateEvaluation(stats);
}

// ==========================================
// KALENDER MINI & EVALUASI
// ==========================================
function generateMiniCalendar() {
    const container = document.getElementById('miniCalendarContainer');
    if(!container) return;

    const days = [LANG.day_mon, LANG.day_tue, LANG.day_wed, LANG.day_thu, LANG.day_fri, LANG.day_sat, LANG.day_sun];
    let html = days.map(d => `<div class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest pb-2">${d}</div>`).join('');

    const year = activeMonth.getFullYear();
    const month = activeMonth.getMonth();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const firstDay = new Date(year, month, 1).getDay();
    let startDay = firstDay === 0 ? 6 : firstDay - 1; 

    for(let i = 0; i < startDay; i++) {
        html += `<div class="aspect-square rounded-xl bg-gray-50/30 dark:bg-slate-800/30 border border-gray-100 dark:border-slate-700/50"></div>`;
    }

    let dateAverages = {};
    const filteredAtt = getFilteredAttendance(); 
    
    filteredAtt.forEach(att => {
        let hCount = 0, total = 0;
        Object.values(att.records).forEach(status => {
            if(status === 'H') hCount++;
            total++;
        });
        const dateObj = getIndoDate(att.date);
        dateAverages[dateObj.getDate()] = (hCount / (total || 1)) * 100;
    });

    const todayIndo = getIndoDate();
    const todayDateOnly = new Date(todayIndo.getFullYear(), todayIndo.getMonth(), todayIndo.getDate());

    for(let day = 1; day <= daysInMonth; day++) {
        const dStr = String(day).padStart(2, '0');
        const mStr = String(month + 1).padStart(2, '0');
        const fullDateStr = `${year}-${mStr}-${dStr}`;
        
        let boxStyle = '';
        let classList = 'bg-slate-50 dark:bg-slate-900 border border-dashed border-slate-200 dark:border-slate-700 text-slate-400 dark:text-slate-500 cursor-pointer hover:border-gray-400 dark:hover:border-slate-500';
        let hoverText = LANG.hover_manage;
        let onClickHtml = `onclick="handleCalendarClick('${fullDateStr}')"`;

        const cellDate = new Date(year, month, day);
        const dayOfWeek = cellDate.getDay(); 
        const isToday = (year === todayIndo.getFullYear() && month === todayIndo.getMonth() && day === todayIndo.getDate());
        const isFuture = cellDate > todayDateOnly;

        if (isFuture) {
            classList = 'bg-gray-100 dark:bg-slate-800/40 border border-transparent text-gray-300 dark:text-slate-600 cursor-not-allowed opacity-50';
            hoverText = "Belum Tersedia (Masa Depan)";
            onClickHtml = ""; 
        } else if (dayOfWeek === 0 || dayOfWeek === 6) { // Akhir Pekan (Sabtu / Minggu)
            classList = 'bg-gray-200/50 dark:bg-slate-700/30 border border-transparent text-gray-400 dark:text-slate-500 cursor-pointer opacity-70';
            hoverText = "Hari Libur (Akhir Pekan)";
        } else {
            if(dateAverages[day] !== undefined) {
                const avg = dateAverages[day];
                classList = 'cursor-pointer border';
                if(avg >= 90) {
                    boxStyle = `background-color: color-mix(in srgb, var(--warna-hadir) 20%, transparent); color: var(--warna-hadir); border-color: color-mix(in srgb, var(--warna-hadir) 40%, transparent);`;
                } else if (avg >= 70) {
                    boxStyle = `background-color: color-mix(in srgb, var(--warna-sakit) 20%, transparent); color: var(--warna-sakit); border-color: color-mix(in srgb, var(--warna-sakit) 40%, transparent);`;
                } else {
                    boxStyle = `background-color: color-mix(in srgb, var(--warna-alpha) 20%, transparent); color: var(--warna-alpha); border-color: color-mix(in srgb, var(--warna-alpha) 40%, transparent);`;
                }
                hoverText = LANG.hover_detail;

                if (isToday) {
                    classList += ' ring-4 ring-offset-2 ring-[var(--warna-primary)] dark:ring-offset-slate-800 scale-105 z-10 shadow-lg';
                    hoverText = "Hari Ini: " + hoverText;
                }
            } else {
                if (isToday) {
                    boxStyle = `background-color: color-mix(in srgb, var(--warna-primary) 10%, transparent); color: var(--warna-primary); border-color: var(--warna-primary); border-style: solid; border-width: 2px;`;
                    classList += ' scale-105 z-10 shadow-md';
                    hoverText = "Hari Ini (Belum Absen)";
                }
            }
        }

        html += `
            <div ${onClickHtml} 
                 class="aspect-square rounded-xl flex items-center justify-center text-xs sm:text-sm font-black transition-all ${isFuture ? '' : 'transform hover:scale-110 hover:z-20'} group relative ${classList}" 
                 style="${boxStyle}">
                ${day}
                <div class="absolute bottom-full mb-2 hidden group-hover:block w-max max-w-[150px] bg-gray-800 dark:bg-slate-700 text-white text-[10px] py-1.5 px-3 rounded-lg shadow-xl z-50 pointer-events-none">
                    <p class="font-bold tracking-wide">${hoverText}</p>
                </div>
            </div>
        `;
    }

    container.innerHTML = html;
}

function handleCalendarClick(dateStr) {
    const attendance = absensiData.attendance.find(a => a.date === dateStr);
    if (attendance) {
        openDetailModal(dateStr, attendance);
    } else {
        if (typeof CAN_CREATE !== 'undefined' && CAN_CREATE) {
            openAbsensiForm(dateStr); 
        } else {
            showToast(LANG.err_no_access_add, false);
        }
    }
}

function openDetailModal(dateStr, attendance) {
    const dateObj = getIndoDate(dateStr);
    const formattedDate = dateObj.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
    
    let h=0, s=0, i=0, a=0;
    let listHtml = '';
    
    absensiData.students.forEach(student => {
        const status = attendance.records[student.id] || 'A';
        if(status==='H') h++; else if(status==='S') s++; else if(status==='I') i++; else if(status==='A') a++;
        
        if (status !== 'H') {
            let statusBadge = '';
            if(status==='S') statusBadge = `<span class="px-3 py-1 rounded-lg bg-yellow-50 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-500 text-[10px] font-bold uppercase tracking-widest border border-yellow-200/50 dark:border-yellow-800/30">${LANG.badge_sick}</span>`;
            if(status==='I') statusBadge = `<span class="px-3 py-1 rounded-lg bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 text-[10px] font-bold uppercase tracking-widest border border-purple-200/50 dark:border-purple-800/30">${LANG.badge_permit}</span>`;
            if(status==='A') statusBadge = `<span class="px-3 py-1 rounded-lg bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-[10px] font-bold uppercase tracking-widest border border-red-200/50 dark:border-red-800/30">${LANG.badge_alpha}</span>`;
            
            let avatarHtml = '';
            if (student.foto_fix && student.foto_fix !== 'null' && String(student.foto_fix).trim() !== '') {
                const cleanBaseUrl = (typeof BASE_URL !== 'undefined' ? BASE_URL : '').replace(/\/$/, '');
                const cacheBuster = '?v=' + new Date().getTime();
                
                const urlAvatars = `${cleanBaseUrl}/assets/uploads/avatars/${student.foto_fix}${cacheBuster}`;
                const urlSiswa = `${cleanBaseUrl}/uploads/siswa/${student.foto_fix}${cacheBuster}`;
                const fallbackHTML = `<div class=\\'w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold text-white bg-tema shadow-sm\\'>${student.name.charAt(0).toUpperCase()}</div>`;
                
                // Coba load folder avatars -> gagal? coba folder siswa -> gagal? tampilkan inisial
                avatarHtml = `<img src="${urlAvatars}" class="w-9 h-9 rounded-xl object-cover shadow-sm" onerror="this.onerror=function(){ this.outerHTML='${fallbackHTML}'; }; this.src='${urlSiswa}';">`;
            } else {
                avatarHtml = `<div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold text-white bg-tema shadow-sm">${student.name.charAt(0).toUpperCase()}</div>`;
            }

            listHtml += `
                <div class="flex items-center justify-between p-3.5 border-b border-gray-100 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                    <div class="flex items-center gap-3.5">
                        ${avatarHtml}
                        <span class="text-sm font-bold text-gray-800 dark:text-slate-200">${student.name}</span>
                    </div>
                    ${statusBadge}
                </div>
            `;
        }
    });

    if (listHtml === '') {
        listHtml = `<div class="p-8 text-center text-sm font-bold text-gray-400 dark:text-slate-500">${LANG.all_present} 🎉</div>`;
    }

    let btnEditHtml = '';
    if (typeof CAN_CREATE !== 'undefined' && CAN_CREATE) {
        btnEditHtml = `
        <button type="button" onclick="closeDetailModal(); setTimeout(() => openAbsensiForm('${dateStr}'), 300);" class="px-6 py-3 text-white font-bold rounded-2xl shadow-lg shadow-[var(--warna-primary)]/30 transition-all bg-tema flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            ${LANG.btn_edit_data}
        </button>`;
    }

    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[60] flex items-center justify-center p-4 overflow-y-auto opacity-0 transition-opacity duration-300';
    modal.id = 'detailModal';
    modal.innerHTML = `
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl max-w-lg w-full my-auto transform scale-95 transition-transform duration-300 overflow-hidden" id="detailModalContent">
            <div class="px-6 py-5 flex items-center justify-between text-white bg-tema" style="background-image: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 100%);">
                <div>
                    <h2 class="text-xl font-black tracking-tight">${LANG.modal_detail_title}</h2>
                    <p class="text-xs opacity-90 mt-0.5 font-medium tracking-wider uppercase">${formattedDate}</p>
                </div>
                <button onclick="closeDetailModal()" class="p-2 hover:bg-white/20 rounded-xl transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 bg-gray-50/50 dark:bg-slate-900/50">
                <div class="grid grid-cols-4 gap-3 mb-5">
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800/30 p-3 rounded-2xl text-center shadow-sm">
                        <p class="text-[9px] font-bold text-green-600 dark:text-green-500 uppercase tracking-widest mb-1">H</p>
                        <p class="text-2xl font-black text-green-700 dark:text-green-400">${h}</p>
                    </div>
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-100 dark:border-yellow-800/30 p-3 rounded-2xl text-center shadow-sm">
                        <p class="text-[9px] font-bold text-yellow-600 dark:text-yellow-500 uppercase tracking-widest mb-1">S</p>
                        <p class="text-2xl font-black text-yellow-700 dark:text-yellow-400">${s}</p>
                    </div>
                    <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-100 dark:border-purple-800/30 p-3 rounded-2xl text-center shadow-sm">
                        <p class="text-[9px] font-bold text-purple-600 dark:text-purple-500 uppercase tracking-widest mb-1">I</p>
                        <p class="text-2xl font-black text-purple-700 dark:text-purple-400">${i}</p>
                    </div>
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800/30 p-3 rounded-2xl text-center shadow-sm">
                        <p class="text-[9px] font-bold text-red-600 dark:text-red-500 uppercase tracking-widest mb-1">A</p>
                        <p class="text-2xl font-black text-red-700 dark:text-red-400">${a}</p>
                    </div>
                </div>

                <p class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 pl-1">${LANG.absent_list_title}</p>
                <div class="mb-6 max-h-[35vh] overflow-y-auto bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl shadow-sm custom-scrollbar">
                    ${listHtml}
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeDetailModal()" class="px-6 py-3 border-2 border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-300 font-bold rounded-2xl hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">${LANG.btn_close}</button>
                    ${btnEditHtml}
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        document.getElementById('detailModalContent').classList.remove('scale-95');
    }, 10);
}

function closeDetailModal() {
    const modal = document.getElementById('detailModal');
    if(!modal) return;
    modal.classList.add('opacity-0');
    document.getElementById('detailModalContent').classList.add('scale-95');
    setTimeout(() => modal.remove(), 300);
}

function generateEvaluation(stats) {
    const container = document.getElementById('evaluationContainer');
    if(!container) return;

    let html = '';
    
    if(stats.hadirPercent >= 95) {
        html += `<div class="flex gap-4 items-start p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/30">
            <div class="mt-0.5 p-2 rounded-xl bg-emerald-100 dark:bg-emerald-900 text-emerald-600 dark:text-emerald-400 shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></div>
            <div>
                <p class="font-bold text-emerald-800 dark:text-emerald-500 mb-1">${LANG.eval_great_title}</p>
                <p class="text-xs font-medium text-emerald-700 dark:text-emerald-400/80 leading-relaxed">${LANG.eval_great_desc.replace('{percent}', stats.hadirPercent)}</p>
            </div>
        </div>`;
    } else if(stats.hadirPercent <= 80) {
        html += `<div class="flex gap-4 items-start p-4 rounded-2xl bg-red-50 dark:bg-red-900/20 border border-red-100 dark:border-red-800/30">
            <div class="mt-0.5 p-2 rounded-xl bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400 shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></div>
            <div>
                <p class="font-bold text-red-800 dark:text-red-500 mb-1">${LANG.eval_warn_title}</p>
                <p class="text-xs font-medium text-red-700 dark:text-red-400/80 leading-relaxed">${LANG.eval_warn_desc.replace('{percent}', stats.hadirPercent)}</p>
            </div>
        </div>`;
    }

    let alphaStudents = [];
    const filteredAtt = getFilteredAttendance(); 
    
    absensiData.students.forEach(student => {
        let alphaCount = 0;
        filteredAtt.forEach(att => {
            if((att.records[student.id] || 'A') === 'A') alphaCount++;
        });
        if(alphaCount >= 2) alphaStudents.push(student.name);
    });

    if(alphaStudents.length > 0) {
        html += `<div class="flex gap-4 items-start p-4 rounded-2xl bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-800/30 mt-3">
            <div class="mt-0.5 p-2 rounded-xl bg-amber-100 dark:bg-amber-900 text-amber-600 dark:text-amber-500 shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            <div>
                <p class="font-bold text-amber-800 dark:text-amber-500 mb-1">${LANG.eval_attn_title}</p>
                <p class="text-xs font-medium text-amber-700 dark:text-amber-400/80 leading-relaxed"><span class="font-bold">${alphaStudents.length} ${LANG.eval_attn_desc_1}</span> ${LANG.eval_attn_desc_2}</p>
            </div>
        </div>`;
    } else {
        html += `<div class="flex gap-4 items-start p-4 rounded-2xl bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800/30 mt-3">
            <div class="mt-0.5 p-2 rounded-xl bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-500 shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg></div>
            <div>
                <p class="font-bold text-blue-800 dark:text-blue-500 mb-1">${LANG.eval_good_title}</p>
                <p class="text-xs font-medium text-blue-700 dark:text-blue-400/80 leading-relaxed">${LANG.eval_good_desc}</p>
            </div>
        </div>`;
    }

    container.innerHTML = html;
}

function calculateAbsensiStats() {
    let hadir = 0, sakit = 0, izin = 0, alpha = 0;
    const filteredAtt = getFilteredAttendance();
    const totalRecords = absensiData.students.length * filteredAtt.length || 1;
    
    filteredAtt.forEach(att => {
        Object.values(att.records).forEach(st => {
            if (st === 'H') hadir++; else if (st === 'S') sakit++; else if (st === 'I') izin++; else if (st === 'A') alpha++;
        });
    });
    return { hadir, sakit, izin, alpha, hadirPercent: Math.round((hadir / totalRecords) * 100) || 0 };
}

function generateDateHeaders() {
    const filteredAtt = getFilteredAttendance();
    
    if (filteredAtt.length === 0) return `<th class="px-4 py-5 text-center text-xs font-medium text-gray-400 dark:text-slate-500 border-b border-gray-200 dark:border-slate-700">Belum Ada Absen Bulan Ini</th>`;
    
    return filteredAtt.map(att => {
        const date = getIndoDate(att.date);
        return `<th class="px-2 py-4 text-center border-b border-gray-200 dark:border-slate-700">
            <div class="flex flex-col items-center justify-center p-2 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-gray-100 dark:border-slate-700 min-w-[3.5rem] group cursor-default">
                <span class="text-[9px] font-bold text-gray-400 dark:text-slate-500 uppercase mb-0.5">${date.toLocaleDateString('id-ID', { weekday: 'short' })}</span>
                <span class="text-lg font-black text-gray-800 dark:text-slate-200">${date.getDate()}</span>
            </div>
        </th>`;
    }).join('');
}

function getBtnStyle(status) {
    let colorVar = '--warna-alpha';
    if (status === 'H') colorVar = '--warna-hadir';
    if (status === 'S') colorVar = '--warna-sakit';
    if (status === 'I') colorVar = '--warna-izin';
    return `color: var(${colorVar}); background-color: color-mix(in srgb, var(${colorVar}) 15%, transparent); border: 1px solid color-mix(in srgb, var(${colorVar}) 30%, transparent);`;
}

function renderAbsensiTable() {
    const tableBody = document.getElementById('absensiTableBody');
    if(!tableBody) return;
    
    tableBody.innerHTML = '';
    const filteredAtt = getFilteredAttendance();

    if (absensiData.students.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="100%" class="text-center py-20 text-gray-500 dark:text-slate-400 font-medium">${LANG.no_data_table}</td></tr>`;
        return;
    }

    absensiData.students.forEach((student) => {
        const row = document.createElement('tr');
        row.className = 'group hover:bg-white dark:hover:bg-slate-700/50 hover:shadow-md transition-all duration-300 relative z-10 hover:z-20';

        let totalH = 0, totalS = 0, totalI = 0, totalA = 0;
        
        filteredAtt.forEach(att => {
            const st = att.records[student.id] || 'A';
            if (st === 'H') totalH++; else if (st === 'S') totalS++; else if (st === 'I') totalI++; else if (st === 'A') totalA++;
        });
        
        const totalHari = filteredAtt.length || 1;
        const persen = Math.round((totalH / totalHari) * 100);
        
        let barColor = 'var(--warna-alpha)';
        let predikatText = LANG.pred_warning || 'Kurang';
        let gradientColor = 'from-[var(--warna-alpha)] to-rose-600';

        if (filteredAtt.length === 0) {
            barColor = 'var(--warna-primary)';
            predikatText = 'Siap Diisi';
            gradientColor = 'from-gray-300 to-gray-400';
        } else if (persen >= 90) {
            barColor = 'var(--warna-hadir)'; predikatText = LANG.pred_discipline || 'Disiplin';
            gradientColor = 'from-[var(--warna-hadir)] to-[#22c55e]'; 
        } else if (persen >= 75) {
            barColor = 'var(--warna-sakit)'; predikatText = LANG.pred_enough || 'Cukup';
            gradientColor = 'from-blue-400 to-indigo-500';
        }

        let avatarHtml = '';
        if (student.foto_fix && student.foto_fix !== 'null' && String(student.foto_fix).trim() !== '') {
            const cleanBaseUrl = (typeof BASE_URL !== 'undefined' ? BASE_URL : '').replace(/\/$/, '');
            const cacheBuster = '?v=' + new Date().getTime();
            
            const urlAvatars = `${cleanBaseUrl}/assets/uploads/avatars/${student.foto_fix}${cacheBuster}`;
            const urlSiswa = `${cleanBaseUrl}/uploads/siswa/${student.foto_fix}${cacheBuster}`;
            const fallbackHTML = `<div class=\\'w-10 h-10 rounded-2xl flex items-center justify-center text-sm font-bold text-white bg-tema shadow-sm\\'>${student.name.charAt(0).toUpperCase()}</div>`;
            
            // Coba load folder avatars -> gagal? coba folder siswa -> gagal? tampilkan inisial
            avatarHtml = `<img src="${urlAvatars}" class="w-10 h-10 rounded-2xl object-cover shadow-sm border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800" onerror="this.onerror=function(){ this.outerHTML='${fallbackHTML}'; }; this.src='${urlSiswa}';">`;
        } else {
            avatarHtml = `<div class="w-10 h-10 rounded-2xl flex items-center justify-center text-sm font-bold text-white bg-tema shadow-sm">${student.name.charAt(0).toUpperCase()}</div>`;
        }

        let cellsHtml = `
            <td class="px-6 py-4 sticky left-0 group-hover:bg-white dark:group-hover:bg-slate-800 bg-gray-50/20 dark:bg-slate-900/50 backdrop-blur-md border-r border-transparent group-hover:border-gray-50 dark:group-hover:border-slate-700">
                <div class="flex items-center gap-4">
                    ${avatarHtml}
                    <div class="flex flex-col">
                        <div class="font-bold text-gray-800 dark:text-slate-200 text-sm truncate max-w-[150px]">${student.name}</div>
                        <span class="text-[10px] text-gray-400 dark:text-slate-500 font-semibold mt-1">NISN: ${student.nisn}</span>
                    </div>
                </div>
            </td>`;

        if (filteredAtt.length === 0) {
            cellsHtml += `<td class="px-2 py-4 text-center text-xs font-medium text-gray-400 dark:text-slate-500 italic">Silakan Tambah Data</td>`;
        } else {
            filteredAtt.forEach(att => {
                const status = att.records[student.id] || 'A';
                const actionClick = (typeof CAN_CREATE !== 'undefined' && CAN_CREATE) ? `onclick="changeAbsensi(${student.id}, '${att.date}')"` : '';
                const cursorStyle = (typeof CAN_CREATE !== 'undefined' && CAN_CREATE) ? 'hover:scale-110 cursor-pointer' : 'cursor-not-allowed opacity-80';

                cellsHtml += `<td class="px-2 py-4 text-center">
                    <button ${actionClick} class="w-9 h-9 rounded-lg font-bold text-sm transition-all focus:outline-none shadow-sm ${cursorStyle}" style="${getBtnStyle(status)}">${status}</button>
                </td>`;
            });
        }

        cellsHtml += `
            <td class="px-3 py-4 text-center border-l border-gray-100/80 dark:border-slate-700/50">
                <div class="mx-auto w-7 h-7 flex items-center justify-center rounded-md font-bold text-xs" style="color: var(--warna-hadir); background-color: color-mix(in srgb, var(--warna-hadir) 15%, transparent);">${totalH}</div>
            </td>
            <td class="px-3 py-4 text-center">
                <div class="mx-auto w-7 h-7 flex items-center justify-center rounded-md font-bold text-xs" style="color: var(--warna-sakit); background-color: color-mix(in srgb, var(--warna-sakit) 15%, transparent);">${totalS}</div>
            </td>
            <td class="px-3 py-4 text-center">
                <div class="mx-auto w-7 h-7 flex items-center justify-center rounded-md font-bold text-xs" style="color: var(--warna-izin); background-color: color-mix(in srgb, var(--warna-izin) 15%, transparent);">${totalI}</div>
            </td>
            <td class="px-3 py-4 text-center">
                <div class="mx-auto w-7 h-7 flex items-center justify-center rounded-md font-bold text-xs" style="color: var(--warna-alpha); background-color: color-mix(in srgb, var(--warna-alpha) 15%, transparent);">${totalA}</div>
            </td>
            <td class="px-6 py-4 border-l border-gray-100/80 dark:border-slate-700/50">
                <div class="flex flex-col gap-1.5 w-full min-w-[150px]">
                    <div class="flex justify-between items-center w-full">
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded" style="color: ${barColor}; background-color: color-mix(in srgb, ${barColor} 10%, transparent);">${predikatText}</span>
                        <span class="text-xs font-black text-gray-800 dark:text-slate-200">${filteredAtt.length === 0 ? '0' : persen}%</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-2">
                        <div class="h-2 rounded-full bg-gradient-to-r ${gradientColor}" style="width: ${filteredAtt.length === 0 ? '0' : persen}%;"></div>
                    </div>
                </div>
            </td>`;

        row.innerHTML = cellsHtml;
        tableBody.appendChild(row);
    });
}

// ==========================================
// FORM ABSENSI (INPUT/EDIT)
// ==========================================
function openAbsensiForm(prefillDate = null) {
    const isEditing = typeof prefillDate === 'string';
    let targetDate = '';
    
    const todayIndo = getIndoDate();
    let yT = todayIndo.getFullYear();
    let mT = String(todayIndo.getMonth() + 1).padStart(2, '0');
    let dT = String(todayIndo.getDate()).padStart(2, '0');
    const maxDateStr = `${yT}-${mT}-${dT}`; 
    
    if (isEditing) {
        targetDate = prefillDate;
    } else {
        let y = activeMonth.getFullYear();
        let m = String(activeMonth.getMonth() + 1).padStart(2, '0');
        let d = "01"; 
        
        if (y === todayIndo.getFullYear() && activeMonth.getMonth() === todayIndo.getMonth()) {
            d = String(todayIndo.getDate()).padStart(2, '0'); 
        }
        targetDate = `${y}-${m}-${d}`;
    }

    if (targetDate > maxDateStr) {
        targetDate = maxDateStr;
    }

    const existingData = absensiData.attendance.find(a => a.date === targetDate);

    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[70] flex items-center justify-center p-4 overflow-y-auto opacity-0 transition-opacity duration-300';
    modal.id = 'absensiModal';
    modal.innerHTML = `
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl max-w-xl w-full transform scale-95 transition-transform duration-300" id="absensiModalContent">
            <div class="px-6 py-5 flex items-center justify-between text-white bg-tema">
                <h2 class="text-xl font-bold">${isEditing ? LANG.modal_edit_title : LANG.modal_add_title}</h2>
                <button onclick="closeAbsensiForm()" class="p-2 hover:bg-white/20 rounded-xl transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
            </div>
            <div class="p-6 bg-gray-50/50 dark:bg-slate-900/50">
                <form onsubmit="submitAbsensi(event)">
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">${LANG.select_date}</label>
                        <input type="date" id="absensiDate" value="${targetDate}" max="${maxDateStr}" ${isEditing ? 'readonly' : ''} required class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-200 focus-tema transition-all">
                    </div>
                    
                    <div class="mb-6 max-h-[40vh] overflow-y-auto border border-gray-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 divide-y divide-gray-100 dark:divide-slate-700 custom-scrollbar">
                        ${absensiData.students.map(s => {
                            const sStatus = existingData ? (existingData.records[s.id] || 'A') : 'H';
                            return `
                            <div class="flex items-center justify-between p-4 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                                <label class="flex items-center gap-3 flex-1 cursor-pointer">
                                    <input type="checkbox" checked class="w-4 h-4 rounded text-tema bg-white dark:bg-slate-900 border-gray-300 dark:border-slate-600 focus:ring-tema" data-student-id="${s.id}">
                                    <span class="text-sm font-bold text-gray-800 dark:text-slate-200">${s.name}</span>
                                </label>
                                <select data-status-select="${s.id}" class="px-4 py-2 border border-gray-200 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-900 text-sm font-bold text-gray-800 dark:text-slate-200 focus-tema cursor-pointer">
                                    <option value="H" ${sStatus === 'H' ? 'selected' : ''}>${LANG.opt_present}</option>
                                    <option value="S" ${sStatus === 'S' ? 'selected' : ''}>${LANG.opt_sick}</option>
                                    <option value="I" ${sStatus === 'I' ? 'selected' : ''}>${LANG.opt_permit}</option>
                                    <option value="A" ${sStatus === 'A' ? 'selected' : ''}>${LANG.opt_alpha}</option>
                                </select>
                            </div>`
                        }).join('')}
                    </div>
                    
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeAbsensiForm()" class="px-6 py-2.5 border-2 border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">${LANG.btn_cancel}</button>
                        <button type="submit" class="px-6 py-2.5 text-white font-bold rounded-xl bg-tema shadow-lg shadow-[var(--warna-primary)]/30 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            ${LANG.btn_save}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    `;
    document.body.appendChild(modal);

    setTimeout(() => {
        modal.classList.remove('opacity-0');
        document.getElementById('absensiModalContent').classList.remove('scale-95');
    }, 10);
}

function closeAbsensiForm() {
    const modal = document.getElementById('absensiModal');
    if(!modal) return;
    modal.classList.add('opacity-0');
    document.getElementById('absensiModalContent').classList.add('scale-95');
    setTimeout(() => modal.remove(), 300);
}

async function submitAbsensi(event) {
    event.preventDefault();
    const date = document.getElementById('absensiDate').value;
    const newRecords = {};

    document.querySelectorAll('#absensiModal input[type="checkbox"]:checked').forEach(cb => {
        const studentId = parseInt(cb.getAttribute('data-student-id'));
        newRecords[studentId] = document.querySelector(`[data-status-select="${studentId}"]`).value;
    });

    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = `<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ${LANG.processing}`;
    submitBtn.disabled = true;

    try {
        const response = await fetch(API_URL + '/save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ date: date, records: newRecords })
        });
        
        const res = await response.json();

        if (response.ok && res.success) {
            await loadAbsensiFromDB();
            closeAbsensiForm();
            showToast(res.message, true);
        } else {
            showToast(res.message || LANG.err_update_fail, false);
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    } catch (error) {
        console.error("Gagal simpan:", error);
        showToast(LANG.err_save_server, false);
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }
}

async function changeAbsensi(studentId, date) {
    const statusOrder = ['H', 'S', 'I', 'A'];
    let attendance = absensiData.attendance.find(a => a.date === date);
    if (!attendance) return;

    const currentStatus = attendance.records[studentId] || 'A';
    const nextStatus = statusOrder[(statusOrder.indexOf(currentStatus) + 1) % statusOrder.length];
    attendance.records[studentId] = nextStatus;
    
    renderAbsensiKelas(); 

    const singleRecord = {};
    singleRecord[studentId] = nextStatus;

    try {
        const response = await fetch(API_URL + '/save', { 
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ date: date, records: singleRecord })
        });
        if(response.ok) {
            showToast(LANG.succ_update, true);
        } else {
            showToast(LANG.err_update_fail, false);
        }
    } catch (error) {
        console.error("Gagal update data:", error);
        showToast(LANG.err_save_server, false);
    }
}

// ==========================================
// FITUR EKSPOR & TEMPLATE EXCEL CERDAS (Melewati Hari Libur)
// ==========================================

function isWeekend(dateString) {
    const date = getIndoDate(dateString);
    const day = date.getDay();
    return day === 0 || day === 6; // 0 = Minggu, 6 = Sabtu
}

function createExcelHTML(isTemplate) {
    const filteredAtt = getFilteredAttendance();
    const monthName = monthNames[activeMonth.getMonth()];
    const year = activeMonth.getFullYear();
    const className = window.sekolahConfig.class_name;
    const daysInMonth = new Date(year, activeMonth.getMonth() + 1, 0).getDate();

    // Mengumpulkan list tanggal aktif (Excluding Sabtu & Minggu)
    let dateList = [];
    if (isTemplate) {
        for(let day = 1; day <= daysInMonth; day++) {
            let d = `${year}-${String(activeMonth.getMonth() + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            if (!isWeekend(d)) {
                dateList.push(d);
            }
        }
    } else {
        filteredAtt.forEach(att => {
            dateList.push(att.date);
        });
    }

    // Hitung total kolom dinamis
    const totalCols = isTemplate ? (2 + dateList.length) : (2 + dateList.length + 5);

    let html = `<html xmlns:x="urn:schemas-microsoft-com:office:excel">
    <head>
        <meta charset="utf-8">
        <style>
            .title { font-size: 16pt; font-weight: bold; text-align: center; color: #1e293b; }
            .subtitle { font-size: 12pt; font-weight: bold; text-align: center; color: #64748b; }
            .panduan { background-color: #f8fafc; color: #334155; border: 1px solid #cbd5e1; padding: 15px; font-size: 11pt; text-align: left; vertical-align: top; }
            .hdr { background-color: #10b981; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000; vertical-align: middle; padding: 10px; }
            .cell { border: 1px solid #000000; vertical-align: middle; padding: 5px; }
            .center { text-align: center; border: 1px solid #000000; padding: 5px; }
            .h { background-color: #d1fae5; color: #065f46; text-align: center; font-weight: bold; border: 1px solid #000000; }
            .s { background-color: #fef3c7; color: #92400e; text-align: center; font-weight: bold; border: 1px solid #000000; }
            .i { background-color: #f3e8ff; color: #6b21a8; text-align: center; font-weight: bold; border: 1px solid #000000; }
            .a { background-color: #fee2e2; color: #991b1b; text-align: center; font-weight: bold; border: 1px solid #000000; }
        </style>
    </head>
    <body>
        <table>
            <tr><td colspan="${totalCols}" class="title">REKAP ABSENSI KELAS ${className}</td></tr>
            <tr><td colspan="${totalCols}" class="subtitle">Bulan: ${monthName} ${year}</td></tr>
            <tr><td colspan="${totalCols}"></td></tr>`;

    if (isTemplate) {
        html += `
            <tr>
                <td colspan="${totalCols}" class="panduan">
                    <b>📋 PANDUAN PENGISIAN ABSENSI:</b><br>
                    1. Isi kolom di bawah tanggal dengan salah satu kode huruf berikut:<br>
                    &#160;&#160;&#160;• <b>H</b> = Hadir<br>
                    &#160;&#160;&#160;• <b>S</b> = Sakit<br>
                    &#160;&#160;&#160;• <b>I</b> = Izin<br>
                    &#160;&#160;&#160;• <b>A</b> = Alpha (Tanpa Keterangan)<br>
                    2. Hari Sabtu, Ahad, dan Hari Libur lainnya <b>Dilewati (Tidak ada di dalam kolom)</b> agar mempermudah pengisian.<br>
                    3. Kosongkan sel/kotak jika belum ada absensi pada tanggal tersebut.<br>
                    4. <b>PENTING:</b> Setelah selesai diisi, lakukan <b>File -> Save As</b>, lalu pastikan memilih format <b>"CSV (Comma delimited) (*.csv)"</b> sebelum diunggah/diimpor ke aplikasi.
                </td>
            </tr>
            <tr><td colspan="${totalCols}"></td></tr>`;
    }

    html += `
            <tr>
                <td class="hdr" style="width: 100px;">NISN</td>
                <td class="hdr" style="width: 250px;">Nama Siswa</td>`;

    // Render Headers (Dates)
    dateList.forEach(dateStr => {
        const headerValue = isTemplate ? `&#39;${dateStr}` : dateStr;
        const headerStyle = isTemplate
            ? `width: 80px; mso-number-format:'\\@';`
            : `width: 80px;`;
        html += `<td class="hdr" style="${headerStyle}">${headerValue}</td>`;
    });

    if (!isTemplate) {
        html += `<td class="hdr">Hadir</td><td class="hdr">Sakit</td><td class="hdr">Izin</td><td class="hdr">Alpha</td><td class="hdr">%</td>`;
    }

    html += `</tr>`;

    absensiData.students.forEach(student => {
        html += `<tr>
            <td class="cell" style="mso-number-format:'\\@';">${student.nisn}</td>
            <td class="cell">${student.name}</td>`;
        
        let th=0, ts=0, ti=0, ta=0;

        if (isTemplate) {
            dateList.forEach(() => { html += `<td class="cell"></td>`; });
        } else {
            dateList.forEach(dateStr => {
                const att = filteredAtt.find(a => a.date === dateStr);
                const status = att ? (att.records[student.id] || 'A') : 'A';
                
                let cssClass = 'cell center';
                if(status === 'H') { cssClass = 'h'; th++; }
                else if(status === 'S') { cssClass = 's'; ts++; }
                else if(status === 'I') { cssClass = 'i'; ti++; }
                else if(status === 'A') { cssClass = 'a'; ta++; }

                html += `<td class="${cssClass}">${status}</td>`;
            });
            const total = dateList.length || 1;
            const persen = Math.round((th / total) * 100);
            html += `<td class="center h">${th}</td><td class="center s">${ts}</td><td class="center i">${ti}</td><td class="center a">${ta}</td><td class="center font-bold">${persen}%</td>`;
        }
        html += `</tr>`;
    });

    html += `</table></body></html>`;
    return html;
}

function exportAbsensi() {
    const filteredAtt = getFilteredAttendance();
    if (!filteredAtt || filteredAtt.length === 0) return alert(LANG.err_export_empty);
    
    const html = createExcelHTML(false);
    const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = `Rekap_${monthNames[activeMonth.getMonth()]}_${activeMonth.getFullYear()}_${window.sekolahConfig.class_name}.xls`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function downloadTemplate() {
    if (!absensiData || absensiData.students.length === 0) return alert("Belum ada data siswa.");
    
    const html = createExcelHTML(true);
    const blob = new Blob([html], { type: 'application/vnd.ms-excel' });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = `Template_${monthNames[activeMonth.getMonth()]}_${activeMonth.getFullYear()}_${window.sekolahConfig.class_name}.xls`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

async function importDataAbsensi(event) {
    const file = event.target.files[0];
    const inputElement = event.target;
    if (!file) return;

    if (file.name.endsWith('.xls') || file.name.endsWith('.xlsx')) {
        alert("PENTING!\n\nAnda mengunggah file format Excel biasa (" + file.name + ").\nSistem membutuhkan format CSV murni.\n\nCara mengatasi:\n1. Buka file Anda di Excel.\n2. Klik File -> Save As.\n3. Pilih format 'CSV (Comma delimited)'.\n4. Simpan, lalu unggah file CSV tersebut ke sini.");
        inputElement.value = ""; 
        return;
    }

    const formData = new FormData();
    formData.append('file_csv', file);

    showToast("Sedang mensinkronisasi data absensi...", true);

    try {
        const response = await fetch(API_URL + '/import', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const res = await response.json();

        if (response.ok && res.success) {
            showToast(res.message || "Data berhasil diimpor!", true);
            await loadAbsensiFromDB(); 
        } else {
            showToast(res.message || "Gagal mengimpor data.", false);
        }
    } catch (error) {
        console.error("Error import:", error);
        showToast("Terjadi kesalahan pada server saat impor.", false);
    } finally {
        inputElement.value = ""; 
    }
}

// ==========================================
// TOAST NOTIFICATION
// ==========================================
function showToast(message, isSuccess = true) {
    const toast = document.createElement('div');
    const bgColor = isSuccess ? 'bg-gradient-to-r from-emerald-500 to-teal-600' : 'bg-gradient-to-r from-amber-500 to-orange-600';
    const icon = isSuccess 
        ? `<svg class="w-6 h-6 text-white drop-shadow-md flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`
        : `<svg class="w-6 h-6 text-white drop-shadow-md flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`;

    toast.className = `fixed top-8 right-8 flex items-center gap-3 px-6 py-4 rounded-2xl shadow-2xl text-white transform transition-all duration-500 translate-x-[120%] opacity-0 z-[999] ${bgColor}`;
    toast.innerHTML = `${icon} <p class="font-bold tracking-wide text-sm drop-shadow-sm">${message}</p>`;
    
    document.body.appendChild(toast);

    setTimeout(() => toast.classList.remove('translate-x-[120%]', 'opacity-0'), 50);
    setTimeout(() => {
        toast.classList.add('translate-x-[120%]', 'opacity-0');
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}
