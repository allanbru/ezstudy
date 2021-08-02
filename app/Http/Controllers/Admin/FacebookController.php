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

                $pw = time();

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

    public function dataDeletionCallback(Request $request)
    {
        $req = $request->get('signed_request');
        $data = $this->parse_signed_request($req);
        $user_id = $data['user_id'];

        $u = User::where('facebook_id', $user_id)->forceDelete();

        // here will check if the user is deleted
        $isDeleted = User::where('facebook_id', $user_id)->get();
        
        $code = base64_encode($facebook_id);

        if (!count($isDeleted)) {
            echo json_encode([
                'url' => route('facebook.dataDeletionStatus', ['code' => $code]), 
                'confirmation_code' => $code
            ]);
            exit;
        }

        return response()->json([
            'message' => 'operation not successful'
        ], 500);
    }

    public function dataDeletionStatus($code)
    {

        $user_id = base64_decode($code);
        
        $u = User::where('facebook_id', $user_id)->get();
        
        if (!count($u)) {
            echo "Usuário não consta no sistema.";
            exit;
        }
        echo "Usuário ainda não deletado. Confirmação pendente.";
        exit;

    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password' => ['required'],
        ]);
    }

    protected function parse_signed_request($signed_request) {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        $secret = config('service.facebook.client_secret'); // Use your app secret here

        // decode the data
        $sig = $this->base64_url_decode($encoded_sig);
        $data = json_decode($this->base64_url_decode($payload), true);

        // confirm the signature
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            error_log('Bad Signed JSON signature!');
            return null;
        }

        return $data;
    }

    protected function base64_url_decode($input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }
}
