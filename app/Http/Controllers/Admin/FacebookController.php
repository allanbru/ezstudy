<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookSignIn()
    {
        try {
    
            $user = Socialite::driver('facebook')->user();
            $facebookId = User::where('facebook_id', $user->id)->first();
     
            if($facebookId){
                Auth::login($facebookId);
                return redirect('/painel');
            }else{

                $pw = " " + time();

                $data = [
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => $pw
                ];

                $validator = $this->validator($data);

                if($validator->fails()){
                    return redirect()->route('login')
                    ->withErrors($validator)
                    ->withInput();
                }
                
                $createUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'facebook_id' => $user->id,
                    'password' => Hash::make($pw)
                ]);
    
                Auth::login($createUser);
                return redirect('/painel');
            }
    
        } catch (Exception $exception) {
            dd($exception->getMessage());
        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);
    }
}
