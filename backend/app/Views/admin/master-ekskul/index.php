<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Master Ekstrakurikuler - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
  :root { 
    --warna-utama: <?= $color['warna_primary'] ?? '#10b981' ?>; 
    --warna-scroll: <?= $color['warna_primary'] ?>; 
  }
  .text-dinamis { color: var(--warna-utama) !important; }
  .bg-dinamis { background-color: var(--warna-utama) !important; }

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
  <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
    <span>Master Data</span>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    <span class="font-bold text-dinamis">Ekstrakurikuler</span>
  </div>
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
      <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Master Ekstrakurikuler</h1>
      <p class="text-slate-500 text-sm mt-1">Kelola daftar ekstrakurikuler yang tersedia di sekolah.</p>
    </div>
    <div>
        <button onclick="showModal()" class="px-4 py-2.5 bg-dinamis text-white font-bold rounded-xl shadow-lg hover:brightness-110 transition-all flex items-center gap-2 outline-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Tambah Ekskul
        </button>
    </div>
  </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm transition-colors">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            </div>
        </div>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1">Total Ekstrakurikuler</p>
        <h3 id="statTotal" class="text-2xl font-bold text-gray-800 dark:text-white">0</h3>
    </div>

    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm transition-colors">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1">Ekskul Aktif</p>
        <h3 id="statAktif" class="text-2xl font-bold text-gray-800 dark:text-white">0</h3>
    </div>

    <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl border border-gray-100 dark:border-slate-700 shadow-sm transition-colors">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 rounded-xl bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center">
                <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
        </div>
        <p class="text-sm font-medium text-gray-500 dark:text-slate-400 mb-1">Ekskul Nonaktif</p>
        <h3 id="statNonaktif" class="text-2xl font-bold text-gray-800 dark:text-white">0</h3>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden mb-6">
  <div class="overflow-x-auto w-full max-h-[400px] custom-table-scroll">
    <table class="w-full text-left border-collapse whitespace-nowrap">
      <thead class="bg-slate-50 dark:bg-slate-900/80 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-wider sticky top-0 z-10">
        <tr>
          <th class="p-4 font-bold text-center w-12 border-b border-slate-200 dark:border-slate-700">No</th>
          <th class="p-4 font-bold border-b border-slate-200 dark:border-slate-700">Nama Ekstrakurikuler</th>
          <th class="p-4 font-bold text-center border-b border-slate-200 dark:border-slate-700">Peserta Aktif</th>
          <th class="p-4 font-bold text-center border-b border-slate-200 dark:border-slate-700">Status</th>
          <th class="p-4 font-bold text-center w-24 border-b border-slate-200 dark:border-slate-700">Aksi</th>
        </tr>
      </thead>
      <tbody id="tableBody" class="divide-y divide-slate-100 dark:divide-slate-700 text-sm text-slate-700 dark:text-slate-300">
        <tr>
            <td colspan="5" class="p-8 text-center text-slate-400">
                <div class="animate-spin w-8 h-8 border-4 border-slate-200 border-t-emerald-500 rounded-full mx-auto mb-2"></div>
                Memuat data...
            </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
    
    <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 transition-colors">
        <div class="flex items-center justify-between mb-5 border-b border-slate-100 dark:border-slate-700 pb-4">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                Top Ekskul Terfavorit
            </h3>
        </div>
        <div id="topEkskulContainer" class="space-y-5">
            <div class="text-center text-slate-400 text-sm py-4">Memuat data statistik...</div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-3xl p-6 shadow-sm text-white relative overflow-hidden">
        <div class="absolute -right-6 -top-6 opacity-10">
            <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
        </div>
        <h3 class="text-lg font-bold mb-2 relative z-10 flex items-center gap-2">
            <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
            Panduan Pintar
        </h3>
        <p class="text-xs text-slate-300 mb-5 relative z-10 leading-relaxed">Pahami aturan pengelolaan ekstrakurikuler agar sistem Rapor berjalan maksimal.</p>
        <ul class="space-y-4 text-sm text-slate-200 relative z-10">
            <li class="flex items-start gap-3">
                <div class="mt-0.5 w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></div>
                <span class="leading-snug"><b class="text-white">Status Nonaktif:</b> Ekskul yang diset Nonaktif akan disembunyikan secara otomatis dari daftar pilihan input nilai Wali Kelas.</span>
            </li>
            <li class="flex items-start gap-3">
                <div class="mt-0.5 w-1.5 h-1.5 rounded-full bg-blue-400 shrink-0"></div>
                <span class="leading-snug"><b class="text-white">Peserta Aktif:</b> Angka ini dihitung secara <i>realtime</i> berdasarkan jumlah siswa yang telah diberikan nilai ekskul pada semester ini.</span>
            </li>
            <li class="flex items-start gap-3">
                <div class="mt-0.5 w-1.5 h-1.5 rounded-full bg-rose-400 shrink-0"></div>
                <span class="leading-snug"><b class="text-white">Hapus Data:</b> Menghapus ekskul akan menghilangkan semua riwayat penilaian siswa pada ekskul tersebut. Lakukan hanya jika data tersebut salah input.</span>
            </li>
        </ul>
    </div>
    
</div>

<div id="formModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-2xl w-full max-w-lg my-auto transform scale-95 opacity-0 transition-all duration-300 flex flex-col" id="modalContent">
    <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center bg-slate-50 dark:bg-slate-900/50 rounded-t-3xl">
      <h3 class="text-xl font-bold text-slate-800 dark:text-white" id="modalTitle">Tambah Ekstrakurikuler</h3>
      <button onclick="closeModal()" class="text-slate-400 hover:text-rose-500 transition-colors outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
    </div>
    
    <div class="p-6">
      <form id="dataForm" class="space-y-5">
        <input type="hidden" id="item_id" name="id">
        <div>
            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Nama Ekstrakurikuler <span class="text-rose-500">*</span></label>
            <input type="text" name="nama_ekskul" id="nama_ekskul" required placeholder="Contoh: Pramuka" class="w-full p-3 border border-slate-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-dinamis dark:bg-slate-800 dark:text-white text-sm">
        </div>
        <div>
            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Status <span class="text-rose-500">*</span></label>
            <select name="status" id="status" required class="w-full p-3 border border-slate-200 dark:border-slate-600 rounded-xl outline-none focus:ring-2 focus:ring-dinamis dark:bg-slate-800 dark:text-white text-sm cursor-pointer">
              <option value="Aktif">Aktif</option>
              <option value="Nonaktif">Nonaktif</option>
            </select>
        </div>
      </form>
    </div>

    <div class="p-6 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 flex justify-end gap-3 rounded-b-3xl">
        <button type="button" onclick="closeModal()" class="px-5 py-2.5 text-slate-600 dark:text-slate-300 font-bold bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 rounded-xl transition-colors outline-none">Batal</button>
        <button type="submit" form="dataForm" id="btnSave" class="px-8 py-2.5 text-white font-bold bg-dinamis hover:brightness-110 rounded-xl shadow-lg transition-transform hover:-translate-y-0.5 flex items-center gap-2 outline-none">Simpan</button>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const API_URL = "<?= base_url('admin/master-ekskul') ?>";
    let allData = [];

    document.addEventListener('DOMContentLoaded', loadData);

    function loadData() {
        const tbody = document.getElementById('tableBody');
        
        fetch(`${API_URL}/get-data`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json())
        .then(res => {
            if(res.status === 'success') {
                allData = res.data;
                renderTable();
                renderTopEkskul(); // Panggil fungsi render grafik
                
                if(res.stats) {
                    document.getElementById('statTotal').innerText = res.stats.total;
                    document.getElementById('statAktif').innerText = res.stats.aktif;
                    document.getElementById('statNonaktif').innerText = res.stats.nonaktif;
                }
            } else {
                tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-rose-500 font-bold">${res.message}</td></tr>`;
            }
        })
        .catch(err => {
            tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-rose-500 font-bold">Koneksi ke server terputus. Silakan Refresh (F5).</td></tr>`;
        });
    }

    function renderTable() {
        const tbody = document.getElementById('tableBody');
        if (allData.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-slate-400 font-medium">Belum ada data Ekstrakurikuler.</td></tr>`;
            return;
        }

        let html = '';
        allData.forEach((item, index) => {
            const badge = item.status === 'Aktif' ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-rose-100 text-rose-700 border-rose-200';
            const totalSiswa = item.total_siswa || 0;
            
            html += `
            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors border-b border-slate-100 dark:border-slate-700/50 group">
                <td class="p-4 text-center font-medium">${index + 1}</td>
                <td class="p-4 font-bold text-slate-800 dark:text-white text-base">${item.nama_ekskul}</td>
                <td class="p-4 text-center">
                    <span class="px-3 py-1 bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400 font-bold rounded-lg border border-blue-200 dark:border-blue-800/50">
                        ${totalSiswa} Siswa
                    </span>
                </td>
                <td class="p-4 text-center"><span class="px-2.5 py-1 rounded-md text-xs font-bold border shadow-sm ${badge}">${item.status}</span></td>
                <td class="p-4 text-center">
                    <div class="flex justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button onclick='editData(${JSON.stringify(item).replace(/'/g, "&#39;")})' class="p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-colors outline-none" title="Edit"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                        <button onclick="deleteData(${item.id})" class="p-2 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors outline-none" title="Hapus"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                    </div>
                </td>
            </tr>`;
        });
        tbody.innerHTML = html;
    }

    // FITUR BARU: MENGGAMBAR GRAFIK PROGRESS BAR
    function renderTopEkskul() {
        const container = document.getElementById('topEkskulContainer');
        if (allData.length === 0) {
            container.innerHTML = `<div class="text-center text-slate-400 text-sm py-4">Belum ada data untuk ditampilkan.</div>`;
            return;
        }

        // Urutkan data berdasarkan total siswa terbanyak dan ambil 5 teratas
        let sorted = [...allData].sort((a, b) => b.total_siswa - a.total_siswa).slice(0, 5);
        let maxVal = sorted[0].total_siswa || 1; // Menghindari pembagian dengan 0

        let html = '';
        sorted.forEach((item, index) => {
            let percentage = (item.total_siswa / maxVal) * 100;
            if(item.total_siswa === 0) percentage = 0; // Tampilan jika kosong

            // Memberikan warna berbeda untuk juara 1, 2, dan 3
            let colorClass = 'bg-slate-400 dark:bg-slate-600';
            if(index === 0 && item.total_siswa > 0) colorClass = 'bg-amber-500';
            else if(index === 1 && item.total_siswa > 0) colorClass = 'bg-blue-500';
            else if(index === 2 && item.total_siswa > 0) colorClass = 'bg-emerald-500';

            html += `
            <div class="mb-4 last:mb-0 group">
                <div class="flex justify-between text-sm mb-2">
                    <span class="font-bold text-slate-700 dark:text-slate-300 group-hover:text-dinamis transition-colors">${item.nama_ekskul}</span>
                    <span class="font-black text-slate-800 dark:text-white">${item.total_siswa} <span class="text-[10px] uppercase font-bold text-slate-400">Siswa</span></span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-900/50 rounded-full h-3 overflow-hidden shadow-inner">
                    <div class="${colorClass} h-3 rounded-full transition-all duration-1000 ease-out relative" style="width: ${percentage}%">
                        ${percentage > 0 ? '<div class="absolute top-0 right-0 bottom-0 w-8 bg-white opacity-20 -skew-x-12"></div>' : ''}
                    </div>
                </div>
            </div>`;
        });
        container.innerHTML = html;
    }

    const modal = document.getElementById('formModal');
    const modalContent = document.getElementById('modalContent');
    const form = document.getElementById('dataForm');

    window.showModal = function() {
        form.reset();
        document.getElementById('item_id').value = '';
        document.getElementById('modalTitle').innerText = 'Tambah Ekstrakurikuler';
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

    window.editData = function(item) {
        document.getElementById('item_id').value = item.id;
        document.getElementById('nama_ekskul').value = item.nama_ekskul;
        document.getElementById('status').value = item.status;
        
        document.getElementById('modalTitle').innerText = 'Edit Ekstrakurikuler';
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    };

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = document.getElementById('btnSave');
        const originalText = btn.innerHTML;
        btn.innerHTML = 'Menyimpan...';
        btn.disabled = true;

        const formData = new FormData(form);
        const id = formData.get('id');
        const url = id ? `${API_URL}/update` : `${API_URL}/store`;

        try {
            const res = await fetch(url, { method: 'POST', body: formData, headers: {'X-Requested-With': 'XMLHttpRequest'} });
            const json = await res.json();
            
            if (json.status === 'success') {
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: json.message, showConfirmButton: false, timer: 1500, customClass:{popup:'rounded-3xl'} });
                closeModal();
                loadData(); // Menarik data dan meng-update Kartu Statistik & Grafik
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal!', text: json.message, customClass:{popup:'rounded-3xl'} });
            }
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Koneksi ke server gagal.', customClass:{popup:'rounded-3xl'} });
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    });

    window.deleteData = function(id) {
        Swal.fire({
            title: 'Hapus Ekskul Ini?',
            text: "Data tidak dapat dikembalikan!",
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
                try {
                    const res = await fetch(`${API_URL}/delete`, { method: 'POST', body: formData, headers: {'X-Requested-With': 'XMLHttpRequest'} });
                    const json = await res.json();
                    if (json.status === 'success') {
                        Swal.fire({ icon: 'success', title: 'Dihapus!', text: json.message, showConfirmButton: false, timer: 1500, customClass:{popup:'rounded-3xl'} });
                        loadData(); // Menarik data dan meng-update Kartu Statistik & Grafik
                    }
                } catch (err) {}
            }
        });
    };
</script>
<?= $this->endSection() ?>