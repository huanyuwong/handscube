<?php

namespace App\Controllers;

use Handscube\Kernel\Controller;

class BaseController extends Controller {

    public function test(){
      echo "test from App BaseController";
    }

    public function printRequest(){
      print_r($this->app->getComponentsMap());
      echo "=========================================";
      print_r($this->app->arr);
      echo "==========================================";
      print_r($this->app->getComponentsMap());
    }

    public function showValue($val){
        echo "----------------\n";
        echo $this->app->request->$val;
    }
}