<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
  <?= lang('Admin/Users.page_title_browser') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/Admin/users.css') ?>">
  <style>
    /* OPTIMASI PAGINATION BAWAAN CODEIGNITER 4 KE TAILWIND */
    .pagination-dark-wrapper .pagination {
        display: inline-flex;
        gap: 0.35rem;
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .pagination-dark-wrapper .pagination li a {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 0.5rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748b;
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .pagination-dark-wrapper .pagination li a:hover {
        background-color: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
    }
    .pagination-dark-wrapper .pagination li.active a {
        background-color: <?= $color['warna_primary'] ?>;
        color: #ffffff;
        border-color: <?= $color['warna_primary'] ?>;
        box-shadow: 0 4px 6px -1px <?= $color['warna_primary'] ?>40;
    }

    .dark .pagination-dark-wrapper .pagination li a {
        background-color: #1e293b;
        border-color: #334155;
        color: #94a3b8;
    }
    .dark .pagination-dark-wrapper .pagination li a:hover {
        background-color: #334155;
        color: #ffffff;
    }
    .dark .pagination-dark-wrapper .pagination li.active a {
        background-color: <?= $color['warna_primary'] ?>;
        color: #ffffff;
        border-color: <?= $color['warna_primary'] ?>;
    }
  </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <?php 
    $cards = [
        [
            'title' => lang('Admin/Users.total_users'),
            'count' => $stats['total'] ?? 0,
            'color' => 'text-blue-600 dark:text-blue-400',
            'bg'    => 'bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/20',
            'svg'   => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'
        ],
        [
            'title' => lang('Admin/Users.teachers'),
            'count' => $stats['guru'] ?? 0,
            'color' => 'text-purple-600 dark:text-purple-400',
            'bg'    => 'bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-800/20',
            'svg'   => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'
        ],
        [
            'title' => lang('Admin/Users.students'),
            'count' => $stats['siswa'] ?? 0,
            'color' => 'text-emerald-600 dark:text-emerald-400',
            'bg'    => 'bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/30 dark:to-emerald-800/20',
            'svg'   => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'
        ],
        [
            'title' => lang('Admin/Users.active_users'),
            'count' => $stats['aktif'] ?? 0,
            'color' => 'text-amber-600 dark:text-amber-400',
            'bg'    => 'bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/30 dark:to-amber-800/20',
            'svg'   => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
        ]
    ];
    ?>

    <?php foreach($cards as $c): ?>
    <div class="bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm flex items-center gap-4 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
        <div class="w-12 h-12 <?= $c['bg'] ?> rounded-xl flex items-center justify-center <?= $c['color'] ?>">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $c['svg'] ?>" />
            </svg>
        </div>
        <div>
            <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider"><?= $c['title'] ?></p>
            <h3 class="text-2xl font-bold text-slate-800 dark:text-white"><?= number_format($c['count']) ?></h3>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="bg-white dark:bg-slate-800 p-5 rounded-t-2xl border-b border-slate-100 dark:border-slate-700 shadow-sm flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4 relative z-10 transition-colors">
    <div class="flex items-center gap-4 w-full xl:w-auto mb-2 xl:mb-0">
        <div class="flex items-center justify-center bg-slate-50 dark:bg-slate-700/50 p-2 rounded-lg border border-slate-200 dark:border-slate-600 hover:border-blue-300 dark:hover:border-blue-500 transition-colors">
            <input type="checkbox" id="selectAll" class="w-5 h-5 rounded border-slate-300 dark:border-slate-500 bg-white dark:bg-slate-700 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-500 cursor-pointer focus:ring-offset-0">
        </div>

        <div>
            <div class="flex items-center gap-2">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white leading-tight"><?= lang('Admin/Users.user_list') ?></h2>
                <span class="px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-[10px] font-bold border border-slate-200 dark:border-slate-600">
                    <?= $pager->getTotal('users') ?>
                </span>
            </div>
            <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5"><?= lang('Admin/Users.user_list_desc') ?></p>
        </div>
        
        <div id="bulkActions" class="hidden items-center animate-fade-in-left ml-2">
            <div class="h-8 w-px bg-slate-200 dark:bg-slate-700 mx-2"></div>
            <button onclick="confirmBulkDelete()" class="flex items-center gap-2 px-3 py-1.5 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs font-semibold rounded-lg hover:bg-red-100 dark:hover:bg-red-900/50 transition-colors border border-red-100 dark:border-red-800/50">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                <?= lang('Admin/Users.btn_bulk_delete') ?> <span id="selectedCount" class="ml-1 opacity-75"></span>
            </button>
        </div>
    </div>

    <div class="flex flex-col sm:flex-row flex-wrap xl:flex-nowrap gap-3 w-full xl:w-auto items-center">
        <div class="relative w-full sm:w-40">
            <select id="filter-role" class="w-full pl-3 pr-8 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all cursor-pointer appearance-none"> 
                <option value=""><?= lang('Admin/Users.all_roles') ?></option>
                <?php if (!empty($roles)): ?>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['id'] ?>" <?= ($selected_role == $r['id']) ? 'selected' : '' ?>>
                            <?= ucfirst($r['role_name']) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>

        <div class="relative w-full sm:w-36">
            <select id="filter-status" class="w-full pl-3 pr-8 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all cursor-pointer appearance-none"> 
                <option value=""><?= lang('Admin/Users.all_statuses') ?></option>
                <option value="active" <?= ($selected_status == 'active') ? 'selected' : '' ?>><?= lang('Admin/Users.active') ?></option>
                <option value="inactive" <?= ($selected_status == 'inactive') ? 'selected' : '' ?>><?= lang('Admin/Users.inactive') ?></option>
            </select>
            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </div>
        </div>

        <div class="relative w-full sm:w-56 group">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[<?= $color['warna_primary'] ?>] transition-colors pointer-events-none">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input type="text" id="search-input" value="<?= htmlspecialchars(string: $search_query ?? '') ?>" class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:border-[<?= $color['warna_primary'] ?>] focus:ring-1 focus:ring-[<?= $color['warna_primary'] ?>] transition-all shadow-sm" placeholder="<?= lang('Admin/Users.search_user') ?>">
        </div>

        <button onclick="showAddUserModal()" class="w-full sm:w-auto flex items-center justify-center gap-2 px-5 py-2.5 bg-[<?= $color['warna_primary'] ?>]/90 hover:bg-[<?= $color['warna_primary'] ?>] text-white text-sm font-semibold rounded-xl active:scale-95 transition-all shadow-lg shadow-[<?= $color['warna_primary'] ?>]/20 whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            <?= lang('Admin/Users.btn_add_user') ?>
        </button>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-b-2xl border-x border-b border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden transition-colors">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-slate-50 dark:bg-slate-900/50 text-slate-500 dark:text-slate-400 font-semibold border-b border-slate-100 dark:border-slate-700">
                <tr>
                    <th class="py-4 px-4 w-12 text-center bg-slate-50/50 dark:bg-slate-800/30">#</th>
                    <th class="py-4 px-4"><?= lang('Admin/Users.th_account') ?></th>
                    <th class="py-4 px-4"><?= lang('Admin/Users.th_email') ?></th>
                    <th class="py-4 px-4"><?= lang('Admin/Users.th_role') ?></th>
                    <th class="py-4 px-4"><?= lang('Admin/Users.th_status') ?></th>
                    <th class="py-4 px-4 text-center"><?= lang('Admin/Users.th_action') ?></th>
                </tr>
            </thead>
            <tbody id="users-table-body" class="divide-y divide-slate-100 dark:divide-slate-700/50">
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="flex flex-col items-center justify-center text-slate-400 dark:text-slate-500">
                                <div class="w-16 h-16 bg-slate-50 dark:bg-slate-700/30 rounded-full flex items-center justify-center mb-3">
                                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <p class="font-medium text-slate-500 dark:text-slate-400"><?= lang('Admin/Users.no_user_data') ?></p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors group">
                        <td class="py-3 px-4 text-center">
                            <input type="checkbox" class="user-checkbox w-4 h-4 rounded border-slate-300 dark:border-slate-500 bg-white dark:bg-slate-700 text-blue-600 focus:ring-blue-500 dark:focus:ring-blue-500 cursor-pointer focus:ring-offset-0" value="<?= $user['id'] ?>">
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center gap-3">
                                <?php 
                                    $displayName = $user['full_name'] ?? $user['username'] ?? 'US'; 
                                    $initials = strtoupper(substr($displayName, 0, 2));
                                    $fotoUser = $user['foto_profil'] ?? null;

                                    if ($fotoUser && file_exists(FCPATH . 'assets/uploads/avatars/' . $fotoUser)) {
                                        $urlFotoTabel = base_url('assets/uploads/avatars/' . $fotoUser);
                                    } elseif ($fotoUser && file_exists(FCPATH . 'assets/uploads/siswa/' . $fotoUser)) {
                                        $urlFotoTabel = base_url('assets/uploads/siswa/' . $fotoUser);
                                    } else {                                    
                                        $urlFotoTabel = "https://ui-avatars.com/api/?name={$initials}&background=1F7A4D&color=fff&size=100&bold=true&rounded=true";
                                    }
                                ?>

                                <img src="<?= $urlFotoTabel ?>" alt="Avatar" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-600 shadow-sm shrink-0 select-none">

                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-800 dark:text-white group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors truncate max-w-[180px]">
                                        @<?= htmlspecialchars($user['username']) ?>
                                    </p>
                                    <?php if(!empty($user['full_name'])): ?>
                                        <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate max-w-[180px]"><?= htmlspecialchars($user['full_name']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-slate-600 dark:text-slate-300"><?= htmlspecialchars($user['email']) ?></td>
                       <td class="py-3 px-4 relative max-w-[200px]">
                            <?php 
                                $roleIdsString = !empty($user['all_roles_ids']) ? $user['all_roles_ids'] : $user['role_id'];
                                $roleIdsArray = array_unique(array_filter(array_map('trim', explode(',', (string)$roleIdsString))));
                            ?>
                            
                            <div class="relative inline-block w-full text-left dropdown-container" id="dropdown-wrapper-<?= $user['id'] ?>">
                                
                                <button type="button" onclick="toggleRoleDropdown(<?= $user['id'] ?>, event)" class="flex items-center justify-between w-full px-3 py-2 text-[11px] font-bold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg shadow-sm hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/30 outline-none">
                                    <span class="flex items-center gap-1.5 truncate">
                                        <svg class="w-3.5 h-3.5 text-[<?= $color['warna_primary'] ?>] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                        <span id="role-count-<?= $user['id'] ?>"><?= count($roleIdsArray) ?> <?= lang('Admin/Users.access_count') ?></span>
                                    </span>
                                    <svg class="w-3.5 h-3.5 opacity-50 shrink-0 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>

                                <div id="role-menu-<?= $user['id'] ?>" class="hidden absolute z-[999] w-64 mt-1 origin-top-left bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-xl shadow-2xl left-0 right-0 md:right-auto overflow-hidden">
                                    <div class="px-3 py-2 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-100 dark:border-slate-700">
                                        <p class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider"><?= lang('Admin/Users.manage_login_access') ?></p>
                                    </div>
                                    <div class="max-h-48 overflow-y-auto custom-scrollbar p-1.5 space-y-0.5 bg-white dark:bg-slate-800">
                                        <?php foreach ($roles as $r): ?>
                                            <?php 
                                                $isChecked = in_array($r['id'], $roleIdsArray) ? 'checked' : ''; 
                                                $isDisabled = ($user['role_id'] == 1 && $r['id'] == 1) ? 'disabled' : '';
                                            ?>
                                            <label class="flex items-center justify-between p-2 rounded-lg cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors group <?= $isDisabled ? 'opacity-50 cursor-not-allowed' : '' ?>">
                                                <span class="text-xs font-bold text-slate-700 dark:text-slate-300 group-hover:text-[<?= $color['warna_primary'] ?>] transition-colors"><?= ucfirst($r['role_name']) ?></span>
                                                <div class="relative flex items-center">
                                                    <input type="checkbox" 
                                                           class="inline-role-cb peer h-4 w-4 cursor-pointer appearance-none rounded border border-slate-300 dark:border-slate-600 checked:bg-[<?= $color['warna_primary'] ?>] checked:border-[<?= $color['warna_primary'] ?>] transition-all" 
                                                           value="<?= $r['id'] ?>" 
                                                           data-userid="<?= $user['id'] ?>"
                                                           onchange="updateInlineRoles(<?= $user['id'] ?>)"
                                                           <?= $isChecked ?> <?= $isDisabled ?>>
                                                    <span class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 pointer-events-none">
                                                        <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                                    </span>
                                                </div>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                            </td>
                        <td class="py-3 px-4">
                            <?php if ($user['is_active'] == 1): ?>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/30">
                                    <span class="relative flex h-2 w-2"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span><span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span></span> <?= lang('Admin/Users.active') ?>
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800/30">
                                    <span class="h-2 w-2 rounded-full bg-red-500"></span> <?= lang('Admin/Users.inactive') ?>
                                </span>
                            <?php endif; ?>
                        </td>                        
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick='openDetailModal(<?= json_encode($user, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)' class="group/btn p-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 hover:border-blue-300 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-all shadow-sm" title="<?= lang('Admin/Users.tooltip_detail') ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                                <button onclick='openEditModal(<?= json_encode($user, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)' class="group/btn p-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 dark:text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 hover:border-amber-300 dark:hover:border-amber-500 hover:bg-amber-50 dark:hover:bg-amber-900/30 transition-all shadow-sm" title="<?= lang('Admin/Users.tooltip_edit') ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"></path></svg>
                                </button>
                                <button onclick="confirmDelete(<?= $user['id'] ?>)" class="group/btn p-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg text-slate-500 dark:text-slate-400 hover:text-red-600 dark:hover:text-red-400 hover:border-red-300 dark:hover:border-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 transition-all shadow-sm" title="<?= lang('Admin/Users.tooltip_delete') ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <div id="pagination-container" class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 flex flex-col sm:flex-row items-center justify-between gap-4 bg-slate-50/50 dark:bg-slate-800/80">
        <p class="text-xs text-slate-500 dark:text-slate-400 font-medium"><?= lang('Admin/Users.showing_page') ?> <span class="font-bold text-slate-800 dark:text-white"><?= $pager->getCurrentPage('users') ?></span> <?= lang('Admin/Users.from') ?> <span class="font-bold text-slate-800 dark:text-white"><?= $pager->getPageCount('users') ?></span></p>
        
        <nav aria-label="Page navigation" class="pagination-dark-wrapper scale-95 origin-right">
            <?= $pager->links('users', 'default_full') ?>
        </nav>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<div id="user-modal" class="hidden fixed inset-0 z-50 overflow-hidden">
    <div id="modal-backdrop" onclick="hideUserModal()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0"></div>

    <div id="modal-wrapper" class="flex min-h-full items-center justify-center p-4 text-center sm:p-0 transition-all duration-300 ease-in-out">
        <div id="modal-panel" class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-all sm:w-full sm:max-w-xl scale-95 opacity-0 flex flex-col max-h-[90vh]">
            <div class="bg-white dark:bg-slate-800 px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between shrink-0 z-10 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-full flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                    </div>
                    <div>
                        <h3 id="user-modal-title" class="text-lg font-bold text-slate-800 dark:text-white"><?= lang('Admin/Users.modal_add_title') ?></h3>
                    </div>
                </div>
                <button type="button" onclick="hideUserModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form id="user-form" method="POST" novalidate class="flex flex-col flex-1 overflow-hidden">
                <?= csrf_field() ?>
                <input type="hidden" id="user-id" name="id" value="">
                
                <div class="px-6 py-6 space-y-5 overflow-y-auto custom-scrollbar flex-1">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1"><?= lang('Admin/Users.user_role') ?> <span class="text-red-500">*</span></label>
                        <select id="user-role" name="role_id" required class="w-full px-4 py-2.5 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl text-sm text-slate-700 dark:text-slate-200 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/20 focus:border-[<?= $color['warna_primary'] ?>] outline-none transition-all shadow-sm appearance-none">
                            <option value=""><?= lang('Admin/Users.select_role') ?></option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['id'] ?>" data-name="<?= strtolower($role['role_name']) ?>"><?= ucfirst($role['role_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1"><?= lang('Admin/Users.full_name') ?> <span class="text-red-500">*</span></label>
                        <input type="text" id="user-fullname" name="nama_lengkap" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/20 focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none" placeholder="<?= lang('Admin/Users.full_name_placeholder') ?>">
                    </div>

                    <div>
                        <div class="flex justify-between items-end mb-1">
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300"><?= lang('Admin/Users.username') ?> <span class="text-red-500">*</span></label>
                            <span id="username-hint" class="text-[10px] text-blue-600 dark:text-blue-400 font-bold hidden bg-blue-50 dark:bg-blue-900/30 border dark:border-blue-800/50 px-2 py-0.5 rounded"><?= lang('Admin/Users.auto_fill_available') ?></span>
                            <span id="username-locked-hint" class="text-[10px] text-red-500 font-bold hidden bg-red-50 dark:bg-red-900/30 border dark:border-red-800/50 px-2 py-0.5 rounded"><?= lang('Admin/Users.cannot_be_changed') ?></span>
                        </div>
                        <input type="text" id="user-username" name="username" list="username-options" autocomplete="off" required 
                               class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/20 focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm read-only:bg-slate-100 dark:read-only:bg-slate-800 read-only:text-slate-500 read-only:cursor-not-allowed outline-none" placeholder="budi123">
                        <datalist id="username-options"></datalist>
                        <input type="hidden" name="linked_id" id="linked-id">
                        <input type="hidden" name="linked_type" id="linked-type">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1"><?= lang('Admin/Users.th_email') ?> <span class="text-red-500">*</span></label>
                        <input type="email" id="user-email" name="email" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-900/50 border border-slate-300 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:bg-white dark:focus:bg-slate-700 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/20 focus:border-[<?= $color['warna_primary'] ?>] transition-all shadow-sm outline-none" placeholder="<?= lang('Admin/Users.email_placeholder') ?>">
                    </div>

                    <div class="p-5 bg-slate-50 dark:bg-slate-900/30 rounded-2xl border border-slate-200 dark:border-slate-700 space-y-4 transition-colors">
                        <div class="flex justify-between items-center">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg> 
                                <?= lang('Admin/Users.account_security') ?>
                            </label>
                            <span id="password-hint" class="text-[10px] text-slate-400 dark:text-slate-500 italic hidden"><?= lang('Admin/Users.leave_blank_hint') ?></span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <div class="flex justify-between items-center">
                                    <label class="text-xs font-medium text-slate-500 dark:text-slate-400"><?= lang('Admin/Users.new_password') ?></label>
                                    <button type="button" onclick="generatePassword()" class="text-[10px] font-bold text-[<?= $color['warna_primary'] ?>] hover:underline cursor-pointer flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg> <?= lang('Admin/Users.auto_generate') ?>
                                    </button>
                                </div>
                                <div class="relative">
                                    <input type="password" id="user-password" name="password" class="w-full pl-4 pr-10 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/20 focus:border-[<?= $color['warna_primary'] ?>] transition-all outline-none" placeholder="<?= lang('Admin/Users.password_placeholder') ?>">
                                    <button type="button" onclick="togglePasswordVisibility('user-password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg></button>
                                </div>
                                <div class="flex items-center justify-between mt-1">
                                    <div class="flex gap-1 h-1 w-24">
                                        <div id="strength-bar-1" class="h-full w-1/4 bg-slate-200 dark:bg-slate-600 rounded-full transition-colors"></div>
                                        <div id="strength-bar-2" class="h-full w-1/4 bg-slate-200 dark:bg-slate-600 rounded-full transition-colors"></div>
                                        <div id="strength-bar-3" class="h-full w-1/4 bg-slate-200 dark:bg-slate-600 rounded-full transition-colors"></div>
                                        <div id="strength-bar-4" class="h-full w-1/4 bg-slate-200 dark:bg-slate-600 rounded-full transition-colors"></div>
                                    </div>
                                    <span id="password-strength-text" class="text-[10px] text-slate-400 italic"></span>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-medium text-slate-500 dark:text-slate-400"><?= lang('Admin/Users.password_confirm') ?></label>
                                <input type="password" id="user-confirm-password" name="confirm_password" class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-xl text-sm text-slate-800 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-[<?= $color['warna_primary'] ?>]/20 focus:border-[<?= $color['warna_primary'] ?>] transition-all outline-none" placeholder="<?= lang('Admin/Users.password_placeholder') ?>">
                            </div>
                        </div>
                    </div>
                    
                    <label class="flex items-center gap-3 cursor-pointer p-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800/30 rounded-xl hover:bg-emerald-100 dark:hover:bg-emerald-900/40 transition-colors">
                        <div class="relative flex items-center">
                            <input type="checkbox" name="send_credentials" value="1" checked class="peer h-5 w-5 cursor-pointer appearance-none rounded border border-emerald-300 dark:border-emerald-600 bg-white dark:bg-slate-800 shadow transition-all checked:border-emerald-600 checked:bg-emerald-600 hover:shadow-md">
                            <span class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 peer-checked:opacity-100 pointer-events-none"><svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg></span>
                        </div>
                        <div><span class="block text-sm font-bold text-emerald-900 dark:text-emerald-400"><?= lang('Admin/Users.send_login_info') ?></span><span class="block text-[10px] text-emerald-600 dark:text-emerald-500"><?= lang('Admin/Users.send_login_info_desc') ?></span></div>
                    </label>

                </div>

                <div class="bg-white dark:bg-slate-800 px-6 py-4 border-t border-slate-100 dark:border-slate-700 flex flex-row-reverse gap-3 shrink-0 rounded-b-2xl transition-colors">
                    <button type="submit" id="btn-save-user" class="inline-flex w-full sm:w-auto justify-center rounded-xl px-6 py-2.5 text-sm font-bold text-white shadow-lg transition-all hover:scale-[1.02]" style="background-color: <?= $color['warna_primary'] ?>;"><?= lang('Admin/Users.btn_save_account') ?></button>
                    <button type="button" onclick="hideUserModal()" class="inline-flex w-full sm:w-auto justify-center rounded-xl bg-white dark:bg-slate-700 px-6 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 transition-all"><?= lang('Admin/Users.btn_cancel') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="drawer-overlay" class="drawer-overlay fixed inset-0 bg-gray-900/40 backdrop-blur-sm z-40 hidden transition-opacity" onclick="closeDrawer()"></div>

<div id="detailDrawer" class="fixed inset-y-0 right-0 w-full md:w-[450px] bg-white dark:bg-slate-800 shadow-2xl z-50 transform translate-x-full transition-all duration-300 ease-in-out flex flex-col border-l border-slate-200 dark:border-slate-700">
    <div class="relative p-6 text-white shrink-0" style="background-color: <?= $color['warna_primary'] ?>; background-image: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(0,0,0,0.2) 100%);">
        <button onclick="closeDrawer()" class="absolute top-4 right-4 p-2 bg-white/10 hover:bg-white/20 rounded-lg text-white transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
        <div class="mt-4 flex flex-col items-center">
            <img id="drawerAvatar" src="" alt="Avatar" class="w-20 h-20 rounded-full object-cover border-4 border-white/30 shadow-lg mb-3 bg-white dark:bg-slate-800">
            <h3 id="drawerName" class="text-xl font-bold text-center drop-shadow-sm">Nama Pengguna</h3>
            <p id="drawerEmail" class="text-white/80 text-sm font-medium">email@contoh.com</p>
            <div class="mt-3 flex gap-2">
                <span id="drawerRole" class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-semibold border border-white/20 shadow-inner">Admin</span>
                <span id="drawerStatus" class="px-3 py-1 bg-emerald-500 text-white rounded-full text-xs font-semibold border border-emerald-400 shadow-inner"><?= lang('Admin/Users.active') ?></span>
            </div>
        </div>
    </div>
    
    <div class="flex-1 overflow-y-auto p-6 space-y-6 custom-scrollbar">
        <div class="bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl border border-slate-100 dark:border-slate-700 transition-colors">
            <h4 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-3"><?= lang('Admin/Users.account_info') ?></h4>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between py-1.5 border-b border-slate-200/50 dark:border-slate-700/50"><span class="text-slate-500 dark:text-slate-400"><?= lang('Admin/Users.username') ?></span><span id="info-username" class="font-bold text-slate-800 dark:text-white">-</span></div>
                <div class="flex justify-between py-1.5"><span class="text-slate-500 dark:text-slate-400"><?= lang('Admin/Users.joined_since') ?></span><span id="info-joined" class="font-bold text-slate-800 dark:text-white">-</span></div>
            </div>
        </div>
        
        <div>
            <h4 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-3"><?= lang('Admin/Users.quick_actions') ?></h4>
            <div class="space-y-3 mt-4">
                <div class="flex items-center justify-between p-3.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm transition-colors">
                    <div class="text-sm"><p class="font-bold text-slate-800 dark:text-white"><?= lang('Admin/Users.account_status') ?></p><p id="status-text-label" class="text-xs font-medium text-slate-500 dark:text-slate-400"><?= lang('Admin/Users.status_active') ?></p></div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="toggle-status-akun" class="sr-only peer">
                        <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500 transition-colors border border-slate-300 dark:border-slate-600"></div>
                    </label>
                </div>
                <button id="btn-edit-drawer" class="w-full px-4 py-2.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-bold rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors text-sm flex items-center justify-center gap-2 border border-blue-200 dark:border-blue-800/50 shadow-sm">
                    <?= lang('Admin/Users.btn_edit_user') ?>
                </button>
            </div>
            <button onclick="confirmDelete(document.getElementById('detail-id-input').value)" class="mt-4 flex items-center justify-center gap-2 w-full py-2.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-xl text-sm font-bold hover:bg-red-100 dark:hover:bg-red-900/40 border border-red-200 dark:border-red-800/50 transition-colors shadow-sm">
                <?= lang('Admin/Users.btn_delete_permanent') ?>
            </button>
        </div>
    </div>
    <input type="hidden" id="detail-id-input">
    <input type="hidden" id="status-id-input"> 
</div>

<div id="delete-modal" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative z-50 transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md animate-scale-up border border-slate-100 dark:border-slate-700">
            <div class="bg-white dark:bg-slate-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4 transition-colors">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10 border border-red-200 dark:border-red-800/50">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <h3 class="text-base font-bold leading-6 text-slate-900 dark:text-white"><?= lang('Admin/Users.modal_delete_title') ?></h3>
                        <div class="mt-2"><p class="text-sm font-medium text-slate-500 dark:text-slate-400"><?= lang('Admin/Users.modal_delete_desc') ?></p></div>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/80 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-100 dark:border-slate-700 transition-colors">
                <button type="button" onclick="executeDeleteAction()" class="inline-flex w-full justify-center rounded-xl bg-red-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-colors"><?= lang('Admin/Users.btn_yes_delete') ?></button>
                <button type="button" onclick="hideDeleteModal()" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white dark:bg-slate-700 px-5 py-2.5 text-sm font-bold text-slate-700 dark:text-slate-300 shadow-sm border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 sm:mt-0 sm:w-auto transition-colors"><?= lang('Admin/Users.btn_cancel') ?></button>
            </div>
        </div>
    </div>
</div>

<div id="activate-modal" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative z-50 transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md animate-scale-up border border-slate-100 dark:border-slate-700">
            <div class="bg-white dark:bg-slate-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4 transition-colors">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-900/30 sm:mx-0 sm:h-10 sm:w-10 border border-emerald-200 dark:border-emerald-800/50">
                        <svg class="h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <h3 class="text-base font-bold leading-6 text-slate-900 dark:text-white"><?= lang('Admin/Users.modal_activate_title') ?></h3>
                        <div class="mt-2"><p class="text-sm font-medium text-slate-500 dark:text-slate-400"><?= lang('Admin/Users.modal_activate_desc') ?></p></div>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/80 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-100 dark:border-slate-700 transition-colors">
                <button type="button" onclick="executeActivateAction()" class="inline-flex w-full justify-center rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-emerald-500 sm:ml-3 sm:w-auto transition-colors"><?= lang('Admin/Users.btn_activate') ?></button>
                <button type="button" onclick="hideActivateModal()" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white dark:bg-slate-700 px-5 py-2.5 text-sm font-bold text-slate-700 dark:text-slate-300 shadow-sm border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 sm:mt-0 sm:w-auto transition-colors"><?= lang('Admin/Users.btn_cancel') ?></button>
            </div>
        </div>
    </div>
</div>

<div id="confirm-modal" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative z-50 transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-md animate-scale-up border border-slate-100 dark:border-slate-700">
            <div class="bg-white dark:bg-slate-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4 transition-colors">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-amber-100 dark:bg-amber-900/30 sm:mx-0 sm:h-10 sm:w-10 border border-amber-200 dark:border-amber-800/50">
                        <svg class="h-6 w-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <h3 class="text-base font-bold leading-6 text-slate-900 dark:text-white"><?= lang('Admin/Users.modal_deactivate_title') ?></h3>
                        <div class="mt-2"><p class="text-sm font-medium text-slate-500 dark:text-slate-400"><?= lang('Admin/Users.modal_deactivate_desc') ?></p></div>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/80 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-100 dark:border-slate-700 transition-colors">
                <button type="button" onclick="executeDeactivateAction()" class="inline-flex w-full justify-center rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-amber-400 sm:ml-3 sm:w-auto transition-colors"><?= lang('Admin/Users.btn_deactivate') ?></button>
                <button type="button" onclick="hideConfirmModal()" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white dark:bg-slate-700 px-5 py-2.5 text-sm font-bold text-slate-700 dark:text-slate-300 shadow-sm border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 sm:mt-0 sm:w-auto transition-colors"><?= lang('Admin/Users.btn_cancel') ?></button>
            </div>
        </div>
    </div>
</div>

<form id="delete-user-form" action="<?= base_url('admin/users/delete') ?>" method="POST" class="hidden"><?= csrf_field() ?><input type="hidden" name="id" id="delete-user-id"></form>
<form id="deactivate-form" action="<?= base_url('admin/users/deactivate') ?>" method="POST" class="hidden"><?= csrf_field() ?><input type="hidden" name="id" id="deactivate-id"></form>
<form id="activate-form" action="<?= base_url('admin/users/activate') ?>" method="POST" class="hidden"><?= csrf_field() ?><input type="hidden" name="id" id="activate-id"></form>

<div id="base-url" data-url="<?= base_url() ?>"></div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="application/json" id="json-data-guru">
    <?= json_encode($calon_guru) ?>
</script>
<script type="application/json" id="json-data-siswa">
    <?= json_encode($calon_siswa) ?>
</script>
<script type="application/json" id="json-data-orangtua">
    <?= json_encode($calon_orangtua) ?>
</script>
<script type="application/json" id="json-data-tahfidz">
    <?= json_encode($calon_tahfidz ?? []) ?>
</script>
<script>
    const LANG = {
        modal_add_title: "<?= lang('Admin/Users.modal_add_title') ?>",
        modal_edit_title: "<?= lang('Admin/Users.modal_edit_title') ?>",
        btn_save_account: "<?= lang('Admin/Users.btn_save_account') ?>",
        btn_save_changes: "<?= lang('Admin/Users.btn_save_changes') ?>",
        js_select_teacher: "<?= lang('Admin/Users.js_select_teacher') ?>",
        js_select_student: "<?= lang('Admin/Users.js_select_student') ?>",
        js_select_parent: "<?= lang('Admin/Users.js_select_parent') ?>",
        js_select_tahfidz: "<?= lang('Admin/Users.js_select_tahfidz') ?>",
        js_type_manual: "<?= lang('Admin/Users.js_type_manual') ?>",
        js_pass_mismatch: "<?= lang('Admin/Users.js_pass_mismatch') ?>",
        js_action_denied: "<?= lang('Admin/Users.js_action_denied') ?>",
        js_must_select_data: "<?= lang('Admin/Users.js_must_select_data') ?>",
        js_processing: "<?= lang('Admin/Users.js_processing') ?>",
        js_success: "<?= lang('Admin/Users.js_success') ?>",
        js_failed: "<?= lang('Admin/Users.js_failed') ?>",
        js_server_error: "<?= lang('Admin/Users.js_server_error') ?>",
        js_delete_selected: "<?= lang('Admin/Users.js_delete_selected') ?>",
        js_saving: "<?= lang('Admin/Users.js_saving') ?>",
        js_saved: "<?= lang('Admin/Users.js_saved') ?>",
        js_connection_lost: "<?= lang('Admin/Users.js_connection_lost') ?>",
        js_pass_weak: "<?= lang('Admin/Users.js_pass_weak') ?>",
        js_pass_medium: "<?= lang('Admin/Users.js_pass_medium') ?>",
        js_pass_strong: "<?= lang('Admin/Users.js_pass_strong') ?>",
        js_deleting: "<?= lang('Admin/Users.js_deleting') ?>",
        js_fail_delete: "<?= lang('Admin/Users.js_fail_delete') ?>",
        js_conn_error: "<?= lang('Admin/Users.js_conn_error') ?>",
        status_active: "<?= lang('Admin/Users.status_active') ?>",
        status_inactive: "<?= lang('Admin/Users.status_inactive') ?>",
        active: "<?= lang('Admin/Users.active') ?>",
        inactive: "<?= lang('Admin/Users.inactive') ?>"
    };
</script>
<script src="<?= base_url('assets/js/Admin/users.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>