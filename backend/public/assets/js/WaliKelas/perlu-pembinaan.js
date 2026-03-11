/**
 * File: public/assets/js/WaliKelas/perlu-pembinaan.js
 * Script untuk mengelola animasi Modal, Pencarian Siswa, dan Submit AJAX
 */

// ==========================================
// 1. KONFIGURASI SDK (WARNA & TEMA)
// ==========================================
const defaultConfig = {
    school_name: 'SMPIT Ad Durrah',
    teacher_name: 'Wali Kelas',
    class_name: 'VII-A',
    primary_color: '#1F7A4D',
    secondary_color: '#E6F4EC',
    text_color: '#1f2937',
    background_color: '#f9fafb',
    accent_color: '#10b981'
};

if (window.elementSdk) {
    window.elementSdk.init({
        defaultConfig,
        onConfigChange: async (config) => {
            const schoolNameSidebar = document.getElementById('schoolNameSidebar');
            if (schoolNameSidebar) schoolNameSidebar.textContent = config.school_name || defaultConfig.school_name;

            const teacherNameHeader = document.getElementById('teacherNameHeader');
            if (teacherNameHeader) teacherNameHeader.textContent = config.teacher_name || defaultConfig.teacher_name;

            const classNameHeader = document.getElementById('classNameHeader');
            if (classNameHeader) classNameHeader.textContent = 'Wali Kelas ' + (config.class_name || defaultConfig.class_name);
        },
        mapToCapabilities: (config) => ({
            recolorables: [
                { get: () => config.primary_color, set: (v) => { config.primary_color = v; window.elementSdk.setConfig({ primary_color: v }); } },
                { get: () => config.secondary_color, set: (v) => { config.secondary_color = v; window.elementSdk.setConfig({ secondary_color: v }); } },
                { get: () => config.text_color, set: (v) => { config.text_color = v; window.elementSdk.setConfig({ text_color: v }); } }
            ]
        })
    });
}

// ==========================================
// 2. LOGIKA MODAL 1 (TAMBAH CATATAN PEMBINAAN)
// ==========================================
function openNoteModal(siswaId = '') {
    const modal = document.getElementById('noteModal');
    const backdrop = document.getElementById('noteBackdrop');
    const content = document.getElementById('noteContent');
    const selectSiswa = document.getElementById('selectSiswa');
    
    if(!modal) return; // Mencegah error jika elemen tidak ada di halaman
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Kunci scroll halaman
    
    // Auto-select nama siswa jika diklik dari daftar
    if(siswaId && selectSiswa) { 
        selectSiswa.value = siswaId; 
    } else if(selectSiswa) { 
        selectSiswa.value = ''; 
    }

    // Trigger Animasi Masuk (Fade & Scale)
    setTimeout(() => {
        if(backdrop) backdrop.classList.remove('opacity-0');
        if(content) {
            content.classList.remove('opacity-0', 'scale-95');
            content.classList.add('opacity-100', 'scale-100');
        }
    }, 10);
}

function closeNoteModal() {
    const modal = document.getElementById('noteModal');
    const backdrop = document.getElementById('noteBackdrop');
    const content = document.getElementById('noteContent');
    const form = document.getElementById('formPembinaan');
    
    if(!modal) return;
    
    // Trigger Animasi Keluar
    if(backdrop) backdrop.classList.add('opacity-0');
    if(content) {
        content.classList.remove('opacity-100', 'scale-100');
        content.classList.add('opacity-0', 'scale-95');
    }

    // Tunggu animasi selesai baru di-hide
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        if(form) form.reset();
    }, 300); 
}

// ==========================================
// 3. LOGIKA MODAL 2 (DAFTAR SEMUA SISWA)
// ==========================================
function openListModal() {
    const modal = document.getElementById('allStudentsModal');
    const backdrop = document.getElementById('listBackdrop');
    const content = document.getElementById('listContent');
    const searchInput = document.getElementById('searchSiswa');
    
    if(!modal) return;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    if(searchInput) {
        searchInput.value = ''; // Reset pencarian
        filterSiswa();
    }

    setTimeout(() => {
        if(backdrop) backdrop.classList.remove('opacity-0');
        if(content) {
            content.classList.remove('opacity-0', 'scale-95');
            content.classList.add('opacity-100', 'scale-100');
        }
    }, 10);
}

function closeListModal() {
    const modal = document.getElementById('allStudentsModal');
    const backdrop = document.getElementById('listBackdrop');
    const content = document.getElementById('listContent');
    
    if(!modal) return;
    
    if(backdrop) backdrop.classList.add('opacity-0');
    if(content) {
        content.classList.remove('opacity-100', 'scale-100');
        content.classList.add('opacity-0', 'scale-95');
    }

    setTimeout(() => { 
        modal.classList.add('hidden'); 
        document.body.style.overflow = '';
    }, 300);
}

// ==========================================
// 4. INTERAKSI LAINNYA
// ==========================================

// Pindah dari Modal Daftar ke Modal Catatan
function pilihSiswaDariDaftar(siswaId) {
    closeListModal();
    // Beri jeda 300ms agar modal daftar selesai menutup sebelum modal catatan terbuka
    setTimeout(() => { 
        openNoteModal(siswaId); 
    }, 300); 
}

// Fitur Pencarian Real-Time di Modal Daftar Siswa
function filterSiswa() {
    const searchInput = document.getElementById('searchSiswa');
    if(!searchInput) return;
    
    let input = searchInput.value.toLowerCase();
    let items = document.getElementsByClassName('siswa-item');
    
    for (let i = 0; i < items.length; i++) {
        let namaEl = items[i].querySelector('.nama-siswa');
        if (namaEl) {
            let nama = namaEl.innerText.toLowerCase();
            if (nama.indexOf(input) > -1) {
                items[i].style.display = "flex";
            } else {
                items[i].style.display = "none";
            }
        }
    }
}

// ==========================================
// 5. AJAX FORM SUBMIT (SIMPAN CATATAN)
// ==========================================
async function savePembinaan(e) {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button[type="submit"]');
    const originalHtml = btn.innerHTML;
    
    // Animasi Loading Button
    btn.innerHTML = `<svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...`;
    btn.disabled = true;

    try {
        const formData = new FormData(form);
        const res = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        
        if (data.status === 'success') {
            closeNoteModal();
            // Optional: Pakai SweetAlert kalau ada
            if (typeof Swal !== 'undefined') {
                Swal.fire('Berhasil', data.message, 'success').then(() => location.reload());
            } else {
                alert('✅ ' + data.message);
                location.reload(); // Refresh untuk melihat hasil
            }
        } else {
            alert('❌ Gagal: ' + data.message);
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        }
    } catch (err) {
        console.error(err);
        alert('Terjadi kesalahan jaringan.');
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    }
}