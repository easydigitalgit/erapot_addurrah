// SABUK PENGAMAN BAHASA
const textObj = window.LANG || {
  js_loading_matrix: "Memuat matriks akses untuk",
  js_success_load: "Hak akses berhasil dimuat",
  js_err_load: "Gagal memuat data hak akses.",
  js_preset_apply: "Template berhasil diterapkan.",
  js_add_role_title: "Tambah Role Baru",
  js_lbl_role_name: "Nama Role",
  js_ph_role_name: "Contoh: Koordinator Akademik",
  js_lbl_role_desc: "Deskripsi",
  js_ph_role_desc: "Contoh: Monitoring & Koordinasi",
  js_lbl_color: "Pilih Warna Icon",
  js_lbl_status: "Status Awal",
  status_active: "Aktif",
  status_inactive: "Nonaktif",
  js_role_note:
    'Setelah membuat role, Anda dapat mengatur detail hak aksesnya pada panel <strong class="font-bold">Matrix Hak Akses</strong>.',
  btn_cancel: "Batal",
  js_btn_create_role: "Buat Role",
  js_saving_role: "Sedang menyimpan role baru...",
  js_err_net_save: "Terjadi kesalahan jaringan saat menyimpan role.",
  js_check_confirm: "Harap centang konfirmasi terlebih dahulu.",
  js_select_role_1st: "Pilih role terlebih dahulu dari daftar di sebelah kiri.",
  js_saving_changes: "Menyimpan perubahan...",
  js_err_sys_save: "Terjadi kesalahan sistem saat menyimpan data.",
  js_sys_notif: "Notifikasi Sistem",
};

// ==============================================================
// 1. DATABASE MENU UNTUK TIAP ROLE (DYNAMIC MATRIX ENGINE)
// ==============================================================
const ROLE_MATRIX = {
  1: [
    // ID 1: Super Admin
    {
      id: "dashboard",
      label: "Dashboard",
      subs: ["Statistik", "Insight", "Analitik"],
    },
    {
      id: "pengguna",
      label: "Manajemen Pengguna",
      subs: ["Siswa", "Guru", "Orang Tua", "Import"],
    },
    {
      id: "akademik",
      label: "Master Akademik",
      subs: ["Tingkat Rombel", "Mapel", "Wali Kelas", "Mapping"],
    },
    {
      id: "konfigurasi",
      label: "Konfigurasi Akademik",
      subs: ["Tahun Ajaran", "Kurikulum", "Jadwal", "Tahfidz", "Aturan Nilai"],
    },
    {
      id: "penilaian",
      label: "Penilaian",
      subs: ["Input", "Validasi", "Lock", "Monitoring"],
    },
    {
      id: "rapor",
      label: "Rapor & Laporan",
      subs: ["Preview", "Print", "Download", "Leger"],
    },
    { id: "sistem", label: "Sistem", subs: ["Profil", "Hak Akses", "Backup"] },
  ],
  2: [
    // ID 2: Guru Mapel
    { id: "guru_dashboard", label: "Dashboard", subs: [] },
    {
      id: "guru_kelas",
      label: "Kelas Mengajar",
      subs: ["Daftar Kelas", "Daftar Siswa"],
    },
    {
      id: "guru_penilaian",
      label: "Penilaian Akademik",
      subs: ["Nilai Harian", "Nilai Sumatif", "Proyek (P5)"],
    },
    {
      id: "guru_sikap",
      label: "Sikap & Karakter",
      subs: ["Observasi Sikap", "Akhlak Siswa"],
    },
    {
      id: "guru_materi",
      label: "Materi & Soal",
      subs: ["Unggah Materi", "Bank Soal"],
    },
  ],
  3: [
    // ID 3: Wali Kelas (DESAIN DROPDOWN DENGAN TOGGLE DI INDUK)
    {
      id: "wali_dashboard",
      label: "Dashboard",
      subs: ["Ringkasan Kelas", "Siswa Perlu Pembinaan"],
    },
    { id: "wali_kelas", label: "Kelas Perwalian", subs: ["Daftar Siswa"] },
    {
      id: "wali_monitoring",
      label: "Monitoring Nilai",
      subs: ["Progres Nilai Mapel", "Validasi Catatan Guru"],
    },
    {
      id: "wali_karakter",
      label: "Karakter & Pembinaan",
      subs: [
        "Absensi Kelas",
        "Pelanggaran & Prestasi",
        "Catatan Wali Kelas",
        "Progres Tahfidz",
      ],
    },
    { id: "wali_rapor", label: "Rapor", subs: ["Preview Rapor Kelas"] },
  ],
  4: [
    // ID 4: Siswa
    { id: "siswa_dashboard", label: "Dashboard", subs: [] },
    {
      id: "siswa_profil",
      label: "Profil Saya",
      subs: ["Ganti Foto", "Password"],
    },
  ],
  5: [
    // ID 5: Orang Tua
    { id: "ortu_dashboard", label: "Dashboard", subs: [] },
    { id: "ortu_akademik", label: "Akademik", subs: ["Lihat Nilai"] },
    { id: "ortu_tahfidz", label: "Tahfidz", subs: ["Lihat Hafalan"] },
    { id: "ortu_kehadiran", label: "Kehadiran", subs: ["Lihat Absensi"] },
  ],
  7: [
    // ID 7: Guru Tahfidz
    { id: "tahfidz_dashboard", label: "Dashboard", subs: ["Rekap"] },
    { id: "tahfidz_setoran", label: "Setoran", subs: ["Input Hafalan"] },
    { id: "tahfidz_monitoring", label: "Monitoring", subs: ["Lihat Riwayat"] },
    { id: "tahfidz_nilai", label: "Nilai Rapor", subs: ["Input Nilai Akhir"] },
  ],
};

// ==============================================================
// 2. FUNGSI GENERATE MATRIKS DINAMIS KE HTML
// ==============================================================
function renderDynamicMatrix(roleId) {
  const tbody = document.getElementById("matrixBody");
  if (!tbody) return;

  // Jika ID Role tidak terdaftar di ROLE_MATRIX (Misal Role Custom), pakai default Guru (ID 2)
  const modules = ROLE_MATRIX[roleId] || ROLE_MATRIX[2];

  let html = "";
  modules.forEach((mod) => {
    let subsHtml = "";
    let expandIcon = `<div class="w-5 h-5"></div>`; // Kosong jika tidak ada submenu

    if (mod.subs.length > 0) {
      expandIcon = `<svg class="w-5 h-5 text-[var(--warna-primary)] expand-icon transition-transform cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>`;
      let subsList = mod.subs
        .map(
          (s) =>
            `<p class="flex items-center gap-2"><span class="text-[var(--warna-primary)]">•</span> ${s}</p>`,
        )
        .join("");

      subsHtml = `
            <tr id="expand-${mod.id}" class="bg-gray-50/50 dark:bg-slate-900/30 transition-colors hidden">
              <td colspan="5" class="p-0">
                <div class="expandable-section px-6 py-4 pl-14">
                  <div class="text-sm text-gray-600 dark:text-slate-400 space-y-2 font-medium">
                    <p class="font-bold text-gray-800 dark:text-slate-200 mb-2 border-b border-gray-200 dark:border-slate-700 pb-1 inline-block">Sub Menu</p>
                    ${subsList}
                  </div>
                </div>
              </td>
            </tr>`;
    }

    html += `
        <tr class="module-row hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors" data-module="${mod.id}">
          <td class="font-bold text-gray-900 dark:text-white px-6 py-4" onclick="toggleExpand('${mod.id}')">
            <div class="flex items-center gap-3">
              ${expandIcon}
              <span class="module-name hover:text-[var(--warna-primary)] cursor-pointer transition-colors">${mod.label}</span>
            </div>
          </td>
          <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[var(--warna-primary)]" data-action="view" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
          <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[var(--warna-primary)]" data-action="create" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
          <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[var(--warna-primary)]" data-action="update" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
          <td class="text-center px-4 py-4"><span class="toggle-switch w-11 h-6 bg-gray-200 dark:bg-slate-600 rounded-full relative inline-block cursor-pointer transition-colors [&.active]:bg-[var(--warna-primary)]" data-action="delete" onclick="event.stopPropagation(); togglePermission(this)"></span></td>
        </tr>
        ${subsHtml}
        `;
  });

  tbody.innerHTML = html;
}

// ==============================================================
// 3. FUNGSI UTAMA UI
// ==============================================================
let currentSelectedRoleId = null;

function selectRole(card, roleId) {
  document.querySelectorAll(".role-card").forEach((c) => {
    c.classList.remove("active");
    c.style.borderColor = "";
  });

  card.classList.add("active");
  currentSelectedRoleId = roleId;

  const roleName = card.querySelector("h4").textContent;
  showToast(`${textObj.js_loading_matrix} "${roleName}"...`, "info");

  // MENGUBAH TAMPILAN MATRIKS SESUAI ROLE (TRANSFORM)
  renderDynamicMatrix(roleId);

  const timeStamp = new Date().getTime();
  fetch(`${API_GET_PERM}/${roleId}?t=${timeStamp}`, {
    method: "GET",
    headers: { "X-Requested-With": "XMLHttpRequest" },
  })
    .then((response) => {
      if (!response.ok) throw new Error("Network error");
      return response.json();
    })
    .then((res) => {
      if (res.status === "success") {
        res.data.forEach((perm) => {
          // Cari ID modul yang cocok (contoh: 'guru_penilaian')
          const row = document.querySelector(
            `.module-row[data-module="${perm.module_name}"]`,
          );
          if (row) {
            if (perm.can_view == 1)
              row
                .querySelector('[data-action="view"]')
                ?.classList.add("active");
            if (perm.can_create == 1)
              row
                .querySelector('[data-action="create"]')
                ?.classList.add("active");
            if (perm.can_update == 1)
              row
                .querySelector('[data-action="update"]')
                ?.classList.add("active");
            if (perm.can_delete == 1)
              row
                .querySelector('[data-action="delete"]')
                ?.classList.add("active");
          }
        });
        showToast(`${textObj.js_success_load}`, "success");
      }
    })
    .catch((err) => {
      console.error(err);
      showToast(textObj.js_err_load, "error");
    });
}

function togglePermission(toggle) {
  if (!toggle.hasAttribute("disabled")) {
    toggle.classList.toggle("active");
  }
}

function toggleExpand(moduleId) {
  const expandSection = document.getElementById(`expand-${moduleId}`);
  if (!expandSection) return;

  const expandable = expandSection.querySelector(".expandable-section");
  const row = document.querySelector(`.module-row[data-module="${moduleId}"]`);
  const icon = row ? row.querySelector(".expand-icon") : null;

  if (expandSection.classList.contains("hidden")) {
    expandSection.classList.remove("hidden");
    if (expandable) expandable.classList.add("open");
    if (icon) icon.classList.add("rotate-90");
  } else {
    expandSection.classList.add("hidden");
    if (expandable) expandable.classList.remove("open");
    if (icon) icon.classList.remove("rotate-90");
  }
}

// LOGIKA PRESET HARUS LEBIH FLEKSIBEL KARENA ID MODUL SUDAH DINAMIS
function applyPreset(presetType) {
  if (!currentSelectedRoleId) {
    showToast(textObj.js_select_role_1st, "warning");
    return;
  }

  document
    .querySelectorAll(".toggle-switch")
    .forEach((toggle) => toggle.classList.remove("active"));

  if (presetType === "guru") {
    document.querySelectorAll(".module-row").forEach((row) => {
      const mod = row.getAttribute("data-module");
      row.querySelector('[data-action="view"]')?.classList.add("active");
      if (
        mod.includes("penilaian") ||
        mod.includes("materi") ||
        mod.includes("sikap")
      ) {
        row.querySelector('[data-action="create"]')?.classList.add("active");
        row.querySelector('[data-action="update"]')?.classList.add("active");
      }
    });
  } else if (presetType === "walikelas") {
    // PRESET WALI KELAS BERDASARKAN INDUK
    const fullCrudModules = ["wali_karakter", "wali_monitoring"];

    document.querySelectorAll(".module-row").forEach((row) => {
      const mod = row.getAttribute("data-module");

      // View untuk semua
      row.querySelector('[data-action="view"]')?.classList.add("active");

      // Create, Update, Delete untuk operasional Karakter & Monitoring
      if (fullCrudModules.includes(mod)) {
        row.querySelector('[data-action="create"]')?.classList.add("active");
        row.querySelector('[data-action="update"]')?.classList.add("active");
        row.querySelector('[data-action="delete"]')?.classList.add("active");
      }
    });
  } else if (presetType === "admin" || presetType === "kepsek") {
    document.querySelectorAll(".module-row").forEach((row) => {
      row.querySelector('[data-action="view"]')?.classList.add("active");
      if (presetType === "admin") {
        row.querySelector('[data-action="create"]')?.classList.add("active");
        row.querySelector('[data-action="update"]')?.classList.add("active");
        row.querySelector('[data-action="delete"]')?.classList.add("active");
      }
    });
  }

  showToast(
    "Template diterapkan di layar. Jangan lupa klik Simpan Perubahan.",
    "info",
  );
}

// (Fungsi Modal dan Create Role tidak diubah dan biarkan seperti ini)
function addRole() {
  showAddRoleModal();
}
function showAddRoleModal() {
  /* KODE SEBELUMNYA BISA DIGUNAKAN - KARENA PANJANG SAYA POTONG DI SINI, SILAKAN COPY KODE MODAL CREATE ROLE LU YANG LAMA KESINI */
}
function closeAddRoleModal() {
  const modal = document.getElementById("addRoleModal");
  if (modal) {
    modal.remove();
    document.body.style.overflow = "";
  }
}
function selectColor(button, color) {
  document.querySelectorAll(".color-option").forEach((opt) => {
    opt.classList.add("border-transparent");
  });
  button.classList.remove("border-transparent");
  button.style.borderColor = "white";
  document.getElementById("roleColor").value = color;
}
function createNewRole(name, desc, status) {
  /* KODE LAMA */
}

function showSaveModal() {
  const modal = document.getElementById("saveModal");
  if (modal) {
    modal.classList.remove("hidden");
    document.body.style.overflow = "hidden";
  }
}

function closeModal() {
  const modal = document.getElementById("saveModal");
  if (modal) modal.classList.add("hidden");
  const cb = document.getElementById("confirmCheck");
  if (cb) cb.checked = false;
  document.body.style.overflow = "";
}

function confirmSave() {
  const checkbox = document.getElementById("confirmCheck");
  if (!checkbox.checked) {
    showToast(textObj.js_check_confirm, "warning");
    return;
  }
  if (!currentSelectedRoleId) {
    showToast(textObj.js_select_role_1st, "error");
    return;
  }

  closeModal();
  showToast(textObj.js_saving_changes, "info");

  let permissions = {};
  document.querySelectorAll(".module-row").forEach((row) => {
    const moduleName = row.getAttribute("data-module");
    if (moduleName) {
      permissions[moduleName] = {
        view:
          row
            .querySelector('[data-action="view"]')
            ?.classList.contains("active") || false,
        create:
          row
            .querySelector('[data-action="create"]')
            ?.classList.contains("active") || false,
        update:
          row
            .querySelector('[data-action="update"]')
            ?.classList.contains("active") || false,
        delete:
          row
            .querySelector('[data-action="delete"]')
            ?.classList.contains("active") || false,
      };
    }
  });

  const formData = new FormData();
  formData.append("role_id", currentSelectedRoleId);
  formData.append("permissions", JSON.stringify(permissions));

  if (typeof CSRF_NAME !== "undefined" && typeof CSRF_TOKEN !== "undefined") {
    formData.append(CSRF_NAME, CSRF_TOKEN);
  }

  fetch(API_SAVE_PERM, {
    method: "POST",
    body: formData,
    headers: { "X-Requested-With": "XMLHttpRequest" },
  })
    .then((response) => response.json())
    .then((res) => {
      if (res.status === "success") {
        showToast(res.message, "success");
        setTimeout(() => {
          window.location.href =
            window.location.pathname + "?v=" + new Date().getTime();
        }, 1200);
      } else {
        showToast(res.message, "error");
      }
    })
    .catch((err) => {
      console.error(err);
      showToast(textObj.js_err_sys_save, "error");
    });
}

function showToast(message, type = "success") {
  const toast = document.createElement("div");
  const colors = {
    success: {
      border: "border-emerald-500",
      bg: "bg-emerald-100 dark:bg-emerald-900/30",
      text: "text-emerald-600 dark:text-emerald-400",
      path: "M5 13l4 4L19 7",
    },
    error: {
      border: "border-red-500",
      bg: "bg-red-100 dark:bg-red-900/30",
      text: "text-red-600 dark:text-red-400",
      path: "M6 18L18 6M6 6l12 12",
    },
    info: {
      border: "border-blue-500",
      bg: "bg-blue-100 dark:bg-blue-900/30",
      text: "text-blue-600 dark:text-blue-400",
      path: "M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z",
    },
    warning: {
      border: "border-amber-500",
      bg: "bg-amber-100 dark:bg-amber-900/30",
      text: "text-amber-600 dark:text-amber-400",
      path: "M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z",
    },
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
  requestAnimationFrame(() =>
    toast.classList.remove("translate-x-full", "opacity-0"),
  );
  setTimeout(() => {
    toast.classList.add("translate-x-full", "opacity-0");
    setTimeout(() => toast.remove(), 300);
  }, 4000);
}

function openMobileSidebar() {
  const sb = document.getElementById("sidebar");
  const ov = document.getElementById("sidebar-overlay");
  if (sb) sb.classList.add("mobile-open");
  if (ov) ov.classList.add("active");
  document.body.style.overflow = "hidden";
}
function closeMobileSidebar() {
  const sb = document.getElementById("sidebar");
  const ov = document.getElementById("sidebar-overlay");
  if (sb) sb.classList.remove("mobile-open");
  if (ov) ov.classList.remove("active");
  document.body.style.overflow = "";
}

document.addEventListener("DOMContentLoaded", () => {
  const firstRole = document.querySelector(".role-card");
  if (firstRole) {
    setTimeout(() => {
      firstRole.click();
    }, 150);
  }
});

document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") {
    closeModal();
    const addModal = document.getElementById("addRoleModal");
    if (addModal) closeAddRoleModal();
  }
});

// HAPUS BARIS const API_GET_ALL_AUDIT YANG ADA TAG PHP-NYA!

function openFullAuditModal() {
  const URL_AUDIT =
    typeof API_GET_ALL_AUDIT !== "undefined"
      ? API_GET_ALL_AUDIT
      : "/admin/hak-akses/getAllAuditLogs";

  const modal = document.getElementById("auditModal");
  const content = modal.querySelector(".modal-content");
  const body = document.getElementById("auditModalBody");

  modal.classList.remove("hidden");
  document.body.style.overflow = "hidden";
  setTimeout(() => {
    content.classList.remove("scale-95");
    content.classList.add("scale-100");
  }, 10);

  body.innerHTML = `<div class="flex justify-center items-center py-20"><svg class="w-10 h-10 animate-spin text-[var(--warna-primary)]" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>`;

  fetch(URL_AUDIT, { headers: { "X-Requested-With": "XMLHttpRequest" } })
    .then((response) => {
      if (!response.ok) throw new Error("Gagal fetch API");
      return response.json();
    })
    .then((res) => {
      if (res.status === "success") {
        if (res.data.length === 0) {
          body.innerHTML = `<div class="text-center py-10 text-gray-500 font-medium">Belum ada riwayat audit.</div>`;
          return;
        }

        // HTML bersih dari class dark: agar di-override oleh CSS
        let tableHtml = `
              <table class="audit-table w-full text-left border-collapse">
                  <thead class="bg-gray-50 border-b border-gray-100 text-xs font-black text-gray-500 uppercase tracking-widest">
                      <tr>
                          <th class="px-6 py-4">Waktu</th>
                          <th class="px-6 py-4">Aksi</th>
                          <th class="px-6 py-4">Detail Perubahan</th>
                          <th class="px-6 py-4">Diubah Oleh</th>
                      </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-100">`;

        res.data.forEach((log) => {
          const dateObj = new Date(log.created_at);
          const dateStr = dateObj.toLocaleDateString("id-ID", {
            day: "2-digit",
            month: "short",
            year: "numeric",
          });
          const timeStr = dateObj.toLocaleTimeString("id-ID", {
            hour: "2-digit",
            minute: "2-digit",
          });

          // NAMA AKAN MUNCUL DI SINI!
          const userStr = log.username ? log.username : "Sistem / Unknown";
          const badgeColor =
            log.action === "UPDATE_PERMISSION" ? "emerald" : "amber";
          const actionName =
            log.action === "UPDATE_PERMISSION"
              ? "Update Permission"
              : log.action;

          tableHtml += `
                  <tr class="hover:bg-gray-50 transition-colors">
                      <td class="px-6 py-4 font-bold text-gray-900">
                          ${dateStr}<br><span class="text-[11px] font-medium text-gray-500 tracking-wider">${timeStr} WIB</span>
                      </td>
                      <td class="px-6 py-4">
                          <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-${badgeColor}-100 text-${badgeColor}-700 border border-${badgeColor}-200 shadow-sm">${actionName}</span>
                      </td>
                      <td class="px-6 py-4 text-sm font-medium text-gray-700 leading-relaxed">${log.description}</td>
                      <td class="px-6 py-4 font-bold text-gray-900">
                          ${userStr}<br><span class="text-[11px] font-medium font-mono text-gray-500 tracking-wider">IP: ${log.ip_address}</span>
                      </td>
                  </tr>`;
        });

        tableHtml += `</tbody></table>`;
        body.innerHTML = tableHtml;
      } else {
        body.innerHTML = `<div class="text-center py-10 text-red-500 font-bold">Gagal memuat data.</div>`;
      }
    })
    .catch((err) => {
      console.error("AJAX Error: ", err);
      body.innerHTML = `<div class="text-center py-10 text-red-500 font-bold">Terjadi kesalahan jaringan atau API tidak ditemukan.</div>`;
    });
}

function closeFullAuditModal() {
  const modal = document.getElementById("auditModal");
  const content = modal.querySelector(".modal-content");
  content.classList.remove("scale-100");
  content.classList.add("scale-95");
  setTimeout(() => {
    modal.classList.add("hidden");
    document.body.style.overflow = "";
  }, 200);
}
