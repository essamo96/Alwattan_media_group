<?php
/**
 * سكربت تشخيص مؤقت — يكشف الخطأ القاتل الذي يمنع artisan من العمل.
 * التشغيل:  php diagnose.php
 * احذف الملف بعد الانتهاء.
 */

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/diagnose-error.log');
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

function out($label, $value = '') {
    echo str_pad($label, 34, '.') . ' ' . $value . PHP_EOL;
    flush();
}

// اي خطأ قاتل يُلتقط هنا حتى لو فشل معالج Laravel
register_shutdown_function(function () {
    $e = error_get_last();
    if ($e && in_array($e['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        echo PHP_EOL . '>>> FATAL ERROR <<<' . PHP_EOL;
        echo $e['message'] . PHP_EOL;
        echo 'FILE: ' . $e['file'] . ':' . $e['line'] . PHP_EOL;
    }
});

echo '=== DIAGNOSE START ===' . PHP_EOL;
out('PHP version', PHP_VERSION);
out('memory_limit', ini_get('memory_limit'));
out('display_errors', ini_get('display_errors'));
out('error_log', ini_get('error_log') ?: '(none)');

$required = ['pdo_mysql', 'mbstring', 'openssl', 'tokenizer', 'json', 'fileinfo', 'ctype', 'xml'];
foreach ($required as $ext) {
    out('ext ' . $ext, extension_loaded($ext) ? 'OK' : 'MISSING');
}

out('vendor/autoload.php', is_file(__DIR__ . '/vendor/autoload.php') ? 'OK' : 'MISSING');
out('.env', is_file(__DIR__ . '/.env') ? 'OK' : 'MISSING');
out('bootstrap/cache writable', is_writable(__DIR__ . '/bootstrap/cache') ? 'OK' : 'NOT WRITABLE');
out('storage writable', is_writable(__DIR__ . '/storage') ? 'OK' : 'NOT WRITABLE');

foreach (['config.php', 'routes-v7.php', 'services.php', 'packages.php'] as $f) {
    out('bootstrap/cache/' . $f, is_file(__DIR__ . '/bootstrap/cache/' . $f) ? 'EXISTS' : '-');
}

echo PHP_EOL . '--- STEP 1: autoload ---' . PHP_EOL;
try {
    require __DIR__ . '/vendor/autoload.php';
    echo 'autoload OK' . PHP_EOL;
} catch (Throwable $t) {
    echo 'AUTOLOAD FAILED: ' . get_class($t) . ': ' . $t->getMessage() . PHP_EOL;
    echo $t->getFile() . ':' . $t->getLine() . PHP_EOL;
    exit(1);
}

echo PHP_EOL . '--- STEP 2: bootstrap/app.php ---' . PHP_EOL;
try {
    $app = require_once __DIR__ . '/bootstrap/app.php';
    echo 'app created OK' . PHP_EOL;
} catch (Throwable $t) {
    echo 'BOOTSTRAP FAILED: ' . get_class($t) . ': ' . $t->getMessage() . PHP_EOL;
    echo $t->getFile() . ':' . $t->getLine() . PHP_EOL;
    echo $t->getTraceAsString() . PHP_EOL;
    exit(1);
}

echo PHP_EOL . '--- STEP 3: console kernel ---' . PHP_EOL;
try {
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    echo 'kernel bootstrapped OK' . PHP_EOL;
} catch (Throwable $t) {
    echo 'KERNEL FAILED: ' . get_class($t) . ': ' . $t->getMessage() . PHP_EOL;
    echo $t->getFile() . ':' . $t->getLine() . PHP_EOL;
    echo $t->getTraceAsString() . PHP_EOL;
    exit(1);
}

echo PHP_EOL . '--- STEP 4: database ---' . PHP_EOL;
try {
    $pdo = Illuminate\Support\Facades\DB::connection()->getPdo();
    echo 'DB connected OK -> ' . Illuminate\Support\Facades\DB::connection()->getDatabaseName() . PHP_EOL;
    $has = Illuminate\Support\Facades\Schema::hasTable('menus');
    echo 'table menus: ' . ($has ? 'EXISTS' : 'MISSING') . PHP_EOL;
    if ($has) {
        echo 'menus rows: ' . Illuminate\Support\Facades\DB::table('menus')->count() . PHP_EOL;
    }
    echo 'menus permissions: ' . Illuminate\Support\Facades\DB::table('permissions')
                    ->where('name', 'like', 'admin.menus.%')->count() . PHP_EOL;
} catch (Throwable $t) {
    echo 'DB FAILED: ' . get_class($t) . ': ' . $t->getMessage() . PHP_EOL;
    echo $t->getFile() . ':' . $t->getLine() . PHP_EOL;
    exit(1);
}

echo PHP_EOL . '=== ALL CHECKS PASSED ===' . PHP_EOL;
