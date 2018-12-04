<?php

namespace App\Models;

use App\Kernel\Model;

class User extends Model
{

    protected $table = "user";

    public function updatePolicy(User $user, Post $post)
    {
        return $user->id === $post->user_id;
    }
}
