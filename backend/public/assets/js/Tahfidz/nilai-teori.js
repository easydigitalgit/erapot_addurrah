/**
 * File: public/assets/js/Tahfidz/nilai-teori.js
 */

setInterval(() => {
    const el = document.getElementById('realtimeClock');
    if(el) el.innerText = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
}, 1000);

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

function getGrade(val) {
    if (val === '' || isNaN(val)) return { huruf: '-', derajat: '-', taqdir: '-', color: 'slate' };
    
    let huruf, derajat, color;
    if (val > 87) { huruf = 'A'; derajat = 'Mutqin'; color = 'emerald'; }
    else if (val > 70) { huruf = 'B'; derajat = 'Jayyid Jiddan'; color = 'blue'; }
    else if (val > 60) { huruf = 'C'; derajat = 'Jayyid'; color = 'amber'; }
    else if (val > 50) { huruf = 'D'; derajat = 'Maqbül'; color = 'orange'; }
    else { huruf = 'E'; derajat = 'Mardüd'; color = 'rose'; }

    let taqdir = ['Mutqin', 'Jayyid Jiddan', 'Jayyid', 'Maqbül'].includes(derajat) ? "Najah" : "I'adah";
    return { huruf, derajat, taqdir, color };
}

function autoUpdateTeori(inputElem) {
    let val = inputElem.value;
    if(val > 100) { val = 100; inputElem.value = 100; }
    if(val !== '' && val < 0) { val = 0; inputElem.value = 0; }

    const row = inputElem.closest('tr');
    const hurufCol = row.querySelector('.huruf-col');
    const derajatCol = row.querySelector('.derajat-col');
    const taqdirCol = row.querySelector('.taqdir-col');

    const grade = getGrade(val !== '' ? parseInt(val) : NaN);

    hurufCol.innerText = grade.huruf;
    hurufCol.className = `px-5 py-4 text-center font-black text-2xl huruf-col text-${grade.color}-500 border-r border-slate-50/50 dark:border-slate-700/50`;

    derajatCol.innerText = grade.derajat;
    derajatCol.className = `px-5 py-4 text-center font-bold text-sm derajat-col text-${grade.color}-700 border-r border-slate-50/50 dark:border-slate-700/50 bg-${grade.color}-50/30`;

    taqdirCol.innerText = grade.taqdir;
    taqdirCol.className = `px-5 py-4 text-center font-semibold text-sm taqdir-col text-${grade.color}-600`;

    if(val !== '') {
        inputElem.classList.add('input-success');
        setTimeout(() => inputElem.classList.remove('input-success'), 500);
    }
    updateProgressTeori();
}

function updateProgressTeori() {
    const inputs = document.querySelectorAll('input[name="nilai_teori[]"]');
    let terisi = 0;
    inputs.forEach(input => {
        if (input.value !== '') terisi++;
    });

    const percent = totalSantriTeori > 0 ? Math.round((terisi / totalSantriTeori) * 100) : 0;
    
    const progText = document.getElementById('progressText');
    const progBar = document.getElementById('progressBar');
    
    if(progText) progText.innerText = `${percent}% (${terisi}/${totalSantriTeori} Santri Dievaluasi)`;
    if(progBar) {
        progBar.style.width = `${percent}%`;
        if(percent === 100 && totalSantriTeori > 0) progBar.classList.add('bg-emerald-500');
        else progBar.classList.remove('bg-emerald-500');
    }
}

let globalStudentsTeori = [];
let totalSantriTeori = 0;

async function loadSiswa() {
    try {
        const kelasSelect = document.getElementById('kelasSelect');
        const juzSelect = document.getElementById('juzSelect');
        
        if (!kelasSelect || !juzSelect) return;
        
        const emptyState = document.getElementById('emptyState');
        const tableContainer = document.getElementById('tableContainer');
        
        if (!kelasSelect.value || !juzSelect.value) {
            if (emptyState) emptyState.style.display = 'block';
            if (tableContainer) {
                tableContainer.style.display = 'none';
                tableContainer.classList.add('hidden');
            }
            return; 
        }
        
        if (emptyState) emptyState.style.display = 'none';
        if (tableContainer) {
            tableContainer.classList.remove('hidden');
            tableContainer.style.display = 'block';
        }
        
        const opt = kelasSelect.options[kelasSelect.selectedIndex];
        CONFIG.class_name = opt ? (opt.getAttribute('data-name') || opt.text) : "Kelas";
        
        const optJuz = juzSelect.options[juzSelect.selectedIndex];
        const juzText = optJuz ? optJuz.text : "Juz " + juzSelect.value;
        if (infoKelas) infoKelas.textContent = `- ${CONFIG.class_name} (${juzText})`;

        document.getElementById('formJuzInput').value = juzSelect.value;

        const tbody = document.getElementById('tbodyNilai');
        if (!tbody) return;
        
        tbody.innerHTML = `<tr><td colspan="6" class="text-center p-20"><div class="w-10 h-10 border-4 border-slate-200 rounded-full animate-spin mx-auto mb-4 border-t-emerald-500"></div><p class="text-slate-500 font-bold tracking-wide">Memuat Lembar Nilai Teori...</p></td></tr>`;

        const safeRombel = encodeURIComponent(kelasSelect.value);
        const safeJuz = encodeURIComponent(juzSelect.value);
        
        const response = await fetch(`${CONFIG.fetch_url}?rombel_id=${safeRombel}&juz=${safeJuz}`);
        
        if (!response.ok) {
            throw new Error(`HTTP Error: ${response.status} - Gagal memuat data dari server.`);
        }

        const responseText = await response.text();
        let result;
        try {
            result = JSON.parse(responseText);
        } catch (e) {
            throw new Error("System Error dari PHP: <br><br> <span class='text-[10px] text-left block text-red-400 bg-red-950 p-2 rounded overflow-auto max-h-32'>" + responseText + "</span>");
        }

        if (result.status === 'success') {
            globalStudentsTeori = result.data; 
            totalSantriTeori = result.data.length;
            let html = '';

            if(totalSantriTeori === 0) {
                html = `<tr><td colspan="6" class="text-center p-12 text-rose-500 font-bold">Tidak ada santri pada rombel ini.</td></tr>`;
                document.getElementById('progressText').innerText = `0% (0/0 Santri)`;
                document.getElementById('progressBar').style.width = `0%`;
            } else {
                result.data.forEach((siswa, index) => {
                    // --- LOGIKA HYBRID AVATAR ---
                    let avatarHtml = '';
                    const cleanBase = (typeof BASE_URL !== 'undefined' ? BASE_URL : window.location.origin).replace(/\/$/, ""); 
                    
                    if (siswa.foto_fix && siswa.foto_fix !== 'null' && String(siswa.foto_fix).trim() !== '') {
                        const cacheBuster = '?v=' + new Date().getTime();
                        const urlAvatars = `${cleanBase}/assets/uploads/avatars/${siswa.foto_fix}${cacheBuster}`;
                        const urlSiswa = `${cleanBase}/uploads/siswa/${siswa.foto_fix}${cacheBuster}`;
                        const fallbackInitial = getInitials(siswa.nama_lengkap);
                        
                        // Coba folder avatars -> gagal? coba folder siswa -> gagal? tampilkan inisial
                        avatarHtml = `<img src="${urlAvatars}" class="w-full h-full object-cover" alt="Foto" onerror="this.onerror=function(){ this.outerHTML='${fallbackInitial}'; }; this.src='${urlSiswa}';">`;
                    } else {
                        avatarHtml = getInitials(siswa.nama_lengkap);
                    }
                    
                    let nTeori = (siswa.nilai_teori === null || siswa.nilai_teori === '') ? '' : siswa.nilai_teori;
                    let grade = getGrade(nTeori !== '' ? parseInt(nTeori) : NaN);

                    html += `
                    <tr class="baris-santri group transition-all duration-200 hover:bg-slate-50/70 dark:hover:bg-slate-700/30 border-b border-slate-100 dark:border-slate-700 last:border-0">
                        <td class="px-5 py-4 text-center text-sm font-medium text-slate-400 group-hover:text-slate-500 border-r border-slate-50/50 dark:border-slate-700/50">${index + 1}</td>
                        <td class="px-5 py-4 border-r border-slate-50/50 dark:border-slate-700/50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold text-xs ring-1 ring-slate-200 dark:ring-slate-600 shadow-sm flex-shrink-0 overflow-hidden">
                                    ${avatarHtml}
                                </div>
                                <div>
                                    <p class="nama-santri font-bold text-slate-800 dark:text-white text-sm tracking-tight">${siswa.nama_lengkap}</p>
                                    <div class="text-[10px] mt-1 text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                        NISN: <span class="font-bold text-slate-600 dark:text-slate-300">${siswa.nisn || '-'}</span>
                                    </div>
                                    <input type="hidden" name="siswa_id[]" value="${siswa.id}">
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-center border-r border-slate-50/50 dark:border-slate-700/50">
                            <input type="number" name="nilai_teori[]" min="0" max="100" value="${nTeori}" placeholder="0-100" 
                            oninput="autoUpdateTeori(this);" 
                            class="w-24 mx-auto text-center text-xl font-black text-slate-800 dark:text-white border-2 border-slate-200 dark:border-slate-600 rounded-xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 bg-white dark:bg-slate-800 py-3 outline-none transition-all shadow-inner">
                        </td>
                        <td class="px-5 py-4 text-center font-black text-2xl huruf-col text-${grade.color}-500 border-r border-slate-50/50 dark:border-slate-700/50">${grade.huruf}</td>
                        <td class="px-5 py-4 text-center font-bold text-sm derajat-col text-${grade.color}-700 border-r border-slate-50/50 dark:border-slate-700/50 bg-${grade.color}-50/30">${grade.derajat}</td>
                        <td class="px-5 py-4 text-center font-semibold text-sm taqdir-col text-${grade.color}-600">${grade.taqdir}</td>
                    </tr>
                    `;
                });
            }
            tbody.innerHTML = html;
            updateProgressTeori();
        } else {
             Swal.fire({ icon: 'error', title: 'Perhatian', text: result.message });
             tbody.innerHTML = `<tr><td colspan="6" class="text-center p-6 text-rose-500 font-bold">${result.message}</td></tr>`;
        }
    } catch (error) {
        console.error(error); 
        const tbody = document.getElementById('tbodyNilai');
        if(tbody) tbody.innerHTML = `<tr><td colspan="6" class="text-center p-6 text-rose-500 font-bold">Terjadi Kesalahan!<br><span class="text-xs text-slate-500 mt-2 block">${error.message}</span></td></tr>`;
    }
}

async function simpanNilai() {
    const form = document.getElementById('formNilaiTeori');
    const formData = new FormData(form);

    Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

    try {
        const response = await fetch(CONFIG.save_url, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const result = await response.json();
        if (result.status === 'success') {
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: result.message }).then(() => { loadSiswa(); });
        } else {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: result.message });
        }
    } catch (e) {
        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Koneksi ke server terputus.' });
    }
}

function openExcelModal() {
    const modal = document.getElementById('modalExcel');
    if(!document.getElementById('kelasSelect').value || !document.getElementById('juzSelect').value) {
        return Swal.fire({ icon: 'info', title: 'Perhatian', text: 'Silakan pilih Kelas dan Juz terlebih dahulu di halaman utama.'});
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
    setTimeout(() => { document.getElementById('modalExcel').classList.add('hidden'); document.getElementById('modalExcel').classList.remove('flex'); }, 300);
}

async function exportToExcel(isTemplate) {
    if (typeof ExcelJS === 'undefined') return Swal.fire('Error', 'Modul Excel gagal dimuat. Pastikan ada koneksi internet.', 'error');
    if (!globalStudentsTeori || globalStudentsTeori.length === 0) return Swal.fire('Error', 'Data Kosong.', 'error');

    const juzName = document.getElementById('juzSelect').value;
    const juzText = document.getElementById('juzSelect').options[document.getElementById('juzSelect').selectedIndex].text;
    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet('EVALUASI TEORI');

    worksheet.getCell('A1').value = `INPUT EVALUASI TEORI - ${juzText.toUpperCase()}`;
    worksheet.getCell('A1').font = { bold: true, size: 14 };
    
    worksheet.getCell('A3').value = 'TANGGAL RAPORT :';
    worksheet.getCell('A4').value = 'Medan, ' + new Date().toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'});
    worksheet.getCell('A5').value = 'PEMBINA TAHFIZ :';
    worksheet.getCell('A6').value = 'Ustadz/ah'; 

    worksheet.getCell('F3').value = 'SEMESTER :';
    worksheet.getCell('F4').value = CONFIG.ta_info.split(" ")[0]; 
    worksheet.getCell('F5').value = 'TAHUN AJARAN :';
    worksheet.getCell('F6').value = CONFIG.ta_info.split(" ").slice(1).join(" "); 

    const headerRow = worksheet.getRow(7);
    const headers = ["No", "Nama", "NISN", "Kelas", "Angka", "Huruf", "Derajat", "Taqdir"];
    headers.forEach((h, i) => {
        const cell = headerRow.getCell(i+1);
        cell.value = h;
        cell.font = { bold: true, color: { argb: 'FFFFFFFF' } };
        cell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF10B981' } }; 
        cell.border = { top: {style:'thin'}, bottom: {style:'thin'}, left: {style:'thin'}, right: {style:'thin'} };
        cell.alignment = { vertical: 'middle', horizontal: 'center' };
    });

    worksheet.columns = [ {width: 5}, {width: 35}, {width: 20}, {width: 15}, {width: 12}, {width: 10}, {width: 18}, {width: 15} ];

    let currentRow = 8;
    globalStudentsTeori.forEach((siswa, idx) => {
        let nTeori = (siswa.nilai_teori === null || siswa.nilai_teori === '') ? "" : siswa.nilai_teori;

        const row = worksheet.getRow(currentRow);
        row.getCell(1).value = idx + 1;
        row.getCell(2).value = siswa.nama_lengkap;
        row.getCell(3).value = siswa.nisn || siswa.nis || '-';
        row.getCell(4).value = CONFIG.class_name;
        
        row.getCell(5).value = isTemplate ? "" : nTeori;
        
        row.getCell(6).value = { formula: `IF(ISNUMBER(E${currentRow}), IF(E${currentRow}>87,"A", IF(E${currentRow}>70,"B",IF(E${currentRow}>60,"C", IF(E${currentRow}>50,"D", "E")))), "")` };
        row.getCell(7).value = { formula: `IF(ISNUMBER(E${currentRow}), IF(E${currentRow}>87,"Mutqin", IF(E${currentRow}>70,"Jayyid Jiddan",IF(E${currentRow}>60,"Jayyid", IF(E${currentRow}>50,"Maqbül", "Mardüd")))), "")` };
        row.getCell(8).value = { formula: `IF(G${currentRow}="","", IF(OR(G${currentRow}="Mutqin", G${currentRow}="Jayyid Jiddan", G${currentRow}="Jayyid", G${currentRow}="Maqbül"), "Najah", "I'adah"))` };
        
        for(let i=1; i<=8; i++) {
            row.getCell(i).border = { top: {style:'thin'}, bottom: {style:'thin'}, left: {style:'thin'}, right: {style:'thin'} };
            if (i !== 2) row.getCell(i).alignment = { horizontal: 'center', vertical: 'middle' };
        }
        currentRow++;
    });

    const buffer = await workbook.xlsx.writeBuffer();
    const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = isTemplate ? `Input_Teori_Juz_${juzName}_(Template).xlsx` : `Input_Teori_Juz_${juzName}_(Export).xlsx`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function downloadTemplateTeori() { exportToExcel(true); }
function exportDataTeori() { exportToExcel(false); }

document.addEventListener("DOMContentLoaded", () => {
    const dropzone = document.getElementById('dropzoneCSV');
    if(!dropzone) return;
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(e => dropzone.addEventListener(e, prevDef, false));
    function prevDef(e) { e.preventDefault(); e.stopPropagation(); }
    ['dragenter', 'dragover'].forEach(e => dropzone.addEventListener(e, () => dropzone.classList.add('dropzone-active'), false));
    ['dragleave', 'drop'].forEach(e => dropzone.addEventListener(e, () => dropzone.classList.remove('dropzone-active'), false));
    dropzone.addEventListener('drop', (e) => { if(e.dataTransfer.files.length > 0) processFile(e.dataTransfer.files[0]); }, false);
});

function handleFileSelect(e) { if(e.target.files.length > 0) processFile(e.target.files[0]); }

async function processFile(file) {
    if(!file) return;
    closeExcelModal();
    Swal.fire({ title: 'Memproses File...', html: `Membaca file <b>${file.name}</b>`, allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

    if (file.name.endsWith('.xls') || file.name.endsWith('.xlsx')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, {type: 'array'});
            const worksheet = workbook.Sheets[workbook.SheetNames[0]];
            const csvStr = XLSX.utils.sheet_to_csv(worksheet);
            const csvFile = new File([new Blob([csvStr], { type: 'text/csv' })], "converted.csv", { type: "text/csv" });
            uploadCSV(csvFile);
        };
        reader.readAsArrayBuffer(file);
    } else {
        uploadCSV(file);
    }
}

async function uploadCSV(file) {
    const juzName = document.getElementById('juzSelect').value;
    if(!juzName) return Swal.fire('Error', 'Sistem tidak mengetahui Juz berapa yang sedang dinilai. Harap pilih Juz.', 'error');

    const formData = new FormData();
    formData.append('file_csv', file);
    formData.append('juz_import', juzName); 
    
    Swal.fire({ title: 'Sedang Mengimpor...', html: `Mentransfer data ke server...`, allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

    try {
        const response = await fetch(CONFIG.import_url, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const res = await response.json();
        if (response.ok && res.status === 'success') {
            Swal.fire({ icon: 'success', title: 'Alhamdulillah!', text: res.message }).then(() => { loadSiswa(); }); 
        } else {
            Swal.fire({ icon: 'error', title: 'Gagal', text: res.message });
        }
    } catch (error) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan pada server saat impor.' });
    }
}