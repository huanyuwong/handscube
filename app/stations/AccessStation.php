<?php

namespace App\Stations;

use Handscube\Kernel\CrossGate;
use Handscube\Kernel\Response;
use Handscube\Kernel\Station;

class AccessStation extends Station
{

    public function handle(\Handscube\Kernel\Request $request)
    {
        if ($request->requestType == 'get') {
            if (!isset($request->header['Origin'])) {
                return;
            }
        }
        if (CrossGate::verifyAccessToken($request->header['Access-Token'], $request->input()) === true) {
            return;
        } else {
            (new Response())->withJson(['status' => Response::HTTP_UNAUTHORIZED, 'message' => Response::$statusTexts[Response::HTTP_UNAUTHORIZED]])->send();
        }
    }
}
