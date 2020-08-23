<?php

return [
    'providers' => [
        \Jakmall\Recruitment\Calculator\History\CommandHistoryServiceProvider::class,
    ],

    'connections' => [
        'sqlite' => [
            'driver' => 'sqlite',
            // 'url' => 'database.sqlite',
            'database' => 'sqlite.db',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ],

        'mysql' => [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'port' => '3306',
            'database' => 'jakmall',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],
    ],
];
