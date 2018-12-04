<?php

// namespace Configs;
return [

    "components" => [
        "register" => [
            "router" => Handscube\Kernel\Route::class,
            "dev" => Handscube\Dev\DevComponent::class,
        ],
        "defer" => [
            \Handscube\Assistants\Arr::class,
        ],
    ],
    "router" => [
        "auto_parse" => 0,
    ],
    "guards" => [
        // "appGuard" =>
    ],
    "session_expire" => 5,
    "session_driver" => '',
    "session_driver_table" => 'session',
    "open_cross_domain" => true,
    "domain_config" => [
        "allow_domains" => [
            "http://localhost:8082",
        ],
        "allow_headers" => [
            "Content-Type",
            "X-Requested-With",
            "Access-Token",
        ],
        "allow_methods" => [
            "PUT,POST,GET,DELETE,OPTIONS",
        ],
        "expose_headers" => [

        ],
        "enable_cookie" => "true",

    ],
    "cross_key" => "",

];
