<?php

namespace App\Suppliers;

use Handscube\Kernel\Events\PostComplete;
use Handscube\Kernel\Listeners\AdminNotifination;
use Handscube\Kernel\Listeners\PostNotifination;

class ScheduleSupplier
{
    public static $listeners = [
        PostComplete::class => [
            PostNotifination::class,
            AdminNotifination::class,
        ],
        'App\Events\UserStore' => AdminNotifination::class,
    ];

    public static $subscribers = [
        \App\Listeners\StoreEventSubscriber::class,
    ];

    public static $observers = [

    ];

    public static function boot()
    {

    }
}
