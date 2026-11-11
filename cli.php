<?php
declare(strict_types=1);

require __DIR__ . '/MolTransliterator.php';

/**
 * Usage:
 *   php cli.php "Chișinău"                 # auto-detect
 *   php cli.php --to=cyr "Bună ziua"       # Latin -> Cyrillic
 *   php cli.php --to=lat "Молдова"         # Cyrillic -> Latin
 *   echo "Moldova" | php cli.php           # read from stdin
 *   php cli.php --detect "Moldova"         # print script name
 */

$mode = 'auto';
$detect = false;
$parts = [];

foreach (array_slice($argv, 1) as $arg) {
    if ($arg === '--to=cyr') {
        $mode = 'lat2cyr';
    } elseif ($arg === '--to=lat') {
        $mode = 'cyr2lat';
    } elseif ($arg === '--detect') {
        $detect = true;
    } elseif ($arg === '-h' || $arg === '--help') {
        fwrite(STDOUT, "Usage: php cli.php [--to=cyr|--to=lat|--detect] [text]\n");
        exit(0);
    } else {
        $parts[] = $arg;
    }
}

$text = $parts ? implode(' ', $parts) : (string) stream_get_contents(STDIN);

echo $detect
    ? MolTransliterator::detectScript($text)
    : MolTransliterator::convert($text, $mode);
echo PHP_EOL;
