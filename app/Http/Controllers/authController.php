<?php

namespace App\Http\Controllers;

use JWTAuth;
use Auth;
use App\Exceptions\Handler;
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

    //  public function authenticate()
    // {
    //     $credentials = Socialite::driver('google')->stateless()->user();
    //     //Request is validated
    //     //Creat token
    //     try {
    //         if (! $token = JWTAuth::attempt($credentials)) {
    //             return response()->json([
    //             	'success' => false,
    //             	'message' => 'Login credentials are invalid.',
    //             ], 400);
    //         }
    //     } catch (JWTException $e) {
    // 	return $credentials;
    //         return response()->json([
    //             	'success' => false,
    //             	'message' => 'Could not create token.',
    //             ], 500);
    //     }

 		//Token created, return with success response and jwt token
    //     return response()->json([
    //         'success' => true,
    //         'token' => $token,
    //     ]);
    // }


    //google callback
    public function google_callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        // $token = "";
        // dd($googleUser);
        $user = Null;
        // $user_create;
        DB::transaction(function () use ($googleUser, &$user, &$user_create) {
            $user = User::where('email', $googleUser->email);
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->user->name,
                    'email' => $googleUser->user->email,
                    // 'avatar' => $googleUser->user->picture,
                    // 'remember_token' => $token,
                ]);
            }
        });
         // generating JWT user tokens
            if (! $token = JWTAuth::attempt($user)) {
                return response()->json([
                    'success' => true,
                    'message' => 'user exists.',
                ], 200);
            }else{
                return response()->json([
                    'success' => true,
                    'data' => [
                        'email' => $user->email,
                        'token' => $token
                    ],
                    'message' => 'login successfull'
                ], 200);
            }
    }
}
