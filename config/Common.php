<?php
namespace Config;

return [
	"preloading" => [
		"objects" => [
			"App\Kernel\Request",

		],
		"service" => [
			"kernelService" => [
				"App\Boot\AuthService",
				"App\Boot\InteruptService",
			],
		],
	],
	"events" => ['strict'],

];