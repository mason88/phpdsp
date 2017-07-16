<?php
require __DIR__ . '/../vendor/autoload.php';
use mason88\PhpDsp;


$imported = array_map('str_getcsv', file('dow_6months.csv'));
$dow = array_column($imported, 1);  // second column has the dow close data
$wave = new PhpDsp\Wave($dow, NULL, 1); // framerate = 1 to make ts per day

// from here you can perfrom Wave operations on this object
$dct = $wave->make_dct();
$dct->band_pass(10, 30);

echo($dct->google_data());




