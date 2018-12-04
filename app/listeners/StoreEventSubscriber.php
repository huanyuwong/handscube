<?php

namespace App\Listeners;

use App\Events\UserStore;

class StoreEventSubscriber
{

    public function onUserStore($event)
    {
        echo "> User $event->trigger Store Listener trigger.\n";
    }

    public function onUserLogin($event)
    {
        echo "> User Login Listener trigger.\n";
    }

    public function onPostStore($event)
    {
        echo ">> Post Store Listener trigger.\n";
    }

    public function subscribe($event)
    {
        $event->on(
            '\Handscube\Kernel\Events\PostComplete',
            'App\Listeners\StoreEventSubscriber@onPostStore'
        );
        $event->listen(
            UserStore::class,
            [
                'App\Listeners\StoreEventSubscriber@onUserLogin',
                'App\Listeners\StoreEventSubscriber@onUserStore',
            ]
        );
    }
}
