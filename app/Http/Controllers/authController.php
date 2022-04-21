<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
// use Tymon\JWTAuth\Contracts\JWTSubject;

class authController extends Controller
{
    //redirect to google page
    
    public function google_redirect()
    {
        return [
            'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl(),
        ];
    }
    // public function login()
    // {
    //     $credentials = request(['email', 'password']);

    //     if (! $token = auth()->attempt($credentials)) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     return $this->respondWithToken($token);
    // }
    // //google callback
    public function google_callback()
    {

        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = null;
        DB::transaction(function () use ($googleUser, &$user) {
            $socialAccount = User::firstOrNew(
                ['email' => $googleUser->getEmail(), 'provider' => 'google']
            );
            $user = User::where('email', $googleUser->email)->first();
            if ($user) {
                $user->update([
                    'avatar' => $googleUser->avatar,
                    'remember_token' => $googleUser->token,
                ]);
            } else {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'avatar' => $googleUser->avatar,
                    'remember_token' => $googleUser->token,
                ]);
            }
        });
        return [
            'name' => $googleUser->name,
            'remember_token' => $googleUser->token,
        ];
    }
  
}
