<?php

namespace App\Controllers\Admin;

use App\Models\Option;
use App\Models\User;
use Handscube\Kernel\Controller;
use Handscube\Kernel\PDO;
use Handscube\Kernel\Request;

class IndexController extends Controller
{

    public static function model()
    {

    }

    public function user(Request $request, User $user, Option $option)
    {
        print_r($user->id);
        echo "\n";
        print_r($option->id);
        exit();
        // $response = (new Response())->setStatusCode(Response::HTTP_NOT_FOUND);
    }

    // public function __get($name)
    // {
    //     $getter = 'get' . ucfirst($name);
    //     if (method_exists($this, $getter)) {
    //         echo "exits\n";
    //         return $this->$getter();
    //     }
    // }

    // public function getApp()
    // {
    //     return H::$app;
    // }

    public function connect()
    {
        // return $this->app->router->route('admin', ['1', '2']);
        $this->redirect('testsadf');
    }

    public function test(Request $request, $id, $name)
    {

    }

    public function pdo()
    {
        $pdo = new PDO([
            "driver" => "mysql",
            "host" => "127.0.0.1",
            "database" => "test",
            "port" => 3306,
        ]);
        ff($pdo->dsnInString);
    }
}
