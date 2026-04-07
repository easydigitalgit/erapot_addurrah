<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= lang('OrangTua/AkunSaya.page_title') ?> - <?= session()->get('nama_lengkap') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<meta name="X-CSRF-TOKEN" content="<?= csrf_hash() ?>">
<meta name="base-url" content="<?= rtrim(base_url(), '/') ?>">
<style>
    :root {
        --warna-primary: <?= $color['warna_primary'] ?? '#3b82f6' ?>;
        --warna-secondary: <?= $color['warna_secondary'] ?? '#eff6ff' ?>;
        --warna-scroll: <?= $color['warna_primary'] ?>;
    }

    .avatar-upload-overlay {
        transition: all 0.3s ease;
    }

    .radio-custom {
        width: 1.25rem;
        height: 1.25rem;
        cursor: pointer;
    }

    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background-color: var(--warna-scroll);
        border-radius: 3px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-3">
        <span class="text-[var(--warna-primary)] font-medium"><?= lang('OrangTua/AkunSaya.page_title') ?></span>
    </div>

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-3">
                <svg class="w-8 h-8 text-[var(--warna-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <?= lang('OrangTua/AkunSaya.page_title') ?>
            </h1>
            <p class="text-sm md:text-base text-gray-600 dark:text-slate-300"><?= lang('OrangTua/AkunSaya.page_subtitle') ?? 'Kelola informasi pribadi, keamanan, dan preferensi akun Anda.' ?></p>
        </div>
    </div>
</div>

<div class="mb-6 rounded-2xl shadow-lg overflow-hidden border-0">
    <div class="bg-[var(--warna-primary)] p-6 md:p-8 text-white relative">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/3 blur-2xl"></div>

        <div class="flex flex-col md:flex-row gap-6 items-center md:items-start relative z-10">
            <?php
            $fotoProfil  = $user['foto_profil'] ?? session()->get('foto_profil');
            $namaLengkap = $user['nama_lengkap'] ?? session()->get('username') ?? 'User';
            $inisial     = strtoupper(substr($namaLengkap, 0, 2));

            if (!empty($fotoProfil) && file_exists(FCPATH . 'assets/uploads/avatars/' . $fotoProfil)) {
                $avatarUrl = base_url('assets/uploads/avatars/' . $fotoProfil);
            } else {
                $avatarUrl = "https://ui-avatars.com/api/?name={$inisial}&background=1F7A4D&color=fff&size=160&bold=true&rounded=true";
            }
            ?>

            <div class="avatar-upload w-32 h-32 md:w-40 md:h-40 relative group shrink-0">
                <img src="<?= $avatarUrl ?>"
                    alt="Avatar"
                    class="w-full h-full rounded-full object-cover border-4 border-white/30 shadow-md transition-all duration-300"
                    id="avatarImage"
                    onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=<?= urlencode($inisial) ?>&background=1F7A4D&color=fff&size=160&bold=true&rounded=true';">
                <label for="avatarUpload" class="avatar-upload-overlay absolute inset-0 flex items-center justify-center bg-black/40 hover:bg-black/60 rounded-full cursor-pointer opacity-0 group-hover:opacity-100">
                    <svg class="w-10 h-10 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </label>
                <input type="file" id="avatarUpload" name="avatar" accept="image/png, image/jpeg, image/jpg" class="hidden">
            </div>

            <div class="flex-1 w-full text-center md:text-left">
                <div class="flex flex-col md:flex-row md:items-start justify-between mb-6 gap-4">
                    <div>
                        <h4 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight mb-2 drop-shadow-md">
                            <?= esc($namaLengkap) ?>
                        </h4>
                        <div class="inline-flex items-center gap-2 bg-black/20 px-4 py-1.5 rounded-full backdrop-blur-md border border-white/10 shadow-inner">
                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-100 tracking-wide">@<?= esc($user['username'] ?? session()->get('username')) ?></span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-center md:justify-end gap-2 mt-2 md:mt-0">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-500/20 backdrop-blur-md border border-emerald-400/30 text-emerald-50 rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-50"></span>
                            </span>
                            <?= lang('OrangTua/AkunSaya.status_active') ?? 'AKTIF' ?>
                        </span>

                        <?php if (!empty($semua_role)): ?>
                            <?php foreach ($semua_role as $role): ?>
                                <span class="inline-flex items-center px-3 py-1.5 bg-white/10 backdrop-blur-md border border-white/20 text-white rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm cursor-default">
                                    <svg class="w-3 h-3 mr-1.5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    <?= esc($role['role_name'] ?? $role['nama_role'] ?? 'Role') ?>
                                </span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="inline-flex items-center px-3 py-1.5 bg-white/10 backdrop-blur-md border border-white/20 text-white rounded-full text-[10px] font-black uppercase tracking-widest shadow-sm cursor-default">
                                <svg class="w-3 h-3 mr-1.5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <?= session()->get('role_label') ?? 'Orang Tua' ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-6 bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
        <svg class="w-6 h-6 text-[var(--warna-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <?= lang('OrangTua/AkunSaya.personal_title') ?? 'Informasi Pribadi' ?>
    </h3>

    <form id="personalInfoForm">
        <?= csrf_field(); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('OrangTua/AkunSaya.full_name') ?? 'Nama Lengkap' ?> <span class="text-red-500">*</span></label>
                <input type="text" name="nama_lengkap" value="<?= esc($user['nama_lengkap'] ?? '') ?>" class="w-full px-4 py-2 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg focus:border-[var(--warna-primary)] outline-none transition-colors" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('OrangTua/AkunSaya.email') ?? 'Alamat Email' ?> <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="<?= esc($user['email'] ?? '') ?>" class="w-full px-4 py-2 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg focus:border-[var(--warna-primary)] outline-none transition-colors" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('OrangTua/AkunSaya.phone') ?? 'Nomor HP/WA' ?></label>
                <input type="tel" name="no_hp" value="<?= esc($user['no_hp'] ?? '') ?>" class="w-full px-4 py-2 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg focus:border-[var(--warna-primary)] outline-none transition-colors">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('OrangTua/AkunSaya.address') ?? 'Alamat Tempat Tinggal' ?></label>
                <textarea name="alamat" class="w-full px-4 py-2 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg focus:border-[var(--warna-primary)] outline-none transition-colors" rows="3"><?= esc($user['alamat'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button type="submit" id="btnSaveInfo" class="bg-[var(--warna-primary)] px-6 py-2.5 text-white font-bold rounded-xl hover:brightness-90 transition-all flex items-center gap-2 shadow-md outline-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span id="textSaveInfo"><?= lang('OrangTua/AkunSaya.btn_save_changes') ?? 'Simpan Perubahan' ?></span>
            </button>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="card bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-[var(--warna-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <?= lang('OrangTua/AkunSaya.account_sec') ?? 'Keamanan Akun' ?>
        </h3>

        <div class="p-4 bg-gray-50 dark:bg-slate-700 rounded-xl border border-gray-100 dark:border-slate-600 mb-4">
            <div class="flex items-center justify-between mb-2">
                <p class="font-semibold text-gray-900 dark:text-white"><?= lang('OrangTua/AkunSaya.password') ?? 'Password' ?></p>
                <button onclick="showChangePasswordModal()" class="text-sm text-white bg-[var(--warna-primary)] px-3 py-1.5 rounded-lg hover:brightness-90 font-bold transition-all shadow-sm outline-none">
                    <?= lang('OrangTua/AkunSaya.btn_change_pass') ?? 'Ganti Password' ?>
                </button>
            </div>
            <p class="text-sm text-gray-600 dark:text-slate-300"><?= lang('OrangTua/AkunSaya.sec_desc') ?? 'Pastikan akun Anda menggunakan password yang kuat dan unik.' ?></p>
        </div>

        <div class="p-4 bg-[var(--warna-secondary)] border border-[var(--warna-primary)] rounded-xl">
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 text-[var(--warna-primary)] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="text-sm text-[var(--warna-primary)] font-semibold mb-1"><?= lang('OrangTua/AkunSaya.sec_tips') ?? 'Tips Keamanan' ?></p>
                    <ul class="text-sm text-[var(--warna-primary)] space-y-1">
                        <li>• <?= lang('OrangTua/AkunSaya.tip_1') ?? 'Gunakan minimal 8 karakter.' ?></li>
                        <li>• <?= lang('OrangTua/AkunSaya.tip_2') ?? 'Kombinasikan huruf besar, huruf kecil, dan angka.' ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-[var(--warna-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <?= lang('OrangTua/AkunSaya.sys_pref') ?? 'Preferensi Sistem' ?>
        </h3>

        <?php
        $currentLang = session()->get('bahasa') ?? 'id';
        $currentTheme = session()->get('theme') ?? 'light';
        ?>
        <div class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('OrangTua/AkunSaya.sys_lang') ?? 'Bahasa Sistem' ?></label>
                <select id="pref_bahasa" class="w-full border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white rounded-xl p-3 focus:border-[var(--warna-primary)] outline-none transition-colors">
                    <option value="id" <?= $currentLang == 'id' ? 'selected' : '' ?>><?= lang('OrangTua/AkunSaya.lang_id') ?? 'Indonesia' ?></option>
                    <option value="en" <?= $currentLang == 'en' ? 'selected' : '' ?>><?= lang('OrangTua/AkunSaya.lang_en') ?? 'English' ?></option>
                    <option value="ar" <?= $currentLang == 'ar' ? 'selected' : '' ?>><?= lang('OrangTua/AkunSaya.lang_ar') ?? 'Arabic' ?></option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-3"><?= lang('OrangTua/AkunSaya.display_mode') ?? 'Mode Tampilan' ?></label>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-700 cursor-pointer transition-colors bg-white dark:bg-slate-800">
                        <input type="radio" name="theme" value="light" <?= $currentTheme == 'light' ? 'checked' : '' ?> class="radio-custom accent-[var(--warna-primary)]">
                        <div class="flex items-center gap-2 flex-1">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white text-sm"><?= lang('OrangTua/AkunSaya.light_mode') ?? 'Mode Terang' ?></p>
                                <p class="text-xs text-gray-500 dark:text-slate-400"><?= lang('OrangTua/AkunSaya.light_desc') ?? 'Tampilan standar yang cerah.' ?></p>
                            </div>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-700 cursor-pointer transition-colors bg-white dark:bg-slate-800">
                        <input type="radio" name="theme" value="dark" <?= $currentTheme == 'dark' ? 'checked' : '' ?> class="radio-custom accent-[var(--warna-primary)]">
                        <div class="flex items-center gap-2 flex-1">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white text-sm"><?= lang('OrangTua/AkunSaya.dark_mode') ?? 'Mode Gelap' ?></p>
                                <p class="text-xs text-gray-500 dark:text-slate-400"><?= lang('OrangTua/AkunSaya.dark_desc') ?? 'Nyaman di mata untuk penggunaan malam hari.' ?></p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button id="btnSavePref" onclick="savePreferences()" class="bg-[var(--warna-primary)] px-6 py-2.5 text-white font-bold rounded-xl hover:brightness-90 transition-all flex items-center gap-2 shadow-md outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span id="textSavePref"><?= lang('OrangTua/AkunSaya.btn_save_pref') ?? 'Simpan Preferensi' ?></span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card mb-6 bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
        <svg class="w-6 h-6 text-[var(--warna-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <?= lang('OrangTua/AkunSaya.recent_login') ?? 'Riwayat Login Terbaru' ?>
    </h3>
    <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-slate-700">
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-slate-700/50 border-b border-gray-100 dark:border-slate-700">
                <tr>
                    <th class="py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-300"><?= lang('OrangTua/AkunSaya.th_time') ?? 'Waktu' ?></th>
                    <th class="py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-300"><?= lang('OrangTua/AkunSaya.th_device') ?? 'Perangkat' ?></th>
                    <th class="py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-300"><?= lang('OrangTua/AkunSaya.th_ip') ?? 'IP Address' ?></th>
                    <th class="py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-300"><?= lang('OrangTua/AkunSaya.th_status') ?? 'Status' ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                <?php if (empty($login_logs)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-500 dark:text-slate-400"><?= lang('OrangTua/AkunSaya.empty_login') ?? 'Belum ada riwayat login.' ?></td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($login_logs as $log): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                            <td class="py-3 px-4 font-medium text-gray-900 dark:text-white">
                                <?= $log['waktu'] ? date('d M Y', strtotime($log['waktu'])) : '-' ?><br>
                                <span class="text-xs text-gray-500 dark:text-slate-400"><?= $log['waktu'] ? date('H:i', strtotime($log['waktu'])) . ' WIB' : '-' ?></span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-gray-100 dark:bg-slate-600 flex items-center justify-center flex-shrink-0">
                                        <?php if (strpos($log['device'], 'Smartphone') !== false): ?>
                                            <svg class="w-4 h-4 text-gray-500 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        <?php else: ?>
                                            <svg class="w-4 h-4 text-gray-500 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white text-sm truncate max-w-[200px]" title="<?= esc($log['device']) ?>"><?= esc($log['device']) ?></p>
                                        <p class="text-xs text-gray-500 dark:text-slate-400"><?= esc($log['browser']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 font-mono text-sm text-gray-700 dark:text-slate-300"><?= htmlspecialchars($log['ip']) ?></td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 <?= $log['status'] == 'Berhasil' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' ?> rounded-full text-xs font-bold"><?= $log['status'] ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<div id="passwordModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-800 rounded-3xl w-full max-w-md p-6 transform scale-95 transition-transform duration-300 shadow-2xl border border-gray-100 dark:border-slate-700">
        <div class="text-center mb-6">
            <div class="w-16 h-16 rounded-full bg-[var(--warna-primary)]/10 flex items-center justify-center mx-auto mb-4 border-2 border-[var(--warna-primary)]/20">
                <svg class="w-8 h-8 text-[var(--warna-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </div>
            <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2"><?= lang('OrangTua/AkunSaya.modal_pass_title') ?? 'Ganti Password' ?></h3>
        </div>

        <form id="changePasswordForm">
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1"><?= lang('Admin/Akun.old_pass') ?? 'Password Lama' ?></label>
                    <div class="relative">
                        <input type="password" id="oldPassword" class="w-full pl-4 pr-12 py-2 border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?? '#1F7A4D' ?>] outline-none transition-all" required>
                        <button type="button" class="toggle-password absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 z-10 transition-colors" tabindex="-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1"><?= lang('Admin/Akun.new_pass') ?? 'Password Baru' ?></label>
                    <div class="relative">
                        <input type="password" id="newPassword" class="w-full pl-4 pr-12 py-2 border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?? '#1F7A4D' ?>] outline-none transition-all" required>
                        <button type="button" class="toggle-password absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 z-10 transition-colors" tabindex="-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1"><?= lang('Admin/Akun.pass_min_char') ?? 'Minimal 8 karakter' ?></p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1"><?= lang('Admin/Akun.conf_new_pass') ?? 'Konfirmasi Password' ?></label>
                    <div class="relative">
                        <input type="password" id="confirmPassword" class="w-full pl-4 pr-12 py-2 border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?? '#1F7A4D' ?>] outline-none transition-all" required>
                        <button type="button" class="toggle-password absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 z-10 transition-colors" tabindex="-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closePasswordModal()" class="flex-1 py-3 px-4 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors outline-none"><?= lang('OrangTua/AkunSaya.btn_cancel') ?? 'Batal' ?></button>
                <button type="submit" id="btnSavePassword" class="flex-1 py-3 px-4 bg-[var(--warna-primary)] text-white font-bold rounded-xl hover:brightness-90 transition-all shadow-md outline-none">
                    <span id="textSavePassword"><?= lang('OrangTua/AkunSaya.btn_change_pass') ?? 'Simpan Password' ?></span>
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // SEMUA JAVASCRIPT DITANAM LANGSUNG KE VIEW AGAR ANTI CACHE
    const BASE_URL = document.querySelector('meta[name="base-url"]').content;
    const CSRF_TOKEN = document.querySelector('meta[name="X-CSRF-TOKEN"]').content;

    const LANG = {
        saving: "Menyimpan...",
        success: "Berhasil",
        failed: "Gagal",
        server_error: "Terjadi kesalahan pada server.",
        err_pwd_match: "Konfirmasi password baru tidak cocok.",
        err_pwd_len: "Password minimal 8 karakter.",
        err_img_size: "Ukuran foto terlalu besar (Max 2MB).",
        uploading: "Mengunggah...",
        photo_updated: "Foto profil berhasil diperbarui.",
        btn_change_pass: "Ubah Password",
        btn_save_changes: "Simpan Perubahan",
        error_title: "Error",
        warning_title: "Peringatan"
    };

    document.addEventListener('DOMContentLoaded', () => {

        // --- TOGGLE PASSWORD VISIBILITY (INJEKSI BARU) ---
        const togglePasswordBtns = document.querySelectorAll('.toggle-password');
        togglePasswordBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.previousElementSibling;
                if (input.type === 'password') {
                    input.type = 'text';
                    // Mata disilang
                    this.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>`;
                    this.classList.add('text-gray-700', 'dark:text-gray-200');
                } else {
                    input.type = 'password';
                    // Mata normal
                    this.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>`;
                    this.classList.remove('text-gray-700', 'dark:text-gray-200');
                }
            });
        });
        // ------------------------------------------------

        // 1. UPDATE DATA PRIBADI (INFO PROFIL)
        const personalForm = document.getElementById('personalInfoForm');
        if (personalForm) {
            personalForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = document.getElementById('btnSaveInfo');
                const text = document.getElementById('textSaveInfo');
                const originalText = text.innerText;

                btn.disabled = true;
                text.innerHTML = `<svg class="animate-spin h-4 w-4 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ${LANG.saving}`;

                const formData = new FormData(this);
                const csrfTokenMeta = document.querySelector('meta[name="X-CSRF-TOKEN"]');
                if (csrfTokenMeta) formData.append('csrf_test_name', csrfTokenMeta.content);

                fetch(`${BASE_URL}/orangtua/akun-saya/updateProfile`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.token && csrfTokenMeta) csrfTokenMeta.content = data.token;
                        if (data.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: LANG.success,
                                text: data.message,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire(LANG.error_title, data.message, 'error');
                        }
                    })
                    .catch(err => Swal.fire(LANG.error_title, LANG.server_error, 'error'))
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
                    Swal.fire(LANG.error_title, LANG.err_img_size, 'error');
                    this.value = '';
                    return;
                }

                const formData = new FormData();
                formData.append('avatar', file);

                const csrfTokenMeta = document.querySelector('meta[name="X-CSRF-TOKEN"]');
                if (csrfTokenMeta) formData.append('csrf_test_name', csrfTokenMeta.content);

                Swal.fire({
                    title: LANG.uploading,
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch(`${BASE_URL}/orangtua/akun-saya/uploadAvatar`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.token && csrfTokenMeta) csrfTokenMeta.content = data.token;
                        if (data.status === 'success') {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                document.getElementById('avatarImage').src = e.target.result;
                            };
                            reader.readAsDataURL(file);

                            // UPDATE AVATAR DI NAVBAR SECARA LIVE 
                            const navbarAvatar = document.getElementById('navbarAvatar');
                            if (navbarAvatar && data.new_avatar_url) {
                                const originalNavSrc = navbarAvatar.src;
                                navbarAvatar.src = data.new_avatar_url;
                                navbarAvatar.onerror = function() {
                                    this.onerror = null;
                                    this.src = originalNavSrc;
                                };
                            }

                            Swal.fire({
                                icon: 'success',
                                title: LANG.success,
                                text: LANG.photo_updated,
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire(LANG.error_title, data.message, 'error');
                        }
                    })
                    .catch(err => Swal.fire(LANG.error_title, LANG.server_error, 'error'));
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

                if (newPass.length < 8) return Swal.fire(LANG.warning_title, LANG.err_pwd_len, 'warning');
                if (newPass !== confPass) return Swal.fire(LANG.warning_title, LANG.err_pwd_match, 'warning');

                const btn = document.getElementById('btnSavePassword');
                const text = document.getElementById('textSavePassword');
                btn.disabled = true;
                text.innerHTML = `<svg class="animate-spin h-4 w-4 mr-2 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ${LANG.saving}`;

                const formData = new FormData();
                formData.append('old_password', oldPass);
                formData.append('new_password', newPass);

                const csrfTokenMeta = document.querySelector('meta[name="X-CSRF-TOKEN"]');
                if (csrfTokenMeta) formData.append('csrf_test_name', csrfTokenMeta.content);

                fetch(`${BASE_URL}/orangtua/akun-saya/updatePassword`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.token && csrfTokenMeta) csrfTokenMeta.content = data.token;
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
                            Swal.fire(LANG.error_title, data.message, 'error');
                        }
                    })
                    .catch(err => Swal.fire(LANG.error_title, LANG.server_error, 'error'))
                    .finally(() => {
                        btn.disabled = false;
                        text.innerText = LANG.btn_change_pass;
                    });
            });
        }
    });

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
        text.innerHTML = `<svg class="animate-spin h-4 w-4 mr-2 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ${LANG.saving}`;

        const theme = document.querySelector('input[name="theme"]:checked').value;
        const lang = document.getElementById('pref_bahasa').value;

        const formData = new FormData();
        formData.append('theme', theme);
        formData.append('bahasa', lang);

        const csrfTokenMeta = document.querySelector('meta[name="X-CSRF-TOKEN"]');
        if (csrfTokenMeta) formData.append('csrf_test_name', csrfTokenMeta.content);

        fetch(`${BASE_URL}/orangtua/akun-saya/updatePreferences`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.token && csrfTokenMeta) csrfTokenMeta.content = data.token;
                if (data.status === 'success') {
                    localStorage.setItem('theme', theme);
                    if (theme === 'dark') document.documentElement.classList.add('dark');
                    else document.documentElement.classList.remove('dark');

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
                    Swal.fire(LANG.error_title, data.message, 'error');
                }
            })
            .catch(err => Swal.fire(LANG.error_title, LANG.server_error, 'error'))
            .finally(() => {
                btn.disabled = false;
                text.innerText = originalText;
            });
    };
</script>
<?= $this->endSection() ?>