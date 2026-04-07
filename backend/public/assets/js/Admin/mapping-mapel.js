/**
 * File: public/assets/js/Admin/mapping-mapel.js
 */

const mappingData = (typeof dbMappingData !== 'undefined') ? dbMappingData : [];
let filteredData = [...mappingData];
let deleteTargetId = null;

// TAMBAHKAN VARIABEL INI
let currentPage = 1;
const itemsPerPage = 10; // Anda bisa mengubah angka ini (misal 15 atau 20)

// --- FUNGSI TOAST ---
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `transform transition-all duration-300 translate-x-full opacity-0 flex items-center w-full max-w-sm p-4 space-x-3 text-gray-500 bg-white dark:bg-slate-800 rounded-xl shadow-2xl border-l-4 pointer-events-auto ${type === 'success' ? 'border-emerald-500' : (type === 'warning' ? 'border-amber-500' : 'border-red-500')}`;
    
    const icon = type === 'success' 
        ? `<div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-emerald-500 bg-emerald-100 rounded-lg"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg></div>`
        : (type === 'warning' ? `<div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-amber-500 bg-amber-100 rounded-lg"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg></div>` 
        : `<div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg></div>`);

    toast.innerHTML = `
        ${icon}
        <div class="ml-3 text-sm font-medium text-gray-800 dark:text-slate-200 leading-snug">${message}</div>
        <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white dark:bg-slate-800 text-gray-400 hover:text-gray-900 dark:hover:text-white rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 dark:hover:bg-slate-700 inline-flex h-8 w-8" onclick="this.parentElement.remove()">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </button>
    `;

    container.appendChild(toast);

    requestAnimationFrame(() => {
        toast.classList.remove('translate-x-full', 'opacity-0');
    });

    setTimeout(() => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

function checkPendingToast() {
    const msg = localStorage.getItem('toastMessage');
    const type = localStorage.getItem('toastType');
    if (msg) {
        showToast(msg, type || 'success');
        localStorage.removeItem('toastMessage');
        localStorage.removeItem('toastType');
    }
}

// --- FUNGSI TABEL & FILTER ---
function populateTable() {
    const tbody = document.getElementById('mappingTableBody');
    const paginationContainer = document.getElementById('pagination-container'); // Ambil elemen pagination
    if (!tbody) return;
    if (filteredData.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="px-6 py-16 text-center text-gray-500 dark:text-slate-400 font-medium">Data mapping kosong atau tidak ditemukan.</td></tr>`;
        if (paginationContainer) paginationContainer.style.display = 'none'; // Sembunyikan pagination
        return;
    }

    if (paginationContainer) paginationContainer.style.display = 'flex'; // Tampilkan pagination

    // --- LOGIKA POTONG DATA (PAGINATION) ---
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const paginatedData = filteredData.slice(startIndex, endIndex); // Ambil data sesuai halaman

    tbody.innerHTML = paginatedData.map(item => {
        let initials = item.teacher ? item.teacher.substring(0, 2).toUpperCase() : 'GU';
        let tableAvatar = '';
        
        // Logika Avatar Tabel (Sistem Single Source)
        if (item.foto && String(item.foto).trim() !== '' && item.foto !== 'null') {
            let safeBaseUrl = BASE_URL;
            if(!safeBaseUrl.endsWith('/')) safeBaseUrl += '/';
            const cacheBuster = '?v=' + new Date().getTime();
            const fotoUrl = `${safeBaseUrl}assets/uploads/avatars/${item.foto}${cacheBuster}`;
            const fallbackHTML = `<span class=\\'text-white font-bold text-xs\\'>${initials}</span>`;
            
            tableAvatar = `<img src="${fotoUrl}" class="w-full h-full object-cover" onerror="this.onerror=null; this.outerHTML='${fallbackHTML}';">`;
        } else {
            tableAvatar = `<span class="text-white font-bold text-xs">${initials}</span>`;
        }

        return `
        <tr class="table-row bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700/50 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors group">
          <td class="px-6 py-4">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-lg flex items-center justify-center shadow-md border border-transparent overflow-hidden" style="background-color: var(--warna-primary, #10b981);">
                ${tableAvatar}
              </div>
              <div>
                <p class="font-bold text-gray-800 dark:text-white group-hover:text-[var(--warna-primary,#10b981)] transition-colors">${item.teacher}</p>
                <p class="text-[11px] font-medium text-gray-500 dark:text-slate-400 font-mono">NIK: ${item.nik || '-'}</p>
              </div>
            </div>
          </td>
          <td class="px-6 py-4"><span class="font-bold text-gray-800 dark:text-slate-200">${item.mapel}</span></td>
          <td class="px-6 py-4"><span class="text-sm font-semibold text-gray-600 dark:text-slate-300">Kelas ${item.level}</span></td>
          <td class="px-6 py-4"><div class="flex flex-wrap gap-1"><span class="px-2.5 py-1 text-xs font-bold bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800/50 rounded-lg shadow-sm">${item.level}-${item.rombel}</span></div></td>
          <td class="px-6 py-4 text-center"><span class="font-bold text-gray-800 dark:text-slate-200">${item.jam} JP</span></td>
          <td class="px-6 py-4"><span class="text-sm font-medium text-gray-600 dark:text-slate-300">${item.tahunAjaran}</span></td>
          <td class="px-6 py-4 text-center">
            ${item.status === 'active' 
              ? `<span class="px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 rounded-full shadow-sm">Aktif</span>` 
              : `<span class="px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 border border-gray-200 dark:border-slate-600 rounded-full shadow-sm">Nonaktif</span>`
            }
          </td>
          <td class="px-6 py-4">
            <div class="flex items-center justify-center gap-2 opacity-1 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity">
              <button onclick="showDetail(${item.id})" class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg tooltip transition-colors outline-none" title="Detail">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              </button>
              <button onclick="showEditModal(${item.id})" class="p-2 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-lg transition-colors outline-none" title="Edit">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              </button>
              <button onclick="confirmDeactivate(${item.id})" class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors outline-none" title="Hapus">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
              </button>
            </div>
          </td>
        </tr>
        `;
    }).join('');
    renderPagination();
}

// --- FUNGSI RENDER TOMBOL PAGINATION ---
function renderPagination() {
    const totalItems = filteredData.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    
    // Update teks info (Contoh: Menampilkan 1 sampai 10 dari 50 entri)
    const startItem = (currentPage - 1) * itemsPerPage + 1;
    const endItem = Math.min(currentPage * itemsPerPage, totalItems);
    const infoText = document.getElementById('pagination-info');
    if(infoText) infoText.textContent = `Menampilkan ${startItem} sampai ${endItem} dari ${totalItems} entri`;

    const controls = document.getElementById('pagination-controls');
    if(!controls) return;
    
    let paginationHTML = '';

    // Tombol Prev
    paginationHTML += `<button onclick="changePage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-slate-600 text-sm font-medium ${currentPage === 1 ? 'text-gray-400 bg-gray-50 dark:bg-slate-800 cursor-not-allowed' : 'text-gray-700 dark:text-slate-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-[var(--warna-primary,#10b981)] hover:text-white hover:border-transparent transition-colors outline-none'}">Prev</button>`;

    // Logika Tombol Angka (Dibatasi agar tidak kepanjangan jika halamannya banyak)
    for (let i = 1; i <= totalPages; i++) {
        if(i === currentPage) {
            paginationHTML += `<button class="px-3 py-1.5 rounded-lg text-sm font-bold text-white shadow-sm outline-none" style="background-color: var(--warna-primary, #10b981);">${i}</button>`;
        } else if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
            paginationHTML += `<button onclick="changePage(${i})" class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-slate-600 text-sm font-medium text-gray-700 dark:text-slate-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-[var(--warna-primary,#10b981)] hover:text-white hover:border-transparent transition-colors outline-none">${i}</button>`;
        } else if (i === currentPage - 2 || i === currentPage + 2) {
            paginationHTML += `<span class="px-2 text-gray-500 dark:text-slate-400">...</span>`;
        }
    }

    // Tombol Next
    paginationHTML += `<button onclick="changePage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''} class="px-3 py-1.5 rounded-lg border border-gray-200 dark:border-slate-600 text-sm font-medium ${currentPage === totalPages ? 'text-gray-400 bg-gray-50 dark:bg-slate-800 cursor-not-allowed' : 'text-gray-700 dark:text-slate-300 bg-white dark:bg-slate-700 hover:bg-gray-50 dark:hover:bg-[var(--warna-primary,#10b981)] hover:text-white hover:border-transparent transition-colors outline-none'}">Next</button>`;

    controls.innerHTML = paginationHTML;
}

// Fungsi untuk mengeksekusi perpindahan halaman
window.changePage = function(page) {
    const totalPages = Math.ceil(filteredData.length / itemsPerPage);
    if (page >= 1 && page <= totalPages) {
        currentPage = page;
        populateTable();
    }
};

function applyFilters() {
    const tahun = document.getElementById('filterTahun') ? document.getElementById('filterTahun').value : '';
    const level = document.getElementById('filterLevel') ? document.getElementById('filterLevel').value : '';
    const rombelId = document.getElementById('filterRombel') ? document.getElementById('filterRombel').value : ''; 
    const mapelId = document.getElementById('filterMapel') ? document.getElementById('filterMapel').value : '';   
    const guruId = document.getElementById('filterGuru') ? document.getElementById('filterGuru').value : '';     
    const search = document.getElementById('searchInput') ? document.getElementById('searchInput').value.toLowerCase() : '';
    const activeOnly = document.getElementById('toggleActiveOnly') ? document.getElementById('toggleActiveOnly').checked : false;

    filteredData = mappingData.filter(item => {
        const matchTahun = !tahun || (item.tahunAjaran && item.tahunAjaran.includes(tahun)) || (item.tahunAjaran && tahun.includes(item.tahunAjaran));
        
        const matchLevel = !level || item.level === level;
        const matchRombel = !rombelId || item.rombel_id == rombelId;
        const matchMapel = !mapelId || item.mapel_id == mapelId;
        const matchGuru = !guruId || item.teacher_id == guruId;
        
        const matchSearch = !search || 
            (item.teacher && item.teacher.toLowerCase().includes(search)) || 
            (item.mapel && item.mapel.toLowerCase().includes(search)) ||
            (item.rombel_full && item.rombel_full.toLowerCase().includes(search)) ||
            (item.nik && item.nik.toLowerCase().includes(search));

        const matchActive = !activeOnly || item.status === 'active';

        currentPage = 1; // <-- TAMBAHKAN BARIS INI: Reset ke halaman 1 setiap kali filter diubah
        return matchTahun && matchLevel && matchRombel && matchMapel && matchGuru && matchSearch && matchActive;
    });

    populateTable();
}

['filterTahun', 'filterLevel', 'filterRombel', 'filterMapel', 'filterGuru'].forEach(id => {
    if(document.getElementById(id)) document.getElementById(id).addEventListener('change', applyFilters);
});
if(document.getElementById('searchInput')) document.getElementById('searchInput').addEventListener('input', applyFilters);
if(document.getElementById('toggleActiveOnly')) document.getElementById('toggleActiveOnly').addEventListener('change', applyFilters);

// --- DRAWER KARTU IDENTITAS MENGAJAR ---
function showDetail(id) {
    const item = mappingData.find(d => d.id == id);
    if (!item) return;

    // Logika Avatar Drawer
    const avatarContainer = document.getElementById('drawerAvatarContainer');
    if (item.foto && String(item.foto).trim() !== '' && item.foto !== 'null') {
        let safeBaseUrl = BASE_URL;
        if(!safeBaseUrl.endsWith('/')) safeBaseUrl += '/';
        const cacheBuster = '?v=' + new Date().getTime();
        const fotoUrl = `${safeBaseUrl}assets/uploads/avatars/${item.foto}${cacheBuster}`;
        const fallbackHTML = `<svg class=\\'w-12 h-12 text-white/90\\' fill=\\'none\\' stroke=\\'currentColor\\' viewBox=\\'0 0 24 24\\'><path stroke-linecap=\\'round\\' stroke-linejoin=\\'round\\' stroke-width=\\'2\\' d=\\'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z\\' /></svg>`;
        
        avatarContainer.innerHTML = `<img src="${fotoUrl}" alt="Profil Guru" class="w-full h-full object-cover" onerror="this.onerror=null; this.outerHTML='${fallbackHTML}';">`;
    } else {
        avatarContainer.innerHTML = `<svg class="w-12 h-12 text-white/90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>`;
    }

    document.getElementById('drawerTeacherName').textContent = item.teacher || 'Tidak Diketahui';
    document.getElementById('drawerTeacherNIP').textContent = `NIK: ${item.nik || '-'}`;
    
    document.getElementById('drawerMapel').textContent = item.mapel;
    document.getElementById('drawerKodeMapel').textContent = item.kode_mapel || '-';
    
    document.getElementById('drawerTotalRombel').textContent = `${item.total_rombel} Kelas`;
    document.getElementById('drawerListRombel').textContent = item.list_rombel || '-';
    
    document.getElementById('drawerJam').textContent = `${item.jam} JP`;
    document.getElementById('drawerTahunAjaran').textContent = item.tahunAjaran;

    // --- LOGIKA KALENDER MINI ---
    const daysOfWeek = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    let activeDays = item.hari_masuk ? item.hari_masuk.split(', ') : [];
    
    let calendarHTML = '<div class="flex gap-1.5 w-full">';
    daysOfWeek.forEach(day => {
        let isActive = activeDays.includes(day);
        if (isActive) {
            // Hari Aktif (Ter-Highlight dengan Primary Color)
            calendarHTML += `
            <div class="flex-1 min-w-[40px] py-1.5 text-white text-center rounded-lg shadow-sm border" style="background-color: ${PRIMARY_COLOR}; border-color: ${PRIMARY_COLOR};">
                <span class="text-[10px] font-bold block uppercase tracking-wider">${day.substring(0,3)}</span>
            </div>`;
        } else {
            // Hari Pasif (Abu-abu)
            calendarHTML += `
            <div class="flex-1 min-w-[40px] py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 text-center rounded-lg border border-slate-200 dark:border-slate-700 opacity-60">
                <span class="text-[10px] font-medium block uppercase tracking-wider">${day.substring(0,3)}</span>
            </div>`;
        }
    });
    calendarHTML += '</div>';

    // Jika array kosong (artinya jadwal belum dibuat di tabel jadwal_pelajaran)
    if (activeDays.length === 0 || activeDays[0] === '') {
        calendarHTML += `<p class="text-[10px] text-amber-500 font-medium mt-2 italic flex items-center gap-1.5"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Data hari masuk belum tersedia di Jadwal Pelajaran.</p>`;
    }

    document.getElementById('drawerHari').innerHTML = calendarHTML;
    document.getElementById('editId').value = item.id;

    document.getElementById('detailDrawer').classList.remove('hidden');
    document.getElementById('drawer-overlay').classList.remove('hidden');
    setTimeout(() => document.getElementById('detailDrawer').classList.remove('translate-x-full'), 10);
}

function closeDrawer() {
    const drawer = document.getElementById('detailDrawer');
    if(drawer) {
        drawer.classList.add('translate-x-full');
        setTimeout(() => drawer.classList.add('hidden'), 300);
    }
    const overlay = document.getElementById('drawer-overlay');
    if(overlay) overlay.classList.add('hidden');
}

// --- MODAL MANUAL ---
function showAddModal() {
    const modal = document.getElementById('addModal');
    if(modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeAddModal() {
    const modal = document.getElementById('addModal');
    if(modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
    document.getElementById('addMappingForm').reset();
    document.getElementById('editId').value = ''; 
    document.querySelector('#btnSubmitAdd').textContent = 'Simpan Mapping';
    document.querySelectorAll('.rombel-cb').forEach(cb => cb.checked = false);
    document.getElementById('modalTitle').textContent = "Tambah Mapping Mapel";
}

function showEditModal(id) {
    const item = mappingData.find(d => d.id == id);
    if(!item) return;

    document.getElementById('editId').value = item.id;
    document.getElementById('add_guru').value = item.teacher_id; 
    document.getElementById('add_mapel').value = item.mapel_id;  
    document.getElementById('add_jam').value = item.jam;        
    document.getElementById('add_catatan').value = item.catatan || '';
    
    document.querySelectorAll('.rombel-cb').forEach(cb => cb.checked = false);
    const checkbox = document.querySelector(`.rombel-cb[value="${item.rombel_id}"]`);
    if(checkbox) checkbox.checked = true;
    
    document.querySelector('#btnSubmitAdd').textContent = 'Simpan Perubahan';
    document.getElementById('modalTitle').textContent = "Edit Mapping Mapel";
    
    closeDrawer(); 
    showAddModal();
}

async function handleAddSubmit(event) {
    event.preventDefault();

    const rombels = document.querySelectorAll('.rombel-cb:checked');
    if (rombels.length === 0) {
        showToast('Pilih minimal satu rombel!', 'error');
        return;
    }

    const rombelArray = Array.from(rombels).map(cb => cb.value);

    const form = event.target;
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.innerText;
    btn.innerText = 'Menyimpan...';
    btn.disabled = true;

    try {
        const formData = new FormData(form);
        formData.delete('add_rombel');
        formData.append('add_rombel', JSON.stringify(rombelArray));

        const id = document.getElementById('editId').value;
        const url = id ? `${BASE_URL}/admin/mapping-mapel/update` : `${BASE_URL}/admin/mapping-mapel/store`;

        const res = await fetch(url, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        
        const json = await res.json();

        if (json.status === 'success') {
            localStorage.setItem('toastMessage', json.message);
            localStorage.setItem('toastType', 'success');
            window.location.reload(); 
        } else {
            showToast(json.message, 'error');
        }
    } catch (err) {
        console.error("ERROR FETCH MAPPING:", err);
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan Server',
            text: err.message || 'Gagal terhubung ke server.',
            customClass: { popup: 'rounded-3xl' }
        });
    } finally {
        btn.innerText = originalText;
        btn.disabled = false;
    }
}

// --- HANDLE DELETE ---
function confirmDeactivate(id) {
    deleteTargetId = id;
    const modal = document.getElementById('deleteModal');
    if(modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    if(modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
    deleteTargetId = null;
}

window.confirmDelete = async function() {
    if(!deleteTargetId) return;
    const formData = new FormData();
    formData.append('id', deleteTargetId);

    try {
        const res = await fetch(`${BASE_URL}/admin/mapping-mapel/delete`, {
            method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();

        if (json.status === 'success') {
            localStorage.setItem('toastMessage', json.message);
            localStorage.setItem('toastType', 'success');
            window.location.reload();
        } else {
            showToast(json.message, 'error');
            closeDeleteModal();
        }
    } catch (err) {
        showToast('Koneksi terputus.', 'error');
        closeDeleteModal();
    } 
};

// --- HANDLE IMPORT EXCEL SMART MATCH ---
function showImportModal() {
    const modal = document.getElementById('importModal');
    if(modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeImportModal() {
    const modal = document.getElementById('importModal');
    if(modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
    document.getElementById('importForm').reset();
    document.getElementById('fileNameText').textContent = 'Klik atau Seret file Excel Anda';
    document.getElementById('fileNameText').classList.remove('text-blue-600', 'dark:text-blue-400');
}

function updateFileName(input) {
    const fileNameText = document.getElementById('fileNameText');
    if (input.files && input.files.length > 0) {
        fileNameText.textContent = input.files[0].name;
        fileNameText.classList.add('text-blue-600', 'dark:text-blue-400');
    } else {
        fileNameText.textContent = 'Klik atau Seret file Excel Anda';
        fileNameText.classList.remove('text-blue-600', 'dark:text-blue-400');
    }
}

async function handleImportSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const btn = document.getElementById('btnImport');
    const originalText = btn.innerText;
    
    btn.innerHTML = `<svg class="animate-spin h-5 w-5 mr-3 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mengekstraksi AI...`;
    btn.disabled = true;

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        
        const result = await response.json();
        
        if (result.status === 'success' || result.status === 'warning') {
            localStorage.setItem('toastMessage', result.message);
            localStorage.setItem('toastType', result.status);
            window.location.reload();
        } else {
            showToast(result.message, 'error');
        }
    } catch (error) {
        showToast('Gagal menghubungi server.', 'error');
    } finally {
        btn.innerText = originalText;
        btn.disabled = false;
    }
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeDeleteModal();
        closeDrawer();
        closeAddModal();
        closeImportModal();
    }
});

// Init
document.addEventListener('DOMContentLoaded', () => {
    populateTable();
    checkPendingToast();
});