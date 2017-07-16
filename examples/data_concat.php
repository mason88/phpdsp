<?php
require __DIR__ . '/../vendor/autoload.php';
use mason88\PhpDsp;

// slower frequency with shorter amplitude
$signal = new PhpDsp\Sinusoid(300, 0.7, 0, 'sin');
$wave = $signal->make_wave(0.03);

// faster frequency with higher amplitude
$signal2 = new PhpDsp\Sinusoid(600, 1.0, 0, 'sin');
$wave2 = $signal2->make_wave(0.03);

// $wave2 gets appended to the end of $wave
$concat_wave = $wave->concat($wave2);

echo($concat_wave->google_data());




