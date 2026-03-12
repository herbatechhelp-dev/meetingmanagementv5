<?php
$lines = file('storage/logs/laravel.log');
$start = max(0, count($lines) - 200);
for ($i = $start; $i < count($lines); $i++) {
    if (strpos($lines[$i], 'Update meeting error') !== false) {
        for ($j = $i; $j < min($i + 25, count($lines)); $j++) {
            echo $lines[$j];
        }
        break;
    }
}
