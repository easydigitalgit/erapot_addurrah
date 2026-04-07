// State Form
let activeSiswaId = null;
let selectedCategories = [];
let selectedStatus = null;

// ==============================
// 1. GANTI SISWA (FETCH AJAX)
// ==============================
function changeSiswa(siswaId) {
    if (!siswaId) {
        document.getElementById('mainArea').classList.add('hidden');
        activeSiswaId = null;
        return;
    }

    activeSiswaId = siswaId;
    document.getElementById('infoName').textContent = LANG.loading;
    document.getElementById('infoNis').textContent = "...";
    document.getElementById('mainArea').classList.remove('hidden');
    resetForm();

    fetch(`${URL_GET_SISWA}?siswa_id=${siswaId}&mapel_id=${ACTIVE_MAPEL_ID}`, {
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(async res => {
        if (!res.ok) throw new Error("Terjadi kesalahan rute atau server.");
        return res.json();
    })
    .then(data => {
        if(data.status === 'success' && data.siswa) {
            document.getElementById('infoName').textContent = data.siswa.nama_lengkap;
            document.getElementById('infoNis').textContent = data.siswa.nis || '-';
            
            // Badge Statistik Dark Mode Ready
            let badgeColor = data.stats.perlu_pembinaan > 0 
                ? 'bg-rose-100 text-rose-700 dark:!bg-rose-900/30 dark:!text-rose-400' 
                : 'bg-emerald-100 text-emerald-700 dark:!bg-emerald-900/30 dark:!text-emerald-400';
            
            document.getElementById('statBadge').className = `px-3 py-1 rounded-full text-xs font-bold transition-colors ${badgeColor}`;
            document.getElementById('statBadge').textContent = `${data.stats.total_riwayat} ${LANG.notes_count}`;

            renderHistory(data.riwayat);
        } else {
            document.getElementById('infoName').textContent = LANG.not_found;
            showToast(LANG.err_load, "error");
        }
    })
    .catch(err => {
        console.error(err);
        document.getElementById('infoName').textContent = LANG.err_server;
        showToast(LANG.err_server, "error");
    });
}

function renderHistory(riwayat) {
    const container = document.getElementById("historyContainer");
    container.innerHTML = "";

    if(riwayat.length === 0) {
        container.innerHTML = `<div class="text-center py-8 text-gray-500 dark:!text-slate-400 font-semibold border-2 border-dashed border-gray-200 dark:!border-slate-700 rounded-2xl transition-colors">${LANG.empty_history}</div>`;
        return;
    }

    riwayat.forEach(r => {
        // Kelas untuk badge status disesuaikan dengan Tailwind Dark Mode
        let badgeStyle = "bg-gray-100 text-gray-700 dark:!bg-slate-700 dark:!text-slate-300 border-gray-200 dark:!border-slate-600";
        if(r.status_pembinaan === 'Sangat Baik' || r.status_pembinaan === 'Baik') badgeStyle = "bg-emerald-100 text-emerald-700 dark:!bg-emerald-900/30 dark:!text-emerald-400 border-emerald-200 dark:!border-emerald-800/50";
        if(r.status_pembinaan === 'Perlu Pembinaan' || r.status_pembinaan === 'Pembinaan Intensif') badgeStyle = "bg-rose-100 text-rose-700 dark:!bg-rose-900/30 dark:!text-rose-400 border-rose-200 dark:!border-rose-800/50";

        const d = new Date(r.tanggal);
        const tglStr = `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth()+1).toString().padStart(2, '0')}/${d.getFullYear()} ${d.getHours().toString().padStart(2, '0')}:${d.getMinutes().toString().padStart(2, '0')}`;

        container.innerHTML += `
        <div class="bg-white dark:!bg-slate-800/80 border border-gray-200 dark:!border-slate-700 rounded-xl p-5 mb-4 shadow-sm transition-colors" style="border-left: 5px solid ${THEME_COLOR};">
            <div class="flex items-start justify-between mb-2">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-3 flex-wrap">
                        <span class="text-[10px] font-black px-2.5 py-1 rounded-md uppercase tracking-wider bg-blue-50 text-blue-700 border border-blue-100 dark:!bg-blue-900/30 dark:!text-blue-400 dark:!border-blue-800/50 transition-colors">${r.kategori_akhlak}</span> 
                        <span class="text-[10px] font-black px-2.5 py-1 rounded-md uppercase tracking-wider border transition-colors ${badgeStyle}">${r.status_pembinaan}</span>
                    </div>
                    <p class="text-sm text-gray-700 dark:!text-slate-300 font-medium leading-relaxed bg-gray-50 dark:!bg-slate-700/50 p-4 rounded-xl border border-gray-100 dark:!border-slate-600 transition-colors">"${r.catatan}"</p>
                    ${r.tindak_lanjut ? `<p class="text-xs text-amber-700 dark:!text-amber-400 mt-4 font-semibold flex items-center gap-1.5 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> ${LANG.follow_up} <span class="font-medium text-gray-600 dark:!text-slate-300">${r.tindak_lanjut}</span></p>` : ''}
                </div>
            </div>
            <div class="flex items-center justify-between pt-4 mt-4 border-t border-gray-100 dark:!border-slate-700 transition-colors">
                <div class="flex items-center gap-1.5 text-[10px] uppercase tracking-widest text-gray-500 dark:!text-slate-400 font-bold transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    ${tglStr}
                </div>
                <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:!text-slate-400 font-bold transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    ${r.nama_guru || CURRENT_TEACHER_NAME} 
                </div>
            </div>
        </div>
        `;
    });
}

function toggleCategory(btn, category) {
    if (btn.classList.contains('active')) {
        // Jika batal dipilih, hilangkan properti inline dan biarkan Tailwind yang bekerja
        btn.classList.remove('active');
        btn.style.backgroundColor = ""; 
        btn.style.borderColor = ""; 
        btn.style.color = ""; 
        selectedCategories = selectedCategories.filter(c => c !== category);
    } else {
        // Jika dipilih, gunakan warna primary
        btn.classList.add('active');
        btn.style.backgroundColor = THEME_COLOR;
        btn.style.borderColor = THEME_COLOR;
        btn.style.color = "#ffffff";
        selectedCategories.push(category);
    }
}

function selectStatus(btn, status) {
    document.querySelectorAll('#statusContainer button').forEach(b => {
        b.style.opacity = "0.4";
        b.classList.remove('ring-4', 'scale-105', 'ring-emerald-400', 'ring-blue-400', 'ring-amber-400', 'ring-rose-400');
    });
    
    btn.style.opacity = "1";
    btn.classList.add('ring-4', 'scale-105');
    
    if(status === 'Sangat Baik') btn.classList.add("ring-emerald-400");
    if(status === 'Baik') btn.classList.add("ring-blue-400");
    if(status === 'Perlu Pembinaan') btn.classList.add("ring-amber-400");
    if(status === 'Pembinaan Intensif') btn.classList.add("ring-rose-400");
    
    selectedStatus = status;
}

function updateCharCounter() {
    const text = document.getElementById('catatanText').value;
    const counter = document.getElementById('charCounter');
    if(counter) {
        // GANTI BARIS INI
        counter.textContent = `${text.length} ${LANG.characters}`;
    }
}

function resetForm() {
    selectedCategories = [];
    selectedStatus = null;
    
    // Reset Categories (Hapus inline CSS agar Dark Mode jalan)
    document.querySelectorAll('#categoryContainer button').forEach(b => {
        b.classList.remove('active');
        b.style.backgroundColor = "";
        b.style.borderColor = "";
        b.style.color = "";
    });
    
    // Reset Status
    document.querySelectorAll('#statusContainer button').forEach(b => {
        b.style.opacity = "1";
        b.classList.remove('ring-4', 'scale-105', 'ring-emerald-400', 'ring-blue-400', 'ring-amber-400', 'ring-rose-400');
    });
    
    // Reset Checkboxes
    document.querySelectorAll('.checkbox-item input[type="checkbox"]').forEach(c => c.checked = false);
    
    // Reset Textarea
    const textarea = document.getElementById('catatanText');
    if(textarea) {
        textarea.value = "";
        updateCharCounter();
    }
}

function kirimKeWaliKelas() {
    if (!activeSiswaId) {
        showToast(LANG.err_select, "error");
        return;
    }

    if (selectedCategories.length === 0 || !selectedStatus || document.getElementById('catatanText').value.trim() === "") {
        showToast(LANG.err_empty, "error");
        return;
    }

    const btn = document.getElementById("btnSubmit");
    const originalText = btn.innerHTML;
    btn.innerHTML = `<svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ${LANG.sending}`;
    btn.disabled = true;

    const followUps = [];
    document.querySelectorAll('.checkbox-item input:checked').forEach(cb => followUps.push(cb.value));

    const formData = new FormData();
    formData.append('siswa_id', activeSiswaId);
    formData.append('mapel_id', ACTIVE_MAPEL_ID);
    formData.append('rombel_id', ACTIVE_ROMBEL_ID);
    formData.append('kategori', selectedCategories.join(', '));
    formData.append('status_pembinaan', selectedStatus);
    formData.append('tindak_lanjut', followUps.join(', '));
    formData.append('catatan', document.getElementById('catatanText').value.trim());
    formData.append(csrfTokenName, csrfTokenHash);

    fetch(URL_STORE, {
        method: 'POST',
        body: formData,
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            showToast(LANG.succ_send, "success");
            resetForm();
            changeSiswa(activeSiswaId); 
        } else {
            showToast(data.message || LANG.err_save, "error");
        }
    })
    .catch(err => {
        console.error(err);
        showToast(LANG.err_save, "error");
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

function showToast(message, type = 'success') {
  const toast = document.getElementById("toast");
  const toastMessage = document.getElementById("toastMessage");
  if(!toast) return;
  
  toast.className = 'fixed top-4 right-4 z-[1000000] flex items-center gap-3 px-5 py-4 bg-white dark:!bg-slate-800 text-gray-800 dark:!text-white border-l-4 rounded-xl shadow-2xl transition-all duration-300 transform translate-x-full opacity-0';
  
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