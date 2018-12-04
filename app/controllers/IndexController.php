<?php

namespace App\Controllers;

use Handscube\Facades\Redis;
use Handscube\Kernel\Controller;
use Handscube\Kernel\Request;
use Handscube\Kernel\View;

class IndexController extends Controller
{

    public function welcome(Request $request)
    {
        return (new View('welcome'));
    }

    public function getQueue()
    {
        if (Redis::llen('tasks')) {
            $task = unserialize(Redis::rpop('tasks'));
            ff($task);
        }
    }

}
