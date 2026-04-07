document.addEventListener("DOMContentLoaded", function () {
  // =========================================================================
  // 1. INISIALISASI VARIABEL & ELEMENT
  // =========================================================================
  const baseUrlElement = document.getElementById("base-url");
  const baseUrl = baseUrlElement
    ? baseUrlElement.getAttribute("data-url")
    : "/";

  const searchInput = document.getElementById("search-input");
  const filterRoleSelect = document.getElementById("filter-role");
  const filterStatusSelect = document.getElementById("filter-status");
  const selectAllCheckbox = document.getElementById("selectAll");
  const bulkActions = document.getElementById("bulkActions");
  const selectedCount = document.getElementById("selectedCount");

  const modalForm = document.getElementById("user-modal");
  const backdropForm = document.getElementById("modal-backdrop");
  const panelForm = document.getElementById("modal-panel");
  const formUser = document.getElementById("user-form");

  const inputId = document.getElementById("user-id");
  const inputUsername = document.getElementById("user-username");
  const inputEmail = document.getElementById("user-email");
  const inputFullname = document.getElementById("user-fullname");
  const inputRole = document.getElementById("user-role");
  const inputPassword = document.getElementById("user-password");
  const inputConfirm = document.getElementById("user-confirm-password");
  const passwordHint = document.getElementById("password-hint");

  const usernameOptions = document.getElementById("username-options");
  const usernameHint = document.getElementById("username-hint");
  const linkedIdInput = document.getElementById("linked-id");
  const linkedTypeInput = document.getElementById("linked-type");

  const modalTitle = document.getElementById("user-modal-title");
  const btnSave = document.getElementById("btn-save-user");

  const detailDrawer = document.getElementById("detailDrawer");
  const drawerOverlay = document.getElementById("drawer-overlay");

  const modalDelete = document.getElementById("delete-modal");
  const deleteForm = document.getElementById("delete-user-form");
  const deleteInput = document.getElementById("delete-user-id");

  const modalActivate = document.getElementById("activate-modal");
  const activateForm = document.getElementById("activate-form");
  const activateInput = document.getElementById("activate-id");

  const modalDeactivate = document.getElementById("confirm-modal");
  const deactivateForm = document.getElementById("deactivate-form");
  const deactivateInput = document.getElementById("deactivate-id");

  const rawGuru = document.getElementById("json-data-guru");
  const rawSiswa = document.getElementById("json-data-siswa");
  const rawOrangTua = document.getElementById("json-data-orangtua");
  const rawTahfidz = document.getElementById("json-data-tahfidz");

  const dataGuru = rawGuru ? JSON.parse(rawGuru.textContent) : [];
  const dataSiswa = rawSiswa ? JSON.parse(rawSiswa.textContent) : [];
  const dataOrangTua = rawOrangTua ? JSON.parse(rawOrangTua.textContent) : [];
  const dataTahfidz = rawTahfidz ? JSON.parse(rawTahfidz.textContent) : [];

  let deleteIdTarget = null;
  let activateIdTarget = null;
  let deactivateIdTarget = null;

  // =========================================================================
  // FITUR ROLE & USERNAME PINTAR
  // =========================================================================
  if (inputRole) {
    inputRole.addEventListener("change", function () {
      if (usernameOptions) usernameOptions.innerHTML = "";
      if (inputUsername && inputId.value === "") inputUsername.value = "";
      if (linkedIdInput) linkedIdInput.value = "";
      if (linkedTypeInput) linkedTypeInput.value = "";
      if (usernameHint) usernameHint.classList.add("hidden");

      const selectedOption = this.options[this.selectedIndex];
      if (!selectedOption || !selectedOption.value) return;

      const roleName = selectedOption.getAttribute("data-name");
      const roleKey = roleName
        ? roleName.toLowerCase().replace(/\s+/g, "")
        : "";

      if (
        roleKey === "guru" ||
        roleKey === "tendik" ||
        roleKey === "walikelas" ||
        roleKey === "superadmin"
      ) {
        if (usernameHint && inputId.value === "")
          usernameHint.classList.remove("hidden");
        if (linkedTypeInput) linkedTypeInput.value = "guru";
        if (inputUsername) inputUsername.placeholder = LANG.js_select_teacher;

        dataGuru.forEach((guru) => {
          const opt = document.createElement("option");
          opt.value = guru.nuptk
            ? guru.nuptk
            : guru.nama.replace(/\s+/g, "").toLowerCase();
          opt.textContent = `${guru.nama} (NUPTK: ${guru.nuptk || "-"})`;
          opt.setAttribute("data-id", guru.id);
          if (usernameOptions) usernameOptions.appendChild(opt);
        });
      } else if (roleKey === "siswa") {
        if (usernameHint && inputId.value === "")
          usernameHint.classList.remove("hidden");
        if (linkedTypeInput) linkedTypeInput.value = "siswa";
        if (inputUsername) inputUsername.placeholder = LANG.js_select_student;

        dataSiswa.forEach((siswa) => {
          const opt = document.createElement("option");
          // UBAH BARIS INI: Hilangkan titik pada value yang akan masuk ke kotak Username
          opt.value = siswa.nis ? siswa.nis.replace(/\./g, "") : "";

          // Teks di daftar pencarian tetap pakai titik agar rapi dibaca Admin
          opt.textContent = `${siswa.nama} (NIS: ${siswa.nis})`;
          opt.setAttribute("data-id", siswa.id);
          if (usernameOptions) usernameOptions.appendChild(opt);
        });
      } else if (roleKey === "orangtua" || roleKey === "wali") {
        if (usernameHint && inputId.value === "")
          usernameHint.classList.remove("hidden");
        if (linkedTypeInput) linkedTypeInput.value = "orangtua";
        if (inputUsername) inputUsername.placeholder = LANG.js_select_parent;

        dataOrangTua.forEach((ortu) => {
          const opt = document.createElement("option");
          opt.value =
            ortu.nama.replace(/\s+/g, "").toLowerCase() +
            Math.floor(Math.random() * 100);
          opt.textContent = ortu.nama;
          opt.setAttribute("data-id", ortu.id);
          if (usernameOptions) usernameOptions.appendChild(opt);
        });
      } else if (roleKey === "gurutahfidzh" || roleKey === "gurutahfidz") {
        if (usernameHint && inputId.value === "")
          usernameHint.classList.remove("hidden");
        if (linkedTypeInput) linkedTypeInput.value = "tahfidz";
        if (inputUsername) inputUsername.placeholder = LANG.js_select_tahfidz;

        dataTahfidz.forEach((tahfidz) => {
          const opt = document.createElement("option");
          opt.value = tahfidz.nuptk
            ? tahfidz.nuptk
            : tahfidz.nama.replace(/\s+/g, "").toLowerCase();
          opt.textContent = `${tahfidz.nama} (NUPTK: ${tahfidz.nuptk || "-"})`;
          opt.setAttribute("data-id", tahfidz.id);
          if (usernameOptions) usernameOptions.appendChild(opt);
        });
      } else {
        if (inputUsername) inputUsername.placeholder = LANG.js_type_manual;
      }
    });
  }

  if (inputUsername) {
    inputUsername.addEventListener("input", function () {
      if (inputId.value !== "") return;

      const typedValue = this.value;
      let matchFound = false;
      let selectedData = null;

      const roleName =
        inputRole.options[inputRole.selectedIndex].getAttribute("data-name");
      const roleKey = roleName
        ? roleName.toLowerCase().replace(/\s+/g, "")
        : "";

      let currentSource = [];
      if (
        roleKey === "guru" ||
        roleKey === "tendik" ||
        roleKey === "walikelas" ||
        roleKey === "superadmin"
      )
        currentSource = dataGuru;
      else if (roleKey === "siswa") currentSource = dataSiswa;
      else if (roleKey === "orangtua" || roleKey === "wali")
        currentSource = dataOrangTua;
      else if (roleKey === "gurutahfidzh" || roleKey === "gurutahfidz")
        currentSource = dataTahfidz;

      // UBAH BARIS INI: Hilangkan titik pada kedua sisi agar sistem bisa mencocokkan data dengan benar
      selectedData = currentSource.find((item) => {
        let val =
          item.nuptk || item.nis || item.nama.replace(/\s+/g, "").toLowerCase();
        if (val) val = val.replace(/\./g, ""); // Bersihkan dari titik
        return val == typedValue.replace(/\./g, ""); // Bersihkan ketikan user dari titik
      });

      if (selectedData) {
        if (linkedIdInput) linkedIdInput.value = selectedData.id;
        if (inputFullname) inputFullname.value = selectedData.nama || "";

        // ==========================================
        // FITUR BARU: AUTO-GENERATE EMAIL INSTITUSI
        // ==========================================
        if (inputEmail) {
          if (selectedData.email && selectedData.email.trim() !== "") {
            inputEmail.value = selectedData.email;
          } else {
            // UBAH BARIS INI: Pastikan email menggunakan username yang tanpa titik
            const cleanUsername = typedValue.replace(/\./g, "");
            const generatedEmail = `${cleanUsername}@${typeof SCHOOL_DOMAIN !== "undefined" ? SCHOOL_DOMAIN : "sekolah.sch.id"}`;
            inputEmail.value = generatedEmail.toLowerCase();
          }
        }

        matchFound = true;
      }

      if (!matchFound) {
        if (linkedIdInput) linkedIdInput.value = "";
      }
    });
  }

  // =========================================================================
  // 2. LOGIKA SUBMIT FORM (TAMBAH/EDIT)
  // =========================================================================
  if (formUser) {
    formUser.addEventListener("submit", async function (e) {
      e.preventDefault();

      if (
        inputPassword.value !== "" &&
        inputPassword.value !== inputConfirm.value
      ) {
        Swal.fire(LANG.js_failed, LANG.js_pass_mismatch, "error");
        return;
      }

      const isEdit = inputId.value !== "";

      if (!isEdit) {
        const selectedOption = inputRole.options[inputRole.selectedIndex];
        const roleName = selectedOption
          ? selectedOption.getAttribute("data-name")
          : "";
        const roleKey = roleName
          ? roleName.toLowerCase().replace(/\s+/g, "")
          : "";

        const requiredRoles = [
          "guru",
          "tendik",
          "walikelas",
          "siswa",
          "orangtua",
          "wali",
          "gurutahfidzh",
          "gurutahfidz",
        ];

        if (requiredRoles.includes(roleKey)) {
          if (!linkedIdInput.value || linkedIdInput.value.trim() === "") {
            Swal.fire({
              icon: "error",
              title: LANG.js_action_denied,
              text: LANG.js_must_select_data,
            });
            return;
          }
        }
      }

      const btnText = btnSave.innerHTML;
      btnSave.disabled = true;
      btnSave.innerHTML = `<i class="fas fa-spinner animate-spin mr-2"></i> ${LANG.js_processing}`;

      const targetUrl = isEdit
        ? baseUrl + "admin/users/update"
        : baseUrl + "admin/users/store";

      try {
        const formData = new FormData(this);
        const response = await fetch(targetUrl, {
          method: "POST",
          body: formData,
          headers: { "X-Requested-With": "XMLHttpRequest" },
        });

        const res = await response.json();

        if (res.status === "success") {
          Swal.fire({
            icon: "success",
            title: LANG.js_success,
            text: res.message,
            showConfirmButton: false,
            timer: 1500,
          }).then(() => {
            window.location.reload();
          });
        } else {
          Swal.fire({
            icon: "error",
            title: LANG.js_failed,
            text: res.message,
          });
        }
      } catch (err) {
        console.error(err);
        Swal.fire("Error", LANG.js_server_error, "error");
      } finally {
        btnSave.disabled = false;
        btnSave.innerHTML = btnText;
      }
    });
  }

  // =========================================================================
  // 3. LOGIKA FILTER
  // =========================================================================
  function applyFilters() {
    const params = new URLSearchParams(window.location.search);
    const searchValue = searchInput ? searchInput.value : "";
    const roleValue = filterRoleSelect ? filterRoleSelect.value : "";
    const statusValue = filterStatusSelect ? filterStatusSelect.value : "";

    if (searchValue) params.set("search", searchValue);
    else params.delete("search");
    if (roleValue) params.set("role", roleValue);
    else params.delete("role");
    if (statusValue) params.set("status", statusValue);
    else params.delete("status");
    params.delete("page_users");

    window.location.href = window.location.pathname + "?" + params.toString();
  }

  if (filterRoleSelect)
    filterRoleSelect.addEventListener("change", applyFilters);
  if (filterStatusSelect)
    filterStatusSelect.addEventListener("change", applyFilters);
  if (searchInput) {
    searchInput.addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        e.preventDefault();
        applyFilters();
      }
    });
  }

  // =========================================================================
  // 4. CHECKBOX & BULK ACTION
  // =========================================================================
  if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener("change", function () {
      const isChecked = this.checked;
      document.querySelectorAll(".user-checkbox").forEach((cb) => {
        cb.checked = isChecked;
        toggleRowHighlight(cb);
      });
      updateBulkUI();
    });
  }

  document.addEventListener("change", function (e) {
    if (e.target.classList.contains("user-checkbox")) {
      toggleRowHighlight(e.target);
      updateSelectAllState();
      updateBulkUI();
    }
  });

  function toggleRowHighlight(checkbox) {
    const tr = checkbox.closest("tr");
    if (tr) {
      if (checkbox.checked) {
        tr.classList.add("bg-blue-50");
        tr.classList.remove("hover:bg-slate-50");
      } else {
        tr.classList.remove("bg-blue-50");
        tr.classList.add("hover:bg-slate-50");
      }
    }
  }

  function updateSelectAllState() {
    if (!selectAllCheckbox) return;
    const all = document.querySelectorAll(".user-checkbox");
    const checked = document.querySelectorAll(".user-checkbox:checked");
    if (all.length > 0) {
      selectAllCheckbox.checked = all.length === checked.length;
      selectAllCheckbox.indeterminate =
        checked.length > 0 && checked.length < all.length;
    } else {
      selectAllCheckbox.checked = false;
      selectAllCheckbox.indeterminate = false;
    }
  }

  function updateBulkUI() {
    const count = document.querySelectorAll(".user-checkbox:checked").length;
    if (selectedCount) selectedCount.innerText = `(${count})`;

    if (bulkActions) {
      if (count > 0) {
        bulkActions.classList.remove("hidden");
        bulkActions.classList.add("flex");
      } else {
        bulkActions.classList.add("hidden");
        bulkActions.classList.remove("flex");
      }
    }
  }

  // =========================================================================
  // 5. MODAL LOGIC (OPEN/CLOSE)
  // =========================================================================
  window.showAddUserModal = function () {
    if (!formUser) return;
    formUser.reset();
    clearAllErrors();

    if (inputId) inputId.value = "";

    if (passwordHint) passwordHint.classList.add("hidden");

    if (inputPassword) {
      inputPassword.type = "password";
      inputPassword.required = true;
      inputPassword.value = "password"; // <-- Set nilai default
    }
    if (inputConfirm) {
      inputConfirm.type = "password";
      inputConfirm.required = true;
      inputConfirm.value = "password"; // <-- Set nilai default konfirmasi
    }

    // PENTING: Pancing event 'input' agar indikator kekuatan password (warna warni) ikut jalan
    if (inputPassword) {
      inputPassword.dispatchEvent(new Event("input"));
    }

    const sendCreds = document.querySelector('input[name="send_credentials"]');
    if (sendCreds) sendCreds.checked = true;

    if (modalTitle) modalTitle.innerText = LANG.modal_add_title;
    if (btnSave) btnSave.innerText = LANG.btn_save_account;

    if (linkedIdInput) linkedIdInput.value = "";
    if (linkedTypeInput) linkedTypeInput.value = "";
    if (usernameOptions) usernameOptions.innerHTML = "";

    if (inputUsername) {
      inputUsername.removeAttribute("readonly");
      inputUsername.value = "";
    }

    if (inputFullname) inputFullname.value = "";

    if (usernameHint) usernameHint.classList.add("hidden");
    const usernameLockedHint = document.getElementById("username-locked-hint");
    if (usernameLockedHint) usernameLockedHint.classList.add("hidden");

    openModal(modalForm, backdropForm, panelForm);
  };

  window.openEditModal = function (userData) {
    if (!formUser) return;
    clearAllErrors();
    formUser.reset();

    if (modalTitle) modalTitle.innerText = LANG.modal_edit_title;
    if (btnSave) btnSave.innerText = LANG.btn_save_changes;

    if (inputId) inputId.value = userData.id;

    if (inputUsername) {
      inputUsername.value = userData.username || "";
      inputUsername.setAttribute("readonly", "true");
    }

    if (inputFullname)
      inputFullname.value = userData.full_name || userData.nama_lengkap || "";

    if (usernameHint) usernameHint.classList.add("hidden");
    const usernameLockedHint = document.getElementById("username-locked-hint");
    if (usernameLockedHint) usernameLockedHint.classList.remove("hidden");

    if (inputEmail) inputEmail.value = userData.email || "";

    if (inputRole) {
      inputRole.value =
        userData.role_id && userData.role_id != 0 ? userData.role_id : "";
    }

    if (inputPassword) {
      inputPassword.value = "";
      inputPassword.required = false;
    }
    if (inputConfirm) {
      inputConfirm.value = "";
      inputConfirm.required = false;
    }
    if (passwordHint) passwordHint.classList.remove("hidden");

    const allRoleCheckboxes = document.querySelectorAll(".additional-role-cb");
    allRoleCheckboxes.forEach((cb) => {
      cb.checked = false;
      cb.disabled = false;
    });

    let roleIdsString = userData.all_roles_ids
      ? String(userData.all_roles_ids)
      : String(userData.role_id);
    let userRolesArray = roleIdsString.split(",");

    allRoleCheckboxes.forEach((cb) => {
      if (userRolesArray.includes(cb.value)) {
        cb.checked = true;
      }
      if (userData.role_id == 1 && cb.value == 1) {
        cb.disabled = true;
      }
    });

    openModal(modalForm, backdropForm, panelForm);
  };

  window.hideUserModal = function () {
    closeModal(modalForm, backdropForm, panelForm);
  };

  if (inputRole) {
    inputRole.addEventListener("change", function () {
      const selectedMainRole = String(this.value);
      const allRoleCheckboxes = document.querySelectorAll(
        ".additional-role-cb",
      );

      if (inputId && inputId.value === "") {
        allRoleCheckboxes.forEach((cb) => {
          if (cb.value === selectedMainRole && selectedMainRole !== "") {
            cb.checked = true;
          } else {
            cb.checked = false;
          }
        });
      } else {
        allRoleCheckboxes.forEach((cb) => {
          if (cb.value === selectedMainRole && selectedMainRole !== "") {
            cb.checked = true;
          }
        });
      }
    });
  }

  // =========================================================================
  // 6. PASSWORD FUNCTIONS
  // =========================================================================
  window.togglePasswordVisibility = function (id) {
    const input = document.getElementById(id);
    if (input) input.type = input.type === "password" ? "text" : "password";
  };

  window.generatePassword = function (targetId = "user-password") {
    const input = document.getElementById(targetId);
    const confirmInput = document.getElementById("user-confirm-password");
    if (!input) return;

    const length = 12;
    const charset =
      "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
    let retVal = "";
    for (let i = 0, n = charset.length; i < length; ++i) {
      retVal += charset.charAt(Math.floor(Math.random() * n));
    }

    input.value = retVal;
    if (confirmInput) confirmInput.value = retVal;

    input.type = "text";
    if (confirmInput) confirmInput.type = "text";
    input.focus();
    input.dispatchEvent(new Event("input"));

    setTimeout(() => {
      input.type = "password";
      if (confirmInput) confirmInput.type = "password";
    }, 3000);
  };

  if (inputPassword) {
    inputPassword.addEventListener("input", function () {
      const val = this.value;
      let score = 0;
      if (val.length > 5) score++;
      if (val.length > 8) score++;
      if (/[A-Z]/.test(val)) score++;
      if (/[0-9]/.test(val)) score++;

      const bars = [
        document.getElementById("strength-bar-1"),
        document.getElementById("strength-bar-2"),
        document.getElementById("strength-bar-3"),
        document.getElementById("strength-bar-4"),
      ];
      const text = document.getElementById("password-strength-text");

      bars.forEach((bar, idx) => {
        if (bar) {
          if (idx < score) {
            bar.className = `h-full w-1/4 rounded-full transition-all ${score < 2 ? "bg-red-500" : score < 3 ? "bg-orange-500" : "bg-emerald-500"}`;
          } else {
            bar.className =
              "h-full w-1/4 bg-slate-200 rounded-full transition-all";
          }
        }
      });

      if (text) {
        text.innerText =
          score < 2
            ? LANG.js_pass_weak
            : score < 3
              ? LANG.js_pass_medium
              : LANG.js_pass_strong;
        text.className = `text-[10px] mt-1 italic text-right ${score < 2 ? "text-red-500" : score < 3 ? "text-orange-500" : "text-emerald-500"}`;
      }
    });
  }

  // =========================================================================
  // 7. DRAWER DETAIL
  // =========================================================================
  window.openDetailModal = function (userData) {
    if (detailDrawer && drawerOverlay) {
      drawerOverlay.classList.remove("hidden");
      setTimeout(() => {
        detailDrawer.classList.remove("translate-x-full");
      }, 10);
    }
    document.body.style.overflow = "hidden";

    const v = (val) => (val ? val : "-");

    // Bersihkan titik dari username
    const cleanUsername = userData.username
      ? userData.username.replace(/\./g, "")
      : "";

    if (document.getElementById("drawerName"))
      document.getElementById("drawerName").innerText = v(cleanUsername); // Gunakan username bersih
    if (document.getElementById("drawerEmail"))
      document.getElementById("drawerEmail").innerText = v(userData.email);

    const displayName = userData.full_name || cleanUsername || "US";
    const initials = displayName.substring(0, 2).toUpperCase();

    const fallbackUrl = `https://ui-avatars.com/api/?name=${initials}&background=1F7A4D&color=fff&size=160&bold=true&rounded=true`;

    let avatarUrl = "";
    const cleanBaseUrl = baseUrl.replace(/\/$/, "");

    // LOGIKA BARU: Cek foto_profil dulu, lalu cek foto_siswa
    const validFotoProfil =
      userData.foto_profil &&
      userData.foto_profil !== "null" &&
      String(userData.foto_profil).trim() !== "";
    const validFotoSiswa =
      userData.foto_siswa &&
      userData.foto_siswa !== "null" &&
      String(userData.foto_siswa).trim() !== "";

    if (validFotoProfil) {
      avatarUrl = `${cleanBaseUrl}/assets/uploads/avatars/${userData.foto_profil}`;
    } else if (validFotoSiswa) {
      // Cek path lama atau path baru (tergantung di mana kamu simpan foto siswa)
      // Coba arahkan ke folder siswa defaultmu
      avatarUrl = `${cleanBaseUrl}/uploads/siswa/${userData.foto_siswa}`;
    } else {
      avatarUrl = fallbackUrl;
    }

    const avatarEl = document.getElementById("drawerAvatar");
    if (avatarEl) {
      avatarEl.src = avatarUrl;
      avatarEl.onerror = function () {
        this.onerror = null;
        this.src = fallbackUrl;
      };
    }

    const roleEl = document.getElementById("drawerRole");
    if (roleEl) roleEl.innerText = userData.role_name || "User";

    const statusEl = document.getElementById("drawerStatus");
    const isActive = userData.is_active == 1;
    if (statusEl) {
      statusEl.innerText = isActive ? LANG.active : LANG.inactive;
      statusEl.className = isActive
        ? "px-3 py-1 bg-emerald-500/20 text-emerald-100 rounded-full text-xs font-semibold border border-emerald-500/30"
        : "px-3 py-1 bg-red-500/20 text-red-50 border border-red-500/30";
    }

    if (document.getElementById("info-username"))
      document.getElementById("info-username").innerText = v(cleanUsername); // Gunakan username bersih

    if (document.getElementById("info-joined") && userData.created_at) {
      const d = new Date(userData.created_at);
      document.getElementById("info-joined").innerText =
        new Intl.DateTimeFormat(document.documentElement.lang || "id-ID", {
          dateStyle: "medium",
        }).format(d);
    }

    if (document.getElementById("status-id-input"))
      document.getElementById("status-id-input").value = userData.id;
    if (document.getElementById("detail-id-input"))
      document.getElementById("detail-id-input").value = userData.id;

    const toggle = document.getElementById("toggle-status-akun");
    const label = document.getElementById("status-text-label");
    if (toggle) {
      toggle.checked = isActive;
      if (label)
        label.innerText = isActive ? LANG.status_active : LANG.status_inactive;

      const newToggle = toggle.cloneNode(true);
      toggle.parentNode.replaceChild(newToggle, toggle);

      newToggle.addEventListener("change", function () {
        const url = this.checked
          ? baseUrl + "admin/users/activate"
          : baseUrl + "admin/users/deactivate";
        const fd = new FormData();
        fd.append("id", userData.id);
        fetch(url, { method: "POST", body: fd }).then((res) => {
          if (res.ok) window.location.reload();
        });
      });
    }

    const btnEdit = document.getElementById("btn-edit-drawer");
    if (btnEdit) {
      btnEdit.onclick = function () {
        window.closeDrawer();
        setTimeout(() => window.openEditModal(userData), 300);
      };
    }
  };

  window.closeDrawer = function () {
    if (detailDrawer && drawerOverlay) {
      detailDrawer.classList.add("translate-x-full");
      setTimeout(() => {
        drawerOverlay.classList.add("hidden");
      }, 300);
    }
    document.body.style.overflow = "";
  };

  // =========================================================================
  // 8. HELPERS UI
  // =========================================================================
  function clearAllErrors() {
    document
      .querySelectorAll(".text-red-500.text-xs")
      .forEach((el) => el.classList.add("hidden"));
    document
      .querySelectorAll("input, select")
      .forEach((el) => el.classList.remove("border-red-500"));
  }

  function openModal(modal, backdrop, panel) {
    if (modal) modal.classList.remove("hidden");
    setTimeout(() => {
      if (backdrop) backdrop.classList.remove("opacity-0");
      if (panel) {
        panel.classList.remove("opacity-0", "scale-95");
        panel.classList.add("opacity-100", "scale-100");
      }
    }, 10);
  }

  function closeModal(modal, backdrop, panel) {
    if (backdrop) backdrop.classList.add("opacity-0");
    if (panel) {
      panel.classList.remove("opacity-100", "scale-100");
      panel.classList.add("opacity-0", "scale-95");
    }
    setTimeout(() => {
      if (modal) modal.classList.add("hidden");
    }, 300);
  }

  // =========================================================================
  // 9. CONFIRMATION MODALS
  // =========================================================================
  window.confirmDelete = function (id) {
    deleteIdTarget = id;
    if (modalDelete) modalDelete.classList.remove("hidden");
  };
  window.hideDeleteModal = function () {
    if (modalDelete) modalDelete.classList.add("hidden");
    deleteIdTarget = null;
  };
  window.executeDeleteAction = async function () {
    if (!deleteIdTarget) return;

    const deleteForm = document.getElementById("delete-user-form");
    const deleteInput = document.getElementById("delete-user-id");
    deleteInput.value = deleteIdTarget;

    const btn = document.querySelector(
      '#delete-modal button[onclick="executeDeleteAction()"]',
    );
    const originalText = btn.innerText;
    btn.innerText = LANG.js_deleting;
    btn.disabled = true;

    try {
      const formData = new FormData(deleteForm);
      const response = await fetch(deleteForm.action, {
        method: "POST",
        headers: { "X-Requested-With": "XMLHttpRequest" },
        body: formData,
      });

      const result = await response.json();

      if (result.status === "success") {
        window.location.reload();
      } else {
        alert(result.message || LANG.js_fail_delete);
        btn.innerText = originalText;
        btn.disabled = false;
        hideDeleteModal();
      }
    } catch (error) {
      alert(LANG.js_conn_error);
      btn.innerText = originalText;
      btn.disabled = false;
      hideDeleteModal();
    }
  };

  window.confirmActivate = function (id) {
    activateIdTarget = id;
    if (modalActivate) modalActivate.classList.remove("hidden");
  };
  window.hideActivateModal = function () {
    if (modalActivate) modalActivate.classList.add("hidden");
    activateIdTarget = null;
  };
  window.executeActivateAction = async function () {
    if (!activateIdTarget) return;
    const activateForm = document.getElementById("activate-form");
    const activateInput = document.getElementById("activate-id");
    activateInput.value = activateIdTarget;

    try {
      const formData = new FormData(activateForm);
      const response = await fetch(activateForm.action, {
        method: "POST",
        headers: { "X-Requested-With": "XMLHttpRequest" },
        body: formData,
      });
      const result = await response.json();
      if (result.status === "success") window.location.reload();
      else alert(result.message || LANG.js_failed);
    } catch (e) {
      alert(LANG.js_conn_error);
    }
  };

  window.confirmDeactivate = function (id) {
    deactivateIdTarget = id;
    if (modalDeactivate) modalDeactivate.classList.remove("hidden");
  };
  window.hideConfirmModal = function () {
    if (modalDeactivate) modalDeactivate.classList.add("hidden");
    deactivateIdTarget = null;
  };
  window.executeDeactivateAction = async function () {
    if (!deactivateIdTarget) return;
    const deactivateForm = document.getElementById("deactivate-form");
    const deactivateInput = document.getElementById("deactivate-id");
    deactivateInput.value = deactivateIdTarget;

    try {
      const formData = new FormData(deactivateForm);
      const response = await fetch(deactivateForm.action, {
        method: "POST",
        headers: { "X-Requested-With": "XMLHttpRequest" },
        body: formData,
      });
      const result = await response.json();
      if (result.status === "success") window.location.reload();
      else alert(result.message || LANG.js_failed);
    } catch (e) {
      alert(LANG.js_conn_error);
    }
  };

  window.confirmBulkDelete = function () {
    const checkedBoxes = document.querySelectorAll(".user-checkbox:checked");
    if (checkedBoxes.length === 0) return;

    if (
      confirm(LANG.js_delete_selected.replace("{count}", checkedBoxes.length))
    ) {
      const ids = Array.from(checkedBoxes).map((cb) => cb.value);
      fetch(baseUrl + "admin/users/bulk-delete", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify({ ids: ids }),
      })
        .then((res) => res.json())
        .then((data) => {
          window.location.reload();
        });
    }
  };
});

// =========================================================================
// 10. INLINE MULTI-ROLE (DROPDOWN TABLE FIX)
// =========================================================================

window.toggleRoleDropdown = function (userId, event) {
  if (event) {
    event.preventDefault();
    event.stopPropagation();
  }

  const menu = document.getElementById(`role-menu-${userId}`);
  if (!menu) return;

  const isCurrentlyHidden = menu.classList.contains("hidden");

  document.querySelectorAll('[id^="role-menu-"]').forEach((el) => {
    el.classList.add("hidden");
  });

  if (isCurrentlyHidden) {
    menu.classList.remove("hidden");

    const td = menu.closest("td");
    if (td) {
      td.style.overflow = "visible";
      td.style.position = "relative";
    }
    const tr = menu.closest("tr");
    if (tr) {
      tr.style.zIndex = "50";
    }
  }
};

document.addEventListener("click", function (event) {
  if (!event.target.closest(".dropdown-container")) {
    document.querySelectorAll('[id^="role-menu-"]').forEach((el) => {
      el.classList.add("hidden");

      const tr = el.closest("tr");
      if (tr) tr.style.zIndex = "";
    });
  }
});

window.updateInlineRoles = function (userId) {
  const wrapper = document.getElementById(`role-menu-${userId}`);
  if (!wrapper) return;

  const checkedBoxes = wrapper.querySelectorAll(".inline-role-cb:checked");
  const roleIds = Array.from(checkedBoxes).map((cb) => cb.value);

  const countSpan = document.getElementById(`role-count-${userId}`);
  if (countSpan) countSpan.innerText = `${roleIds.length} Akses`;

  const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timerProgressBar: true,
  });
  Toast.fire({ icon: "info", title: LANG.js_saving, timer: 1000 });

  const baseUrlRaw = document
    .getElementById("base-url")
    .dataset.url.replace(/\/$/, "");

  fetch(`${baseUrlRaw}/admin/users/update-inline-roles`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-Requested-With": "XMLHttpRequest",
    },
    body: JSON.stringify({ user_id: userId, role_ids: roleIds }),
  })
    .then((r) => r.json())
    .then((data) => {
      if (data.status === "success") {
        Toast.fire({ icon: "success", title: LANG.js_saved, timer: 1500 });
      } else {
        Swal.fire(LANG.js_failed, data.message, "error");
      }
    })
    .catch((err) => {
      console.error(err);
      Swal.fire("Error", LANG.js_connection_lost, "error");
    });
};
