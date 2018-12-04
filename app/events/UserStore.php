<?php

namespace App\Events;

use App\Models\User;
use Handscube\Kernel\Events\Event;

class UserStore extends Event
{

    public $user;

    public function __construct(User $user)
    {
        parent::__construct($user);
        $this->user = $user;
    }
}
