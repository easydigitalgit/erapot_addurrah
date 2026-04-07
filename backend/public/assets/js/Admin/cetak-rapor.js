/**
 * File: public/assets/js/Admin/cetak-rapor.js
 */

let selectedStudentData = null;
let currentAction = 'preview';
let rombelIsLocked = false;

function loadSiswaOptions() {
    const rombelId = document.getElementById('filterRombel').value;
    const siswaSelect = document.getElementById('filterSiswa');
    const taId = document.getElementById('selectTA').value;
    
    selectedStudentData = null; 
    siswaSelect.innerHTML = `<option value="">${window.LANG.js_loading}</option>`;

    if (!rombelId) {
        siswaSelect.innerHTML = `<option value="">${window.LANG.js_select_room}</option>`;
        resetStatusInfo();
        return;
    }

    fetch(`${API_URL}/getSiswaByRombel?rombel_id=${rombelId}&ta=${taId}`)
        .then(res => res.json())
        .then(response => {
            if (response.status === 'success') {
                const siswa = response.data_siswa;
                const info = response.info_rombel;
                rombelIsLocked = info.is_locked;

                if (siswa.length > 0) {
                    let options = `<option value="">${window.LANG.js_select_student}</option>`;
                    siswa.forEach(s => {
                        options += `<option value="${s.id}" data-nama="${s.nama_lengkap}" data-nis="${s.nis}">${s.nama_lengkap} (${s.nis})</option>`;
                    });
                    siswaSelect.innerHTML = options;
                    siswaSelect.disabled = false;
                } else {
                    siswaSelect.innerHTML = `<option value="">${window.LANG.js_no_student}</option>`;
                }
                
                updateStatusBadge(info);
            }
        });
}

function updateStatusBadge(info) {
    const statusBadge = document.getElementById('statusLock');
    const waktuLock = document.getElementById('waktuLock');
    const waliKelasText = document.getElementById('infoWaliKelas'); // <-- Tangkap elemen wali kelas

    // 1. Update teks Wali Kelas secara dinamis
    if (waliKelasText) {
        waliKelasText.innerText = info.wali_kelas || '-';
    }

    // 2. Update Status Lock & Waktu (Tetap sama)
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
                    <div><p class="text-xs font-bold text-emerald-900 mb-1">${window.LANG.js_ready_print}</p><p class="text-xs text-emerald-800">${window.LANG.js_valid_report}</p></div>
                </div>`;
        } else {
            validMessage.innerHTML = `
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    <div><p class="text-xs font-bold text-amber-900 mb-1">${window.LANG.js_warning}</p><p class="text-xs text-amber-800">${window.LANG.js_unlocked_val}</p></div>
                </div>`;
        }
    } else {
        validMessage.classList.add('hidden');
    }
}

function checkAndOpenModal(action) {
    const rombelVal = document.getElementById('filterRombel').value;
    const siswaVal = document.getElementById('filterSiswa').value;

    if (!rombelVal) {
        Swal.fire({icon: 'warning', title: window.LANG.js_swal_no_room, text: window.LANG.js_swal_room_desc, confirmButtonColor: '#f59e0b'});
        return;
    }

    if (!siswaVal || !selectedStudentData) {
        Swal.fire({icon: 'info', title: window.LANG.js_swal_no_stu, text: window.LANG.js_swal_stu_desc, footer: window.LANG.js_swal_stu_tip, confirmButtonColor: '#3b82f6'});
        return;
    }

    const studentName = document.getElementById('filterSiswa').options[document.getElementById('filterSiswa').selectedIndex].text.split('(')[0].trim();
    openInputModal(siswaVal, studentName);
}

function openInputModal(siswaId, studentName) {
    selectedStudentData = { id: siswaId, nama: studentName };
    
    // DYNAMISE DECISION OPTIONS INSTANTLY (Robust & Immediate)
    const rombelSelect = document.getElementById('filterRombel');
    let tingkat = '7'; // Default fallback

    if (rombelSelect && rombelSelect.selectedIndex !== -1) {
        const opt = rombelSelect.options[rombelSelect.selectedIndex];
        const textTingkat = opt.text.toUpperCase();
        const attrTingkat = (opt.getAttribute('data-tingkat') || '').toString().toUpperCase();
        
        // SMART EXTRACTION: Check attribute first, then text
        const source = attrTingkat || textTingkat;
        
        if (source.includes('IX') || source.includes('9')) {
            tingkat = '9';
        } else if (source.includes('VIII') || source.includes('8')) {
            tingkat = '8';
        } else if (source.includes('VII') || source.includes('7')) {
            tingkat = '7';
        }
    }
    
    const selectKenaikan = document.getElementById('inputKenaikan');
    let options = '';
    
    if (tingkat === '9') {
        options = `
            <option value="LULUS">${(window.LANG && window.LANG.graduated) || 'LULUS'}</option>
            <option value="TIDAK LULUS">${(window.LANG && window.LANG.not_graduated) || 'TIDAK LULUS'}</option>
        `;
    } else if (tingkat === '8') {
        options = `
            <option value="NAIK KE KELAS : IX (SEMBILAN)">${(window.LANG && window.LANG.promo_9) || 'NAIK KE KELAS : IX (SEMBILAN)'}</option>
            <option value="TINGGAL DI KELAS : VIII (DELAPAN)">${(window.LANG && window.LANG.retain_8) || 'TINGGAL DI KELAS : VIII (DELAPAN)'}</option>
        `;
    } else { // Grade 7 or default fallback
        options = `
            <option value="NAIK KE KELAS : VIII (DELAPAN)">${(window.LANG && window.LANG.promo_8) || 'NAIK KE KELAS : VIII (DELAPAN)'}</option>
            <option value="TINGGAL DI KELAS : VII (TUJUH)">${(window.LANG && window.LANG.retain_7) || 'TINGGAL DI KELAS : VII (TUJUH)'}</option>
        `;
    }

    if (selectKenaikan) {
        selectKenaikan.innerHTML = options;
    }

    Swal.fire({
        title: window.LANG.js_loading,
        text: 'Mengambil riwayat catatan...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    const taId = document.getElementById('selectTA') ? document.getElementById('selectTA').value : '';
    
    fetch(`${API_URL}/getCatatanSiswa?siswa_id=${siswaId}&ta=${taId}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => {
        if (!res.ok) throw new Error('Server tidak merespon dengan baik');
        return res.json();
    })
    .then(res => {
        Swal.close();
        if(res.status === 'success') {
            const cat = res.catatan;

            document.getElementById('inputCatatanWali').value = cat && cat.catatan_wali_kelas ? cat.catatan_wali_kelas : '';
            
            if (cat && cat.status_kenaikan) {
                // Pastikan value lama masih ada di opsi baru (misal ganti kelas/tingkat)
                const exists = Array.from(selectKenaikan.options).some(opt => opt.value === cat.status_kenaikan);
                if (exists) {
                    selectKenaikan.value = cat.status_kenaikan;
                } else {
                    selectKenaikan.selectedIndex = 0;
                }
            } else {
                selectKenaikan.selectedIndex = 0;
            }

            document.getElementById('modalInputRapor').classList.remove('hidden');
        }
    })
    .catch(err => {
        Swal.close();
        console.error(err);
        document.getElementById('inputCatatanWali').value = '';
        document.getElementById('inputKenaikan').selectedIndex = 0;
        document.getElementById('modalInputRapor').classList.remove('hidden');
    });
}
function simpanDanCetak(actionType) {
    if (!selectedStudentData) return;

    const pengantar = document.getElementById('inputPengantar').value;
    const catatan = document.getElementById('inputCatatanWali').value;
    const kenaikan = document.getElementById('inputKenaikan').value;

    Swal.fire({
        title: window.LANG.js_swal_saving,
        text: window.LANG.js_swal_wait,
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading(); }
    });

    const formData = new FormData();
    formData.append('siswa_id', selectedStudentData.id);
    formData.append('kata_pengantar', pengantar);
    formData.append('catatan_wali', catatan);
    formData.append('status_kenaikan', kenaikan);

    const csrfToken = document.getElementById('csrf_token');
    if(csrfToken) {
        formData.append(csrfToken.name, csrfToken.value);
    }

    fetch(`${API_URL}/saveCatatanRapor`, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(response => {
        if (response.status === 'success') {
            closeInputModal();
            Swal.close(); 

            let jenisRapor = 'lengkap';
            const radioJenis = document.querySelector('input[name="jenisRapor"]:checked');
            if (radioJenis) {
                jenisRapor = radioJenis.value; 
            }

            const optCover = document.getElementById('checkCover').checked ? '1' : '0';
            const optTtd   = document.getElementById('checkTTD') && document.getElementById('checkTTD').checked ? '1' : '0';
            const optQr    = document.getElementById('checkQR') && document.getElementById('checkQR').checked ? '1' : '0';
            
            const kategori = document.getElementById('filterKategori') ? encodeURIComponent(document.getElementById('filterKategori').value) : 'Akhir%20Semester';

            const taId = document.getElementById('selectTA') ? document.getElementById('selectTA').value : '';
            const tglRapor = document.getElementById('tglRapor') ? encodeURIComponent(document.getElementById('tglRapor').value) : '';
            const tempat = document.getElementById('tempatRapor') ? encodeURIComponent(document.getElementById('tempatRapor').value) : '';

            let url = `${API_URL}/printPDF/${selectedStudentData.id}/${actionType}`;
            
            url += `?jenis_rapor=${jenisRapor}&cover=${optCover}&ttd=${optTtd}&qr=${optQr}&ta=${taId}&tgl_rapor=${tglRapor}&tempat=${tempat}&kategori=${kategori}`;
            
            if (actionType === 'preview') {
                openIframePreview(url, selectedStudentData.nama);
            } else {
                Swal.fire({icon: 'info', title: window.LANG.js_preparing_download, showConfirmButton: false, timer: 1500});
                window.location.href = url;
            }
        } else {
            Swal.fire(window.LANG.js_swal_fail, response.message || window.LANG.js_swal_fail_desc, 'error');
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire(window.LANG.js_swal_err, window.LANG.js_swal_err_desc, 'error');
    });
}

function openIframePreview(url, studentName) {
    const modal = document.getElementById('modalPreviewKertas');
    const iframeContainer = document.getElementById('iframeContainer');
    const loader = document.getElementById('iframeLoader');
    
    document.getElementById('previewSiswaName').textContent = `${window.LANG.js_modal_showing_pdf} ${studentName}`;

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    loader.classList.remove('hidden');

    iframeContainer.innerHTML = `<iframe id="raporIframe" src="${url}" class="w-full h-full border-none" onload="hideIframeLoader()"></iframe>`;

    setTimeout(() => {
        modal.querySelector('.modal-overlay').classList.remove('opacity-0');
        document.getElementById('modalPreviewContent').classList.remove('scale-95');
    }, 10);
}

window.hideIframeLoader = function() {
    const loader = document.getElementById('iframeLoader');
    if(loader) loader.classList.add('hidden');
}

function closePreviewKertas() {
    const modal = document.getElementById('modalPreviewKertas');
    const iframeContainer = document.getElementById('iframeContainer');
    
    modal.querySelector('.modal-overlay').classList.add('opacity-0');
    document.getElementById('modalPreviewContent').classList.add('scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        iframeContainer.innerHTML = ''; 
    }, 300);
}

function closeInputModal() {
    document.getElementById('modalInputRapor').classList.add('hidden');
}

function resetStatusInfo() {
    document.getElementById('statusLock').innerHTML = '-';
    document.getElementById('waktuLock').textContent = '-';
}

function showPreview(pageNumber) {
    Swal.fire({icon: 'info', title: 'Simulasi Halaman', text: `Anda mengklik simulasi halaman ${pageNumber}`, timer: 1500, showConfirmButton: false});
}

async function batchPrint() {
    // 1. Ambil daftar semua siswa dari dropdown (kecuali opsi "-- Pilih Siswa --")
    const selectSiswa = document.getElementById('filterSiswa');
    const optionsSiswa = Array.from(selectSiswa.options).filter(opt => opt.value !== "");

    // Jika belum memilih kelas atau kelas kosong
    if (optionsSiswa.length === 0) {
        Swal.fire({
            icon: 'warning', 
            title: 'Kelas Kosong!', 
            text: 'Silakan pilih kelas terlebih dahulu atau pastikan ada siswa di kelas tersebut.', 
            confirmButtonColor: '#f59e0b'
        });
        return;
    }

    // 2. Minta Konfirmasi ke Guru/Admin
    const konfirmasi = await Swal.fire({
        title: 'Mulai Cetak Massal?',
        html: `Sistem akan mengunduh (download) rapor <b>${optionsSiswa.length} siswa</b> secara otomatis satu per satu.<br><br><span class="text-sm text-red-500 font-bold">PENTING: Jangan tutup halaman web ini selama proses berlangsung!</span>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, Mulai Download!',
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-3xl' }
    });

    if (!konfirmasi.isConfirmed) return;

    // 3. Siapkan parameter format rapor yang dipilih oleh User
    let jenisRapor = 'lengkap';
    const radioJenis = document.querySelector('input[name="jenisRapor"]:checked');
    if (radioJenis) jenisRapor = radioJenis.value;

    const optCover = document.getElementById('checkCover') && document.getElementById('checkCover').checked ? '1' : '0';
    // Karena kita tidak memakai checkTTD, kita abaikan optTtd, tapi tetap dikirim 1 agar format ttd tampil.
    const optTtd   = '1'; 
    const optQr    = document.getElementById('checkQR') && document.getElementById('checkQR').checked ? '1' : '0';
    const kategori = document.getElementById('filterKategori') ? encodeURIComponent(document.getElementById('filterKategori').value) : 'Akhir%20Semester';
    const taId = document.getElementById('selectTA') ? document.getElementById('selectTA').value : '';
    const tglRapor = document.getElementById('tglRapor') ? encodeURIComponent(document.getElementById('tglRapor').value) : '';
    const tempat = document.getElementById('tempatRapor') ? encodeURIComponent(document.getElementById('tempatRapor').value) : '';

    // 4. ROBOT DOWNLOADER LEVEL PRO: Looping satu per satu siswa
    let successCount = 0;
    
    for (let i = 0; i < optionsSiswa.length; i++) {
        const siswaId = optionsSiswa[i].value;
        const namaSiswa = optionsSiswa[i].getAttribute('data-nama');

        // Tampilkan loading screen dinamis (gunakan Swal.update agar popup tidak berkedip)
        if (i === 0) {
            Swal.fire({
                title: 'Mendownload...',
                html: `Memproses Rapor:<br><b class="text-indigo-600 text-lg">${namaSiswa}</b><br><br>Siswa ke <b>${i + 1}</b> dari <b>${optionsSiswa.length}</b><br><br><div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700"><div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500" style="width: ${((i+1)/optionsSiswa.length)*100}%"></div></div>`,
                allowOutsideClick: false,
                showConfirmButton: false,
                customClass: { popup: 'rounded-3xl' },
                didOpen: () => { Swal.showLoading(); }
            });
        } else {
            Swal.update({
                html: `Memproses Rapor:<br><b class="text-indigo-600 text-lg">${namaSiswa}</b><br><br>Siswa ke <b>${i + 1}</b> dari <b>${optionsSiswa.length}</b><br><br><div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700"><div class="bg-indigo-600 h-2.5 rounded-full transition-all duration-500" style="width: ${((i+1)/optionsSiswa.length)*100}%"></div></div>`
            });
        }

        // Rakit URL Download
        const url = `${API_URL}/printPDF/${siswaId}/download?jenis_rapor=${jenisRapor}&cover=${optCover}&ttd=${optTtd}&qr=${optQr}&ta=${taId}&tgl_rapor=${tglRapor}&tempat=${tempat}&kategori=${kategori}`;

        try {
            // MESIN PRO: Fetch data dan TUNGGU sampai server selesai merakit PDF
            const response = await fetch(url);
            
            // Ubah response menjadi format file (Blob)
            const blob = await response.blob();
            
            // Buat link download bayangan di browser
            const downloadUrl = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = downloadUrl;
            
            // Buat nama file sesuai standar: rapor_NIS_Nama_STS/SAS_Lengkap.pdf
            const nisSiswa = optionsSiswa[i].getAttribute('data-nis') || '000';
            const namaFileBersih = namaSiswa.replace(/[^a-zA-Z0-9]/g, '_');
            const kategoriValue = document.getElementById('filterKategori')?.value || '';
            const katShort = kategoriValue.includes('Tengah') ? 'STS' : 'SAS';
            
            if (jenisRapor === 'tahfidz') {
                const juzId = new URL(url).searchParams.get('juz') || '30';
                a.download = `Rapor_Tahfidz_Juz${juzId}_${nisSiswa}_${namaFileBersih}.pdf`;
            } else {
                a.download = `rapor_${nisSiswa}_${namaFileBersih}_${katShort}_Lengkap.pdf`;
            }
            
            // Eksekusi download!
            document.body.appendChild(a);
            a.click();
            
            // Bersihkan memori RAM dari link bayangan
            window.URL.revokeObjectURL(downloadUrl);
            a.remove();
            
            // Beri nafas ke server 1 detik saja, karena fetch tadi sudah memastikan proses PDF selesai
            await new Promise(resolve => setTimeout(resolve, 1000));
            
            successCount++;
        } catch (error) {
            console.error(`Gagal mendownload rapor ${namaSiswa}`, error);
            // Tetap lanjut ke siswa berikutnya meskipun ada 1 yang gagal
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
}