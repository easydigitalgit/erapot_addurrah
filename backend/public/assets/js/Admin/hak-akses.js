// SABUK PENGAMAN BAHASA
const textObj = window.LANG || {
    js_loading_matrix: 'Memuat matriks akses untuk', js_success_load: 'Hak akses berhasil dimuat', js_err_load: 'Gagal memuat data hak akses.',
    js_preset_apply: 'Template berhasil diterapkan.', js_add_role_title: 'Tambah Role Baru', js_lbl_role_name: 'Nama Role',
    js_ph_role_name: 'Contoh: Koordinator Akademik', js_lbl_role_desc: 'Deskripsi', js_ph_role_desc: 'Contoh: Monitoring & Koordinasi',
    js_lbl_color: 'Pilih Warna Icon', js_lbl_status: 'Status Awal', status_active: 'Aktif', status_inactive: 'Nonaktif',
    js_role_note: 'Setelah membuat role, Anda dapat mengatur detail hak aksesnya pada panel <strong class="font-bold">Matrix Hak Akses</strong>.',
    btn_cancel: 'Batal', js_btn_create_role: 'Buat Role', js_saving_role: 'Sedang menyimpan role baru...',
    js_err_net_save: 'Terjadi kesalahan jaringan saat menyimpan role.', js_check_confirm: 'Harap centang konfirmasi terlebih dahulu.',
    js_select_role_1st: 'Pilih role terlebih dahulu dari daftar di sebelah kiri.', js_saving_changes: 'Menyimpan perubahan...',
    js_err_sys_save: 'Terjadi kesalahan sistem saat menyimpan data.', js_sys_notif: 'Notifikasi Sistem'
};

function openMobileSidebar() {
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebar-overlay');
  if(sidebar) sidebar.classList.add('mobile-open');
  if(overlay) overlay.classList.add('active');
  document.body.style.overflow = 'hidden';
}

function closeMobileSidebar() {
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebar-overlay');
  if(sidebar) sidebar.classList.remove('mobile-open');
  if(overlay) overlay.classList.remove('active');
  document.body.style.overflow = '';
}

let currentSelectedRoleId = null;

function selectRole(card, roleId) {
    document.querySelectorAll('.role-card').forEach(c => {
        c.classList.remove('active');
        c.style.borderColor = '';
    });
    
    card.classList.add('active');
    currentSelectedRoleId = roleId;

    const roleName = card.querySelector('h4').textContent;
    showToast(`${textObj.js_loading_matrix} "${roleName}"...`, 'info');

    document.querySelectorAll('.toggle-switch').forEach(toggle => toggle.classList.remove('active'));

    fetch(`${BASE_URL}admin/hak-akses/getRolePermissions/${roleId}`, {
        method: 'GET',
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(response => response.json())
    .then(res => {
        if (res.status === 'success') {
            res.data.forEach(perm => {
                const row = document.querySelector(`.module-row[data-module="${perm.module_name}"]`);
                if(row) {
                    if(perm.can_view == 1) row.querySelector('[data-action="view"]')?.classList.add('active');
                    if(perm.can_create == 1) row.querySelector('[data-action="create"]')?.classList.add('active');
                    if(perm.can_update == 1) row.querySelector('[data-action="update"]')?.classList.add('active');
                    if(perm.can_delete == 1) row.querySelector('[data-action="delete"]')?.classList.add('active');
                    if(perm.can_special == 1) row.querySelector('[data-action="special"]')?.classList.add('active');
                }
            });
            showToast(`${textObj.js_success_load}`, 'success');
        }
    })
    .catch(err => {
        console.error(err);
        showToast(textObj.js_err_load, 'error');
    });
}

function togglePermission(toggle) {
  if (!toggle.hasAttribute('disabled')) {
    toggle.classList.toggle('active');
  }
}

function toggleExpand(moduleId) {
  const expandSection = document.getElementById(`expand-${moduleId}`);
  if(!expandSection) return;
  
  const expandable = expandSection.querySelector('.expandable-section');
  const icon = event.currentTarget.querySelector('.expand-icon');
  
  if (expandSection.classList.contains('hidden')) {
    expandSection.classList.remove('hidden');
    if(expandable) expandable.classList.add('open');
    if(icon) icon.classList.add('rotate-90');
  } else {
    expandSection.classList.add('hidden');
    if(expandable) expandable.classList.remove('open');
    if(icon) icon.classList.remove('rotate-90');
  }
}

function applyPreset(presetType) {
  showToast(textObj.js_preset_apply, 'success');
}

function addRole() {
  showAddRoleModal();
}

function showAddRoleModal() {
  const modal = document.createElement('div');
  modal.className = 'fixed inset-0 z-[100000] flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm transition-opacity';
  modal.id = 'addRoleModal';
  
  modal.innerHTML = `
    <div class="relative w-full max-w-md bg-white dark:bg-slate-800 rounded-3xl shadow-2xl flex flex-col pointer-events-auto border border-transparent dark:border-slate-700 transition-colors" onclick="event.stopPropagation()">
      <div class="p-6 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between transition-colors">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
          <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
          ${textObj.js_add_role_title}
        </h3>
        <button type="button" onclick="closeAddRoleModal()" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-full transition-colors outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>
      
      <div class="p-6 overflow-y-auto custom-scrollbar max-h-[70vh]">
        <form id="addRoleForm" class="space-y-5">
          <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">${textObj.js_lbl_role_name}</label>
            <input type="text" id="roleName" required class="w-full px-4 py-3 bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all placeholder-gray-400 dark:placeholder-slate-400 shadow-sm" placeholder="${textObj.js_ph_role_name}">
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">${textObj.js_lbl_role_desc}</label>
            <input type="text" id="roleDesc" required class="w-full px-4 py-3 bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all placeholder-gray-400 dark:placeholder-slate-400 shadow-sm" placeholder="${textObj.js_ph_role_desc}">
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-3">${textObj.js_lbl_color}</label>
            <div class="grid grid-cols-6 gap-3">
              <button type="button" onclick="selectColor(this, 'emerald')" class="color-option w-full h-10 rounded-xl bg-emerald-500 hover:scale-110 border-4 border-transparent transition-transform shadow-sm focus:outline-none" data-color="emerald"></button>
              <button type="button" onclick="selectColor(this, 'blue')" class="color-option w-full h-10 rounded-xl bg-blue-500 hover:scale-110 border-4 border-transparent transition-transform shadow-sm focus:outline-none" data-color="blue"></button>
              <button type="button" onclick="selectColor(this, 'purple')" class="color-option w-full h-10 rounded-xl bg-purple-500 hover:scale-110 border-4 border-transparent transition-transform shadow-sm focus:outline-none" data-color="purple"></button>
              <button type="button" onclick="selectColor(this, 'orange')" class="color-option w-full h-10 rounded-xl bg-orange-500 hover:scale-110 border-4 border-transparent transition-transform shadow-sm focus:outline-none" data-color="orange"></button>
              <button type="button" onclick="selectColor(this, 'pink')" class="color-option w-full h-10 rounded-xl bg-pink-500 hover:scale-110 border-4 border-transparent transition-transform shadow-sm focus:outline-none" data-color="pink"></button>
              <button type="button" onclick="selectColor(this, 'indigo')" class="color-option w-full h-10 rounded-xl bg-indigo-500 hover:scale-110 border-4 border-transparent transition-transform shadow-sm focus:outline-none" data-color="indigo"></button>
            </div>
            <input type="hidden" id="roleColor" value="emerald" required>
          </div>
          <div>
            <label class="block text-sm font-bold text-gray-700 dark:text-slate-300 mb-2">${textObj.js_lbl_status}</label>
            <select id="roleStatus" class="w-full px-4 py-3 bg-white dark:bg-slate-700 border-2 border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all appearance-none cursor-pointer shadow-sm outline-none">
              <option value="active">${textObj.status_active}</option>
              <option value="inactive">${textObj.status_inactive}</option>
            </select>
          </div>
          <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 rounded-xl p-4 flex items-start gap-3 shadow-sm transition-colors mt-2">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm font-medium text-blue-800 dark:text-blue-300 leading-relaxed">${textObj.js_role_note}</p>
          </div>
          <div class="flex gap-3 pt-5 border-t border-gray-100 dark:border-slate-700 transition-colors">
            <button type="button" onclick="closeAddRoleModal()" class="flex-1 px-6 py-3.5 bg-white dark:bg-slate-700 border-2 border-gray-300 dark:border-slate-600 text-gray-700 dark:text-slate-300 font-bold rounded-xl hover:bg-gray-50 dark:hover:bg-slate-600 transition-colors shadow-sm outline-none">
              ${textObj.btn_cancel}
            </button>
            <button type="submit" class="flex-1 px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-transform transform hover:-translate-y-0.5 shadow-lg shadow-emerald-600/30 outline-none">
              ${textObj.js_btn_create_role}
            </button>
          </div>
        </form>
      </div>
    </div>
  `;
  
  document.body.appendChild(modal);
  document.body.style.overflow = 'hidden';
  
  const firstColor = modal.querySelector('.color-option');
  if (firstColor) selectColor(firstColor, 'emerald');
  
  document.getElementById('addRoleForm').addEventListener('submit', (e) => {
    e.preventDefault();
    const name = document.getElementById('roleName').value;
    const desc = document.getElementById('roleDesc').value;
    const color = document.getElementById('roleColor').value;
    const status = document.getElementById('roleStatus').value;
    createNewRole(name, desc, color, status);
  });
  
  modal.addEventListener('click', (e) => {
    if (e.target.id === 'addRoleModal') closeAddRoleModal();
  });
}

function selectColor(button, color) {
  document.querySelectorAll('.color-option').forEach(opt => {
    opt.classList.add('border-transparent');
  });
  button.classList.remove('border-transparent');
  button.style.borderColor = 'white'; 
  document.getElementById('roleColor').value = color;
}

function closeAddRoleModal() {
  const modal = document.getElementById('addRoleModal');
  if (modal) {
    modal.remove();
    document.body.style.overflow = '';
  }
}

function createNewRole(name, desc, color, status) {
    showToast(textObj.js_saving_role, 'info');

    const formData = new FormData();
    formData.append('role_name', name);
    formData.append('description', desc);
    formData.append('status', status);

    if (typeof CSRF_NAME !== 'undefined' && typeof CSRF_TOKEN !== 'undefined') {
        formData.append(CSRF_NAME, CSRF_TOKEN);
    }

    const fetchUrl = (typeof BASE_URL !== 'undefined' ? BASE_URL.replace(/\/$/, '') : '') + '/admin/hak-akses/addRole';

    fetch(fetchUrl, {
        method: 'POST',
        body: formData,
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(response => response.json())
    .then(res => {
        if (res.status === 'success') {
            const roleList = document.getElementById('roleList');
            const newCard = document.createElement('div');
            
            newCard.className = 'role-card p-4 rounded-xl border border-gray-100 dark:border-slate-600 bg-gray-50/50 dark:bg-slate-700/30 hover:bg-[var(--warna-secondary)] dark:hover:bg-slate-700/80 cursor-pointer transition-colors';
            newCard.onclick = function() { selectRole(this, res.id); };
            
            const statusBadge = status === 'active' 
              ? `<span class="inline-flex px-2 py-0.5 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded text-[10px] font-bold uppercase tracking-wider border border-emerald-200 dark:border-emerald-800/50 shadow-sm">${textObj.status_active}</span>`
              : `<span class="inline-flex px-2 py-0.5 bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-slate-400 rounded text-[10px] font-bold uppercase tracking-wider border border-gray-200 dark:border-slate-600 shadow-sm">${textObj.status_inactive}</span>`;
            
            newCard.innerHTML = `
              <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-lg bg-${color}-100 dark:bg-${color}-900/30 flex items-center justify-center flex-shrink-0 border border-${color}-200 dark:border-${color}-800/50">
                    <svg class="w-5 h-5 text-${color}-600 dark:text-${color}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                  </div>
                  <div class="min-w-0">
                    <h4 class="font-bold text-gray-900 dark:text-white text-sm truncate">${name}</h4>
                    <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 truncate mt-0.5">${desc}</p>
                  </div>
                </div>  
              </div>
              <div class="flex items-center justify-between text-xs mt-3 pt-3 border-t border-gray-200 dark:border-slate-600 transition-colors">
                <span class="text-gray-600 dark:text-slate-400 font-medium">${textObj.status}</span>
                ${statusBadge}
              </div>
            `;
            
            roleList.appendChild(newCard);
            closeAddRoleModal();
            showToast(res.message, 'success');
            
            setTimeout(() => { newCard.click(); }, 300);
        } else {
            showToast(res.message, 'error');
        }
    })
    .catch(err => {
        console.error(err);
        showToast(textObj.js_err_net_save, 'error');
    });
}   

function showSaveModal() {
  const modal = document.getElementById('saveModal');
  if(modal) {
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
  }
}

function closeModal() {
  const modal = document.getElementById('saveModal');
  if(modal) modal.classList.add('hidden');
  
  const cb = document.getElementById('confirmCheck');
  if(cb) cb.checked = false;
  
  document.body.style.overflow = '';
}

function confirmSave() {
    const checkbox = document.getElementById('confirmCheck');
    if (!checkbox.checked) {
        showToast(textObj.js_check_confirm, 'warning');
        return;
    }
    if (!currentSelectedRoleId) {
        showToast(textObj.js_select_role_1st, 'error');
        return;
    }

    closeModal();
    showToast(textObj.js_saving_changes, 'info');
    
    let permissions = {};
    document.querySelectorAll('.module-row').forEach(row => {
        const moduleName = row.getAttribute('data-module');
        if (moduleName) {
            permissions[moduleName] = {
                view: row.querySelector('[data-action="view"]')?.classList.contains('active') || false,
                create: row.querySelector('[data-action="create"]')?.classList.contains('active') || false,
                update: row.querySelector('[data-action="update"]')?.classList.contains('active') || false,
                delete: row.querySelector('[data-action="delete"]')?.classList.contains('active') || false,
                special: row.querySelector('[data-action="special"]')?.classList.contains('active') || false
            };
        }
    });

    const formData = new FormData();
    formData.append('role_id', currentSelectedRoleId);
    formData.append('permissions', JSON.stringify(permissions));

    if (typeof CSRF_NAME !== 'undefined' && typeof CSRF_TOKEN !== 'undefined') {
        formData.append(CSRF_NAME, CSRF_TOKEN);
    }

    const fetchUrl = (typeof BASE_URL !== 'undefined' ? BASE_URL.replace(/\/$/, '') : '') + '/admin/hak-akses/saveRolePermissions';

    fetch(fetchUrl, {
        method: 'POST',
        body: formData,
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(response => response.json())
    .then(res => {
        if (res.status === 'success') {
            showToast(res.message, 'success');
        } else {
            showToast(res.message, 'error');
        }
    })
    .catch(err => {
        console.error(err);
        showToast(textObj.js_err_sys_save, 'error');
    });
}

function showToast(message, type = 'success') {
  const toast = document.createElement('div');
  
  const colors = {
    success: { border: 'border-emerald-500', bg: 'bg-emerald-100 dark:bg-emerald-900/30', text: 'text-emerald-600 dark:text-emerald-400', path: 'M5 13l4 4L19 7' },
    error: { border: 'border-red-500', bg: 'bg-red-100 dark:bg-red-900/30', text: 'text-red-600 dark:text-red-400', path: 'M6 18L18 6M6 6l12 12' },
    info: { border: 'border-blue-500', bg: 'bg-blue-100 dark:bg-blue-900/30', text: 'text-blue-600 dark:text-blue-400', path: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
    warning: { border: 'border-amber-500', bg: 'bg-amber-100 dark:bg-amber-900/30', text: 'text-amber-600 dark:text-amber-400', path: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' }
  };
  
  const config = colors[type] || colors.success;
  
  toast.className = `toast fixed top-4 right-4 z-[100000] flex items-center gap-3 px-4 py-3 bg-white dark:bg-slate-800 border-l-4 border border-gray-100 dark:border-slate-700 rounded-xl shadow-2xl transition-all duration-300 transform translate-x-full opacity-0 ${config.border}`;
  
  toast.innerHTML = `
    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 transition-colors ${config.bg}">
      <svg class="w-6 h-6 ${config.text}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${config.path}"/>
      </svg>
    </div>
    <div class="min-w-0">
      <p class="font-bold text-sm text-gray-800 dark:text-white transition-colors">${textObj.js_sys_notif}</p>
      <p class="text-xs font-medium text-gray-500 dark:text-slate-400 transition-colors truncate">${message}</p>
    </div>
  `;
  
  document.body.appendChild(toast);
  
  requestAnimationFrame(() => {
      toast.classList.remove('translate-x-full', 'opacity-0');
  });

  setTimeout(() => {
      toast.classList.add('translate-x-full', 'opacity-0');
      setTimeout(() => toast.remove(), 300);
  }, 4000);
}

document.addEventListener('DOMContentLoaded', () => {
  const firstRole = document.querySelector('.role-card');
  if (firstRole) firstRole.click();
});

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeModal();
        closeAddRoleModal();
    }
});