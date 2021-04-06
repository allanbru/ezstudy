<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Cards_history;
use App\Models\Cards_queue;
use App\Models\Group_module;
use App\Models\Group_user;
use App\Models\Module;
use App\Models\Modules_user;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ModuleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fav =  Modules_user::where('user',intval(Auth::id()))->get();
        global $favs;
        $favs = [];
        foreach($fav as $f){
            $favs[] = $f->module;
        }

        $modules = Module::where('author', intval(Auth::id()))
        ->WhereIn('id', $favs, 'or')
        ->orderBy('title', 'ASC')->paginate(9);

        return view('admin.modules.index', [
            'modules' => $modules 
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.modules.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only([
            'title',
            'icon',
            'public'
        ]);

        $validator = Validator::make($data, [
            'title' => ['required', 'string', 'max:18'],
            'icon' => ['string', 'max:100'],
            'public' => ['string']
        ]);

        if($validator->fails()){
            return redirect()->route("modules.create")
            ->withErrors($validator)
            ->withInput();
        }

        $module = new Module;
        $module->title = $data["title"];
        $module->icon = $data["icon"];
        $module->author = intval(Auth::id());
        $module->public = (isset($data["public"]) && $data["public"] !== null);
        $module->save();

        return redirect()->route("modules.show", ['module' => $module->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $module = Module::find($id);
        if($module){
            $loggedId = intval(Auth::id());
            $is_owner = $module->author === $loggedId;
            if($this->canAccessModule($loggedId, $id)){
                $is_fav = Modules_user::where([
                    ['user', '=', $loggedId],
                    ['module', '=', $id]
                ])->count() === 1;

                $elo_user = false;

                $history = Cards_history::where([
                    ["module", '=', $id],
                    ['user', '=', $loggedId]
                    ])
                    ->orderBy('created_at', 'DESC')
                    ->first();
                if($history){
                    $elo_user = $history->elo_user;
                }

                $tags = Tag::where("module", $id)->orderBy('title', 'ASC')->get();

                $cards = Card::where("module", $id)->orderBy('front', 'ASC')->paginate(11);

                return view('admin.modules.show', [
                    'module' => $module,
                    'cards' => $cards,
                    'tags' => $tags,
                    'is_owner' => $is_owner,
                    'is_fav' => $is_fav,
                    'elo_user' => $elo_user
                ]);
            }
        }

        return redirect()->route('admin');
        
    }

    public function practice($id)
    {
        $module = Module::find($id);
        if($module){
            $loggedId = intval(Auth::id());

            if($this->canAccessModule($loggedId, $id)){

                $elo_user = false;

                $history = Cards_history::where([
                    ["module", '=', $id],
                    ['user', '=', $loggedId]
                    ])
                    ->orderBy('created_at', 'DESC')
                    ->first();
                if($history){
                    $elo_user = $history->elo_user;
                }

                $cards = Card::where("module", $id)->orderBy('front', 'ASC')->get();

                return view('admin.modules.practice', [
                    'module' => $module,
                    'cards' => $cards,
                    'elo_user' => $elo_user
                ]);
            }
        }

        return redirect()->route('admin');
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $module = Module::find($id);
        $cards = Card::where("module", $id)->orderBy('front', 'ASC')->get();
        $tags = Tag::where("module", $id)->orderBy('title', 'ASC')->get();

        if($module && $module->author === intval(Auth::id())){
            return view("admin.modules.edit", [
                'module' => $module,
                'cards' => $cards,
                'tags' => $tags
            ]);
        }

        return redirect()->route("modules.index");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $module = Module::find($id);

        if($module && $module->author === intval(Auth::id())){
            $data = $request->only([
                'title',
                'icon',
                'public'
            ]);
    
            $validator = Validator::make($data, [
                'title' => ['required', 'string', 'max:18'],
                'icon' => ['string', 'max:100'],
                'public' => ['string']
            ]);

            if($validator->fails()){
                return redirect()->route("modules.edit", [
                    'module' => $id
                ])
                ->withErrors($validator)
                ->withInput();
            }

            $module->title = $data['title'];
            $module->icon = $data['icon'];
            $module->public = (isset($data["public"]) && $data["public"] !== null);

            
            if(count($validator->errors()) > 0){
                return redirect()->route("modules.edit", ["module" => $id])
                ->withErrors($validator);
            }

            $module->save();
        }
        return redirect()->route("modules.show", ['module' => $module->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $module = Module::find($id);
        
        if($module && $module->author === intval(Auth::id())){
            Cards_queue::where('module', $id)->delete();

            $module->delete();
        }

        return redirect()->route("modules.index");
    }

    public function search(){
        return view('admin.modules.search', [
            'default' => ""
        ]);
    }

    public function search2(Request $request){
        $data = $request->only(['menu-search-input']);
        return view('admin.modules.search', [
            'default' => $data['menu-search-input']
        ]);
    }

    public function searchDB(Request $request){
        $array = [
            'error' => '',
            'result' => ''
        ];

        $title = $request->input("title");
        if(strlen($title)){
            $result = Module::where('public', 1)->
            where('title', 'like', $title . "%")
            ->limit(9)
            ->get();
            $found = [];
            foreach($result as $obj){
                $obj["link"] = route('modules.show', ['module' => $obj["id"]]);
                $found[] = $obj;
            }
            $array['result'] = $found;            
        }else{
            $array['error'] = 9;
        }
        
        echo json_encode($array);
        exit;
        
    }

    public function toggleFavorite($id){
        $module = Module::find($id);
        if($module){
            $is_fav = Modules_user::where([
                ['user', '=', intval(Auth::id())],
                ['module', '=', $id]
            ])->count() === 1;
            if($is_fav){
                Modules_user::where([
                    ['user', '=', intval(Auth::id())],
                    ['module', '=', $id]
                ])->delete();
                Cards_queue::where([
                    ['user', '=', intval(Auth::id())],
                    ['module', '=', $id]
                ])->delete();
                echo json_encode(-1);
                exit;
            }else{
                $fav = new Modules_user;
                $fav->user = intval(Auth::id());
                $fav->module = $id;
                $fav->save();
                echo json_encode(1);
                exit;
            }
            echo json_encode(0);
            exit;
        }
    }

    protected function canAccessModule($user, $module_id){
        $module = Module::find($module_id);
        if($module){
            if($module->public || $module->author === intval($user)) return true;
            
            $uGroups = [];
            $groups = Group_user::where("user", $user)->get();
            foreach($groups as $g){
                $uGroups[] = $g->grupo;
            }

            $count = Group_module::where('module', $module_id)->whereIn('grupo', $uGroups)->count();
            if($count > 0) return true;
        }
        return false;
        
    }
}
