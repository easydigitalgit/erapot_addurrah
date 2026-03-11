// ==========================================
// SABUK PENGAMAN (FALLBACK)
// ==========================================
const LANG = window.LANG || {
    desc_a: "Menunjukkan pemahaman yang sangat baik dan mampu menerapkan konsep dengan sempurna.",
    desc_b: "Menunjukkan pemahaman yang baik dan mampu menerapkan konsep dengan cukup baik.",
    desc_c: "Menunjukkan pemahaman cukup baik namun perlu peningkatan dalam penerapan konsep.",
    desc_d: "Perlu bimbingan lebih lanjut untuk meningkatkan pemahaman konsep dasar.",
    auto_save: "✓ Perubahan tersimpan otomatis", loading: "Memuat...", ready: "Siap Validasi", locked: "Terkunci", draft: "Draft",
    err_load_server: "Gagal memuat data dari server. Periksa koneksi atau console browser.",
    err_no_data_filled: "Belum ada nilai yang diisi. Silakan isi minimal satu nilai siswa.",
    saving: "Menyimpan...", succ_draft: "Draft nilai berhasil disimpan!", err_save_data: "Gagal menyimpan data.",
    err_server_save: "Gagal terhubung ke server saat menyimpan data.",
    err_no_student: "Tidak ada data siswa yang ditampilkan. Silakan load data terlebih dahulu.",
    warn_empty_val: "Masih ada nilai siswa yang kosong. Anda yakin ingin menandai data ini sebagai Siap Validasi?",
    processing: "Memproses...", succ_ready: "✓ Nilai berhasil ditandai Siap Validasi!",
    succ_ready_alert: "Berhasil! Data nilai sekarang berstatus Siap Validasi.", err_update_status: "Gagal mengupdate status ke server.",
    err_server_update: "Gagal terhubung ke server saat mengupdate status.",
    lock_warning: "<strong>PERINGATAN PENTING:</strong><br><br>Anda akan mengunci nilai akhir ini. Setelah dikunci:<br>• Nilai tidak dapat diubah atau ditarik kembali<br>• Nilai akan masuk secara resmi ke rapor<br>• Hanya Admin yang dapat membuka kunci<br><br>Apakah Anda yakin ingin melanjutkan?",
    locking: "Mengunci...", succ_lock: "✓ Nilai berhasil dikunci! Data telah final.",
    succ_lock_alert: "Berhasil! Data nilai telah terkunci secara permanen.", err_lock: "Gagal mengunci nilai.",
    err_server_lock: "Gagal terhubung ke server saat mengunci nilai.",
    warn_cancel_ready: "Apakah Anda yakin ingin menarik kembali data ini menjadi Draft? Anda akan bisa mengedit nilai lagi.",
    succ_cancel: "✓ Data berhasil dikembalikan ke Draft!", succ_cancel_alert: "Berhasil! Silakan edit kembali nilai siswa.",
    err_server_conn: "Gagal terhubung ke server."
};

let students = [];
let nilaiData = {};
let currentStatus = "draft";
let confirmCallback = null;

const deskripsiTemplates = {
  A: LANG.desc_a, B: LANG.desc_b, C: LANG.desc_c, D: LANG.desc_d,
};

function checkReadyToInput() {
    const jenisSumatif = document.getElementById('jenisSumatif').value;
    const btnLoadData = document.getElementById('btnLoadData');
    
    if (jenisSumatif !== "") {
        btnLoadData.removeAttribute('disabled');
        btnLoadData.classList.remove('opacity-50', 'cursor-not-allowed');
    } else {
        btnLoadData.setAttribute('disabled', 'true');
        btnLoadData.classList.add('opacity-50', 'cursor-not-allowed');
    }
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
  if (predikat === "A") return "bg-emerald-100 dark:!bg-emerald-900/30 text-emerald-700 dark:!text-emerald-400 border border-emerald-200 dark:!border-emerald-800/50";
  if (predikat === "B") return "bg-blue-100 dark:!bg-blue-900/30 text-blue-700 dark:!text-blue-400 border border-blue-200 dark:!border-blue-800/50";
  if (predikat === "C") return "bg-amber-100 dark:!bg-amber-900/30 text-amber-700 dark:!text-amber-400 border border-amber-200 dark:!border-amber-800/50";
  if (predikat === "D") return "bg-red-100 dark:!bg-red-900/30 text-red-700 dark:!text-red-400 border border-red-200 dark:!border-red-800/50";
  return "bg-gray-100 dark:!bg-slate-700 text-gray-400 dark:!text-slate-500 border border-gray-200 dark:!border-slate-600";
}

function updatePredikatAndDeskripsi(studentId) {
  const kkm = parseInt(document.getElementById("kkm").value) || 75;
  const input = document.getElementById(`nilai-${studentId}`);
  const predikatBadge = document.getElementById(`predikat-${studentId}`);
  const deskripsiTextarea = document.getElementById(`deskripsi-${studentId}`);

  const nilai = input.value;
  const predikat = getPredikat(nilai, kkm);

  predikatBadge.textContent = predikat || "-";
  predikatBadge.className = "inline-flex px-4 py-2 rounded-xl text-lg font-black uppercase tracking-wider transition-colors shadow-sm " + getPredikatClass(predikat);

  if (predikat && deskripsiTextarea.value === "") {
    deskripsiTextarea.value = deskripsiTemplates[predikat] || "";
  }

  // Handle Border Input Colors
  input.classList.remove("border-red-500", "border-emerald-500");
  if (nilai) {
    const n = parseInt(nilai);
    if (n < kkm) { input.classList.add("border-red-500"); } 
    else { input.classList.add("border-emerald-500"); }
  }

  autoSave(studentId);
}

function autoSave(studentId) {
  const input = document.getElementById(`nilai-${studentId}`);
  const deskripsi = document.getElementById(`deskripsi-${studentId}`);

  nilaiData[studentId] = {
    nilai: input.value,
    deskripsi: deskripsi.value,
    locked: nilaiData[studentId]?.locked || false,
  };

  showToast(LANG.auto_save);
}

async function loadNilaiData() {
    const btnLoadData = document.getElementById('btnLoadData');
    const url = btnLoadData.getAttribute('data-url');
    const jenisSumatif = document.getElementById('jenisSumatif').value;

    if (!jenisSumatif) return;

    const originalText = btnLoadData.innerHTML;
    btnLoadData.innerHTML = `<svg class="animate-spin h-5 w-5 mr-3 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ` + LANG.loading;
    btnLoadData.disabled = true;

    try {
        // FIX: Kirim rombel_id dan mapel_id sebagai parameter URL
        const response = await fetch(`${url}?jenis=${jenisSumatif}&rombel_id=${ACTIVE_ROMBEL_ID}&mapel_id=${ACTIVE_MAPEL_ID}`);
        const result = await response.json();

        if (result.status === 'success') {
            students = result.data;
            
            if (students.length > 0 && students[0].status) {
                let dbStatus = students[0].status;
                if (dbStatus === 'siap_validasi') currentStatus = 'ready';
                else if (dbStatus === 'terkunci') currentStatus = 'locked';
                else currentStatus = 'draft';
            } else {
                currentStatus = 'draft';
            }

            renderTable(students);
            updateProgressStatus();
            updateButtonStates();
            
            document.getElementById('tableContainer').style.display = 'block';
            document.getElementById('actionToolbar').style.display = 'block';
            document.getElementById('progressStatusContainer').style.display = 'block';
            
            const emptyState = document.getElementById('emptyState');
            if(emptyState) emptyState.style.display = 'none';
          }
    } catch (error) {
        console.error("Terjadi kesalahan:", error);
        showToast(LANG.err_load_server, "error");
    } finally {
        btnLoadData.innerHTML = originalText;
        btnLoadData.disabled = false;
    }
}

function renderTable(dataSiswa) {
    const tbody = document.getElementById('nilaiTableBody');
    if (!tbody) return;

    tbody.innerHTML = ''; 
    const kkm = parseInt(document.getElementById("kkm").value) || 75;

    dataSiswa.forEach((siswa, index) => {
        const nilai = siswa.nilai !== null ? siswa.nilai : '';
        const deskripsi = siswa.deskripsi !== null ? siswa.deskripsi : '';
        const idNilai = siswa.nilai_id !== null ? siswa.nilai_id : '';
        const predikat = getPredikat(nilai, kkm);

        // Styling input dinamis untuk Dark Mode & Locked State
        const isInputLocked = (currentStatus === 'locked') 
            ? 'readonly class="input-nilai w-24 py-3 px-3 rounded-xl border border-gray-200 dark:!border-slate-700 bg-gray-100 dark:!bg-slate-700/50 text-gray-500 dark:!text-slate-500 cursor-not-allowed font-black text-center text-xl outline-none shadow-inner"' 
            : 'class="input-nilai w-24 py-3 px-3 rounded-xl border-2 border-gray-300 dark:!border-slate-600 bg-white dark:!bg-slate-700 focus:border-emerald-500 dark:focus:!border-emerald-400 font-black text-center text-xl text-gray-900 dark:!text-white outline-none transition-colors shadow-sm"';
            
        const isDescLocked = (currentStatus === 'locked')
            ? 'readonly class="input-deskripsi w-full py-3 px-4 rounded-xl border border-gray-200 dark:!border-slate-700 bg-gray-100 dark:!bg-slate-700/50 text-gray-500 dark:!text-slate-500 cursor-not-allowed text-xs font-medium outline-none resize-y min-h-[60px] shadow-inner"'
            : 'class="input-deskripsi w-full py-3 px-4 rounded-xl border-2 border-gray-300 dark:!border-slate-600 bg-gray-50 dark:!bg-slate-900/50 focus:bg-white dark:focus:!bg-slate-700 focus:border-emerald-500 dark:focus:!border-emerald-400 text-xs font-semibold text-gray-800 dark:!text-slate-200 outline-none transition-colors resize-y min-h-[60px] shadow-inner placeholder-gray-400 dark:placeholder-slate-500"';

        const statusBadge = (currentStatus === 'locked') 
            ? '<span class="inline-flex px-2 py-1 bg-rose-100 dark:!bg-rose-900/30 text-rose-700 dark:!text-rose-400 border border-rose-200 dark:!border-rose-800/50 rounded-lg text-[10px] font-black uppercase tracking-wider shadow-sm transition-colors">Locked</span>'
            : (currentStatus === 'ready') 
                ? '<span class="inline-flex px-2 py-1 bg-blue-100 dark:!bg-blue-900/30 text-blue-700 dark:!text-blue-400 border border-blue-200 dark:!border-blue-800/50 rounded-lg text-[10px] font-black uppercase tracking-wider shadow-sm transition-colors">Ready</span>'
                : '<span class="inline-flex px-2 py-1 bg-gray-100 dark:!bg-slate-700 text-gray-600 dark:!text-slate-300 border border-gray-200 dark:!border-slate-600 rounded-lg text-[10px] font-black uppercase tracking-wider shadow-sm transition-colors">Draft</span>';

        const tr = document.createElement('tr');
        tr.className = 'hover:bg-gray-50 dark:hover:!bg-slate-700/30 transition-colors border-b border-gray-100 dark:!border-slate-700/50 last:border-0 group bg-white dark:!bg-slate-800';
        tr.innerHTML = `
            <td class="py-4 px-6 text-center font-bold text-gray-500 dark:!text-slate-400 transition-colors">${index + 1}</td>
            <td class="py-4 px-6 font-bold text-gray-900 dark:!text-white transition-colors group-hover:text-[var(--warna-primary)]">
                ${siswa.nama}
                <input type="hidden" class="siswa-id" value="${siswa.siswa_id}">
                <input type="hidden" class="nilai-id" value="${idNilai}">
            </td>
            <td class="py-4 px-6 text-gray-500 dark:!text-slate-400 font-bold text-xs font-mono uppercase tracking-wider transition-colors">${siswa.nis}</td>
            <td class="py-4 px-6 text-center">
                <input type="number" min="0" max="100" id="nilai-${siswa.siswa_id}" ${isInputLocked} value="${nilai}" placeholder="-" onchange="updatePredikatAndDeskripsi(${siswa.siswa_id})">
            </td>
            <td class="py-4 px-6 text-center">
                <div id="predikat-${siswa.siswa_id}" class="inline-flex px-4 py-2 rounded-xl text-lg font-black uppercase tracking-wider transition-colors shadow-sm ${getPredikatClass(predikat)}">
                    ${predikat || '-'}
                </div>
            </td>
            <td class="py-4 px-6">
                <textarea id="deskripsi-${siswa.siswa_id}" ${isDescLocked} placeholder="${LANG.ph_desc}" onchange="autoSave(${siswa.siswa_id})">${deskripsi}</textarea>
            </td>
            <td class="py-4 px-6 text-center">
                ${statusBadge}
            </td>
        `;
        tbody.appendChild(row);
    });
}

function updateProgressStatus() {
  const step1 = document.getElementById("step1");
  const step2 = document.getElementById("step2");
  const step3 = document.getElementById("step3");

  // Reset classes manual (Bawaan CSS file) -> Ganti dengan Tailwind murni
  step1.className = "flex items-center gap-2 px-4 py-2 rounded-full font-bold text-xs transition-colors " + (currentStatus === "draft" ? "bg-[var(--warna-primary)] text-white shadow-lg shadow-[var(--warna-primary)]/30" : "bg-gray-100 dark:!bg-slate-700 text-gray-500 dark:!text-slate-400");
  step2.className = "flex items-center gap-2 px-4 py-2 rounded-full font-bold text-xs transition-colors " + (currentStatus === "ready" ? "bg-[var(--warna-primary)] text-white shadow-lg shadow-[var(--warna-primary)]/30" : "bg-gray-100 dark:!bg-slate-700 text-gray-500 dark:!text-slate-400");
  step3.className = "flex items-center gap-2 px-4 py-2 rounded-full font-bold text-xs transition-colors " + (currentStatus === "locked" ? "bg-[var(--warna-primary)] text-white shadow-lg shadow-[var(--warna-primary)]/30" : "bg-gray-100 dark:!bg-slate-700 text-gray-500 dark:!text-slate-400");

  const badge = document.getElementById("statusBadge");
  if (currentStatus === "draft") {
    badge.innerHTML = `<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg> ${LANG.draft}`;
    badge.className = "status-badge status-draft inline-flex items-center gap-1.5 dark:!bg-slate-700 dark:!text-slate-300 px-2 py-1 rounded-lg text-[10px] font-bold transition-all";
  } else if (currentStatus === "ready") {
    badge.innerHTML = `<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> ${LANG.ready}`;
    badge.className = "status-badge status-ready inline-flex items-center gap-1.5 bg-blue-100 dark:!bg-blue-900/30 text-blue-700 dark:!text-blue-400 border border-blue-200 dark:!border-blue-800/50 px-2 py-1 rounded-lg text-[10px] font-bold transition-all";
  } else if (currentStatus === "locked") {
    badge.innerHTML = `<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg> ${LANG.locked}`;
    badge.className = "status-badge status-locked inline-flex items-center gap-1.5 bg-rose-100 dark:!bg-rose-900/30 text-rose-700 dark:!text-rose-400 border border-rose-200 dark:!border-rose-800/50 px-2 py-1 rounded-lg text-[10px] font-bold transition-all";
  }
}

async function saveDraft() {
    const btnSave = document.getElementById('btnSaveDraft');
    const url = btnSave.getAttribute('data-url');
    const jenisSumatif = document.getElementById('jenisSumatif').value;

    const rows = document.querySelectorAll('#nilaiTableBody tr');
    let dataNilai = [];

    rows.forEach(row => {
        const siswaId = row.querySelector('.siswa-id').value;
        const nilaiId = row.querySelector('.nilai-id').value;
        const nilai = row.querySelector('.input-nilai').value;
        const deskripsi = row.querySelector('.input-deskripsi').value;

        if (nilai !== "") {
            dataNilai.push({
                siswa_id: siswaId, nilai_id: nilaiId,
                nilai: parseFloat(nilai), deskripsi: deskripsi
            });
        }
    });

    if (dataNilai.length === 0) {
        showToast(LANG.err_no_data_filled, "error");
        return;
    }

    const originalText = btnSave.innerHTML;
    btnSave.innerHTML = LANG.saving;
    btnSave.disabled = true;

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                [csrfTokenName]: csrfTokenHash
            },
            body: JSON.stringify({
                jenis_sumatif: jenisSumatif,
                mapel_id: ACTIVE_MAPEL_ID,
                rombel_id: ACTIVE_ROMBEL_ID,
                data_nilai: dataNilai
            })
        });

        const result = await response.json();

        if (result.status === 'success') {
            showToast(LANG.succ_draft, "success");
            loadNilaiData(); 
        } else {
            showToast(result.message || LANG.err_save_data, "error");
        }
    } catch (error) {
        console.error("Terjadi kesalahan:", error);
        showToast(LANG.err_server_save, "error");
    } finally {
        btnSave.innerHTML = originalText;
        btnSave.disabled = false;
    }
}

async function markReady() {
    const btnMarkReady = document.getElementById('btnMarkReady');
    const url = btnMarkReady.getAttribute('data-url');
    const jenisSumatif = document.getElementById('jenisSumatif').value;

    const inputs = document.querySelectorAll('.input-nilai');
    let allFilled = true;
    
    if (inputs.length === 0) {
        showToast(LANG.err_no_student, "error");
        return;
    }

    inputs.forEach(input => { if (input.value === "") allFilled = false; });

    if (!allFilled) {
        document.getElementById("confirmMessage").innerHTML = LANG.warn_empty_val;
        document.getElementById("confirmModal").classList.remove("hidden");
        document.getElementById("confirmModal").classList.add("flex");
        document.body.style.overflow = "hidden";
        
        confirmCallback = async () => {
            closeConfirmModal();
            await executeMarkReady(btnMarkReady, url, jenisSumatif);
        };
        return; // Hentikan eksekusi, tunggu konfirmasi modal
    }

    await executeMarkReady(btnMarkReady, url, jenisSumatif);
}

async function executeMarkReady(btnMarkReady, url, jenisSumatif) {
    const originalText = btnMarkReady.innerHTML;
    btnMarkReady.innerHTML = LANG.processing;
    btnMarkReady.disabled = true;

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                [csrfTokenName]: csrfTokenHash
            },
            body: JSON.stringify({
                jenis_sumatif: jenisSumatif,
                status: 'siap_validasi',
                mapel_id: ACTIVE_MAPEL_ID,
                rombel_id: ACTIVE_ROMBEL_ID
            })
        });

        const result = await response.json();

        if (result.status === 'success') {
            currentStatus = "ready";
            updateProgressStatus();
            updateButtonStates();
            showToast(LANG.succ_ready, "success");
            loadNilaiData();
        } else {
            showToast(result.message || LANG.err_update_status, "error");
        }
    } catch (error) {
        console.error("Terjadi kesalahan:", error);
        showToast(LANG.err_server_update, "error");
    } finally {
        btnMarkReady.innerHTML = originalText;
        btnMarkReady.disabled = false;
    }
}

function lockNilai() {
    const btnLock = document.getElementById('btnLock');
    const url = btnLock.getAttribute('data-url');
    const jenisSumatif = document.getElementById('jenisSumatif').value;

    document.getElementById("confirmMessage").innerHTML = LANG.lock_warning;
    document.getElementById("confirmModal").classList.remove("hidden");
    document.getElementById("confirmModal").classList.add("flex");
    document.body.style.overflow = "hidden";

    confirmCallback = async () => {
        closeConfirmModal(); 
        
        const originalText = btnLock.innerHTML;
        btnLock.innerHTML = LANG.locking;
        btnLock.disabled = true;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    [csrfTokenName]: csrfTokenHash
                },
                body: JSON.stringify({
                    jenis_sumatif: jenisSumatif,
                    status: 'terkunci',
                    mapel_id: ACTIVE_MAPEL_ID,
                    rombel_id: ACTIVE_ROMBEL_ID
                })
            });

            const result = await response.json();

            if (result.status === 'success') {
                currentStatus = "locked";
                updateProgressStatus();
                updateButtonStates();
                
                document.querySelectorAll('.input-nilai, .input-deskripsi').forEach(input => {
                    input.readOnly = true;
                    input.classList.add('bg-gray-100', 'dark:!bg-slate-700/50', 'cursor-not-allowed', 'text-gray-500', 'dark:!text-slate-500');
                    input.classList.remove('bg-white', 'dark:!bg-slate-700', 'text-gray-900', 'dark:!text-white');
                });

                showToast(LANG.succ_lock, "success");
                loadNilaiData();
            } else {
                showToast(result.message || LANG.err_lock, "error");
            }
        } catch (error) {
            console.error("Terjadi kesalahan:", error);
            showToast(LANG.err_server_lock, "error");
        } finally {
            btnLock.innerHTML = originalText;
        }
    };
}

function updateButtonStates() {
    const btnSaveDraft = document.getElementById('btnSaveDraft');
    const btnMarkReady = document.getElementById('btnMarkReady');
    const btnCancelReady = document.getElementById('btnCancelReady');
    const btnLock = document.getElementById('btnLock');

    if (currentStatus === 'draft' || currentStatus === '') {
        btnSaveDraft.disabled = false;
        btnMarkReady.classList.remove('hidden');
        btnMarkReady.classList.add('flex');
        btnCancelReady.classList.add('hidden');
        btnCancelReady.classList.remove('flex');
        btnLock.disabled = true;
    } 
    else if (currentStatus === 'ready' || currentStatus === 'siap_validasi') {
        btnSaveDraft.disabled = true;  
        btnMarkReady.classList.add('hidden');
        btnMarkReady.classList.remove('flex');
        btnCancelReady.classList.remove('hidden');
        btnCancelReady.classList.add('flex');
        btnLock.disabled = false;      
    } 
    else if (currentStatus === 'locked' || currentStatus === 'terkunci') {
        btnSaveDraft.disabled = true;
        btnMarkReady.classList.add('hidden');
        btnMarkReady.classList.remove('flex');
        btnCancelReady.classList.add('hidden');
        btnCancelReady.classList.remove('flex');
        btnLock.disabled = true;       
    }
}

async function cancelReady() {
    const btnCancel = document.getElementById('btnCancelReady');
    const url = btnCancel.getAttribute('data-url'); 
    const jenisSumatif = document.getElementById('jenisSumatif').value;
    
    document.getElementById("confirmMessage").innerHTML = LANG.warn_cancel_ready;
    document.getElementById("confirmModal").classList.remove("hidden");
    document.getElementById("confirmModal").classList.add("flex");
    document.body.style.overflow = "hidden";

    confirmCallback = async () => {
        closeConfirmModal();

        const originalText = btnCancel.innerHTML;
        btnCancel.innerHTML = LANG.processing;
        btnCancel.disabled = true;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    [csrfTokenName]: csrfTokenHash
                },
                body: JSON.stringify({
                    jenis_sumatif: jenisSumatif,
                    status: 'draft',
                    mapel_id: ACTIVE_MAPEL_ID,
                    rombel_id: ACTIVE_ROMBEL_ID
                })
            });

            const result = await response.json();

            if (result.status === 'success') {
                currentStatus = "draft";
                updateProgressStatus();
                updateButtonStates(); 
                showToast(LANG.succ_cancel, "success");
                loadNilaiData();
            } else {
                showToast(result.message || LANG.err_update_status, "error");
            }
        } catch (error) {
            console.error("Terjadi kesalahan:", error);
            showToast(LANG.err_server_conn, "error");
        } finally {
            btnCancel.innerHTML = originalText;
            btnCancel.disabled = false;
        }
    };
}

function closeConfirmModal() {
  document.getElementById("confirmModal").classList.add("hidden");
  document.getElementById("confirmModal").classList.remove("flex");
  document.body.style.overflow = "";
  confirmCallback = null;
}

function confirmAction() {
  if (confirmCallback) confirmCallback();
}

function showToast(message, type = 'success') {
  const toast = document.getElementById("toast");
  const toastMessage = document.getElementById("toastMessage");
  if(!toast || !toastMessage) return;

  toast.className = 'fixed top-4 right-4 z-[1000000] flex items-center gap-3 px-5 py-4 bg-white dark:!bg-slate-800 text-gray-800 dark:!text-white border-l-4 rounded-xl shadow-2xl transition-all duration-300 transform translate-x-full opacity-0';
  
  const iconDiv = toast.querySelector('div');
  const svg = toast.querySelector('svg');

  if (type === 'error') {
      toast.classList.add('border-rose-500');
      if(iconDiv) iconDiv.className = 'w-8 h-8 rounded-full bg-rose-100 dark:!bg-rose-900/30 flex items-center justify-center flex-shrink-0 transition-colors';
      if(svg) {
        svg.className = 'w-5 h-5 text-rose-600 dark:!text-rose-400';
        svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />';
      }
  } else {
      toast.classList.add('border-emerald-500');
      if(iconDiv) iconDiv.className = 'w-8 h-8 rounded-full bg-emerald-100 dark:!bg-emerald-900/30 flex items-center justify-center flex-shrink-0 transition-colors';
      if(svg) {
        svg.className = 'w-5 h-5 text-emerald-600 dark:!text-emerald-400';
        svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />';
      }
  }

  toastMessage.textContent = message;
  
  requestAnimationFrame(() => {
      toast.classList.remove('translate-x-full', 'opacity-0');
  });

  setTimeout(() => {
      toast.classList.add('translate-x-full', 'opacity-0');
  }, 3500);
}