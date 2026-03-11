document.addEventListener('DOMContentLoaded', () => {
    // 1. Avatar Upload Logic
    const avatarInput = document.getElementById('avatarUpload');
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validasi Ukuran (Max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                Swal.fire('Error', LANG.err_img_size, 'error');
                this.value = '';
                return;
            }

            const formData = new FormData();
            formData.append('avatar', file);

            // Karena kita menggunakan CSRF protection, ambil token dari meta tag
            const csrfTokenMeta = document.querySelector('meta[name="X-CSRF-TOKEN"]');
            if (csrfTokenMeta) {
                formData.append('csrf_test_name', csrfTokenMeta.content);
            }

            Swal.fire({
                title: LANG.uploading,
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            // Endpoint ini perlu disesuaikan dengan Controller Siswa Anda yang menangani upload foto
            fetch(`${BASE_URL}/siswa/update-foto`, { 
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    // Update meta token untuk next request (Penting di CI4)
                    if(data.token && csrfTokenMeta) csrfTokenMeta.content = data.token;
                    
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        document.getElementById('avatarImage').src = e.target.result;
                    };
                    reader.readAsDataURL(file);

                    Swal.fire({
                        icon: 'success',
                        title: LANG.success,
                        text: LANG.photo_updated,
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    if(data.token && csrfTokenMeta) csrfTokenMeta.content = data.token;
                    Swal.fire('Error', data.message || LANG.failed, 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', LANG.server_error, 'error');
            });
        });
    }

    // 2. Change Password Submit
    const passwordForm = document.getElementById('changePasswordForm');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const oldPass = document.getElementById('oldPassword').value;
            const newPass = document.getElementById('newPassword').value;
            const confPass = document.getElementById('confirmPassword').value;

            if (newPass.length < 8) {
                Swal.fire('Warning', LANG.err_pwd_len, 'warning');
                return;
            }
            if (newPass !== confPass) {
                Swal.fire('Warning', LANG.err_pwd_match, 'warning');
                return;
            }

            const btn = document.getElementById('btnSavePassword');
            const text = document.getElementById('textSavePassword');
            
            btn.disabled = true;
            text.innerHTML = `<i class="fas fa-spinner animate-spin mr-2"></i> ${LANG.saving}`;

            const formData = new FormData();
            formData.append('old_password', oldPass);
            formData.append('new_password', newPass);

            const csrfTokenMeta = document.querySelector('meta[name="X-CSRF-TOKEN"]');
            if (csrfTokenMeta) {
                formData.append('csrf_test_name', csrfTokenMeta.content);
            }

            // Endpoint ini perlu dibuat di controller Siswa
            fetch(`${BASE_URL}/siswa/update-password`, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if(data.token && csrfTokenMeta) csrfTokenMeta.content = data.token;

                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: LANG.success,
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        closePasswordModal();
                        passwordForm.reset();
                    });
                } else {
                    Swal.fire('Error', data.message || LANG.failed, 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', LANG.server_error, 'error');
            })
            .finally(() => {
                btn.disabled = false;
                text.innerText = LANG.btn_change_pass;
            });
        });
    }

    // 3. Password Strength Checker
    const newPasswordInput = document.getElementById('newPassword');
    if (newPasswordInput) {
        newPasswordInput.addEventListener('input', function() {
            const val = this.value;
            let score = 0;
            if(val.length > 5) score++;
            if(val.length > 7) score++;
            if(/[A-Z]/.test(val)) score++;
            if(/[0-9]/.test(val)) score++;
            
            const bars = [
                document.getElementById('strength-bar-1'),
                document.getElementById('strength-bar-2'),
                document.getElementById('strength-bar-3'),
                document.getElementById('strength-bar-4')
            ];
            const text = document.getElementById('password-strength-text');
            
            bars.forEach((bar, idx) => {
                if(bar) {
                    if(idx < score) {
                        bar.className = `h-full w-1/4 rounded-full transition-all duration-300 ${score < 2 ? 'bg-red-500' : score < 3 ? 'bg-orange-500' : 'bg-emerald-500'}`;
                    } else {
                        bar.className = 'h-full w-1/4 bg-gray-200 dark:bg-slate-600 rounded-full transition-all duration-300';
                    }
                }
            });
            
            if(text) {
                if(val.length === 0) {
                    text.innerText = '';
                } else {
                    text.innerText = score < 2 ? LANG.pass_weak : score < 3 ? LANG.pass_medium : LANG.pass_strong;
                    text.className = `text-xs font-bold w-16 text-right transition-colors ${score < 2 ? 'text-red-500' : score < 3 ? 'text-orange-500' : 'text-emerald-500'}`;
                }
            }
        });
    }
});

// Modals Function
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

// System Preferences Submit
function savePreferences() {
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
    if (csrfTokenMeta) {
        formData.append('csrf_test_name', csrfTokenMeta.content);
    }

    // Endpoint ini perlu dibuat di controller Siswa
    fetch(`${BASE_URL}/siswa/update-prefs`, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if(data.token && csrfTokenMeta) csrfTokenMeta.content = data.token;
        
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: LANG.success,
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire('Error', data.message || LANG.failed, 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', LANG.server_error, 'error');
    })
    .finally(() => {
        btn.disabled = false;
        text.innerText = originalText;
    });
}