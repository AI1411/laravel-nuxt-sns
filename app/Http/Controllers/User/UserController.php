<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\Contracts\IUser;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $users;

    public function __construct(IUser $users)
    {
        $this->users = $users;
    }

    public function index()
    {
        $users = $this->users->withCriteria([
            new EagerLoad(['designs'])
        ])->all();

        return UserResource::collection($users);
    }
}
