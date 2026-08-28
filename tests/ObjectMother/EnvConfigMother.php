<?php

namespace Tests\ObjectMother;

use Jalejandro\DecampoacampoChallenge\Infrastructure\EnvConfig;

class EnvConfigMother
{
    public static function array(array $overrides = []): array
    {
        return array_merge([
            'PRECIO_USD' => '1000.0',
            'DB_HOST' => '127.0.0.1',
            'DB_USER' => 'root',
            'DB_PORT' => '3306',
            'DB_NAME' => 'decampoacampo_challenge',
            'DB_PASS' => 'root',
        ], $overrides);
    }

    public static function create(array $overrides = []): EnvConfig
    {
        return new EnvConfig(self::array($overrides));
    }
}