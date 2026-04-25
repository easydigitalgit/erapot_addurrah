<!DOCTYPE html>
<html lang="<?= $locale ?? 'id' ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= lang('Errors.pageNotFound') ?> - Rapor SMPIT</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .bg-mesh {
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 0% 0%, hsla(161, 71%, 95%, 1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(161, 71%, 90%, 1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(161, 71%, 95%, 1) 0, transparent 50%);
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>

<body class="bg-mesh min-h-screen flex items-center justify-center p-6 sm:p-12 overflow-hidden">
    <!-- Abstract Shapes -->
    <div class="fixed top-[-10%] left-[-10%] w-[40%] h-[40%] bg-emerald-100/50 rounded-full blur-[100px] z-0"></div>
    <div class="fixed bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-emerald-200/30 rounded-full blur-[100px] z-0"></div>

    <div class="relative z-10 max-w-xl w-full">
        <!-- Decoration -->
        <div class="absolute -top-12 -left-12 w-24 h-24 bg-emerald-500/10 rounded-full floating" style="animation-delay: 0.5s;"></div>
        <div class="absolute -bottom-10 -right-10 w-32 h-32 bg-emerald-500/10 rounded-full floating" style="animation-delay: 0s;"></div>

        <div class="glass p-12 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] text-center relative overflow-hidden">
            <!-- Header Icon -->
            <div class="mb-8 relative inline-flex">
                <div class="absolute inset-0 bg-emerald-500 blur-2xl opacity-20 floating"></div>
                <div class="relative bg-white p-6 rounded-3xl shadow-sm border border-emerald-50">
                    <svg class="w-16 h-16 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
            </div>

            <h1 class="text-7xl font-black text-slate-900 mb-4 tracking-tighter">404</h1>
            <h2 class="text-2xl font-bold text-slate-800 mb-4 px-4">Halaman Tidak Ditemukan</h2>
            
            <p class="text-slate-500 font-medium mb-10 leading-relaxed mx-auto max-w-sm">
                Sepertinya Anda tersesat di koridor digital. Halaman yang Anda cari tidak ada atau telah berpindah alamat.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <button onclick="history.back()" class="w-full sm:w-auto px-10 py-4 bg-white border border-slate-200 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 hover:text-slate-900 transition-all shadow-sm flex items-center justify-center gap-2 group">
                    <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </button>
                <a href="<?= base_url() ?>" class="w-full sm:w-auto px-10 py-4 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-700 transition-all shadow-[0_10px_20px_rgba(16,185,129,0.3)] flex items-center justify-center gap-2 transform hover:scale-[1.02] active:scale-[0.98]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Beranda
                </a>
            </div>

            <?php if (ENVIRONMENT !== 'production') : ?>
                <div class="mt-10 p-6 bg-slate-100/50 rounded-2xl text-left border border-slate-200 backdrop-blur-sm shadow-inner group">
                    <div class="flex items-center gap-2 mb-3 text-slate-400">
                        <div class="w-2 h-2 bg-rose-500 rounded-full animate-pulse"></div>
                        <span class="text-[10px] font-bold uppercase tracking-[0.2em]">Dev Info Only</span>
                    </div>
                    <code class="text-xs font-mono text-slate-600 block leading-relaxed break-all">
                        <?= nl2br(esc($message)) ?>
                    </code>
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-8 text-center">
            <p class="text-slate-400 text-sm font-medium">
                &copy; <?= date('Y') ?> Rapor Digital SMPIT - Pendidikan Berkarakter
            </p>
        </div>
    </div>
</body>

</html>