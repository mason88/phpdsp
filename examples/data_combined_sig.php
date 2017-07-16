<?php
require __DIR__ . '/../vendor/autoload.php';
use mason88\PhpDsp;


$signal1 = new PhpDsp\TriangleSignal(200, 1.0, 0);
$signal2 = new PhpDsp\Sinusoid(600, 1.0, 0, 'sin');
$signal3 = new PhpDsp\SquareSignal(100, 1.0, 0);

// combine first and second signals
$combined_sig = $signal1->add($signal2);

// combine third signal
$combined_sig = $combined_sig->add($signal3);

$combined_wave = $combined_sig->make_wave(0.06);

echo($combined_wave->google_data());




