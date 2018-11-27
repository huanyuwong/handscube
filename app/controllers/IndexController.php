<?php

namespace App\Controllers;

use Handscube\Facades\Redis;
use Handscube\Kernel\Controller;
use Handscube\Kernel\Request;

class IndexController extends Controller
{

    public function connect(Request $request)
    {
        // $data = [
        //     "author" => [
        //         "name" => "jim",
        //         "description" => "good",
        //     ],
        //     "article" => "this is article contents",
        // ];

        // $this->validate($data, [
        //     'author.name' => 'required',
        //     'author.description' => 'required',
        //     'article' => 'required',
        // ]);
    }

    public function queue()
    {
        for ($i = 0; $i < 10; $i++) {
            if ($this->dispatch(new \App\Tasks\SendMail($i))) {
                echo "$i - true\n";
            }
        }
    }

    public function getQueue()
    {
        if (Redis::llen('tasks')) {
            $task = unserialize(Redis::rpop('tasks'));
            ff($task);
        }
    }

    public function test()
    {
        // $this->response()->setContent("hello test!!")->send();
        // $this->response()->setContent('hi~dsfasf')->send();
        // print_r($this->app);
        // return "hello";
    }
}
