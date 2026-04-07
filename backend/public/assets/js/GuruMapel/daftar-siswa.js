// ==========================================
// DATA STATE
// ==========================================
let students = [];
let currentPage = 1;
const itemsPerPage = 10;
let filteredStudentsCache = [];

document.addEventListener("DOMContentLoaded", () => {
  fetchStudentsData();
});

// ==========================================
// FETCH DATA DARI CONTROLLER
// ==========================================
function fetchStudentsData() {
  const tbody = document.getElementById("studentTableBody");
  if (!tbody) return;

  tbody.innerHTML = `<tr><td colspan="5" class="text-center py-10 font-bold text-gray-500 dark:!text-slate-400 bg-white dark:!bg-slate-800 transition-colors">Memuat data siswa...</td></tr>`;

  if (typeof ACTIVE_ROMBEL_ID === "undefined" || ACTIVE_ROMBEL_ID === 0) {
    tbody.innerHTML = `<tr><td colspan="5" class="text-center py-10 text-red-500 dark:!text-red-400 font-bold bg-white dark:!bg-slate-800 transition-colors">Guru belum ditugaskan di kelas manapun.</td></tr>`;
    return;
  }

  fetch(
    `${URL_GET_DATA}?rombel_id=${ACTIVE_ROMBEL_ID}&mapel_id=${ACTIVE_MAPEL_ID}`,
    {
      method: "GET",
      headers: { "X-Requested-With": "XMLHttpRequest" },
    },
  )
    .then((res) => {
      if (!res.ok) throw new Error("Terjadi kesalahan rute (404/500)");
      return res.json();
    })
    .then((res) => {
      if (res.status === "success") {
        students = res.data;
        applyFiltersAndRender();
      } else {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-10 text-red-500 dark:!text-red-400 font-bold bg-white dark:!bg-slate-800 transition-colors">Gagal memuat data</td></tr>`;
      }
    })
    .catch((err) => {
      console.error("Fetch error:", err);
      tbody.innerHTML = `<tr><td colspan="5" class="text-center py-10 text-red-500 dark:!text-red-400 font-bold bg-white dark:!bg-slate-800 transition-colors">Gagal terhubung ke Server. Pastikan kolom di database sesuai.</td></tr>`;
    });
}

// Helpers Avatar
function getAvatarColor(index) {
  const colors = [
    "linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%)",
    "linear-gradient(135deg, #EC4899 0%, #DB2777 100%)",
    "linear-gradient(135deg, #F59E0B 0%, #D97706 100%)",
    "linear-gradient(135deg, #10B981 0%, #059669 100%)",
  ];
  return colors[index % colors.length];
}

function getInitials(name) {
  if (!name) return "S";
  const parts = name.trim().split(" ");
  return parts.length >= 2
    ? (parts[0][0] + parts[1][0]).toUpperCase()
    : parts[0].substring(0, 2).toUpperCase();
}

// ==========================================
// RENDER TABEL & PAGINATION
// ==========================================
function filterStudents() {
  applyFiltersAndRender();
}

function applyFiltersAndRender() {
  const searchInput = document.getElementById("searchInput");
  const searchTerm = searchInput ? searchInput.value.toLowerCase() : "";

  filteredStudentsCache = students.filter((s) => {
    return (
      (s.name && s.name.toLowerCase().includes(searchTerm)) ||
      (s.nis && s.nis.toString().includes(searchTerm))
    );
  });

  currentPage = 1;
  renderCurrentPage();
}

function renderCurrentPage() {
  const tbody = document.getElementById("studentTableBody");
  const emptyState = document.getElementById("emptyState");
  const tableContainer = document.querySelector(".student-table")
    ? document.querySelector(".student-table").parentElement.parentElement
    : null;
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

  const cacheBuster = "?v=" + new Date().getTime(); // Tambahan agar foto selalu up-to-date

  pageData.forEach((student, index) => {
    const actualIndex = startIndex + index + 1;
    const initials = getInitials(student.name);

    let avatarHTML = "";
    let finalFoto = student.foto_final; // Menggunakan variabel hybrid dari PHP

    if (finalFoto && finalFoto.trim() !== "" && finalFoto !== "null") {
      const cacheBuster = "?v=" + new Date().getTime();

      // URL Sumber 1: Folder Avatars (Data Baru)
      const urlAvatars = `${BASE_URL}assets/uploads/avatars/${finalFoto}${cacheBuster}`;
      // URL Sumber 2: Folder Siswa (Data Lama)
      const urlSiswa = `${BASE_URL}uploads/siswa/${finalFoto}${cacheBuster}`;

      const fallbackHTML = `<div class=\\'w-10 h-10 rounded-xl flex items-center justify-center text-white font-black mx-auto shadow-sm group-hover:scale-110 transition-transform duration-300\\' style=\\'background: ${getAvatarColor(actualIndex)};\\'>${initials}</div>`;

      // Logika OnError Ganda: Coba load urlAvatars -> Gagal? Coba load urlSiswa -> Gagal? Tampilkan Inisial
      avatarHTML = `<img src="${urlAvatars}" alt="${student.name}" class="w-10 h-10 rounded-xl object-cover mx-auto shadow-sm group-hover:scale-110 transition-transform duration-300" onerror="this.onerror=function(){ this.outerHTML='${fallbackHTML}'; }; this.src='${urlSiswa}';">`;
    } else {
      avatarHTML = `<div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-black mx-auto shadow-sm group-hover:scale-110 transition-transform duration-300" style="background: ${getAvatarColor(actualIndex)};">${initials}</div>`;
    }

    let jkBadge = "";
    if (student.jk_kode === "L") {
      jkBadge = `<span class="inline-flex px-2.5 py-1 text-[10px] uppercase tracking-wider font-black rounded-lg bg-blue-50 text-blue-600 dark:!bg-blue-900/30 dark:!text-blue-400">${student.jk_text}</span>`;
    } else if (student.jk_kode === "P") {
      jkBadge = `<span class="inline-flex px-2.5 py-1 text-[10px] uppercase tracking-wider font-black rounded-lg bg-pink-50 text-pink-600 dark:!bg-pink-900/30 dark:!text-pink-400">${student.jk_text}</span>`;
    } else {
      jkBadge = `<span class="inline-flex px-2.5 py-1 text-[10px] uppercase tracking-wider font-black rounded-lg bg-gray-50 text-gray-600 dark:!bg-slate-700 dark:!text-slate-400">-</span>`;
    }

    const row = document.createElement("tr");
    row.className =
      "bg-white dark:!bg-slate-800 transition-colors group border-b border-gray-100 dark:!border-slate-700/50 last:border-0";
    row.innerHTML = `
              <td class="font-bold text-gray-500 dark:!text-slate-400 text-center py-4 px-6 transition-colors">${actualIndex}</td>
              <td class="text-center py-4 px-6">${avatarHTML}</td>
              <td class="py-4 px-6 transition-colors">
                  <div class="font-mono text-xs text-gray-800 dark:!text-white font-black tracking-wider">${student.nis || "-"}</div>
                  <div class="text-[10px] text-gray-400 dark:!text-slate-500 font-bold uppercase tracking-widest mt-0.5">NISN: ${student.nisn || "-"}</div>
              </td>
              <td class="font-bold text-gray-900 dark:!text-white py-4 px-6 transition-colors group-hover:text-[var(--warna-primary)]">${student.name}</td>
              <td class="text-center py-4 px-6">${jkBadge}</td>
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

  document.getElementById("pageStart").textContent =
    totalItems > 0 ? startItem : 0;
  document.getElementById("pageEnd").textContent = endItem;
  document.getElementById("pageTotal").textContent = totalItems;

  const btnPrev = document.getElementById("btnPrevPage");
  const btnNext = document.getElementById("btnNextPage");

  if (btnPrev) btnPrev.disabled = currentPage === 1;
  if (btnNext)
    btnNext.disabled = currentPage === totalPages || totalPages === 0;

  renderPageNumbers(totalPages);
}

function renderPageNumbers(totalPages) {
  const container = document.getElementById("pageNumbers");
  if (!container) return;
  container.innerHTML = "";

  for (let i = 1; i <= totalPages; i++) {
    const btn = document.createElement("button");
    btn.textContent = i;

    if (i === currentPage) {
      btn.className =
        "w-10 h-10 flex items-center justify-center rounded-xl transition-all text-sm font-bold outline-none bg-[var(--warna-scroll)] text-white shadow-md border-transparent";
    } else {
      btn.className =
        "w-10 h-10 flex items-center justify-center rounded-xl border border-gray-300 dark:!border-slate-600 bg-white dark:!bg-slate-800 text-gray-600 dark:!text-slate-300 hover:bg-gray-50 dark:hover:!bg-slate-700 transition-colors text-sm font-bold outline-none shadow-sm";
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
