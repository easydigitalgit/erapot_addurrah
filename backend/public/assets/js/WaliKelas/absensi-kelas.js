let currentPage = 'absensi'; 
let absensiData = {
    students: [],
    attendance: []
};

const API_URL = (typeof BASE_URL !== 'undefined' ? BASE_URL : '') + '/wali/absensi';

document.addEventListener('DOMContentLoaded', function() {
    loadAbsensiFromDB();
});

async function loadAbsensiFromDB() {
    try {
        const response = await fetch(API_URL + '/get-data');
        if (!response.ok) throw new Error('Network error');
        const data = await response.json();
        
        absensiData.students = data.students || [];
        absensiData.attendance = data.attendance || [];
        
        renderAbsensiKelas();
    } catch (error) {
        console.error("Ralat server:", error);
        document.getElementById('mainContent').innerHTML = `
            <div class="text-center py-20 text-red-500 font-bold bg-red-50 rounded-xl border border-red-200 shadow-sm mx-4">
                <p>Gagal memuat data dari server. Pastikan akaun adalah Wali Kelas yang sah.</p>
            </div>`;
    }
}

function renderAbsensiKelas() {
    const mainContent = document.getElementById('mainContent');
    if (!mainContent) return; 

    const stats = calculateAbsensiStats();
    
    const bgPrimaryLight = `color-mix(in srgb, var(--warna-primary) 12%, transparent)`;
    const bgHadirLight = `color-mix(in srgb, var(--warna-hadir) 12%, transparent)`;
    const bgSakitLight = `color-mix(in srgb, var(--warna-sakit) 12%, transparent)`;
    const bgAlphaLight = `color-mix(in srgb, var(--warna-alpha) 12%, transparent)`;

    mainContent.innerHTML = `
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 rounded-full opacity-5 group-hover:scale-150 transition-transform duration-700 ease-out bg-tema"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 mb-1 tracking-widest uppercase">Jumlah Pelajar</p>
                        <p class="text-4xl font-black text-gray-800 tracking-tight">${absensiData.students.length}</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-inner text-tema transition-transform group-hover:rotate-6" style="background-color: ${bgPrimaryLight};">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 rounded-full opacity-5 group-hover:scale-150 transition-transform duration-700 ease-out" style="background-color: var(--warna-hadir);"></div>
                <div class="flex items-center justify-between relative z-10 mb-3">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 mb-1 tracking-widest uppercase">Kehadiran</p>
                        <p class="text-4xl font-black tracking-tight" style="color: var(--warna-hadir);">${stats.hadirPercent}%</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-inner transition-transform group-hover:rotate-6" style="background-color: ${bgHadirLight}; color: var(--warna-hadir);">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2 relative z-10 overflow-hidden shadow-inner">
                    <div class="h-full rounded-full transition-all duration-1000 relative" style="width: ${stats.hadirPercent}%; background-color: var(--warna-hadir);"></div>
                </div>
            </div>
            
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 rounded-full opacity-5 group-hover:scale-150 transition-transform duration-700 ease-out" style="background-color: var(--warna-sakit);"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 mb-1 tracking-widest uppercase">Sakit</p>
                        <p class="text-4xl font-black tracking-tight" style="color: var(--warna-sakit);">${stats.sakit}</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-inner transition-transform group-hover:rotate-6" style="background-color: ${bgSakitLight}; color: var(--warna-sakit);">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 rounded-full opacity-5 group-hover:scale-150 transition-transform duration-700 ease-out" style="background-color: var(--warna-alpha);"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 mb-1 tracking-widest uppercase">Izin & Alpha</p>
                        <p class="text-4xl font-black tracking-tight" style="color: var(--warna-alpha);">${stats.izin + stats.alpha}</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-inner transition-transform group-hover:rotate-6" style="background-color: ${bgAlphaLight}; color: var(--warna-alpha);">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-5">
                <div class="flex-1 w-full">
                    <h2 class="font-black text-gray-800 text-2xl flex items-center gap-3 text-tema tracking-tight">
                        <div class="p-2 rounded-xl bg-tema-light">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        Papan Rekod
                    </h2>
                </div>
                <div class="flex gap-3 w-full lg:w-auto">
                    <button onclick="exportAbsensi()" class="flex-1 lg:flex-none px-6 py-3 bg-white border-2 font-bold rounded-2xl transition-all hover:bg-gray-50 flex items-center justify-center gap-2 border-tema text-tema">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Eksport CSV
                    </button>
                    <button onclick="openAbsensiForm()" class="flex-1 lg:flex-none px-6 py-3 text-white font-bold rounded-2xl transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2 bg-tema">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Tambah Data
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="overflow-x-auto pb-4 custom-scrollbar">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead>
                        <tr class="bg-gray-50/80 backdrop-blur-sm border-b-2 border-gray-200">
                            <th class="px-6 py-5 text-xs font-bold text-gray-500 uppercase tracking-widest min-w-[280px] sticky left-0 z-20 bg-gray-50/95 backdrop-blur-md">Nama Pelajar</th>
                            ${generateDateHeaders()}
                            <th class="px-4 py-5 text-center"><div class="inline-flex px-3 py-1 rounded-lg" style="background-color: ${bgHadirLight}; color: var(--warna-hadir);"><span class="text-xs font-bold">H</span></div></th>
                            <th class="px-4 py-5 text-center"><div class="inline-flex px-3 py-1 rounded-lg" style="background-color: ${bgSakitLight}; color: var(--warna-sakit);"><span class="text-xs font-bold">S</span></div></th>
                            <th class="px-4 py-5 text-center"><div class="inline-flex px-3 py-1 rounded-lg" style="background-color: color-mix(in srgb, var(--warna-izin) 12%, transparent); color: var(--warna-izin);"><span class="text-xs font-bold">I</span></div></th>
                            <th class="px-4 py-5 text-center"><div class="inline-flex px-3 py-1 rounded-lg" style="background-color: ${bgAlphaLight}; color: var(--warna-alpha);"><span class="text-xs font-bold">A</span></div></th>
                            <th class="px-8 py-5 text-left text-xs font-bold text-gray-500 uppercase tracking-widest min-w-[240px] border-l border-gray-200">Prestasi Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100/80" id="absensiTableBody">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <h3 class="font-black text-gray-800 text-lg flex items-center gap-2 text-tema">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"/></svg>
                        Kalendar Kelas
                    </h3>
                    <div class="flex flex-wrap items-center gap-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest">
                        <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full" style="background-color: var(--warna-hadir);"></span> Aktif</div>
                        <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full" style="background-color: var(--warna-sakit);"></span> Bermasalah</div>
                        <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-gray-200"></span> Kosong</div>
                    </div>
                </div>
                <div class="grid grid-cols-7 gap-2 sm:gap-3 text-center" id="miniCalendarContainer"></div>
                <p class="text-[11px] font-medium text-gray-400 mt-4 text-center tracking-wide">Klik pada tarikh untuk lihat perincian atau tambah data baru.</p>
            </div>

            <div class="lg:col-span-1 bg-white rounded-3xl shadow-sm border border-gray-100 p-6 bg-gradient-to-b from-white to-gray-50/50">
                <h3 class="font-black text-gray-800 text-lg mb-6 flex items-center gap-2 text-tema">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Penilaian Sistem
                </h3>
                <div class="space-y-4" id="evaluationContainer"></div>
            </div>
        </div>
    `;
    
    renderAbsensiTable();
    generateMiniCalendar();
    generateEvaluation(stats);
}

function generateMiniCalendar() {
    const container = document.getElementById('miniCalendarContainer');
    if(!container) return;

    const days = ['Isn', 'Sel', 'Rab', 'Kha', 'Jum', 'Sab', 'Aha'];
    let html = days.map(d => `<div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest pb-2">${d}</div>`).join('');

    const date = new Date();
    const year = date.getFullYear();
    const month = date.getMonth();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const firstDay = new Date(year, month, 1).getDay();
    let startDay = firstDay === 0 ? 6 : firstDay - 1; 

    for(let i = 0; i < startDay; i++) {
        html += `<div class="aspect-square rounded-xl bg-gray-50/30 border border-gray-100"></div>`;
    }

    let dateAverages = {};
    absensiData.attendance.forEach(att => {
        let hCount = 0, total = 0;
        Object.values(att.records).forEach(status => {
            if(status === 'H') hCount++;
            total++;
        });
        const dateObj = new Date(att.date);
        if(dateObj.getMonth() === month && dateObj.getFullYear() === year) {
            dateAverages[dateObj.getDate()] = (hCount / (total || 1)) * 100;
        }
    });

    for(let day = 1; day <= daysInMonth; day++) {
        const fullDateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        
        let boxStyle = `background-color: #f8fafc; color: #cbd5e1; border: 1px dashed #e2e8f0;`; 
        let hoverText = `Klik Untuk Tambah`;
        let cursorClass = 'cursor-pointer hover:border-gray-300';

        if(dateAverages[day] !== undefined) {
            const avg = dateAverages[day];
            if(avg >= 90) {
                boxStyle = `background-color: color-mix(in srgb, var(--warna-hadir) 20%, white); color: var(--warna-hadir); border: 1px solid color-mix(in srgb, var(--warna-hadir) 40%, transparent);`;
            } else if (avg >= 70) {
                boxStyle = `background-color: color-mix(in srgb, var(--warna-sakit) 20%, white); color: var(--warna-sakit); border: 1px solid color-mix(in srgb, var(--warna-sakit) 40%, transparent);`;
            } else {
                boxStyle = `background-color: color-mix(in srgb, var(--warna-alpha) 20%, white); color: var(--warna-alpha); border: 1px solid color-mix(in srgb, var(--warna-alpha) 40%, transparent);`;
            }
            hoverText = `Ada Rekod (Klik Detail)`;
        }

        html += `
            <div onclick="handleCalendarClick('${fullDateStr}')" 
                 class="aspect-square rounded-xl flex items-center justify-center text-xs sm:text-sm font-black transition-all transform hover:scale-110 hover:z-20 ${cursorClass} group relative" 
                 style="${boxStyle}">
                ${day}
                <div class="absolute bottom-full mb-2 hidden group-hover:block w-max max-w-[150px] bg-gray-800 text-white text-[10px] py-1.5 px-3 rounded-lg shadow-xl z-50 pointer-events-none">
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
        openAbsensiForm(dateStr); 
    }
}

function openDetailModal(dateStr, attendance) {
    const dateObj = new Date(dateStr);
    const formattedDate = dateObj.toLocaleDateString('ms-MY', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
    
    let h=0, s=0, i=0, a=0;
    let listHtml = '';
    
    absensiData.students.forEach(student => {
        const status = attendance.records[student.id] || 'A';
        if(status==='H') h++; else if(status==='S') s++; else if(status==='I') i++; else if(status==='A') a++;
        
        if (status !== 'H') {
            let statusBadge = '';
            if(status==='S') statusBadge = `<span class="px-3 py-1 rounded-lg bg-yellow-50 text-yellow-700 text-[10px] font-bold uppercase tracking-widest border border-yellow-200/50">Sakit</span>`;
            if(status==='I') statusBadge = `<span class="px-3 py-1 rounded-lg bg-purple-50 text-purple-700 text-[10px] font-bold uppercase tracking-widest border border-purple-200/50">Izin</span>`;
            if(status==='A') statusBadge = `<span class="px-3 py-1 rounded-lg bg-red-50 text-red-700 text-[10px] font-bold uppercase tracking-widest border border-red-200/50">Alpha</span>`;
            
            listHtml += `
                <div class="flex items-center justify-between p-3.5 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3.5">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold text-white bg-tema shadow-sm">
                            ${student.name.charAt(0).toUpperCase()}
                        </div>
                        <span class="text-sm font-bold text-gray-800">${student.name}</span>
                    </div>
                    ${statusBadge}
                </div>
            `;
        }
    });

    if (listHtml === '') {
        listHtml = `<div class="p-8 text-center text-sm font-bold text-gray-400">Semua pelajar hadir pada hari ini. 🎉</div>`;
    }

    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto opacity-0 transition-opacity duration-300';
    modal.id = 'detailModal';
    modal.innerHTML = `
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full my-auto transform scale-95 transition-transform duration-300 overflow-hidden" id="detailModalContent">
            <div class="px-6 py-5 flex items-center justify-between text-white bg-tema" style="background-image: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 100%);">
                <div>
                    <h2 class="text-xl font-black tracking-tight">Perincian Rekod</h2>
                    <p class="text-xs opacity-90 mt-0.5 font-medium tracking-wider uppercase">${formattedDate}</p>
                </div>
                <button onclick="closeDetailModal()" class="p-2 hover:bg-white/20 rounded-xl transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 bg-gray-50/50">
                <div class="grid grid-cols-4 gap-3 mb-5">
                    <div class="bg-green-50 border border-green-100 p-3 rounded-2xl text-center shadow-sm">
                        <p class="text-[9px] font-bold text-green-600 uppercase tracking-widest mb-1">Hadir</p>
                        <p class="text-2xl font-black text-green-700">${h}</p>
                    </div>
                    <div class="bg-yellow-50 border border-yellow-100 p-3 rounded-2xl text-center shadow-sm">
                        <p class="text-[9px] font-bold text-yellow-600 uppercase tracking-widest mb-1">Sakit</p>
                        <p class="text-2xl font-black text-yellow-700">${s}</p>
                    </div>
                    <div class="bg-purple-50 border border-purple-100 p-3 rounded-2xl text-center shadow-sm">
                        <p class="text-[9px] font-bold text-purple-600 uppercase tracking-widest mb-1">Izin</p>
                        <p class="text-2xl font-black text-purple-700">${i}</p>
                    </div>
                    <div class="bg-red-50 border border-red-100 p-3 rounded-2xl text-center shadow-sm">
                        <p class="text-[9px] font-bold text-red-600 uppercase tracking-widest mb-1">Alpha</p>
                        <p class="text-2xl font-black text-red-700">${a}</p>
                    </div>
                </div>

                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 pl-1">Senarai Tidak Hadir:</p>
                <div class="mb-6 max-h-[35vh] overflow-y-auto bg-white border border-gray-100 rounded-2xl shadow-sm divide-y divide-gray-50 custom-scrollbar">
                    ${listHtml}
                </div>
                
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeDetailModal()" class="px-6 py-3 border-2 border-gray-200 text-gray-600 font-bold rounded-2xl hover:bg-gray-100 transition-colors">Tutup</button>
                    <button type="button" onclick="closeDetailModal(); setTimeout(() => openAbsensiForm('${dateStr}'), 300);" class="px-6 py-3 text-white font-bold rounded-2xl shadow-md transition-all bg-tema flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        Edit Data
                    </button>
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
        html += `<div class="flex gap-4 items-start p-4 rounded-2xl bg-green-50 border border-green-100">
            <div class="mt-0.5 p-2 rounded-xl bg-green-100 text-green-600 shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></div>
            <div>
                <p class="font-bold text-green-800 mb-1">Tahniah!</p>
                <p class="text-xs font-medium text-green-700 leading-relaxed">Tahap kehadiran sangat baik (${stats.hadirPercent}%).</p>
            </div>
        </div>`;
    } else if(stats.hadirPercent <= 80) {
        html += `<div class="flex gap-4 items-start p-4 rounded-2xl bg-red-50 border border-red-100">
            <div class="mt-0.5 p-2 rounded-xl bg-red-100 text-red-600 shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></div>
            <div>
                <p class="font-bold text-red-800 mb-1">Perhatian</p>
                <p class="text-xs font-medium text-red-700 leading-relaxed">Kehadiran kelas merosot (${stats.hadirPercent}%).</p>
            </div>
        </div>`;
    }

    let alphaStudents = [];
    absensiData.students.forEach(student => {
        let alphaCount = 0;
        absensiData.attendance.forEach(att => {
            if((att.records[student.id] || 'A') === 'A') alphaCount++;
        });
        if(alphaCount >= 2) alphaStudents.push(student.name);
    });

    if(alphaStudents.length > 0) {
        html += `<div class="flex gap-4 items-start p-4 rounded-2xl bg-orange-50 border border-orange-100">
            <div class="mt-0.5 p-2 rounded-xl bg-orange-100 text-orange-600 shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
            <div>
                <p class="font-bold text-orange-800 mb-1">Amaran</p>
                <p class="text-xs font-medium text-orange-700 leading-relaxed"><span class="font-bold">${alphaStudents.length} pelajar</span> kerap tidak hadir (Alpha). Sila ambil tindakan.</p>
            </div>
        </div>`;
    } else {
        html += `<div class="flex gap-4 items-start p-4 rounded-2xl bg-blue-50 border border-blue-100">
            <div class="mt-0.5 p-2 rounded-xl bg-blue-100 text-blue-600 shadow-sm"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg></div>
            <div>
                <p class="font-bold text-blue-800 mb-1">Disiplin Terkawal</p>
                <p class="text-xs font-medium text-blue-700 leading-relaxed">Tiada rekod Alpha yang kritikal bulan ini.</p>
            </div>
        </div>`;
    }

    container.innerHTML = html;
}

function calculateAbsensiStats() {
    let hadir = 0, sakit = 0, izin = 0, alpha = 0;
    const totalRecords = absensiData.students.length * absensiData.attendance.length || 1;
    absensiData.attendance.forEach(att => {
        Object.values(att.records).forEach(st => {
            if (st === 'H') hadir++; else if (st === 'S') sakit++; else if (st === 'I') izin++; else if (st === 'A') alpha++;
        });
    });
    return { hadir, sakit, izin, alpha, hadirPercent: Math.round((hadir / totalRecords) * 100) };
}

function generateDateHeaders() {
    if (absensiData.attendance.length === 0) return '<th class="px-4 py-5 text-center text-xs font-medium text-gray-400">Belum ada data</th>';
    return absensiData.attendance.map(att => {
        const date = new Date(att.date);
        return `<th class="px-2 py-4 text-center">
            <div class="flex flex-col items-center justify-center p-2 rounded-xl bg-white shadow-sm border border-gray-100 min-w-[3.5rem] group cursor-default">
                <span class="text-[9px] font-bold text-gray-400 uppercase mb-0.5">${date.toLocaleDateString('ms-MY', { weekday: 'short' })}</span>
                <span class="text-lg font-black text-gray-800">${date.getDate()}</span>
            </div>
        </th>`;
    }).join('');
}

function getBtnStyle(status) {
    let colorVar = '--warna-alpha';
    if (status === 'H') colorVar = '--warna-hadir';
    if (status === 'S') colorVar = '--warna-sakit';
    if (status === 'I') colorVar = '--warna-izin';
    return `color: var(${colorVar}); background-color: color-mix(in srgb, var(${colorVar}) 12%, transparent); border: 1px solid color-mix(in srgb, var(${colorVar}) 30%, transparent);`;
}

function renderAbsensiTable() {
    const tableBody = document.getElementById('absensiTableBody');
    if(!tableBody) return;
    
    tableBody.innerHTML = '';

    if (absensiData.students.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="100%" class="text-center py-20 text-gray-500 font-medium">Data pelajar kosong.</td></tr>`;
        return;
    }

    absensiData.students.forEach((student) => {
        const row = document.createElement('tr');
        row.className = 'group hover:bg-white hover:shadow-md transition-all duration-300 relative z-10 hover:z-20';

        let totalH = 0, totalS = 0, totalI = 0, totalA = 0;
        
        absensiData.attendance.forEach(att => {
            const st = att.records[student.id] || 'A';
            if (st === 'H') totalH++; else if (st === 'S') totalS++; else if (st === 'I') totalI++; else if (st === 'A') totalA++;
        });
        
        const totalHari = absensiData.attendance.length || 1;
        const persen = Math.round((totalH / totalHari) * 100);
        
        let barColor, predikatText, gradientColor;

        if (persen >= 90) {
            barColor = 'var(--warna-hadir)'; predikatText = 'Disiplin';
            gradientColor = 'from-[var(--warna-hadir)] to-[#22c55e]'; 
        } else if (persen >= 75) {
            barColor = 'var(--warna-sakit)'; predikatText = 'Sederhana';
            gradientColor = 'from-blue-400 to-indigo-500';
        } else {
            barColor = 'var(--warna-alpha)'; predikatText = 'Amaran';
            gradientColor = 'from-[var(--warna-alpha)] to-rose-600';
        }

        let cellsHtml = `
            <td class="px-6 py-4 sticky left-0 group-hover:bg-white bg-gray-50/20 backdrop-blur-md border-r border-transparent group-hover:border-gray-50">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-sm font-bold text-white bg-tema shadow-sm">${student.name.charAt(0).toUpperCase()}</div>
                    <div class="flex flex-col">
                        <div class="font-bold text-gray-800 text-sm truncate max-w-[150px]">${student.name}</div>
                        <span class="text-[10px] text-gray-400 font-semibold mt-1">NISN: ${student.nisn}</span>
                    </div>
                </div>
            </td>`;

        absensiData.attendance.forEach(att => {
            const status = att.records[student.id] || 'A';
            cellsHtml += `<td class="px-2 py-4 text-center">
                <button onclick="changeAbsensi(${student.id}, '${att.date}')" class="w-9 h-9 rounded-lg font-bold text-sm transition-all focus:outline-none" style="${getBtnStyle(status)}">${status}</button>
            </td>`;
        });

        cellsHtml += `
            <td class="px-3 py-4 text-center border-l border-gray-100/80">
                <div class="mx-auto w-7 h-7 flex items-center justify-center rounded-md font-bold text-xs" style="color: var(--warna-hadir); background-color: color-mix(in srgb, var(--warna-hadir) 8%, transparent);">${totalH}</div>
            </td>
            <td class="px-3 py-4 text-center">
                <div class="mx-auto w-7 h-7 flex items-center justify-center rounded-md font-bold text-xs" style="color: var(--warna-sakit); background-color: color-mix(in srgb, var(--warna-sakit) 8%, transparent);">${totalS}</div>
            </td>
            <td class="px-3 py-4 text-center">
                <div class="mx-auto w-7 h-7 flex items-center justify-center rounded-md font-bold text-xs" style="color: var(--warna-izin); background-color: color-mix(in srgb, var(--warna-izin) 8%, transparent);">${totalI}</div>
            </td>
            <td class="px-3 py-4 text-center">
                <div class="mx-auto w-7 h-7 flex items-center justify-center rounded-md font-bold text-xs" style="color: var(--warna-alpha); background-color: color-mix(in srgb, var(--warna-alpha) 8%, transparent);">${totalA}</div>
            </td>
            <td class="px-6 py-4 border-l border-gray-100/80">
                <div class="flex flex-col gap-1.5 w-full min-w-[150px]">
                    <div class="flex justify-between items-center w-full">
                        <span class="text-[10px] font-bold" style="color: ${barColor};">${predikatText}</span>
                        <span class="text-xs font-black text-gray-800">${persen}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="h-2 rounded-full bg-gradient-to-r ${gradientColor}" style="width: ${persen}%;"></div>
                    </div>
                </div>
            </td>`;

        row.innerHTML = cellsHtml;
        tableBody.appendChild(row);
    });
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
        await fetch(API_URL + '/save', { 
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ date: date, records: singleRecord })
        });
    } catch (error) {
        console.error("Gagal kemaskini data:", error);
    }
}

function openAbsensiForm(prefillDate = null) {
    const isEditing = typeof prefillDate === 'string';
    const targetDate = isEditing ? prefillDate : new Date().toISOString().split('T')[0];
    const existingData = absensiData.attendance.find(a => a.date === targetDate);

    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto';
    modal.id = 'absensiModal';
    modal.innerHTML = `
        <div class="bg-white rounded-3xl shadow-2xl max-w-xl w-full">
            <div class="px-6 py-5 flex items-center justify-between text-white bg-tema">
                <h2 class="text-xl font-bold">${isEditing ? 'Kemaskini' : 'Tambah'} Data Kehadiran</h2>
                <button onclick="document.getElementById('absensiModal').remove()" class="p-2 hover:bg-white/20 rounded-xl">✕</button>
            </div>
            <div class="p-6 bg-gray-50/50">
                <form onsubmit="submitAbsensi(event)">
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tarikh</label>
                        <input type="date" id="absensiDate" value="${targetDate}" ${isEditing ? 'readonly' : ''} required class="w-full px-4 py-2 border rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div class="mb-6 max-h-[40vh] overflow-y-auto border border-gray-200 rounded-xl bg-white divide-y divide-gray-100">
                        ${absensiData.students.map(s => {
                            const sStatus = existingData ? (existingData.records[s.id] || 'A') : 'H';
                            return `
                            <div class="flex items-center justify-between p-3.5 hover:bg-gray-50">
                                <label class="flex items-center gap-3 flex-1 cursor-pointer">
                                    <input type="checkbox" checked class="w-4 h-4 rounded text-tema" data-student-id="${s.id}">
                                    <span class="text-sm font-semibold text-gray-800">${s.name}</span>
                                </label>
                                <select data-status-select="${s.id}" class="px-3 py-1.5 border rounded-lg bg-gray-50 text-sm font-bold">
                                    <option value="H" ${sStatus === 'H' ? 'selected' : ''}>Hadir</option>
                                    <option value="S" ${sStatus === 'S' ? 'selected' : ''}>Sakit</option>
                                    <option value="I" ${sStatus === 'I' ? 'selected' : ''}>Izin</option>
                                    <option value="A" ${sStatus === 'A' ? 'selected' : ''}>Alpha</option>
                                </select>
                            </div>`
                        }).join('')}
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="document.getElementById('absensiModal').remove()" class="px-5 py-2.5 bg-gray-200 text-gray-700 font-bold rounded-xl">Batal</button>
                        <button type="submit" class="px-6 py-2.5 text-white font-bold rounded-xl bg-tema">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

async function submitAbsensi(event) {
    event.preventDefault();
    const date = document.getElementById('absensiDate').value;
    const newRecords = {};

    document.querySelectorAll('#absensiModal input[type="checkbox"]:checked').forEach(cb => {
        const studentId = parseInt(cb.getAttribute('data-student-id'));
        newRecords[studentId] = document.querySelector(`[data-status-select="${studentId}"]`).value;
    });

    try {
        const response = await fetch(API_URL + '/save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ date: date, records: newRecords })
        });
        
        if (response.ok) {
            await loadAbsensiFromDB();
            document.getElementById('absensiModal').remove();
        }
    } catch (error) {
        console.error("Gagal simpan:", error);
    }
}

function exportAbsensi() {
    if (!absensiData || absensiData.students.length === 0) return alert("Tiada data pelajar.");
    let csvContent = "Nama Pelajar,NISN";
    absensiData.attendance.forEach(att => { const d = new Date(att.date); csvContent += `,${d.getDate()}/${d.getMonth()+1}/${d.getFullYear()}`; });
    csvContent += ",Hadir,Sakit,Izin,Alpha,Peratusan\n";

    absensiData.students.forEach(student => {
        let row = `"${student.name}","${student.nisn}"`;
        let th = 0, ts = 0, ti = 0, ta = 0;
        absensiData.attendance.forEach(att => {
            const status = att.records[student.id] || 'A';
            row += `,${status}`;
            if (status === 'H') th++; else if (status === 'S') ts++; else if (status === 'I') ti++; else if (status === 'A') ta++;
        });
        const persen = Math.round((th / (absensiData.attendance.length || 1)) * 100);
        row += `,${th},${ts},${ti},${ta},${persen}%\n`;
        csvContent += row;
    });

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = `Rekod_Kehadiran_${new Date().toLocaleDateString('ms-MY').replace(/\//g, '-')}.csv`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}