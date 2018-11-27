<?php

require_once __DIR__ . "/../boot/Boot.php";

define("__APP_PATH__", __DIR__ . "/../app/");

$app = Handscube\Handscube::run(__APP_PATH__);
$request = $app->singleton(Handscube\Kernel\Request::class);
$response = $app->handle($request);
$app->send($response);
