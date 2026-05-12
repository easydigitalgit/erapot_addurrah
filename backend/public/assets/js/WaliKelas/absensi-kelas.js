/**
 * File: public/assets/js/WaliKelas/absensi-kelas.js
 * Semester-Based Attendance Logic
 */

let absensiData = { students: [], filter: { id_ta: '', semester: '' } };
const API_URL = (typeof BASE_URL !== 'undefined' ? BASE_URL : '') + '/wali/absensi';

document.addEventListener('DOMContentLoaded', function() { loadAbsensiFromDB(); });

async function loadAbsensiFromDB() {
    const container = document.getElementById('absensiContainer');
    if (!container) return;

    // Ambil nilai filter dari DOM
    const id_ta = document.getElementById('filterTA')?.value || '';
    const semester = document.getElementById('filterSemester')?.value || 'Ganjil';

    try {
        const response = await fetch(`${API_URL}/get-data?id_ta=${id_ta}&semester=${semester}`);
        if (!response.ok) throw new Error('Network error');
        const data = await response.json();
        
        absensiData.students = data.students || [];
        absensiData.filter = data.filter || { id_ta, semester };
        
        renderAbsensiKelas();
    } catch (error) {
        console.error("Kesalahan server:", error);
        container.innerHTML = `
            <div class="text-center py-20 text-red-500 font-bold bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-200 dark:border-red-800 mx-4">
                <p>${LANG.err_server_load}</p>
            </div>`;
    }
}

function renderAbsensiKelas() {
    const container = document.getElementById('absensiContainer');
    if (!container) return; 

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
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            ${LANG.btn_add_data}
        </button>`;
    }

    container.innerHTML = `
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">${LANG.page_title}</h1>
                <p class="text-sm text-gray-500 dark:text-slate-400 mt-1">${LANG.page_subtitle_1} <span class="font-bold text-tema">${config.class_name}</span></p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 dark:text-slate-500 mb-1 uppercase tracking-widest">${LANG.stat_total_student}</p>
                        <p class="text-4xl font-black text-gray-800 dark:text-white">${absensiData.students.length}</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background-color: ${bgPrimaryLight}; color: var(--warna-primary);">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10 mb-3">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 dark:text-slate-500 mb-1 uppercase tracking-widest">${LANG.stat_attendance}</p>
                        <p class="text-4xl font-black tracking-tight" style="color: var(--warna-hadir);">${stats.avgPercent}%</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background-color: ${bgHadirLight}; color: var(--warna-hadir);">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-2">
                    <div class="h-full rounded-full transition-all duration-1000" style="width: ${stats.avgPercent}%; background-color: var(--warna-hadir);"></div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 dark:text-slate-500 mb-1 uppercase tracking-widest">${LANG.stat_sick}</p>
                        <p class="text-4xl font-black tracking-tight" style="color: var(--warna-sakit);">${stats.totalS}</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background-color: ${bgSakitLight}; color: var(--warna-sakit);">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-slate-700 relative overflow-hidden group">
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 dark:text-slate-500 mb-1 uppercase tracking-widest">${LANG.stat_permission_alpha}</p>
                        <p class="text-4xl font-black tracking-tight" style="color: var(--warna-alpha);">${stats.totalI + stats.totalA}</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center" style="background-color: ${bgAlphaLight}; color: var(--warna-alpha);">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Bar -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-5 mb-6 flex flex-wrap items-center justify-between gap-4">
            <h2 class="font-black text-gray-800 dark:text-white text-xl flex items-center gap-3 text-tema uppercase tracking-tight">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                ${LANG.board_title}
            </h2>
            <div class="flex flex-wrap gap-3">
                <button onclick="downloadTemplate()" class="px-4 py-2.5 bg-blue-50 text-blue-600 border border-blue-100 font-bold rounded-2xl transition-all hover:bg-blue-100 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Template
                </button>
                <label class="px-4 py-2.5 bg-amber-50 text-amber-600 border border-amber-100 font-bold rounded-2xl transition-all hover:bg-amber-100 flex items-center gap-2 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Import CSV
                    <input type="file" class="hidden" onchange="importDataAbsensi(event)">
                </label>
                ${btnTambahData}
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden mb-8">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/80 dark:bg-slate-900/50 border-b-2 border-gray-100 dark:border-slate-700">
                            <th class="px-6 py-5 text-xs font-bold text-gray-500 uppercase tracking-widest">${LANG.th_student_name}</th>
                            <th class="px-4 py-5 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Hadir</th>
                            <th class="px-4 py-5 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Sakit</th>
                            <th class="px-4 py-5 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Izin</th>
                            <th class="px-4 py-5 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Alpha</th>
                            <th class="px-6 py-5 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">${LANG.th_percentage}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                        ${renderTableRows()}
                    </tbody>
                </table>
            </div>
        </div>
    `;
}

function renderTableRows() {
    if (absensiData.students.length === 0) {
        return `<tr><td colspan="6" class="text-center py-20 text-gray-400 font-bold uppercase tracking-widest">${LANG.no_data_table}</td></tr>`;
    }

    return absensiData.students.map(student => {
        const r = student.rekap || { hadir: 0, sakit: 0, izin: 0, alpha: 0 };
        const total = (parseInt(r.hadir) || 0) + (parseInt(r.sakit) || 0) + (parseInt(r.izin) || 0) + (parseInt(r.alpha) || 0);
        const percent = total > 0 ? Math.round((parseInt(r.hadir) / total) * 100) : 0;
        
        let barColor = 'var(--warna-hadir)';
        if (percent < 75) barColor = 'var(--warna-alpha)';
        else if (percent < 90) barColor = 'var(--warna-sakit)';

        return `
            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors group">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-tema flex items-center justify-center text-white font-bold text-sm shadow-sm">
                            ${student.name.charAt(0)}
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 dark:text-slate-200 text-sm">${student.name}</p>
                            <p class="text-[10px] text-gray-400 font-medium">NISN: ${student.nisn}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-4 text-center">
                    <span class="px-3 py-1 rounded-lg bg-green-50 text-green-600 text-sm font-black">${r.hadir}</span>
                </td>
                <td class="px-4 py-4 text-center">
                    <span class="px-3 py-1 rounded-lg bg-yellow-50 text-yellow-600 text-sm font-black">${r.sakit}</span>
                </td>
                <td class="px-4 py-4 text-center">
                    <span class="px-3 py-1 rounded-lg bg-purple-50 text-purple-600 text-sm font-black">${r.izin}</span>
                </td>
                <td class="px-4 py-4 text-center">
                    <span class="px-3 py-1 rounded-lg bg-red-50 text-red-600 text-sm font-black">${r.alpha}</span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex-1 bg-gray-100 dark:bg-slate-700 rounded-full h-1.5 overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-1000" style="width: ${percent}%; background-color: ${barColor};"></div>
                        </div>
                        <span class="text-xs font-black text-gray-800 dark:text-slate-200 w-8 text-right">${percent}%</span>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function calculateAbsensiStats() {
    let totalH = 0, totalS = 0, totalI = 0, totalA = 0;
    absensiData.students.forEach(s => {
        const r = s.rekap || { hadir: 0, sakit: 0, izin: 0, alpha: 0 };
        totalH += parseInt(r.hadir) || 0;
        totalS += parseInt(r.sakit) || 0;
        totalI += parseInt(r.izin) || 0;
        totalA += parseInt(r.alpha) || 0;
    });

    const totalAll = totalH + totalS + totalI + totalA;
    const avgPercent = totalAll > 0 ? Math.round((totalH / totalAll) * 100) : 0;

    return { totalS, totalI, totalA, avgPercent };
}

function openAbsensiForm() {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[70] flex items-center justify-center p-4 overflow-y-auto';
    modal.id = 'absensiModal';
    
    modal.innerHTML = `
        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl max-w-4xl w-full my-auto transform transition-all duration-300">
            <div class="px-8 py-6 flex items-center justify-between text-white bg-tema rounded-t-3xl">
                <div>
                    <h2 class="text-2xl font-black tracking-tight">${LANG.modal_edit_title}</h2>
                    <p class="text-xs opacity-90 font-medium uppercase tracking-widest mt-1">TA: ${absensiData.filter.id_ta} | Semester: ${absensiData.filter.semester}</p>
                </div>
                <button onclick="closeAbsensiForm()" class="p-2 hover:bg-white/20 rounded-xl transition-colors">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form onsubmit="submitAbsensi(event)" class="p-8">
                <div class="max-h-[60vh] overflow-y-auto custom-scrollbar border border-gray-100 dark:border-slate-700 rounded-2xl bg-gray-50/50 dark:bg-slate-900/50 divide-y divide-gray-100 dark:divide-slate-800 mb-6">
                    ${absensiData.students.map(s => {
                        const r = s.rekap || { hadir: 0, sakit: 0, izin: 0, alpha: 0 };
                        return `
                        <div class="p-6 grid grid-cols-1 lg:grid-cols-2 gap-6 items-center">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-tema flex items-center justify-center text-white font-black text-lg">
                                    ${s.name.charAt(0)}
                                </div>
                                <div>
                                    <p class="font-black text-gray-800 dark:text-slate-200">${s.name}</p>
                                    <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">NISN: ${s.nisn}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-4 gap-3">
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 ml-1">Hadir</label>
                                    <input type="number" min="0" data-sid="${s.id}" data-field="hadir" value="${r.hadir}" class="w-full px-3 py-2.5 rounded-xl border-2 border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 text-center font-black text-tema focus-tema transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 ml-1">Sakit</label>
                                    <input type="number" min="0" data-sid="${s.id}" data-field="sakit" value="${r.sakit}" class="w-full px-3 py-2.5 rounded-xl border-2 border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 text-center font-black text-yellow-500 focus:border-yellow-500 focus:ring-4 focus:ring-yellow-500/10 outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 ml-1">Izin</label>
                                    <input type="number" min="0" data-sid="${s.id}" data-field="izin" value="${r.izin}" class="w-full px-3 py-2.5 rounded-xl border-2 border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 text-center font-black text-purple-500 focus:border-purple-500 focus:ring-4 focus:ring-purple-500/10 outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-gray-400 uppercase mb-1 ml-1">Alpha</label>
                                    <input type="number" min="0" data-sid="${s.id}" data-field="alpha" value="${r.alpha}" class="w-full px-3 py-2.5 rounded-xl border-2 border-gray-100 dark:border-slate-700 bg-white dark:bg-slate-800 text-center font-black text-red-500 focus:border-red-500 focus:ring-4 focus:ring-red-500/10 outline-none transition-all">
                                </div>
                            </div>
                        </div>`;
                    }).join('')}
                </div>
                <div class="flex justify-end gap-4">
                    <button type="button" onclick="closeAbsensiForm()" class="px-8 py-3.5 border-2 border-gray-100 dark:border-slate-700 text-gray-500 font-black rounded-2xl hover:bg-gray-50 transition-all uppercase tracking-widest text-sm">${LANG.btn_cancel}</button>
                    <button type="submit" class="px-10 py-3.5 bg-tema text-white font-black rounded-2xl shadow-xl shadow-tema/20 hover:-translate-y-1 transition-all uppercase tracking-widest text-sm flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        ${LANG.btn_save}
                    </button>
                </div>
            </form>
        </div>
    `;
    document.body.appendChild(modal);
}

function closeAbsensiForm() {
    document.getElementById('absensiModal')?.remove();
}

async function submitAbsensi(event) {
    event.preventDefault();
    const records = {};
    
    document.querySelectorAll('#absensiModal input[type="number"]').forEach(input => {
        const sid = input.getAttribute('data-sid');
        const field = input.getAttribute('data-field');
        if (!records[sid]) records[sid] = {};
        records[sid][field] = parseInt(input.value) || 0;
    });

    const btn = event.target.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    btn.innerHTML = `<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ${LANG.processing}`;
    btn.disabled = true;

    try {
        const response = await fetch(`${API_URL}/save`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                id_ta: absensiData.filter.id_ta, 
                semester: absensiData.filter.semester, 
                records 
            })
        });
        
        const res = await response.json();
        if (res.success) {
            showToast(res.message, true);
            closeAbsensiForm();
            loadAbsensiFromDB();
        } else {
            showToast(res.message, false);
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    } catch (error) {
        console.error(error);
        showToast(LANG.err_save_server, false);
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
}

async function importDataAbsensi(event) {
    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('file_csv', file);
    formData.append('id_ta', absensiData.filter.id_ta);
    formData.append('semester', absensiData.filter.semester);

    try {
        const response = await fetch(`${API_URL}/import`, {
            method: 'POST',
            body: formData
        });
        const res = await response.json();
        if (res.success) {
            showToast(res.message, true);
            loadAbsensiFromDB();
        } else {
            showToast(res.message, false);
        }
    } catch (error) {
        console.error(error);
        showToast("Gagal mengimpor data", false);
    }
    event.target.value = '';
}

function downloadTemplate() {
    const url = `${API_URL}/downloadTemplate?id_ta=${absensiData.filter.id_ta}&semester=${absensiData.filter.semester}`;
    window.location.href = url;
}

function showToast(message, success = true) {
    // Implementasi toast sederhana menggunakan Swal jika tersedia, atau alert
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: success ? 'success' : 'error',
            title: success ? 'Berhasil' : 'Gagal',
            text: message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    } else {
        alert(message);
    }
}
