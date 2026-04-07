<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Input Nilai Tahfidz - Wali Kelas
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<meta name="X-CSRF-TOKEN" content="<?= csrf_hash() ?>">
<style>
  :root {
    --warna-scroll: <?= $color['warna_primary'] ?>; 
  }

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
<div class="card mb-6 bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 p-6 shadow-sm transition-colors">
    <div class="flex flex-col md:flex-row justify-between items-center gap-5">
        <div class="w-full md:w-1/3">
            <label class="block text-[11px] font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2 transition-colors">Filter Berdasarkan Juz</label>
            <select id="filterJuz" class="w-full px-4 py-3 border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-gray-800 dark:text-white rounded-xl focus:ring-2 focus:ring-emerald-500 transition-colors outline-none cursor-pointer shadow-sm">
                <?php for($j=1; $j<=30; $j++): ?>
                    <option value="<?= $j ?>" <?= $j == 30 ? 'selected' : '' ?>>Juz <?= $j ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="text-right">
            <p class="text-xs text-gray-500 dark:text-slate-400 italic">* Data nilai diinput oleh Guru Tahfizh</p>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700 p-6 shadow-sm overflow-hidden transition-colors mb-20">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead class="bg-gray-100/50 dark:bg-slate-900/50 text-gray-500 dark:text-slate-400 text-[10px] uppercase tracking-wider border-b border-gray-200 dark:border-slate-600">
                <tr>
                    <th class="p-4 font-black text-center w-12">No</th>
                    <th class="p-4 font-black">Peserta Didik</th>
                    <th class="p-4 font-black text-center">Nilai Teori (NAT)</th>
                    <th class="p-4 font-black text-center">Hasil Setoran (NHAS)</th>
                    <th class="p-4 font-black text-center">Total Rata-Rata</th>
                    <th class="p-4 font-black text-center">Harkat / Taqdir</th>
                    <th class="p-4 font-black text-center w-24">Cetak Rapor</th>
                </tr>
            </thead>
            <tbody id="studentTableBody" class="divide-y divide-gray-100 dark:divide-slate-700 text-sm">
                <tr><td colspan="7" class="p-12 text-center text-gray-400">Silakan pilih Juz untuk memuat data...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', loadData);
    if(document.getElementById('filterJuz')) {
        document.getElementById('filterJuz').addEventListener('change', loadData);
    }

    function loadData() {
        const tbody = document.getElementById('studentTableBody');
        const juz = document.getElementById('filterJuz') ? document.getElementById('filterJuz').value : 30;
        tbody.innerHTML = '<tr><td colspan="5" class="p-8 text-center text-gray-500 animate-pulse">Memuat data...</td></tr>';
        
        fetch(`<?= base_url('wali/tahfidz/get-data') ?>?juz=${juz}`)
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    renderTable(res.data);
                }
            });
    }

    function renderTable(data) {
        const tbody = document.getElementById('studentTableBody');
        const juz = document.getElementById('filterJuz') ? document.getElementById('filterJuz').value : 30;
        let html = '';
        data.forEach((s, i) => {
            const avg = parseFloat(s.nilai_rata_rata || 0).toFixed(1);
            html += `
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors">
                    <td class="p-4 text-center text-gray-500 font-medium">${i + 1}</td>
                    <td class="p-4">
                        <div class="font-bold text-gray-900 dark:text-white mb-0.5">${s.nama_lengkap}</div>
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">${s.nis}</div>
                    </td>
                    <td class="p-4 text-center">
                        <span class="font-black text-blue-600 dark:text-blue-400 text-base">
                            ${parseFloat(s.nilai_teori || 0).toFixed(1)}
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <span class="font-black text-emerald-600 dark:text-emerald-400 text-base">
                            ${parseFloat(s.nilai_setoran || 0).toFixed(1)}
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <div class="px-3 py-1 bg-gray-50 dark:bg-slate-700/50 rounded-lg inline-block font-black text-slate-700 dark:text-slate-300">
                            ${avg}
                        </div>
                    </td>
                    <td class="p-4 text-center">
                        <span class="px-3 py-1 bg-gray-100 dark:bg-slate-700 text-gray-700 dark:text-slate-300 rounded-full text-[10px] font-black uppercase tracking-wider">
                            ${s.taqdir || '-'}
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <button type="button" onclick="openIframePreview('<?= base_url('wali/tahfidz/cetak-rapor') ?>/${s.id}?juz=${juz}', '${s.nama_lengkap}')" class="p-2 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-xl hover:bg-emerald-100 transition-all border border-emerald-100 dark:border-emerald-800/50 shadow-sm" title="Cetak Rapor">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
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
        
        document.getElementById('previewSiswaName').textContent = `Menampilkan Rapor: ${studentName}`;
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
    <div class="modal-overlay absolute inset-0 bg-slate-900/80 backdrop-blur-sm" onclick="closePreviewKertas()"></div>
    <div class="relative bg-gray-200 dark:bg-slate-900 rounded-3xl shadow-2xl w-full max-w-5xl mx-auto overflow-hidden flex flex-col h-[90vh] transform scale-95 transition-all duration-300" id="modalPreviewContent">
        <div class="bg-white dark:bg-slate-800 px-6 py-4 flex justify-between items-center border-b border-gray-300 dark:border-slate-700 shadow-sm z-10">
            <div>
                <h3 class="font-black text-lg text-gray-800 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    Pratinjau Rapor Tahfizh
                </h3>
                <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5" id="previewSiswaName">Memuat pratinjau...</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="closePreviewKertas()" class="text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 p-2 rounded-xl transition-colors outline-none border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-800">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
        <div class="flex-1 overflow-hidden bg-gray-200 dark:bg-slate-900 relative flex justify-center w-full h-full">
            <div id="iframeLoader" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-200/80 dark:bg-slate-900/80 backdrop-blur-sm z-20">
                <div class="animate-spin rounded-full h-14 w-14 border-b-4 border-blue-500 mx-auto mb-4"></div>
                <p class="text-gray-600 dark:text-gray-300 font-bold tracking-widest uppercase text-sm">Menyiapkan Dokumen...</p>
            </div>
            <div id="iframeContainer" class="w-full h-full shadow-2xl bg-white transition-all duration-500 flex justify-center"></div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
