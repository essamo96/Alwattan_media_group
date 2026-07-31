<?php
/**
 * مسح كاش Laravel بدون تشغيل الاطار.
 * بديل عن: artisan cache:clear / view:clear / route:clear / config:clear
 * يعمل على اي نسخة PHP لانه لا يحمّل Laravel اطلاقاً.
 *
 * التشغيل:  php clear-cache.php
 * احذف الملف بعد الانتهاء.
 */

$base = __DIR__;

$targets = [
    'bootstrap/cache' => ['config.php', 'routes-v7.php', 'routes.php', 'services.php', 'packages.php'],
];

$wipe_dirs = [
    'storage/framework/views',
    'storage/framework/cache/data',
    'storage/framework/sessions',
];

$deleted = 0;

foreach ($targets as $dir => $files) {
    foreach ($files as $file) {
        $path = $base . '/' . $dir . '/' . $file;
        if (is_file($path)) {
            if (@unlink($path)) {
                echo "deleted: $dir/$file" . PHP_EOL;
                $deleted++;
            } else {
                echo "FAILED : $dir/$file (permission?)" . PHP_EOL;
            }
        }
    }
}

foreach ($wipe_dirs as $dir) {
    $path = $base . '/' . $dir;
    if (!is_dir($path)) {
        echo "skip   : $dir (not found)" . PHP_EOL;
        continue;
    }
    $items = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
    );
    $count = 0;
    foreach ($items as $item) {
        $name = $item->getFilename();
        if ($name === '.gitignore' || $name === '.gitkeep') {
            continue;
        }
        if ($item->isDir()) {
            @rmdir($item->getRealPath());
        } elseif (@unlink($item->getRealPath())) {
            $count++;
        }
    }
    echo "cleared: $dir ($count files)" . PHP_EOL;
    $deleted += $count;
}

echo PHP_EOL . "DONE — $deleted file(s) removed." . PHP_EOL;
