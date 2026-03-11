let allStudentsData = []; 
let currentPage = 1;
const itemsPerPage = 10;

// --- 1. SABUK PENGAMAN BAHASA (MENCEGAH UNDEFINED DARI PHP LANG) ---
// Jika variabel LANG dari PHP tidak ada/undefined, sistem akan otomatis pakai teks yang ada di kanan (||).
const txt = {
    identity: (typeof LANG !== 'undefined' && LANG.js_table_identity) ? LANG.js_table_identity : 'Identitas Siswa',
    class: (typeof LANG !== 'undefined' && LANG.js_table_class) ? LANG.js_table_class : 'Kelas / Rombel',
    status: (typeof LANG !== 'undefined' && LANG.js_table_status) ? LANG.js_table_status : 'Status Rapor',
    detail: (typeof LANG !== 'undefined' && LANG.js_table_detail) ? LANG.js_table_detail : 'Aksi',
    ready: (typeof LANG !== 'undefined' && LANG.js_ready_print) ? LANG.js_ready_print : 'Siap Cetak',
    subject: (typeof LANG !== 'undefined' && LANG.js_subject) ? LANG.js_subject : 'Mapel',
    process: (typeof LANG !== 'undefined' && LANG.js_process) ? LANG.js_process : 'Proses Input',
    student: (typeof LANG !== 'undefined' && LANG.js_student) ? LANG.js_student : 'Siswa',
    showing: (typeof LANG !== 'undefined' && LANG.js_showing) ? LANG.js_showing : 'Menampilkan',
    to: (typeof LANG !== 'undefined' && LANG.js_to) ? LANG.js_to : '-',
    from: (typeof LANG !== 'undefined' && LANG.js_from) ? LANG.js_from : 'dari total',
    prev: (typeof LANG !== 'undefined' && LANG.js_prev) ? LANG.js_prev : 'Sebelumnya',
    next: (typeof LANG !== 'undefined' && LANG.js_next) ? LANG.js_next : 'Selanjutnya',
    filter_title: (typeof LANG !== 'undefined' && LANG.js_filter_warning_title) ? LANG.js_filter_warning_title : 'Peringatan',
    filter_desc: (typeof LANG !== 'undefined' && LANG.js_filter_warning_desc) ? LANG.js_filter_warning_desc : 'Pilih tingkat/rombel atau masukkan kata kunci.',
    understand: (typeof LANG !== 'undefined' && LANG.js_understand) ? LANG.js_understand : 'Mengerti',
    searching: (typeof LANG !== 'undefined' && LANG.js_searching_student) ? LANG.js_searching_student : 'Mencari data siswa...',
    no_data_title: (typeof LANG !== 'undefined' && LANG.js_no_data_title) ? LANG.js_no_data_title : 'Tidak Ada Data',
    no_data_desc: (typeof LANG !== 'undefined' && LANG.js_no_data_desc) ? LANG.js_no_data_desc : 'Data tidak ditemukan. Silakan sesuaikan filter.',
    load_fail: (typeof LANG !== 'undefined' && LANG.js_load_failed) ? LANG.js_load_failed : 'Gagal',
    load_fail_desc: (typeof LANG !== 'undefined' && LANG.js_load_failed_desc) ? LANG.js_load_failed_desc : 'Gagal mengambil data dari server.',
    preparing: (typeof LANG !== 'undefined' && LANG.js_preparing_report) ? LANG.js_preparing_report : 'Menyiapkan Rapor...',
    no_grades: (typeof LANG !== 'undefined' && LANG.js_no_grades) ? LANG.js_no_grades : 'Belum ada data nilai yang diinputkan.',
    report_title: (typeof LANG !== 'undefined' && LANG.js_report_title) ? LANG.js_report_title : 'LAPORAN CAPAIAN KOMPETENSI',
    semester: (typeof LANG !== 'undefined' && LANG.js_semester) ? LANG.js_semester : 'Semester',
    homeroom: (typeof LANG !== 'undefined' && LANG.js_homeroom) ? LANG.js_homeroom : 'Wali Kelas',
    ack: (typeof LANG !== 'undefined' && LANG.js_ack) ? LANG.js_ack : 'Mengetahui,',
    parent: (typeof LANG !== 'undefined' && LANG.js_parent) ? LANG.js_parent : 'Orang Tua / Wali',
    principal: (typeof LANG !== 'undefined' && LANG.js_principal) ? LANG.js_principal : 'Kepala Sekolah'
};

function updateRombelOptions() {
    // Logic update rombel (Bisa diisi nanti jika butuh ajax rombel dinamis)
}

function applyFilters() {
    const tingkat = document.getElementById('filterTingkat').value;
    const rombel  = document.getElementById('filterRombel').value;
    const search  = document.getElementById('searchSiswa').value.trim();

    if (!tingkat && !rombel && !search) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'warning', title: txt.filter_title, text: txt.filter_desc,
                confirmButtonColor: '#F59E0B', confirmButtonText: txt.understand
            });
        } else {
            alert(txt.filter_desc);
        }
        return; 
    }

    const container  = document.getElementById('studentsTableContainer');
    const emptyState = document.getElementById('emptyState');

    container.innerHTML = `
        <div class="flex flex-col items-center justify-center py-12">
            <svg class="animate-spin h-10 w-10 text-[var(--warna-primary)] mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-gray-500 dark:text-slate-400 font-medium animate-pulse transition-colors">${txt.searching}</p>
        </div>
    `;
    emptyState.style.display = 'none';

    fetch(`${API_URL}/getSiswa?tingkat=${tingkat}&rombel=${rombel}&search=${search}`)
        .then(res => {
            if (!res.ok) throw new Error('Gagal mengambil data dari server');
            return res.json();
        })
        .then(res => {
            if(res.data && res.data.length > 0) {
                allStudentsData = res.data;
                currentPage = 1;
                renderStudentsTable(); 
            } else {
                allStudentsData = []; 
                container.innerHTML = '';
                emptyState.style.display = 'block';
                document.querySelector('#emptyState h3').textContent = txt.no_data_title;
                document.getElementById('emptyStateText').textContent = txt.no_data_desc;
                document.getElementById('studentCount').textContent = `(0 ${txt.student})`;
            }
        })
        .catch(err => {
            console.error(err);
            if (typeof Swal !== 'undefined') Swal.fire(txt.load_fail, txt.load_fail_desc, 'error');
            else alert(txt.load_fail_desc);
            container.innerHTML = '';
            emptyState.style.display = 'block';
        });
}

function renderStudentsTable() {
    const container = document.getElementById('studentsTableContainer');
    document.getElementById('studentCount').textContent = `(${allStudentsData.length} ${txt.student})`;
    document.getElementById('emptyState').style.display = 'none';

    const totalPages = Math.ceil(allStudentsData.length / itemsPerPage);
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const currentStudents = allStudentsData.slice(startIndex, endIndex);

    let html = `
    <div class="table-container bg-transparent dark:bg-transparent overflow-x-auto w-full custom-scrollbar">
      <table class="w-full text-left border-collapse min-w-max bg-white dark:bg-slate-800 rounded-xl overflow-hidden transition-colors shadow-sm">
        <thead class="bg-gray-50 dark:bg-slate-900/60 border-b border-gray-200 dark:border-slate-700 transition-colors">
          <tr class="text-[11px] text-gray-500 dark:text-slate-400 uppercase tracking-widest font-black">
            <th class="text-left py-4 px-5">${txt.identity}</th>
            <th class="text-center py-4 px-4 w-24">${txt.class}</th>
            <th class="text-left py-4 px-4 w-48">${txt.status}</th>
            <th class="text-right py-4 px-6 w-24">${txt.detail}</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-slate-700/50 bg-white dark:bg-slate-800 transition-colors">
    `;

    currentStudents.forEach((s) => {
        // SABUK PENGAMAN NAMA (Hindari error split jika nama kosong)
        const fullName = s.nama_lengkap || 'Siswa Tanpa Nama';
        const names = fullName.trim().split(' ');
        let initials = 'SW';
        if(names.length > 0 && names[0].length > 0) {
            initials = names[0][0];
            if(names.length > 1 && names[1].length > 0) initials += names[1][0];
        }
        
        const nis = s.nis || '-';
        const kelas = s.kelas || 'Belum Diatur';
        
        let statusHTML = '';
        let barColor = '';
        
        const masuk = s.nilaiMasuk || 0;
        const target = s.targetMapel || 1; // Cegah bagi nol
        const persen = s.persentase || 0;

        if (s.statusNilai === 'lengkap') {
            barColor = 'bg-emerald-500';
            statusHTML = `
                <div class="flex items-center justify-between mb-1.5">
                    <div class="flex items-center gap-2">
                        <span class="flex h-2.5 w-2.5 rounded-full bg-emerald-500 shadow-[0_0_5px_#10b981]"></span>
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-slate-300 transition-colors">${txt.ready}</span>
                    </div>
                    <span class="text-[10px] font-mono text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 px-1.5 py-0.5 rounded border border-emerald-100 dark:border-emerald-800/50 font-bold transition-colors">${masuk}/${target} ${txt.subject}</span>
                </div>
                <div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-1.5 overflow-hidden transition-colors">
                    <div class="h-1.5 rounded-full ${barColor}" style="width: 100%"></div>
                </div>
            `;
        } else {
            barColor = 'bg-amber-500';
            statusHTML = `
                <div class="flex items-center justify-between mb-1.5">
                    <div class="flex items-center gap-2">
                        <span class="flex h-2.5 w-2.5 rounded-full bg-amber-500 animate-pulse shadow-[0_0_5px_#f59e0b]"></span>
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-slate-300 transition-colors">${txt.process}</span>
                    </div>
                    <span class="text-[10px] font-mono text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/30 px-1.5 py-0.5 rounded border border-amber-100 dark:border-amber-800/50 font-bold transition-colors">${masuk}/${target} ${txt.subject}</span>
                </div>
                <div class="w-full bg-gray-100 dark:bg-slate-700 rounded-full h-1.5 overflow-hidden transition-colors">
                    <div class="h-1.5 rounded-full ${barColor} transition-all duration-500" style="width: ${persen}%"></div>
                </div>
            `;
        }

        html += `
        <tr onclick="showPreviewRapor(${s.id})" class="group cursor-pointer bg-white dark:bg-slate-800 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
          <td class="p-4 px-5">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 flex-shrink-0 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 p-0.5 shadow-sm ring-2 ring-white dark:ring-slate-800 group-hover:scale-105 transition-transform duration-300">
                    <div class="h-full w-full rounded-full bg-white dark:bg-slate-800 flex items-center justify-center transition-colors">
                        <span class="bg-gradient-to-br from-blue-600 to-indigo-500 bg-clip-text text-transparent font-black text-sm uppercase">
                            ${initials.toUpperCase()}
                        </span>
                    </div>
                </div>
                <div class="min-w-0">
                    <div class="font-bold text-gray-900 dark:text-white text-sm  transition-colors truncate">
                        ${fullName}
                    </div>
                    <div class="text-[11px] text-gray-500 dark:text-slate-400 font-mono mt-0.5 flex items-center gap-1.5 transition-colors">
                        <svg class="w-3.5 h-3.5 text-gray-400 dark:text-slate-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0c0 .6.4 1 1 1s1-.4 1-1m0 0H9m1 1h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V9a2 2 0 012-2zm0 0h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V9a2 2 0 012-2z" /></svg>
                        ${nis}
                    </div>
                </div>
            </div>
          </td>
          <td class="p-4 text-center">
            <span class="inline-block px-3 py-1 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 font-bold text-xs rounded-lg border border-gray-200 dark:border-slate-600 group-hover:bg-white dark:group-hover:bg-slate-600 group-hover:border-[var(--warna-primary)] transition-colors shadow-sm">
                ${kelas}
            </span>
          </td>
          <td class="p-4">
            <div class="max-w-[180px] w-full">
                ${statusHTML}
            </div>
          </td>
          <td class="p-4 text-right pr-6">
            <div class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-gray-50 dark:bg-slate-700 text-gray-400 dark:text-slate-500 group-hover:bg-[var(--warna-primary)] group-hover:text-white transition-all shadow-sm">
                <svg class="w-5 h-5 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </div>
          </td>
        </tr>
        `;
    });

    html += `
        </tbody>
      </table>
    </div>
    `;

    if (totalPages > 1) {
        html += `
        <div class="flex items-center justify-between mt-6 px-2">
            <p class="text-sm text-gray-600 dark:text-slate-400">
                ${txt.showing} <span class="font-bold text-gray-900 dark:text-white">${startIndex + 1}</span> ${txt.to} <span class="font-bold text-gray-900 dark:text-white">${Math.min(endIndex, allStudentsData.length)}</span> ${txt.from} <span class="font-bold text-gray-900 dark:text-white">${allStudentsData.length}</span> ${txt.student}
            </p>
            <div class="flex gap-2">
                <button onclick="changePage(-1)" ${currentPage === 1 ? 'disabled' : ''} class="px-4 py-2 text-sm font-semibold rounded-lg bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm outline-none">
                    ${txt.prev}
                </button>
                <button onclick="changePage(1)" ${currentPage === totalPages ? 'disabled' : ''} class="px-4 py-2 text-sm font-semibold rounded-lg bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm outline-none">
                    ${txt.next}
                </button>
            </div>
        </div>
        `;
    }

    container.innerHTML = html;
}

function changePage(direction) {
    currentPage += direction;
    renderStudentsTable();
}

let currentStudentId = null;

function showPreviewRapor(studentId) {
    currentStudentId = studentId;
    
    const modal = document.getElementById('previewRaporModal');
    const content = document.getElementById('previewContent');

    content.innerHTML = `
        <div class="flex flex-col items-center justify-center py-20">
            <svg class="animate-spin h-10 w-10 text-[var(--warna-primary)] mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-gray-500 font-semibold">${txt.preparing}</p>
        </div>
    `;

    modal.classList.remove('hidden'); 
    document.body.style.overflow = 'hidden';

    fetch(`${API_URL}/getDetailRapor/${studentId}`)
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
                renderRaporDetail(res.data);
            } else {
                alert(res.message || "Terjadi kesalahan.");
                closePreviewModal();
            }
        })
        .catch(err => {
            console.error(err);
            alert(txt.load_fail_desc);
            closePreviewModal();
        });
}

function renderRaporDetail(data) {
    // 1. Ekstrak Semua Data Dinamis yang dikirim Controller
    const { siswa, nilai, wali_kelas, wali_nuptk, sekolah, kepala_sekolah, kepsek_nuptk, tahun_ajaran, semester } = data;
    
    let nilaiRows = '';
    let totalNilai = 0;

    if (nilai && nilai.length > 0) {
        nilaiRows = nilai.map((n, idx) => {
            totalNilai += parseInt(n.nilai_akhir || 0);
            return `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="text-center font-bold border border-gray-400 p-2 text-sm">${idx + 1}</td>
                <td class="border border-gray-400 p-2 text-sm font-semibold">${n.nama_mapel || '-'}</td>
                <td class="text-center border border-gray-400 p-2 text-sm text-gray-600">${n.kkm || 75}</td>
                <td class="text-center font-black text-base border border-gray-400 p-2 ${parseInt(n.nilai_akhir) < parseInt(n.kkm) ? 'text-red-600' : 'text-gray-900'}">${n.nilai_akhir || 0}</td>
                <td class="text-center font-bold ${getPredikatColor(n.predikat)} border border-gray-400 p-2 text-sm">${n.predikat || '-'}</td>
                <td class="text-xs text-gray-700 italic border border-gray-400 p-2 leading-relaxed">${n.deskripsi || '-'}</td>
            </tr>
        `}).join('');
    } else {
        nilaiRows = `<tr><td colspan="6" class="p-8 text-center text-gray-400 italic font-medium border border-gray-400">${txt.no_grades}</td></tr>`;
    }

    const rataRata = (nilai && nilai.length > 0) ? (totalNilai / nilai.length).toFixed(1) : 0;
    const tanggalSekarang = new Date().toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'});

    // 2. Persiapkan Variabel Data Sekolah (Dinamis dari Tabel 'sekolah')
    const s_namaSekolah = (sekolah && sekolah.nama_sekolah) ? sekolah.nama_sekolah : 'NAMA SEKOLAH BELUM DIATUR';
    const s_alamat      = (sekolah && sekolah.alamat) ? sekolah.alamat : 'Alamat belum diatur';
    const s_akreditasi  = (sekolah && sekolah.akreditasi && sekolah.akreditasi !== 'Belum') ? `Terakreditasi ${sekolah.akreditasi}` : 'Belum Akreditasi';
    const s_npsn        = (sekolah && sekolah.npsn) ? sekolah.npsn : '-';
    const s_kota        = (sekolah && sekolah.kabupaten) ? sekolah.kabupaten : 'Lhokseumawe';
    
    // --- LOGIKA LOGO SEKOLAH DINAMIS (ANTI-PECAH) ---
    const baseUrl = window.BASE_URL || (window.location.origin + '/');
    const safeBaseUrl = baseUrl.endsWith('/') ? baseUrl : baseUrl + '/';
    let logoHtml = '';
    
    // Ikon Buku SVG Base64 (Sangat aman dari bentrok kutip HTML)
    const fallbackImage = "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjMDU5NjY5IiBzdHJva2Utd2lkdGg9IjEuNSI+PHBhdGggc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIiBkPSJNMTIgNi4yNTN2MTNtMC0xM0MxMC44MzIgNS40NzcgOS4yNDYgNSA3LjUgNVM0LjE2OCA1LjQ3NyAzIDYuMjUzdjEzQzQuMTY4IDE4LjQ3NyA1Ljc1NCAxOCA3LjUgMThzMy4zMzIuNDc3IDQuNSAxLjI1M20wLTEzQzEzLjE2OCA1LjQ3NyAxNC43NTQgNSAxNi41IDVjMS43NDcgMCAzLjMzMi40NzcgNC41IDEuMjUzdjEzQzE5LjgzMiAxOC40NzcgMTguMjQ3IDE4IDE2LjUgMThjLTEuNzQ2IDAtMy4zMzIuNDc3LTQuNSAxLjI1MyIvPjwvc3ZnPg==";

    if (sekolah && sekolah.logo && sekolah.logo !== '') {
        const namaFileLogo = sekolah.logo;
        
        let logoUrl = '';
        if (namaFileLogo === 'default_logo.png') {
            logoUrl = safeBaseUrl + 'assets/images/' + namaFileLogo;
        } else {
            // PERBAIKAN FOLDER LOGO: 
            // Biasanya modul Profile Sekolah menyimpannya di folder 'uploads/logo/' atau 'uploads/sekolah/'
            // Di sini kita coba pakai 'uploads/logo/'. Jika salah, Anda bisa ubah ini ke folder yang benar.
            logoUrl = safeBaseUrl + 'uploads/logo/' + namaFileLogo;
        }
        
        // Kita juga tambahkan atribut id agar jika image error, kita bisa menanganinya via JS terpisah
        logoHtml = `<img src="${logoUrl}" alt="Logo Sekolah" class="w-20 h-20 object-contain p-1" 
                     onerror="this.onerror=null; this.src='${fallbackImage}';">`;
    } else {
        logoHtml = `<img src="${fallbackImage}" alt="Logo Default" class="w-20 h-20 object-contain p-1">`;
    }

    // 3. Sabuk Pengaman Identitas
    const s_nama  = (siswa && siswa.nama_lengkap) ? siswa.nama_lengkap : 'Siswa Belum Ada Nama';
    const s_nis   = (siswa && siswa.nis) ? siswa.nis : '-';
    const s_nisn  = (siswa && siswa.nisn) ? siswa.nisn : '-';
    const s_kelas = (siswa && siswa.kelas) ? siswa.kelas : 'Belum Diatur';
    const ta      = tahun_ajaran || '-';
    const sem     = semester || '-';

    // ... LANJUTAN KODE (const htmlContent = `... )

 const htmlContent = `
        <div class="watermark text-gray-100 opacity-30 absolute inset-0 flex items-center justify-center -z-10 text-[12rem] font-black transform -rotate-45 pointer-events-none select-none">DRAFT</div>

        <div class="flex flex-col md:flex-row items-center justify-between gap-4 border-b-[5px] border-double border-gray-800 pb-4 mb-6 relative z-10">
            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-emerald-600 flex-shrink-0 border-2 border-emerald-100 overflow-hidden shadow-sm">
                ${logoHtml}
            </div>
            <div class="text-center flex-1">
                <h1 class="text-2xl font-black text-gray-900 uppercase tracking-widest mb-1">${s_namaSekolah}</h1>
                <p class="text-sm text-gray-800 font-serif mb-0.5">${s_alamat}</p>
                <p class="text-sm text-gray-800 font-serif font-bold">${s_akreditasi} | NPSN: ${s_npsn}</p>
            </div>
            <div class="hidden md:block w-24"></div> 
        </div>

        <div class="text-center mb-6 relative z-10">
            <h2 class="text-xl font-bold text-gray-900 uppercase underline decoration-2 underline-offset-4 tracking-wide">${txt.report_title}</h2>
            <p class="text-sm text-gray-600 mt-2 font-bold uppercase tracking-widest">Tahun Ajaran ${ta} | Semester ${sem}</p>
        </div>

        <div class="p-5 mb-6 relative z-10 text-sm border border-gray-300 rounded-xl bg-gray-50/50">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-3 md:gap-x-12">
                <div class="flex justify-between border-b border-gray-300 pb-1.5">
                    <span class="text-gray-600 font-semibold">${txt.identity}</span>
                    <span class="font-black text-gray-900 uppercase">${s_nama}</span>
                </div>
                <div class="flex justify-between border-b border-gray-300 pb-1.5">
                    <span class="text-gray-600 font-semibold">NIS / NISN</span>
                    <span class="font-mono font-bold text-gray-900">${s_nis} / ${s_nisn}</span>
                </div>
                <div class="flex justify-between border-b border-gray-300 pb-1.5">
                    <span class="text-gray-600 font-semibold">${txt.class}</span>
                    <span class="font-bold text-gray-900">${s_kelas}</span>
                </div>
                <div class="flex justify-between border-b border-gray-300 pb-1.5">
                    <span class="text-gray-600 font-semibold">${txt.homeroom}</span>
                    <span class="font-bold text-gray-900">${wali_kelas}</span>
                </div>
            </div>
        </div>

        <div class="mb-10 relative z-10">
            <h3 class="text-base font-black text-gray-900 uppercase mb-3">A. Capaian Akademik</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border border-gray-400">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="w-10 text-center border border-gray-400 p-2.5 text-sm text-gray-900 uppercase">No</th>
                            <th class="text-left border border-gray-400 p-2.5 text-sm text-gray-900 uppercase">Mata Pelajaran</th>
                            <th class="w-16 text-center border border-gray-400 p-2.5 text-sm text-gray-900 uppercase">KKM</th>
                            <th class="w-16 text-center border border-gray-400 p-2.5 text-sm text-gray-900 uppercase">Nilai</th>
                            <th class="w-16 text-center border border-gray-400 p-2.5 text-sm text-gray-900 uppercase">Pred</th>
                            <th class="text-center border border-gray-400 p-2.5 text-sm text-gray-900 uppercase w-64">Deskripsi Kompetensi</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${nilaiRows}
                    </tbody>
                    <tfoot class="bg-gray-100 font-bold">
                        <tr>
                            <td colspan="3" class="text-right pr-4 border border-gray-400 p-3 text-gray-900 uppercase">Rata-Rata Kelas:</td>
                            <td class="text-center text-gray-900 font-black border border-gray-400 p-3 text-lg">${rataRata}</td>
                            <td colspan="2" class="border border-gray-400"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12 text-sm text-center relative z-10 break-inside-avoid">
            <div class="order-2 md:order-1">
                <p class="mb-20 text-gray-800">${txt.ack}<br>${txt.parent}</p>
                <p class="font-bold text-gray-900 border-b border-gray-800 inline-block min-w-[180px] pb-1">( ........................ )</p>
            </div>
            
            <div class="hidden md:block md:order-2"></div> 
            
            <div class="order-1 md:order-3">
                <p class="mb-20 text-gray-800">${s_kota}, ${tanggalSekarang}<br>${txt.homeroom}</p>
                <p class="font-bold text-gray-900 border-b border-gray-800 inline-block min-w-[180px] pb-1">${wali_kelas}</p>
                <p class="text-xs text-gray-600 mt-1">NIP/NUPTK: ${wali_nuptk}</p>
            </div>
        </div>
        
        <div class="mt-8 text-center relative z-10 pb-8 break-inside-avoid">
             <p class="mb-20 text-gray-800">${txt.ack}<br>${txt.principal}</p>
             <p class="font-bold text-gray-900 border-b border-gray-800 inline-block min-w-[220px] pb-1 uppercase">${kepala_sekolah}</p>
             <p class="text-xs text-gray-600 mt-1">NIP/NUPTK: ${kepsek_nuptk}</p>
        </div>
    `;

    document.getElementById('previewContent').innerHTML = htmlContent;
}

function getPredikatColor(predikat) {
    if (predikat === 'A') return 'text-emerald-600';
    if (predikat === 'B') return 'text-blue-600';
    if (predikat === 'C') return 'text-amber-600';
    return 'text-red-600';
}

window.closePreviewModal = function(event) {
    if (event && !event.target.classList.contains('modal-backdrop') && !event.target.getAttribute('onclick')) {
        return;
    }
    const modal = document.getElementById('previewRaporModal');
    if(modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
    currentStudentId = null;
}

// Fitur Lainnya (Dummy / Sesuai Code Asli Anda)
window.returnToTeacher = function() {
    Swal.fire({icon:'info', title:'Belum Tersedia', text:'Fitur ini sedang dikembangkan.'});
}

window.lockRapor = function() {
    Swal.fire({icon:'success', title:'Terkunci', text:'Data ini telah diamankan.'});
}