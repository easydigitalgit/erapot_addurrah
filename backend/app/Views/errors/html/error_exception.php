<?php
use CodeIgniter\HTTP\Header;
use CodeIgniter\CodeIgniter;

$errorId = uniqid('error', true);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">

    <title><?= esc($title) ?> - Rapor SMPIT Debugger</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <style>
        <?= preg_replace('#[\r\n\t ]+#', ' ', file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'debug.css')) ?>
        
        body { font-family: 'Outfit', sans-serif; }
        code, pre, .font-mono { font-family: 'JetBrains Mono', monospace; }
        
        .tab-btn-active { 
            background: #ecfdf5 !important;
            color: #059669 !important;
            border-bottom: 3px solid #10b981 !important;
        }
        
        /* CustomScrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }

        .source-highlight {
            background: #1e293b;
            color: #e2e8f0;
            border-radius: 12px;
            padding: 1rem;
            font-size: 13px;
            line-height: 1.6;
            overflow: auto;
        }
        
        .line-highlight {
            background: rgba(244, 63, 94, 0.2);
            display: block;
            margin: 0 -1rem;
            padding: 0 1rem;
            border-left: 4px solid #f43f5e;
        }

        .tab-content-item { display: none; }
        .tab-content-item.active { display: block; }
    </style>

    <script>
        <?= file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'debug.js') ?>
        
        function switchTab(event, id) {
            document.querySelectorAll('.tab-content-item').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tab-btn').forEach(el => {
                el.classList.remove('tab-btn-active');
                el.classList.remove('text-emerald-600', 'bg-emerald-50', 'border-emerald-500');
                el.classList.add('text-slate-400', 'border-transparent');
            });
            
            document.getElementById(id).classList.add('active');
            event.currentTarget.classList.add('tab-btn-active');
            return false;
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen pb-20" onload="init()">

    <!-- Premium Sticky Header -->
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-[1600px] mx-auto px-6 py-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-rose-500 text-white rounded-2xl shadow-lg shadow-rose-500/20">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2 py-0.5 bg-rose-100 text-rose-600 text-[10px] font-black uppercase tracking-widest rounded-md">Exception</span>
                            <span class="text-slate-400 text-xs font-medium uppercase tracking-tighter">ID: <?= strtoupper($errorId) ?></span>
                        </div>
                        <h1 class="text-2xl font-extrabold tracking-tight text-slate-900"><?= esc($title) ?><?= esc($exception->getCode() ? ' #' . $exception->getCode() : '') ?></h1>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="px-4 py-2 bg-slate-100 rounded-xl border border-slate-200 text-center min-w-[80px]">
                        <span class="text-[10px] block text-slate-400 font-bold uppercase mb-0.5 tracking-wider">Env</span>
                        <span class="text-xs font-bold text-slate-700"><?= strtoupper(ENVIRONMENT) ?></span>
                    </div>
                    <div class="px-4 py-2 bg-slate-100 rounded-xl border border-slate-200 text-center min-w-[80px]">
                        <span class="text-[10px] block text-slate-400 font-bold uppercase mb-0.5 tracking-wider">CI</span>
                        <span class="text-xs font-bold text-slate-700"><?= esc(CodeIgniter::CI_VERSION) ?></span>
                    </div>
                    <div class="px-4 py-2 bg-slate-100 rounded-xl border border-slate-200 text-center min-w-[80px]">
                        <span class="text-[10px] block text-slate-400 font-bold uppercase mb-0.5 tracking-wider">PHP</span>
                        <span class="text-xs font-bold text-slate-700"><?= esc(PHP_VERSION) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-[1600px] mx-auto px-6 mt-8">
        <!-- Error Message Card -->
        <section class="mb-10">
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-8">
                    <h2 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span> Error Message
                    </h2>
                    <p class="text-xl md:text-2xl text-slate-700 leading-relaxed font-bold bg-slate-50 p-8 rounded-3xl border border-slate-100 border-dashed">
                        <?= nl2br(esc($exception->getMessage())) ?>
                    </p>
                    <div class="mt-6 flex items-center gap-4">
                        <a href="https://www.google.com/search?q=<?= urlencode('CodeIgniter 4 ' . $title . ' ' . $exception->getMessage()) ?>"
                           rel="noreferrer" target="_blank" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-slate-600 font-bold text-xs hover:border-emerald-500 hover:text-emerald-600 transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.48 10.92v3.28h7.84c-.24 1.84-.908 3.152-1.928 4.172-1.228 1.228-3.212 2.668-6.144 2.668-5.32 0-9.432-4.312-9.432-9.612s4.112-9.612 9.432-9.612c2.812 0 4.884 1.108 6.368 2.508l2.308-2.308c-2.4-2.28-5.592-3.608-8.676-3.608-7.392 0-13.484 6.092-13.484 13.484s6.092 13.484 13.484 13.484c4.12 0 7.828-1.376 10.052-3.712 2.224-2.336 3.192-5.184 3.192-7.512 0-.64-.048-1.28-.144-1.92h-12.836z"/></svg>
                            Search Google
                        </a>
                        <a href="https://stackoverflow.com/search?q=<?= urlencode('[codeigniter-4] ' . $exception->getMessage()) ?>"
                           rel="noreferrer" target="_blank" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-slate-600 font-bold text-xs hover:border-orange-500 hover:text-orange-600 transition-all shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.986 21.865v-6.404h2.134V24H1.844v-8.539h2.13v6.404h15.012zM6.111 19.731H14.745v-2.134H6.111v2.134zm.18-4.563l8.4 1.764.444-2.089-8.4-1.765-.444 2.09zm1.09-4.321l7.85 3.535.882-1.956-7.85-3.536-.882 1.957zm2.464-3.708l6.732 5.378 1.322-1.656-6.733-5.378-1.321 1.656zm4.184-2.883l5.065 7.009 1.731-1.253-5.065-7.009-1.731 1.253zM16.517 0l-.824 2.052 3.655 1.488.824-2.052-3.655-1.488z"/></svg>
                            StackOverflow
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Source Code Section -->
        <section class="mb-10">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                </div>
                <h3 class="text-xl font-black text-slate-800 tracking-tight">System Source Trace</h3>
            </div>
            
            <div class="bg-slate-900 rounded-[2.5rem] p-4 shadow-2xl border border-slate-800">
                <div class="px-8 py-5 flex items-center justify-between border-b border-slate-800 mb-4">
                    <div class="flex items-center gap-3">
                        <span class="w-4 h-4 rounded-full bg-rose-500 shadow-[0_0_10px_rgba(244,63,94,0.5)]"></span>
                        <span class="text-sm font-bold font-mono tracking-tight text-slate-200"><?= esc(clean_path($file)) ?> <span class="text-rose-400 opacity-80">at line <?= esc($line) ?></span></span>
                    </div>
                    <div class="flex gap-2">
                        <div class="w-3 h-3 rounded-full bg-slate-700"></div>
                        <div class="w-3 h-3 rounded-full bg-slate-700"></div>
                        <div class="w-3 h-3 rounded-full bg-slate-700"></div>
                    </div>
                </div>
                
                <div class="source-content overflow-auto custom-scrollbar p-4 max-h-[600px]">
                    <?php if (is_file($file)) : ?>
                        <?= static::highlightFile($file, $line, 15); ?>
                    <?php else: ?>
                        <div class="p-20 text-center text-slate-500 italic font-medium">Source file could not be traced on this environment.</div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- Previous Exceptions -->
        <?php
        $last = $exception;
        while ($prevException = $last->getPrevious()) {
            $last = $prevException;
        ?>
        <section class="mb-10 bg-amber-50 border border-amber-100 rounded-[2rem] p-8 shadow-sm">
            <h4 class="text-amber-800 font-black mb-4 uppercase tracking-[0.2em] text-[10px]">Caused by:</h4>
            <div class="flex flex-col gap-3">
                <p class="text-lg font-black text-amber-900"><?= esc($prevException::class) ?> <span class="opacity-40">#<?= esc($prevException->getCode()) ?></span></p>
                <code class="text-amber-700 bg-white/60 p-5 rounded-2xl border border-amber-100 leading-relaxed"><?= nl2br(esc($prevException->getMessage())) ?></code>
                <p class="text-amber-600/60 text-xs font-mono font-bold mt-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                    <?= esc(clean_path($prevException->getFile()) . ':' . $prevException->getLine()) ?>
                </p>
            </div>
        </section>
        <?php } ?>

        <!-- Debug Tabs Section -->
        <?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE) : ?>
        <section class="mt-16 bg-white rounded-[3rem] shadow-sm border border-slate-200 overflow-hidden">
            <div class="border-b border-slate-100 bg-slate-50/30">
                <nav class="flex px-10 overflow-x-auto no-scrollbar" id="tabs">
                    <?php $tabItems = ['backtrace' => 'Backtrace', 'server' => 'Server', 'request' => 'Request', 'response' => 'Response', 'files' => 'Files', 'memory' => 'Memory']; ?>
                    <?php foreach($tabItems as $id => $label): ?>
                        <button onclick="return switchTab(event, '<?= $id ?>')" 
                                class="tab-btn px-8 py-8 text-sm font-black transition-all border-b-4 <?= $id === 'backtrace' ? 'tab-btn-active' : 'text-slate-400 border-transparent hover:text-slate-600' ?>">
                            <?= $label ?>
                        </button>
                    <?php endforeach; ?>
                </nav>
            </div>

            <div class="p-10">
                <!-- Backtrace -->
                <div class="tab-content-item active" id="backtrace">
                    <div class="flex flex-col gap-6">
                        <?php foreach ($trace as $index => $row) : ?>
                            <div class="group relative pl-12 border-l-2 border-slate-100 hover:border-emerald-300 transition-colors py-2">
                                <div class="absolute left-[-11px] top-6 w-5 h-5 rounded-full bg-white border-4 border-slate-200 group-hover:border-emerald-400 transition-all z-10"></div>
                                
                                <div class="p-8 bg-slate-50/50 border border-slate-100 rounded-[2rem] group-hover:bg-white group-hover:shadow-xl group-hover:shadow-slate-200/50 transition-all">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-3">
                                            <span class="text-xs font-black text-slate-300 uppercase tracking-widest italic"><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?>. Call</span>
                                            <?php if (isset($row['file']) && is_file($row['file']) && isset($row['class'])) : ?>
                                                <span class="text-[9px] font-black py-0.5 px-2 bg-emerald-100 text-emerald-600 rounded uppercase tracking-tighter">Traceable</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <p class="text-sm font-mono text-slate-500 break-all bg-white p-4 rounded-2xl border border-slate-100 mb-4 shadow-inner">
                                        <?php
                                        if (isset($row['file']) && is_file($row['file'])) {
                                            $func = $row['function'] ?? '';
                                            if (in_array($func, ['include', 'include_once', 'require', 'require_once'], true)) {
                                                echo '<span class="text-slate-700 font-bold">' . esc($func) . '</span> ' . esc(clean_path($row['file']));
                                            } else {
                                                echo esc(clean_path($row['file'])) . ' <span class="text-rose-400 font-bold ml-2">:' . $row['line'] . '</span>';
                                            }
                                        } else {
                                            echo '<span class="text-rose-400 italic opacity-60">{PHP internal core}</span>';
                                        }
                                        ?>
                                    </p>

                                    <?php if (isset($row['class'])) : ?>
                                        <div class="flex flex-wrap items-center gap-3">
                                            <div class="flex items-center gap-2 py-3 px-5 bg-white text-slate-800 rounded-2xl border border-slate-100 shadow-sm">
                                                <span class="text-xs font-black tracking-tight"><?= esc($row['class'] . $row['type'] . $row['function']) ?>()</span>
                                            </div>
                                            <?php if (! empty($row['args'])) : ?>
                                                <?php $argsId = $errorId . 'args' . $index ?>
                                                <button onclick="return toggle('<?= esc($argsId, 'attr') ?>');" class="px-5 py-3 bg-slate-900 text-white text-[10px] font-black rounded-2xl hover:bg-emerald-600 transition-all uppercase tracking-widest shadow-lg shadow-slate-900/10">Views Args</button>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (! empty($row['args'])) : ?>
                                            <div class="args mt-6 hidden" id="<?= esc($argsId, 'attr') ?>">
                                                <div class="overflow-x-auto rounded-3xl border border-slate-100 shadow-inner bg-slate-50 p-4">
                                                    <table class="w-full text-xs text-left">
                                                        <thead><tr class="text-slate-400 font-black uppercase text-[9px] tracking-widest"><th class="p-4">Parameter</th><th class="p-4">Value Representation</th></tr></thead>
                                                        <tbody>
                                                            <?php
                                                            $params = null;
                                                            if (! str_ends_with($row['function'], '}')) {
                                                                try {
                                                                    $mirror = isset($row['class']) ? new ReflectionMethod($row['class'], $row['function']) : new ReflectionFunction($row['function']);
                                                                    $params = $mirror->getParameters();
                                                                } catch (Exception $e) {}
                                                            }
                                                            foreach ($row['args'] as $key => $value) : ?>
                                                                <tr class="bg-white border-b border-slate-50 last:border-0"><td class="p-4 font-bold text-emerald-600"><code><?= esc(isset($params[$key]) ? '$' . $params[$key]->name : "#{$key}") ?></code></td><td class="p-4"><pre class="whitespace-pre-wrap text-slate-500 font-mono"><?= esc(print_r($value, true)) ?></pre></td></tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php elseif (isset($row['function'])) : ?>
                                        <div class="inline-flex items-center gap-2 py-3 px-5 bg-white text-blue-800 rounded-2xl border border-blue-50 shadow-sm">
                                            <span class="text-xs font-black tracking-tight"><?= esc($row['function']) ?>()</span>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Source Highlight In Trace? -->
                                    <?php if (isset($row['file']) && is_file($row['file']) && isset($row['class'])) : ?>
                                        <div class="mt-6 source-highlight hidden lg:block border-l-4 border-emerald-500/20">
                                            <?= static::highlightFile($row['file'], $row['line']) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Server Vars -->
                <div class="tab-content-item" id="server">
                    <div class="space-y-12">
                        <?php foreach (['_SERVER', '_SESSION'] as $var) : ?>
                            <?php if (empty($GLOBALS[$var]) || ! is_array($GLOBALS[$var])) continue; ?>
                            <div>
                                <h4 class="text-xl font-black text-slate-800 mb-6 flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-mono font-bold">$</span>
                                    $<?= esc($var) ?>
                                </h4>
                                <div class="overflow-hidden border border-slate-100 rounded-[2.5rem] shadow-sm">
                                    <table class="w-full text-xs text-left">
                                        <thead class="bg-slate-50">
                                            <tr class="text-slate-400 uppercase tracking-widest font-black text-[9px]"><th class="p-6 w-1/4">Key Name</th><th class="p-6">Current Value</th></tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($GLOBALS[$var] as $key => $value) : ?>
                                                <tr class="hover:bg-slate-50 border-b border-slate-50 last:border-0 transition-colors font-medium">
                                                    <td class="p-6 font-bold text-slate-700 bg-slate-50/30 font-mono"><?= esc($key) ?></td>
                                                    <td class="p-6 text-slate-400 font-mono italic break-all">
                                                        <?php if (is_string($value)) : ?>
                                                            <?= esc($value) ?>
                                                        <?php else: ?>
                                                            <pre class="bg-slate-100/50 p-4 rounded-2xl text-slate-600 mt-2 font-mono"><?= esc(print_r($value, true)) ?></pre>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Request -->
                <div class="tab-content-item" id="request">
                    <?php $request = service('request'); ?>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        <div class="space-y-8">
                            <h4 class="text-xl font-black text-slate-800 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">?</span>
                                Client Request
                            </h4>
                            <div class="overflow-hidden border border-slate-100 rounded-[2rem] shadow-sm bg-white">
                                <table class="w-full text-xs text-left">
                                    <tbody>
                                        <tr class="border-b border-slate-50"><td class="p-6 bg-slate-50 font-black w-40 text-slate-400 uppercase text-[9px]">Path</td><td class="p-6 font-mono text-slate-600"><?= esc($request->getUri()) ?></td></tr>
                                        <tr class="border-b border-slate-50"><td class="p-6 bg-slate-50 font-black text-slate-400 uppercase text-[9px]">Method</td><td class="p-6"><span class="px-3 py-1 bg-emerald-600 text-white rounded-lg font-black"><?= esc($request->getMethod()) ?></span></td></tr>
                                        <tr class="border-b border-slate-50"><td class="p-6 bg-slate-50 font-black text-slate-400 uppercase text-[9px]">IP Source</td><td class="p-6 font-mono text-slate-600"><?= esc($request->getIPAddress()) ?></td></tr>
                                        <tr class="border-b border-slate-50"><td class="p-6 bg-slate-50 font-black text-slate-400 uppercase text-[9px]">SSL/TLS</td><td class="p-6"><span class="font-bold <?= $request->isSecure() ? 'text-emerald-500' : 'text-rose-400' ?>"><?= $request->isSecure() ? 'Enabled' : 'None' ?></span></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="space-y-8">
                            <?php $headers = $request->headers(); ?>
                            <h4 class="text-xl font-black text-slate-800 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold">H</span>
                                Headers
                            </h4>
                            <div class="max-h-[400px] overflow-auto border border-slate-100 rounded-[2rem] custom-scrollbar shadow-sm bg-white">
                                <table class="w-full text-[10px] text-left">
                                    <thead class="bg-slate-50 sticky top-0 z-10"><tr><th class="p-4 font-black uppercase text-slate-400">Name</th><th class="p-4 font-black uppercase text-slate-400">Value</th></tr></thead>
                                    <tbody>
                                        <?php foreach ($headers as $name => $value) : ?>
                                            <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50 transition-colors">
                                                <td class="p-4 font-black text-slate-700 bg-slate-50/20 w-1/3"><?= esc($name) ?></td>
                                                <td class="p-4 text-slate-500 font-mono leading-relaxed"><?= esc($value instanceof Header ? $value->getValueLine() : implode(', ', $value)) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Response (Placeholder logic as Response is usually fixed here) -->
                <div class="tab-content-item" id="response">
                    <?php
                        $response = service('response');
                        $response->setStatusCode(http_response_code());
                    ?>
                    <div class="p-12 text-center bg-slate-50 border border-slate-100 rounded-[3rem]">
                        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-emerald-100 text-emerald-600 mb-6 font-black text-3xl">
                            <?= esc($response->getStatusCode()) ?>
                        </div>
                        <h4 class="text-2xl font-black text-slate-800 mb-2"><?= esc($response->getReasonPhrase()) ?></h4>
                        <p class="text-slate-400 font-medium italic">Internal State captured at point of failure.</p>
                    </div>
                </div>

                <!-- Files -->
                <div class="tab-content-item" id="files">
                    <h4 class="text-xl font-black text-slate-800 mb-8 px-2 flex items-center gap-3">
                        <span class="w-3 h-3 bg-indigo-500 rounded-full"></span>
                        Included Resources (<?= count(get_included_files()) ?>)
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach (get_included_files() as $file) :?>
                            <div class="bg-white p-5 rounded-2xl border border-slate-100 text-[10px] font-mono text-slate-600 flex items-center gap-4 hover:shadow-lg hover:shadow-slate-200/40 transition-all group">
                                <span class="w-6 h-6 flex items-center justify-center bg-slate-50 rounded-lg group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                                </span>
                                <span class="truncate font-bold opacity-80"><?= esc(clean_path($file)) ?></span>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>

                <!-- Memory -->
                <div class="tab-content-item" id="memory">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="p-10 rounded-[2.5rem] bg-gradient-to-br from-indigo-50 to-white border border-indigo-100 text-center shadow-sm">
                            <span class="text-[10px] font-black uppercase text-indigo-400 tracking-[0.3em] block mb-4">Allocated</span>
                            <span class="text-5xl font-black text-indigo-700 tracking-tighter"><?= esc(static::describeMemory(memory_get_usage(true))) ?></span>
                        </div>
                        <div class="p-10 rounded-[2.5rem] bg-gradient-to-br from-rose-50 to-white border border-rose-100 text-center shadow-sm">
                            <span class="text-[10px] font-black uppercase text-rose-400 tracking-[0.3em] block mb-4">System Peak</span>
                            <span class="text-5xl font-black text-rose-700 tracking-tighter"><?= esc(static::describeMemory(memory_get_peak_usage(true))) ?></span>
                        </div>
                        <div class="p-10 rounded-[2.5rem] bg-gradient-to-br from-emerald-50 to-white border border-emerald-100 text-center shadow-sm">
                            <span class="text-[10px] font-black uppercase text-emerald-400 tracking-[0.3em] block mb-4">Instance Limit</span>
                            <span class="text-5xl font-black text-emerald-700 tracking-tighter"><?= esc(ini_get('memory_limit')) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <footer class="mt-24 border-t border-slate-200 pt-12 pb-20">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <div class="inline-flex items-center gap-2 px-6 py-2 bg-slate-900 rounded-full text-white text-[10px] font-black uppercase tracking-[0.3em] mb-6">
                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span> Security Integrity Verified
            </div>
            <p class="text-slate-400 text-sm font-medium">System meticulously developed for Rapor Digital SMPIT - Educational Excellence &copy; <?= date('Y') ?></p>
        </div>
    </footer>

</body>
</html>
