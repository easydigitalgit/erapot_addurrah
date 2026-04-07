const textObj = window.LANG;
let isLocked = localStorage.getItem('jadwal_locked') === 'true';
let currentMode = isLocked ? 'view' : 'edit'; 

const ALL_MAPELS = typeof window.DATA_MAPEL !== 'undefined' ? window.DATA_MAPEL : [];
const ALL_GURUS = typeof window.DATA_GURU !== 'undefined' ? window.DATA_GURU : [];

// Variabel global untuk menyimpan data mapping kelas yang sedang dipilih
let currentRombelMappings = []; 

document.addEventListener('DOMContentLoaded', () => {
    applyLockState(); 
    renderSchedule();

    const rombelSelect = document.getElementById('add_rombel_id'); 
    const mapelSelect = document.getElementById('add_mapel_id');   
    const guruSelect = document.getElementById('add_guru_id');     
    const hariSelect = document.getElementById('add_hari');
    const jamSelect = document.getElementById('add_jam_ke');

    // LOGIKA DINAMIS HARI KE JAM PELAJARAN
    if (hariSelect && jamSelect) {
        hariSelect.addEventListener('change', function() {
            const hari = this.value.toUpperCase();
            
            if (!hari) {
                jamSelect.innerHTML = '<option value="">Pilih Hari Terlebih Dahulu</option>';
                jamSelect.disabled = true;
                jamSelect.classList.remove('bg-white', 'dark:bg-slate-700', 'cursor-pointer');
                jamSelect.classList.add('bg-gray-50', 'dark:bg-slate-900', 'cursor-not-allowed', 'opacity-70', 'pointer-events-none');
                return;
            }

            // Kamus Waktu Sesuai File Roster Excel
            const JADWAL_WAKTU = {
                'REGULER': [ // Senin - Kamis
                    {les: 1, waktu: '09:30 - 10:00'}, {les: 2, waktu: '10:00 - 10:30'},
                    {les: 3, waktu: '10:30 - 11:00'}, {les: 4, waktu: '11:00 - 11:30'},
                    {les: 5, waktu: '11:30 - 12:00'}, {les: 6, waktu: '13:30 - 14:00'},
                    {les: 7, waktu: '14:00 - 14:30'}, {les: 8, waktu: '14:30 - 15:00'},
                    {les: 9, waktu: '15:00 - 15:30'}
                ],
                'JUMAT_BIASA': [ // Jumat Tanpa BPI
                    {les: 1, waktu: '09:30 - 09:50'}, {les: 2, waktu: '09:50 - 10:10'},
                    {les: 3, waktu: '10:10 - 10:30'}, {les: 4, waktu: '10:30 - 10:50'},
                    {les: 5, waktu: '10:50 - 11:10'}, {les: 6, waktu: '11:10 - 11:30'},
                    {les: 7, waktu: '11:30 - 11:50'}, {les: 8, waktu: '13:30 - 13:50'},
                    {les: 9, waktu: '13:50 - 14:10'}
                ],
              'JUMAT_BPI': [ // Khusus Jumat BPI (Mulai 10:30)
                    {les: 1, waktu: '10:30 - 11:00'}, {les: 2, waktu: '11:00 - 11:30'},
                    {les: 3, waktu: '11:30 - 12:00'}, {les: 4, waktu: '13:30 - 14:00'},
                    {les: 5, waktu: '14:00 - 14:30'}, {les: 6, waktu: '14:30 - 15:00'},
                    {les: 7, waktu: '15:00 - 15:30'}
                ]
            };

            let opsiWaktu = JADWAL_WAKTU.REGULER;
            if (hari === 'JUMAT BPI') {
                opsiWaktu = JADWAL_WAKTU.JUMAT_BPI;
            } else if (hari === 'JUMAT' || hari === "JUM'AT") {
                opsiWaktu = JADWAL_WAKTU.JUMAT_BIASA;
            }

            let optionsHtml = '<option value="">Pilih Jam Ke-</option>';
            opsiWaktu.forEach(w => {
                optionsHtml += `<option value="${w.les}">${w.les} (${w.waktu})</option>`;
            });

            jamSelect.innerHTML = optionsHtml;
            jamSelect.disabled = false;
            jamSelect.classList.remove('bg-gray-50', 'dark:bg-slate-900', 'cursor-not-allowed', 'opacity-70', 'pointer-events-none');
            jamSelect.classList.add('bg-white', 'dark:bg-slate-700', 'cursor-pointer');
        });
    }

    if (rombelSelect) {
        rombelSelect.addEventListener('change', async function() {
            const rombelId = this.value;
            
            mapelSelect.innerHTML = '<option value="">Memuat data...</option>';
            guruSelect.innerHTML = '<option value="">Pilih Mapel Terlebih Dahulu</option>';
            
            mapelSelect.disabled = true;
            guruSelect.disabled = true;
            
            mapelSelect.classList.remove('bg-white', 'dark:bg-slate-700', 'cursor-pointer');
            mapelSelect.classList.add('bg-gray-50', 'dark:bg-slate-900', 'cursor-not-allowed', 'opacity-70', 'pointer-events-none');
            
            guruSelect.classList.remove('bg-white', 'dark:bg-slate-700', 'cursor-pointer');
            guruSelect.classList.add('bg-gray-50', 'dark:bg-slate-900', 'cursor-not-allowed', 'opacity-70', 'pointer-events-none');

            if (!rombelId) {
                mapelSelect.innerHTML = '<option value="">Pilih Rombel Terlebih Dahulu</option>';
                return;
            }

            const filterValue = document.getElementById('tahunSemesterFilter') ? document.getElementById('tahunSemesterFilter').value : '';
            let taParam = '', semParam = '';
            if (filterValue) {
                const [ta_id, semester] = filterValue.split('_');
                taParam = `&ta_id=${ta_id}`;
                semParam = `&semester=${semester}`;
            }

            try {
                const response = await fetch(`${BASE_URL}admin/jadwal/get-mapping-by-rombel?rombel_id=${rombelId}${taParam}${semParam}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                
                const json = await response.json();

                if (json.status === 'success') {
                    currentRombelMappings = json.data;
                    let mapelOptions = '<option value="">Pilih mata pelajaran</option>';

                    ALL_MAPELS.forEach(m => {
                        let displayLabel = m.nama_mapel;
                        mapelOptions += `<option value="${m.id}">${displayLabel}</option>`;
                    });
                    
                    mapelSelect.innerHTML = mapelOptions;
                    mapelSelect.disabled = false;
                    mapelSelect.classList.remove('bg-gray-50', 'dark:bg-slate-900', 'cursor-not-allowed', 'opacity-70', 'pointer-events-none');
                    mapelSelect.classList.add('bg-white', 'dark:bg-slate-700', 'cursor-pointer');
                } else {
                    mapelSelect.innerHTML = `<option value="">Error: ${json.message}</option>`;
                }
            } catch (error) {
                mapelSelect.innerHTML = '<option value="">Gagal memuat data dari server</option>';
            }
        });
    }

    if (mapelSelect) {
        mapelSelect.addEventListener('change', function() {
            const selectedMapelId = this.value;
            
            if (selectedMapelId === "") {
                 guruSelect.innerHTML = '<option value="">Pilih Mapel Terlebih Dahulu</option>';
                 guruSelect.disabled = true;
                 guruSelect.classList.remove('bg-white', 'dark:bg-slate-700', 'cursor-pointer');
                 guruSelect.classList.add('bg-gray-50', 'dark:bg-slate-900', 'cursor-not-allowed', 'opacity-70', 'pointer-events-none');
                 return;
            }

            const mappedGurus = currentRombelMappings.filter(m => m.mapel_id == selectedMapelId);
            const mappedGuruIds = mappedGurus.map(m => m.guru_id);

            let guruOptions = '<option value="">Pilih Guru Pengampu</option>';
            let recommendedGuruId = null;

            ALL_GURUS.forEach(g => {
                let displayLabel = g.nama_lengkap;
                const isMapped = mappedGuruIds.includes(g.id);
                if (isMapped) {
                    displayLabel += ` ✓ (Sesuai Mapping)`;
                    if (!recommendedGuruId) recommendedGuruId = g.id;
                }
                guruOptions += `<option value="${g.id}">${displayLabel}</option>`;
            });

            guruSelect.innerHTML = guruOptions;
            guruSelect.disabled = false;
            guruSelect.classList.remove('bg-gray-50', 'dark:bg-slate-900', 'cursor-not-allowed', 'opacity-70', 'pointer-events-none');
            guruSelect.classList.add('bg-white', 'dark:bg-slate-700', 'cursor-pointer');

            if (recommendedGuruId) guruSelect.value = recommendedGuruId;
        });
    }
});

async function fetchMappingAndSetForEdit(rombelId, mapelId, guruId) {
    const mapelSelect = document.getElementById('add_mapel_id');
    const guruSelect = document.getElementById('add_guru_id');

    mapelSelect.innerHTML = '<option value="">Memuat data...</option>';
    guruSelect.innerHTML = '<option value="">Memuat data...</option>';
    mapelSelect.disabled = true;
    guruSelect.disabled = true;

    const filterValue = document.getElementById('tahunSemesterFilter') ? document.getElementById('tahunSemesterFilter').value : '';
    let taParam = '', semParam = '';
    if (filterValue) {
        const [ta_id, semester] = filterValue.split('_');
        taParam = `&ta_id=${ta_id}`;
        semParam = `&semester=${semester}`;
    }

    try {
        const response = await fetch(`${BASE_URL}admin/jadwal/get-mapping-by-rombel?rombel_id=${rombelId}${taParam}${semParam}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await response.json();
        
        if (json.status === 'success') {
            currentRombelMappings = json.data;
            
            let mapelOptions = '<option value="">Pilih mata pelajaran</option>';
            ALL_MAPELS.forEach(m => {
                let displayLabel = m.nama_mapel;
                if (m.id == mapelId) displayLabel += ` (Edit Mode)`;
                mapelOptions += `<option value="${m.id}">${displayLabel}</option>`;
            });

            mapelSelect.innerHTML = mapelOptions;
            mapelSelect.disabled = false;
            mapelSelect.classList.remove('bg-gray-50', 'dark:bg-slate-900', 'cursor-not-allowed', 'opacity-70', 'pointer-events-none');
            mapelSelect.classList.add('bg-white', 'dark:bg-slate-700', 'cursor-pointer');
            mapelSelect.value = mapelId;

            const mappedGurus = currentRombelMappings.filter(m => m.mapel_id == mapelId);
            const mappedGuruIds = mappedGurus.map(m => m.guru_id);

            let guruOptions = '<option value="">Pilih Guru Pengampu</option>';
            ALL_GURUS.forEach(g => {
                let displayLabel = g.nama_lengkap;
                if (mappedGuruIds.includes(g.id)) displayLabel += ` ✓ (Sesuai Mapping)`;
                guruOptions += `<option value="${g.id}">${displayLabel}</option>`;
            });

            guruSelect.innerHTML = guruOptions;
            guruSelect.disabled = false;
            guruSelect.classList.remove('bg-gray-50', 'dark:bg-slate-900', 'cursor-not-allowed', 'opacity-70', 'pointer-events-none');
            guruSelect.classList.add('bg-white', 'dark:bg-slate-700', 'cursor-pointer');
            
            if (guruId) setTimeout(() => { guruSelect.value = guruId; }, 50);
        }
    } catch(e) {
        mapelSelect.innerHTML = '<option value="">Error fetching data</option>';
        guruSelect.innerHTML = '<option value="">Error fetching data</option>';
    }
}

function showCreateScheduleModal() {
    if (isLocked) {
        Swal.fire(textObj.js_locked_warn, textObj.js_locked_desc, 'warning');
        return;
    }

    const filterRombel = document.getElementById('rombelFilter');
    const filterTahunSemester = document.getElementById('tahunSemesterFilter');

    if (!filterTahunSemester || !filterTahunSemester.value) {
        Swal.fire('Perhatian', 'Pastikan Tahun Ajaran dan Semester sudah terpilih di filter.', 'warning');
        return;
    }
    if (!filterRombel || !filterRombel.value) {
        Swal.fire('Perhatian', textObj.js_select_room_warn, 'warning');
        if(filterRombel) filterRombel.focus(); 
        return; 
    }

    const rombelId = filterRombel.value; 

    const form = document.getElementById('formTambahJadwal');
    if (form) form.reset(); 

    document.getElementById('inputIdJadwal').value = ''; 

    const addRombel = document.getElementById('add_rombel_id');
    if (addRombel) {
        addRombel.value = rombelId;
        addRombel.dispatchEvent(new Event('change')); 
    }

    const addHari = document.getElementById('add_hari');
    if (addHari) addHari.dispatchEvent(new Event('change'));

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

    fetchMappingAndSetForEdit(scheduleData.rombel_id, scheduleData.mapel_id, scheduleData.guru_id);

  // SISTEM DETEKTIF REVERSE-ENGINEERING: Menentukan ini Jumat Biasa atau BPI
    let hariToSelect = scheduleData.hari;
    if (hariToSelect === 'Jumat' || hariToSelect === "Jum'at") {
        const bpiStarts = ['10:30:00', '11:00:00', '11:30:00', '13:30:00', '14:00:00', '14:30:00', '15:00:00'];
        const bpiEnds = ['11:00:00', '11:30:00', '12:00:00', '14:00:00', '14:30:00', '15:00:00', '15:30:00'];
        
        if (bpiStarts.includes(scheduleData.jam_mulai) && bpiEnds.includes(scheduleData.jam_selesai)) {
            hariToSelect = 'Jumat BPI';
        } else {
            hariToSelect = 'Jumat';
        }
    }   

    setSelectValue('hari', hariToSelect);
    
    const hariSelect = document.getElementById('add_hari');
    if (hariSelect) hariSelect.dispatchEvent(new Event('change'));
    
    setTimeout(() => {
        setSelectValue('jam_ke', scheduleData.jam_ke); 
    }, 50);
}

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

function renderSchedule() {
    const rombelSelect = document.getElementById('rombelFilter');
    const tahunSemesterSelect = document.getElementById('tahunSemesterFilter');

    const selectedRombelId = rombelSelect ? rombelSelect.value : null;
    const filterValue = tahunSemesterSelect ? tahunSemesterSelect.value : null;
    const container = document.getElementById('scheduleContainer');

    if (!selectedRombelId || !filterValue) {
        if(container) container.innerHTML = `<div class="p-8 text-center bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700"><p class="text-gray-500 dark:text-slate-400 font-medium">Pilih Rombel terlebih dahulu.</p></div>`;
        updateStatCards(0, 0, 0, 0);
        return;
    }

    // 🚀 FILTER HANYA BERDASARKAN ROMBEL (TA sudah difilter di server)
    const data = DB_SCHEDULES.filter(item => item.rombel_id == selectedRombelId);
    
    // =========================================================
    // ALGORITMA KALKULASI DINAMIS (PERBAIKAN STATISTIK)
    // =========================================================
    // Pisahkan data yang tampil di grid kotak (jam_ke > 0) dengan non-reguler (jam_ke = 0)
    const regulerData = data.filter(item => item.jam_ke > 0);
    const ekstraData = data.filter(item => item.jam_ke == 0);

    // Deteksi apakah kelas ini menggunakan Jumat BPI (Jika ya, Jumat cuma 7 les, bukan 9)
    const hasBPI = ekstraData.some(item => item.kode_jadwal_excel === 'BPI' || (item.nama_mapel && item.nama_mapel.toUpperCase() === 'BPI'));
    
    // Kapasitas Dinamis: 36 Les (Senin-Kamis) + 7 Les (Jumat BPI) atau 9 Les (Jumat Biasa)
    const maxGridSlots = hasBPI ? 43 : 45; 

    // Total JP = Reguler + BPI + Tahfizh (Sesuai beban asli)
    const totalJP = data.length; 
    
    // Persentase & Jam Kosong MURNI berpatokan pada kotak UI (Reguler)
    const filledGridSlots = regulerData.length;
    let emptySlots = maxGridSlots - filledGridSlots;
    if (emptySlots < 0) emptySlots = 0; // Failsafe
    
    let filledPercentage = maxGridSlots > 0 ? Math.round((filledGridSlots / maxGridSlots) * 100) : 0;
    if (filledPercentage > 100) filledPercentage = 100; // Failsafe

    // Hitung beban guru (Hanya ambil data yang memiliki ID guru)
    const uniqueTeachers = new Set(data.filter(item => item.guru_id).map(item => item.guru_id));
    const totalTeachers = uniqueTeachers.size;
    const avgTeacherLoad = totalTeachers > 0 ? Math.round(totalJP / totalTeachers) : 0;

    updateStatCards(totalJP, avgTeacherLoad, emptySlots, filledPercentage);
    // =========================================================

    if (data.length === 0) {
        if(container) container.innerHTML = `<div class="p-8 text-center bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700"><p class="text-gray-500 dark:text-slate-400 font-medium">${textObj.js_empty_schedule}</p></div>`;
        return;
    }

    const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
    const periods = 9; // Grid UI tetap merender 9 baris untuk konsistensi layar

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
            
            // Mengunci visual kotak Jumat Les 8 & 9 agar tidak abu-abu "Kosong" jika kelas tersebut pakai BPI
            const isBpiBlankSlot = (hasBPI && day === 'Jumat' && period > 7);

            if (schedule) {
                let guruName = schedule.nama_lengkap;
                let clickAction = `onclick="showScheduleDetail('${schedule.id}', '${day}', ${period}, '${schedule.nama_mapel.replace(/'/g, "\\'")}', '${guruName.replace(/'/g, "\\'")}', '${schedule.jam_mulai} - ${schedule.jam_selesai}')"`;
                html += `<div class="cursor-pointer bg-white dark:bg-slate-800 hover:opacity-90 transition-opacity border-l-[3px] border-[var(--warna-primary)]" style="padding: 12px; height: 100%;" ${clickAction}>
                  <div class="flex flex-col h-full justify-between">
                    <div>
                        <span class="inline-block px-2 py-0.5 bg-[var(--warna-primary)]/10 dark:bg-[var(--warna-primary)]/20 text-[var(--warna-primary)] text-[10px] font-bold rounded-md mb-2">${textObj.js_period} ${period}</span>
                        <p class="font-bold text-gray-900 dark:text-white text-xs sm:text-sm mb-1 line-clamp-2 leading-tight">${schedule.nama_mapel}</p>
                    </div>
                    <p class="text-[10px] sm:text-xs text-gray-600 dark:text-slate-400 line-clamp-1">${guruName}</p>
                  </div>
                </div>`;
            } else if (isBpiBlankSlot) {
                // Render kotak mati/OFF khusus Jumat sore jika jadwal BPI
                html += `<div class="bg-gray-100 dark:bg-slate-900/50" style="padding: 12px; height: 100%; display: flex; align-items: center; justify-content: center;">
                  <div class="text-center text-gray-300 dark:text-slate-600">
                    <svg class="w-5 h-5 mx-auto mb-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <p class="text-[9px] font-bold uppercase tracking-wider">OFF</p>
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

function updateStatCards(totalJP, avgTeacherLoad, emptySlots, percentage) {
    const elTotalJP = document.getElementById('statTotalJP');
    const elTeacherLoad = document.getElementById('statTeacherLoad');
    const elEmptySlots = document.getElementById('statEmptySlots');
    const elPercentage = document.getElementById('statPercentage');

    if (elTotalJP) elTotalJP.textContent = `${totalJP} JP`;
    if (elTeacherLoad) elTeacherLoad.textContent = `${avgTeacherLoad} JP`;
    if (elEmptySlots) elEmptySlots.textContent = `${emptySlots} Slot`;
    if (elPercentage) elPercentage.textContent = `${percentage}%`;
}

function getTimeSlot(period) {
    const times = [
        '09:30-10:00', 
        '10:00-10:30', 
        '10:30-11:00', 
        '11:00-11:30', 
        '11:30-12:00', 
        '13:30-14:00', 
        '14:00-14:30', 
        '14:30-15:00',
        '15:00-15:30'
    ];
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
    fetch(`${BASE_URL}admin/jadwal/delete/${id}`, {
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