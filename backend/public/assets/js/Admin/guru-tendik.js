let allTeachers = [];       
let filteredTeachers = [];  
let currentPage = 1;        
const itemsPerPage = 10;    
let selectedIds = new Set();

const textObj = window.LANG || {
    js_loading: 'Memuat data...', js_load_fail: 'Gagal memuat data.', js_no_data: 'Data tidak ditemukan',
    showing_data: 'Menampilkan', from_data: 'dari', data: 'data', js_status_active: 'Aktif',
    js_status_inactive: 'Nonaktif', js_role_employee: 'Pegawai', js_btn_view: 'Lihat', js_btn_edit: 'Edit',
    js_title_add: 'Tambah Pegawai Baru', js_title_edit: 'Edit Data Pegawai', js_btn_save_add: 'Simpan Data Pegawai',
    js_btn_save_edit: 'Simpan Perubahan', js_saving: 'Menyimpan...', js_uploading: 'Mengupload...',
    js_err_server: 'Terjadi kesalahan server.', js_err_upload: 'Gagal upload.', js_confirm_del: 'Hapus data?',
    js_confirm_del_desc: 'Data akan dihapus permanen!', js_btn_yes_del: 'Ya, Hapus', js_del_success: 'Terhapus',
    js_del_fail: 'Gagal: ', js_bulk_no_select: 'Pilih data dulu', js_bulk_del_conf: 'Hapus data terpilih?',
    js_bulk_del_desc: 'Data tidak bisa dikembalikan!', js_success: 'Berhasil', js_fail: 'Gagal'
};

document.addEventListener('DOMContentLoaded', function () {
    const modalsToMove = ['addModal', 'importModal', 'detailDrawer', 'drawer-overlay'];
    modalsToMove.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            document.body.appendChild(el);
            el.style.zIndex = "100000"; 
        }
    });

    loadTeachers();

    const searchInput = document.getElementById('searchInput');
    const filterRole = document.getElementById('filterRole');
    const filterMapel = document.getElementById('filterMapel');
    const filterStatus = document.getElementById('filterStatus');
    const selectAllCheckbox = document.getElementById('selectAll');

    if(searchInput) searchInput.addEventListener('input', applyFilters);
    if(filterRole) filterRole.addEventListener('change', applyFilters);
    if(filterMapel) filterMapel.addEventListener('change', applyFilters);
    if(filterStatus) filterStatus.addEventListener('change', applyFilters);

    if(selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            filteredTeachers.forEach(teacher => {
                const id = parseInt(teacher.id);
                if (isChecked) selectedIds.add(id); else selectedIds.delete(id);
            });
            updateBulkUI();
            renderTable();
        });
    }
});

function loadTeachers() {
    const tableBody = document.getElementById('teacherTableBody');
    const apiUrlEl = document.getElementById('api-url');
    if(!apiUrlEl) return;
    
    const url = apiUrlEl.getAttribute('data-url');
    tableBody.innerHTML = `<tr><td colspan="9" class="px-6 py-12 text-center text-gray-500">${textObj.js_loading}</td></tr>`;

    fetch(url, { headers: { "X-Requested-With": "XMLHttpRequest" } })
    .then(r => r.json())
    .then(data => {
        allTeachers = data;         
        filteredTeachers = [...data];
        applyFilters(); 
    })
    .catch(error => {
        tableBody.innerHTML = `<tr><td colspan="9" class="px-6 py-8 text-center text-red-500">${textObj.js_load_fail}</td></tr>`;
    });
}

function applyFilters() {
    const keyword = document.getElementById('searchInput').value.toLowerCase();
    const role = document.getElementById('filterRole').value;
    const mapel = document.getElementById('filterMapel') ? document.getElementById('filterMapel').value : '';
    const status = document.getElementById('filterStatus').value;

    filteredTeachers = allTeachers.filter(item => {
        const matchName = (item.nama_lengkap || '').toLowerCase().includes(keyword) || (item.nuptk || '').includes(keyword);
        const matchRole = role === '' || item.jabatan === role;
        const matchMapel = mapel === '' || (item.mapel_utama && item.mapel_utama === mapel);
        let statusText = (item.is_active == 1) ? 'Aktif' : 'Nonaktif';
        const matchStatus = status === '' || statusText === status;
        return matchName && matchRole && matchMapel && matchStatus;
    });

    currentPage = 1; 
    renderTable();   
}

function renderTable() {
    const tableBody = document.getElementById('teacherTableBody');
    const baseUrl = document.getElementById('base-url') ? document.getElementById('base-url').getAttribute('data-url') : '/';

    if (filteredTeachers.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="9" class="px-6 py-12 text-center text-gray-500 dark:text-slate-400">${textObj.js_no_data}</td></tr>`;
        if(document.getElementById('pagination-info')) renderPaginationInfo(0, 0, 0);
        if(document.getElementById('pagination-buttons')) document.getElementById('pagination-buttons').innerHTML = '';
        return;
    }

    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const paginatedData = filteredTeachers.slice(startIndex, endIndex);

    let html = '';
    paginatedData.forEach(item => {
        const name = item.nama_lengkap || 'GT';
        const initials = name.substring(0, 2).toUpperCase();
        
        let avatarHtml = '';
        const fallbackAvatar = `https://ui-avatars.com/api/?name=${initials}&background=1F7A4D&color=fff&size=100&bold=true&rounded=true`;

        if (item.foto_profil && item.foto_profil !== 'null' && String(item.foto_profil).trim() !== '') {
            const cleanBaseUrl = baseUrl.replace(/\/$/, '');
            const fotoUrl = `${cleanBaseUrl}/assets/uploads/avatars/${item.foto_profil}`;
            avatarHtml = `<img src="${fotoUrl}" class="w-10 h-10 rounded-lg object-cover shadow-sm border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800" onerror="this.onerror=null; this.src='${fallbackAvatar}';">`;
        } else {
            avatarHtml = `<img src="${fallbackAvatar}" class="w-10 h-10 rounded-lg object-cover shadow-sm">`;
        }

        const isActive = item.is_active == 1;
        const statusBadge = isActive 
            ? `<span class="px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/50">${textObj.js_status_active}</span>`
            : `<span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-100 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800/50">${textObj.js_status_inactive}</span>`;
        
        let roleBadgeClass = 'bg-slate-50 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700'; 
        const jabatan = (item.jabatan || '').toLowerCase();

        if (jabatan.includes('kepala sekolah')) roleBadgeClass = 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800/50 font-bold'; 
        else if (jabatan.includes('waka') || jabatan.includes('wakil')) roleBadgeClass = 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-900/30 dark:text-indigo-400 dark:border-indigo-800/50 font-bold'; 
        else if (jabatan.includes('tahfidz') || jabatan.includes('tahfiz')) roleBadgeClass = 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800/50 font-bold'; 
        else if (jabatan.includes('wali kelas') || jabatan.includes('walikelas')) roleBadgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/50 font-bold'; 
        else if (jabatan.includes('bk')) roleBadgeClass = 'bg-teal-50 text-teal-700 border-teal-200 dark:bg-teal-900/30 dark:text-teal-400 dark:border-teal-800/50 font-bold'; 
        else if (jabatan.includes('mapel')) roleBadgeClass = 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800/50 font-bold'; 
        else if (jabatan.includes('tu') || jabatan.includes('tata usaha') || jabatan.includes('operator')) roleBadgeClass = 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-900/30 dark:text-purple-400 dark:border-purple-800/50 font-bold'; 
        else if (jabatan.includes('lab tik') || jabatan.includes('laboratorium')) roleBadgeClass = 'bg-cyan-50 text-cyan-700 border-cyan-200 dark:bg-cyan-900/30 dark:text-cyan-400 dark:border-cyan-800/50 font-bold'; 

        const itemId = parseInt(item.id);
        const isSelected = selectedIds.has(itemId);
        const rowBackground = isSelected ? 'bg-emerald-50/40 dark:bg-emerald-900/20' : '';
        const checkedAttr = isSelected ? 'checked' : '';
        const listKelas = item.jabatan === 'Tendik' ? '-' : (item.kelas_mengajar || '-');

        html += `
            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors border-b border-gray-50 dark:border-slate-700/50 last:border-0 group ${rowBackground}">
                <td class="pl-6 py-4 w-12">
                    <input type="checkbox" class="row-checkbox w-5 h-5 text-emerald-600 rounded border-gray-300 dark:border-slate-500 dark:bg-slate-800 cursor-pointer focus:ring-emerald-500" 
                        value="${itemId}" onchange="toggleRowSelection(this, ${itemId})" ${checkedAttr}> 
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        ${avatarHtml}
                        <div>
                            <div class="font-bold text-gray-800 dark:text-white text-sm group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">${item.nama_lengkap}</div>
                            <div class="mt-1"><span class="px-2 py-0.5 rounded text-[10px] border ${roleBadgeClass}">${item.jabatan || textObj.js_role_employee}</span></div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4"><div class="font-bold text-gray-700 dark:text-slate-300 text-xs font-mono">${item.nuptk || '-'}</div></td>
                <td class="px-6 py-4"><div class="font-bold text-xs text-gray-400 dark:text-slate-500 mt-0.5 font-mono">${item.nik || '-'}</div></td>
                <td class="px-6 py-4"><div class="font-bold text-xs text-gray-700 dark:text-slate-300 mt-0.5 font-mono">${item.email || '-'}</div></td>
                <td class="px-6 py-4"><span class="text-sm text-gray-600 dark:text-slate-300 font-medium">${item.mapel_utama || '-'}</span></td>
                <td class="px-6 py-4 max-w-[150px] whitespace-normal leading-relaxed"><span class="text-xs text-gray-600 dark:text-slate-400 font-medium">${listKelas}</span></td>
                <td class="px-6 py-4">${statusBadge}</td>
                <td class="px-6 py-4 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <button onclick="viewTeacher(${item.id})" class="text-emerald-500 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300 transition-colors action-btn p-1.5 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-lg" title="${textObj.js_btn_view}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </button>
                        <button onclick="editTeacher(${item.id})" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors action-btn p-1.5 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg" title="${textObj.js_btn_edit}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });

    tableBody.innerHTML = html;
    renderPaginationButtons();
    renderPaginationInfo(startIndex + 1, Math.min(endIndex, filteredTeachers.length), filteredTeachers.length);
    updateBulkUI();
}

window.showAddModal = function() {
    const modal = document.getElementById('addModal');
    resetTeacherForm(); 
    if (modal) {
        modal.classList.remove('hidden'); 
        document.body.style.overflow = 'hidden'; 
    }
}

window.closeAddModal = function() {
    const modal = document.getElementById('addModal');
    if (modal) {
        modal.classList.add('hidden'); 
        document.body.style.overflow = '';
    }
}

window.resetTeacherForm = function() {
    const form = document.getElementById('addTeacherForm');
    const baseUrl = document.getElementById('base-url') ? document.getElementById('base-url').getAttribute('data-url') : '/';
    const btn = form.querySelector('button[type="submit"]');
    
    form.reset();
    form.action = `${baseUrl}admin/guru-tendik/store`; 
    document.getElementById('photoPreview').innerHTML = textObj.no_image || 'No Image';
    
    const headerTitle = document.querySelector('#addModal h3');
    if(headerTitle) headerTitle.textContent = textObj.js_title_add;
    
    btn.innerHTML = `
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span id="btnSubmitText">${textObj.js_btn_save_add}</span>
    `;
}

window.showImportModal = function() {
    document.getElementById('importModal').classList.remove('hidden');
}

window.closeImportModal = function() {
    document.getElementById('importModal').classList.add('hidden');
    document.getElementById('importForm').reset();
}

window.handleSubmit = function(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;

    btn.innerHTML = `<span class="loading-spinner"></span> ${textObj.js_saving}`;
    btn.disabled = true;

    fetch(form.action, { method: 'POST', body: formData, headers: { "X-Requested-With": "XMLHttpRequest" } })
    .then(r => r.json())
    .then(data => {
        if(data.status === 'success') {
            if (typeof Swal !== 'undefined') Swal.fire(textObj.js_success, data.message, 'success');
            else alert(data.message);
            closeAddModal();
            loadTeachers();
        } else {
            let errorMsg = data.message;
            if (data.errors) errorMsg += '\n' + Object.values(data.errors).join('\n');
            if (typeof Swal !== 'undefined') Swal.fire(textObj.js_fail, errorMsg, 'error');
            else alert(errorMsg);
        }
    })
    .catch(err => { console.error(err); alert(textObj.js_err_server); })
    .finally(() => { btn.innerHTML = originalText; btn.disabled = false; });
}

window.handleImport = function(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;

    btn.innerHTML = textObj.js_uploading;
    btn.disabled = true;

    fetch(form.action, { method: 'POST', body: formData, headers: { "X-Requested-With": "XMLHttpRequest" } })
    .then(r => r.json())
    .then(data => {
        if(data.status === 'success') {
            if (typeof Swal !== 'undefined') Swal.fire(textObj.js_success, data.message, 'success');
            else alert(data.message);
            closeImportModal();
            loadTeachers();
        } else {
            if (typeof Swal !== 'undefined') Swal.fire(textObj.js_fail, data.message, 'error');
            else alert(data.message);
        }
    })
    .catch(err => alert(textObj.js_err_upload))
    .finally(() => { btn.innerHTML = originalText; btn.disabled = false; });
}

window.editTeacher = function(id) {
    const baseUrl = document.getElementById('base-url').getAttribute('data-url');
    
    fetch(`${baseUrl}admin/guru-tendik/show/${id}`, { headers: { "X-Requested-With": "XMLHttpRequest" } })
    .then(r => r.json())
    .then(data => {
        if(data) {
            const modal = document.getElementById('addModal');
            const form = document.getElementById('addTeacherForm');
            const btn = form.querySelector('button[type="submit"]');
            
            form.action = `${baseUrl}admin/guru-tendik/update/${id}`;
            const headerTitle = modal.querySelector('h3');
            if(headerTitle) headerTitle.textContent = textObj.js_title_edit;
            
            btn.innerHTML = `
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span id="btnSubmitText">${textObj.js_btn_save_edit}</span>
            `;

            if(form.querySelector('[name="fullname"]')) form.querySelector('[name="fullname"]').value = data.nama_lengkap || '';
            if(form.querySelector('[name="nuptk"]')) form.querySelector('[name="nuptk"]').value = data.nuptk || '';
            if(form.querySelector('[name="nik"]')) form.querySelector('[name="nik"]').value = data.nik || '';
            if(form.querySelector('[name="email"]')) form.querySelector('[name="email"]').value = data.email || '';
            if(form.querySelector('[name="phone"]')) form.querySelector('[name="phone"]').value = data.no_hp || '';
            if(form.querySelector('[name="gender"]')) form.querySelector('[name="gender"]').value = (data.jenis_kelamin || '').trim();

            const setSuperSelect = (selector, dbValue) => {
                const selectElement = form.querySelector(selector);
                if (!selectElement) return;
                
                if (!dbValue || dbValue.trim() === '' || dbValue.trim() === '-') {
                    selectElement.selectedIndex = 0;
                    return;
                }

                const valLower = dbValue.toString().toLowerCase().trim();
                let matched = false;

                for (let i = 0; i < selectElement.options.length; i++) {
                    if (selectElement.options[i].value.toLowerCase().trim() === valLower) {
                        selectElement.selectedIndex = i;
                        matched = true;
                        break;
                    }
                }

                if (!matched) {
                    for (let i = 0; i < selectElement.options.length; i++) {
                        const optValLower = selectElement.options[i].value.toLowerCase();
                        if (optValLower.includes(valLower) || valLower.includes(optValLower)) {
                            selectElement.selectedIndex = i;
                            matched = true;
                            break;
                        }
                    }
                }

                if (!matched) {
                    const newOption = new Option(dbValue, dbValue, true, true);
                    selectElement.add(newOption);
                }
            };

            setSuperSelect('[name="role"]', data.jabatan);
            setSuperSelect('[name="employment_status"]', data.status_kepegawaian);
            setSuperSelect('[name="subject"]', data.mapel_utama);

            if(data.foto && data.foto !== 'null') {
                const cleanBaseUrl = baseUrl.replace(/\/$/, '');
                document.getElementById('photoPreview').innerHTML = `<img src="${cleanBaseUrl}/assets/uploads/guru/${data.foto}" class="w-full h-full object-cover">`;
            } else {
                document.getElementById('photoPreview').innerHTML = textObj.no_image || 'No Image';
            }
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } else {
            alert(textObj.js_no_data);
        }
    })
    .catch(err => { console.error("Edit Error:", err); alert(textObj.js_load_fail); });
}

window.previewPhoto = function(event) {
    const file = event.target.files[0];
    const container = document.getElementById('photoPreview');
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => container.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
        reader.readAsDataURL(file);
    } else {
        container.innerHTML = textObj.no_image || 'No Image';
    }
};

window.deleteTeacher = function(id) {
    if (typeof Swal === 'undefined') {
        if(confirm(textObj.js_confirm_del)) executeDelete(id);
    } else {
        Swal.fire({
            title: textObj.js_confirm_del, text: textObj.js_confirm_del_desc, icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: textObj.js_btn_yes_del
        }).then(res => { if(res.isConfirmed) executeDelete(id); });
    }
}

function executeDelete(id) {
    const urlEl = document.getElementById('delete-url');
    const url = urlEl ? urlEl.getAttribute('data-url') : '/admin/guru-tendik/delete';
    
    fetch(`${url}/${id}`, { method: 'DELETE', headers: { "X-Requested-With": "XMLHttpRequest" } })
    .then(r => r.json())
    .then(data => {
        if(data.status === 'success') {
            if (typeof Swal !== 'undefined') Swal.fire(textObj.js_del_success, data.message, 'success');
            else alert(data.message);
            loadTeachers();
        } else {
            alert(textObj.js_del_fail + data.message);
        }
    });
}

window.viewTeacher = function(id) {
    const baseUrl = document.getElementById('base-url').getAttribute('data-url');
    const drawer = document.getElementById('detailDrawer');
    const overlay = document.getElementById('drawer-overlay');

    fetch(`${baseUrl}admin/guru-tendik/show/${id}`, { headers: { "X-Requested-With": "XMLHttpRequest" } })
    .then(r => r.json())
    .then(data => {
        if(data) {
            const name = data.nama_lengkap || 'GT';
            const initials = name.substring(0, 2).toUpperCase();
            const avatarEl = document.getElementById('drawerAvatar');
            const fallbackDrawer = `https://ui-avatars.com/api/?name=${initials}&background=1F7A4D&color=fff&size=160&bold=true&rounded=true`;
            
            const teacherDataFromTable = filteredTeachers.find(t => t.id == id);
            const userFoto = teacherDataFromTable ? teacherDataFromTable.foto_profil : null;

            if (userFoto && userFoto !== 'null' && String(userFoto).trim() !== '') {
                const cleanBaseUrl = baseUrl.replace(/\/$/, '');
                const fotoUrl = `${cleanBaseUrl}/assets/uploads/avatars/${userFoto}`;
                
                avatarEl.innerHTML = `<img src="${fotoUrl}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='${fallbackDrawer}';">`;
                avatarEl.classList.remove('bg-emerald-600', 'text-white');
            } else {
                avatarEl.innerHTML = `<img src="${fallbackDrawer}" class="w-full h-full object-cover">`;
                avatarEl.classList.remove('bg-emerald-600', 'text-white');
            }

            if(document.getElementById('drawerName')) document.getElementById('drawerName').textContent = data.nama_lengkap;
            if(document.getElementById('drawerNip')) document.getElementById('drawerNip').textContent = `NUPTK: ${data.nuptk || '-'}`;
            if(document.getElementById('drawerRole')) document.getElementById('drawerRole').textContent = data.jabatan || '-';
            if(document.getElementById('drawerNik')) document.getElementById('drawerNik').textContent = data.nik || '-';
            if(document.getElementById('drawerEmail')) document.getElementById('drawerEmail').textContent = data.email || '-';
            if(document.getElementById('drawerPhone')) document.getElementById('drawerPhone').textContent = data.no_hp || '-';
            if(document.getElementById('drawerEmpStatus')) document.getElementById('drawerEmpStatus').textContent = data.status_kepegawaian || '-';
            if(document.getElementById('drawerSubject')) document.getElementById('drawerSubject').textContent = data.mapel_utama || '-';
            
            let birthInfo = data.tempat_lahir || '-';
            if (data.tanggal_lahir) {
                const date = new Date(data.tanggal_lahir);
                birthInfo += `, ${date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}`;
            }
            if(document.getElementById('drawerBirth')) document.getElementById('drawerBirth').textContent = birthInfo;
            
            const drawerEditBtn = document.getElementById('drawerEditBtn');
            if(drawerEditBtn) drawerEditBtn.setAttribute('onclick', `closeDrawer(); editTeacher(${data.id})`);

            overlay.classList.remove('hidden');
            drawer.classList.remove('hidden'); 
            
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                drawer.classList.remove('translate-x-full');
            }, 10);
        }
    });
}

window.closeDrawer = function() {
    const drawer = document.getElementById('detailDrawer');
    const overlay = document.getElementById('drawer-overlay');
    
    drawer.classList.add('translate-x-full');
    overlay.classList.add('opacity-0');
    
    setTimeout(() => { 
        overlay.classList.add('hidden'); 
        drawer.classList.add('hidden'); 
    }, 300);
}

window.toggleRowSelection = function(checkbox, id) {
    const itemId = parseInt(id);
    if (checkbox.checked) selectedIds.add(itemId); 
    else selectedIds.delete(itemId);
    
    updateBulkUI();
    const tr = checkbox.closest('tr');
    if(checkbox.checked) tr.classList.add('bg-emerald-50/40'); 
    else tr.classList.remove('bg-emerald-50/40');
}

function updateBulkUI() {
    const count = selectedIds.size;
    const counterEl = document.getElementById('selectedCount');
    const actionsEl = document.getElementById('bulkActions');
    const selectAllEl = document.getElementById('selectAll');

    if(counterEl) {
        counterEl.textContent = `(${count} ${textObj.selected_count})`;
        counterEl.classList.toggle('hidden', count === 0);
    }
    if(actionsEl) {
        actionsEl.classList.toggle('hidden', count === 0);
        actionsEl.classList.toggle('flex', count > 0);
    }
    if(selectAllEl && filteredTeachers.length > 0) {
        const allVisibleSelected = filteredTeachers.every(t => selectedIds.has(parseInt(t.id)));
        selectAllEl.checked = count > 0 && allVisibleSelected;
        selectAllEl.indeterminate = count > 0 && !allVisibleSelected;
    }
}

function renderPaginationInfo(start, end, total) {
    const infoEl = document.getElementById('pagination-info');
    if (infoEl) {
        infoEl.innerHTML = `${textObj.showing_data} <span class="font-bold text-gray-900 dark:text-white">${start}-${end}</span> ${textObj.from_data} <span class="font-bold text-gray-900 dark:text-white">${total}</span> ${textObj.data}`;
    }
}

function renderPaginationButtons() {
    const dataList = typeof filteredTeachers !== 'undefined' ? filteredTeachers : (typeof filteredStudents !== 'undefined' ? filteredStudents : []);
    const totalPages = Math.ceil(dataList.length / itemsPerPage);
    
    const container = document.getElementById('pagination-buttons');
    if (!container) return;
    
    let html = `<button onclick="changePage(${currentPage - 1})" class="px-3 py-1.5 border border-gray-200 dark:border-slate-600 rounded-lg text-sm text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors" ${currentPage === 1 ? 'disabled' : ''}>&lt;</button>`;
    
    for (let i = 1; i <= totalPages; i++) {
        let activeClass = '';
        if (i === currentPage) {
            activeClass = 'bg-[var(--warna-primary)] text-white border-emerald-600'; 
        } else {
            activeClass = 'bg-white dark:bg-slate-800 text-gray-600 dark:text-slate-300 border border-gray-200 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-700';
        }
        
        html += `<button onclick="changePage(${i})" class="px-3.5 py-1.5 rounded-lg text-sm transition-colors ${activeClass}">${i}</button>`;
    }
    
    html += `<button onclick="changePage(${currentPage + 1})" class="px-3 py-1.5 border border-gray-200 dark:border-slate-600 rounded-lg text-sm text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors" ${currentPage >= totalPages ? 'disabled' : ''}>&gt;</button>`;
    
    container.innerHTML = html;
}

function changePage(page) {
    const totalPages = Math.ceil(filteredTeachers.length / itemsPerPage);
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    renderTable();
}

window.bulkExport = function() {
    if (selectedIds.size === 0) return alert(textObj.js_bulk_no_select);
    const idsString = Array.from(selectedIds).join(',');
    const baseUrl = document.getElementById('base-url').getAttribute('data-url');
    window.location.href = `${baseUrl}admin/guru-tendik/export?ids=${idsString}`;
}

window.bulkDelete = function() {
    if (selectedIds.size === 0) return alert(textObj.js_bulk_no_select);
    if (typeof Swal === 'undefined') {
        if(confirm(textObj.js_bulk_del_conf)) executeBulkDelete();
    } else {
        Swal.fire({
            title: textObj.js_bulk_del_conf, text: textObj.js_bulk_del_desc, icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: textObj.js_btn_yes_del
        }).then(res => { if(res.isConfirmed) executeBulkDelete(); });
    }
}

function executeBulkDelete() {
    const baseUrl = document.getElementById('base-url').getAttribute('data-url');
    const idsArray = Array.from(selectedIds);
    fetch(`${baseUrl}admin/guru-tendik/bulk-delete`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ ids: idsArray })
    }).then(r => r.json()).then(data => {
        if(data.status === 'success') { 
            if (typeof Swal !== 'undefined') Swal.fire(textObj.js_success, data.message, 'success');
            else alert(data.message);
            selectedIds.clear(); 
            updateBulkUI(); 
            loadTeachers(); 
        } else { 
            alert(data.message); 
        }
    });
}