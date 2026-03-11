// --- 1. SETUP DATA ---
const defaultConfig = {
    school_name: 'SMPIT Ad Durrah',
    app_title: 'Rapor Digital',
    academic_year: '2024/2025',
    page_title: 'Mapping Guru Mata Pelajaran',
    page_subtitle: 'Tetapkan guru pengampu untuk setiap mata pelajaran dan kelas'
};

let config = { ...defaultConfig };
const mappingData = (typeof dbMappingData !== 'undefined') ? dbMappingData : [];
let filteredData = [...mappingData];
let selectedRombels = [];
let deleteTargetId = null;

const textObj = window.LANG || {
    js_loading: 'Memproses...', js_saving: 'Menyimpan...', js_analyzing: 'Menganalisis...',
    js_no_data: 'Tidak ada data mapping yang cocok.', js_status_active: 'Aktif',
    js_status_inactive: 'Nonaktif', js_teacher_not_found: 'Guru Tidak Ditemukan',
    js_err_min_bulk: 'Minimal 1 Mapel dan 1 Rombel wajib dipilih!', js_err_server: 'Terjadi kesalahan server.',
    js_err_conn: 'Koneksi terputus.', js_err_fatal: 'Terjadi kesalahan fatal server.', js_fail_prefix: 'Gagal: ',
    btn_save_changes: 'Simpan Perubahan', btn_save_mapping: 'Simpan Mapping',
    lbl_click_room: 'Klik untuk memilih rombel', lbl_click_subj: 'Klik untuk memilih mapel'
};

// --- 2. FUNGSI TOAST ---
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `transform transition-all duration-300 translate-x-full opacity-0 flex items-center w-full max-w-xs p-4 space-x-3 text-gray-500 bg-white dark:bg-slate-800 rounded-xl shadow-xl border-l-4 pointer-events-auto ${type === 'success' ? 'border-emerald-500' : 'border-red-500'}`;
    
    const icon = type === 'success' 
        ? `<div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-emerald-500 bg-emerald-100 rounded-lg"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg></div>`
        : `<div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></div>`;

    toast.innerHTML = `
        ${icon}
        <div class="ml-3 text-sm font-medium text-gray-800 dark:text-slate-200">${message}</div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white dark:bg-slate-800 text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 dark:hover:bg-slate-700 inline-flex h-8 w-8" onclick="this.parentElement.remove()">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>
    `;

    container.appendChild(toast);

    requestAnimationFrame(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
    });

    setTimeout(() => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

function checkPendingToast() {
    const msg = localStorage.getItem('toastMessage');
    const type = localStorage.getItem('toastType');
    if (msg) {
        showToast(msg, type || 'success');
        localStorage.removeItem('toastMessage');
        localStorage.removeItem('toastType');
    }
}

// --- 3. UI UTAMA ---
function updateUI() {
    const fields = ['sidebar-school-name', 'sidebar-app-title', 'header-school-name', 'header-academic-year'];
    fields.forEach(id => {
        if(document.getElementById(id)) document.getElementById(id).textContent = defaultConfig[id.replace(/-/g, '_')] || '';
    });
}

function populateTable() {
    const tbody = document.getElementById('mappingTableBody');
    if (!tbody) return;
    if (filteredData.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="px-6 py-16 text-center text-gray-500 dark:text-slate-400 font-medium">${textObj.js_no_data}</td></tr>`;
        return;
    }

    tbody.innerHTML = filteredData.map(item => `
        <tr class="table-row bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700/50 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors group">
          <td class="px-6 py-4">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-lg text-white flex items-center justify-center font-bold text-xs shadow-md border border-transparent" style="background-color: var(--warna-primary, #10b981);">
                ${item.teacher ? item.teacher.substring(0, 2).toUpperCase() : 'GU'}
              </div>
              <div>
                <p class="font-bold text-gray-800 dark:text-white group-hover:text-[var(--warna-primary,#10b981)] transition-colors">${item.teacher}</p>
                <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 font-mono">NIK: ${item.nik || '-'}</p>
              </div>
            </div>
          </td>
          <td class="px-6 py-4"><span class="font-bold text-gray-800 dark:text-slate-200">${item.mapel}</span></td>
          <td class="px-6 py-4"><span class="text-sm font-semibold text-gray-600 dark:text-slate-300">${item.level}</span></td>
          <td class="px-6 py-4"><div class="flex flex-wrap gap-1"><span class="px-2.5 py-1 text-xs font-bold bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800/50 rounded-lg shadow-sm">${item.level}-${item.rombel}</span></div></td>
          <td class="px-6 py-4 text-center"><span class="font-bold text-gray-800 dark:text-slate-200">${item.jam}</span></td>
          <td class="px-6 py-4"><span class="text-sm font-medium text-gray-600 dark:text-slate-300">${item.tahunAjaran}</span></td>
          <td class="px-6 py-4 text-center">
            ${item.status === 'active' 
              ? `<span class="px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 rounded-full shadow-sm">${textObj.js_status_active}</span>` 
              : `<span class="px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 border border-gray-200 dark:border-slate-600 rounded-full shadow-sm">${textObj.js_status_inactive}</span>`
            }
          </td>
          <td class="px-6 py-4">
            <div class="flex items-center justify-center gap-2 opacity-1 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity">
              
              <button onclick="showDetail(${item.id})" class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg tooltip transition-colors outline-none" title="Detail">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              </button>

              <button onclick="showEditModal(${item.id})" class="p-2 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-lg transition-colors outline-none" title="Edit">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              </button>

              <button onclick="confirmDeactivate(${item.id})" class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors outline-none" title="Hapus">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
              </button>

            </div>
          </td>
        </tr>
    `).join('');
}

// --- 4. FILTER LOGIC ---
function applyFilters() {
    const tahun = document.getElementById('filterTahun') ? document.getElementById('filterTahun').value : '';
    const level = document.getElementById('filterLevel') ? document.getElementById('filterLevel').value : '';
    const rombelId = document.getElementById('filterRombel') ? document.getElementById('filterRombel').value : ''; 
    const mapelId = document.getElementById('filterMapel') ? document.getElementById('filterMapel').value : '';   
    const guruId = document.getElementById('filterGuru') ? document.getElementById('filterGuru').value : '';     
    const search = document.getElementById('searchInput') ? document.getElementById('searchInput').value.toLowerCase() : '';
    const activeOnly = document.getElementById('toggleActiveOnly') ? document.getElementById('toggleActiveOnly').checked : false;

    filteredData = mappingData.filter(item => {
        const matchTahun = !tahun || item.tahunAjaran === tahun;
        const matchLevel = !level || item.level === level;
        const matchRombel = !rombelId || item.rombel_id == rombelId;
        const matchMapel = !mapelId || item.mapel_id == mapelId;
        const matchGuru = !guruId || item.teacher_id == guruId;
        
        const matchSearch = !search || 
            (item.teacher && item.teacher.toLowerCase().includes(search)) || 
            (item.mapel && item.mapel.toLowerCase().includes(search)) ||
            (item.rombel_full && item.rombel_full.toLowerCase().includes(search)) ||
            (item.nik && item.nik.toLowerCase().includes(search));

        const matchActive = !activeOnly || item.status === 'active';

        return matchTahun && matchLevel && matchRombel && matchMapel && matchGuru && matchSearch && matchActive;
    });

    populateTable();
}

if(document.getElementById('filterTahun')) document.getElementById('filterTahun').addEventListener('change', applyFilters);
if(document.getElementById('filterLevel')) document.getElementById('filterLevel').addEventListener('change', applyFilters);
if(document.getElementById('filterRombel')) document.getElementById('filterRombel').addEventListener('change', applyFilters);
if(document.getElementById('filterMapel')) document.getElementById('filterMapel').addEventListener('change', applyFilters);
if(document.getElementById('filterGuru')) document.getElementById('filterGuru').addEventListener('change', applyFilters);
if(document.getElementById('searchInput')) document.getElementById('searchInput').addEventListener('input', applyFilters);
if(document.getElementById('toggleActiveOnly')) document.getElementById('toggleActiveOnly').addEventListener('change', applyFilters);

// --- 5. LOGIKA DRAWER & MODAL TAMBAH ---
function showDetail(id) {
    const item = mappingData.find(d => d.id == id);
    if (!item) { showToast("Data tidak ditemukan!", 'error'); return; }

    // PERBAIKAN: Mapping ke HTML ID yang tepat di file View
    if(document.getElementById('drawerTeacherName')) document.getElementById('drawerTeacherName').textContent = item.teacher || textObj.js_teacher_not_found;
    if(document.getElementById('drawerTeacherNIP')) document.getElementById('drawerTeacherNIP').textContent = `NIK: ${item.nik || '-'}`;
    if(document.getElementById('drawerMapel')) document.getElementById('drawerMapel').textContent = item.mapel;
    if(document.getElementById('drawerTingkat')) document.getElementById('drawerTingkat').textContent = `${item.level}`;
    if(document.getElementById('drawerRombel')) document.getElementById('drawerRombel').textContent = `${item.level}-${item.rombel}`;
    if(document.getElementById('drawerJam')) document.getElementById('drawerJam').textContent = `${item.jam} JP`;
    if(document.getElementById('drawerTahunAjaran')) document.getElementById('drawerTahunAjaran').textContent = item.tahunAjaran;
    if(document.getElementById('editId')) document.getElementById('editId').value = item.id;

    const drawer = document.getElementById('detailDrawer');
    const overlay = document.getElementById('drawer-overlay');
    if(drawer) drawer.classList.remove('hidden');
    if(overlay) overlay.classList.remove('hidden');
    setTimeout(() => { if(drawer) drawer.classList.remove('translate-x-full'); }, 10);
}

function closeDrawer() {
    const drawer = document.getElementById('detailDrawer');
    const overlay = document.getElementById('drawer-overlay');
    if(drawer) {
        drawer.classList.add('translate-x-full');
        setTimeout(() => drawer.classList.add('hidden'), 300);
    }
    if(overlay) overlay.classList.add('hidden');
}

function showAddModal() {
    const modal = document.getElementById('addModal');
    if(modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeAddModal() {
    const modal = document.getElementById('addModal');
    if(modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
    if(document.getElementById('addMappingForm')) document.getElementById('addMappingForm').reset();
    if(document.getElementById('editId')) document.getElementById('editId').value = ''; 
    const btn = document.querySelector('#btnSubmitAdd');
    if(btn) btn.textContent = textObj.btn_save_mapping;
    
    document.querySelectorAll('.rombel-cb').forEach(cb => cb.checked = false);
    if(document.getElementById('modalTitle')) document.getElementById('modalTitle').textContent = "Tambah Mapping Mapel";
}

function showEditModal(id) {
    const item = mappingData.find(d => d.id == id);
    if(!item) return;

    if(document.getElementById('editId')) document.getElementById('editId').value = item.id;
    if(document.getElementById('add_guru')) document.getElementById('add_guru').value = item.teacher_id; 
    if(document.getElementById('add_mapel')) document.getElementById('add_mapel').value = item.mapel_id;  
    if(document.getElementById('add_jam')) document.getElementById('add_jam').value = item.jam;        
    if(document.getElementById('add_tahun_ajaran')) document.getElementById('add_tahun_ajaran').value = item.tahunAjaran;
    if(document.getElementById('add_catatan')) document.getElementById('add_catatan').value = item.catatan || '';
    
    document.querySelectorAll('.rombel-cb').forEach(cb => cb.checked = false);
    const checkbox = document.querySelector(`.rombel-cb[value="${item.rombel_id}"]`);
    if(checkbox) checkbox.checked = true;
    
    const btn = document.querySelector('#btnSubmitAdd');
    if(btn) btn.textContent = textObj.btn_save_changes;
    if(document.getElementById('modalTitle')) document.getElementById('modalTitle').textContent = "Edit Mapping Mapel";
    
    closeDrawer(); 
    showAddModal();
}

async function handleAddSubmit(event) {
    event.preventDefault();

    const rombels = document.querySelectorAll('.rombel-cb:checked');
    if (rombels.length === 0) {
        showToast('Pilih minimal satu rombel!', 'error');
        return;
    }

    const rombelArray = Array.from(rombels).map(cb => cb.value);

    const form = event.target;
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.innerText;
    btn.innerText = textObj.js_saving;
    btn.disabled = true;

    try {
        const formData = new FormData(form);
        formData.delete('add_rombel');
        formData.append('add_rombel', JSON.stringify(rombelArray));

        const id = document.getElementById('editId') ? document.getElementById('editId').value : '';
        const url = id ? `${BASE_URL}/admin/mapping-mapel/update` : `${BASE_URL}/admin/mapping-mapel/store`;

        const res = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        
        if (!res.ok) throw new Error("Server error " + res.status);
        
        const json = await res.json();

        if (json.status === 'success') {
            localStorage.setItem('toastMessage', json.message);
            localStorage.setItem('toastType', 'success');
            closeAddModal();
            window.location.reload(); 
        } else {
            showToast(textObj.js_fail_prefix + json.message, 'error');
        }
    } catch (err) {
        console.error(err);
        showToast(textObj.js_err_server, 'error');
    } finally {
        btn.innerText = originalText;
        btn.disabled = false;
    }
}

// --- 7. HANDLE DELETE (MODAL) ---
function confirmDeactivate(id) {
    deleteTargetId = id;
    const modal = document.getElementById('deleteModal');
    if(modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    if(modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
    deleteTargetId = null;
}

// PERBAIKAN: FUNGSI HAPUS DIDEFINISIKAN SEBAGAI GLOBAL FUNCTION
window.confirmDelete = async function() {
    if(!deleteTargetId) return;

    // Tambahkan id ke formData
    const formData = new FormData();
    formData.append('id', deleteTargetId);

    try {
        const res = await fetch(`${BASE_URL}/admin/mapping-mapel/delete`, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();

        if (json.status === 'success') {
            localStorage.setItem('toastMessage', json.message);
            localStorage.setItem('toastType', 'success');
            closeDeleteModal();
            window.location.reload();
        } else {
            showToast(textObj.js_fail_prefix + json.message, 'error');
            closeDeleteModal();
        }
    } catch (err) {
        console.error(err);
        showToast(textObj.js_err_conn, 'error');
        closeDeleteModal();
    } 
};


// ==========================================
// FUNGSI BULK MAPPING & IMPORT EXCEL 
// ==========================================
function showBulkModal() {
    const modal = document.getElementById('bulkModal');
    if(modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeBulkModal() {
    const modal = document.getElementById('bulkModal');
    if(modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
    if(document.getElementById('bulkMappingForm')) document.getElementById('bulkMappingForm').reset();
    document.querySelectorAll('.bulk-mapel-cb, .bulk-rombel-cb').forEach(cb => cb.checked = false);
    updateBulkMapelLabel();
    updateBulkRombelLabel();
}

function toggleBulkMapel() {
    const d = document.getElementById('bulkMapelDropdown');
    if(d) d.classList.toggle('hidden');
}

function toggleBulkRombel() {
    const d = document.getElementById('bulkRombelDropdown');
    if(d) d.classList.toggle('hidden');
}

function updateBulkMapelLabel() {
    const checked = document.querySelectorAll('.bulk-mapel-cb:checked');
    const label = document.getElementById('bulkMapelLabel');
    if(!label) return;
    if (checked.length > 0) {
        label.textContent = Array.from(checked).map(cb => cb.getAttribute('data-name')).join(', ');
        label.classList.add('text-gray-800', 'dark:!text-white');
        label.classList.remove('text-gray-500');
    } else {
        label.textContent = textObj.lbl_click_subj;
        label.classList.add('text-gray-500');
        label.classList.remove('text-gray-800', 'dark:!text-white');
    }
}

function updateBulkRombelLabel() {
    const checked = document.querySelectorAll('.bulk-rombel-cb:checked');
    const label = document.getElementById('bulkRombelLabel');
    if(!label) return;
    if (checked.length > 0) {
        label.textContent = Array.from(checked).map(cb => cb.getAttribute('data-name')).join(', ');
        label.classList.add('text-gray-800', 'dark:!text-white');
        label.classList.remove('text-gray-500');
    } else {
        label.textContent = textObj.lbl_click_room;
        label.classList.add('text-gray-500');
        label.classList.remove('text-gray-800', 'dark:!text-white');
    }
}

document.addEventListener('click', function(event) {
    const dMapel = document.getElementById('bulkMapelDropdown');
    const bMapel = dMapel ? dMapel.previousElementSibling : null;
    if (dMapel && bMapel && !dMapel.contains(event.target) && !bMapel.contains(event.target)) {
        dMapel.classList.add('hidden');
    }

    const dRombel = document.getElementById('bulkRombelDropdown');
    const bRombel = dRombel ? dRombel.previousElementSibling : null;
    if (dRombel && bRombel && !dRombel.contains(event.target) && !bRombel.contains(event.target)) {
        dRombel.classList.add('hidden');
    }
});

async function handleBulkSubmit(event) {
    event.preventDefault();
    const mapels = document.querySelectorAll('.bulk-mapel-cb:checked');
    const rombels = document.querySelectorAll('.bulk-rombel-cb:checked');
    
    if (mapels.length === 0 || rombels.length === 0) {
        showToast(textObj.js_err_min_bulk, 'error');
        return;
    }

    const form = event.target;
    const btn = form.querySelector('button[type="submit"]');
    const ori = btn.innerText;
    btn.innerText = textObj.js_saving; btn.disabled = true;

    try {
        const res = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const json = await res.json();
        if (json.status === 'success') {
            localStorage.setItem('toastMessage', json.message);
            localStorage.setItem('toastType', 'success');
            window.location.reload(); 
        } else {
            showToast(textObj.js_fail_prefix + json.message, 'error');
        }
    } catch (err) {
        showToast(textObj.js_err_server, 'error');
    } finally {
        btn.innerText = ori; btn.disabled = false;
    }
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeDeleteModal();
        closeDrawer();
        closeAddModal();
        if(typeof closeBulkModal === 'function') closeBulkModal();
    }
});

// Init
document.addEventListener('DOMContentLoaded', () => {
    updateUI();
    populateTable();
    checkPendingToast();
});