<?php

namespace App\Guards;

use Handscube\Kernel\Guards\ControllerGuard;
use Handscube\Kernel\Request;

class IndexGuard extends ControllerGuard
{
    protected $register = [

    ];

    protected $except = [

    ];

    protected $only = [

    ];

    protected $specified = [
        // "connect" => ChangeIdStation::class,
    ];

    public function handle(Request $reqeust, $fnParams = [], $stations = [])
    {
        parent::handle($reqeust, $fnParams, $stations);
    }

    public function indexGuard()
    {

    }

    public function loginGuard()
    {

    }

    public function connectGuard(Request $reqeust)
    {

    }
}
