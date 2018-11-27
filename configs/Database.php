<?php

return [
    "mysql" => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'testdb',
        'username' => 'root',
        'password' => 'rootpsd',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
    ],

    "redis" => [
        "parameters" => [
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => 6379,
            'database' => 0,
        ],
        "options" => [

        ],
    ],
];
