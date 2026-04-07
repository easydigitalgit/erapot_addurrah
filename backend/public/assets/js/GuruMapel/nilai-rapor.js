let rawBaseUrl = document.getElementById('base-url')?.getAttribute('data-url') || '';
const BASE_URL = rawBaseUrl.endsWith('/') ? rawBaseUrl : rawBaseUrl + '/';

let penugasanData = [];

document.addEventListener('DOMContentLoaded', () => {
    const elKategori = document.getElementById('filterKategori');
    const elTa = document.getElementById('filterTahunAjaran');
    const elRombel = document.getElementById('filterRombel');
    const elMapel = document.getElementById('filterMapel');

    if(elKategori) elKategori.addEventListener('change', () => {
        updateTableHeaders();
        window.loadDataRapor();
    });

    if(elTa) {
        elTa.addEventListener('change', fetchPenugasan);
        if(elTa.value !== "") fetchPenugasan(); 
    }
    
    if(elRombel) elRombel.addEventListener('change', populateMapel);
    if(elMapel) elMapel.addEventListener('change', window.loadDataRapor);

    updateTableHeaders();
});

function updateTableHeaders() {
    const kategori = document.getElementById('filterKategori')?.value;
    const headerSumatif = document.getElementById('headerSumatif');
    const colSTS = document.getElementById('colSTS');
    const colSAS = document.getElementById('colSAS');
    
    if (kategori === 'Tengah Semester') {
        if(headerSumatif) headerSumatif.colSpan = 1;
        if(colSTS) {
            colSTS.classList.add('border-r');
            colSTS.innerHTML = "Rata PTS/STS"; 
        }
        if(colSAS) {
            colSAS.classList.add('hidden');
            colSAS.style.display = 'none';
        }
    } else {
        if(headerSumatif) headerSumatif.colSpan = 2;
        if(colSTS) {
            colSTS.classList.remove('border-r'); 
            colSTS.innerHTML = "Rata PAS"; 
        }
        if(colSAS) {
            colSAS.classList.remove('hidden');
            colSAS.style.display = 'table-cell';
            colSAS.innerHTML = "Rata SAS";
        }
    }
}

async function fetchPenugasan() {
    const elTa = document.getElementById('filterTahunAjaran');
    const ta_id = elTa.value;
    const elRombel = document.getElementById('filterRombel');
    const elMapel = document.getElementById('filterMapel');
    const btnSync = document.getElementById('btnSync');
    const tbody = document.getElementById('tableBodyRapor');

    elRombel.innerHTML = '<option value="">-- Pilih Kelas --</option>';
    elMapel.innerHTML = '<option value="">-- Pilih Kelas Dulu --</option>';
    elRombel.disabled = true;
    elMapel.disabled = true;
    btnSync.disabled = true;
    
    const colSpanTotal = document.getElementById('filterKategori')?.value === 'Tengah Semester' ? 6 : 7;
    tbody.innerHTML = `<tr><td colspan="${colSpanTotal}" class="px-6 py-12 text-center text-gray-500 font-bold">Silakan Pilih Filter Kelas dan Mapel</td></tr>`;
    
    if (!ta_id) return;

    elRombel.innerHTML = '<option value="">Memuat data kelas...</option>';

    try {
        const response = await fetch(`${BASE_URL}guru/nilai-rapor/get-penugasan/${ta_id}`, { 
            headers: { 'X-Requested-With': 'XMLHttpRequest' } 
        });
        const textResp = await response.text();
        
        let dataParsed;
        try {
            dataParsed = JSON.parse(textResp);
            penugasanData = dataParsed;
        } catch(e) {
            throw new Error("Respon server gagal (Bukan format JSON).");
        }

        if (penugasanData.length === 0) {
            elRombel.innerHTML = '<option value="">Tidak ada jadwal mengajar di TA ini</option>';
            tbody.innerHTML = `<tr><td colspan="${colSpanTotal}" class="px-6 py-12 text-center text-gray-500 font-bold">Anda tidak memiliki jadwal/tugas mengajar di Tahun Ajaran ini.</td></tr>`;
            return;
        }

        const uniqueRombel = [];
        const map = new Map();
        for (const item of penugasanData) {
            if(!map.has(item.rombel_id)){
                map.set(item.rombel_id, true);
                uniqueRombel.push({ id: item.rombel_id, nama: item.nama_rombel });
            }
        }

        elRombel.innerHTML = '<option value="">-- Pilih Kelas --</option>';
        uniqueRombel.forEach(r => {
            elRombel.innerHTML += `<option value="${r.id}">${r.nama}</option>`;
        });
        elRombel.disabled = false;
        tbody.innerHTML = `<tr><td colspan="${colSpanTotal}" class="px-6 py-12 text-center text-gray-500 font-bold">Silakan Pilih Filter Kelas dan Mata Pelajaran</td></tr>`;

    } catch (err) {
        console.error(err);
        elRombel.innerHTML = '<option value="">Error memuat kelas</option>';
    }
}

function populateMapel() {
    const elRombel = document.getElementById('filterRombel');
    const rombel_id = elRombel.value;
    const elMapel = document.getElementById('filterMapel');
    const btnSync = document.getElementById('btnSync');

    elMapel.innerHTML = '<option value="">-- Pilih Mata Pelajaran --</option>';
    elMapel.disabled = true;
    btnSync.disabled = true;

    if(!rombel_id) return;

    const mapelsInClass = penugasanData.filter(item => item.rombel_id == rombel_id);
    
    mapelsInClass.forEach(m => {
        elMapel.innerHTML += `<option value="${m.mapel_id}">${m.nama_mapel}</option>`;
    });

    elMapel.disabled = false;
}

window.loadDataRapor = async function() {
    const kategori = document.getElementById('filterKategori').value;
    const ta_id = document.getElementById('filterTahunAjaran').value;
    const rombel_id = document.getElementById('filterRombel').value;
    const mapel_id = document.getElementById('filterMapel').value;
    
    const tbody = document.getElementById('tableBodyRapor');
    const btnSync = document.getElementById('btnSync');

    updateTableHeaders();
    const isTengahSemester = kategori === 'Tengah Semester';
    const colSpanTotal = isTengahSemester ? 6 : 7;

    if(!ta_id || !rombel_id || !mapel_id || !kategori) {
        btnSync.disabled = true;
        return;
    }
    
    btnSync.disabled = false;
    tbody.innerHTML = `<tr><td colspan="${colSpanTotal}" class="px-6 py-12 text-center"><div class="animate-spin inline-block w-8 h-8 border-4 border-[var(--warna-primary)] border-t-transparent rounded-full mb-2"></div><p class="text-gray-500 font-medium">Memuat data siswa & nilai...</p></td></tr>`;

    try {
        const formData = new FormData();
        formData.append('tahun_ajaran_id', ta_id);
        formData.append('rombel_id', rombel_id);
        formData.append('mapel_id', mapel_id);
        formData.append('kategori', kategori);

        // Security Helper
        if(typeof csrfTokenName !== 'undefined' && typeof csrfTokenHash !== 'undefined') {
            formData.append(csrfTokenName, csrfTokenHash);
        }

        const response = await fetch(`${BASE_URL}guru/nilai-rapor/get-data`, { 
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' } 
        });
        
        const textResp = await response.text();
        let data;
        try {
            data = JSON.parse(textResp);
        } catch(e) {
            throw new Error("Gagal mengambil data dari server.");
        }

        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="${colSpanTotal}" class="px-6 py-16 text-center text-gray-500 dark:text-slate-400"><p class="font-bold text-lg">Belum Ada Siswa</p><p class="text-sm">Tidak ada data siswa aktif di kelas ini.</p></td></tr>`;
            return;
        }

        tbody.innerHTML = '';
        data.forEach(item => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors';

            const isSynced = item.nilai_akhir !== null;
            
            let predikatHtml = '';
            if(item.predikat === 'Sangat Baik') predikatHtml = `<span class="bg-emerald-100 text-emerald-700 font-bold px-2.5 py-1 rounded-lg text-[10px] uppercase tracking-wider ml-2 whitespace-nowrap">${item.predikat}</span>`;
            else if(item.predikat === 'Baik') predikatHtml = `<span class="bg-blue-100 text-blue-700 font-bold px-2.5 py-1 rounded-lg text-[10px] uppercase tracking-wider ml-2 whitespace-nowrap">${item.predikat}</span>`;
            else if(item.predikat === 'Cukup') predikatHtml = `<span class="bg-amber-100 text-amber-700 font-bold px-2.5 py-1 rounded-lg text-[10px] uppercase tracking-wider ml-2 whitespace-nowrap">${item.predikat}</span>`;
            else if(item.predikat === 'Perlu Bimbingan' || item.predikat === 'Sangat Kurang' || item.predikat === 'Kurang') predikatHtml = `<span class="bg-red-100 text-red-700 font-bold px-2.5 py-1 rounded-lg text-[10px] uppercase tracking-wider ml-2 whitespace-nowrap">${item.predikat}</span>`;
            else predikatHtml = `<span class="bg-gray-100 text-gray-700 font-bold px-2.5 py-1 rounded-lg text-[10px] uppercase tracking-wider ml-2 whitespace-nowrap">${item.predikat || '-'}</span>`;

            const nh = item.rata_nh > 0 ? item.rata_nh : '-';
            const uh = item.rata_uh > 0 ? item.rata_uh : '-';
            
            const sts_pts = item.rata_sts > 0 ? item.rata_sts : '-'; 
            const pas     = item.rata_pas > 0 ? item.rata_pas : '-'; 
            const sas     = item.rata_sas > 0 ? item.rata_sas : '-'; 
            
            const finalGrade = (isSynced && item.nilai_akhir !== null) ? item.nilai_akhir : '0';

            let colsHTML = `
                <td class="px-6 py-4 border-r border-gray-100 dark:border-slate-700">
                    <div class="font-bold text-gray-800 dark:text-slate-200 text-sm truncate max-w-[200px]">${item.nama_siswa}</div>
                    <span class="text-[10px] text-gray-400 dark:text-slate-500 font-semibold uppercase tracking-widest mt-0.5 inline-block">NISN: ${item.nisn || '-'}</span>
                </td>
                <td class="px-4 py-4 text-center font-mono text-sm text-blue-600 dark:text-blue-400 bg-blue-50/20">${nh}</td>
                <td class="px-4 py-4 text-center font-mono text-sm text-blue-600 dark:text-blue-400 bg-blue-50/20 border-r border-gray-100 dark:border-slate-700">${uh}</td>
            `;

            if (isTengahSemester) {
                colsHTML += `<td class="px-4 py-4 text-center font-mono text-sm text-amber-600 dark:text-amber-400 bg-amber-50/20 border-r border-gray-100 dark:border-slate-700">${sts_pts}</td>`;
            } else {
                colsHTML += `<td class="px-4 py-4 text-center font-mono text-sm text-amber-600 dark:text-amber-400 bg-amber-50/20">${pas}</td>`;
                colsHTML += `<td class="px-4 py-4 text-center font-mono text-sm text-amber-600 dark:text-amber-400 bg-amber-50/20 border-r border-gray-100 dark:border-slate-700">${sas}</td>`;
            }

            colsHTML += `
                <td class="px-6 py-4 text-center bg-emerald-50/20 dark:bg-emerald-900/5 border-r border-gray-100 dark:border-slate-700">
                    <div class="flex items-center justify-center">
                        <span class="text-lg font-black ${isSynced ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400'}">${finalGrade}</span>
                        ${isSynced ? predikatHtml : ''}
                    </div>
                </td>
                <td class="px-6 py-4 text-center bg-gray-50 dark:bg-slate-800/80">
                    ${isSynced 
                        ? `<span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-bold rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg> Disinkronisasi</span>` 
                        : `<span class="inline-flex px-2.5 py-1 text-[10px] font-bold rounded-lg bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400 border border-rose-200 dark:border-rose-800/50">Belum Direkap</span>`
                    }
                </td>
            `;

            tr.innerHTML = colsHTML;
            tbody.appendChild(tr);
        });

    } catch (err) {
        console.error(err);
        tbody.innerHTML = `<tr><td colspan="${colSpanTotal}" class="px-6 py-12 text-center text-red-500 font-bold">${err.message}</td></tr>`;
    }
}

window.syncNilai = function() {
    const kategori = document.getElementById('filterKategori').value;
    const ta_id = document.getElementById('filterTahunAjaran').value;
    const rombel_id = document.getElementById('filterRombel').value;
    const mapel_id = document.getElementById('filterMapel').value;

    Swal.fire({
        title: `Sinkronisasi Rapor ${kategori}?`,
        text: "Sistem akan menarik dan mengkalkulasi seluruh nilai sesuai bobot terbaru secara cerdas berdasarkan progress.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, Sinkronisasikan!',
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-3xl' }
    }).then(async (result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Sedang Memproses...',
                html: 'Tunggu sebentar, sistem sedang melakukan kalkulasi bobot.',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                const formData = new FormData();
                formData.append('tahun_ajaran_id', ta_id);
                formData.append('rombel_id', rombel_id);
                formData.append('mapel_id', mapel_id);
                formData.append('kategori', kategori);

                // Security Helper
                if(typeof csrfTokenName !== 'undefined' && typeof csrfTokenHash !== 'undefined') {
                    formData.append(csrfTokenName, csrfTokenHash);
                }

                const res = await fetch(`${BASE_URL}guru/nilai-rapor/sync`, { 
                    method: 'POST', 
                    body: formData,
                    headers: {'X-Requested-With': 'XMLHttpRequest'} 
                });
                
                const textResp = await res.text();
                let json;
                try {
                    json = JSON.parse(textResp);
                } catch(e) {
                    throw new Error("Gagal membaca respon server.");
                }
                
                if (json.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: json.message, customClass: { popup: 'rounded-3xl' } });
                    window.loadDataRapor(); 
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: json.message, customClass: { popup: 'rounded-3xl' } });
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Error System', text: err.message, customClass: { popup: 'rounded-3xl' } });
            }
        }
    });
}