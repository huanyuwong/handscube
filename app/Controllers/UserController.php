<?php

namespace App\Controllers;

use Handscube\Kernel\Controller;

class UserController extends Controller
{

    public function index()
    {
        echo "index";
    }

    public function show($id)
    {
        // return $this->response("show $id");
    }

    public function update($id)
    {
        return $this->response($id);
    }

}
