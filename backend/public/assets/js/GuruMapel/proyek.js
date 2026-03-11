// ==========================================
// SABUK PENGAMAN (FALLBACK)
// ==========================================
const LANG = window.LANG || {
    no_project_title: "Belum Ada Proyek", no_project_desc: "Silakan klik 'Buat Proyek Baru' untuk memulai.", no_desc: "Tidak ada deskripsi.",
    individual: "Proyek Individu", group: "Proyek Kelompok", draft: "Draft", loading_rubric: "Memuat rubrik...",
    err_get_rubric: "⚠️ Gagal mengambil data rubrik!", saving: "Menyimpan...", err_field_req: "⚠️ Lengkapi field yang wajib diisi!",
    succ_saved: "✓ Berhasil disimpan", fail_prefix: "⚠️ Gagal: ", err_conn: "⚠️ Kesalahan koneksi server!",
    save_btn: "Simpan & Lanjutkan", aspect_label: "Aspek Penilaian", weight_label: "Bobot (%)", new_aspect: "Aspek Baru",
    del_aspect_conf: "Hapus aspek ini?", err_del_aspect: "⚠️ Gagal menghapus aspek!", save_rubric: "Simpan Rubrik",
    succ_rubric: "✓ Rubrik berhasil disimpan!", err_server: "⚠️ Terjadi kesalahan server",
    not_found: "Siswa tidak ditemukan (atau sudah ditambah)", add_btn: "+ Tambah",
    kick_conf: "Keluarkan siswa ini dan hapus nilainya permanen?", succ_kick: "✓ Siswa dikeluarkan.",
    err_kick: "⚠️ Gagal mengeluarkan siswa.", succ_add_stu: " berhasil ditambahkan!", succ_save_stu: " berhasil disimpan!",
    err_save_stu: "⚠️ Gagal menyimpan: ", err_server_html: "⚠️ Gagal! Cek Console (F12) untuk detail error.",
    err_server_conn: "⚠️ Terjadi kesalahan saat menghubungi server!", ph_notes: "Ketik catatan opsional...",
    btn_save_grade: "Simpan Nilai Siswa", btn_kick_group: "Keluarkan dari kelompok", err_load_grade: "Gagal memuat nilai siswa"
};

let listProyek = [];
let proyekAktif = null; 
let rubrikItems = [];

let allSiswaDB = []; 
let siswaRendered = []; 

document.addEventListener('DOMContentLoaded', () => {
    if (typeof initialProyekList !== 'undefined' && Array.isArray(initialProyekList) && initialProyekList.length > 0) {
        listProyek = initialProyekList;
        renderProjectList();
    } else {
        document.getElementById("projectListContainer").innerHTML = `
            <div class="col-span-full text-center py-12 bg-white rounded-xl border border-gray-200 border-dashed">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-700 mb-2">${LANG.no_project_title}</h3>
                <p class="text-gray-500 mb-6">${LANG.no_project_desc}</p>
            </div>
        `;
    }
    fetchMasterSiswa();
});

function renderProjectList() {
    const container = document.getElementById("projectListContainer");
    container.innerHTML = "";

    listProyek.forEach((p) => {
        const tanggal = new Date(p.tanggal_pelaksanaan).toLocaleDateString("id-ID", {day: 'numeric', month: 'long', year: 'numeric'});
        const isActive = proyekAktif && parseInt(proyekAktif.id) === parseInt(p.id);
        const ringClass = isActive ? "ring-2 ring-[var(--warna-primary)] shadow-md bg-blue-50" : "border border-gray-200 hover:border-[var(--warna-primary)]/50 bg-white";
        
        container.innerHTML += `
            <div class="rounded-2xl p-5 transition-all cursor-pointer flex flex-col justify-between h-full ${ringClass}" onclick="pilihProyek(${p.id})">
                <div>
                    <div class="flex justify-between items-start mb-3 gap-2">
                        <h3 class="font-black text-lg text-gray-800 leading-tight">${p.nama_proyek}</h3>
                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-[0.65rem] font-black uppercase tracking-wider rounded-md border border-blue-200 flex-shrink-0">${p.jenis}</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-4 line-clamp-2">${p.deskripsi || LANG.no_desc}</p>
                </div>
                <div class="bg-white/50 border border-gray-100 p-3 rounded-lg mt-auto">
                    <div class="flex justify-between items-center text-sm">
                        <div class="text-gray-600"><span class="font-bold text-gray-800">KKM:</span> ${p.kkm}</div>
                        <div class="text-gray-600 font-medium"><svg class="w-4 h-4 inline-block mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>${tanggal}</div>
                    </div>
                </div>
            </div>
        `;
    });
}

async function pilihProyek(id) {
    const p = listProyek.find(x => parseInt(x.id) === parseInt(id));
    if(!p) return;

    proyekAktif = p;
    renderProjectList();

    document.getElementById("jenisNilai").textContent = p.jenis === "Individu" ? LANG.individual : LANG.group;
    document.getElementById("statusBadge").textContent = p.status || LANG.draft;
    
    document.getElementById("dividerLine").style.display = "block";
    document.getElementById("rubrikCard").style.display = "block";
    document.getElementById("namaProyekAktif").textContent = p.nama_proyek;
    document.getElementById("nilaiTableCard").style.display = "none"; 

    rubrikItems = [];
    document.getElementById("rubrikContainer").innerHTML = `<p class="text-gray-400 text-sm italic py-2 text-center">${LANG.loading_rubric}</p>`;

    try {
        const response = await fetch(`${BASE_URL}/guru/proyek/getRubrik/${p.id}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" } 
        });
        const result = await response.json();

        if (result.status === 'success' && result.data.length > 0) {
            rubrikItems = result.data.map(item => ({
                id: parseInt(item.id),
                aspek: item.nama_aspek,
                bobot: parseInt(item.bobot)
            }));
            
            const maxId = Math.max(...rubrikItems.map(r => r.id));
            rubrikIdCounter = maxId + 1;

            renderRubrik();
            updateTotalBobot();

            if (rubrikItems.reduce((sum, item) => sum + item.bobot, 0) === 100) {
                document.getElementById("nilaiTableCard").style.display = "block";
                await loadNilaiSiswaDariDB(); 
            }
        } else {
            rubrikIdCounter = 4;
            rubrikItems = [
                { id: 1, aspek: "Pemahaman Materi", bobot: 30 },
                { id: 2, aspek: "Proses & Kreativitas", bobot: 30 },
                { id: 3, aspek: "Kerapian / Teknik", bobot: 40 },
            ];
            renderRubrik();
            updateTotalBobot();
        }
    } catch (error) {
        showToast(LANG.err_get_rubric);
    }
    document.getElementById("rubrikCard").scrollIntoView({ behavior: "smooth", block: "start" });
}

function openSetupModal() {
    const modal = document.getElementById("setupModal");
    modal.classList.remove("hidden");
    modal.classList.add("flex"); 
    document.body.style.overflow = "hidden"; 
    document.getElementById("namaProyek").value = "";
    document.getElementById("deskripsiProyek").value = "";
}

function closeSetupModal() {
    const modal = document.getElementById("setupModal");
    modal.classList.add("hidden");
    modal.classList.remove("flex");
    document.body.style.overflow = "";
}

async function simpanSetupProyek() {
    const nama = document.getElementById("namaProyek").value;
    const jenis = document.querySelector('input[name="jenis"]:checked').value;
    const tanggal = document.getElementById("tanggalProyek").value;
    const kkm = document.getElementById("kkmProyek").value;
    const deskripsi = document.getElementById("deskripsiProyek").value;

    if (!nama || !tanggal) return showToast(LANG.err_field_req);

    const btnSubmit = document.querySelector('#setupModal button[onclick="simpanSetupProyek()"]');
    let originalBtnContent = LANG.save_btn;
    
    if (btnSubmit) {
        originalBtnContent = btnSubmit.innerHTML;
        btnSubmit.innerHTML = `<svg class="w-5 h-5 animate-spin inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> ${LANG.saving}`;
        btnSubmit.disabled = true;
        btnSubmit.classList.add("opacity-70", "cursor-wait");
    }

    const formData = new FormData();
    formData.append('nama', nama);
    formData.append('jenis', jenis);
    formData.append('tanggal', tanggal);
    formData.append('kkm', kkm);
    formData.append('deskripsi', deskripsi);
    formData.append('mapel_id', ACTIVE_MAPEL_ID);
    formData.append('rombel_id', ACTIVE_ROMBEL_ID);
    formData.append(csrfTokenName, csrfTokenHash);

    try {
        const response = await fetch(`${BASE_URL}/guru/proyek/simpanProyek`, { 
            method: 'POST', 
            body: formData,
            headers: { "X-Requested-With": "XMLHttpRequest" } 
        });
        const result = await response.json();
        
        if (result.status === 'success') {
            listProyek.push(result.data);
            renderProjectList();
            closeSetupModal();
            showToast(`✓ ${result.message}`);
            pilihProyek(result.data.id);
        } else {
            showToast(`${LANG.fail_prefix}${result.message}`);
        }
    } catch (e) { 
        showToast(LANG.err_conn); 
    } finally { 
        if (btnSubmit) {
            btnSubmit.innerHTML = originalBtnContent; 
            btnSubmit.disabled = false;
            btnSubmit.classList.remove("opacity-70", "cursor-wait");
        }
    }
}

let rubrikIdCounter = 4;

function renderRubrik() {
    const container = document.getElementById("rubrikContainer");
    container.innerHTML = "";
    rubrikItems.forEach((item) => {
        const div = document.createElement("div");
        div.className = "bg-white p-4 rounded-xl border border-gray-200 shadow-sm";
        div.innerHTML = `
            <div class="flex items-center gap-4">
                <div class="flex-1">
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">${LANG.aspect_label}</label>
                    <input type="text" value="${item.aspek}" onchange="updateAspek(${item.id}, this.value)" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--warna-primary)] outline-none font-bold text-gray-800">
                </div>
                <div style="width: 120px;">
                    <label class="block text-[10px] font-black text-gray-500 mb-1 uppercase tracking-wider">${LANG.weight_label}</label>
                    <input type="number" value="${item.bobot}" min="0" max="100" onchange="updateBobot(${item.id}, this.value)" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[var(--warna-primary)] outline-none font-bold text-center text-[var(--warna-primary)]">
                </div>
                <div class="flex items-end gap-2" style="padding-bottom: 0.2rem;">
                    ${rubrikItems.length > 1 ? `
                        <button onclick="hapusAspek(${item.id})" class="p-2.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors mt-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>` : ""}
                </div>
            </div>`;
        container.appendChild(div);
    });
}

function tambahAspekRubrik() { rubrikItems.push({ id: rubrikIdCounter++, aspek: LANG.new_aspect, bobot: 0 }); renderRubrik(); updateTotalBobot(); }
function updateAspek(id, value) { const item = rubrikItems.find((r) => parseInt(r.id) === parseInt(id)); if (item) item.aspek = value; }
function updateBobot(id, value) { const item = rubrikItems.find((r) => parseInt(r.id) === parseInt(id)); if (item) item.bobot = parseInt(value) || 0; updateTotalBobot(); }

async function hapusAspek(id) {
    if (!confirm(LANG.del_aspect_conf)) return;
    try {
        await fetch(`${BASE_URL}/guru/proyek/deleteRubrik/${id}`, {
            method: 'POST', headers: { "X-Requested-With": "XMLHttpRequest", [csrfTokenName]: csrfTokenHash }
        });
        rubrikItems = rubrikItems.filter((r) => parseInt(r.id) !== parseInt(id));
        renderRubrik(); updateTotalBobot();
    } catch (e) { showToast(LANG.err_del_aspect); }
}

function updateTotalBobot() {
    const total = rubrikItems.reduce((sum, item) => sum + item.bobot, 0);
    const totalElement = document.getElementById("totalBobot");
    const btnSimpan = document.getElementById("btnSimpanRubrik"); 
    
    totalElement.textContent = `${total}%`;
    document.getElementById("nilaiTableCard").style.display = "none"; 

    if (total === 100) {
        totalElement.style.color = "#065F46"; 
        if(btnSimpan) { btnSimpan.disabled = false; btnSimpan.classList.remove("opacity-50", "cursor-not-allowed"); }
    } else {
        totalElement.style.color = total > 100 ? "#991B1B" : "#F59E0B"; 
        if(btnSimpan) { btnSimpan.disabled = true; btnSimpan.classList.add("opacity-50", "cursor-not-allowed"); }
    }
}

async function saveRubrikToDB() {
    if (!proyekAktif) return;
    const btnSimpan = document.getElementById("btnSimpanRubrik");
    btnSimpan.innerHTML = LANG.saving; btnSimpan.disabled = true;

    try {
        const response = await fetch(`${BASE_URL}/guru/proyek/simpanRubrik`, {
            method: 'POST',
            headers: { "Content-Type": "application/json", "X-Requested-With": "XMLHttpRequest", [csrfTokenName]: csrfTokenHash },
            body: JSON.stringify({ proyek_id: proyekAktif.id, rubrik_items: rubrikItems.map(i => ({nama_aspek: i.aspek, bobot: i.bobot})) })
        });
        const result = await response.json();
        if (result.status === 'success') {
            showToast(LANG.succ_rubric);
            document.getElementById("nilaiTableCard").style.display = "block";
            await loadNilaiSiswaDariDB(); 
            document.getElementById("nilaiTableCard").scrollIntoView({ behavior: "smooth", block: "start" });
        }
    } catch (error) { showToast(LANG.err_server); } 
    finally { btnSimpan.innerHTML = LANG.save_rubric; btnSimpan.disabled = false; }
}

async function fetchMasterSiswa() {
    try {
        const response = await fetch(`${BASE_URL}/guru/proyek/getSiswaByRombel?rombel_id=${ACTIVE_ROMBEL_ID}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        });
        const result = await response.json();
        if (result.status === 'success') {
            allSiswaDB = result.data.map(s => ({ id: parseInt(s.id), nama: s.nama, nis: s.nis, nilai: {}, catatan: "" }));
        }
    } catch (e) { console.error(e); }
}

async function loadNilaiSiswaDariDB() {
    if(!proyekAktif) return;
    
    document.getElementById("nilaiTableCard").style.display = "block";
    const jenisProyek = (proyekAktif.jenis || "").toLowerCase();
    const isKelompok = jenisProyek === "kelompok";
    
    document.getElementById("labelTipeInput").textContent = `(${isKelompok ? 'Kelompok' : 'Individu'})`;
    
    const searchContainer = document.getElementById("searchSiswaContainer");
    const emptyState = document.getElementById("emptyKelompokState");
    const mainTable = document.getElementById("mainNilaiTable");
    
    if (isKelompok) {
        searchContainer.style.display = "block"; 
    } else {
        searchContainer.style.display = "none"; 
    }
    
    try {
        const response = await fetch(`${BASE_URL}/guru/proyek/getNilaiProyek/${proyekAktif.id}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" } 
        });
        const res = await response.json();
        const savedGrades = res.status === 'success' ? res.data : [];

        if (isKelompok) {
            siswaRendered = allSiswaDB.filter(s => savedGrades.some(g => parseInt(g.siswa_id) === parseInt(s.id)));
            
            if(siswaRendered.length === 0) {
                emptyState.classList.remove("hidden");
                mainTable.classList.add("hidden");
            } else {
                emptyState.classList.add("hidden");
                mainTable.classList.remove("hidden");
            }
        } else {
            siswaRendered = JSON.parse(JSON.stringify(allSiswaDB)); 
            emptyState.classList.add("hidden");
            mainTable.classList.remove("hidden");
        }

        siswaRendered.forEach(siswa => {
            const gradeDB = savedGrades.find(g => parseInt(g.siswa_id) === parseInt(siswa.id));
            if(gradeDB) {
                siswa.nilai = JSON.parse(gradeDB.nilai_json || '{}');
                siswa.catatan = gradeDB.catatan || '';
            } else {
                siswa.nilai = {}; siswa.catatan = '';
            }
        });

        renderNilaiTable();
    } catch (e) { 
        console.error(e);
        showToast(LANG.err_load_grade); 
    }
}

function filterSearchSiswa() {
    const input = document.getElementById("inputSearchSiswa").value.toLowerCase();
    const dropdown = document.getElementById("dropdownSearchSiswa");
    if (input.length < 1) { dropdown.classList.add("hidden"); return; }

    const filtered = allSiswaDB.filter(s => s.nama.toLowerCase().includes(input) && !siswaRendered.some(render => parseInt(render.id) === parseInt(s.id)));
    dropdown.innerHTML = "";
    if (filtered.length === 0) {
        dropdown.innerHTML = `<div class="px-4 py-3 text-sm text-gray-500">${LANG.not_found}</div>`;
    } else {
        filtered.forEach(s => {
            const div = document.createElement("div");
            div.className = "px-4 py-3 hover:bg-blue-50 cursor-pointer text-sm font-bold text-gray-700 flex justify-between border-b border-gray-100";
            div.innerHTML = `<span>${s.nama}</span> <span class="text-xs text-[var(--warna-primary)] px-2 py-1 bg-blue-100 rounded-md">${LANG.add_btn}</span>`;
            
            div.onmousedown = function(e) {
                e.preventDefault(); 
                tambahSiswaKeKelompok(s);
            };
            dropdown.appendChild(div);
        });
    }
    dropdown.classList.remove("hidden");
}

async function tambahSiswaKeKelompok(siswaMaster) {
    const clone = JSON.parse(JSON.stringify(siswaMaster));
    siswaRendered.push(clone);
    
    const searchInput = document.getElementById("inputSearchSiswa");
    if(searchInput) searchInput.value = "";
    
    document.getElementById("dropdownSearchSiswa").classList.add("hidden");
    document.getElementById("emptyKelompokState").classList.add("hidden");
    document.getElementById("mainNilaiTable").classList.remove("hidden");
    
    renderNilaiTable();
    await simpanNilaiSiswaDB(clone.id, true); 
}

async function hapusSiswaDariKelompok(siswaId) {
    if(!confirm(LANG.kick_conf)) return;

    const formData = new FormData();
    formData.append('proyek_id', proyekAktif.id);
    formData.append('siswa_id', siswaId);
    formData.append(csrfTokenName, csrfTokenHash);

    try {
        await fetch(`${BASE_URL}/guru/proyek/hapusNilaiSiswa`, { 
            method: 'POST', 
            body: formData,
            headers: { "X-Requested-With": "XMLHttpRequest" } 
        });

        siswaRendered = siswaRendered.filter(s => parseInt(s.id) !== parseInt(siswaId));
        if(siswaRendered.length === 0) {
            document.getElementById("emptyKelompokState").classList.remove("hidden");
            document.getElementById("mainNilaiTable").classList.add("hidden");
        }
        renderNilaiTable();
        showToast(LANG.succ_kick);
    } catch(e) {
        showToast(LANG.err_kick);
    }
}

document.addEventListener('click', function(event) {
    const searchContainer = document.getElementById('searchSiswaContainer');
    const dropdown = document.getElementById('dropdownSearchSiswa');
    if (searchContainer && dropdown && !searchContainer.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});

function renderNilaiTable() {
    const jenisProyek = (proyekAktif && proyekAktif.jenis) ? proyekAktif.jenis.toLowerCase() : "";
    const isKelompok = jenisProyek === "kelompok";

    const thead = document.querySelector("#mainNilaiTable thead");
    let aspekHeadersHTML = "";

    rubrikItems.forEach((item) => {
        aspekHeadersHTML += `
            <th class="px-4 py-4 text-center text-xs font-black text-[var(--warna-primary)] uppercase tracking-wider bg-blue-50/30">
                ${item.aspek}<br>
                <span class="text-[10px] font-bold text-blue-600 bg-blue-100 px-2 py-0.5 rounded-md mt-1.5 inline-block border border-blue-200 shadow-sm">BOBOT ${item.bobot}%</span>
            </th>
        `;
    });

    thead.innerHTML = `
        <tr class="bg-gray-50 border-b border-gray-200">
            <th class="px-4 py-4 text-center text-xs font-black text-gray-500 uppercase tracking-wider w-12">No</th>
            <th class="px-4 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider w-64">Nama Siswa</th>
            ${aspekHeadersHTML}
            <th class="px-4 py-4 text-center text-xs font-black text-gray-500 uppercase tracking-wider w-28">Nilai Akhir</th>
            <th class="px-4 py-4 text-center text-xs font-black text-gray-500 uppercase tracking-wider w-24">Predikat</th>
            <th class="px-4 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider min-w-[200px]">Catatan</th>
            <th class="px-4 py-4 text-center text-xs font-black text-gray-500 uppercase tracking-wider w-24" style="display: ${isKelompok ? 'table-cell' : 'none'};">Aksi</th>
        </tr>
    `;

    const tbody = document.getElementById("nilaiTableBody");
    tbody.innerHTML = "";

    const kkm = proyekAktif?.kkm || 75;

    siswaRendered.forEach((siswa, index) => {
        const tr = document.createElement("tr");
        tr.id = `row_siswa_${siswa.id}`; 
        
        const nilaiAkhir = hitungNilaiAkhir(siswa.nilai);
        const predikat = getPredikat(nilaiAkhir);
        const belowKKM = nilaiAkhir < kkm && nilaiAkhir > 0;
        
        tr.className = "hover:bg-gray-50 transition-colors group " + (belowKKM ? "bg-red-50/40" : "");

        let aspekInputs = "";
        rubrikItems.forEach((item) => {
            const val = siswa.nilai[item.id] !== undefined ? siswa.nilai[item.id] : "";
            aspekInputs += `
                <td class="text-center px-2 py-4 border-b border-gray-100">
                    <input type="number" value="${val}" min="0" max="100" onchange="updateNilaiLocal(${siswa.id}, ${item.id}, this.value)"
                           class="w-16 px-2 py-2 border-2 border-gray-200 rounded-lg text-center font-bold text-gray-700 focus:border-[var(--warna-primary)] focus:bg-white outline-none transition-all ${val ? 'bg-white' : 'bg-gray-50'} mx-auto block shadow-sm">
                </td>`;
        });

        const btnHapusHTML = isKelompok ? `
            <button type="button" onclick="hapusSiswaDariKelompok(${siswa.id})" class="p-2.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-colors shadow-sm" title="${LANG.btn_kick_group}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>` : "";

        tr.innerHTML = `
            <td class="px-4 py-4 whitespace-nowrap text-sm font-black text-gray-400 text-center border-b border-gray-100">${index + 1}</td>
            <td class="px-4 py-4 whitespace-nowrap text-sm font-bold text-gray-900 border-b border-gray-100">${siswa.nama}</td>
            ${aspekInputs}
            <td class="px-4 py-4 whitespace-nowrap text-center bg-gray-50/50 group-hover:bg-white transition-colors border-b border-gray-100">
                <span id="nilai_akhir_${siswa.id}" class="text-2xl font-black ${nilaiAkhir > 0 ? (belowKKM ? "text-red-600" : "text-[var(--warna-primary)]") : "text-gray-300"}">${nilaiAkhir > 0 ? Math.round(nilaiAkhir) : "-"}</span>
            </td>
            <td class="px-4 py-4 whitespace-nowrap text-center border-b border-gray-100" id="predikat_container_${siswa.id}">
                ${predikat ? `<span class="px-4 py-1.5 rounded-lg text-xs font-black ${predikat==='D'?'bg-red-100 text-red-700':'bg-emerald-100 text-emerald-700'} shadow-sm">${predikat}</span>` : '-'}
            </td>
            <td class="px-4 py-4 whitespace-nowrap border-b border-gray-100">
                <input type="text" class="w-full px-4 py-2 border-2 border-gray-200 rounded-lg text-sm font-medium outline-none focus:border-[var(--warna-primary)] focus:bg-white transition-all shadow-sm ${siswa.catatan ? 'bg-white' : 'bg-gray-50'}" placeholder="${LANG.ph_notes}" value="${siswa.catatan}" onchange="updateCatatanLocal(${siswa.id}, this.value)">
            </td>
            <td class="px-4 py-4 whitespace-nowrap text-center border-b border-gray-100" style="display: ${isKelompok ? 'table-cell' : 'none'};">
                <div class="flex justify-center items-center gap-2">
                    <button type="button" id="btn_simpan_${siswa.id}" onclick="simpanNilaiSiswaDB(${siswa.id})" class="p-2.5 bg-[var(--warna-primary)] text-white rounded-lg hover:opacity-90 shadow-md flex items-center justify-center transition-all cursor-pointer" title="${LANG.btn_save_grade}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </button>
                    ${btnHapusHTML}
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function updateRowDOM(siswaId) {
    const siswa = siswaRendered.find(s => parseInt(s.id) === parseInt(siswaId));
    if (!siswa) return;

    const kkm = proyekAktif?.kkm || 75;
    const nilaiAkhir = hitungNilaiAkhir(siswa.nilai);
    const predikat = getPredikat(nilaiAkhir);
    const belowKKM = nilaiAkhir < kkm && nilaiAkhir > 0;

    const tr = document.getElementById(`row_siswa_${siswaId}`);
    if (tr) {
        if (belowKKM) tr.classList.add("bg-red-50/40");
        else tr.classList.remove("bg-red-50/40");
    }

    const elNilaiAkhir = document.getElementById(`nilai_akhir_${siswaId}`);
    if (elNilaiAkhir) {
        elNilaiAkhir.textContent = nilaiAkhir > 0 ? Math.round(nilaiAkhir) : "-";
        elNilaiAkhir.className = `text-2xl font-black ${nilaiAkhir > 0 ? (belowKKM ? "text-red-600" : "text-[var(--warna-primary)]") : "text-gray-300"}`;
    }

    const elPredikat = document.getElementById(`predikat_container_${siswaId}`);
    if (elPredikat) {
        if (predikat) {
            const colorClass = predikat === 'D' ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700';
            elPredikat.innerHTML = `<span class="px-4 py-1.5 rounded-lg text-xs font-black ${colorClass} shadow-sm">${predikat}</span>`;
        } else {
            elPredikat.innerHTML = '-';
        }
    }
}

function updateNilaiLocal(siswaId, aspekId, nilai) {
    const siswa = siswaRendered.find(s => parseInt(s.id) === parseInt(siswaId));
    if (siswa) { 
        let val = parseInt(nilai) || 0;
        if(val > 100) val = 100;
        if(val < 0) val = 0;

        const inputEl = document.querySelector(`#row_siswa_${siswaId} input[onchange*="${aspekId}"]`);
        if(inputEl && inputEl.value != val) inputEl.value = val;

        siswa.nilai[aspekId] = val; 
        updateRowDOM(siswaId); 
    }
}

function updateCatatanLocal(siswaId, catatan) {
    const siswa = siswaRendered.find(s => parseInt(s.id) === parseInt(siswaId));
    if (siswa) { siswa.catatan = catatan; }
}

async function simpanNilaiSiswaDB(siswaId, isNewAdd = false) {
    const siswa = siswaRendered.find(s => parseInt(s.id) === parseInt(siswaId));
    if(!siswa) return;

    const btnSimpan = document.getElementById(`btn_simpan_${siswaId}`);
    if (btnSimpan) {
        btnSimpan.disabled = true;
        btnSimpan.classList.add("opacity-50", "cursor-wait");
        btnSimpan.innerHTML = `<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>`;
    }

    const nilaiAkhir = hitungNilaiAkhir(siswa.nilai);

    const formData = new FormData();
    formData.append('proyek_id', proyekAktif.id);
    formData.append('siswa_id', siswaId);
    formData.append('nilai_json', JSON.stringify(siswa.nilai));
    formData.append('nilai_akhir', nilaiAkhir);
    formData.append('catatan', siswa.catatan);
    formData.append(csrfTokenName, csrfTokenHash);

    try {
        const response = await fetch(`${BASE_URL}/guru/proyek/simpanNilaiSiswa`, { 
            method: 'POST', 
            body: formData,
            headers: { "X-Requested-With": "XMLHttpRequest" } 
        });
        
        const text = await response.text();
        let result;
        try {
            result = JSON.parse(text);
        } catch (err) {
            console.error("Server Error HTML:", text);
            showToast(LANG.err_server_html);
            return;
        }
        
        if(result.status === 'success') {
            if(isNewAdd) {
                showToast(`✓ ${siswa.nama}${LANG.succ_add_stu}`);
            } else {
                showToast(`✓ ${LANG.succ_save_stu}`);
            }
        } else {
            showToast(`${LANG.err_save_stu}${result.message}`);
        }
    } catch(e) { 
        console.error(e);
        showToast(LANG.err_server_conn); 
    } finally {
        if (btnSimpan) {
            btnSimpan.disabled = false;
            btnSimpan.classList.remove("opacity-50", "cursor-wait");
            btnSimpan.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>`;
        }
    }
}

function hitungNilaiAkhir(nilaiObj) {
    let total = 0; let hasNilai = false;
    rubrikItems.forEach(item => { if (nilaiObj[item.id] !== undefined && nilaiObj[item.id] !== null && nilaiObj[item.id] !== "") { total += (parseInt(nilaiObj[item.id]) * item.bobot) / 100; hasNilai = true; } });
    return hasNilai ? Math.round(total) : 0;
}

function getPredikat(n) { if (n === 0) return null; if (n >= 90) return "A"; if (n >= 80) return "B"; if (n >= 70) return "C"; return "D"; }

function showToast(message) {
    const toast = document.getElementById("toast"); if(!toast) return; 
    
    toast.classList.remove("translate-y-10", "opacity-0");
    document.getElementById("toastMessage").textContent = message;
    
    setTimeout(() => { 
        toast.classList.add("translate-y-10", "opacity-0"); 
    }, 3500);
}