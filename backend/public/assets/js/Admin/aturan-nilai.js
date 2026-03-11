// ==========================================
// SABUK PENGAMAN BAHASA (FALLBACK)
// ==========================================
const LANG = window.LANG || {
    js_valid: '✔️ Valid', js_unbalanced: '⚠️ Tidak Seimbang', js_saving: 'Menyimpan...',
    js_err_range: 'Nilai minimum tidak boleh lebih besar dari nilai maksimum!',
    js_succ_save: 'Berhasil! Bobot penilaian telah disimpan.', js_fail_prefix: 'Gagal: ',
    js_err_server: 'Terjadi kesalahan server.',
    js_conf_reset: 'Apakah Anda yakin ingin mereset semua bobot ke pengaturan awal? Perubahan yang belum disimpan akan hilang.',
    js_succ_reset: 'Berhasil mereset pengaturan!', js_fail_reset: 'Gagal reset: ',
    js_empty_hist: 'Belum ada riwayat perubahan.', js_err_load_hist: 'Gagal memuat data riwayat.',
    js_err_auto_bal: 'Isi minimal satu bobot sebelum melakukan Auto Balance!',
    js_succ_auto_bal: 'Bobot berhasil disesuaikan otomatis menjadi 100%'
};

function openMobileSidebar() {
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebar-overlay');
  sidebar.classList.add('mobile-open');
  overlay.classList.add('active');
  document.body.style.overflow = 'hidden';
}

function closeMobileSidebar() {
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebar-overlay');
  sidebar.classList.remove('mobile-open');
  overlay.classList.remove('active');
  document.body.style.overflow = '';
}

function toggleAccordion(header) {
  const content = header.nextElementSibling;
  const icon = header.querySelector('.accordion-icon');
  
  if (content.classList.contains('open')) {
    content.classList.remove('open');
    icon.style.transform = 'rotate(0deg)';
  } else {
    content.classList.add('open');
    icon.style.transform = 'rotate(180deg)';
  }
}

// ==========================================
// FUNGSI UPDATE TOTAL & PREVIEW DINAMIS
// ==========================================
function updateTotal() {
  let akademikTotal = 0;
  document.querySelectorAll('.akademik-weight').forEach(input => {
    const toggle = input.closest('.flex').querySelector('input[type="checkbox"]');
    if (toggle && toggle.checked) akademikTotal += parseFloat(input.value) || 0;
  });
  document.getElementById('akademikTotal').textContent = akademikTotal + '%';

  let karakterTotal = 0;
  document.querySelectorAll('.karakter-weight').forEach(input => {
    const toggle = input.closest('.flex').querySelector('input[type="checkbox"]');
    if (toggle && toggle.checked) karakterTotal += parseFloat(input.value) || 0;
  });
  document.getElementById('karakterTotal').textContent = karakterTotal + '%';

  let keislamanTotal = 0;
  document.querySelectorAll('.keislaman-weight').forEach(input => {
    const toggle = input.closest('.flex').querySelector('input[type="checkbox"]');
    if (toggle && toggle.checked) keislamanTotal += parseFloat(input.value) || 0;
  });
  document.getElementById('keislamanTotal').textContent = keislamanTotal + '%';

  const grandTotal = akademikTotal + karakterTotal + keislamanTotal;
  document.getElementById('totalBobotValue').textContent = grandTotal + '%';
  
  const currentWarning = document.getElementById('currentBobotWarning');
  if(currentWarning) currentWarning.textContent = grandTotal + '%';

  const statusDiv = document.getElementById('bobotStatus');
  const warningAlert = document.getElementById('warningAlert');
  const totalCard = document.getElementById('totalBobotCard');

  if (grandTotal === 100) {
    if(statusDiv) statusDiv.innerHTML = `<span class="inline-flex px-2 py-0.5 bg-emerald-50 text-emerald-700 font-bold text-[10px] rounded border border-emerald-200 shadow-sm">${LANG.js_valid}</span>`;
    if(warningAlert) warningAlert.classList.add('hidden');
    if(totalCard) totalCard.style.borderColor = '#E5E7EB';
  } else {
    if(statusDiv) statusDiv.innerHTML = `<span class="inline-flex px-2 py-0.5 bg-amber-50 text-amber-700 font-bold text-[10px] rounded border border-amber-200 shadow-sm">${LANG.js_unbalanced}</span>`;
    if(warningAlert) warningAlert.classList.remove('hidden');
    if(totalCard) totalCard.style.borderColor = '#F59E0B';
  }

  // UPDATE PREVIEW FORMULA (TEKS & SVG PIE CHART)
  updatePreviewFormula(akademikTotal, karakterTotal, keislamanTotal, grandTotal);
}

function updatePreviewFormula(akademik, karakter, keislaman, total) {
    // Update Teks Label & Formula
    const lblAkademik = document.getElementById('labelAkademikValue');
    const lblKarakter = document.getElementById('labelKarakterValue');
    const lblKeislaman = document.getElementById('labelKeislamanValue');
    
    if(lblAkademik) lblAkademik.textContent = akademik + '%';
    if(lblKarakter) lblKarakter.textContent = karakter + '%';
    if(lblKeislaman) lblKeislaman.textContent = keislaman + '%';

    const formAkademik = document.getElementById('formulaAkademik');
    const formKarakter = document.getElementById('formulaKarakter');
    const formKeislaman = document.getElementById('formulaKeislaman');
    
    if(formAkademik) formAkademik.textContent = `(Akademik × ${akademik}%)`;
    if(formKarakter) formKarakter.textContent = `(Karakter × ${karakter}%)`;
    if(formKeislaman) formKeislaman.textContent = `(Keislaman × ${keislaman}%)`;

    const pieText = document.getElementById('pieTotalText');
    if(pieText) pieText.textContent = total + '%';

    // Update SVG Pie Chart Math (Keliling lingkaran r=80 adalah 502.7)
    const circumference = 502.7;
    const pieKarakter = document.getElementById('pieKarakter');
    const pieKeislaman = document.getElementById('pieKeislaman');

    if (total > 0 && pieKarakter && pieKeislaman) {
        // Kalkulasi proporsi dari 100% (bukan dari total saat itu agar visual terlihat tidak seimbang jika total < 100)
        let pctAkademik = akademik;
        let pctKarakter = karakter;
        let pctKeislaman = keislaman;

        let strokeKarakter = (pctKarakter / 100) * circumference;
        let offsetKarakter = -((pctAkademik / 100) * circumference);

        let strokeKeislaman = (pctKeislaman / 100) * circumference;
        let offsetKeislaman = offsetKarakter - strokeKarakter;

        pieKarakter.style.strokeDasharray = `${strokeKarakter} ${circumference}`;
        pieKarakter.style.strokeDashoffset = offsetKarakter;

        pieKeislaman.style.strokeDasharray = `${strokeKeislaman} ${circumference}`;
        pieKeislaman.style.strokeDashoffset = offsetKeislaman;
    }
}

// Eksekusi kalkulasi saat pertama kali load
document.addEventListener('DOMContentLoaded', () => {
    updateTotal();
});

// ==========================================
// AJAX: SIMPAN BOBOT
// ==========================================
function saveChanges() {
    const totalText = document.getElementById('totalBobotValue').textContent;
    const total = parseFloat(totalText); 
    
    if (total !== 100) {
        showToast(LANG.js_unbalanced, 'error');
        return;
    }

    let payload = {
        bobot: { akademik: {}, karakter: {}, keislaman: {} }
    };

    const inputs = document.querySelectorAll('.weight-input');
    
    inputs.forEach(input => {
        const kategori = input.getAttribute('data-kategori'); 
        const sub = input.getAttribute('data-sub');           
        const nilai = parseInt(input.value) || 0;

        if (kategori && sub) {
            payload.bobot[kategori][sub] = nilai;
        }
    });

    fetch(BASE_URL + 'admin/aturan-nilai/update-bobot', { 
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showToast(LANG.js_succ_save, 'success');
        } else {
            showToast(LANG.js_fail_prefix + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast(LANG.js_err_server, 'error');
    });
}

// --- MODAL LOGIC (ADD RULE) ---
function showAddRuleModal() {
    const modal = document.getElementById('addRuleModal');
    if (modal) {
        modal.classList.remove('hidden'); 
        document.body.style.overflow = 'hidden';
        
        const form = document.getElementById('addRuleForm');
        if(form) form.reset();
        selectColor('emerald'); 
    }
}

function closeAddRuleModal() {
    const modal = document.getElementById('addRuleModal');
    if (modal) {
        modal.classList.add('hidden'); 
        document.body.style.overflow = '';
    }
}

// --- COLOR SELECTION LOGIC ---
function selectColor(color) {
    document.getElementById('selectedColor').value = color;
    document.querySelectorAll('.color-option').forEach(btn => {
        if (btn.dataset.color === color) {
            btn.style.borderColor = '#1F7A4D'; 
            btn.style.transform = 'scale(1.1)';
        } else {
            btn.style.borderColor = 'transparent';
            btn.style.transform = 'scale(1)';
        }
    });
}

// --- FORM SUBMIT LOGIC (TAMBAH ATURAN) ---
document.addEventListener('DOMContentLoaded', () => {
    
    const form = document.getElementById('addRuleForm');
    
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const formData = new FormData(form);
            formData.append('warna', document.getElementById('selectedColor').value);
            
            const status = document.getElementById('statusToggle').checked ? 'on' : 'off';
            formData.append('status', status);

            const nilaiMin = parseInt(document.getElementById('nilaiMinInput').value);
            const nilaiMax = parseInt(document.getElementById('nilaiMaxInput').value);

            if (nilaiMin > nilaiMax) {
                showToast(LANG.js_err_range, 'error');
                return;
            }

            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = LANG.js_saving;
            submitBtn.disabled = true;

            fetch(BASE_URL + 'admin/aturan-nilai/store-aturan', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    closeAddRuleModal();
                    showToast(data.message, 'success');
                    form.reset();
                    selectColor('emerald');
                    setTimeout(() => { window.location.reload(); }, 1000); 
                } else {
                    showToast(LANG.js_fail_prefix + data.message, 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showToast(LANG.js_err_server, 'error');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});

// --- TOAST FUNCTION ---
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = 'toast fixed top-4 right-4 z-[100000] flex items-center gap-3 px-4 py-3 bg-white border border-gray-100 rounded-xl shadow-lg transition-all duration-300';
    
    const iconColor = type === 'success' ? 'text-emerald-600' : 'text-red-600';
    const bgColor = type === 'success' ? 'bg-emerald-100' : 'bg-red-100';
    
    const icon = type === 'success' 
        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>' 
        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';

    toast.innerHTML = `
        <div class="w-10 h-10 rounded-full flex items-center justify-center ${bgColor} flex-shrink-0">
            <svg class="w-6 h-6 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">${icon}</svg>
        </div>
        <div>
            <p class="font-semibold text-gray-800">${message}</p>
        </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeAddRuleModal();
        closeHistoryModal();
    }
});

// --- FITUR RESET KE DEFAULT ---
function resetToDefault() {
    if(!confirm(LANG.js_conf_reset)) {
        return;
    }

    fetch(BASE_URL + 'admin/aturan-nilai/reset-bobot', { 
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            showToast(LANG.js_succ_reset, 'success');
            setTimeout(() => location.reload(), 1000); 
        } else {
            showToast(LANG.js_fail_reset + data.message, 'error');
        }
    })
    .catch(err => showToast(LANG.js_err_server, 'error'));
}

// --- FITUR RIWAYAT (HISTORY) ---
function showHistory() {
    const modal = document.getElementById('historyModal');
    const container = document.getElementById('historyListContainer');
    
    if(modal) {
        modal.classList.remove('hidden');
        
        fetch(BASE_URL + 'admin/aturan-nilai/get-riwayat', { 
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.json())
        .then(data => {
            if(data.length === 0) {
                container.innerHTML = `<div class="p-8 text-center text-gray-500">${LANG.js_empty_hist}</div>`;
                return;
            }

            let html = '<ul class="divide-y divide-gray-100">';
            data.forEach(item => {
                const date = new Date(item.created_at).toLocaleString('id-ID');
                
                html += `
                <li class="p-4 hover:bg-gray-50 transition-colors">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 text-blue-600 mt-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 text-sm">${item.aksi}</p>
                            <p class="text-xs text-gray-600 mt-0.5">${item.detail}</p>
                            <div class="flex items-center gap-2 mt-2">
                                <span class="text-[10px] bg-gray-100 px-2 py-0.5 rounded text-gray-500">${item.user}</span>
                                <span class="text-[10px] text-gray-400">${date}</span>
                            </div>
                        </div>
                    </div>
                </li>`;
            });
            html += '</ul>';
            container.innerHTML = html;
        })
        .catch(err => {
            container.innerHTML = `<div class="p-6 text-center text-red-500">${LANG.js_err_load_hist}</div>`;
        });
    }
}

function closeHistoryModal() {
    const modal = document.getElementById('historyModal');
    if(modal) modal.classList.add('hidden');
}

// --- FITUR AUTO BALANCE BOBOT ---
function autoBalance() {
    const inputs = document.querySelectorAll('.weight-input');
    let currentTotal = 0;
    let activeInputs = [];

    inputs.forEach(input => {
        let val = parseFloat(input.value) || 0;
        currentTotal += val;
        activeInputs.push({
            element: input,
            value: val
        });
    });

    if (currentTotal === 0) {
        showToast(LANG.js_err_auto_bal, 'error');
        return;
    }

    const factor = 100 / currentTotal;
    let newRunningTotal = 0;

    for (let i = 0; i < activeInputs.length - 1; i++) {
        let item = activeInputs[i];
        let newVal = Math.round(item.value * factor);
        item.element.value = newVal;
        newRunningTotal += newVal;
    }

    let lastItem = activeInputs[activeInputs.length - 1];
    let remainder = 100 - newRunningTotal;
    
    if(remainder < 0) remainder = 0;
    
    lastItem.element.value = remainder;

    updateTotal();
    showToast(LANG.js_succ_auto_bal, 'success');
}