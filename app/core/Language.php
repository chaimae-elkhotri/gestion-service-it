<?php

class Language
{
    private static array $translations = [];

    private static array $supportedLanguages = [
        'fr',
        'ar'
    ];

    public static function init(): void
    {
        $defaultLanguage = defined('DEFAULT_LANGUAGE')
            ? DEFAULT_LANGUAGE
            : 'fr';

        $language =
            $_GET['lang']
            ?? $_SESSION['lang']
            ?? $_COOKIE['lang']
            ?? $defaultLanguage;

        if (
            !in_array(
                $language,
                self::$supportedLanguages,
                true
            )
        ) {
            $language = $defaultLanguage;
        }

        $_SESSION['lang'] = $language;

        setcookie(
            'lang',
            $language,
            [
                'expires' => time() + (365 * 24 * 60 * 60),
                'path' => '/',
                'secure' => (
                    isset($_SERVER['HTTPS'])
                    && $_SERVER['HTTPS'] !== 'off'
                ),
                'httponly' => false,
                'samesite' => 'Lax'
            ]
        );

        $file = __DIR__
            . '/../lang/'
            . $language
            . '.php';

        if (is_file($file)) {
            self::$translations = require $file;
        } else {
            self::$translations = [];
        }
    }

    public static function get(): string
    {
        $defaultLanguage = defined('DEFAULT_LANGUAGE')
            ? DEFAULT_LANGUAGE
            : 'fr';

        return $_SESSION['lang']
            ?? $defaultLanguage;
    }

    public static function isArabic(): bool
    {
        return self::get() === 'ar';
    }

    public static function isRtl(): bool
    {
        return self::isArabic();
    }

    public static function translate(
        string $key,
        array $replacements = []
    ): string {
        $keys = explode('.', $key);

        $value = self::$translations;

        foreach ($keys as $part) {
            if (
                !is_array($value)
                || !array_key_exists($part, $value)
            ) {
                return $key;
            }

            $value = $value[$part];
        }

        if (!is_string($value)) {
            return $key;
        }

        foreach ($replacements as $name => $replacement) {
            $value = str_replace(
                ':' . $name,
                (string)$replacement,
                $value
            );
        }

        return $value;
    }
}

if (!function_exists('t')) {
    function t(
        string $key,
        array $replacements = []
    ): string {
        return Language::translate(
            $key,
            $replacements
        );
    }
}