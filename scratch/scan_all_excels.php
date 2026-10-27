<?php
function scanDirRecursive($dir) {
    $result = [];
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = $dir . '/' . $item;
        if (is_dir($path)) {
            $result = array_merge($result, scanDirRecursive($path));
        } else {
            if (preg_match('/\.(xlsx|xls|csv)$/i', $item)) {
                $result[] = $path;
            }
        }
    }
    return $result;
}

$files = scanDirRecursive('.');
echo "Total Excel files di workspace: " . count($files) . "\n\n";
foreach ($files as $f) {
    if (stripos($f, 'vendor') === false && stripos($f, 'node_modules') === false) {
        echo "- $f\n";
    }
}

