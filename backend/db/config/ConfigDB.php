<?php

namespace backend\db\config;
class ConfigDB
{
    public static function getConfig() {
        return [
            'host' => 'localhost',
            'user' => 'root',
            'password' => 'root',
            'database' => 'roulburo',
        ];
    }
}