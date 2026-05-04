let students = [];
let isLocked = false;
let confirmCallback = null;
let currentLM = null;

document.addEventListener("DOMContentLoaded", () => {
    updatePertemuanOptions();
    loadStudents();
});

function handleFilterChange() {
    updateClassesByYear(); 
}

async function updateClassesByYear() {
    const taEl = document.getElementById("tahunAjaran");
    const classSelect = document.getElementById("rombelMapelSelect");
    if (!taEl || !classSelect) return;

    const currentSelection = classSelect.value;
    classSelect.innerHTML = '<option value="">... Memuat Kelas ...</option>';
    classSelect.disabled = true;

    try {
        const response = await fetch(`${URL_GET_ASSIGNMENTS}?ta_id=${taEl.value}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const result = await response.json();

        classSelect.innerHTML = '';
        if (result.status === 'success' && result.data.length > 0) {
            result.data.forEach(item => {
                let opt = document.createElement('option');
                opt.value = `${item.rombel_id}|${item.mapel_id}`;
                opt.text = `${item.nama_kelas} - ${item.nama_mapel}`;
                
                opt.setAttribute('data-kelas', item.nama_kelas);
                opt.setAttribute('data-mapel', item.nama_mapel);
                opt.setAttribute('data-wali', item.wali_kelas || 'Belum Diset');
                
                classSelect.appendChild(opt);
            });
            
            if (currentSelection && result.data.some(d => `${d.rombel_id}|${d.mapel_id}` === currentSelection)) {
                classSelect.value = currentSelection;
            }
            classSelect.disabled = false;
        } else {
            classSelect.innerHTML = '<option value="">-- Tidak ada Kelas --</option>';
            classSelect.disabled = true;
        }
        
        handleClassChange();

    } catch (e) {
        console.error("Gagal load kelas:", e);
        classSelect.innerHTML = '<option value="">-- Error --</option>';
    }
}

function handleClassChange() {
    const classSelect = document.getElementById("rombelMapelSelect");
    if (!classSelect || !classSelect.value) {
        ACTIVE_ROMBEL_ID = 0;
        ACTIVE_MAPEL_ID = 0;
        updateInfoCard("-", "-", "-");
        checkAndLoadGrades();
        return;
    }

    const [rombelId, mapelId] = classSelect.value.split('|');
    ACTIVE_ROMBEL_ID = parseInt(rombelId);
    ACTIVE_MAPEL_ID = parseInt(mapelId);

    const selectedOpt = classSelect.options[classSelect.selectedIndex];
    const namaKelas = selectedOpt.getAttribute('data-kelas');
    const namaMapel = selectedOpt.getAttribute('data-mapel');
    const namaWali = selectedOpt.getAttribute('data-wali');

    updateInfoCard(namaMapel, namaKelas, namaWali);
    
    updatePertemuanOptions();
    loadStudents();
}

function updateInfoCard(mapel, kelas, wali) {
    const elMapel = document.getElementById("infoSubject");
    const elKelas = document.getElementById("infoClass");
    const elWali = document.getElementById("infoWaliKelas");

    if (elMapel) elMapel.textContent = mapel;
    if (elKelas) elKelas.textContent = kelas;
    if (elWali) elWali.textContent = wali;
}

async function updatePertemuanOptions() {
    const katEl = document.getElementById("kategoriFilter");
    const pertEl = document.getElementById("pertemuan");
    const taEl = document.getElementById("tahunAjaran");
    if (!katEl || !pertEl || !taEl || !ACTIVE_ROMBEL_ID) {
        pertEl.innerHTML = '<option value="">-- Pertemuan --</option>';
        return;
    }

    const currentVal = pertEl.value; 
    pertEl.innerHTML = '<option value="">... Memuat LM ...</option>';
    pertEl.disabled = true;

    try {
        const query = `?rombel_id=${ACTIVE_ROMBEL_ID}&mapel_id=${ACTIVE_MAPEL_ID}&kategori=${encodeURIComponent(katEl.value)}&ta_id=${taEl.value}`;
        const response = await fetch(`${URL_GET_JUMLAH_LM}${query}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const result = await response.json();

        pertEl.innerHTML = '';
        if (result.status === 'success' && result.pertemuan_list.length > 0) {
            pertEl.innerHTML = '<option value="">-- Pertemuan --</option>';
            result.pertemuan_list.forEach(item => {
                let opt = document.createElement('option');
                opt.value = item.pertemuan;
                
                // Potong teks materi jika lebih dari 40 karakter agar rapi
                let textMateri = item.materi;
                if (textMateri.length > 40) {
                    textMateri = textMateri.substring(0, 40) + '...';
                }
                
                opt.text = `Pertemuan ${item.pertemuan}: ${textMateri}`;
                opt.title = item.materi; // Teks lengkap akan muncul saat di-hover
                
                pertEl.appendChild(opt);
            });
            
            if (currentVal && result.pertemuan_list.some(i => i.pertemuan == currentVal)) {
                pertEl.value = currentVal;
            }
            pertEl.disabled = false;
        } else {
            pertEl.innerHTML = '<option value="">-- Tidak ada LM --</option>';
            pertEl.disabled = true;
        }
        
        if (pertEl.value) checkAndLoadGrades();
        else renderNilaiTable(true);

    } catch (e) {
        console.error("Gagal load jumlah LM:", e);
        pertEl.innerHTML = '<option value="">-- Error Load --</option>';
    }
}

async function loadStudents() {
    const taEl = document.getElementById("tahunAjaran");
    const taId = taEl ? taEl.value : "";

    try {
        const response = await fetch(`${URL_GET_STUDENTS}?rombel_id=${ACTIVE_ROMBEL_ID}&ta_id=${taId}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const result = await response.json();
        
        if (result.status === 'success') {
            students = result.data;
            renderNilaiTable(true);
        } else {
            showToast("Gagal memuat siswa: " + result.message, "error");
        }
    } catch (e) {
        showToast("Error koneksi server.", "error");
    }
}

async function checkAndLoadGrades() {
    const katEl = document.getElementById("kategoriFilter");
    const jenisEl = document.getElementById("jenisPenilaian");
    const pertemuanEl = document.getElementById("pertemuan");
    const taEl = document.getElementById("tahunAjaran");

    const kategori = katEl ? katEl.value : "Tengah Semester";
    const jenis = jenisEl ? jenisEl.value : "";
    const pertemuan = pertemuanEl ? pertemuanEl.value : "";
    const tahunAjaranId = taEl ? taEl.value : "";
    
    const semester = taEl && taEl.selectedIndex >= 0 ? taEl.options[taEl.selectedIndex].getAttribute("data-semester") : "Ganjil";

    const badge = document.getElementById("statusBadge");
    const toolbar = document.getElementById("actionToolbar");

    if (!jenis || !pertemuan || !tahunAjaranId) {
        renderNilaiTable(true); 
        if(toolbar) {
            toolbar.style.display = "none";
            toolbar.classList.add("hidden");
        }
        
        if(badge) {
            badge.textContent = "PILIH JENIS NILAI";
            badge.className = "inline-flex px-2 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all bg-gray-100 text-gray-500";
        }
        return;
    }

    const tbody = document.getElementById("nilaiTableBody");
    if(tbody) tbody.innerHTML = `<tr><td colspan="6" class="text-center py-10 font-bold text-gray-500">Mencari data nilai...</td></tr>`;

    try {
        const query = `?rombel_id=${ACTIVE_ROMBEL_ID}&mapel_id=${ACTIVE_MAPEL_ID}&kategori=${encodeURIComponent(kategori)}&jenis=${encodeURIComponent(jenis)}&pertemuan=${pertemuan}&semester=${encodeURIComponent(semester)}&tahun_ajaran_id=${tahunAjaranId}`;
        const response = await fetch(`${URL_GET_GRADES}${query}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const result = await response.json();

        if (result.status === 'success') {
            const grades = result.data;
            currentLM = result.lm; 
            
            if (!currentLM) {
                showToast("Master LM belum diset. Fitur Auto-Fill dimatikan sementara.", "warning");
            }

            isLocked = false;
            
            students.forEach(student => {
                const foundGrade = grades.find(g => g.siswa_id == student.id);
                if (foundGrade) {
                    student.nilai_tersimpan = foundGrade.nilai_angka;
                    student.catatan_tersimpan = foundGrade.catatan;
                    if (foundGrade.status_simpan === 'terkunci') isLocked = true;
                } else {
                    student.nilai_tersimpan = '';
                    student.catatan_tersimpan = '';
                }
            });

            renderNilaiTable(false); 
            
            if(toolbar) {
                toolbar.style.display = "block";
                toolbar.classList.remove("hidden");
            }
            
            const btnDraft = document.getElementById("btnSaveDraft");
            const btnLock = document.getElementById("btnSaveLock");
            const btnImport = document.getElementById("btnImportExcel");

            if (isLocked) {
                if(badge) {
                    badge.innerHTML = `TERKUNCI`;
                    badge.className = "inline-flex px-2 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all bg-red-100 text-red-700";
                }
                if(btnDraft) btnDraft.style.display = "none";
                if(btnLock) btnLock.style.display = "none";
                if(btnImport) btnImport.style.display = "none"; 
            } else {
                if(badge) {
                    badge.innerHTML = `DRAFT AKTIF`;
                    badge.className = "inline-flex px-2 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all bg-yellow-100 text-yellow-700";
                }
                if(btnDraft) btnDraft.style.display = "flex";
                if(btnLock) btnLock.style.display = "flex";
                if(btnImport) btnImport.style.display = "flex";
            }
        } else {
            showToast("Gagal memuat data: " + result.message, "error");
            renderNilaiTable(true);
        }
    } catch (e) {
        showToast("Error sistem. Silakan muat ulang halaman.", "error");
        renderNilaiTable(true);
    }
}

function validateMaxValue(el) {
    if (el.value !== "") {
        let val = parseInt(el.value);
        if (val > 100) el.value = 100;
        if (val < 0) el.value = 0;
    }
}

function renderNilaiTable(isDisabled) {
    const tbody = document.getElementById("nilaiTableBody");
    if (!tbody) return;

    const kkmEl = document.getElementById("kkm");
    const kkm = kkmEl && kkmEl.value ? parseInt(kkmEl.value) : 75;
    tbody.innerHTML = "";

    let completed = 0;

    students.forEach((student, index) => {
        const nilai = student.nilai_tersimpan !== undefined && student.nilai_tersimpan !== null ? student.nilai_tersimpan : ""; 
        const catatan = student.catatan_tersimpan || "";
        const predikat = getPredikat(nilai, kkm);

        if (nilai !== "") completed++;

        let placeholder = "";
        if (isDisabled) {
            placeholder = "Pilih Jenis & Pertemuan";
        } else if (currentLM) {
            placeholder = "Keterangan otomatis...";
        } else {
            placeholder = "Ketik manual (Materi LM belum diset Admin)";
        }

        const lockState = (isDisabled || isLocked) ? "disabled" : "";
        const bgLock = (isDisabled || isLocked) ? "!bg-gray-100 dark:!bg-slate-900/60 !cursor-not-allowed !opacity-60" : "!bg-white dark:!bg-slate-700";

        const row = document.createElement("tr");
        row.className = "transition-colors !bg-white dark:!bg-slate-800 hover:!bg-gray-50 dark:hover:!bg-slate-700/50 border-b !border-gray-100 dark:!border-slate-700/50 last:border-0";
        
        row.innerHTML = `
            <td class="px-6 py-4 font-bold text-gray-500 dark:!text-slate-400 text-center">${index + 1}</td>
            <td class="px-6 py-4 font-bold text-gray-900 dark:!text-white">${student.name}</td>
            <td class="px-6 py-4 font-mono text-xs text-gray-500 dark:!text-slate-400 font-black tracking-wider">${student.nis}</td>
            <td class="px-6 py-4 text-center">
                <input type="number" id="nilai-${student.id}"
                class="w-24 px-4 py-2 border-2 !border-gray-200 dark:!border-slate-600 ${bgLock} text-gray-900 dark:!text-white rounded-xl transition-all font-black text-center outline-none focus:border-[var(--warna-primary)] disabled:!bg-gray-100 disabled:dark:!bg-slate-900/60 disabled:!cursor-not-allowed disabled:!opacity-50" 
                value="${nilai}" min="0" max="100" placeholder="${isDisabled ? '-' : '0'}"
                oninput="validateMaxValue(this); evaluateKkmColor('nilai-${student.id}'); updatePredikat(${student.id}, true); triggerAutoSave();"
                ${lockState}>
            </td>
            <td class="px-6 py-4 text-center">
                <div class="inline-flex px-3 py-1.5 rounded-xl text-sm font-black shadow-sm transition-all ${getPredikatClass(predikat)}" id="predikat-${student.id}">
                ${predikat || "-"}
                </div>
            </td>
            <td class="px-6 py-4">
                <input type="text" id="keterangan-${student.id}"
                class="w-full px-4 py-2 ${bgLock} border !border-gray-200 dark:!border-slate-600 text-gray-800 dark:!text-slate-300 rounded-xl outline-none focus:border-gray-400 dark:focus:border-slate-400 transition-all text-xs font-medium disabled:!bg-gray-100 disabled:dark:!bg-slate-900/60 disabled:!cursor-not-allowed disabled:!opacity-50" 
                value="${catatan}" placeholder="${placeholder}"
                onchange="triggerAutoSave()" ${lockState}>
            </td>
        `;
        tbody.appendChild(row);
    });

    updateProgressUI(completed);
    refreshKkmColors();
}

function updateProgressUI(completedCount) {
    const progText = document.getElementById("progressText");
    const progFill = document.getElementById("progressFill");
    
    if (progText) progText.textContent = `${completedCount}/${students.length} siswa`;
    if (progFill && students.length > 0) {
        const percentage = (completedCount / students.length) * 100;
        progFill.style.width = percentage + "%";
    }
}

function getPredikat(nilai, kkm) {
    if (nilai === null || nilai === undefined || nilai === "") return "";
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

function updatePredikat(studentId, isManualInput = false) {
    const kkmEl = document.getElementById("kkm");
    const kkm = kkmEl && kkmEl.value ? parseInt(kkmEl.value) : 75;
    
    const input = document.getElementById(`nilai-${studentId}`);
    const badge = document.getElementById(`predikat-${studentId}`);
    const catatan = document.getElementById(`keterangan-${studentId}`);
    
    if (!input || !badge) return;

    const predikat = getPredikat(input.value, kkm);

    badge.textContent = predikat || "-";
    badge.className = "inline-flex px-3 py-1.5 rounded-xl text-sm font-black shadow-sm transition-all " + getPredikatClass(predikat);
    
    if (isManualInput && catatan) {
        if (predikat && currentLM) {
            const key = 'deskripsi_' + predikat.toLowerCase(); 
            
            if (currentLM[key] && String(currentLM[key]).trim() !== "") {
                catatan.value = currentLM[key];
            } else if (currentLM['deskripsi_lm'] && String(currentLM['deskripsi_lm']).trim() !== "") {
                const materi = currentLM['deskripsi_lm'];
                if (predikat === 'A') catatan.value = "Sangat baik memahami " + materi;
                else if (predikat === 'B') catatan.value = "Baik memahami " + materi;
                else if (predikat === 'C') catatan.value = "Cukup memahami " + materi;
                else catatan.value = "Perlu bimbingan memahami " + materi;
            }
        } else if (!predikat) {
            catatan.value = ""; 
        }
    }
    
    let completed = 0;
    students.forEach(s => { 
        const el = document.getElementById(`nilai-${s.id}`);
        if (el && el.value !== "") completed++; 
    });

    updateProgressUI(completed);
}

function evaluateKkmColor(inputId) {
    const kkmEl = document.getElementById("kkm");
    const kkm = kkmEl && kkmEl.value ? parseInt(kkmEl.value) : 75;
    
    const inputEl = document.getElementById(inputId);
    if (!inputEl) return;

    const val = inputEl.value;
    
    inputEl.classList.remove(
        'text-emerald-700', 'dark:!text-emerald-400', 'border-emerald-500', 'dark:!border-emerald-500', 'bg-emerald-50', 'dark:!bg-emerald-900/30',
        'text-red-700', 'dark:!text-red-400', 'border-red-500', 'dark:!border-red-500', 'bg-red-50', 'dark:!bg-red-900/30'
    );

    if (val !== "" && parseFloat(val) >= kkm) {
        inputEl.classList.add('text-emerald-700', 'dark:!text-emerald-400', 'border-emerald-500', 'dark:!border-emerald-500', 'bg-emerald-50', 'dark:!bg-emerald-900/30');
    } else if (val !== "" && parseFloat(val) < kkm) {
        inputEl.classList.add('text-red-700', 'dark:!text-red-400', 'border-red-500', 'dark:!border-red-500', 'bg-red-50', 'dark:!bg-red-900/30');
    }
}

function refreshKkmColors() {
    students.forEach(s => evaluateKkmColor(`nilai-${s.id}`));
}

// ==============================================================
// KUNCI: AMBIL TOKEN CSRF & HEADER YANG VALID UNTUK JSON PAYLOAD
// ==============================================================
function getSafeHeaders() {
    let headers = {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    };
    try {
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfHeader = document.querySelector('meta[name="csrf-header"]');
        if (csrfMeta && csrfHeader) {
            headers[csrfHeader.getAttribute('content')] = csrfMeta.getAttribute('content');
        } else {
            headers[csrfTokenName] = csrfTokenHash;
        }
    } catch(e) {
        headers[csrfTokenName] = csrfTokenHash;
    }
    return headers;
}

let autoSaveTimer;
function triggerAutoSave() {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(() => { autoSaveDraftSilent(); }, 1500); 
}

async function autoSaveDraftSilent(forceSave = false) {
    const katEl = document.getElementById("kategoriFilter");
    const jenisEl = document.getElementById("jenisPenilaian");
    const pertemuanEl = document.getElementById("pertemuan");
    const taEl = document.getElementById("tahunAjaran");

    if (!jenisEl || !pertemuanEl || !taEl) return;

    const kategori = katEl ? katEl.value : "Tengah Semester";
    const jenis = jenisEl.value;
    const pertemuan = pertemuanEl.value;
    const semester = taEl && taEl.selectedIndex >= 0 ? taEl.options[taEl.selectedIndex].getAttribute("data-semester") : "Ganjil";
    
    if (!jenis || !pertemuan || isLocked) return;

    let payloadData = {};
    let hasData = false;
    const kkmEl = document.getElementById("kkm");
    const kkm = kkmEl && kkmEl.value ? parseInt(kkmEl.value) : 75;

    students.forEach((s) => {
        const input = document.getElementById(`nilai-${s.id}`);
        const catatan = document.getElementById(`keterangan-${s.id}`);
        if (input) {
            payloadData[s.id] = {
                nilai: input.value,
                predikat: getPredikat(input.value, kkm),
                keterangan: catatan ? catatan.value : ""
            };
            if (input.value !== "") hasData = true;
        }
    });

    if (!hasData && !forceSave) return; 

    // KUNCI: MENGIRIM PAYLOAD SESUAI FORMAT YANG DITANGKAP $json = $this->request->getJSON();
    const payloadObject = {
        rombel_id: ACTIVE_ROMBEL_ID, 
        mapel_id: ACTIVE_MAPEL_ID,
        kategori: kategori, 
        jenis_penilaian: jenis, 
        pertemuan: pertemuan,
        semester: semester,
        tahun_ajaran_id: taEl.value, 
        nilaiData: payloadData, 
        status_simpan: 'draft' 
    };

    // Sisipkan juga CSRF token ke dalam payload sebagai fallback (untuk CodeIgniter 4)
    payloadObject[csrfTokenName] = csrfTokenHash;

    try {
        const res = await fetch(URL_SAVE_DATA, {
            method: 'POST',
            headers: getSafeHeaders(),
            body: JSON.stringify(payloadObject)
        });
        const result = await res.json();
        
        if (result.status === 'success') {
            if(!forceSave) showToast("⏳ Disimpan otomatis", "success");
            
            const badge = document.getElementById("statusBadge");
            if (badge && !isLocked) {
                badge.innerHTML = `DRAFT AKTIF`;
                badge.className = "inline-flex px-2 py-0.5 rounded-lg text-[10px] font-black uppercase tracking-wider transition-all bg-yellow-100 text-yellow-700";
            }
        } else {
            console.error("Auto save gagal:", result.message);
            if(forceSave) showToast("Gagal menyimpan: " + result.message, "error");
        }
    } catch (e) {
        console.error("Auto save error:", e);
        if(forceSave) showToast("Koneksi bermasalah saat menyimpan.", "error");
    }
}

function saveDraft() {
    showToast("Menyimpan draft...", "success");
    autoSaveDraftSilent(true);
}

function saveLock() {
    const katEl = document.getElementById("kategoriFilter");
    const jenisEl = document.getElementById("jenisPenilaian");
    const pertemuanEl = document.getElementById("pertemuan");
    const taEl = document.getElementById("tahunAjaran");
    
    if (!jenisEl || !pertemuanEl) return;

    const kategori = katEl ? katEl.value : "Tengah Semester";
    const jenis = jenisEl.value;
    const pertemuan = pertemuanEl.value;
    const semester = taEl && taEl.selectedIndex >= 0 ? taEl.options[taEl.selectedIndex].getAttribute("data-semester") : "Ganjil";
    
    let hasData = false;
    students.forEach(s => {
        const input = document.getElementById(`nilai-${s.id}`);
        if (input && input.value !== "") hasData = true;
    });

    if (!hasData) { showToast("Tidak ada nilai yang diinput!", "error"); return; }

    confirmCallback = async () => {
        closeConfirmModal();
        const btn = document.getElementById("btnSaveLock");
        if (!btn) return;
        
        const original = btn.innerHTML;
        btn.innerHTML = "MEMPROSES...";
        btn.disabled = true;

        let payloadData = {};
        const kkmEl = document.getElementById("kkm");
        const kkm = kkmEl && kkmEl.value ? parseInt(kkmEl.value) : 75;

        students.forEach((s) => {
            const input = document.getElementById(`nilai-${s.id}`);
            const catatan = document.getElementById(`keterangan-${s.id}`);
            if (input && input.value !== "") {
                payloadData[s.id] = {
                    nilai: input.value,
                    predikat: getPredikat(input.value, kkm),
                    keterangan: catatan ? catatan.value : ""
                };
            }
        });

        const payloadObject = {
            rombel_id: ACTIVE_ROMBEL_ID, 
            mapel_id: ACTIVE_MAPEL_ID,
            kategori: kategori, 
            jenis_penilaian: jenis, 
            pertemuan: pertemuan,
            semester: semester,
            tahun_ajaran_id: taEl ? taEl.value : "", 
            nilaiData: payloadData, 
            status_simpan: 'terkunci' 
        };
        payloadObject[csrfTokenName] = csrfTokenHash;

        try {
            const res = await fetch(URL_SAVE_DATA, {
                method: 'POST',
                headers: getSafeHeaders(),
                body: JSON.stringify(payloadObject)
            });
            const result = await res.json();
            if (result.status === 'success') {
                showToast("✓ Berhasil Terkunci", "success");
                checkAndLoadGrades(); 
            } else {
                showToast("Gagal: " + result.message, "error");
            }
        } catch (e) {
            showToast("Error sistem", "error");
        } finally {
            btn.innerHTML = original;
            btn.disabled = false;
        }
    };
    openConfirmModal(`Anda yakin ingin MENGUNCI data ${jenis} Ke-${pertemuan}? Data yang dikunci tidak bisa diubah!`);
}

function resetAllNilai() {
    confirmCallback = () => {
        students.forEach(s => {
            const input = document.getElementById(`nilai-${s.id}`);
            const catatan = document.getElementById(`keterangan-${s.id}`);
            if (input) input.value = "";
            if (catatan) catatan.value = "";
            updatePredikat(s.id, true); 
        });
        updateProgressUI(0);
        showToast("✓ Form berhasil dibersihkan", "success");
        autoSaveDraftSilent(true); 
        closeConfirmModal();
    };
    openConfirmModal("Yakin ingin mereset seluruh nilai di form ini? Sistem akan langsung menyimpannya sebagai kosong di database.");
}

function openConfirmModal(msg) {
    const msgEl = document.getElementById("confirmMessage");
    const modal = document.getElementById("confirmModal");
    if (msgEl) msgEl.textContent = msg;
    if (modal) {
        modal.classList.remove("hidden");
        modal.classList.add("flex"); 
    }
}

function closeConfirmModal() {
    const modal = document.getElementById("confirmModal");
    if (modal) {
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    }
    confirmCallback = null;
}

function confirmAction() { 
    if (typeof confirmCallback === 'function') {
        confirmCallback(); 
    }
}

function showToast(msg, type = "success") {
    const toast = document.getElementById("toast");
    const msgEl = document.getElementById("toastMessage");
    const icon = document.getElementById("toastIcon");
    
    if(!toast || !msgEl) return;

    msgEl.textContent = msg;
    const iconBg = icon ? icon.parentElement : null;

    toast.className = "fixed top-4 right-4 z-[1000000] flex items-center gap-3 px-4 py-3 bg-white dark:!bg-slate-800 text-gray-800 dark:!text-white border-l-4 rounded-xl shadow-2xl transition-all duration-300 transform translate-x-full opacity-0";
    
    if (iconBg) iconBg.className = "w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors";
    if (icon) icon.className = "w-5 h-5 transition-colors";

    if (type === "error") {
        toast.classList.add("border-red-500");
        if (iconBg) iconBg.classList.add("bg-red-100", "dark:!bg-red-900/30");
        if (icon) {
            icon.classList.add("text-red-600", "dark:!text-red-400");
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />';
        }
    } else {
        toast.classList.add("border-emerald-500");
        if (iconBg) iconBg.classList.add("bg-emerald-100", "dark:!bg-emerald-900/30");
        if (icon) {
            icon.classList.add("text-emerald-600", "dark:!text-emerald-400");
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />';
        }
    }
    
    setTimeout(() => {
        toast.classList.remove("translate-x-full", "opacity-0");
    }, 10);
    
    if (window.toastTimer) clearTimeout(window.toastTimer);
    window.toastTimer = setTimeout(() => { 
        toast.classList.add("translate-x-full", "opacity-0"); 
    }, 3000);
}

function switchImportTab(tabName) {
    const tabSingle = document.getElementById("tabSingle");
    const tabGlobal = document.getElementById("tabGlobal");
    const contentSingle = document.getElementById("contentSingle");
    const contentGlobal = document.getElementById("contentGlobal");

    if (tabName === 'single') {
        tabSingle.className = "flex-1 py-4 text-blue-600 border-b-2 border-blue-600 dark:text-blue-400 dark:border-blue-400 transition-colors outline-none";
        tabGlobal.className = "flex-1 py-4 text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-slate-200 border-b-2 border-transparent transition-colors outline-none";
        contentSingle.style.display = "block";
        contentGlobal.style.display = "none";
    } else {
        tabGlobal.className = "flex-1 py-4 text-indigo-600 border-b-2 border-indigo-600 dark:text-indigo-400 dark:border-indigo-400 transition-colors outline-none";
        tabSingle.className = "flex-1 py-4 text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-slate-200 border-b-2 border-transparent transition-colors outline-none";
        contentGlobal.style.display = "block";
        contentSingle.style.display = "none";
    }
}

function openImportModal() {
    const jenis = document.getElementById("jenisPenilaian").value;
    
    if (!jenis) {
        showToast("Pilih Jenis Penilaian terlebih dahulu sebelum import!", "error");
        return;
    }
    
    if (isLocked) {
        showToast("Data sudah dikunci. Buka kunci ke Admin jika ingin Import ulang.", "error");
        return;
    }

    const modal = document.getElementById("importModal");
    if(modal) {
        modal.classList.remove("hidden");
        modal.classList.add("flex");
        switchImportTab('single');
    }
}

function closeImportModal() {
    const modal = document.getElementById("importModal");
    if(modal) {
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    }
}

function downloadTemplateExcel() {
    const katEl = document.getElementById("kategoriFilter");
    const jenis = document.getElementById("jenisPenilaian").value;
    const pertemuan = document.getElementById("pertemuan").value;
    
    if(!pertemuan) {
        showToast("Pilih Pertemuan Ke berapa untuk mendownload template Single", "error");
        return;
    }
    
    const kategori = katEl ? katEl.value : "Tengah Semester";
    const url = `${URL_DOWNLOAD_TEMPLATE}?rombel_id=${ACTIVE_ROMBEL_ID}&mapel_id=${ACTIVE_MAPEL_ID}&kategori=${encodeURIComponent(kategori)}&jenis=${encodeURIComponent(jenis)}&pertemuan=${pertemuan}`;
    window.location.href = url;
}

function downloadTemplateAllExcel() {
    const katEl = document.getElementById("kategoriFilter");
    const jenis = document.getElementById("jenisPenilaian").value;
    const taEl = document.getElementById("tahunAjaran");
    const kategori = katEl ? katEl.value : "Tengah Semester";
    const ta_id = taEl ? taEl.value : "";

    const url = `${URL_DOWNLOAD_TEMPLATE_ALL}?rombel_id=${ACTIVE_ROMBEL_ID}&mapel_id=${ACTIVE_MAPEL_ID}&kategori=${encodeURIComponent(kategori)}&jenis=${encodeURIComponent(jenis)}&ta_id=${ta_id}`;
    window.location.href = url;
}

async function submitImport(e) {
    e.preventDefault();
    const form = e.target;
    const fileInput = form.querySelector('input[type="file"]');
    
    if (!fileInput.files.length) {
        showToast("Pilih file excel terlebih dahulu!", "error");
        return;
    }
    
    const pertemuan = document.getElementById("pertemuan").value;
    if(!pertemuan) {
        showToast("Anda belum memilih Pertemuan Ke berapa di layar utama!", "error");
        return;
    }

    const btn = document.getElementById("btnSubmitImport");
    const originalText = btn.innerHTML;
    btn.innerHTML = "Memproses Upload...";
    btn.disabled = true;

    const katEl = document.getElementById("kategoriFilter");
    const taEl = document.getElementById("tahunAjaran");
    const kkmEl = document.getElementById("kkm");
    
    const kkm = kkmEl && kkmEl.value ? kkmEl.value : "75";
    const semester = taEl && taEl.selectedIndex >= 0 ? taEl.options[taEl.selectedIndex].getAttribute("data-semester") : "Ganjil";

    const formData = new FormData();
    formData.append('rombel_id', ACTIVE_ROMBEL_ID);
    formData.append('mapel_id', ACTIVE_MAPEL_ID);
    formData.append('kategori', katEl ? katEl.value : "Tengah Semester");
    formData.append('jenis_penilaian', document.getElementById("jenisPenilaian").value);
    formData.append('pertemuan', pertemuan);
    formData.append('semester', semester);
    formData.append('tahun_ajaran_id', taEl ? taEl.value : ""); 
    formData.append('kkm', kkm); 
    formData.append('file_excel', fileInput.files[0]);
    formData.append(csrfTokenName, csrfTokenHash);

    try {
        const response = await fetch(URL_IMPORT_EXCEL, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        });
        const result = await response.json();
        
        if (result.status === 'success') {
            showToast(result.message, "success");
            closeImportModal();
            checkAndLoadGrades(); 
        } else {
            showToast(result.message, "error");
        }
    } catch (err) {
        showToast("Gagal melakukan import data.", "error");
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
        form.reset();
    }
}

async function submitImportAll(e) {
    e.preventDefault();
    const form = e.target;
    const fileInput = form.querySelector('input[type="file"]');
    
    if (!fileInput.files.length) {
        showToast("Pilih file excel terlebih dahulu!", "error");
        return;
    }

    const btn = document.getElementById("btnSubmitImportAll");
    const originalText = btn.innerHTML;
    btn.innerHTML = "Memproses Global Upload...";
    btn.disabled = true;

    const katEl = document.getElementById("kategoriFilter");
    const taEl = document.getElementById("tahunAjaran");
    const kkmEl = document.getElementById("kkm");
    
    const kkm = kkmEl && kkmEl.value ? kkmEl.value : "75";
    const semester = taEl && taEl.selectedIndex >= 0 ? taEl.options[taEl.selectedIndex].getAttribute("data-semester") : "Ganjil";

    const formData = new FormData();
    formData.append('rombel_id', ACTIVE_ROMBEL_ID);
    formData.append('mapel_id', ACTIVE_MAPEL_ID);
    formData.append('kategori', katEl ? katEl.value : "Tengah Semester");
    formData.append('jenis_penilaian', document.getElementById("jenisPenilaian").value);
    formData.append('semester', semester);
    formData.append('tahun_ajaran_id', taEl ? taEl.value : ""); 
    formData.append('kkm', kkm); 
    formData.append('file_excel_all', fileInput.files[0]);
    formData.append(csrfTokenName, csrfTokenHash);

    try {
        const response = await fetch(URL_IMPORT_EXCEL_ALL, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        });
        const result = await response.json();
        
        if (result.status === 'success') {
            showToast(result.message, "success");
            closeImportModal();
            if(document.getElementById("pertemuan").value !== "") {
                checkAndLoadGrades(); 
            }
        } else {
            showToast(result.message, "error");
        }
    } catch (err) {
        showToast("Gagal melakukan import data global.", "error");
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
        form.reset();
    }
}