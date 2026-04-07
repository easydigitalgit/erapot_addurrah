/**
 * ==============================================================================
 * SCRIPT MANAJEMEN ORANG TUA / WALI (FULL REVISION & ANTI-CRASH)
 * ==============================================================================
 */

let parentsData = [];
let currentPage = 1;
const itemsPerPage = 10; 

document.addEventListener('DOMContentLoaded', () => {
    loadTableData();
    setupStudentSearch();

    const searchInput = document.getElementById('searchInput');
    const filterRelation = document.getElementById('filterRelation');
    const filterClass = document.getElementById('filterClass');
    const filterStatus = document.getElementById('filterStatus');

    let debounceTimer;
    if(searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => loadTableData(), 500); 
        });
    }

    if(filterRelation) filterRelation.addEventListener('change', loadTableData);
    if(filterClass)    filterClass.addEventListener('change', loadTableData);
    if(filterStatus)   filterStatus.addEventListener('change', loadTableData);
});

function loadTableData() {
    const keyword = document.getElementById('searchInput')?.value || '';
    const relation = document.getElementById('filterRelation')?.value || '';
    const kelas = document.getElementById('filterClass')?.value || '';
    const status = document.getElementById('filterStatus')?.value || '';
    const tbody = document.getElementById('parentTableBody');

    if(tbody) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-12">
                    <div class="animate-pulse flex justify-center items-center gap-2">
                        <span class="text-emerald-600 font-semibold text-sm">${LANG.js_loading_data}</span>
                    </div>
                </td>
            </tr>`;
    }

    const params = new URLSearchParams({ keyword, relation, class: kelas, status });

    fetch(`${BASE_URL}admin/orangtua/fetchData?${params.toString()}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(res => {
        if (res.status === 'success') {
            parentsData = res.data.map(p => {
                if (p.user_id === null || p.user_id === '0' || !p.user_id) {
                    p.status_akun = 'Belum Aktivasi';
                } else {
                    p.status_akun = (p.is_active == 1) ? 'Aktif' : 'Nonaktif';
                }
                p.no_hp_ortu = (p.no_hp_ortu && p.no_hp_ortu !== 'null') ? p.no_hp_ortu : '-';
                p.email_ortu = (p.email_ortu && p.email_ortu !== 'null') ? p.email_ortu : '-';
                return p;
            });

            currentPage = 1; 
            renderTableHTML(relation); 
            
            const countEl = document.getElementById('totalData');
            if(countEl) countEl.innerText = parentsData.length;
        } else {
            if(tbody) tbody.innerHTML = `<tr><td colspan="7" class="text-center py-6 text-red-500">${LANG.js_failed_load}</td></tr>`;
        }
    })
    .catch(err => {
        console.error(err);
        if(tbody) tbody.innerHTML = `<tr><td colspan="7" class="text-center py-6 text-red-500">${LANG.js_server_error}</td></tr>`;
    });
}

function renderTableHTML(currentFilter = '') {
    const tbody = document.getElementById('parentTableBody');
    if (!tbody) return;
    tbody.innerHTML = '';

    if (!parentsData || parentsData.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="text-center py-12 text-gray-400 dark:text-slate-500 text-sm">${LANG.js_no_data}</td></tr>`;
        const displayEl = document.getElementById('displayRange');
        if(displayEl) displayEl.innerText = '0-0';
        return;
    }

    const totalPages = Math.ceil(parentsData.length / itemsPerPage);
    if (currentPage > totalPages) currentPage = totalPages;
    const start = (currentPage - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const paginatedItems = parentsData.slice(start, end);

    updatePaginationInfo(start + 1, Math.min(end, parentsData.length), parentsData.length);
    renderPaginationButtons(totalPages);

    let html = '';

    paginatedItems.forEach(p => {
        // --- 1. PROTEKSI NAMA ---
        let rawNama = '';
        let relation = '';

        if (currentFilter === 'Ibu' && p.nama_ibu && p.nama_ibu !== '-') {
            rawNama = p.nama_ibu; relation = LANG.mother || 'Ibu';
        } else if (currentFilter === 'Wali' && p.nama_wali && p.nama_wali !== '-') {
            rawNama = p.nama_wali; relation = LANG.guardian || 'Wali';
        } else if (currentFilter === 'Ayah' && p.nama_ayah && p.nama_ayah !== '-') {
            rawNama = p.nama_ayah; relation = LANG.father || 'Ayah';
        } else {
            if (p.nama_ayah && p.nama_ayah !== '-' && p.nama_ayah !== '') {
                rawNama = p.nama_ayah; relation = LANG.father || 'Ayah';
            } else if (p.nama_ibu && p.nama_ibu !== '-' && p.nama_ibu !== '') {
                rawNama = p.nama_ibu; relation = LANG.mother || 'Ibu';
            } else {
                rawNama = p.nama_wali || 'Tanpa Nama';
                relation = LANG.guardian || 'Wali';
            }
        }

        const namaDisplay = String(rawNama).trim();
        const initials = namaDisplay.substring(0, 2).toUpperCase();

        // --- 2. LOGIKA AVATAR (ANTI-BROKEN) ---
        const fallbackUrl = `https://ui-avatars.com/api/?name=${initials}&background=10b981&color=fff&size=100&bold=true`;
        let avatarHtml = '';

        if (p.foto_profil && p.foto_profil !== 'null' && String(p.foto_profil).trim() !== '') {
            const cleanBaseUrl = BASE_URL.replace(/\/$/, '');
            const cacheBuster = '?v=' + new Date().getTime();
            const fotoUrl = `${cleanBaseUrl}/assets/uploads/avatars/${p.foto_profil}${cacheBuster}`;
            
            avatarHtml = `
                <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center text-white font-black text-sm shadow-sm ring-2 ring-white dark:ring-slate-800 transition-colors overflow-hidden shrink-0">
                    <img class="rounded-xl w-full h-full object-cover" src="${fotoUrl}" alt="Avatar" onerror="this.onerror=null; this.src='${fallbackUrl}';">
                </div>
            `;
        } else {
            avatarHtml = `
                <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center text-white font-black text-sm shadow-sm ring-2 ring-white dark:ring-slate-800 transition-colors overflow-hidden shrink-0">
                    <img class="rounded-xl w-full h-full object-cover" src="${fallbackUrl}" alt="Avatar">
                </div>
            `;
        }

        // --- 3. BADGE & KELAS ---
        let badgeStyle = 'bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 border border-purple-200 dark:border-purple-800/50';
        if (relation === (LANG.father || 'Ayah')) badgeStyle = 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800/50';
        else if (relation === (LANG.mother || 'Ibu')) badgeStyle = 'bg-pink-50 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400 border border-pink-200 dark:border-pink-800/50';

        const kelasFull = p.nama_rombel ? `${p.tingkat || ''} - ${p.nama_rombel}` : (LANG.js_no_class || 'Belum ada kelas');
        const safeNama = namaDisplay.replace(/"/g, '&quot;').replace(/'/g, '&#39;');

        // --- 4. RENDER ROW (Dengan type="button" di button action) ---
        html += `
            <tr class="group hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors border-b border-gray-100 dark:border-slate-700/50 last:border-0">
                <td class="px-4 md:px-6 py-4 w-12 text-center">
                    <input type="checkbox" class="parent-checkbox w-4 h-4 rounded border-gray-300 dark:border-slate-500 bg-white dark:bg-slate-700 focus:ring-emerald-500 cursor-pointer focus:ring-offset-0" value="${p.id}">
                </td>
                <td class="px-4 md:px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="cursor-pointer hover:opacity-80 transition-opacity" onclick="showDetailDrawer(${p.id})">
                            ${avatarHtml}
                        </div>
                        <div class="min-w-0 cursor-pointer group-hover:text-emerald-600 transition-colors" onclick="showDetailDrawer(${p.id})">
                            <div class="font-semibold text-gray-800 dark:text-white whitespace-nowrap truncate max-w-[180px]">${namaDisplay}</div>
                            <div class="mt-1"><span class="px-2 py-0.5 rounded text-[10px] border ${badgeStyle}">${relation}</span></div>
                        </div>
                    </div>
                </td>
                <td class="px-4 md:px-6 py-4">
                    <span class="text-sm font-medium text-gray-800 dark:text-slate-200 truncate block max-w-[150px]" title="${p.nama_siswa || '-'}">${p.nama_siswa || '-'}</span>
                </td>
                <td class="px-4 md:px-6 py-4">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-800/50">
                        ${kelasFull}
                    </span>
                </td>
                <td class="px-4 md:px-6 py-4">
                    <div class="text-sm">
                        <div class="font-medium text-gray-800 dark:text-slate-200 font-mono">${p.no_hp_ortu || '-'}</div>
                        <div class="text-xs text-gray-500 dark:text-slate-400 truncate max-w-[150px]">${p.email_ortu || '-'}</div>
                    </div>
                </td>
                <td class="px-4 md:px-6 py-4 text-center">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ${p.status_akun === 'Aktif' ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50' : (p.status_akun === 'Nonaktif' ? 'bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800/50' : 'bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50')}">${p.status_akun || 'Aktif'}</span>
                </td>
                <td class="px-4 md:px-6 py-4 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <button type="button" onclick="showDetailDrawer(${p.id})" class="p-2 text-gray-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-emerald-600 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        </button>
                        <button type="button" onclick="editParent(${p.id})" class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        </button>
                        <button type="button" onclick="triggerDeleteParent(this)" data-id="${p.id}" data-nama="${safeNama}" class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });

    tbody.innerHTML = html;
}

function updatePaginationInfo(start, end, total) {
    const displayEl = document.getElementById('displayRange');
    if(displayEl) displayEl.innerText = `${start}-${end}`;
}

function renderPaginationButtons(totalPages) {
    const btnContainer = document.getElementById('pagination-buttons');
    if (!btnContainer) return;

    let btns = `<button onclick="changePage(${currentPage - 1})" class="px-3 py-1.5 border border-gray-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-800 text-gray-600 dark:text-slate-300 ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors'}" ${currentPage === 1 ? 'disabled' : ''}>&lt;</button>`;
    
    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
            const activeClass = i === currentPage 
                ? 'bg-emerald-600 text-white border-emerald-600' 
                : 'bg-white dark:bg-slate-800 text-gray-600 dark:text-slate-300 border-gray-200 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-700';
            
            btns += `<button onclick="changePage(${i})" class="px-3 py-1.5 border rounded-lg text-sm transition-colors ${activeClass}">${i}</button>`;
        } else if (i === currentPage - 2 || i === currentPage + 2) {
            btns += `<span class="px-2 text-gray-500 dark:text-slate-400">...</span>`;
        }
    }
    
    btns += `<button onclick="changePage(${currentPage + 1})" class="px-3 py-1.5 border border-gray-200 dark:border-slate-600 rounded-lg text-sm bg-white dark:bg-slate-800 text-gray-600 dark:text-slate-300 ${currentPage >= totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors'}" ${currentPage >= totalPages ? 'disabled' : ''}>&gt;</button>`;
    
    btnContainer.innerHTML = btns;
}

function changePage(page) {
    const totalPages = Math.ceil(parentsData.length / itemsPerPage);
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    const relation = document.getElementById('filterRelation')?.value || '';
    renderTableHTML(relation);
}

// --- FUNGSI LACI DETAIL (DRAWER) AMAN DARI ERROR ---
window.showDetailDrawer = function(id) {
    const p = parentsData.find(x => x.id == id);
    if (!p) return;

    const v = (val) => (val && val !== '-' && val !== 'null' && String(val).trim() !== '') ? val : '-';
    
    let rawNama = '';
    let relation = '';
    if (p.nama_ayah && p.nama_ayah !== '-' && p.nama_ayah !== '') {
        rawNama = p.nama_ayah; relation = LANG.father || 'Ayah';
    } else if (p.nama_ibu && p.nama_ibu !== '-' && p.nama_ibu !== '') {
        rawNama = p.nama_ibu; relation = LANG.mother || 'Ibu';
    } else {
        rawNama = p.nama_wali || ''; 
        relation = LANG.guardian || 'Wali';
    }

    const nama = String(rawNama || 'Tanpa Nama').trim();

    // DOM Updates Text
    if(document.getElementById('drawerName')) document.getElementById('drawerName').innerText = v(nama);
    if(document.getElementById('drawerRelation')) document.getElementById('drawerRelation').innerText = relation;
    
    let relClass = 'bg-gray-100 text-gray-700';
    if (relation === (LANG.father || 'Ayah')) relClass = 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400';
    else if (relation === (LANG.mother || 'Ibu')) relClass = 'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400';
    else if (relation === (LANG.guardian || 'Wali')) relClass = 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400';
    
    const dRelation = document.getElementById('drawerRelation');
    if(dRelation) dRelation.className = `inline-flex items-center px-2.5 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider ${relClass}`;

    const statusEl = document.getElementById('drawerStatus');
    if(statusEl) {
        statusEl.innerText = p.status_akun;
        if(p.status_akun === 'Aktif') {
            statusEl.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400';
        } else if (p.status_akun === 'Nonaktif') {
            statusEl.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';
        } else {
            statusEl.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400';
        }
    }

    const mapText = [
        ['drawerPhone', p.no_hp_ortu], ['drawerEmail', p.email_ortu], ['drawerAddress', p.alamat_orangtua],
        ['dtlAyahNama', p.nama_ayah], ['dtlAyahNik', p.nik_ayah], ['dtlAyahLahir', p.tahun_lahir_ayah], 
        ['dtlAyahPend', p.pendidikan_ayah], ['dtlAyahKerja', p.pekerjaan_ayah], ['dtlAyahGaji', p.penghasilan_ayah],
        ['dtlIbuNama', p.nama_ibu], ['dtlIbuNik', p.nik_ibu], ['dtlIbuLahir', p.tahun_lahir_ibu], 
        ['dtlIbuPend', p.pendidikan_ibu], ['dtlIbuKerja', p.pekerjaan_ibu], ['dtlIbuGaji', p.penghasilan_ibu],
        ['dtlWaliNama', p.nama_wali], ['dtlWaliNik', p.nik_wali], ['dtlWaliKerja', p.pekerjaan_wali]
    ];
    mapText.forEach(item => {
        const el = document.getElementById(item[0]);
        if(el) el.innerText = v(item[1]);
    });

    // --- AVATAR LACI (ANTI-BROKEN) ---
    const initials = nama.substring(0, 2).toUpperCase();
    const fallbackUrl = `https://ui-avatars.com/api/?name=${initials}&background=10b981&color=fff&size=160&bold=true`;
    
    const avatarEl = document.getElementById('drawerAvatar');
    if(avatarEl) {
        avatarEl.className = 'w-24 h-24 rounded-2xl mx-auto mb-4 shadow-lg border border-gray-200 dark:border-slate-600 overflow-hidden flex items-center justify-center shrink-0';
        
        if (p.foto_profil && p.foto_profil !== 'null' && String(p.foto_profil).trim() !== '') {
            const cleanBaseUrl = BASE_URL.replace(/\/$/, '');
            const cacheBuster = '?v=' + new Date().getTime();
            const fotoUrl = `${cleanBaseUrl}/assets/uploads/avatars/${p.foto_profil}${cacheBuster}`;
            
            avatarEl.innerHTML = `<img src="${fotoUrl}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='${fallbackUrl}';">`;
        } else {
            avatarEl.innerHTML = `<img src="${fallbackUrl}" class="w-full h-full object-cover">`;
        }
    }

    const drawer = document.getElementById('detailDrawer');
    const overlay = document.getElementById('drawer-overlay');
    if(drawer && overlay) {
        overlay.classList.remove('hidden');
        setTimeout(() => drawer.classList.remove('translate-x-full'), 10);
    }
    
    const btnEdit = document.getElementById('btnDrawerEdit');
    if (btnEdit) {
        btnEdit.onclick = function() {
            closeDrawer(); 
            setTimeout(() => editParent(id), 300); 
        };
    }
};

window.closeDrawer = function() {
    const drawer = document.getElementById('detailDrawer');
    const overlay = document.getElementById('drawer-overlay');
    if(drawer && overlay) {
        drawer.classList.add('translate-x-full');
        setTimeout(() => overlay.classList.add('hidden'), 300);
    }
};

function setupStudentSearch() {
    const searchInput = document.getElementById('student_search');
    const resultsBox = document.getElementById('student_results');
    const hiddenId = document.getElementById('student_id_hidden');
    let timeout = null;

    if (!searchInput || !resultsBox || !hiddenId) return;

    searchInput.addEventListener('input', function() {
        const keyword = this.value;
        clearTimeout(timeout);

        if (keyword.length < 1) { 
            resultsBox.classList.add('hidden');
            hiddenId.value = ''; 
            return;
        }

        timeout = setTimeout(() => {
            resultsBox.innerHTML = `<div class="px-4 py-3 text-sm text-gray-500 dark:text-slate-400">${LANG.js_searching}</div>`;
            resultsBox.classList.remove('hidden');

            fetch(`${BASE_URL}admin/orangtua/searchSiswa?term=${keyword}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                resultsBox.innerHTML = '';
                if (data.length > 0) {
                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.className = 'px-4 py-2 cursor-pointer text-sm border-b border-gray-100 dark:border-slate-700 text-gray-800 dark:text-white hover:bg-emerald-50 dark:hover:bg-slate-700 hover:border-l-4 hover:border-l-blue-500 transition-all last:border-b-0';
                        div.textContent = item.text;
                        div.addEventListener('click', () => {
                            searchInput.value = item.text;
                            hiddenId.value = item.id;
                            resultsBox.classList.add('hidden');
                        });
                        resultsBox.appendChild(div);
                    });
                } else {
                    resultsBox.innerHTML = `<div class="px-4 py-2 text-sm text-red-500 dark:text-red-400">${LANG.js_student_not_found}</div>`;
                }
            })
            .catch(err => {
                console.error(err);
                resultsBox.innerHTML = `<div class="px-4 py-2 text-sm text-red-500 dark:text-red-400">${LANG.js_server_error}</div>`;
            });
        }, 300);
    });
    
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
            resultsBox.classList.add('hidden');
        }
    });
}

window.showAddModal = function() {
    const form = document.getElementById('addParentForm');
    const modal = document.getElementById('addModal');
    
    if(form) form.reset();
    if(document.getElementById('student_id_hidden')) document.getElementById('student_id_hidden').value = '';
    if(document.getElementById('student_search')) document.getElementById('student_search').value = '';
    
    if(modal) {
        if(modal.querySelector('h3')) modal.querySelector('h3').textContent = LANG.js_add_new_parent;
        if(modal.querySelector('p')) modal.querySelector('p').textContent = LANG.js_add_new_parent_desc;
        modal.classList.remove('hidden'); 
        document.body.style.overflow = 'hidden';
    }
};

window.closeAddModal = function() {
    const modal = document.getElementById('addModal');
    if(modal) {
        modal.classList.add('hidden'); 
        document.body.style.overflow = '';
    }
};

window.editParent = function(id) {
    const loadingToast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timerProgressBar: true });
    loadingToast.fire({ icon: 'info', title: LANG.js_fetching_data });

    fetch(`${BASE_URL}admin/orangtua/show/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(response => response.json())
    .then(res => {
        if (res.status === 'success') {
            const data = res.data;
            const form = document.getElementById('addParentForm');
            const modal = document.getElementById('addModal');

            if(modal.querySelector('h3')) modal.querySelector('h3').textContent = LANG.js_edit_parent;
            if(modal.querySelector('p')) modal.querySelector('p').textContent = LANG.js_edit_parent_desc;
            
            const setVal = (name, val) => { if(form.elements[name]) form.elements[name].value = (val && val !== '-') ? val : ''; };

            setVal('nama_ayah', data.nama_ayah);
            setVal('nik_ayah', data.nik_ayah);
            setVal('tahun_lahir_ayah', data.tahun_lahir_ayah);
            setVal('pendidikan_ayah', data.pendidikan_ayah);
            setVal('pekerjaan_ayah', data.pekerjaan_ayah);
            setVal('penghasilan_ayah', data.penghasilan_ayah);

            setVal('nama_ibu', data.nama_ibu);
            setVal('nik_ibu', data.nik_ibu);
            setVal('tahun_lahir_ibu', data.tahun_lahir_ibu);
            setVal('pendidikan_ibu', data.pendidikan_ibu);
            setVal('pekerjaan_ibu', data.pekerjaan_ibu);
            setVal('penghasilan_ibu', data.penghasilan_ibu);

            setVal('nama_wali', data.nama_wali);
            setVal('nik_wali', data.nik_wali);
            setVal('pekerjaan_wali', data.pekerjaan_wali);

            setVal('phone', data.no_hp_ortu);
            setVal('email', data.email_ortu);
            setVal('address', data.alamat_orangtua);

            const statusAkunEl = document.getElementById('status_akun');
            if (statusAkunEl) {
                statusAkunEl.value = (data.is_active === '0' || data.is_active === 0) ? '0' : '1';
            }

            if(document.getElementById('student_id_hidden')) document.getElementById('student_id_hidden').value = data.siswa_id;
            let studentLabel = data.nama_siswa || LANG.js_student_deleted;
            if(data.nis) studentLabel += ` (${data.nis})`;
            if(document.getElementById('student_search')) document.getElementById('student_search').value = studentLabel;
            
            modal.classList.remove('hidden'); 
            document.body.style.overflow = 'hidden';
            Swal.close();
        } else {
            Swal.fire('Error', res.message, 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error', LANG.js_server_error, 'error');
    });
};

window.handleSubmit = function(event) {
    event.preventDefault();
    const form = document.getElementById('addParentForm');

    // --- UI VALIDATION CUSTOM (EFEK TEKS MERAH) ---
    // 1. Bersihkan efek merah & teks error sebelumnya (jika ada)
    const errorFields = form.querySelectorAll('.border-red-500');
    errorFields.forEach(el => {
        el.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
        el.classList.add('border-gray-200', 'dark:border-slate-600');
    });
    form.querySelectorAll('.error-text-validation').forEach(el => el.remove());

    // 2. Cek validasi khusus untuk Input Hidden Siswa
    const hiddenStudent = document.getElementById('student_id_hidden');
    const searchStudent = document.getElementById('student_search');
    let isStudentValid = hiddenStudent && hiddenStudent.value !== '';

    // 3. Jika form tidak valid ATAU siswa belum dipilih dari dropdown pencarian
    if (!form.checkValidity() || !isStudentValid) {
        let invalidElements = Array.from(form.querySelectorAll(':invalid'));

        // Karena input siswa itu hidden, HTML5 tidak bisa memberinya border merah.
        // Kita keluarkan input hidden dari array, dan ganti dengan kotak pencarian visualnya
        invalidElements = invalidElements.filter(el => el.type !== 'hidden');
        if (!isStudentValid && !invalidElements.includes(searchStudent)) {
            invalidElements.unshift(searchStudent); 
        }

        invalidElements.forEach((el, index) => {
            // Warnai GARIS TEPI kotak input jadi merah
            el.classList.remove('border-gray-200', 'dark:border-slate-600');
            el.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');

            // Buat elemen teks pesan error
            let pesanError = '⚠️ Wajib diisi';
            if (el.type === 'email' && el.value !== '') pesanError = '⚠️ Format email tidak valid';
            if (el.id === 'student_search') pesanError = '⚠️ Silakan cari dan klik nama siswa dari daftar!';

            const errorText = document.createElement('p');
            errorText.className = 'error-text-validation text-red-500 text-[11px] mt-1.5 font-medium animate-pulse';
            errorText.innerText = pesanError;
            
            el.parentNode.appendChild(errorText);

            // Fokuskan layar ke error pertama
            if (index === 0) el.focus();

            // Fungsi untuk menghilangkan error saat user mulai mengetik/memilih
            const removeError = function() {
                if (el.checkValidity() || (el.id === 'student_search' && hiddenStudent.value !== '')) {
                    el.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                    el.classList.add('border-gray-200', 'dark:border-slate-600');
                    const errTxt = el.parentNode.querySelector('.error-text-validation');
                    if (errTxt) errTxt.remove();
                    
                    el.removeEventListener('input', removeError);
                    el.removeEventListener('change', removeError);
                }
            };

            // Pasang pendeteksi
            el.addEventListener('input', removeError);
            el.addEventListener('change', removeError);
        });

        // Hentikan proses simpan agar tidak muncul SweetAlert dari backend
        return; 
    }
    // -----------------------------------------

    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...`;

    const formData = new FormData(form);

    fetch(`${BASE_URL}admin/orangtua/store`, {
        method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({ icon: 'success', title: LANG.js_success, text: data.message, timer: 1500, showConfirmButton: false }).then(() => {
                closeAddModal();
                loadTableData(); 
            });
        } else {
            let msg = data.errors ? Object.values(data.errors).join('\n') : data.message;
            Swal.fire(LANG.js_failed, msg, 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire('Error!', LANG.js_system_error, 'error');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
};

window.triggerDeleteParent = function(btn) {
    const id = btn.getAttribute('data-id');
    const name = btn.getAttribute('data-nama');
    
    const csrfInput = document.querySelector('input[name="csrf_test_name"]');
    const csrfToken = csrfInput ? csrfInput.value : '';

    Swal.fire({
        title: 'Hapus Data?',
        text: `Data wali "${name}" dan akun loginnya akan dihapus permanen!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.showLoading();
            
            fetch(`${BASE_URL}admin/orangtua/delete/${id}`, {
                method: 'DELETE', 
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken 
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('Terhapus!', 'Data berhasil dihapus.', 'success').then(() => loadTableData());
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error!', 'Terjadi kesalahan sistem (Muat ulang halaman jika diperlukan).', 'error');
            });
        }
    });
};

window.showImportModal = function() {
    const modal = document.getElementById('importModal');
    if(modal) modal.classList.remove('hidden');
};

window.closeImportModal = function() {
    const modal = document.getElementById('importModal');
    if(modal) modal.classList.add('hidden');
};

window.handleImport = async function(event) {
    event.preventDefault();
    const form = event.target;
    const btn = form.querySelector('button[type="submit"]');
    const oriText = btn.innerHTML;
    
    btn.innerHTML = LANG.js_analyzing;
    btn.disabled = true;

    try {
        const response = await fetch(form.action, { method: 'POST', body: new FormData(form) });
        const rawText = await response.text();
        let data;
        
        try {
            const jsonStart = rawText.indexOf('{');
            const jsonEnd = rawText.lastIndexOf('}') + 1;
            if (jsonStart === -1) throw new Error("Tidak ada JSON");
            data = JSON.parse(rawText.substring(jsonStart, jsonEnd));
        } catch (e) {
            Swal.fire(LANG.js_info, LANG.js_import_warning, 'warning').then(() => {
                closeImportModal(); loadTableData();
            });
            return; 
        }

        if (data.status === 'success') {
            Swal.fire({ icon: 'success', title: LANG.js_success, text: data.message, timer: 2000, showConfirmButton: false }).then(() => { 
                closeImportModal(); loadTableData(); 
            });
        } else {
            Swal.fire(LANG.js_failed, data.message, 'error');
        }
    } catch (error) {
        console.error("Koneksi Mati:", error);
        Swal.fire('Error', LANG.js_connection_lost, 'error');
    } finally {
        if(btn) { btn.innerHTML = oriText; btn.disabled = false; }
    }
};

window.toggleSelectAll = function(source) {
    const checkboxes = document.querySelectorAll('.parent-checkbox');
    checkboxes.forEach(cb => cb.checked = source.checked);
    updateBulkActionVisibility();
};

window.updateBulkActionVisibility = function() {
    const count = document.querySelectorAll('.parent-checkbox:checked').length;
    const bulkDiv = document.getElementById('bulkActions');
    const countSpan = document.getElementById('selectedCount');
    
    if(count > 0) {
        if(bulkDiv) bulkDiv.classList.remove('hidden');
        if(countSpan) {
            countSpan.innerText = `(${count} ${LANG.js_selected})`;
            countSpan.classList.remove('hidden');
        }
    } else {
        if(bulkDiv) bulkDiv.classList.add('hidden');
        if(countSpan) countSpan.classList.add('hidden');
        const selAll = document.getElementById('selectAll');
        if(selAll) selAll.checked = false; 
    }
};

document.getElementById('parentTableBody')?.addEventListener('change', function(e) {
    if(e.target && e.target.classList.contains('parent-checkbox')) {
        updateBulkActionVisibility();
    }
});