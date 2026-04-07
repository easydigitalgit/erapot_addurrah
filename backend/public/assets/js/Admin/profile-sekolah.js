function openMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    if (sidebar && overlay) {
        sidebar.classList.add('mobile-open');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    if (sidebar && overlay) {
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const menus = document.querySelectorAll('.sidebar-menu button');
    if (menus.length > 6 && typeof toggleMenu === 'function') {
        toggleMenu(menus[6]);
    }
    if(savedProv) {
        loadKabupaten(savedProv, savedKab);
    }
});

// === LOGIKA WILAYAH (AJAX) ===
function loadKabupaten(kodeProv, selectedKab = null) {
    const kabSelect = document.getElementById('kabupaten');
    const kecSelect = document.getElementById('kecamatan');
    const desaSelect = document.getElementById('desa');

    resetDropdown(kabSelect, LANG.js_loading || 'Memuat...');
    resetDropdown(kecSelect, LANG.select_regency_first || '-- Pilih Kabupaten Dulu --');
    if(desaSelect) resetDropdown(desaSelect, LANG.select_district_first || '-- Pilih Kecamatan Dulu --');

    if (!kodeProv) {
        resetDropdown(kabSelect, LANG.select_province_first || '-- Pilih Provinsi Dulu --');
        return;
    }

    fetch(`${URL_GET_KAB}?kode_propinsi=${kodeProv}`, { headers: { "X-Requested-With": "XMLHttpRequest" } })
    .then(response => {
        if(!response.ok) throw new Error(LANG.js_fail_load_region);
        return response.json();
    })
    .then(data => {
        // Gunakan LANG.select_regency dengan fallback Anti-Undefined
        populateDropdown(kabSelect, data, selectedKab, LANG.select_regency || '-- Pilih Kabupaten --', 'kode');
        kabSelect.disabled = false;
        if(selectedKab) {
            loadKecamatan(selectedKab, savedKec);
        }
    })
    .catch(err => handleError(err, kabSelect));
}

function loadKecamatan(kodeKab, selectedKec = null) {
    const kecSelect = document.getElementById('kecamatan');
    const desaSelect = document.getElementById('desa');

    resetDropdown(kecSelect, LANG.js_loading || 'Memuat...');
    if(desaSelect) resetDropdown(desaSelect, LANG.select_district_first || '-- Pilih Kecamatan Dulu --');

    if (!kodeKab) return;

    fetch(`${URL_GET_KEC}?kode_kabupaten=${kodeKab}`, { headers: { "X-Requested-With": "XMLHttpRequest" } })
    .then(response => {
        if(!response.ok) throw new Error(LANG.js_fail_load_region);
        return response.json();
    })
    .then(data => {
        // Gunakan LANG.select_district dengan fallback Anti-Undefined
        populateDropdown(kecSelect, data, selectedKec, LANG.select_district || '-- Pilih Kecamatan --', 'kode');
        kecSelect.disabled = false;
        if(selectedKec) {
            loadDesa(selectedKec, savedDesa);
        }
    })
    .catch(err => handleError(err, kecSelect));
}

function loadDesa(kodeKec, selectedDesa = null) {
    const desaSelect = document.getElementById('desa');
    if(!desaSelect) return;

    resetDropdown(desaSelect, LANG.js_loading || 'Memuat...');

    if (!kodeKec) return;

    fetch(`${URL_GET_DESA}?kode_kecamatan=${kodeKec}`, { headers: { "X-Requested-With": "XMLHttpRequest" } })
    .then(response => {
        if(!response.ok) throw new Error(LANG.js_fail_load_region);
        return response.json();
    })
    .then(data => {
        // Gunakan LANG.select_village dengan fallback Anti-Undefined
        populateDropdown(desaSelect, data, selectedDesa, LANG.select_village || '-- Pilih Kelurahan/Desa --', 'id'); 
        desaSelect.disabled = false;
    })
    .catch(err => handleError(err, desaSelect));
}

// === HELPER FUNCTIONS ===
function resetDropdown(element, defaultText) {
    if(element) {
        element.innerHTML = `<option value="">${defaultText}</option>`;
        element.disabled = true;
    }
}

function populateDropdown(element, data, selectedValue, placeholder, valueKey = 'kode') {
    let html = `<option value="">${placeholder}</option>`;
    if (data.length === 0) {
        html = `<option value="">${LANG.js_no_data}</option>`;
    } else {
        data.forEach(item => {
            const isSelected = (selectedValue == item[valueKey]) ? 'selected' : '';
            html += `<option value="${item[valueKey]}" ${isSelected}>${item.nama}</option>`;
        });
    }
    element.innerHTML = html;
}

function handleError(err, element) {
    console.error("AJAX Error:", err);
    if(element) {
        element.innerHTML = `<option value="">${LANG.js_fail_load_region}</option>`;
        element.disabled = true;
    }
    showToast(LANG.js_fail_load_region, 'error');
}

// === UI & FORM HANDLERS ===
function previewImage(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('logoPreview').src = e.target.result;
            const uploadArea = document.getElementById('logoUploadArea');
            if(uploadArea) uploadArea.classList.add('has-file');
        }
        reader.readAsDataURL(input.files[0]);
        showToast(LANG.js_logo_uploaded, 'success');
    }
}

function syncColor(type, value, isFromText = false) {
    const picker = document.getElementById('picker_' + type);
    const text = document.getElementById('text_' + type);
    const display = document.getElementById('display_' + type);

    if (isFromText) {
        if (value.length === 7 && /^#[0-9A-Fa-f]{6}$/.test(value)) {
            picker.value = value;
            if(display) display.style.backgroundColor = value;
        }
    } else {
        text.value = value.replace('#', '').toUpperCase();
        if(display) display.style.backgroundColor = value;
    }
}

function resetForm() {
    if(confirm(LANG.js_confirm_reset)) {
        document.getElementById('profileForm').reset();
        showToast(LANG.js_form_reset, 'info');
        setTimeout(() => location.reload(), 1000); 
    }
}

function handleSubmit(event) {
    event.preventDefault(); 
    showToast(LANG.js_saving, 'info');

    const form = document.getElementById('profileForm');
    const formData = new FormData(form);

    fetch(API_URL_UPDATE, {
        method: 'POST',
        body: formData,
        headers: { "X-Requested-With": "XMLHttpRequest" }
    })
    .then(response => {
        if (!response.ok) throw new Error(LANG.js_fail_server);
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            showToast(data.message, 'success');
            
            setTimeout(() => {
                window.location.reload();
            }, 1500);
            
            const namaInput = document.querySelector('input[name="nama_sekolah"]').value;
            const headerTitle = document.querySelector('header h2');
            if(headerTitle) headerTitle.innerText = namaInput;
        } else {
            let msg = data.message;
            if (data.errors) msg += ': ' + Object.values(data.errors)[0];
            showToast(msg, 'error');
        }
    })
    .catch(error => {
        console.error(error);
        showToast(LANG.js_system_error, 'error');
    });
}

function showToast(message, type = 'success') {
    const existingToast = document.querySelector('.custom-toast');
    if (existingToast) existingToast.remove();

    const toast = document.createElement('div');
    toast.className = 'custom-toast fixed top-4 right-4 z-[99999] px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 transition-all duration-300 transform translate-y-[-20px] opacity-0';
    
    let bgClass, iconSvg;
    if (type === 'success') {
        bgClass = 'bg-white border-l-4 border-emerald-500';
        iconSvg = '<svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
    } else if (type === 'error') {
        bgClass = 'bg-white border-l-4 border-red-500';
        iconSvg = '<svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
    } else {
        bgClass = 'bg-blue-600 text-white';
        iconSvg = '<svg class="animate-spin w-5 h-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    }

    toast.classList.add(...bgClass.split(' '));
    toast.innerHTML = `<div>${iconSvg}</div><div class="font-medium ${type === 'info' ? 'text-white' : 'text-gray-800'} text-sm">${message}</div>`;
    document.body.appendChild(toast);

    requestAnimationFrame(() => toast.classList.remove('translate-y-[-20px]', 'opacity-0'));

    if (type !== 'info') {
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-[-20px]');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
}