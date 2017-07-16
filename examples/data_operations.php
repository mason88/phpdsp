<?php
require __DIR__ . '/../vendor/autoload.php';
use mason88\PhpDsp;


$signal = new PhpDsp\Sinusoid(600, 1.0, 0, 'sin');
$wave = $signal->make_wave(0.08);

// shift ts by 0.01 seconds, normalize amplitude to 2
$wave->shift(0.01)->normalize(2);

// cut a segment of the wave that starts from 0.03 seconds that lasts for 0.04 seconds
$wave_segment = $wave->segment(0.03, 0.04);

// apodize to taper ends
$wave_segment->apodize();

echo($wave_segment->google_data());




