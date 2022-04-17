<?php

namespace App\Http\Controllers;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class authController extends Controller
{
    //redirect to google page
    public function google_redirect()
    {
        return Response::json([
            'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    //google callback
    public function google_callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = null;
        DB::transaction(function () use ($googleUser, &$user) {
            $socialAccount = User::firstOrNew(
                ['email' => $googleUser->getEmail(), 'provider' => 'google']
            );
            if (!($user = $socialAccount->user)) {
                $user = User::create([
                    'email' => $googleUser->getEmail(),
                    'name' => $googleUser->getName(),
                    'provider' => $googleUser->getProvider(),
                    'avatar' => $googleUser->getAvatar(),
                    'remember_token' => $googleUser->getToken(),
                ]);
                $socialAccount->save();
            }
        });
        return Response::json([
            'user' => new UserResource($user),
            'google_user' => $googleUser,
        ]);
    }

    //Storing of data in database
    // private function createorupdateuser($data, $provider)
    // {
    //     $user = User::where('email', $data->email)->first();
    //     if ($user) {
    //         $user->update([
    //             'provider' => $provider,
    //             'avatar' => $data->avatar,
    //             'remember_token' => $data->token
    //         ]);
    //     } else {
    //         $user = User::create([
    //             'name' => $data->name,
    //             'email' => $data->email,
    //             'provider' => $provider,
    //             'avatar' => $data->avatar,
    //             'remember_token' => $data->token
    //         ]);
    //     }
    //     Auth::login($user);
    // }
}
