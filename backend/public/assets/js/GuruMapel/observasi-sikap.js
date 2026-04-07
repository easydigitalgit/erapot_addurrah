// ==========================================
// SABUK PENGAMAN (FALLBACK)
// ==========================================
const LANG = window.LANG || {
    empty_data: "Belum ada data observasi", advanced_act: "Aksi Lanjutan", delete_perm: "Hapus Permanen",
    err_incomplete: "⚠️ Mohon lengkapi semua data!", err_quick_inc: "⚠️ Pilih minimal 1 siswa dan lengkapi data!",
    succ_saved: "✓ Observasi berhasil disimpan!", del_confirm: "Yakin ingin menghapus catatan observasi ini?", succ_del: "🗑️ Data observasi dihapus!"
};

let currentMode = "persiswa";
let selectedSkala = null;
let dataObservasi = [];

function fetchData() {
    fetch(`${URL_GET_DATA}?rombel_id=${ACTIVE_ROMBEL_ID}&mapel_id=${ACTIVE_MAPEL_ID}`, {
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(res => res.json())
    .then(res => {
        if(res.status === 'success') {
            dataObservasi = res.data;
            renderTable();
            renderTimeline();
        }
    })
    .catch(err => console.error(err));
}

function getParamIcon(param) {
    const icons = {
        'disiplin': '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
        'kerjasama': '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
        'kejujuran': '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>',
        'tanggung-jawab': '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>',
        'sopan-santun': '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" /></svg>',
        'kepedulian': '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>',
        'ketaatan-ibadah': '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>'
    };
    return icons[param] || icons['disiplin'];
}

function getParamBadgeClass(param) {
    const badges = {
        'disiplin': 'bg-blue-50 text-blue-700 border-blue-200 dark:!bg-blue-900/30 dark:!text-blue-400 dark:!border-blue-800/50',
        'kerjasama': 'bg-purple-50 text-purple-700 border-purple-200 dark:!bg-purple-900/30 dark:!text-purple-400 dark:!border-purple-800/50',
        'kejujuran': 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:!bg-emerald-900/30 dark:!text-emerald-400 dark:!border-emerald-800/50',
        'tanggung-jawab': 'bg-rose-50 text-rose-700 border-rose-200 dark:!bg-rose-900/30 dark:!text-rose-400 dark:!border-rose-800/50',
        'sopan-santun': 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:!bg-indigo-900/30 dark:!text-indigo-400 dark:!border-indigo-800/50',
        'kepedulian': 'bg-amber-50 text-amber-700 border-amber-200 dark:!bg-amber-900/30 dark:!text-amber-400 dark:!border-amber-800/50',
        'ketaatan-ibadah': 'bg-teal-50 text-teal-700 border-teal-200 dark:!bg-teal-900/30 dark:!text-teal-400 dark:!border-teal-800/50'
    };
    return badges[param] || 'bg-gray-50 text-gray-700 border-gray-200 dark:!bg-slate-700 dark:!text-slate-300 dark:!border-slate-600';
}

function getSkalaBadgeClass(skala) {
    if (skala === 'sangat-baik') return 'bg-emerald-100 text-emerald-700 border-emerald-300 dark:!bg-emerald-900/30 dark:!text-emerald-400 dark:!border-emerald-800/50';
    if (skala === 'baik') return 'bg-blue-100 text-blue-700 border-blue-300 dark:!bg-blue-900/30 dark:!text-blue-400 dark:!border-blue-800/50';
    if (skala === 'cukup') return 'bg-amber-100 text-amber-700 border-amber-300 dark:!bg-amber-900/30 dark:!text-amber-400 dark:!border-amber-800/50';
    if (skala === 'perlu-pembinaan') return 'bg-rose-100 text-rose-700 border-rose-300 dark:!bg-rose-900/30 dark:!text-rose-400 dark:!border-rose-800/50';
    return 'bg-gray-100 text-gray-700 border-gray-300 dark:!bg-slate-700 dark:!text-slate-300 dark:!border-slate-600';
}

function formatTeks(teks) {
    if (!teks) return '';
    return teks.split('-').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
}

function renderTable() {
    const tbody = document.getElementById("observasiTableBody");
    tbody.innerHTML = "";
    
    if(dataObservasi.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-10 font-bold text-gray-500 dark:!text-slate-400 bg-white dark:!bg-slate-800 transition-colors">${LANG.empty_data}</td></tr>`;
        return;
    }

    dataObservasi.forEach((obs, index) => {
        const icon = getParamIcon(obs.parameter_sikap);
        const paramClass = getParamBadgeClass(obs.parameter_sikap);
        const paramLabel = formatTeks(obs.parameter_sikap);
        const skalaLabel = formatTeks(obs.skala);
        const skalaClass = getSkalaBadgeClass(obs.skala);
        
        const tr = document.createElement("tr");
        // PERBAIKAN: Menambahkan class bg-white dan dark:!bg-slate-800 agar tabel tidak transparan/putih di darkmode
        tr.className = `bg-white dark:!bg-slate-800 hover:bg-gray-50 dark:hover:!bg-slate-700/50 border-b border-gray-100 dark:!border-slate-700/50 transition-colors group`;
        
        tr.innerHTML = `
            <td class="font-bold text-gray-700 dark:!text-slate-400 py-4 px-6 text-center transition-colors">${index + 1}</td>
            <td class="font-bold text-gray-900 dark:!text-white py-4 px-6 group-hover:text-[var(--warna-primary)] transition-colors">${obs.nama_lengkap}</td>
            <td class="py-4 px-6">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider border transition-colors shadow-sm ${paramClass}">
                    ${icon} ${paramLabel}
                </span>
            </td>
            <td class="py-4 px-6 text-center">
                <span class="inline-flex items-center justify-center px-3 py-1 rounded text-[10px] font-black uppercase tracking-wider border transition-colors shadow-sm ${skalaClass}">
                    ${skalaLabel}
                </span>
            </td>
            <td class="text-xs text-gray-600 dark:!text-slate-300 font-medium py-4 px-6 leading-relaxed transition-colors">${obs.catatan}</td>
            <td class="text-[10px] text-gray-500 dark:!text-slate-400 font-bold uppercase tracking-widest py-4 px-6 transition-colors">${obs.tanggal}</td>
            <td class="py-4 px-6 text-center">
                <button onclick="expandRow(this, ${obs.id})" class="text-emerald-600 dark:!text-emerald-400 hover:text-emerald-700 dark:hover:!text-emerald-300 p-2 rounded-lg hover:bg-emerald-50 dark:hover:!bg-emerald-900/30 transition-colors outline-none focus:ring-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function renderTimeline() {
    const container = document.getElementById("timelineContainer");
    container.innerHTML = "";
    
    const recent = dataObservasi.slice(0, 3);
    
    if(recent.length === 0) {
        container.innerHTML = `<p class="text-sm font-bold text-gray-400 dark:!text-slate-500 text-center py-4 transition-colors">${LANG.empty_data}</p>`;
        return;
    }

    recent.forEach(obs => {
        const paramLabel = formatTeks(obs.parameter_sikap);
        const skalaLabel = formatTeks(obs.skala);
        const skalaClass = getSkalaBadgeClass(obs.skala);

        container.innerHTML += `
            <div class="relative pl-6 py-4 border-l-2 border-gray-200 dark:!border-slate-700 last:border-0 transition-colors">
                <div class="absolute left-[-9px] top-5 w-4 h-4 rounded-full border-4 border-white dark:!border-slate-800 shadow-sm transition-colors" style="background-color: ${THEME_COLOR}"></div>
                <div class="bg-white dark:!bg-slate-700/50 border border-gray-100 dark:!border-slate-600 rounded-2xl p-4 shadow-sm transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <p class="font-bold text-gray-900 dark:!text-white text-sm transition-colors">${obs.nama_lengkap}</p>
                        <span class="text-[10px] text-gray-500 dark:!text-slate-400 font-bold uppercase tracking-widest transition-colors">${obs.tanggal}</span>
                    </div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-bold text-gray-700 dark:!text-slate-300 transition-colors">${paramLabel}</span>
                        <span class="text-gray-300 dark:!text-slate-600 transition-colors">|</span>
                        <span class="inline-flex px-2 py-0.5 text-[9px] font-black uppercase tracking-wider rounded border transition-colors shadow-sm ${skalaClass}">${skalaLabel}</span>
                    </div>
                    <p class="text-xs text-gray-600 dark:!text-slate-400 font-medium italic transition-colors">"${obs.catatan}"</p>
                </div>
            </div>
        `;
    });
}

function switchMode(mode) {
  currentMode = mode;
  // Pastikan warna kembali normal saat tidak aktif
  document.querySelectorAll(".mode-toggle button").forEach(btn => {
      btn.classList.remove("active", "dark:[&.active]:!text-white");
  });
  event.target.classList.add("active", "dark:[&.active]:!text-white");
  if (mode === "massal") openQuickModal();
}

// ==========================================
// PERBAIKAN LOGIKA BUKA/TUTUP MODAL
// ==========================================
// Menghapus 'hidden' dan menambah 'flex' agar Tailwind merendernya
// ==========================================
// PERBAIKAN LOGIKA BUKA/TUTUP MODAL
// ==========================================

function tambahObservasi() {
    const modal = document.getElementById("observasiModal");
    const content = modal.querySelector('.transform'); // Ambil elemen dalam
    
    modal.classList.remove("hidden");
    modal.classList.add("flex");
    document.body.style.overflow = "hidden";
    
    // Beri sedikit jeda agar transisi Tailwind terbaca
    setTimeout(() => {
        content.classList.remove("opacity-0", "scale-95");
        content.classList.add("opacity-100", "scale-100");
    }, 10);
}

function closeObservasiModal() {
    const modal = document.getElementById("observasiModal");
    const content = modal.querySelector('.transform');
    
    // Jalankan animasi keluar dulu
    content.classList.remove("opacity-100", "scale-100");
    content.classList.add("opacity-0", "scale-95");
    
    // Tunggu durasi animasi (300ms) selesai baru hilangkan modal
    setTimeout(() => {
        modal.classList.add("hidden");
        modal.classList.remove("flex");
        document.body.style.overflow = "";
        
        // Reset Form
        document.getElementById("modalSiswa").value = "";
        document.querySelectorAll('input[name="parameter"]').forEach(i => i.checked = false);
        document.getElementById("modalCatatan").value = "";
        selectedSkala = null;
        document.querySelectorAll(".skala-badge").forEach(b => b.classList.remove("active", "ring-2", "scale-105"));
    }, 300);
}

function openQuickModal() {
    const modal = document.getElementById("quickModal");
    const content = modal.querySelector('.transform');
    
    modal.classList.remove("hidden");
    modal.classList.add("flex");
    document.body.style.overflow = "hidden";
    
    setTimeout(() => {
        content.classList.remove("opacity-0", "scale-95");
        content.classList.add("opacity-100", "scale-100");
    }, 10);
}

function closeQuickModal() {
    const modal = document.getElementById("quickModal");
    const content = modal.querySelector('.transform');
    
    content.classList.remove("opacity-100", "scale-100");
    content.classList.add("opacity-0", "scale-95");
    
    setTimeout(() => {
        modal.classList.add("hidden");
        modal.classList.remove("flex");
        document.body.style.overflow = "";
    }, 300);
}// ==========================================

function selectSkala(element, skala) {
  document.querySelectorAll(".skala-badge").forEach(b => b.classList.remove("active", "ring-2", "scale-105", "ring-emerald-400", "ring-blue-400", "ring-amber-400", "ring-rose-400"));
  
  element.classList.add("active", "ring-2", "scale-105");
  if(skala === 'sangat-baik') element.classList.add("ring-emerald-400");
  if(skala === 'baik') element.classList.add("ring-blue-400");
  if(skala === 'cukup') element.classList.add("ring-amber-400");
  if(skala === 'perlu-pembinaan') element.classList.add("ring-rose-400");

  selectedSkala = skala;
}

// ... [KODE LAINNYA DI ATAS TETAP SAMA] ...

function selectSkala(element, skala) {
  document.querySelectorAll(".skala-badge").forEach(b => b.classList.remove("active", "ring-2", "scale-105", "ring-emerald-400", "ring-blue-400", "ring-amber-400", "ring-rose-400"));
  
  element.classList.add("active", "ring-2", "scale-105");
  if(skala === 'sangat-baik') element.classList.add("ring-emerald-400");
  if(skala === 'baik') element.classList.add("ring-blue-400");
  if(skala === 'cukup') element.classList.add("ring-amber-400");
  if(skala === 'perlu-pembinaan') element.classList.add("ring-rose-400");

  selectedSkala = skala;
}

// IMPLEMENTASI RBAC PADA TOMBOL DELETE PERMANEN
function expandRow(button, id) {
  const row = button.closest("tr");
  if (row.classList.contains("expand-row")) {
      row.classList.remove("expand-row", "bg-emerald-50/50", "dark:!bg-slate-700/80");
      if (row.nextElementSibling && row.nextElementSibling.classList.contains("detail-row")) {
          row.nextElementSibling.remove();
      }
  } else {
      document.querySelectorAll('.expand-row').forEach(r => r.classList.remove('expand-row', 'bg-emerald-50/50', 'dark:!bg-slate-700/80'));
      document.querySelectorAll('.detail-row').forEach(r => r.remove());

      row.classList.add("expand-row", "bg-emerald-50/50", "dark:!bg-slate-700/80");
      const detailRow = document.createElement("tr");
      
      detailRow.className = "detail-row bg-gray-50 dark:!bg-slate-800/90 border-b border-gray-100 dark:!border-slate-700 transition-colors";
      
      // Jika Admin tidak mengizinkan Delete, kita tampilkan pesan bahwa fitur dikunci
      let actionHTML = '';
      if (typeof CAN_DELETE !== 'undefined' && CAN_DELETE) {
          actionHTML = `
            <button onclick="hapusObservasi(${id})" class="flex items-center gap-2 px-4 py-2 bg-rose-50 dark:!bg-rose-900/20 text-rose-600 dark:!text-rose-400 text-xs font-bold rounded-xl border border-rose-200 dark:!border-rose-800/50 hover:bg-rose-100 dark:hover:!bg-rose-900/40 transition-colors shadow-sm outline-none">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg> 
              ${LANG.delete_perm}
            </button>`;
      } else {
          actionHTML = `<span class="text-xs font-bold text-gray-400 italic flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg> Fitur Terkunci (RBAC)</span>`;
      }

      detailRow.innerHTML = `
          <td colspan="7" class="p-0">
            <div class="p-4 flex items-center justify-end border-t border-dashed border-gray-200 dark:!border-slate-700 w-full transition-colors">
              <div class="flex items-center gap-3">
                  <span class="text-xs font-bold text-gray-500 dark:!text-slate-400 uppercase tracking-widest transition-colors">${LANG.advanced_act}:</span>
                  ${actionHTML}
              </div>
            </div>
          </td>`;
      row.insertAdjacentElement("afterend", detailRow);
  }
}

// ... [KODE LAINNYA DI BAWAH TETAP SAMA] ...

function simpanObservasi() {
  const siswaId = document.getElementById("modalSiswa").value;
  const parameter = document.querySelector('input[name="parameter"]:checked');
  const catatan = document.getElementById("modalCatatan").value.trim();

  if (!siswaId || !parameter || !selectedSkala || !catatan) {
      showToast(LANG.err_incomplete, "error");
      return;
  }

  prosesSimpan([siswaId], parameter.value, selectedSkala, catatan, closeObservasiModal);
}

function simpanQuickObservasi() {
  const checkboxes = document.querySelectorAll(".student-checkbox input:checked");
  const siswaIds = Array.from(checkboxes).map(cb => cb.value);
  const parameter = document.getElementById("quickParameter").value;
  const skala = document.getElementById("quickSkala").value;
  const catatan = document.getElementById("quickCatatan").value.trim();

  if (siswaIds.length === 0 || !parameter || !skala || !catatan) {
      showToast(LANG.err_quick_inc, "error");
      return;
  }

  prosesSimpan(siswaIds, parameter, skala, catatan, closeQuickModal);
}

function prosesSimpan(siswaIds, parameter, skala, catatan, callbackModal) {
    const formData = new FormData();
    formData.append('siswa_ids', JSON.stringify(siswaIds));
    formData.append('mapel_id', ACTIVE_MAPEL_ID);
    formData.append('rombel_id', ACTIVE_ROMBEL_ID);
    formData.append('parameter', parameter);
    formData.append('skala', skala);
    formData.append('catatan', catatan);
    formData.append(csrfTokenName, csrfTokenHash);

    fetch(URL_STORE, {
        method: 'POST',
        body: formData,
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            showToast(LANG.succ_saved, "success");
            callbackModal();
            fetchData(); 
        } else {
            showToast("Gagal menyimpan data", "error");
        }
    })
    .catch(err => {
        console.error(err);
        showToast("Terjadi kesalahan jaringan", "error");
    });
}

function hapusObservasi(id) {
    if(!confirm(LANG.del_confirm)) return;
    
    const formData = new FormData();
    formData.append('id', id);
    formData.append(csrfTokenName, csrfTokenHash);

    fetch(URL_DELETE, {
        method: 'POST',
        body: formData,
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            showToast(LANG.succ_del, "success");
            fetchData(); 
        } else {
            showToast("Gagal menghapus data", "error");
        }
    })
    .catch(err => {
        console.error(err);
        showToast("Terjadi kesalahan jaringan", "error");
    });
}

function showToast(message, type = 'success') {
  const toast = document.getElementById("toast");
  const toastMessage = document.getElementById("toastMessage");
  if(!toast) return;
  
toast.className = 'toast fixed top-4 right-4 z-[1000000] flex items-center gap-3 px-5 py-4 !bg-white dark:!bg-slate-800 text-gray-800 dark:!text-white border-l-4 rounded-xl shadow-2xl transition-all duration-300 transform translate-x-full opacity-0 w-max max-w-md h-min max-h-24';  
  const iconDiv = toast.querySelector('div');
  const svg = toast.querySelector('svg');

  if (type === 'error') {
      toast.classList.add('border-rose-500');
      if(iconDiv) iconDiv.className = 'w-8 h-8 rounded-full bg-rose-100 dark:!bg-rose-900/30 flex items-center justify-center flex-shrink-0 transition-colors';
      if(svg) {
        svg.className = 'w-5 h-5 text-rose-600 dark:!text-rose-400';
        svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />';
      }
  } else {
      toast.classList.add('border-emerald-500');
      if(iconDiv) iconDiv.className = 'w-8 h-8 rounded-full bg-emerald-100 dark:!bg-emerald-900/30 flex items-center justify-center flex-shrink-0 transition-colors';
      if(svg) {
        svg.className = 'w-5 h-5 text-emerald-600 dark:!text-emerald-400';
        svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />';
      }
  }

  if(toastMessage) toastMessage.textContent = message;
  
  requestAnimationFrame(() => {
      toast.classList.remove('translate-x-full', 'opacity-0');
  });

  setTimeout(() => {
      toast.classList.add('translate-x-full', 'opacity-0');
  }, 3500);
}

document.addEventListener("DOMContentLoaded", fetchData);