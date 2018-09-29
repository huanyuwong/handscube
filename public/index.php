<?php


require_once __DIR__ . "/../boot/Boot.php";



define("__APP_PATH__",__DIR__ . "/../app/");


// exit();

$app = Handscube\Handscube::run(__APP_PATH__);

$r = $app->make("Handscube\\Dev\\TestController");
print_r($r);
$app->test();



