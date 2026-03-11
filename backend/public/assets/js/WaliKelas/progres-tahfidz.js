// Terima data dari pelayan
let tahfizData = typeof serverTahfizData !== 'undefined' ? serverTahfizData : [];

document.addEventListener('DOMContentLoaded', function() {
    renderProgresTahfidz();
});

function renderProgresTahfidz() {
    const mainContent = document.getElementById('mainContent');
    if (!mainContent) return;

    if (tahfizData.length === 0) {
        mainContent.innerHTML = `<div class="bg-white p-12 rounded-3xl shadow-sm text-center">
            <h2 class="text-2xl font-bold text-gray-400">Tiada Data Pelajar Ditemui</h2>
        </div>`;
        return;
    }

    const avgProgress = Math.round(tahfizData.reduce((a, b) => a + b.progress, 0) / tahfizData.length);
    const completedTarget = tahfizData.filter(t => t.progress === 100).length;
    const totalJuz = tahfizData.reduce((a, b) => a + b.juzCurrent, 0);

    const bgPrimaryLight = `color-mix(in srgb, var(--warna-primary) 12%, transparent)`;

    mainContent.innerHTML = `
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-8">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 rounded-full opacity-5 group-hover:scale-150 transition-transform duration-700 ease-out bg-tema"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 mb-1 tracking-widest uppercase">Total Pelajar</p>
                        <p class="text-4xl font-black text-gray-800 tracking-tight">${tahfizData.length}</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-inner text-tema transition-transform group-hover:rotate-6 bg-tema-light">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 rounded-full opacity-5 group-hover:scale-150 transition-transform duration-700 ease-out bg-blue-500"></div>
                <div class="flex items-center justify-between relative z-10 mb-3">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 mb-1 tracking-widest uppercase">Rata-rata Kelas</p>
                        <p class="text-4xl font-black text-blue-600 tracking-tight">${avgProgress}%</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-inner text-blue-600 transition-transform group-hover:rotate-6 bg-blue-50">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2 relative z-10 overflow-hidden shadow-inner">
                    <div class="h-full rounded-full bg-blue-500 transition-all duration-1000" style="width: ${avgProgress}%;"></div>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 rounded-full opacity-5 group-hover:scale-150 transition-transform duration-700 ease-out bg-green-500"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 mb-1 tracking-widest uppercase">Khatam Target</p>
                        <p class="text-4xl font-black text-green-600 tracking-tight">${completedTarget}</p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-inner text-green-600 transition-transform group-hover:rotate-6 bg-green-50">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-6 -top-6 w-32 h-32 rounded-full opacity-5 group-hover:scale-150 transition-transform duration-700 ease-out bg-purple-500"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 mb-1 tracking-widest uppercase">Total Hafalan</p>
                        <p class="text-4xl font-black text-purple-600 tracking-tight">${totalJuz} <span class="text-sm">Juz</span></p>
                    </div>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-inner text-purple-600 transition-transform group-hover:rotate-6 bg-purple-50">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-4">
                <div class="flex-1 w-full">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <input type="text" id="searchTahfidz" placeholder="Cari nama pelajar..." class="px-4 py-2.5 bg-gray-50 border-none rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-tema w-full" onkeyup="filterTahfidz()">
                        <select id="filterStatus" class="px-4 py-2.5 bg-gray-50 border-none rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-tema w-full cursor-pointer" onchange="filterTahfidz()">
                            <option value="">Semua Status</option>
                            <option value="Aktif">Aktif Mengaji</option>
                            <option value="Tidak Aktif">Gagal/Rehat</option>
                        </select>
                        <select id="sortTahfidz" class="px-4 py-2.5 bg-gray-50 border-none rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-tema w-full cursor-pointer" onchange="filterTahfidz()">
                            <option value="progress">Progres Tertinggi</option>
                            <option value="name">Nama A-Z</option>
                        </select>
                    </div>
                </div>
                <button onclick="exportTahfidz()" class="px-6 py-2.5 bg-white border-2 border-tema text-tema font-bold rounded-xl shadow-sm hover:bg-gray-50 transition-colors flex items-center justify-center gap-2 whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Eksport Excel
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
        const statusColor = t.progress >= 75 ? 'green' : t.progress >= 50 ? 'amber' : 'red';
        const isKhatam = t.progress === 100;
        const lastTest = t.testResults[t.testResults.length - 1] || 0;
        
        return `
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300 relative overflow-hidden flex flex-col group">
                ${isKhatam ? `<div class="absolute -right-10 -top-10 bg-green-500 w-24 h-24 rotate-45 opacity-20"></div>` : ''}
                
                <div class="flex items-start gap-4 mb-5">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-lg font-black text-white shadow-md bg-tema" style="background-image: linear-gradient(135deg, rgba(255,255,255,0.3) 0%, transparent 100%);">
                        ${t.name.charAt(0).toUpperCase()}
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-black text-gray-800 truncate pr-2" title="${t.name}">${t.name}</h3>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="px-2 py-0.5 rounded-lg bg-gray-100 text-[10px] font-bold text-gray-500 uppercase tracking-widest">${t.status}</span>
                            ${isKhatam ? `<span class="px-2 py-0.5 rounded-lg bg-green-100 text-green-700 text-[10px] font-bold uppercase tracking-widest flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> KHATAM</span>` : ''}
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-2xl p-4 mb-5 border border-gray-100 flex-grow">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Hafalan Terkini</span>
                        <span class="text-xs font-black text-tema bg-tema-light px-2 py-0.5 rounded-md">Juz ${t.juzCurrent} / ${t.juzTarget}</span>
                    </div>
                    <p class="font-bold text-gray-800 mb-4">${t.surahCurrent} <span class="text-gray-400 font-medium">(Ayat ${t.ayahCurrent})</span></p>

                    <div class="flex justify-between items-end mb-1.5">
                        <span class="text-[10px] font-bold text-${statusColor}-600 uppercase tracking-widest">Progress</span>
                        <span class="text-sm font-black text-gray-800">${t.progress}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 shadow-inner overflow-hidden relative">
                        <div class="h-2 rounded-full bg-${statusColor}-500 transition-all duration-1000 ease-out" style="width: ${t.progress}%;"></div>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-auto pt-2 border-t border-gray-100">
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Ujian Terakhir</p>
                        <p class="text-sm font-black text-gray-800">${lastTest} <span class="text-[10px] text-gray-400 font-medium">Markah</span></p>
                    </div>
                    <div class="flex gap-1.5">
                        <button onclick="openEditModal(${t.id})" title="Kemaskini" class="w-9 h-9 flex items-center justify-center bg-gray-100 hover:bg-tema hover:text-white text-gray-600 rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button onclick="alert('Ciri Rekod Ujian dalam fasa pembangunan.')" title="Ujian" class="w-9 h-9 flex items-center justify-center bg-gray-100 hover:bg-purple-600 hover:text-white text-gray-600 rounded-xl transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }).join('');
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
    alert("Borang Edit akan bersambung ke API pangkalan data kelak.");
}

function exportTahfidz() {
    alert("Ciri eksport Excel akan dimuat turun.");
}