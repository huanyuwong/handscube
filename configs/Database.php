<?php

return [
    "mysql" => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'testdb',
        'username' => 'root',
        'password' => 'rootpsd',
        'unix_socket' => '',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => null,
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
