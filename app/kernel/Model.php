<?php

namespace App\Kernel;

use Handscube\Kernel\Model as KernelModel;

class Model extends KernelModel
{
    const EXCEPT_MODEL_POLICY = [
        'create',
    ];
}
