<?php if (empty($guru_data)) : ?>
    <tr>
        <td colspan="7" class="px-6 py-10 text-center text-gray-500 bg-gray-50">
            <div class="flex flex-col items-center justify-center">
                <p class="text-base font-medium text-gray-600">Data tidak ditemukan</p>
            </div>
        </td>
    </tr>
<?php else : ?>
    <?php foreach ($guru_data as $row) : ?>
        <tr class="hover:bg-gray-50 transition-colors group border-b border-gray-100">
            <td class="px-6 py-4">
                <input type="checkbox" class="checkbox-custom" value="<?= $row['id'] ?>">
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-sm">
                        <?= strtoupper(substr($row['nama_lengkap'], 0, 2)) ?>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800 text-sm"><?= esc($row['nama_lengkap']) ?></p>
                        <p class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md inline-block mt-1"><?= esc($row['jabatan'] ?? 'Guru') ?></p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-700 font-mono"><?= !empty($row['nip']) ? esc($row['nip']) : '-' ?></span>
                    <span class="text-xs text-gray-400">NIK: <?= esc($row['nik'] ?? '-') ?></span>
                </div>
            </td>
            <td class="px-6 py-4">
                <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                    <?= esc($row['mapel_utama'] ?? '-') ?>
                </span>
            </td>
            <td class="px-6 py-4"><span class="text-gray-400 text-xs">-</span></td>
            <td class="px-6 py-4">
                <?php if (isset($row['is_active']) && $row['is_active'] == 1) : ?>
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 inline-flex items-center gap-1">Aktif</span>
                <?php else : ?>
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 inline-flex items-center gap-1">Nonaktif</span>
                <?php endif; ?>
            </td>
            <td class="px-6 py-4 text-center">
                 <div class="flex items-center justify-center gap-2">
                    <button class="p-2 text-gray-400 hover:text-emerald-600">Edit</button>
                    <button class="p-2 text-gray-400 hover:text-red-600">Hapus</button>
                 </div>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>