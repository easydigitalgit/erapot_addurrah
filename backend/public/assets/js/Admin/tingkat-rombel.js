// ==========================================
// 1. SETUP & KONFIGURASI AWAL
// ==========================================
const defaultConfig = {
    school_name: 'SMPIT Ad Durrah',
    app_title: 'Rapor Digital',
    academic_year: typeof window.DYNAMIC_YEAR !== 'undefined' ? window.DYNAMIC_YEAR : '2024/2025'
};

let config = { ...defaultConfig };

const txtClass = window.LANG?.class_word || 'Kelas';
const txtActive = window.LANG?.active || 'Aktif';

let rombelsData = window.rombels || []; 
let selectedLevel = null;
let filteredRombels = [...rombelsData];
let currentRombelId = null;
let cachedCurrentStudents = [];

function updateUI() {
    if(document.getElementById('sidebar-school-name')) document.getElementById('sidebar-school-name').textContent = config.school_name;
    if(document.getElementById('sidebar-app-title')) document.getElementById('sidebar-app-title').textContent = config.app_title;
    if(document.getElementById('header-school-name')) document.getElementById('header-school-name').textContent = config.school_name;
    if(document.getElementById('header-academic-year')) document.getElementById('header-academic-year').textContent = config.academic_year;
    
    if(document.getElementById('page-title')) document.getElementById('page-title').textContent = window.LANG?.page_title || 'Tingkat & Rombel';
    if(document.getElementById('page-subtitle')) document.getElementById('page-subtitle').textContent = window.LANG?.page_subtitle || 'Kelola struktur kelas dan rombongan belajar';
}

// ==========================================
// 2. RENDER & FILTER TINGKAT (LEVELS)
// ==========================================
function populateLevels() {
    const container = document.getElementById('levelList');
    if(!container) return;
    
    const txtRombelCount = window.LANG?.js_class_count || 'kelas';
    const txtStudentCount = window.LANG?.js_student_count || 'siswa';
    const txtViewStats = window.LANG?.js_view_level_stats || 'Lihat Statistik Tingkat';
    const txtRombel = window.LANG?.study_group || 'Rombel'; 
    const txtTotalSiswa = window.LANG?.total_student || 'Total Siswa';

    const selectedTaId = document.getElementById('filterTahunAjaranTable')?.value;

    let levelsDynamic = [
        { id: 7, name: `${txtClass} 7`, code: 'VII', rombel_count: 0, student_count: 0, laki: 0, perempuan: 0, status: txtActive },
        { id: 8, name: `${txtClass} 8`, code: 'VIII', rombel_count: 0, student_count: 0, laki: 0, perempuan: 0, status: txtActive },
        { id: 9, name: `${txtClass} 9`, code: 'IX', rombel_count: 0, student_count: 0, laki: 0, perempuan: 0, status: txtActive }
    ];

    let totalRombelFiltered = 0;
    let totalSiswaFiltered = 0;

    if(window.rawRombelStats) {
        window.rawRombelStats.forEach(r => {
            if (r.is_lulus == 1) return; 

            if (selectedTaId && r.id_tahun_ajaran != selectedTaId) return;

            totalRombelFiltered++;
            let tingkatRaw = String(r.tingkat).toUpperCase().trim();
            if (tingkatRaw === 'VII' || tingkatRaw === '7') levelsDynamic[0].rombel_count++;
            else if (tingkatRaw === 'VIII' || tingkatRaw === '8') levelsDynamic[1].rombel_count++;
            else if (tingkatRaw === 'IX' || tingkatRaw === '9') levelsDynamic[2].rombel_count++;
        });
    }

    if(window.rawSiswaStats) {
        window.rawSiswaStats.forEach(s => {
            if (s.is_lulus == 1) return;

            if (selectedTaId && s.id_tahun_ajaran != selectedTaId) return;

            totalSiswaFiltered++;
            let tingkatRaw = String(s.tingkat).toUpperCase().trim();
            let jk = String(s.jenis_kelamin).toUpperCase().trim();

            if (tingkatRaw === 'VII' || tingkatRaw === '7') {
                levelsDynamic[0].student_count++;
                if (jk === 'L') levelsDynamic[0].laki++;
                if (jk === 'P') levelsDynamic[0].perempuan++;
            } else if (tingkatRaw === 'VIII' || tingkatRaw === '8') {
                levelsDynamic[1].student_count++;
                if (jk === 'L') levelsDynamic[1].laki++;
                if (jk === 'P') levelsDynamic[1].perempuan++;
            } else if (tingkatRaw === 'IX' || tingkatRaw === '9') {
                levelsDynamic[2].student_count++;
                if (jk === 'L') levelsDynamic[2].laki++;
                if (jk === 'P') levelsDynamic[2].perempuan++;
            }
        });
    }

    const elHeaderRombel = document.getElementById('headerStatTotalRombel');
    const elHeaderRombelAktif = document.getElementById('headerStatRombelAktif');
    const elHeaderSiswa = document.getElementById('headerStatTotalSiswa');

    if(elHeaderRombel) elHeaderRombel.textContent = totalRombelFiltered;
    if(elHeaderRombelAktif) elHeaderRombelAktif.textContent = totalRombelFiltered; 
    if(elHeaderSiswa) elHeaderSiswa.textContent = totalSiswaFiltered;

    container.innerHTML = levelsDynamic.map(level => {
        const bgClass = selectedLevel === level.id 
            ? `bg-emerald-50 dark:bg-emerald-900/20 border-[${window.themePrimary}]` 
            : 'bg-white dark:bg-slate-700/50 border-gray-200 dark:border-slate-600 hover:border-emerald-300 dark:hover:border-emerald-600';
            
        return `
        <div class="level-card p-4 border-2 rounded-xl hover:shadow-md transition-all cursor-pointer ${bgClass}" onclick="selectLevel(${level.id})" style="${selectedLevel === level.id ? 'border-color: ' + window.themePrimary : ''}">
            <div class="flex items-center justify-between mb-3">
            <h4 class="font-bold text-gray-800 dark:text-white text-lg">${level.name}</h4>
            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50">${level.status}</span>
            </div>
            <div class="space-y-2">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500 dark:text-slate-400">${txtRombel}</span>
                <span class="font-semibold text-gray-800 dark:text-slate-200">${level.rombel_count} ${txtRombelCount}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500 dark:text-slate-400">${txtTotalSiswa}</span>
                <span class="font-semibold text-gray-800 dark:text-slate-200">${level.student_count} ${txtStudentCount}</span>
            </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100 dark:border-slate-600/50">
            <button onclick="event.stopPropagation(); showDetailTingkat(${level.id}, ${level.rombel_count}, ${level.student_count}, ${level.laki}, ${level.perempuan}, '${level.name}')" class="w-full px-3 py-2 text-xs bg-gray-50 dark:bg-slate-800 text-gray-700 dark:text-slate-300 font-bold rounded-lg hover:bg-gray-100 dark:hover:bg-slate-700 transition-colors border border-gray-200 dark:border-slate-600 outline-none flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                ${txtViewStats}
            </button>
            </div>
        </div>
        `;
    }).join('');
}

function selectLevel(levelId) {
    const txtAllLevels = window.LANG?.all_levels || 'Semua Tingkat';
    if (selectedLevel === levelId) {
        selectedLevel = null;
        if(document.getElementById('selectedLevelName')) document.getElementById('selectedLevelName').textContent = txtAllLevels;
    } else {
        selectedLevel = levelId;
        const level = levels.find(l => l.id === levelId);
        if(document.getElementById('selectedLevelName') && level) document.getElementById('selectedLevelName').textContent = level.name;
    }
    
    populateLevels();
    filterTableDOM();
}

function filterTableDOM() {
    let tingkatFilter = '';
    if(selectedLevel === 7) tingkatFilter = `${txtClass} VII`;
    if(selectedLevel === 8) tingkatFilter = `${txtClass} VIII`;
    if(selectedLevel === 9) tingkatFilter = `${txtClass} IX`;

    const taFilter = document.getElementById('filterTahunAjaranTable')?.value || '';
    const searchTerm = document.getElementById('searchRombel')?.value.toLowerCase() || '';

    const rows = document.querySelectorAll('#rombelTableBody tr');
    rows.forEach(row => {
        if(row.querySelector('td[colspan]')) return; 
        
        const cellTingkat = row.cells[1].innerText.trim();
        const rowTaId = row.getAttribute('data-ta-id');
        const textContent = row.innerText.toLowerCase();

        let matchLevel = !selectedLevel || cellTingkat === tingkatFilter;
        let matchTa = !taFilter || rowTaId === taFilter;
        let matchSearch = !searchTerm || textContent.includes(searchTerm);

        if(matchLevel && matchTa && matchSearch) {
            row.style.display = ''; 
        } else {
            row.style.display = 'none'; 
        }
    });

    populateLevels();
}

if(document.getElementById('searchRombel')) {
    document.getElementById('searchRombel').addEventListener('input', filterTableDOM);
}

if(document.getElementById('filterTahunAjaranTable')) {
    document.getElementById('filterTahunAjaranTable').addEventListener('change', filterTableDOM);
}

window.showDetailTingkat = function(levelId, rombelCount, studentCount, laki, perempuan, levelName) {
    document.getElementById('detailTingkatTitle').textContent = levelName;
    document.getElementById('detailTingkatRombel').textContent = rombelCount;
    document.getElementById('detailTingkatSiswa').textContent = studentCount;
    document.getElementById('detailTingkatLaki').textContent = laki;
    document.getElementById('detailTingkatPerempuan').textContent = perempuan;

    const modal = document.getElementById('detailTingkatModal');
    if(modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

window.closeDetailTingkatModal = function() {
    const modal = document.getElementById('detailTingkatModal');
    if(modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

// ==========================================
// 3. LACI DETAIL ROMBEL (DRAWER)
// ==========================================
window.showDetail = function(rombelId) {
    currentRombelId = rombelId;
    document.getElementById('drawerRombelIcon').textContent = '...';
    document.getElementById('drawerRombelName').textContent = window.LANG?.js_loading || 'Memuat...';
    document.getElementById('drawerRombelLevel').textContent = '-';
    document.getElementById('drawerStudentCount').textContent = '0';
    document.getElementById('drawerMaleCount').textContent = '0';
    document.getElementById('drawerFemaleCount').textContent = '0';
    
    const teacherNameEl = document.getElementById('drawerWaliKelas');
    const txtHomeroom = window.LANG?.js_homeroom || 'Wali Kelas:';
    if(teacherNameEl) teacherNameEl.textContent = `${txtHomeroom} ${window.LANG?.js_loading || 'Memuat...'}`;
    
    const studentList = document.getElementById('drawerStudentList');
    if(studentList) studentList.innerHTML = `<p class="text-center text-sm font-medium text-gray-400 py-6">${window.LANG?.js_loading_db || 'Memuat data dari database...'}</p>`;

    const drawer = document.getElementById('detailDrawer');
    const overlay = document.getElementById('drawer-overlay');
    drawer.dataset.rombelId = rombelId; 
    drawer.classList.remove('translate-x-full');
    overlay.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    fetch(`${BASE_URL}admin/rombel/show/${rombelId}`)
    .then(res => res.json())
    .then(data => {
        if(data.status === 'error') {
            Swal.fire('Error', data.message, 'error');
            closeDrawer(); return;
        }
        document.getElementById('drawerRombelIcon').textContent = data.nama_rombel.charAt(0);
        document.getElementById('drawerRombelName').textContent = data.nama_rombel;
        document.getElementById('drawerRombelLevel').textContent = `${window.LANG?.th_level || 'Tingkat'} ` + data.tingkat;
        
        document.getElementById('drawerRombelYear').textContent = data.nama_tahun_ajaran || 'Belum Diset';
        
        document.getElementById('drawerStudentCount').textContent = data.jumlah_siswa;
        document.getElementById('drawerMaleCount').textContent = data.jumlah_laki;
        document.getElementById('drawerFemaleCount').textContent = data.jumlah_perempuan;

        if (teacherNameEl) teacherNameEl.textContent = data.nama_wali_kelas ? `${txtHomeroom} ${data.nama_wali_kelas}` : `${txtHomeroom} -`;

        if(studentList) {
            if(data.siswa && data.siswa.length > 0) {
                cachedCurrentStudents = data.siswa; 
                let listHtml = '';
                data.siswa.forEach(s => {
                    const genderIcon = s.jenis_kelamin === 'L' 
                        ? `<span class="bg-blue-100 text-blue-600 px-2 py-0.5 rounded text-[10px] font-bold">${window.LANG?.male || 'Laki-laki'}</span>` 
                        : `<span class="bg-pink-100 text-pink-600 px-2 py-0.5 rounded text-[10px] font-bold">${window.LANG?.female || 'Perempuan'}</span>`;
                    
                    listHtml += `
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-700/50 rounded-lg border border-gray-100 dark:border-slate-600">
                        <div>
                            <p class="text-sm font-bold text-gray-800 dark:text-white">${s.nama_lengkap}</p>
                            <p class="text-[10px] text-gray-500 dark:text-slate-400 font-mono mt-0.5">NISN: ${s.nisn || '-'}</p>
                        </div>
                        ${genderIcon}
                    </div>`;
                });
                studentList.innerHTML = listHtml;
            } else {
                cachedCurrentStudents = [];
                studentList.innerHTML = `<p class="text-center text-sm font-medium text-amber-500 py-6 bg-amber-50 rounded-xl border border-dashed border-amber-200">Belum ada siswa di kelas ini.</p>`;
            }
        }
    })
    .catch(err => {
        console.error(err);
        if(studentList) studentList.innerHTML = `<p class="text-center text-sm text-red-500 py-6">Gagal memuat data siswa.</p>`;
    });
}
    
window.closeDrawer = function() {
    const drawer = document.getElementById('detailDrawer');
    const overlay = document.getElementById('drawer-overlay');
    if(drawer) drawer.classList.add('translate-x-full');
    if(overlay) overlay.classList.add('hidden');
    document.body.style.overflow = '';
}


// ==================================================
// 4. KELOLA SISWA (TABS) LOGIC
// ==================================================
window.openStudentManagementFromDrawer = function() {
    if (!currentRombelId) return;
    closeDrawer();
    setTimeout(() => { showStudentManagement(currentRombelId); }, 300);
}

window.showStudentManagement = function(rombelId) {
    const rombel = rombelsData.find(r => r.id == rombelId);
    if (!rombel) return;

    fetch(`${BASE_URL}admin/rombel/show/${rombelId}`)
    .then(res => res.json())
    .then(data => {
        if(data.status !== 'error') {
            cachedCurrentStudents = data.siswa || [];
            const countText = document.getElementById('currentStudentCountText');
            if(countText) countText.innerHTML = `Total <span class="font-black text-gray-900 dark:!text-white">${cachedCurrentStudents.length} siswa</span> di rombel ini`;
            renderCurrentStudents();
            renderTransferStudents();
        }
    });

    document.getElementById('studentModalRombelName').textContent = rombel.nama_rombel;
    document.getElementById('studentModalLevel').textContent = `${window.LANG?.class_word || 'Kelas'} ` + rombel.tingkat;
    
    const modal = document.getElementById('studentManagementModal');
    if(modal) { modal.classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    switchStudentTab('current');
}

window.closeStudentManagementModal = function() {
    const modal = document.getElementById('studentManagementModal');
    if(modal) { modal.classList.add('hidden'); document.body.style.overflow = ''; }
}

window.switchStudentTab = function(tabName) {
    const tabs = ['current', 'add', 'transfer'];
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = {
        current: document.getElementById('currentStudentsTab'),
        add: document.getElementById('addStudentsTab'),
        transfer: document.getElementById('transferStudentsTab')
    };
    
    tabs.forEach(tab => { if (tabContents[tab]) tabContents[tab].classList.toggle('hidden', tab !== tabName); });
    
    tabButtons.forEach(button => {
        if (button.dataset.tab === tabName) {
            button.classList.add('active', 'border-[var(--warna-primary)]', 'text-[var(--warna-primary)]');
            button.classList.remove('border-transparent', 'text-gray-500', 'dark:!text-slate-400');
        } else {
            button.classList.remove('active', 'border-[var(--warna-primary)]', 'text-[var(--warna-primary)]');
            button.classList.add('border-transparent', 'text-gray-500', 'dark:!text-slate-400');
        }
    });

    if (tabName === 'current') renderCurrentStudents();
    if (tabName === 'add') searchUnassignedStudents();
    if (tabName === 'transfer') renderTransferStudents();
}

function renderCurrentStudents() {
    const container = document.getElementById('listCurrentStudents');
    const keyword = document.getElementById('searchCurrentStudent').value.toLowerCase();
    
    let filtered = cachedCurrentStudents.filter(s => {
        const nama = s.nama_lengkap ? s.nama_lengkap.toLowerCase() : '';
        const nisn = s.nisn ? String(s.nisn).toLowerCase() : '';
        return nama.includes(keyword) || nisn.includes(keyword);
    });

    if(filtered.length === 0) {
        container.innerHTML = `<div class="flex items-center justify-center h-32 text-gray-400 dark:!text-slate-500 font-medium">Tidak ada siswa.</div>`;
        return;
    }

    let html = '';
    filtered.forEach(s => {
        html += `
        <div class="flex items-center justify-between p-4 bg-white dark:!bg-slate-800 rounded-xl border border-gray-200 dark:!border-slate-600 shadow-sm transition-colors hover:border-red-300 dark:hover:!border-red-500">
            <div>
                <p class="text-sm font-bold text-gray-900 dark:!text-white mb-0.5">${s.nama_lengkap}</p>
                <p class="text-[11px] font-black text-gray-400 dark:!text-slate-400 uppercase tracking-widest">NISN: ${s.nisn || '-'}</p>
            </div>
            <button onclick="removeStudentFromRombel(${s.id})" class="p-2.5 bg-red-50 dark:!bg-red-900/30 hover:bg-red-100 dark:hover:!bg-red-900/50 text-red-600 dark:!text-red-400 rounded-xl outline-none transition-colors" title="Keluarkan dari Rombel">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
            </button>
        </div>`;
    });
    container.innerHTML = html;
}

if(document.getElementById('searchCurrentStudent')) {
    document.getElementById('searchCurrentStudent').addEventListener('input', renderCurrentStudents);
}

function searchUnassignedStudents() {
    const keyword = document.getElementById('searchUnassignedStudent').value;
    const container = document.getElementById('listUnassignedStudents');
    container.innerHTML = `<div class="flex items-center justify-center h-32 text-gray-400 dark:!text-slate-500 font-medium">Mencari siswa...</div>`;

    fetch(`${BASE_URL}admin/rombel/searchUnassignedStudents?keyword=${keyword}`)
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success' && data.data.length > 0) {
            let html = '';
            data.data.forEach(s => {
                html += `
                <div class="flex items-center justify-between p-4 bg-white dark:!bg-slate-800 rounded-xl border border-gray-200 dark:!border-slate-600 shadow-sm transition-colors hover:border-emerald-300 dark:hover:!border-emerald-500">
                    <div>
                        <p class="text-sm font-bold text-gray-900 dark:!text-white mb-0.5">${s.nama_lengkap}</p>
                        <p class="text-[11px] font-black text-gray-400 dark:!text-slate-400 uppercase tracking-widest">NISN: ${s.nisn || '-'}</p>
                    </div>
                    <button onclick="addStudentToRombel(${s.id})" class="px-4 py-2 bg-emerald-50 dark:!bg-emerald-900/30 hover:bg-emerald-100 dark:hover:!bg-emerald-900/50 text-emerald-600 dark:!text-emerald-400 font-bold text-xs uppercase tracking-wider rounded-xl outline-none transition-colors">
                        Tambah
                    </button>
                </div>`;
            });
            container.innerHTML = html;
        } else {
            container.innerHTML = `<div class="flex items-center justify-center h-32 text-gray-400 dark:!text-slate-500 font-medium">Semua siswa aktif sudah memiliki kelas.</div>`;
        }
    });
}

function debounce(func, timeout = 300){
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => { func.apply(this, args); }, timeout);
    };
}

if(document.getElementById('searchUnassignedStudent')) {
    document.getElementById('searchUnassignedStudent').addEventListener('input', debounce(searchUnassignedStudents, 500));
}

function renderTransferStudents() {
    const container = document.getElementById('listTransferStudents');
    if(cachedCurrentStudents.length === 0) {
        container.innerHTML = `<div class="flex items-center justify-center h-32 text-gray-400 dark:!text-slate-500 font-medium">Tidak ada siswa untuk dipindahkan.</div>`;
        return;
    }

    let html = '';
    cachedCurrentStudents.forEach(s => {
        html += `
        <label class="flex items-center gap-4 p-4 bg-white dark:!bg-slate-800 rounded-xl border border-gray-200 dark:!border-slate-600 shadow-sm transition-colors cursor-pointer hover:bg-gray-50 dark:hover:!bg-slate-700/50">
            <input type="checkbox" value="${s.id}" class="transfer-cb w-4 h-4 text-[var(--warna-primary)] rounded border-gray-300 dark:!border-slate-500 focus:ring-[var(--warna-primary)] outline-none">
            <div>
                <p class="text-sm font-bold text-gray-900 dark:!text-white mb-0.5">${s.nama_lengkap}</p>
                <p class="text-[11px] font-black text-gray-400 dark:!text-slate-400 uppercase tracking-widest">NISN: ${s.nisn || '-'}</p>
            </div>
        </label>`;
    });
    container.innerHTML = html;
}

if(document.getElementById('selectAllTransfer')) {
    document.getElementById('selectAllTransfer').addEventListener('change', function() {
        const cbs = document.querySelectorAll('.transfer-cb');
        cbs.forEach(cb => cb.checked = this.checked);
    });
}

window.addStudentToRombel = function(siswaId) {
    const formData = new FormData();
    formData.append('siswa_id', siswaId);
    formData.append('rombel_id', currentRombelId);

    fetch(`${BASE_URL}admin/rombel/addStudentToRombel`, { method: 'POST', body: formData, headers: {'X-Requested-With': 'XMLHttpRequest'} })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            Swal.fire({icon: 'success', title: 'Berhasil', text: data.message, timer: 1500, showConfirmButton: false});
            showStudentManagement(currentRombelId); 
            searchUnassignedStudents(); 
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    });
}

window.removeStudentFromRombel = function(siswaId) {
    Swal.fire({
        title: 'Keluarkan Siswa?', text: "Siswa ini akan dihapus dari Rombel.", icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'Ya, Keluarkan'
    }).then((result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('siswa_id', siswaId);
            fetch(`${BASE_URL}admin/rombel/removeStudentFromRombel`, { method: 'POST', body: formData, headers: {'X-Requested-With': 'XMLHttpRequest'} })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    Swal.fire({icon: 'success', title: 'Berhasil', text: data.message, timer: 1500, showConfirmButton: false});
                    showStudentManagement(currentRombelId);
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            });
        }
    });
}

window.executeTransfer = function() {
    const targetRombelId = document.getElementById('transferTargetRombel').value;
    const cbs = document.querySelectorAll('.transfer-cb:checked');
    const siswaIds = Array.from(cbs).map(cb => cb.value);

    if(!targetRombelId) { Swal.fire('Peringatan', 'Pilih rombel tujuan terlebih dahulu!', 'warning'); return; }
    if(targetRombelId == currentRombelId) { Swal.fire('Peringatan', 'Rombel tujuan tidak boleh sama dengan kelas saat ini.', 'warning'); return; }
    if(siswaIds.length === 0) { Swal.fire('Peringatan', 'Pilih minimal satu siswa!', 'warning'); return; }

    const formData = new FormData();
    formData.append('target_rombel_id', targetRombelId);
    siswaIds.forEach(id => formData.append('siswa_ids[]', id));

    fetch(`${BASE_URL}admin/rombel/transferStudents`, { method: 'POST', body: formData, headers: {'X-Requested-With': 'XMLHttpRequest'} })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            Swal.fire({icon: 'success', title: 'Berhasil', text: data.message, timer: 1500, showConfirmButton: false});
            document.getElementById('selectAllTransfer').checked = false;
            showStudentManagement(currentRombelId);
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    });
}

// ============================================
// 5. CRUD ROMBEL & MIGRASI SINGLE
// ============================================
window.showAddRombelModal = function() {
    const modal = document.getElementById('addRombelModal');
    if(modal) { modal.classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
}

window.closeAddRombelModal = function() {
    const modal = document.getElementById('addRombelModal');
    if(modal) { modal.classList.add('hidden'); document.body.style.overflow = ''; }
    if(document.getElementById('formTambahRombel')) document.getElementById('formTambahRombel').reset();
    if(document.getElementById('rombelId')) document.getElementById('rombelId').value = ''; 
    if(document.getElementById('modalRombelTitle')) document.getElementById('modalRombelTitle').innerText = window.LANG?.modal_add_title || "Tambah Rombel Baru";
}

window.editRombel = function(id) {
    fetch(`${BASE_URL}admin/rombel/show/${id}`)
    .then(res => res.json())
    .then(data => {
        if(data.status === 'error') throw new Error(data.message);

        document.getElementById('rombelId').value = data.id;
        document.querySelector('#addRombelModal [name="rombel_name"]').value = data.nama_rombel;
        document.querySelector('#addRombelModal [name="level"]').value = data.tingkat;
        document.querySelector('#addRombelModal [name="homeroom_teacher"]').value = data.wali_kelas_id || '';
        
        if (data.id_tahun_ajaran) {
            document.querySelector('#addRombelModal [name="id_tahun_ajaran"]').value = data.id_tahun_ajaran;
        }

        document.getElementById('modalRombelTitle').innerText = window.LANG?.modal_edit_title || "Edit Data Rombel";
        showAddRombelModal();
    })
    .catch(err => Swal.fire('Error', err.message, 'error'));
}

window.handleRombelSubmit = function(event) {
    event.preventDefault();
    const form = event.target;
    const btn = form.querySelector('button[type="submit"]');
    const ori = btn.innerHTML;
    btn.innerHTML = window.LANG?.js_saving || 'Menyimpan...'; 
    btn.disabled = true;

    const formData = new FormData(form);
    const id = document.getElementById('rombelId').value;

    const url = id ? 'admin/rombel/update' : 'admin/rombel/store';
    const successTitle = id ? (window.LANG?.js_success_updated || 'Berhasil Diperbarui!') : (window.LANG?.js_success_added || 'Berhasil Ditambahkan!');

    fetch(BASE_URL + url, { 
        method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            closeAddRombelModal();
            Swal.fire({ icon: 'success', title: successTitle, text: data.message, showConfirmButton: false, timer: 1500
            }).then(() => location.reload());
        } else {
            Swal.fire(window.LANG?.js_failed || 'Gagal', data.message, 'error');
        }
    })
    .catch(err => Swal.fire('Error', window.LANG?.js_error_system || 'Terjadi kesalahan sistem', 'error'))
    .finally(() => { btn.innerHTML = ori; btn.disabled = false; });
}

window.deleteRombelPrompt = function(id) {
    document.getElementById('deleteRombelId').value = id;
    const modal = document.getElementById('deleteRombelModal');
    if(modal) modal.classList.remove('hidden');
}

window.closeDeleteModal = function() {
    const modal = document.getElementById('deleteRombelModal');
    if(modal) modal.classList.add('hidden');
}

window.confirmDeleteRombel = function() {
    const id = document.getElementById('deleteRombelId').value;
    const formData = new FormData();
    formData.append('id', id);

    fetch(BASE_URL + 'admin/rombel/delete', { 
        method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        closeDeleteModal(); 
        if(data.status === 'success') {
            Swal.fire({ icon: 'success', title: window.LANG?.js_deleted || 'Terhapus!', text: data.message, showConfirmButton: false, timer: 1500
            }).then(() => location.reload());
        } else {
            Swal.fire(window.LANG?.js_failed || 'Gagal', data.message, 'error');
        }
    })
    .catch(err => {
        closeDeleteModal();
        Swal.fire('Error', window.LANG?.js_error_server || 'Gagal menghubungi server', 'error');
    });
}

window.openMigrateModal = function(id) {
    fetch(`${BASE_URL}admin/rombel/show/${id}`)
    .then(res => res.json())
    .then(data => {
        if(data.status === 'error') throw new Error(data.message);

        document.getElementById('migrasiRombelId').value = data.id;
        document.getElementById('migrasiInfoAsal').innerText = `${data.nama_rombel} (Tingkat ${data.tingkat}) - ${data.nama_tahun_ajaran || 'TA Lama'}`;
        
        const formNaikKelas = document.getElementById('formAreaNaikKelas');
        const formLulus = document.getElementById('formAreaLulus');
        const jenisMigrasi = document.getElementById('jenisMigrasiInput');
        const btnSubmit = document.getElementById('btnSubmitMigrasi');
        const titleModal = document.getElementById('modalMigrateTitle');
        const descModal = document.getElementById('modalMigrateDesc');
        const headerBg = document.getElementById('migrateHeaderBg');

        const normalLulusMsg = document.getElementById('normalLulusMessage');
        const emptyWarningLulus = document.getElementById('emptyStudentWarningLulus');

        btnSubmit.disabled = false;
        btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');

        if (data.tingkat === 'IX' || data.tingkat === '9') {
            formNaikKelas.classList.add('hidden');
            formLulus.classList.remove('hidden');
            jenisMigrasi.value = 'lulus';
            
            btnSubmit.innerHTML = 'Luluskan Siswa';
            btnSubmit.classList.remove('bg-amber-500', 'hover:bg-amber-600');
            btnSubmit.classList.add('bg-emerald-500', 'hover:bg-emerald-600');
            
            headerBg.classList.remove('bg-amber-500');
            headerBg.classList.add('bg-emerald-500');
            
            titleModal.innerText = 'Luluskan Rombel';
            descModal.innerText = 'Proses kelulusan siswa tingkat akhir';
            
            document.querySelector('#migrateRombelModal [name="target_tahun_ajaran"]').removeAttribute('required');
            document.getElementById('migrasiTingkat').removeAttribute('required');
            document.getElementById('migrasiNamaRombel').removeAttribute('required');

            if (data.jumlah_siswa == 0) {
                if (normalLulusMsg) normalLulusMsg.classList.add('hidden');
                if (emptyWarningLulus) emptyWarningLulus.classList.remove('hidden');
                btnSubmit.disabled = true; 
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                if (normalLulusMsg) normalLulusMsg.classList.remove('hidden');
                if (emptyWarningLulus) emptyWarningLulus.classList.add('hidden');
            }

        } else {
            formNaikKelas.classList.remove('hidden');
            formLulus.classList.add('hidden');
            jenisMigrasi.value = 'naik_kelas';
            
            btnSubmit.innerHTML = 'Proses Migrasi';
            btnSubmit.classList.remove('bg-emerald-500', 'hover:bg-emerald-600');
            btnSubmit.classList.add('bg-amber-500', 'hover:bg-amber-600');
            
            headerBg.classList.remove('bg-emerald-500');
            headerBg.classList.add('bg-amber-500');
            
            titleModal.innerText = 'Migrasi / Naik Tingkat';
            descModal.innerText = 'Duplikasi rombel ke semester atau TA baru';
            
            let nextLevel = data.tingkat;
            if (data.tingkat === 'VII' || data.tingkat == '7') nextLevel = 'VIII';
            else if (data.tingkat === 'VIII' || data.tingkat == '8') nextLevel = 'IX';
            
            document.getElementById('migrasiTingkat').value = nextLevel; 
            document.getElementById('migrasiNamaRombel').value = data.nama_rombel; 
            
            document.querySelector('#migrateRombelModal [name="target_tahun_ajaran"]').setAttribute('required', 'required');
            document.getElementById('migrasiTingkat').setAttribute('required', 'required');
            document.getElementById('migrasiNamaRombel').setAttribute('required', 'required');
        }

        const modal = document.getElementById('migrateRombelModal');
        if(modal) { modal.classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
    })
    .catch(err => Swal.fire('Error', err.message, 'error'));
}

window.closeMigrateModal = function() {
    const modal = document.getElementById('migrateRombelModal');
    if(modal) { modal.classList.add('hidden'); document.body.style.overflow = ''; }
    if(document.getElementById('formMigrateRombel')) document.getElementById('formMigrateRombel').reset();
}

window.handleMigrateSubmit = function(event) {
    event.preventDefault();
    const form = event.target;
    const btn = form.querySelector('button[type="submit"]');
    const ori = btn.innerHTML;
    btn.innerHTML = 'Memproses...'; 
    btn.disabled = true;

    const formData = new FormData(form);

    let headers = { 'X-Requested-With': 'XMLHttpRequest' };
    try {
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfHeader = document.querySelector('meta[name="csrf-header"]');
        if (csrfMeta && csrfHeader) {
            headers[csrfHeader.getAttribute('content')] = csrfMeta.getAttribute('content');
        }
    } catch(e) {}

    fetch(BASE_URL + 'admin/rombel/migrate', { 
        method: 'POST', body: formData, headers: headers
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            closeMigrateModal();
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: data.message, showConfirmButton: false, timer: 2000
            }).then(() => location.reload());
        } else {
            Swal.fire('Gagal', data.message, 'error');
        }
    })
    .catch(err => Swal.fire('Error', 'Terjadi kesalahan sistem', 'error'))
    .finally(() => { btn.innerHTML = ori; btn.disabled = false; });
}

// ============================================
// 6. IMPORT EXCEL
// ============================================
window.showImportModal = function() {
    const modal = document.getElementById('importModal');
    if(modal) { modal.classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
};

window.closeImportModal = function() {
    const modal = document.getElementById('importModal');
    if(modal) { modal.classList.add('hidden'); document.body.style.overflow = ''; }
};

window.handleImport = async function(event) {
    event.preventDefault();
    const form = event.target;
    const btn = form.querySelector('button[type="submit"]');
    const oriText = btn.innerHTML;

    btn.innerHTML = window.LANG?.js_analyzing || 'Menganalisis & Upload...';
    btn.disabled = true;

    try {
        const response = await fetch(form.action, { method: 'POST', body: new FormData(form) });
        const rawText = await response.text();
        let data;
        
        try { data = JSON.parse(rawText); } 
        catch (e) {
            console.error("SERVER CRASH! Raw Response:", rawText);
            document.open(); document.write(rawText); document.close();
            return; 
        }

        if (data.status === 'success') {
            Swal.fire({ icon: 'success', title: window.LANG?.js_success || 'Berhasil', text: data.message, timer: 2000, showConfirmButton: false
            }).then(() => { closeImportModal(); window.location.reload(); });
        } else {
            Swal.fire(window.LANG?.js_failed || 'Gagal', data.message, 'error');
        }
    } catch (error) {
        Swal.fire('Error', window.LANG?.js_error_connection || 'Koneksi ke server terputus.', 'error');
    } finally {
        if(btn) { btn.innerHTML = oriText; btn.disabled = false; }
    }
};

// ============================================
// 7. FITUR MIGRASI MASSAL (SAPU JAGAT)
// ============================================
window.showMigrateMassalModal = function() {
    const modal = document.getElementById('migrateMassalModal');
    if(modal) { modal.classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
}

window.closeMigrateMassalModal = function() {
    const modal = document.getElementById('migrateMassalModal');
    if(modal) { modal.classList.add('hidden'); document.body.style.overflow = ''; }
    if(document.getElementById('formMigrateMassal')) document.getElementById('formMigrateMassal').reset();
}

window.handleMigrateMassalSubmit = function(event) {
    event.preventDefault();
    const form = event.target;
    const btn = form.querySelector('button[type="submit"]');
    const ori = btn.innerHTML;

    Swal.fire({
        title: 'Yakin Lakukan Migrasi Massal?',
        html: "Aksi ini tidak dapat dibatalkan. Sistem akan memindahkan seluruh rombel dan siswa secara otomatis.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, Lanjutkan!'
    }).then((result) => {
        if (result.isConfirmed) {
            btn.innerHTML = '<svg class="animate-spin w-5 h-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...'; 
            btn.disabled = true;

            const formData = new FormData(form);

            let headers = { 'X-Requested-With': 'XMLHttpRequest' };
            try {
                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                const csrfHeader = document.querySelector('meta[name="csrf-header"]');
                if (csrfMeta && csrfHeader) {
                    headers[csrfHeader.getAttribute('content')] = csrfMeta.getAttribute('content');
                }
            } catch(e) {}

            fetch(BASE_URL + 'admin/rombel/migrateMassal', { 
                method: 'POST', body: formData, headers: headers
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    closeMigrateMassalModal();
                    Swal.fire({ icon: 'success', title: 'Berhasil!', html: data.message, showConfirmButton: true
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Gagal', data.message, 'error');
                }
            })
            .catch(err => Swal.fire('Error', 'Terjadi kesalahan sistem', 'error'))
            .finally(() => { btn.innerHTML = ori; btn.disabled = false; });
        }
    });
}

// 8. EVENT LISTENER GLOBAL
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        if(typeof closeDrawer === 'function') closeDrawer();
        if(typeof closeAddRombelModal === 'function') closeAddRombelModal();
        if(typeof closeDetailTingkatModal === 'function') closeDetailTingkatModal();
        if(typeof closeStudentManagementModal === 'function') closeStudentManagementModal();
        if(typeof closeImportModal === 'function') closeImportModal();
        if(typeof closeDeleteModal === 'function') closeDeleteModal();
        if(typeof closeMigrateModal === 'function') closeMigrateModal();
        if(typeof closeMigrateMassalModal === 'function') closeMigrateMassalModal();
    }
});

document.addEventListener("DOMContentLoaded", function () {
    updateUI();
    populateLevels(); 
    
    // Auto filter saat halaman pertama kali diload
    filterTableDOM(); 
});