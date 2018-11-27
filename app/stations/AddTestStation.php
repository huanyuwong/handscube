<?php

namespace App\Stations;

use Handscube\Kernel\Station;

class AddTestStation extends Station
{

    public function handle(\Handscube\Kernel\Request $request, $admin = '')
    {
        echo __CLASS__;
    }
}
