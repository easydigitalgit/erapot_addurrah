/**
 * File: public/assets/js/WaliKelas/validasi-catatan-guru.js
 */

let catatanGuruData = window.dynamicCatatanData || [];

document.addEventListener('DOMContentLoaded', function() {
    renderValidasiUtama();
});

// 1. RENDER KERANGKA TAB & UPDATE STATS 
function renderValidasiUtama() {
    const tabsContainer = document.getElementById('validasiTabsContainer');
    if(!tabsContainer) return;

    // A. Update Angka Statistik
    const totalMenunggu = catatanGuruData.filter(c => c.status === 'Menunggu Validasi').length;
    const totalDisetujui = catatanGuruData.filter(c => c.status === 'Disetujui').length;
    const totalDitolak = catatanGuruData.filter(c => c.status === 'Ditolak').length;

    const elTotal = document.getElementById('statTotal');
    const elMenunggu = document.getElementById('statMenunggu');
    const elDisetujui = document.getElementById('statDisetujui');
    const elDitolak = document.getElementById('statDitolak');

    if(elTotal) elTotal.textContent = catatanGuruData.length;
    if(elMenunggu) elMenunggu.textContent = totalMenunggu;
    if(elDisetujui) elDisetujui.textContent = totalDisetujui;
    if(elDitolak) elDitolak.textContent = totalDitolak;

    // B. Gambar Struktur Tab yang Mendukung Dark Mode
    tabsContainer.innerHTML = `
        <div class="flex gap-0 mb-6 border-b border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-t-xl overflow-x-auto custom-scrollbar">
          <button onclick="switchValidasiTab('semua', this)" class="validasi-tab-active px-6 py-3.5 font-bold flex items-center gap-2 whitespace-nowrap transition-colors">
            Semua Catatan <span class="ml-2 px-2.5 py-0.5 bg-gray-100 dark:bg-slate-700 text-gray-800 dark:text-slate-200 text-xs rounded-full">${catatanGuruData.length}</span>
          </button>
          <button onclick="switchValidasiTab('menunggu', this)" class="validasi-tab-inactive px-6 py-3.5 font-semibold flex items-center gap-2 whitespace-nowrap text-gray-500 dark:text-slate-400 hover:text-gray-800 dark:hover:text-slate-200 transition-colors">
            Menunggu <span class="ml-2 px-2.5 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-500 text-xs rounded-full">${totalMenunggu}</span>
          </button>
          <button onclick="switchValidasiTab('disetujui', this)" class="validasi-tab-inactive px-6 py-3.5 font-semibold flex items-center gap-2 whitespace-nowrap text-gray-500 dark:text-slate-400 hover:text-gray-800 dark:hover:text-slate-200 transition-colors">
            Disetujui <span class="ml-2 px-2.5 py-0.5 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-500 text-xs rounded-full">${totalDisetujui}</span>
          </button>
          <button onclick="switchValidasiTab('ditolak', this)" class="validasi-tab-inactive px-6 py-3.5 font-semibold flex items-center gap-2 whitespace-nowrap text-gray-500 dark:text-slate-400 hover:text-gray-800 dark:hover:text-slate-200 transition-colors">
            Ditolak <span class="ml-2 px-2.5 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-500 text-xs rounded-full">${totalDitolak}</span>
          </button>
        </div>

        <div id="validasi-semua" class="validasi-tab-content block transition-all"></div>
        <div id="validasi-menunggu" class="validasi-tab-content hidden transition-all"></div>
        <div id="validasi-disetujui" class="validasi-tab-content hidden transition-all"></div>
        <div id="validasi-ditolak" class="validasi-tab-content hidden transition-all"></div>
    `;

    // C. Render isinya pertama kali
    filterValidasiCatatan();
}


// 2. FUNGSI FILTER
function filterValidasiCatatan() {
    const searchStudent = document.getElementById('searchStudent')?.value.toLowerCase() || '';
    const filterCategory = document.getElementById('filterCategory')?.value || '';
    const sortValidasi = document.getElementById('sortValidasi')?.value || 'date-new';

    let filtered = catatanGuruData.filter(c => {
        let match = true;
        if (searchStudent && !c.studentName.toLowerCase().includes(searchStudent)) match = false;
        if (filterCategory && (!c.category || !c.category.toLowerCase().includes(filterCategory.toLowerCase()))) match = false;
        return match;
    });

    if (sortValidasi === 'date-new') {
        filtered.sort((a, b) => new Date(b.date) - new Date(a.date));
    } else if (sortValidasi === 'date-old') {
        filtered.sort((a, b) => new Date(a.date) - new Date(b.date));
    } else if (sortValidasi === 'student') {
        filtered.sort((a, b) => a.studentName.localeCompare(b.studentName));
    }

    let currentTab = 'semua';
    const activeContent = document.querySelector('.validasi-tab-content:not(.hidden)');
    if (activeContent) {
        currentTab = activeContent.id.replace('validasi-', '');
    }
    
    renderValidasiContent(currentTab, filtered);
}

function resetFilter() {
    if(document.getElementById('searchStudent')) document.getElementById('searchStudent').value = '';
    if(document.getElementById('filterCategory')) document.getElementById('filterCategory').value = '';
    if(document.getElementById('sortValidasi')) document.getElementById('sortValidasi').value = 'date-new';
    filterValidasiCatatan();
}


// 3. FUNGSI RENDER KONTEN TAB (KARTU)
function switchValidasiTab(tabName, btnElement) {
    document.querySelectorAll('.validasi-tab-content').forEach(tab => {
        tab.classList.add('hidden');
        tab.classList.remove('block');
    });

    document.querySelectorAll('[onclick*="switchValidasiTab"]').forEach(btn => {
        btn.classList.remove('validasi-tab-active', 'text-tema');
        btn.classList.add('validasi-tab-inactive', 'text-gray-500', 'dark:text-slate-400');
    });

    const selectedTab = document.getElementById(`validasi-${tabName}`);
    if (selectedTab) {
        selectedTab.classList.remove('hidden');
        selectedTab.classList.add('block');
    }

    if(btnElement) {
        btnElement.classList.remove('validasi-tab-inactive', 'text-gray-500', 'dark:text-slate-400');
        btnElement.classList.add('validasi-tab-active', 'text-tema');
    }

    filterValidasiCatatan();
}

function renderValidasiContent(type, dataToRender) {
    let data = dataToRender;
    
    if (type === 'menunggu') data = data.filter(c => c.status === 'Menunggu Validasi');
    else if (type === 'disetujui') data = data.filter(c => c.status === 'Disetujui');
    else if (type === 'ditolak') data = data.filter(c => c.status === 'Ditolak');

    const container = document.getElementById(`validasi-${type}`);
    if (!container) return;
    container.innerHTML = '';

    if (data.length === 0) {
      container.innerHTML = `
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 p-12 text-center">
          <div class="w-16 h-16 bg-gray-50 dark:bg-slate-700/50 text-gray-300 dark:text-slate-500 rounded-full flex items-center justify-center mx-auto mb-4">
             <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
          </div>
          <p class="text-gray-500 dark:text-slate-400 font-medium">Tidak ada catatan untuk ditampilkan pada bagian ini.</p>
        </div>
      `;
      return;
    }

    data.forEach(catatan => {
      let categoryIcon = '📝';
      let categoryColor = 'gray'; // blue, purple, amber
      let displayCategory = catatan.category || 'Umum';
      
      if(catatan.category) {
          let catLower = catatan.category.toLowerCase();
          if(catLower.includes('akademik')) { categoryIcon = '📚'; categoryColor = 'blue'; }
          else if(catLower.includes('perilaku') || catLower.includes('akhlak') || catLower.includes('karakter')) { categoryIcon = '👤'; categoryColor = 'purple'; }
          else if(catLower.includes('prestasi')) { categoryIcon = '🏆'; categoryColor = 'amber'; }
      }

      const statusBgColor = catatan.status === 'Menunggu Validasi' ? 'amber' : (catatan.status === 'Disetujui' ? 'emerald' : 'red');

      const card = document.createElement('div');
      card.className = 'bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 p-5 lg:p-6 mb-4 hover:shadow-md transition-shadow';
      card.innerHTML = `
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-4 pb-4 border-b border-gray-100 dark:border-slate-700">
          <div class="flex-1">
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl bg-${categoryColor}-50 dark:bg-${categoryColor}-900/30 border border-${categoryColor}-100 dark:border-${categoryColor}-800/50 shadow-sm flex-shrink-0">
                  ${categoryIcon}
              </div>
              <div>
                <div class="flex items-center gap-2 flex-wrap mb-1.5">
                  <h3 class="text-lg font-bold text-gray-800 dark:text-white">${catatan.studentName}</h3>
                  <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider rounded-md bg-${categoryColor}-100 dark:bg-${categoryColor}-900/40 text-${categoryColor}-700 dark:text-${categoryColor}-400 border border-${categoryColor}-200 dark:border-${categoryColor}-800/50">${displayCategory}</span>
                </div>
                <p class="text-xs text-gray-500 dark:text-slate-400 font-medium">Dari Guru: <span class="text-gray-700 dark:text-slate-300 font-semibold">${catatan.teacher}</span> <span class="mx-1">•</span> Mapel: <span class="text-gray-700 dark:text-slate-300 font-semibold">${catatan.subject}</span></p>
              </div>
            </div>
          </div>
          <div class="flex items-center justify-between md:justify-end gap-2 md:w-auto w-full border-t md:border-t-0 border-gray-100 dark:border-slate-700 pt-3 md:pt-0">
            <span class="text-xs text-gray-400 dark:text-slate-500 font-mono">${catatan.date}</span>
            <span class="px-3 py-1.5 text-[10px] font-black uppercase tracking-wider rounded-lg bg-${statusBgColor}-50 dark:bg-${statusBgColor}-900/20 text-${statusBgColor}-600 dark:text-${statusBgColor}-400 border border-${statusBgColor}-200 dark:border-${statusBgColor}-800/50">${catatan.status}</span>
          </div>
        </div>

        <div class="relative bg-gray-50 dark:bg-slate-900/50 border border-gray-200 dark:border-slate-700 rounded-xl p-4 mb-4">
          <svg class="absolute top-2 right-2 w-5 h-5 text-gray-200 dark:text-slate-700" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
          <p class="text-sm text-gray-700 dark:text-slate-300 leading-relaxed italic pr-6">"${catatan.catatan}"</p>
        </div>

        ${catatan.status === 'Ditolak' ? `
          <div class="flex items-start gap-2 text-xs font-medium text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/10 p-3 rounded-lg border border-red-100 dark:border-red-900/30 mb-2">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <span><strong>Ditolak karena:</strong> ${catatan.reason}</span>
          </div>` : ''}

        ${catatan.status === 'Menunggu Validasi' ? `
          <div class="flex flex-col sm:flex-row gap-3 pt-2">
            <button onclick="approveCatatan(${catatan.id})" class="flex-1 py-2.5 px-4 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl transition-all shadow-md shadow-emerald-500/20 text-sm flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
              Setujui & Terbitkan ke Rapor
            </button>
            <button onclick="openRejectModal(${catatan.id})" class="flex-1 py-2.5 px-4 bg-white dark:bg-slate-800 border-2 border-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 dark:text-red-500 font-bold rounded-xl transition-all text-sm flex items-center justify-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              Kembalikan ke Guru
            </button>
          </div>
        ` : ''}
      `;
      container.appendChild(card);
    });
}

// 4. AKSI TOMBOL (SETUJUI / TOLAK)
function approveCatatan(id) {
    if(!confirm('Validasi: Apakah Anda yakin menyetujui catatan ini untuk dicetak di rapor siswa?')) return;
    
    // (Opsional) Disini nanti Anda tambahkan fungsi AJAX fetch() untuk update DB.
    
    const catatan = catatanGuruData.find(c => c.id === id);
    if (!catatan) return;
    catatan.status = 'Disetujui';
    renderValidasiUtama(); 
    showToast('Catatan berhasil disetujui.', true);
}

// MODAL REJECT CUSTOM MENGGANTIKAN PROMPT()
function openRejectModal(id) {
    document.getElementById('rejectCatatanId').value = id;
    document.getElementById('rejectReason').value = '';
    
    const modal = document.getElementById('rejectModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    setTimeout(() => {
        modal.querySelector('.modal-overlay').classList.remove('opacity-0');
        modal.querySelector('.modal-content').classList.remove('scale-95', 'opacity-0');
    }, 10);
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.querySelector('.modal-overlay').classList.add('opacity-0');
    modal.querySelector('.modal-content').classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 300);
}

function submitReject() {
    const id = parseInt(document.getElementById('rejectCatatanId').value);
    const reason = document.getElementById('rejectReason').value.trim();
    
    if(!reason) {
        alert("Harap masukkan alasan penolakan!");
        document.getElementById('rejectReason').focus();
        return;
    }

    // (Opsional) Disini nanti Anda tambahkan fungsi AJAX fetch() untuk update DB.
    
    const catatan = catatanGuruData.find(c => c.id === id);
    if (catatan) {
        catatan.status = 'Ditolak';
        catatan.reason = reason;
        renderValidasiUtama();
        closeRejectModal();
        showToast('Catatan dikembalikan ke guru.', false);
    }
}

// ==========================================
// 5. TOAST NOTIFICATION
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