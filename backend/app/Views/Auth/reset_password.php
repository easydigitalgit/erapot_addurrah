<?php
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
    <title>Buat Password Baru - <?= esc($nama_sekolah) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f3f4f6; }
        .form-input { border: 1px solid #e5e7eb; transition: all 0.2s; }
        .form-input:focus { border-color: <?= $color['warna_primary'] ?>; box-shadow: 0 0 0 3px <?= $color['warna_primary'] ?>20; }
        
        #toast { transition: transform 0.3s ease-in-out; will-change: transform; }
        .translate-x-full { transform: translateX(100%); }
        .translate-x-0 { transform: translateX(0); }
    </style>
</head>

<body class="h-full flex items-center justify-center p-4 relative">
    
    <div class="bg-white w-full max-w-md rounded-3xl shadow-xl overflow-hidden border border-gray-100 relative z-10">
        <div class="bg-[<?= $color['warna_primary'] ?>] p-8 text-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 20px 20px;"></div>
            <div class="w-16 h-16 bg-white rounded-2xl mx-auto flex items-center justify-center shadow-lg relative z-10 mb-4 p-2">
                <?php if ($logo_url): ?>
                    <img src="<?= $logo_url ?>" alt="Logo" class="w-full h-full object-contain">
                <?php else: ?>
                    <span class="text-[<?= $color['warna_primary'] ?>] font-bold text-xl">Rapor</span>
                <?php endif; ?>
            </div>
            <h2 class="text-white text-2xl font-bold relative z-10">Buat Password Baru</h2>
            <p class="text-white/80 text-sm mt-2 relative z-10">Silakan masukkan kombinasi password baru Anda.</p>
        </div>

        <div class="p-8">
            <form id="reset-form" class="space-y-6">
                <input type="hidden" name="token" value="<?= $token ?>">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                    <div class="relative">
                        <input type="password" id="new_password" name="new_password" class="form-input w-full pl-4 pr-12 py-3.5 rounded-xl bg-gray-50 text-gray-800 outline-none" placeholder="Minimal 8 karakter" required>
                        <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[<?= $color['warna_primary'] ?>] transition-colors toggle-password" data-target="new_password">
                            <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </button>
                    </div>
                    <div class="mt-2 flex gap-1 h-1.5 w-full rounded-full overflow-hidden bg-gray-200">
                        <div id="bar-1" class="h-full w-1/4 transition-colors duration-300"></div>
                        <div id="bar-2" class="h-full w-1/4 transition-colors duration-300"></div>
                        <div id="bar-3" class="h-full w-1/4 transition-colors duration-300"></div>
                        <div id="bar-4" class="h-full w-1/4 transition-colors duration-300"></div>
                    </div>
                    <p id="strength-text" class="text-xs text-gray-500 mt-1.5 text-right transition-colors duration-300">Kekuatan password</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <input type="password" id="confirm_password" name="confirm_password" class="form-input w-full pl-4 pr-12 py-3.5 rounded-xl bg-gray-50 text-gray-800 outline-none" placeholder="Ketik ulang password baru" required>
                        <button type="button" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-[<?= $color['warna_primary'] ?>] transition-colors toggle-password" data-target="confirm_password">
                            <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </button>
                    </div>
                    <p id="match-text" class="text-xs mt-1.5 text-right hidden"></p>
                </div>

                <button type="submit" id="btn-submit" class="w-full py-4 rounded-xl bg-[<?= $color['warna_primary'] ?>] hover:brightness-95 text-white font-semibold text-lg flex items-center justify-center gap-2 mt-2 transition-all shadow-lg cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                    <span>Simpan Password Baru</span>
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <a href="<?= base_url('login') ?>" class="text-sm font-medium text-gray-500 hover:text-[<?= $color['warna_primary'] ?>] transition-colors">Batal & Kembali ke Login</a>
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

    <script>
        // --- 1. ICON MATA (SHOW/HIDE PASSWORD) ---
        const iconEye = `<svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>`;
        const iconEyeSlash = `<svg class="w-5 h-5 eye-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" fill="currentColor"><path d="m644-428-58-58q9-47-27-88t-93-32l-58-58q17-8 34.5-12t37.5-4q75 0 127.5 52.5T660-500q0 20-4 37.5T644-428Zm128 126-58-56q38-29 67.5-63.5T832-500q-50-101-143.5-160.5T480-720q-29 0-57 4t-55 12l-62-62q41-17 84-25.5t90-8.5q151 0 269 83.5T920-500q-23 59-60.5 109.5T772-302Zm20 246L624-222q-35 11-70.5 16.5T480-200q-151 0-269-83.5T40-500q21-53 53-98.5t73-81.5L56-792l56-56 736 736-56 56ZM222-624q-29 26-53 57t-41 67q50 101 143.5 160.5T480-280q20 0 39-2.5t39-5.5l-36-38q-11 3-21 4.5t-21 1.5q-75 0-127.5-52.5T300-500q0-11 1.5-21t4.5-21l-84-82Zm319 93Zm-151 75Z"/></svg>`;

        document.querySelectorAll('.toggle-password').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const inputEl = document.getElementById(targetId);
                
                if (inputEl.type === 'password') {
                    inputEl.type = 'text';
                    this.innerHTML = iconEyeSlash;
                } else {
                    inputEl.type = 'password';
                    this.innerHTML = iconEye;
                }
            });
        });

        // --- 2. PASSWORD STRENGTH METER ---
        const newPassInput = document.getElementById('new_password');
        const confirmPassInput = document.getElementById('confirm_password');
        const matchText = document.getElementById('match-text');
        
        const bars = [
            document.getElementById('bar-1'),
            document.getElementById('bar-2'),
            document.getElementById('bar-3'),
            document.getElementById('bar-4')
        ];
        const strengthText = document.getElementById('strength-text');

        newPassInput.addEventListener('input', function() {
            const val = this.value;
            let score = 0;
            
            // Hitung skor kekuatan
            if (val.length > 4) score += 1;
            if (val.length >= 8) score += 1;
            if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score += 1; // Huruf besar & kecil
            if (/[0-9]/.test(val) && /[^A-Za-z0-9]/.test(val)) score += 1; // Angka & Simbol

            // Reset warna bar
            bars.forEach(bar => bar.className = 'h-full w-1/4 transition-colors duration-300');

            if (val.length === 0) {
                strengthText.textContent = "Kekuatan password";
                strengthText.className = "text-xs text-gray-500 mt-1.5 text-right";
            } else if (score === 1) {
                bars[0].classList.add('bg-red-500');
                strengthText.textContent = "Sangat Lemah";
                strengthText.className = "text-xs text-red-500 mt-1.5 text-right font-medium";
            } else if (score === 2) {
                bars[0].classList.add('bg-amber-400');
                bars[1].classList.add('bg-amber-400');
                strengthText.textContent = "Sedang";
                strengthText.className = "text-xs text-amber-500 mt-1.5 text-right font-medium";
            } else if (score === 3) {
                bars[0].classList.add('bg-emerald-400');
                bars[1].classList.add('bg-emerald-400');
                bars[2].classList.add('bg-emerald-400');
                strengthText.textContent = "Kuat";
                strengthText.className = "text-xs text-emerald-500 mt-1.5 text-right font-medium";
            } else if (score >= 4) {
                bars[0].classList.add('bg-emerald-600');
                bars[1].classList.add('bg-emerald-600');
                bars[2].classList.add('bg-emerald-600');
                bars[3].classList.add('bg-emerald-600');
                strengthText.textContent = "Sangat Kuat";
                strengthText.className = "text-xs text-emerald-600 mt-1.5 text-right font-bold";
            }
            
            checkPasswordMatch();
        });

        // --- 3. PASSWORD MATCH CHECKER ---
        confirmPassInput.addEventListener('input', checkPasswordMatch);

        function checkPasswordMatch() {
            if(confirmPassInput.value.length === 0) {
                matchText.classList.add('hidden');
                return;
            }
            
            matchText.classList.remove('hidden');
            if (newPassInput.value === confirmPassInput.value) {
                matchText.textContent = '✓ Password cocok';
                matchText.className = 'text-xs mt-1.5 text-right text-emerald-600 font-medium';
            } else {
                matchText.textContent = '✗ Password tidak cocok';
                matchText.className = 'text-xs mt-1.5 text-right text-red-500 font-medium';
            }
        }

        // --- 4. FORM SUBMIT & TOAST ---
        function showToast(type, title, message) {
            const toast = document.getElementById('toast');
            const icon = document.getElementById('toast-icon');
            icon.className = `w-10 h-10 rounded-full flex items-center justify-center text-white ${type === 'success' ? 'bg-emerald-500' : 'bg-red-500'}`;
            icon.innerHTML = type === 'success' 
                ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>'
                : '<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>';
            
            document.getElementById('toast-title').textContent = title;
            document.getElementById('toast-message').textContent = message;
            toast.classList.remove('translate-x-full');
            toast.classList.add('translate-x-0');
            setTimeout(() => { toast.classList.remove('translate-x-0'); toast.classList.add('translate-x-full'); }, 4000);
        }

        document.getElementById('reset-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-submit');
            const originalText = btn.innerHTML;
            
            // Validasi client-side sebelum kirim
            if (newPassInput.value !== confirmPassInput.value) {
                showToast('error', 'Peringatan', 'Konfirmasi password tidak cocok dengan password baru.');
                confirmPassInput.focus();
                return;
            }

            btn.innerHTML = `<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;
            btn.disabled = true;

            try {
                const formData = new FormData(e.target);
                const response = await fetch('<?= base_url('auth/lupa-password/update') ?>', {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                
                const result = await response.json();
                btn.innerHTML = originalText;
                btn.disabled = false;

                if(result.status === 'success') {
                    showToast('success', 'Berhasil!', result.message);
                    // Hilangkan form agar user tidak klik lagi
                    document.getElementById('reset-form').style.display = 'none';
                    setTimeout(() => window.location.href = '<?= base_url('login') ?>', 2000);
                } else {
                    showToast('error', 'Gagal', result.message);
                }
            } catch (error) {
                btn.innerHTML = originalText;
                btn.disabled = false;
                showToast('error', 'Kesalahan Sistem', 'Gagal memproses kata laluan baharu.');
            }
        });
    </script>
</body>
</html>