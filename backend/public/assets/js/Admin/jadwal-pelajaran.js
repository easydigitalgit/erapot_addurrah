const textObj = window.LANG || {
    js_unlock_title: 'Buka Kunci Jadwal?', js_unlock_desc: 'Jadwal akan kembali bisa diedit.',
    js_btn_yes_unlock: 'Ya, Buka Kunci', js_btn_cancel: 'Batal', js_unlock_success: 'Kunci berhasil dibuka.',
    js_locked_warn: 'Terkunci!', js_locked_desc: 'Jadwal sedang terkunci.', js_feat_na: 'Fitur Belum Tersedia',
    js_feat_na_desc: 'Sistem sedang dalam pengembangan.', js_empty_schedule: 'Jadwal masih kosong.',
    js_period: 'Jam', js_period_ke: 'Jam ke-', js_empty_slot: 'Kosong', js_select_room_warn: 'Pilih Rombel dulu!',
    js_saving: 'Menyimpan...', js_err_no_room: 'Error: Rombel belum dipilih.', js_err_sys: 'Terjadi kesalahan.',
    js_lock_check_warn: 'Centang persetujuan dulu', js_lock_success: 'Berhasil!', js_lock_success_desc: 'Jadwal dikunci.',
    js_clear_slot_title: 'Kosongkan Slot?', js_clear_slot_desc: 'Yakin kosongkan?', js_btn_yes_clear: 'Ya, Kosongkan!',
    js_clear_success: 'Dikosongkan!', js_clear_success_desc: 'Slot dikosongkan.', js_import_locked: 'Jadwal dikunci.',
    js_processing: 'Memproses...', js_err_fatal: 'Kesalahan server.', js_err_conn: 'Koneksi terputus.'
};

let isLocked = localStorage.getItem('jadwal_locked') === 'true';
let currentMode = isLocked ? 'view' : 'edit'; 

document.addEventListener('DOMContentLoaded', () => {
    applyLockState(); 
    renderSchedule();

    // ==========================================
    // LOGIKA DROPDOWN MAPPING DI MODAL TAMBAH
    // ==========================================
    const rombelSelect = document.getElementById('add_rombel_id'); 
    const mapelSelect = document.getElementById('add_mapel_id');   
    const guruSelect = document.getElementById('add_guru_id');     
    const hiddenGuru = document.getElementById('hidden_guru_id');

    if (rombelSelect) {
        rombelSelect.addEventListener('change', async function() {
            const rombelId = this.value;
            
            mapelSelect.innerHTML = '<option value="">Memuat data...</option>';
            guruSelect.innerHTML = '<option value="">Pilih Mapel Terlebih Dahulu</option>';
            hiddenGuru.value = '';
            mapelSelect.disabled = true;
            
            mapelSelect.classList.remove('bg-white', 'dark:bg-slate-700', 'cursor-pointer');
            mapelSelect.classList.add('bg-gray-50', 'dark:bg-slate-900', 'cursor-not-allowed', 'opacity-70', 'pointer-events-none');

            if (!rombelId) {
                mapelSelect.innerHTML = '<option value="">Pilih Rombel Terlebih Dahulu</option>';
                return;
            }

            try {
                const response = await fetch(`${BASE_URL}admin/jadwal/get-mapping-by-rombel?rombel_id=${rombelId}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                
                const json = await response.json();

                if (json.status === 'success') {
                    let currentMappings = json.data;
                    
                    if (currentMappings.length === 0) {
                        mapelSelect.innerHTML = '<option value="" disabled>Semua JP/Mapping untuk kelas ini sudah penuh</option>';
                        return;
                    }

                    let mapelOptions = '<option value="">Pilih mata pelajaran</option>';
                    currentMappings.forEach(item => {
                        mapelOptions += `<option value="${item.mapel_id}" data-guru="${item.guru_id}" data-namaguru="${item.nama_guru}">
                                            ${item.nama_mapel} (Sisa: ${item.sisa_jp} JP)
                                         </option>`;
                    });
                    
                    mapelSelect.innerHTML = mapelOptions;
                    mapelSelect.disabled = false;
                    mapelSelect.classList.remove('bg-gray-50', 'dark:bg-slate-900', 'cursor-not-allowed', 'opacity-70', 'pointer-events-none');
                    mapelSelect.classList.add('bg-white', 'dark:bg-slate-700', 'cursor-pointer');
                } else {
                    mapelSelect.innerHTML = `<option value="">Error: ${json.message}</option>`;
                }
            } catch (error) {
                console.error("Gagal memuat mapping:", error);
                mapelSelect.innerHTML = '<option value="">Gagal memuat data dari server</option>';
            }
        });
    }

    if (mapelSelect) {
        mapelSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (selectedOption.value === "") {
                 guruSelect.innerHTML = '<option value="">Pilih Mapel Terlebih Dahulu</option>';
                 hiddenGuru.value = '';
                 return;
            }

            const guruId = selectedOption.getAttribute('data-guru');
            const namaGuru = selectedOption.getAttribute('data-namaguru');

            guruSelect.innerHTML = `<option value="${guruId}" selected>${namaGuru}</option>`;
            hiddenGuru.value = guruId; 
        });
    }
});

// ==========================================
// FUNGSI UTAMA (CREATE, EDIT, FETCH)
// ==========================================

async function fetchMappingAndSetForEdit(rombelId, mapelId, guruId) {
    const mapelSelect = document.getElementById('add_mapel_id');
    const guruSelect = document.getElementById('add_guru_id');
    const hiddenGuru = document.getElementById('hidden_guru_id');

    mapelSelect.innerHTML = '<option value="">Memuat data...</option>';
    mapelSelect.disabled = true;

    try {
        const response = await fetch(`${BASE_URL}admin/jadwal/get-mapping-by-rombel?rombel_id=${rombelId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await response.json();
        
        if (json.status === 'success') {
            let mapelOptions = '<option value="">Pilih mata pelajaran</option>';
            // Karena ini mode edit, kita pastikan mapel yang mau diedit tetap muncul di dropdown
            let mapelExists = false;

            json.data.forEach(item => {
                if(item.mapel_id == mapelId) mapelExists = true;
                mapelOptions += `<option value="${item.mapel_id}" data-guru="${item.guru_id}" data-namaguru="${item.nama_guru}">
                                    ${item.nama_mapel} (Sisa: ${item.sisa_jp} JP)
                                 </option>`;
            });

            // Jika mapel target tidak ada (sisa 0), kita injeksi manual untuk edit
            if(!mapelExists) {
                 const sched = DB_SCHEDULES.find(s => s.mapel_id == mapelId && s.guru_id == guruId);
                 if(sched) {
                     mapelOptions += `<option value="${sched.mapel_id}" data-guru="${sched.guru_id}" data-namaguru="${sched.nama_lengkap}">
                                        ${sched.nama_mapel} (Edit Mode)
                                     </option>`;
                 }
            }

            mapelSelect.innerHTML = mapelOptions;
            mapelSelect.disabled = false;
            mapelSelect.classList.remove('bg-gray-50', 'dark:bg-slate-900', 'cursor-not-allowed', 'opacity-70', 'pointer-events-none');
            mapelSelect.classList.add('bg-white', 'dark:bg-slate-700', 'cursor-pointer');

            // Select value
            mapelSelect.value = mapelId;

            // Trigger guru
            const selectedOption = mapelSelect.options[mapelSelect.selectedIndex];
            if (selectedOption && selectedOption.value !== "") {
                const namaGuru = selectedOption.getAttribute('data-namaguru');
                guruSelect.innerHTML = `<option value="${guruId}" selected>${namaGuru}</option>`;
                hiddenGuru.value = guruId;
            }
        }
    } catch(e) {
        console.error(e);
    }
}

function showCreateScheduleModal() {
    if (isLocked) {
        Swal.fire(textObj.js_locked_warn, textObj.js_locked_desc, 'warning');
        return;
    }

    const filterRombel = document.getElementById('rombelFilter');
    if (!filterRombel || !filterRombel.value) {
        Swal.fire('Perhatian', textObj.js_select_room_warn, 'warning');
        if(filterRombel) filterRombel.focus(); 
        return; 
    }

    const rombelId = filterRombel.value; 

    const form = document.getElementById('formTambahJadwal');
    if (form) form.reset(); 

    document.getElementById('inputIdJadwal').value = ''; 
    document.getElementById('hidden_guru_id').value = '';

    const addRombel = document.getElementById('add_rombel_id');
    if (addRombel) {
        addRombel.value = rombelId;
        addRombel.dispatchEvent(new Event('change')); // Trigger AJAX mapel otomatis
    }

    const modal = document.getElementById('createScheduleModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function prepareEditJadwal(mode) {
    const id = document.getElementById('selectedScheduleId').value;
    const scheduleData = DB_SCHEDULES.find(s => s.id == id);
    if (!scheduleData) return;

    closeDrawer(); 

    const modal = document.getElementById('createScheduleModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    document.getElementById('inputIdJadwal').value = id; 
    
    const addRombel = document.getElementById('add_rombel_id');
    addRombel.value = scheduleData.rombel_id;

    // Load data via AJAX berdasarkan rombel dan paksa select mapel yg mau diedit
    fetchMappingAndSetForEdit(scheduleData.rombel_id, scheduleData.mapel_id, scheduleData.guru_id);

    setSelectValue('hari', scheduleData.hari);
    setSelectValue('jam_ke', scheduleData.jam_ke); 
}

// MENGGANTIKAN FUNGSI LAMA YANG ERROR
async function handleCreateSchedule(event) {
    event.preventDefault(); 
    const form = document.getElementById('formTambahJadwal');
    const idJadwal = document.getElementById('inputIdJadwal').value; 
    const btnSubmit = form.querySelector('button[type="submit"]');
    const originalText = btnSubmit.innerHTML;

    const rombelId = document.getElementById('add_rombel_id').value;
    if (!rombelId) {
        showToast(textObj.js_err_no_room, 'error');
        return;
    }

    let url = idJadwal ? `${BASE_URL}admin/jadwal/update/${idJadwal}` : `${BASE_URL}admin/jadwal/save`;

    btnSubmit.disabled = true;
    btnSubmit.innerHTML = textObj.js_saving;

    const formData = new FormData(form);

    try {
        const response = await fetch(url, { 
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();
        
        if (data.status === 'success') {
            showToast(data.message, 'success');
            closeCreateScheduleModal(); 
            setTimeout(() => window.location.reload(), 1000);
        } else {
            let msg = data.message;
            if (data.errors) msg = Object.values(data.errors)[0]; 
            showToast(msg, 'warning'); 
        }
    } catch(error) {
        showToast(textObj.js_err_sys, 'error');
    } finally {
        if(btnSubmit) {
            btnSubmit.innerHTML = originalText;
            btnSubmit.disabled = false;
        }
    }
}

// ==========================================
// FUNGSI LAINNYA (LOCK, UI, DRAWER)
// ==========================================

function applyLockState() {
    const badge = document.getElementById('badgeLockStatus');
    const textStatus = document.getElementById('textLockStatus');
    const btnLock = document.getElementById('btnLockToggle'); 

    if (isLocked) {
        if(badge) {
            badge.textContent = 'TERKUNCI';
            badge.className = 'badge bg-red-100 text-red-700 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border border-red-200 shadow-sm';
        }
        if(textStatus) textStatus.textContent = 'Status: Terkunci (Tidak bisa diedit)';
        
        if(btnLock) {
            btnLock.className = "px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl transition-colors shadow-lg shadow-amber-500/30 flex items-center gap-2";
            btnLock.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" /></svg><span>Buka Kunci</span>`;
            btnLock.setAttribute('onclick', 'confirmUnlockSchedule()');
        }

        currentMode = 'view'; 
    } else {
        if(badge) {
            badge.textContent = 'AKTIF';
            badge.className = 'badge bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border border-emerald-200 shadow-sm';
        }
        if(textStatus) textStatus.textContent = 'Status: Bisa Diedit';
        
        if(btnLock) {
            btnLock.className = "px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-colors shadow-lg shadow-blue-600/30 flex items-center gap-2";
            btnLock.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg><span>Kunci Jadwal</span>`;
            btnLock.setAttribute('onclick', 'showLockScheduleModal()');
        }
    }
    updateModeUI();
}

function confirmUnlockSchedule() {
    Swal.fire({
        title: textObj.js_unlock_title,
        text: textObj.js_unlock_desc,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#d33',
        confirmButtonText: textObj.js_btn_yes_unlock,
        cancelButtonText: textObj.js_btn_cancel
    }).then((result) => {
        if (result.isConfirmed) {
            localStorage.setItem('jadwal_locked', 'false');
            isLocked = false;
            currentMode = 'edit';
            applyLockState();
            renderSchedule(); 
            showToast(textObj.js_unlock_success, 'success');
        }
    });
}

function toggleMode(mode) {
    if (isLocked && mode === 'edit') {
        Swal.fire(textObj.js_locked_warn, textObj.js_locked_desc, 'warning');
        return;
    }
    currentMode = mode;
    updateModeUI();
    renderSchedule(); 
}

function updateModeUI() {
    const btnView = document.getElementById('btnModeView');
    const btnEdit = document.getElementById('btnModeEdit');
    if(!btnView || !btnEdit) return;

    if (currentMode === 'view') {
        btnView.className = "flex-1 py-1.5 px-3 rounded-lg text-sm font-bold bg-blue-600 text-white shadow-sm transition-colors outline-none flex justify-center items-center";
        btnEdit.className = "flex-1 py-1.5 px-3 rounded-lg text-sm font-medium text-gray-500 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 hover:text-gray-800 dark:hover:text-white transition-colors outline-none flex justify-center items-center";
    } else {
        btnEdit.className = "flex-1 py-1.5 px-3 rounded-lg text-sm font-bold bg-blue-600 text-white shadow-sm transition-colors outline-none flex justify-center items-center";
        btnView.className = "flex-1 py-1.5 px-3 rounded-lg text-sm font-medium text-gray-500 dark:text-slate-400 hover:bg-gray-200 dark:hover:bg-slate-700 hover:text-gray-800 dark:hover:text-white transition-colors outline-none flex justify-center items-center";
    }
}

function generateOtomatis() {
    Swal.fire({
        icon: 'info',
        title: textObj.js_feat_na,
        text: textObj.js_feat_na_desc,
        confirmButtonColor: '#10b981'
    });
}

function renderSchedule() {
    const rombelSelect = document.getElementById('rombelFilter');
    const selectedRombelId = rombelSelect ? rombelSelect.value : null;
    if (!selectedRombelId) {
        document.getElementById('scheduleContainer').innerHTML = '';
        return;
    }

    const data = DB_SCHEDULES.filter(item => item.rombel_id == selectedRombelId);
    const container = document.getElementById('scheduleContainer');
    
    if (data.length === 0) {
        if(container) container.innerHTML = `<div class="p-8 text-center bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700"><p class="text-gray-500 dark:text-slate-400 font-medium">${textObj.js_empty_schedule}</p></div>`;
        return;
    }

    const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
    const periods = 8; 

    let html = '<div class="schedule-grid border-gray-200 dark:border-slate-700" style="display: grid; grid-template-columns: 80px repeat(5, 1fr); gap: 1px; background: #e5e7eb; border-radius: 16px; overflow: hidden; border: 1px solid;">';
    
    html += `<div class="bg-[var(--warna-primary)]" style=" color: white; padding: 12px; text-align: center; font-weight: 700; font-size: 12px; align-self: stretch;">${textObj.js_period}</div>`;
    days.forEach((day) => {
        html += `<div class="bg-[var(--warna-primary)]"  style=" color: white; padding: 12px; text-align: center; font-weight: 700; font-size: 14px;">${day}</div>`;
    });

    for (let period = 1; period <= periods; period++) {
        html += `<div class="bg-gray-100 dark:bg-slate-800" style=" padding: 12px; text-align: center; display: flex; flex-direction: column; justify-content: center;">
          <div class="text-xs font-bold text-gray-700 dark:text-white">${textObj.js_period} ${period}</div>
          <div class="text-[10px] text-gray-500 mt-1 dark:text-gray-400">${getTimeSlot(period)}</div>
        </div>`;

        days.forEach((day) => {
            const schedule = data.find(s => s.hari === day && s.jam_ke == period);
            if (schedule) {
                let guruName = schedule.nama_lengkap;
                if(schedule.gelar) guruName += ', ' + schedule.gelar;
                let clickAction = `onclick="showScheduleDetail('${schedule.id}', '${day}', ${period}, '${schedule.nama_mapel}', '${guruName}', '${schedule.jam_mulai} - ${schedule.jam_selesai}')"`;
                html += `<div class="cursor-pointer bg-white dark:bg-slate-800 hover:opacity-90 transition-opacity border-l-[3px] border-[var(--warna-primary)]" style="padding: 12px; height: 100%;" ${clickAction}>
                  <div class="flex flex-col h-full justify-between">
                    <div>
                        <span class="inline-block px-2 py-0.5 bg-[var(--warna-primary)]/10 dark:bg-[var(--warna-primary)]/20 text-[var(--warna-primary)] text-[10px] font-bold rounded-md mb-2">${textObj.js_period} ${period}</span>
                        <p class="font-bold text-gray-900 dark:text-white text-xs sm:text-sm mb-1 line-clamp-2 leading-tight">${schedule.nama_mapel}</p>
                    </div>
                    <p class="text-[10px] sm:text-xs text-gray-600 dark:text-slate-400 line-clamp-1">${guruName}</p>
                  </div>
                </div>`;
            } else {
                let clickAction = (currentMode === 'edit' && !isLocked) ? `onclick="showCreateScheduleModal()"` : '';
                let cursorStyle = (currentMode === 'edit' && !isLocked) ? 'cursor-pointer hover:bg-gray-50 dark:hover:bg-slate-700/50' : 'cursor-not-allowed opacity-50';
                html += `<div class="${cursorStyle} bg-white dark:bg-slate-800 transition-colors" style="padding: 12px; height: 100%; display: flex; align-items: center; justify-content: center;" ${clickAction}>
                  <div class="text-center text-gray-300 dark:text-slate-500">
                    <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <p class="text-[10px] font-bold uppercase tracking-wider">${textObj.js_empty_slot}</p>
                  </div>
                </div>`;
            }        
        });
    }
    html += '</div>';
    if(container) container.innerHTML = html;
}

function getTimeSlot(period) {
    const times = ['07:30-08:10', '08:10-08:50', '08:50-09:30', '09:45-10:25', '10:25-11:05', '11:05-11:45', '13:00-13:40', '13:40-14:20'];
    return times[period - 1] || '';
}

function filterSchedule() { renderSchedule(); }

function closeCreateScheduleModal() {
    const modal = document.getElementById('createScheduleModal');
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

function showLockScheduleModal() {
    const modal = document.getElementById('lockScheduleModal');
    if(modal) {
        modal.classList.remove('hidden'); 
        document.body.style.overflow = 'hidden';
    }
}

function closeLockScheduleModal() {
    const modal = document.getElementById('lockScheduleModal');
    if(modal) {
        modal.classList.add('hidden'); 
        const checkbox = document.getElementById('confirmLock');
        if(checkbox) checkbox.checked = false;
        document.body.style.overflow = '';
    }
}

function handleLockSchedule() {
    const checkbox = document.getElementById('confirmLock');
    if (!checkbox.checked) {
        showToast(textObj.js_lock_check_warn, 'warning');
        return;
    }
    localStorage.setItem('jadwal_locked', 'true');
    isLocked = true;
    applyLockState();
    Swal.fire(textObj.js_lock_success, textObj.js_lock_success_desc, 'success');
    closeLockScheduleModal();
}

function showScheduleDetail(id, day, period, subject, teacher, time) {
    const idInput = document.getElementById('selectedScheduleId');
    if (idInput) idInput.value = id; 

    document.getElementById('drawerSubject').textContent = subject;
    document.getElementById('drawerTime').textContent = `${day}, ${textObj.js_period_ke}${period} (${time})`;
    document.getElementById('drawerTeacher').textContent = teacher;
    
    const rombelSelect = document.getElementById('rombelFilter');
    if(rombelSelect && rombelSelect.selectedIndex >= 0) {
        document.getElementById('drawerRombel').textContent = rombelSelect.options[rombelSelect.selectedIndex].text;
    }
    
    const actionButtons = document.getElementById('drawerActionButtons');
    if(actionButtons) {
        if (currentMode === 'view' || isLocked) actionButtons.classList.add('hidden');
        else actionButtons.classList.remove('hidden');
    }

    const drawer = document.getElementById('scheduleDrawer');
    const overlay = document.getElementById('drawerOverlay');
    if(drawer) drawer.classList.remove('hidden');
    if(overlay) overlay.classList.remove('hidden');

    setTimeout(() => {
        if(drawer) {
            drawer.classList.add('open'); 
            drawer.style.right = '0';
            drawer.classList.remove('translate-x-full');
        }
        if(overlay) {
            overlay.style.display = 'block';
            overlay.style.opacity = '1';
        }
    }, 10);
    document.body.style.overflow = 'hidden';
}

function closeDrawer() {
    const drawer = document.getElementById('scheduleDrawer');
    const overlay = document.getElementById('drawerOverlay');
    
    if(drawer) {
        drawer.style.right = '-100%';
        drawer.classList.add('translate-x-full');
        drawer.classList.remove('open');
    }
    if(overlay) overlay.style.opacity = '0';

    setTimeout(() => {
        if(drawer) drawer.classList.add('hidden');
        if(overlay) {
            overlay.style.display = 'none';
            overlay.classList.add('hidden');
        }
    }, 300);
    document.body.style.overflow = '';
}

function setSelectValue(name, value) {
    const element = document.querySelector(`select[name="${name}"]`);
    if (element) element.value = value;
}

function deleteScheduleSlot() {
    const id = document.getElementById('selectedScheduleId').value;
    if(!id) return;
    Swal.fire({
        title: textObj.js_clear_slot_title,
        text: textObj.js_clear_slot_desc,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: textObj.js_btn_yes_clear,
        cancelButtonText: textObj.js_btn_cancel
    }).then((result) => {
        if (result.isConfirmed) performDelete(id); 
    });
}

function performDelete(id) {
    fetch(`${BASE_URL}/admin/jadwal/delete/${id}`, {
        method: 'DELETE',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            Swal.fire(textObj.js_clear_success, textObj.js_clear_success_desc, 'success');
            closeDrawer();
            setTimeout(() => window.location.reload(), 1000);
        } else {
            Swal.fire('Gagal', data.message, 'error');
        }
    });
}

function showImportModal() {
    if (isLocked) {
        Swal.fire(textObj.js_locked_warn, textObj.js_import_locked, 'warning');
        return;
    }
    const modal = document.getElementById('importModal');
    if(modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeImportModal() {
    const modal = document.getElementById('importModal');
    if(modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

async function handleImportSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const btn = form.querySelector('button[type="submit"]');
    const oriText = btn.innerHTML;
    btn.innerHTML = textObj.js_processing;
    btn.disabled = true;

    try {
        const response = await fetch(form.action, {
            method: 'POST', body: new FormData(form) 
        });
        const rawText = await response.text();
        let data;
        try { data = JSON.parse(rawText); } catch (e) { showToast(textObj.js_err_fatal, 'error'); return; }

        if (data.status === 'success') {
            showToast(data.message, 'success');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast(textObj.js_err_conn, 'error');
    } finally {
        if(btn) { btn.innerHTML = oriText; btn.disabled = false; }
    }
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast fixed top-4 right-4 z-[100000] flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg transition-all duration-300 transform translate-y-0 opacity-100 bg-white dark:bg-slate-800 border-l-4 ${type==='success'?'border-emerald-500':'border-red-500'}`; 
    const iconColor = type === 'success' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400';
    const bgIcon = type === 'success' ? 'bg-emerald-100 dark:bg-emerald-900/30' : 'bg-red-100 dark:bg-red-900/30';
    let iconPath = type === 'success' 
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>' 
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>';

    toast.innerHTML = `<div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 ${bgIcon}"><svg class="w-6 h-6 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">${iconPath}</svg></div><div><p class="font-semibold text-gray-800 dark:text-slate-200 text-sm">${message}</p></div>`;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeDrawer();
        closeCreateScheduleModal();
        closeLockScheduleModal();
        closeImportModal();
    }
});