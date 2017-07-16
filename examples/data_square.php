<?php
require __DIR__ . '/../vendor/autoload.php';
use mason88\PhpDsp;


$signal = new PhpDsp\SquareSignal(200, 1.0, 0);
$wave = $signal->make_wave(0.06);

echo($wave->google_data());
