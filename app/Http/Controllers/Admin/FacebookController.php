<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Exception;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;



class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookSignin()
    {
        try {
    
            $user = Socialite::driver('facebook')->user();
            $facebookId = User::where('facebook_id', $user->id)->first();
     
            if($facebookId){
                Auth::login($facebookId);
                return redirect('/painel');
            }else{
                $createUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'facebook_id' => $user->id,
                    'password' => encrypt('john123')
                ]);
    
                Auth::login($createUser);
                return redirect('/dashboard');
            }
    
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
    }
}