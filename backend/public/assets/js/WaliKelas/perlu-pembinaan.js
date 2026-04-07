// ==========================================
// 1. KONFIGURASI SDK (WARNA & TEMA DINAMIS)
// ==========================================
const configData = window.sekolahConfig || {};

const defaultConfig = {
    school_name: configData.school_name || 'SMPIT Ad Durrah',
    teacher_name: configData.teacher_name || 'Wali Kelas',
    class_name: configData.class_name || 'Belum Ada Kelas',
    primary_color: configData.primary_color || '#1F7A4D',
    secondary_color: configData.secondary_color || '#E6F4EC',
    text_color: '#1f2937',
    background_color: '#f9fafb',
    accent_color: configData.primary_color || '#10b981'
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
    
    if(!modal) return; 
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden'; 
    
    if(siswaId && selectSiswa) { 
        selectSiswa.value = siswaId; 
    } else if(selectSiswa) { 
        selectSiswa.value = ''; 
    }

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
    
    if(backdrop) backdrop.classList.add('opacity-0');
    if(content) {
        content.classList.remove('opacity-100', 'scale-100');
        content.classList.add('opacity-0', 'scale-95');
    }

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
        searchInput.value = ''; 
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
function pilihSiswaDariDaftar(siswaId) {
    closeListModal();
    setTimeout(() => { 
        openNoteModal(siswaId); 
    }, 300); 
}

function filterSiswa() {
    const searchInput = document.getElementById('searchSiswa');
    const noResultDiv = document.getElementById('noResult');
    if (!searchInput) return;
    
    let input = searchInput.value.toLowerCase();
    let items = document.getElementsByClassName('siswa-item');
    let visibleCount = 0;
    
    for (let i = 0; i < items.length; i++) {
        let namaEl = items[i].querySelector('.nama-siswa');
        let nisEl = items[i].querySelector('.nis-siswa');
        
        let nama = namaEl ? namaEl.innerText.toLowerCase() : "";
        let nis = nisEl ? nisEl.innerText.toLowerCase() : "";
        
        if (nama.includes(input) || nis.includes(input)) {
            items[i].style.display = "flex";
            visibleCount++;
        } else {
            items[i].style.display = "none";
        }
    }
    
    if (noResultDiv) {
        if (visibleCount === 0) {
            noResultDiv.classList.remove('hidden');
            noResultDiv.classList.add('flex');
        } else {
            noResultDiv.classList.add('hidden');
            noResultDiv.classList.remove('flex');
        }
    }
}

// ==========================================
// 5. POPUP ALERT KEREN (TAILWIND TOAST)
// ==========================================
function showToast(message, isSuccess = true) {
    const toast = document.createElement('div');
    
    const bgColor = isSuccess ? 'bg-gradient-to-r from-emerald-500 to-teal-600' : 'bg-gradient-to-r from-red-500 to-rose-600';
    const icon = isSuccess 
        ? `<svg class="w-6 h-6 text-white drop-shadow-md flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`
        : `<svg class="w-6 h-6 text-white drop-shadow-md flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;

    toast.className = `fixed top-8 right-8 flex items-center gap-3 px-6 py-4 rounded-2xl shadow-2xl text-white transform transition-all duration-500 translate-x-[120%] opacity-0 z-[999] ${bgColor}`;
    toast.innerHTML = `${icon} <p class="font-bold tracking-wide text-sm drop-shadow-sm">${message}</p>`;
    
    document.body.appendChild(toast);

    setTimeout(() => toast.classList.remove('translate-x-[120%]', 'opacity-0'), 50);

    setTimeout(() => {
        toast.classList.add('translate-x-[120%]', 'opacity-0');
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}

// ==========================================
// 6. AJAX FORM SUBMIT (SIMPAN CATATAN)
// ==========================================
async function savePembinaan(e) {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button[type="submit"]');
    const originalHtml = btn.innerHTML;
    
    btn.innerHTML = `<svg class="w-5 h-5 animate-spin text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> ${LANG.js_processing}`;
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
            showToast(LANG.js_succ_saved, true); 
            
            setTimeout(() => {
                location.reload(); 
            }, 1500);
            
        } else {
            showToast(LANG.js_err_failed + ' ' + data.message, false); 
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        }
    } catch (err) {
        console.error(err);
        showToast(LANG.js_err_network, false); 
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    }
}