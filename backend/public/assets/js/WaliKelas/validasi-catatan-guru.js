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

    // B. Gambar Struktur Tab
    tabsContainer.innerHTML = `
        <div class="flex gap-0 mb-6 border-b border-gray-200 bg-white rounded-t-xl overflow-x-auto">
          <button onclick="switchValidasiTab('semua', this)" class="validasi-tab-active px-6 py-3 font-bold flex items-center gap-2 whitespace-nowrap border-b-2">
            Semua Catatan <span class="ml-2 px-2 py-0.5 bg-gray-200 text-gray-800 text-xs rounded-full">${catatanGuruData.length}</span>
          </button>
          <button onclick="switchValidasiTab('menunggu', this)" class="validasi-tab-inactive px-6 py-3 font-semibold flex items-center gap-2 whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-800">
            Menunggu <span class="ml-2 px-2 py-0.5 bg-amber-100 text-amber-800 text-xs rounded-full">${totalMenunggu}</span>
          </button>
          <button onclick="switchValidasiTab('disetujui', this)" class="validasi-tab-inactive px-6 py-3 font-semibold flex items-center gap-2 whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-800">
            Disetujui <span class="ml-2 px-2 py-0.5 bg-green-100 text-green-800 text-xs rounded-full">${totalDisetujui}</span>
          </button>
          <button onclick="switchValidasiTab('ditolak', this)" class="validasi-tab-inactive px-6 py-3 font-semibold flex items-center gap-2 whitespace-nowrap border-b-2 border-transparent text-gray-500 hover:text-gray-800">
            Ditolak <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-800 text-xs rounded-full">${totalDitolak}</span>
          </button>
        </div>

        <div id="validasi-semua" class="validasi-tab-content block animate-fade-in"></div>
        <div id="validasi-menunggu" class="validasi-tab-content hidden animate-fade-in"></div>
        <div id="validasi-disetujui" class="validasi-tab-content hidden animate-fade-in"></div>
        <div id="validasi-ditolak" class="validasi-tab-content hidden animate-fade-in"></div>
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
        btn.classList.remove('validasi-tab-active', 'border-tema', 'bg-tema-light', 'text-tema');
        btn.classList.add('validasi-tab-inactive', 'border-transparent', 'text-gray-500');
    });

    const selectedTab = document.getElementById(`validasi-${tabName}`);
    if (selectedTab) {
        selectedTab.classList.remove('hidden');
        selectedTab.classList.add('block');
    }

    if(btnElement) {
        btnElement.classList.remove('validasi-tab-inactive', 'border-transparent', 'text-gray-500');
        btnElement.classList.add('validasi-tab-active', 'border-tema', 'bg-tema-light', 'text-tema');
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
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
          <p class="text-gray-500 text-lg">Tidak ada catatan untuk ditampilkan pada bagian ini.</p>
        </div>
      `;
      return;
    }

    data.forEach(catatan => {
      let categoryIcon = '📝';
      let categoryColor = 'gray';
      let displayCategory = catatan.category || 'Umum';
      
      if(catatan.category) {
          let catLower = catatan.category.toLowerCase();
          if(catLower.includes('akademik')) { categoryIcon = '📚'; categoryColor = 'blue'; }
          else if(catLower.includes('perilaku') || catLower.includes('akhlak') || catLower.includes('karakter')) { categoryIcon = '👤'; categoryColor = 'purple'; }
          else if(catLower.includes('prestasi')) { categoryIcon = '🏆'; categoryColor = 'amber'; }
      }

      const statusBgColor = catatan.status === 'Menunggu Validasi' ? 'amber' : (catatan.status === 'Disetujui' ? 'emerald' : 'red');

      const dateStr = catatan.date ? new Date(catatan.date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) : 'Tanggal tidak diketahui';

      const card = document.createElement('div');
      card.className = 'bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-4 hover:shadow-md transition-shadow animate-fade-in';
      card.innerHTML = `
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-4 pb-4 border-b border-gray-100">
          <div class="flex-1">
            <div class="flex items-start gap-4">
              <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl bg-${categoryColor}-50 border border-${categoryColor}-100">
                  ${categoryIcon}
              </div>
              <div>
                <div class="flex items-center gap-2 flex-wrap mb-1">
                  <h3 class="text-lg font-bold text-gray-800">${catatan.studentName}</h3>
                  <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">${catatan.class}</span>
                  <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider rounded-full bg-${categoryColor}-100 text-${categoryColor}-700">${displayCategory}</span>
                </div>
                <p class="text-sm text-gray-600 font-medium"><strong>Oleh:</strong> ${catatan.teacher} | <strong>Mapel:</strong> ${catatan.subject}</p>
              </div>
            </div>
          </div>
          <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 text-xs font-bold uppercase tracking-wider rounded-full bg-${statusBgColor}-100 text-${statusBgColor}-700 border border-${statusBgColor}-200">${catatan.status}</span>
          </div>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 mb-4 relative">
          <p class="text-sm text-gray-800 leading-relaxed font-medium italic">"${catatan.catatan}"</p>
        </div>

        <div class="flex flex-wrap items-center justify-between text-xs text-gray-500 mb-2 font-medium gap-2">
          <span>📅 Masuk pada: ${dateStr}</span>
          ${catatan.status === 'Ditolak' ? `<span class="text-red-600 font-bold bg-red-50 px-2 py-1 rounded">⚠ Alasan Tolak: ${catatan.reason}</span>` : ''}
        </div>

        ${catatan.status === 'Menunggu Validasi' ? `
          <div class="flex gap-3 pt-5 border-t border-gray-100 mt-2">
            <button onclick="approveCatatan(${catatan.id})" class="flex-1 py-2.5 px-4 bg-emerald-600 text-white font-bold rounded-lg hover:bg-emerald-700 transition-colors shadow-sm text-sm">
              ✓ Setujui Catatan
            </button>
            <button onclick="rejectCatatan(${catatan.id})" class="flex-1 py-2.5 px-4 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm text-sm">
              ✕ Tolak / Revisi
            </button>
          </div>
        ` : ''}
      `;
      container.appendChild(card);
    });
}

// 4. AKSI TOMBOL (SETUJUI / TOLAK)
function approveCatatan(id) {
    if(!confirm('Apakah Anda yakin ingin menyetujui catatan ini untuk dicetak di rapor?')) return;
    const catatan = catatanGuruData.find(c => c.id === id);
    if (!catatan) return;
    catatan.status = 'Disetujui';
    renderValidasiUtama(); 
}

function rejectCatatan(id) {
    const alasan = prompt('Alasan menolak catatan ini (Misal: Terlalu singkat / Mengandung kata tidak pantas):');
    if(alasan === null) return;
    const catatan = catatanGuruData.find(c => c.id === id);
    if (!catatan) return;
    catatan.status = 'Ditolak';
    catatan.reason = alasan;
    renderValidasiUtama();
}