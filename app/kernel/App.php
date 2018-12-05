<?php

namespace App\Kernel;

use App\Kernel\AppGuard;
use Handscube\Kernel\Application;

class App extends Application
{
    //Rewrite app guard.
    public function bindGuard()
    {
        return AppGuard::class;
    }
}
