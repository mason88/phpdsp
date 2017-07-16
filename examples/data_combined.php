<?php
require __DIR__ . '/../vendor/autoload.php';
use mason88\PhpDsp;


$tri_signal = new PhpDsp\TriangleSignal(200, 1.0, 0);
$tri_wave = $tri_signal->make_wave(0.06);

$sin_signal = new PhpDsp\Sinusoid(600, 1.0, 0, 'sin');
$sin_wave = $sin_signal->make_wave(0.06);

// add two waves
$combined_wave = $tri_wave->add($sin_wave);

echo($combined_wave->google_data());




