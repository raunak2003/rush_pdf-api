<?php

namespace App\Http\Controllers;

use App\Models\User;
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

    // public function getJWTIdentifier()
    // {
    //     return $this->getKey();
    // }

    //google callback
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
            'remember_token' =>$googleUser->token,
        ];
    }

}
