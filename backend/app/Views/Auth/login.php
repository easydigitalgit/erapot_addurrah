<?php
// AMBIL DATA IDENTITAS SEKOLAH SECARA GLOBAL
$sekolahData = function_exists('get_identitas_sekolah') ? get_identitas_sekolah() : [];
$nama_sekolah = $sekolahData['nama_sekolah'] ?? 'SMPIT Ad Durrah';
$logo_db = $sekolahData['logo'] ?? 'default_logo.png';
$logo_url = ($logo_db !== 'default_logo.png' && $logo_db !== '') ? base_url('uploads/logo/' . $logo_db) : null;
?>
<!doctype html>
<html lang="id" class="h-full">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - <?= esc($nama_sekolah) ?></title>
<script src="https://cdn.tailwindcss.com"></script>
<script src="/_sdk/element_sdk.js"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= base_url("/assets/css/login.css") ?>">
<script src="/_sdk/data_sdk.js" type="text/javascript"></script>

<style>
    /* Animasi smooth untuk modal */
    .animate-fade-in { animation: fadeIn 0.3s ease-out; }
    .animate-scale-up { animation: scaleUp 0.3s ease-out; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes scaleUp { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    
    #toast {
        transition: transform 0.3s ease-in-out;
        will-change: transform;
    }
    .translate-x-full { transform: translateX(100%); }
    .translate-x-0 { transform: translateX(0); }
    
    /* MENGIKAT VARIABEL CSS KE DATABASE */
    :root {
        --warna-primary: <?= $color['warna_primary'] ?>;
        --warna-secondary: <?= $color['warna_secondary'] ?>;
    }
</style>
</head>

<body class="h-full " 
    data-process-url="<?= base_url('/login/process') ?>"
    data-session-url="<?= base_url('/auth/logincontroller/setRoleSession') ?>">
    
<div class="login-wrapper h-full w-full flex flex-col lg:flex-row overflow-auto">
    
<div class="branding-section hidden lg:flex w-full lg:w-1/2 p-8 lg:p-12 flex-col justify-center items-center text-white relative lg:min-h-0 bg-gradient-to-br from-[<?= $color['warna_primary'] ?>]/90 to-[<?= $color['warna_primary'] ?>]/70">
    <div class="islamic-pattern"></div>
    <div class="relative z-10 text-center max-w-md">
    <div class="mb-6 lg:mb-8">
    
    <div class="w-20 h-20 lg:w-28 lg:h-28 mx-auto bg-white rounded-2xl shadow-lg flex items-center justify-center p-2 overflow-hidden">
        <?php if ($logo_url): ?>
            <img src="<?= $logo_url ?>" alt="Logo Sekolah" class="w-full h-full object-contain">
        <?php else: ?>
            <svg viewBox="0 0 100 100" class="w-full h-full p-2"> 
                <path d="M50 15 C30 15 20 35 20 45 L20 70 L80 70 L80 45 C80 35 70 15 50 15Z" fill="<?= $color['warna_primary'] ?>" /> 
                <circle cx="50" cy="10" r="6" fill="<?= $color['warna_primary'] ?>" /> 
                <circle cx="53" cy="10" r="4" fill="<?= $color['warna_primary'] ?>" /> 
                <rect x="30" y="55" width="40" height="25" rx="2" fill="<?= $color['warna_secondary'] ?>" /> 
                <line x1="50" y1="55" x2="50" y2="80" stroke="<?= $color['warna_primary'] ?>" stroke-width="2" /> 
                <path d="M32 60 Q40 65 48 60" fill="none" stroke="<?= $color['warna_primary'] ?>" stroke-width="1.5" /> 
                <path d="M52 60 Q60 65 68 60" fill="none" stroke="<?= $color['warna_primary'] ?>" stroke-width="1.5" /> 
            </svg>
        <?php endif; ?>
    </div>
    
    </div>
    <h1 id="main-title" class="text-2xl lg:text-3xl xl:text-4xl font-bold mb-3 leading-tight">Rapor Digital <?= esc($nama_sekolah) ?></h1>
    <p id="main-subtitle" class="text-base lg:text-lg text-white/90 mb-8 lg:mb-10">Sistem Informasi Akademik &amp; Karakter Terpadu</p>
    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 lg:p-5">
    <p id="quote-text" class="text-sm lg:text-base italic text-white/90">"Mendampingi tumbuh kembang akademik &amp; akhlak anak"</p>
    </div>
    </div>
</div>

<div class="w-full lg:w-1/2 p-6 lg:p-12 flex flex-col justify-center items-center bg-white">
    <div class="card-form w-full max-w-md">
    <div class="text-center mb-8">
    <h2 id="form-title" class="text-2xl lg:text-3xl font-bold text-gray-800 mb-2 dark:b">Masuk ke Dashboard</h2>
    <p id="form-subtitle" class="text-gray-500">Silakan login menggunakan akun resmi sekolah</p>
    </div>

    <div id="alert-container" class="hidden mb-5 p-4 rounded shadow-sm flex items-start"></div>

    <form id="login-form" class="space-y-5">
    <?= csrf_field() ?>

    <div>
    <label for="username" class="block text-sm font-medium text-gray-700 mb-2"> Username / Email </label>
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
        </div>
        <input type="text" id="username" name="username" class="form-input w-full pl-12 pr-4 py-3.5 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 outline-none focus:ring-2 focus:border-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>]/50 transition-all" placeholder="Masukkan username atau email" required>
    </div>
    </div>

    <div>
    <label for="password" class="block text-sm font-medium text-gray-700 mb-2"> Password </label>
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
        </div>
        <input type="password" id="password" name="password" class="form-input w-full pl-12 pr-12 py-3.5 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 outline-none focus:ring-1 focus:border-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>]/50 transition-all" placeholder="Masukkan password" required>
        <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 cursor-pointer z-10">
        <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
        </button>
    </div>
    </div>

    <div class="hidden">
    <select id="role" name="role"><option value="auto">Auto</option></select>
    </div>

    <div class="flex items-center justify-between">
    <label class="flex items-center cursor-pointer group">
        <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded accent-[<?= $color['warna_primary'] ?>]/80 border-gray-300 text-[<?= $color['warna_primary'] ?>] focus:ring-[<?= $color['warna_primary'] ?>]/80 cursor-pointer">
        <span class="ml-2 text-sm text-gray-600 group-hover:text-gray-800 transition-colors"> Ingat saya </span>
    </label>
    <a href="javascript:void(0)" onclick="showForgotPasswordModal()" class="text-sm text-[<?= $color['warna_primary'] ?>]/80 hover:text-[<?= $color['warna_primary'] ?>] font-medium transition-colors"> Lupa Password? </a>
    </div>

    <button type="submit" id="btn-login" class="w-full py-4 rounded-xl bg-[<?= $color['warna_primary'] ?>]/90 hover:bg-[<?= $color['warna_primary'] ?>] text-white font-semibold text-lg flex items-center justify-center gap-2 mt-6 transition-colors shadow-lg shadow-[<?= $color['warna_primary'] ?>]/20 cursor-pointer">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" /></svg>
    <span>Masuk</span>
    </button>
    </form>

    <div class="mt-8 pt-6 border-t border-gray-100 text-center">
    <p id="copyright-text" class="text-sm text-gray-500 mb-1">© <?= esc($nama_sekolah) ?></p>
    <p class="text-xs text-gray-400">Powered by Sistem Rapor Digital</p>
    </div>
    </div>
</div>
</div>

<div id="toast" class="fixed top-4 right-4 transform translate-x-full transition-transform duration-300 z-50">
<div class="bg-white rounded-xl shadow-lg border border-gray-100 p-4 flex items-center gap-3 max-w-sm">
    <div id="toast-icon" class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"></div>
    <div>
    <p id="toast-title" class="font-semibold text-gray-800"></p>
    <p id="toast-message" class="text-sm text-gray-500"></p>
    </div>
</div>
</div>

<div id="modalPilihRole" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity animate-fade-in" onclick="closeRoleModal()"></div>
    
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl relative z-10 overflow-hidden animate-scale-up m-4">
        <div class="bg-[<?= $color['warna_primary'] ?>] p-6 text-center">
            <h3 class="text-white text-xl font-bold font-plus-jakarta">Pilih Akses Masuk</h3>
            <p class="text-emerald-100 text-sm mt-1">Email ini terhubung ke beberapa akun</p>
        </div>

        <div class="p-4 bg-gray-50 border-b border-gray-100">
            <p class="text-xs text-gray-500 text-center uppercase tracking-wider">Silakan pilih salah satu</p>
        </div>
        <div class="p-4 space-y-3 max-h-[50vh] overflow-y-auto" id="roleListContainer">
            </div>

        <div class="p-4 bg-white text-center border-t border-gray-100">
            <button type="button" onclick="closeRoleModal()" class="text-gray-400 hover:text-red-500 text-sm font-medium transition-colors flex items-center justify-center gap-2 w-full">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Batal Login
            </button>
        </div>
    </div>
</div>

<div id="modalLupaPassword" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity animate-fade-in" onclick="closeForgotPasswordModal()"></div>
    
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl relative z-10 overflow-hidden animate-scale-up m-4">
        <div class="bg-[<?= $color['warna_primary'] ?>] p-6 text-center">
            <h3 class="text-white text-xl font-bold font-plus-jakarta">Lupa Password?</h3>
            <p class="text-emerald-100 text-sm mt-1">Pilih metode untuk menerima link reset password</p>
        </div>

        <div class="p-6 space-y-4">
            <button onclick="processResetPassword('whatsapp')" class="w-full flex items-center gap-4 p-4 border border-gray-200 rounded-xl hover:border-green-500 hover:bg-green-50 transition-all group text-left">
                <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.327.101.144.447.706.953 1.12.653.535 1.204.708 1.348.765.144.057.228.047.315-.054l.36-.453c.116-.145.242-.121.378-.072.136.051.865.405.981.462.115.058.192.087.219.135.027.048.027.28-.117.685z"/></svg>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Via WhatsApp</h4>
                    <p class="text-xs text-gray-500">Link akan dikirim ke nomor HP terdaftar</p>
                </div>
            </button>

            <button onclick="processResetPassword('email')" class="w-full flex items-center gap-4 p-4 border border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition-all group text-left">
                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800">Via Email</h4>
                    <p class="text-xs text-gray-500">Link akan dikirim ke email terdaftar</p>
                </div>
            </button>
        </div>

        <div class="p-4 bg-gray-50 text-center border-t border-gray-100">
            <button type="button" onclick="closeForgotPasswordModal()" class="text-gray-500 hover:text-gray-800 text-sm font-medium transition-colors">
                Kembali ke Login
            </button>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/js/login.js') ?>"></script>

</body>
</html>