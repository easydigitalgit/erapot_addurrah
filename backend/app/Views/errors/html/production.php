<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title><?= lang('Errors.whoops') ?> - Rapor SMPIT</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .bg-mesh-error {
            background-color: #fff1f2;
            background-image: 
                radial-gradient(at 0% 0%, hsla(350, 100%, 95%, 1) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(350, 100%, 90%, 1) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(350, 100%, 95%, 1) 0, transparent 50%);
        }

        .shake {
            animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
            transform: translate3d(0, 0, 0);
        }

        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>

<body class="bg-mesh-error min-h-screen flex items-center justify-center p-6 sm:p-12 overflow-hidden">
    <!-- Abstract Shapes -->
    <div class="fixed top-[-10%] right-[-10%] w-[50%] h-[50%] bg-rose-100/50 rounded-full blur-[120px] z-0"></div>
    <div class="fixed bottom-[-10%] left-[-10%] w-[50%] h-[50%] bg-orange-100/30 rounded-full blur-[120px] z-0"></div>

    <div class="relative z-10 max-w-xl w-full">
        <!-- Decoration Dots -->
        <div class="absolute -top-6 -right-6 grid grid-cols-3 gap-2 opacity-20">
            <?php for($i=0; $i<9; $i++): ?>
                <div class="w-2 h-2 bg-rose-500 rounded-full"></div>
            <?php endfor; ?>
        </div>

        <div class="glass p-12 rounded-[3rem] shadow-[0_25px_60px_rgba(244,63,94,0.1)] text-center relative overflow-hidden border border-white/50">
            <!-- Alert Icon -->
            <div class="mb-10 relative inline-flex">
                <div class="absolute inset-0 bg-rose-500 blur-3xl opacity-20 floating"></div>
                <div class="relative bg-rose-50 p-6 rounded-full shadow-inner border border-rose-100 shake">
                    <svg class="w-16 h-16 text-rose-600" fill="none" stroke="currentColor" stroke-width="1.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
            </div>

            <h1 class="text-4xl font-extrabold text-slate-900 mb-4 tracking-tight leading-tight">Terjadi Kesalahan Sistem</h1>
            
            <p class="text-slate-500 font-medium mb-12 leading-relaxed mx-auto max-w-sm text-lg">
                Mohon maaf, server kami sedang mengalami gangguan teknis. Tim IT kami telah dinotifikasi.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <button onclick="window.location.reload()" class="w-full sm:w-auto px-10 py-4 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition-all shadow-xl shadow-slate-900/20 flex items-center justify-center gap-2 group transform hover:-translate-y-1">
                    <svg class="w-5 h-5 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Muat Ulang
                </button>
                <a href="<?= base_url() ?>" class="w-full sm:w-auto px-10 py-4 bg-white border border-slate-200 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 hover:text-slate-900 transition-all shadow-sm flex items-center justify-center gap-2">
                    Beranda
                </a>
            </div>

            <div class="mt-14 pt-8 border-t border-slate-100 flex flex-col items-center gap-4">
                <p class="text-slate-400 text-xs font-semibold tracking-widest uppercase">
                    Status: Internal Server Error (500)
                </p>
                <div class="flex items-center gap-3">
                    <span class="w-2 h-2 bg-rose-500 rounded-full animate-pulse"></span>
                    <span class="text-slate-400 text-[10px] font-bold">MONITORING ACTIVE</span>
                </div>
            </div>
        </div>

        <div class="mt-10 text-center">
            <p class="text-slate-400 text-sm font-medium">
                &copy; <?= date('Y') ?> Rapor Digital SMPIT - Infrastructure Integrity
            </p>
        </div>
    </div>
</body>

</html>
