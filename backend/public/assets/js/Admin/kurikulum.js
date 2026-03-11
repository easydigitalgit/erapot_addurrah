// SABUK PENGAMAN BAHASA
const textObj = window.LANG || {
    js_loading: 'Memproses...', js_err_data_not_found: 'Data kurikulum tidak ditemukan.',
    js_succ_edit: 'Kurikulum berhasil diperbarui!', js_succ_add: 'Kurikulum baru berhasil ditambahkan!',
    js_warn_check: 'Harap centang konfirmasi terlebih dahulu', js_succ_apply: 'Kurikulum berhasil diterapkan!',
    js_succ_activate: 'Kurikulum berhasil diaktifkan!', js_succ_archive: 'Kurikulum berhasil diarsipkan!',
    js_notification: 'Notifikasi', js_err_fatal: 'Terjadi kesalahan fatal pada server.', js_err_conn: 'Koneksi ke server terputus.'
};

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

function showEditModal(id) {
    const curriculum = typeof curriculumData !== 'undefined' ? curriculumData.find(item => item.id == id) : null;
    
    if (curriculum) {
        document.getElementById('edit_curriculum_id').value = curriculum.id;
        document.getElementById('edit_curriculum_name').value = curriculum.nama_kurikulum; 
        document.getElementById('edit_curriculum_type').value = curriculum.jenis;          
        document.getElementById('edit_year_start').value = curriculum.tahun_berlaku;       
        
        if(document.getElementById('edit_year_end')) {
            document.getElementById('edit_year_end').value = curriculum.tahun_akhir || ''; 
        }
        if(document.getElementById('edit_description')) {
            document.getElementById('edit_description').value = curriculum.deskripsi || '';
        }

        toggleModal('editCurriculumModal', true);
    } else {
        console.error(textObj.js_err_data_not_found);
    }
}

function closeEditModal() {
    toggleModal('editCurriculumModal', false);
}

function showAddCurriculumModal() {
    toggleModal('addCurriculumModal', true);
}

function closeAddCurriculumModal() {
    toggleModal('addCurriculumModal', false);
}

function showApplyModal() {
    toggleModal('applyModal', true);
}

function closeApplyModal() {
    toggleModal('applyModal', false);
    const cb = document.getElementById('confirmApply');
    if(cb) cb.checked = false;
}

function showStructureModal(id) {
    const curriculum = typeof curriculumData !== 'undefined' ? curriculumData.find(item => item.id == id) : null;
    
    if (curriculum) {
        document.getElementById('structureCurriculumName').textContent = curriculum.nama_kurikulum; 
    }
    toggleModal('structureModal', true);
}

function closeStructureModal() {
    toggleModal('structureModal', false);
}

function showImpactModal() {
    toggleModal('impactModal', true);
}

function closeImpactModal() {
    toggleModal('impactModal', false);
}

// PERBAIKAN FATAL: AJAX FETCH UNTUK EDIT/UPDATE DATA KE DATABASE
async function handleEditCurriculum(event) {
    event.preventDefault();
    const form = event.target;
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = textObj.js_loading;
    btn.disabled = true;

    try {
        const baseUrlPath = typeof BASE_URL !== 'undefined' ? BASE_URL : window.location.origin + '/raporsmpit/';
        
        const response = await fetch(`${baseUrlPath}admin/kurikulum/update`, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        const data = await response.json();

        if (data.status === 'success') {
            showToast(data.message, 'success');
            closeEditModal();
            // Refresh halaman biar tabel nampilin data yang udah ter-update
            setTimeout(() => { window.location.reload(); }, 1500); 
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast(textObj.js_err_conn, 'error');
        console.error(error);
    } finally {
        if(btn) { 
            btn.innerHTML = originalText; 
            btn.disabled = false; 
        }
    }
}

async function handleAddCurriculum(event) {
    event.preventDefault();
    const form = event.target;
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = textObj.js_loading;
    btn.disabled = true;

    try {
        const baseUrlPath = typeof BASE_URL !== 'undefined' ? BASE_URL : window.location.origin + '/raporsmpit/';
        
        const response = await fetch(`${baseUrlPath}admin/kurikulum/store`, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        const data = await response.json();

        if (data.status === 'success') {
            showToast(data.message, 'success');
            closeAddCurriculumModal();
            form.reset();
            setTimeout(() => { window.location.reload(); }, 1500); 
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast(textObj.js_err_conn, 'error');
        console.error(error);
    } finally {
        if(btn) { 
            btn.innerHTML = originalText; 
            btn.disabled = false; 
        }
    }
}

function deleteCurriculum(id, name) {
    Swal.fire({
        title: 'Hapus Kurikulum?',
        text: `Anda yakin ingin menghapus kurikulum "${name}" secara permanen?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then(async (result) => {
        if (result.isConfirmed) {
            const baseUrlPath = typeof BASE_URL !== 'undefined' ? BASE_URL : window.location.origin + '/raporsmpit/';
            
            try {
                const response = await fetch(`${baseUrlPath}admin/kurikulum/delete/${id}`, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();
                if(data.status === 'success') {
                    showToast(data.message, 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast(data.message, 'error');
                }
            } catch(e) {
                showToast(textObj.js_err_conn, 'error');
            }
        }
    });
}

// PERBAIKAN FATAL: AJAX AKTIFKAN KURIKULUM
function activateCurriculum(id, name) {
    Swal.fire({
        title: 'Aktifkan Kurikulum?',
        text: `Anda akan MENGAKTIFKAN kurikulum "${name}".`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Aktifkan!',
        cancelButtonText: 'Batal'
    }).then(async (result) => {
        if (result.isConfirmed) {
            const baseUrlPath = typeof BASE_URL !== 'undefined' ? BASE_URL : window.location.origin + '/raporsmpit/';
            try {
                const response = await fetch(`${baseUrlPath}admin/kurikulum/activate/${id}`, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();
                if(data.status === 'success') {
                    showToast(data.message, 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast(data.message, 'error');
                }
            } catch(e) {
                showToast(textObj.js_err_conn, 'error');
            }
        }
    });
}

// PERBAIKAN FATAL: AJAX NONAKTIFKAN KURIKULUM
function deactivateCurriculum(id, name) {
    Swal.fire({
        title: 'Nonaktifkan Kurikulum?',
        text: `Anda akan MENONAKTIFKAN kurikulum "${name}".`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Nonaktifkan!',
        cancelButtonText: 'Batal'
    }).then(async (result) => {
        if (result.isConfirmed) {
            const baseUrlPath = typeof BASE_URL !== 'undefined' ? BASE_URL : window.location.origin + '/raporsmpit/';
            try {
                const response = await fetch(`${baseUrlPath}admin/kurikulum/deactivate/${id}`, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();
                if(data.status === 'success') {
                    showToast(data.message, 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast(data.message, 'error');
                }
            } catch(e) {
                showToast(textObj.js_err_conn, 'error');
            }
        }
    });
}

function handleApplyCurriculum(event) {
    event.preventDefault();
    const checkbox = document.getElementById('confirmApply');
    if (!checkbox.checked) {
        showToast(textObj.js_warn_check, 'warning');
        return;
    }
    showToast(textObj.js_succ_apply, 'success');
    closeApplyModal();
}

function toggleAccordion(button) {
    const panel = button.nextElementSibling;
    const arrow = button.querySelector('.menu-arrow');
    
    if (panel.classList.contains('hidden')) {
        panel.classList.remove('hidden');
        arrow.classList.add('rotate-180'); 
    } else {
        panel.classList.add('hidden');
        arrow.classList.remove('rotate-180');
    }
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = 'toast fixed top-4 right-4 z-[100000] flex items-center gap-3 px-4 py-3 bg-white border border-gray-100 rounded-xl shadow-lg transition-all duration-300';
    
    const bgClass = type === 'success' ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600';
    const icon = type === 'success' 
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>' 
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>';

    toast.innerHTML = `
        <div class="w-10 h-10 rounded-full flex items-center justify-center ${bgClass} flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">${icon}</svg>
        </div>
        <div>
            <p class="font-semibold text-gray-800">${textObj.js_notification}</p>
            <p class="text-sm text-gray-500">${message}</p>
        </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeEditModal();
        closeAddCurriculumModal();
        closeApplyModal();
        closeStructureModal();
        closeImpactModal();
        closeImportModal();
    }
});

function showImportModal() {
    toggleModal('importModal', true);
}

function closeImportModal() {
    toggleModal('importModal', false);
    const form = document.getElementById('importForm');
    if (form) form.reset();
}

async function handleImportSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const btn = form.querySelector('button[type="submit"]');
    const oriText = btn.innerHTML;
    
    btn.innerHTML = textObj.js_loading;
    btn.disabled = true;

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form) 
        });

        const rawText = await response.text();
        let data;
        
        try {
            data = JSON.parse(rawText);
        } catch (e) {
            console.error("SERVER CRASH! Response:", rawText);
            showToast(textObj.js_err_fatal, 'error');
            return; 
        }

        if (data.status === 'success') {
            showToast(data.message, 'success');
            setTimeout(() => {
                window.location.reload(); 
            }, 1500);
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        showToast(textObj.js_err_conn, 'error');
    } finally {
        if(btn) { 
            btn.innerHTML = oriText; 
            btn.disabled = false; 
        }
    }
}