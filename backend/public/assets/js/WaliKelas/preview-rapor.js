let studentsData = [];
let selectedStudentData = { id: '', nama: '' };
let currentActionType = 'preview';

document.addEventListener('DOMContentLoaded', function() {
    if (typeof serverStudents !== 'undefined') {
        studentsData = Array.isArray(serverStudents) ? serverStudents : [];
    }
    
    // Inisialisasi awal
    renderStudentsTable(studentsData); 
    populateStudentSelect();
});

// 1. Fungsi Mengisi Dropdown (Pilih Siswa)
function populateStudentSelect() {
    const selectEl = document.getElementById('filterSiswa');
    if (!selectEl) return;

    let options = `<option value="">-- Pilih Siswa Terlebih Dahulu --</option>`;
    studentsData.forEach(s => {
        options += `<option value="${s.id}">${s.name} (${s.nis || '-'})</option>`;
    });
    
    selectEl.innerHTML = options;
}

// 2. Fungsi Merender Tabel Visual
function renderStudentsTable(data) {
    const container = document.getElementById('studentsTableContainer');
    const emptyState = document.getElementById('emptyState');
    const countBadge = document.getElementById('studentCount');

    if (!Array.isArray(data) || data.length === 0) {
        if (container) container.innerHTML = '';
        if (emptyState) emptyState.style.display = 'flex'; 
        if (countBadge) countBadge.textContent = `0 Siswa`;
        return;
    }

    if (emptyState) emptyState.style.display = 'none';
    if (countBadge) countBadge.textContent = `${data.length} Siswa`;

    let html = `
    <table class="w-full text-left border-collapse whitespace-nowrap mb-4">
        <thead class="bg-gray-50 dark:bg-slate-700/50 text-gray-500 dark:text-slate-400 text-xs uppercase tracking-wider border-b border-gray-200 dark:border-slate-600">
            <tr>
                <th class="p-4 font-bold text-center w-12">No</th>
                <th class="p-4 font-bold">Nama Siswa</th>
                <th class="p-4 font-bold text-center">NIS / NISN</th>
                <th class="p-4 font-bold text-center w-48">Aksi Cetak</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-slate-700 text-sm text-gray-700 dark:text-slate-300">
    `;

    data.forEach((s, index) => {
        const safeName = s.name ? s.name.replace(/'/g, "\\'").replace(/"/g, "&quot;") : 'Tanpa Nama';
        const nis = s.nis ? s.nis : '-';
        
        html += `
            <tr class="hover:bg-gray-50 dark:hover:bg-slate-800/50 transition-colors group">
                <td class="p-4 text-center font-medium">${index + 1}</td>
                <td class="p-4 font-bold text-gray-900 dark:text-white text-base">${s.name}</td>
                <td class="p-4 text-center font-mono text-gray-500 font-bold">${nis}</td>
                <td class="p-4 text-center">
                    <div class="flex justify-center gap-2">
                        <button onclick="quickAction('${s.id}', 'preview', '${safeName}')" class="px-4 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg font-bold transition-colors flex items-center gap-2 text-xs outline-none border border-blue-200 shadow-sm hover:shadow">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> Lihat Rapor
                        </button>
                        <button onclick="quickAction('${s.id}', 'download', '${safeName}')" class="px-4 py-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-lg font-bold transition-colors flex items-center gap-2 text-xs outline-none border border-emerald-200 shadow-sm hover:shadow">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg> Unduh PDF
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });

    html += `</tbody></table>`;
    if (container) container.innerHTML = html;
}

// 3. Fitur Live Search (Ketik Langsung Terfilter)
const searchInput = document.getElementById('searchSiswa');
if (searchInput) {
    searchInput.addEventListener('input', function(e) {
        const keyword = e.target.value.toLowerCase();
        const filtered = studentsData.filter(s => {
            const nameMatch = s.name && s.name.toLowerCase().includes(keyword);
            const nisMatch = s.nis && s.nis.toLowerCase().includes(keyword);
            return nameMatch || nisMatch;
        });
        renderStudentsTable(filtered);
    });
}

// 4. Aksi Dari Dropdown Atas (Pilih Siswa)
window.checkAndOpenAction = function(actionType) {
    const select = document.getElementById('filterSiswa');
    if (!select || !select.value) {
        Swal.fire({
            icon: 'warning',
            title: 'Siswa Belum Dipilih',
            text: 'Silakan pilih siswa dari menu dropdown terlebih dahulu.',
            confirmButtonColor: '#10b981'
        });
        return;
    }
    
    const siswaId = select.value;
    const studentName = select.options[select.selectedIndex].text.split('(')[0].trim();
    quickAction(siswaId, actionType, studentName);
};

// 5. Eksekusi Pencetakan URL PDF (API Call)
window.quickAction = function(siswaId, actionType, studentName) {
    selectedStudentData = { id: siswaId, nama: studentName };
    currentActionType = actionType;
    openInputModal(siswaId, studentName);
};

// --- MODAL INPUT CATATAN ---
window.openInputModal = function(siswaId, studentName) {
    document.getElementById('inputSiswaName').textContent = studentName;
    document.getElementById('inputSiswaId').value = siswaId;
    
    // DECISION LOGIC: Get Grade From UI (Dynamic extraction)
    let tingkat = '7'; // Default
    const textTingkat = document.body.innerText.toUpperCase(); // Brute search in page context if needed
    // Prefer explicitly extracted context
    if (window.location.search.includes('tingkat=')) {
        const urlParams = new URLSearchParams(window.location.search);
        tingkat = urlParams.get('tingkat');
    } else {
        // Fallback: search for "Kelas VII", etc. in headers
        const pageTitle = document.querySelector('h1') ? document.querySelector('h1').innerText.toUpperCase() : '';
        const classBadge = document.querySelector('.border-l-tema') ? document.querySelector('.border-l-tema').innerText.toUpperCase() : '';
        const combined = pageTitle + ' ' + classBadge;

        if (combined.includes('IX') || combined.includes('9')) tingkat = '9';
        else if (combined.includes('VIII') || combined.includes('8')) tingkat = '8';
        else if (combined.includes('VII') || combined.includes('7')) tingkat = '7';
    }

    const selectKenaikan = document.getElementById('inputKenaikan');
    let options = '';
    
    if (tingkat === '9') {
        options = `
            <option value="LULUS">LULUS</option>
            <option value="TIDAK LULUS">TIDAK LULUS</option>
        `;
    } else if (tingkat === '8') {
        options = `
            <option value="NAIK KE KELAS : IX (SEMBILAN)">NAIK KE KELAS : IX (SEMBILAN)</option>
            <option value="TINGGAL DI KELAS : VIII (DELAPAN)">TINGGAL DI KELAS : VIII (DELAPAN)</option>
        `;
    } else {
        options = `
            <option value="NAIK KE KELAS : VIII (DELAPAN)">NAIK KE KELAS : VIII (DELAPAN)</option>
            <option value="TINGGAL DI KELAS : VII (TUJUH)">TINGGAL DI KELAS : VII (TUJUH)</option>
        `;
    }

    if (selectKenaikan) selectKenaikan.innerHTML = options;

    const modal = document.getElementById('modalInputCatatan');
    const modalContent = document.getElementById('modalInputContent');
    
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            if (modalContent) {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }
        }, 10);
    }

    // Fetch existing data
    const taId = document.getElementById('selectTA') ? document.getElementById('selectTA').value : '';
    fetch(`${API_URL}/get-catatan?siswa_id=${siswaId}&ta=${taId}`)
    .then(res => res.json())
    .then(res => {
        if (res.status === 'success' && res.catatan) {
            document.getElementById('inputPengantar').value = res.catatan.catatan_wali_kelas || '';
            document.getElementById('inputCatatan').value = res.catatan.catatan_wali_kelas || ''; // Map correctly
            document.getElementById('inputKenaikan').value = res.catatan.status_kenaikan || '';
        } else {
            document.getElementById('inputPengantar').value = '';
            document.getElementById('inputCatatan').value = '';
            // Don't reset select, keep the auto-filled first option
        }
    });
};

window.closeInputModal = function() {
    const modal = document.getElementById('modalInputCatatan');
    const modalContent = document.getElementById('modalInputContent');
    if (modalContent) {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
    }
    setTimeout(() => {
        if (modal) modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 300);
};

window.saveAndProcess = function() {
    const siswaId = document.getElementById('inputSiswaId').value;
    const pengantarEl = document.getElementById('inputPengantar');
    const pengantar = pengantarEl ? pengantarEl.value : '';
    const catatan = document.getElementById('inputCatatan').value;
    const kenaikan = document.getElementById('inputKenaikan').value;
    const taId = document.getElementById('selectTA') ? document.getElementById('selectTA').value : '';

    console.log('Saving catatan for student:', siswaId, 'TA:', taId);

    const formData = new FormData();
    formData.append('siswa_id', siswaId);
    formData.append('catatan_wali', (pengantar + "\n\n" + catatan).trim());
    formData.append('status_kenaikan', kenaikan);
    formData.append('ta', taId);
    
    // CSRF - Robust detection
    const csrfEl = document.getElementById('csrf_token') || document.querySelector('input[type="hidden"][value*="f"]'); // Try find by value hint if name unknown
    const csrfName = csrfEl ? csrfEl.name : 'csrf_test_name';
    const csrfValue = csrfEl ? csrfEl.value : '';
    
    if (csrfEl) {
        formData.append(csrfName, csrfValue);
        console.log('CSRF Token found:', csrfName);
    } else {
        console.warn('CSRF Token NOT found! This might cause a hang.');
    }

    Swal.fire({
        title: 'Menyimpan Catatan...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    fetch(`${API_URL}/save-catatan`, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === 'success') {
            Swal.close(); // Tutup spinner loading
            closeInputModal();
            // Proceed to PDF
            executeFinalPDF();
        } else {
            Swal.fire('Gagal!', res.message, 'error');
        }
    })
    .catch(err => {
        Swal.close();
        console.error('Save error:', err);
        Swal.fire('Error!', 'Terjadi kesalahan sistem saat menyimpan catatan.', 'error');
    });
};

function executeFinalPDF() {
    const actionType = currentActionType;
    const siswaId = selectedStudentData.id;
    const studentName = selectedStudentData.nama;

    const jenisRaporEl = document.querySelector('input[name="jenisRapor"]:checked');
    const jenisRapor = jenisRaporEl ? jenisRaporEl.value : 'lengkap';
    
    const optCover = document.getElementById('checkCover').checked ? 1 : 0;
    const optTtd = document.getElementById('checkTTD').checked ? 1 : 0;
    const optQr = document.getElementById('checkQR').checked ? 1 : 0;

    const taId = document.getElementById('selectTA') ? document.getElementById('selectTA').value : '';
    const tglRapor = document.getElementById('tglRapor') ? encodeURIComponent(document.getElementById('tglRapor').value) : '';
    const tempat = document.getElementById('tempatRapor') ? encodeURIComponent(document.getElementById('tempatRapor').value) : '';

    const kategoriEl = document.getElementById('filterKategori');
    const kategori = kategoriEl ? encodeURIComponent(kategoriEl.value) : 'Akhir%20Semester';

    const pdfUrl = `${API_URL}/printPDF/${siswaId}/${actionType}?jenis_rapor=${jenisRapor}&cover=${optCover}&ttd=${optTtd}&qr=${optQr}&ta=${taId}&tgl_rapor=${tglRapor}&tempat=${tempat}&kategori=${kategori}`;

    if (actionType === 'preview') {
        openIframePreview(pdfUrl, studentName);
    } else {
        Swal.fire({
            title: 'Menyiapkan PDF',
            text: 'Memproses laporan siswa...',
            timer: 2000,
            showConfirmButton: false,
            willOpen: () => { Swal.showLoading(); }
        });
        window.location.href = pdfUrl; 
    }
}

// 6. Preview Layout Kertas (Simulasi)
window.showPreview = function(pageNumber) {
    Swal.fire({
        icon: 'info',
        title: 'Pratinjau Tata Letak',
        text: `Ini hanyalah pratinjau gambaran untuk Halaman ${pageNumber}. Silakan klik "Lihat Rapor" pada tabel untuk merender hasil PDF asli.`,
        confirmButtonColor: '#10b981'
    });
};

// 7. Modal Iframe PDF Loader
window.openIframePreview = function(url, studentName) {
    const modal = document.getElementById('modalPreviewKertas');
    const modalContent = document.getElementById('modalPreviewContent');
    const iframeContainer = document.getElementById('iframeContainer');
    const loader = document.getElementById('iframeLoader');
    const titleEl = document.getElementById('previewSiswaName');
    
    if (titleEl) titleEl.textContent = `Menampilkan Rapor: ${studentName}`;

    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    if (loader) loader.classList.remove('hidden');

    if (iframeContainer) {
        iframeContainer.innerHTML = `<iframe id="raporIframe" src="${url}" class="w-full h-full border-none bg-gray-200 dark:bg-slate-800" onload="hideIframeLoader()"></iframe>`;
    }

    setTimeout(() => {
        if (modalContent) {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }
    }, 10);
};

window.hideIframeLoader = function() {
    const loader = document.getElementById('iframeLoader');
    if(loader) loader.classList.add('hidden');
};

window.closePreviewKertas = function() {
    const modal = document.getElementById('modalPreviewKertas');
    const iframeContainer = document.getElementById('iframeContainer');
    const modalContent = document.getElementById('modalPreviewContent');
    
    if (modalContent) {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
    }
    
    setTimeout(() => {
        if (modal) modal.classList.add('hidden');
        document.body.style.overflow = '';
        if (iframeContainer) iframeContainer.innerHTML = ''; 
    }, 300);
};

window.printFromIframe = function() {
    const iframe = document.getElementById('raporIframe');
    if (iframe && iframe.contentWindow) {
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
    }
};

// ==========================================
// 8. ROBO-DOWNLOADER: CETAK MASSAL WALI KELAS
// ==========================================
window.batchPrint = async function() {
    // 1. Cek apakah ada data siswa di kelas ini
    if (studentsData.length === 0) {
        Swal.fire({
            icon: 'warning', 
            title: 'Kelas Kosong!', 
            text: 'Tidak ada data siswa di kelas ini.', 
            confirmButtonColor: '#f59e0b',
            customClass: { popup: 'rounded-3xl' }
        });
        return;
    }

    // 2. Minta Konfirmasi ke Wali Kelas
    const konfirmasi = await Swal.fire({
        title: 'Mulai Cetak Massal?',
        html: `Sistem akan mengunduh (download) rapor <b>${studentsData.length} siswa</b> secara otomatis satu per satu.<br><br><span class="text-sm text-red-500 font-bold">PENTING: Jangan tutup halaman web ini selama proses berlangsung!</span>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, Mulai Download!',
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-3xl' }
    });

    if (!konfirmasi.isConfirmed) return;

    // 3. Siapkan parameter format rapor yang dipilih oleh Wali Kelas
    let jenisRapor = 'lengkap';
    const radioJenis = document.querySelector('input[name="jenisRapor"]:checked');
    if (radioJenis) jenisRapor = radioJenis.value;

    const optCover = document.getElementById('checkCover') && document.getElementById('checkCover').checked ? '1' : '0';
    const optTtd   = document.getElementById('checkTTD') && document.getElementById('checkTTD').checked ? '1' : '0'; 
    const optQr    = document.getElementById('checkQR') && document.getElementById('checkQR').checked ? '1' : '0';
    
    const kategori = document.getElementById('filterKategori') ? encodeURIComponent(document.getElementById('filterKategori').value) : 'Akhir%20Semester';
    const taId = document.getElementById('selectTA') ? document.getElementById('selectTA').value : '';
    const tglRapor = document.getElementById('tglRapor') ? encodeURIComponent(document.getElementById('tglRapor').value) : '';
    const tempat = document.getElementById('tempatRapor') ? encodeURIComponent(document.getElementById('tempatRapor').value) : '';

    // 4. Looping satu per satu siswa (The Magic!)
    let successCount = 0;
    
    for (let i = 0; i < studentsData.length; i++) {
        const siswaId = studentsData[i].id;
        const namaSiswa = studentsData[i].name || 'Siswa';
        const nisSiswa = studentsData[i].nis || '000';

        // Tampilkan loading screen dinamis (gunakan Swal.update agar popup tidak berkedip)
        if (i === 0) {
            Swal.fire({
                title: 'Mendownload...',
                html: `Memproses Rapor:<br><b class="text-indigo-600 text-lg">${namaSiswa}</b><br><br>Siswa ke <b>${i + 1}</b> dari <b>${studentsData.length}</b><br><br><div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700"><div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500" style="width: ${((i+1)/studentsData.length)*100}%"></div></div>`,
                allowOutsideClick: false,
                showConfirmButton: false,
                customClass: { popup: 'rounded-3xl' },
                didOpen: () => { Swal.showLoading(); }
            });
        } else {
            Swal.update({
                html: `Memproses Rapor:<br><b class="text-indigo-600 text-lg">${namaSiswa}</b><br><br>Siswa ke <b>${i + 1}</b> dari <b>${studentsData.length}</b><br><br><div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700"><div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500" style="width: ${((i+1)/studentsData.length)*100}%"></div></div>`
            });
        }

        // Rakit URL Download
        const url = `${API_URL}/printPDF/${siswaId}/download?jenis_rapor=${jenisRapor}&cover=${optCover}&ttd=${optTtd}&qr=${optQr}&ta=${taId}&tgl_rapor=${tglRapor}&tempat=${tempat}&kategori=${kategori}`;

        try {
            // Fetch data dan tunggu sampai server selesai merakit PDF
            const response = await fetch(url);
            const blob = await response.blob();
            
            // Buat link download bayangan di browser
            const downloadUrl = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = downloadUrl;
            
            // Format Nama File: rapor_NIS_Nama_STS/SAS_Lengkap.pdf
            const namaFileBersih = namaSiswa.replace(/[^a-zA-Z0-9]/g, '_');
            const kategoriValue = document.getElementById('filterKategori')?.value || '';
            const katShort = kategoriValue.includes('Tengah') ? 'STS' : 'SAS';
            
            if (jenisRapor === 'tahfidz') {
                const juzId = new URL(url).searchParams.get('juz') || '30';
                a.download = `Rapor_Tahfidz_Juz${juzId}_${nisSiswa}_${namaFileBersih}.pdf`;
            } else {
                a.download = `rapor_${nisSiswa}_${namaFileBersih}_${katShort}_Lengkap.pdf`;
            }
            
            // Eksekusi download
            document.body.appendChild(a);
            a.click();
            
            // Bersihkan memori RAM
            window.URL.revokeObjectURL(downloadUrl);
            a.remove();
            
            // Beri nafas ke server 1.5 detik sebelum memproses siswa berikutnya
            await new Promise(resolve => setTimeout(resolve, 1500));
            
            successCount++;
        } catch (error) {
            console.error(`Gagal mendownload rapor ${namaSiswa}`, error);
        }
    }

    // 5. Proses Selesai
    Swal.fire({
        icon: 'success',
        title: 'Selesai!',
        text: `Berhasil mengunduh rapor ${successCount} siswa. Silakan cek folder "Downloads" di komputer Anda.`,
        confirmButtonColor: '#10b981',
        customClass: { popup: 'rounded-3xl' }
    });
};