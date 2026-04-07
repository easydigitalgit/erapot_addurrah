let studentsData = [];
let sysRombelId = '';
let sysTahunId = ''; 
let sysSemester = '';
let sysKategori = 'Akhir Semester'; // Tambahkan penampung State Kategori

document.addEventListener('DOMContentLoaded', loadData);

function getEkskulName(id) {
    if (!id) return '';
    const eks = masterEkskul.find(e => e.id == id);
    return eks ? eks.nama_ekskul : 'Ekskul Tidak Dikenal';
}

function loadData() {
    // Ambil value dropdown Filter
    const filterEl = document.getElementById('filterKategori');
    const selectedKategori = filterEl ? filterEl.value : 'Akhir Semester';

    fetch(`${API_URL}/get-data?kategori=${encodeURIComponent(selectedKategori)}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(res => res.json())
    .then(res => {
        if(res.status === 'success') {
            studentsData = res.data;
            sysRombelId = res.rombel_id;
            sysTahunId = res.tahun_ajaran_id; 
            sysSemester = res.semester;
            sysKategori = res.kategori; // Simpan hasil kembalian
            renderTable(studentsData);
        } else {
            document.getElementById('tableBody').innerHTML = `<tr><td colspan="4" class="p-8 text-center text-rose-500 font-bold">${res.message}</td></tr>`;
        }
    });
}

function renderTable(data) {
    const tbody = document.getElementById('tableBody');
    if (data.length === 0) {
        tbody.innerHTML = `<tr><td colspan="4" class="p-8 text-center text-slate-400 font-medium">Data siswa tidak ditemukan.</td></tr>`;
        return;
    }

    let html = '';
    data.forEach((item, index) => {
        const inisial = item.nama_lengkap.substring(0, 2).toUpperCase();
        let avatarSrc = '';
        const fallbackAvatar = `https://ui-avatars.com/api/?name=${inisial}&background=10b981&color=fff&bold=true`;
        
        if (item.foto_fix && item.foto_fix !== 'null' && String(item.foto_fix).trim() !== '') {
            const cleanBaseUrl = (typeof BASE_URL !== 'undefined' ? BASE_URL : window.location.origin).replace(/\/$/, '');
            const cacheBuster = '?v=' + new Date().getTime();
            avatarSrc = `${cleanBaseUrl}/assets/uploads/avatars/${item.foto_fix}${cacheBuster}`;
        } else {
            avatarSrc = fallbackAvatar;
        }

        const enrolledEkskuls = [item.ekskul_1, item.ekskul_2, item.ekskul_3].filter(e => e != null && e !== '');
        
        let listEkskulHTML = '';
        
        if (enrolledEkskuls.length === 0) {
            listEkskulHTML = '<div class="text-xs text-rose-600 font-bold bg-rose-50 p-2.5 rounded-xl border border-rose-200 dark:bg-rose-900/20 dark:border-rose-800">Siswa belum memilih Ekskul.<br><span class="font-normal text-[10px] text-rose-500">Hubungi Admin Sekolah untuk mendaftarkan ekskul siswa ini.</span></div>';
        } else {
            listEkskulHTML = '<div class="flex flex-col gap-2">';
            enrolledEkskuls.forEach(eid => {
                const ekskulName = getEkskulName(eid);
                const nilai = item.nilai_ekskul.find(n => n.ekskul_id == eid);
                
                if (nilai) {
                    let badgeColor = 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800/50';
                    if(nilai.predikat === 'A') badgeColor = 'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/50';
                    else if(nilai.predikat === 'C') badgeColor = 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800/50';
                    else if(nilai.predikat === 'D') badgeColor = 'bg-rose-100 text-rose-700 border-rose-200 dark:bg-rose-900/30 dark:text-rose-400 dark:border-rose-800/50';

                    listEkskulHTML += `
                    <div class="flex items-center justify-between p-2.5 bg-slate-50 dark:bg-slate-700/30 rounded-xl border border-slate-100 dark:border-slate-700 group/item">
                        <div class="flex-1 pr-3">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-bold text-slate-800 dark:text-white text-[13px]">${ekskulName}</span>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-black border ${badgeColor}">Nilai: ${nilai.predikat}</span>
                            </div>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-tight line-clamp-1 group-hover/item:line-clamp-none transition-all" title="${nilai.deskripsi}">${nilai.deskripsi}</p>
                        </div>
                        <button onclick="deleteNilai(${nilai.id})" class="p-1.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-lg transition-colors outline-none shrink-0" title="Hapus Nilai Saja">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>`;
                } else {
                    listEkskulHTML += `
                    <div class="flex items-center justify-between p-2.5 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 rounded-xl">
                        <span class="font-bold text-amber-800 dark:text-amber-500 text-xs">${ekskulName}</span>
                        <span class="text-[10px] bg-amber-200 dark:bg-amber-800 text-amber-800 dark:text-amber-300 px-2 py-0.5 rounded font-bold">Belum Dinilai</span>
                    </div>`;
                }
            });
            listEkskulHTML += '</div>';
        }

        const btnState = (enrolledEkskuls.length === 0) ? 'opacity-50 cursor-not-allowed bg-gray-100 text-gray-400 border-gray-200' : 'bg-dinamis/10 text-dinamis hover:bg-dinamis hover:text-white border-dinamis/20';

        html += `
        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border-b border-slate-100 dark:border-slate-700/50">
            <td class="p-4 text-center font-medium align-top pt-5">${index + 1}</td>
            <td class="p-4 align-top pt-5">
                <div class="flex items-center gap-3">
                    <img src="${avatarSrc}" class="w-10 h-10 rounded-full object-cover shadow-sm border border-slate-200 dark:border-slate-600" onerror="this.onerror=function(){ this.src='${fallbackAvatar}'; }; ${item.foto_fix && item.foto_fix !== 'null' ? `this.src='${(typeof BASE_URL !== 'undefined' ? BASE_URL : window.location.origin).replace(/\/$/, '')}/uploads/siswa/${item.foto_fix}?v=${new Date().getTime()}';` : ''}">
                    <div>
                        <div class="font-bold text-slate-800 dark:text-white text-sm">${item.nama_lengkap}</div>
                        <div class="text-[11px] text-slate-500 font-mono mt-0.5">NIS: ${item.nis || '-'}</div>
                    </div>
                </div>
            </td>
            <td class="p-4 align-top pt-4 w-1/2">
                ${listEkskulHTML}
            </td>
            <td class="p-4 text-center align-top pt-5">
                <button onclick="showInputModal(${item.id}, '${item.nama_lengkap.replace(/'/g, "\\'")}')" class="px-4 py-2 rounded-xl text-xs font-bold transition-colors flex items-center justify-center gap-2 mx-auto outline-none border shadow-sm ${btnState}" ${enrolledEkskuls.length === 0 ? 'disabled' : ''}>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg> 
                    Beri Nilai
                </button>
            </td>
        </tr>`;
    });
    tbody.innerHTML = html;
}

document.getElementById('searchInput')?.addEventListener('input', function(e) {
    const keyword = e.target.value.toLowerCase();
    const filtered = studentsData.filter(s => 
        s.nama_lengkap.toLowerCase().includes(keyword) || 
        (s.nis && s.nis.toLowerCase().includes(keyword))
    );
    renderTable(filtered);
});

window.generateDeskripsi = function(index, ekskulName) {
    const predikatSelect = document.getElementById(`predikat_${index}`);
    const deskripsiInput = document.getElementById(`deskripsi_${index}`);

    if (!predikatSelect.value) {
        deskripsiInput.value = '';
        return;
    }

    const predikat = predikatSelect.value;
    
    let text = '';
    if (predikat === 'A') {
        text = `Sangat Baik dalam kegiatan ekstrakurikuler ${ekskulName}, menunjukkan minat, bakat, kedisiplinan, dan antusiasme yang sangat menonjol.`;
    } else if (predikat === 'B') {
        text = `Baik dalam kegiatan ekstrakurikuler ${ekskulName}, menunjukkan keaktifan, kerja sama yang baik, dan perkembangan keterampilan yang positif.`;
    } else if (predikat === 'C') {
        text = `Cukup dalam kegiatan ekstrakurikuler ${ekskulName}, perlu sedikit dorongan untuk lebih aktif berpartisipasi dan menggali potensi diri.`;
    } else if (predikat === 'D') {
        text = `Kurang aktif dalam kegiatan ekstrakurikuler ${ekskulName}, sangat membutuhkan bimbingan, perhatian, dan motivasi lebih lanjut untuk berkembang.`;
    }

    deskripsiInput.value = text;
    
    deskripsiInput.classList.add('ring-2', 'ring-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-900/30');
    setTimeout(() => { deskripsiInput.classList.remove('ring-2', 'ring-emerald-500', 'bg-emerald-50', 'dark:bg-emerald-900/30'); }, 800);
};

const modal = document.getElementById('formModal');
const modalContent = document.getElementById('modalContent');
const form = document.getElementById('nilaiForm');

window.showInputModal = function(siswaId, namaSiswa) {
    const student = studentsData.find(s => s.id == siswaId);
    if(!student) return;

    form.reset();
    document.getElementById('siswa_id').value = siswaId;
    document.getElementById('rombel_id').value = sysRombelId;
    document.getElementById('tahun_ajaran_id').value = sysTahunId; 
    document.getElementById('semester').value = sysSemester;
    
    // Set Input Kategori
    document.getElementById('kategori').value = sysKategori;
        
    document.getElementById('modalStudentName').innerText = `Siswa: ${namaSiswa} | Kategori: ${sysKategori}`;

    const container = document.getElementById('dynamicEkskulContainer');
    container.innerHTML = '';

    const enrolledEkskuls = [student.ekskul_1, student.ekskul_2, student.ekskul_3].filter(e => e != null && e !== '');
    
    let formHTML = '';
    enrolledEkskuls.forEach((eid, idx) => {
        const ekskulName = getEkskulName(eid);
        const existingNilai = student.nilai_ekskul.find(n => n.ekskul_id == eid);
        
        const selA = (existingNilai && existingNilai.predikat === 'A') ? 'selected' : '';
        const selB = (existingNilai && existingNilai.predikat === 'B') ? 'selected' : '';
        const selC = (existingNilai && existingNilai.predikat === 'C') ? 'selected' : '';
        const selD = (existingNilai && existingNilai.predikat === 'D') ? 'selected' : '';
        const currentDeskripsi = existingNilai ? existingNilai.deskripsi : '';

        formHTML += `
        <div class="bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700 p-4 rounded-2xl relative overflow-hidden">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-dinamis"></div>
            <input type="hidden" name="ekskul_id[]" value="${eid}">
            
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-700 pb-3 mb-3 pl-2">
                <h4 class="font-black text-slate-800 dark:text-white text-sm">
                    <span class="text-dinamis mr-1">#${idx+1}</span> ${ekskulName}
                </h4>
                <div class="w-1/3">
                    <select name="predikat[]" id="predikat_${idx}" onchange="generateDeskripsi(${idx}, '${ekskulName}')" class="w-full px-3 py-1.5 border border-slate-300 dark:border-slate-600 rounded-lg outline-none focus:ring-2 focus:ring-dinamis dark:bg-slate-800 dark:text-white text-xs cursor-pointer font-bold shadow-sm">
                        <option value="">-- Pilih Nilai --</option>
                        <option value="A" ${selA}>Sangat Baik (A)</option>
                        <option value="B" ${selB}>Baik (B)</option>
                        <option value="C" ${selC}>Cukup (C)</option>
                        <option value="D" ${selD}>Kurang (D)</option>
                    </select>
                </div>
            </div>

            <div class="pl-2">
                <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1 flex justify-between items-center">
                    <span>Deskripsi Rapor</span>
                    <span class="text-[9px] text-dinamis bg-emerald-50 dark:bg-emerald-900/30 px-2 py-0.5 rounded border border-emerald-200 dark:border-emerald-800/50">✨ Auto-Fill Active</span>
                </label>
                <textarea name="deskripsi[]" id="deskripsi_${idx}" rows="2" placeholder="Pilih nilai di atas untuk auto-fill deskripsi..." class="w-full p-3 border border-slate-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-dinamis dark:bg-slate-800 dark:text-white text-xs resize-none transition-colors leading-relaxed shadow-inner">${currentDeskripsi}</textarea>
            </div>
        </div>`;
    });

    container.innerHTML = formHTML;
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
};

window.closeModal = function() {
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    setTimeout(() => modal.classList.add('hidden'), 300);
};

form.addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('btnSave');
    const originalText = btn.innerHTML;
    btn.innerHTML = 'Menyimpan Semua Nilai...';
    btn.disabled = true;

    // AMANKAN REQUEST DENGAN CSRF TOKEN
    const formData = new FormData(form);
    const csrfTokenMeta = document.querySelector('meta[name="X-CSRF-TOKEN"]');
    if (csrfTokenMeta) formData.append('csrf_test_name', csrfTokenMeta.content);

    try {
        const res = await fetch(`${API_URL}/save`, { 
            method: 'POST', 
            body: formData, 
            headers: {'X-Requested-With': 'XMLHttpRequest'} 
        });
        const json = await res.json();
        
        // Update Token agar form bisa di-submit berkali-kali tanpa refresh
        if(json.token && csrfTokenMeta) csrfTokenMeta.content = json.token;

        if (json.status === 'success') {
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: json.message, showConfirmButton: false, timer: 1500, customClass:{popup:'rounded-3xl'} });
            closeModal();
            loadData(); // Refresh Data List secara dinamis
        } else {
            Swal.fire({ icon: 'error', title: 'Gagal!', text: json.message, customClass:{popup:'rounded-3xl'} });
        }
    } catch (err) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Koneksi terputus.', customClass:{popup:'rounded-3xl'} });
    } finally {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }
});

window.deleteNilai = function(id) {
    Swal.fire({
        title: 'Hapus Nilai Ekskul Ini?',
        text: "Hanya menghapus nilai, ekskul tetap ada.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Ya, Hapus!',
        customClass: { popup: 'rounded-3xl' }
    }).then(async (result) => {
        if (result.isConfirmed) {
            const formData = new FormData();
            formData.append('id', id);
            
            const csrfTokenMeta = document.querySelector('meta[name="X-CSRF-TOKEN"]');
            if (csrfTokenMeta) formData.append('csrf_test_name', csrfTokenMeta.content);

            try {
                const res = await fetch(`${API_URL}/delete`, { method: 'POST', body: formData, headers: {'X-Requested-With': 'XMLHttpRequest'} });
                const json = await res.json();
                
                if(json.token && csrfTokenMeta) csrfTokenMeta.content = json.token;

                if (json.status === 'success') {
                    Swal.fire({ icon: 'success', title: 'Dihapus!', text: json.message, showConfirmButton: false, timer: 1500, customClass:{popup:'rounded-3xl'} });
                    loadData();
                }
            } catch (err) {}
        }
    });
};