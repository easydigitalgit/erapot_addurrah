/**
 * File: public/assets/js/WaliKelas/daftar-siswa.js
 */

// ==========================================
// 1. FILTERING TABLE REAL-TIME
// ==========================================
function filterTable() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const akademikFilter = document.getElementById('akademikFilter').value;
    const tahfidzFilter = document.getElementById('tahfidzFilter').value;
    
    const rows = document.querySelectorAll('.student-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const name = row.getAttribute('data-name').toLowerCase();
        const nis = row.getAttribute('data-nis').toLowerCase();
        const status = row.getAttribute('data-status');
        const akademik = row.getAttribute('data-akademik');
        const tahfidz = row.getAttribute('data-tahfidz');
        
        let matchesSearch = name.includes(searchInput) || nis.includes(searchInput);
        let matchesStatus = statusFilter === "" || status === statusFilter;
        let matchesAkademik = akademikFilter === "" || akademik === akademikFilter;
        let matchesTahfidz = tahfidzFilter === "" || tahfidz === tahfidzFilter;

        if (matchesSearch && matchesStatus && matchesAkademik && matchesTahfidz) {
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
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('akademikFilter').value = '';
    document.getElementById('tahfidzFilter').value = '';
    filterTable();
}

// ==========================================
// 2. ANIMASI MODAL MASTER
// ==========================================
function openGenericModal(modalId) {
    const modal = document.getElementById(modalId);
    if(!modal) return;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    const content = modal.querySelector('.modal-content') || modal.querySelector('[id$="Content"]');
    const overlay = modal.querySelector('.modal-overlay') || modal.querySelector('[id$="Backdrop"]');
    
    setTimeout(() => {
        if(overlay) overlay.classList.remove('opacity-0');
        if(content) {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }
    }, 10);
}

function closeGenericModal(modalId) {
    const modal = document.getElementById(modalId);
    if(!modal) return;
    
    const content = modal.querySelector('.modal-content') || modal.querySelector('[id$="Content"]');
    const overlay = modal.querySelector('.modal-overlay') || modal.querySelector('[id$="Backdrop"]');
    
    if(overlay) overlay.classList.add('opacity-0');
    if(content) {
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
    }
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 300);
}


// ==========================================
// 3. FUNGSI KHUSUS MODAL
// ==========================================

// --- Modal Catatan Singkat ---
function openNoteModal(id, nama) {
    document.getElementById('noteSiswaId').value = id;
    document.getElementById('noteModalTitle').textContent = LANG.note_title_prefix + " " + nama;
    openGenericModal('noteModal');
}
function closeNoteModal() { closeGenericModal('noteModal'); }
function saveNote(e) {
    e.preventDefault();
    alert(LANG.note_saved);
    closeNoteModal();
}


// --- Modal Profil Lengkap ---
function openProfileModal(siswaObj) {
    document.getElementById('profileName').textContent = siswaObj.nama_lengkap;
    document.getElementById('profileNis').textContent = siswaObj.nis || '-';
    document.getElementById('profileNisn').textContent = siswaObj.nisn || '-';
    
    // LOGIKA HYBRID AVATAR: Cek users.foto_profil -> siswa.foto_siswa -> foto_fix
    const profileInitial = document.getElementById('profileInitial');
    let finalFoto = siswaObj.foto_profil;
    if (!finalFoto || finalFoto === 'null' || finalFoto === '') finalFoto = siswaObj.foto_siswa;
    if (!finalFoto || finalFoto === 'null' || finalFoto === '') finalFoto = siswaObj.foto_fix;
    
    const fallbackText = siswaObj.nama_lengkap.substring(0, 2).toUpperCase();

    if (finalFoto && finalFoto !== 'null' && String(finalFoto).trim() !== '') {
        const cleanBaseUrl = (typeof BASE_URL !== 'undefined' ? BASE_URL : '').replace(/\/$/, '');
        const cacheBuster = '?v=' + new Date().getTime();
        
        const urlAvatars = `${cleanBaseUrl}/assets/uploads/avatars/${finalFoto}${cacheBuster}`;
        const urlSiswa = `${cleanBaseUrl}/uploads/siswa/${finalFoto}${cacheBuster}`;
        
        profileInitial.innerHTML = `<img src="${urlAvatars}" class="w-full h-full object-cover" alt="Foto" onerror="this.onerror=function(){ this.outerHTML='${fallbackText}'; }; this.src='${urlSiswa}';">`;
    } else {
        profileInitial.innerHTML = fallbackText;
    }

    document.getElementById('prof_jk').textContent = siswaObj.jenis_kelamin === 'L' ? LANG.gender_m : (siswaObj.jenis_kelamin === 'P' ? LANG.gender_f : '-');

    document.getElementById('prof_rata').textContent = siswaObj.rata_nilai || '0';
    
    const elStatus = document.getElementById('prof_status_akademik');
    if (siswaObj.rata_nilai >= 75 || siswaObj.rata_nilai == 0) {
        elStatus.textContent = LANG.prof_safe;
        elStatus.className = 'px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs rounded-lg font-bold uppercase tracking-wider border border-emerald-200 dark:border-emerald-800/50';
    } else {
        elStatus.textContent = LANG.prof_warn;
        elStatus.className = 'px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs rounded-lg font-bold uppercase tracking-wider border border-red-200 dark:border-red-800/50';
    }

    document.getElementById('prof_h').textContent = siswaObj.absen_h;
    document.getElementById('prof_s').textContent = (siswaObj.absen_s + siswaObj.absen_i);
    document.getElementById('prof_a').textContent = siswaObj.absen_a;
    document.getElementById('prof_persen_absen').textContent = siswaObj.persen_absen + '%';

    document.getElementById('prof_capaian_tahfidz').textContent = siswaObj.capaian_tahfidz;
    document.getElementById('prof_tipe_catatan').textContent = siswaObj.tipe_catatan;
    document.getElementById('prof_isi_catatan').textContent = siswaObj.isi_catatan;
    
    openGenericModal('profileModal');
}
function closeProfileModal() { closeGenericModal('profileModal'); }


// --- Modal Rapor Preview ---
function openRaporModal(siswaObj) {
    document.getElementById('raporStudentName').textContent = siswaObj.nama_lengkap;
    document.getElementById('raporStudentNIS').textContent = siswaObj.nis || siswaObj.nisn || '-';

    let htmlNilai = '';
    let totalMapel = Object.keys(siswaObj.nilai_mapel).length;
    
    if (totalMapel > 0) {
        for (const [mapel, nilai] of Object.entries(siswaObj.nilai_mapel)) {
            let badgeClass = nilai < 75 ? 'bg-red-50 text-red-700 border-red-200' : 'bg-white text-gray-800 border-gray-200 dark:bg-slate-800 dark:text-slate-200 dark:border-slate-600';
            htmlNilai += `
            <div class="${badgeClass} p-3 rounded-lg border flex justify-between items-center shadow-sm">
                <p class="text-xs font-bold uppercase tracking-wider truncate w-2/3">${mapel}</p>
                <p class="text-lg font-black">${nilai}</p>
            </div>`;
        }
    } else {
        htmlNilai = `<div class="col-span-1 sm:col-span-2 text-center text-sm text-gray-500 py-6 border border-dashed border-gray-300 dark:border-slate-600 rounded-xl">${LANG.rapor_no_grade}</div>`;
    }
    
    document.getElementById('raporNilaiContainer').innerHTML = htmlNilai;
    document.getElementById('raporCatatan').textContent = siswaObj.isi_catatan !== 'Belum ada catatan khusus dari wali kelas.' ? '"' + siswaObj.isi_catatan + '"' : LANG.rapor_good_behavior;

    openGenericModal('raporModal');
}
function closeRaporModal() { closeGenericModal('raporModal'); }