<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function getMe()
    {
        if (auth()->check()) {
            return response()->json(['user' => auth()->user()], 200);
        }
        return response()->json(null, 200);
    }
}
