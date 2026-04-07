<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= lang('Admin/ProfileSekolah.page_title_browser') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root {
        --warna-scroll: <?= $color['warna_primary'] ?>;
    }
</style>
<link rel="stylesheet" href="<?= base_url('assets/css/Admin/profile-sekolah.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<form id="profileForm" onsubmit="handleSubmit(event)" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-3 transition-colors">
            <span><?= lang('Admin/ProfileSekolah.system_menu') ?></span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-[<?= $color['warna_primary'] ?>] font-medium"><?= lang('Admin/ProfileSekolah.page_title') ?></span>
        </div>

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-3 transition-colors">
                    <svg class="w-8 h-8 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <?= lang('Admin/ProfileSekolah.page_title') ?>
                </h1>
                <p class="text-sm md:text-base text-gray-600 dark:text-slate-400 transition-colors"><?= lang('Admin/ProfileSekolah.page_subtitle') ?></p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button type="button" onclick="resetForm()" class="px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-xl text-gray-700 dark:text-slate-300 font-medium hover:bg-gray-50 dark:hover:bg-slate-700 flex items-center gap-2 transition-colors shadow-sm outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span><?= lang('Admin/ProfileSekolah.btn_reset') ?></span>
                </button>

                <button type="button" onclick="document.getElementById('profileForm').requestSubmit()" class="px-5 py-2.5 bg-[<?= $color['warna_primary'] ?>]/90 hover:bg-[<?= $color['warna_primary'] ?>] text-white font-bold rounded-xl flex items-center gap-2 shadow-lg transition-transform transform hover:-translate-y-0.5 outline-none" style="box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    <span><?= lang('Admin/ProfileSekolah.btn_save') ?></span>
                </button>
            </div>
        </div>

        <div class="bg-[<?= $color['warna_secondary'] ?>]/50 dark:bg-slate-800 border-l-4 border-[<?= $color['warna_primary'] ?>] p-4 rounded-r-xl mb-6 flex items-start gap-3 shadow-sm transition-colors">
            <div class="text-[<?= $color['warna_primary'] ?>] mt-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h4 class="font-bold text-[<?= $color['warna_primary'] ?>] mb-1"><?= lang('Admin/ProfileSekolah.info_title') ?></h4>
                <p class="text-sm font-medium text-gray-700 dark:text-slate-300 leading-relaxed"><?= lang('Admin/ProfileSekolah.info_desc') ?></p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 md:p-8 mb-6 transition-colors">
        <div class="mb-6 border-b border-gray-100 dark:border-slate-700 pb-4 transition-colors">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white"><?= lang('Admin/ProfileSekolah.main_identity') ?></h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/ProfileSekolah.school_name') ?> <span class="text-red-500">*</span></label>
                <input type="text" name="nama_sekolah" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none placeholder-gray-400 dark:placeholder-slate-400"
                    value="<?= esc($sekolah['nama_sekolah'] ?? '') ?>" placeholder="<?= lang('Admin/ProfileSekolah.school_name_placeholder') ?>" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/ProfileSekolah.npsn') ?> <span class="text-red-500">*</span></label>
                <input type="text" name="npsn" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none"
                    value="<?= esc($sekolah['npsn'] ?? '') ?>" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/ProfileSekolah.nss') ?></label>
                <input type="text" name="nss" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none"
                    value="<?= esc($sekolah['nss'] ?? '') ?>">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/ProfileSekolah.founded_year') ?> <span class="text-red-500">*</span></label>
                <input type="number" name="tahun_berdiri" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none"
                    value="<?= esc($sekolah['tahun_berdiri'] ?? '') ?>" required>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/ProfileSekolah.education_level') ?></label>
                <select name="jenjang" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none appearance-none cursor-pointer">
                    <option value="SMPIT" <?= ($sekolah['jenjang'] ?? '') == 'SMPIT' ? 'selected' : '' ?>>SMPIT</option>
                    <option value="SDIT" <?= ($sekolah['jenjang'] ?? '') == 'SDIT' ? 'selected' : '' ?>>SDIT</option>
                    <option value="SMAIT" <?= ($sekolah['jenjang'] ?? '') == 'SMAIT' ? 'selected' : '' ?>>SMAIT</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/ProfileSekolah.school_status') ?></label>
                <select name="status_sekolah" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none appearance-none cursor-pointer">
                    <option value="Swasta" <?= ($sekolah['status_sekolah'] ?? '') == 'Swasta' ? 'selected' : '' ?>><?= lang('Admin/ProfileSekolah.status_private') ?></option>
                    <option value="Negeri" <?= ($sekolah['status_sekolah'] ?? '') == 'Negeri' ? 'selected' : '' ?>><?= lang('Admin/ProfileSekolah.status_public') ?></option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/ProfileSekolah.accreditation') ?></label>
                <select name="akreditasi" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none appearance-none cursor-pointer">
                    <option value="A" <?= ($sekolah['akreditasi'] ?? '') == 'A' ? 'selected' : '' ?>>A</option>
                    <option value="B" <?= ($sekolah['akreditasi'] ?? '') == 'B' ? 'selected' : '' ?>>B</option>
                    <option value="C" <?= ($sekolah['akreditasi'] ?? '') == 'C' ? 'selected' : '' ?>>C</option>
                    <option value="Belum" <?= ($sekolah['akreditasi'] ?? '') == 'Belum' ? 'selected' : '' ?>><?= lang('Admin/ProfileSekolah.accreditation_none') ?></option>
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 md:p-8 mb-6 transition-colors">
        <div class="flex items-center gap-4 mb-6 border-b border-gray-100 dark:border-slate-700 pb-4 transition-colors">
            <div class="p-3 bg-[<?= $color['warna_secondary'] ?>] dark:bg-[<?= $color['warna_primary'] ?>]/20 rounded-xl shadow-sm">
                <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white"><?= lang('Admin/ProfileSekolah.address_contact') ?></h3>
                <p class="text-sm font-medium text-gray-600 dark:text-slate-400 mt-1"><?= lang('Admin/ProfileSekolah.address_contact_desc') ?></p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/ProfileSekolah.full_address') ?></label>
                <textarea name="alamat" rows="3" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none resize-none placeholder-gray-400 dark:placeholder-slate-400" placeholder="<?= lang('Admin/ProfileSekolah.full_address_placeholder') ?>"><?= esc($sekolah['alamat'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/ProfileSekolah.province') ?></label>
                <select name="provinsi" id="provinsi" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none appearance-none cursor-pointer" onchange="loadKabupaten(this.value)">
                    <option value=""><?= lang('Admin/ProfileSekolah.select_province') ?></option>
                    <?php foreach ($list_propinsi as $p): ?>
                        <option value="<?= $p['kode'] ?>" <?= ($sekolah['provinsi'] ?? '') == $p['kode'] ? 'selected' : '' ?>>
                            <?= $p['nama'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/ProfileSekolah.regency_city') ?></label>
                <select name="kabupaten" id="kabupaten" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed" onchange="loadKecamatan(this.value)" disabled>
                    <option value=""><?= lang('Admin/ProfileSekolah.select_province_first') ?></option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/ProfileSekolah.district') ?></label>
                <select name="kecamatan" id="kecamatan" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed" onchange="loadDesa(this.value)" disabled>
                    <option value=""><?= lang('Admin/ProfileSekolah.select_regency_first') ?></option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/ProfileSekolah.village') ?></label>
                <select name="desa_id" id="desa" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <option value=""><?= lang('Admin/ProfileSekolah.select_district_first') ?></option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/ProfileSekolah.postal_code') ?></label>
                <input type="text" name="kode_pos" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none" value="<?= esc($sekolah['kode_pos'] ?? '') ?>" maxlength="5">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/ProfileSekolah.phone_number') ?></label>
                <input type="text" name="telepon" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none" value="<?= esc($sekolah['telepon'] ?? '') ?>">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/ProfileSekolah.official_email') ?></label>
                <input type="email" name="email" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none" value="<?= esc($sekolah['email'] ?? '') ?>">
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Admin/ProfileSekolah.website') ?></label>
                <input type="url" name="website" class="w-full px-4 py-3 bg-gray-50 dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none placeholder-gray-400 dark:placeholder-slate-400" value="<?= esc($sekolah['website'] ?? '') ?>" placeholder="https://...">
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700 p-6 md:p-8 mb-6 transition-colors">
        <div class="flex items-center gap-4 mb-6 border-b border-gray-100 dark:border-slate-700 pb-4 transition-colors">
            <div class="p-3 bg-[<?= $color['warna_secondary'] ?>] dark:bg-[<?= $color['warna_primary'] ?>]/20 rounded-xl shadow-sm">
                <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white"><?= lang('Admin/ProfileSekolah.logo_visual') ?></h3>
                <p class="text-sm font-medium text-gray-600 dark:text-slate-400 mt-1"><?= lang('Admin/ProfileSekolah.logo_visual_desc') ?></p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12">
            <div>
                <label class="block text-sm font-black text-gray-800 dark:text-slate-200 mb-3 uppercase tracking-wider"><?= lang('Admin/ProfileSekolah.school_logo') ?></label>
                <div id="logoUploadArea" class="upload-area border-2 border-dashed border-gray-300 dark:border-slate-600 bg-gray-50 dark:bg-slate-700/50 hover:bg-[<?= $color['warna_primary'] ?>]/5 dark:hover:bg-[<?= $color['warna_primary'] ?>]/10 hover:border-[<?= $color['warna_primary'] ?>] rounded-3xl p-8 text-center cursor-pointer relative transition-all duration-300 shadow-sm group"
                    onclick="document.getElementById('logoInput').click()">

                    <?php
                    $logoPath = !empty($sekolah['logo']) && $sekolah['logo'] != 'default_logo.png'
                        ? base_url('uploads/logo/' . $sekolah['logo'])
                        : 'https://via.placeholder.com/150?text=LOGO';
                    ?>

                    <div class="relative w-36 h-36 mx-auto mb-5 rounded-2xl bg-white dark:bg-slate-800 p-2 shadow-md border border-gray-100 dark:border-slate-600 group-hover:scale-105 transition-transform duration-300">
                        <img id="logoPreview" src="<?= $logoPath ?>" class="w-full h-full object-contain rounded-xl">
                        <div class="absolute inset-0 bg-black/40 rounded-xl opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <p class="text-[<?= $color['warna_primary'] ?>] font-black text-[13px] uppercase tracking-widest mb-1"><?= lang('Admin/ProfileSekolah.click_to_change_logo') ?></p>
                    <p class="text-xs font-medium text-gray-400 dark:text-slate-500"><?= lang('Admin/ProfileSekolah.logo_format') ?></p>

                    <input type="file" id="logoInput" name="logo_sekolah" class="hidden" accept="image/png, image/jpeg, image/jpg" onchange="previewImage(event)">
                </div>
            </div>

            <div>
                <label class="block text-sm font-black text-gray-800 dark:text-slate-200 mb-3 uppercase tracking-wider"><?= lang('Admin/ProfileSekolah.identity_color') ?></label>
                <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-6 leading-relaxed"><?= lang('Admin/ProfileSekolah.identity_color_desc') ?></p>

                <div class="space-y-5">

                    <div class="p-5 border border-gray-200 dark:border-slate-600 rounded-2xl bg-white dark:bg-slate-700/30 shadow-sm hover:shadow-md transition-shadow">
                        <label class="text-[11px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[<?= $color['warna_primary'] ?>]"></span> <?= lang('Admin/ProfileSekolah.primary_color') ?>
                        </label>
                        <div class="flex items-center gap-4">

                            <?php $valPrimary = '#' . ltrim(esc($sekolah['warna_primary'] ?? '1F7A4D'), '#'); ?>
                            <div id="display_primary" class="relative w-12 h-12 rounded-full shadow-inner border-4 border-white dark:border-slate-800 ring-1 ring-gray-200 dark:ring-slate-600 flex-shrink-0 transition-transform hover:scale-105 overflow-hidden" style="background-color: <?= $valPrimary ?>;">
                                <input type="color" name="warna_primary" id="picker_primary"
                                    value="<?= $valPrimary ?>"
                                    oninput="syncColor('primary', this.value)"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>

                            <div class="flex-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-400 dark:text-slate-500 font-black">#</span>
                                </div>
                                <input type="text" id="text_primary"
                                    class="w-full pl-8 pr-4 py-3.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl font-mono text-sm uppercase font-bold focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all outline-none shadow-inner"
                                    value="<?= ltrim($valPrimary, '#') ?>"
                                    oninput="syncColor('primary', '#' + this.value, true)" maxlength="6" placeholder="1F7A4D">
                            </div>
                        </div>
                    </div>

                    <div class="p-5 border border-gray-200 dark:border-slate-600 rounded-2xl bg-white dark:bg-slate-700/30 shadow-sm hover:shadow-md transition-shadow">
                        <label class="text-[11px] font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-[<?= $color['warna_secondary'] ?>]"></span> <?= lang('Admin/ProfileSekolah.secondary_color') ?>
                        </label>
                        <div class="flex items-center gap-4">

                            <?php $valSecondary = '#' . ltrim(esc($sekolah['warna_secondary'] ?? 'E6F4EC'), '#'); ?>
                            <div id="display_secondary" class="relative w-12 h-12 rounded-full shadow-inner border-4 border-white dark:border-slate-800 ring-1 ring-gray-200 dark:ring-slate-600 flex-shrink-0 transition-transform hover:scale-105 overflow-hidden" style="background-color: <?= $valSecondary ?>;">
                                <input type="color" name="warna_secondary" id="picker_secondary"
                                    value="<?= $valSecondary ?>"
                                    oninput="syncColor('secondary', this.value)"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>

                            <div class="flex-1 relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-400 dark:text-slate-500 font-black">#</span>
                                </div>
                                <input type="text" id="text_secondary"
                                    class="w-full pl-8 pr-4 py-3.5 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl font-mono text-sm uppercase font-bold focus:outline-none focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] transition-all outline-none shadow-inner"
                                    value="<?= ltrim($valSecondary, '#') ?>"
                                    oninput="syncColor('secondary', '#' + this.value, true)" maxlength="6" placeholder="E6F4EC">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row justify-end gap-4 mt-8 mb-10">
        <button type="button" onclick="resetForm()" class="px-8 py-3.5 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition-all shadow-sm flex items-center justify-center gap-2 outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            <?= lang('Admin/ProfileSekolah.btn_reset') ?>
        </button>

        <button type="submit" class="px-8 py-3.5 text-white font-bold rounded-xl transition-all transform hover:-translate-y-0.5 shadow-lg flex items-center justify-center gap-2 outline-none" style="background-color: <?= $color['warna_primary'] ?>; box-shadow: 0 10px 15px -3px <?= $color['warna_primary'] ?>40;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
            </svg>
            <?= lang('Admin/ProfileSekolah.btn_save_config') ?>
        </button>
    </div>

</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const API_URL_UPDATE = "<?= base_url('admin/profile-sekolah/update') ?>";
    const URL_GET_KAB = "<?= base_url('admin/profile-sekolah/get_kabupaten') ?>";
    const URL_GET_KEC = "<?= base_url('admin/profile-sekolah/get_kecamatan') ?>";
    const URL_GET_DESA = "<?= base_url('admin/profile-sekolah/get_desa') ?>";

    const savedProv = "<?= $sekolah['provinsi'] ?? '' ?>";
    const savedKab = "<?= $sekolah['kabupaten'] ?? '' ?>";
    const savedKec = "<?= $sekolah['kecamatan'] ?? '' ?>";
    const savedDesa = "<?= $sekolah['desa_id'] ?? '' ?>";

    const LANG = {
        js_loading: "<?= lang('Admin/ProfileSekolah.js_loading') ?>",
        select_province_first: "<?= lang('Admin/ProfileSekolah.select_province_first') ?>",
        select_regency_first: "<?= lang('Admin/ProfileSekolah.select_regency_first') ?>",
        select_district_first: "<?= lang('Admin/ProfileSekolah.select_district_first') ?>",
        
        // Logika cerdas: Cek nama kunci versi baru, jika tidak ada, pakai kunci versi lama (js_)
        select_regency: "<?= lang('Admin/ProfileSekolah.select_regency') !== 'Admin/ProfileSekolah.select_regency' ? lang('Admin/ProfileSekolah.select_regency') : lang('Admin/ProfileSekolah.js_select_regency') ?>",
        select_district: "<?= lang('Admin/ProfileSekolah.select_district') !== 'Admin/ProfileSekolah.select_district' ? lang('Admin/ProfileSekolah.select_district') : lang('Admin/ProfileSekolah.js_select_district') ?>",
        select_village: "<?= lang('Admin/ProfileSekolah.select_village') !== 'Admin/ProfileSekolah.select_village' ? lang('Admin/ProfileSekolah.select_village') : lang('Admin/ProfileSekolah.js_select_village') ?>",
        
        js_no_data: "<?= lang('Admin/ProfileSekolah.js_no_data') ?>",
        js_fail_load_region: "<?= lang('Admin/ProfileSekolah.js_fail_load_region') ?>",
        js_logo_uploaded: "<?= lang('Admin/ProfileSekolah.js_logo_uploaded') ?>",
        js_confirm_reset: "<?= lang('Admin/ProfileSekolah.js_confirm_reset') ?>",
        js_form_reset: "<?= lang('Admin/ProfileSekolah.js_form_reset') ?>",
        js_saving: "<?= lang('Admin/ProfileSekolah.js_saving') ?>",
        js_fail_server: "<?= lang('Admin/ProfileSekolah.js_fail_server') ?>",
        js_system_error: "<?= lang('Admin/ProfileSekolah.js_system_error') ?>"
    };
</script>
<script src="<?= base_url('assets/js/Admin/profile-sekolah.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>