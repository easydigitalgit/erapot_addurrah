/**
 * File: public/assets/js/Tahfidz/nilai-rapor.js
 */

const BASE_URL = document.querySelector('meta[name="base-url"]')?.content || (typeof window.BASE_URL !== 'undefined' ? window.BASE_URL : "");

setInterval(() => {
    const now = new Date();
    const el = document.getElementById('realtimeClock');
    if(el) el.innerText = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';
}, 1000);

let totalSantri = 0;

const getInitials = (name) => {
    let initials = name.match(/\b\w/g) || [];
    return ((initials.shift() || '') + (initials.pop() || '')).toUpperCase();
};

function cariSantri() {
    let inputElem = document.getElementById("searchInput");
    if(!inputElem) return;
    
    let input = inputElem.value.toLowerCase();
    let baris = document.querySelectorAll("#tbodyNilai .baris-santri"); 
    baris.forEach(row => {
        let namaSantriElem = row.querySelector(".nama-santri");
        if(namaSantriElem) {
            let namaSantri = namaSantriElem.innerText.toLowerCase();
            row.style.display = namaSantri.includes(input) ? "" : "none";
        }
    });
}

// [PERBAIKAN] FUNGSI STANDARISASI KONVERSI NILAI
function getGrade(val) {
    if (val === '' || isNaN(val) || val <= 0) return { huruf: '-', derajat: 'Belum Hafal', taqdir: '-', color: 'slate' };
    
    let huruf, derajat, color;
    if (val > 87) { huruf = 'A'; derajat = 'Mutqin'; color = 'emerald'; }
    else if (val > 70) { huruf = 'B'; derajat = 'Jayyid Jiddan'; color = 'blue'; }
    else if (val > 60) { huruf = 'C'; derajat = 'Jayyid'; color = 'amber'; }
    else if (val > 50) { huruf = 'D'; derajat = 'Maqbül'; color = 'orange'; }
    else { huruf = 'E'; derajat = 'Mardüd'; color = 'rose'; }

    let taqdir = ['Mutqin', 'Jayyid Jiddan', 'Jayyid', 'Maqbül'].includes(derajat) ? "Najah" : "I'adah";
    return { huruf, derajat, taqdir, color };
}

async function loadSiswa() {
    const kelas_id = document.getElementById('kelasSelect').value;
    const juzName  = document.getElementById('juzSelect').value;

    if(!kelas_id || !juzName) { 
        document.getElementById('emptyState').style.display = 'block';
        document.getElementById('tableContainer').style.display = 'none';
        return; 
    }
    
    document.getElementById('emptyState').style.display = 'none';
    document.getElementById('tableContainer').style.display = 'block';
    
    const selectElement = document.getElementById('kelasSelect');
    const optJuz = document.getElementById('juzSelect').options[document.getElementById('juzSelect').selectedIndex];
    const juzText = optJuz ? optJuz.text : "Juz " + juzName;
    const namaKelas = selectElement.options[selectElement.selectedIndex].text;
    document.getElementById('infoKelas').textContent = `- ${namaKelas} (${juzText})`;
    CONFIG.class_name = selectElement.options[selectElement.selectedIndex].getAttribute('data-name') || namaKelas;

    document.getElementById('formJuzInput').value = juzName;

    const tbody = document.getElementById('tbodyNilai');
    tbody.innerHTML = `<tr><td colspan="11" class="text-center p-20"><div class="w-12 h-12 border-4 border-slate-200 rounded-full animate-spin mx-auto mb-4" style="border-top-color: var(--warna-utama);"></div><p class="text-slate-500 font-bold tracking-wide">Menghitung rata-rata nilai ${juzText} dan merangkai narasi otomatis...</p></td></tr>`;

    try {
        const response = await fetch(`${CONFIG.fetch_url}?rombel_id=${kelas_id}&juz=${juzName}`);
        const result = await response.json();

        if (result.status === 'success') {
            totalSantri = result.data.length;
            globalStudentsRapor = result.data; 
            
            let html = '';

            if(totalSantri === 0) {
                html = `<tr><td colspan="11" class="text-center p-12 text-rose-500 font-bold">Tidak ada santri pada rombel ini.</td></tr>`;
                document.getElementById('progressText').innerText = `0% (0/0 Santri)`;
                document.getElementById('progressBar').style.width = `0%`;
            } else {
                result.data.forEach((siswa, index) => {
                    let capaian = (siswa.surah_terakhir && siswa.surah_terakhir !== '-') ? `${siswa.surah_terakhir} (Ayat ${siswa.ayat_terakhir})` : 'Belum Mulai';
                    
                    // --- LOGIKA HYBRID AVATAR ---
                    let avatarHtml = '';
                    const cleanBase = (typeof BASE_URL !== 'undefined' ? BASE_URL : window.location.origin).replace(/\/$/, ""); 
                    
                    if (siswa.foto_fix && siswa.foto_fix !== 'null' && String(siswa.foto_fix).trim() !== '') {
                        const cacheBuster = '?v=' + new Date().getTime();
                        const urlAvatars = `${cleanBase}/assets/uploads/avatars/${siswa.foto_fix}${cacheBuster}`;
                        const urlSiswa = `${cleanBase}/uploads/siswa/${siswa.foto_fix}${cacheBuster}`;
                        const fallbackInitial = getInitials(siswa.nama_lengkap);
                        
                        // Coba load dari folder avatars -> kalau gagal, coba folder siswa -> kalau gagal lagi, pakai inisial
                        avatarHtml = `<img src="${urlAvatars}" class="w-full h-full object-cover" alt="Foto" onerror="this.onerror=function(){ this.outerHTML='${fallbackInitial}'; }; this.src='${urlSiswa}';">`;
                    } else {
                        avatarHtml = getInitials(siswa.nama_lengkap);
                    }
                    // ----------------------------

                    // =========================================================
                    // LOGIKA FULL AUTOMATION (HAFALAN + TEORI)
                    // =========================================================
                    let rata = siswa.rata_rata ? parseInt(siswa.rata_rata) : 0;
                    let nTeori = siswa.nilai_teori ? parseInt(siswa.nilai_teori) : 0;
                    
                    let h_grade = getGrade(rata);
                    let t_grade = getGrade(nTeori);

                    let narasi = siswa.deskripsi || "";
                    if (rata > 0 && (!narasi || narasi.trim() === '')) {
                        let s = (siswa.surah_terakhir && siswa.surah_terakhir !== '-') ? siswa.surah_terakhir : '';
                        let kalimatSetoran = (s !== '-' && s !== 'Belum Ada' && s !== '') ? ` Ananda telah menyelesaikan hafalan pada Surah ${s} di ${juzText}.` : ` Ananda belum menyetorkan hafalan baru di ${juzText}.`;
                        let kalimatTeori = nTeori > 0 ? ` Hasil evaluasi teori tajwid meraih predikat ${t_grade.derajat} (${nTeori}).` : "";

                        if(h_grade.derajat === 'Mutqin') narasi = `Alhamdulillah, capaian hafalan ananda sangat membanggakan dengan predikat Mutqin (Sangat Lancar) dan rata-rata nilai ${rata}.${kalimatSetoran}${kalimatTeori} Teruslah berinteraksi dengan Al-Quran.`;
                        else if(h_grade.derajat === 'Jayyid Jiddan') narasi = `Capaian hafalan ananda sudah baik dengan predikat Jayyid Jiddan (Lancar) dan rata-rata nilai ${rata}.${kalimatSetoran}${kalimatTeori} Tingkatkan terus muroja'ah ananda.`;
                        else if(h_grade.derajat === 'Jayyid') narasi = `Ananda meraih predikat Jayyid (Cukup) dengan rata-rata nilai ${rata}.${kalimatSetoran}${kalimatTeori} Masih memerlukan lebih banyak muroja'ah agar hafalan lebih kuat.`;
                        else if(rata > 0) narasi = `Ananda mendapat predikat ${h_grade.derajat} dengan nilai ${rata}.${kalimatSetoran}${kalimatTeori} Mohon tingkatkan motivasi dan muroja'ah di rumah agar mencapai target.`;
                    } else if (rata === 0 && nTeori === 0) {
                        narasi = "";
                    }

                    // Update data global untuk Excel
                    siswa.predikat = h_grade.derajat; // Simpan untuk disumbit via hidden input
                    siswa.deskripsi = narasi;
                    
                    let isZero = rata === 0 && nTeori === 0;
                    let readOnlyTextarea = isZero ? "readonly tabindex='-1'" : "";
                    let placeholderText = isZero ? "Belum ada nilai di Juz ini..." : "Tulis narasi di sini...";
                    let opacityClass = isZero ? "opacity-50 cursor-not-allowed bg-slate-100 dark:bg-slate-700/50" : "bg-white dark:bg-slate-800";

                    // [PERBAIKAN] Tampilan Tabel 2 Kolom Identik (Hafalan & Teori)
                    html += `
                    <tr class="baris-santri group transition-all duration-200 hover:bg-slate-50/70 dark:hover:bg-slate-700/30 border-b border-slate-100 dark:border-slate-700 last:border-0 ${isZero ? 'opacity-80' : ''}">
                        <td class="px-5 py-4 text-center text-sm font-medium text-slate-300 dark:text-slate-600 border-r border-slate-50/50 dark:border-slate-700/50">${index + 1}</td>
                        <td class="px-5 py-4 border-r border-slate-50/50 dark:border-slate-700/50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold text-xs ring-1 ring-slate-200 dark:ring-slate-600 shadow-sm flex-shrink-0 overflow-hidden">
                                    ${avatarHtml}
                                </div>
                                <div>
                                    <p class="nama-santri font-bold text-slate-800 dark:text-white text-sm tracking-tight">${siswa.nama_lengkap}</p>
                                    <div class="text-[10px] mt-1 text-slate-500 dark:text-slate-400 leading-relaxed uppercase tracking-wider">
                                        Capaian: <span class="font-bold text-slate-700 dark:text-slate-300">${capaian}</span>
                                    </div>
                                    <div class="text-[10px] mt-0.5 text-blue-500 dark:text-blue-400 font-bold uppercase tracking-wider">
                                        Total Setor Semester: ${siswa.total_setor}x
                                    </div>
                                    <input type="hidden" name="siswa_id[]" value="${siswa.id}">
                                    <input type="hidden" name="predikat[]" value="${h_grade.derajat}">
                                    <input type="hidden" class="data-rata" value="${rata}">
                                </div>
                            </div>
                        </td>
                        
                        <!-- Kolom Terpisah untuk Hafalan -->
                        <td class="px-3 py-4 text-center border-r border-slate-100 dark:border-slate-700 font-black text-${h_grade.color}-600 bg-slate-50/50 dark:bg-slate-900/30">${rata}</td>
                        <td class="px-3 py-4 text-center border-r border-slate-100 dark:border-slate-700 font-black text-${h_grade.color}-600 bg-slate-50/50 dark:bg-slate-900/30">${h_grade.huruf}</td>
                        <td class="px-3 py-4 text-center border-r border-slate-100 dark:border-slate-700 font-bold text-${h_grade.color}-700 dark:text-${h_grade.color}-400 bg-${h_grade.color}-50/50 dark:bg-${h_grade.color}-900/10">${h_grade.derajat}</td>
                        <td class="px-3 py-4 text-center border-r border-slate-100 dark:border-slate-700 text-[11px] font-bold ${h_grade.taqdir === 'Najah' ? 'text-emerald-500' : 'text-rose-500'} bg-${h_grade.color}-50/50 dark:bg-${h_grade.color}-900/10">${h_grade.taqdir}</td>
                        
                        <!-- Kolom Terpisah untuk Teori -->
                        <td class="px-3 py-4 text-center border-r border-slate-100 dark:border-slate-700 font-black text-${t_grade.color}-600 bg-emerald-50/10 dark:bg-emerald-900/5">${nTeori}</td>
                        <td class="px-3 py-4 text-center border-r border-slate-100 dark:border-slate-700 font-black text-${t_grade.color}-600 bg-emerald-50/10 dark:bg-emerald-900/5">${t_grade.huruf}</td>
                        <td class="px-3 py-4 text-center border-r border-slate-100 dark:border-slate-700 font-bold text-${t_grade.color}-700 dark:text-${t_grade.color}-400 bg-${t_grade.color}-50/50 dark:bg-${t_grade.color}-900/10">${t_grade.derajat}</td>
                        <td class="px-3 py-4 text-center border-r border-slate-100 dark:border-slate-700 text-[11px] font-bold ${t_grade.taqdir === 'Najah' ? 'text-emerald-500' : 'text-rose-500'} bg-${t_grade.color}-50/50 dark:bg-${t_grade.color}-900/10">${t_grade.taqdir}</td>
                        
                        <td class="px-5 py-4 align-top border-r border-slate-100 dark:border-slate-700">
                            <textarea name="deskripsi[]" onkeyup="updateProgress()" rows="4" placeholder="${placeholderText}" ${readOnlyTextarea} class="w-full text-sm font-medium text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-dinamis transition-shadow px-4 py-3 outline-none resize-none leading-relaxed ${opacityClass}">${narasi}</textarea>
                        </td>
                        <td class="px-5 py-4 text-center align-middle">
                            <button type="button" onclick="previewRapor('${siswa.id}', '${siswa.nama_lengkap}')" class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-500 hover:text-dinamis hover:bg-dinamis/10 transition-all flex items-center justify-center mx-auto group/btn" title="Lihat Rapor">
                                <svg class="w-5 h-5 transition-transform group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </td>
                    </tr>
                    `;
                });
            }
            tbody.innerHTML = html;
            updateProgress(); 
        } else {
             Swal.fire({ icon: 'error', title: 'Akses Ditolak', text: result.message, customClass: { popup: 'rounded-3xl dark:bg-slate-800 dark:text-white' } });
             tbody.innerHTML = `<tr><td colspan="11" class="text-center p-6 text-rose-500 font-bold">${result.message}</td></tr>`;
        }
    } catch (error) {
        tbody.innerHTML = `<tr><td colspan="11" class="text-center p-6 text-rose-500 font-bold">Gagal terhubung ke database.</td></tr>`;
    }
}

function updateProgress() {
    const textareas = document.querySelectorAll('textarea[name="deskripsi[]"]');
    const rataArray = document.querySelectorAll('.data-rata'); 
    
    let siswaDievaluasi = 0;

    rataArray.forEach((r, i) => {
        let rata = parseInt(r.value) || 0;
        if (rata > 0 && textareas[i].value.trim() !== '') {
            siswaDievaluasi++;
        }
    });

    const percent = totalSantri > 0 ? Math.round((siswaDievaluasi / totalSantri) * 100) : 0;
    
    document.getElementById('progressText').innerText = `${percent}% (${siswaDievaluasi}/${totalSantri} Santri Dievaluasi)`;
    document.getElementById('progressBar').style.width = `${percent}%`;
    
    if(percent === 100 && totalSantri > 0) {
        document.getElementById('progressBar').classList.add('bg-emerald-500');
    } else {
        document.getElementById('progressBar').classList.remove('bg-emerald-500');
    }
}

async function simpanNilai() {
    const form = document.getElementById('formNilaiRapor');
    const formData = new FormData(form);

    Swal.fire({ title: 'Menyimpan ke Rapor...', text: 'Sistem sedang memproses data akhir semester.', allowOutsideClick: false, customClass: { popup: 'rounded-3xl dark:bg-slate-800 dark:text-white' }, didOpen: () => { Swal.showLoading(); } });

    try {
        const response = await fetch(CONFIG.save_url, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const result = await response.json();
        if (result.status === 'success') {
            Swal.fire({ icon: 'success', title: 'Berhasil Disimpan!', text: result.message, customClass: { popup: 'rounded-3xl dark:bg-slate-800 dark:text-white' } });
        } else {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: result.message, customClass: { popup: 'rounded-3xl dark:bg-slate-800 dark:text-white' } });
        }
    } catch (e) {
        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Koneksi ke server terputus.', customClass: { popup: 'rounded-3xl dark:bg-slate-800 dark:text-white' } });
    }
}

// =========================================================================
// FITUR MODAL EXCELJS & DRAG AND DROP
// =========================================================================
function openExcelModal() {
    const modal = document.getElementById('modalExcel');
    if(!document.getElementById('kelasSelect').value || !document.getElementById('juzSelect').value) {
        return Swal.fire({ icon: 'info', title: 'Perhatian', text: 'Silakan pilih Kelas dan Juz terlebih dahulu.'});
    }
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    setTimeout(() => {
        document.getElementById('backdropExcel').classList.replace('opacity-0', 'opacity-100');
        document.getElementById('cardExcel').classList.replace('scale-95', 'scale-100');
        document.getElementById('cardExcel').classList.replace('opacity-0', 'opacity-100');
    }, 10);
}

function closeExcelModal() {
    document.getElementById('backdropExcel').classList.replace('opacity-100', 'opacity-0');
    document.getElementById('cardExcel').classList.replace('scale-100', 'scale-95');
    document.getElementById('cardExcel').classList.replace('opacity-100', 'opacity-0');
    
    setTimeout(() => {
        document.getElementById('modalExcel').classList.add('hidden');
        document.getElementById('modalExcel').classList.remove('flex');
    }, 300);
}

document.addEventListener("DOMContentLoaded", () => {
    const dropzone = document.getElementById('dropzoneCSV');
    if(!dropzone) return;

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) { e.preventDefault(); e.stopPropagation(); }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => { dropzone.classList.add('dropzone-active'); }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, () => { dropzone.classList.remove('dropzone-active'); }, false);
    });

    dropzone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        if(dt.files.length > 0) processFile(dt.files[0]);
    }, false);
});

function handleFileSelect(e) {
    if(e.target.files.length > 0) processFile(e.target.files[0]);
}

async function processFile(file) {
    if(!file) return;

    closeExcelModal();
    Swal.fire({ title: 'Memproses File...', html: `Membaca file <b>${file.name}</b>`, allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

    if (file.name.endsWith('.xls') || file.name.endsWith('.xlsx')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, {type: 'array'});
            
            const firstSheetName = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[firstSheetName];
            
            const csvStr = XLSX.utils.sheet_to_csv(worksheet);
            const csvFile = new File([new Blob([csvStr], { type: 'text/csv' })], "converted_excel.csv", { type: "text/csv" });
            
            uploadCSV(csvFile);
        };
        reader.readAsArrayBuffer(file);
    } else {
        uploadCSV(file);
    }
}

async function uploadCSV(file) {
    const juzName = document.getElementById('juzSelect').value;
    if(!juzName) return Swal.fire('Error', 'Sistem tidak mengetahui Juz berapa yang diimpor. Silakan pilih Juz terlebih dahulu.', 'error');

    const formData = new FormData();
    formData.append('file_csv', file);
    formData.append('juz_import', juzName);

    Swal.fire({ title: 'Sedang Mengimpor Data...', html: `Mentransfer data ke server...`, allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

    try {
        const response = await fetch(CONFIG.import_url, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        const res = await response.json();

        if (response.ok && res.status === 'success') {
            Swal.fire({ icon: 'success', title: 'Alhamdulillah!', text: res.message, customClass: { popup: 'rounded-[2rem]' } }).then(() => { loadSiswa(); }); 
        } else {
            Swal.fire({ icon: 'error', title: 'Gagal', text: res.message, customClass: { popup: 'rounded-[2rem]' } });
        }
    } catch (error) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan pada server saat impor.' });
    } finally {
        document.getElementById('fileInputCSV').value = ""; 
    }
}

// [PERBAIKAN] EKSPOR KE EXCEL DENGAN KOLOM LENGKAP (HAFALAN & TEORI)
async function exportToExcel(isTemplate) {
    if (typeof ExcelJS === 'undefined') {
        Swal.fire('Error', 'Sistem gagal memuat modul pembuat Excel.', 'error');
        return;
    }

    if (!globalStudentsRapor || globalStudentsRapor.length === 0) {
        return Swal.fire({ icon: 'error', title: 'Data Kosong', text: 'Pilih kelas terlebih dahulu.' });
    }

    const juzName = document.getElementById('juzSelect').value;
    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet('Narasi Rapor Tahfidz');

    const headerFill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF3B82F6' } }; 
    const headerFont = { color: { argb: 'FFFFFFFF' }, bold: true };
    const borderAll = { top: { style: 'thin' }, left: { style: 'thin' }, bottom: { style: 'thin' }, right: { style: 'thin' } };
    const alignCenter = { vertical: 'middle', horizontal: 'center' };

    let currentRow = 1;

    const optJuz = document.getElementById('juzSelect').options[document.getElementById('juzSelect').selectedIndex];
    const juzText = optJuz ? optJuz.text : "Juz " + juzName;
    
    worksheet.mergeCells(`A${currentRow}:K${currentRow}`);
    const titleCell = worksheet.getCell(`A${currentRow}`);
    titleCell.value = `LEMBAR NARASI RAPOR TAHFIDZ - KELAS ${CONFIG.class_name.toUpperCase()} - ${juzText.toUpperCase()}`;
    titleCell.font = { size: 14, bold: true, color: { argb: 'FF1D4ED8' } }; 
    titleCell.alignment = alignCenter;
    currentRow += 2;

    if (isTemplate) {
        worksheet.mergeCells(`A${currentRow}:K${currentRow}`);
        const instCell = worksheet.getCell(`A${currentRow}`);
        instCell.value = "PANDUAN PENGISIAN RAPOR TAHFIDZ:\n1. Predikat, Derajat dan Taqdir akan terisi otomatis oleh sistem Excel.\n2. Silakan ubah narasi rapor pada kolom terakhir jika diperlukan.\n3. Jangan merubah kolom NISN dan Nama Siswa.";
        instCell.font = { italic: true, color: { argb: 'FF1E40AF' } };
        instCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFDBEAFE' } }; 
        instCell.alignment = { vertical: 'middle', horizontal: 'left', wrapText: true };
        worksheet.getRow(currentRow).height = 60;
        currentRow += 2;
    }

    // Header Kompleks
    const headers = [
        "NISN", "Nama Siswa", 
        "Hafalan (Angka)", "Hafalan (Huruf)", "Hafalan (Derajat)", "Hafalan (Taqdir)",
        "Teori (Angka)", "Teori (Huruf)", "Teori (Derajat)", "Teori (Taqdir)",
        "Narasi Rapor (Deskripsi)"
    ];
    
    const headerRow = worksheet.getRow(currentRow);
    headers.forEach((header, index) => {
        const cell = headerRow.getCell(index + 1);
        cell.value = header;
        cell.fill = headerFill;
        cell.font = headerFont;
        cell.border = borderAll;
        cell.alignment = alignCenter;
    });
    currentRow++;

    worksheet.columns = [ 
        { width: 15 }, { width: 35 }, 
        { width: 16 }, { width: 15 }, { width: 20 }, { width: 15 },
        { width: 16 }, { width: 15 }, { width: 20 }, { width: 15 },
        { width: 70 } 
    ];

    globalStudentsRapor.forEach(siswa => {
        const row = worksheet.getRow(currentRow);
        row.getCell(1).value = siswa.nisn || siswa.nis || '-';
        row.getCell(2).value = siswa.nama_lengkap;
        
        let rata = siswa.rata_rata || 0;
        let teori = siswa.nilai_teori || 0;

        row.getCell(3).value = isTemplate ? "" : (rata === 0 ? "" : rata); 
        row.getCell(4).value = { formula: `IF(ISNUMBER(C${currentRow}), IF(C${currentRow}>87,"A", IF(C${currentRow}>70,"B",IF(C${currentRow}>60,"C", IF(C${currentRow}>50,"D", "E")))), "")` };
        row.getCell(5).value = { formula: `IF(ISNUMBER(C${currentRow}), IF(C${currentRow}>87,"Mutqin", IF(C${currentRow}>70,"Jayyid Jiddan",IF(C${currentRow}>60,"Jayyid", IF(C${currentRow}>50,"Maqbül", "Mardüd")))), "")` };
        row.getCell(6).value = { formula: `IF(E${currentRow}="","", IF(OR(E${currentRow}="Mutqin", E${currentRow}="Jayyid Jiddan", E${currentRow}="Jayyid", E${currentRow}="Maqbül"), "Najah", "I'adah"))` };

        row.getCell(7).value = isTemplate ? "" : (teori === 0 ? "" : teori); 
        row.getCell(8).value = { formula: `IF(ISNUMBER(G${currentRow}), IF(G${currentRow}>87,"A", IF(G${currentRow}>70,"B",IF(G${currentRow}>60,"C", IF(G${currentRow}>50,"D", "E")))), "")` };
        row.getCell(9).value = { formula: `IF(ISNUMBER(G${currentRow}), IF(G${currentRow}>87,"Mutqin", IF(G${currentRow}>70,"Jayyid Jiddan",IF(G${currentRow}>60,"Jayyid", IF(G${currentRow}>50,"Maqbül", "Mardüd")))), "")` };
        row.getCell(10).value = { formula: `IF(I${currentRow}="","", IF(OR(I${currentRow}="Mutqin", I${currentRow}="Jayyid Jiddan", I${currentRow}="Jayyid", I${currentRow}="Maqbül"), "Najah", "I'adah"))` };
        
        row.getCell(11).value = isTemplate ? "" : (siswa.deskripsi || '');

        for(let i = 1; i <= 11; i++) {
            row.getCell(i).border = borderAll;
            if(i !== 2 && i !== 11) row.getCell(i).alignment = alignCenter; 
        }

        currentRow++;
    });

    const buffer = await workbook.xlsx.writeBuffer();
    const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = isTemplate ? `Rapor_${juzText}_(Template).xlsx` : `Rapor_${juzText}_(Ekspor).xlsx`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function downloadTemplateRapor() { exportToExcel(true); }
function exportDataRapor() { exportToExcel(false); }

// =========================================================================
// FITUR PREVIEW RAPOR TAHFIDZ PDF
// =========================================================================
function previewRapor(siswaId, studentName) {
    const juzId = document.getElementById('juzSelect').value;
    if(!juzId) return Swal.fire('Perhatian', 'Pilih Juz terlebih dahulu.', 'warning');

    const modal = document.getElementById('modalPreviewRapor');
    const backdrop = document.getElementById('backdropPreview');
    const card = document.getElementById('cardPreview');
    const iframe = document.getElementById('previewIframe');
    const loader = document.getElementById('previewLoader');
    
    document.getElementById('previewStudentName').innerText = studentName;
    const optJuz = document.getElementById('juzSelect').options[document.getElementById('juzSelect').selectedIndex];
    document.getElementById('previewStudentInfo').innerText = `Rapor Tahfidz - ${optJuz ? optJuz.text : 'Juz ' + juzId}`;
    
    // Reset Iframe & Loader
    iframe.src = "";
    loader.style.display = 'flex';
    
    // Construct URL
    const pdfUrl = `${BASE_URL}/tahfidz/nilai-rapor/preview/${siswaId}?juz=${juzId}`;
    
    // Show Modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    setTimeout(() => {
        backdrop.classList.replace('opacity-0', 'opacity-100');
        card.classList.replace('scale-95', 'scale-100');
        card.classList.replace('opacity-0', 'opacity-100');
        iframe.src = pdfUrl;
    }, 50);
}

function closePreviewModal() {
    const backdrop = document.getElementById('backdropPreview');
    const card = document.getElementById('cardPreview');
    const modal = document.getElementById('modalPreviewRapor');
    
    backdrop.classList.replace('opacity-100', 'opacity-0');
    card.classList.replace('scale-100', 'scale-95');
    card.classList.replace('opacity-100', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.getElementById('previewIframe').src = "";
    }, 500);
}