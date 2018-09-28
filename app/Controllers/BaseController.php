<?php

namespace App\Controllers;

use Handscube\Kernel\Controller;

class BaseController extends Controller {

    public function test(){
      echo "test from App BaseController";
    }
}