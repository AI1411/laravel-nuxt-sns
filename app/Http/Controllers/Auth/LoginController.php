<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function attemptLogin(Request $request)
    {
        $token = $this->guard()->attempt($this->credentials($request));

        if (!$token) {
            return false;
        }

        //認証済ユーザーを取得
        $user = $this->guard()->user();

        if ($user instanceof MustVerifyEmail && $user->hasVerifiedEmail()) {
            return false;
        }

        //ユーザートークンをセット
        $this->guard()->setToken($token);

        return true;
    }

    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        //JWTからtokenを取得
        $token = (string)$this->guard()->getToken();
        $expiration = $this->guard()->getPayload()->get('exp');

        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'expire_in' => $expiration
        ]);
    }

    protected function sendFailedLoginResponse()
    {
        $user = $this->guard()->user();

        if ($user instanceof MustVerifyEmail && !$user->hasVerifiedEmail()) {
            return response()->json(['errors' => [
                'verification' => 'メールアドレスで認証する必要があります'
            ]]);
        }

        throw ValidationException::withMessages([
           $this->username() => "認証が失敗しました"
        ]);
    }

}
