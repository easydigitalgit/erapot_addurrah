/**
 * File: public/assets/js/WaliKelas/progres-tahfidz.js
 */

const LANG = window.LANG || {
    no_data_found: 'Belum Ada Data Hafalan',
    stat_total_student: 'Total Siswa',
    stat_class_avg: 'Rata-rata Kelas',
    stat_target_done: 'Mencapai Target',
    stat_total_juz: 'Total Juz',
    lbl_juz: 'Juz',
    search_ph: 'Cari nama siswa...',
    filter_all_status: 'Semua Status',
    filter_active: 'Aktif',
    filter_inactive: 'Tidak Aktif',
    sort_highest: 'Progres Tertinggi',
    sort_az: 'Nama A-Z',
    btn_export: 'Ekspor Excel',
    badge_completed: 'Khatam',
    lbl_last_memorized: 'Hafalan Terakhir',
    lbl_ayah: 'Ayat',
    lbl_target_prog: 'Progres Target',
    lbl_last_score: 'Nilai Terakhir',
    lbl_points: 'Poin',
    btn_update_data: 'Perbarui Data',
    toast_history_info: 'Membuka Riwayat Hafalan...',
    btn_history: 'Riwayat Setoran',
    toast_update_info: 'Membuka Form Update...',
    toast_export_info: 'Mengekspor Data...'
};

let tahfizData = typeof serverTahfizData !== 'undefined' ? serverTahfizData : [];

document.addEventListener('DOMContentLoaded', function() {
    renderProgresTahfidz();
});

function renderProgresTahfidz() {
    const mainContent = document.getElementById('mainContent');
    if (!mainContent) return;

    if (!tahfizData || tahfizData.length === 0) {
        mainContent.innerHTML = `
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 p-12 flex flex-col items-center justify-center text-center shadow-sm">
            <div class="w-20 h-20 bg-gray-50 dark:bg-slate-900 rounded-full flex items-center justify-center mb-5">
                <svg class="w-10 h-10 text-gray-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-black text-gray-800 dark:text-white mb-2">${LANG.no_data_found}</h2>
            <p class="text-sm text-gray-500 dark:text-slate-400 max-w-md">Data progres tahfidz belum tersedia karena Anda belum memiliki siswa di kelas ini, atau tahun ajaran belum diatur.</p>
        </div>`;
        return;
    }

    const avgProgress = Math.round(tahfizData.reduce((a, b) => a + (b.progress || 0), 0) / tahfizData.length);
    const completedTarget = tahfizData.filter(t => t.progress >= 100).length;
    const totalJuz = tahfizData.reduce((a, b) => a + (b.juzCurrent || 0), 0);

    const bgPrimaryLight = `color-mix(in srgb, var(--warna-primary) 15%, transparent)`;

    mainContent.innerHTML = `
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 rounded-full opacity-5 group-hover:scale-150 transition-transform duration-700 ease-out bg-tema"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 dark:text-slate-500 mb-1 tracking-widest uppercase">${LANG.stat_total_student}</p>
                        <p class="text-4xl font-black text-gray-800 dark:text-white tracking-tight">${tahfizData.length}</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-inner text-tema transition-transform group-hover:rotate-6 bg-tema-light">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 rounded-full opacity-5 group-hover:scale-150 transition-transform duration-700 ease-out bg-blue-500"></div>
                <div class="flex items-center justify-between relative z-10 mb-3">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 dark:text-slate-500 mb-1 tracking-widest uppercase">${LANG.stat_class_avg}</p>
                        <p class="text-4xl font-black text-blue-600 dark:text-blue-400 tracking-tight">${avgProgress}%</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-inner text-blue-600 transition-transform group-hover:rotate-6 bg-blue-50 dark:bg-blue-900/30">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
                <div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-2 relative z-10 overflow-hidden shadow-inner">
                    <div class="h-full rounded-full bg-blue-500 transition-all duration-1000" style="width: ${avgProgress}%;"></div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 rounded-full opacity-5 group-hover:scale-150 transition-transform duration-700 ease-out bg-green-500"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 dark:text-slate-500 mb-1 tracking-widest uppercase">${LANG.stat_target_done}</p>
                        <p class="text-4xl font-black text-green-600 dark:text-green-500 tracking-tight">${completedTarget}</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-inner text-green-600 transition-transform group-hover:rotate-6 bg-green-50 dark:bg-green-900/30">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 hover:shadow-md hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 rounded-full opacity-5 group-hover:scale-150 transition-transform duration-700 ease-out bg-purple-500"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 dark:text-slate-500 mb-1 tracking-widest uppercase">${LANG.stat_total_juz}</p>
                        <p class="text-4xl font-black text-purple-600 dark:text-purple-400 tracking-tight">${totalJuz} <span class="text-sm">${LANG.lbl_juz}</span></p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-inner text-purple-600 transition-transform group-hover:rotate-6 bg-purple-50 dark:bg-purple-900/30">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 mb-6">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-4">
                <div class="flex-1 w-full">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <input type="text" id="searchTahfidz" placeholder="${LANG.search_ph}" class="px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-gray-800 dark:text-slate-200 focus-tema w-full transition-all" onkeyup="filterTahfidz()">
                        <select id="filterStatus" class="px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-gray-800 dark:text-slate-200 focus-tema w-full cursor-pointer transition-all" onchange="filterTahfidz()">
                            <option value="">${LANG.filter_all_status}</option>
                            <option value="Aktif">${LANG.filter_active}</option>
                            <option value="Tidak Aktif">${LANG.filter_inactive}</option>
                        </select>
                        <select id="sortTahfidz" class="px-4 py-3 bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-semibold text-gray-800 dark:text-slate-200 focus-tema w-full cursor-pointer transition-all" onchange="filterTahfidz()">
                            <option value="progress">${LANG.sort_highest}</option>
                            <option value="name">${LANG.sort_az}</option>
                        </select>
                    </div>
                </div>
                <button onclick="exportTahfidz()" class="px-6 py-3 bg-white dark:bg-slate-800 border-2 border-tema text-tema font-bold rounded-xl shadow-sm hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center justify-center gap-2 whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    ${LANG.btn_export}
                </button>
            </div>
        </div>

        <div id="tahfizList" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6"></div>
    `;

    renderTahfizList();
}

function renderTahfizList() {
    const container = document.getElementById('tahfizList');
    if (!container) return;

    container.innerHTML = tahfizData.map(t => {
        const statusColor = t.progress >= 75 ? 'emerald' : t.progress >= 50 ? 'amber' : 'red';
        const isKhatam = t.progress >= 100;
        const lastTest = (t.testResults && t.testResults.length > 0) ? t.testResults[t.testResults.length - 1] : 0;
        
        let avatarHtml = '';
        if (t.foto_fix && t.foto_fix !== 'null' && String(t.foto_fix).trim() !== '') {
            const cleanBaseUrl = (typeof BASE_URL !== 'undefined' ? BASE_URL : '').replace(/\/$/, '');
            const cacheBuster = '?v=' + new Date().getTime();
            
            const urlAvatars = `${cleanBaseUrl}/assets/uploads/avatars/${t.foto_fix}${cacheBuster}`;
            const urlSiswa = `${cleanBaseUrl}/uploads/siswa/${t.foto_fix}${cacheBuster}`;
            const fallbackInitial = (t.name || '?').charAt(0).toUpperCase();
            
            avatarHtml = `<img src="${urlAvatars}" class="w-full h-full object-cover" onerror="this.onerror=function(){ this.outerHTML='${fallbackInitial}'; }; this.src='${urlSiswa}';">`;
        } else {
            avatarHtml = (t.name || '?').charAt(0).toUpperCase();
        }

        return `
            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 hover:shadow-lg transition-all duration-300 relative overflow-hidden flex flex-col group">
                ${isKhatam ? `<div class="absolute -right-10 -top-10 bg-emerald-500 w-24 h-24 rotate-45 opacity-20"></div>` : ''}
                
                <div class="flex items-start gap-4 mb-5 mt-1">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-xl font-black text-white shadow-md bg-tema flex-shrink-0 overflow-hidden" style="background-image: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, transparent 100%);">
                        ${avatarHtml}
                    </div>
                    <div class="flex-1 overflow-hidden">
                        <h3 class="text-lg font-black text-gray-800 dark:text-white truncate" title="${t.name}">${t.name}</h3>
                        <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                            <span class="px-2 py-0.5 rounded-lg bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 text-[10px] font-bold uppercase tracking-widest">${t.status}</span>
                            ${isKhatam ? `<span class="px-2 py-0.5 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400 text-[10px] font-bold uppercase tracking-widest flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> ${LANG.badge_completed}</span>` : ''}
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 dark:bg-slate-900/50 rounded-2xl p-4 mb-5 border border-gray-100 dark:border-slate-700/50 flex-grow">
                    <div class="flex justify-between items-center mb-1.5">
                        <span class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">${LANG.lbl_last_memorized}</span>
                        <span class="text-xs font-black text-tema bg-tema-light dark:bg-slate-800 px-2 py-0.5 rounded-md border border-tema/20">${LANG.lbl_juz} ${t.juzCurrent} / ${t.juzTarget}</span>
                    </div>
                    <p class="font-bold text-gray-800 dark:text-slate-200 mb-4">${t.surahCurrent} <span class="text-gray-400 dark:text-slate-500 font-medium text-sm">(${LANG.lbl_ayah} ${t.ayahCurrent})</span></p>

                    <div class="flex justify-between items-end mb-1.5">
                        <span class="text-[10px] font-bold text-${statusColor}-600 dark:text-${statusColor}-500 uppercase tracking-widest">${LANG.lbl_target_prog}</span>
                        <span class="text-sm font-black text-gray-800 dark:text-white">${t.progress}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-slate-700 rounded-full h-2 shadow-inner overflow-hidden relative">
                        <div class="h-full rounded-full bg-${statusColor}-500 transition-all duration-1000 ease-out" style="width: ${t.progress}%;"></div>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-auto pt-3 border-t border-gray-100 dark:border-slate-700">
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-0.5">${LANG.lbl_last_score}</p>
                        <p class="text-sm font-black text-gray-800 dark:text-slate-200">${lastTest} <span class="text-[10px] text-gray-400 font-medium">${LANG.lbl_points}</span></p>
                    </div>
                    <div class="flex gap-1.5">
                        ${(typeof CAN_UPDATE !== 'undefined' && CAN_UPDATE) ? `
                        <button onclick="openEditModal(${t.id})" title="${LANG.btn_update_data}" class="w-9 h-9 flex items-center justify-center bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 hover:bg-tema hover:text-white hover:border-transparent text-gray-600 dark:text-slate-400 rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        ` : ''}

                        <button onclick="bukaModalRiwayat(${t.id})" title="${LANG.btn_history}" class="w-9 h-9 flex items-center justify-center bg-gray-50 dark:bg-slate-900 border border-gray-200 dark:border-slate-700 hover:bg-purple-600 hover:text-white hover:border-transparent text-gray-600 dark:text-slate-400 rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

// =========================================================================
// FUNGSI BARU: MENGELOLA MODAL RIWAYAT
// =========================================================================
// =========================================================================
// FUNGSI BARU: MENGELOLA MODAL RIWAYAT
// =========================================================================
function bukaModalRiwayat(id) {
    // PERBAIKAN BUG: Gunakan parseInt() agar tipe data (Angka vs Teks) tidak bentrok
    const student = tahfizData.find(s => parseInt(s.id) === parseInt(id));
    
    if(!student) {
        console.error("Data santri tidak ditemukan untuk ID:", id);
        return;
    }

    const modal = document.getElementById('modalRiwayat');
    const modalBackdrop = document.getElementById('modalBackdrop');
    const modalPanel = document.getElementById('modalPanel');

    if(!modal) {
        console.error("Elemen modal tidak ditemukan di View HTML!");
        return;
    }

    document.getElementById('modalNamaSantri').innerText = student.name;
    
    let avatarHtml = '';
    if (student.foto_fix && student.foto_fix !== 'null' && String(student.foto_fix).trim() !== '') {
        const cleanBaseUrl = (typeof BASE_URL !== 'undefined' ? BASE_URL : '').replace(/\/$/, '');
        const cacheBuster = '?v=' + new Date().getTime();
        
        const urlAvatars = `${cleanBaseUrl}/assets/uploads/avatars/${student.foto_fix}${cacheBuster}`;
        const urlSiswa = `${cleanBaseUrl}/uploads/siswa/${student.foto_fix}${cacheBuster}`;
        const fallbackInitial = student.name.charAt(0).toUpperCase();
        
        avatarHtml = `<img src="${urlAvatars}" class="w-full h-full object-cover" onerror="this.onerror=function(){ this.outerHTML='${fallbackInitial}'; }; this.src='${urlSiswa}';">`;
    } else {
        avatarHtml = student.name.charAt(0).toUpperCase();
    }
    document.getElementById('modalAvatar').innerHTML = avatarHtml;

    const timelineBox = document.getElementById('timelineContainer');
    
    if (!student.riwayat_lengkap || student.riwayat_lengkap.length === 0) {
        timelineBox.innerHTML = `
            <div class="p-8 text-center bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm ml-6">
                <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h4 class="text-base font-bold text-slate-700 dark:text-white">Tidak Ada Riwayat</h4>
                <p class="text-xs text-slate-500 mt-1">Santri belum menyetorkan hafalan apapun.</p>
            </div>
        `;
    } else {
        let timelineHTML = '';
        student.riwayat_lengkap.forEach(item => {
            let dotColor = item.jenis_setoran === 'Ziyadah' ? 'bg-emerald-400 ring-emerald-100 dark:ring-emerald-900/50' : 'bg-blue-400 ring-blue-100 dark:ring-blue-900/50';
            let badgeColor = 'bg-slate-50 text-slate-600 border-slate-200 dark:bg-slate-900 dark:text-slate-300 dark:border-slate-700';
            let iconStatus = '';
            
            if (item.predikat === 'Sangat Lancar') { badgeColor = 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800'; iconStatus = '🌟'; }
            else if (item.predikat === 'Lancar') { badgeColor = 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800'; iconStatus = '✨'; }
            else if (item.predikat === 'Kurang Lancar') { badgeColor = 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800'; iconStatus = '⚠️'; }
            else if (item.predikat === 'Belum Hafal') { badgeColor = 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800'; iconStatus = '🚨'; }

            let dateObj = new Date(item.tanggal);
            let tglNum = dateObj.getDate();
            let monthStr = dateObj.toLocaleDateString('id-ID', { month: 'short' });
            let yearStr = dateObj.getFullYear();

            timelineHTML += `
            <div class="relative pl-10 py-2 group">
                <div class="absolute w-4 h-4 rounded-full ${dotColor} ring-[6px] -left-[9px] top-7 shadow-sm group-hover:scale-125 transition-transform duration-300"></div>
                
                <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-slate-100/80 dark:border-slate-800 shadow-sm hover:shadow-lg hover:border-tema/30 transition-all duration-300">
                    <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 items-start sm:items-center">
                        <div class="flex flex-col items-center justify-center bg-slate-50 dark:bg-slate-800 rounded-xl px-4 py-2 border border-slate-100 dark:border-slate-700 min-w-[4rem] flex-shrink-0">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">${monthStr}</span>
                            <span class="text-xl font-black text-slate-700 dark:text-white leading-none my-0.5">${tglNum}</span>
                            <span class="text-[9px] font-bold text-slate-400">${yearStr}</span>
                        </div>
                        <div class="flex-1 w-full">
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">${item.jenis_setoran}</span>
                                <span class="text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-lg border shadow-sm ${badgeColor}">${iconStatus} ${item.predikat}</span>
                            </div>
                            <h4 class="text-lg font-bold text-slate-800 dark:text-white tracking-tight mt-1">Surah ${item.surah} <span class="text-sm font-semibold text-slate-500 ml-1">Ayat ${item.ayat}</span></h4>
                        </div>
                    </div>
                    ${item.catatan ? `
                        <div class="mt-4 ml-0 sm:ml-20 relative">
                            <div class="absolute -top-2 left-6 w-4 h-4 bg-amber-50 dark:bg-amber-900/30 rotate-45 border-l border-t border-amber-200 dark:border-amber-800"></div>
                            <div class="bg-amber-50 dark:bg-amber-900/20 p-3.5 rounded-2xl rounded-tl-none border border-amber-200 dark:border-amber-800/50 relative z-10">
                                <p class="text-xs text-amber-900 dark:text-amber-400 font-medium leading-relaxed italic flex gap-2">
                                    <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 18L14.017 21 16.133 21 18.89 15 21.646 15 21.646 9 14.017 9zM4.017 18L4.017 21 6.133 21 8.89 15 11.646 15 11.646 9 4.017 9z"></path></svg>
                                    ${item.catatan}
                                </p>
                            </div>
                        </div>
                    ` : ''}
                </div>
            </div>
            `;
        });
        timelineBox.innerHTML = timelineHTML;
    }

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden'; 
    
    setTimeout(() => {
        modalBackdrop.classList.remove('opacity-0');
        modalBackdrop.classList.add('opacity-100');
        modalPanel.classList.remove('opacity-0', 'translate-y-8', 'sm:scale-95');
        modalPanel.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
    }, 10);
}

function tutupModalRiwayat() {
    const modal = document.getElementById('modalRiwayat');
    const modalBackdrop = document.getElementById('modalBackdrop');
    const modalPanel = document.getElementById('modalPanel');

    modalBackdrop.classList.remove('opacity-100');
    modalBackdrop.classList.add('opacity-0');
    modalPanel.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
    modalPanel.classList.add('opacity-0', 'translate-y-8', 'sm:scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto'; 
    }, 300);
}

function filterTahfidz() {
    const search = document.getElementById('searchTahfidz').value.toLowerCase();
    const status = document.getElementById('filterStatus').value;
    const sort = document.getElementById('sortTahfidz').value;

    let filtered = [...(typeof serverTahfizData !== 'undefined' ? serverTahfizData : tahfizData)];

    filtered = filtered.filter(t => {
        return (!search || t.name.toLowerCase().includes(search)) &&
            (!status || t.status === status);
    });

    if (sort === 'progress') filtered.sort((a, b) => b.progress - a.progress);
    if (sort === 'name') filtered.sort((a, b) => a.name.localeCompare(b.name));

    tahfizData = filtered;
    renderTahfizList();
}

function openEditModal(id) {
    showToast(LANG.toast_update_info, "tema");
}

function exportTahfidz() {
    showToast(LANG.toast_export_info, "emerald");
}

function showToast(message, colorClass = 'emerald') {
    const toast = document.createElement('div');
    
    let gradient = 'from-emerald-500 to-teal-600';
    if(colorClass === 'blue') gradient = 'from-blue-500 to-indigo-600';
    if(colorClass === 'tema') gradient = 'from-[var(--warna-primary)] to-[var(--warna-primary)]'; 

    toast.className = `fixed bottom-6 right-6 bg-gradient-to-r ${gradient} text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center gap-3 z-[100] transform translate-y-20 opacity-0 transition-all duration-500`;
    toast.innerHTML = `
        <svg class="w-6 h-6 flex-shrink-0 drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="font-bold text-sm tracking-wide drop-shadow-sm">${message}</span>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => { toast.classList.remove('translate-y-20', 'opacity-0'); }, 10);
    setTimeout(() => {
        toast.classList.add('translate-y-20', 'opacity-0');
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}