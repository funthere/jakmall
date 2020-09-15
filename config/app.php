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
    ],
];
