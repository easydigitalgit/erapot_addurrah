// ==========================================
// KODE CETAK LEGER
// ==========================================
const LANG = window.LANG || {
    js_loading: 'Memuat data dari database...',
    js_err_load: 'Gagal memuat data',
    js_err_net: 'Terjadi kesalahan jaringan',
    js_empty_data: 'Belum ada data nilai terkunci untuk kelas ini.',
    js_show_num: 'Nilai angka ditampilkan',
    js_hide_num: 'Nilai angka disembunyikan',
    js_show_pred: 'Predikat ditampilkan',
    js_hide_pred: 'Predikat disembunyikan',
    js_preparing: 'Sedang disiapkan...',
    js_preparing_print: 'Menyiapkan dokumen untuk dicetak...'
};

let currentLegerData = [];
let currentSortMode = 'abjad'; 

function hitungRataRata(siswa) {
    let total = 0;
    let count = MAPEL_DATA.length;
    
    if (count === 0) return 0;
    
    MAPEL_DATA.forEach(mapel => {
        total += parseFloat(siswa.nilai[mapel.id].angka) || 0;
    });
    
    return (total / count).toFixed(1);
}

function loadLegerData() {
  const tbody = document.getElementById('legerTableBody');
  
  const totalCols = 3 + (MAPEL_DATA.length * 2) + 2;

  tbody.innerHTML = `<tr class="bg-white dark:bg-slate-800 transition-colors"><td colspan="${totalCols}" class="text-center py-12 text-gray-500 dark:text-slate-400 font-medium text-sm border-b border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800"><div class="flex flex-col items-center justify-center gap-2"><svg class="w-8 h-8 text-gray-300 dark:text-slate-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>${LANG.js_loading}</div></td></tr>`;

  const ta_smt_val = document.getElementById('filter_ta_smt').value;
  const [ta_id, ta_tahun, ta_semester] = ta_smt_val.split('|');
  const rombel_id = document.getElementById('filter_rombel').value;
  const kategori_val = document.getElementById('filter_kategori').value; 

  const formData = new FormData();
  formData.append('tahun_ajaran', ta_id); 
  formData.append('semester', ta_semester);
  formData.append('rombel_id', rombel_id); 
  formData.append('kategori', kategori_val);

  const baseUrl = window.BASE_URL || '/';
  fetch(`${baseUrl}admin/cetak-leger/get-data`, {
      method: 'POST',
      body: formData,
      headers: { "X-Requested-With": "XMLHttpRequest" }
  })
  .then(response => response.json())
  .then(res => {
      if (res.status === 'success') {
          currentLegerData = res.data; 
          
          // --- TAMBAHKAN BARIS INI ---
          window.globalInfoKelas = res.info_kelas; 

          renderTable(currentLegerData);
          updateInfoKelas(res.info_kelas); 
      } else {
          tbody.innerHTML = `<tr class="bg-white dark:bg-slate-800 transition-colors"><td colspan="${totalCols}" class="text-center py-12 text-red-500 font-medium text-sm border-b border-gray-200 dark:border-slate-700">${LANG.js_err_load}</td></tr>`;
      }
  })
  .catch(err => {
      console.error(err);
      tbody.innerHTML = `<tr class="bg-white dark:bg-slate-800 transition-colors"><td colspan="${totalCols}" class="text-center py-12 text-red-500 font-medium text-sm border-b border-gray-200 dark:border-slate-700">${LANG.js_err_net}</td></tr>`;
  });
}

function changeSortMode() {
    currentSortMode = document.getElementById('sort_mode').value;
    if(currentLegerData.length > 0) {
        renderTable(currentLegerData);
    }
}

function renderTable(dataSiswa) {
    const tbody = document.getElementById('legerTableBody');
    tbody.innerHTML = '';
    const totalCols = 3 + (MAPEL_DATA.length * 2) + 2;

    if(dataSiswa.length === 0) {
        tbody.innerHTML = `<tr class="bg-white dark:bg-slate-800 transition-colors"><td colspan="${totalCols}" class="text-center py-12 text-gray-500 dark:text-slate-400 font-medium text-sm border-b border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800"><div class="flex flex-col items-center justify-center gap-2"><svg class="w-8 h-8 text-gray-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>${LANG.js_empty_data}</div></td></tr>`;
        updateStatisticsCards([]);
        return;
    }
  
    let processedData = dataSiswa.map(s => ({
        ...s,
        avg: parseFloat(hitungRataRata(s))
    }));

    processedData.sort((a, b) => b.avg - a.avg);
    processedData.forEach((siswa, index) => {
        siswa.rank = index + 1;
    });

    updateStatisticsCards(processedData);

    if (currentSortMode === 'abjad') {
        processedData.sort((a, b) => a.nama.localeCompare(b.nama));
    } else {
        processedData.sort((a, b) => a.rank - b.rank);
    }  

    // --- TAMBAHKAN BARIS INI UNTUK MENYELAMATKAN DATA PRINT ---
    currentLegerData = processedData;

    const highest = {};
    const lowest = {};
    
    MAPEL_DATA.forEach(mapel => {
        const mId = mapel.id;
        const values = processedData.map(s => s.nilai[mId].angka).filter(v => v > 0); 
        highest[mId] = values.length > 0 ? Math.max(...values) : 0;
        lowest[mId] = values.length > 0 ? Math.min(...values) : 0;
    });
  
    processedData.forEach((siswa, index) => {
        const rank = siswa.rank; 
        const isTopStudent = rank === 1;
        
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-200 border-b border-gray-200 dark:border-slate-700';
        
        let htmlContent = `
            <td class="p-2 text-center border-r border-gray-200 dark:border-slate-700 md:sticky md:left-0 bg-white dark:bg-slate-800 z-10">${index + 1}</td> 
            <td class="p-2 text-center border-r border-gray-200 dark:border-slate-700 md:sticky md:left-[40px] bg-white dark:bg-slate-800 z-10">${siswa.nis}</td>
            <td class="text-left font-medium p-2 border-r border-gray-200 dark:border-slate-700 sticky left-0 md:left-[120px] bg-white dark:bg-slate-800 z-10 whitespace-nowrap min-w-[200px]">${siswa.nama}</td>
        `;

        MAPEL_DATA.forEach(mapel => {
            const mId = mapel.id;
            const nAngka = siswa.nilai[mId].angka;
            const nPredikat = siswa.nilai[mId].predikat;

            let colorClass = '';
            if (nAngka === highest[mId] && nAngka > 0) colorClass = 'text-green-600 font-bold';
            else if (nAngka === lowest[mId] && nAngka > 0) colorClass = 'text-red-600 font-bold';

            htmlContent += `
                <td class="nilai-angka p-2 text-center border-r border-gray-200 dark:border-slate-700 ${colorClass}">${nAngka}</td>
                <td class="nilai-predikat p-2 text-center border-r border-gray-200 dark:border-slate-700">${nPredikat}</td>
            `;
        });
        
        htmlContent += `
            <td class="p-2 text-center border-r border-gray-200 dark:border-slate-700 ${isTopStudent ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : ''}" style="font-weight: 700;">${siswa.avg}</td>
            <td class="p-2 text-center ${isTopStudent ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'text-emerald-600 dark:text-emerald-500'}" style="font-weight: 900;">${rank}</td>
        `;
        
        row.innerHTML = htmlContent;
        tbody.appendChild(row);
    });
}

function updateStatisticsCards(dataSiswa) {
    let allValidScores = [];

    dataSiswa.forEach(siswa => {
        MAPEL_DATA.forEach(mapel => {
            const mId = mapel.id;
            let nilai = parseFloat(siswa.nilai[mId].angka);
            if (nilai > 0) {
                allValidScores.push({
                    nama: siswa.nama,
                    mapel: mapel.nama_mapel, 
                    nilai: nilai,
                    kkm: mapel.kkm 
                });
            }
        });
    });

    if (allValidScores.length === 0) {
        document.getElementById('stat_rata_kelas').innerText = '0';
        document.getElementById('bar_rata_kelas').style.width = '0%';
        document.getElementById('stat_nilai_tertinggi').innerText = '0';
        document.getElementById('text_nilai_tertinggi').innerText = '-';
        document.getElementById('stat_nilai_terendah').innerText = '0';
        document.getElementById('text_nilai_terendah').innerText = '-';
        document.getElementById('stat_ketuntasan').innerText = '0%';
        document.getElementById('bar_ketuntasan').style.width = '0%';
        return;
    }

    const totalNilai = allValidScores.reduce((sum, item) => sum + item.nilai, 0);
    const rataKelas = (totalNilai / allValidScores.length).toFixed(1);

    allValidScores.sort((a, b) => a.nilai - b.nilai);
    const terendah = allValidScores[0];
    const tertinggi = allValidScores[allValidScores.length - 1];

    const tuntasCount = allValidScores.filter(item => item.nilai >= (item.kkm || 75)).length;
    const persenTuntas = Math.round((tuntasCount / allValidScores.length) * 100);

    document.getElementById('stat_rata_kelas').innerText = rataKelas;
    document.getElementById('bar_rata_kelas').style.width = rataKelas + '%';

    document.getElementById('stat_nilai_tertinggi').innerText = tertinggi.nilai;
    document.getElementById('text_nilai_tertinggi').innerText = `${tertinggi.nama} - ${tertinggi.mapel}`;

    document.getElementById('stat_nilai_terendah').innerText = terendah.nilai;
    document.getElementById('text_nilai_terendah').innerText = `${terendah.nama} - ${terendah.mapel}`;

    document.getElementById('stat_ketuntasan').innerText = persenTuntas + '%';
    document.getElementById('bar_ketuntasan').style.width = persenTuntas + '%';
}

function toggleNilaiAngka() {
    const isChecked = event.target.checked;
    const cells = document.querySelectorAll('.nilai-angka');
    cells.forEach(cell => {
        cell.style.display = isChecked ? '' : 'none';
    });
    showToast(isChecked ? LANG.js_show_num : LANG.js_hide_num, 'success');
}

function togglePredikat() {
    const isChecked = event.target.checked;
    const cells = document.querySelectorAll('.nilai-predikat');
    cells.forEach(cell => {
        cell.style.display = isChecked ? '' : 'none';
    });
    showToast(isChecked ? LANG.js_show_pred : LANG.js_hide_pred, 'success');
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = 'toast fixed top-4 right-4 z-[100000] flex items-center gap-3 px-4 py-3 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-xl shadow-lg transition-all duration-300';
    
    const iconColor = type === 'success' ? 'text-emerald-600 dark:text-emerald-400' : (type === 'error' ? 'text-red-600 dark:text-red-400' : 'text-blue-600 dark:text-blue-400');
    const bgColor = type === 'success' ? 'bg-emerald-100 dark:bg-emerald-900/30' : (type === 'error' ? 'bg-red-100 dark:bg-red-900/30' : 'bg-blue-100 dark:bg-blue-900/30');
    
    let iconPath = '';
    if(type === 'success') iconPath = 'M5 13l4 4L19 7';
    else if(type === 'error') iconPath = 'M6 18L18 6M6 6l12 12';
    else iconPath = 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';

    toast.innerHTML = `
        <div class="w-10 h-10 rounded-full flex items-center justify-center ${bgColor} flex-shrink-0">
            <svg class="w-6 h-6 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPath}"/>
            </svg>
        </div>
        <div>
            <p class="font-semibold text-gray-800 dark:text-white text-sm">${message}</p>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function printLeger() {
    if (currentLegerData.length === 0) {
        showToast('Tidak ada data untuk dicetak!', 'error');
        return;
    }
    showToast('Menyiapkan pemotongan halaman otomatis...', 'info');
    
    // 1. HAPUS KANVAS LAMA JIKA ADA, LALU BUAT BARU DI BAWAH <body>
    let oldPrintArea = document.getElementById('printArea');
    if (oldPrintArea) oldPrintArea.remove();

    const printArea = document.createElement('div');
    printArea.id = 'printArea';
    document.body.appendChild(printArea);

    // 2. AMBIL VARIABEL FILTER SEPERTI BIASA
    const selectRombel = document.getElementById('filter_rombel');
    const namaKelas = selectRombel.options[selectRombel.selectedIndex].text;
    const kategori_val = document.getElementById('filter_kategori').value; 
    const ta_smt_val = document.getElementById('filter_ta_smt').value;
    const [ta_id, ta_tahun, ta_semester] = ta_smt_val.split('|');

    // 3. AMBIL DATA TANDA TANGAN DARI FOOTER ASLI
    const info = window.globalInfoKelas || {};
    
    const namaWali = info.wali_kelas || 'Belum Diatur';
    const nipWali = (info.wali_nip && info.wali_nip !== '-') ? `NIP. ${info.wali_nip}` : 'NIP. -';
    
    const namaWaka = info.waka_nama || 'Belum Diatur';
    const nipWaka = (info.waka_nip && info.waka_nip !== '-') ? `NIP. ${info.waka_nip}` : 'NIP. -';
    
    const namaKepsek = info.kepsek_nama || 'Belum Diatur';
    const nipKepsek = (info.kepsek_nip && info.kepsek_nip !== '-') ? `NIP. ${info.kepsek_nip}` : 'NIP. -';

    // =======================================================
    // 🧠 4. LOGIKA PEMOTONGAN DATA (CHUNKING) IDE ANDA 🧠
    // =======================================================
    const batasPerHalaman = 15; // <-- Jumlah maksimal per halaman
    let chunks = [];
    
    // Potong array data siswa menjadi grup-grup kecil
    for (let i = 0; i < currentLegerData.length; i += batasPerHalaman) {
        chunks.push(currentLegerData.slice(i, i + batasPerHalaman));
    }

    let finalHtml = '';

    // Lakukan Perulangan untuk Membangun Halaman (Satu Chunk = Satu Kertas)
    chunks.forEach((chunkData, index) => {
        const halamanKe = index + 1;
        const totalHalaman = chunks.length;
        const isLastPage = (halamanKe === totalHalaman);

        // ✂️ PASANG CLASS 'potong-kertas' JIKA BUKAN HALAMAN TERAKHIR ✂️
        const classPotong = isLastPage ? '' : 'potong-kertas';
        finalHtml += `<div class="${classPotong}" style="width: 100%; margin-bottom: 20px;">`;
        
        // KOP SURAT (Terulang di Setiap Halaman)
        finalHtml += `
            <div style="text-align: center; margin-bottom: 10px;">
                <h2 style="font-size: 16px; font-family: Arial, sans-serif; font-weight: bold; margin: 0 0 5px 0;">LEGER NILAI AKADEMIK SISWA (Hal. ${halamanKe}/${totalHalaman})</h2>
                <p style="font-size: 11px; font-family: Arial, sans-serif; margin: 0;">
                    <strong>Kelas:</strong> ${namaKelas} &nbsp;|&nbsp; 
                    <strong>Kategori:</strong> ${kategori_val} &nbsp;|&nbsp; 
                    <strong>Semester:</strong> ${ta_semester} &nbsp;|&nbsp; 
                    <strong>T.A:</strong> ${ta_tahun}
                </p>
            </div>
        `;

        // HEADER TABEL (Terulang di Setiap Halaman)
        finalHtml += `
            <table>
                <thead>
                    <tr style="background-color: #e5e7eb;">
                        <th rowspan="2">NO</th>
                        <th rowspan="2">NIS</th>
                        <th rowspan="2" class="text-left" style="min-width: 150px;">NAMA SISWA</th>
        `;
        MAPEL_DATA.forEach(m => { finalHtml += `<th colspan="2">${m.nama_mapel}</th>`; });
        finalHtml += `
                        <th rowspan="2">RATA</th>
                        <th rowspan="2">RANK</th>
                    </tr>
                    <tr style="background-color: #e5e7eb;">
        `;
        MAPEL_DATA.forEach(() => { finalHtml += `<th>NILAI</th><th>PRED</th>`; });
        finalHtml += `</tr></thead><tbody>`;

        // ISI DATA TABEL KHUSUS 15 ORANG DI HALAMAN INI
        chunkData.forEach((siswa) => {
            // Cari Nomor Urut aslinya di kelas (Melanjutkan dari 16, 17, dst)
            const nomorUrutAsli = currentLegerData.indexOf(siswa) + 1;
            
            finalHtml += `
                <tr>
                    <td>${nomorUrutAsli}</td>
                    <td>${siswa.nis}</td>
                    <td class="text-left"><strong>${siswa.nama}</strong></td>
            `;
            MAPEL_DATA.forEach(m => {
                finalHtml += `
                    <td>${siswa.nilai[m.id].angka}</td>
                    <td>${siswa.nilai[m.id].predikat}</td>
                `;
            });
            finalHtml += `
                    <td style="font-weight: bold; background-color: #f8fafc;">${siswa.avg}</td>
                    <td style="font-weight: bold; background-color: #f8fafc;">${siswa.rank}</td>
                </tr>
            `;
        });
        
        finalHtml += `</tbody></table>`;

        // FOOTER TANDA TANGAN (HANYA MUNCUL DI HALAMAN PALING TERAKHIR)
        if (isLastPage) {
            finalHtml += `
                <table style="width: 100%; border: none !important; margin-top: 30px;">
                    <tr>
                        <td style="border: none !important; padding-bottom: 50px; font-size: 11px;">Wali Kelas ${namaKelas}</td>
                        <td style="border: none !important; padding-bottom: 50px; font-size: 11px;">Wakil Kurikulum</td>
                        <td style="border: none !important; padding-bottom: 50px; font-size: 11px;">Kepala Sekolah</td>
                    </tr>
                    <tr>
                        <td style="border: none !important; font-size: 11px;"><strong>${namaWali}</strong><br>${nipWali}</td>
                        <td style="border: none !important; font-size: 11px;"><strong>${namaWaka}</strong><br>${nipWaka}</td>
                        <td style="border: none !important; font-size: 11px;"><strong>${namaKepsek}</strong><br>${nipKepsek}</td>
                    </tr>
                </table>
                <div style="text-align: center; margin-top: 15px; font-family: Arial, sans-serif; font-size: 8px; border-top: 1px solid #000; padding-top: 8px;">
                    <strong>SMPIT Ad Durrah</strong> • Tahun Ajaran ${ta_tahun} Semester ${ta_semester}<br>
                    DOKUMEN RESMI • NO. LEGER: LGR/VII-A/2025/001 • STATUS: TERKUNCI
                </div>
            `;
        }

        finalHtml += `</div>`; // Tutup div satu kertas
    });

    // 5. MASUKKAN SEMUA HTML YANG SUDAH DIPOTONG KE DALAM KANVAS
    printArea.innerHTML = finalHtml;

    // --- TRIK SULAP NAMA FILE PDF ---
    const originalTitle = document.title; // Simpan judul web yang asli
    
    // Ganti judul tab sementara (Browser akan membaca ini sebagai nama file)
    // Hasilnya akan seperti: Leger_Nilai_Amazonite_Tengah_Semester
    const namaFileBersih = namaKelas.replace(/[^a-zA-Z0-9]/g, '_');
    const kategoriBersih = kategori_val.replace(/[^a-zA-Z0-9]/g, '_');
    document.title = `Leger_Nilai_${namaFileBersih}_${kategoriBersih}`;

    // 6. PANGGIL MESIN PRINT BROWSER
    setTimeout(() => { 
        window.print(); 
    }, 500);

    // 7. PASUKAN PEMBERSIH (CLEANER) 🧹
    window.onafterprint = function() {
        const sisaPrint = document.getElementById('printArea');
        if (sisaPrint) sisaPrint.remove();
        
        // Kembalikan judul web ke aslinya setelah selesai print/cancel
        document.title = originalTitle;
    };
}

document.addEventListener('DOMContentLoaded', () => {
  loadLegerData();
});

function exportExcel() {
    showToast('Menyiapkan file Excel dari server...', 'info');
    
    const ta_smt_val = document.getElementById('filter_ta_smt').value;
    const [ta_id, ta_tahun, ta_semester] = ta_smt_val.split('|');
    const rombel_id = document.getElementById('filter_rombel').value;
    const kategori_val = document.getElementById('filter_kategori').value; 
    
    const baseUrl = window.BASE_URL || '/';
    const url = `${baseUrl}admin/cetak-leger/export-excel?tahun_ajaran=${encodeURIComponent(ta_id)}&semester=${encodeURIComponent(ta_semester)}&rombel_id=${rombel_id}&kategori=${encodeURIComponent(kategori_val)}`;
    
    window.location.href = url;
}

// Fungsi untuk mengubah panel informasi kelas secara dinamis
function updateInfoKelas(info) {
    if (!info) return;
    
    document.getElementById('info_nama_rombel').innerText = info.nama_rombel || '-';
    document.getElementById('info_wali_kelas').innerText = info.wali_kelas || 'Belum Diatur';
    document.getElementById('info_jumlah_siswa').innerText = info.jumlah_siswa || '0';
    document.getElementById('info_kurikulum').innerText = info.kurikulum || '-';
    
    const statusEl = document.getElementById('info_status');
    if (statusEl) {
        // Hapus warna lama, tambahkan warna baru secara dinamis
        statusEl.className = `badge-chip text-[10px] font-bold px-2 py-1 rounded bg-${info.status_color}-100 dark:bg-${info.status_color}-900/30 text-${info.status_color}-800 dark:text-${info.status_color}-200 border border-${info.status_color}-200 dark:border-${info.status_color}-800/50 transition-colors`;
        
        // Atur Icon (Terkunci vs Kosong)
        let icon = '';
        if (info.status_text === 'TERKUNCI') {
            icon = `<svg class="w-3 h-3 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>`;
        } else {
            icon = `<svg class="w-3 h-3 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`;
        }
        
        statusEl.innerHTML = icon + info.status_text;
    }
}