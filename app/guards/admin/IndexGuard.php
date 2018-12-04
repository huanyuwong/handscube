<?php

namespace App\Guards\Admin;

use App\Models\User;
use Handscube\Kernel\Guards\ControllerGuard;
use Handscube\Kernel\Route;

class IndexGuard extends ControllerGuard
{

    protected $register = [
        // AddTestStation::class,
    ];

    protected $only = [
        "user",
    ];

    protected $specified = [
        // "user" => ChangeIdStation::class,
    ];

    public function model()
    {
        parent::model();
        Route::bind("user", function ($value) {
            return User::find(1);
        });
        // Route::bind("option", function ($value) {
        //     return Option::find(1);
        // });
    }

    public function userGuard(User $user)
    {

        // $hash = Encrypt::hash('sha256', 'handscube_framework');
        // $header = [
        //     'name' => 'access_secret',
        //     'rid' => mt_random(),
        // ];
        // $token = \Handscube\Kernel\CrossGate::signToken();

        // $data = base64_encode(\urlencode('_key_handscube&' . time() . '_key_'));
        // ff(base64_decode($data));
    }

    // public function handle(Request $request)
    // {
    //     return parent::handle($request);
    // }

    public function connectGuard()
    {
        // ff("guard");
    }
}
