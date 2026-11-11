<?php
declare(strict_types=1);

require __DIR__ . '/MolTransliterator.php';

$tests = [
    ['latinToCyrillic', 'Moldova',   'Молдова'],
    ['latinToCyrillic', 'Bună ziua', 'Бунэ зиуа'],
    ['latinToCyrillic', 'Mulțumesc', 'Мулцумеск'],
    ['latinToCyrillic', 'Chișinău',  'Кишинэу'],
    ['cyrillicToLatin', 'Молдова',   'Moldova'],
    ['detectScript',    'Moldova',   'latin'],
    ['detectScript',    'Молдова',   'cyrillic'],
    ['autoConvert',     'Chișinău',  'Кишинэу'],
    ['autoConvert',     'Кишинэу',   'Chișinău'],
];

$passed = 0;
$failed = 0;

foreach ($tests as [$fn, $input, $expected]) {
    $result = MolTransliterator::$fn($input);
    $ok = $result === $expected;
    $ok ? $passed++ : $failed++;
    echo ($ok ? '✅' : '❌') . " $fn(\"$input\") → \"$result\"" . ($ok ? '' : " (expected: \"$expected\")") . PHP_EOL;
}

echo PHP_EOL . "📊 $passed ✅ / $failed ❌ из " . count($tests) . " тестов" . PHP_EOL;
exit($failed > 0 ? 1 : 0);
