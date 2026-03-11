const textObj = window.LANG || {
    js_no_data: 'Data tidak ditemukan.', js_status_active: 'Aktif', js_status_inactive: 'Nonaktif',
    js_tooltip_edit: 'Edit', js_tooltip_delete: 'Hapus', js_saving: 'Menyimpan...',
    js_success: 'Berhasil', js_fail: 'Gagal', js_err_conn: 'Kesalahan koneksi',
    js_err_del_fail: 'Gagal menghapus data', js_succ_del: 'Data mata pelajaran dihapus',
    js_error: 'Error', js_notif_title: 'Notifikasi', js_analyzing: 'Menganalisis & Upload...'
};

document.addEventListener('DOMContentLoaded', () => {
    window.mapelData = typeof dbMapelData !== 'undefined' ? dbMapelData : [];
    window.filteredMapel = [...window.mapelData];
    populateMapel();

    const searchInput = document.getElementById('searchMapel');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            filterData(e.target.value.toLowerCase(), activeFilter);
        });
    }

    const filterChips = document.querySelectorAll('.filter-chip');
    let activeFilter = 'Semua';
    
    filterChips.forEach(chip => {
        chip.addEventListener('click', function() {
            filterChips.forEach(c => {
                c.className = 'filter-chip px-4 py-2 rounded-lg text-sm font-semibold border border-gray-200 dark:border-slate-600 text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors outline-none';
                c.style.backgroundColor = ''; 
                c.style.color = '';           
                c.style.borderColor = '';     
            });

            this.className = 'filter-chip active px-4 py-2 rounded-lg text-sm font-semibold border transition-colors outline-none';
            this.style.backgroundColor = 'var(--warna-primary, #10b981)'; 
            this.style.color = '#ffffff';          
            this.style.borderColor = 'var(--warna-primary, #10b981)';    

            activeFilter = this.textContent.trim();
            filterData(searchInput ? searchInput.value.toLowerCase() : '', activeFilter);
        });
    });
});

function filterData(searchTerm, filterCategory) {
    window.filteredMapel = window.mapelData.filter(item => {
        const matchesSearch = item.name.toLowerCase().includes(searchTerm) || 
                              item.code.toLowerCase().includes(searchTerm);
        let matchesFilter = true;
        if (filterCategory !== 'Semua' && filterCategory !== 'All' && filterCategory !== 'الكل') {
            if (filterCategory === 'Umum' || filterCategory === 'General' || filterCategory === 'عامة') matchesFilter = (item.group === 'Wajib' || item.group === 'Peminatan' || item.group === 'Umum' || item.group === 'A' || item.group === 'B');
            else if (filterCategory === 'Keislaman' || filterCategory === 'Islamic' || filterCategory === 'إسلامية') matchesFilter = (item.group === 'Keislaman' || item.group === 'C');
            else if (filterCategory === 'Lokal' || filterCategory === 'Local' || filterCategory === 'محلية') matchesFilter = (item.group === 'Muatan Lokal' || item.group === 'Mulok' || item.group === 'Lokal');
        }
        return matchesSearch && matchesFilter;
    });
    populateMapel();
}

function getGroupBadgeColor(groupColor) {
    const colors = { 
        emerald: 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/50', 
        purple: 'bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 border-purple-200 dark:border-purple-800/50', 
        amber: 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-800/50', 
        blue: 'bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-800/50', 
        gray: 'bg-gray-50 dark:bg-slate-700 text-gray-700 dark:text-slate-300 border-gray-200 dark:border-slate-600' 
    };
    return colors[groupColor] || colors.emerald;
}

function populateMapel() {
    const tbody = document.getElementById('mapelTableBody');
    if (!tbody) return;

    if (window.filteredMapel.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-16 text-center text-gray-500 dark:text-slate-400">${textObj.js_no_data}</td></tr>`;
        return;
    }

    tbody.innerHTML = window.filteredMapel.map(mapel => `
        <tr class="table-row hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors border-b border-gray-100 dark:border-slate-700/50 group">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg text-white flex items-center justify-center font-bold text-xs shadow-md border border-transparent" style="background-color: var(--warna-primary, #10b981);">
                        ${mapel.code}
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 font-bold text-gray-800 dark:text-white group-hover:text-[var(--warna-primary,#10b981)] transition-colors">
                ${mapel.name}
            </td>
            <td class="px-6 py-4 text-center">
                <span class="px-3 py-1 ${getGroupBadgeColor(mapel.groupColor)} rounded-lg text-xs font-bold border transition-colors shadow-sm">
                    ${mapel.group}
                </span>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="px-3 py-1 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800/50 rounded-lg text-xs font-bold transition-colors shadow-sm">
                    ${mapel.curriculum}
                </span>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="font-bold text-gray-800 dark:text-slate-200">${mapel.hours}</span>
            </td>
            <td class="px-6 py-4 text-center">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider transition-colors shadow-sm ${mapel.status === 'Aktif' ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50' : 'bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800/50'}">
                    ${mapel.status === 'Aktif' ? textObj.js_status_active : textObj.js_status_inactive}
                </span>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center justify-center gap-2 opacity-1 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity">
                    <button onclick="showEditMapelModal('${mapel.id}')" class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors tooltip" title="${textObj.js_tooltip_edit}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </button>
                    <button onclick="deleteMapel('${mapel.id}', this.getAttribute('data-name'))" data-name="${mapel.name.replace(/"/g, '&quot;')}" class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors tooltip" title="${textObj.js_tooltip_delete}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// DRAWER ACTIONS
window.showDetail = function(id) { /* Tidak ada fungsi detail drawer asli dari file Anda, tombolnya juga tak terlihat memanggil showDetail. Saya biarkan utuh karena drawer ada di HTML. Jika ada action khusus, disisipkan di sini. */ };
window.closeDrawer = function() {
    document.getElementById('detailDrawer').classList.add('translate-x-full');
    document.getElementById('drawer-overlay').classList.add('hidden');
    document.getElementById('drawer-overlay').classList.remove('opacity-100');
};

// MODAL ACTIONS (ADD)
window.showAddMapelModal = () => { 
    document.getElementById('addMapelModal').classList.remove('hidden'); 
    document.getElementById('addMapelForm').reset(); 
};
window.closeAddMapelModal = () => document.getElementById('addMapelModal').classList.add('hidden');

window.handleMapelSubmit = async (e) => { 
    e.preventDefault(); 
    const form = e.target;
    await submitForm(form, `${BASE_URL}/admin/mata-pelajaran/store`, closeAddMapelModal); 
};

// MODAL ACTIONS (EDIT)
window.showEditMapelModal = (id) => {
    const mapel = window.mapelData.find(m => m.id == id);
    if (!mapel) {
        console.error(textObj.js_no_data);
        return;
    }

    setValue('edit_mapel_id', mapel.id);
    setValue('edit_mapel_code', mapel.code);
    setValue('edit_mapel_name', mapel.name);
    setValue('edit_group', mapel.group);
    setValue('edit_curriculum', mapel.curriculum_id); 
    setValue('edit_hours', mapel.hours);
    
    document.getElementById('editMapelModal').classList.remove('hidden');
};
window.closeEditMapelModal = () => document.getElementById('editMapelModal').classList.add('hidden');

window.handleEditMapelSubmit = async (e) => { 
    e.preventDefault(); 
    const form = e.target;
    const id = document.getElementById('edit_mapel_id').value; 
    await submitForm(form, `${BASE_URL}/admin/mata-pelajaran/update/${id}`, closeEditMapelModal); 
};

// MODAL ACTIONS (DELETE)
let deleteId = null;

window.deleteMapel = function(id, name) {
    deleteId = id;
    document.getElementById('deleteMapelName').textContent = name;
    document.getElementById('deleteModal').classList.remove('hidden');
};

window.closeDeleteModal = function() {
    deleteId = null;
    document.getElementById('deleteModal').classList.add('hidden');
};

window.confirmDelete = async function() {
    if (!deleteId) return;

    try {
        const formData = new FormData();
        formData.append(csrfTokenName, csrfTokenHash);
        formData.append('_method', 'DELETE');

        const res = await fetch(`${BASE_URL}/admin/mata-pelajaran/delete/${deleteId}`, {
            method: 'POST', 
            body: formData,
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        });

        const json = await res.json();
        
        if (json.status === 'success') {
            showToast('success', textObj.js_success, textObj.js_succ_del);
            closeDeleteModal();
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showToast('error', textObj.js_fail, json.message || textObj.js_err_del_fail);
        }
    } catch (err) {
        console.error(err);
        showToast('error', textObj.js_error, textObj.js_err_conn);
    }
};

// MODAL ACTIONS (IMPORT - BARU)
window.showImportModal = () => document.getElementById('importModal').classList.remove('hidden');
window.closeImportModal = () => document.getElementById('importModal').classList.add('hidden');

window.handleImportSubmit = async function(event) {
    event.preventDefault();
    const form = event.target;
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;

    btn.innerHTML = textObj.js_analyzing || 'Menganalisis & Upload...';
    btn.disabled = true;

    try {
        const formData = new FormData(form);
        const res = await fetch(form.action, { 
            method: 'POST', 
            body: formData,
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        });
        
        const json = await res.json();

        if (json.status === 'success') {
            showToast('success', textObj.js_success, json.message);
            closeImportModal();
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showToast('error', textObj.js_fail, json.message);
        }
    } catch (err) {
        console.error(err);
        showToast('error', textObj.js_error, textObj.js_err_conn);
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
};

// MODAL ACTIONS (GROUP SETTING - BARU)
window.showGroupSettingModal = () => document.getElementById('groupSettingModal').classList.remove('hidden');
window.closeGroupSettingModal = () => document.getElementById('groupSettingModal').classList.add('hidden');

// GLOBAL UTILITIES
async function submitForm(form, url, closeCallback) {
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.innerText; 
    btn.innerText = textObj.js_saving; 
    btn.disabled = true;
    
    try {
        const formData = new FormData(form);
        if (!formData.has(csrfTokenName)) {
            formData.append(csrfTokenName, csrfTokenHash);
        }

        const res = await fetch(url, { 
            method: 'POST', 
            body: formData, 
            headers: {'X-Requested-With': 'XMLHttpRequest'} 
        });
        
        const json = await res.json();
        if (json.status === 'success') { 
            showToast('success', textObj.js_success, json.message); 
            closeCallback(); 
            setTimeout(() => window.location.reload(), 1000); 
        } else { 
            showToast('error', textObj.js_fail, json.message); 
        }
    } catch (err) { 
        console.error(err); 
        showToast('error', textObj.js_error, textObj.js_err_conn); 
    } finally { 
        btn.innerText = originalText; 
        btn.disabled = false; 
    }
}

function setValue(id, val) { const el = document.getElementById(id); if(el) el.value = val; }

function showToast(type, title, msg) {
    const div = document.createElement('div');
    div.className = `fixed top-4 right-4 z-[999999] bg-white dark:bg-slate-800 p-4 rounded-xl shadow-2xl border-l-4 flex gap-3 ${type==='success'?'border-emerald-500':'border-red-500'}`;
    div.innerHTML = `<div><h4 class="font-bold text-sm text-gray-800 dark:text-white">${title}</h4><p class="text-sm text-gray-500 dark:text-slate-400">${msg}</p></div>`;
    document.body.appendChild(div); 
    setTimeout(() => div.remove(), 3000);
}

document.addEventListener('keydown', (e) => { 
    if (e.key === 'Escape') { 
        closeAddMapelModal(); 
        closeEditMapelModal(); 
        closeDeleteModal(); 
        closeImportModal();
        closeGroupSettingModal();
        if (typeof closeDrawer === 'function') closeDrawer();
    } 
});