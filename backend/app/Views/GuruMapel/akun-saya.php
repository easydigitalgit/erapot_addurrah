<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= lang('GuruMapel/Akun.page_title') ?> - <?= session()->get('nama_lengkap') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/GuruMapel/akun-saya.css') ?>">
<style>
    :root {
        --warna-scroll: <?= $color['warna_primary'] ?>;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-3">
        <span class="text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('GuruMapel/Akun.page_title') ?></span>
    </div>
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-3">
                <svg class="w-8 h-8 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <?= lang('GuruMapel/Akun.page_title') ?>
            </h1>
            <p class="text-sm md:text-base text-gray-600 dark:text-slate-300"><?= lang('GuruMapel/Akun.page_subtitle') ?></p>
        </div>
    </div>

    <div class="bg-[<?= $color['warna_secondary'] ?>] border border-[<?= $color['warna_primary'] ?>] p-4 rounded-xl mb-6">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg bg-[<?= $color['warna_secondary'] ?>] flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="font-bold text-[<?= $color['warna_primary'] ?>] mb-1"><?= lang('GuruMapel/Akun.banner_sec_title') ?></h4>
                <ul class="text-sm text-[<?= $color['warna_primary'] ?>] space-y-1">
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg> <?= lang('GuruMapel/Akun.banner_sec_1') ?></li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg> <?= lang('GuruMapel/Akun.banner_sec_2') ?></li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg> <?= lang('GuruMapel/Akun.banner_sec_3') ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="mb-6 rounded-2xl shadow-lg overflow-hidden border-0">
    <div class="bg-gradient-to-r from-[<?= $color['warna_primary'] ?>] to-[<?= $color['warna_primary'] ?>]/70 p-6 md:p-8 text-white relative">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/3 blur-2xl"></div>
        <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2 relative z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <?= lang('GuruMapel/Akun.profile_title') ?>
        </h3>
        <div class="flex flex-col md:flex-row gap-6 items-center md:items-start relative z-10">
            <?php
            $fotoProfil  = $user['foto_profil'] ?? session()->get('foto_profil');
            $namaLengkap = $user['nama_lengkap'] ?? session()->get('nama_lengkap') ?? session()->get('username') ?? 'User';
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
                <label for="avatarUpload" class="avatar-upload-overlay absolute inset-0 flex items-center justify-center bg-black/40 hover:bg-black/60 rounded-full cursor-pointer transition-all duration-300 opacity-0 group-hover:opacity-100">
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
                        <h4 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-white tracking-tight mb-2 drop-shadow-md"><?= $namaLengkap ?></h4>
                        <div class="inline-flex items-center gap-2 bg-black/20 px-4 py-1.5 rounded-full backdrop-blur-md border border-white/10 shadow-inner">
                            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="text-sm font-medium text-gray-100 tracking-wide">@<?= session()->get('username') ?></span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-center md:justify-end gap-2 mt-2 md:mt-0">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-500/20 backdrop-blur-md border border-emerald-400/30 text-emerald-50 rounded-full text-xs font-bold uppercase tracking-wide shadow-sm">
                            <span class="relative flex h-2.5 w-2.5"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span></span>
                            <?= lang('GuruMapel/Akun.active_badge') ?>
                        </span>
                        <?php if (!empty($semua_role)): ?>
                            <?php foreach ($semua_role as $role): ?>
                                <span class="inline-flex items-center px-3 py-1.5 bg-white/10 backdrop-blur-md border border-white/20 text-white rounded-full text-xs font-bold uppercase tracking-wide shadow-sm hover:bg-white/20 transition-colors">
                                    <?= esc($role['role_name'] ?? $role['nama_role'] ?? 'Role') ?>
                                </span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="inline-flex items-center px-3 py-1.5 bg-white/10 backdrop-blur-md border border-white/20 text-white rounded-full text-xs font-bold uppercase tracking-wide shadow-sm">
                                <?= session()->get('role_label') ?? 'User' ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 pt-6 border-t border-white/20 mt-2">
                    <div class="group bg-white/10 hover:bg-white/20 p-4 rounded-xl backdrop-blur-md border border-white/10 transition-all duration-300">
                        <div class="flex items-center gap-2 mb-2">
                            <p class="text-[11px] text-gray-200 font-semibold uppercase tracking-wider"><?= lang('GuruMapel/Akun.prof_email') ?></p>
                        </div>
                        <p class="font-bold text-white text-sm sm:text-base truncate" title="<?= $user['email'] ?? '-' ?>"><?= $user['email'] ?? lang('GuruMapel/Akun.not_set') ?></p>
                    </div>
                    <div class="group bg-white/10 hover:bg-white/20 p-4 rounded-xl backdrop-blur-md border border-white/10 transition-all duration-300">
                        <div class="flex items-center gap-2 mb-2">
                            <p class="text-[11px] text-gray-200 font-semibold uppercase tracking-wider"><?= lang('GuruMapel/Akun.prof_main_access') ?></p>
                        </div>
                        <p class="font-bold text-white text-sm sm:text-base truncate"><?= session()->get('role_label') ?? 'User' ?></p>
                    </div>
                    <div class="group bg-white/10 hover:bg-white/20 p-4 rounded-xl backdrop-blur-md border border-white/10 transition-all duration-300">
                        <div class="flex items-center gap-2 mb-2">
                            <p class="text-[11px] text-gray-200 font-semibold uppercase tracking-wider"><?= lang('GuruMapel/Akun.prof_joined') ?></p>
                        </div>
                        <p class="font-bold text-white text-sm sm:text-base truncate"><?= isset($user['created_at']) ? date('d F Y', strtotime($user['created_at'])) : '-' ?></p>
                    </div>
                    <div class="group bg-white/10 hover:bg-white/20 p-4 rounded-xl backdrop-blur-md border border-white/10 transition-all duration-300">
                        <div class="flex items-center gap-2 mb-2">
                            <p class="text-[11px] text-gray-200 font-semibold uppercase tracking-wider"><?= lang('GuruMapel/Akun.prof_last_login') ?></p>
                        </div>
                        <p class="font-bold text-white text-sm sm:text-base truncate"><?= lang('GuruMapel/Akun.just_now') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-6 bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
        <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <?= lang('GuruMapel/Akun.personal_title') ?>
    </h3>
    <form id="personalInfoForm">
        <?= csrf_field(); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">
                    <?= lang('GuruMapel/Akun.pers_fullname') ?> <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_lengkap" value="<?= $user['nama_lengkap'] ?? '' ?>" class="input-field bg-white dark:bg-slate-700 text-gray-900 dark:text-white border-gray-200 dark:border-slate-600 focus:border-[<?= $color['warna_primary'] ?>] w-full px-4 py-2 border rounded-lg" placeholder="<?= lang('GuruMapel/Akun.pers_fullname_ph') ?>" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">
                    <?= lang('GuruMapel/Akun.pers_email') ?> <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" value="<?= $user['email'] ?? '' ?>" class="input-field bg-white dark:bg-slate-700 text-gray-900 dark:text-white border-gray-200 dark:border-slate-600 focus:border-[<?= $color['warna_primary'] ?>] w-full px-4 py-2 border rounded-lg" placeholder="<?= lang('GuruMapel/Akun.pers_email_ph') ?>" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">
                    <?= lang('GuruMapel/Akun.pers_phone') ?> <span class="text-red-500">*</span>
                </label>
                <input type="tel" name="no_hp" value="<?= $user['no_hp'] ?? '' ?>" class="input-field bg-white dark:bg-slate-700 text-gray-900 dark:text-white border-gray-200 dark:border-slate-600 focus:border-[<?= $color['warna_primary'] ?>] w-full px-4 py-2 border rounded-lg" placeholder="<?= lang('GuruMapel/Akun.pers_phone_ph') ?>" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">
                    <?= lang('GuruMapel/Akun.pers_alt_phone') ?>
                </label>
                <input type="tel" name="no_darurat" value="<?= $user['no_darurat'] ?? '' ?>" class="input-field bg-white dark:bg-slate-700 text-gray-900 dark:text-white border-gray-200 dark:border-slate-600 focus:border-[<?= $color['warna_primary'] ?>] w-full px-4 py-2 border rounded-lg" placeholder="<?= lang('GuruMapel/Akun.optional') ?>">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2">
                    <?= lang('GuruMapel/Akun.pers_address') ?>
                </label>
                <textarea name="alamat" class="input-field bg-white dark:bg-slate-700 text-gray-900 dark:text-white border-gray-200 dark:border-slate-600 focus:border-[<?= $color['warna_primary'] ?>] w-full px-4 py-2 border rounded-lg" rows="3" placeholder="<?= lang('GuruMapel/Akun.pers_address_ph') ?>"><?= $user['alamat_domisili'] ?? '' ?></textarea>
            </div>
        </div>
        <div class="flex justify-end mt-6">
            <button type="submit" id="btnSaveInfo" class="btn-primary bg-[<?= $color['warna_primary'] ?>] px-4 py-2 text-white rounded-lg hover:brightness-90 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span id="textSaveInfo"><?= lang('GuruMapel/Akun.btn_save_changes') ?></span>
            </button>
        </div>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="card bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <?= lang('GuruMapel/Akun.sec_title') ?>
        </h3>
        <div class="space-y-4">
            <div class="p-4 bg-gray-50 dark:bg-slate-700 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <p class="font-semibold text-gray-900 dark:text-white"><?= lang('GuruMapel/Akun.sec_password') ?></p>
                    <button onclick="showChangePasswordModal()" class="text-sm text-[<?= $color['warna_primary'] ?>] hover:brightness-110 font-semibold"> <?= lang('GuruMapel/Akun.sec_change_pass') ?> </button>
                </div>
                <p class="text-sm text-gray-600 dark:text-slate-300"><?= lang('GuruMapel/Akun.sec_pass_desc') ?></p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-slate-700 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-white mb-1"><?= lang('GuruMapel/Akun.sec_login_notif') ?></p>
                        <p class="text-sm text-gray-600 dark:text-slate-300"><?= lang('GuruMapel/Akun.sec_login_notif_desc') ?></p>
                    </div>
                    <span id="tg_notif_login" class="toggle-switch <?= ($prefs['notif_login'] ?? 0) ? 'active bg-[' . $color['warna_primary'] . ']' : '' ?> [&.active]:bg-[<?= $color['warna_primary'] ?>]" onclick="toggleSwitch(this)"></span>
                </div>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-slate-700 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-white mb-1"><?= lang('GuruMapel/Akun.sec_2fa') ?></p>
                        <p class="text-sm text-gray-600 dark:text-slate-300"><?= lang('GuruMapel/Akun.sec_2fa_desc') ?></p>
                    </div>
                    <span id="tg_2fa" class="toggle-switch <?= ($prefs['two_factor'] ?? 0) ? 'active bg-[' . $color['warna_primary'] . ']' : '' ?> [&.active]:bg-[<?= $color['warna_primary'] ?>]" onclick="toggleSwitch(this)"></span>
                </div>
            </div>
            <div class="p-4 bg-[<?= $color['warna_secondary'] ?>] border border-[<?= $color['warna_primary'] ?>] rounded-lg">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="text-sm text-[<?= $color['warna_primary'] ?>] font-semibold mb-1"><?= lang('GuruMapel/Akun.sec_tips_title') ?></p>
                        <ul class="text-sm text-[<?= $color['warna_primary'] ?>] space-y-1">
                            <li>• <?= lang('GuruMapel/Akun.sec_tip_1') ?></li>
                            <li>• <?= lang('GuruMapel/Akun.sec_tip_2') ?></li>
                            <li>• <?= lang('GuruMapel/Akun.sec_tip_3') ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <?= lang('GuruMapel/Akun.pref_title') ?>
        </h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('GuruMapel/Akun.pref_sys_lang') ?></label>
                <select id="pref_bahasa" class="input-field w-full border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg p-2 focus:outline-none focus:border-[<?= $color['warna_primary'] ?>]">
                    <option value="id" <?= ($prefs['bahasa'] ?? '') == 'id' ? 'selected' : '' ?>><?= lang('GuruMapel/Akun.pref_lang_id') ?></option>
                    <option value="en" <?= ($prefs['bahasa'] ?? '') == 'en' ? 'selected' : '' ?>><?= lang('GuruMapel/Akun.pref_lang_en') ?></option>
                    <option value="ar" <?= ($prefs['bahasa'] ?? '') == 'ar' ? 'selected' : '' ?>><?= lang('GuruMapel/Akun.pref_lang_ar') ?></option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-3"><?= lang('GuruMapel/Akun.pref_display_mode') ?></label>
                <div class="space-y-2">
                    <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-700 cursor-pointer transition-colors">
                        <input type="radio" name="theme" value="light" <?= ($prefs['theme'] ?? 'light') == 'light' ? 'checked' : '' ?> class="radio-custom accent-[<?= $color['warna_primary'] ?>]">
                        <div class="flex items-center gap-2 flex-1">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white"><?= lang('GuruMapel/Akun.pref_light_mode') ?></p>
                                <p class="text-xs text-gray-500 dark:text-slate-400"><?= lang('GuruMapel/Akun.pref_light_desc') ?></p>
                            </div>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-700 cursor-pointer transition-colors">
                        <input type="radio" name="theme" value="dark" <?= ($prefs['theme'] ?? '') == 'dark' ? 'checked' : '' ?> class="radio-custom accent-[<?= $color['warna_primary'] ?>]">
                        <div class="flex items-center gap-2 flex-1">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white"><?= lang('GuruMapel/Akun.pref_dark_mode') ?></p>
                                <p class="text-xs text-gray-500 dark:text-slate-400"><?= lang('GuruMapel/Akun.pref_dark_desc') ?></p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200 dark:border-slate-700">
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-3"><?= lang('GuruMapel/Akun.pref_notifications') ?></label>
                <div class="space-y-3">
                    <label class="flex items-center justify-between cursor-pointer">
                        <span class="text-sm text-gray-700 dark:text-slate-300"><?= lang('GuruMapel/Akun.pref_notif_email') ?></span>
                        <span id="tg_notif_email" class="toggle-switch <?= ($prefs['notif_email'] ?? 0) ? 'active bg-[' . $color['warna_primary'] . ']' : '' ?> [&.active]:bg-[<?= $color['warna_primary'] ?>]" onclick="toggleSwitch(this)"></span>
                    </label>
                    <label class="flex items-center justify-between cursor-pointer">
                        <span class="text-sm text-gray-700 dark:text-slate-300"><?= lang('GuruMapel/Akun.pref_notif_sys') ?></span>
                        <span id="tg_notif_sistem" class="toggle-switch <?= ($prefs['notif_sistem'] ?? 0) ? 'active bg-[' . $color['warna_primary'] . ']' : '' ?> [&.active]:bg-[<?= $color['warna_primary'] ?>]" onclick="toggleSwitch(this)"></span>
                    </label>
                    <label class="flex items-center justify-between cursor-pointer">
                        <span class="text-sm text-gray-700 dark:text-slate-300"><?= lang('GuruMapel/Akun.pref_notif_update') ?></span>
                        <span id="tg_notif_update" class="toggle-switch <?= ($prefs['notif_update'] ?? 0) ? 'active bg-[' . $color['warna_primary'] . ']' : '' ?> [&.active]:bg-[<?= $color['warna_primary'] ?>]" onclick="toggleSwitch(this)"></span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button id="btnSavePref" onclick="savePreferences()" class="btn-primary bg-[<?= $color['warna_primary'] ?>] px-4 py-2 text-white rounded-lg hover:brightness-90 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span id="textSavePref"><?= lang('GuruMapel/Akun.btn_save_pref') ?></span>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card mb-6 bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
        <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <?= lang('GuruMapel/Akun.login_act_title') ?>
    </h3>
    <div class="overflow-x-auto">
        <table class="activity-table w-full text-left ">
            <thead class="border-b dark:border-slate-700 ">
                <tr class="dark:bg-slate-600">
                    <th class="py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-300 dark:bg-slate-700"><?= lang('GuruMapel/Akun.login_act_time') ?></th>
                    <th class="py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-300 "><?= lang('GuruMapel/Akun.login_act_device') ?></th>
                    <th class="py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-300"><?= lang('GuruMapel/Akun.login_act_ip') ?></th>
                    <th class="py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-300"><?= lang('GuruMapel/Akun.login_act_status') ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                <?php if (empty($login_logs)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-500 dark:text-slate-400"><?= lang('GuruMapel/Akun.login_act_empty') ?></td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($login_logs as $log): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                            <td class="py-3 px-4 font-medium text-gray-900 dark:text-white">
                                <?= date('d M Y', strtotime($log['created_at'])) ?><br>
                                <span class="text-xs text-gray-500 dark:text-slate-400"><?= date('H:i', strtotime($log['created_at'])) ?> WIB</span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-gray-100 dark:bg-slate-600 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($log['device_name']) ?></p>
                                        <p class="text-xs text-gray-500 dark:text-slate-400"><?= htmlspecialchars($log['browser_name']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 font-mono text-sm text-gray-700 dark:text-slate-300"><?= htmlspecialchars($log['ip_address']) ?></td>
                            <td class="py-3 px-4"><span class="px-2 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-full text-xs font-bold"><?= htmlspecialchars($log['status']) ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="card bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
            <?= lang('GuruMapel/Akun.role_title') ?>
        </h3>
        <div class="space-y-4">
            <div class="info-panel">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-[<?= $color['warna_secondary'] ?>] flex items-center justify-center">
                        <svg class="w-5 h-5 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-[<?= $color['warna_primary'] ?>] font-semibold"><?= lang('GuruMapel/Akun.role_current') ?></p>
                        <p class="text-xl font-bold text-[<?= $color['warna_primary'] ?>]"><?= session()->get('role_label') ?? 'Super Admin' ?></p>
                    </div>
                </div>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-700 dark:text-slate-300 mb-3"><?= lang('GuruMapel/Akun.role_main_access') ?></p>
                <div class="space-y-2">
                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-slate-400"><svg class="w-4 h-4 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg> <?= lang('GuruMapel/Akun.role_acc_1') ?></div>
                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-slate-400"><svg class="w-4 h-4 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg> <?= lang('GuruMapel/Akun.role_acc_2') ?></div>
                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-slate-400"><svg class="w-4 h-4 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg> <?= lang('GuruMapel/Akun.role_acc_3') ?></div>
                    <div class="flex items-center gap-2 text-sm text-gray-700 dark:text-slate-400"><svg class="w-4 h-4 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg> <?= lang('GuruMapel/Akun.role_acc_4') ?></div>
                </div>
            </div>
            <div class="bg-amber-50 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-800/50 rounded-lg p-3">
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="text-xs text-amber-800 dark:text-amber-400"><?= lang('GuruMapel/Akun.role_warning') ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <?= lang('GuruMapel/Akun.privacy_title') ?>
        </h3>
        <div class="space-y-4">
            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800/50 rounded-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <div>
                        <p class="font-semibold text-emerald-900 dark:text-emerald-300 mb-1"><?= lang('GuruMapel/Akun.privacy_enc') ?></p>
                        <p class="text-sm text-emerald-800 dark:text-emerald-400"><?= lang('GuruMapel/Akun.privacy_enc_desc') ?></p>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800/50 rounded-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <div>
                        <p class="font-semibold text-blue-900 dark:text-blue-300 mb-1"><?= lang('GuruMapel/Akun.privacy_audit') ?></p>
                        <p class="text-sm text-blue-800 dark:text-blue-400"><?= lang('GuruMapel/Akun.privacy_audit_desc') ?></p>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 rounded-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-gray-600 dark:text-slate-300 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-900 dark:text-white mb-1"><?= lang('GuruMapel/Akun.privacy_history') ?></p>
                        <p class="text-sm text-gray-700 dark:text-slate-400"><?= lang('GuruMapel/Akun.privacy_history_desc') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<div id="passwordModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-800 rounded-2xl w-full max-w-md p-6 transform scale-95 transition-transform duration-300 shadow-2xl">
        <div class="text-center mb-6">
            <div class="w-16 h-16 rounded-full bg-[<?= $color['warna_primary'] ?>]/10 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2"><?= lang('GuruMapel/Akun.modal_pass_title') ?></h3>
            <p class="text-gray-600 dark:text-slate-400 text-sm"><?= lang('GuruMapel/Akun.modal_pass_sub') ?></p>
        </div>

        <form id="changePasswordForm">
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1"><?= lang('Admin/Akun.old_pass') ?></label>
                    <div class="relative">
                        <input type="password" id="oldPassword" class="w-full pl-4 pr-12 py-2 border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] outline-none transition-all" required>
                        <button type="button" class="toggle-password absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 z-10 transition-colors" tabindex="-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1"><?= lang('Admin/Akun.new_pass') ?></label>
                    <div class="relative">
                        <input type="password" id="newPassword" class="w-full pl-4 pr-12 py-2 border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] outline-none transition-all" required>
                        <button type="button" class="toggle-password absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 z-10 transition-colors" tabindex="-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-1"><?= lang('Admin/Akun.pass_min_char') ?></p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1"><?= lang('Admin/Akun.conf_new_pass') ?></label>
                    <div class="relative">
                        <input type="password" id="confirmPassword" class="w-full pl-4 pr-12 py-2 border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] outline-none transition-all" required>
                        <button type="button" class="toggle-password absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 z-10 transition-colors" tabindex="-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800/50 rounded-lg p-3 mb-6">
                <p class="text-sm text-blue-800 dark:text-blue-300 flex items-start gap-2">
                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span><?= lang('GuruMapel/Akun.modal_warning') ?></span>
                </p>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closePasswordModal()" class="flex-1 py-2.5 px-4 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-slate-600 transition"><?= lang('GuruMapel/Akun.btn_cancel') ?></button>
                <button type="submit" id="btnSavePassword" class="flex-1 py-2.5 px-4 bg-[<?= $color['warna_primary'] ?>] text-white font-medium rounded-lg hover:brightness-90 transition flex items-center justify-center gap-2">
                    <span id="textSavePassword"><?= lang('GuruMapel/Akun.btn_save') ?></span>
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const BASE_URL = "<?= base_url() ?>";

    // Deklarasikan window.LANG secara eksplisit agar bisa dibaca file JS eksternal
    window.LANG = {
        saving: "<?= lang('GuruMapel/Akun.js_saving') ?: 'Menyimpan...' ?>",
        save_changes: "<?= lang('GuruMapel/Akun.btn_save_changes') ?: 'Simpan Perubahan' ?>",
        err_pwd_match: "<?= lang('GuruMapel/Akun.js_err_pwd_match') ?: 'Password baru dan konfirmasi tidak cocok' ?>",
        err_pwd_len: "<?= lang('GuruMapel/Akun.js_err_pwd_len') ?: 'Password minimal 8 karakter' ?>",
        processing: "<?= lang('GuruMapel/Akun.js_processing') ?: 'Memproses...' ?>",
        succ_pwd: "<?= lang('GuruMapel/Akun.js_succ_pwd') ?: 'Password diubah! Redirecting ke Login...' ?>",
        change_pwd: "<?= lang('GuruMapel/Akun.sec_change_pass') ?: 'Ubah Password' ?>",
        err_img_size: "<?= lang('GuruMapel/Akun.js_err_img_size') ?: 'Ukuran gambar maksimal 2MB!' ?>",
        uploading: "<?= lang('GuruMapel/Akun.js_uploading') ?: 'Sedang mengunggah foto...' ?>",
        save_pref: "<?= lang('GuruMapel/Akun.btn_save_pref') ?: 'Simpan Preferensi' ?>"
    };
</script>
<script src="<?= base_url('assets/js/GuruMapel/akun-saya.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>