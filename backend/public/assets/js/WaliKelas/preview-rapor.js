let studentsData = typeof serverStudents !== 'undefined' ? serverStudents : [];
let currentRombel = typeof rombelName !== 'undefined' ? rombelName : 'Kelas';

document.addEventListener('DOMContentLoaded', function() {
    renderPreviewRaporUtama();
});

function renderPreviewRaporUtama() {
    const mainContent = document.getElementById('mainContent');
    
    // Jana pilihan dropdown pelajar
    const studentOptions = studentsData.map(s => `<option value="${s.id}">${s.name}</option>`).join('');

    mainContent.innerHTML = `
        <div class="bg-gradient-to-r from-slate-800 to-slate-700 rounded-3xl p-8 mb-6 text-white shadow-lg no-print">
            <div class="flex items-center justify-between flex-col sm:flex-row gap-4">
                <div>
                    <h1 class="text-3xl font-black mb-2 flex items-center gap-3">
                        📋 Pratonton Rapor Rasmi
                    </h1>
                    <p class="text-slate-300 font-medium tracking-wide">Semak data akademik, tahfiz, dan catatan kelas ${currentRombel}.</p>
                </div>
                <button onclick="printRapor()" class="px-8 py-3.5 bg-white text-slate-800 font-bold rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak Rapor
                </button>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6 no-print">
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Pilih Nama Pelajar</label>
            <select id="siswaRaporSelect" class="w-full px-5 py-3 border-2 border-gray-200 rounded-xl font-bold text-gray-800 focus:outline-none focus:border-slate-500 bg-gray-50 cursor-pointer" onchange="fetchRaporData()">
                <option value="">-- Sila Pilih Pelajar Untuk Menjana Rapor --</option>
                ${studentOptions}
            </select>
        </div>

        <div id="raporContainer" class="hidden animate-fade-in"></div>

        <div id="loadingState" class="hidden bg-white rounded-3xl shadow-sm border border-gray-100 p-16 text-center">
            <div class="animate-spin rounded-full h-14 w-14 border-b-4 border-slate-800 mx-auto mb-4"></div>
            <p class="text-slate-500 font-bold">Mengekstrak data dari pelayan...</p>
        </div>

        <div id="emptyState" class="bg-white rounded-3xl shadow-sm border border-gray-100 p-16 text-center no-print">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-5 text-gray-300">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <p class="text-gray-400 font-bold text-lg">Sila pilih nama pelajar dari menu atas untuk memaparkan Rapor.</p>
        </div>
    `;
}

// Mengambil Data dari API PHP
async function fetchRaporData() {
    const siswaId = document.getElementById('siswaRaporSelect').value;
    const emptyState = document.getElementById('emptyState');
    const loadingState = document.getElementById('loadingState');
    const raporContainer = document.getElementById('raporContainer');

    if (!siswaId) {
        emptyState.classList.remove('hidden');
        raporContainer.classList.add('hidden');
        return;
    }

    emptyState.classList.add('hidden');
    raporContainer.classList.add('hidden');
    loadingState.classList.remove('hidden');

    try {
        const response = await fetch(`${BASE_URL}/wali/preview-rapor/get-data/${siswaId}`);
        const data = await response.json();
        
        if (data.error) throw new Error(data.error);

        // Setelah data berjaya diambil, paparkan rapor
        loadingState.classList.add('hidden');
        renderTemplateRapor(data);
        raporContainer.classList.remove('hidden');

    } catch (err) {
        console.error("Gagal mendapatkan rapor:", err);
        loadingState.classList.add('hidden');
        emptyState.classList.remove('hidden');
        alert("Gagal memuat turun data rapor pelajar ini. Pastikan pangkalan data mempunyai maklumat yang tepat.");
    }
}

// Menjana Reka Bentuk Rapor
function renderTemplateRapor(rapor) {
    const raporContainer = document.getElementById('raporContainer');
    
    // Kira Statistik Nilai
    let totalNilai = 0, validMapel = 0;
    rapor.nilaiAkademik.forEach(n => {
        if (n.nilai > 0) { totalNilai += n.nilai; validMapel++; }
    });
    const rataRataNilai = validMapel > 0 ? Math.round(totalNilai / validMapel) : 0;
    const maxNilai = validMapel > 0 ? Math.max(...rapor.nilaiAkademik.map(n => n.nilai)) : 0;
    const minNilai = validMapel > 0 ? Math.min(...rapor.nilaiAkademik.map(n => n.nilai)) : 0;

    raporContainer.innerHTML = `
        <div class="rapor-card bg-white rounded-3xl shadow-xl border-4 border-slate-800 overflow-hidden mb-8 print:shadow-none print:border-none print:rounded-none">
            
            <div class="bg-slate-800 text-white p-10 text-center print:bg-white print:text-black print:border-b-4 print:border-black">
                <h2 class="text-4xl font-black mb-3 tracking-widest uppercase">LAPORAN HASIL BELAJAR</h2>
                <p class="text-slate-300 print:text-slate-700 text-lg font-bold">Semester ${rapor.semester} | Tahun Ajaran ${rapor.tahunAjaran}</p>
            </div>

            <div class="p-8 border-b border-slate-200 bg-slate-50 print:bg-white">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div>
                        <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold mb-1">Nama Siswa</p>
                        <p class="text-lg font-black text-slate-900">${rapor.nama}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold mb-1">NISN / NIS</p>
                        <p class="text-lg font-black text-slate-900">${rapor.nisn}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold mb-1">Kelas</p>
                        <p class="text-lg font-black text-slate-900">${rapor.kelas}</p>
                    </div>
                    <div class="text-right print:text-left">
                        <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold mb-1">Peringkat Kelas</p>
                        <p class="text-2xl font-black text-slate-800">#${rapor.karakter.ranking}</p>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <h3 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-3">
                    <span class="p-2 bg-slate-100 rounded-lg">A</span> CAPAIAN AKADEMIK
                </h3>
                
                <div class="overflow-hidden rounded-xl border border-slate-200 mb-8">
                    <table class="w-full text-left">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="p-4 font-bold text-sm text-slate-700 w-16 text-center">NO</th>
                                <th class="p-4 font-bold text-sm text-slate-700">MATA PELAJARAN</th>
                                <th class="p-4 font-bold text-sm text-slate-700 text-center">NILAI AKHIR</th>
                                <th class="p-4 font-bold text-sm text-slate-700 text-center">PREDIKAT</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            ${rapor.nilaiAkademik.map((item, idx) => `
                                <tr class="hover:bg-slate-50 print:bg-transparent">
                                    <td class="p-4 text-center font-semibold text-slate-500">${idx + 1}</td>
                                    <td class="p-4 font-bold text-slate-800">${item.mapel}</td>
                                    <td class="p-4 text-center font-black text-lg text-slate-900">${item.nilai}</td>
                                    <td class="p-4 text-center">
                                        <span class="inline-block w-8 text-center font-bold text-slate-700 border border-slate-300 rounded bg-white">${item.grade}</span>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>

                <div class="grid grid-cols-3 gap-4 p-5 bg-slate-800 rounded-xl text-white print:bg-slate-100 print:text-black print:border print:border-slate-300">
                    <div class="text-center">
                        <p class="text-[10px] text-slate-400 print:text-slate-500 font-bold mb-1 uppercase tracking-widest">Rata-Rata</p>
                        <p class="text-3xl font-black">${rataRataNilai}</p>
                    </div>
                    <div class="text-center border-l border-r border-slate-600 print:border-slate-300">
                        <p class="text-[10px] text-slate-400 print:text-slate-500 font-bold mb-1 uppercase tracking-widest">Tertinggi</p>
                        <p class="text-3xl font-black text-green-400 print:text-green-600">${maxNilai}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] text-slate-400 print:text-slate-500 font-bold mb-1 uppercase tracking-widest">Terendah</p>
                        <p class="text-3xl font-black text-red-400 print:text-red-600">${minNilai}</p>
                    </div>
                </div>
            </div>

            <div class="p-8 border-t border-slate-200 grid grid-cols-1 md:grid-cols-2 gap-8 bg-slate-50 print:bg-white">
                <div>
                    <h3 class="text-xl font-black text-slate-900 mb-4 flex items-center gap-3">
                        <span class="p-2 bg-white border border-slate-200 rounded-lg">B</span> PROGRES TAHFIDZ
                    </h3>
                    <div class="bg-white p-5 rounded-xl border border-slate-200">
                        <div class="flex justify-between items-end mb-3">
                            <div>
                                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest">Hafalan Terakhir</p>
                                <p class="text-lg font-black text-slate-800">${rapor.tahfidz.surah} (Ayat ${rapor.tahfidz.ayat})</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-black text-slate-900 mb-4 flex items-center gap-3">
                        <span class="p-2 bg-white border border-slate-200 rounded-lg">C</span> CATATAN WALI KELAS
                    </h3>
                    <div class="bg-white p-5 rounded-xl border-l-4 border-l-blue-500 border-t border-r border-b border-slate-200 h-full">
                        <p class="text-slate-700 font-medium leading-relaxed italic">"${rapor.catatan}"</p>
                    </div>
                </div>
            </div>

            <div class="p-10 border-t border-slate-200 print:mt-10">
                <div class="grid grid-cols-3 gap-8 text-center mt-6">
                    <div>
                        <p class="text-xs text-slate-500 font-bold mb-20 uppercase tracking-widest">Wali/Orang Tua</p>
                        <p class="text-sm font-bold text-slate-800 border-b border-slate-400 pb-1 inline-block min-w-[150px]">( ................................ )</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-bold mb-20 uppercase tracking-widest">Siswa</p>
                        <p class="text-sm font-bold text-slate-800 border-b border-slate-400 pb-1 inline-block min-w-[150px]">${rapor.nama}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-bold mb-20 uppercase tracking-widest">Wali Kelas</p>
                        <p class="text-sm font-bold text-slate-800 border-b border-slate-400 pb-1 inline-block min-w-[150px]">${rapor.waliKelasName}</p>
                    </div>
                </div>
            </div>
            
            <p class="text-center text-xs text-slate-400 my-6 print:block hidden">
                Dicetak pada: ${new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}
            </p>

        </div>
    `;
}

// Gantikan fungsi printRapor() yang lama dengan ini:

function printRapor() {
    const siswaId = document.getElementById('siswaRaporSelect').value;
    
    if (!siswaId) {
        alert('Sila pilih nama pelajar terlebih dahulu sebelum mencetak!');
        return;
    }

    // Ambil tahun ajaran dan semester dari data rapor yang sedang dipaparkan
    // Atau jika anda mahu cara paling selamat (menggunakan semester ganjil/genap default):
    const semester = 'Ganjil'; // Boleh disesuaikan jika perlu dinamik
    
    // Buka tetingkap/tab baharu yang menghala terus ke sistem Cetak PDF Rapor rasmi anda
    const cetakUrl = `${BASE_URL}/admin/cetak-rapor/printPDF/${siswaId}/${semester}`;
    
    // Buka di tab baru agar preview tidak tertutup
    window.open(cetakUrl, '_blank');
}