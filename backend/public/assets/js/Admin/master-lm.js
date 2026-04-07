/**
 * File: public/assets/js/Admin/master-lm.js
 */

let lmData = [];

document.addEventListener('DOMContentLoaded', () => {
    loadData();
    
    // Event Listener Filter Dinamis
    document.getElementById('filterSemester')?.addEventListener('change', () => {
        updateKategoriLabels();
        applyFilters();
    });
    document.getElementById('filterMapel')?.addEventListener('change', applyFilters);
    document.getElementById('filterLevel')?.addEventListener('change', applyFilters);
    document.getElementById('filterKodeLM')?.addEventListener('change', applyFilters);
    document.getElementById('filterKategori')?.addEventListener('change', applyFilters);
    document.getElementById('searchInput')?.addEventListener('input', applyFilters);
    
    // Inisialisasi Label Kategori
    updateKategoriLabels();
});

function showToast(message, type = 'success') {
    Swal.fire({
        toast: true, position: 'top-end', icon: type, title: message,
        showConfirmButton: false, timer: 3000,
        customClass: { popup: 'rounded-2xl shadow-xl' }
    });
}

function updateKategoriLabels() {
    const filterSemester = document.getElementById('filterSemester');
    const filterKategori = document.getElementById('filterKategori');
    if (!filterSemester || !filterKategori) return;

    const semester = filterSemester.value; // ALL, Ganjil, Genap
    const options = filterKategori.options;

    for (let i = 0; i < options.length; i++) {
        const val = options[i].value;
        if (val === 'Tengah') {
            options[i].text = (semester === 'ALL') ? 'STS (Tengah - Semua)' : `STS (Tengah - ${semester})`;
        } else if (val === 'Akhir') {
            options[i].text = (semester === 'ALL') ? 'SAS (Akhir - Semua)' : `SAS (Akhir - ${semester})`;
        }
    }
}

async function loadData() {
    const tbody = document.getElementById('tableBody');
    if(tbody) tbody.innerHTML = `<tr><td colspan="7" class="p-8 text-center text-slate-400"><div class="animate-spin w-8 h-8 border-4 border-slate-200 border-t-emerald-500 rounded-full mx-auto mb-2"></div>Memuat data...</td></tr>`;
    
    try {
        const res = await fetch(`${BASE_URL}/admin/master-lm/get-data`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const json = await res.json();
        if (json.status === 'success') {
            lmData = json.data;
            populateDynamicFilters(); 
            applyFilters();
        } else {
            if(tbody) tbody.innerHTML = `<tr><td colspan="7" class="p-8 text-center text-rose-500 font-bold">Gagal memuat data.</td></tr>`;
        }
    } catch (err) {
        if(tbody) tbody.innerHTML = `<tr><td colspan="7" class="p-8 text-center text-rose-500 font-bold">Koneksi ke server terputus. Silakan muat ulang.</td></tr>`;
    }
}

function populateDynamicFilters() {
    const filterLevel = document.getElementById('filterLevel');
    if (filterLevel) {
        let romToNum = {'VII':7, 'VIII':8, 'IX':9, 'X':10, 'XI':11, 'XII':12};
        const parseTingkat = (val) => {
            let str = String(val).toUpperCase();
            return romToNum[str] ? romToNum[str] : parseInt(str);
        };
        const uniqueKelas = [...new Set(lmData.map(item => parseTingkat(item.tingkat)))].filter(k => !isNaN(k)).sort((a, b) => a - b);
        const currentLevel = filterLevel.value;
        let htmlLevel = '<option value="ALL">Semua Tingkat</option>';
        uniqueKelas.forEach(k => {
            htmlLevel += `<option value="${k}">Kelas ${k}</option>`;
        });
        filterLevel.innerHTML = htmlLevel;
        if(currentLevel && (uniqueKelas.includes(parseInt(currentLevel)) || currentLevel === 'ALL')) {
            filterLevel.value = currentLevel;
        }
    }

    const filterKode = document.getElementById('filterKodeLM');
    if (filterKode) {
        const uniqueKodes = [...new Set(lmData.map(item => item.kode_lm))].filter(Boolean);
        uniqueKodes.sort((a, b) => a.localeCompare(b, undefined, {numeric: true, sensitivity: 'base'}));
        const currentKode = filterKode.value;
        let htmlKode = '<option value="ALL">Semua Kode LM</option>';
        uniqueKodes.forEach(kode => {
            htmlKode += `<option value="${kode}">${kode}</option>`;
        });
        filterKode.innerHTML = htmlKode;
        if(currentKode && (uniqueKodes.includes(currentKode) || currentKode === 'ALL')) {
            filterKode.value = currentKode;
        }
    }
}

function applyFilters() {
    const filterSemester = document.getElementById('filterSemester')?.value;
    const filterMapel = document.getElementById('filterMapel')?.value;
    const filterLevel = document.getElementById('filterLevel')?.value;
    const filterKategori = document.getElementById('filterKategori')?.value;
    const filterKodeLM = document.getElementById('filterKodeLM')?.value;
    const keyword = document.getElementById('searchInput')?.value.toLowerCase();

    const filtered = lmData.filter(item => {
        let sem = (item.semester || item.ta_semester || 'Ganjil').toLowerCase();
        let kat = (item.kategori || 'Akhir').toLowerCase();
        
        let fSem = (filterSemester || 'ALL').toLowerCase();
        let fKat = (filterKategori || 'ALL').toLowerCase();
        let fKode = (filterKodeLM || 'ALL').toLowerCase();

        let romToNum = {'VII':7, 'VIII':8, 'IX':9, 'X':10, 'XI':11, 'XII':12};
        let tStr = String(item.tingkat).toUpperCase();
        let numTingkat = romToNum[tStr] ? romToNum[tStr] : parseInt(tStr);

        const matchSemester = fSem === 'all' || sem === fSem;
        const matchMapel = !filterMapel || filterMapel === 'ALL' || item.mapel_id == filterMapel;
        const matchLevel = !filterLevel || filterLevel === 'ALL' || numTingkat == filterLevel;
        const matchKategori = fKat === 'all' || kat === fKat;
        const matchKode = fKode === 'all' || (item.kode_lm && item.kode_lm.toLowerCase() === fKode);
        
        const matchSearch = !keyword || 
            (item.nama_mapel && item.nama_mapel.toLowerCase().includes(keyword)) ||
            (item.kode_lm && item.kode_lm.toLowerCase().includes(keyword)) ||
            (item.deskripsi_lm && item.deskripsi_lm.toLowerCase().includes(keyword));

        return matchSemester && matchMapel && matchLevel && matchKategori && matchKode && matchSearch;
    });

    renderTable(filtered);
}

function renderTable(data) {
    const tbody = document.getElementById('tableBody');
    if (!tbody) return;

    if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="8" class="p-12 text-center text-slate-400 font-medium bg-slate-50/30 dark:bg-slate-900/10">Data tidak ditemukan sesuai filter.</td></tr>`;
        return;
    }

    let html = '';
    data.forEach((item, index) => {
        let sem = item.semester || item.ta_semester || 'Ganjil';
        let badgeSem = sem.toLowerCase() === 'genap' 
            ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 border-indigo-200/50' 
            : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border-emerald-200/50';

        let kat = item.kategori || 'Akhir';
        let badgeKat = kat === 'Tengah' 
            ? 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 border-amber-300 shadow-sm' 
            : 'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border-blue-300 shadow-sm';

        let iconKat = kat === 'Tengah' 
            ? `<svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`
            : `<svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;

        let romToNum = {'VII':7, 'VIII':8, 'IX':9, 'X':10, 'XI':11, 'XII':12};
        let tStr = String(item.tingkat).toUpperCase();
        let dispTingkat = romToNum[tStr] ? romToNum[tStr] : tStr;

        html += `
        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-all group border-b border-slate-100 dark:border-slate-700/50 animate-slide-up" style="animation-delay: ${index * 0.02}s">
            <td class="p-4 text-center font-medium text-slate-400">${index + 1}</td>
            <td class="p-4">
                <div class="flex flex-col">
                    <span class="font-bold text-slate-800 dark:text-white leading-tight">${item.nama_mapel}</span>
                    <span class="text-[10px] text-slate-400 uppercase tracking-tighter mt-0.5">Mata Pelajaran</span>
                </div>
            </td>
            <td class="p-4 text-center">
                <span class="px-3 py-1 bg-white dark:bg-slate-800 rounded-lg text-xs font-black text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-600 shadow-sm">Kls ${dispTingkat}</span>
            </td>
            <td class="p-4 text-center">
                <span class="px-2.5 py-1 rounded-md text-[10px] font-black border tracking-wider ${badgeSem}">${sem.toUpperCase()}</span>
            </td>
            <td class="p-4 text-center">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-black border tracking-wider ${badgeKat}">
                    ${iconKat}
                    ${kat === 'Tengah' ? 'STS' : 'SAS'}
                </span>
            </td>
            <td class="p-4 text-center">
                <span class="font-mono font-black text-dinamis bg-sekunder px-3 py-1 rounded-lg border border-emerald-100 dark:border-emerald-900/30">${item.kode_lm}</span>
            </td>
            <td class="p-4">
                <p class="whitespace-normal min-w-[280px] leading-relaxed text-slate-600 dark:text-slate-300 text-sm italic font-medium">"${item.deskripsi_lm}"</p>
            </td>
            <td class="p-4 text-center">
                <div class="flex justify-center gap-1.5">
                    <button onclick='lihatDetail(${JSON.stringify(item).replace(/'/g, "&#39;")})' class="p-2 text-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 rounded-xl transition-all outline-none" title="Detail"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></button>
                    <button onclick='editData(${JSON.stringify(item).replace(/'/g, "&#39;")})' class="p-2 text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-xl transition-all outline-none" title="Edit"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                    <button onclick="deleteData(${item.id})" class="p-2 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-xl transition-all outline-none" title="Hapus"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                </div>
            </td>
        </tr>`;
    });
    tbody.innerHTML = html;
}

window.autoGenerateDeskripsi = function() {
    const judulMateri = document.getElementById('deskripsi_lm').value.trim();
    if (judulMateri === '') {
        showToast('Tuliskan Judul/Pokok Materi (LM) terlebih dahulu!', 'warning');
        document.getElementById('deskripsi_lm').focus();
        return;
    }

    let materiStr = judulMateri.charAt(0).toLowerCase() + judulMateri.slice(1);
    const textA = `Menunjukkan penguasaan yang sangat baik dalam ${materiStr}.`;
    const textB = `Menunjukkan penguasaan yang baik dalam ${materiStr}.`;
    const textC = `Cukup menguasai dalam ${materiStr}, namun perlu peningkatan pemahaman lebih lanjut.`;
    const textD = `Perlu bimbingan dan pendampingan khusus dalam ${materiStr}.`;

    const fields = ['a', 'b', 'c', 'd'];
    const texts = [textA, textB, textC, textD];

    fields.forEach((f, idx) => {
        const el = document.getElementById('deskripsi_' + f);
        if(el) {
            el.value = texts[idx];
            el.classList.add('ring-2', 'ring-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/30');
            setTimeout(() => el.classList.remove('ring-2', 'ring-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/30'), 1000);
        }
    });

    showToast('✨ Template deskripsi berhasil dirangkai!', 'success');
};

const modal = document.getElementById('formModal');
const modalContent = document.getElementById('modalContent');
const form = document.getElementById('lmForm');

window.showModal = function() {
    form.reset();
    document.getElementById('lm_id').value = '';
    document.getElementById('modalTitle').innerText = 'Buat Template Deskripsi (LM)';
    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
};

window.closeModal = function() {
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    setTimeout(() => modal.classList.add('hidden'), 300);
};

window.editData = function(item) {
    document.getElementById('lm_id').value = item.id;
    if(document.getElementById('semester')) document.getElementById('semester').value = item.semester || 'Ganjil';
    if(document.getElementById('mapel_id')) document.getElementById('mapel_id').value = item.mapel_id;
    if(document.getElementById('kategori')) document.getElementById('kategori').value = item.kategori || 'Akhir';
    
    if(document.getElementById('tingkat')) {
        let romToNum = {'VII':'7', 'VIII':'8', 'IX':'9', 'X':'10', 'XI':'11', 'XII':'12'};
        let tStr = String(item.tingkat).toUpperCase();
        document.getElementById('tingkat').value = romToNum[tStr] ? romToNum[tStr] : tStr;
    }
    if(document.getElementById('kode_lm')) document.getElementById('kode_lm').value = item.kode_lm;
    if(document.getElementById('deskripsi_lm')) document.getElementById('deskripsi_lm').value = item.deskripsi_lm;
    
    if(document.getElementById('deskripsi_a')) document.getElementById('deskripsi_a').value = item.deskripsi_a || '';
    if(document.getElementById('deskripsi_b')) document.getElementById('deskripsi_b').value = item.deskripsi_b || '';
    if(document.getElementById('deskripsi_c')) document.getElementById('deskripsi_c').value = item.deskripsi_c || '';
    if(document.getElementById('deskripsi_d')) document.getElementById('deskripsi_d').value = item.deskripsi_d || '';
    
    document.getElementById('modalTitle').innerText = 'Edit Template Deskripsi (LM)';
    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
};

window.lihatDetail = function(item) {
    let sem = item.semester || item.ta_semester || 'Ganjil';
    let kat = item.kategori || 'Akhir';
    let badgeKat = kat === 'Tengah' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700';

    let htmlContent = `
        <div class="text-left space-y-4 -mt-2">
            <!-- Header Info -->
            <div class="flex flex-wrap gap-2 mb-6 p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-800">
                <div class="flex flex-col pr-4 border-r border-slate-200 dark:border-slate-700">
                    <span class="text-[10px] uppercase font-bold text-slate-400 leading-none mb-1">Tingkat</span>
                    <span class="text-sm font-black text-slate-700 dark:text-white">Kelas ${item.tingkat}</span>
                </div>
                <div class="flex flex-col px-4 border-r border-slate-200 dark:border-slate-700">
                    <span class="text-[10px] uppercase font-bold text-slate-400 leading-none mb-1">Semester</span>
                    <span class="text-sm font-black text-slate-700 dark:text-white">${sem}</span>
                </div>
                <div class="flex flex-col px-4">
                    <span class="text-[10px] uppercase font-bold text-slate-400 leading-none mb-1">Kategori</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-black ${badgeKat}">${kat === 'Tengah' ? 'STS' : 'SAS'}</span>
                </div>
            </div>

            <!-- List Predikat -->
            <div class="grid grid-cols-1 gap-3">
                <div class="group p-4 bg-emerald-50/50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-900/30 rounded-2xl transition-all hover:shadow-md">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-emerald-600 text-white rounded-xl flex items-center justify-center font-black shadow-lg shadow-emerald-600/20">A</div>
                        <span class="font-bold text-emerald-800 dark:text-emerald-400">Pencapaian Sangat Baik</span>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed italic pl-11">"${item.deskripsi_a || 'Belum diatur'}"</p>
                </div>

                <div class="group p-4 bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-900/30 rounded-2xl transition-all hover:shadow-md">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-blue-600 text-white rounded-xl flex items-center justify-center font-black shadow-lg shadow-blue-600/20">B</div>
                        <span class="font-bold text-blue-800 dark:text-blue-400">Pencapaian Baik</span>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed italic pl-11">"${item.deskripsi_b || 'Belum diatur'}"</p>
                </div>

                <div class="group p-4 bg-amber-50/50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/30 rounded-2xl transition-all hover:shadow-md">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-amber-500 text-white rounded-xl flex items-center justify-center font-black shadow-lg shadow-amber-500/20">C</div>
                        <span class="font-bold text-amber-800 dark:text-amber-400">Pencapaian Cukup</span>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed italic pl-11">"${item.deskripsi_c || 'Belum diatur'}"</p>
                </div>

                <div class="group p-4 bg-rose-50/50 dark:bg-rose-900/10 border border-rose-100 dark:border-rose-900/30 rounded-2xl transition-all hover:shadow-md">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-rose-600 text-white rounded-xl flex items-center justify-center font-black shadow-lg shadow-rose-600/20">D</div>
                        <span class="font-bold text-rose-800 dark:text-rose-400">Perlu Bimbingan</span>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed italic pl-11">"${item.deskripsi_d || 'Belum diatur'}"</p>
                </div>
            </div>
        </div>
    `;

    Swal.fire({
        title: `<div class="text-left"><span class="text-xs font-black text-dinamis uppercase tracking-widest">${item.kode_lm}</span><h2 class="text-xl font-bold text-slate-800 dark:text-white leading-tight">${item.nama_mapel}</h2></div>`,
        html: htmlContent,
        width: '600px',
        showCloseButton: true,
        showConfirmButton: false,
        customClass: { 
            popup: 'rounded-3xl border-none shadow-2xl dark:bg-slate-800',
            title: 'border-b border-slate-100 dark:border-slate-700 pb-4 mb-4'
        }
    });
};

form.addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSave');
    const originalText = btn.innerHTML;
    btn.innerHTML = 'Menyimpan...';
    btn.disabled = true;

    const formData = new FormData(form);
    const id = formData.get('id');
    const url = id ? `${BASE_URL}/admin/master-lm/update` : `${BASE_URL}/admin/master-lm/store`;

    try {
        const res = await fetch(url, { method: 'POST', body: formData, headers: {'X-Requested-With': 'XMLHttpRequest'} });
        const json = await res.json();
        
        if (json.status === 'success') {
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: json.message, showConfirmButton: false, timer: 1500, customClass:{popup:'rounded-3xl'} });
            closeModal();
            loadData();
        } else {
            Swal.fire({ icon: 'error', title: 'Gagal!', text: json.message, customClass:{popup:'rounded-3xl'} });
        }
    } catch (err) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Koneksi ke server gagal.', customClass:{popup:'rounded-3xl'} });
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
});

window.deleteData = function(id) {
    Swal.fire({
        title: 'Hapus Template Ini?',
        text: "Deskripsi ini tidak akan muncul lagi di Rapor Siswa!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, Hapus!',
        customClass: { popup: 'rounded-3xl' }
    }).then(async (result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id', id);
            try {
                const res = await fetch(`${BASE_URL}/admin/master-lm/delete`, { method: 'POST', body: formData, headers: {'X-Requested-With': 'XMLHttpRequest'} });
                const json = await res.json();
                if (json.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Dihapus!', text: json.message, showConfirmButton: false, timer: 1500, customClass:{popup:'rounded-3xl'} });
                    loadData();
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menghapus data.', customClass:{popup:'rounded-3xl'} });
            }
        }
    });
};

// --- LOGIKA IMPORT & EXPORT EXCEL ---
window.showExportModal = function() {
    const m = document.getElementById('exportModal');
    const c = document.getElementById('exportModalContent');
    m.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    setTimeout(() => {
        c.classList.remove('scale-95', 'opacity-0');
        c.classList.add('scale-100', 'opacity-100');
    }, 10);
};

window.closeExportModal = function() {
    const m = document.getElementById('exportModal');
    const c = document.getElementById('exportModalContent');
    c.classList.remove('scale-100', 'opacity-100');
    c.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        m.classList.add('hidden');
        document.body.style.overflow = '';
    }, 300);
};

// FITUR BARU: CEK DATA GENAP SEBELUM EKSPOR
window.executeExport = function(jenis) {
    // Cari apakah ada data semester Genap di array lmData
    const hasGenap = lmData.some(item => (item.semester || '').toLowerCase() === 'genap');

    // Jika yang mau diekspor adalah 'semua' atau 'akhir' (yg mungkin butuh data genap) dan ternyata kosong
    if (!hasGenap) {
        Swal.fire({
            title: 'Peringatan!',
            text: 'Data Semester Genap belum ada. Apakah Anda yakin tetap ingin mengekspor?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Tetap Ekspor',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-3xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                closeExportModal();
                window.location.href = `${BASE_URL}/admin/master-lm/export/${jenis}`;
            }
        });
    } else {
        closeExportModal();
        window.location.href = `${BASE_URL}/admin/master-lm/export/${jenis}`;
    }
};

window.showDownloadModal = function() {
    const m = document.getElementById('downloadModal');
    const c = document.getElementById('downloadModalContent');
    m.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    setTimeout(() => {
        c.classList.remove('scale-95', 'opacity-0');
        c.classList.add('scale-100', 'opacity-100');
    }, 10);
};

window.closeDownloadModal = function() {
    const m = document.getElementById('downloadModal');
    const c = document.getElementById('downloadModalContent');
    c.classList.remove('scale-100', 'opacity-100');
    c.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        m.classList.add('hidden');
        document.body.style.overflow = '';
    }, 300);
};

window.showImportModal = function() {
    const m = document.getElementById('importModal');
    const c = document.getElementById('importModalContent');
    m.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    setTimeout(() => {
        c.classList.remove('scale-95', 'opacity-0');
        c.classList.add('scale-100', 'opacity-100');
    }, 10);
};

window.closeImportModal = function() {
    const m = document.getElementById('importModal');
    const c = document.getElementById('importModalContent');
    c.classList.remove('scale-100', 'opacity-100');
    c.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        m.classList.add('hidden');
        document.body.style.overflow = '';
        document.getElementById('importForm').reset();
        document.getElementById('fileNameText').textContent = 'Klik atau Seret file Excel / Word (.docx)';
        document.getElementById('fileNameText').classList.remove('text-amber-600', 'dark:text-amber-400');
    }, 300);
};

window.updateFileName = function(input) {
    const fileNameText = document.getElementById('fileNameText');
    if (input.files && input.files.length > 0) {
        fileNameText.textContent = input.files[0].name;
        fileNameText.classList.add('text-amber-600', 'dark:text-amber-400');
    } else {
        fileNameText.textContent = 'Klik atau Seret file Excel / Word (.docx)';
        fileNameText.classList.remove('text-amber-600', 'dark:text-amber-400');
    }
};

window.handleImportSubmit = async function(event) {
    event.preventDefault();
    const form = event.target;
    const btn = document.getElementById('btnImport');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = `<svg class="animate-spin h-5 w-5 mr-3 text-white inline-block" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Membaca & Merangkai...`;
    btn.disabled = true;

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        
        const result = await response.json();
        
        if (result.status === 'success' || result.status === 'warning') {
            Swal.fire({ icon: 'success', title: 'Import Selesai', html: result.message, customClass: { popup: 'rounded-3xl' }});
            closeImportModal();
            loadData(); // Langsung refresh data tabel di belakang layar
        } else {
            Swal.fire({ icon: 'error', title: 'Gagal Import', text: result.message, customClass: { popup: 'rounded-3xl' }});
        }
    } catch (error) {
        Swal.fire({ icon: 'error', title: 'Terputus', text: 'Koneksi ke server gagal.', customClass: { popup: 'rounded-3xl' }});
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
};