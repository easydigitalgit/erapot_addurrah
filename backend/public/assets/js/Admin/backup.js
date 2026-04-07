// Gunakan object LANG_BACKUP dari view
function toggleAutoBackup(toggle) {
    toggle.classList.toggle('active');
    const scheduleSettings = document.getElementById('scheduleSettings');
    const hiddenInput = document.getElementById('val_auto_backup');
    
    if (toggle.classList.contains('active')) {
        scheduleSettings.style.opacity = '1';
        scheduleSettings.style.pointerEvents = 'auto';
        hiddenInput.value = '1';
        showToast(LANG_BACKUP.conf_on, 'info');
    } else {
        scheduleSettings.style.opacity = '0.5';
        scheduleSettings.style.pointerEvents = 'none';
        hiddenInput.value = '0';
        showToast(LANG_BACKUP.conf_off, 'info');
    }
}

function toggleCategories(isFull) {
    const checkboxes = document.querySelectorAll('.backup-cat-checkbox');
    checkboxes.forEach(cb => {
        if (isFull) {
            cb.checked = true;
            cb.disabled = true;
        } else {
            cb.disabled = false;
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const formAutoBackup = document.getElementById('formAutoBackup');
    if (formAutoBackup) {
        formAutoBackup.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSaveSetting');
            const originalText = btn.innerHTML;
            btn.innerHTML = LANG_BACKUP.saving;
            btn.disabled = true;

            const formData = new FormData(this);
            if (typeof CSRF_NAME !== 'undefined') formData.append(CSRF_NAME, CSRF_TOKEN);

            fetch(BASE_URL + 'admin/backup/save-settings', {
                method: 'POST', body: formData, headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(res => res.json())
            .then(data => {
                showToast(data.message, data.status);
                btn.innerHTML = originalText;
                btn.disabled = false;
            })
            .catch(err => {
                showToast(LANG_BACKUP.err_conn, 'error');
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        });
    }
});

// ==========================================
// BACKUP FUNCTIONS
// ==========================================
function showBackupModal() {
    document.getElementById('backupModal').classList.remove('hidden');
    setTimeout(() => {
        document.getElementById('backupModal').children[0].classList.remove('opacity-0');
        document.getElementById('backupModal').children[1].classList.remove('scale-95');
    }, 10);
    document.body.style.overflow = 'hidden';
}

function closeBackupModal() {
    document.getElementById('backupModal').children[0].classList.add('opacity-0');
    document.getElementById('backupModal').children[1].classList.add('scale-95');
    setTimeout(() => {
        document.getElementById('backupModal').classList.add('hidden');
    }, 300);
    document.getElementById('confirmBackupModal').checked = false;
    document.body.style.overflow = '';
}

function startBackup() {
    const checkbox = document.getElementById('confirmBackupModal');
    
    if (!checkbox.checked) {
        showToast(LANG_BACKUP.warn_check, 'error');
        return;
    }

    closeBackupModal();
    
    const formData = new FormData();
    if (typeof CSRF_NAME !== 'undefined') formData.append(CSRF_NAME, CSRF_TOKEN);

    const mode = document.querySelector('input[name="backupMode"]:checked').value;
    formData.append('mode', mode);

    if (mode === 'partial') {
        const checkedCats = Array.from(document.querySelectorAll('.backup-cat-checkbox:checked')).map(cb => cb.value);
        if (checkedCats.length === 0) {
            showToast(LANG_BACKUP.warn_cat, 'error');
            return;
        }
        formData.append('categories', JSON.stringify(checkedCats));
    }

    // Peningkatan UX: SweetAlert Loading Spinner agar user tidak refresh page
    Swal.fire({
        title: LANG_BACKUP.prog,
        text: LANG_BACKUP.desc,
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(BASE_URL + 'admin/backup/do-backup', {
        method: 'POST', body: formData, headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(response => response.json())
    .then(res => {
        if (res.status === 'success') {
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message, timer: 2000, showConfirmButton: false });
            setTimeout(() => window.location.reload(), 2000);
        } else {
            Swal.fire('Error', res.message, 'error');
        }
    })
    .catch(err => {
        Swal.fire('Network Error', LANG_BACKUP.err_conn, 'error');
    });
}

function deleteBackup(filename) {
    Swal.fire({
        title: 'Hapus File?',
        text: LANG_BACKUP.del_conf + ' ' + filename,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            showToast(LANG_BACKUP.deleting, 'info');
            const formData = new FormData();
            formData.append('filename', filename);
            if (typeof CSRF_NAME !== 'undefined') formData.append(CSRF_NAME, CSRF_TOKEN);

            fetch(BASE_URL + 'admin/backup/delete', {
                method: 'POST', body: formData, headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    showToast(res.message, 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else { showToast(res.message, 'error'); }
            });
        }
    });
} 

// ==========================================
// RESTORE FUNCTIONS
// ==========================================
let selectedRestoreFile = null;  
let selectedServerFile = null;   

function showRestoreModal(filename) {
    selectedServerFile = filename;
    selectedRestoreFile = null; 
    document.getElementById('restoreFileName').textContent = filename;
    
    document.getElementById('restoreModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function handleFileUploadSelection(file) {
    selectedRestoreFile = file;
    selectedServerFile = null; 
    document.getElementById('restoreFileName').textContent = file.name;
    
    document.getElementById('restoreModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRestoreModal() {
    document.getElementById('restoreModal').classList.add('hidden');
    document.getElementById('confirmRestore1').checked = false;
    document.getElementById('confirmRestore2').checked = false;
    document.body.style.overflow = '';
    document.getElementById('fileInput').value = ''; 
}

function startRestore() {
    const checkbox1 = document.getElementById('confirmRestore1');
    const checkbox2 = document.getElementById('confirmRestore2');
    
    if (!checkbox1.checked || !checkbox2.checked) {
        showToast(LANG_BACKUP.warn_all_chk, 'error');
        return;
    }

    if (!selectedRestoreFile && !selectedServerFile) {
        showToast(LANG_BACKUP.err_no_file, 'error'); return;
    }

    closeRestoreModal();
    
    // Peningkatan UX: SweetAlert Loading Spinner untuk Restore
    Swal.fire({
        title: LANG_BACKUP.rest_prog,
        text: LANG_BACKUP.rest_desc,
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    const formData = new FormData();
    if (selectedRestoreFile) formData.append('backup_file', selectedRestoreFile);
    else if (selectedServerFile) formData.append('filename', selectedServerFile);
    if (typeof CSRF_NAME !== 'undefined') formData.append(CSRF_NAME, CSRF_TOKEN);

    fetch(BASE_URL + 'admin/backup/restore', {
        method: 'POST', body: formData, headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === 'success') {
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: res.message, timer: 2500, showConfirmButton: false });
            setTimeout(() => window.location.reload(), 2500);
        } else { 
            Swal.fire('Restore Gagal', res.message, 'error'); 
        }
    })
    .catch(err => { 
        Swal.fire('Network Error', LANG_BACKUP.err_conn, 'error'); 
    });
}

// ==========================================
// UTILS
// ==========================================
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    const colors = { success: { icon: 'emerald', path: 'M5 13l4 4L19 7' }, error: { icon: 'red', path: 'M6 18L18 6M6 6l12 12' }, info: { icon: 'blue', path: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' } };
    const { icon, path } = colors[type] || colors.success;
    
    toast.className = `toast fixed top-4 right-4 z-[100000] flex items-center gap-3 px-4 py-3 bg-white dark:bg-slate-800 border-l-4 border-${icon}-500 rounded-xl shadow-lg transition-all duration-300`;
    toast.innerHTML = `<div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-${icon}-100 dark:bg-${icon}-900/30"><svg class="w-6 h-6 text-${icon}-600 dark:text-${icon}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${path}"/></svg></div><div><p class="font-semibold text-gray-800 dark:text-white text-sm">${message}</p></div>`;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 4000);
}

document.getElementById('fileInput').addEventListener('change', function(e) {
    if (e.target.files.length > 0) handleFileUploadSelection(e.target.files[0]);
});

const dropzone = document.querySelector('.dropzone');
dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.classList.add('border-blue-500'); });
dropzone.addEventListener('dragleave', () => { dropzone.classList.remove('border-blue-500'); });
dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.classList.remove('border-blue-500');
    if (e.dataTransfer.files.length > 0) handleFileUploadSelection(e.dataTransfer.files[0]);
});