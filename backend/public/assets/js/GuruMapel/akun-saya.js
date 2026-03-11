// ==========================================
// 0. SABUK PENGAMAN BAHASA (FALLBACK)
// ==========================================
const LANG = window.LANG || {
    saving: 'Menyimpan...',
    save_changes: 'Simpan Perubahan',
    err_pwd_match: 'Password baru dan konfirmasi tidak cocok',
    err_pwd_len: 'Password minimal 8 karakter',
    processing: 'Memproses...',
    succ_pwd: 'Password diubah! Redirecting ke Login...',
    change_pwd: 'Ubah Password',
    err_img_size: 'Ukuran gambar maksimal 2MB!',
    uploading: 'Sedang mengunggah foto...',
    save_pref: 'Simpan Preferensi'
};

// ==========================================
// 1. HELPERS & UTILS
// ==========================================
function getCsrfToken() {
    const csrfMeta = document.querySelector('meta[name="X-CSRF-TOKEN"]');
    if (csrfMeta) {
        return csrfMeta.getAttribute('content');
    } else {
        return '';
    }
}

function toggleSwitch(toggle) {
    toggle.classList.toggle('active');
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = 'toast fixed top-4 right-4 z-[100000] flex items-center gap-3 px-4 py-3 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-xl shadow-lg transition-all duration-300';
    
    const colors = {
        success: { icon: 'emerald', path: 'M5 13l4 4L19 7' },
        error:   { icon: 'red', path: 'M6 18L18 6M6 6l12 12' },
        info:    { icon: 'blue', path: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' }
    };
    
    const { icon, path } = colors[type] || colors.success;
    
    toast.innerHTML = `
        <svg class="w-6 h-6 text-${icon}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${path}"/>
        </svg>
        <div>
            <p class="font-semibold text-gray-800 dark:text-white text-sm">${message}</p>
        </div>
    `;
    
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

// ==========================================
// 2. MODAL PASSWORD
// ==========================================
function showChangePasswordModal() {
    const modal = document.getElementById('passwordModal');
    if(modal) {
        modal.classList.remove('pointer-events-none', 'opacity-0');
        modal.classList.add('opacity-100');
        const modalPanel = modal.querySelector('div.bg-white, div.dark\\:bg-slate-800');
        if (modalPanel) {
            modalPanel.classList.remove('scale-95');
            modalPanel.classList.add('scale-100');
        }
        document.body.style.overflow = 'hidden';
    }
}

function closePasswordModal() {
    const modal = document.getElementById('passwordModal');
    const form = document.getElementById('changePasswordForm');
    if(modal) {
        modal.classList.add('pointer-events-none', 'opacity-0');
        modal.classList.remove('opacity-100');
        const modalPanel = modal.querySelector('div.bg-white, div.dark\\:bg-slate-800');
        if (modalPanel) {
            modalPanel.classList.add('scale-95');
            modalPanel.classList.remove('scale-100');
        }
        document.body.style.overflow = '';
    }
    if(form) form.reset();
}

// ==========================================
// 3. MESIN AJAX UTAMA
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
    
    // --- A. SIMPAN PROFIL PRIBADI ---
    const personalInfoForm = document.getElementById('personalInfoForm');
    if (personalInfoForm) {
        personalInfoForm.addEventListener('submit', async function(e) {
            e.preventDefault(); 
            const btn = document.getElementById('btnSaveInfo');
            const text = document.getElementById('textSaveInfo');
            
            btn.disabled = true; text.textContent = LANG.saving;

            const formData = new FormData(this);
            formData.append('csrf_test_name', getCsrfToken());

            try {
                const response = await fetch(BASE_URL + 'guru/akun-saya/update-profile', {
                    method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const result = await response.json();
                
                if (!response.ok || result.status !== 'success') throw new Error(result.message);
                showToast(result.message, 'success');
            } catch (error) {
                showToast(error.message, 'error');
            } finally {
                btn.disabled = false; text.textContent = LANG.save_changes;
            }
        });
    }

    // --- B. SIMPAN UBAH PASSWORD ---
    const pwdForm = document.getElementById('changePasswordForm');
    if (pwdForm) {
        pwdForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const oldPass = document.getElementById('oldPassword').value;
            const newPass = document.getElementById('newPassword').value;
            const confirmPass = document.getElementById('confirmPassword').value;

            if (newPass !== confirmPass) { showToast(LANG.err_pwd_match, 'error'); return; }
            if (newPass.length < 8) { showToast(LANG.err_pwd_len, 'error'); return; }

            const btn = document.getElementById('btnSavePassword');
            const text = document.getElementById('textSavePassword');
            btn.disabled = true; text.textContent = LANG.processing;

            const formData = new FormData();
            formData.append('csrf_test_name', getCsrfToken());
            formData.append('old_password', oldPass);
            formData.append('new_password', newPass);

            try {
                const response = await fetch(BASE_URL + 'guru/akun-saya/update-password', {
                    method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const result = await response.json();
                
                if (!response.ok || result.status !== 'success') throw new Error(result.message);
                
                closePasswordModal();
                showToast(LANG.succ_pwd, 'success');
                setTimeout(() => window.location.href = BASE_URL + 'logout', 2000);
            } catch (error) {
                showToast(error.message, 'error');
                btn.disabled = false; text.textContent = LANG.change_pwd;
            }
        });
    }

    // --- C. UPLOAD AVATAR ---
    const avatarInput = document.getElementById('avatarUpload');
    if(avatarInput) {
        avatarInput.addEventListener('change', async function(e) {
            if (e.target.files.length > 0) {
                const file = e.target.files[0];
                if(file.size > 2 * 1024 * 1024) { showToast(LANG.err_img_size, 'error'); return; }
                
                const reader = new FileReader();
                const avatarImg = document.getElementById('avatarImage');
                const originalSrc = avatarImg.src; 
                
                reader.onload = (event) => avatarImg.src = event.target.result;
                reader.readAsDataURL(file);
                
                const formData = new FormData();
                formData.append('avatar', file);
                formData.append('csrf_test_name', getCsrfToken());
                showToast(LANG.uploading, 'info');
                
                try {
                    const response = await fetch(BASE_URL + 'guru/akun-saya/upload-avatar', {
                        method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const result = await response.json();
                    
                    if(!response.ok || result.status !== 'success') throw new Error(result.message);
                    
                    avatarImg.src = result.new_avatar_url;
                    showToast(result.message, 'success'); 
                } catch (error) {
                    avatarImg.src = originalSrc;
                    showToast(error.message, 'error');
                }
            }
        });    
    }
});

// --- D. SIMPAN PREFERENSI SISTEM ---
async function savePreferences() {
    const btn = document.getElementById('btnSavePref');
    const text = document.getElementById('textSavePref'); // Menggunakan rentang elemen span jika ada
    
    if(btn) { 
        btn.disabled = true; 
        if(text) text.innerHTML = LANG.saving; 
        else btn.innerHTML = LANG.saving;
    }

    const formData = new FormData();
    formData.append('csrf_test_name', getCsrfToken());
    
    formData.append('notif_login', document.getElementById('tg_notif_login').classList.contains('active') ? 1 : 0);
    formData.append('two_factor', document.getElementById('tg_2fa').classList.contains('active') ? 1 : 0);
    formData.append('notif_email', document.getElementById('tg_notif_email').classList.contains('active') ? 1 : 0);
    formData.append('notif_sistem', document.getElementById('tg_notif_sistem').classList.contains('active') ? 1 : 0);
    formData.append('notif_update', document.getElementById('tg_notif_update').classList.contains('active') ? 1 : 0);
    
    formData.append('bahasa', document.getElementById('pref_bahasa').value);
    const themeChecked = document.querySelector('input[name="theme"]:checked');
    if(themeChecked) formData.append('theme', themeChecked.value);

    try {
        const response = await fetch(BASE_URL + 'guru/akun-saya/update-preferences', {
            method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const result = await response.json();
        
        if (!response.ok || result.status !== 'success') throw new Error(result.message);
        
        window.location.reload();
    } catch (error) {
        showToast(error.message, 'error');
        if(btn) { 
            btn.disabled = false; 
            if(text) text.innerHTML = LANG.save_pref; 
            else btn.innerHTML = LANG.save_pref;
        }
    }
}