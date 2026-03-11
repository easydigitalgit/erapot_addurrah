let studentsData = typeof serverStudents !== 'undefined' ? serverStudents : [];
let catatanWaliKelasData = typeof serverCatatan !== 'undefined' ? serverCatatan : [];

document.addEventListener('DOMContentLoaded', function() {
    renderCatatanWaliKelas();
});

function renderCatatanWaliKelas() {
    const mainContent = document.getElementById('mainContent');
    const totalCatatan = catatanWaliKelasData.length;
    const catatanPositif = catatanWaliKelasData.filter(c => ['Akademik', 'Bakat'].includes(c.category)).length;
    const catatanPerhatian = catatanWaliKelasData.filter(c => c.priority === 'Tinggi').length;

    mainContent.innerHTML = `
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-5 mb-8">
            <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 hover:-translate-y-1 transition-transform group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 mb-1 uppercase tracking-wider">Keseluruhan</p>
                        <p class="text-3xl font-black text-gray-800">${totalCatatan}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 hover:-translate-y-1 transition-transform group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 mb-1 uppercase tracking-wider">Catatan Positif</p>
                        <p class="text-3xl font-black text-green-600">${catatanPositif}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 hover:-translate-y-1 transition-transform group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 mb-1 uppercase tracking-wider">Perhatian Khusus</p>
                        <p class="text-3xl font-black text-red-600">${catatanPerhatian}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl p-5 shadow-sm border border-gray-100 hover:-translate-y-1 transition-transform group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 mb-1 uppercase tracking-wider">Jumlah Pelajar</p>
                        <p class="text-3xl font-black text-purple-600">${studentsData.length}</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-2 mb-6 border-b border-gray-200 bg-white rounded-t-2xl overflow-x-auto p-2">
            <button onclick="switchTab('overview')" class="tab-active px-6 py-2.5 font-bold text-sm rounded-xl transition-colors bg-tema-light text-tema">
                Analitik
            </button>
            <button onclick="switchTab('semua')" class="tab-inactive px-6 py-2.5 font-bold text-sm rounded-xl transition-colors text-gray-500 hover:bg-gray-50">
                Papan Catatan
            </button>
        </div>

        <div id="tab-overview" class="tab-content">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-black text-gray-800 mb-5">📊 Taburan Kategori Catatan</h3>
                    <div class="space-y-4" id="categoryStats"></div>
                </div>
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-100 rounded-3xl p-6 relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 opacity-10 text-emerald-600">
                        <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="text-lg font-black text-emerald-900 mb-4 relative z-10">💡 Rekomendasi Pintar AI</h3>
                    <div class="space-y-3 text-sm text-emerald-800 relative z-10">
                        <div class="flex gap-3 bg-white/60 p-3 rounded-xl border border-white/50">
                            <span class="font-black text-emerald-600">1.</span>
                            <p>Terdapat <span class="font-bold text-red-600">${catatanPerhatian} pelajar</span> berstatus kritikal yang memerlukan campur tangan serta-merta.</p>
                        </div>
                        <div class="flex gap-3 bg-white/60 p-3 rounded-xl border border-white/50">
                            <span class="font-black text-emerald-600">2.</span>
                            <p>Sila pastikan rekod "Tanggapan Orang Tua" diisi bagi memudahkan perbincangan semasa penyerahan Buku Rapor kelak.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="tab-semua" class="tab-content hidden">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <input type="text" id="searchCWK" placeholder="Cari nama pelajar..." class="px-4 py-2.5 bg-gray-50 border-none rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-tema w-full" onkeyup="filterCatatan()">
                    <select id="filterCategory" class="px-4 py-2.5 bg-gray-50 border-none rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-tema w-full cursor-pointer" onchange="filterCatatan()">
                        <option value="">Semua Kategori</option>
                        <option value="Akademik">Akademik</option>
                        <option value="Sosial">Sosial / Perilaku</option>
                        <option value="Bakat">Bakat & Minat</option>
                    </select>
                    <select id="filterPriority" class="px-4 py-2.5 bg-gray-50 border-none rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-tema w-full cursor-pointer" onchange="filterCatatan()">
                        <option value="">Semua Tahap</option>
                        <option value="Rendah">Biasa</option>
                        <option value="Sedang">Sedang (Perhatian)</option>
                        <option value="Tinggi">Tinggi (Kritikal)</option>
                    </select>
                </div>
            </div>
            <div id="catatanList" class="grid grid-cols-1 md:grid-cols-2 gap-5"></div>
        </div>

        <div class="fixed bottom-6 right-6 z-30">
            <button onclick="openFormModal()" class="px-6 py-3.5 bg-tema text-white font-bold rounded-2xl shadow-[0_8px_20px_-6px_var(--warna-primary)] hover:shadow-[0_12px_25px_-6px_var(--warna-primary)] transition-all transform hover:-translate-y-1 flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span class="hidden sm:inline tracking-wide">Catat Rekod Baharu</span>
            </button>
        </div>
    `;

    renderCategoryStats();
    renderCatatanList();
}

function renderCategoryStats() {
    const stats = {};
    catatanWaliKelasData.forEach(c => stats[c.category] = (stats[c.category] || 0) + 1);

    const container = document.getElementById('categoryStats');
    if (Object.keys(stats).length === 0) {
        container.innerHTML = `<p class="text-sm text-gray-400 font-medium">Tiada data rekod buat masa ini.</p>`;
        return;
    }

    container.innerHTML = Object.entries(stats).map(([cat, count]) => {
        const pct = Math.round((count / catatanWaliKelasData.length) * 100);
        return `
            <div>
                <div class="flex justify-between mb-1">
                    <span class="text-sm font-bold text-gray-700">${cat}</span>
                    <span class="text-xs font-black text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md">${count} Rekod</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                    <div class="bg-tema h-full rounded-full transition-all duration-1000 ease-out" style="width: ${pct}%"></div>
                </div>
            </div>
        `;
    }).join('');
}

function renderCatatanList() {
    const container = document.getElementById('catatanList');
    if(!container) return;

    const search = document.getElementById('searchCWK')?.value.toLowerCase() || '';
    const category = document.getElementById('filterCategory')?.value || '';
    const priority = document.getElementById('filterPriority')?.value || '';

    const filtered = catatanWaliKelasData.filter(c => {
        return (!search || c.studentName.toLowerCase().includes(search)) &&
               (!category || c.category === category) &&
               (!priority || c.priority === priority);
    });

    if (filtered.length === 0) {
        container.innerHTML = '<div class="col-span-full text-center py-10"><p class="text-gray-400 font-bold">Tiada padanan data ditemui.</p></div>';
        return;
    }

    container.innerHTML = filtered.map(c => {
        const pColor = c.priority === 'Tinggi' ? 'red' : c.priority === 'Sedang' ? 'amber' : 'green';
        return `
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow relative overflow-hidden flex flex-col h-full">
                <div class="absolute top-0 left-0 w-full h-1 bg-${pColor}-400"></div>
                
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-black text-gray-800">${c.studentName}</h3>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">${c.date}</p>
                    </div>
                    <div class="flex gap-1.5 flex-col items-end">
                        <span class="px-2.5 py-1 text-[9px] font-black rounded-lg bg-blue-50 text-blue-600 uppercase tracking-wider">${c.category}</span>
                        <span class="px-2.5 py-1 text-[9px] font-black rounded-lg bg-${pColor}-50 text-${pColor}-600 uppercase tracking-wider">${c.priority}</span>
                    </div>
                </div>
                
                <div class="mb-4 flex-grow">
                    <p class="text-sm font-semibold text-gray-600 mb-1">Catatan Wali Kelas:</p>
                    <p class="text-sm text-gray-800 leading-relaxed italic border-l-2 border-gray-200 pl-3">"${c.note}"</p>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-2xl mb-4 border border-gray-100">
                    <p class="text-xs font-bold text-gray-600 uppercase tracking-wide mb-1 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Tindakan / Respon Penjaga:
                    </p>
                    <p class="text-xs text-gray-700 font-medium">${c.followUp}</p>
                </div>
                
                <div class="flex gap-2 pt-3 border-t border-gray-100 mt-auto">
                    <button onclick="openFormModal(${c.id})" class="flex-1 py-2 px-4 bg-gray-50 text-gray-600 font-bold rounded-xl hover:bg-tema hover:text-white transition-colors text-sm border border-gray-200 hover:border-transparent">
                        Edit
                    </button>
                    <button onclick="confirmDelete(${c.id})" class="py-2 px-4 bg-red-50 text-red-600 font-bold rounded-xl hover:bg-red-600 hover:text-white transition-colors text-sm border border-red-100 hover:border-transparent">
                        Padam
                    </button>
                </div>
            </div>
        `;
    }).join('');
}

function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(t => t.classList.add('hidden'));
    document.querySelectorAll('[onclick*="switchTab"]').forEach(btn => {
        btn.classList.remove('bg-tema-light', 'text-tema');
        btn.classList.add('text-gray-500');
    });

    document.getElementById(`tab-${tabName}`).classList.remove('hidden');
    event.target.closest('button').classList.remove('text-gray-500');
    event.target.closest('button').classList.add('bg-tema-light', 'text-tema');

    if (tabName === 'semua') renderCatatanList();
}

function filterCatatan() { renderCatatanList(); }

// ==========================================
// MODAL BORANG (TAMBAH / EDIT)
// ==========================================
function openFormModal(editId = null) {
    const isEdit = editId !== null;
    let data = { studentId: '', category: 'Akademik', priority: 'Rendah', status: 'Baru', note: '', followUp: '', date: new Date().toISOString().split('T')[0] };
    
    if (isEdit) {
        const found = catatanWaliKelasData.find(c => c.id === editId);
        if(found) data = { ...found };
    }

    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto opacity-0 transition-opacity duration-300';
    modal.id = 'formModal';
    modal.innerHTML = `
        <div class="bg-white rounded-3xl shadow-2xl max-w-xl w-full transform scale-95 transition-transform duration-300 overflow-hidden" id="modalContent">
            <div class="px-8 py-6 flex items-center justify-between text-white bg-tema" style="background-image: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 100%);">
                <div>
                    <h2 class="text-2xl font-black tracking-tight">${isEdit ? 'Kemaskini' : 'Tambah'} Catatan Baru</h2>
                    <p class="text-xs opacity-90 mt-1 font-medium tracking-widest uppercase">Rekod Profil Pelajar</p>
                </div>
                <button onclick="closeFormModal()" class="p-2 hover:bg-white/20 rounded-2xl transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="p-8 bg-gray-50/50">
                <form onsubmit="saveCatatan(event, ${editId})">
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Nama Pelajar</label>
                        <select id="f_student" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-bold text-gray-800 focus:outline-none focus:border-tema bg-white">
                            <option value="">-- Pilih Pelajar Terlebih Dahulu --</option>
                            ${studentsData.map(s => `<option value="${s.id}" ${data.studentId == s.id ? 'selected' : ''}>${s.name}</option>`).join('')}
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-5 mb-5">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Kategori</label>
                            <select id="f_category" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-bold text-gray-800 focus:outline-none focus:border-tema bg-white">
                                <option value="Akademik" ${data.category === 'Akademik' ? 'selected' : ''}>Kecemerlangan Akademik</option>
                                <option value="Sosial" ${data.category === 'Sosial' ? 'selected' : ''}>Sikap & Perilaku</option>
                                <option value="Bakat" ${data.category === 'Bakat' ? 'selected' : ''}>Bakat & Minat</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Tahap Perhatian</label>
                            <select id="f_priority" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-bold text-gray-800 focus:outline-none focus:border-tema bg-white">
                                <option value="Rendah" ${data.priority === 'Rendah' ? 'selected' : ''}>Biasa (Hijau)</option>
                                <option value="Sedang" ${data.priority === 'Sedang' ? 'selected' : ''}>Perhatian (Kuning)</option>
                                <option value="Tinggi" ${data.priority === 'Tinggi' ? 'selected' : ''}>Kritikal (Merah)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Teks Catatan (Rapor)</label>
                        <textarea id="f_note" required rows="3" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-medium text-gray-800 focus:outline-none focus:border-tema bg-white resize-none" placeholder="Tuliskan pesanan mendidik yang membina...">${data.note}</textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wider">Tindak Lanjut / Respon Ibu Bapa</label>
                        <textarea id="f_followup" rows="2" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl font-medium text-gray-800 focus:outline-none focus:border-tema bg-white resize-none" placeholder="Boleh dikosongkan jika belum ada">${data.followUp}</textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeFormModal()" class="px-6 py-3 border-2 border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-100 transition-colors">Batal</button>
                        <button type="submit" class="px-8 py-3 text-white font-bold rounded-xl shadow-md hover:shadow-lg transition-transform hover:-translate-y-0.5 bg-tema flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            ${isEdit ? 'Kemaskini' : 'Simpan Data'}
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

async function saveCatatan(e, editId) {
    e.preventDefault();
    const payload = {
        id: editId !== null ? editId : null,
        studentId: document.getElementById('f_student').value,
        category: document.getElementById('f_category').value,
        priority: document.getElementById('f_priority').value,
        status: 'Baru',
        note: document.getElementById('f_note').value,
        followUp: document.getElementById('f_followup').value
    };

    try {
        const response = await fetch(BASE_URL + '/wali/catatan-walikelas/save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        
        if(response.ok) {
            closeFormModal();
            showToast('Menyimpan Data ke Pelayan...', 'tema');
            setTimeout(() => window.location.reload(), 1000); 
        }
    } catch(err) {
        console.error("Gagal simpan:", err);
    }
}

function confirmDelete(id) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm z-[60] flex items-center justify-center p-4 opacity-0 transition-opacity duration-300';
    modal.id = 'deleteModal';
    modal.innerHTML = `
        <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full p-6 text-center transform scale-95 transition-transform duration-300" id="deleteModalContent">
            <div class="w-16 h-16 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="text-xl font-black text-gray-800 mb-2">Padam Catatan Ini?</h3>
            <p class="text-sm text-gray-500 mb-6">Nota ini akan dipadam kekal dari Buku Rapor pelajar.</p>
            <div class="flex gap-3 justify-center">
                <button onclick="document.getElementById('deleteModal').remove()" class="px-6 py-2.5 border-2 border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-colors">Batal</button>
                <button onclick="executeDelete(${id})" class="px-6 py-2.5 bg-red-600 text-white font-bold rounded-xl shadow-md hover:bg-red-700 transition-colors">Ya, Padam</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
    setTimeout(() => { modal.classList.remove('opacity-0'); document.getElementById('deleteModalContent').classList.remove('scale-95'); }, 10);
}

async function executeDelete(id) {
    try {
        const response = await fetch(BASE_URL + '/wali/catatan-walikelas/delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id: id })
        });
        
        if(response.ok) {
            document.getElementById('deleteModal').remove();
            showToast('Rekod berjaya dipadam.', 'gray');
            setTimeout(() => window.location.reload(), 1000);
        }
    } catch(err) {
        console.error("Gagal padam:", err);
    }
}

function showToast(message, colorClass) {
    const isTema = colorClass === 'tema';
    const bgClass = isTema ? 'bg-tema' : `bg-${colorClass}-600`;

    const toast = document.createElement('div');
    toast.className = `fixed bottom-6 right-6 ${bgClass} text-white px-6 py-4 rounded-2xl shadow-[0_10px_25px_-5px_rgba(0,0,0,0.2)] flex items-center gap-3 z-[100] transform translate-y-20 opacity-0 transition-all duration-500`;
    toast.innerHTML = `
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span class="font-bold text-sm tracking-wide">${message}</span>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => toast.classList.remove('translate-y-20', 'opacity-0'), 10);
    setTimeout(() => {
        toast.classList.add('translate-y-20', 'opacity-0');
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}