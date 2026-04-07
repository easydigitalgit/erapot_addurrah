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

// PERBAIKAN: Menambahkan parameter yang dibutuhkan untuk laci
window.showDetailDrawer = function(tingkat, semester, target, surahMulai, surahSampai, minimal) {
  document.getElementById('drawerTitle').textContent = target;
  document.getElementById('drawerSubtitle').textContent = `Tingkat ${tingkat} - Semester ${semester}`;
  
  document.getElementById('drawerSurahMulai').textContent = surahMulai;
  document.getElementById('drawerSurahSampai').textContent = surahSampai;
  document.getElementById('drawerMinimal').textContent = `${minimal}%`;
  
  const drawer = document.getElementById('detailDrawer');
  const overlay = document.getElementById('drawerOverlay');

  if (overlay) {
      overlay.classList.remove('hidden');
      setTimeout(() => { overlay.classList.remove('opacity-0'); }, 10);
  }
  if (drawer) {
      drawer.classList.remove('hidden');
      setTimeout(() => { drawer.classList.remove('translate-x-full'); }, 10);
  }
  document.body.style.overflow = 'hidden';
}

window.closeDrawer = function() {
  const drawer = document.getElementById('detailDrawer');
  const overlay = document.getElementById('drawerOverlay');
  
  if (drawer) drawer.classList.add('translate-x-full');
  if (overlay) overlay.classList.add('opacity-0');
  
  setTimeout(()=> {
      if (drawer) drawer.classList.add('hidden');
      if (overlay) overlay.classList.add('hidden');
      document.body.style.overflow = '';
  }, 300);
}

function toggleModal(modalId, show) {
  const modal = document.getElementById(modalId);
  if (modal) {
      if (show) {
          modal.classList.remove('hidden');
          document.body.style.overflow = 'hidden';
      } else {
          modal.classList.add('hidden');
          document.body.style.overflow = '';
      }
  }
}

function showAddTargetModal() { toggleModal('addTargetModal', true); }
function closeAddTargetModal() { toggleModal('addTargetModal', false); }

function saveTarget(event) {
  event.preventDefault();
  const form = document.getElementById('formAddTarget');
  const formData = new FormData(form);
  const url = form.getAttribute('action'); 
  const btn = form.querySelector('button[type="submit"]');
  const ori = btn.innerHTML;
  btn.innerHTML = LANG?.js_saving || 'Menyimpan...'; 
  btn.disabled = true;

  fetch(url, { method: 'POST', body: formData, headers: { "X-Requested-With": "XMLHttpRequest" } })
  .then(response => { if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`); return response.json(); })
  .then(data => {
      if (data.status === 'success') {
          showToast(`${LANG?.js_success || 'Berhasil'} ${LANG?.js_data_saved || 'Data tersimpan.'}`, 'success');
          closeAddTargetModal();
          setTimeout(() => location.reload(), 1000); 
      } else {
          let errorMsg = data.message;
          if (data.errors) errorMsg += "\n" + Object.values(data.errors).join("\n");
          showToast(`${LANG?.js_failed || 'Gagal'}: ${errorMsg}`, 'error');
      }
  })
  .catch(error => { showToast(LANG?.js_error_system || 'Terjadi kesalahan sistem.', 'error'); })
  .finally(() => { btn.innerHTML = ori; btn.disabled = false; });
}

function showTemplateModal() { toggleModal('templateModal', true); }
function closeTemplateModal() { toggleModal('templateModal', false); }

function showEditModal(data) {
  document.getElementById('edit_id').value = data.id;
  document.getElementById('edit_juz_id').value = data.juz_id;
  
  fetchSurahByJuz(data.juz_id, '#formEditTarget').then(() => {
       document.getElementById('edit_surah_mulai_id').value = data.surah_mulai_id || '';
       document.getElementById('edit_surah_sampai_id').value = data.surah_sampai_id || '';
       refreshSelect2(document.getElementById('edit_surah_mulai_id'), document.getElementById('edit_surah_sampai_id'));
  });

  const labelInfo = document.getElementById('label_tingkat_semester');
  const textEditing = document.getElementById('text_editing');
  
  if(textEditing) textEditing.textContent = LANG.js_editing_target || 'Currently editing target:';
  
  const semesterStr = data.semester === 'Ganjil' ? (LANG.odd || 'Ganjil') : (LANG.even || 'Genap');
  if(labelInfo) labelInfo.textContent = `${data.tingkat} - ${semesterStr}`;
  
  toggleModal('editTargetModal', true);
}

function updateTarget(event) {
  event.preventDefault();
  const form = document.getElementById('formEditTarget');
  const formData = new FormData(form);
  const url = form.getAttribute('action'); 
  const btn = form.querySelector('button[type="submit"]');
  const ori = btn.innerHTML;
  btn.innerHTML = LANG?.js_updating || 'Update...'; 
  btn.disabled = true;

  fetch(url, { method: 'POST', body: formData, headers: { "X-Requested-With": "XMLHttpRequest" } })
  .then(response => { if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`); return response.json(); })
  .then(data => {
      if (data.status === 'success') {
          showToast(LANG?.js_data_updated || 'Data berhasil diperbarui!', 'success');
          closeEditTargetModal();
          setTimeout(() => location.reload(), 1000);
      } else {
          let errorMsg = data.message || LANG?.js_failed || 'Gagal';
          if (data.errors) errorMsg += "\n" + Object.values(data.errors).join("\n");
          showToast(`${LANG?.js_failed || 'Gagal'}: ${errorMsg}`, 'error');
      }
  })
  .catch(error => { showToast(LANG?.js_error_update || 'Terjadi kesalahan sistem saat update.', 'error'); })
  .finally(() => { btn.innerHTML = ori; btn.disabled = false; });
}

function closeEditTargetModal() { toggleModal('editTargetModal', false); }

function filterTable() {
  const fTingkat = document.getElementById('filter_tingkat').value;
  const fSemester = document.getElementById('filter_semester').value;
  const fStatus = document.getElementById('filter_status').value;
  const fCheckAktif = document.getElementById('check_aktif').checked;

  const rows = document.querySelectorAll('.target-row');
  rows.forEach(row => {
      const rTingkat = row.getAttribute('data-tingkat');
      const rSemester = row.getAttribute('data-semester');
      const rStatus = row.getAttribute('data-status');

      let show = true;
      if (fTingkat && rTingkat !== fTingkat) show = false;
      if (fSemester && rSemester !== fSemester) show = false;
      if (fStatus && rStatus !== fStatus) show = false;
      if (fCheckAktif && rStatus !== 'Aktif') show = false;
      row.style.display = show ? '' : 'none';
  });
}

function toggleActiveCheck() {
  const check = document.getElementById('check_aktif');
  const dropStatus = document.getElementById('filter_status');
  if(check.checked) {
      dropStatus.value = '';
      dropStatus.disabled = true;
  } else {
      dropStatus.disabled = false;
  }
  filterTable();
}

function resetFilter() {
  document.getElementById('filter_tingkat').value = '';
  document.getElementById('filter_semester').value = '';
  document.getElementById('filter_status').value = '';
  document.getElementById('check_aktif').checked = false;
  document.getElementById('filter_status').disabled = false;
  filterTable();
}

function showImportModal() { toggleModal('importModal', true); }
function closeImportModal() { toggleModal('importModal', false); }

function handleImportSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const btn = form.querySelector('button[type="submit"]');
    const oriText = btn.innerHTML;
    btn.innerHTML = LANG?.js_processing || 'Memproses...'; 
    btn.disabled = true;

    fetch(form.action, { method: 'POST', body: new FormData(form) })
    .then(response => response.text())
    .then(rawText => {
        let data;
        try { data = JSON.parse(rawText); } catch (e) { showToast(LANG?.js_error_fatal || 'Error Fatal', 'error'); return; }
        if (data.status === 'success') {
            showToast(data.message, 'success');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(err => showToast(LANG?.js_error_connection || 'Koneksi terputus', 'error'))
    .finally(() => { btn.innerHTML = oriText; btn.disabled = false; });
}

function showRiwayatModal() {
    toggleModal('riwayatModal', true);
    loadRiwayat();
}

function closeRiwayatModal() {
    toggleModal('riwayatModal', false);
}

function loadRiwayat() {
    const container = document.getElementById('listRiwayatContainer');
    container.innerHTML = `<li class="text-center text-sm text-gray-500 dark:text-slate-400 py-10">${LANG?.loading_history || 'Memuat riwayat...'}</li>`;

    fetch(`${BASE_URL}/admin/target-tahfidz/riwayat`, { headers: { "X-Requested-With": "XMLHttpRequest" } })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            if(data.data.length === 0) {
                container.innerHTML = `<li class="text-center text-sm text-gray-500 dark:text-slate-400 py-10">${LANG?.no_history || 'Belum ada riwayat'}</li>`;
                return;
            }
            let html = '';
            data.data.forEach(item => {
                let iconBg = item.status === 'Aktif' 
                    ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' 
                    : 'bg-gray-200 dark:bg-slate-700 text-gray-600 dark:text-slate-400';
                    
                html += `
                <li class="flex gap-4">
                    <div class="relative flex flex-col items-center justify-start">
                        <div class="w-8 h-8 rounded-full ${iconBg} flex items-center justify-center z-10 shrink-0 border dark:border-slate-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="w-0.5 h-full bg-gray-200 dark:bg-slate-700 absolute top-8"></div>
                    </div>
                    <div class="bg-white dark:bg-slate-800 p-4 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm w-full mb-2 transition-colors">
                        <p class="text-xs font-semibold text-gray-500 dark:text-slate-400 mb-1">${item.tanggal}</p>
                        <p class="text-sm font-bold text-gray-800 dark:text-white">${item.aksi}</p>
                        <p class="text-sm text-gray-600 dark:text-slate-300 mt-1">${item.detail}</p>
                    </div>
                </li>`;
            });
            container.innerHTML = html;
        }
    })
    .catch(err => {
        container.innerHTML = `<li class="text-center text-sm text-red-500 dark:text-red-400 py-10">${LANG?.fail_load_history || 'Gagal'}</li>`;
    });
}

function showToast(message, type = 'success') {
  const toast = document.createElement('div');
  toast.className = 'toast fixed top-4 right-4 z-[100000] flex items-center gap-3 px-4 py-3 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-xl shadow-lg transition-all duration-300';
  const icon = type === 'success' 
      ? '<svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>' 
      : '<svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';
  
  toast.innerHTML = `<div class="w-10 h-10 rounded-full flex items-center justify-center ${type === 'success' ? 'bg-emerald-100 dark:bg-emerald-900/30' : 'bg-amber-100 dark:bg-amber-900/30'} flex-shrink-0">${icon}</div><div><p class="font-semibold text-gray-800 dark:text-white text-sm">Notifikasi</p><p class="text-xs text-gray-500 dark:text-slate-400">${message}</p></div>`;
  
  document.body.appendChild(toast);
  
  requestAnimationFrame(() => {
      toast.style.opacity = '1';
      toast.style.transform = 'translateX(0)';
  });

  setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateX(100%)';
      setTimeout(() => toast.remove(), 300);
  }, 3000);
}

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
      window.closeDrawer(); closeAddTargetModal(); closeEditTargetModal(); closeTemplateModal(); closeImportModal(); closeRiwayatModal();
  }
});

document.addEventListener('DOMContentLoaded', function() {
  const selectJuzAdd = document.querySelector('#formAddTarget select[name="juz_id"]');
  if (selectJuzAdd) { selectJuzAdd.addEventListener('change', function() { fetchSurahByJuz(this.value, '#formAddTarget'); }); }
  const selectJuzEdit = document.querySelector('#formEditTarget select[name="juz_id"]');
  if (selectJuzEdit) { selectJuzEdit.addEventListener('change', function() { fetchSurahByJuz(this.value, '#formEditTarget'); }); }
});

function fetchSurahByJuz(juzId, formId) {
  return new Promise((resolve, reject) => {
      const elMulai = document.querySelector(`${formId} select[name="surah_mulai_id"]`);
      const elSampai = document.querySelector(`${formId} select[name="surah_sampai_id"]`);
      
      const txtSelectSurah = LANG?.select_surah || 'Pilih Surah...';
      const txtLoading = LANG?.js_loading_data || 'Memuat...';
      
      if (!juzId) {
          elMulai.innerHTML = `<option value="">${txtSelectSurah}</option>`;
          elSampai.innerHTML = `<option value="">${txtSelectSurah}</option>`;
          refreshSelect2(elMulai, elSampai); resolve(); return;
      }
      
      elMulai.innerHTML = `<option value="">${txtLoading}</option>`; 
      elSampai.innerHTML = `<option value="">${txtLoading}</option>`;
      refreshSelect2(elMulai, elSampai);
      
      fetch(`${BASE_URL}/admin/target-tahfidz/get-surah?juz_id=${juzId}`, { headers: { "X-Requested-With": "XMLHttpRequest" } })
      .then(response => response.json())
      .then(data => {
          if (data.status === 'success') {
              let optionsHTML = `<option value="">${txtSelectSurah}</option>`;
              data.data.forEach(surah => { optionsHTML += `<option value="${surah.id}">${surah.no_surah}. ${surah.nama_surah}</option>`; });
              elMulai.innerHTML = optionsHTML; elSampai.innerHTML = optionsHTML;
              refreshSelect2(elMulai, elSampai); resolve(); 
          } else { 
              reject(LANG?.js_fail_server || 'Gagal'); 
          }
      }).catch(error => { reject(error); });
  });
}

function refreshSelect2(el1, el2) {
  try { if (typeof jQuery !== 'undefined') { $(el1).trigger('change'); $(el2).trigger('change'); } } catch (e) { }
}