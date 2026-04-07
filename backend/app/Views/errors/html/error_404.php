<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= lang('Errors.pageNotFound') ?> - Rapor SMPIT</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
</head>

<body class="bg-pattern min-h-screen flex items-center justify-center p-4">

    <div class="max-w-2xl w-full bg-white rounded-[2rem] shadow-2xl p-8 md:p-12 text-center relative overflow-hidden border border-gray-100">
        <div class="relative z-10">
            <h1 class="text-3xl md:text-5xl font-black text-gray-900 mb-2 tracking-tight">404</h1>
            <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-4">Waduh! Halaman Tidak Ditemukan</h2>

            <div class="text-gray-500 font-medium mb-10 leading-relaxed px-2 md:px-8">
                <?php if (ENVIRONMENT !== 'production') : ?>
                    <div class="bg-red-50 text-red-600 p-5 rounded-2xl text-sm border border-red-100 mt-4 text-left overflow-auto shadow-inner">
                        <strong class="uppercase tracking-widest text-xs mb-2 block">Development Error Info:</strong>
                        <code class="font-mono"><?= nl2br(esc($message)) ?></code>
                    </div>                <?php endif; ?>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <button onclick="history.back()" class="w-full sm:w-auto px-8 py-3.5 bg-white border-2 border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 hover:border-gray-300 hover:text-gray-900 transition-all outline-none flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </button>
                <a href="<?= base_url() ?>" class="w-full sm:w-auto px-8 py-3.5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/30 outline-none flex items-center justify-center gap-2 transform hover:-translate-y-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
        </div>

    </div>

</body>

</html>