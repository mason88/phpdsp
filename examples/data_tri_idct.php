<?php
require __DIR__ . '/../vendor/autoload.php';
use mason88\PhpDsp;


$sin_signal = new PhpDsp\TriangleSignal(200, 1.0, 0);
$sin_wave = $sin_signal->make_wave(0.06);

// create DCT from Wave
$dct = $sin_wave->make_dct();

// create Wave from DCT back again
$idct_wave = $dct->make_wave();

echo($idct_wave->google_data());

