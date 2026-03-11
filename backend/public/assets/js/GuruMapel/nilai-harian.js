// ==========================================
// 0. SABUK PENGAMAN BAHASA (FALLBACK)
// ==========================================
const LANG = window.LANG || {
    fill_all: "Mohon lengkapi semua field!", loading: "Memuat...", locked: "Terkunci", draft: "Draft",
    load_success: "Data berhasil dimuat!", err_load_data: "Gagal memuat data siswa: ",
    err_server: "Terjadi kesalahan pada server saat memuat data.", btn_load: "Load Data",
    ph_desc: "Deskripsi capaian siswa...", no_input: "Tidak ada nilai yang diinput!",
    saving: "Menyimpan...", succ_draft: "✓ Draft berhasil disimpan!", fail_prefix: "Gagal: ",
    err_save_draft: "Terjadi kesalahan pada server saat menyimpan draft.",
    lock_confirm_1: "Anda akan mengunci nilai untuk ", lock_confirm_2: " - ",
    lock_confirm_3: ". Setelah dikunci, nilai akan masuk ke database. Lanjutkan?",
    err_save: "Terjadi kesalahan pada server saat menyimpan.", succ_reset: "✓ Semua nilai berhasil direset!",
    reset_confirm: "Apakah Anda yakin ingin menghapus semua nilai yang sudah diinput? Tindakan ini tidak dapat dibatalkan.",
    auto_save: "✓ Nilai tersimpan otomatis", auto_save_silent: "⏳ Nilai tersimpan otomatis",
    exit_edit: "Keluar dari mode edit."
};

let students = [];
let nilaiData = {};
let isLocked = false;
let confirmCallback = null;

function checkReadyToInput() {
    const jenis = document.getElementById("jenisPenilaian").value;
    const pertemuan = document.getElementById("pertemuan").value;
    const tanggal = document.getElementById("tanggalPenilaian").value;
    document.getElementById("btnLoadData").disabled = !(jenis && pertemuan && tanggal);
}

function getPredikat(nilai, kkm) {
    if (!nilai || nilai === "") return "";
    const n = parseInt(nilai);
    if (n >= 90) return "A";
    if (n >= 80) return "B";
    if (n >= kkm) return "C";
    return "D";
}

function getPredikatClass(predikat) {
    if (predikat === "A") return "bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50";
    if (predikat === "B") return "bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-800/50";
    if (predikat === "C") return "bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800/50";
    if (predikat === "D") return "bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 border border-red-200 dark:border-red-800/50";
    return "bg-gray-100 dark:bg-slate-700 text-gray-400 dark:text-slate-500 border border-gray-200 dark:border-slate-600";
}

function updatePredikat(studentId) {
    const kkm = parseInt(document.getElementById("kkm").value) || 75;
    const input = document.getElementById(`nilai-${studentId}`);
    const predikatBadge = document.getElementById(`predikat-${studentId}`);
    const nilai = input.value;
    const predikat = getPredikat(nilai, kkm);

    predikatBadge.textContent = predikat || "-";
    predikatBadge.className = "predikat-badge " + getPredikatClass(predikat);

    if (nilai) {
        const n = parseInt(nilai);
        if (n < kkm) { input.classList.add("below-kkm"); input.classList.remove("above-kkm"); } 
        else { input.classList.add("above-kkm"); input.classList.remove("below-kkm"); }
    } else {
        input.classList.remove("below-kkm", "above-kkm");
    }
    updateProgress();
    autoSave(studentId);
}

function updateAllPredikat() {
    students.forEach((student) => { updatePredikat(student.id); });
}

function updateProgress() {
    let completed = 0;
    students.forEach((student) => {
        const input = document.getElementById(`nilai-${student.id}`);
        if (input && input.value) completed++;
    });
    const percentage = (completed / students.length) * 100;
    document.getElementById("progressFill").style.width = percentage + "%";
    document.getElementById("progressText").textContent = `${completed}/${students.length} siswa`;
}

function autoSave(studentId) {
    const input = document.getElementById(`nilai-${studentId}`);
    const keterangan = document.getElementById(`keterangan-${studentId}`);
    nilaiData[studentId] = { nilai: input.value, keterangan: keterangan.value, };
    showToast(LANG.auto_save);
}

async function loadNilaiData() {
    const jenisValue = document.getElementById("jenisPenilaian").value;
    const pertemuanValue = document.getElementById("pertemuan").value;
    const tanggalValue = document.getElementById("tanggalPenilaian").value;
    const btnLoad = document.getElementById("btnLoadData");
    const semesterValue = document.getElementById("semesterFilter").value;

    if (!jenisValue || !pertemuanValue || !tanggalValue) {
        showToast(LANG.fill_all);
        return;
    }

    try {
        btnLoad.disabled = true;
        btnLoad.innerHTML = `<svg class="animate-spin h-5 w-5 mr-3 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ` + LANG.loading;

        // FIX: Kirim rombel_id dan mapel_id via URL parameter 
        const response = await fetch(`${BASE_URL}/guru/get-students?jenis=${jenisValue}&pertemuan=${pertemuanValue}&semester=${semesterValue}&rombel_id=${ACTIVE_ROMBEL_ID}&mapel_id=${ACTIVE_MAPEL_ID}`);
        const result = await response.json();

        if (result.status === 200) {
            students = result.data; 
            nilaiData = {}; 
            isLocked = false; 

            students.forEach(student => {
                if (student.nilai_tersimpan !== "" && student.nilai_tersimpan !== null) {
                    const nilaiBulat = parseFloat(student.nilai_tersimpan).toString(); 
                    nilaiData[student.id] = {
                        nilai: nilaiBulat,
                        keterangan: student.keterangan_tersimpan || ""
                    };
                }
                if (student.status_simpan === 'terkunci') {
                    isLocked = true;
                }
            });

            document.getElementById("emptyState").style.display = "none";
            document.getElementById("tableContainer").style.display = "block";
            document.getElementById("progressContainer").style.display = "flex";
            document.getElementById("actionToolbar").style.display = "block";

            const badge = document.getElementById("statusBadge");
            const btnDraft = document.getElementById("btnSaveDraft");
            const btnLock = document.getElementById("btnSaveLock");

            if (isLocked) {
                badge.innerHTML = `<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg> ${LANG.locked}`;
                badge.className = "px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 flex items-center gap-1 border border-red-200";
                if (btnDraft) btnDraft.disabled = true;
                if (btnLock) btnLock.disabled = true;
            } else {
                badge.innerHTML = `<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg> ${LANG.draft}`;
                badge.className = "px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 flex items-center gap-1 border border-yellow-200";
                if (btnDraft) btnDraft.disabled = false;
                if (btnLock) btnLock.disabled = false;
            }

            renderNilaiTable();
            showToast(LANG.load_success);

        } else {
            showToast(LANG.err_load_data + result.message);
        }
    } catch (error) {
        console.error("Error fetching data:", error);
        showToast(LANG.err_server);
    } finally {
        btnLoad.disabled = false;
        btnLoad.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewbox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> ${LANG.btn_load}`;
    }
}

function renderNilaiTable() {
    const tbody = document.getElementById("nilaiTableBody");
    const kkm = parseInt(document.getElementById("kkm").value) || 75;
    tbody.innerHTML = "";

    students.forEach((student, index) => {
        const savedData = nilaiData[student.id] || {};
        const nilai = savedData.nilai || ""; 
        const keterangan = savedData.keterangan || "";
        const predikat = getPredikat(nilai, kkm);

        const row = document.createElement("tr");
        row.className = "hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700/50 last:border-0";
        row.innerHTML = `
            <td class="px-6 py-4 font-bold text-gray-500 dark:text-slate-400 text-center transition-colors">${index + 1}</td>
            <td class="px-6 py-4 font-bold text-gray-900 dark:text-white transition-colors">${student.name}</td>
            <td class="px-6 py-4 font-mono text-xs text-gray-500 dark:text-slate-400 font-black tracking-wider transition-colors">${student.nis}</td>
            <td class="px-6 py-4 text-center">
                <input type="number" id="nilai-${student.id}"
                class="form-input w-24 px-4 py-2.5 border-2 border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-900 dark:text-white rounded-xl transition-all font-black text-center outline-none focus:ring-4 focus:ring-[var(--warna-primary)]/20" 
                value="${nilai}" min="0" max="100" placeholder="0"
                oninput="evaluateKkmColor('nilai-${student.id}')"
                onchange="updatePredikat(${student.id}); triggerAutoSave();"
                onkeyup="triggerAutoSave()" ${isLocked ? "disabled" : ""} >
            </td>
            <td class="px-6 py-4 text-center">
                <div class="inline-flex px-3 py-1.5 rounded-xl text-sm font-black shadow-sm transition-all ${getPredikatClass(predikat)}" id="predikat-${student.id}">
                ${predikat || "-"}
                </div>
            </td>
            <td class="px-6 py-4">
                <input type="text" id="keterangan-${student.id}"
                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-slate-900/50 border border-gray-200 dark:border-slate-600 text-gray-800 dark:text-slate-300 rounded-xl outline-none focus:bg-white dark:focus:bg-slate-700 transition-all text-xs font-medium placeholder-gray-400 dark:placeholder-slate-500" 
                value="${keterangan}" placeholder="${LANG.ph_desc}"
                onchange="triggerAutoSave()" ${isLocked ? "disabled" : ""} >
            </td>
            `;
        tbody.appendChild(row);
    });

    updateProgress();
    refreshKkmColors(); 
}

async function saveDraft() {
    const jenisValue = document.getElementById("jenisPenilaian").value;
    const pertemuanValue = document.getElementById("pertemuan").value;
    const tanggalValue = document.getElementById("tanggalPenilaian").value;
    const semesterValue = document.getElementById("semesterFilter").value;
    
    let payloadData = {};
    let adaYangDiisi = false;

    students.forEach((student) => {
        const input = document.getElementById(`nilai-${student.id}`);
        const keterangan = document.getElementById(`keterangan-${student.id}`);
        
        if (input && input.value !== "") {
            payloadData[student.id] = {
                nilai: input.value,
                predikat: calculatePredikat(input.value),
                keterangan: keterangan ? keterangan.value : ""
            };
            adaYangDiisi = true;
        }
    });

    if (!adaYangDiisi) { showToast(LANG.no_input); return; }

    try {
        const btnSaveDraft = document.getElementById("btnSaveDraft");
        const originalText = btnSaveDraft.innerHTML;
        btnSaveDraft.innerHTML = LANG.saving;
        btnSaveDraft.disabled = true;

        // FIX: Kirim rombel_id dan mapel_id via Body JSON
        const response = await fetch(`${BASE_URL}/guru/save-nilai`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                [csrfTokenName]: csrfTokenHash
            },
            body: JSON.stringify({
                rombel_id: ACTIVE_ROMBEL_ID,
                mapel_id: ACTIVE_MAPEL_ID,
                jenis_penilaian: jenisValue, 
                pertemuan: pertemuanValue,
                tanggal_penilaian: tanggalValue, 
                semester: semesterValue,
                nilaiData: payloadData, 
                status_simpan: 'draft' 
            })
        });
        const result = await response.json();

        if (result.status === 200) {
            showToast(LANG.succ_draft);
        } else {
            showToast(LANG.fail_prefix + result.message);
        }
        btnSaveDraft.innerHTML = originalText;
        btnSaveDraft.disabled = false;
    } catch (error) {
        console.error("Error saving draft:", error);
        showToast(LANG.err_save_draft);
    }
}

function saveLock() {
  const jenisValue = document.getElementById("jenisPenilaian").value;
  const pertemuanValue = document.getElementById("pertemuan").value;
  const tanggalValue = document.getElementById("tanggalPenilaian").value;
  const semesterValue = document.getElementById("semesterFilter").value;
  
  const jenisText = document.getElementById("jenisPenilaian").options[document.getElementById("jenisPenilaian").selectedIndex].text;
  const pertemuanText = document.getElementById("pertemuan").options[document.getElementById("pertemuan").selectedIndex].text;
  const kkm = parseInt(document.getElementById("kkm").value) || 75;

  const payloadData = {};
  for (const studentId in nilaiData) {
      const nilai = nilaiData[studentId].nilai;
      payloadData[studentId] = {
          nilai: nilai,
          keterangan: nilaiData[studentId].keterangan,
          predikat: getPredikat(nilai, kkm)
      };
  }

  if (Object.keys(payloadData).length === 0) {
      showToast(LANG.no_input);
      return;
  }

  confirmCallback = async () => {
    try {
        const btnSave = document.getElementById("btnSaveLock");
        const originalBtnText = btnSave.innerHTML;
        btnSave.innerHTML = LANG.saving;
        btnSave.disabled = true;

        // FIX: Kirim rombel_id dan mapel_id via Body JSON
        const response = await fetch(`${BASE_URL}/guru/save-nilai`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                [csrfTokenName]: csrfTokenHash
            },
            body: JSON.stringify({
                rombel_id: ACTIVE_ROMBEL_ID,
                mapel_id: ACTIVE_MAPEL_ID,
                jenis_penilaian: jenisValue, 
                pertemuan: pertemuanValue,
                tanggal_penilaian: tanggalValue, 
                semester: semesterValue,
                nilaiData: payloadData, 
                status_simpan: 'terkunci' 
            })
        });

        const result = await response.json();

        if (result.status === 200) {
            isLocked = true;
            students.forEach((student) => {
                const input = document.getElementById(`nilai-${student.id}`);
                const keterangan = document.getElementById(`keterangan-${student.id}`);
                if (input) input.disabled = true;
                if (keterangan) keterangan.disabled = true;
            });

            const badge = document.getElementById("statusBadge");
            badge.innerHTML = `<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg> ${LANG.locked}`;
            badge.className = "px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 flex items-center gap-1 border border-red-200";
            document.getElementById("btnSaveDraft").disabled = true;
            showToast("✓ " + result.message);
        } else {
            showToast(LANG.fail_prefix + result.message);
            btnSave.innerHTML = originalBtnText;
            btnSave.disabled = false;
        }
    } catch (error) {
        console.error("Error saving data:", error);
        showToast(LANG.err_save);
    } finally {
        closeConfirmModal();
    }
  };

  openConfirmModal(LANG.lock_confirm_1 + jenisText + LANG.lock_confirm_2 + pertemuanText + LANG.lock_confirm_3);
}

function resetAllNilai() {
  confirmCallback = () => {
    nilaiData = {};
    renderNilaiTable();
    showToast(LANG.succ_reset);
    closeConfirmModal();
  };
  openConfirmModal(LANG.reset_confirm);
}

function openConfirmModal(message) {
  document.getElementById("confirmMessage").textContent = message;
  document.getElementById("confirmModal").classList.add("active");
  document.body.style.overflow = "hidden";
}

function closeConfirmModal() {
  document.getElementById("confirmModal").classList.remove("active");
  document.body.style.overflow = "";
  confirmCallback = null;
}

function confirmAction() {
  if (confirmCallback) confirmCallback();
}

function showToast(message) {
  const toast = document.getElementById("toast");
  const toastMessage = document.getElementById("toastMessage");
  if(!toast || !toastMessage) return;

  toastMessage.textContent = message;
  toast.classList.remove("translate-x-full", "opacity-0");
  
  setTimeout(() => { 
      toast.classList.add("translate-x-full", "opacity-0"); 
  }, 3000);
}

const today = new Date().toISOString().split("T")[0];
document.getElementById("tanggalPenilaian").value = today;

function calculatePredikat(nilai) {
    const angka = parseFloat(nilai);
    if (isNaN(angka)) return "-"; 
    if (angka >= 90 && angka <= 100) return "A";
    else if (angka >= 80 && angka < 90) return "B";
    else if (angka >= 70 && angka < 80) return "C";
    else if (angka >= 0 && angka < 70) return "D";
    else return "-";
}

let autoSaveTimer;
function triggerAutoSave() {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(() => {
        autoSaveDraftSilent();
    }, 1500); 
}

async function autoSaveDraftSilent() {
    const jenisValue = document.getElementById("jenisPenilaian").value;
    const pertemuanValue = document.getElementById("pertemuan").value;
    const tanggalValue = document.getElementById("tanggalPenilaian").value;
    const semesterValue = document.getElementById("semesterFilter").value;
    
    let payloadData = {};
    let adaYangDiisi = false;

    students.forEach((student) => {
        const input = document.getElementById(`nilai-${student.id}`);
        const keterangan = document.getElementById(`keterangan-${student.id}`);
        
        if (input && input.value !== "") {
            payloadData[student.id] = {
                nilai: input.value,
                predikat: calculatePredikat(input.value),
                keterangan: keterangan ? keterangan.value : ""
            };
            adaYangDiisi = true;
        }
    });

    if (!adaYangDiisi) return; 

    try {
        const response = await fetch(`${BASE_URL}/guru/save-nilai`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                [csrfTokenName]: csrfTokenHash
            },
            // FIX: Tambahkan rombel_id dan mapel_id
            body: JSON.stringify({
                rombel_id: ACTIVE_ROMBEL_ID,
                mapel_id: ACTIVE_MAPEL_ID,
                jenis_penilaian: jenisValue, 
                pertemuan: pertemuanValue,
                tanggal_penilaian: tanggalValue, 
                semester: semesterValue,
                nilaiData: payloadData, 
                status_simpan: 'draft' 
            })
        });

        const result = await response.json();
        if (result.status === 200) {
            showToast(LANG.auto_save_silent);
        }
    } catch (error) {
        console.error("Auto-save error:", error);
    }
}

function evaluateKkmColor(inputId) {
    const kkmInput = document.getElementById("kkm");
    const kkm = kkmInput && kkmInput.value !== "" ? parseFloat(kkmInput.value) : 75; 

    const inputEl = document.getElementById(inputId);
    if (!inputEl) return;

    const val = inputEl.value;
    
    inputEl.classList.remove(
        'text-emerald-700', 'dark:text-emerald-400', 'border-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-900/20',
        'text-red-700', 'dark:text-red-400', 'border-red-500', 'bg-red-50', 'dark:bg-red-900/20',
        'border-amber-400', 'bg-amber-50', 'dark:bg-amber-900/10'
    );

    if (val === "" || parseFloat(val) === 0) {
        inputEl.classList.add('border-amber-400', 'bg-amber-50', 'dark:bg-amber-900/10');
    } else if (parseFloat(val) >= kkm) {
        inputEl.classList.add('text-emerald-700', 'dark:text-emerald-400', 'border-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-900/20');
    } else {
        inputEl.classList.add('text-red-700', 'dark:text-red-400', 'border-red-500', 'bg-red-50', 'dark:bg-red-900/20');
    }
}

function refreshKkmColors() {
    if (typeof students !== 'undefined') {
        students.forEach(student => {
            evaluateKkmColor(`nilai-${student.id}`);
        });
    }
}

function closeTable() {
    document.getElementById("tableContainer").style.display = "none";
    document.getElementById("progressContainer").style.display = "none";
    document.getElementById("actionToolbar").style.display = "none";
    document.getElementById("emptyState").style.display = ""; 
    nilaiData = {};
    document.getElementById("nilaiTableBody").innerHTML = "";
    document.getElementById("progressFill").style.width = "0%";
    document.getElementById("progressText").textContent = "0/0 siswa";
    document.getElementById("statusBadge").innerHTML = "";
    document.getElementById("statusBadge").className = "";
    showToast(LANG.exit_edit);
}