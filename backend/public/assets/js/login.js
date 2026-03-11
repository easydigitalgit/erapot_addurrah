/**
 * File: public/assets/js/login.js
 * Berisi logika UI, SDK Element, dan Logic Login Multi-Role (Updated)
 */

// ==========================================
// 1. KONFIGURASI UI & SDK (BAGIAN DESIGN)
// ==========================================

const defaultConfig = {
    main_title: 'Rapor Digital SMPIT Ad Durrah',
    main_subtitle: 'Sistem Informasi Akademik & Karakter Terpadu',
    quote_text: '"Mendampingi tumbuh kembang akademik & akhlak anak"',
    form_title: 'Masuk ke Sistem Rapor Digital',
    form_subtitle: 'Silakan login menggunakan akun resmi sekolah',
    copyright_text: '© SMPIT Ad Durrah',
    primary_color: '#1F7A4D',
    background_color: '#E6F4EC',
    text_color: '#1F2937',
    surface_color: '#FFFFFF',
    accent_color: '#34A853'
};

let config = { ...defaultConfig };

// Element SDK integration
async function onConfigChange(newConfig) {
    // Update Text
    safeSetText('main-title', newConfig.main_title || defaultConfig.main_title);
    safeSetText('main-subtitle', newConfig.main_subtitle || defaultConfig.main_subtitle);
    safeSetText('quote-text', newConfig.quote_text || defaultConfig.quote_text);
    safeSetText('form-title', newConfig.form_title || defaultConfig.form_title);
    safeSetText('form-subtitle', newConfig.form_subtitle || defaultConfig.form_subtitle);
    safeSetText('copyright-text', newConfig.copyright_text || defaultConfig.copyright_text);

    // Apply colors
    const primary = newConfig.primary_color || defaultConfig.primary_color;
    const bg = newConfig.background_color || defaultConfig.background_color;
    const text = newConfig.text_color || defaultConfig.text_color;
    const surface = newConfig.surface_color || defaultConfig.surface_color;
    const accent = newConfig.accent_color || defaultConfig.accent_color;

    // Update branding gradient
    const brandingSection = document.querySelector('.branding-section');
    if (brandingSection) {
        brandingSection.style.background = `linear-gradient(160deg, ${primary} 0%, ${accent} 50%, ${primary} 100%)`;
    }

    // Update login button
    const loginBtn = document.getElementById('btn-login');
    if (loginBtn) {
        loginBtn.style.background = `linear-gradient(135deg, ${primary} 0%, ${accent} 100%)`;
    }

    // Update form section background
    const formSection = document.querySelector('.card-form');
    if (formSection && formSection.parentElement) {
        formSection.parentElement.style.backgroundColor = surface;
    }

    // Update text colors
    const formTitle = document.getElementById('form-title');
    if(formTitle) formTitle.style.color = text;
}

// Helper agar tidak error jika elemen tidak ada
function safeSetText(id, text) {
    const el = document.getElementById(id);
    if(el) el.textContent = text;
}

function mapToCapabilities(config) {
    return {
        recolorables: [
            { get: () => config.background_color, set: (v) => updateConfig('background_color', v) },
            { get: () => config.surface_color, set: (v) => updateConfig('surface_color', v) },
            { get: () => config.text_color, set: (v) => updateConfig('text_color', v) },
            { get: () => config.primary_color, set: (v) => updateConfig('primary_color', v) },
            { get: () => config.accent_color, set: (v) => updateConfig('accent_color', v) }
        ]
    };
}

function updateConfig(key, value) {
    config[key] = value;
    if (window.elementSdk) window.elementSdk.setConfig({ [key]: value });
}

// Initialize Element SDK
if (window.elementSdk) {
    window.elementSdk.init({
        defaultConfig,
        onConfigChange,
        mapToCapabilities,
        mapToEditPanelValues: (cfg) => new Map(Object.entries(cfg))
    });
}


// ==========================================
// 2. LOGIKA INTERAKSI DASAR (PASSWORD & TOAST)
// ==========================================

const passwordInput = document.getElementById('password');
const toggleBtn = document.getElementById('toggle-password');

// 1. SVG Eye Normal (Mata Terbuka - Saat password disembunyikan)
const iconEye = `<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>`;

// 2. SVG Eye Slash (Mata Dicoret dari Mas Zaidan - Saat password terlihat)
// Diubah ke fill="currentColor" dan viewBox diseuaikan agar sinkron dengan warna form
const iconEyeSlash = `<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor"><path d="m644-428-58-58q9-47-27-88t-93-32l-58-58q17-8 34.5-12t37.5-4q75 0 127.5 52.5T660-500q0 20-4 37.5T644-428Zm128 126-58-56q38-29 67.5-63.5T832-500q-50-101-143.5-160.5T480-720q-29 0-57 4t-55 12l-62-62q41-17 84-25.5t90-8.5q151 0 269 83.5T920-500q-23 59-60.5 109.5T772-302Zm20 246L624-222q-35 11-70.5 16.5T480-200q-151 0-269-83.5T40-500q21-53 53-98.5t73-81.5L56-792l56-56 736 736-56 56ZM222-624q-29 26-53 57t-41 67q50 101 143.5 160.5T480-280q20 0 39-2.5t39-5.5l-36-38q-11 3-21 4.5t-21 1.5q-75 0-127.5-52.5T300-500q0-11 1.5-21t4.5-21l-84-82Zm319 93Zm-151 75Z"/></svg>`;

// Password Toggle Event
if (toggleBtn && passwordInput) {
    toggleBtn.addEventListener('click', () => {
        // Cek apakah password sedang tersembunyi
        const isPassword = passwordInput.type === 'password';
        
        // Ubah Tipe Input
        passwordInput.type = isPassword ? 'text' : 'password';
        
        // Ganti icon (Jika teks terlihat pakai EyeSlash, jika tersembunyi pakai Eye)
        toggleBtn.innerHTML = isPassword ? iconEyeSlash : iconEye;
    });
}

// Toast Notification
function showToast(type, title, message) {
    const toast = document.getElementById('toast');
    const toastIcon = document.getElementById('toast-icon');
    const toastTitle = document.getElementById('toast-title');
    const toastMessage = document.getElementById('toast-message');

    if(!toast) return;

    const icons = {
        success: `<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>`,
        error: `<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>`,
        warning: `<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>`
    };

    const colors = {
        success: 'bg-emerald-500',
        error: 'bg-red-500',
        warning: 'bg-amber-500'
    };

    toastIcon.className = `w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 ${colors[type]}`;
    toastIcon.innerHTML = icons[type];
    toastTitle.textContent = title;
    toastMessage.textContent = message;

    toast.classList.remove('translate-x-full');
    toast.classList.add('translate-x-0');

    setTimeout(() => {
        toast.classList.remove('translate-x-0');
        toast.classList.add('translate-x-full');
    }, 4000);
}


// ==========================================
// 3. LOGIKA LOGIN UTAMA (FETCH & DYNAMIC ROLE)
// ==========================================

const loginForm = document.getElementById('login-form');
const btnLogin = document.getElementById('btn-login');
const modalRole = document.getElementById('modalPilihRole');
const roleContainer = document.getElementById('roleListContainer');

// Gunakan URL dari atribut body
const PROCESS_URL = document.body.getAttribute('data-process-url') || '/login/process';
const SESSION_URL = document.body.getAttribute('data-session-url') || '/auth/logincontroller/setRoleSession';

if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Validasi
        const usernameVal = document.getElementById('username').value;
        const passwordVal = document.getElementById('password').value;

        if (!usernameVal || !passwordVal) {
            showToast('warning', 'Peringatan', 'Mohon lengkapi Username dan Password');
            return;
        }
        
        // UI Loading
        const originalBtnContent = btnLogin.innerHTML;
        btnLogin.disabled = true;
        btnLogin.innerHTML = `<svg class="w-5 h-5 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;

        const formData = new FormData(loginForm);

        try {
            // Panggil Controller process()
            const response = await fetch(PROCESS_URL, {
                method: 'POST',
                body: formData,
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json' 
                }
            });

            const result = await response.json();

            if (result.status === 'error') {
                showToast('error', 'Gagal Masuk', result.message);
                btnLogin.disabled = false;
                btnLogin.innerHTML = originalBtnContent;
            } 
            else if (result.status === 'success') {
                showToast('success', 'Berhasil', 'Login berhasil, mengalihkan...');
                setTimeout(() => {
                    window.location.href = result.redirect;
                }, 1000);
            } 
            else if (result.status === 'multi_role') {
                // Munculkan Modal Pilihan jika role > 1
                showRoleSelection(result.roles, result.user_id);
                // Kembalikan tombol ke kondisi semula tapi tetap disabled agar fokus ke modal
                btnLogin.innerHTML = originalBtnContent; 
            }

        } catch (error) {
            console.error('Fetch Error:', error);
            showToast('error', 'Kesalahan Sistem', 'Gagal menghubungi server. Cek koneksi internet.');
            btnLogin.disabled = false;
            btnLogin.innerHTML = originalBtnContent;
        
        }
    });
}

// Fungsi Menampilkan Modal Role
function showRoleSelection(roles, userId) {
    if(!roleContainer) return;
    roleContainer.innerHTML = ''; 

    roles.forEach(role => {
        // Icon Emoji Simple sesuai Role Key
        let iconEmoji = '👤';
        if (role.key === 'admin') iconEmoji = '👨‍💼';
        if (role.key === 'guru') iconEmoji = '👨‍🏫';
        if (role.key === 'wali_kelas') iconEmoji = '🏫';
        if (role.key === 'guru_tahfidz') iconEmoji = '📖'; // <-- Tambahkan baris ini
        if (role.key === 'siswa') iconEmoji = '🎓';
        if (role.key === 'orang_tua') iconEmoji = '👨‍👩‍👧';

        const btn = document.createElement('button');
        btn.className = "w-full flex items-center justify-between p-4 bg-white border border-gray-200 rounded-xl hover:border-[<?= $color['warna_primary'] ?>] hover:bg-[<?=  $color['warna_secondary'] ?>] hover:shadow-md transition-all group duration-200 text-left mb-2";
        
        btn.innerHTML = `
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-[var(--warna-secondary)] flex items-center justify-center text-xl shadow-inner group-hover:scale-110 transition-transform">
                    ${iconEmoji}
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 group-hover:text-[var(--warna-primary)] text-base">${role.label}</h4>
                    <p class="text-xs text-gray-500">Masuk sebagai ${role.label}</p>
                </div>
            </div>
            <div class="text-gray-300 group-hover:text-[var(--warna-primary)] transform group-hover:translate-x-1 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        `;
        // Pass 'role.key' ke fungsi finalize
        btn.onclick = () => finalizeRoleLogin(userId, role.key);
        roleContainer.appendChild(btn);
    });

    if(modalRole) {
        modalRole.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent scroll saat modal aktif
    }
}

function closeRoleModal() {
    if(modalRole) modalRole.classList.add('hidden');
    document.body.style.overflow = '';
    
    // Reset tombol login utama
    if(btnLogin) {
        btnLogin.disabled = false;
    }
}

async function finalizeRoleLogin(userId, roleKey) {
    // Tampilan Loading di dalam Modal
    roleContainer.innerHTML = `
        <div class="flex flex-col items-center justify-center py-8 text-[var(--warna-primary)] animate-pules">
            <svg class="w-10 h-10 animate-spin mb-3" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <p class="font-medium">Menyiapkan dashboard...</p>
        </div>
    `;

    const formData = new FormData();
    formData.append('user_id', userId);
    formData.append('role_key', roleKey); // Kirim Role Key yang dipilih

    try {
        const response = await fetch(SESSION_URL, { 
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const result = await response.json();

        if (result.status === 'success') {
            window.location.href = result.redirect;
        } else {
            showToast('error', 'Gagal', result.message);
            closeRoleModal(); // Tutup modal biar user bisa coba lagi
        }
    } catch (error) {
        console.error(error);
        showToast('error', 'Error', 'Gagal memproses sesi role.');
        closeRoleModal();
    }
}

// ==========================================
// 4. LOGIKA LUPA PASSWORD (MODAL & PROSES)
// ==========================================

const modalLupaPassword = document.getElementById('modalLupaPassword');

// Menampilkan Modal Lupa Password
window.showForgotPasswordModal = function() {
    const inputUsername = document.getElementById('username').value;
    
    // Validasi Cerdas: Pastikan username sudah diisi sebelum memilih metode
    if (!inputUsername || inputUsername.trim() === '') {
        showToast('warning', 'Perhatian', 'Silakan ketik Username/Email Anda terlebih dahulu di form login sebelum menekan Lupa Password.');
        document.getElementById('username').focus();
        return;
    }

    if (modalLupaPassword) {
        modalLupaPassword.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

// Menutup Modal Lupa Password
window.closeForgotPasswordModal = function() {
    if (modalLupaPassword) {
        modalLupaPassword.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

// Proses Pemilihan Reset Password
window.processResetPassword = async function(method) {
    const inputUsername = document.getElementById('username').value;
    const loginForm = document.getElementById('login-form'); // Ambil form untuk dapet CSRF Token
    
    // Tutup modal
    closeForgotPasswordModal();
    
    // Set UI Login button ke mode loading
    const btnLogin = document.getElementById('btn-login');
    const originalBtnContent = btnLogin.innerHTML;
    btnLogin.disabled = true;
    btnLogin.innerHTML = `<svg class="w-5 h-5 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengirim Link...`;

    try {
        // PERBAIKAN 1: Gunakan FormData dari form login asli agar CSRF Token otomatis ikut!
        const formData = new FormData(loginForm);
        formData.append('method', method); // Tambahkan data 'whatsapp' atau 'email'

        // PERBAIKAN 2: Dinamiskan URL menyesuaikan localhost/raporsmpit
        const processUrl = document.body.getAttribute('data-process-url');
        const resetUrl = processUrl.replace('/login/process', '/auth/lupa-password/proses');

        // TEMBAK KE BACKEND
        const response = await fetch(resetUrl, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        
        // PERBAIKAN 3: Baca sebagai teks dulu untuk menangkap error HTML CodeIgniter jika gagal
        const textResponse = await response.text();
        
        let result;
        try {
            result = JSON.parse(textResponse);
        } catch (parseError) {
            console.error("ERROR SERVER (Buka F12 -> Network / Console):", textResponse);
            throw new Error("Gagal terhubung ke SMTP Email. Pastikan koneksi internet aktif dan setting .env (Sandi Aplikasi) sudah benar.");
        }
        
        btnLogin.disabled = false;
        btnLogin.innerHTML = originalBtnContent;

        if (result.status === 'success') {
            showToast('success', 'Berhasil Dikirim', result.message);
        } else if (result.status === 'warning') {
            showToast('warning', 'Info', result.message);
        } else {
            showToast('error', 'Gagal', result.message);
        }

    } catch (error) {
        console.error(error);
        btnLogin.disabled = false;
        btnLogin.innerHTML = originalBtnContent;
        showToast('error', 'Pemberitahuan', error.message || 'Terjadi kesalahan sistem saat meminta reset password.');
    }
}