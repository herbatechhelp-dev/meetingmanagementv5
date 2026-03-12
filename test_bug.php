<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $a = null;
    $x = $a['foo'];
} catch (\Exception $e) {
    echo "1. Caught: " . $e->getMessage() . "\n";
}

try {
    $b = [];
    $y = $b['foo'];
} catch (\Exception $e) {
    echo "2. Caught: " . $e->getMessage() . "\n";
}
