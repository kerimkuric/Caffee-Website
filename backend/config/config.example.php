<?php
/**
 * Copy this file to config.php and fill in real values.
 * config.php is gitignored and must never be committed.
 */
class Config
{
    public static function DB_HOST(): string
    {
        return 'localhost';
    }

    public static function DB_PORT(): int
    {
        return 3306;
    }

    public static function DB_NAME(): string
    {
        return 'kod_kere';
    }

    public static function DB_USER(): string
    {
        return 'root';
    }

    public static function DB_PASSWORD(): string
    {
        return '';
    }

    public static function JWT_SECRET(): string
    {
        return 'change-this-to-a-long-random-string';
    }
}
