<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function __construct(){
        $this->middleware("auth");
        $this->middleware("can:edit-settings");
    }

    public function index(){
        
        $settings = [];

        $dbsettings = Setting::get();

        foreach($dbsettings as $setting){
            $settings[$setting['name']] = $setting['content'];
        }
        
        return view("admin.settings.index",[
            'settings' => $settings
        ]);
    }

    public function save(Request $request){

        $data = $request->only([
            'title',
            'subtitle',
            'email',
            'facebook',
            'twitter',
            'instagram',
            'about',
            'termsofuse',
            'privacypolicy',
            'bgcolor',
            'textcolor'
        ]);

        $validator = $this->validator($data);

        if($validator->fails()){
            return redirect()->route('settings')
            ->withErrors($validator);
        }

        foreach($data as $item=>$value){

            Setting::where('name', $item)->update([
                'content' => $value
            ]);

        }
        
        return redirect()->route("settings")
        ->with('warning', 'Informações atualizadas com sucesso.');

    }

    protected function validator($data){
        return Validator::make($data, [
            'title' => ['string', 'max:100'],
            'subtitle' => ['string', 'max:500'],
            'email' => ['string', 'email', 'max:100'],
            'bgcolor' => ['string', 'regex:/#[A-Z0-9]{6}/i'],
            'textcolor' => ['string', 'regex:/#[A-Z0-9]{6}/i'],
        ]);
    }
}
