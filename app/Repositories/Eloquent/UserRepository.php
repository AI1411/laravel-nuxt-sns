<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Contracts\IUser;

class UserRepository implements IUser
{
    public function all()
    {
        return User::all();
    }
}
