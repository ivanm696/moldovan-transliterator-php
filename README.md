# MolTransliterator (PHP)

PHP-порт [moldovan-transliterator](https://github.com/ivanm696/moldovan-transliterator) —
конвертер Latin ↔ Кириллица для молдавского языка (Стандарт Лумина, ISO 639-3: `mol`),
как на странице https://ivanm696.github.io/github/. Требуется PHP 8.0+ (расширение `mbstring` не нужно, нужен `pcre` с UTF-8).

## Файлы

| Файл | Назначение |
|---|---|
| `MolTransliterator.php` | Класс с правилами и API |
| `cli.php` | Командная строка |
| `index.php` | Веб-форма + JSON API |
| `test.php` | Тесты |

## Библиотека

```php
require 'MolTransliterator.php';

MolTransliterator::latinToCyrillic('Chișinău'); // Кишинэу
MolTransliterator::cyrillicToLatin('Молдова');  // Moldova
MolTransliterator::autoConvert('Bună ziua');    // Бунэ зиуа
MolTransliterator::detectScript('Moldova');     // latin
```

## CLI

```bash
php cli.php "Chișinău"
php cli.php --to=lat "Молдова"
echo "Bună ziua" | php cli.php
```

## Веб

```bash
php -S localhost:8000
# http://localhost:8000/                                  — форма
# http://localhost:8000/index.php?api=1&text=Chișinău      — JSON
```

## Тесты

```bash
php test.php
```

## Лицензия

Apache License 2.0 — см. [LICENSE](LICENSE). © 2026 ivanm696
