// SABUK PENGAMAN
const textObj = window.LANG || {
    drawer_info_title: "Informasi Penilaian",
    drawer_subject: "Mata Pelajaran",
    drawer_target_class: "Target Kelas",
    drawer_input_prog: "Progres Input",
    drawer_quick_action: "Aksi Cepat",
    btn_send_msg: "Kirim Notifikasi Aplikasi",
    js_remind_all: "Reminder berhasil dikirim ke guru yang belum menyelesaikan input nilai",
    js_remind_single: "Reminder berhasil dikirim ke",
    js_exporting: "Mengekspor laporan progres input nilai...",
    js_filter_apply: "Filter berhasil diterapkan",
    js_filter_reset: "Filter direset ke semua kategori",
    js_view_grades: "Membuka data nilai...",
    js_msg_sent: "Pesan berhasil dikirim ke guru",
    js_export_excel: "Mengekspor data nilai ke Excel...",
    js_prep_print: "Mempersiapkan dokumen untuk dicetak...",
    js_server_error: "Terjadi kesalahan sistem",
    js_failed: "Gagal memproses permintaan"
};

function openMobileSidebar() {
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebar-overlay');
  sidebar.classList.add('mobile-open');
  overlay.classList.add('active');
  document.body.style.overflow = 'hidden';
}
function closeMobileSidebar() {
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebar-overlay');
  sidebar.classList.remove('mobile-open');
  overlay.classList.remove('active');
  document.body.style.overflow = '';
}

// 1. UPDATE LACI DETAIL (Dinamisasi Warna)
function showDetail(guru, mapel, kelas, persen, status, badgeType, guruId, fotoProfil) {
  const drawer = document.getElementById('drawerHeader');
  const parentDrawer = document.getElementById('detailDrawer');
  const overlay = document.getElementById('detailDrawerOverlay');
  
  const inisial = guru.substring(0, 2).toUpperCase();
  
  let progressColor = 'text-red-600';
  if (badgeType === 'success') progressColor = 'text-emerald-600';
  else if (badgeType === 'warning') progressColor = 'text-amber-600';

  let btnAction = '';
  // LOGIKA AVATAR GURU (Dinamis Warna Inisial)
  let avatarBoxHTML = '';
  if (fotoProfil && fotoProfil !== 'null' && String(fotoProfil).trim() !== '') {
      const cleanBaseUrl = (typeof BASE_URL !== 'undefined' ? BASE_URL : window.location.origin).replace(/\/$/, '');
      const cacheBuster = '?v=' + new Date().getTime();
      const fotoUrl = `${cleanBaseUrl}/assets/uploads/avatars/${fotoProfil}${cacheBuster}`;
      
      avatarBoxHTML = `
        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-3xl font-black mb-4 shadow-lg overflow-hidden border-4 border-white/50" style="color: var(--warna-scroll);">
            <img src="${fotoUrl}" class="w-full h-full object-cover" onerror="this.onerror=null; this.outerHTML='${inisial}';">
        </div>`;
  } else {
      avatarBoxHTML = `
        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-3xl font-black mb-4 shadow-lg border-4 border-white/50" style="color: var(--warna-scroll);">
            ${inisial}
        </div>`;
  }

  // LACI DINAMIS (Menggunakan style var(--warna-scroll))
  drawer.innerHTML = `
    <div class="px-6 py-8 flex flex-col items-center relative text-white shrink-0" style="background-color: var(--warna-scroll);">
        
        ${avatarBoxHTML}
        
        <h3 class="text-xl font-bold text-center">${guru}</h3>
        <p class="text-white/80 text-sm mb-4">${mapel}</p>
        
        <div class="flex flex-wrap justify-center gap-2 text-sm">
            <span class="px-4 py-1.5 bg-white/20 rounded-full border border-white/30 backdrop-blur-sm">${kelas}</span>
            <span class="px-4 py-1.5 bg-white/20 rounded-full border border-white/30 backdrop-blur-sm">${status}</span>
        </div>
    </div>

    <div class="p-6 overflow-y-auto flex-1">
        <h4 class="text-xs font-bold text-gray-400 mb-3 uppercase tracking-wider">${textObj.drawer_info_title}</h4>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 p-1 mb-6 shadow-sm transition-colors">
            <div class="flex justify-between items-center py-3 px-4 border-b border-gray-50 dark:border-slate-700">
                <span class="text-gray-500 dark:text-slate-400 text-sm">${textObj.drawer_subject}</span>
                <span class="font-medium text-gray-800 dark:text-slate-200 text-sm">${mapel}</span>
            </div>
            <div class="flex justify-between items-center py-3 px-4 border-b border-gray-50 dark:border-slate-700">
                <span class="text-gray-500 dark:text-slate-400 text-sm">${textObj.drawer_target_class}</span>
                <span class="font-medium text-gray-800 dark:text-slate-200 text-sm">${kelas}</span>
            </div>
            <div class="flex justify-between items-center py-3 px-4">
                <span class="text-gray-500 dark:text-slate-400 text-sm">${textObj.drawer_input_prog}</span>
                <span class="font-bold ${progressColor} text-sm">${persen}%</span>
            </div>
        </div>

        <h4 class="text-xs font-bold text-gray-400 mb-3 uppercase tracking-wider">${textObj.drawer_quick_action}</h4>
        <div class="space-y-3">
             ${btnAction}
        </div>
    </div>
  `;
  
  overlay.classList.remove('hidden');
  
  setTimeout(() => {
      overlay.classList.remove('opacity-0');
      overlay.classList.add('opacity-100');
      parentDrawer.classList.remove('translate-x-full');
      parentDrawer.classList.add('translate-x-0');
  }, 10);
  
  document.body.style.overflow = 'hidden';
}

function closeDetailDrawer() {
  const drawer = document.getElementById('detailDrawer');
  const overlay = document.getElementById('detailDrawerOverlay');
  
  if (!drawer || !overlay) return; 

  drawer.classList.remove('translate-x-0', 'active');
  drawer.classList.add('translate-x-full');
  
  overlay.classList.remove('opacity-100', 'active');
  
  setTimeout(() => {
      overlay.classList.add('hidden');
  }, 300);
  
  document.body.style.overflow = '';
}

// 1. KIRIM NOTIFIKASI TUNGGAL KE DATABASE
window.sendReminderSingle = function(guru, mapel, kelas, guruId) {
    if (!guruId || guruId === 'undefined') {
        Swal.fire('Gagal', 'ID Guru tidak ditemukan.', 'error');
        return;
    }

    const baseUrl = window.BASE_URL || window.location.origin + '/';
    
    Swal.fire({
        title: 'Mengirim Notifikasi...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    const formData = new FormData();
    formData.append('guru_id', guruId);
    formData.append('mapel', mapel);
    formData.append('kelas', kelas);
    
    fetch(`${baseUrl}admin/monitoring-input/send-notif`, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({icon: 'success', title: 'Terkirim!', text: `Notifikasi aplikasi untuk ${guru} telah berhasil dikirim.`, timer: 2000, showConfirmButton: false});
            if (typeof closeDetailDrawer === 'function') closeDetailDrawer();
        } else {
            Swal.fire('Gagal', data.message || "Gagal mengirim notifikasi", 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', "Terjadi kesalahan sistem/jaringan.", 'error');
    });
};

// 2. KIRIM NOTIFIKASI MASSAL KE DATABASE
window.sendReminder = function() {
    let targetGurus = [];
    
    // Sabuk Pengaman 1: Jika variabel global jalan, pakai itu.
    if (window.ALL_MONITORING_DATA && window.ALL_MONITORING_DATA.length > 0) {
        targetGurus = window.ALL_MONITORING_DATA.filter(g => parseInt(g.persen) < 100);
    } 
    // Sabuk Pengaman 2: Jika global gagal, kita baca (scraping) dari tombol detail di HTML!
    else {
        const detailButtons = document.querySelectorAll('button[onclick^="showDetail"]');
        detailButtons.forEach(btn => {
            const onclickText = btn.getAttribute('onclick');
            const paramsMatch = onclickText.match(/showDetail\((.*?)\)/);
            if (paramsMatch && paramsMatch[1]) {
                const params = paramsMatch[1].split(',').map(s => s.trim().replace(/^'|'$/g, ''));
                if (params.length >= 7) {
                    const persen = parseInt(params[3]);
                    const guruId = params[6];
                    const namaGuru = params[0];
                    if (persen < 100 && guruId && guruId !== 'undefined') {
                        targetGurus.push({
                            guru_id: guruId,
                            guru: namaGuru,
                            persen: persen
                        });
                    }
                }
            }
        });
    }

    if (targetGurus.length === 0) {
        Swal.fire('Sempurna!', 'Semua guru telah menyelesaikan input nilai (100%) atau data tidak ditemukan.', 'success');
        return;
    }

    // Ekstrak data unik agar tidak spam notif ke 1 guru yang sama berkali-kali
    const uniqueGurus = [];
    const map = new Map();
    for (const item of targetGurus) {
        if (!map.has(item.guru_id) && item.guru_id) {
            map.set(item.guru_id, true);
            uniqueGurus.push({ id: item.guru_id, nama: item.guru });
        }
    }

    if (uniqueGurus.length === 0) {
        Swal.fire("Gagal mendeteksi data ID guru.", "Pastikan format ID guru valid.", 'error');
        return;
    }

    let htmlList = '<ul style="text-align: left; font-size: 14px; max-height: 200px; overflow-y: auto;" class="custom-scrollbar border rounded-lg p-3 bg-gray-50 dark:bg-slate-800">';
    uniqueGurus.forEach(g => {
        htmlList += `<li class="mb-1"><span class="text-amber-500">🔔</span> ${g.nama}</li>`;
    });
    htmlList += '</ul>';

    Swal.fire({
        title: 'Kirim Notifikasi Massal?',
        html: `<p class="mb-3 text-sm text-gray-600 dark:text-gray-300">Lonceng notifikasi akan berbunyi di akun <b>${uniqueGurus.length} Guru</b> berikut:</p> ${htmlList}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#10b981', 
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, Kirim Semua!'
    }).then(async (result) => {
        if (result.isConfirmed) {
            Swal.fire({ title: 'Memproses...', text: 'Mengirim notifikasi ke database', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }});
            
            let successCount = 0;
            const baseUrl = window.BASE_URL || window.location.origin + '/';
            
            // Loop pengiriman menggunakan await
            for (const g of uniqueGurus) {
                const fd = new FormData();
                fd.append('guru_id', g.id);
                fd.append('mapel', ''); 
                fd.append('kelas', '');
                
                try {
                    const res = await fetch(`${baseUrl}admin/monitoring-input/send-notif`, {
                        method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const d = await res.json();
                    if (d.status === 'success') successCount++;
                } catch(e) { console.error("Gagal mengirim ke", g.nama, e); }
            }
            
            Swal.fire('Selesai!', `Berhasil mengirim notifikasi ke ${successCount} akun guru.`, 'success');
        }
    });
};

// FUNGSI UMUM LAINNYA
function exportProgress() { showToast(textObj.js_exporting, 'success'); }
function applyFilter() { showToast(textObj.js_filter_apply, 'success'); }
function resetFilter() {
  const selects = document.querySelectorAll('.filter-bar select');
  selects.forEach(select => select.selectedIndex = 0);
  showToast(textObj.js_filter_reset, 'success');
}
function viewNilai() { showToast(textObj.js_view_grades, 'success'); }
function exportNilai() { showToast(textObj.js_export_excel, 'success'); }
function printNilai() { showToast(textObj.js_prep_print, 'success'); }

function showToast(message, type = 'success') {
  const toast = document.createElement('div');
  toast.className = 'toast fixed top-4 right-4 z-[100000] flex items-center gap-3 px-4 py-3 bg-white dark:bg-slate-800 border-l-4 rounded-xl shadow-2xl transition-all duration-300 transform translate-y-0 opacity-100';
  
  const iconColor = type === 'success' ? 'text-emerald-600 dark:text-emerald-400' : type === 'warning' ? 'text-amber-600 dark:text-amber-400' : 'text-red-600 dark:text-red-400';
  const bgColor = type === 'success' ? 'bg-emerald-100 dark:bg-emerald-900/30' : type === 'warning' ? 'bg-amber-100 dark:bg-amber-900/30' : 'bg-red-100 dark:bg-red-900/30';
  const borderColor = type === 'success' ? 'border-emerald-500' : type === 'warning' ? 'border-amber-500' : 'border-red-500';
  
  toast.classList.add(borderColor);
  
  let iconPath = '';
  if(type === 'success') iconPath = 'M5 13l4 4L19 7';
  else if(type === 'error') iconPath = 'M6 18L18 6M6 6l12 12';
  else iconPath = 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z';

  toast.innerHTML = `
    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 ${bgColor}">
      <svg class="w-6 h-6 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPath}"/>
      </svg>
    </div>
    <div>
      <p class="font-semibold text-gray-800 dark:text-white text-sm">${message}</p>
    </div>
  `;
  document.body.appendChild(toast);
  setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(-20px)';
      setTimeout(() => toast.remove(), 300);
  }, 4000);
}

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    closeDetailDrawer();
  }
});

// =================================================================
// COMPACT PAGINATION (PREV - INDICATOR - NEXT)
// =================================================================
document.addEventListener('DOMContentLoaded', () => {
    setupTablePagination();
});

function setupTablePagination() {
    const tableBody = document.querySelector('#monitoringTable tbody');
    const paginationContainer = document.getElementById('pagination-container');
    
    if (!tableBody || !paginationContainer) return;

    const rows = Array.from(tableBody.querySelectorAll('tr.group'));
    if (rows.length === 0) return; 

    paginationContainer.classList.remove('hidden');
    paginationContainer.classList.add('flex');

    let currentPage = 1;
    const rowsPerPage = 10; 
    const totalPages = Math.ceil(rows.length / rowsPerPage);

    const infoEl = document.getElementById('pagination-info');
    const indicatorEl = document.getElementById('pageIndicator');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    window.displayPage = function(page) {
        // Validasi Halaman
        if (page < 1) page = 1;
        if (page > totalPages) page = totalPages;
        
        currentPage = page;
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        // Potong Baris Tabel
        rows.forEach((row, index) => {
            row.style.display = (index >= start && index < end) ? '' : 'none';
        });

        // Update Info Teks (Kiri)
        const currentEnd = Math.min(end, rows.length);
        if (infoEl) infoEl.innerText = `Data ${start + 1} - ${currentEnd} dari ${rows.length}`;

        // Update Indikator (Tengah)
        if (indicatorEl) indicatorEl.innerText = `${currentPage} / ${totalPages}`;

        // Update Status Tombol (Kiri & Kanan)
        if (prevBtn) prevBtn.disabled = (currentPage === 1);
        if (nextBtn) nextBtn.disabled = (currentPage === totalPages);
    }

    // Pasang Event Listener ke Tombol
    if (prevBtn) prevBtn.onclick = () => window.displayPage(currentPage - 1);
    if (nextBtn) nextBtn.onclick = () => window.displayPage(currentPage + 1);

    // Jalankan Halaman Pertama
    window.displayPage(1); 
}