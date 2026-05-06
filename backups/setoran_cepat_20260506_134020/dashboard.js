/**
 * File: public/assets/js/Tahfidz/dashboard.js
 */

setInterval(() => {
    const now = new Date();
    const el = document.getElementById('realtimeClock');
    if(el) {
        el.innerText = now.toLocaleTimeString('id-ID', { 
            timeZone: 'Asia/Jakarta', 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit' 
        });
    }
}, 1000);

const hour = new Date().getHours();
let greeting = "Selamat Malam";
if (hour >= 4 && hour < 11) greeting = "Selamat Pagi";
else if (hour >= 11 && hour < 15) greeting = "Selamat Siang";
else if (hour >= 15 && hour < 18) greeting = "Selamat Sore";

const elGreeting = document.getElementById('greetingTime');
if(elGreeting) elGreeting.innerText = greeting;

document.addEventListener("DOMContentLoaded", () => {
    setTimeout(() => {
        document.querySelectorAll('.progress-fill').forEach(bar => {
            bar.style.width = bar.getAttribute('data-width');
        });
    }, 200);
});

function exportRekap(btn) {
    const textAsli = btn.innerHTML;
    btn.innerHTML = `<svg class="animate-spin h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span class="hidden md:inline ml-1">Mempersiapkan...</span>`;
    btn.disabled = true;

    setTimeout(() => {
        btn.innerHTML = textAsli;
        btn.disabled = false;
        window.location.href = BASE_URL + '/tahfidz/dashboard/exportRekap';
    }, 1500); 
}

function openSetoranModal() {
    const modal = document.getElementById('modalSetoran');
    const backdrop = document.getElementById('backdropSetoran');
    const card = document.getElementById('cardSetoran');
    if(!modal) return;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        if(backdrop) backdrop.classList.replace('opacity-0', 'opacity-100');
        if(card) {
            card.classList.replace('scale-95', 'scale-100');
            card.classList.replace('opacity-0', 'opacity-100');
        }
    }, 10);
}

function closeSetoranModal() {
    const modal = document.getElementById('modalSetoran');
    const backdrop = document.getElementById('backdropSetoran');
    const card = document.getElementById('cardSetoran');
    if(!modal) return;
    if(backdrop) backdrop.classList.replace('opacity-100', 'opacity-0');
    if(card) {
        card.classList.replace('scale-100', 'scale-95');
        card.classList.replace('opacity-100', 'opacity-0');
    }
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}

function refreshSurahCepat() {
    const juzElem = document.getElementById('inputJuzCepat');
    const surahElem = document.getElementById('inputSurahCepat');
    const selectSantri = document.getElementById('selectSantriCepat');
    const jenisSetoran = document.getElementById('inputJenisCepat').value;
    
    surahElem.innerHTML = '<option value="">-- Pilih Surah / Target --</option>';

    if (!juzElem.value || juzElem.value === "") {
        surahElem.disabled = true;
        return;
    }
    surahElem.disabled = false;

    const juzId = juzElem.value;
    const siswaId = selectSantri.value;
    const surahList = JUZ_DATA_DB[juzId] || [];
    const riwayatSiswaIni = (siswaId && typeof RIWAYAT_BLOK !== 'undefined' && RIWAYAT_BLOK[siswaId]) ? RIWAYAT_BLOK[siswaId] : [];

    let lastBlock = null;
    if(siswaId) {
        const opt = selectSantri.options[selectSantri.selectedIndex];
        lastBlock = opt.getAttribute('data-block');
    }

    surahList.forEach(s => {
        const val = s.surah_id + '|' + s.ayat;
        if (jenisSetoran === 'Ziyadah' && riwayatSiswaIni.includes(val) && val !== lastBlock) {
            return;
        }
        const newOpt = document.createElement('option');
        newOpt.value = val;
        newOpt.text = s.display;
        surahElem.appendChild(newOpt);
    });
}

function handlePilihSantri(selectElem) {
    const juzElem = document.getElementById('inputJuzCepat');
    const surahElem = document.getElementById('inputSurahCepat');
    
    if (!selectElem.value) {
        juzElem.value = "";
        surahElem.innerHTML = '<option value="">-- Pilih Juz Dulu --</option>';
        surahElem.disabled = true;
        return;
    }

    const opt = selectElem.options[selectElem.selectedIndex];
    const lastJuzId = opt.getAttribute('data-juz-id');
    const lastBlock = opt.getAttribute('data-block');
    
    if (lastJuzId && lastJuzId !== '') {
        juzElem.value = lastJuzId;
        refreshSurahCepat();
        
        if (lastBlock && lastBlock !== '') {
            let exists = false;
            for (let i = 0; i < surahElem.options.length; i++) {
                if (surahElem.options[i].value === lastBlock) {
                    surahElem.selectedIndex = i;
                    exists = true;
                    break;
                }
            }
            if(!exists) {
                const newOpt = document.createElement('option');
                newOpt.value = lastBlock;
                newOpt.text = "Target Terakhir (Beda Juz)";
                newOpt.selected = true;
                surahElem.appendChild(newOpt);
            }
        }
        
        juzElem.classList.add('input-success');
        surahElem.classList.add('input-success');
        setTimeout(() => {
            juzElem.classList.remove('input-success');
            surahElem.classList.remove('input-success');
        }, 800);
    } else {
        juzElem.value = "";
        refreshSurahCepat();
    }
}

function hitungTaqdir(nilaiAvg) {
    const dispTaqdir = document.getElementById('displayTaqdir');
    const hidPredikat = document.getElementById('inputPredikatCepat');
    const hidNilai = document.getElementById('inputNilaiCepat');
    
    let nilai = parseInt(nilaiAvg);
    
    if (nilai > 100) { nilai = 100; }
    if (nilai < 0) { nilai = 0; }
    
    if (isNaN(nilai) || nilaiAvg === '') {
        dispTaqdir.innerHTML = '-';
        hidPredikat.value = '';
        hidNilai.value = '';
        return;
    }

    let derajat = '';
    let huruf = '';
    let taqdir = 'Lulus';
    let color = '';

    if (nilai >= 90) {
        derajat = 'Sangat Lancar'; huruf = 'A'; color = 'text-emerald-500';
    } else if (nilai >= 80) {
        derajat = 'Lancar'; huruf = 'B'; color = 'text-blue-500';
    } else if (nilai >= 70) {
        derajat = 'Kurang Lancar'; huruf = 'C'; color = 'text-amber-500';
    } else if (nilai >= 60) {
        derajat = 'Kurang Lancar'; huruf = 'D'; color = 'text-orange-500';
    } else {
        derajat = 'Belum Hafal'; huruf = 'E'; taqdir = "Mengulang"; color = 'text-rose-500';
    }

    hidPredikat.value = derajat;
    hidNilai.value = nilai;
    dispTaqdir.innerHTML = `<span class='${color} text-sm md:text-base'>${huruf} | ${derajat} <br> <span class="text-xs">(${nilai})</span></span><br><span class='text-[10px] ${taqdir === 'Lulus' ? 'text-emerald-500' : 'text-rose-500'} mt-0.5 inline-block'>[ ${taqdir} ]</span>`;
}

function calcAvgCepat(inputElem) {
    if (inputElem.value > 100) inputElem.value = 100;
    if (inputElem.value < 0) inputElem.value = 0;

    let hfl = parseInt(document.getElementById('inputValHFL').value);
    let hrf = parseInt(document.getElementById('inputValHRF').value);
    let m = parseInt(document.getElementById('inputValM').value);
    let t = parseInt(document.getElementById('inputValT').value);
    
    if (isNaN(hfl) && isNaN(hrf) && isNaN(m) && isNaN(t)) {
        hitungTaqdir(NaN);
        return;
    }
    
    let count = 0; let sum = 0;
    if (!isNaN(hfl)) { sum += hfl; count++; }
    if (!isNaN(hrf)) { sum += hrf; count++; }
    if (!isNaN(m)) { sum += m; count++; }
    if (!isNaN(t)) { sum += t; count++; }
    
    let avg = count > 0 ? Math.round(sum / count) : NaN;
    hitungTaqdir(avg);
}

function submitSetoranCepat(e) {
    e.preventDefault(); 
    
    const btn = document.getElementById('btnSimpanSetoran');
    const textAsli = btn.innerHTML;
    btn.innerHTML = `<svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...`;
    btn.disabled = true;

    const fd = new FormData();
    fd.append('tanggal', new Date().toISOString().split('T')[0]); 
    fd.append('siswa_id[]', document.getElementById('selectSantriCepat').value);
    
    // MENGIRIM MURNI ID KE BACKEND
    fd.append('juz_id[]', document.getElementById('inputJuzCepat').value);
    fd.append('surah_id[]', document.getElementById('inputSurahCepat').value);
    
    fd.append('jenis_setoran[]', document.getElementById('inputJenisCepat').value);
    fd.append('nilai[]', document.getElementById('inputNilaiCepat').value);
    fd.append('nilai_hfl[]', document.getElementById('inputValHFL').value);
    fd.append('nilai_hrf[]', document.getElementById('inputValHRF').value);
    fd.append('nilai_m[]', document.getElementById('inputValM').value);
    fd.append('nilai_t[]', document.getElementById('inputValT').value);
    fd.append('predikat[]', document.getElementById('inputPredikatCepat').value);
    fd.append('catatan[]', ''); 
    
    fetch(BASE_URL + '/tahfidz/setoran/save', {
        method: 'POST',
        body: fd,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        btn.innerHTML = textAsli;
        btn.disabled = false;

        if (data.status === 'success') {
            closeSetoranModal();
            setTimeout(() => { openSuccessModal(); }, 300);
            document.getElementById('formSetoranCepat').reset();
            hitungTaqdir(NaN); 
        } else {
            if (typeof Swal !== 'undefined') Swal.fire('Gagal', data.message || 'Gagal menyimpan.', 'warning');
        }
    })
    .catch(err => {
        btn.innerHTML = textAsli;
        btn.disabled = false;
        if (typeof Swal !== 'undefined') Swal.fire('Error', 'Terjadi kesalahan sistem saat menyimpan ke database.', 'error');
    });
}