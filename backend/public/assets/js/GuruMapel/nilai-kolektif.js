function downloadTemplate() {
    const ta = document.getElementById('dl_ta').value;
    const kelas = document.getElementById('dl_kelas').value;
    const mapel = document.getElementById('dl_mapel').value;
    const jenis = document.getElementById('dl_jenis').value;

    if (!kelas || !mapel || !ta) {
        Swal.fire({
            icon: 'warning',
            title: window.LANG.swal_warn_title || 'Pilih Data Dulu',
            text: window.LANG.swal_warn_text || 'Harap lengkapi semua pilihan filter.',
            confirmButtonColor: '#F59E0B'
        });
        return;
    }

    Swal.fire({
        title: window.LANG.swal_loading_title || 'Memproses',
        text: window.LANG.swal_loading_text || 'Menyiapkan file Excel...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    const exportUrl = `${API_DOWNLOAD}?ta=${ta}&kelas=${kelas}&mapel=${mapel}&jenis=${jenis}`;
    window.location.href = exportUrl;

    setTimeout(() => {
        Swal.close();
    }, 1500);
}

function importExcel(event) {
    event.preventDefault();
    
    // KUNCI: AMBIL TAHUN AJARAN DARI FILTER LAYAR
    const ta = document.getElementById('dl_ta').value;
    if (!ta) {
        Swal.fire({
            icon: 'warning',
            title: window.LANG.swal_warn_title || 'Pilih Data Dulu',
            text: 'Silakan pilih Tahun Ajaran pada form sebelah kiri terlebih dahulu.',
            confirmButtonColor: '#F59E0B'
        });
        return;
    }

    const form = document.getElementById('formImport');
    const formData = new FormData(form);
    
    // Sisipkan id tahun ajaran yang dipilih ke request form
    formData.append('ta_id', ta);

    const btnImport = document.getElementById('btnImport');
    const originalBtnContent = btnImport.innerHTML;

    btnImport.disabled = true;
    btnImport.innerHTML = `<svg class="animate-spin w-5 h-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> <span>${window.LANG.js_processing || 'Memproses...'}</span>`;

    let headers = { 'X-Requested-With': 'XMLHttpRequest' };
    try {
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfHeader = document.querySelector('meta[name="csrf-header"]');
        if (csrfMeta && csrfHeader) {
            headers[csrfHeader.getAttribute('content')] = csrfMeta.getAttribute('content');
        }
    } catch(e) {}

    fetch(API_IMPORT, {
        method: 'POST',
        headers: headers,
        body: formData
    })
    .then(response => response.json())
    .then(res => {
        if(res.status === 'success') {
            Swal.fire({
                title: window.LANG.swal_succ_title || 'Berhasil',
                text: res.message,
                icon: 'success',
                confirmButtonColor: '#10B981'
            }).then(() => {
                form.reset(); 
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: window.LANG.swal_fail_title || 'Gagal',
                text: res.message,
                confirmButtonColor: '#EF4444'
            });
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: window.LANG.swal_err_title || 'Error Server',
            text: window.LANG.swal_err_text || 'Terjadi kesalahan sistem',
            confirmButtonColor: '#EF4444'
        });
    })
    .finally(() => {
        btnImport.disabled = false;
        btnImport.innerHTML = originalBtnContent;
    });
}