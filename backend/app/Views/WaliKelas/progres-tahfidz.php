<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= esc($title) ?> - Rapor Digital
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/WaliKelas/progres-tahfidz.css') ?>">
<style>
  :root {
    --warna-primary: <?= $color['warna_primary'] ?? '#10b981' ?>;
    --warna-secondary: <?= $color['warna_secondary'] ?? '#ecfdf5' ?>;
    --warna-scroll: <?= $color['warna_primary'] ?>; 
  }

  .text-tema { color: var(--warna-primary) !important; }
  .bg-tema { background-color: var(--warna-primary) !important; }
  .bg-tema-light { background-color: color-mix(in srgb, var(--warna-primary) 12%, transparent) !important; }
  .border-tema { border-color: var(--warna-primary) !important; }
  .focus-tema:focus {
    border-color: var(--warna-primary) !important;
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--warna-primary) 20%, transparent) !important;
    outline: none;
  }

  /* Dark Mode Overrides */
  html.dark .text-tema { color: color-mix(in srgb, var(--warna-primary) 80%, white) !important; }
  html.dark .bg-tema-light { background-color: rgba(255, 255, 255, 0.05) !important; }
  html.dark .bg-white { background-color: #1e293b !important; border-color: #334155 !important; }
  html.dark .text-gray-800 { color: #f1f5f9 !important; }
  html.dark .text-gray-600 { color: #cbd5e1 !important; }
  html.dark .text-gray-500 { color: #94a3b8 !important; }
  html.dark .text-gray-400 { color: #64748b !important; }
  html.dark .bg-gray-50 { background-color: #0f172a !important; }
  html.dark .bg-gray-100 { background-color: #1e293b !important; }
  html.dark .border-gray-100, html.dark .border-gray-200 { border-color: #334155 !important; }
  html.dark .bg-blue-50 { background-color: rgba(59, 130, 246, 0.1) !important; }
  html.dark .bg-amber-50 { background-color: rgba(245, 158, 11, 0.1) !important; }
  html.dark .bg-green-50 { background-color: rgba(34, 197, 94, 0.1) !important; }
  html.dark .bg-purple-50 { background-color: rgba(168, 85, 247, 0.1) !important; }
  html.dark .bg-red-50 { background-color: rgba(239, 68, 68, 0.1) !important; }

  .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
  .custom-scrollbar::-webkit-scrollbar-thumb { background: var(--warna-primary); border-radius: 10px; }

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
<main id="mainContent" class="min-h-[70vh] p-4 lg:p-6 w-full">
  <div class="flex flex-col items-center justify-center py-32">
    <div class="animate-spin rounded-full h-12 w-12 border-b-4 mb-4 border-tema"></div>
    <span class="font-bold tracking-widest text-lg text-tema animate-pulse">Menyiapkan Data Hafalan...</span>
  </div>
</main>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<div id="modalRiwayat" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div id="modalBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0 duration-300" onclick="tutupModalRiwayat()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div id="modalPanel" class="relative transform overflow-hidden rounded-[2rem] bg-slate-50 dark:bg-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95 duration-300">
                
                <div class="bg-tema relative overflow-hidden px-8 py-6">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white rounded-full opacity-10 blur-2xl"></div>
                    <div class="absolute -left-5 -bottom-5 w-24 h-24 bg-black rounded-full opacity-10 blur-xl"></div>
                    
                    <div class="relative z-10 flex justify-between items-start text-white">
                        <div class="flex items-center gap-5">
                            <div id="modalAvatar" class="w-16 h-16 rounded-2xl bg-white/20 border border-white/30 flex items-center justify-center text-white font-black text-2xl shadow-inner backdrop-blur-md overflow-hidden">
                            </div>
                            <div>
                                <h3 class="text-2xl font-extrabold tracking-tight mb-1" id="modalNamaSantri">Memuat Profil...</h3>
                                <p class="text-[11px] font-bold uppercase tracking-widest bg-black/10 inline-block px-3 py-1 rounded-lg backdrop-blur-sm border border-white/10">10 Setoran Terakhir</p>
                            </div>
                        </div>
                        <button type="button" onclick="tutupModalRiwayat()" class="rounded-full p-2 text-white/70 hover:bg-white/20 hover:text-white transition-all outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>

                <div class="px-8 py-8 max-h-[65vh] overflow-y-auto custom-scrollbar bg-slate-50 dark:bg-slate-800 relative">
                    <div id="timelineContainer" class="relative border-l-2 border-slate-200/80 dark:border-slate-700 ml-4 space-y-8 pb-4">
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  const BASE_URL = '<?= rtrim(base_url(), '/') ?>';
  const serverTahfizData = <?= $tahfizData ?>;

  // IMPLEMENTASI RBAC: Kunci Fitur Update Modul Tahfidz (wali_karakter)
  const CAN_UPDATE = <?= has_permission('wali_karakter', 'update') ? 'true' : 'false' ?>;
</script>
<script src="<?= base_url('assets/js/WaliKelas/progres-tahfidz.js') ?>?v=<?= time() ?>"></script>
<?= $this->endSection() ?>