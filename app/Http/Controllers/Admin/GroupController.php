<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Group;
use App\Models\Group_link;
use App\Models\Group_message;
use App\Models\Group_module;
use App\Models\Group_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
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
        $loggedId = intval(Auth::id());
        $member_groups = Group_user::where('user', $loggedId)->join('groups', 'group_users.grupo', '=', 'groups.id')->paginate(5);
         
        return view("admin.groups.home", [
            'groups' => $member_groups
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.groups.create');
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
            'name',
            'description',
            'public'
        ]);

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:50'],
            'description' => ['required', 'string'],
            'public' => ['string']
        ]);

        if($validator->fails()){   
            return redirect()->route("groups.create")
            ->withErrors($validator)
            ->withInput();
        }
        
        $group = new Group;
        $group->name = strip_tags($data["name"]);
        $group->description = strip_tags($data["description"]);
        $group->public = (isset($data["public"]) && $data["public"] !== null);
        $group->save();

        $guser = new Group_user;
        $guser->grupo = $group->id;
        $guser->user = intval(Auth::id());
        $guser->privileges = 3;
        $guser->save();

        return redirect()->route("groups.show", ["group" => $group->id]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $group = Group::find($id);
        if($group){
            $loggedId = intval(Auth::id());

            $members = Group_user::where('grupo', $id)->join('users', 'group_users.user', '=', 'users.id')->get();
            $is_member = Group_user::where('grupo', $id)->where('user', $loggedId)->count() > 0;
            $modules = Group_module::where('grupo', $id)->join('modules', 'group_modules.module', '=', 'modules.id')->paginate(9, ['*'], 'modules');

            $modules2 = Group_module::where('grupo', $id)->join('modules', 'group_modules.module', '=', 'modules.id')->get();
            $modules_ids=[];
            foreach($modules2 as $m){
                $modules_ids[] = $m->id;
            }
            $aval_modules = Module::where("author", $loggedId)->whereNotIn('id', $modules_ids)->get();

            $messages = Group_message::where("grupo", $id)->join("users", "group_messages.user", "=", "users.id")->orderBy("group_messages.created_at", "DESC")->paginate(5, ['*'], 'messages');
            
            if(!$is_member && !$group->public){
                return redirect()->route("admin");
            }

            return view('admin.groups.show', [
                'group' => $group,
                'members' => $members,
                'modules' => $modules,
                'loggedId' => $loggedId,
                'is_member' => $is_member,
                'aval_modules' => $aval_modules,
                'messages' => $messages,
                'canAddMembers' => $this->canAddMember($loggedId, $id),
                'canEditGroup' => $this->canEditGroup($loggedId, $id)
            ]);
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
        $group = Group::find($id);

        if($group && $this->canEditGroup(intval(Auth::id()), $id)){
            return view("admin.groups.edit", [
                'group' => $group
            ]);
        }

        return redirect()->route("groups.index");
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
        $group = Group::find($id);

        if($group && $this->canEditGroup(intval(Auth::id()), $id)){
            $data = $request->only([
                'name',
                'description',
                'public'
            ]);
    
            $validator = Validator::make($data, [
                'name' => ['required', 'string', 'max:50'],
                'description' => ['required', 'string'],
                'public' => ['string']
            ]);
    
            if($validator->fails()){   
                return redirect()->route("groups.edit", [
                    'group' => $id
                ])
                ->withErrors($validator)
                ->withInput();
            }

            $group->name = $data['name'];
            $group->description = strip_tags($data['description']);
            $group->public = (isset($data["public"]) && $data["public"] !== null);
            
            if(count($validator->errors()) > 0){
                return redirect()->route("groups.edit", ["group" => $id])
                ->withErrors($validator);
            }

            $group->save();
        }
        return redirect()->route("groups.show", ['group' => $group->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function genlink($id){
        $loggedId = intval(Auth::id());
        if($this->canAddMember($loggedId, $id)){
            $link = "";
            $validLink = Group_link::where("grupo", $id)->first();
            if($validLink){
                $link = $validLink->link;
            }else{
                $link = hash("adler32", $loggedId . "_" . $id . "_" . date("d-m-Y"));
                $gl = new Group_link;
                $gl->grupo = $id;
                $gl->link = $link;
                $gl->save();
            }
            
            return json_encode([
                "STATUS" => 1,
                "LINK" => route("groups.join", ['link' => $link])
            ]);
        }
    }

    public function join($link){
        $group = Group_link::where("link", $link)->first();
        if($group){
            $loggedId = intval(Auth::id());
            $inGroup = Group_user::where("user", $loggedId)->where("grupo", $group->grupo)->count();
            if(!$inGroup){
                $guser = new Group_user;
                $guser->grupo = $group->grupo;
                $guser->user = $loggedId;
                $guser->privileges = 0;
                $guser->save();
            }
            
            return redirect()->route("groups.show", ['group' => $group->grupo]);
        }
        return redirect()->route("admin");
    }

    public function joinMember($group_id){
        $group = Group::find($group_id);
        if($group && $group->public){
            $loggedId = intval(Auth::id());
            $inGroup = Group_user::where("user", $loggedId)->where("grupo", $group->id)->count();
            if(!$inGroup){
                $guser = new Group_user;
                $guser->grupo = $group->id;
                $guser->user = $loggedId;
                $guser->privileges = 0;
                $guser->save();
            }
            
            return redirect()->route("groups.show", ['group' => $group->id]);
        }
        return redirect()->route("groups.index");
    }

    public function removeMember($id, $group_id){

        $gUser = Group_user::where("user", $id)->where("grupo", $group_id)->first();
        if($gUser){

            $loggedId = intval(Auth::id());
            if($loggedId === intval($id)){
                $gUser->delete();
                return redirect()->route("groups.index");
            }

            if($this->canAddMember($loggedId, $group_id)){
                $gUser->delete();
                return json_encode(["STATUS" => 1]);
            }
            
        }
        return redirect()->route("admin");
    }

    public function addModule($group_id, $module_id){
        $module = Module::find($module_id);
        $group = Group::find($group_id);
        if($group && $module){
            $loggedId = intval(Auth::id());
            $inGroup = Group_user::where("user", $loggedId)->where("grupo", $group->id)->count();
            if($inGroup){
                if($module->author === $loggedId){
                    $gModule = new Group_module;
                    $gModule->grupo = $group_id;
                    $gModule->module = $module_id;
                    $gModule->save();
                    return redirect()->route("groups.show", ['group' => $group->id]);
                }
            }
        }
        return redirect()->route("groups.show", ['group' => $group_id]);
    }

    public function writeMsg($group_id, Request $request){
        $data = $request->only(['msg']);

        $loggedId = intval(Auth::id());

        $gUser = Group_user::where('grupo', $group_id)->where('user', $loggedId)->first();
        if($gUser){
            $msg = new Group_message;
            $msg->user = $loggedId;
            $msg->grupo = $group_id;
            $msg->message = strip_tags($data["msg"]);
            $msg->save();
        }
        return redirect()->route("groups.show", ["group" => $group_id]);
    }

    protected function canAddMember($user, $group){
        $data = Group_user::where("user", $user)->where("grupo", $group)->first();
        if($data && $data->privileges >= 1) return true;
        return false;
    }

    protected function canEditGroup($user, $group){
        $data = Group_user::where("user", $user)->where("grupo", $group)->first();
        if($data && $data->privileges >= 3) return true;
        return false;
    }
}
