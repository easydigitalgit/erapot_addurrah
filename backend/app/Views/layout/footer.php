<?php
// Ambil data sekolah dari database (menggunakan helper yang sama dengan sidebar)
$sekolahData = function_exists('get_identitas_sekolah') ? get_identitas_sekolah() : [];
$namaSekolah = $sekolahData['nama_sekolah'] ?? 'Sekolah';
$tahunSekarang = date('Y');
?>
<footer class="mt-8 mb-10 text-center text-sm text-gray-500 dark:text-slate-400 transition-colors">
   <p>Hak Cipta &copy; <?= $tahunSekarang ?> <?= esc($namaSekolah) ?>. Seluruh hak dilindungi.</p>
   <p class="mt-1 text-xs text-gray-400 dark:text-slate-500 transition-colors"><?= lang('Footer.privacy') ?></p>
</footer>