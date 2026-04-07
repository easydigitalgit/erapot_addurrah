const API_URL = document.getElementById('api-url')?.getAttribute('data-url');
const DELETE_URL = document.getElementById('delete-url')?.getAttribute('data-url');
const BASE_URL = document.getElementById('base-url')?.getAttribute('data-url');

let allData = [];
let filteredData = [];
let currentPage = 1;
const itemsPerPage = 10;

document.addEventListener('DOMContentLoaded', () => {
    loadData();
    document.getElementById('searchInput')?.addEventListener('input', applyFilters);
    document.getElementById('filterRole')?.addEventListener('change', applyFilters);
    document.getElementById('filterMapel')?.addEventListener('change', applyFilters);
    document.getElementById('filterStatus')?.addEventListener('change', applyFilters);
    document.getElementById('selectAll')?.addEventListener('change', toggleSelectAll);
    document.getElementById('gender')?.addEventListener('change', handleGenderChange);
});

function loadData() {
    const tbody = document.getElementById('teacherTableBody');
    if(!tbody) return;
    
    fetch(API_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json())
        .then(response => {
            allData = response.rows; 
            
            // UPDATE ANGKA PADA CARD SECARA LIVE
            if(response.stats) {
                if(document.getElementById('card-total-guru'))   document.getElementById('card-total-guru').innerText = response.stats.total_guru;
                if(document.getElementById('card-total-tahfiz')) document.getElementById('card-total-tahfiz').innerText = response.stats.total_tahfiz;
                if(document.getElementById('card-total-tendik')) document.getElementById('card-total-tendik').innerText = response.stats.total_tendik;
                if(document.getElementById('card-wali-kelas'))   document.getElementById('card-wali-kelas').innerText = response.stats.wali_kelas;
            }

            applyFilters();
        })
        .catch(err => {
            console.error(err);
            tbody.innerHTML = `<tr><td colspan="9" class="px-6 py-12 text-center text-red-500">${window.LANG.js_load_fail}</td></tr>`;
        });
}

function applyFilters() {
    const search = document.getElementById('searchInput')?.value.toLowerCase() || '';
    const role = document.getElementById('filterRole')?.value || '';
    const mapel = document.getElementById('filterMapel')?.value || '';
    const status = document.getElementById('filterStatus')?.value || '';

    filteredData = allData.filter(item => {
        const matchSearch = (item.nama_lengkap || '').toLowerCase().includes(search) || 
                            (item.nik || '').toLowerCase().includes(search) || 
                            (item.nuptk || '').toLowerCase().includes(search);
        
        // ==========================================
        // FILTER JABATAN HYBRID (ANTI-BOCOR)
        // ==========================================
        let matchRole = true;
        if (role !== '') {
            // 1. Ambil Jabatan Asli dari Master Database
            let jabatanAsli = item.nama_jabatan_master || item.jabatan || '';
            let isOfficialMatch = (jabatanAsli === role);
            
            // 2. Hybrid: Cek apakah dia Guru Mapel TAPI diberi tugas Wali Kelas
            let isHybridWali = false;
            if (role === 'Wali Kelas') {
                if (item.info_wali && item.info_wali !== 'Belum Bimbing Kelas' && item.info_wali !== 'null') {
                    isHybridWali = true;
                }
            }
            
            matchRole = isOfficialMatch || isHybridWali;
        }
        // ==========================================

        const matchMapel = mapel ? (item.nama_mapel_master || item.mapel_utama) === mapel : true;
        
        let itemStatus = item.is_active == 1 ? 'Aktif' : 'Nonaktif';
        const matchStatus = status ? itemStatus === status : true;

        return matchSearch && matchRole && matchMapel && matchStatus;
    });

    currentPage = 1;
    renderTable();
}

function renderTable() {
    const tbody = document.getElementById('teacherTableBody');
    if(!tbody) return;

    tbody.innerHTML = '';

    if (filteredData.length === 0) {
        tbody.innerHTML = `<tr><td colspan="9" class="px-6 py-12 text-center text-gray-500">${window.LANG.js_no_data}</td></tr>`;
        return;
    }

    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const paginated = filteredData.slice(start, end);

    paginated.forEach(item => {
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors group';

        const inisial = (item.nama_lengkap || 'U').substring(0, 2).toUpperCase();
        let safeBaseUrl = BASE_URL;
        if(!safeBaseUrl.endsWith('/')) safeBaseUrl += '/';

        // Pakai path absolut untuk memastikan tidak ada salah baca folder
        const avatarUrl = item.foto_profil 
            ? `${safeBaseUrl}assets/uploads/avatars/${item.foto_profil}` 
            : `https://ui-avatars.com/api/?name=${inisial}&background=10b981&color=fff&bold=true`;

        const isActive = item.is_active == 1;
        const badgeStatus = isActive 
            ? `<span class="px-2.5 py-1 text-[10px] font-bold rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 shadow-sm">${window.LANG.js_status_active}</span>` 
            : `<span class="px-2.5 py-1 text-[10px] font-bold rounded-lg bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400 border border-rose-200 dark:border-rose-800/50 shadow-sm">${window.LANG.js_status_inactive}</span>`;

        tr.innerHTML = `
            <td class="pl-4 md:pl-6 py-4">
                <input type="checkbox" value="${item.id}" class="row-checkbox w-4 h-4 text-emerald-600 rounded border-gray-300 dark:border-slate-500 bg-white dark:bg-slate-700 cursor-pointer" onchange="updateBulkActions()">
            </td>
            <td class="px-4 md:px-6 py-4">
                <div class="flex items-center gap-3">
                    <img src="${avatarUrl}" 
                         class="w-10 h-10 rounded-full object-cover border border-gray-200 dark:border-slate-600 shadow-sm" 
                         style="min-width: 40px; min-height: 40px; display: block !important;"
                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=${inisial}&background=10b981&color=fff'">
                    <div>
                        <div class="font-bold text-sm text-gray-900 dark:text-white">${item.nama_lengkap}</div>
                        <div class="text-[11px] text-gray-500 dark:text-slate-400 font-medium">${item.jabatan_asli || item.nama_jabatan_master || window.LANG.js_role_employee}</div>
                    </div>
                </div>
            </td>
            <td class="px-4 md:px-6 py-4 text-sm text-gray-600 dark:text-slate-300 font-mono">${item.nuptk || '-'}</td>
            <td class="px-4 md:px-6 py-4 text-sm text-gray-600 dark:text-slate-300 font-mono">${item.nik || '-'}</td>
            <td class="px-4 md:px-6 py-4 text-sm text-gray-600 dark:text-slate-300">${item.email || '-'}</td>
            <td class="px-4 md:px-6 py-4 text-sm font-semibold text-gray-800 dark:text-slate-200">${item.mapel_utama || '-'}</td>
            <td class="px-4 md:px-6 py-4 text-xs text-gray-600 dark:text-slate-400">
                <div class="max-w-[150px] truncate" title="${item.kelas_mengajar || '-'}">${item.kelas_mengajar || '-'}</div>
            </td>
            <td class="px-4 md:px-6 py-4">${badgeStatus}</td>
            <td class="px-4 md:px-6 py-4 text-center">
                <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button onclick="showDrawer(${item.id})" class="p-2 bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 dark:hover:bg-blue-900/50 rounded-lg transition-colors outline-none" title="${window.LANG.js_btn_view}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    </button>
                    <button onclick="window.editData(${item.id})" class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-100 dark:bg-amber-900/30 dark:text-amber-400 dark:hover:bg-amber-900/50 rounded-lg transition-colors outline-none" title="${window.LANG.js_btn_edit}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    </button>
                    <button onclick="deleteData(${item.id})" class="p-2 bg-rose-50 text-rose-600 hover:bg-rose-100 dark:bg-rose-900/30 dark:text-rose-400 dark:hover:bg-rose-900/50 rounded-lg transition-colors outline-none" title="${window.LANG.js_btn_yes_del}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                    <button onclick="window.toggleStatus(${item.id})" class="p-2 ${isActive ? 'bg-slate-50 text-slate-600 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-400' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400'} rounded-lg transition-colors outline-none" title="${isActive ? 'Nonaktifkan Akun' : 'Aktifkan Akun'}">
                        ${isActive 
                            ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>' 
                            : '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                        }
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });

    updatePagination();
    updateBulkActions();
}

function updatePagination() {
    const total = filteredData.length;
    const totalPages = Math.ceil(total / itemsPerPage);
    
    if(document.getElementById('pagination-info')) {
        document.getElementById('pagination-info').innerText = `${window.LANG.showing_data} ${total === 0 ? 0 : ((currentPage - 1) * itemsPerPage) + 1} - ${Math.min(currentPage * itemsPerPage, total)} ${window.LANG.from_data} ${total} ${window.LANG.data}`;
    }

    const container = document.getElementById('pagination-buttons');
    if(!container) return;
    container.innerHTML = '';

    if (totalPages > 1) {
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.innerText = i;
            if (i === currentPage) {
                btn.className = "px-3 py-1 bg-emerald-500 text-white text-sm font-medium rounded-lg shadow-sm";
            } else {
                btn.className = "px-3 py-1 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-600 text-sm font-medium rounded-lg transition-colors";
                btn.onclick = () => { currentPage = i; renderTable(); };
            }
            container.appendChild(btn);
        }
    }
}

function showAddModal() {
    const modal = document.getElementById('addModal');
    if(modal) modal.classList.remove('hidden');
}

function closeAddModal() {
    const modal = document.getElementById('addModal');
    if(modal) modal.classList.add('hidden');
    window.resetTeacherForm();
}

window.resetTeacherForm = function() {
    const form = document.getElementById('addTeacherForm');
    if(form) form.reset();

    const maritalSelect = document.getElementById('status_marital');
    if (maritalSelect) {
        while (maritalSelect.options.length > 3) maritalSelect.remove(3);
    }
    
    if(document.getElementById('item_id')) document.getElementById('item_id').value = '';
    if(document.getElementById('modalTitle')) document.getElementById('modalTitle').innerText = window.LANG.js_title_add;
    if(document.getElementById('btnSubmitText')) document.getElementById('btnSubmitText').innerText = window.LANG.js_btn_save_add;
    if(document.getElementById('photoPreview')) document.getElementById('photoPreview').innerHTML = window.LANG.no_image || 'Tidak ada foto';
    if(document.getElementById('jabatan_id')) document.getElementById('jabatan_id').value = '';
}

window.previewPhoto = function(event) {
    const file = event.target.files[0];
    if (file && document.getElementById('photoPreview')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
        }
        reader.readAsDataURL(file);
    }
}

window.editData = function(id) {
    const item = allData.find(d => d.id == id);
    if (!item) return;
    
    window.resetTeacherForm();
    
    if(document.getElementById('modalTitle')) document.getElementById('modalTitle').innerText = window.LANG.js_title_edit;
    
    if(document.getElementById('item_id')) document.getElementById('item_id').value = item.id;
    if(document.getElementById('fullname')) document.getElementById('fullname').value = item.nama_lengkap || '';    
    if(document.getElementById('nuptk')) document.getElementById('nuptk').value = item.nuptk || '';
    if(document.getElementById('nik')) document.getElementById('nik').value = item.nik || '';
    if(document.getElementById('email')) document.getElementById('email').value = item.email || '';
    if(document.getElementById('phone')) document.getElementById('phone').value = item.no_hp || '';
    if(document.getElementById('gender')) {
        document.getElementById('gender').value = item.jenis_kelamin || '';
        handleGenderChange(); // Panggil sihirnya agar opsi Duda/Janda muncul
    }
    
    if(document.getElementById('tempat_lahir')) document.getElementById('tempat_lahir').value = item.tempat_lahir || '';
    if(document.getElementById('tanggal_lahir')) document.getElementById('tanggal_lahir').value = item.tanggal_lahir || '';
    if(document.getElementById('suku')) document.getElementById('suku').value = item.suku || '';
    if(document.getElementById('golongan_darah')) document.getElementById('golongan_darah').value = item.golongan_darah || '';
    if(document.getElementById('status_marital')) {
        let statusM = item.status_marital || '';
        // Konversi otomatis jika di database masih tersimpan kata "Cerai" versi lama
        if (statusM === 'Cerai') {
            statusM = item.jenis_kelamin === 'L' ? 'Duda' : 'Janda';
        }
        document.getElementById('status_marital').value = statusM;
    }
    if(document.getElementById('nama_pasangan')) document.getElementById('nama_pasangan').value = item.nama_pasangan || '';
    if(document.getElementById('jumlah_anak')) document.getElementById('jumlah_anak').value = item.jumlah_anak || '';
    if(document.getElementById('no_darurat')) document.getElementById('no_darurat').value = item.no_darurat || '';
    if(document.getElementById('alamat_ktp')) document.getElementById('alamat_ktp').value = item.alamat_ktp || '';
    if(document.getElementById('alamat_domisili')) document.getElementById('alamat_domisili').value = item.alamat_domisili || '';
    if(document.getElementById('pendidikan_terakhir')) document.getElementById('pendidikan_terakhir').value = item.pendidikan_terakhir || '';
    if(document.getElementById('jurusan_prodi')) document.getElementById('jurusan_prodi').value = item.jurusan_prodi || '';
    if(document.getElementById('tmt_ad_durrah')) document.getElementById('tmt_ad_durrah').value = item.tmt_ad_durrah || '';
    
    if (document.getElementById('jabatan_id')) document.getElementById('jabatan_id').value = item.jabatan_id || '';
    if (document.getElementById('employment_status')) document.getElementById('employment_status').value = item.status_kepegawaian || '';
    if (document.getElementById('subject')) document.getElementById('subject').value = item.mapel_id || item.mapel_utama || '-';

    if (item.foto_profil && document.getElementById('photoPreview')) {
        let safeBaseUrl = BASE_URL;
        if(!safeBaseUrl.endsWith('/')) safeBaseUrl += '/';
        const cacheBuster = '?v=' + new Date().getTime();
        document.getElementById('photoPreview').innerHTML = `<img src="${safeBaseUrl}assets/uploads/avatars/${item.foto_profil}${cacheBuster}" class="w-full h-full object-cover" onerror="this.onerror=null; this.outerHTML='${window.LANG.no_image || 'Tidak ada foto'}';">`;
    }
    
    showAddModal();
}

window.submitForm = async function() {
    const form = document.getElementById('addTeacherForm');
    if(!form) return;
    
    // --- UI VALIDATION CUSTOM (EFEK TEKS MERAH) ---
    // 1. Bersihkan efek merah & teks error sebelumnya (jika ada)
    const errorFields = form.querySelectorAll('.border-red-500');
    errorFields.forEach(el => {
        el.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
        el.classList.add('border-gray-200', 'dark:border-slate-600');
    });
    form.querySelectorAll('.error-text-validation').forEach(el => el.remove());

    // 2. Cek apakah ada form yang kosong/tidak valid
    if (!form.checkValidity()) {
        const invalidElements = form.querySelectorAll(':invalid');

        invalidElements.forEach((el, index) => {
            // Warnai GARIS TEPI kotak input jadi merah (Background dibiarkan normal agar Dropdown tidak rusak)
            el.classList.remove('border-gray-200', 'dark:border-slate-600');
            el.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');

            // Buat elemen teks pesan error
            let pesanError = '⚠️ Wajib diisi';
            if (el.type === 'email' && el.value !== '') pesanError = '⚠️ Format email tidak valid';

            const errorText = document.createElement('p');
            errorText.className = 'error-text-validation text-red-500 text-[11px] mt-1.5 font-medium animate-pulse';
            errorText.innerText = pesanError;
            
            // Masukkan teks di bawah kotak input/select
            el.parentNode.appendChild(errorText);

            // Fokuskan layar ke error pertama
            if (index === 0) {
                el.focus();
            }

            // Fungsi untuk menghilangkan error saat user mulai mengetik/memilih
            const removeError = function() {
                if (el.checkValidity()) {
                    el.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                    el.classList.add('border-gray-200', 'dark:border-slate-600');
                    const errTxt = el.parentNode.querySelector('.error-text-validation');
                    if (errTxt) errTxt.remove();
                    
                    el.removeEventListener('input', removeError);
                    el.removeEventListener('change', removeError);
                }
            };

            // Pasang pendeteksi ketikan (input) dan pilihan dropdown (change)
            el.addEventListener('input', removeError);
            el.addEventListener('change', removeError);
        });

        // Hentikan proses simpan (TIDAK ADA POP-UP SWEETALERT LAGI)
        return; 
    }
    // -----------------------------------------

    const formData = new FormData(form);
    const idItem = document.getElementById('item_id')?.value;
    
    let safeBaseUrl = BASE_URL;
    if(!safeBaseUrl.endsWith('/')) safeBaseUrl += '/';
    const url = idItem ? `${safeBaseUrl}admin/guru-tendik/update/${idItem}` : form.action;
    
    const btn = document.getElementById('btnSubmitForm');
    let originalHtml = 'Simpan';
    
    if(btn) {
        originalHtml = btn.innerHTML;
        btn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span>Menyimpan...</span>`;
        btn.disabled = true;
    }

    try {
        const res = await fetch(url, { method: 'POST', body: formData, headers: {'X-Requested-With': 'XMLHttpRequest'} });
        const textResp = await res.text();
        
        let json;
        try {
            json = JSON.parse(textResp);
        } catch (parseError) {
            console.error("SERVER RETURNED:", textResp);
            throw new Error("Respon server bukan format yang benar. Buka Inspect Element -> Console untuk detail.");
        }
        
        if (json.status === 'success') {
            Swal.fire({ icon: 'success', title: window.LANG.js_success, text: json.message, showConfirmButton: false, timer: 1500, customClass: { popup: 'rounded-3xl' } });
            closeAddModal();
            loadData();
        } else {
            let errorMsg = json.message;
            if (json.errors && Object.keys(json.errors).length > 0) {
                errorMsg = Object.values(json.errors)[0];
            }
            Swal.fire({ icon: 'error', title: window.LANG.js_fail, text: errorMsg, customClass: { popup: 'rounded-3xl' } });
        }
    } catch (err) {
        Swal.fire({ icon: 'error', title: 'Error System', text: err.message || window.LANG.js_err_server, customClass: { popup: 'rounded-3xl' } });
    } finally {
        if(btn) {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        }
    }
}

window.deleteData = function(id) {
    Swal.fire({
        title: window.LANG.js_confirm_del,
        text: window.LANG.js_confirm_del_desc,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: window.LANG.js_btn_yes_del,
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-3xl' }
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                let safeDeleteUrl = DELETE_URL;
                if(!safeDeleteUrl.endsWith('/')) safeDeleteUrl += '/';
                const res = await fetch(`${safeDeleteUrl}${id}`, { method: 'DELETE', headers: {'X-Requested-With': 'XMLHttpRequest'} });
                const json = await res.json();
                
                if (json.status === 'success') {
                    Swal.fire({ icon: 'success', title: window.LANG.js_del_success, text: json.message, showConfirmButton: false, timer: 1500, customClass: { popup: 'rounded-3xl' } });
                    loadData();
                } else {
                    Swal.fire({ icon: 'error', title: window.LANG.js_fail, text: json.message, customClass: { popup: 'rounded-3xl' } });
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Error', text: window.LANG.js_err_server, customClass: { popup: 'rounded-3xl' } });
            }
        }
    });
}

window.showDrawer = function(id) {
    const item = allData.find(d => d.id == id);
    if(!item) return;

    const inisial = (item.nama_lengkap || 'U').substring(0, 2).toUpperCase();
    let safeBaseUrl = BASE_URL;
    if(!safeBaseUrl.endsWith('/')) safeBaseUrl += '/';
    const cacheBuster = '?v=' + new Date().getTime();
    const avatarHtml = item.foto_profil ? `<img src="${safeBaseUrl}assets/uploads/avatars/${item.foto_profil}${cacheBuster}" class="w-full h-full object-cover" onerror="this.onerror=null; this.outerHTML='${inisial}';">` : inisial;
    
    if(document.getElementById('drawerAvatar')) document.getElementById('drawerAvatar').innerHTML = avatarHtml;    
    const namaLengkap = item.nama_lengkap || '-';
    if(document.getElementById('drawerName')) document.getElementById('drawerName').innerText = namaLengkap;
    if(document.getElementById('drawerNip')) document.getElementById('drawerNip').innerText = `NUPTK: ${item.nuptk || '-'}`;
    if(document.getElementById('drawerRole')) document.getElementById('drawerRole').innerText = item.jabatan_asli || item.nama_jabatan_master || '-';
    
    if(document.getElementById('drawerNik')) document.getElementById('drawerNik').innerText = item.nik || '-';
    
    // Format Date
    let tglLahir = item.tanggal_lahir ? new Date(item.tanggal_lahir).toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'}) : '';
    let tempatLahir = item.tempat_lahir ? item.tempat_lahir + ', ' : '';
    if(document.getElementById('drawerBirthPlace')) document.getElementById('drawerBirthPlace').innerText = tempatLahir;
    if(document.getElementById('drawerBirth')) document.getElementById('drawerBirth').innerText = tglLahir || '-';
    
    if(document.getElementById('drawerGender')) document.getElementById('drawerGender').innerText = item.jenis_kelamin === 'L' ? 'Laki-Laki' : (item.jenis_kelamin === 'P' ? 'Perempuan' : '-');
    if(document.getElementById('drawerBlood')) document.getElementById('drawerBlood').innerText = item.golongan_darah || '-';
    if(document.getElementById('drawerEmail')) document.getElementById('drawerEmail').innerText = item.email || '-';
    if(document.getElementById('drawerPhone')) document.getElementById('drawerPhone').innerText = item.no_hp || '-';
    if(document.getElementById('drawerEmergency')) document.getElementById('drawerEmergency').innerText = item.no_darurat || '-';
    
    if(document.getElementById('drawerAlamatKtp')) document.getElementById('drawerAlamatKtp').innerText = item.alamat_ktp || '-';
    if(document.getElementById('drawerAlamatDomisili')) document.getElementById('drawerAlamatDomisili').innerText = item.alamat_domisili || '-';
    
    if(document.getElementById('drawerPendidikan')) document.getElementById('drawerPendidikan').innerText = item.pendidikan_terakhir ? `${item.pendidikan_terakhir} ${item.jurusan_prodi ? '('+item.jurusan_prodi+')' : ''}` : '-';
    if(document.getElementById('drawerEmpStatus')) document.getElementById('drawerEmpStatus').innerText = item.status_kepegawaian || '-';
    
    let tmt = item.tmt_ad_durrah ? new Date(item.tmt_ad_durrah).toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'}) : '-';
    if(document.getElementById('drawerTmt')) document.getElementById('drawerTmt').innerText = tmt;
    
    if(document.getElementById('drawerSubject')) document.getElementById('drawerSubject').innerText = item.mapel_utama || '-';
    
    if(document.getElementById('drawerEditBtn')) document.getElementById('drawerEditBtn').onclick = () => { closeDrawer(); window.editData(id); };

    if(document.getElementById('drawer-overlay')) document.getElementById('drawer-overlay').classList.remove('hidden');
    if(document.getElementById('detailDrawer')) document.getElementById('detailDrawer').classList.remove('hidden');
    setTimeout(() => {
        if(document.getElementById('drawer-overlay')) document.getElementById('drawer-overlay').classList.add('opacity-100');
        if(document.getElementById('detailDrawer')) document.getElementById('detailDrawer').classList.remove('translate-x-full');
    }, 10);
}

window.closeDrawer = function() {
    if(document.getElementById('drawer-overlay')) document.getElementById('drawer-overlay').classList.remove('opacity-100');
    if(document.getElementById('detailDrawer')) document.getElementById('detailDrawer').classList.add('translate-x-full');
    setTimeout(() => {
        if(document.getElementById('drawer-overlay')) document.getElementById('drawer-overlay').classList.add('hidden');
        if(document.getElementById('detailDrawer')) document.getElementById('detailDrawer').classList.add('hidden');
    }, 300);
}

function toggleSelectAll() {
    const isChecked = document.getElementById('selectAll')?.checked;
    const checkboxes = document.querySelectorAll('.row-checkbox');
    checkboxes.forEach(cb => cb.checked = isChecked);
    updateBulkActions();
}

window.updateBulkActions = function() {
    const checked = document.querySelectorAll('.row-checkbox:checked').length;
    const total = document.querySelectorAll('.row-checkbox').length;
    const selectAllCb = document.getElementById('selectAll');
    
    if(total > 0 && selectAllCb) selectAllCb.checked = (checked === total);
    
    const countText = document.getElementById('selectedCount');
    const bulkDiv = document.getElementById('bulkActions');
    
    if (checked > 0) {
        if(countText) { countText.innerText = `(${checked} terpilih)`; countText.classList.remove('hidden'); }
        if(bulkDiv) bulkDiv.classList.remove('hidden');
    } else {
        if(countText) countText.classList.add('hidden');
        if(bulkDiv) bulkDiv.classList.add('hidden');
    }
}

function getSelectedIds() {
    return Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);
}

window.bulkExport = function() {
    const ids = getSelectedIds();
    if(ids.length === 0) return;
    let safeBaseUrl = BASE_URL;
    if(!safeBaseUrl.endsWith('/')) safeBaseUrl += '/';
    window.location.href = `${safeBaseUrl}admin/guru-tendik/export?ids=${ids.join(',')}`;
}

window.bulkDelete = function() {
    const ids = getSelectedIds();
    if(ids.length === 0) return;

    Swal.fire({
        title: window.LANG.js_bulk_del_conf,
        text: window.LANG.js_bulk_del_desc,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: window.LANG.js_btn_yes_del,
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-3xl' }
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                let safeBaseUrl = BASE_URL;
                if(!safeBaseUrl.endsWith('/')) safeBaseUrl += '/';
                const res = await fetch(`${safeBaseUrl}admin/guru-tendik/bulk-delete`, { 
                    method: 'POST', 
                    body: JSON.stringify({ ids: ids }),
                    headers: {'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest'} 
                });
                const json = await res.json();
                if (json.status === 'success') {
                    Swal.fire({ icon: 'success', title: window.LANG.js_del_success, text: json.message, showConfirmButton: false, timer: 1500, customClass: { popup: 'rounded-3xl' } });
                    if(document.getElementById('selectAll')) document.getElementById('selectAll').checked = false;
                    loadData();
                } else {
                    Swal.fire({ icon: 'error', title: window.LANG.js_fail, text: json.message, customClass: { popup: 'rounded-3xl' } });
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Error', text: window.LANG.js_err_server, customClass: { popup: 'rounded-3xl' } });
            }
        }
    });
}

window.showImportModal = function() { const modal = document.getElementById('importModal'); if(modal) modal.classList.remove('hidden'); }
window.closeImportModal = function() { 
    const modal = document.getElementById('importModal'); 
    if(modal) modal.classList.add('hidden'); 
    const form = document.getElementById('importForm');
    if(form) form.reset(); 
}

window.handleImport = async function(e) {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn ? btn.innerHTML : 'Upload';
    if(btn) { btn.innerHTML = window.LANG.js_uploading; btn.disabled = true; }

    try {
        const res = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: {'X-Requested-With': 'XMLHttpRequest'} });
        const json = await res.json();
        if (json.status === 'success') {
            Swal.fire({ icon: 'success', title: window.LANG.js_success, text: json.message, showConfirmButton: false, timer: 1500, customClass: { popup: 'rounded-3xl' } });
            closeImportModal();
            loadData();
        } else {
            Swal.fire({ icon: 'error', title: window.LANG.js_fail, text: json.message, customClass: { popup: 'rounded-3xl' } });
        }
    } catch (err) {
        Swal.fire({ icon: 'error', title: 'Error', text: window.LANG.js_err_upload, customClass: { popup: 'rounded-3xl' } });
    } finally {
        if(btn) { btn.innerHTML = originalText; btn.disabled = false; }
    }
}

window.toggleStatus = function(id) {
    Swal.fire({
        title: 'Ubah Status Akun?',
        text: "Status keaktifan pegawai ini akan diubah.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, Ubah Status',
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-3xl' }
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                let safeBaseUrl = BASE_URL;
                if(!safeBaseUrl.endsWith('/')) safeBaseUrl += '/';
                
                const res = await fetch(`${safeBaseUrl}admin/guru-tendik/toggle-status/${id}`, { 
                    method: 'POST', 
                    headers: {'X-Requested-With': 'XMLHttpRequest'} 
                });
                const json = await res.json();
                
                if (json.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: json.message, showConfirmButton: false, timer: 1500, customClass: { popup: 'rounded-3xl' } });
                    loadData(); // Otomatis refresh agar badge status berubah
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: json.message, customClass: { popup: 'rounded-3xl' } });
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan server.', customClass: { popup: 'rounded-3xl' } });
            }
        }
    });
}

function handleGenderChange() {
    const maritalSelect = document.getElementById('status_marital');
    const genderVal = document.getElementById('gender').value;
    const isEdit = document.getElementById('item_id').value !== '';
    
    if (!maritalSelect) return;
    
    const currentMarital = maritalSelect.value;

    // Bersihkan opsi ekstra (Duda/Janda/Cerai) jika ada
    while (maritalSelect.options.length > 3) {
        maritalSelect.remove(3);
    }

    // Tambahkan Duda / Janda HANYA jika dalam mode Edit
    if (isEdit && (genderVal === 'L' || genderVal === 'P')) {
        const opt = document.createElement('option');
        opt.value = genderVal === 'L' ? 'Duda' : 'Janda';
        opt.text  = genderVal === 'L' ? 'Duda' : 'Janda';
        maritalSelect.add(opt);

        // Pertahankan pilihan jika statusnya sedang Duda/Janda atau data lama 'Cerai'
        if (currentMarital === 'Duda' || currentMarital === 'Janda' || currentMarital === 'Cerai') {
            maritalSelect.value = opt.value;
        } else {
            maritalSelect.value = currentMarital;
        }
    }
}