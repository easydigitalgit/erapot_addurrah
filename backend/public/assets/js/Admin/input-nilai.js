const textObj = window.LANG || {
    js_swal_warn_title: 'Pilih Data Dulu', 
    js_swal_warn_text: 'Mohon pilih Kelas dan Mata Pelajaran sebelum menampilkan data.', 
    js_swal_btn_ok: 'Siap, Mengerti', 
    js_loading_fetch: 'Sedang mengambil data siswa...', 
    js_no_students: 'Tidak ada siswa ditemukan di kelas ini.', 
    js_swal_err_title: 'Gagal Memuat',
    js_swal_err_text: 'Terjadi kesalahan saat mengambil data. Coba refresh halaman.', 
    js_err_load_table: 'Gagal memuat data.',
    js_swal_oops: 'Oops...', 
    js_swal_sel_exp: 'Pilih Kelas dan Mata Pelajaran dulu sebelum export ya!', 
    js_swal_prep_data: 'Menyiapkan Data...', 
    js_swal_prep_desc: 'Mohon tunggu sebentar, file Excel sedang dibuat.'
};

let globalStudentsData = [];

function loadSiswa() {
    const ta = document.getElementById('pilihTA').value;
    const kelas = document.getElementById('pilihKelas').value;
    const mapel = document.getElementById('pilihMapel').value;
    const kategori = document.getElementById('pilihKategori').value; 
    
    const table = document.getElementById('mainTable');
    const tableBody = document.getElementById('tableBody');
    const emptyState = document.getElementById('emptyState');

    if(!kelas || !mapel || !ta) {
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
    table.classList.remove('hidden'); 
    
    tableBody.innerHTML = `<tr><td colspan="7" class="text-center py-12 bg-white dark:bg-slate-800"><div class="flex flex-col items-center"><svg class="animate-spin h-10 w-10 mb-3" style="color: ${typeof COLOR_PRIMARY !== 'undefined' ? COLOR_PRIMARY : '#3b82f6'}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span class="text-gray-500 dark:text-slate-400 font-medium">${textObj.js_loading_fetch}</span></div></td></tr>`;

    fetch(`${API_URL}/get-siswa?ta=${ta}&kelas=${kelas}&mapel=${mapel}&kategori=${kategori}`)
        .then(async response => {
            const isJson = response.headers.get('content-type')?.includes('application/json');
            const data = isJson ? await response.json() : null;
            
            if (!response.ok || !isJson) {
                throw new Error("Sistem Server Error. Hubungi Admin.");
            }
            return data;
        })
        .then(res => {
            if(res.status === 'success' && res.data && res.data.length > 0) {
                globalStudentsData = res.data; 
                window.GLOBAL_JUMLAH_LM = res.jumlah_lm || 0; 

                let html = '';
                const theadTr = document.getElementById('tableHead');
                
                // CEK APAKAH PERLU MENAMPILKAN KOLOM PAS/AKHIR SEMESTER
                const thPasHtml = (kategori === 'Akhir Semester') ? '<th class="px-4 py-4 text-center w-24">AKHIR / PAS</th>' : '';
                
                if (res.is_tahfidz) {
                    theadTr.innerHTML = `
                        <tr>
                            <th class="px-6 py-4 w-12 text-center">NO</th>
                            <th class="px-6 py-4 min-w-[200px]">NAMA SISWA</th>
                            <th class="px-4 py-4 text-center font-bold text-gray-700 dark:text-slate-300">HAFALAN TERAKHIR</th>
                            <th class="px-4 py-4 text-center text-blue-600 dark:text-blue-400">RATA-RATA TEORI</th>
                            <th class="px-4 py-4 text-center text-emerald-600 dark:text-emerald-400">P. TAJWID</th>
                            <th class="px-4 py-4 text-center text-emerald-600 dark:text-emerald-400">P. KELANCARAN</th>
                            <th class="px-4 py-4 text-center text-emerald-600 dark:text-emerald-400">P. MAKHROJ</th>
                        </tr>
                    `;
                } else {
                    theadTr.innerHTML = `
                        <tr>
                            <th class="px-6 py-4 w-12 text-center">NO</th>
                            <th class="px-6 py-4 min-w-[200px]">NAMA SISWA</th>
                            <th class="px-4 py-4 text-center w-32">RATA-RATA HARIAN</th>
                            <th class="px-4 py-4 text-center w-32">RATA-RATA UH</th>
                            <th class="px-4 py-4 text-center w-24">MID / PTS</th>
                            ${thPasHtml}
                            <th class="px-4 py-4 text-center w-32">AKSI</th>
                        </tr>
                    `;
                }

                res.data.forEach((siswa, index) => {
                    let rowHtml = `
                    <tr class="bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors group border-b border-gray-100 dark:border-slate-700/50 last:border-0">
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
                        </td>`;
                    
                    if (res.is_tahfidz) {
                        rowHtml += `
                        <td class="px-4 py-4 text-center font-bold text-slate-700 dark:text-slate-300">${siswa.tahfidz_hafalan_terakhir}</td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-12 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-black border border-blue-100 dark:border-blue-800/50">${siswa.tahfidz_rata_nilai_teori}</span>
                        </td>
                        <td class="px-4 py-4 text-center font-black text-emerald-600 dark:text-emerald-400">${siswa.tahfidz_predikat_tajwid !== '-' ? siswa.tahfidz_predikat_tajwid : '<span class="text-gray-400 font-normal">-</span>'}</td>
                        <td class="px-4 py-4 text-center font-black text-emerald-600 dark:text-emerald-400">${siswa.tahfidz_predikat_kelancaran !== '-' ? siswa.tahfidz_predikat_kelancaran : '<span class="text-gray-400 font-normal">-</span>'}</td>
                        <td class="px-4 py-4 text-center font-black text-emerald-600 dark:text-emerald-400">${siswa.tahfidz_predikat_makhroj !== '-' ? siswa.tahfidz_predikat_makhroj : '<span class="text-gray-400 font-normal">-</span>'}</td>
                        `;
                    } else {
                        // CEK APAKAH PERLU MENAMPILKAN DATA AKHIR/PAS
                        const tdPasHtml = (kategori === 'Akhir Semester') ? `<td class="px-4 py-4 text-center font-black text-gray-700 dark:text-slate-300">${siswa.pas}</td>` : '';

                        rowHtml += `
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-12 h-8 rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-black border border-blue-100 dark:border-blue-800/50">${siswa.rata_harian}</span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-12 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 font-black border border-emerald-100 dark:border-emerald-800/50">${siswa.rata_uh}</span>
                        </td>
                        <td class="px-4 py-4 text-center font-black text-gray-700 dark:text-slate-300">${siswa.pts}</td>
                        ${tdPasHtml}
                        <td class="px-4 py-4 text-center">
                            <button onclick="openDetailModal(${siswa.siswa_id})" class="px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-100 dark:hover:bg-indigo-800/50 text-indigo-600 dark:text-indigo-400 font-bold text-xs rounded-xl transition-colors outline-none border border-indigo-200 dark:border-indigo-800/50">
                                Lihat Detail
                            </button>
                        </td>
                        `;
                    }
                    
                    rowHtml += `</tr>`;
                    html += rowHtml;
                });
                tableBody.innerHTML = html;

                if(!res.is_tahfidz && window.GLOBAL_JUMLAH_LM === 0){
                    Swal.fire({
                        icon: 'info',
                        title: 'Info Konfigurasi',
                        text: 'Daftar nilai tampil, namun Rincian Nilai (Detail) tidak tersedia karena Master LM untuk mapel dan kategori ini belum diatur/kosong.',
                        confirmButtonColor: '#3b82f6'
                    });
                }

            } else {
                let msg = res.message || textObj.js_no_students;
                tableBody.innerHTML = `<tr><td colspan="7" class="text-center py-10 bg-white dark:bg-slate-800 text-gray-500 dark:text-slate-400 transition-colors font-bold">${msg}</td></tr>`;
            }
        })
        .catch(err => {
            console.error("Fetch Error:", err);
            Swal.fire({
                icon: 'error',
                title: textObj.js_swal_err_title,
                text: textObj.js_swal_err_text,
                confirmButtonColor: '#EF4444'
            });
            tableBody.innerHTML = `<tr><td colspan="7" class="text-center py-6 bg-white dark:bg-slate-800 text-red-500 dark:text-red-400 transition-colors">${textObj.js_err_load_table}</td></tr>`;
        });
}

function exportExcel() {
    const ta = document.getElementById('pilihTA').value;
    const kelas = document.getElementById('pilihKelas').value;
    const mapel = document.getElementById('pilihMapel').value;
    const kategori = document.getElementById('pilihKategori').value;

    if(!kelas || !mapel || !ta) {
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
        didOpen: () => { Swal.showLoading(); }
    });

    const exportUrl = `${API_URL}/export?ta=${ta}&kelas=${kelas}&mapel=${mapel}&kategori=${kategori}`;
    window.location.href = exportUrl;

    setTimeout(() => { Swal.close(); }, 1500);
}

function openDetailModal(siswaId) {
    const siswa = globalStudentsData.find(s => s.siswa_id == siswaId);
    if (!siswa) return;

    document.getElementById("detailModalTitle").innerText = `Rincian Nilai - ${siswa.nama}`;
    const tbody = document.getElementById("detailModalBody");
    
    let html = "";
    
    const totalLm = window.GLOBAL_JUMLAH_LM || 0; 
    
    if(totalLm === 0){
        html = `<tr><td colspan="3" class="px-4 py-8 text-center text-gray-500 dark:text-slate-400 font-medium">Belum ada Lingkup Materi (LM) yang diatur di database untuk mapel ini.</td></tr>`;
    } else {
        for (let i = 1; i <= totalLm; i++) {
            let nHarian = siswa.detail_harian[i] !== undefined ? siswa.detail_harian[i] : '-';
            let nUh = siswa.detail_uh[i] !== undefined ? siswa.detail_uh[i] : '-';
            
            let classHarian = nHarian !== '-' ? 'text-gray-900 dark:text-white font-black' : 'text-gray-400 dark:text-slate-500';
            let classUh = nUh !== '-' ? 'text-gray-900 dark:text-white font-black' : 'text-gray-400 dark:text-slate-500';

            html += `
            <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors border-b border-gray-100 dark:border-slate-700/50 last:border-0">
                <td class="px-4 py-4 text-center text-gray-800 dark:text-slate-300 font-bold">Pertemuan ke-${i}</td>
                <td class="px-4 py-4 text-center ${classHarian} text-base">${nHarian}</td>
                <td class="px-4 py-4 text-center ${classUh} text-base">${nUh}</td>
            </tr>
            `;
        }
    }
    
    tbody.innerHTML = html;

    const modal = document.getElementById("detailModal");
    modal.classList.remove("hidden");
    modal.classList.add("flex");
    document.body.style.overflow = "hidden";
}

function closeDetailModal() {
    const modal = document.getElementById("detailModal");
    modal.classList.add("hidden");
    modal.classList.remove("flex");
    document.body.style.overflow = "";
}