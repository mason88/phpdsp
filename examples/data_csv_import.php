<?php
require __DIR__ . '/../vendor/autoload.php';
use mason88\PhpDsp;


$imported = array_map('str_getcsv', file('dow_6months.csv'));
$dow = array_column($imported, 1);  // second column has the dow close data

$wave = new PhpDsp\Wave($dow, NULL, 1); // framerate = 1 to make ts per day

echo($wave->google_data());




