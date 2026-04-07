/**
 * File: public/assets/js/WaliKelas/pelanggaran-prestasi.js
 */

let currentPage = 'pelanggaran-prestasi'; 

// Menggunakan data dari server PHP
let pelanggaranPrestasi = {
    students: typeof serverStudents !== 'undefined' ? serverStudents : [],
    pelanggaran: typeof serverPelanggaran !== 'undefined' ? serverPelanggaran : [],
    prestasi: typeof serverPrestasi !== 'undefined' ? serverPrestasi : []
};

// Data Dummy jika DB Kosong (Untuk UI Preview)
if (pelanggaranPrestasi.students.length === 0) {
    pelanggaranPrestasi.students = [
        { id: 1, name: 'Ahmad Ridho', nisn: '12345001' },
        { id: 2, name: 'Siti Nur Azizah', nisn: '12345002' }
    ];
}

document.addEventListener('DOMContentLoaded', function() {
    renderPelanggaranPrestasi();
});

// Fungsi Helper Translasi Nilai DB ke UI
function translateCategory(cat) {
    if(cat === 'Terlambat') return LANG.cat_late;
    if(cat === 'Tidak Siap Tugas') return LANG.cat_not_ready;
    if(cat === 'Pakaian') return LANG.cat_clothes;
    if(cat === 'Bising') return LANG.cat_noise;
    if(cat === 'Lain-lain') return LANG.cat_other;
    if(cat === 'Akademik') return LANG.cat_academic;
    if(cat === 'Non-Akademik') return LANG.cat_non_academic;
    if(cat === 'Karakter') return LANG.cat_character;
    return cat;
}

function translateSeverity(sev) {
    if(severity === 'ringan') return LANG.filter_light;
    if(severity === 'sedang') return LANG.filter_medium;
    if(severity === 'berat') return LANG.filter_heavy;
    return sev;
}

function renderPelanggaranPrestasi() {
    const mainContent = document.getElementById('mainContent');
    if(!mainContent) return;

    const totalPelanggaran = pelanggaranPrestasi.pelanggaran.length;
    const totalPrestasi = pelanggaranPrestasi.prestasi.length;
    const beratCount = pelanggaranPrestasi.pelanggaran.filter(p => p.severity === 'berat').length;
    const akademikCount = pelanggaranPrestasi.prestasi.filter(p => p.category === 'Akademik').length;

    mainContent.innerHTML = `
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6">
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:-translate-y-1 transition-transform group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 dark:text-slate-500 mb-1 uppercase tracking-wider">${LANG.stat_tot_pelanggaran}</p>
                        <p class="text-3xl font-black text-red-600 dark:text-red-500">${totalPelanggaran}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:-translate-y-1 transition-transform group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 dark:text-slate-500 mb-1 uppercase tracking-wider">${LANG.stat_kasus_berat}</p>
                        <p class="text-3xl font-black text-orange-600 dark:text-orange-500">${beratCount}</p>
                        <p class="text-[10px] text-gray-400 dark:text-slate-500 mt-1 font-semibold">${totalPelanggaran > 0 ? Math.round((beratCount / totalPelanggaran) * 100) : 0}% ${LANG.stat_dari_keseluruhan}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-orange-50 dark:bg-orange-900/30 text-orange-600 dark:text-orange-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:-translate-y-1 transition-transform group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 dark:text-slate-500 mb-1 uppercase tracking-wider">${LANG.stat_tot_prestasi}</p>
                        <p class="text-3xl font-black text-green-600 dark:text-green-500">${totalPrestasi}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 hover:-translate-y-1 transition-transform group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 dark:text-slate-500 mb-1 uppercase tracking-wider">${LANG.stat_prestasi_akademik}</p>
                        <p class="text-3xl font-black text-blue-600 dark:text-blue-500">${akademikCount}</p>
                        <p class="text-[10px] text-gray-400 dark:text-slate-500 mt-1 font-semibold">${totalPrestasi > 0 ? Math.round((akademikCount / totalPrestasi) * 100) : 0}% ${LANG.stat_dari_keseluruhan}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-2 mb-6 border-b border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-t-2xl overflow-x-auto p-2 custom-scrollbar">
            <button onclick="switchPelanggaranTab(event, 'overview')" class="pp-tab-btn px-6 py-2.5 font-bold text-sm rounded-xl transition-colors bg-tema-light text-tema">
                ${LANG.tab_analitik}
            </button>
            <button onclick="switchPelanggaranTab(event, 'pelanggaran')" class="pp-tab-btn px-6 py-2.5 font-bold text-sm rounded-xl transition-colors text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-700/50 flex items-center gap-2">
                ${LANG.tab_pelanggaran}
                <span class="px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-md text-[10px]">${totalPelanggaran}</span>
            </button>
            <button onclick="switchPelanggaranTab(event, 'prestasi')" class="pp-tab-btn px-6 py-2.5 font-bold text-sm rounded-xl transition-colors text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-700/50 flex items-center gap-2">
                ${LANG.tab_prestasi}
                <span class="px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-md text-[10px]">${totalPrestasi}</span>
            </button>
        </div>

        <div id="pp-overview" class="pp-tab-content">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6">
                    <h3 class="text-lg font-black text-gray-800 dark:text-white mb-4 pb-3 border-b border-gray-100 dark:border-slate-700">⚠️ ${LANG.title_pelanggaran_terbanyak}</h3>
                    <div class="space-y-3">
                        ${getPelanggaranStats().map((stat, idx) => `
                            <div class="flex items-center justify-between p-3 bg-red-50/50 dark:bg-red-900/10 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-xl transition-colors">
                                <div class="flex-1">
                                    <p class="font-bold text-gray-800 dark:text-slate-200 text-sm">${idx + 1}. ${translateCategory(stat.category)}</p>
                                </div>
                                <span class="px-3 py-1 bg-white dark:bg-slate-800 border border-red-100 dark:border-red-800 text-red-600 dark:text-red-500 text-xs font-black rounded-lg shadow-sm">${stat.count} ${LANG.lbl_kasus}</span>
                            </div>
                        `).join('') || `<p class="text-sm text-gray-500 dark:text-slate-400 text-center py-4">${LANG.no_data}</p>`}
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6">
                    <h3 class="text-lg font-black text-gray-800 dark:text-white mb-4 pb-3 border-b border-gray-100 dark:border-slate-700">🏆 ${LANG.title_prestasi_terbanyak}</h3>
                    <div class="space-y-3">
                        ${getPrestasiByCat().map((cat, idx) => `
                            <div class="flex items-center justify-between p-3 bg-green-50/50 dark:bg-green-900/10 hover:bg-green-50 dark:hover:bg-green-900/30 rounded-xl transition-colors">
                                <div class="flex-1">
                                    <p class="font-bold text-gray-800 dark:text-slate-200 text-sm">${idx + 1}. ${translateCategory(cat.name)}</p>
                                </div>
                                <span class="px-3 py-1 bg-white dark:bg-slate-800 border border-green-100 dark:border-green-800 text-green-600 dark:text-green-500 text-xs font-black rounded-lg shadow-sm">${cat.count} ${LANG.lbl_capaian}</span>
                            </div>
                        `).join('') || `<p class="text-sm text-gray-500 dark:text-slate-400 text-center py-4">${LANG.no_data}</p>`}
                    </div>
                </div>
                
                <div class="lg:col-span-2 bg-gradient-to-r from-red-50 dark:from-red-900/20 to-orange-50 dark:to-orange-900/20 border border-red-100 dark:border-red-800/50 rounded-3xl p-6">
                    <h3 class="text-lg font-black text-red-800 dark:text-red-400 mb-4">📍 ${LANG.title_butuh_perhatian}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        ${getSiswaWithMostPelanggaran().map(student => `
                            <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-sm border border-red-50 dark:border-red-900/30 flex flex-col">
                                <span class="font-bold text-gray-800 dark:text-white truncate" title="${student.name}">${student.name}</span>
                                <div class="mt-auto pt-3 flex justify-between items-center">
                                    <span class="text-xs font-semibold text-gray-500 dark:text-slate-400">${student.count} ${LANG.lbl_kasus}</span>
                                    <span class="px-2 py-1 bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-400 text-[10px] font-black rounded-md">${student.totalPoints} ${LANG.lbl_poin_penalti}</span>
                                </div>
                            </div>
                        `).join('') || `<p class="text-sm text-gray-500 dark:text-slate-400 md:col-span-3 text-center">${LANG.no_heavy_problem}</p>`}
                    </div>
                </div>
            </div>
        </div>

        <div id="pp-pelanggaran" class="pp-tab-content hidden">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 mb-6">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-4">
                    <div class="flex-1 w-full">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <input type="text" id="searchPelanggaran" placeholder="${LANG.search_ph}" class="px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-gray-800 dark:text-slate-200 placeholder-gray-400 dark:placeholder-slate-500 focus-tema w-full transition-all" onkeyup="filterPelanggaran()">
                            <select id="filterSeverity" class="px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-gray-800 dark:text-slate-200 focus-tema w-full cursor-pointer transition-all" onchange="filterPelanggaran()">
                                <option value="">${LANG.filter_all_level}</option>
                                <option value="ringan">${LANG.filter_light}</option>
                                <option value="sedang">${LANG.filter_medium}</option>
                                <option value="berat">${LANG.filter_heavy}</option>
                            </select>
                        </div>
                    </div>
                    <button onclick="openFormPelanggaran()" class="px-6 py-3 text-white font-bold rounded-xl shadow-md shadow-red-500/30 transition-transform hover:-translate-y-0.5 bg-red-600 dark:bg-red-700 whitespace-nowrap flex items-center gap-2 w-full sm:w-auto justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        ${LANG.btn_catat_pelanggaran}
                    </button>
                </div>
            </div>
            <div id="pelanggaranList" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4"></div>
        </div>

        <div id="pp-prestasi" class="pp-tab-content hidden">
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 mb-6">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-4">
                    <div class="flex-1 w-full">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <input type="text" id="searchPrestasi" placeholder="${LANG.search_ph}" class="px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-gray-800 dark:text-slate-200 placeholder-gray-400 dark:placeholder-slate-500 focus-tema w-full transition-all" onkeyup="filterPrestasi()">
                        </div>
                    </div>
                    <button onclick="openFormPrestasi()" class="px-6 py-3 text-white font-bold rounded-xl shadow-md shadow-green-500/30 transition-transform hover:-translate-y-0.5 bg-green-600 dark:bg-green-700 whitespace-nowrap flex items-center gap-2 w-full sm:w-auto justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        ${LANG.btn_catat_prestasi}
                    </button>
                </div>
            </div>
            <div id="prestasiList" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4"></div>
        </div>
    `;

    renderPelanggaranList();
    renderPrestasiList();
}

function switchPelanggaranTab(event, tabName) {
    document.querySelectorAll('.pp-tab-content').forEach(tab => tab.classList.add('hidden'));
    
    const allBtns = document.querySelectorAll('.pp-tab-btn');
    allBtns.forEach(btn => {
        btn.classList.remove('bg-tema-light', 'text-tema');
        btn.classList.add('text-gray-500', 'dark:text-slate-400');
    });

    document.getElementById(`pp-${tabName}`).classList.remove('hidden');
    
    const activeBtn = event.currentTarget;
    activeBtn.classList.remove('text-gray-500', 'dark:text-slate-400');
    activeBtn.classList.add('bg-tema-light', 'text-tema');
}

// ============================================================================
// LOGIKA RENDER DAFTAR KARTU
// ============================================================================
function getPelanggaranStats() {
    const stats = {};
    pelanggaranPrestasi.pelanggaran.forEach(p => stats[p.category] = (stats[p.category] || 0) + 1);
    return Object.entries(stats).map(([category, count]) => ({ category, count })).sort((a, b) => b.count - a.count).slice(0, 5);
}

function getPrestasiByCat() {
    const stats = {};
    pelanggaranPrestasi.prestasi.forEach(p => stats[p.category] = (stats[p.category] || 0) + 1);
    return Object.entries(stats).map(([name, count]) => ({ name, count })).sort((a, b) => b.count - a.count);
}

function getSiswaWithMostPelanggaran() {
    const stats = {};
    pelanggaranPrestasi.pelanggaran.forEach(p => {
        if (!stats[p.studentId]) stats[p.studentId] = { name: p.studentName, count: 0, totalPoints: 0 };
        stats[p.studentId].count++;
        stats[p.studentId].totalPoints += p.points;
    });
    return Object.values(stats).sort((a, b) => b.count - a.count).slice(0, 6);
}

function renderPelanggaranList() {
    const container = document.getElementById('pelanggaranList');
    if (!container) return;

    const search = document.getElementById('searchPelanggaran')?.value.toLowerCase() || '';
    const severity = document.getElementById('filterSeverity')?.value || '';

    const filtered = pelanggaranPrestasi.pelanggaran.filter(p => {
        return (!search || p.studentName.toLowerCase().includes(search)) && 
               (!severity || p.severity === severity);
    });

    container.innerHTML = filtered.length === 0 ? `<p class="col-span-full text-center py-10 text-gray-400 dark:text-slate-500 font-bold">${LANG.no_pelanggaran_record}</p>` : '';

    filtered.forEach(pelanggaran => {
        const severityColor = pelanggaran.severity === 'berat' ? 'red' : pelanggaran.severity === 'sedang' ? 'orange' : 'yellow';
        
        container.innerHTML += `
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-5 hover:shadow-lg transition-shadow relative overflow-hidden flex flex-col h-full group">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-${severityColor}-400 dark:bg-${severityColor}-600 group-hover:h-2 transition-all"></div>
                <div class="flex justify-between items-start mb-3 mt-1">
                    <h3 class="font-black text-gray-800 dark:text-white truncate pr-2 text-lg" title="${pelanggaran.studentName}">${pelanggaran.studentName}</h3>
                    <span class="px-2.5 py-1 bg-${severityColor}-50 dark:bg-${severityColor}-900/30 text-${severityColor}-700 dark:text-${severityColor}-400 text-[10px] font-black rounded-lg uppercase tracking-wider border border-${severityColor}-100 dark:border-${severityColor}-800/50 shadow-sm">${translateSeverity(pelanggaran.severity)}</span>
                </div>
                <p class="text-sm font-bold text-gray-600 dark:text-slate-300 mb-3 flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    ${translateCategory(pelanggaran.category)}
                </p>
                <div class="bg-gray-50 dark:bg-slate-900/60 rounded-xl p-3.5 mb-4 flex-grow border border-gray-100 dark:border-slate-700/50 relative">
                    <svg class="absolute top-2 right-2 w-4 h-4 text-gray-200 dark:text-slate-700" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                    <p class="text-xs text-gray-600 dark:text-slate-400 leading-relaxed italic pr-4">"${pelanggaran.description}"</p>
                </div>
                <div class="flex items-center justify-between mt-auto pt-3 border-t border-gray-100 dark:border-slate-700">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest">${pelanggaran.date}</p>
                        <p class="text-[11px] font-semibold text-gray-500 dark:text-slate-400 mt-0.5">${LANG.lbl_poin_penalti}: <span class="text-red-600 dark:text-red-500 font-bold">-${pelanggaran.points}</span></p>
                    </div>
                    <div class="flex gap-1.5">
                        <button onclick="openFormPelanggaran(${pelanggaran.id})" class="text-blue-500 dark:text-blue-400 hover:text-white dark:hover:text-white bg-white dark:bg-slate-800 hover:bg-blue-500 dark:hover:bg-blue-600 p-2 rounded-lg transition-colors border border-blue-100 dark:border-blue-900/50 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button onclick="confirmDelete('pelanggaran', ${pelanggaran.id})" class="text-red-500 dark:text-red-400 hover:text-white dark:hover:text-white bg-white dark:bg-slate-800 hover:bg-red-500 dark:hover:bg-red-600 p-2 rounded-lg transition-colors border border-red-100 dark:border-red-900/50 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
}

function filterPelanggaran() { renderPelanggaranList(); }

function renderPrestasiList() {
    const container = document.getElementById('prestasiList');
    if (!container) return;

    const search = document.getElementById('searchPrestasi')?.value.toLowerCase() || '';

    const filtered = pelanggaranPrestasi.prestasi.filter(p => !search || p.studentName.toLowerCase().includes(search));

    container.innerHTML = filtered.length === 0 ? `<p class="col-span-full text-center py-10 text-gray-400 dark:text-slate-500 font-bold">${LANG.no_prestasi_record}</p>` : '';

    filtered.forEach(prestasi => {
        container.innerHTML += `
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-5 hover:shadow-lg transition-shadow relative overflow-hidden flex flex-col h-full group">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-green-400 dark:bg-green-600 group-hover:h-2 transition-all"></div>
                <div class="flex justify-between items-start mb-3 mt-1">
                    <h3 class="font-black text-gray-800 dark:text-white truncate pr-2 text-lg">${prestasi.studentName}</h3>
                    <span class="px-2.5 py-1 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-[10px] font-black rounded-lg uppercase tracking-wider border border-green-100 dark:border-green-800/50 shadow-sm">${translateCategory(prestasi.category)}</span>
                </div>
                <div class="bg-gray-50 dark:bg-slate-900/60 rounded-xl p-3.5 mb-4 flex-grow border border-gray-100 dark:border-slate-700/50 relative">
                    <svg class="absolute top-2 right-2 w-4 h-4 text-gray-200 dark:text-slate-700" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
                    <p class="text-sm font-bold text-gray-700 dark:text-slate-200 leading-snug mb-1 pr-4">${prestasi.achievement}</p>
                    ${prestasi.description ? `<p class="text-xs text-gray-500 dark:text-slate-400 mt-1.5 italic pr-4">"${prestasi.description}"</p>` : ''}
                </div>
                <div class="flex justify-between items-end mt-auto pt-3 border-t border-gray-100 dark:border-slate-700">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest">${prestasi.date}</p>
                        <p class="text-[11px] font-black text-green-600 dark:text-green-500 mt-0.5">🎁 ${prestasi.reward || 'Sertifikat/Apresiasi'}</p>
                    </div>
                    <div class="flex gap-1.5">
                        <button onclick="openFormPrestasi(${prestasi.id})" class="text-blue-500 dark:text-blue-400 hover:text-white dark:hover:text-white bg-white dark:bg-slate-800 hover:bg-blue-500 dark:hover:bg-blue-600 p-2 rounded-lg transition-colors border border-blue-100 dark:border-blue-900/50 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button onclick="confirmDelete('prestasi', ${prestasi.id})" class="text-red-500 dark:text-red-400 hover:text-white dark:hover:text-white bg-white dark:bg-slate-800 hover:bg-red-500 dark:hover:bg-red-600 p-2 rounded-lg transition-colors border border-red-100 dark:border-red-900/50 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
}

function filterPrestasi() { renderPrestasiList(); }

// ============================================================================
// POPUP MODALS (FORMULIR TAMBAH & UPDATE)
// ============================================================================

// 1. MODAL PELANGGARAN
function openFormPelanggaran(editId = null) {
    const isEdit = editId !== null;
    let data = { studentId: '', category: '', severity: 'ringan', status: 'Tercatat', description: '', date: new Date().toISOString().split('T')[0] };
    
    if (isEdit) {
        const found = pelanggaranPrestasi.pelanggaran.find(p => p.id === editId);
        if(found) data = { ...found };
    }

    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[70] flex items-center justify-center p-4 overflow-y-auto opacity-0 transition-opacity duration-300';
    modal.id = 'formModal';
    modal.innerHTML = `
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl max-w-lg w-full transform scale-95 transition-transform duration-300 overflow-hidden" id="modalContent">
            <div class="px-6 py-5 flex items-center justify-between text-white bg-gradient-to-r from-red-600 to-red-700 dark:from-red-700 dark:to-red-900 shadow-md">
                <div>
                    <h2 class="text-xl font-black tracking-tight">${isEdit ? LANG.modal_title_update : LANG.modal_title_add}</h2>
                    <p class="text-xs opacity-90 mt-0.5 font-medium tracking-wider uppercase">${LANG.modal_pelanggaran_subtitle}</p>
                </div>
                <button onclick="closeFormModal()" class="p-2 hover:bg-white/20 rounded-xl transition-colors border border-transparent hover:border-white/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 bg-gray-50/50 dark:bg-slate-900/50">
                <form onsubmit="savePelanggaran(event, ${editId})">
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">${LANG.form_student}</label>
                        <select id="f_student" required class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 rounded-xl font-semibold text-gray-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white dark:bg-slate-900 transition-all shadow-sm">
                            <option value="">-- ${LANG.form_student_ph} --</option>
                            ${pelanggaranPrestasi.students.map(s => `<option value="${s.id}" ${data.studentId == s.id ? 'selected' : ''}>${s.name}</option>`).join('')}
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">${LANG.form_category}</label>
                            <select id="f_category" required class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 rounded-xl font-semibold text-gray-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white dark:bg-slate-900 transition-all shadow-sm">
                                <option value="Terlambat" ${data.category === 'Terlambat' ? 'selected' : ''}>${LANG.cat_late}</option>
                                <option value="Tidak Siap Tugas" ${data.category === 'Tidak Siap Tugas' ? 'selected' : ''}>${LANG.cat_not_ready}</option>
                                <option value="Pakaian" ${data.category === 'Pakaian' ? 'selected' : ''}>${LANG.cat_clothes}</option>
                                <option value="Bising" ${data.category === 'Bising' ? 'selected' : ''}>${LANG.cat_noise}</option>
                                <option value="Lain-lain" ${data.category === 'Lain-lain' ? 'selected' : ''}>${LANG.cat_other}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">${LANG.form_severity}</label>
                            <select id="f_severity" required class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 rounded-xl font-semibold text-gray-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white dark:bg-slate-900 transition-all shadow-sm">
                                <option value="ringan" ${data.severity === 'ringan' ? 'selected' : ''}>${LANG.filter_light}</option>
                                <option value="sedang" ${data.severity === 'sedang' ? 'selected' : ''}>${LANG.filter_medium}</option>
                                <option value="berat" ${data.severity === 'berat' ? 'selected' : ''}>${LANG.filter_heavy}</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">${LANG.form_date}</label>
                            <input type="date" id="f_date" value="${data.date}" required class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 rounded-xl font-semibold text-gray-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white dark:bg-slate-900 transition-all shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">${LANG.form_action_status}</label>
                            <select id="f_status" required class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 rounded-xl font-semibold text-gray-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white dark:bg-slate-900 transition-all shadow-sm">
                                <option value="Tercatat" ${data.status === 'Tercatat' ? 'selected' : ''}>${LANG.stat_recorded}</option>
                                <option value="Sudah Ditegur" ${data.status === 'Sudah Ditegur' ? 'selected' : ''}>${LANG.stat_warned}</option>
                                <option value="Panggil Orang Tua" ${data.status === 'Panggil Orang Tua' ? 'selected' : ''}>${LANG.stat_parent_call}</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">${LANG.form_desc}</label>
                        <textarea id="f_desc" rows="3" required placeholder="${LANG.form_desc_pel_ph}" class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 rounded-xl font-medium text-gray-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white dark:bg-slate-900 resize-none transition-all shadow-sm">${data.description}</textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeFormModal()" class="px-6 py-2.5 border-2 border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors">${LANG.btn_cancel}</button>
                        <button type="submit" class="px-8 py-2.5 text-white font-bold rounded-xl shadow-lg shadow-red-500/30 hover:shadow-red-500/50 transition-all transform hover:-translate-y-0.5 bg-red-600 dark:bg-red-700">
                            ${isEdit ? LANG.btn_update : LANG.btn_save}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    setTimeout(() => { modal.classList.remove('opacity-0'); document.getElementById('modalContent').classList.remove('scale-95'); }, 10);
}

// 2. MODAL PRESTASI
function openFormPrestasi(editId = null) {
    const isEdit = editId !== null;
    let data = { studentId: '', category: 'Akademik', achievement: '', reward: '', points: 10, description: '', date: new Date().toISOString().split('T')[0] };
    
    if (isEdit) {
        const found = pelanggaranPrestasi.prestasi.find(p => p.id === editId);
        if(found) data = { ...found };
    }

    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto opacity-0 transition-opacity duration-300';
    modal.id = 'formModal';
    modal.innerHTML = `
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl max-w-lg w-full transform scale-95 transition-transform duration-300 overflow-hidden" id="modalContent">
            <div class="px-6 py-5 flex items-center justify-between text-white bg-gradient-to-r from-green-600 to-green-700 dark:from-green-700 dark:to-green-900 shadow-md">
                <div>
                    <h2 class="text-xl font-black tracking-tight">${isEdit ? LANG.modal_title_update : LANG.modal_title_add}</h2>
                    <p class="text-xs opacity-90 mt-0.5 font-medium tracking-wider uppercase">${LANG.modal_prestasi_subtitle}</p>
                </div>
                <button onclick="closeFormModal()" class="p-2 hover:bg-white/20 rounded-xl transition-colors border border-transparent hover:border-white/30">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 bg-gray-50/50 dark:bg-slate-900/50">
                <form onsubmit="savePrestasi(event, ${editId})">
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">${LANG.form_student}</label>
                        <select id="f_student" required class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 rounded-xl font-semibold text-gray-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white dark:bg-slate-900 transition-all shadow-sm">
                            <option value="">-- ${LANG.form_student_ph} --</option>
                            ${pelanggaranPrestasi.students.map(s => `<option value="${s.id}" ${data.studentId == s.id ? 'selected' : ''}>${s.name}</option>`).join('')}
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">${LANG.form_category}</label>
                            <select id="f_category" required class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 rounded-xl font-semibold text-gray-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white dark:bg-slate-900 transition-all shadow-sm">
                                <option value="Akademik" ${data.category === 'Akademik' ? 'selected' : ''}>${LANG.cat_academic}</option>
                                <option value="Non-Akademik" ${data.category === 'Non-Akademik' ? 'selected' : ''}>${LANG.cat_non_academic}</option>
                                <option value="Karakter" ${data.category === 'Karakter' ? 'selected' : ''}>${LANG.cat_character}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">${LANG.form_date}</label>
                            <input type="date" id="f_date" value="${data.date}" required class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 rounded-xl font-semibold text-gray-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white dark:bg-slate-900 transition-all shadow-sm">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">${LANG.form_achieve_name}</label>
                        <input type="text" id="f_achieve" value="${data.achievement}" required placeholder="${LANG.form_achieve_ph}" class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 rounded-xl font-semibold text-gray-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white dark:bg-slate-900 transition-all shadow-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">${LANG.form_reward}</label>
                            <input type="text" id="f_reward" value="${data.reward}" placeholder="${LANG.form_reward_ph}" class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 rounded-xl font-semibold text-gray-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white dark:bg-slate-900 transition-all shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-slate-300 mb-1.5 uppercase tracking-wider">${LANG.form_points}</label>
                            <input type="number" id="f_points" value="${data.points}" min="1" required class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 rounded-xl font-semibold text-gray-800 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent bg-white dark:bg-slate-900 transition-all shadow-sm">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2 border-t border-gray-200/50 dark:border-slate-700/50">
                        <button type="button" onclick="closeFormModal()" class="px-6 py-2.5 border-2 border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-100 dark:hover:bg-slate-800 transition-colors">${LANG.btn_cancel}</button>
                        <button type="submit" class="px-8 py-2.5 text-white font-bold rounded-xl shadow-lg shadow-green-500/30 hover:shadow-green-500/50 transition-all transform hover:-translate-y-0.5 bg-green-600 dark:bg-green-700">
                            ${isEdit ? LANG.btn_update : LANG.btn_save}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    setTimeout(() => { modal.classList.remove('opacity-0'); document.getElementById('modalContent').classList.remove('scale-95'); }, 10);
}

function closeFormModal() {
    const modal = document.getElementById('formModal');
    if(!modal) return;
    modal.classList.add('opacity-0');
    document.getElementById('modalContent').classList.add('scale-95');
    setTimeout(() => modal.remove(), 300);
}

// 3. LOGIKA SIMPAN DATA MENGGUNAKAN FETCH API
// ============================================================================

async function savePelanggaran(e, editId) {
    e.preventDefault();
    const stId = parseInt(document.getElementById('f_student').value);
    const severity = document.getElementById('f_severity').value;
    const points = severity === 'berat' ? 20 : (severity === 'sedang' ? 10 : 5);

    const payload = {
        id: editId !== null ? editId : null,
        studentId: stId,
        category: document.getElementById('f_category').value,
        severity: severity,
        description: document.getElementById('f_desc').value,
        date: document.getElementById('f_date').value,
        status: document.getElementById('f_status').value,
        points: points
    };

    try {
        const response = await fetch(BASE_URL + '/wali/pelanggaran-prestasi/save-pelanggaran', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        
        if(response.ok) {
            closeFormModal();
            showToast(LANG.succ_saved, 'red');
            setTimeout(() => window.location.reload(), 1000); 
        }
    } catch(err) {
        console.error("Gagal simpan pelanggaran:", err);
        showToast(LANG.err_save, 'red');
    }
}

async function savePrestasi(e, editId) {
    e.preventDefault();
    const stId = parseInt(document.getElementById('f_student').value);
    
    const payload = {
        id: editId !== null ? editId : null,
        studentId: stId,
        category: document.getElementById('f_category').value,
        achievement: document.getElementById('f_achieve').value,
        date: document.getElementById('f_date').value,
        points: parseInt(document.getElementById('f_points').value),
        reward: document.getElementById('f_reward').value
    };

    try {
        const response = await fetch(BASE_URL + '/wali/pelanggaran-prestasi/save-prestasi', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        
        if(response.ok) {
            closeFormModal();
            showToast(LANG.succ_saved, 'emerald');
            setTimeout(() => window.location.reload(), 1000); 
        }
    } catch(err) {
        console.error("Gagal simpan prestasi:", err);
        showToast(LANG.err_save, 'red');
    }
}

async function executeDelete(type, id) {
    try {
        const response = await fetch(BASE_URL + '/wali/pelanggaran-prestasi/delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        });
        
        if(response.ok) {
            closeDeleteModal();
            showToast(LANG.succ_deleted, 'slate');
            setTimeout(() => window.location.reload(), 1000);
        }
    } catch(err) {
        console.error("Gagal hapus data:", err);
        showToast(LANG.err_delete, 'red');
    }
}

// 4. MODAL KONFIRMASI HAPUS (CUSTOM CONFIRM)
function confirmDelete(type, id) {
    const isPelanggaran = type === 'pelanggaran';
    const color = isPelanggaran ? 'red' : 'emerald';
    const title = isPelanggaran ? LANG.del_pelanggaran_title : LANG.del_prestasi_title;
    
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[70] flex items-center justify-center p-4 opacity-0 transition-opacity duration-300';
    modal.id = 'deleteModal';
    modal.innerHTML = `
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl max-w-sm w-full p-6 text-center transform scale-95 transition-transform duration-300" id="deleteModalContent">
            <div class="w-16 h-16 rounded-full bg-${color}-50 dark:bg-${color}-900/30 text-${color}-600 dark:text-${color}-500 flex items-center justify-center mx-auto mb-4 border border-${color}-100 dark:border-${color}-800/50 shadow-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-xl font-black text-gray-800 dark:text-white mb-2">${title}</h3>
            <p class="text-sm text-gray-500 dark:text-slate-400 mb-6">${LANG.del_desc}</p>
            <div class="flex gap-3 justify-center">
                <button onclick="closeDeleteModal()" class="px-6 py-2.5 border-2 border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors">${LANG.btn_cancel}</button>
                <button onclick="executeDelete('${type}', ${id})" class="px-6 py-2.5 bg-${color}-600 text-white font-bold rounded-xl shadow-md hover:bg-${color}-700 transition-colors">${LANG.btn_yes_delete}</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    setTimeout(() => { modal.classList.remove('opacity-0'); document.getElementById('deleteModalContent').classList.remove('scale-95'); }, 10);
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    if(!modal) return;
    modal.classList.add('opacity-0');
    document.getElementById('deleteModalContent').classList.add('scale-95');
    setTimeout(() => modal.remove(), 300);
}

// 5. TOAST NOTIFICATION 
function showToast(message, colorClass = 'emerald') {
    const toast = document.createElement('div');
    
    let gradient = 'from-emerald-500 to-teal-600';
    if(colorClass === 'red') gradient = 'from-red-500 to-rose-600';
    if(colorClass === 'amber') gradient = 'from-amber-500 to-orange-600';
    if(colorClass === 'slate') gradient = 'from-slate-600 to-slate-800';

    toast.className = `fixed bottom-6 right-6 bg-gradient-to-r ${gradient} text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 z-[100] transform translate-y-20 opacity-0 transition-all duration-500`;
    toast.innerHTML = `
        <svg class="w-6 h-6 flex-shrink-0 drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="font-bold text-sm tracking-wide drop-shadow-sm">${message}</span>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => { toast.classList.remove('translate-y-20', 'opacity-0'); }, 10);
    setTimeout(() => {
        toast.classList.add('translate-y-20', 'opacity-0');
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}