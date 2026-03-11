// ==========================================
// SABUK PENGAMAN (FALLBACK)
// ==========================================
const LANG = window.LANG || {
    js_loading: 'Memuat data...',
    js_select_room: '-- Pilih Rombel Terlebih Dahulu --',
    js_select_student: '-- Pilih Siswa --',
    js_no_student: 'Tidak ada siswa di rombel ini',
    js_locked: 'Terkunci',
    js_unlocked: 'Terbuka',
    js_ready_print: 'Siap Cetak',
    js_valid_report: 'Rapor siswa ini valid.',
    js_warning: 'Peringatan',
    js_unlocked_val: 'Nilai belum dikunci.',
    js_swal_no_room: 'Rombel Belum Dipilih',
    js_swal_room_desc: 'Silakan pilih Tingkat dan Rombel terlebih dahulu pada filter di atas.',
    js_swal_no_stu: 'Siswa Belum Dipilih',
    js_swal_stu_desc: 'Mohon pilih spesifik satu siswa untuk dicetak rapornya.',
    js_swal_stu_tip: 'Tips: Pilih nama siswa di dropdown sebelah kanan.',
    js_swal_saving: 'Menyimpan Data...',
    js_swal_wait: 'Mohon tunggu sebentar',
    js_swal_fail: 'Gagal',
    js_swal_fail_desc: 'Gagal menyimpan.',
    js_swal_err: 'Error',
    js_swal_err_desc: 'Terjadi kesalahan sistem.'
};

// Variable Global
let selectedStudentData = null;
let currentAction = 'preview';
let rombelIsLocked = false;

// 1. Load Daftar Siswa
function loadSiswaOptions() {
    const rombelId = document.getElementById('filterRombel').value;
    const siswaSelect = document.getElementById('filterSiswa');
    
    selectedStudentData = null; 
    siswaSelect.innerHTML = `<option value="">${LANG.js_loading}</option>`;

    if (!rombelId) {
        siswaSelect.innerHTML = `<option value="">${LANG.js_select_room}</option>`;
        resetStatusInfo();
        return;
    }

    fetch(`${API_URL}/getSiswaByRombel?rombel_id=${rombelId}`)
        .then(res => res.json())
        .then(response => {
            if (response.status === 'success') {
                const siswa = response.data_siswa;
                const info = response.info_rombel;
                rombelIsLocked = info.is_locked;

                if (siswa.length > 0) {
                    let options = `<option value="">${LANG.js_select_student}</option>`;
                    siswa.forEach(s => {
                        options += `<option value="${s.id}" data-nama="${s.nama_lengkap}" data-nis="${s.nis}">${s.nama_lengkap} (${s.nis})</option>`;
                    });
                    siswaSelect.innerHTML = options;
                    siswaSelect.disabled = false;
                } else {
                    siswaSelect.innerHTML = `<option value="">${LANG.js_no_student}</option>`;
                }
                
                updateStatusBadge(info);
            }
        });
}

function updateStatusBadge(info) {
    const statusBadge = document.getElementById('statusLock');
    const waktuLock = document.getElementById('waktuLock');
    
    if (info.is_locked) {
        statusBadge.innerHTML = `<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg> ${LANG.js_locked}`;
        statusBadge.className = 'badge-chip badge-locked bg-emerald-100 text-emerald-700 border-emerald-200';
        waktuLock.textContent = info.locked_at;
    } else {
        statusBadge.innerHTML = `<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" /></svg> ${LANG.js_unlocked}`;
        statusBadge.className = 'badge-chip bg-amber-100 text-amber-700 border-amber-200 px-2 py-1 rounded text-xs font-bold flex items-center gap-1 w-fit';
        waktuLock.textContent = '-';
    }
}

// 2. Fungsi saat Siswa Dipilih
function enablePrintButton() {
    const siswaSelect = document.getElementById('filterSiswa');
    const selectedOption = siswaSelect.options[siswaSelect.selectedIndex];
    const siswaId = selectedOption.value;

    if (siswaId) {
        selectedStudentData = {
            id: siswaId,
            nama: selectedOption.getAttribute('data-nama'),
            nis: selectedOption.getAttribute('data-nis')
        };
        showValidMessage(true);
    } else {
        selectedStudentData = null;
        showValidMessage(false);
    }
}

function showValidMessage(show) {
    const validMessage = document.getElementById('validMessage');
    if(show) {
        validMessage.classList.remove('hidden');
        if (rombelIsLocked) {
            validMessage.innerHTML = `
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div><p class="text-xs font-bold text-emerald-900 mb-1">${LANG.js_ready_print}</p><p class="text-xs text-emerald-800">${LANG.js_valid_report}</p></div>
                </div>`;
        } else {
            validMessage.innerHTML = `
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <div><p class="text-xs font-bold text-amber-900 mb-1">${LANG.js_warning}</p><p class="text-xs text-amber-800">${LANG.js_unlocked_val}</p></div>
                </div>`;
        }
    } else {
        validMessage.classList.add('hidden');
    }
}

// ------------------------------------------------------------------
// 3. LOGIKA VALIDASI & MODAL INPUT
// ------------------------------------------------------------------

function checkAndOpenModal(action) {
    const rombelVal = document.getElementById('filterRombel').value;
    const siswaVal = document.getElementById('filterSiswa').value;

    if (!rombelVal) {
        Swal.fire({
            icon: 'warning',
            title: LANG.js_swal_no_room,
            text: LANG.js_swal_room_desc,
            confirmButtonColor: '#f59e0b'
        });
        return;
    }

    if (!siswaVal || !selectedStudentData) {
        Swal.fire({
            icon: 'info',
            title: LANG.js_swal_no_stu,
            text: LANG.js_swal_stu_desc,
            footer: `<span class="text-sm text-gray-500">${LANG.js_swal_stu_tip}</span>`,
            confirmButtonColor: '#3b82f6'
        });
        return;
    }

    openInputModal(action);
}

function closeInputModal() {
    document.getElementById('modalInputRapor').classList.add('hidden');
}

function simpanDanCetak(actionType) {
    if (!selectedStudentData) return;

    const pengantar = document.getElementById('inputPengantar').value;
    const catatan = document.getElementById('inputCatatanWali').value;
    const kenaikan = document.getElementById('inputKenaikan').value;
    
    const sakit = document.getElementById('inputSakit').value;
    const izin  = document.getElementById('inputIzin').value;
    const alpha = document.getElementById('inputAlpha').value;

    Swal.fire({
        title: LANG.js_swal_saving,
        text: LANG.js_swal_wait,
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    const formData = new FormData();
    formData.append('siswa_id', selectedStudentData.id);
    formData.append('kata_pengantar', pengantar);
    formData.append('catatan_wali', catatan);
    formData.append('status_kenaikan', kenaikan);
    formData.append('sakit', sakit);
    formData.append('izin', izin);
    formData.append('alpha', alpha);

    fetch(`${API_URL}/saveCatatanRapor`, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(response => {
        if (response.status === 'success') {
            closeInputModal();
            Swal.close(); 

            const url = `${API_URL}/printPDF/${selectedStudentData.id}/${actionType}`;
            
            if (actionType === 'preview') {
                window.open(url, '_blank');
            } else {
                window.location.href = url;
            }
        } else {
            Swal.fire(LANG.js_swal_fail, response.message || LANG.js_swal_fail_desc, 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire(LANG.js_swal_err, LANG.js_swal_err_desc, 'error');
    });
}

function openInputModal(action) {
    currentAction = action;
    document.getElementById('inputCatatanWali').value = ''; 
    document.getElementById('inputSakit').value = '0'; 
    document.getElementById('inputIzin').value = '0'; 
    document.getElementById('inputAlpha').value = '0';
    document.getElementById('modalInputRapor').classList.remove('hidden');
}

function resetStatusInfo() {
    document.getElementById('statusLock').innerHTML = '-';
    document.getElementById('waktuLock').textContent = '-';
}

// ------------------------------------------------------------------
// 4. LOGIKA MODAL PREVIEW KERTAS (LIVE ANIMATION)
// ------------------------------------------------------------------

function showPreview(pageNumber) {
    const modalPreview = document.getElementById('modalPreviewKertas');
    const judul = document.getElementById('teksHalaman');
    
    // Ganti Teks Judul Kertas Sesuai Kotak yang Diklik
    const judulHalaman = [
        'Halaman Cover', 'Halaman Identitas Siswa', 'Halaman Nilai Akademik',
        'Halaman Ekstrakurikuler', 'Halaman Absensi', 'Halaman Sikap/Karakter'
    ];
    if(judul) judul.textContent = judulHalaman[pageNumber - 1];

    // Tampilkan Modal
    if(modalPreview) {
        modalPreview.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Panggil ulang biar ukurannya nge-set
    updatePreviewLayout();
}

function closePreviewKertas() {
    const modalPreview = document.getElementById('modalPreviewKertas');
    if(modalPreview) modalPreview.classList.add('hidden');
    document.body.style.overflow = '';
}

// Fungsi Inti Kalkulator CSS Kertas (Bikin Thumbnails Melebar/Gepeng)
function updatePreviewLayout() {
    const ukuranSelect = document.getElementById('settingUkuran');
    const marginSelect = document.getElementById('settingMargin');
    const skalaSelect = document.getElementById('settingSkala');
    const nomorSelect = document.getElementById('settingNomor');
    
    if (!ukuranSelect) return;

    const ukuran = ukuranSelect.value;
    const margin = marginSelect ? marginSelect.value : 'standard';
    const skala = skalaSelect ? skalaSelect.value : '100';
    const penomoran = nomorSelect ? nomorSelect.value : 'on';

    const kertas = document.getElementById('kertasSimulasi');
    const info = document.getElementById('infoKertas');
    const halNomor = document.getElementById('nomorHalaman');

    // 1. Atur Ukuran Kertas di Modal (Potrait vs Landscape)
    if(kertas) {
        if (ukuran === 'a4-landscape') {
            kertas.style.width = '29.7cm';
            kertas.style.height = '21cm';
        } else {
            kertas.style.width = '21cm';
            kertas.style.height = '29.7cm';
        }

        // Atur Margin (Padding)
        if (margin === 'narrow') kertas.style.padding = '1.27cm';
        else if (margin === 'wide') kertas.style.padding = '3cm';
        else kertas.style.padding = '2cm'; // standard

        // Atur Skala
        if (skala === '95') kertas.style.transform = 'scale(0.95)';
        else if (skala === '90') kertas.style.transform = 'scale(0.90)';
        else kertas.style.transform = 'scale(1)';
    }

    if(halNomor) halNomor.style.display = (penomoran === 'off') ? 'none' : 'block';

    if(info) {
        info.innerHTML = `
            Ukuran: ${ukuran === 'a4-landscape' ? 'A4 Landscape' : 'A4 Portrait'}<br>
            Margin: ${margin === 'narrow' ? 'Sempit' : (margin === 'wide' ? 'Lebar' : 'Standar')}<br>
            Skala: ${skala}%
        `;
    }

    // 2. KUNCI UTAMA: Animasi Thumbnails di Layar Depan Ikut Melebar!
    const thumbnails = document.querySelectorAll('.kertas-thumbnail');
    thumbnails.forEach(thumb => {
        if (ukuran === 'a4-landscape') {
            // Rasio A4 Landscape = 1.414 / 1
            thumb.style.aspectRatio = '1.414 / 1';
        } else {
            // Rasio A4 Potrait = 1 / 1.414
            thumb.style.aspectRatio = '1 / 1.414';
        }
    });
}

function batchPrint() {
    Swal.fire({
        icon: 'info',
        title: 'Fitur Segera Hadir',
        text: 'Fitur cetak massal (Batch Print) sedang dalam tahap pengembangan akhir oleh tim IT.',
        confirmButtonColor: '#10b981'
    });
}

// ------------------------------------------------------------------
// INIT & EVENT LISTENER PENCEGAH CRASH
// ------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    // Tutup modal jika klik di luar area (Dibungkus agar tidak error null)
    const modalPreview = document.getElementById('modalPreviewKertas');
    if (modalPreview) {
        modalPreview.addEventListener('click', function(e) {
            if (e.target === this) closePreviewKertas();
        });
    }
    
    // Setel tampilan awal berdasarkan nilai select box saat web dibuka
    updatePreviewLayout();
});