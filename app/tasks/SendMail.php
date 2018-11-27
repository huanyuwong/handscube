<?php

namespace App\Tasks;

use Handscube\Kernel\Queue\Task;

class SendMail extends Task
{
    protected $user;

    protected $name = 'sendMail';

    public function handle()
    {
        print_r($this->taskId);
    }
}
