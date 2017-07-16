<?php
require __DIR__ . '/../vendor/autoload.php';
use mason88\PhpDsp;


$sin_signal = new PhpDsp\TriangleSignal(200, 1.0, 0);
$sin_wave = $sin_signal->make_wave(0.06);
$dct = $sin_wave->make_dct();

echo($dct->google_data());




