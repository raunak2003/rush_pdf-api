<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

class authController extends Controller
{
    //google callback
    public function google_callback(Request $req)
    {
        try {
            $Googletoken = $req->tokenId;
            $googleUser = Socialite::driver('google')->stateless()->userFromToken($Googletoken);
            $user = null;
            DB::transaction(function () use ($googleUser, &$user) {
                $user = User::where('email', $googleUser->email)->first();
                if (!$user) {
                    $user = User::create([
                        'provider' => 'google',
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'avatar' => $googleUser->avatar,
                    ]);
                }
            });
            try {
                // verify the credentials and create a token for the user
                if (!$token = $this->guard()->fromUser($user)) {
                    return response()->json([
                        'error' => 'unauthorized',
                    ], 401);
                }
            } catch (JWTException $e) {
                // something went wrong
                return response()->json(['error' => 'could_not_create_token'], 500);
            }
            return response()->json([
                'email' => $user->email,
                'token' => $this->respondWithToken($token),
            ]);

        } catch (\Throwable$th) {
            return response()->json(['error' => 'Some error occured'], 500);
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
        ]);
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
