document.addEventListener("DOMContentLoaded", () => {
  window.waliKelasData = typeof dbWaliKelas !== "undefined" ? dbWaliKelas : [];
  window.filteredData = [...window.waliKelasData];

  populateTable();

  document
    .getElementById("searchInput")
    ?.addEventListener("input", (e) => applyFilters());
  document
    .getElementById("filterLevel")
    ?.addEventListener("change", () => applyFilters());
});

function applyFilters() {
  const search = document.getElementById("searchInput").value.toLowerCase();
  const level = document.getElementById("filterLevel").value;

  window.filteredData = window.waliKelasData.filter((item) => {
    const matchSearch =
      item.teacher.toLowerCase().includes(search) ||
      item.full_rombel.toLowerCase().includes(search);
    const matchLevel = level === "" || item.level === level;
    return matchSearch && matchLevel;
  });
  populateTable();
}

function populateTable() {
  const tbody = document.getElementById("waliKelasTableBody");
  if (!tbody) return;

  if (window.filteredData.length === 0) {
    tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-16 text-center text-gray-500 dark:text-slate-400 font-medium">${LANG.js_no_data}</td></tr>`;
    return;
  }

  tbody.innerHTML = window.filteredData
    .map((item) => {
      let bgClass = "bg-gradient-to-br from-emerald-500 to-emerald-700";
      if (item.level === "VIII")
        bgClass = "bg-gradient-to-br from-blue-500 to-blue-700";
      if (item.level === "IX")
        bgClass = "bg-gradient-to-br from-purple-500 to-purple-700";

      return `
        <tr class="table-row hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors border-b border-gray-50 dark:border-slate-700/50 group">
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg ${bgClass} text-white flex items-center justify-center font-bold text-sm shadow-md border border-transparent">
                        ${item.level}
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 font-bold text-gray-800 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">${item.full_rombel}</td>
            <td class="px-6 py-4">
                ${
                  item.status === "assigned"
                    ? `<span class="font-bold text-gray-800 dark:text-white">${item.teacher}</span>`
                    : `<span class="text-gray-400 dark:text-slate-500 italic font-medium">${LANG.js_unassigned}</span>`
                }
            </td>
            <td class="px-6 py-4 text-gray-600 dark:text-slate-400 font-mono text-sm">${item.nip || "-"}</td>
            <td class="px-6 py-4 text-center">
                ${
                  item.status === "assigned"
                    ? `<span class="inline-flex px-2.5 py-1 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 rounded-full text-[11px] font-bold uppercase tracking-wider shadow-sm">${LANG.js_assigned}</span>`
                    : `<span class="inline-flex px-2.5 py-1 bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 border border-gray-200 dark:border-slate-600 rounded-full text-[11px] font-bold uppercase tracking-wider shadow-sm">${LANG.js_unassigned}</span>`
                }
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center justify-center gap-2">
                    ${
                      item.status === "assigned"
                        ? `
                        <button onclick="showDetail(${item.id})" class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg tooltip transition-colors outline-none" title="${LANG.js_detail}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                        <button onclick="showChangeTeacherModal(${item.id})" class="p-2 text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-900/30 rounded-lg tooltip transition-colors outline-none" title="${LANG.js_change}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </button>
                    `
                        : `
                        <button onclick="showAssignModal(${item.id}, '${item.full_rombel}')" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition-transform transform hover:-translate-y-0.5 shadow-md shadow-emerald-600/30 outline-none">
                            ${LANG.js_assign}
                        </button>
                    `
                    }
                </div>
            </td>
        </tr>
    `;
    })
    .join("");
}

window.showAssignModal = (id, rombelName) => {
  document.getElementById("assign_rombel_id").value = id;
  document.getElementById("assignTargetName").textContent = rombelName;
  document.getElementById("assignModal").classList.remove("hidden");
};
window.closeAssignModal = () =>
  document.getElementById("assignModal").classList.add("hidden");

window.showChangeTeacherModal = (id) => {
  const data = window.waliKelasData.find((d) => d.id == id);
  if (!data) return;

  document.getElementById("change_rombel_id").value = id;
  document.getElementById("currentTeacherName").textContent = data.teacher;
  document.getElementById("changeTeacherModal").classList.remove("hidden");
};
window.closeChangeTeacherModal = () =>
  document.getElementById("changeTeacherModal").classList.add("hidden");

window.showDetail = (id) => {
  const data = window.waliKelasData.find((d) => d.id == id);
  if (!data) return;

  document.getElementById("drawerTeacherName").textContent = data.teacher;
  document.getElementById("drawerTeacherNIP").textContent =
    "NIP: " + (data.nip || "-");
  document.getElementById("drawerInitial").textContent = data.teacher
    .substring(0, 2)
    .toUpperCase();

  document.getElementById("drawerChangeBtn").onclick = () =>
    showChangeTeacherModal(id);
  window.currentDetailId = id;

  document.getElementById("detailDrawer").classList.remove("hidden");
  setTimeout(() => {
    document
      .getElementById("detailDrawer")
      .classList.remove("translate-x-full");
    document.getElementById("drawer-overlay").classList.remove("hidden");
  }, 10);
};

window.closeDrawer = () => {
  document.getElementById("detailDrawer").classList.add("translate-x-full");
  document.getElementById("drawer-overlay").classList.add("hidden");
  setTimeout(
    () => document.getElementById("detailDrawer").classList.add("hidden"),
    300,
  );
};

window.handleAssignSubmit = async (e) => {
  e.preventDefault();
  await submitForm(
    e.target,
    `${BASE_URL}/admin/wali-kelas/update`,
    closeAssignModal,
  );
};

window.handleChangeTeacherSubmit = async (e) => {
  e.preventDefault();
  await submitForm(
    e.target,
    `${BASE_URL}/admin/wali-kelas/update`,
    closeChangeTeacherModal,
  );
};

window.confirmRemoveAssignment = async () => {
  document.getElementById("removeModal").classList.remove("hidden");
};

window.closeRemoveModal = () => {
  document.getElementById("removeModal").classList.add("hidden");
};

window.handleRemoveSubmit = async () => {
  const btn = document.getElementById("btnConfirmRemove");
  const originalText = btn.innerText;
  btn.innerText = LANG.js_processing;
  btn.disabled = true;

  const rombelId = window.currentDetailId;
  const formData = new FormData();
  formData.append("rombel_id", rombelId);

  try {
    const res = await fetch(`${BASE_URL}/admin/wali-kelas/delete`, {
      method: "POST",
      body: formData,
      headers: { "X-Requested-With": "XMLHttpRequest" },
    });

    const json = await res.json();

    if (json.status === "success") {
      showToast("success", LANG.js_success, json.message);
      closeRemoveModal();
      closeDrawer();
      setTimeout(() => window.location.reload(), 500);
    } else {
      showToast("error", LANG.js_failed, json.message);
    }
  } catch (err) {
    showToast("error", LANG.js_error, LANG.js_fail_connect);
  } finally {
    btn.innerText = originalText;
    btn.disabled = false;
  }
};

document.addEventListener("keydown", (e) => {
  if (e.key === "Escape") {
    closeRemoveModal();
    closeStatusModal();
    closeUnassignedListModal();
  }
});

async function submitForm(form, url, closeCallback) {
  const btn = form.querySelector('button[type="submit"]');
  const oriText = btn.innerText;
  btn.innerText = LANG.js_saving;
  btn.disabled = true;

  try {
    const res = await fetch(url, {
      method: "POST",
      body: new FormData(form),
      headers: { "X-Requested-With": "XMLHttpRequest" },
    });

    const json = await res.json();

    if (json.status === "success") {
      showToast("success", LANG.js_success, json.message);
      closeCallback();
      setTimeout(() => window.location.reload(), 500);
    } else {
      showToast("error", LANG.js_failed, json.message);
    }
  } catch (err) {
    console.error(err);
    showToast("error", LANG.js_error, LANG.js_fail_connect);
  } finally {
    btn.innerText = oriText;
    btn.disabled = false;
  }
}

window.showToast = (type, title, msg) => {
  document.getElementById("statusIconSuccess").classList.add("hidden");
  document.getElementById("statusIconError").classList.add("hidden");
  document.getElementById("statusProgressBar").style.width = "100%";
  document.getElementById("statusProgressBar").style.transition = "none";

  document.getElementById("statusTitle").textContent = title;
  document.getElementById("statusMessage").textContent = msg;

  const progressBar = document.getElementById("statusProgressBar");

  if (type === "success") {
    document.getElementById("statusIconSuccess").classList.remove("hidden");
    progressBar.className = "h-full bg-emerald-500 w-full";
  } else {
    document.getElementById("statusIconError").classList.remove("hidden");
    progressBar.className = "h-full bg-red-500 w-full";
  }

  document.getElementById("statusModal").classList.remove("hidden");

  requestAnimationFrame(() => {
    progressBar.style.transition = "width 2000ms linear";
    progressBar.style.width = "0%";
  });

  if (window.statusTimer) clearTimeout(window.statusTimer);
  window.statusTimer = setTimeout(() => {
    closeStatusModal();
  }, 2000);
};

window.closeStatusModal = () => {
  document.getElementById("statusModal").classList.add("hidden");
};

window.showUnassignedListModal = () => {
  const container = document.getElementById("unassignedListContainer");
  const unassignedData = window.waliKelasData.filter(
    (item) => item.status === "unassigned",
  );

  if (unassignedData.length === 0) {
    container.innerHTML = `
            <div class="text-center py-8">
                <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-full flex items-center justify-center mx-auto mb-3 border border-emerald-200 dark:border-emerald-800/50">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <p class="text-gray-800 dark:text-white font-bold text-lg">${LANG.js_all_safe}</p>
                <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mt-1">${LANG.js_all_safe_desc}</p>
            </div>
        `;
  } else {
    container.innerHTML = unassignedData
      .map(
        (item) => `
            <div class="flex items-center justify-between p-3.5 hover:bg-gray-50 dark:hover:bg-slate-700/50 rounded-xl transition-colors border-b last:border-0 border-gray-100 dark:border-slate-700 group">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gray-200 dark:bg-slate-700 text-gray-600 dark:text-slate-300 flex items-center justify-center font-black text-xs border border-transparent dark:border-slate-600 transition-colors">
                        ${item.level}
                    </div>
                    <div>
                        <p class="font-bold text-gray-800 dark:text-white text-sm group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">${item.full_rombel}</p>
                        <p class="text-xs text-red-500 dark:text-red-400 font-bold mt-0.5">${LANG.js_unassigned}</p>
                    </div>
                </div>
                <button onclick="openQuickAssign(${item.id}, '${item.full_rombel}')" class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold uppercase tracking-wider rounded-lg shadow-sm transition-transform transform hover:-translate-y-0.5 flex items-center gap-1.5 outline-none">
                    ${LANG.js_assign}
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        `,
      )
      .join("");
  }

  document.getElementById("unassignedListModal").classList.remove("hidden");
};

window.closeUnassignedListModal = () => {
  document.getElementById("unassignedListModal").classList.add("hidden");
};

window.openQuickAssign = (id, name) => {
  closeUnassignedListModal();
  setTimeout(() => {
    showAssignModal(id, name);
  }, 200);
};
