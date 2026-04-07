/**
 * Cetak Leger Ekskul - Admin JavaScript
 */

window.BASE_URL = window.BASE_URL || "/";
// --- TAMBAHKAN DUA BARIS INI ---
let currentLegerData = [];
let currentSummary = {};
// Tambahkan baris ini:
let currentInfoTtd = {};

async function loadLegerData() {
    const filterTaSmt = document.getElementById('filter_ta_smt');
    if (!filterTaSmt) return;

    const fullTa = filterTaSmt.value;
    const [taId, taTahun, semester] = fullTa.split('|');
    const rombel_id = document.getElementById('filter_rombel').value;
    const body = document.getElementById('legerTableBody');
    const foot = document.getElementById('legerTableFoot');

    // Loading State
    body.innerHTML = `
        <tr>
            <td colspan="${4 + EKSKUL_LIST.length}" class="text-center py-10">
                <div class="flex justify-center">
                    <div class="w-8 h-8 border-4 border-emerald-500/20 border-t-emerald-500 rounded-full animate-spin"></div>
                </div>
                <p class="mt-2 text-xs font-bold text-gray-500 uppercase tracking-widest animate-pulse">${window.LANG.loading_data}</p>
            </td>
        </tr>
    `;

    try {
        const formData = new FormData();
        formData.append('tahun_ajaran_id', taId);
        formData.append('semester', semester);
        formData.append('rombel_id', rombel_id);

        const res = await fetch(window.BASE_URL + 'admin/cetak-leger-ekskul/get-data', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        
        if (!res.ok) throw new Error('Network response was not ok');
        const result = await res.json();

        if (result.status === 'success') {
            const data = result.data;
            const summary = result.summary;

            currentLegerData = data;
            currentSummary = summary;
            // Tambahkan baris ini:
            currentInfoTtd = result.info_ttd || {};
            
            // Update UI Counters
            const totalCountEl = document.getElementById('total_peserta_count');
            if (totalCountEl) {
                let current = 0;
                const target = data.length;
                const step = target / 20;
                const counter = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        totalCountEl.textContent = target;
                        clearInterval(counter);
                    } else {
                        totalCountEl.textContent = Math.floor(current);
                    }
                }, 30);
            }

            const printSub = document.getElementById('printSubtitle');
            if (printSub) {
                printSub.textContent = `${window.LANG.class_prefix} ${document.getElementById('filter_rombel').selectedOptions[0].text} - ${taTahun} ${semester.toUpperCase()}`;
            }

            if (data.length === 0) {
                body.innerHTML = `<tr><td colspan="${4 + EKSKUL_LIST.length}" class="text-center py-12 text-gray-400 font-medium italic">${window.LANG.no_data}</td></tr>`;
                foot.innerHTML = '';
                return;
            }

            let html = '';
            data.forEach((row, idx) => {
                let cells = '';
                EKSKUL_LIST.forEach(ek => {
                    const pred = row.nilai[ek.id] || '-';
                    let colorClass = 'text-gray-400 font-medium';
                    
                    if (pred === 'A') colorClass = 'text-emerald-600 dark:text-emerald-400 font-black';
                    else if (pred === 'B') colorClass = 'text-blue-600 dark:text-blue-400 font-bold';
                    else if (pred === 'C') colorClass = 'text-amber-600 dark:text-amber-400 font-bold';
                    else if (pred === 'D') colorClass = 'text-rose-500 font-bold';

                    cells += `<td class="px-3 py-4 text-center border-b border-gray-50 dark:border-slate-700/50 ${colorClass} transition-colors">${pred}</td>`;
                });

                html += `
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-all duration-200">
                        <td class="px-4 py-4 text-center text-[10px] font-bold text-gray-400 border-b border-gray-50 dark:border-slate-700/50 md:sticky md:left-0 bg-white dark:bg-slate-800 z-10">${row.no}</td>
                        <td class="px-4 py-4 text-center text-xs font-mono text-gray-500 border-b border-gray-50 dark:border-slate-700/50 md:sticky md:left-[50px] bg-white dark:bg-slate-800 z-10">${row.nis}</td>
                        <td class="px-4 py-4 font-bold text-gray-700 dark:text-slate-200 border-b border-gray-50 dark:border-slate-700/50 md:sticky md:left-[130px] bg-white dark:bg-slate-800 z-10 shadow-[2px_0_5px_rgba(0,0,0,0.05)]">${row.nama}</td>
                        ${cells}
                        <td class="px-4 py-4 text-center font-black text-emerald-600 dark:text-emerald-400 border-b border-gray-50 dark:border-slate-700/50 bg-emerald-50/20 dark:bg-emerald-900/10">${row.total_ekskul}</td>
                    </tr>
                `;
            });
            body.innerHTML = html;

            // Summary Row
            let footCells = '';
            EKSKUL_LIST.forEach(ek => {
                const count = summary[ek.id] || 0;
                footCells += `<td class="px-3 py-5 text-center text-gray-900 dark:text-white border-b border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-900/50 font-black">${count}</td>`;
            });

            foot.innerHTML = `
                <tr class="bg-gray-100/30 dark:bg-slate-900/30 text-xs uppercase tracking-widest ring-1 ring-gray-100 dark:ring-slate-700">
                    <td colspan="3" class="px-6 py-5 text-right pr-8 font-black text-gray-600 dark:text-slate-400">${window.LANG.total_active}</td>
                    ${footCells}
                    <td class="px-4 py-5 bg-gray-200/20 dark:bg-slate-800/20 shadow-inner"></td>
                </tr>
            `;
        }
    } catch (e) {
        console.error('Leger Error:', e);
        body.innerHTML = `<tr><td colspan="${4 + EKSKUL_LIST.length}" class="text-center py-16 text-rose-500 font-bold flex flex-col items-center gap-2"><svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg> ${window.LANG.err_server}</td></tr>`;
    }
}

window.exportExcel = function(event) {
    const filterTaSmt = document.getElementById('filter_ta_smt');
    if (!filterTaSmt) return;

    const taSmt = filterTaSmt.value;
    const rombel_id = document.getElementById('filter_rombel').value;
    
    // Add effect
    const btn = event.currentTarget;
    const origHtml = btn.innerHTML;
    btn.innerHTML = `<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span>${window.LANG.exporting}</span>`;
    btn.disabled = true;

    setTimeout(() => {
        window.location.href = window.BASE_URL + 'admin/cetak-leger-ekskul/export-excel?ta_smt=' + encodeURIComponent(taSmt) + '&rombel_id=' + rombel_id;
        btn.innerHTML = origHtml;
        btn.disabled = false;
    }, 800);
}

// Initial Load
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('legerTable')) {
        loadLegerData();
    }
});

// ==========================================
// FUNGSI PRINT LEGER EKSKUL (CHUNKING)
// ==========================================
function printLegerEkskul() {
    if (currentLegerData.length === 0) {
        alert('Tidak ada data untuk dicetak!');
        return;
    }
    
    // 1. Bersihkan area print lama
    let oldPrintArea = document.getElementById('printArea');
    if (oldPrintArea) oldPrintArea.remove();

    const printArea = document.createElement('div');
    printArea.id = 'printArea';
    document.body.appendChild(printArea);

    // 2. Ambil Info
    const selectRombel = document.getElementById('filter_rombel');
    const namaKelas = selectRombel.options[selectRombel.selectedIndex].text;
    const fullTa = document.getElementById('filter_ta_smt').value;
    const [taId, taTahun, semester] = fullTa.split('|');

    // 3. Logika Chunking (15 per halaman)
    const batasPerHalaman = 15; 
    let chunks = [];
    for (let i = 0; i < currentLegerData.length; i += batasPerHalaman) {
        chunks.push(currentLegerData.slice(i, i + batasPerHalaman));
    }

    let finalHtml = '';

    chunks.forEach((chunkData, index) => {
        const halamanKe = index + 1;
        const totalHalaman = chunks.length;
        const isLastPage = (halamanKe === totalHalaman);
        const classPotong = isLastPage ? '' : 'potong-kertas';

        finalHtml += `<div class="${classPotong}" style="width: 100%; padding-top: 10px;">`;
        
        // KOP SURAT
        finalHtml += `
            <div style="text-align: center; margin-bottom: 15px;">
                <h2 style="font-size: 18px; font-family: Arial, sans-serif; font-weight: bold; margin: 0 0 5px 0;">LEGER EKSTRAKURIKULER SISWA (Hal. ${halamanKe}/${totalHalaman})</h2>
                <p style="font-size: 12px; font-family: Arial, sans-serif; margin: 0;">
                    <strong>Kelas:</strong> ${namaKelas} &nbsp;|&nbsp; 
                    <strong>Semester:</strong> ${semester} &nbsp;|&nbsp; 
                    <strong>Tahun Ajaran:</strong> ${taTahun}
                </p>
            </div>
        `;

        // HEADER TABEL
        finalHtml += `
            <table>
                <thead>
                    <tr style="background-color: #e5e7eb;">
                        <th style="width: 40px;">NO</th>
                        <th style="width: 80px;">NIS</th>
                        <th class="text-left" style="min-width: 200px;">NAMA SISWA</th>
        `;
        EKSKUL_LIST.forEach(ek => { finalHtml += `<th>${ek.nama_ekskul}</th>`; });
        finalHtml += `
                        <th style="width: 60px;">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
        `;

        // ISI TABEL
        chunkData.forEach((siswa) => {
            const nomorUrutAsli = currentLegerData.indexOf(siswa) + 1;
            finalHtml += `
                <tr>
                    <td>${nomorUrutAsli}</td>
                    <td>${siswa.nis}</td>
                    <td class="text-left"><strong>${siswa.nama}</strong></td>
            `;
            EKSKUL_LIST.forEach(ek => {
                const pred = siswa.nilai[ek.id] || '-';
                finalHtml += `<td>${pred}</td>`;
            });
            finalHtml += `
                    <td style="font-weight: bold; background-color: #f8fafc;">${siswa.total_ekskul}</td>
                </tr>
            `;
        });
        
        // BARIS TOTAL (KHUSUS HALAMAN TERAKHIR)
        if (isLastPage) {
            finalHtml += `
                <tr style="background-color: #f3f4f6; font-weight: bold;">
                    <td colspan="3" style="text-align: right; padding-right: 15px;">TOTAL SISWA AKTIF EKSKUL</td>
            `;
            EKSKUL_LIST.forEach(ek => {
                const count = currentSummary[ek.id] || 0;
                finalHtml += `<td>${count}</td>`;
            });
            finalHtml += `<td></td></tr>`;
        }

        finalHtml += `</tbody></table>`;

        // TANDA TANGAN (KHUSUS HALAMAN TERAKHIR)
        if (isLastPage) {
            // Ambil data TTD dari memori
            const namaWali = currentInfoTtd.wali_nama || 'Belum Diatur';
            const nipWali = (currentInfoTtd.wali_nip && currentInfoTtd.wali_nip !== '-') ? `NIP. ${currentInfoTtd.wali_nip}` : 'NIP. -';
            
            const namaKepsek = currentInfoTtd.kepsek_nama || 'Belum Diatur';
            const nipKepsek = (currentInfoTtd.kepsek_nip && currentInfoTtd.kepsek_nip !== '-') ? `NIP. ${currentInfoTtd.kepsek_nip}` : 'NIP. -';

            finalHtml += `
                <table style="width: 100%; border: none !important; margin-top: 40px;">
                    <tr>
                        <td style="border: none !important; padding-bottom: 60px; font-size: 11px;">Wali Kelas ${namaKelas}</td>
                        <td style="border: none !important; padding-bottom: 60px; font-size: 11px;">Kepala Sekolah</td>
                    </tr>
                    <tr>
                        <td style="border: none !important; font-size: 11px;"><strong>${namaWali}</strong><br>${nipWali}</td>
                        <td style="border: none !important; font-size: 11px;"><strong>${namaKepsek}</strong><br>${nipKepsek}</td>
                    </tr>
                </table>
                <div style="text-align: center; margin-top: 15px; font-family: Arial, sans-serif; font-size: 8px; border-top: 1px solid #000; padding-top: 8px;">
                    <strong>SMPIT Ad Durrah</strong> • Tahun Ajaran ${taTahun} Semester ${semester}<br>
                    DOKUMEN RESMI • STATUS: TERKUNCI
                </div>
            `;
        }

        finalHtml += `</div>`; 
    });

    printArea.innerHTML = finalHtml;

    // --- TRIK SULAP NAMA FILE PDF ---
    const originalTitle = document.title;
    const namaFileBersih = namaKelas.replace(/[^a-zA-Z0-9]/g, '_');
    document.title = `Leger_Ekskul_${namaFileBersih}_${semester}`;

    setTimeout(() => { window.print(); }, 500);

    // --- PASUKAN PEMBERSIH ---
    window.onafterprint = function() {
        const sisaPrint = document.getElementById('printArea');
        if (sisaPrint) sisaPrint.remove();
        document.title = originalTitle; // Kembalikan judul asli
    };
}
