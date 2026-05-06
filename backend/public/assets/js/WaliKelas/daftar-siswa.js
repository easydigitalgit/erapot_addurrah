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


// ==========================================
// 4. FITUR EDIT BIODATA (WALI KELAS)
// ==========================================
function openAddModal() { openGenericModal('addModal'); }
function closeAddModal() { closeGenericModal('addModal'); }

/**
 * Buka Modal Edit & Ambil Data Lengkap dari Server
 */
async function openEditModal(id, btn = null) {
    // 1. Tampilkan Loading
    const targetBtn = btn || document.getElementById('btnOpenEdit');
    let originalHtml = '';
    if (targetBtn) {
        originalHtml = targetBtn.innerHTML;
        targetBtn.innerHTML = '<svg class="animate-spin h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        targetBtn.disabled = true;
    }

    try {
        const response = await fetch(`${BASE_URL}/wali/daftar-siswa/get-detail/${id}`);
        const data = await response.json();

        if (data.status === 'error') {
            alert(data.message);
            return;
        }

        // 2. Isi Form Modal
        const form = document.getElementById('addStudentForm');
        form.reset();

        // Mapping Data ke Form
        document.getElementById('edit_id').value = data.id;
        document.getElementById('nis').value = data.nis || '';
        document.getElementById('nisn').value = data.nisn || '';
        document.getElementById('nik').value = data.nik || '';
        document.getElementById('nama_lengkap').value = data.nama_lengkap || '';
        document.getElementById('jenis_kelamin').value = data.jenis_kelamin || 'L';
        document.getElementById('agama').value = data.agama || 'Islam';
        document.getElementById('tempat_lahir').value = data.tempat_lahir || '';
        document.getElementById('tanggal_lahir').value = data.tanggal_lahir || '';
        document.getElementById('no_kk').value = data.no_kk || '';
        document.getElementById('no_registrasi_akta').value = data.no_registrasi_akta || '';
        
        document.getElementById('status_dalam_keluarga').value = data.status_dalam_keluarga || '';
        document.getElementById('anak_ke').value = data.anak_ke || '';
        document.getElementById('jml_saudara_kandung').value = data.jml_saudara_kandung || '';
        document.getElementById('kebutuhan_khusus').value = data.kebutuhan_khusus || '';
        document.getElementById('berat_badan').value = data.berat_badan || '';
        document.getElementById('tinggi_badan').value = data.tinggi_badan || '';
        document.getElementById('lingkar_kepala').value = data.lingkar_kepala || '';
        document.getElementById('jarak_ke_sekolah').value = data.jarak_ke_sekolah || '';

        document.getElementById('alamat_siswa').value = data.alamat_siswa || '';
        document.getElementById('kecamatan').value = data.kecamatan || '';
        document.getElementById('kelurahan').value = data.kelurahan || '';
        document.getElementById('dusun').value = data.dusun || '';
        document.getElementById('rt').value = data.rt || '';
        document.getElementById('rw').value = data.rw || '';
        document.getElementById('kode_pos').value = data.kode_pos || '';
        document.getElementById('jenis_tinggal').value = data.jenis_tinggal || '';
        document.getElementById('alat_transportasi').value = data.alat_transportasi || '';
        document.getElementById('no_hp').value = data.no_hp || '';
        document.getElementById('email_siswa').value = data.email_siswa || '';

        document.getElementById('diterima_dikelas').value = data.diterima_dikelas || '';
        document.getElementById('tgl_diterima').value = data.tgl_diterima || '';
        document.getElementById('asal_sekolah').value = data.asal_sekolah || '';
        document.getElementById('skhun').value = data.skhun || '';
        document.getElementById('no_peserta_un').value = data.no_peserta_un || '';
        document.getElementById('no_seri_ijazah').value = data.no_seri_ijazah || '';

        document.getElementById('nama_ayah').value = data.nama_ayah || '';
        document.getElementById('nik_ayah').value = data.nik_ayah || '';
        document.getElementById('tahun_lahir_ayah').value = data.tahun_lahir_ayah || '';
        document.getElementById('pendidikan_ayah').value = data.pendidikan_ayah || '';
        document.getElementById('pekerjaan_ayah').value = data.pekerjaan_ayah || '';

        document.getElementById('nama_ibu').value = data.nama_ibu || '';
        document.getElementById('nik_ibu').value = data.nik_ibu || '';
        document.getElementById('tahun_lahir_ibu').value = data.tahun_lahir_ibu || '';
        document.getElementById('pendidikan_ibu').value = data.pendidikan_ibu || '';
        document.getElementById('pekerjaan_ibu').value = data.pekerjaan_ibu || '';

        document.getElementById('no_hp_ortu').value = data.no_hp_ortu || '';
        document.getElementById('email_ortu').value = data.email_ortu || '';
        document.getElementById('alamat_orangtua').value = data.alamat_orangtua || '';

        document.getElementById('ekskul_1').value = data.ekskul_1 || '';
        document.getElementById('ekskul_2').value = data.ekskul_2 || '';
        document.getElementById('ekskul_3').value = data.ekskul_3 || '';
        document.getElementById('status_siswa').value = data.status_siswa || 'Aktif';

        // 3. Tutup Modal Profil & Buka Modal Edit
        closeProfileModal();
        openGenericModal('addModal');
    } catch (error) {
        console.error(error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Gagal mengambil data siswa dari server.',
            confirmButtonColor: '#ef4444'
        });
    } finally {
        if (targetBtn) {
            targetBtn.innerHTML = originalHtml;
            targetBtn.disabled = false;
        }
    }
}

/**
 * Handle Submit Form Edit (Wali Kelas)
 */
async function handleSubmit(e) {
    e.preventDefault();
    
    const form = document.getElementById('addStudentForm');
    const formData = new FormData(form);
    const id = document.getElementById('edit_id').value;
    const btnSubmit = document.getElementById('btnSubmit');

    const originalText = btnSubmit.innerHTML;
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<svg class="animate-spin h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan...';

    try {
        const response = await fetch(`${BASE_URL}/wali/daftar-siswa/update/${id}`, {
            method: 'POST',
            body: formData
        });
        const result = await response.json();

        if (result.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: result.message,
                showConfirmButton: true,
                confirmButtonColor: 'var(--warna-primary)'
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: result.message || 'Gagal memperbarui data.',
                confirmButtonColor: '#ef4444'
            });
        }
    } catch (error) {
        console.error(error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan koneksi ke server.',
            confirmButtonColor: '#ef4444'
        });
    } finally {
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = originalText;
    }
}

// EVENT LISTENER: Tombol Edit di Modal Profil
document.addEventListener('DOMContentLoaded', () => {
    const btnOpenEdit = document.getElementById('btnOpenEdit');
    if (btnOpenEdit) {
        btnOpenEdit.addEventListener('click', () => {
            // Kita butuh ID siswa. Kita ambil dari profileModalTitle atau simpan di global var
            // Tapi paling aman, simpan di data attribute saat buka profile modal
            const sId = btnOpenEdit.getAttribute('data-id');
            if (sId) openEditModal(sId);
        });
    }
});

// Patch openProfileModal untuk menyimpan ID di tombol edit
const originalOpenProfileModal = openProfileModal;
openProfileModal = function(siswaObj) {
    const btnOpenEdit = document.getElementById('btnOpenEdit');
    if (btnOpenEdit) btnOpenEdit.setAttribute('data-id', siswaObj.id);
    originalOpenProfileModal(siswaObj);
};