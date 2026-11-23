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

    public static function SHOP_ADDRESS(): string
    {
        return 'Ferhadija, Sarajevo, Bosnia and Herzegovina';
    }

    public static function NOMINATIM_USER_AGENT(): string
    {
        return 'KodKere/1.0 (kerim.kuric@stu.ibu.edu.ba)';
    }

    /**
     * Optional LocationIQ key. Leave as the placeholder to use
     * OpenStreetMap Nominatim (no key required).
     */
    public static function LOCATIONIQ_API_KEY(): string
    {
        return 'YOUR_LOCATIONIQ_KEY';
    }
}
