<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Auth;

class authController extends Controller
{
    public function login(){
        return view('login');
    }

    public function google_redirect(){
        return Socialite::driver('google')->redirect();
    }

    public function google_callback(){
        $user = Socialite::driver('google')->user();
        $this->createorupdateuser($user,'google');
        return redirect('dashboard');
    }

    private function createorupdateuser($data,$provider){
        $user = User::where('email',$data->email)->first();
        if ($user) {
           $user->update([
            'provider'=>$provider,
            'avatar'=>$data->avatar
           ]);
           if($user){
            return ["Response"=>"User Successfully Updated"];
        }else{
            return ["Response"=>"Sorry Some error occured"];    
        }
        }else{
            $user = User::create([
                'name'=> $data->name,
                'email'=> $data->email,
                'provider'=>$provider,
                'avatar'=>$data->avatar
            ]);
            if($user){
                return ["Response"=>"User Successfully created"];
            }else{
                return ["Response"=>"Sorry Some error occured"];    
            }
        }

        Auth::login($user);
    }
}
