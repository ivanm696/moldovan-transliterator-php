<?php
declare(strict_types=1);

/**
 * MolTransliterator — Latin <-> Cyrillic converter for Moldovan (Lumina standard, ISO 639-3: mol).
 * PHP port of https://github.com/ivanm696/moldovan-transliterator
 *
 * strtr() with an array always tries the longest key first, so digraphs
 * (chi, che, ghi, ghe, ce, ci, ge, gi, sh, zh, ch, gh ...) win over single letters.
 */
final class MolTransliterator
{
    public const VERSION = '1.0.0';
    public const LANG = 'mol';

    private const LATIN_TO_CYR = [
        'Chi' => 'Ки', 'Che' => 'Ке', 'chi' => 'ки', 'che' => 'ке',
        'Ghi' => 'Ги', 'Ghe' => 'Ге', 'ghi' => 'ги', 'ghe' => 'ге',
        'Ce' => 'Че', 'Ci' => 'Чи', 'ce' => 'че', 'ci' => 'чи',
        'Ge' => 'Дже', 'Gi' => 'Джи', 'ge' => 'дже', 'gi' => 'джи',
        'ch' => 'к', 'Ch' => 'К', 'gh' => 'г', 'Gh' => 'Г',
        'sh' => 'ш', 'Sh' => 'Ш', 'zh' => 'ж', 'Zh' => 'Ж',
        'ă' => 'э', 'Ă' => 'Э', 'â' => 'ы', 'Â' => 'Ы',
        'î' => 'ы', 'Î' => 'Ы', 'ș' => 'ш', 'Ș' => 'Ш',
        'ş' => 'ш', 'Ş' => 'Ш', 'ț' => 'ц', 'Ț' => 'Ц',
        'ţ' => 'ц', 'Ţ' => 'Ц',
        'a' => 'а', 'A' => 'А', 'b' => 'б', 'B' => 'Б',
        'c' => 'к', 'C' => 'К', 'd' => 'д', 'D' => 'Д',
        'e' => 'е', 'E' => 'Е', 'f' => 'ф', 'F' => 'Ф',
        'g' => 'г', 'G' => 'Г', 'h' => 'х', 'H' => 'Х',
        'i' => 'и', 'I' => 'И', 'j' => 'ж', 'J' => 'Ж',
        'l' => 'л', 'L' => 'Л', 'm' => 'м', 'M' => 'М',
        'n' => 'н', 'N' => 'Н', 'o' => 'о', 'O' => 'О',
        'p' => 'п', 'P' => 'П', 'r' => 'р', 'R' => 'Р',
        's' => 'с', 'S' => 'С', 't' => 'т', 'T' => 'Т',
        'u' => 'у', 'U' => 'У', 'v' => 'в', 'V' => 'В',
        'x' => 'кс', 'X' => 'Кс', 'y' => 'й', 'Y' => 'Й',
        'z' => 'з', 'Z' => 'З',
    ];

    private const CYR_TO_LATIN = [
        'дже' => 'ge', 'джи' => 'gi', 'ке' => 'che', 'ки' => 'chi',
        'ге' => 'ghe', 'ги' => 'ghi',
        'ча' => 'cia', 'че' => 'ce', 'чи' => 'ci', 'чо' => 'cio', 'чу' => 'ciu',
        // Capitalised digraphs (not in the upstream npm table, which mangles e.g. "Кишинэу")
        'Дже' => 'Ge', 'Джи' => 'Gi', 'Ке' => 'Che', 'Ки' => 'Chi',
        'Ге' => 'Ghe', 'Ги' => 'Ghi',
        'Ча' => 'Cia', 'Че' => 'Ce', 'Чи' => 'Ci', 'Чо' => 'Cio', 'Чу' => 'Ciu',
        'а' => 'a', 'А' => 'A', 'б' => 'b', 'Б' => 'B', 'в' => 'v', 'В' => 'V',
        'г' => 'g', 'Г' => 'G', 'д' => 'd', 'Д' => 'D', 'е' => 'e', 'Е' => 'E',
        'ж' => 'j', 'Ж' => 'J', 'з' => 'z', 'З' => 'Z', 'и' => 'i', 'И' => 'I',
        'й' => 'i', 'Й' => 'I', 'к' => 'c', 'К' => 'C', 'л' => 'l', 'Л' => 'L',
        'м' => 'm', 'М' => 'M', 'н' => 'n', 'Н' => 'N', 'о' => 'o', 'О' => 'O',
        'п' => 'p', 'П' => 'P', 'р' => 'r', 'Р' => 'R', 'с' => 's', 'С' => 'S',
        'т' => 't', 'Т' => 'T', 'у' => 'u', 'У' => 'U', 'ф' => 'f', 'Ф' => 'F',
        'х' => 'h', 'Х' => 'H', 'ц' => 'ț', 'Ц' => 'Ț', 'ч' => 'c', 'Ч' => 'C',
        'ш' => 'ș', 'Ш' => 'Ș', 'э' => 'ă', 'Э' => 'Ă', 'ы' => 'î', 'Ы' => 'Î',
        'ю' => 'iu', 'Ю' => 'Iu', 'я' => 'ia', 'Я' => 'Ia',
    ];

    public static function latinToCyrillic(string $text): string
    {
        return strtr($text, self::LATIN_TO_CYR);
    }

    public static function cyrillicToLatin(string $text): string
    {
        return strtr($text, self::CYR_TO_LATIN);
    }

    /** @return 'latin'|'cyrillic'|'unknown' */
    public static function detectScript(string $text): string
    {
        $cyr = (int) preg_match_all('/[а-яёА-ЯЁ]/u', $text);
        $lat = (int) preg_match_all('/[a-zA-ZăâîșțşţĂÂÎȘȚŞŢ]/u', $text);

        if ($cyr > $lat) {
            return 'cyrillic';
        }
        if ($lat > $cyr) {
            return 'latin';
        }
        return 'unknown';
    }

    public static function autoConvert(string $text): string
    {
        return match (self::detectScript($text)) {
            'latin' => self::latinToCyrillic($text),
            'cyrillic' => self::cyrillicToLatin($text),
            default => $text,
        };
    }

    /** @param 'auto'|'lat2cyr'|'cyr2lat' $mode */
    public static function convert(string $text, string $mode = 'auto'): string
    {
        return match ($mode) {
            'lat2cyr' => self::latinToCyrillic($text),
            'cyr2lat' => self::cyrillicToLatin($text),
            default => self::autoConvert($text),
        };
    }
}
