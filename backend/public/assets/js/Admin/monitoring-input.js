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

// 1. UPDATE LACI DETAIL (Tombol aksi mengarah ke Notifikasi Internal)
function showDetail(guru, mapel, kelas, persen, status, badgeType, guruId) {
  const drawer = document.getElementById('drawerHeader');
  const parentDrawer = document.getElementById('detailDrawer');
  const overlay = document.getElementById('detailDrawerOverlay');
  
  const inisial = guru.substring(0, 2).toUpperCase();
  
  let progressColor = 'text-red-600';
  if (badgeType === 'success') progressColor = 'text-emerald-600';
  else if (badgeType === 'warning') progressColor = 'text-amber-600';

  // Siapkan Tombol Aksi (Hanya muncul jika belum 100%)
  let btnAction = '';
  if (persen < 100) {
      btnAction = `<button onclick="sendReminderSingle('${guru.replace(/'/g, "\\'")}', '${mapel}', '${kelas}', '${guruId}')" class="w-full py-3 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 font-semibold rounded-xl hover:bg-amber-100 dark:hover:bg-amber-900/50 transition-colors text-sm border border-amber-100 dark:border-amber-800/50 flex items-center justify-center gap-2 outline-none">
                    <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    Kirim Notifikasi Aplikasi
                 </button>`;
  } else {
      btnAction = `<div class="p-3 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-xl text-center text-sm font-semibold border border-emerald-100 dark:border-emerald-800/50">Tugas Guru Telah Selesai ✅</div>`;
  }

  drawer.innerHTML = `
    <div class="bg-blue-600 px-6 py-8 flex flex-col items-center relative text-white shrink-0">
        
        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-blue-600 text-3xl font-black mb-4 shadow-lg">
            ${inisial}
        </div>
        
        <h3 class="text-xl font-bold text-center">${guru}</h3>
        <p class="text-blue-100 text-sm mb-4">${mapel}</p>
        
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
    // --- PERBAIKAN: CARA MENGAMBIL DATA DARI TABEL HTML ---
    // Karena kadang window.ALL_MONITORING_DATA gagal terload (null/undefined), 
    // kita gunakan strategi "Fallback": baca data langsung dari button di HTML tabel!
    
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
            // Contoh format: showDetail('Guru A', 'Mapel', 'Kelas', '90', 'Proses', 'warning', '15')
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