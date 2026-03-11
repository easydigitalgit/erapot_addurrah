<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('Siswa/Dashboard.page_title_browser') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<meta name="X-CSRF-TOKEN" content="<?= csrf_hash() ?>">
<meta name="base-url" content="<?= rtrim(base_url(), '/') ?>">
<style>
  :root {
    --warna-primary: <?= $color['warna_primary'] ?>;
    --warna-secondary: <?= $color['warna_secondary'] ?>;
  }
  
  .glass-card {
      background: linear-gradient(135deg, var(--warna-primary) 0%, #1e40af 100%);
  }
  
  .info-label {
      font-size: 0.75rem;
      font-weight: 800;
      color: #6B7280; 
      text-transform: uppercase;
      letter-spacing: 0.05em;
      margin-bottom: 0.25rem;
      display: block;
  }
  .info-value {
      font-size: 1rem;
      font-weight: 600;
      color: #111827; 
      background-color: #F9FAFB; 
      padding: 0.75rem 1rem;
      border-radius: 0.5rem;
      border: 1px solid #F3F4F6; 
  }
  .dark .info-value {
      color: #F9FAFB;
      background-color: #1F2937;
      border-color: #374151;
  }
  
  /* Radio Custom untuk Theme */
  .radio-custom {
      width: 1.25rem;
      height: 1.25rem;
      cursor: pointer;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-4xl font-black text-gray-900 dark:text-white mb-2 transition-colors duration-300"><?= lang('Siswa/Dashboard.my_profile') ?></h1>
            <p class="text-base text-gray-600 dark:text-slate-400 font-medium transition-colors duration-300"><?= lang('Siswa/Dashboard.profile_desc') ?></p>
        </div>
        <?php if(isset($siswa['status_siswa'])): ?>
            <span class="px-4 py-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-black rounded-xl uppercase tracking-wide border-2 border-emerald-200 dark:border-emerald-800/50 shadow-sm transition-colors duration-300">
                <?= lang('Siswa/Dashboard.status') ?> <?= esc($siswa['status_siswa']) ?>
            </span>
        <?php endif; ?>
    </div>

    <div class="glass-card rounded-3xl p-6 text-white shadow-xl mb-8 relative overflow-hidden transition-all duration-300" style="background-color: <?= $color['warna_primary'] ?>;">
        <svg class="absolute top-0 right-0 transform translate-x-1/3 -translate-y-1/3 w-64 h-64 text-white opacity-10" fill="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
            <?php 
                $namaSiswa = $siswa['nama_lengkap'] ?? 'Siswa';
                $inisial = strtoupper(substr($namaSiswa, 0, 2)); 
                
                $fotoProfil = $siswa['foto_profil'] ?? null;
                $pathFisik = FCPATH . 'assets/uploads/siswa/' . $fotoProfil;
                
                if ($fotoProfil && file_exists($pathFisik)) {
                    $avatarUrl = base_url('assets/uploads/siswa/' . $fotoProfil);
                } else {
                    $avatarUrl = "https://ui-avatars.com/api/?name={$inisial}&background=ffffff&color=1F7A4D&size=160&bold=true&rounded=true";
                }
            ?>
            
            <div class="avatar-upload w-24 h-24 md:w-32 md:h-32 relative group shrink-0 z-10">
                <img src="<?= $avatarUrl ?>" 
                     alt="Avatar" 
                     class="w-full h-full rounded-full object-cover border-4 border-white/30 shadow-xl transition-all duration-300 bg-white dark:bg-slate-800" 
                     id="avatarImage">
                
                <label for="avatarUpload" class="absolute inset-0 flex items-center justify-center bg-black/40 hover:bg-black/60 rounded-full cursor-pointer transition-all duration-300 opacity-0 group-hover:opacity-100">
                    <svg class="w-8 h-8 text-white drop-shadow-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </label>
                <input type="file" id="avatarUpload" name="avatar" accept="image/png, image/jpeg, image/jpg" class="hidden">
            </div>

            <div class="text-center md:text-left flex-1">
                <h2 class="text-2xl md:text-3xl font-black mb-2 tracking-wide drop-shadow-sm">
                    <?= isset($siswa['nama_lengkap']) ? esc($siswa['nama_lengkap']) : lang('Siswa/Dashboard.incomplete_profile') ?>
                </h2>
                <div class="flex flex-wrap justify-center md:justify-start gap-3 text-sm font-bold text-white/90">
                    <span class="bg-black/20 px-4 py-1.5 rounded-lg backdrop-blur-sm border border-white/10 shadow-inner"><?= lang('Siswa/Dashboard.nis_nisn') ?>: <?= isset($siswa['nis']) ? esc($siswa['nis']) : '-' ?></span>
                    <span class="bg-black/20 px-4 py-1.5 rounded-lg backdrop-blur-sm border border-white/10 shadow-inner">
                        <?= lang('Siswa/Dashboard.class') ?> <?= isset($siswa['tingkat']) ? esc($siswa['tingkat']) : '' ?> <?= isset($siswa['nama_rombel']) ? esc($siswa['nama_rombel']) : lang('Siswa/Dashboard.not_in_class') ?>
                    </span>
                    <span class="bg-white/20 text-white px-4 py-1.5 rounded-lg backdrop-blur-sm border border-white/30 flex items-center gap-1 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg> 
                        <?= lang('Siswa/Dashboard.homeroom') ?> <?= isset($siswa['nama_wali_kelas']) ? esc($siswa['nama_wali_kelas']) : lang('Siswa/Dashboard.not_set') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-lg border border-gray-200 dark:border-slate-700 overflow-hidden mb-8 transition-colors duration-300">
        <div class="p-6 bg-gray-50 dark:bg-slate-900/50 border-b border-gray-200 dark:border-slate-700 transition-colors duration-300">
            <h2 class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-[var(--warna-primary)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                <?= lang('Siswa/Dashboard.personal_academic_data') ?>
            </h2>
        </div>
        
        <div class="p-6 lg:p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <span class="info-label"><?= lang('Siswa/Dashboard.full_name') ?></span>
                    <div class="info-value transition-colors duration-300"><?= isset($siswa['nama_lengkap']) ? esc($siswa['nama_lengkap']) : '-' ?></div>
                </div>
                <div>
                    <span class="info-label"><?= lang('Siswa/Dashboard.nis_nisn') ?></span>
                    <div class="info-value transition-colors duration-300 font-mono">
                        <?= isset($siswa['nis']) && $siswa['nis'] != '' ? esc($siswa['nis']) : '-' ?> / 
                        <?= isset($siswa['nisn']) && $siswa['nisn'] != '' ? esc($siswa['nisn']) : '-' ?>
                    </div>
                </div>
                <div>
                    <span class="info-label"><?= lang('Siswa/Dashboard.gender') ?></span>
                    <div class="info-value transition-colors duration-300">
                        <?php 
                            if(isset($siswa['jenis_kelamin'])) {
                                echo $siswa['jenis_kelamin'] == 'L' ? lang('Siswa/Dashboard.male') : lang('Siswa/Dashboard.female');
                            } else {
                                echo '-';
                            }
                        ?>
                    </div>
                </div>

                <div>
                    <span class="info-label"><?= lang('Siswa/Dashboard.birth_place_date') ?></span>
                    <div class="info-value transition-colors duration-300">
                        <?= isset($siswa['tempat_lahir']) && $siswa['tempat_lahir'] != '' ? esc($siswa['tempat_lahir']) : '-' ?>, 
                        <?= isset($siswa['tanggal_lahir']) && $siswa['tanggal_lahir'] != '' ? date('d-m-Y', strtotime($siswa['tanggal_lahir'])) : '-' ?>
                    </div>
                </div>
                <div>
                    <span class="info-label"><?= lang('Siswa/Dashboard.religion') ?></span>
                    <div class="info-value transition-colors duration-300">
                        <?= isset($siswa['agama']) && $siswa['agama'] != '' ? esc($siswa['agama']) : '-' ?>
                    </div>
                </div>
                <div>
                    <span class="info-label"><?= lang('Siswa/Dashboard.student_email') ?></span>
                    <div class="info-value transition-colors duration-300 truncate" title="<?= isset($siswa['email_siswa']) ? esc($siswa['email_siswa']) : '-' ?>">
                        <?= isset($siswa['email_siswa']) && $siswa['email_siswa'] != '' ? esc($siswa['email_siswa']) : '-' ?>
                    </div>
                </div>

                <div>
                    <span class="info-label"><?= lang('Siswa/Dashboard.family_status') ?></span>
                    <div class="info-value transition-colors duration-300">
                        <?= isset($siswa['status_dalam_keluarga']) && $siswa['status_dalam_keluarga'] != '' ? esc($siswa['status_dalam_keluarga']) : '-' ?>
                    </div>
                </div>
                <div>
                    <span class="info-label"><?= lang('Siswa/Dashboard.child_order') ?></span>
                    <div class="info-value transition-colors duration-300">
                        <?= isset($siswa['anak_ke']) && $siswa['anak_ke'] != '' ? esc($siswa['anak_ke']) : '-' ?>
                    </div>
                </div>
                <div>
                    <span class="info-label"><?= lang('Siswa/Dashboard.phone_home') ?></span>
                    <div class="info-value transition-colors duration-300 font-mono">
                        <?= isset($siswa['no_telp_rumah']) && $siswa['no_telp_rumah'] != '' ? esc($siswa['no_telp_rumah']) : '-' ?>
                    </div>
                </div>

                <div class="md:col-span-2 lg:col-span-3">
                    <span class="info-label"><?= lang('Siswa/Dashboard.full_address') ?></span>
                    <div class="info-value min-h-[4rem] transition-colors duration-300">
                        <?= isset($siswa['alamat_siswa']) && $siswa['alamat_siswa'] != '' ? esc($siswa['alamat_siswa']) : '-' ?>
                    </div>
                </div>

                <div class="md:col-span-2 lg:col-span-3 mt-4 pt-6 border-t-2 border-dashed border-gray-200 dark:border-slate-700 transition-colors duration-300">
                    <h3 class="text-lg font-black text-gray-800 dark:text-white mb-5 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        <?= lang('Siswa/Dashboard.enrollment_history') ?>
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <span class="info-label"><?= lang('Siswa/Dashboard.origin_school') ?></span>
                            <div class="info-value transition-colors duration-300">
                                <?= isset($siswa['asal_sekolah']) && $siswa['asal_sekolah'] != '' ? esc($siswa['asal_sekolah']) : '-' ?>
                            </div>
                        </div>
                        <div>
                            <span class="info-label"><?= lang('Siswa/Dashboard.accepted_in_class') ?></span>
                            <div class="info-value font-black text-[var(--warna-primary)] transition-colors duration-300">
                                <?= isset($siswa['diterima_dikelas']) && $siswa['diterima_dikelas'] != '' ? esc($siswa['diterima_dikelas']) : '-' ?>
                            </div>
                        </div>
                        <div>
                            <span class="info-label"><?= lang('Siswa/Dashboard.accepted_date') ?></span>
                            <div class="info-value transition-colors duration-300">
                                <?= isset($siswa['tgl_diterima']) && $siswa['tgl_diterima'] != '' ? date('d-m-Y', strtotime($siswa['tgl_diterima'])) : '-' ?>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700 transition-colors duration-300">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg> 
                <?= lang('Siswa/Dashboard.account_security') ?>
            </h3>
            
            <div class="space-y-4">
                <div class="p-4 bg-gray-50 dark:bg-slate-700 rounded-xl border border-gray-100 dark:border-slate-600 transition-colors">
                    <div class="flex items-center justify-between mb-2">
                        <p class="font-semibold text-gray-900 dark:text-white"><?= lang('Siswa/Dashboard.password') ?></p>
                        <button onclick="showChangePasswordModal()" class="text-sm font-bold text-white bg-[<?= $color['warna_primary'] ?>] px-3 py-1.5 rounded-lg hover:brightness-90 transition-all"> 
                            <?= lang('Siswa/Dashboard.btn_change_pass') ?> 
                        </button>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-slate-300"><?= lang('Siswa/Dashboard.sec_desc') ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-sm border border-gray-200 dark:border-slate-700 transition-colors duration-300">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg> 
                <?= lang('Siswa/Dashboard.sys_pref') ?>
            </h3>
            
            <?php 
                // AMBIL DATA DARI SESSION SECARA LANGSUNG
                $currentLang = session()->get('bahasa') ?? 'id';
                $currentTheme = session()->get('theme') ?? 'light';
            ?>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-2"><?= lang('Siswa/Dashboard.sys_lang') ?></label> 
                    <select id="pref_bahasa" class="w-full border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white rounded-xl p-3 focus:border-[<?= $color['warna_primary'] ?>] outline-none shadow-sm transition-colors"> 
                        <option value="id" <?= $currentLang == 'id' ? 'selected' : '' ?>><?= lang('Siswa/Dashboard.lang_id') ?></option> 
                        <option value="en" <?= $currentLang == 'en' ? 'selected' : '' ?>><?= lang('Siswa/Dashboard.lang_en') ?></option> 
                        <option value="ar" <?= $currentLang == 'ar' ? 'selected' : '' ?>><?= lang('Siswa/Dashboard.lang_ar') ?></option> 
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-3"><?= lang('Siswa/Dashboard.display_mode') ?></label>
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-700 cursor-pointer transition-colors shadow-sm bg-white dark:bg-slate-800"> 
                            <input type="radio" name="theme" value="light" <?= $currentTheme == 'light' ? 'checked' : '' ?> class="radio-custom accent-[<?= $color['warna_primary'] ?>]">
                            <div class="flex items-center gap-2 flex-1">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white text-sm"><?= lang('Siswa/Dashboard.light_mode') ?></p>
                                    <p class="text-xs text-gray-500 dark:text-slate-400"><?= lang('Siswa/Dashboard.light_desc') ?></p>
                                </div>
                            </div>
                        </label> 
                        
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-700 cursor-pointer transition-colors shadow-sm bg-white dark:bg-slate-800"> 
                            <input type="radio" name="theme" value="dark" <?= $currentTheme == 'dark' ? 'checked' : '' ?> class="radio-custom accent-[<?= $color['warna_primary'] ?>]">
                            <div class="flex items-center gap-2 flex-1">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" /></svg>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white text-sm"><?= lang('Siswa/Dashboard.dark_mode') ?></p>
                                    <p class="text-xs text-gray-500 dark:text-slate-400"><?= lang('Siswa/Dashboard.dark_desc') ?></p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div class="flex justify-end pt-4">
                    <button id="btnSavePref" onclick="savePreferences()" class="bg-[<?= $color['warna_primary'] ?>] px-5 py-2.5 text-white font-bold rounded-xl hover:brightness-90 transition-all shadow-md flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> 
                        <span id="textSavePref"><?= lang('Siswa/Dashboard.btn_save_pref') ?></span> 
                    </button>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<div id="passwordModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-800 rounded-3xl w-full max-w-md p-6 transform scale-95 transition-transform duration-300 shadow-2xl border border-gray-100 dark:border-slate-700">
        <div class="text-center mb-6">
            <div class="w-16 h-16 rounded-full bg-[<?= $color['warna_primary'] ?>]/10 flex items-center justify-center mx-auto mb-4 border-2 border-[<?= $color['warna_primary'] ?>]/20">
                <svg class="w-8 h-8 text-[<?= $color['warna_primary'] ?>]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>
            </div>
            <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2"><?= lang('Siswa/Dashboard.modal_pass_title') ?></h3>
            <p class="text-gray-600 dark:text-slate-400 text-sm"><?= lang('Siswa/Dashboard.modal_pass_desc') ?></p>
        </div>
        
        <form id="changePasswordForm">
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1"><?= lang('Siswa/Dashboard.old_pass') ?></label> 
                    <input type="password" id="oldPassword" class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:bg-white outline-none transition-all shadow-sm" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1"><?= lang('Siswa/Dashboard.new_pass') ?></label> 
                    <input type="password" id="newPassword" class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:bg-white outline-none transition-all shadow-sm" required>
                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-1.5"><?= lang('Siswa/Dashboard.pass_min_char') ?></p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-slate-300 mb-1"><?= lang('Siswa/Dashboard.conf_new_pass') ?></label> 
                    <input type="password" id="confirmPassword" class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-700 text-gray-900 dark:text-white rounded-xl focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>] focus:bg-white outline-none transition-all shadow-sm" required>
                </div>
                
                <div class="flex items-center justify-between mt-1">
                    <div class="flex gap-1 h-1.5 w-full mr-4">
                        <div id="strength-bar-1" class="h-full w-1/4 bg-gray-200 dark:bg-slate-600 rounded-full transition-colors duration-300"></div>
                        <div id="strength-bar-2" class="h-full w-1/4 bg-gray-200 dark:bg-slate-600 rounded-full transition-colors duration-300"></div>
                        <div id="strength-bar-3" class="h-full w-1/4 bg-gray-200 dark:bg-slate-600 rounded-full transition-colors duration-300"></div>
                        <div id="strength-bar-4" class="h-full w-1/4 bg-gray-200 dark:bg-slate-600 rounded-full transition-colors duration-300"></div>
                    </div>
                    <span id="password-strength-text" class="text-xs font-bold text-gray-400 w-16 text-right"></span>
                </div>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 rounded-xl p-4 mb-6">
                <p class="text-xs text-blue-800 dark:text-blue-300 flex items-start gap-2 leading-relaxed">
                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span><?= lang('Siswa/Dashboard.pass_warn') ?></span>
                </p>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closePasswordModal()" class="flex-1 py-3 px-4 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors"><?= lang('Siswa/Dashboard.btn_cancel') ?></button> 
                <button type="submit" id="btnSavePassword" class="flex-1 py-3 px-4 bg-[<?= $color['warna_primary'] ?>] text-white font-bold rounded-xl hover:brightness-90 transition-all shadow-md flex items-center justify-center gap-2">
                    <span id="textSavePassword"><?= lang('Siswa/Dashboard.btn_change_pass') ?></span>
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
        saving: <?= json_encode(lang('Siswa/Dashboard.js_saving')) ?>,
        success: <?= json_encode(lang('Siswa/Dashboard.js_success')) ?>,
        failed: <?= json_encode(lang('Siswa/Dashboard.js_failed')) ?>,
        server_error: <?= json_encode(lang('Siswa/Dashboard.js_server_error')) ?>,
        err_pwd_match: <?= json_encode(lang('Siswa/Dashboard.js_err_pwd_match')) ?>,
        err_pwd_len: <?= json_encode(lang('Siswa/Dashboard.js_err_pwd_len')) ?>,
        err_img_size: <?= json_encode(lang('Siswa/Dashboard.js_err_img_size')) ?>,
        uploading: <?= json_encode(lang('Siswa/Dashboard.js_uploading')) ?>,
        photo_updated: <?= json_encode(lang('Siswa/Dashboard.js_photo_updated')) ?>,
        pass_weak: <?= json_encode(lang('Siswa/Dashboard.js_pass_weak')) ?>,
        pass_medium: <?= json_encode(lang('Siswa/Dashboard.js_pass_medium')) ?>,
        pass_strong: <?= json_encode(lang('Siswa/Dashboard.js_pass_strong')) ?>,
        btn_change_pass: <?= json_encode(lang('Siswa/Dashboard.btn_change_pass')) ?>,
        btn_save_pref: <?= json_encode(lang('Siswa/Dashboard.btn_save_pref')) ?>
    };
</script>
<script src="<?= base_url('assets/js/Siswa/dashboard.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>