let currentPage = 'pelanggaran-prestasi'; 

// Menggunakan data dari pelayan (Server PHP)
let pelanggaranPrestasi = {
    students: typeof serverStudents !== 'undefined' ? serverStudents : [],
    pelanggaran: typeof serverPelanggaran !== 'undefined' ? serverPelanggaran : [],
    prestasi: typeof serverPrestasi !== 'undefined' ? serverPrestasi : []
};

// Data Dummy sekiranya DB Kosong (Untuk tunjukkan UI beroperasi)
if (pelanggaranPrestasi.students.length === 0) {
    pelanggaranPrestasi.students = [
        { id: 1, name: 'Ahmad Ridho', nisn: '12345001' },
        { id: 2, name: 'Siti Nur Azizah', nisn: '12345002' },
        { id: 3, name: 'Muhammad Rizki', nisn: '12345003' }
    ];
}

if (pelanggaranPrestasi.pelanggaran.length === 0 && pelanggaranPrestasi.prestasi.length === 0) {
    pelanggaranPrestasi.pelanggaran = [
        { id: 1, studentId: 1, studentName: 'Ahmad Ridho', category: 'Terlambat', severity: 'ringan', description: 'Terlambat masuk kelas 15 minit', date: '2024-01-18', teacher: 'Bu Siti Maryam', points: 5, status: 'Tercatat' },
        { id: 2, studentId: 3, studentName: 'Muhammad Rizki', category: 'Tidak Mengumpulkan Tugas', severity: 'sedang', description: 'Tidak hantar PR Matematik', date: '2024-01-17', teacher: 'Pak Ahmad Fauzi', points: 15, status: 'Sudah Ditegur' }
    ];
    pelanggaranPrestasi.prestasi = [
        { id: 1, studentId: 2, studentName: 'Siti Nur Azizah', category: 'Akademik', achievement: 'Juara 1 Kuiz', date: '2024-01-10', points: 50, reward: 'Sijil', description: 'Menjawab semua soalan dengan tepat.' }
    ];
}

document.addEventListener('DOMContentLoaded', function() {
    renderPelanggaranPrestasi();
});

function renderPelanggaranPrestasi() {
    const mainContent = document.getElementById('mainContent');
    if(!mainContent) return;

    const totalPelanggaran = pelanggaranPrestasi.pelanggaran.length;
    const totalPrestasi = pelanggaranPrestasi.prestasi.length;
    const beratCount = pelanggaranPrestasi.pelanggaran.filter(p => p.severity === 'berat').length;
    const akademikCount = pelanggaranPrestasi.prestasi.filter(p => p.category === 'Akademik').length;

    mainContent.innerHTML = `
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6">
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:-translate-y-1 transition-transform group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Total Pelanggaran</p>
                        <p class="text-3xl font-black text-red-600">${totalPelanggaran}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:-translate-y-1 transition-transform group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Kes Berat</p>
                        <p class="text-3xl font-black text-orange-600">${beratCount}</p>
                        <p class="text-[10px] text-gray-400 mt-1 font-semibold">${totalPelanggaran > 0 ? Math.round((beratCount / totalPelanggaran) * 100) : 0}% dari keseluruhan</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:-translate-y-1 transition-transform group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Total Prestasi</p>
                        <p class="text-3xl font-black text-green-600">${totalPrestasi}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:-translate-y-1 transition-transform group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold text-gray-400 mb-1 uppercase tracking-wider">Prestasi Akademik</p>
                        <p class="text-3xl font-black text-blue-600">${akademikCount}</p>
                        <p class="text-[10px] text-gray-400 mt-1 font-semibold">${totalPrestasi > 0 ? Math.round((akademikCount / totalPrestasi) * 100) : 0}% dari keseluruhan</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-2 mb-6 border-b border-gray-200 bg-white rounded-t-2xl overflow-x-auto p-2">
            <button onclick="switchPelanggaranTab('overview')" class="pp-tab-active px-6 py-2.5 font-bold text-sm rounded-xl transition-colors bg-tema-light text-tema">
                Analitik
            </button>
            <button onclick="switchPelanggaranTab('pelanggaran')" class="pp-tab-inactive px-6 py-2.5 font-bold text-sm rounded-xl transition-colors text-gray-500 hover:bg-gray-50 flex items-center gap-2">
                Pelanggaran
                <span class="px-2 py-0.5 bg-red-100 text-red-600 rounded-md text-[10px]">${totalPelanggaran}</span>
            </button>
            <button onclick="switchPelanggaranTab('prestasi')" class="pp-tab-inactive px-6 py-2.5 font-bold text-sm rounded-xl transition-colors text-gray-500 hover:bg-gray-50 flex items-center gap-2">
                Prestasi
                <span class="px-2 py-0.5 bg-green-100 text-green-600 rounded-md text-[10px]">${totalPrestasi}</span>
            </button>
        </div>

        <div id="pp-overview" class="pp-tab-content">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-black text-gray-800 mb-4 pb-3 border-b border-gray-100">⚠️ Pelanggaran Terbanyak</h3>
                    <div class="space-y-3">
                        ${getPelanggaranStats().map((stat, idx) => `
                            <div class="flex items-center justify-between p-3 bg-red-50/50 hover:bg-red-50 rounded-xl transition-colors">
                                <div class="flex-1">
                                    <p class="font-bold text-gray-800 text-sm">${idx + 1}. ${stat.category}</p>
                                </div>
                                <span class="px-3 py-1 bg-white border border-red-100 text-red-600 text-xs font-black rounded-lg shadow-sm">${stat.count} Kes</span>
                            </div>
                        `).join('') || '<p class="text-sm text-gray-500 text-center py-4">Tiada data</p>'}
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-black text-gray-800 mb-4 pb-3 border-b border-gray-100">🏆 Prestasi Terbanyak</h3>
                    <div class="space-y-3">
                        ${getPrestasiByCat().map((cat, idx) => `
                            <div class="flex items-center justify-between p-3 bg-green-50/50 hover:bg-green-50 rounded-xl transition-colors">
                                <div class="flex-1">
                                    <p class="font-bold text-gray-800 text-sm">${idx + 1}. ${cat.name}</p>
                                </div>
                                <span class="px-3 py-1 bg-white border border-green-100 text-green-600 text-xs font-black rounded-lg shadow-sm">${cat.count} Sijil</span>
                            </div>
                        `).join('') || '<p class="text-sm text-gray-500 text-center py-4">Tiada data</p>'}
                    </div>
                </div>
                
                <div class="lg:col-span-2 bg-gradient-to-r from-red-50 to-orange-50 border border-red-100 rounded-3xl p-6">
                    <h3 class="text-lg font-black text-red-800 mb-4">📍 Pelajar Memerlukan Perhatian Penuh</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        ${getSiswaWithMostPelanggaran().map(student => `
                            <div class="bg-white p-4 rounded-2xl shadow-sm border border-red-50 flex flex-col">
                                <span class="font-bold text-gray-800 truncate" title="${student.name}">${student.name}</span>
                                <div class="mt-auto pt-3 flex justify-between items-center">
                                    <span class="text-xs font-semibold text-gray-500">${student.count} Kes</span>
                                    <span class="px-2 py-1 bg-red-100 text-red-700 text-[10px] font-black rounded-md">${student.totalPoints} Poin Demerit</span>
                                </div>
                            </div>
                        `).join('') || '<p class="text-sm text-gray-500 md:col-span-3 text-center">Tiada pelajar yang bermasalah teruk.</p>'}
                    </div>
                </div>
            </div>
        </div>

        <div id="pp-pelanggaran" class="pp-tab-content hidden">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-6">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-4">
                    <div class="flex-1 w-full">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <input type="text" id="searchPelanggaran" placeholder="Cari nama pelajar..." class="px-4 py-2.5 bg-gray-50 border-none rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-tema w-full" onkeyup="filterPelanggaran()">
                            <select id="filterSeverity" class="px-4 py-2.5 bg-gray-50 border-none rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-tema w-full cursor-pointer" onchange="filterPelanggaran()">
                                <option value="">Semua Tahap</option>
                                <option value="ringan">Ringan</option>
                                <option value="sedang">Sedang</option>
                                <option value="berat">Berat</option>
                            </select>
                        </div>
                    </div>
                    <button onclick="openFormPelanggaran()" class="px-6 py-2.5 text-white font-bold rounded-xl shadow-md transition-transform hover:-translate-y-0.5 bg-red-600 whitespace-nowrap flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Catat Pelanggaran
                    </button>
                </div>
            </div>
            <div id="pelanggaranList" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4"></div>
        </div>

        <div id="pp-prestasi" class="pp-tab-content hidden">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-6">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-4">
                    <div class="flex-1 w-full">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <input type="text" id="searchPrestasi" placeholder="Cari nama pelajar..." class="px-4 py-2.5 bg-gray-50 border-none rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-tema w-full" onkeyup="filterPrestasi()">
                        </div>
                    </div>
                    <button onclick="openFormPrestasi()" class="px-6 py-2.5 text-white font-bold rounded-xl shadow-md transition-transform hover:-translate-y-0.5 bg-green-600 whitespace-nowrap flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Catat Prestasi
                    </button>
                </div>
            </div>
            <div id="prestasiList" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4"></div>
        </div>
    `;

    renderPelanggaranList();
    renderPrestasiList();
}

function switchPelanggaranTab(tabName) {
    document.querySelectorAll('.pp-tab-content').forEach(tab => tab.classList.add('hidden'));
    document.querySelectorAll('[onclick*="switchPelanggaranTab"]').forEach(btn => {
        btn.classList.remove('bg-tema-light', 'text-tema');
        btn.classList.add('text-gray-500');
    });

    document.getElementById(`pp-${tabName}`).classList.remove('hidden');
    event.target.closest('button').classList.remove('text-gray-500');
    event.target.closest('button').classList.add('bg-tema-light', 'text-tema');
}

// ============================================================================
// LOGIK RENDER SENARAI KAD (LIST)
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

    container.innerHTML = filtered.length === 0 ? '<p class="col-span-full text-center py-10 text-gray-400 font-bold">Tiada Rekod Pelanggaran</p>' : '';

    filtered.forEach(pelanggaran => {
        const severityColor = pelanggaran.severity === 'berat' ? 'red' : pelanggaran.severity === 'sedang' ? 'orange' : 'yellow';
        
        container.innerHTML += `
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow relative overflow-hidden flex flex-col h-full">
                <div class="absolute top-0 left-0 w-full h-1 bg-${severityColor}-400"></div>
                <div class="flex justify-between items-start mb-3">
                    <h3 class="font-black text-gray-800 truncate pr-2" title="${pelanggaran.studentName}">${pelanggaran.studentName}</h3>
                    <span class="px-2 py-1 bg-${severityColor}-50 text-${severityColor}-700 text-[10px] font-black rounded-lg uppercase tracking-wider">${pelanggaran.severity}</span>
                </div>
                <p class="text-sm font-bold text-gray-600 mb-2">${pelanggaran.category}</p>
                <div class="bg-gray-50 rounded-xl p-3 mb-4 flex-grow">
                    <p class="text-xs text-gray-500 leading-relaxed italic">"${pelanggaran.description}"</p>
                </div>
                <div class="flex items-center justify-between mt-auto pt-3 border-t border-gray-100">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">${pelanggaran.date}</p>
                        <p class="text-[10px] font-semibold text-gray-400 mt-0.5">Poin: <span class="text-red-600 font-bold">-${pelanggaran.points}</span></p>
                    </div>
                    <div class="flex gap-1.5">
                        <button onclick="openFormPelanggaran(${pelanggaran.id})" class="text-blue-500 hover:text-white hover:bg-blue-500 p-1.5 rounded-lg transition-colors border border-blue-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button onclick="confirmDelete('pelanggaran', ${pelanggaran.id})" class="text-red-500 hover:text-white hover:bg-red-500 p-1.5 rounded-lg transition-colors border border-red-100">
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

    container.innerHTML = filtered.length === 0 ? '<p class="col-span-full text-center py-10 text-gray-400 font-bold">Tiada Rekod Prestasi</p>' : '';

    filtered.forEach(prestasi => {
        container.innerHTML += `
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow relative overflow-hidden flex flex-col h-full">
                <div class="absolute top-0 left-0 w-full h-1 bg-green-400"></div>
                <div class="flex justify-between items-start mb-3">
                    <h3 class="font-black text-gray-800 truncate pr-2">${prestasi.studentName}</h3>
                    <span class="px-2 py-1 bg-green-50 text-green-700 text-[10px] font-black rounded-lg uppercase tracking-wider">${prestasi.category}</span>
                </div>
                <div class="bg-gray-50 rounded-xl p-3 mb-4 flex-grow border border-green-50">
                    <p class="text-sm font-bold text-gray-700 leading-snug">${prestasi.achievement}</p>
                    ${prestasi.description ? `<p class="text-xs text-gray-500 mt-1.5 italic">"${prestasi.description}"</p>` : ''}
                </div>
                <div class="flex justify-between items-end mt-auto pt-3 border-t border-gray-100">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">${prestasi.date}</p>
                        <p class="text-xs font-black text-green-600 mt-0.5">🎁 ${prestasi.reward || 'Sijil/Pujian'}</p>
                    </div>
                    <div class="flex gap-1.5">
                        <button onclick="openFormPrestasi(${prestasi.id})" class="text-blue-500 hover:text-white hover:bg-blue-500 p-1.5 rounded-lg transition-colors border border-blue-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                        <button onclick="confirmDelete('prestasi', ${prestasi.id})" class="text-red-500 hover:text-white hover:bg-red-500 p-1.5 rounded-lg transition-colors border border-red-100">
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
// POPUP MODALS (BORANG TAMBAH & EDIT)
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
    modal.className = 'fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto opacity-0 transition-opacity duration-300';
    modal.id = 'formModal';
    modal.innerHTML = `
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full transform scale-95 transition-transform duration-300 overflow-hidden" id="modalContent">
            <div class="px-6 py-5 flex items-center justify-between text-white bg-red-600" style="background-image: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 100%);">
                <div>
                    <h2 class="text-xl font-black tracking-tight">${isEdit ? 'Kemaskini' : 'Catat'} Pelanggaran</h2>
                    <p class="text-xs opacity-90 mt-0.5 font-medium tracking-wider">Borang Rekod Disiplin Pelajar</p>
                </div>
                <button onclick="closeFormModal()" class="p-2 hover:bg-white/20 rounded-xl transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 bg-gray-50/50">
                <form onsubmit="savePelanggaran(event, ${editId})">
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Nama Pelajar</label>
                        <select id="f_student" required class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl font-semibold text-gray-800 focus:outline-none focus:border-red-500 bg-white">
                            <option value="">-- Pilih Pelajar --</option>
                            ${pelanggaranPrestasi.students.map(s => `<option value="${s.id}" ${data.studentId == s.id ? 'selected' : ''}>${s.name}</option>`).join('')}
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Kategori</label>
                            <select id="f_category" required class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl font-semibold text-gray-800 focus:outline-none focus:border-red-500 bg-white">
                                <option value="Terlambat" ${data.category === 'Terlambat' ? 'selected' : ''}>Terlambat</option>
                                <option value="Tidak Siap Tugas" ${data.category === 'Tidak Siap Tugas' ? 'selected' : ''}>Tidak Siap Tugas</option>
                                <option value="Pakaian" ${data.category === 'Pakaian' ? 'selected' : ''}>Pakaian</option>
                                <option value="Bising" ${data.category === 'Bising' ? 'selected' : ''}>Bising di Kelas</option>
                                <option value="Lain-lain" ${data.category === 'Lain-lain' ? 'selected' : ''}>Lain-lain</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Tahap</label>
                            <select id="f_severity" required class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl font-semibold text-gray-800 focus:outline-none focus:border-red-500 bg-white">
                                <option value="ringan" ${data.severity === 'ringan' ? 'selected' : ''}>Ringan</option>
                                <option value="sedang" ${data.severity === 'sedang' ? 'selected' : ''}>Sedang</option>
                                <option value="berat" ${data.severity === 'berat' ? 'selected' : ''}>Berat</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Tarikh</label>
                            <input type="date" id="f_date" value="${data.date}" required class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl font-semibold text-gray-800 focus:outline-none focus:border-red-500 bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Status Tindakan</label>
                            <select id="f_status" required class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl font-semibold text-gray-800 focus:outline-none focus:border-red-500 bg-white">
                                <option value="Tercatat" ${data.status === 'Tercatat' ? 'selected' : ''}>Tercatat</option>
                                <option value="Sudah Ditegur" ${data.status === 'Sudah Ditegur' ? 'selected' : ''}>Sudah Ditegur</option>
                                <option value="Panggil Ibu Bapa" ${data.status === 'Panggil Ibu Bapa' ? 'selected' : ''}>Panggil Ibu Bapa</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Keterangan / Nota</label>
                        <textarea id="f_desc" rows="3" required placeholder="Huraian ringkas mengenai pelanggaran..." class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-medium text-gray-800 focus:outline-none focus:border-red-500 bg-white resize-none">${data.description}</textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeFormModal()" class="px-6 py-2.5 border-2 border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-100 transition-colors">Batal</button>
                        <button type="submit" class="px-8 py-2.5 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-transform hover:-translate-y-0.5 bg-red-600">
                            ${isEdit ? 'Kemaskini' : 'Simpan'}
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
    modal.className = 'fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto opacity-0 transition-opacity duration-300';
    modal.id = 'formModal';
    modal.innerHTML = `
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full transform scale-95 transition-transform duration-300 overflow-hidden" id="modalContent">
            <div class="px-6 py-5 flex items-center justify-between text-white bg-green-600" style="background-image: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 100%);">
                <div>
                    <h2 class="text-xl font-black tracking-tight">${isEdit ? 'Kemaskini' : 'Catat'} Prestasi</h2>
                    <p class="text-xs opacity-90 mt-0.5 font-medium tracking-wider">Borang Rekod Kecemerlangan Pelajar</p>
                </div>
                <button onclick="closeFormModal()" class="p-2 hover:bg-white/20 rounded-xl transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-6 bg-gray-50/50">
                <form onsubmit="savePrestasi(event, ${editId})">
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Nama Pelajar</label>
                        <select id="f_student" required class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl font-semibold text-gray-800 focus:outline-none focus:border-green-500 bg-white">
                            <option value="">-- Pilih Pelajar --</option>
                            ${pelanggaranPrestasi.students.map(s => `<option value="${s.id}" ${data.studentId == s.id ? 'selected' : ''}>${s.name}</option>`).join('')}
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Kategori</label>
                            <select id="f_category" required class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl font-semibold text-gray-800 focus:outline-none focus:border-green-500 bg-white">
                                <option value="Akademik" ${data.category === 'Akademik' ? 'selected' : ''}>Akademik</option>
                                <option value="Non-Akademik" ${data.category === 'Non-Akademik' ? 'selected' : ''}>Non-Akademik</option>
                                <option value="Sahsiah" ${data.category === 'Sahsiah' ? 'selected' : ''}>Sahsiah / Perilaku</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Tarikh</label>
                            <input type="date" id="f_date" value="${data.date}" required class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl font-semibold text-gray-800 focus:outline-none focus:border-green-500 bg-white">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Nama Pencapaian</label>
                        <input type="text" id="f_achieve" value="${data.achievement}" required placeholder="Cth: Johan Pidato Daerah" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl font-semibold text-gray-800 focus:outline-none focus:border-green-500 bg-white">
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Ganjaran (Reward)</label>
                            <input type="text" id="f_reward" value="${data.reward}" placeholder="Cth: Sijil & Piala" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl font-semibold text-gray-800 focus:outline-none focus:border-green-500 bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1.5 uppercase tracking-wider">Poin Merit</label>
                            <input type="number" id="f_points" value="${data.points}" min="1" required class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl font-semibold text-gray-800 focus:outline-none focus:border-green-500 bg-white">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2 border-t border-gray-200/50">
                        <button type="button" onclick="closeFormModal()" class="px-6 py-2.5 border-2 border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-100 transition-colors">Batal</button>
                        <button type="submit" class="px-8 py-2.5 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-transform hover:-translate-y-0.5 bg-green-600">
                            ${isEdit ? 'Kemaskini' : 'Simpan'}
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

// 3. LOGIK SIMPAN DATA BORANG
// ============================================================================
// LOGIK SIMPAN KE SERVER MENGGUNAKAN FETCH API
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
            showToast('Menyimpan data ke Pelayan...', 'red');
            setTimeout(() => window.location.reload(), 1000); // Reload agar UI memaparkan data baharu DB
        }
    } catch(err) {
        console.error("Gagal simpan pelanggaran:", err);
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
            showToast('Menyimpan data ke Pelayan...', 'green');
            setTimeout(() => window.location.reload(), 1000); 
        }
    } catch(err) {
        console.error("Gagal simpan prestasi:", err);
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
            showToast('Rekod berjaya dipadam dari sistem.', 'gray');
            setTimeout(() => window.location.reload(), 1000);
        }
    } catch(err) {
        console.error("Gagal padam data:", err);
    }
}

// 4. MODAL PENGESAHAN PADAM (CUSTOM CONFIRM)
function confirmDelete(type, id) {
    const isPelanggaran = type === 'pelanggaran';
    const color = isPelanggaran ? 'red' : 'green';
    const title = isPelanggaran ? 'Padam Rekod Pelanggaran?' : 'Padam Rekod Prestasi?';
    
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm z-[60] flex items-center justify-center p-4 opacity-0 transition-opacity duration-300';
    modal.id = 'deleteModal';
    modal.innerHTML = `
        <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full p-6 text-center transform scale-95 transition-transform duration-300" id="deleteModalContent">
            <div class="w-16 h-16 rounded-full bg-${color}-100 text-${color}-600 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-xl font-black text-gray-800 mb-2">${title}</h3>
            <p class="text-sm text-gray-500 mb-6">Tindakan ini tidak boleh dipulihkan. Adakah anda pasti?</p>
            <div class="flex gap-3 justify-center">
                <button onclick="closeDeleteModal()" class="px-6 py-2.5 border-2 border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-colors">Batal</button>
                <button onclick="executeDelete('${type}', ${id})" class="px-6 py-2.5 bg-${color}-600 text-white font-bold rounded-xl shadow-md hover:bg-${color}-700 transition-colors">Ya, Padam</button>
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

// FUNGSI TOAST NOTIFICATION SUPER SMOOTH
function showToast(message, color) {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-6 right-6 bg-${color}-600 text-white px-6 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 z-[100] transform translate-y-20 opacity-0 transition-all duration-500`;
    toast.innerHTML = `
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        <span class="font-bold text-sm tracking-wide">${message}</span>
    `;
    document.body.appendChild(toast);
    
    // Animate In
    setTimeout(() => {
        toast.classList.remove('translate-y-20', 'opacity-0');
    }, 10);

    // Animate Out
    setTimeout(() => {
        toast.classList.add('translate-y-20', 'opacity-0');
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}