<?php
declare(strict_types=1);

/**
 * MolTransliterator — Latin <-> Cyrillic converter for Moldovan (Lumina standard, ISO 639-3: mol).
 * PHP port of https://github.com/ivanm696/moldovan-transliterator
 */
final class MolTransliterator
{
    public const VERSION = '1.0.1';
    public const LANG = 'mol';

    private const LATIN_TO_CYR = [
        // 3-буквенные сочетания (UPPERCASE, Titlecase, lowercase)
        'CHI' => 'КИ', 'Chi' => 'Ки', 'chi' => 'ки',
        'CHE' => 'КЕ', 'Che' => 'Ке', 'che' => 'ке',
        'GHI' => 'ГИ', 'Ghi' => 'Ги', 'ghi' => 'ги',
        'GHE' => 'ГЕ', 'Ghe' => 'Ге', 'ghe' => 'ге',
        'CIA' => 'ЧА', 'Cia' => 'Ча', 'cia' => 'ча',
        'CIO' => 'ЧО', 'Cio' => 'Чо', 'cio' => 'чо',
        'CIU' => 'ЧУ', 'Ciu' => 'Чу', 'ciu' => 'чу',
        'CEA' => 'ЧА', 'Cea' => 'Ча', 'cea' => 'ча',
        'GIA' => 'ДЖА', 'Gia' => 'Джа', 'gia' => 'джа',
        'GIO' => 'ДЖО', 'Gio' => 'Джо', 'gio' => 'джо',
        'GIU' => 'ДЖУ', 'Giu' => 'Джу', 'giu' => 'джу',
        'GEA' => 'ДЖА', 'Gea' => 'Джа', 'gea' => 'джа',

        // 2-буквенные сочетания
        'CE' => 'ЧЕ', 'Ce' => 'Че', 'ce' => 'че',
        'CI' => 'ЧИ', 'Ci' => 'Чи', 'ci' => 'чи',
        'GE' => 'ДЖЕ', 'Ge' => 'Дже', 'ge' => 'дже',
        'GI' => 'ДЖИ', 'Gi' => 'Джи', 'gi' => 'джи',
        'CH' => 'К', 'Ch' => 'К', 'ch' => 'к',
        'GH' => 'Г', 'Gh' => 'Г', 'gh' => 'г',
        'SH' => 'Ш', 'Sh' => 'Ш', 'sh' => 'ш',
        'ZH' => 'Ж', 'Zh' => 'Ж', 'zh' => 'ж',

        // Диакритические знаки (включая старые варианты с седилью)
        'ă' => 'э', 'Ă' => 'Э',
        'â' => 'ы', 'Â' => 'Ы',
        'î' => 'ы', 'Î' => 'Ы',
        'ș' => 'ш', 'Ș' => 'Ш',
        'ş' => 'ш', 'Ş' => 'Ш',
        'ț' => 'ц', 'Ț' => 'Ц',
        'ţ' => 'ц', 'Ţ' => 'Ц',

        // Одиночные буквы (добавлены k, q, w для имен и заимствований)
        'a' => 'а', 'A' => 'А', 'b' => 'б', 'B' => 'Б',
        'c' => 'к', 'C' => 'К', 'd' => 'д', 'D' => 'Д',
        'e' => 'е', 'E' => 'Е', 'f' => 'ф', 'F' => 'Ф',
        'g' => 'г', 'G' => 'Г', 'h' => 'х', 'H' => 'Х',
        'i' => 'и', 'I' => 'И', 'j' => 'ж', 'J' => 'Ж',
        'k' => 'к', 'K' => 'К', 'l' => 'л', 'L' => 'Л',
        'm' => 'м', 'M' => 'М', 'n' => 'н', 'N' => 'Н',
        'o' => 'о', 'O' => 'О', 'p' => 'п', 'P' => 'П',
        'q' => 'к', 'Q' => 'К', 'r' => 'р', 'R' => 'Р',
        's' => 'с', 'S' => 'С', 't' => 'т', 'T' => 'Т',
        'u' => 'у', 'U' => 'У', 'v' => 'в', 'V' => 'В',
        'w' => 'в', 'W' => 'В', 'x' => 'кс', 'X' => 'КС',
        'y' => 'й', 'Y' => 'Й', 'z' => 'з', 'Z' => 'З',
    ];

    private const CYR_TO_LATIN = [
        // 3-буквенные сочетания (UPPERCASE, Titlecase, lowercase)
        'ДЖЕ' => 'GE', 'Дже' => 'Ge', 'дже' => 'ge',
        'ДЖИ' => 'GI', 'Джи' => 'Gi', 'джи' => 'gi',
        'ДЖА' => 'GIA', 'Джа' => 'Gia', 'джа' => 'gia',
        'ДЖО' => 'GIO', 'Джо' => 'Gio', 'джо' => 'gio',
        'ДЖУ' => 'GIU', 'Джу' => 'Giu', 'джу' => 'giu',

        // 2-буквенные сочетания
        'КЕ' => 'CHE', 'Ке' => 'Che', 'ке' => 'che',
        'КИ' => 'CHI', 'Ки' => 'Chi', 'ки' => 'chi',
        'ГЕ' => 'GHE', 'Ге' => 'Ghe', 'ге' => 'ghe',
        'ГИ' => 'GHI', 'Ги' => 'Ghi', 'ги' => 'ghi',
        'ЧА' => 'CIA', 'Ча' => 'Cia', 'ча' => 'cia',
        'ЧЕ' => 'CE', 'Че' => 'Ce', 'че' => 'ce',
        'ЧИ' => 'CI', 'Чи' => 'Ci', 'чи' => 'ci',
        'ЧО' => 'CIO', 'Чо' => 'Cio', 'чо' => 'cio',
        'ЧУ' => 'CIU', 'Чу' => 'Ciu', 'чу' => 'ciu',

        // Одиночные буквы (включая ӂ для советской молдавской кириллицы и ь/ъ)
        'а' => 'a', 'А' => 'A', 'б' => 'b', 'Б' => 'B',
        'в' => 'v', 'В' => 'V', 'г' => 'g', 'Г' => 'G',
        'д' => 'd', 'Д' => 'D', 'е' => 'e', 'Е' => 'E',
        'ё' => 'io', 'Ё' => 'IO', 'ж' => 'j', 'Ж' => 'J',
        'ӂ' => 'g', 'Ӂ' => 'G', 'з' => 'z', 'З' => 'Z',
        'и' => 'i', 'И' => 'I', 'й' => 'i', 'Й' => 'I',
        'к' => 'c', 'К' => 'C', 'л' => 'l', 'Л' => 'L',
        'м' => 'm', 'М' => 'M', 'н' => 'n', 'Н' => 'N',
        'о' => 'o', 'О' => 'O', 'п' => 'p', 'П' => 'P',
        'р' => 'r', 'Р' => 'R', 'с' => 's', 'С' => 'S',
        'т' => 't', 'Т' => 'T', 'у' => 'u', 'У' => 'U',
        'ф' => 'f', 'Ф' => 'F', 'х' => 'h', 'Х' => 'H',
        'ц' => 'ț', 'Ц' => 'Ț', 'ч' => 'c', 'Ч' => 'C',
        'ш' => 'ș', 'Ш' => 'Ș', 'щ' => 'șt', 'Щ' => 'ȘT',
        'ъ' => '', 'Ъ' => '', 'ы' => 'î', 'Ы' => 'Î',
        'ь' => 'i', 'Ь' => 'I', 'э' => 'ă', 'Э' => 'Ă',
        'ю' => 'iu', 'Ю' => 'IU', 'я' => 'ia', 'Я' => 'IA',
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
        // Добавлена буква ӂ/Ӂ для корректного определения советской молдавской кириллицы
        $cyr = (int) preg_match_all('/[а-яёӂА-ЯЁӁ]/u', $text);
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
