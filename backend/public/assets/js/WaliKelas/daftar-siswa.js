/**
 * File: public/assets/js/WaliKelas/daftar-siswa.js
 */

// Filter tabel secara real-time
function filterTable() {
    const input = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('.student-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const name = row.getAttribute('data-name').toLowerCase();
        const nis = row.getAttribute('data-nis').toLowerCase();
        
        if (name.includes(input) || nis.includes(input)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const countEl = document.getElementById('visibleCount');
    if(countEl) countEl.innerText = visibleCount;
}

function resetFilters() {
    const input = document.getElementById('searchInput');
    if(input) {
        input.value = '';
        filterTable();
    }
}

// ================= MODAL CATATAN =================
function openNoteModal(id, nama) {
    const modal = document.getElementById('noteModal');
    
    // Asumsi di modal catatan ada input ID dan elemen Title
    const inputSiswaId = document.getElementById('noteSiswaId');
    const titleModal = document.getElementById('noteModalTitle');
    
    if(inputSiswaId) inputSiswaId.value = id;
    if(titleModal) titleModal.textContent = "Catatan Wali Kelas - " + nama;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeNoteModal() {
    document.getElementById('noteModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function saveNote(e) {
    e.preventDefault();
    alert("Fitur simpan catatan segera hadir!");
    closeNoteModal();
}

// ================= MODAL PROFIL =================
function openProfileModal(siswaObj) {
    document.getElementById('profileName').textContent = siswaObj.nama_lengkap;
    document.getElementById('profileNis').textContent = siswaObj.nis || '-';
    document.getElementById('profileNisn').textContent = siswaObj.nisn || '-';
    document.getElementById('profileInitial').textContent = siswaObj.nama_lengkap.substring(0,2).toUpperCase();

    document.getElementById('prof_jk').textContent = siswaObj.jenis_kelamin === 'L' ? 'Laki-laki' : (siswaObj.jenis_kelamin === 'P' ? 'Perempuan' : '-');
    document.getElementById('prof_tempat').textContent = siswaObj.tempat_lahir || '-';
    document.getElementById('prof_tgl').textContent = siswaObj.tanggal_lahir || '-';

    document.getElementById('prof_rata').textContent = siswaObj.rata_nilai || '0';
    
    const elStatus = document.getElementById('prof_status_akademik');
    if (siswaObj.rata_nilai >= 75 || siswaObj.rata_nilai == 0) {
        elStatus.textContent = 'Aman';
        elStatus.className = 'px-2 py-1 bg-emerald-100 text-emerald-700 text-xs rounded-full font-medium';
    } else {
        elStatus.textContent = 'Perlu Perhatian';
        elStatus.className = 'px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full font-medium';
    }

    document.getElementById('prof_h').textContent = siswaObj.absen_h;
    document.getElementById('prof_s').textContent = siswaObj.absen_s;
    document.getElementById('prof_i').textContent = siswaObj.absen_i;
    document.getElementById('prof_persen_absen').textContent = siswaObj.persen_absen + '%';

    document.getElementById('prof_capaian_tahfidz').textContent = siswaObj.capaian_tahfidz;
    document.getElementById('prof_tipe_catatan').textContent = siswaObj.tipe_catatan;
    document.getElementById('prof_isi_catatan').textContent = siswaObj.isi_catatan;
    
    document.getElementById('profileModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeProfileModal() {
    document.getElementById('profileModal').classList.add('hidden'); 
    document.body.style.overflow = '';
}

// ================= MODAL RAPOR =================
function openRaporModal(siswaObj) {
    document.getElementById('raporStudentName').textContent = siswaObj.nama_lengkap;
    document.getElementById('raporStudentNIS').textContent = siswaObj.nis || '-';

    let htmlNilai = '';
    let totalMapel = Object.keys(siswaObj.nilai_mapel).length;
    
    if (totalMapel > 0) {
        for (const [mapel, nilai] of Object.entries(siswaObj.nilai_mapel)) {
            let color = nilai < 75 ? 'text-red-600' : 'text-emerald-600';
            htmlNilai += `
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-1">${mapel}</p>
                <p class="text-2xl font-bold ${color}">${nilai}</p>
            </div>`;
        }
    } else {
        htmlNilai = `<div class="col-span-3 text-center text-sm text-gray-500 py-6 border border-dashed border-gray-300 rounded-xl">Belum ada nilai diinput oleh guru mata pelajaran.</div>`;
    }
    
    document.getElementById('raporNilaiContainer').innerHTML = htmlNilai;
    document.getElementById('raporRataRata').textContent = siswaObj.rata_nilai || '0';

    document.getElementById('raporHadir').textContent = siswaObj.absen_h;
    document.getElementById('raporSakit').textContent = siswaObj.absen_s;
    document.getElementById('raporIzin').textContent = siswaObj.absen_i;
    document.getElementById('raporAlfa').textContent = siswaObj.absen_a;
    
    document.getElementById('raporTahfidz').textContent = siswaObj.capaian_tahfidz;
    document.getElementById('raporCatatan').textContent = siswaObj.isi_catatan;

    document.getElementById('raporModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRaporModal() {
    document.getElementById('raporModal').classList.add('hidden'); 
    document.body.style.overflow = '';
}