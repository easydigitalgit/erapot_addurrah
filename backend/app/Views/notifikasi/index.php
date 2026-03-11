<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php use CodeIgniter\I18n\Time; ?>

<div class="p-4 md:p-6 lg:p-8 h-[calc(100vh-80px)] max-h-screen flex flex-col">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Kotak Masuk</h1>
            <p class="text-sm text-gray-500 mt-1">Pemberitahuan Sistem Rapor Digital</p>
        </div>
        
        <button onclick="markAllAsRead()" style="color: <?= $color['warna_primary'] ?? '#1F7A4D' ?>;" class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 text-sm font-semibold rounded-xl shadow-sm hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        Tandai Semua Dibaca
    </button>
    </div>

    <div class="flex flex-1 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden relative">
        
        <div id="pesan-list" class="w-full md:w-1/3 lg:w-[35%] flex flex-col border-r border-gray-200 dark:border-slate-700 h-full">
            
            <div class="p-4 border-b border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-800/50 shrink-0">
                <h3 class="font-bold text-gray-700 dark:text-gray-200">Daftar Notifikasi</h3>
            </div>

            <div class="overflow-y-auto h-full custom-scrollbar">
                <?php if(empty($notifikasi)): ?>
                    <div class="p-8 text-center text-gray-400">Belum ada pesan.</div>
                <?php else: ?>
                    <div class="divide-y divide-gray-100 dark:divide-slate-700">
                        <?php foreach($notifikasi as $notif): ?>
                            <?php 
                                $iconColor = 'text-blue-600'; $bgColor = 'bg-blue-100'; $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
                                if ($notif['tipe'] == 'success') { $iconColor = 'text-emerald-600'; $bgColor = 'bg-emerald-100'; $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>'; }
                                elseif ($notif['tipe'] == 'warning') { $iconColor = 'text-amber-600'; $bgColor = 'bg-amber-100'; $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>'; }
                                elseif ($notif['tipe'] == 'error') { $iconColor = 'text-red-600'; $bgColor = 'bg-red-100'; $iconSvg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'; }
                                
                                $waktu = Time::parse($notif['created_at'])->humanize();
                                $waktuLengkap = date('d M Y, H:i', strtotime($notif['created_at']));
                                $link = empty($notif['link']) ? '#' : base_url($notif['link']);
                            ?>
                            
                            <button onclick="bacaPesan(<?= $notif['id'] ?>, this)" 
                                    data-judul="<?= esc($notif['judul']) ?>" 
                                    data-pesan="<?= esc($notif['pesan']) ?>" 
                                    data-waktu="<?= $waktuLengkap ?>" 
                                    data-ikon="<?= htmlspecialchars($iconSvg) ?>" 
                                    data-warna="<?= $iconColor ?>" 
                                    data-bg="<?= $bgColor ?>" 
                                    data-link="<?= $link ?>"
                                    class="pesan-item w-full text-left p-4 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors focus:bg-blue-50 focus:outline-none <?= $notif['is_read'] ? 'bg-white dark:bg-slate-800' : 'bg-blue-50/40 dark:bg-slate-700/50' ?>">
                                <div class="flex gap-3">
                                    <div class="w-10 h-10 rounded-full <?= $bgColor ?> flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 <?= $iconColor ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24"><?= $iconSvg ?></svg>
                                    </div>
                                    <div class="flex-1 overflow-hidden">
                                        <div class="flex justify-between items-start mb-0.5">
                                            <h4 class="font-semibold text-gray-800 dark:text-white text-sm truncate pr-2 <?= $notif['is_read'] ? '' : 'font-bold' ?>"><?= esc($notif['judul']) ?></h4>
                                            <span class="text-[10px] font-medium <?= $notif['is_read'] ? 'text-gray-400' : 'text-blue-500 font-bold' ?> shrink-0"><?= $waktu ?></span>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate"><?= esc($notif['pesan']) ?></p>
                                    </div>
                                </div>
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div id="pesan-detail" class="hidden md:flex flex-col w-full md:w-2/3 lg:w-[65%] bg-gray-50/30 dark:bg-slate-800 h-full absolute md:relative z-10 top-0 left-0">
            
            <div id="detail-kosong" class="flex flex-col items-center justify-center h-full text-center p-8">
                <svg class="w-24 h-24 text-gray-200 dark:text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"></path></svg>
                <h3 class="text-xl font-bold text-gray-400 dark:text-slate-500">Pilih pesan untuk dibaca</h3>
                <p class="text-sm text-gray-400 mt-2">Klik salah satu notifikasi di sebelah kiri untuk melihat detailnya di sini.</p>
            </div>

            <div id="detail-isi" class="hidden flex-col h-full bg-white dark:bg-slate-800">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center gap-4 bg-white dark:bg-slate-800 shrink-0">
                    <button onclick="tutupDetail()" class="md:hidden p-2 -ml-2 rounded-full hover:bg-gray-100 text-gray-600 focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    </button>
                    <h3 class="font-bold text-gray-800 dark:text-white flex-1" id="detail-judul">Memuat Judul...</h3>
                    <span class="text-xs text-gray-500" id="detail-waktu">Memuat waktu...</span>
                </div>

                <div class="p-6 md:p-8 overflow-y-auto custom-scrollbar flex-1">
                    <div class="flex items-start gap-4 mb-6">
                        <div id="detail-ikon-wadah" class="w-12 h-12 rounded-full flex items-center justify-center shrink-0">
                            <svg id="detail-ikon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800 dark:text-white">Sistem Rapor Digital</p>
                            <p class="text-xs text-gray-500">ke Saya</p>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 dark:bg-slate-700/50 p-6 rounded-2xl border border-gray-100 dark:border-slate-600">
                        <p class="text-gray-700 dark:text-gray-200 leading-relaxed whitespace-pre-line" id="detail-teks">
                            Isi pesan akan tampil di sini...
                        </p>
                    </div>

                    <div id="detail-aksi" class="mt-8 hidden">
                        <a href="#" id="detail-link" style="background-color: <?= $color['warna_primary'] ?? '#1F7A4D' ?>; color: #ffffff;" class="inline-flex items-center gap-2 px-6 py-3 hover:brightness-95 font-semibold rounded-xl transition-all shadow-md">
                            Cek Sekarang
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Fungsi untuk memanggil AJAX API Tandai Semua
async function markAllAsRead() {
    try {
        const response = await fetch('<?= base_url('notifikasi/mark-all-read') ?>', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const result = await response.json();
        if(result.status === 'success') window.location.reload();
    } catch (error) {
        alert("Terjadi kesalahan sistem.");
    }
}

// FUNGSI GMAIL LAYOUT: Menampilkan Pesan di Sebelah Kanan
function bacaPesan(id, el) {
    // 1. Ambil data dari atribut HTML tombol yang diklik
    const judul = el.getAttribute('data-judul');
    const pesan = el.getAttribute('data-pesan');
    const waktu = el.getAttribute('data-waktu');
    const ikon = el.getAttribute('data-ikon');
    const bg = el.getAttribute('data-bg');
    const warna = el.getAttribute('data-warna');
    const link = el.getAttribute('data-link');

    // 2. Masukkan data ke panel Kanan (Detail)
    document.getElementById('detail-kosong').classList.add('hidden');
    document.getElementById('detail-isi').classList.remove('hidden');
    document.getElementById('detail-isi').classList.add('flex');

    document.getElementById('detail-judul').textContent = judul;
    document.getElementById('detail-teks').textContent = pesan;
    document.getElementById('detail-waktu').textContent = waktu;
    
    const ikonWadah = document.getElementById('detail-ikon-wadah');
    ikonWadah.className = `w-12 h-12 rounded-full flex items-center justify-center shrink-0 ${bg}`;
    
    const ikonSvg = document.getElementById('detail-ikon');
    ikonSvg.className = `w-6 h-6 ${warna}`;
    ikonSvg.innerHTML = ikon;

    const aksiBtn = document.getElementById('detail-aksi');
    if (link !== '#') {
        aksiBtn.classList.remove('hidden');
        document.getElementById('detail-link').href = link;
    } else {
        aksiBtn.classList.add('hidden');
    }

    // 3. UI Updates (Hapus indikator bold & biru karena sudah dibaca)
    document.querySelectorAll('.pesan-item').forEach(item => item.classList.remove('bg-blue-50/50', 'border-l-4', 'border-blue-500'));
    el.classList.remove('bg-blue-50/40', 'dark:bg-slate-700/50');
    el.classList.add('bg-white', 'dark:bg-slate-800', 'border-l-4', 'border-blue-500'); // Efek sedang aktif/fokus
    
    // Hapus tebal di judul & ubah waktu jadi abu-abu
    const titleEl = el.querySelector('h4');
    const timeEl = el.querySelector('span');
    if(titleEl) titleEl.classList.remove('font-bold');
    if(timeEl) {
        timeEl.classList.remove('text-blue-500', 'font-bold');
        timeEl.classList.add('text-gray-400');
    }

    // 4. Untuk HP/Mobile: Sembunyikan List, Tampilkan Detail
    if (window.innerWidth < 768) { // md breakpoint
        document.getElementById('pesan-list').classList.add('hidden');
        document.getElementById('pesan-detail').classList.remove('hidden');
        document.getElementById('pesan-detail').classList.add('flex');
    }

    // 5. Tembak API AJAX ke Backend untuk update `is_read = 1` tanpa reload!
    fetch(`<?= base_url('notifikasi/mark-read/') ?>${id}`, { 
        method: 'POST', 
        headers: { 'X-Requested-With': 'XMLHttpRequest' } 
    });
}

// Untuk HP/Mobile: Tombol kembali dari Detail ke List
function tutupDetail() {
    document.getElementById('pesan-detail').classList.add('hidden');
    document.getElementById('pesan-detail').classList.remove('flex');
    document.getElementById('pesan-list').classList.remove('hidden');
}
</script>

<?= $this->endSection() ?>