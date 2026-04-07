<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= lang('Tahfidz/AkunSaya.page_title') ?> - <?= session()->get('nama_lengkap') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<meta name="X-CSRF-TOKEN" content="<?= csrf_hash() ?>">
<meta name="base-url" content="<?= rtrim(base_url(), '/') ?>">
<style>
    :root {
        --warna-scroll: <?= $color['warna_primary'] ?>;
        --warna-primary: <?= $color['warna_primary'] ?? '#10b981' ?>;
        --warna-secondary: <?= $color['warna_secondary'] ?? '#ecfdf5' ?>;
    }

    .avatar-upload-overlay {
        transition: all 0.3s ease;
    }

    .radio-custom {
        width: 1.25rem;
        height: 1.25rem;
        cursor: pointer;
    }
</style>
<link rel="stylesheet" href="<?= base_url('assets/css/GuruMapel/akun-saya.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-3">
        <span class="text-[var(--warna-primary)] font-medium"><?= lang('Tahfidz/AkunSaya.page_title') ?></span>
    </div>

    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-3">
                <svg class="w-8 h-8 text-[var(--warna-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <?= lang('Tahfidz/AkunSaya.page_title') ?>
            </h1>
            <p class="text-sm md:text-base text-gray-600 dark:text-slate-300"><?= lang('Tahfidz/AkunSaya.page_subtitle') ?></p>
        </div>
    </div>
</div>

<div class="mb-6 rounded-2xl shadow-lg overflow-hidden border-0">
    <div class="bg-[var(--warna-primary)] p-6 md:p-8 text-white relative">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -translate-y-1/2 translate-x-1/3 blur-2xl"></div>

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
                            <span class="text-sm font-medium text-gray-100 tracking-wide">@<?= esc(session()->get('username')) ?></span>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-center md:justify-end gap-2 mt-2 md:mt-0">
                        <span class="inline-flex items-center px-3 py-1.5 bg-white/10 backdrop-blur-md border border-white/20 text-white rounded-full text-xs font-bold uppercase tracking-wide shadow-sm">
                            <svg class="w-3.5 h-3.5 mr-1.5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <?= session()->get('role_label') ?? 'Guru Tahfidz' ?>
                        </span>
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
        <?= lang('Tahfidz/AkunSaya.personal_title') ?>
    </h3>

    <form id="personalInfoForm">
        <?= csrf_field(); ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Tahfidz/AkunSaya.full_name') ?> <span class="text-red-500">*</span></label>
                <input type="text" name="nama_lengkap" value="<?= esc($user['nama_lengkap'] ?? '') ?>" class="w-full px-4 py-2 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg focus:border-[var(--warna-primary)] outline-none" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Tahfidz/AkunSaya.email') ?> <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="<?= esc($user['email'] ?? '') ?>" class="w-full px-4 py-2 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg focus:border-[var(--warna-primary)] outline-none" required>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Tahfidz/AkunSaya.phone') ?></label>
                <input type="tel" name="no_hp" value="<?= esc($user['no_hp'] ?? '') ?>" class="w-full px-4 py-2 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg focus:border-[var(--warna-primary)] outline-none">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Tahfidz/AkunSaya.address') ?></label>
                <textarea name="alamat" class="w-full px-4 py-2 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white rounded-lg focus:border-[var(--warna-primary)] outline-none" rows="3"><?= esc($user['alamat'] ?? '') ?></textarea>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <button type="submit" id="btnSaveInfo" class="bg-[var(--warna-primary)] px-6 py-2.5 text-white font-bold rounded-xl hover:brightness-90 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span id="textSaveInfo"><?= lang('Tahfidz/AkunSaya.btn_save_changes') ?></span>
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
            <?= lang('Tahfidz/AkunSaya.account_sec') ?>
        </h3>

        <div class="p-4 bg-gray-50 dark:bg-slate-700 rounded-xl border border-gray-100 dark:border-slate-600 mb-4">
            <div class="flex items-center justify-between mb-2">
                <p class="font-semibold text-gray-900 dark:text-white"><?= lang('Tahfidz/AkunSaya.password') ?></p>
                <button onclick="showChangePasswordModal()" class="text-sm text-white bg-[var(--warna-primary)] px-3 py-1.5 rounded-lg hover:brightness-90 font-bold transition-all">
                    <?= lang('Tahfidz/AkunSaya.btn_change_pass') ?>
                </button>
            </div>
            <p class="text-sm text-gray-600 dark:text-slate-300"><?= lang('Tahfidz/AkunSaya.sec_desc') ?></p>
        </div>

        <div class="p-4 bg-[var(--warna-secondary)] border border-[var(--warna-primary)] rounded-xl">
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 text-[var(--warna-primary)] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="text-sm text-[var(--warna-primary)] font-semibold mb-1"><?= lang('Tahfidz/AkunSaya.sec_tips') ?></p>
                    <ul class="text-sm text-[var(--warna-primary)] space-y-1">
                        <li>• <?= lang('Tahfidz/AkunSaya.tip_1') ?></li>
                        <li>• <?= lang('Tahfidz/AkunSaya.tip_2') ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-[var(--warna-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            </svg>
            <?= lang('Tahfidz/AkunSaya.sys_pref') ?>
        </h3>

        <?php
        $currentLang = session()->get('bahasa') ?? 'id';
        $currentTheme = session()->get('theme') ?? 'light';
        ?>
        <div class="space-y-5">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Tahfidz/AkunSaya.sys_lang') ?></label>
                <select id="pref_bahasa" class="w-full border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white rounded-xl p-3 focus:border-[var(--warna-primary)] outline-none transition-colors">
                    <option value="id" <?= $currentLang == 'id' ? 'selected' : '' ?>><?= lang('Tahfidz/AkunSaya.lang_id') ?></option>
                    <option value="en" <?= $currentLang == 'en' ? 'selected' : '' ?>><?= lang('Tahfidz/AkunSaya.lang_en') ?></option>
                    <option value="ar" <?= $currentLang == 'ar' ? 'selected' : '' ?>><?= lang('Tahfidz/AkunSaya.lang_ar') ?></option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-3"><?= lang('Tahfidz/AkunSaya.display_mode') ?></label>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-700 cursor-pointer transition-colors bg-white dark:bg-slate-800">
                        <input type="radio" name="theme" value="light" <?= $currentTheme == 'light' ? 'checked' : '' ?> class="radio-custom accent-[var(--warna-primary)]">
                        <div class="flex items-center gap-2 flex-1">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white text-sm"><?= lang('Tahfidz/AkunSaya.light_mode') ?></p>
                                <p class="text-xs text-gray-500 dark:text-slate-400"><?= lang('Tahfidz/AkunSaya.light_desc') ?></p>
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
                                <p class="font-semibold text-gray-900 dark:text-white text-sm"><?= lang('Tahfidz/AkunSaya.dark_mode') ?></p>
                                <p class="text-xs text-gray-500 dark:text-slate-400"><?= lang('Tahfidz/AkunSaya.dark_desc') ?></p>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button id="btnSavePref" onclick="savePreferences()" class="bg-[var(--warna-primary)] px-6 py-2.5 text-white font-bold rounded-xl hover:brightness-90 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span id="textSavePref"><?= lang('Tahfidz/AkunSaya.btn_save_pref') ?></span>
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
        <?= lang('Tahfidz/AkunSaya.recent_login') ?>
    </h3>
    <div class="overflow-x-auto rounded-xl border border-gray-100 dark:border-slate-700">
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-slate-700/50 border-b border-gray-100 dark:border-slate-700">
                <tr>
                    <th class="py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-300"><?= lang('Tahfidz/AkunSaya.th_time') ?></th>
                    <th class="py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-300"><?= lang('Tahfidz/AkunSaya.th_device') ?></th>
                    <th class="py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-300"><?= lang('Tahfidz/AkunSaya.th_ip') ?></th>
                    <th class="py-3 px-4 text-sm font-semibold text-gray-600 dark:text-slate-300"><?= lang('Tahfidz/AkunSaya.th_status') ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                <?php if (empty($login_logs)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-6 text-gray-500 dark:text-slate-400"><?= lang('Tahfidz/AkunSaya.empty_login') ?></td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($login_logs as $log): ?>
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition">
                            <td class="py-3 px-4 font-medium text-gray-900 dark:text-white">
                                <?= date('d M Y', strtotime($log['created_at'])) ?><br>
                                <span class="text-xs text-gray-500 dark:text-slate-400"><?= date('H:i', strtotime($log['created_at'])) ?></span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-gray-100 dark:bg-slate-600 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white text-sm"><?= htmlspecialchars($log['device_name'] ?? 'Unknown Device') ?></p>
                                        <p class="text-xs text-gray-500 dark:text-slate-400"><?= htmlspecialchars($log['browser_name'] ?? 'Unknown Browser') ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 font-mono text-sm text-gray-700 dark:text-slate-300"><?= htmlspecialchars($log['ip_address']) ?></td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-full text-xs font-bold"><?= htmlspecialchars($log['status'] ?? 'Success') ?></span>
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
            <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2"><?= lang('Tahfidz/AkunSaya.modal_pass_title') ?></h3>
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

            <div class="flex gap-3">
                <button type="button" onclick="closePasswordModal()" class="flex-1 py-3 px-4 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors"><?= lang('Tahfidz/AkunSaya.btn_cancel') ?></button>
                <button type="submit" id="btnSavePassword" class="flex-1 py-3 px-4 bg-[var(--warna-primary)] text-white font-bold rounded-xl hover:brightness-90 transition-all shadow-md">
                    <span id="textSavePassword"><?= lang('Tahfidz/AkunSaya.btn_change_pass') ?></span>
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const BASE_URL = document.querySelector('meta[name="base-url"]').content;
    const CSRF_TOKEN = document.querySelector('meta[name="X-CSRF-TOKEN"]').content;

    const LANG = {
        saving: "<?= lang('Tahfidz/AkunSaya.js_saving') ?>",
        success: "<?= lang('Tahfidz/AkunSaya.js_success') ?? 'Berhasil!' ?>",
        failed: "<?= lang('Tahfidz/AkunSaya.js_failed') ?? 'Gagal!' ?>",
        server_error: "<?= lang('Tahfidz/AkunSaya.js_server_error') ?? 'Terjadi kesalahan server.' ?>",
        err_pwd_match: "<?= lang('Tahfidz/AkunSaya.js_err_pwd_match') ?>",
        err_pwd_len: "<?= lang('Tahfidz/AkunSaya.js_err_pwd_len') ?>",
        err_img_size: "<?= lang('Tahfidz/AkunSaya.js_err_img_size') ?>",
        uploading: "<?= lang('Tahfidz/AkunSaya.js_uploading') ?>",
        photo_updated: "<?= lang('Tahfidz/AkunSaya.js_photo_updated') ?? 'Foto diperbarui.' ?>",
        btn_change_pass: "<?= lang('Tahfidz/AkunSaya.btn_change_pass') ?>",
        btn_save_changes: "<?= lang('Tahfidz/AkunSaya.btn_save_changes') ?>"
    };
</script>
<script src="<?= base_url('assets/js/Tahfidz/akun-saya.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>