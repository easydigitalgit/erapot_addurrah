document.addEventListener('DOMContentLoaded', () => {

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

            fetch(`${BASE_URL}/tahfidz/akun-saya/update`, {
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

            fetch(`${BASE_URL}/tahfidz/akun-saya/upload-avatar`, { 
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

            fetch(`${BASE_URL}/tahfidz/akun-saya/update-password`, {
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

// 4. SIMPAN PREFERENSI (TEMA & BAHASA)
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

    fetch(`${BASE_URL}/tahfidz/akun-saya/update-preferences`, {
        method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if(data.token && csrfTokenMeta) csrfTokenMeta.content = data.token;
        if (data.status === 'success') {
            Swal.fire({icon: 'success', title: LANG.success, text: data.message, timer: 1500, showConfirmButton: false}).then(() => {
                window.location.reload(); // Wajib reload agar tema & bahasa baru diterapkan
            });
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    })
    .catch(err => Swal.fire('Error', LANG.server_error, 'error'))
    .finally(() => { btn.disabled = false; text.innerText = originalText; });
};