<?php

namespace App\Stations;

use Handscube\Kernel\Station;

class HtmlStation extends Station
{

    public function handle(\Handscube\Kernel\Request $request)
    {

        if ($request->requestType === "get") {
            exit("Station cant get");
        }
    }
}
