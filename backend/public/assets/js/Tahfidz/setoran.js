/**
 * File: public/assets/js/Tahfidz/setoran.js
 * 100% ID BASED ARCHITECTURE - UPDATE EXPORT REKAP (SAFE ARRAY MODE)
 */

const Toast = Swal.mixin({
    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true,
    customClass: { popup: 'rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 dark:bg-slate-800 dark:text-white' },
    didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
});

setInterval(() => {
    const now = new Date();
    const el = document.getElementById('realtimeClock');
    if(el) el.innerText = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second:'2-digit' }) + ' WIB';
}, 1000);

document.addEventListener('keydown', function(event) {
    if (event.altKey && (event.key === 's' || event.key === 'S')) {
        event.preventDefault();
        if(document.getElementById('tableContainer').style.display === 'block') { simpanSetoran(); }
    }
});

let isFocusMode = false;
function toggleFocusMode() {
    isFocusMode = !isFocusMode;
    const topSec = document.getElementById('topSection');
    const btnFocus = document.getElementById('btnFocus');
    const textBtnFocus = document.getElementById('textBtnFocus');
    const scrollWrap = document.getElementById('scrollTableWrapper');
    
    if (isFocusMode) {
        topSec.style.display = 'none';
        scrollWrap.classList.replace('max-h-[55vh]', 'max-h-[80vh]');
        textBtnFocus.innerText = "Keluar Fokus";
        btnFocus.classList.replace('bg-slate-800', 'bg-rose-500');
        btnFocus.classList.replace('hover:bg-slate-900', 'hover:bg-rose-600');
    } else {
        topSec.style.display = 'block';
        scrollWrap.classList.replace('max-h-[80vh]', 'max-h-[55vh]');
        textBtnFocus.innerText = "Mode Fokus";
        btnFocus.classList.replace('bg-rose-500', 'bg-slate-800');
        btnFocus.classList.replace('hover:bg-rose-600', 'hover:bg-slate-900');
    }
}

function cariSantri() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let baris = document.querySelectorAll("#tbodySiswa .baris-santri");
    baris.forEach(row => {
        let namaSantri = row.querySelector(".nama-santri").innerText.toLowerCase();
        row.style.display = namaSantri.includes(input) ? "" : "none";
    });
}

function resetBaris(btnElem) {
    const row = btnElem.closest('tr');
    row.querySelector('select[name="surah_id[]"]').value = '';
    row.querySelector('input[name="catatan[]"]').value = '';
    
    const inputNilai = row.querySelector('input[name="nilai[]"]');
    inputNilai.value = '';
    
    const selectJenis = row.querySelector('select[name="jenis_setoran[]"]');
    selectJenis.value = 'Ziyadah';
    
    refreshDropdownSurah(selectJenis);
    updatePredikat(inputNilai);
    
    row.classList.add('bg-rose-50', 'dark:bg-rose-900/30');
    setTimeout(() => row.classList.remove('bg-rose-50', 'dark:bg-rose-900/30'), 500);
}

const getInitials = (name) => {
    let initials = name.match(/\b\w/g) || [];
    return ((initials.shift() || '') + (initials.pop() || '')).toUpperCase();
};

const JUZ_DATA = (typeof JUZ_DATA_DB !== 'undefined' && Object.keys(JUZ_DATA_DB).length > 0) ? JUZ_DATA_DB : {};

function getGrade(val) {
    if (val === '' || isNaN(val) || val <= 0) return { huruf: '-', derajat: 'Belum Hafal', taqdir: '-', color: 'slate' };
    let huruf, derajat, color;
    if (val >= 90) { huruf = 'A'; derajat = 'Sangat Lancar'; color = 'emerald'; }
    else if (val >= 80) { huruf = 'B'; derajat = 'Lancar'; color = 'blue'; }
    else if (val >= 70) { huruf = 'C'; derajat = 'Kurang Lancar'; color = 'amber'; }
    else if (val >= 60) { huruf = 'D'; derajat = 'Kurang Lancar'; color = 'orange'; }
    else { huruf = 'E'; derajat = 'Belum Hafal'; color = 'rose'; }
    let taqdir = ['Sangat Lancar', 'Lancar', 'Kurang Lancar'].includes(derajat) ? "Lulus" : "Mengulang";
    return { huruf, derajat, taqdir, color };
}

async function loadSiswa() {
    const kelas_id = document.getElementById('kelasSelect').value;
    const tanggal = document.getElementById('tanggalSetoran').value; 
    const juzId = document.getElementById('juzSelect').value; 

    if(!kelas_id || !juzId) { 
        document.getElementById('emptyState').style.display = 'block';
        document.getElementById('tableContainer').style.display = 'none';
        return; 
    }
    
    const selectElement = document.getElementById('kelasSelect');
    CONFIG.class_name = selectElement.options[selectElement.selectedIndex].getAttribute('data-name');

    document.getElementById('emptyState').style.display = 'none';
    document.getElementById('tableContainer').style.display = 'block';
    document.getElementById('searchInput').value = ''; 
    
    const tbody = document.getElementById('tbodySiswa');
    tbody.innerHTML = `<tr><td colspan="8" class="text-center p-12 text-slate-500 font-medium"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-[${CONFIG.primary_color}] mx-auto mb-3"></div>Mengambil data santri...</td></tr>`;

    try {
        const response = await fetch(`${CONFIG.fetch_url}?rombel_id=${kelas_id}&tanggal=${tanggal}&juz_id=${juzId}`);
        const result = await response.json();

        if (result.status === 'success') {
            globalStudents = result.data; 
            let html = '';
            
            if(result.data.length === 0) {
                html = `<tr><td colspan="8" class="text-center p-12 text-red-500 font-medium">Tidak ada santri aktif di kelas ini.</td></tr>`;
            } else {
                result.data.forEach((siswa, index) => {
                    let s_block = siswa.setoran ? siswa.setoran.block_val : '';
                    let s_block_name = siswa.setoran ? siswa.setoran.block_name : '';
                    let s_jenis = siswa.setoran ? siswa.setoran.jenis_setoran : 'Ziyadah';
                    let s_nilai = siswa.setoran ? (siswa.setoran.nilai == 0 ? '' : siswa.setoran.nilai) : ''; 
                    let s_catatan = (siswa.setoran && siswa.setoran.catatan) ? siswa.setoran.catatan : '';
                    
                    let riwayatStr = encodeURIComponent(JSON.stringify(siswa.riwayat_blok || []));

                    // --- LOGIKA HYBRID AVATAR ---
                    let avatarHtml = '';
                    const baseDomain = (typeof BASE_URL !== 'undefined' ? BASE_URL : window.location.origin).replace(/\/$/, '');
                    
                    if (siswa.foto_fix && siswa.foto_fix !== 'null' && String(siswa.foto_fix).trim() !== '') {
                        const cacheBuster = '?v=' + new Date().getTime();
                        const urlAvatars = `${baseDomain}/assets/uploads/avatars/${siswa.foto_fix}${cacheBuster}`;
                        const urlSiswa = `${baseDomain}/uploads/siswa/${siswa.foto_fix}${cacheBuster}`;
                        const fallbackInitial = getInitials(siswa.nama_lengkap);
                        
                        avatarHtml = `<img src="${urlAvatars}" class="w-full h-full object-cover" alt="Foto" onerror="this.onerror=function(){ this.outerHTML='${fallbackInitial}'; }; this.src='${urlSiswa}';">`;
                    } else {
                        avatarHtml = getInitials(siswa.nama_lengkap);
                    }

                    let optionsHtml = `<option value="">- Pilih Target Blok -</option>`;
                    const surahList = JUZ_DATA[juzId] || []; 
                    
                    let targetFoundInJuz = false;

                    let dbAyatClean = siswa.setoran ? String(siswa.setoran.ayat || 'Semua').replace(/[^a-zA-Z0-9]/g, '').toLowerCase() : '';
                    let dbBlockClean = siswa.setoran ? String(siswa.setoran.surah_id) + '|' + dbAyatClean : '';

                    surahList.forEach(s => {
                        let refAyatClean = String(s.ayat).replace(/[^a-zA-Z0-9]/g, '').toLowerCase();
                        let refBlockClean = String(s.surah_id) + '|' + refAyatClean;
                        
                        let val = s.surah_id + '|' + s.ayat; 
                        
                        let isDisetor = false;
                        if (siswa.riwayat_blok) {
                            isDisetor = siswa.riwayat_blok.some(r => {
                                let parts = r.split('|');
                                let rSurah = parts[0];
                                let rAyatClean = String(parts[1] || 'Semua').replace(/[^a-zA-Z0-9]/g, '').toLowerCase();
                                return (rSurah + '|' + rAyatClean) === refBlockClean;
                            });
                        }
                        
                        let selected = (dbBlockClean === refBlockClean) ? 'selected' : '';
                        if (selected) targetFoundInJuz = true;
                        
                        if (s_jenis === 'Ziyadah' && isDisetor && !selected) {
                            // Sembunyikan
                        } else {
                            optionsHtml += `<option value="${val}" ${selected}>${s.display}</option>`;
                        }
                    });

                    if (!targetFoundInJuz && s_block !== '') {
                        optionsHtml += `<option value="${s_block}" selected>${s_block_name} (Beda Juz)</option>`;
                    }

                    let grade = getGrade(s_nilai !== '' ? parseInt(s_nilai) : NaN);

                    html += `
                    <tr class="baris-santri group transition-all duration-200 hover:bg-slate-50 dark:hover:bg-slate-800/80 border-b border-slate-100 dark:border-slate-700 last:border-0 focus-within:bg-blue-50/40 dark:focus-within:bg-blue-900/10">
                        <td class="p-4 text-center text-sm font-medium text-slate-400">${index + 1}</td>
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 flex items-center justify-center font-bold text-xs ring-1 ring-slate-200 dark:ring-slate-600 shadow-sm flex-shrink-0 overflow-hidden">
                                    ${avatarHtml}
                                </div>
                                <div class="flex flex-col">
                                    <p class="nama-santri font-semibold text-slate-800 dark:text-white text-sm">${siswa.nama_lengkap}</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5 tracking-wide">NISN: ${siswa.nisn || '-'}</p>
                                    <input type="hidden" name="siswa_id[]" value="${siswa.id}">
                                    <input type="hidden" name="juz_id[]" value="${juzId}"> 
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <select name="jenis_setoran[]" onchange="refreshDropdownSurah(this)" data-riwayat="${riwayatStr}" class="w-full text-sm font-semibold text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:border-transparent bg-slate-50 dark:bg-slate-800 py-2.5 px-3 outline-none cursor-pointer">
                                <option value="Ziyadah" ${s_jenis === 'Ziyadah' ? 'selected' : ''}>Ziyadah</option>
                                <option value="Murojaah" ${s_jenis === 'Murojaah' ? 'selected' : ''}>Muroja'ah</option>
                            </select>
                        </td>
                        <td class="p-4 flex flex-col gap-2">
                            <select name="surah_id[]" class="surah-dropdown w-full text-sm font-bold border border-slate-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:border-transparent bg-white dark:bg-slate-800 py-2 px-3 outline-none text-slate-800 dark:text-white cursor-pointer">
                                ${optionsHtml}
                            </select>
                        </td>
                        <td class="p-4 text-center">
                            <input type="number" name="nilai[]" min="0" max="100" value="${s_nilai}" placeholder="0-100" 
                            oninput="updatePredikat(this)" 
                            class="w-20 text-center mx-auto text-lg font-black text-slate-800 dark:text-white border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-white dark:bg-slate-800 py-2 outline-none shadow-inner transition-colors ${s_nilai !== '' ? `border-${grade.color}-500 text-${grade.color}-600 bg-${grade.color}-50` : ''}">
                            <input type="hidden" name="predikat[]" value="${grade.derajat === 'Belum Hafal' ? '' : grade.derajat}">
                        </td>
                        <td class="p-2 align-middle text-center">
                            <div class="display-taqdir text-xs font-bold text-slate-500">
                                ${s_nilai !== '' ? `<span class="${grade.color} font-black">${grade.huruf} | ${grade.derajat}</span><br><span class="text-[9px] ${grade.taqdir === 'Lulus' ? 'text-emerald-500' : 'text-rose-500'} mt-0.5 inline-block">[ ${grade.taqdir} ]</span>` : '-'}
                            </div>
                        </td>
                        <td class="p-4">
                            <input type="text" name="catatan[]" value="${s_catatan}" placeholder="Catatan" class="w-full text-sm font-medium border-0 border-b-2 border-transparent hover:border-slate-300 focus:ring-0 bg-transparent px-2 py-2 outline-none text-slate-800 dark:text-white">
                        </td>
                        <td class="p-4 text-center">
                            <button type="button" onclick="resetBaris(this)" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-lg outline-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>
                    `;
                });
            }
            tbody.innerHTML = html;
        } else {
            Swal.fire({ icon: 'error', title: 'Ditolak', text: result.message });
            tbody.innerHTML = `<tr><td colspan="8" class="text-center p-6 text-red-500 font-bold">${result.message}</td></tr>`;
        }
    } catch (error) {
        tbody.innerHTML = `<tr><td colspan="8" class="text-center p-6 text-red-500 font-bold">Gagal terhubung ke database.</td></tr>`;
    }
}

function refreshDropdownSurah(selectJenis) {
    const row = selectJenis.closest('tr');
    const selectSurah = row.querySelector('.surah-dropdown');
    const jenis = selectJenis.value;
    const juzId = document.getElementById('juzSelect').value;
    
    const riwayatStr = selectJenis.getAttribute('data-riwayat');
    let riwayatBlok = [];
    try { riwayatBlok = JSON.parse(decodeURIComponent(riwayatStr)); } catch(e) {}
    
    const currentVal = selectSurah.value;
    let html = '<option value="">- Pilih Target Blok -</option>';
    const surahList = JUZ_DATA[juzId] || [];
    
    let targetFoundInJuz = false;
    surahList.forEach(s => {
        let val = s.surah_id + '|' + s.ayat;
        
        let refBlockClean = String(s.surah_id) + '|' + String(s.ayat).replace(/[^a-zA-Z0-9]/g, '').toLowerCase();
        let isDisetor = riwayatBlok.some(r => {
            let parts = r.split('|');
            let rSurah = parts[0];
            let rAyatClean = String(parts[1] || 'Semua').replace(/[^a-zA-Z0-9]/g, '').toLowerCase();
            return (rSurah + '|' + rAyatClean) === refBlockClean;
        });
        
        let selected = (val === currentVal) ? 'selected' : '';
        if(selected) targetFoundInJuz = true;

        if (jenis === 'Ziyadah' && isDisetor && !selected) {
            // Sembunyikan
        } else {
            html += `<option value="${val}" ${selected}>${s.display}</option>`;
        }
    });

    if(!targetFoundInJuz && currentVal !== '') {
        html += `<option value="${currentVal}" selected>Terisi (Beda Juz)</option>`;
    }

    selectSurah.innerHTML = html;
}

function updatePredikat(inputElem) {
    let nilai = parseInt(inputElem.value);
    
    if (nilai > 100) { inputElem.value = 100; nilai = 100; }
    if (nilai < 0) { inputElem.value = 0; nilai = 0; }

    const row = inputElem.closest('tr');
    const hiddenPredikat = row.querySelector('input[name="predikat[]"]');
    const displayHtml = row.querySelector('.display-taqdir');

    if (isNaN(nilai) || inputElem.value === '') {
        displayHtml.innerHTML = '-';
        hiddenPredikat.value = '';
        inputElem.className = "w-20 text-center mx-auto text-lg font-black text-slate-800 dark:text-white border border-slate-300 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-emerald-500 bg-white dark:bg-slate-800 py-2 outline-none shadow-inner transition-colors";
        return;
    }

    const grade = getGrade(nilai);

    if (grade.derajat === 'Sangat Lancar' || grade.derajat === 'Mumtaz') { hiddenPredikat.value = 'Sangat Lancar'; }
    else if (grade.derajat === 'Lancar' || grade.derajat === 'Jayyid Jiddan') { hiddenPredikat.value = 'Lancar'; }
    else if (grade.derajat === 'Kurang Lancar' || grade.derajat === 'Jayyid' || grade.derajat === 'Maqbül') { hiddenPredikat.value = 'Kurang Lancar'; }
    else { hiddenPredikat.value = 'Belum Hafal'; }

    inputElem.className = `w-20 text-center mx-auto text-lg font-black text-${grade.color}-600 bg-${grade.color}-50 border-0 ring-2 ring-${grade.color}-400 rounded-xl py-2 outline-none shadow-inner transition-colors`;
    displayHtml.innerHTML = `
        <span class='text-${grade.color}-600 font-black'>${grade.huruf} | ${grade.derajat}</span><br>
        <span class='text-[9px] ${grade.taqdir === 'Lulus' ? 'text-emerald-500' : 'text-rose-500'} mt-0.5 inline-block'>[ ${grade.taqdir} ]</span>
    `;
}

async function simpanSetoran() {
    const form = document.getElementById('formSetoran');
    const formData = new FormData(form);
    formData.append('tanggal', document.getElementById('tanggalSetoran').value);

    const btn = document.querySelector('button[onclick="simpanSetoran()"]');
    const textSpan = document.getElementById('textBtnSaveAll');
    const originalText = textSpan.innerHTML;

    try {
        textSpan.innerHTML = "Menyimpan...";
        btn.disabled = true;

        Swal.fire({ title: 'Menyimpan Data...', text: 'Mengamankan sistem relasi ID Database', allowOutsideClick: false, showConfirmButton: false, didOpen: () => { Swal.showLoading(); } });

        const response = await fetch(CONFIG.save_url, {
            method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });

        const result = await response.json();
        textSpan.innerHTML = originalText;
        btn.disabled = false;

        if (result.status === 'success') {
            Swal.fire({ icon: 'success', title: 'Alhamdulillah Berhasil!', text: result.message }).then(() => { loadSiswa(); }); 
        } else {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: result.message });
        }
    } catch (error) {
        textSpan.innerHTML = originalText;
        btn.disabled = false;
        Swal.fire({ icon: 'error', title: 'Koneksi Terputus', text: 'Gagal terhubung ke database sekolah.' });
    }
}

// ---------------------------------------------------------
// EXPORT/IMPORT EXCEL (CSV) - MENARIK SELURUH RIWAYAT (FULL REKAP)
// ---------------------------------------------------------
function openExcelModal() {
    const modal = document.getElementById('modalExcel');
    if(!document.getElementById('kelasSelect').value) {
        return Swal.fire({ icon: 'info', title: 'Perhatian', text: 'Silakan pilih Kelas terlebih dahulu.'});
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

async function exportToExcel(isTemplate) {
    if (typeof ExcelJS === 'undefined') return Swal.fire('Error', 'Modul Excel gagal dimuat. Pastikan ada koneksi internet.', 'error');
    if (!globalStudents || globalStudents.length === 0) return Swal.fire('Error', 'Data Santri Kosong. Pastikan kelas telah dipilih dan mempunyai santri.', 'error');

    const juzSelect = document.getElementById('juzSelect');
    const juzOptions = Array.from(juzSelect.options).filter(opt => opt.value !== "");
    
    if(juzOptions.length === 0) return Swal.fire('Error', 'Senarai Juz tidak ditemui dalam sistem.', 'error');

    Swal.fire({ title: 'Membuat Excel...', text: 'Sila tunggu, sedang membina 30 Sheet dan menarik seluruh data hafalan santri...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

    const workbook = new ExcelJS.Workbook();
    const headerFill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FF10B981' } }; 
    const headerFont = { color: { argb: 'FFFFFFFF' }, bold: true };
    const borderAll = { top: { style: 'thin' }, left: { style: 'thin' }, bottom: { style: 'thin' }, right: { style: 'thin' } };
    const alignCenter = { vertical: 'middle', horizontal: 'center' };

    for (let opt of juzOptions) {
        let jId = opt.value;
        let jName = opt.text;
        
        let safeSheetName = jName.replace(/[^a-zA-Z0-9 ]/g, "").substring(0, 31);
        let worksheet = workbook.addWorksheet(safeSheetName);
        
        let surahListObjs = JUZ_DATA[jId] || [];
        let surahList = surahListObjs.map(s => s.display); 
        
        let currentRow = 1;

        worksheet.mergeCells(`A${currentRow}:G${currentRow}`);
        const titleCell = worksheet.getCell(`A${currentRow}`);
        titleCell.value = `LEMBAR INPUT SETORAN TAHFIDZ - KELAS ${CONFIG.class_name.toUpperCase()} - ${jName.toUpperCase()}`;
        titleCell.font = { size: 14, bold: true, color: { argb: 'FF047857' } }; 
        titleCell.alignment = alignCenter;
        currentRow += 2;

        if (isTemplate) {
            worksheet.mergeCells(`A${currentRow}:G${currentRow}`);
            const instCell = worksheet.getCell(`A${currentRow}`);
            instCell.value = "PANDUAN PENGISIAN TAHFIDZ:\n1. Isikan Nilai Angka (0-100) langsung pada kolom surah yang dihafal santri.\n2. Kosongkan sel jika santri belum menyetorkan surah tersebut.\n3. Jangan mengubah urutan kolom No, NISN, Nama Siswa, dan Kelas agar sistem dapat membacanya.";
            instCell.font = { italic: true, color: { argb: 'FF064E3B' } };
            instCell.fill = { type: 'pattern', pattern: 'solid', fgColor: { argb: 'FFECFDF5' } }; 
            instCell.alignment = { vertical: 'middle', horizontal: 'left', wrapText: true };
            worksheet.getRow(currentRow).height = 60;
            currentRow += 2;
        }
        
        worksheet.mergeCells(`A${currentRow}:C${currentRow}`);
        worksheet.getCell(`A${currentRow}`).value = 'TANGGAL SETORAN :';
        worksheet.getCell(`A${currentRow}`).font = { bold: true };
        worksheet.getCell(`D${currentRow}`).value = document.getElementById('tanggalSetoran').value;
        currentRow++;

        worksheet.mergeCells(`A${currentRow}:C${currentRow}`);
        worksheet.getCell(`A${currentRow}`).value = 'SEMESTER AKTIF :';
        worksheet.getCell(`A${currentRow}`).font = { bold: true };
        let namaSemester = '-';
        if (typeof CONFIG.ta_info !== 'undefined' && CONFIG.ta_info !== null) {
             namaSemester = CONFIG.ta_info.nama || CONFIG.ta_info.tahun || '-';
        }
        worksheet.getCell(`D${currentRow}`).value = namaSemester;
        currentRow += 2;

        const headers = ["No", "NISN", "Nama Siswa", "Kelas", ...surahList];
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

        worksheet.columns = [{width: 5}, {width: 15}, {width: 35}, {width: 15}];
        for(let i = 5; i <= headers.length; i++) { worksheet.getColumn(i).width = 18; }

        globalStudents.forEach((siswa, index) => {
            const row = worksheet.getRow(currentRow);
            row.getCell(1).value = index + 1;
            row.getCell(2).value = siswa.nisn || siswa.nis || '-';
            row.getCell(3).value = siswa.nama_lengkap;
            row.getCell(4).value = CONFIG.class_name;

            let colIndex = 5;
            surahListObjs.forEach(block => {
                let val = '';
                
                // MENCARI SELURUH NILAI SANTRI DARI ARRAY
                if (!isTemplate && siswa.semua_nilai && Array.isArray(siswa.semua_nilai)) {
                    let clean_ref_ayat = String(block.ayat).replace(/[^a-zA-Z0-9]/g, '').toLowerCase();
                    
                    siswa.semua_nilai.forEach(rek => {
                        let kSurah = String(rek.surah_id);
                        let kAyatClean = String(rek.ayat || 'Semua').replace(/[^a-zA-Z0-9]/g, '').toLowerCase();
                        
                        if (String(block.surah_id) === kSurah && clean_ref_ayat === kAyatClean) {
                            let score = parseInt(rek.nilai, 10);
                            if (!isNaN(score) && score > 0) {
                                val = score;
                            }
                        }
                    });
                }

                row.getCell(colIndex).value = val;
                row.getCell(colIndex).border = borderAll;
                row.getCell(colIndex).alignment = alignCenter;
                row.getCell(colIndex).dataValidation = {
                    type: 'whole', operator: 'between', allowBlank: true, showInputMessage: true,
                    promptTitle: 'Input Nilai', prompt: 'Masukkan nilai hafalan (0 - 100)', formulae: [0, 100]
                };
                colIndex++;
            });

            for(let i = 1; i <= 4; i++) {
                row.getCell(i).border = borderAll;
                if(i !== 3) row.getCell(i).alignment = alignCenter; 
            }
            
            currentRow++;
        });
    }

    const buffer = await workbook.xlsx.writeBuffer();
    const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
    const link = document.createElement("a");
    link.href = URL.createObjectURL(blob);
    link.download = isTemplate ? `Template_Tahfidz_Semua_Juz_${CONFIG.class_name}.xlsx` : `Export_Rekap_Tahfidz_Semua_Juz_${CONFIG.class_name}.xlsx`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    
    Swal.close();
}

function downloadTemplate() { exportToExcel(true); }
function exportData() { exportToExcel(false); }

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
    
    const juzSelect = document.getElementById('juzSelect');
    if (!juzSelect.value) {
        return Swal.fire('Error', 'Sila pilih Juz Target dari dropdown terlebih dahulu untuk menentukan sheet mana yang akan diimport!', 'error');
    }
    const selectedJuzName = juzSelect.options[juzSelect.selectedIndex].text.toLowerCase().replace(/[^a-z0-9]/g, "");

    Swal.fire({ title: 'Memproses Fail...', html: `Membaca fail <b>${file.name}</b>`, allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

    if (file.name.endsWith('.xls') || file.name.endsWith('.xlsx')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const data = new Uint8Array(e.target.result);
            const workbook = XLSX.read(data, {type: 'array'});
            
            let targetSheetName = null;
            workbook.SheetNames.forEach(name => {
                let cleanName = name.toLowerCase().replace(/[^a-z0-9]/g, "");
                if(cleanName === selectedJuzName || cleanName.includes(selectedJuzName)) {
                    targetSheetName = name;
                }
            });
            
            if(!targetSheetName) targetSheetName = workbook.SheetNames[0];

            const worksheet = workbook.Sheets[targetSheetName];
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
    const tgl = document.getElementById('tanggalSetoran').value;
    const juzId = document.getElementById('juzSelect').value;
    
    if(!juzId) return Swal.fire('Error', 'Sila pilih Juz Target terlebih dahulu.', 'error');

    const formData = new FormData();
    formData.append('file_csv', file);
    formData.append('tanggal_import', tgl);
    formData.append('juz_import', juzId);

    Swal.fire({ title: 'Sedang Mengimport Data...', html: `Mentransfer data ke sistem...`, allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

    try {
        const response = await fetch(CONFIG.import_url, { method: 'POST', body: formData, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const res = await response.json();
        if (response.ok && res.status === 'success') {
            Swal.fire({ icon: 'success', title: 'Alhamdulillah!', text: res.message, customClass: { popup: 'rounded-[2rem]' } }).then(() => { loadSiswa(); }); 
        } else {
            Swal.fire({ icon: 'error', title: 'Gagal', text: res.message, customClass: { popup: 'rounded-[2rem]' } });
        }
    } catch (error) {
        Swal.fire({ icon: 'error', title: 'Ralat', text: 'Berlaku ralat pada server semasa proses import.' });
    } finally {
        document.getElementById('fileInputCSV').value = ""; 
    }
}