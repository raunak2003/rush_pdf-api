<?php

namespace App\Http\Controllers;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Auth;
class authController extends Controller
{
    //login with google api
    public function login()
    {
        return view('login');
    }

    //redirect to google page
    public function google_redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    //redirect to dashboard
    public function google_callback()
    {
        $user = Socialite::driver('google')->user();
        $this->createorupdateuser($user, 'google');
        return redirect('dashboard');
    }

    //Storing of data in database
    private function createorupdateuser($data, $provider)
    {
        $user = User::where('email', $data->email)->first();
        if ($user) {
            $user->update([
                'provider' => $provider,
                'avatar' => $data->avatar,
            ]);
        } else {
            $user = User::create([
                'name' => $data->name,
                'email' => $data->email,
                'provider' => $provider,
                'avatar' => $data->avatar,
            ]);
        }
        Auth::login($user);
    }
}
