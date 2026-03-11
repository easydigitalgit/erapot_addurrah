// ==========================================
// DATA STATE 
// ==========================================
let students = [];
let currentFilter = "all";
let currentStudent = null;

let currentPage = 1;
const itemsPerPage = 10; 
let filteredStudentsCache = []; 

// ==========================================
// FETCH DATA DARI CONTROLLER
// ==========================================
function fetchStudentsData() {
  const tbody = document.getElementById("studentTableBody");
  if(!tbody) return;

  tbody.innerHTML = `<tr><td colspan="8" class="text-center py-10 font-bold text-gray-500 dark:!text-slate-400 bg-white dark:!bg-slate-800 transition-colors">${LANG.loading}</td></tr>`;

  if (typeof ACTIVE_ROMBEL_ID === 'undefined' || ACTIVE_ROMBEL_ID === 0) {
      tbody.innerHTML = `<tr><td colspan="8" class="text-center py-10 text-red-500 dark:!text-red-400 font-bold bg-white dark:!bg-slate-800 transition-colors">${LANG.err_no_class}</td></tr>`;
      return;
  }

  fetch(`${URL_GET_DATA}?rombel_id=${ACTIVE_ROMBEL_ID}&mapel_id=${ACTIVE_MAPEL_ID}`, {
    method: 'GET',
    headers: { "X-Requested-With": "XMLHttpRequest" }
  })
  .then(res => {
      if (!res.ok) throw new Error("Terjadi kesalahan rute (404/500)");
      return res.json();
  })
  .then(res => {
    if (res.status === 'success') {
      students = res.data;
      applyFiltersAndRender(); 
    } else {
        tbody.innerHTML = `<tr><td colspan="8" class="text-center py-10 text-red-500 dark:!text-red-400 font-bold bg-white dark:!bg-slate-800 transition-colors">${LANG.err_load}</td></tr>`;
    }
  })
  .catch(err => {
      console.error("Fetch error:", err);
      tbody.innerHTML = `<tr><td colspan="8" class="text-center py-10 text-red-500 dark:!text-red-400 font-bold bg-white dark:!bg-slate-800 transition-colors">${LANG.err_server}</td></tr>`;
  });
}

// Helpers
function getAvatarColor(index) {
  const colors = ["linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%)", "linear-gradient(135deg, #EC4899 0%, #DB2777 100%)", "linear-gradient(135deg, #F59E0B 0%, #D97706 100%)", "linear-gradient(135deg, #10B981 0%, #059669 100%)"];
  return colors[index % colors.length];
}

function getInitials(name) {
  if (!name) return "S";
  const parts = name.trim().split(" ");
  return parts.length >= 2 ? (parts[0][0] + parts[1][0]).toUpperCase() : parts[0].substring(0, 2).toUpperCase();
}

function calculateAverage(student) {
  const harian = parseInt(student.harian) || 0;
  const uts = parseInt(student.uts) || 0;
  const uas = parseInt(student.uas) || 0;
  const proyek = parseInt(student.proyek) || 0;
  
  const values = [harian, uts, uas, proyek].filter(v => v > 0);
  if (values.length === 0) return 0;
  return (values.reduce((a, b) => a + b, 0) / values.length).toFixed(1);
}

function getProgress(student) {
  const harian = parseInt(student.harian) || 0;
  const uts = parseInt(student.uts) || 0;
  const uas = parseInt(student.uas) || 0;
  const proyek = parseInt(student.proyek) || 0;
  
  const total = [harian, uts, uas, proyek].filter(v => v > 0).length;
  return (total / 4) * 100;
}

// ==========================================
// RENDER TABEL & PAGINATION
// ==========================================
function applyFiltersAndRender() {
    const searchTerm = document.getElementById("searchInput") ? document.getElementById("searchInput").value.toLowerCase() : "";

    filteredStudentsCache = students.filter((s) => {
        const matchesFilter = currentFilter === "all" || s.status === currentFilter;
        const matchesSearch = (s.name && s.name.toLowerCase().includes(searchTerm)) || (s.nis && s.nis.toString().includes(searchTerm));
        return matchesFilter && matchesSearch;
    });

    currentPage = 1;
    renderCurrentPage();
}

function renderCurrentPage() {
    const tbody = document.getElementById("studentTableBody");
    const emptyState = document.getElementById("emptyState");
    const tableContainer = document.querySelector(".student-table") ? document.querySelector(".student-table").parentElement.parentElement : null;
    const paginationContainer = document.getElementById("paginationContainer");

    if (!tbody) return;
    tbody.innerHTML = "";

    if (filteredStudentsCache.length === 0) {
        if (emptyState) {
            emptyState.classList.remove("hidden");
            emptyState.classList.add("flex");
        }
        if (tableContainer) tableContainer.style.display = "none";
        if (paginationContainer) {
            paginationContainer.classList.add("hidden");
            paginationContainer.classList.remove("flex");
        }
        return;
    }

    if (emptyState) {
        emptyState.classList.add("hidden");
        emptyState.classList.remove("flex");
    }
    if (tableContainer) tableContainer.style.display = "block";
    
    if (paginationContainer) {
        paginationContainer.classList.remove("hidden");
        paginationContainer.classList.add("flex");
    }

    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const pageData = filteredStudentsCache.slice(startIndex, endIndex);

    pageData.forEach((student, index) => {
        const actualIndex = startIndex + index + 1; 
        const avg = student.rata_rata || 0; // Ambil nilai rata_rata dari controller
        const progress = getProgress(student);
        const initials = getInitials(student.name);

        let statusBadge = student.status === "lengkap" ? `<span class="px-2.5 py-1 text-[10px] uppercase tracking-wider font-black rounded-lg bg-emerald-100 dark:!bg-emerald-900/30 text-emerald-700 dark:!text-emerald-400 border border-emerald-200 dark:!border-emerald-800/50 shadow-sm transition-colors">${LANG.status_complete}</span>` 
                        : student.status === "proses" ? `<span class="px-2.5 py-1 text-[10px] uppercase tracking-wider font-black rounded-lg bg-amber-100 dark:!bg-amber-900/30 text-amber-700 dark:!text-amber-400 border border-amber-200 dark:!border-amber-800/50 shadow-sm transition-colors">${LANG.status_progress}</span>`
                        : `<span class="px-2.5 py-1 text-[10px] uppercase tracking-wider font-black rounded-lg bg-gray-100 dark:!bg-slate-700 text-gray-600 dark:!text-slate-400 border border-gray-200 dark:!border-slate-600 shadow-sm transition-colors">${LANG.status_unscored}</span>`;
        
        let progressClass = student.status === "lengkap" ? "bg-emerald-500 shadow-[0_0_5px_#10b981]" : student.status === "proses" ? "bg-amber-500 shadow-[0_0_5px_#f59e0b]" : "bg-gray-300 dark:!bg-slate-500";
        
        // Warna text average berdasar KKM (misal >= 75 hijau, < 75 merah)
        let avgColor = avg >= 75 ? "text-emerald-600 dark:!text-emerald-400" : "text-rose-600 dark:!text-rose-400";
        const avgDisplay = avg > 0 ? `<span class="text-base font-black transition-colors ${avgColor}">${avg}</span>` : '<span class="text-sm font-bold text-gray-400 dark:!text-slate-500 transition-colors">-</span>';
        
        const catatanDisplay = student.catatan && student.catatan !== "-" ? `<span class="text-xs font-medium text-gray-700 dark:!text-slate-300 transition-colors">${student.catatan.substring(0, 40)}...</span>` : `<span class="text-xs italic text-gray-400 dark:!text-slate-500 transition-colors">${LANG.no_notes}</span>`;

        const row = document.createElement("tr");
        row.className = "bg-white dark:!bg-slate-800 hover:bg-gray-50/50 dark:hover:!bg-slate-700/30 transition-colors group cursor-pointer border-b border-gray-100 dark:!border-slate-700/50 last:border-0";
        row.innerHTML = `
              <td class="font-bold text-gray-500 dark:!text-slate-400 text-center py-4 px-6 transition-colors">${actualIndex}</td>
              <td class="text-center py-4 px-6">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-black mx-auto shadow-sm group-hover:scale-110 transition-transform duration-300" style="background: ${getAvatarColor(actualIndex)};">${initials}</div>
              </td>
              <td class="font-mono text-xs text-gray-500 dark:!text-slate-400 font-bold py-4 px-6 transition-colors">${student.nis || '-'}</td>
              <td class="font-bold text-gray-900 dark:!text-white py-4 px-6 truncate max-w-[200px] transition-colors group-hover:text-[var(--warna-primary)]">${student.name}</td>
              <td class="py-4 px-6">
                <div class="mb-2 flex items-center">${statusBadge}</div>
                <div class="w-full h-1.5 bg-gray-200 dark:!bg-slate-700 rounded-full overflow-hidden transition-colors">
                  <div class="h-full ${progressClass} transition-all duration-500" style="width: ${progress}%"></div>
                </div>
              </td>
              <td class="text-center py-4 px-6">${avgDisplay}</td>
              <td class="py-4 px-6 truncate max-w-[200px]">${catatanDisplay}</td>
              <td class="py-4 px-6">
                <div class="flex items-center justify-center gap-2 opacity-100 lg:opacity-0 group-hover:opacity-100 transition-opacity">
                  <button class="p-2.5 bg-gray-50 dark:!bg-slate-700 border border-gray-200 dark:!border-slate-600 rounded-xl hover:bg-emerald-50 dark:hover:!bg-emerald-900/30 hover:border-emerald-200 dark:hover:!border-emerald-800 hover:text-emerald-600 dark:hover:!text-emerald-400 text-gray-600 dark:!text-slate-400 transition-all shadow-sm outline-none transform hover:scale-105" onclick="openInputModal(${student.id})" title="${LANG.btn_input}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </button>
                  <button class="p-2.5 bg-gray-50 dark:!bg-slate-700 border border-gray-200 dark:!border-slate-600 rounded-xl hover:bg-blue-50 dark:hover:!bg-blue-900/30 hover:border-blue-200 dark:hover:!border-blue-800 hover:text-blue-600 dark:hover:!text-blue-400 text-gray-600 dark:!text-slate-400 transition-all shadow-sm outline-none transform hover:scale-105" onclick="openObservasiModal(${student.id})" title="${LANG.btn_obs}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  </button>
                  <button class="p-2.5 bg-gray-50 dark:!bg-slate-700 border border-gray-200 dark:!border-slate-600 rounded-xl hover:bg-[var(--warna-primary)] hover:border-[var(--warna-primary)] hover:text-white text-gray-600 dark:!text-slate-400 transition-all shadow-sm outline-none transform hover:scale-105" onclick="openDetailModal(${student.id})" title="${LANG.btn_detail}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  </button>
                </div>
              </td>
            `;
        tbody.appendChild(row);
    });

    updatePaginationUI();
}

function updatePaginationUI() {
    const totalItems = filteredStudentsCache.length;
    const totalPages = Math.ceil(totalItems / itemsPerPage);

    if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;

    const startItem = (currentPage - 1) * itemsPerPage + 1;
    const endItem = Math.min(currentPage * itemsPerPage, totalItems);

    document.getElementById("pageStart").textContent = totalItems > 0 ? startItem : 0;
    document.getElementById("pageEnd").textContent = endItem;
    document.getElementById("pageTotal").textContent = totalItems;

    const btnPrev = document.getElementById("btnPrevPage");
    const btnNext = document.getElementById("btnNextPage");

    btnPrev.disabled = currentPage === 1;
    btnNext.disabled = currentPage === totalPages || totalPages === 0;

    renderPageNumbers(totalPages);
}

function renderPageNumbers(totalPages) {
    const container = document.getElementById("pageNumbers");
    container.innerHTML = "";
    
    for (let i = 1; i <= totalPages; i++) {
        const btn = document.createElement("button");
        btn.textContent = i;
        
        if (i === currentPage) {
            btn.className = "w-10 h-10 flex items-center justify-center rounded-xl transition-all text-sm font-bold outline-none bg-[var(--warna-scroll)] text-white shadow-md border-transparent";
        } else {
            btn.className = "w-10 h-10 flex items-center justify-center rounded-xl border border-gray-300 dark:!border-slate-600 bg-white dark:!bg-slate-800 text-gray-600 dark:!text-slate-300 hover:bg-gray-50 dark:hover:!bg-slate-700 transition-colors text-sm font-bold outline-none shadow-sm";
        }

        btn.onclick = () => {
            currentPage = i;
            renderCurrentPage();
        };
        container.appendChild(btn);
    }
}

function changePage(delta) {
    const totalPages = Math.ceil(filteredStudentsCache.length / itemsPerPage);
    const newPage = currentPage + delta;
    if (newPage >= 1 && newPage <= totalPages) {
        currentPage = newPage;
        renderCurrentPage();
    }
}

// Menghapus styling statis (hardcoded js background) agar Tailwind class berjalan
function filterByStatus(button, status) {
  document.querySelectorAll(".filter-button").forEach((btn) => {
    btn.classList.remove("active", "[&.active]:bg-[var(--warna-scroll)]", "[&.active]:text-white");
  });
  
  button.classList.add("active", "[&.active]:bg-[var(--warna-scroll)]", "[&.active]:text-white");
  
  currentFilter = status;
  applyFiltersAndRender();
}

function filterStudents() { 
    applyFiltersAndRender(); 
}

// ==========================================
// 3. MODAL EDIT NILAI (AKADEMIK)
// ==========================================
function openInputModal(studentId) {
  currentStudent = students.find((s) => s.id == studentId);
  if (!currentStudent) return;

  document.getElementById("modalStudentName").textContent = currentStudent.name + " - " + (currentStudent.nis || '-');
  document.getElementById("inputHarian").value = currentStudent.harian > 0 ? currentStudent.harian : "";
  document.getElementById("inputUTS").value = currentStudent.uts > 0 ? currentStudent.uts : "";
  document.getElementById("inputUAS").value = currentStudent.uas > 0 ? currentStudent.uas : "";
  document.getElementById("inputProyek").value = currentStudent.proyek > 0 ? currentStudent.proyek : "";

  const modal = document.getElementById("modalInputNilai");
  modal.classList.remove("hidden");
  modal.classList.add("flex");
  document.body.style.overflow = "hidden";
}

function closeInputModal() {
  const modal = document.getElementById("modalInputNilai");
  if(modal) {
      modal.classList.add("hidden");
      modal.classList.remove("flex");
  }
  document.body.style.overflow = "";
  currentStudent = null;
}

function saveNilai() {
  if (!currentStudent) return;

  const harian = parseInt(document.getElementById("inputHarian").value) || 0;
  const uts = parseInt(document.getElementById("inputUTS").value) || 0;
  const uas = parseInt(document.getElementById("inputUAS").value) || 0;
  const proyek = parseInt(document.getElementById("inputProyek").value) || 0;

  if (harian < 0 || harian > 100 || uts < 0 || uts > 100 || uas < 0 || uas > 100 || proyek < 0 || proyek > 100) {
      showToast(LANG.err_range, "error");
      return; 
  }

  const btn = event.target.closest('button');
  const originalText = btn.innerHTML;
  btn.innerHTML = `<svg class="animate-spin w-4 h-4 text-white inline-block mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ${LANG.saving}`;
  btn.disabled = true;

  const formData = new FormData();
  formData.append('siswa_id', currentStudent.id);
  formData.append('mapel_id', ACTIVE_MAPEL_ID);
  formData.append('rombel_id', ACTIVE_ROMBEL_ID);
  formData.append('harian', harian);
  formData.append('uts', uts);
  formData.append('uas', uas);
  formData.append('proyek', proyek);
  formData.append('catatan', currentStudent.catatan && currentStudent.catatan !== "-" ? currentStudent.catatan : "");
  formData.append('aspek_kedisiplinan', currentStudent.aspek_kedisiplinan || 0);
  formData.append('aspek_tanggung_jawab', currentStudent.aspek_tanggung_jawab || 0);
  formData.append('aspek_kerjasama', currentStudent.aspek_kerjasama || 0);
  formData.append('aspek_kejujuran', currentStudent.aspek_kejujuran || 0);
  formData.append(csrfTokenName, csrfTokenHash);

  fetch(URL_SAVE_DATA, {
      method: 'POST',
      body: formData,
      headers: { 
          "X-Requested-With": "XMLHttpRequest",
          [csrfTokenName]: csrfTokenHash 
      }
  })
  .then(res => {
      if(!res.ok) throw new Error("Gagal menyimpan (Cek log server)");
      return res.json();
  })
  .then(data => {
      if(data.status === 'success') {
          showToast(LANG.succ_grade);
          closeInputModal();
          fetchStudentsData(); 
      } else {
          showToast(LANG.err_save, "error");
      }
  })
  .catch(err => {
      console.error(err);
      showToast(LANG.err_save, "error");
  })
  .finally(() => {
      btn.innerHTML = originalText;
      btn.disabled = false;
  });
}

// ==========================================
// 4. MODAL OBSERVASI SIKAP & KARAKTER
// ==========================================
function openObservasiModal(studentId) {
  currentStudent = students.find((s) => s.id == studentId);
  if (!currentStudent) return;

  document.getElementById("modalObservasiName").textContent = currentStudent.name + " - " + (currentStudent.nis || '-');
  document.getElementById("inputObservasi").value = currentStudent.catatan && currentStudent.catatan !== "-" ? currentStudent.catatan : "";
  document.getElementById("cekKedisiplinan").checked = currentStudent.aspek_kedisiplinan == 1;
  document.getElementById("cekTanggungJawab").checked = currentStudent.aspek_tanggung_jawab == 1;
  document.getElementById("cekKerjasama").checked = currentStudent.aspek_kerjasama == 1;
  document.getElementById("cekKejujuran").checked = currentStudent.aspek_kejujuran == 1;

  const modal = document.getElementById("modalObservasi");
  modal.classList.remove("hidden");
  modal.classList.add("flex");
  document.body.style.overflow = "hidden";
}

function closeObservasiModal() {
  const modal = document.getElementById("modalObservasi");
  if(modal) {
      modal.classList.add("hidden");
      modal.classList.remove("flex");
  }
  document.body.style.overflow = "";
  currentStudent = null;
}

function saveObservasi() {
  if (!currentStudent) return;
  const btn = event.target.closest('button');
  const originalText = btn.innerHTML;
  btn.innerHTML = `<svg class="animate-spin w-4 h-4 text-white inline-block mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ${LANG.saving}`;
  btn.disabled = true;

  const formData = new FormData();
  formData.append('siswa_id', currentStudent.id);
  formData.append('mapel_id', ACTIVE_MAPEL_ID);
  formData.append('rombel_id', ACTIVE_ROMBEL_ID);
  formData.append('harian', currentStudent.harian || 0);
  formData.append('uts', currentStudent.uts || 0);
  formData.append('uas', currentStudent.uas || 0);
  formData.append('proyek', currentStudent.proyek || 0);
  formData.append('catatan', document.getElementById("inputObservasi").value.trim());
  formData.append('aspek_kedisiplinan', document.getElementById("cekKedisiplinan").checked ? 1 : 0);
  formData.append('aspek_tanggung_jawab', document.getElementById("cekTanggungJawab").checked ? 1 : 0);
  formData.append('aspek_kerjasama', document.getElementById("cekKerjasama").checked ? 1 : 0);
  formData.append('aspek_kejujuran', document.getElementById("cekKejujuran").checked ? 1 : 0);
  formData.append(csrfTokenName, csrfTokenHash);

  fetch(URL_SAVE_DATA, {
      method: 'POST',
      body: formData,
      headers: { 
          "X-Requested-With": "XMLHttpRequest",
          [csrfTokenName]: csrfTokenHash 
      }
  })
  .then(res => {
      if(!res.ok) throw new Error("Gagal menyimpan (Cek log server)");
      return res.json();
  })
  .then(data => {
      if(data.status === 'success') {
          showToast(LANG.succ_obs);
          closeObservasiModal();
          fetchStudentsData(); 
      } else {
          showToast(LANG.err_save, "error");
      }
  })
  .catch(err => {
      console.error(err);
      showToast(LANG.err_save, "error");
  })
  .finally(() => {
      btn.innerHTML = originalText;
      btn.disabled = false;
  });
}

// ==========================================
// 5. DETAIL MODAL
// ==========================================
function openDetailModal(studentId) {
  const student = students.find((s) => s.id == studentId);
  if (!student) return;

  const avg = student.rata_rata || 0;
  const predikat = avg >= 90 ? "A" : avg >= 80 ? "B" : avg >= 70 ? "C" : "D";
  const initials = getInitials(student.name);
  
  const rIndex = filteredStudentsCache.findIndex(s => s.id == studentId);
  const avatarColor = getAvatarColor(rIndex >= 0 ? rIndex + 1 : 0); 

  document.getElementById("detailAvatar").textContent = initials;
  document.getElementById("detailAvatar").style.background = avatarColor;
  document.getElementById("detailName").textContent = student.name;
  document.getElementById("detailNIS").textContent = "NIS: " + (student.nis || '-');
  
  document.getElementById("detailHarian").textContent = parseInt(student.harian) > 0 ? student.harian : "-";
  document.getElementById("detailUTS").textContent = parseInt(student.uts) > 0 ? student.uts : "-";
  document.getElementById("detailUAS").textContent = parseInt(student.uas) > 0 ? student.uas : "-";
  document.getElementById("detailProyek").textContent = parseInt(student.proyek) > 0 ? student.proyek : "-";
  
  document.getElementById("detailRataRata").textContent = avg > 0 ? avg : "-";
  document.getElementById("detailPredikat").textContent = avg > 0 ? predikat : "-";
  document.getElementById("detailCatatan").textContent = student.catatan && student.catatan !== "-" ? student.catatan : LANG.no_notes;

  const modal = document.getElementById("modalDetail");
  modal.classList.remove("hidden");
  modal.classList.add("flex");
  document.body.style.overflow = "hidden";
}

function closeDetailModal() {
  const modal = document.getElementById("modalDetail");
  if(modal) {
      modal.classList.add("hidden");
      modal.classList.remove("flex");
  }
  document.body.style.overflow = "";
}

function openMassInputModal() { showToast(LANG.err_mass, "error"); }

// ==========================================
// TOAST NOTIFICATION (DYNAMIC COLOR)
// ==========================================
function showToast(message, type = 'success') {
  const toast = document.getElementById("toast");
  const toastMessage = document.getElementById("toastMessage");
  
  if(!toast || !toastMessage) return;

  const svgIcon = toast.querySelector("svg");

  if (type === 'error') {
      toast.style.backgroundColor = "#EF4444"; 
      toast.style.color = "white";
      toast.style.borderLeft = "none";
      if(svgIcon) {
          svgIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />`;
      }
  } else {
      const primaryColor = typeof THEME_PRIMARY_COLOR !== 'undefined' ? THEME_PRIMARY_COLOR : getComputedStyle(document.documentElement).getPropertyValue('--warna-scroll').trim();
      
      toast.style.backgroundColor = primaryColor;
      toast.style.color = "white";
      toast.style.borderLeft = "none";
      if(svgIcon) {
          svgIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />`;
      }
  }

  toastMessage.textContent = message;
  
  toast.classList.remove("translate-x-full", "opacity-0");
  
  setTimeout(() => { 
      toast.classList.add("translate-x-full", "opacity-0"); 
  }, 3000);
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') { closeInputModal(); closeObservasiModal(); closeDetailModal(); }
});

document.addEventListener("DOMContentLoaded", () => {
    fetchStudentsData(); 
    
    // Pastikan filter pertama 'all' aktif menggunakan Tailwind Class
    const firstButton = document.querySelector(".filter-button");
    if(firstButton) {
        firstButton.classList.add("active", "[&.active]:bg-[var(--warna-scroll)]", "[&.active]:text-white");
    }
});