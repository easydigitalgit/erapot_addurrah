// ==========================================
// 1. CONFIG & SETUP
// ==========================================
const yearData = (typeof dbYearData !== 'undefined') ? dbYearData : [];

// ==========================================
// 2. FUNGSI RENDER TABEL
// ==========================================
function populateYearTable() {
    const tbody = document.getElementById('yearTableBody');
    if (!tbody) return;

    if (yearData.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-slate-400 font-medium">${LANG.js_no_data_year}</td></tr>`;
        return;
    }

    const formatTgl = (tgl) => {
        if(!tgl || tgl === '0000-00-00') return '-';
        const d = new Date(tgl);
        return d.toLocaleDateString(APP_LANG, { year: 'numeric', month: 'short', day: 'numeric' });
    };

    tbody.innerHTML = yearData.map(item => `
    <tr class="table-row hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors border-b border-gray-100 dark:border-slate-700/50 group"> 
         <td class="px-6 py-4">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-lg ${item.status === 'aktif' ? 'bg-emerald-100 dark:bg-emerald-900/30' : 'bg-gray-100 dark:bg-slate-700'} flex items-center justify-center transition-colors">
                    <svg class="w-5 h-5 ${item.status === 'aktif' ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-500 dark:text-slate-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-gray-900 dark:text-white">${item.year}</p>
                    <p class="text-[10px] text-gray-500 dark:text-slate-400 font-medium">${formatTgl(item.tgl_mulai)} - ${formatTgl(item.tgl_akhir)}</p>
                </div>
            </div>
         </td>
        <td class="px-6 py-4">
            <span class="text-sm font-medium text-gray-700 dark:text-slate-300">${item.semester}</span>
        </td>
        <td class="px-6 py-4 text-center">
            ${item.status === 'aktif' ? 
                `<span class="inline-flex px-3 py-1 text-[11px] font-bold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-full border border-emerald-200 dark:border-emerald-800/50 shadow-sm transition-colors">${LANG.js_status_active}</span>` : 
                `<span class="inline-flex px-3 py-1 text-[11px] font-bold uppercase tracking-wider bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-400 rounded-full border border-gray-200 dark:border-slate-600 shadow-sm transition-colors">${LANG.js_status_archived}</span>`
            }
        </td>
        <td class="px-6 py-4 text-center"><span class="font-bold text-gray-800 dark:text-slate-200">${item.students}</span></td>
        <td class="px-6 py-4 text-center"><span class="font-bold text-gray-800 dark:text-slate-200">${item.teachers}</span></td>
        <td class="px-6 py-4 text-center">
            ${item.locked ? 
                `<span class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider bg-amber-50 dark:bg-amber-900/20 text-amber-700 dark:text-amber-400 rounded-lg border border-amber-200 dark:border-amber-800/50 shadow-sm transition-colors">${LANG.js_locked}</span>` : 
                `<span class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400 rounded-lg border border-blue-200 dark:border-blue-800/50 shadow-sm transition-colors">${LANG.js_unlocked}</span>`
            }
        </td>
        <td class="px-6 py-4">
            <div class="flex items-center justify-center gap-2 opacity-1 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity">
                ${item.status !== 'aktif' ? `
                <button onclick="confirmActivateYear(${item.id}, '${item.year}')" class="px-3 py-1.5 text-xs bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg transition-transform transform hover:-translate-y-0.5 shadow-sm outline-none" title="${LANG.js_btn_activate}">
                    ${LANG.js_btn_activate}
                </button>
                ` : ''}
                <button onclick="showDetailModal(${item.id})" class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors cursor-pointer outline-none" title="${LANG.js_btn_detail}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
                
                <button onclick="showEditYearModal(${item.id})" class="p-2 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-lg transition-colors cursor-pointer outline-none" title="${LANG.js_btn_edit}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                ${item.status !== 'aktif' ? `
                <button onclick="deleteYearPrompt(${item.id}, '${item.year}')" class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors cursor-pointer outline-none" title="${LANG.js_btn_delete}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
                ` : `
                <button class="p-2 text-gray-300 dark:text-gray-600 rounded-lg cursor-not-allowed outline-none" title="${LANG.js_tooltip_cannot_delete}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
                `}
            </div>
        </td>
    </tr>
    `).join('');
}

// ==========================================
// 3. UI HELPER FUNCTIONS
// ==========================================

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = 'toast fixed top-4 right-4 z-[100000] flex items-center gap-3 px-4 py-3 bg-white border border-gray-100 rounded-xl shadow-lg transition-all duration-300 transform translate-x-full opacity-0';
    
    toast.innerHTML = `
        <div class="w-10 h-10 rounded-full flex items-center justify-center ${type === 'success' ? 'bg-emerald-100' : (type === 'error' ? 'bg-red-100' : 'bg-amber-100')} flex-shrink-0">
            <svg class="w-6 h-6 ${type === 'success' ? 'text-emerald-600' : (type === 'error' ? 'text-red-600' : 'text-amber-600')}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${type === 'success' ? 
                  '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>' : 
                  '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>'
                }
            </svg>
        </div>
        <div>
            <p class="font-semibold text-gray-800">${LANG.js_notification}</p>
            <p class="text-sm text-gray-500">${message}</p>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    requestAnimationFrame(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
    });

    setTimeout(() => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// ==========================================
// 4. MODAL HANDLERS
// ==========================================

function toggleModal(modalId, show) {
    const modal = document.getElementById(modalId);
    if (modal) {
        if (show) {
            modal.classList.remove('hidden'); 
            document.body.style.overflow = 'hidden';
        } else {
            modal.classList.add('hidden'); 
            document.body.style.overflow = '';
        }
    }
}

function showAddYearModal() { toggleModal('addYearModal', true); }
function closeAddYearModal() { toggleModal('addYearModal', false); }

async function handleAddYear(event) {
    event.preventDefault();
    const form = event.target;
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.textContent;
    
    btn.textContent = LANG.js_saving;
    btn.disabled = true;

    try {
        const formData = new FormData(form);
        const res = await fetch(`${BASE_URL}admin/tahun-ajaran/store`, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();

        if (json.status === 'success') {
            showToast(json.message, 'success');
            closeAddYearModal();
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showToast(json.message, 'error');
        }
    } catch (err) {
        showToast(LANG.js_fail_server, 'error');
    } finally {
        btn.textContent = originalText;
        btn.disabled = false;
    }
}

async function showEditYearModal(id) {
    try {
        const res = await fetch(`${BASE_URL}admin/tahun-ajaran/show/${id}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();

        if (json.status === 'success') {
            const data = json.data;
            document.getElementById('edit_id').value = data.id;
            document.getElementById('edit_year').value = data.tahun;
            document.getElementById('edit_semester').value = data.semester;
            document.getElementById('edit_start_date').value = data.tgl_mulai;
            document.getElementById('edit_end_date').value = data.tgl_akhir;

            toggleModal('editYearModal', true);
        } else {
            showToast(json.message, 'error');
        }
    } catch (err) {
        showToast(LANG.js_fail_fetch, 'error');
    }
}

function closeEditYearModal() { toggleModal('editYearModal', false); }

async function handleEditYear(event) {
    event.preventDefault();
    const form = event.target;
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.textContent;
    
    btn.textContent = LANG.js_saving;
    btn.disabled = true;

    try {
        const formData = new FormData(form);
        const res = await fetch(`${BASE_URL}admin/tahun-ajaran/update`, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();

        if (json.status === 'success') {
            showToast(json.message, 'success');
            closeEditYearModal();
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showToast(json.message, 'error');
        }
    } catch (err) {
        showToast(LANG.js_fail_server, 'error');
    } finally {
        btn.textContent = originalText;
        btn.disabled = false;
    }
}

function deleteYearPrompt(id, yearText) {
    document.getElementById('delete_year_id').value = id;
    document.getElementById('deleteYearText').textContent = yearText;
    toggleModal('deleteYearModal', true);
}

function closeDeleteYearModal() { toggleModal('deleteYearModal', false); }

async function confirmDeleteYear() {
    const id = document.getElementById('delete_year_id').value;
    if(!id) return;

    try {
        const formData = new FormData();
        formData.append('id', id);

        const res = await fetch(`${BASE_URL}admin/tahun-ajaran/delete`, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();

        if (json.status === 'success') {
            showToast(json.message, 'success');
            closeDeleteYearModal();
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showToast(json.message, 'error');
            closeDeleteYearModal();
        }
    } catch (err) {
        showToast(LANG.js_fail_server, 'error');
    }
}

function showChangeSemesterModal(currentSemester) { 
    toggleModal('changeSemesterModal', true); 
    
    const textEl = document.getElementById('textChangeSemester');
    if(textEl && currentSemester) {
        const targetSemester = currentSemester === 'Ganjil' ? 'Genap' : 'Ganjil';
        textEl.innerHTML = `${LANG.js_change_from} <strong class="text-gray-900 dark:text-white bg-white/50 dark:bg-slate-800/50 px-1 rounded">${currentSemester}</strong> ${LANG.js_change_to} <strong class="text-gray-900 dark:text-white bg-white/50 dark:bg-slate-800/50 px-1 rounded">${targetSemester}</strong>.`;
    }
}

function closeChangeSemesterModal() { 
    toggleModal('changeSemesterModal', false);
    const cb = document.getElementById('confirmChangeSemester');
    if(cb) cb.checked = false;
}

async function confirmChangeSemester() {
    const checkbox = document.getElementById('confirmChangeSemester');
    if (!checkbox.checked) {
        showToast(LANG.js_warn_checkbox, 'warning');
        return;
    }

    try {
        const res = await fetch(`${BASE_URL}admin/tahun-ajaran/changeSemester`, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();

        if (json.status === 'success') {
            showToast(json.message, 'success');
            closeChangeSemesterModal();
            setTimeout(() => window.location.reload(), 1000); 
        } else {
            showToast(json.message, 'error');
        }
    } catch (err) {
        showToast(LANG.js_fail_server, 'error');
    }
}

function showImpactModal() { toggleModal('impactModal', true); }
function closeImpactModal() { toggleModal('impactModal', false); }

function showDeactivateModal() { toggleModal('deactivateModal', true); }
function closeDeactivateModal() { 
    toggleModal('deactivateModal', false);
    ['confirmDeactivate1', 'confirmDeactivate2', 'confirmDeactivate3'].forEach(id => {
        const cb = document.getElementById(id);
        if(cb) cb.checked = false;
    });
}
async function confirmDeactivate() {
    const cb1 = document.getElementById('confirmDeactivate1');
    const cb2 = document.getElementById('confirmDeactivate2');
    const cb3 = document.getElementById('confirmDeactivate3');
    
    // Validasi 3 Checkbox wajib dicentang
    if (!cb1.checked || !cb2.checked || !cb3.checked) {
        showToast(LANG.js_warn_all_checkbox, 'warning');
        return;
    }
    
    // Ambil tombol untuk kita disable saat loading
    const modal = document.getElementById('deactivateModal');
    const btnSubmit = modal.querySelector('button.bg-red-600');
    const originalText = btnSubmit.innerHTML;

    btnSubmit.disabled = true;
    btnSubmit.innerHTML = LANG.js_saving || 'Memproses...';

    try {
        // Tembak API baru yang sudah kita buat di Controller
        const res = await fetch(`${BASE_URL}admin/tahun-ajaran/deactivate-all`, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        
        const json = await res.json();

        if (json.status === 'success') {
            showToast(json.message, 'success');
            closeDeactivateModal();
            setTimeout(() => window.location.reload(), 1500); // Refresh agar UI kosong memuat ulang
        } else {
            showToast(json.message, 'error');
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = originalText;
        }
    } catch (err) {
        console.error(err);
        showToast(LANG.js_fail_server, 'error');
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = originalText;
    }
}

function showDetailModal(id) {
    const data = yearData.find(item => item.id == id);
    if(!data) {
        showToast(LANG.js_data_not_found, "error");
        return;
    }
    
    const formatTgl = (tgl) => {
        if(!tgl || tgl === '0000-00-00') return '-';
        const d = new Date(tgl);
        return d.toLocaleDateString(APP_LANG, { year: 'numeric', month: 'long', day: 'numeric' });
    };

    document.getElementById('detailYear').textContent = data.year;
    document.getElementById('detailSemester').textContent = data.semester;
    document.getElementById('detailStudents').textContent = data.students;
    document.getElementById('detailTeachers').textContent = data.teachers;
    document.getElementById('detailStartDate').textContent = formatTgl(data.tgl_mulai);
    document.getElementById('detailEndDate').textContent = formatTgl(data.tgl_akhir);
    
    const statusBadge = document.getElementById('detailStatusBadge');
    if (data.status === 'aktif') {
        statusBadge.className = 'badge badge-active bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold border border-emerald-200';
        statusBadge.textContent = LANG.js_status_active;
    } else {
        statusBadge.className = 'badge badge-archived bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold border border-gray-200';
        statusBadge.textContent = LANG.js_status_archived;
    }

    const lockedBadge = document.getElementById('detailLocked');
    if (data.locked) {
        lockedBadge.className = 'badge badge-locked bg-amber-50 text-amber-700 px-2 py-1 rounded-lg text-xs font-semibold border border-amber-200';
        lockedBadge.textContent = LANG.js_locked;
    } else {
        lockedBadge.className = 'badge badge-info bg-blue-50 text-blue-700 px-2 py-1 rounded-lg text-xs font-semibold border border-blue-200';
        lockedBadge.textContent = LANG.js_unlocked;
    }

    toggleModal('detailModal', true);
}
function closeDetailModal() { toggleModal('detailModal', false); }

async function confirmActivateYear(id, yearText) {
    if(!confirm(LANG.js_confirm_activate.replace('{year}', yearText))) return;

    try {
        const formData = new FormData();
        formData.append('id', id);

        const res = await fetch(`${BASE_URL}admin/tahun-ajaran/activate`, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();

        if (json.status === 'success') {
            showToast(json.message, 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showToast(json.message, 'error');
        }
    } catch (err) {
        showToast(LANG.js_fail_server, 'error');
    }
}

// ==========================================
// 5. INITIALIZATION
// ==========================================

document.addEventListener('DOMContentLoaded', () => {
    populateYearTable(); 
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeAddYearModal();
        closeEditYearModal();
        closeDeleteYearModal();
        closeChangeSemesterModal();
        closeImpactModal();
        closeDeactivateModal();
        closeDetailModal();
    }
});