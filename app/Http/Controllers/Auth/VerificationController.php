<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class VerificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
//        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    //verifyメソッドをオーバーライド
    public function verify(Request $request, User $user)
    {
//        if (! URL::hasValidSignature($request)) {
//            return response()->json(['errors' => [
//                'message' => '無効なメールアドレスです'
//            ]], 422);
//        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['errors' => [
                'message' => 'メールアドレスは認証されています。'
            ]]);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return response()->json(['message' => 'メールアドレス認証が成功しました。'], 200);
    }

    public function resend(Request $request)
    {
        $this->validate($request, [
            'email' => ['email', 'required']
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['errors' => [
                'email' => 'そのアドレスのユーザーが見つかりません',
            ]], 422);
        }

        if (!$user->hasVerifiedEmail()) {
            return response()->json(['errors' => [
                'email' => 'すでに認証されています',
            ]], 422);
        }
        $user->sendEmailVerificationNotification();

        return response()->json(['status' => '認証リンクを送りました']);
    }
}
