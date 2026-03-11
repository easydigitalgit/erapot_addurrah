// --- 1. VARIABEL GLOBAL ---
let students = [];
let filteredStudents = [];
let rombelData = [];
let currentPage = 1;
const itemsPerPage = 10; 


// --- 2. LOAD DATA DARI API ---
function loadStudents() {
    const tbody = document.getElementById('studentTableBody');
    const urlElement = document.getElementById('api-url');
    
    if (!tbody || !urlElement) return;

    tbody.innerHTML = `
        <tr>
            <td colspan="8" class="text-center py-12 text-gray-500 animate-pulse">
                <span class="text-sm font-medium">${LANG.js_loading_data}</span>
            </td>
        </tr>`;

    fetch(urlElement.dataset.url)
        .then(response => response.json())
        .then(data => {
            console.log("Data Siswa Raw dari DB:", data); // <-- Baris untuk debugging di Inspect Element
            
            // --- PROSES NORMALISASI DATA SUPER KETAT ---
            students = data.map(s => {
                
                // 1. Amankan Status Siswa (Prioritaskan alias 'stat_siswa')
                let sts = s.stat_siswa || s.status_siswa;
                if (!sts || sts === 'undefined' || sts === 'null' || String(sts).trim() === '') {
                    s.status_aman = 'Aktif';
                } else {
                    s.status_aman = sts;
                }

                // 2. Amankan Gender (Prioritaskan alias 'jk_siswa')
                let jk = s.jk_siswa || s.jenis_kelamin;
                if (!jk || jk === 'undefined' || jk === 'null' || String(jk).trim() === '') {
                    s.jk_aman = '-';
                } else {
                    s.jk_aman = jk;
                }

                // 3. Amankan Nama Wali Kelas
                let guru = s.nama_wali_kelas;
                if (!guru || guru === 'undefined' || guru === 'null' || String(guru).trim() === '') {
                    s.guru_aman = '';
                } else {
                    s.guru_aman = guru;
                }

                return s;
            });
            // ---------------------------------------------------------------

            filteredStudents = [...students];
            currentPage = 1;
            applyFilters(); 
        })
        .catch(error => {
            console.error('Error loading students:', error);
            tbody.innerHTML = `<tr><td colspan="8" class="text-center py-8 text-red-500">${LANG.js_server_error}</td></tr>`;
        });
}

function loadRombelOptions() {
    const baseUrl = document.getElementById('base-url').dataset.url;
    
    fetch(baseUrl + '/admin/siswa/get-rombel')
        .then(r => r.json())
        .then(data => {
            rombelData = data;
            const select = document.getElementById('rombel_id'); 
            
            if(select) {
                // FIX: Menghilangkan undefined dari Dropdown Rombel
                const txtSelect = LANG.js_select_rombel || 'Pilih Rombel...';
                const txtNoClass = LANG.not_in_class || 'Belum Masuk Kelas';
                
                select.innerHTML = `<option value="">${txtSelect} / ${txtNoClass}</option>`;
                data.forEach(r => {
                    select.innerHTML += `<option value="${r.id}">${r.tingkat} - ${r.nama_rombel}</option>`;
                });
            }
        })
        .catch(err => console.error("Gagal load rombel:", err));
}

// --- 3. FILTER & PENCARIAN ---
function applyFilters() {
    const term = document.getElementById('searchInput')?.value.toLowerCase().trim() || '';
    const level = document.getElementById('filterLevel')?.value || '';
    const status = document.getElementById('filterStatus')?.value || '';
    const selectedYear = document.getElementById('filterTahunAkurat')?.value || ''; 

    filteredStudents = students.filter(s => {
        const nama = (s.nama_lengkap || '').toLowerCase();
        const nis = String(s.nis || '').trim();
        const rombelFull = (s.tingkat ? s.tingkat + ' ' : '') + (s.nama_rombel || '');
        const statusSiswa = String(s.status_siswa || 'Aktif').trim();

        const matchSearch = term === '' || nama.includes(term) || nis.includes(term) || rombelFull.toLowerCase().includes(term);
        const matchLevel = level === '' || (s.tingkat === level) || (s.diterima_dikelas === level);
        const matchStatus = status === '' || statusSiswa === status;
        
        let matchYear = true;
        
        if (selectedYear !== '') {
            const twoDigitYear = selectedYear.substring(2, 4); 
            const targetPattern = `.${twoDigitYear}.`; 
            matchYear = nis.includes(targetPattern);
        }

        return matchSearch && matchLevel && matchStatus && matchYear;
    });

    currentPage = 1; 
    renderPagination();
    renderTable();
}

// --- INIT ---
document.addEventListener('DOMContentLoaded', () => {
    loadStudents();
    loadRombelOptions();
    loadWilayah(); 
    
    ['searchInput', 'filterLevel', 'filterStatus', 'filterTahunAkurat'].forEach(id => {
        const el = document.getElementById(id);
        if(el) {
            el.addEventListener(id === 'searchInput' ? 'keyup' : 'change', applyFilters);
        }
    });
});

// --- 4. RENDER TABEL ---
// --- 4. RENDER TABEL ---
function renderTable() {
    const tbody = document.getElementById('studentTableBody');
    const baseUrl = document.getElementById('base-url').dataset.url;

    if (filteredStudents.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="px-6 py-12 text-center text-gray-500 dark:text-slate-400">${LANG.js_no_data}</td></tr>`;
        return;
    }

    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const paginatedItems = filteredStudents.slice(start, end);

    tbody.innerHTML = paginatedItems.map(student => {
        let displayRombel = '-';
        if (student.tingkat && student.nama_rombel) {
            displayRombel = `<span class="font-bold text-emerald-700 dark:text-emerald-400">${student.tingkat}</span> - ${student.nama_rombel}`;
        } else if (student.diterima_dikelas) {
            displayRombel = `${LANG.js_candidate} ${student.diterima_dikelas}`;
        }

        const nama = student.nama_lengkap || '-';
        const inisial = nama.substring(0,2).toUpperCase();
        
        // PANGGIL LANGSUNG VARIABEL AMAN YANG BARU KITA BUAT
        const status = student.status_aman; 
        const jk = student.jk_aman;
        const textGender = (jk === 'L') ? 'Laki-laki' : (jk === 'P' ? 'Perempuan' : '-');
        
        // LOGIKA WALI KELAS YANG AMAN
        let displayWaliKelas = `<span class="text-gray-400 dark:text-slate-500 italic text-xs">${LANG.js_not_set || 'Belum diatur'}</span>`;
        if (student.guru_aman !== '') {
            displayWaliKelas = `<div class="flex items-center justify-center gap-1.5 cursor-help" title="${student.guru_aman}">
                                    <div class="w-6 h-6 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center text-[10px] font-bold shrink-0">
                                        ${student.guru_aman.substring(0, 2).toUpperCase()}
                                    </div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-slate-300 truncate max-w-[120px]">
                                        ${student.guru_aman}
                                    </span>
                                </div>`;
        }
        
        // LOGIKA BADGE STATUS (Pastikan warna sesuai)
        let badgeClass = status === 'Aktif' 
            ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border dark:border-emerald-800/50' 
            : 'bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-slate-300 border dark:border-slate-600';
            
        // Text status terjemahan (Mencegah undefined dari language pack)
        let statusTextUI = status;
        if(status === 'Aktif') statusTextUI = LANG.active || 'Aktif';
        else if(status === 'Lulus') statusTextUI = LANG.graduated || 'Lulus';
        else if(status === 'Pindah') statusTextUI = LANG.moved || 'Pindah';
        else if(status === 'Keluar') statusTextUI = LANG.dropped_out || 'Keluar';
        
        let avatarHTML = '';
        const fallbackAvatar = `https://ui-avatars.com/api/?name=${inisial}&background=1F7A4D&color=fff&size=100&bold=true&rounded=true`;
        
        if (student.foto_siswa && student.foto_siswa !== 'null' && String(student.foto_siswa).trim() !== '') {
            const cleanBaseUrl = baseUrl.replace(/\/$/, '');
            const fotoUrl = `${cleanBaseUrl}/uploads/siswa/${student.foto_siswa}`;
            avatarHTML = `<img src="${fotoUrl}" class="w-full h-full object-cover border border-slate-200 dark:border-slate-600 shadow-sm" onerror="this.onerror=null; this.src='${fallbackAvatar}';">`;
        } else {
            avatarHTML = `<img src="${fallbackAvatar}" class="w-full h-full object-cover border border-slate-200 dark:border-slate-600 shadow-sm">`;
        }

        return `
        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 border-b border-gray-100 dark:border-slate-700/50 group transition-colors">
            <td class="px-4 py-4 text-center">
                <input type="checkbox" class="student-checkbox w-4 h-4 text-emerald-600 rounded border-gray-300 dark:border-slate-500 bg-white dark:bg-slate-700 focus:ring-emerald-500 cursor-pointer focus:ring-offset-0" value="${student.id}" onchange="updateBulkActionVisibility()">
            </td>
            <td class="px-4 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-100 dark:bg-slate-700 flex-shrink-0 cursor-pointer shadow-sm hover:opacity-80 transition-opacity" onclick="showDetailDrawer(${student.id})">
                        ${avatarHTML}
                    </div>
                    <div>
                        <div class="font-semibold text-gray-800 dark:text-white cursor-pointer hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors truncate max-w-[180px]" onclick="showDetailDrawer(${student.id})">${nama}</div>
                        <div class="text-xs text-gray-500 dark:text-slate-400 truncate max-w-[180px]">${student.email_siswa || '-'}</div>
                    </div>
                </div>
            </td>
            <td class="px-4 py-4">
                <div class="text-sm">
                    <div class="font-medium text-gray-800 dark:text-slate-200">${student.nis || '-'}</div>
                    <div class="text-xs text-gray-500 dark:text-slate-400">${student.nisn || '-'}</div>
                </div>
            </td>
            <td class="px-4 py-4 text-center">
                <span class="text-sm text-gray-700 dark:text-slate-300">${textGender}</span>
            </td>
            <td class="px-4 py-4 text-center">
                <span class="text-sm font-medium text-gray-800 dark:text-slate-200 bg-gray-100 dark:bg-slate-700 px-2.5 py-1 rounded-lg border border-gray-200 dark:border-slate-600 shadow-sm">${displayRombel}</span>
            </td>
            
            <td class="px-4 py-4 text-center">
                ${displayWaliKelas}
            </td>
            
            <td class="px-4 py-4 text-center">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${badgeClass}">
                    ${statusTextUI}
                </span>
            </td>
            <td class="px-4 py-4 text-center">
                <div class="flex justify-center gap-2 opacity-1 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                    <button onclick="showDetailDrawer(${student.id})" class="p-2 text-gray-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-emerald-600 dark:hover:text-emerald-400 rounded-lg transition-colors" title="${LANG.js_view_detail}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </button>
                    <button onclick="editStudent(${student.id})" class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="${LANG.js_edit}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    </button>
                   <button data-id="${student.id}" data-nama="${nama.replace(/"/g, '&quot;')}" onclick="triggerDeleteStudent(this)" class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Hapus">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

// --- 5. PAGINATION ---
function renderPagination() {
    const totalPages = Math.ceil(filteredStudents.length / itemsPerPage);
    const infoContainer = document.getElementById('pagination-info');
    const btnContainer = document.getElementById('pagination-buttons');

    const start = (filteredStudents.length === 0) ? 0 : (currentPage - 1) * itemsPerPage + 1;
    const end = Math.min(currentPage * itemsPerPage, filteredStudents.length);

    if (infoContainer) {
        infoContainer.innerHTML = `${LANG.js_showing} <span class="font-bold text-gray-900 dark:text-white">${start}-${end}</span> ${LANG.js_from} <span class="font-bold text-gray-900 dark:text-white">${filteredStudents.length}</span> ${LANG.js_data}`;
    }

    if (btnContainer) {
        let btns = `<button onclick="changePage(${currentPage - 1})" class="px-3 py-2 border border-gray-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-800 text-gray-600 dark:text-slate-300 ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors'}" ${currentPage === 1 ? 'disabled' : ''}>&lt;</button>`;
        
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                const activeClass = i === currentPage 
                    ? 'bg-emerald-600 text-white border-emerald-600' 
                    : 'bg-white dark:bg-slate-800 text-gray-600 dark:text-slate-300 border-gray-200 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-700';
                
                btns += `<button onclick="changePage(${i})" class="px-3.5 py-2 border rounded-lg text-sm transition-colors ${activeClass}">${i}</button>`;
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                btns += `<span class="px-2 text-gray-500 dark:text-slate-400">...</span>`;
            }
        }
        
        btns += `<button onclick="changePage(${currentPage + 1})" class="px-3 py-2 border border-gray-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-800 text-gray-600 dark:text-slate-300 ${currentPage >= totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors'}" ${currentPage >= totalPages ? 'disabled' : ''}>&gt;</button>`;
        
        btnContainer.innerHTML = btns;
    }
}

function changePage(page) {
    const totalPages = Math.ceil(filteredStudents.length / itemsPerPage);
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    renderTable();
    renderPagination();
}

// --- 6. MODAL & FORM ---
window.showAddModal = function() {
    const modal = document.getElementById('addModal');
    const form = document.getElementById('addStudentForm');
    const baseUrl = document.getElementById('base-url').dataset.url;
    
    resetForm();
    
    // FIX: Teks Dinamis
    const titleEl = document.querySelector('#addModal h3');
    const subtitleEl = document.querySelector('#addModal p.text-gray-500');
    const btnSubmit = form.querySelector('button[type="submit"]');
    
    if(titleEl) titleEl.innerText = LANG.js_add_student || 'Tambah Siswa Baru';
    if(subtitleEl) subtitleEl.innerText = LANG.form_subtitle || 'Input data siswa beserta wali secara bersamaan';
    if(btnSubmit) btnSubmit.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> ${LANG.save_all_data || 'Simpan Seluruh Data'}`;
    
    form.action = `${baseUrl}/admin/siswa/store`;
    modal.classList.remove('hidden');
};

window.closeAddModal = function() {
    document.getElementById('addModal').classList.add('hidden');
};

window.resetForm = function() {
    const form = document.getElementById('addStudentForm');
    const preview = document.getElementById('photoPreview');
    if(form) {
        form.reset();
        if(form.elements['id']) form.elements['id'].value = '';
    }
    if(preview) preview.innerHTML = `<svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>`;
};

window.editStudent = async function(id) {
    const student = students.find(s => s.id == id);
    if (!student) return;

    const modal = document.getElementById('addModal');
    const form = document.getElementById('addStudentForm');
    const baseUrl = document.getElementById('base-url').dataset.url;
    
    // FIX: Teks Dinamis untuk Edit
    const titleEl = document.querySelector('#addModal h3');
    const subtitleEl = document.querySelector('#addModal p.text-gray-500');
    const btnSubmit = form.querySelector('button[type="submit"]');
    
    if(titleEl) titleEl.innerText = LANG.js_edit_student || 'Edit Data Siswa';
    if(subtitleEl) subtitleEl.innerText = LANG.form_subtitle || 'Update informasi siswa secara detail';
    if(btnSubmit) btnSubmit.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> ${LANG.btn_update || 'Update Data'}`;
    
    form.action = `${baseUrl}/admin/siswa/update/${id}`;

   const setVal = (name, val) => { 
        if(form.elements[name]) {
            form.elements[name].value = (val && val !== 'null' && val !== '-') ? val : ''; 
        } 
    };
    
    // A. Identitas
    setVal('nis', student.nis);
    setVal('nisn', student.nisn);
    setVal('nik', student.nik);
    setVal('nama_lengkap', student.nama_lengkap);
    
    // PERBAIKAN GENDER EDIT:
    // Di editStudent
    setVal('jenis_kelamin', student.jk_siswa);
    setVal('status_siswa', student.stat_siswa);
    
    setVal('agama', student.agama);
    setVal('tempat_lahir', student.tempat_lahir);
    setVal('tanggal_lahir', student.tanggal_lahir);
    setVal('no_kk', student.no_kk);
    setVal('no_registrasi_akta', student.no_registrasi_akta);

    setVal('status_dalam_keluarga', student.stat_keluarga);
    setVal('anak_ke', student.anak_ke);
    setVal('jml_saudara_kandung', student.jml_saudara_kandung);
    setVal('kebutuhan_khusus', student.kebutuhan_khusus);
    setVal('berat_badan', student.berat_badan);
    setVal('tinggi_badan', student.tinggi_badan);
    setVal('lingkar_kepala', student.lingkar_kepala);
    setVal('jarak_ke_sekolah', student.jarak_ke_sekolah);

    setVal('alamat_siswa', student.alamat_siswa);
    setVal('dusun', student.dusun);
    setVal('rt', student.rt);
    setVal('rw', student.rw);
    setVal('kode_pos', student.kode_pos);
    setVal('jenis_tinggal', student.jenis_tinggal);
    setVal('alat_transportasi', student.alat_transportasi);
    setVal('no_hp', student.no_hp);
    setVal('email_siswa', student.email_siswa);
    
    setVal('kecamatan', student.kecamatan);
    if(student.kecamatan) {
        loadKelurahanOptions(student.kecamatan, student.kelurahan);
    } else {
        setVal('kelurahan', '');
    }

    setVal('rombel_id', student.rombel_id);
    setVal('diterima_dikelas', student.diterima_dikelas);
    setVal('tgl_diterima', student.tgl_diterima);
    setVal('asal_sekolah', student.asal_sekolah);
    setVal('skhun', student.skhun);
    setVal('no_peserta_un', student.no_peserta_un);
    setVal('no_seri_ijazah', student.no_seri_ijazah);

    setVal('penerima_kps', student.penerima_kps);
    setVal('no_kps', student.no_kps);
    setVal('penerima_kip', student.penerima_kip);
    setVal('nomor_kip', student.nomor_kip);
    setVal('nama_di_kip', student.nama_di_kip);
    setVal('nomor_kks', student.nomor_kks);
    setVal('layak_pip', student.layak_pip);
    setVal('alasan_layak_pip', student.alasan_layak_pip);

    setVal('nama_ayah', student.nama_ayah);
    setVal('nik_ayah', student.nik_ayah);
    setVal('tahun_lahir_ayah', student.tahun_lahir_ayah);
    setVal('pendidikan_ayah', student.pendidikan_ayah);
    setVal('pekerjaan_ayah', student.pekerjaan_ayah);
    setVal('penghasilan_ayah', student.penghasilan_ayah);
    
    setVal('nama_ibu', student.nama_ibu);
    setVal('nik_ibu', student.nik_ibu);
    setVal('tahun_lahir_ibu', student.tahun_lahir_ibu);
    setVal('pendidikan_ibu', student.pendidikan_ibu);
    setVal('pekerjaan_ibu', student.pekerjaan_ibu);
    setVal('penghasilan_ibu', student.penghasilan_ibu);
    
    setVal('nama_wali', student.nama_wali);
    setVal('nik_wali', student.nik_wali);
    setVal('pekerjaan_wali', student.pekerjaan_wali);
    setVal('no_hp_ortu', student.no_hp_ortu);

    

    const preview = document.getElementById('photoPreview');
    if (student.foto_siswa && student.foto_siswa !== 'null' && String(student.foto_siswa).trim() !== '') {
        const cleanBaseUrl = baseUrl.replace(/\/$/, '');
        preview.innerHTML = `<img src="${cleanBaseUrl}/uploads/siswa/${student.foto_siswa}" class="w-full h-full object-cover">`;
    } else {
        preview.innerHTML = `<svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>`;
    }

    document.getElementById('addModal').classList.remove('hidden');
};

window.handleSubmit = function(event) {
    event.preventDefault();
    const form = event.target;
    const btn = form.querySelector('button[type="submit"]');
    const oriText = btn.innerHTML;
    btn.innerHTML = LANG.js_saving; btn.disabled = true;

    fetch(form.action, { method: 'POST', body: new FormData(form), headers: {'X-Requested-With': 'XMLHttpRequest'} })
    .then(r => r.json())
    .then(data => {
        if(data.status === 'success') {
            Swal.fire({icon: 'success', title: LANG.js_success, text: data.message, timer: 1500, showConfirmButton: false}).then(() => {
                closeAddModal();
                loadStudents();
            });
        } else {
            Swal.fire(LANG.js_failed, data.message || 'Error', 'error');
        }
    })
    .catch(e => Swal.fire('Error', LANG.js_server_error, 'error'))
    .finally(() => { btn.innerHTML = oriText; btn.disabled = false; });
};

// --- AKSI DELETE SATUAN ---
// --- AKSI DELETE SATUAN (KEBAL ERROR & CSRF PROTECTED) ---
window.triggerDeleteStudent = function(btn) {
    const id = btn.getAttribute('data-id');
    const name = btn.getAttribute('data-nama');
    
    // Ambil kunci CSRF dari form tambah agar diizinkan oleh CodeIgniter
    const csrfInput = document.querySelector('input[name="csrf_test_name"]');
    const csrfToken = csrfInput ? csrfInput.value : '';

    Swal.fire({
        title: 'Yakin Hapus Data?',
        text: `Data Siswa "${name}" dan Data Walinya akan dihapus permanen!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#3b82f6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.showLoading();
            const url = document.getElementById('delete-url').dataset.url + '/' + id;
            
            fetch(url, { 
                method: 'DELETE', 
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken // Suntikkan kunci keamanannya di sini!
                } 
            })
            .then(r => r.json())
            .then(d => {
                if(d.status === 'success') { 
                    Swal.fire('Terhapus!', d.message, 'success'); 
                    loadStudents(); // Refresh tabel
                }
                else Swal.fire('Gagal', d.message, 'error');
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error!', 'Terjadi kesalahan. Jika terus berlanjut, muat ulang (refresh) halaman ini.', 'error');
            });
        }
    });
};

// --- 7. DETAIL DRAWER ---
window.showDetailDrawer = function(id) {
    const s = students.find(x => x.id == id);
    if (!s) return;

    const baseUrl = document.getElementById('base-url').dataset.url;

    const v = (val) => (val && String(val).trim() !== '' && val !== 'null') ? val : '-';
    
    const fmtDate = (dateStr) => {
        if(!dateStr || dateStr === 'null' || dateStr === '0000-00-00') return '-';
        const date = new Date(dateStr);
        return new Intl.DateTimeFormat(APP_LANG, { day: 'numeric', month: 'long', year: 'numeric' }).format(date);
    };

    // 0. Header Profil
    document.getElementById('detailName').innerText = v(s.nama_lengkap);
    document.getElementById('detailNis').innerText = s.nis ? s.nis : 'No NIS';
    
    // Di dalam window.showDetailDrawer
    // PERBAIKAN GENDER DRAWER:
    
   // Di showDetailDrawer, panggil variabel aman yang sudah dibuat!
    document.getElementById('detailGender').innerText = (s.jk_aman === 'L') ? 'Laki-laki' : (s.jk_aman === 'P' ? 'Perempuan' : '-');
    
    const statusEl = document.getElementById('detailStatus');
    statusEl.innerText = s.status_aman;
    statusEl.className = `inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ${
        s.status_aman === 'Aktif' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'
    }`;

    const avatarContainer = document.getElementById('detailAvatarContainer');
    const inisialDrawer = s.nama_lengkap.substring(0, 2).toUpperCase();
    const fallbackDrawer = `https://ui-avatars.com/api/?name=${inisialDrawer}&background=1F7A4D&color=fff&size=160&bold=true&rounded=true`;

    if (s.foto_siswa && s.foto_siswa !== 'null' && String(s.foto_siswa).trim() !== '') {
        const cleanBaseUrl = baseUrl.replace(/\/$/, '');
        const fotoUrl = `${cleanBaseUrl}/uploads/siswa/${s.foto_siswa}`;
        avatarContainer.innerHTML = `<img src="${fotoUrl}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='${fallbackDrawer}';">`;
    } else {
        avatarContainer.innerHTML = `<img src="${fallbackDrawer}" class="w-full h-full object-cover">`;
    }

    document.getElementById('detailNisn').innerText = v(s.nisn);
    document.getElementById('detailNik').innerText = v(s.nik);
    document.getElementById('detailKk').innerText = v(s.no_kk);
    document.getElementById('detailAkta').innerText = v(s.no_registrasi_akta);
    
    const ttl = (s.tempat_lahir || '') + ', ' + fmtDate(s.tanggal_lahir);
    document.getElementById('detailTtl').innerText = ttl === ', -' ? '-' : ttl;
    document.getElementById('detailAgama').innerText = v(s.agama);

    document.getElementById('detailAnakKe').innerText = v(s.anak_ke);
    document.getElementById('detailSaudara').innerText = v(s.jml_saudara_kandung);
    document.getElementById('detailKhusus').innerText = v(s.kebutuhan_khusus);
    document.getElementById('detailBerat').innerText = v(s.berat_badan);
    document.getElementById('detailTinggi').innerText = v(s.tinggi_badan);
    document.getElementById('detailKepala').innerText = v(s.lingkar_kepala);

    let fullAlamat = v(s.alamat_siswa);
    if (s.rt || s.rw) fullAlamat += ` RT ${v(s.rt)}/RW ${v(s.rw)}`;
    if (s.dusun) fullAlamat += `, Dusun ${v(s.dusun)}`;
    if (s.kelurahan) fullAlamat += `, Kel. ${v(s.kelurahan)}`;
    if (s.kecamatan) fullAlamat += `, Kec. ${v(s.kecamatan)}`;
    
    document.getElementById('detailAlamatFull').innerText = fullAlamat;
    document.getElementById('detailTinggal').innerText = v(s.jenis_tinggal);
    document.getElementById('detailTransport').innerText = v(s.alat_transportasi);
    document.getElementById('detailJarak').innerText = v(s.jarak_ke_sekolah);
    document.getElementById('detailPos').innerText = v(s.kode_pos);

    document.getElementById('detailHp').innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg> ${v(s.no_hp || s.no_telp_rumah)}`;
    document.getElementById('detailEmail').innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> ${v(s.email_siswa)}`;

    let rombel = '-';
    if(s.nama_rombel) rombel = `${s.tingkat || ''} ${s.nama_rombel}`;
    else if(s.diterima_dikelas) rombel = `${LANG.js_candidate} ${s.diterima_dikelas}`;
    
    document.getElementById('detailRombel').innerText = rombel;
    document.getElementById('detailTglMasuk').innerText = fmtDate(s.tgl_diterima);
    document.getElementById('detailSekolahAsal').innerText = v(s.asal_sekolah);
    
    document.getElementById('detailSkhun').innerText = v(s.skhun);
    
    let infoUN = v(s.no_peserta_un);
    if(s.no_seri_ijazah) infoUN += ` / ${v(s.no_seri_ijazah)}`;
    document.getElementById('detailIjazah').innerText = infoUN;

    let txtKps = v(s.penerima_kps);
    if (s.penerima_kps === 'Ya' && s.no_kps) txtKps += ` (${s.no_kps})`;
    document.getElementById('detailKps').innerText = txtKps;

    let txtKip = v(s.penerima_kip);
    if (s.penerima_kip === 'Ya' && s.nomor_kip) txtKip += ` (${s.nomor_kip})`;
    if (s.layak_pip === 'Ya') txtKip += ` | PIP: Ya`;
    document.getElementById('detailKip').innerText = txtKip;

    document.getElementById('dtlAyahNama').innerText = v(s.nama_ayah);
    document.getElementById('dtlAyahNik').innerText = v(s.nik_ayah);
    document.getElementById('dtlAyahLahir').innerText = v(s.tahun_lahir_ayah);
    document.getElementById('dtlAyahPend').innerText = v(s.pendidikan_ayah);
    document.getElementById('dtlAyahKerja').innerText = v(s.pekerjaan_ayah);
    document.getElementById('dtlAyahGaji').innerText = v(s.penghasilan_ayah);

    document.getElementById('dtlIbuNama').innerText = v(s.nama_ibu);
    document.getElementById('dtlIbuNik').innerText = v(s.nik_ibu);
    document.getElementById('dtlIbuLahir').innerText = v(s.tahun_lahir_ibu);
    document.getElementById('dtlIbuPend').innerText = v(s.pendidikan_ibu);
    document.getElementById('dtlIbuKerja').innerText = v(s.pekerjaan_ibu);
    document.getElementById('dtlIbuGaji').innerText = v(s.penghasilan_ibu);

    document.getElementById('dtlWaliNama').innerText = v(s.nama_wali);
    document.getElementById('dtlWaliNik').innerText = v(s.nik_wali);
    document.getElementById('dtlWaliKerja').innerText = v(s.pekerjaan_wali);

    const btnEdit = document.getElementById('btnDrawerEdit');
    btnEdit.onclick = function() {
        closeDrawer(); 
        setTimeout(() => editStudent(id), 300);
    };

    const drawer = document.getElementById('detailDrawer');
    const overlay = document.getElementById('drawer-overlay');
    if(drawer && overlay) {
        overlay.classList.remove('hidden');
        setTimeout(() => {
            drawer.classList.remove('translate-x-full');
        }, 10);
    }
};

window.closeDrawer = function() {
    const drawer = document.getElementById('detailDrawer');
    const overlay = document.getElementById('drawer-overlay');
    if(drawer && overlay) {
        drawer.classList.add('translate-x-full');
        setTimeout(() => {
            overlay.classList.add('hidden');
        }, 300);
    }
};

function loadWilayah() {
    const baseUrl = document.getElementById('base-url').dataset.url.replace(/\/$/, '');
    const selectKecamatan = document.getElementById('kecamatan');
    
    if (!selectKecamatan) return; 

    fetch(baseUrl + '/admin/siswa/getKecamatan')
        .then(r => r.ok ? r.json() : Promise.reject(r.status))
        .then(data => {
            // Tambahkan || '-- Pilih Kecamatan --' sebagai pengaman
            let optionsHTML = `<option value="">${LANG.js_select_district || '-- Pilih Kecamatan --'}</option>`;
            data.forEach(k => {
                optionsHTML += `<option value="${k.nama}">${k.nama}</option>`;
            });
            selectKecamatan.innerHTML = optionsHTML;
        })
        .catch(err => {
            console.error("Gagal load kecamatan:", err);
            selectKecamatan.innerHTML = `<option value="">${LANG.js_db_error}</option>`;
        });
        
    selectKecamatan.addEventListener('change', function(e) {
        if (e.isTrusted) {
            loadKelurahanOptions(this.value);
        }
    });
}

function loadKelurahanOptions(kecamatanName, selectedKelurahan = '') {
    const selectKelurahan = document.getElementById('kelurahan');
    if (!selectKelurahan) return;
    
    if(!kecamatanName) {
    selectKelurahan.innerHTML = `<option value="">${LANG.js_select_village || '-- Pilih Kelurahan --'}</option>`;
    return;
    }
    selectKelurahan.innerHTML = `<option value="">${LANG.js_loading_village || 'Memuat...'}</option>`;
    const baseUrl = document.getElementById('base-url').dataset.url.replace(/\/$/, '');

    fetch(baseUrl + '/admin/siswa/getKelurahan?kecamatan=' + encodeURIComponent(kecamatanName))
        .then(r => r.ok ? r.json() : Promise.reject(r.status))
        .then(data => {
            let optionsHTML = `<option value="">${LANG.js_select_village}</option>`;
            data.forEach(d => {
                const isSelected = (d.nama === selectedKelurahan) ? 'selected' : '';
                optionsHTML += `<option value="${d.nama}" ${isSelected}>${d.nama}</option>`;
            });
            selectKelurahan.innerHTML = optionsHTML;
        })
        .catch(err => {
            console.error("Gagal load kelurahan:", err);
            selectKelurahan.innerHTML = `<option value="">${LANG.js_no_data}</option>`;
        });
}

window.showImportModal = function() {
    const modal = document.getElementById('importModal');
    if(modal) {
        modal.classList.remove('hidden');
    }
};

window.closeImportModal = function() {
    const modal = document.getElementById('importModal');
    if(modal) modal.classList.add('hidden');
};

window.handleImport = async function(event) {
    event.preventDefault();
    const form = event.target;
    const btn = form.querySelector('button[type="submit"]');
    const oriText = btn.innerHTML;
    
    btn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ${LANG.js_processing}`;
    btn.disabled = true;

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const rawText = await response.text();

        let data;
        try {
            const jsonStart = rawText.indexOf('{');
            const jsonEnd = rawText.lastIndexOf('}') + 1;
            
            if (jsonStart === -1) throw new Error("Tidak ada JSON");
            
            const cleanJson = rawText.substring(jsonStart, jsonEnd);
            data = JSON.parse(cleanJson);
        } catch (e) {
            Swal.fire(LANG.js_info, LANG.js_import_warning, 'warning')
            .then(() => {
                closeImportModal();
                loadStudents();
            });
            return;
        }

        if (data.status === 'success') {
            closeImportModal(); 
            Swal.fire({
                icon: 'success', 
                title: LANG.js_import_success_title, 
                text: data.message, 
                showConfirmButton: true,
                confirmButtonColor: '#10b981' 
            }).then(() => { 
                loadStudents();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: LANG.js_failed,
                html: data.message 
            });
        }

    } catch (error) {
        Swal.fire('Error', LANG.js_connection_lost, 'error');
    } finally {
        if(btn) { 
            btn.innerHTML = oriText; 
            btn.disabled = false; 
        }
    }
}

window.updateBulkActionVisibility = function() {
    const count = document.querySelectorAll('.student-checkbox:checked').length;
    const bulkDiv = document.getElementById('bulkActions');
    const countSpan = document.getElementById('selectedCount');
    
    if(count > 0) {
        if(bulkDiv) bulkDiv.classList.remove('hidden');
        if(countSpan) {
            countSpan.innerText = `(${count} ${LANG.js_selected})`;
            countSpan.classList.remove('hidden');
        }
    } else {
        if(bulkDiv) bulkDiv.classList.add('hidden');
        if(countSpan) countSpan.classList.add('hidden');
        document.getElementById('selectAll').checked = false; 
    }
};

document.addEventListener('change', function(e) {
    if(e.target && e.target.classList.contains('student-checkbox')) {
        updateBulkActionVisibility();
    }
});