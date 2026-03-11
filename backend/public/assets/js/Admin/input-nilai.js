// SABUK PENGAMAN (FALLBACK)
const textObj = window.LANG || {
    js_status_pass: 'Tuntas', js_status_fail: 'Remedial', js_swal_warn_title: 'Pilih Data Dulu',
    js_swal_warn_text: 'Mohon pilih Kelas dan Mata Pelajaran sebelum menampilkan data.', js_swal_btn_ok: 'Siap, Mengerti',
    js_loading_fetch: 'Sedang mengambil data siswa...', js_ph_grade: '-', js_ph_notes: 'Tuliskan catatan apresiasi/evaluasi...',
    js_no_students: 'Tidak ada siswa ditemukan di kelas ini.', js_swal_err_title: 'Gagal Memuat',
    js_swal_err_text: 'Terjadi kesalahan saat mengambil data. Coba refresh halaman.', js_err_load_table: 'Gagal memuat data.',
    js_swal_oops: 'Oops...', js_swal_sel_save: 'Mohon pilih Data Kelas dan Mata Pelajaran dulu ya!',
    js_swal_no_grade: 'Belum ada nilai', js_swal_fill_one: 'Silakan isi setidaknya satu nilai siswa sebelum menyimpan.',
    js_saving: 'Menyimpan...', js_swal_success: 'Alhamdulillah!', js_swal_fail_save: 'Gagal Menyimpan',
    js_swal_sys_err: 'Terjadi Kesalahan', js_swal_err_conn: 'Cek koneksi internet atau hubungi admin.',
    js_swal_sel_exp: 'Pilih Kelas dan Mata Pelajaran dulu sebelum export ya!', js_swal_prep_data: 'Menyiapkan Data...',
    js_swal_prep_desc: 'Mohon tunggu sebentar, file Excel sedang dibuat.'
};

let KKM = 75;

function processInput(inputElement) {
    hitungPredikat(inputElement);
    updateStatistics(); 
}

function hitungPredikat(inputElement) {
    let nilai = parseFloat(inputElement.value);
    const row = inputElement.closest('tr');
    const badgePredikat = row.querySelector('.badge-predikat');
    const badgeKet = row.querySelector('.badge-ket');
    
    if (nilai > 100) { inputElement.value = 100; nilai = 100; }
    if (nilai < 0) { inputElement.value = 0; nilai = 0; }
    
    let predikat = '-';
    let warnaClass = 'bg-gray-100 dark:bg-slate-700 text-gray-400 dark:text-slate-500 ring-1 ring-gray-200 dark:ring-slate-600';
    let ketText = '-';
    let ketClass = 'text-gray-400 dark:text-slate-500';

    if (!isNaN(nilai)) {
        if (nilai >= 92) {
            predikat = 'A';
            warnaClass = 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 ring-1 ring-emerald-600/20 dark:ring-emerald-800/50';
        } else if (nilai >= 83) {
            predikat = 'B';
            warnaClass = 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 ring-1 ring-blue-600/20 dark:ring-blue-800/50';
        } else if (nilai >= 75) {
            predikat = 'C';
            warnaClass = 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 ring-1 ring-yellow-600/20 dark:ring-yellow-800/50';
        } else {
            predikat = 'D';
            warnaClass = 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 ring-1 ring-red-600/20 dark:ring-red-800/50';
        }

        const kkmInput = document.getElementById('kkmValue');
        KKM = kkmInput ? parseInt(kkmInput.value) : 75;

        if (nilai >= KKM) {
            ketText = textObj.js_status_pass;
            ketClass = 'text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-50 dark:bg-emerald-900/20 px-2 py-1 rounded border border-emerald-100 dark:border-emerald-800/50';
        } else {
            ketText = textObj.js_status_fail;
            ketClass = 'text-red-600 dark:text-red-400 font-bold bg-red-50 dark:bg-red-900/20 px-2 py-1 rounded border border-red-100 dark:border-red-800/50';
        }
    }
    
    badgePredikat.className = `badge-predikat px-3 py-1 rounded-lg text-sm font-bold shadow-sm transition-colors ${warnaClass}`;
    badgePredikat.innerText = predikat;

    badgeKet.className = `badge-ket text-xs uppercase tracking-wide transition-colors ${ketClass}`;
    badgeKet.innerText = ketText;
}

function updateStatistics() {
    const inputs = document.querySelectorAll('.input-nilai');
    let totalNilai = 0;
    let countFilled = 0;
    let countPass = 0;
    let countFail = 0;
    
    const kkmInput = document.getElementById('kkmValue');
    const currentKKM = kkmInput ? parseInt(kkmInput.value) : 75;

    inputs.forEach(input => {
        const val = parseFloat(input.value);
        if (!isNaN(val)) {
            totalNilai += val;
            countFilled++;
            if (val >= currentKKM) countPass++;
            else countFail++;
        }
    });

    const avg = countFilled > 0 ? (totalNilai / countFilled).toFixed(1) : 0;
    
    setText('statAvg', avg);
    setText('statPass', countPass);
    setText('statFail', countFail);

    const totalSiswa = inputs.length;
    const percent = totalSiswa > 0 ? Math.round((countFilled / totalSiswa) * 100) : 0;
    
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');

    if(progressBar) progressBar.style.width = `${percent}%`;
    if(progressText) progressText.innerText = `${percent}%`;
}

function setText(id, value) {
    const el = document.getElementById(id);
    if(el) el.innerText = value;
}

function loadSiswa() {
    const kelas = document.getElementById('pilihKelas').value;
    const mapel = document.getElementById('pilihMapel').value;
    const tableBody = document.getElementById('tableBody');
    const emptyState = document.getElementById('emptyState');
    const statsSection = document.getElementById('statsSection');
    const progressSection = document.getElementById('progressSection');
    
    const colorPrimary = getComputedStyle(document.documentElement).getPropertyValue('--warna-primary').trim();
    
    if(!kelas || !mapel) {
        Swal.fire({
            icon: 'warning',
            title: textObj.js_swal_warn_title,
            text: textObj.js_swal_warn_text,
            confirmButtonColor: '#F59E0B',
            confirmButtonText: textObj.js_swal_btn_ok
        });
        return;
    }

    emptyState.classList.add('hidden');
    tableBody.innerHTML = `<tr><td colspan="6" class="text-center py-12 bg-white dark:bg-slate-800"><div class="flex flex-col items-center"><svg class="animate-spin h-10 w-10 mb-3" style="color: ${colorPrimary}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span class="text-gray-500 dark:text-slate-400 font-medium">${textObj.js_loading_fetch}</span></div></td></tr>`;

    fetch(`${API_URL}/get-siswa?kelas=${kelas}&mapel=${mapel}`)
        .then(response => response.json())
        .then(res => {
            if(res.data && res.data.length > 0) {
                let html = '';
                res.data.forEach((siswa, index) => {
                    const existingNilai = siswa.nilai_angka !== null ? siswa.nilai_angka : '';
                    const existingCatatan = siswa.catatan !== null ? siswa.catatan : '';

                    html += `
                    <tr class="bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors group border-b border-gray-100 dark:border-slate-700/50 last:border-0" data-siswa-id="${siswa.siswa_id}">
                        <td class="px-6 py-4 text-center text-gray-500 dark:text-slate-400 font-medium transition-colors">${index + 1}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-slate-700 flex items-center justify-center text-gray-600 dark:text-slate-300 font-bold text-xs shadow-sm transition-colors">
                                    ${siswa.nama.substring(0,2).toUpperCase()}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-bold text-gray-800 dark:text-white truncate transition-colors">${siswa.nama}</div>
                                    <div class="text-xs text-gray-500 dark:text-slate-400 font-mono truncate transition-colors">NIS: ${siswa.nis}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <input type="number" min="0" max="100" 
                                class="input-nilai w-full px-3 py-2 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-[var(--warna-primary)] focus:border-[var(--warna-primary)] transition-colors shadow-sm" 
                                value="${existingNilai}" 
                                oninput="processInput(this)" 
                                placeholder="${textObj.js_ph_grade}">
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="badge-predikat px-3 py-1 rounded-lg text-xs font-bold bg-gray-100 dark:bg-slate-700 text-gray-400 dark:text-slate-500 transition-colors">-</span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="badge-ket text-xs text-gray-300 dark:text-slate-600 transition-colors">-</span>
                        </td>
                        <td class="px-4 py-4">
                            <input type="text" 
                                class="input-catatan w-full px-3 py-2 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 text-gray-900 dark:text-white rounded-lg text-xs focus:bg-white dark:focus:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-[var(--warna-primary)] transition-colors shadow-sm placeholder-gray-400 dark:placeholder-slate-400" 
                                value="${existingCatatan}"
                                placeholder="${textObj.js_ph_notes}">
                        </td>
                    </tr>
                    `;
                });
                tableBody.innerHTML = html;
                
                document.querySelectorAll('.input-nilai').forEach(input => {
                    if(input.value !== '') processInput(input);
                });

                if(statsSection) statsSection.classList.remove('hidden');
                if(progressSection) progressSection.classList.remove('hidden');
                setText('statTotal', res.data.length);
                updateStatistics(); 
            } else {
                tableBody.innerHTML = `<tr><td colspan="6" class="text-center py-10 bg-white dark:bg-slate-800 text-gray-500 dark:text-slate-400 transition-colors">${textObj.js_no_students}</td></tr>`;
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: textObj.js_swal_err_title,
                text: textObj.js_swal_err_text,
                confirmButtonColor: '#EF4444'
            });
            tableBody.innerHTML = `<tr><td colspan="6" class="text-center py-6 bg-white dark:bg-slate-800 text-red-500 dark:text-red-400 transition-colors">${textObj.js_err_load_table}</td></tr>`;
        });
}

function simpanNilai() {
    const btn = event.currentTarget;
    const originalContent = btn.innerHTML;
    const rombelId = document.getElementById('pilihKelas').value;
    const mapelId = document.getElementById('pilihMapel').value;

    if(!rombelId || !mapelId) {
        Swal.fire({
            icon: 'warning',
            title: textObj.js_swal_oops,
            text: textObj.js_swal_sel_save,
            confirmButtonColor: '#F59E0B' 
        });
        return;
    }

    let nilaiData = [];
    const rows = document.querySelectorAll('#tableBody tr');
    
    rows.forEach(row => {
        const siswaId = row.getAttribute('data-siswa-id');
        const nilaiInput = row.querySelector('.input-nilai').value;
        const catatanInput = row.querySelector('.input-catatan').value;
        const predikatBadge = row.querySelector('.badge-predikat').innerText;

        if(nilaiInput !== '') {
            nilaiData.push({
                siswa_id: siswaId,
                nilai: nilaiInput,
                predikat: predikatBadge,
                catatan: catatanInput
            });
        }
    });

    if(nilaiData.length === 0) {
        Swal.fire({
            icon: 'info',
            title: textObj.js_swal_no_grade,
            text: textObj.js_swal_fill_one,
            confirmButtonColor: '#3B82F6' 
        });
        return;
    }

    let formData = new FormData();
    formData.append('rombel_id', rombelId);
    formData.append('mapel_id', mapelId);
    formData.append('nilai_data', JSON.stringify(nilaiData));

    btn.innerHTML = `<svg class="animate-spin w-5 h-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ${textObj.js_saving}`;
    btn.disabled = true;

    let headers = { 'X-Requested-With': 'XMLHttpRequest' };
    try {
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfHeader = document.querySelector('meta[name="csrf-header"]');
        if (csrfMeta && csrfHeader) {
            headers[csrfHeader.getAttribute('content')] = csrfMeta.getAttribute('content');
        }
    } catch(e) {}

    fetch(`${API_URL}/store`, { 
        method: 'POST',
        headers: headers,
        body: formData
    })
    .then(response => response.json())
    .then(res => {
         setTimeout(() => {
            if(res.status === 'success') {
                Swal.fire({
                    title: textObj.js_swal_success,
                    text: res.message,
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 2000, 
                    timerProgressBar: true,
                    iconColor: '#10B981' 
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: textObj.js_swal_fail_save,
                    text: res.message,
                    confirmButtonColor: '#EF4444'
                });
            }
            btn.innerHTML = originalContent;
            btn.disabled = false;
        }, 800);
    })
    .catch(err => {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: textObj.js_swal_sys_err,
            text: textObj.js_swal_err_conn,
            confirmButtonColor: '#EF4444'
        });
        btn.innerHTML = originalContent;
        btn.disabled = false;
    });
}

function exportExcel() {
    const rombelId = document.getElementById('pilihKelas').value;
    const mapelId = document.getElementById('pilihMapel').value;

    if(!rombelId || !mapelId) {
        Swal.fire({
            icon: 'warning',
            title: textObj.js_swal_oops,
            text: textObj.js_swal_sel_exp,
            confirmButtonColor: '#F59E0B'
        });
        return;
    }

    Swal.fire({
        title: textObj.js_swal_prep_data,
        text: textObj.js_swal_prep_desc,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const exportUrl = `${API_URL}/export?kelas=${rombelId}&mapel=${mapelId}`;
    window.location.href = exportUrl;

    setTimeout(() => {
        Swal.close();
    }, 1500);
}