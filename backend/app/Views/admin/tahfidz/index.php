<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Cetak Nilai Tahfizh - Admin
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    :root { --warna-scroll: <?= $color['warna_primary'] ?>; }

    ::-webkit-scrollbar {
      width: 6px;
    }
    
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }
    
    ::-webkit-scrollbar-thumb {
      background-color: var(--warna-scroll);
      border-radius: 3px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-8">
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-2 transition-colors">
        <span>Kurikulum</span>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        <span class="text-[<?= $color['warna_primary'] ?>] font-medium">Cetak Nilai Tahfizh</span>
    </div>
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-800 dark:text-white transition-colors">Manajemen Rapor Tahfizh</h1>
            <p class="text-sm md:text-base text-gray-600 dark:text-slate-400 mt-1 transition-colors">Otoritas pusat untuk memantau dan mencetak laporan capaian Al-Qur'an.</p>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
        <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
        </div>
        <p class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1">Total Peserta</p>
        <h3 class="text-2xl font-black text-gray-800 dark:text-white" id="statTotalSiswa">0</h3>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
        <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        </div>
        <p class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1">Rata-rata NAT</p>
        <h3 class="text-2xl font-black text-emerald-600 dark:text-emerald-400" id="statAvgNAT">0.0</h3>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
        <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
        </div>
        <p class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1">Rata-rata NHAS</p>
        <h3 class="text-2xl font-black text-purple-600 dark:text-purple-400" id="statAvgNHAS">0.0</h3>
    </div>
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow-sm border border-gray-100 dark:border-slate-700 transition-colors">
        <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.175 0l-3.976 2.888c-.783.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.382-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>
        </div>
        <p class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-1">Global Average</p>
        <h3 class="text-2xl font-black text-amber-600 dark:text-amber-400" id="statAvgGlobal">0.0</h3>
    </div>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 p-6 shadow-sm transition-colors mb-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors">Pilih Rombongan Belajar</label>
            <select id="filterRombel" class="w-full px-5 py-3.5 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-900/50 text-gray-800 dark:text-white rounded-2xl focus:ring-2 focus:ring-emerald-500 transition-all outline-none cursor-pointer appearance-none font-bold">
                <option value="">-- Pilih Kelas --</option>
                <?php foreach($list_rombel as $r): ?>
                    <option value="<?= $r['id'] ?>">Kelas <?= esc($r['tingkat']) ?> - <?= esc($r['nama_rombel']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-[11px] font-black text-gray-500 dark:text-slate-400 uppercase tracking-widest mb-2 transition-colors">Target Capaian (Juz)</label>
            <select id="filterJuz" class="w-full px-5 py-3.5 border border-gray-200 dark:border-slate-600 bg-gray-50 dark:bg-slate-900/50 text-gray-800 dark:text-white rounded-2xl focus:ring-2 focus:ring-emerald-500 transition-all outline-none cursor-pointer appearance-none font-bold">
                <?php for($j=1; $j<=30; $j++): ?>
                    <option value="<?= $j ?>" <?= $j == 30 ? 'selected' : '' ?>>Juz <?= $j ?></option>
                <?php endfor; ?>
            </select>
        </div>
    </div>
</div>

<!-- Table -->
<div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden transition-colors mb-20">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead class="bg-gray-100/50 dark:bg-slate-900/50 text-gray-500 dark:text-slate-400 text-[10px] uppercase tracking-wider border-b border-gray-200 dark:border-slate-600">
                <tr>
                    <th class="p-5 font-black text-center w-12 text-gray-400">#</th>
                    <th class="p-5 font-black">Peserta Didik / NIS</th>
                    <th class="p-5 font-black text-center">NAT (Teori)</th>
                    <th class="p-5 font-black text-center">NHAS (Setoran)</th>
                    <th class="p-5 font-black text-center">Rata-Rata</th>
                    <th class="p-5 font-black text-center">Harkat / Taqdir</th>
                    <th class="p-5 font-black text-center w-32">Opsi Cetak</th>
                </tr>
            </thead>
            <tbody id="studentTableBody" class="divide-y divide-gray-100 dark:divide-slate-700 text-sm">
                <tr><td colspan="7" class="p-20 text-center text-gray-400">Silakan pilih filter untuk menampilkan data...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.getElementById('filterRombel').addEventListener('change', loadData);
    document.getElementById('filterJuz').addEventListener('change', loadData);

    function loadData() {
        const rombel = document.getElementById('filterRombel').value;
        const juz = document.getElementById('filterJuz').value;
        const tbody = document.getElementById('studentTableBody');
        
        if(!rombel) return;

        tbody.innerHTML = '<tr><td colspan="7" class="p-20 text-center text-gray-500"><div class="animate-spin rounded-full h-10 w-10 border-b-2 border-emerald-500 mx-auto mb-4"></div>Memproses data...</td></tr>';
        
        fetch(`<?= base_url('admin/tahfidz/get-data') ?>?rombel=${rombel}&juz=${juz}`)
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    renderTable(res.data);
                    calculateStats(res.data);
                }
            });
    }

    function calculateStats(data) {
        if(data.length === 0) return;
        
        const total = data.length;
        const avgNAT = data.reduce((acc, curr) => acc + parseFloat(curr.nilai_teori || 0), 0) / total;
        const avgNHAS = data.reduce((acc, curr) => acc + parseFloat(curr.nilai_setoran || 0), 0) / total;
        const avgGlobal = data.reduce((acc, curr) => acc + parseFloat(curr.nilai_rata_rata || 0), 0) / total;

        document.getElementById('statTotalSiswa').textContent = total;
        document.getElementById('statAvgNAT').textContent = avgNAT.toFixed(1);
        document.getElementById('statAvgNHAS').textContent = avgNHAS.toFixed(1);
        document.getElementById('statAvgGlobal').textContent = avgGlobal.toFixed(1);
    }

    function renderTable(data) {
        const tbody = document.getElementById('studentTableBody');
        const juz = document.getElementById('filterJuz').value;
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="p-20 text-center text-gray-500 font-bold">Data tidak ditemukan untuk filter ini.</td></tr>';
            return;
        }

        let html = '';
        data.forEach((s, i) => {
            const taqdirColor = s.taqdir === 'Mumtaz' ? 'emerald' : (s.taqdir === 'Dhaif' ? 'red' : 'blue');
            html += `
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-900/40 transition-colors">
                    <td class="p-5 text-center text-gray-400 font-medium">${i + 1}</td>
                    <td class="p-5">
                        <div class="font-black text-gray-800 dark:text-white uppercase tracking-tight">${s.nama_lengkap}</div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">${s.nis}</div>
                    </td>
                    <td class="p-5 text-center">
                        <span class="font-black text-blue-600 dark:text-blue-400 text-lg">${parseFloat(s.nilai_teori || 0).toFixed(1)}</span>
                    </td>
                    <td class="p-5 text-center">
                        <span class="font-black text-emerald-600 dark:text-emerald-400 text-lg">${parseFloat(s.nilai_setoran || 0).toFixed(1)}</span>
                    </td>
                    <td class="p-5 text-center">
                        <div class="w-16 h-8 flex items-center justify-center bg-gray-100 dark:bg-slate-900 rounded-lg mx-auto font-black text-gray-700 dark:text-slate-300">
                            ${parseFloat(s.nilai_rata_rata || 0).toFixed(1)}
                        </div>
                    </td>
                    <td class="p-5 text-center">
                        <span class="px-3 py-1 bg-${taqdirColor}-100 dark:bg-${taqdirColor}-900/30 text-${taqdirColor}-700 dark:text-${taqdirColor}-400 rounded-full text-[10px] font-black uppercase tracking-wider border border-${taqdirColor}-200/50 dark:border-${taqdirColor}-800/50">
                            ${s.taqdir || '-'}
                        </span>
                    </td>
                    <td class="p-5 text-center">
                        <button onclick="openIframePreview('<?= base_url('admin/tahfidz/cetak-rapor') ?>/${s.id}?juz=${juz}', '${s.nama_lengkap}')" class="p-2.5 bg-white dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800/50 rounded-xl hover:bg-emerald-50 dark:hover:bg-emerald-900/30 transition-all shadow-sm group" title="Cetak Standar">
                             <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        </button>
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    }

    function openIframePreview(url, studentName) {
        const modal = document.getElementById('modalPreviewKertas');
        const iframeContainer = document.getElementById('iframeContainer');
        const loader = document.getElementById('iframeLoader');
        
        document.getElementById('previewSiswaName').textContent = `Sertifikat Rapor: ${studentName}`;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        loader.classList.remove('hidden');
        iframeContainer.innerHTML = `<iframe src="${url}" class="w-full h-full border-none" onload="hideIframeLoader()"></iframe>`;

        setTimeout(() => {
            document.getElementById('modalPreviewContent').classList.remove('scale-95');
        }, 10);
    }

    function hideIframeLoader() {
        document.getElementById('iframeLoader').classList.add('hidden');
    }

    function closePreviewKertas() {
        const modal = document.getElementById('modalPreviewKertas');
        document.getElementById('modalPreviewContent').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            document.getElementById('iframeContainer').innerHTML = '';
        }, 300);
    }
</script>

<div id="modalPreviewKertas" class="fixed inset-0 z-[100000] hidden flex items-center justify-center p-4 transition-opacity no-print">
    <div class="modal-overlay absolute inset-0 bg-slate-900/90 backdrop-blur-md" onclick="closePreviewKertas()"></div>
    <div class="relative bg-gray-100 dark:bg-slate-900 rounded-[2.5rem] shadow-2xl w-full max-w-6xl mx-auto overflow-hidden flex flex-col h-[92vh] transform scale-95 transition-all duration-300 border border-white/10" id="modalPreviewContent">
        <div class="bg-white dark:bg-slate-800 px-8 py-5 flex justify-between items-center border-b border-gray-200 dark:border-slate-700 shadow-sm z-10 transition-colors">
            <div>
                <h3 class="font-black text-xl text-gray-800 dark:text-white flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    Pratinjau Rapor Tahfizh
                </h3>
                <p class="text-xs text-gray-500 dark:text-slate-400 mt-1 font-bold" id="previewSiswaName">Memuat pratinjau...</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="window.frames[0].print()" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl shadow-lg shadow-emerald-500/20 transition-all active:scale-95 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak Sekarang
                </button>
                <button onclick="closePreviewKertas()" class="p-2.5 text-gray-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:text-red-500 rounded-2xl transition-all outline-none border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        <div class="flex-1 overflow-hidden bg-[#525659] relative flex justify-center w-full h-full shadow-inner">
            <div id="iframeLoader" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900/60 backdrop-blur-md z-20">
                <div class="w-20 h-20 relative">
                    <div class="absolute inset-0 border-4 border-emerald-500/20 rounded-full"></div>
                    <div class="absolute inset-0 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
                </div>
                <p class="text-white font-black tracking-widest uppercase text-xs mt-6 animate-pulse">Menyiapkan Laporan...</p>
            </div>
            <div id="iframeContainer" class="w-full h-full flex justify-center p-4"></div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
