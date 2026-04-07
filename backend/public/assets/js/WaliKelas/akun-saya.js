document.addEventListener('DOMContentLoaded', () => {

    // --- TOGGLE PASSWORD VISIBILITY ---
    const togglePasswordBtns = document.querySelectorAll('.toggle-password');
    togglePasswordBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.previousElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                // Ubah icon jadi mata disilang (Eye-slash)
                this.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                </svg>`;
                this.classList.add('text-gray-700', 'dark:text-gray-200');
            } else {
                input.type = 'password';
                // Ubah icon kembali ke mata normal (Eye)
                this.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>`;
                this.classList.remove('text-gray-700', 'dark:text-gray-200');
            }
        });
    });

    // 1. UPDATE DATA PRIBADI (INFO PROFIL)
    const personalForm = document.getElementById('personalInfoForm');
    if (personalForm) {
        personalForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSaveInfo');
            const text = document.getElementById('textSaveInfo');
            const originalText = text.innerText;
            
            btn.disabled = true;
            text.innerHTML = `<i class="fas fa-spinner animate-spin mr-2"></i> ${LANG.saving}`;

            const formData = new FormData(this);
            const csrfTokenMeta = document.querySelector('meta[name="X-CSRF-TOKEN"]');
            if (csrfTokenMeta) formData.append('csrf_test_name', csrfTokenMeta.content);

            // UBAH KE URL WALIKELAS
            fetch(`${BASE_URL}/wali/akun-saya/update`, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if(data.token && csrfTokenMeta) csrfTokenMeta.content = data.token;
                if (data.status === 'success') {
                    Swal.fire({icon: 'success', title: LANG.success, text: data.message, timer: 1500, showConfirmButton: false});
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(err => Swal.fire('Error', LANG.server_error, 'error'))
            .finally(() => {
                btn.disabled = false;
                text.innerText = originalText;
            });
        });
    }

    // 2. UPLOAD FOTO PROFIL
    const avatarInput = document.getElementById('avatarUpload');
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                Swal.fire('Error', LANG.err_img_size, 'error');
                this.value = '';
                return;
            }

            const formData = new FormData();
            formData.append('avatar', file);

            const csrfTokenMeta = document.querySelector('meta[name="X-CSRF-TOKEN"]');
            if (csrfTokenMeta) formData.append('csrf_test_name', csrfTokenMeta.content);

            Swal.fire({ title: LANG.uploading, allowOutsideClick: false, didOpen: () => Swal.showLoading() });

            // UBAH KE URL WALIKELAS
            fetch(`${BASE_URL}/wali/akun-saya/upload-avatar`, { 
                method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if(data.token && csrfTokenMeta) csrfTokenMeta.content = data.token;
                if (data.status === 'success') {
                    const reader = new FileReader();
                    reader.onload = (e) => { document.getElementById('avatarImage').src = e.target.result; };
                    reader.readAsDataURL(file);
                    Swal.fire({icon: 'success', title: LANG.success, text: LANG.photo_updated, timer: 1500, showConfirmButton: false});
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(err => Swal.fire('Error', LANG.server_error, 'error'));
        });
    }

    // 3. GANTI PASSWORD
    const passwordForm = document.getElementById('changePasswordForm');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const oldPass = document.getElementById('oldPassword').value;
            const newPass = document.getElementById('newPassword').value;
            const confPass = document.getElementById('confirmPassword').value;

            if (newPass.length < 8) return Swal.fire('Warning', LANG.err_pwd_len, 'warning');
            if (newPass !== confPass) return Swal.fire('Warning', LANG.err_pwd_match, 'warning');

            const btn = document.getElementById('btnSavePassword');
            const text = document.getElementById('textSavePassword');
            btn.disabled = true; text.innerHTML = `<i class="fas fa-spinner animate-spin mr-2"></i> ${LANG.saving}`;

            const formData = new FormData();
            formData.append('old_password', oldPass);
            formData.append('new_password', newPass);

            const csrfTokenMeta = document.querySelector('meta[name="X-CSRF-TOKEN"]');
            if (csrfTokenMeta) formData.append('csrf_test_name', csrfTokenMeta.content);

            // UBAH KE URL WALIKELAS
            fetch(`${BASE_URL}/wali/akun-saya/update-password`, {
                method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if(data.token && csrfTokenMeta) csrfTokenMeta.content = data.token;
                if (data.status === 'success') {
                    Swal.fire({icon: 'success', title: LANG.success, text: data.message, timer: 1500, showConfirmButton: false}).then(() => {
                        closePasswordModal(); passwordForm.reset();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(err => Swal.fire('Error', LANG.server_error, 'error'))
            .finally(() => { btn.disabled = false; text.innerText = LANG.btn_change_pass; });
        });
    }
});

// Fungsi Toggle Modals
function showChangePasswordModal() {
    const modal = document.getElementById('passwordModal');
    if (modal) {
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.querySelector('div').classList.remove('scale-95');
        document.body.style.overflow = 'hidden';
    }
}
function closePasswordModal() {
    const modal = document.getElementById('passwordModal');
    if (modal) {
        modal.classList.add('opacity-0', 'pointer-events-none');
        modal.querySelector('div').classList.add('scale-95');
        document.body.style.overflow = '';
    }
}

// 4. SIMPAN PREFERENSI (TEMA & BAHASA) DENGAN SINKRONISASI LOCALSTORAGE
window.savePreferences = function() {
    const btn = document.getElementById('btnSavePref');
    const text = document.getElementById('textSavePref');
    const originalText = text.innerText;
    
    btn.disabled = true;
    text.innerHTML = `<i class="fas fa-spinner animate-spin mr-2"></i> ${LANG.saving}`;

    const theme = document.querySelector('input[name="theme"]:checked').value;
    const lang = document.getElementById('pref_bahasa').value;

    const formData = new FormData();
    formData.append('theme', theme);
    formData.append('bahasa', lang);

    const csrfTokenMeta = document.querySelector('meta[name="X-CSRF-TOKEN"]');
    if (csrfTokenMeta) formData.append('csrf_test_name', csrfTokenMeta.content);

    // UBAH KE URL WALIKELAS
    fetch(`${BASE_URL}/wali/akun-saya/update-preferences`, {
        method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if(data.token && csrfTokenMeta) csrfTokenMeta.content = data.token;
        if (data.status === 'success') {
            
            // Simpan ke localStorage agar tidak reset saat direload
            localStorage.setItem('theme', theme); 
            if(theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            Swal.fire({icon: 'success', title: LANG.success, text: data.message, timer: 1500, showConfirmButton: false}).then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(err => Swal.fire('Error', LANG.server_error, 'error'))
    .finally(() => { btn.disabled = false; text.innerText = originalText; });
};