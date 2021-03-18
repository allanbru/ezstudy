<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cards_tags;
use App\Models\Module;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($module)
    {
        $module = Module::find($module);
        
        if($module && $module->author === intval(Auth::id())){
            return view('admin.tags.create', [
                "module" => $module
            ]);
        }

        return redirect()->route("admin");
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
            'text',
            'module'
        ]);

        $validator = Validator::make($data, [
            'title' => ['required', 'string', 'max:100'],
            'text' => ['string'],
            'module' => ['required', 'int']
        ]);

        if($validator->fails()){   
            if($validator->fails()){
                return redirect()->route("tags.create", ["module" => intval($data["module"])])
                ->withErrors($validator)
                ->withInput();
            }
        }

        $module = Module::find(intval($data["module"]));
        
        if($module && $module->author === intval(Auth::id())){

            $doc = new \DOMDocument();
            $doc->loadHTML($data["text"]);
            $script_tags = $doc->getElementsByTagName('script');
            $length = $script_tags->length;
            for ($i = 0; $i < $length; $i++) {
                $script_tags->item($i)->parentNode->removeChild($script_tags->item($i));
            }
            $safe_text = $doc->saveHTML();

            $tag = new Tag;
            $tag->title = strip_tags($data["title"]);
            $tag->text = $safe_text;
            $tag->module = $data["module"];
            $tag->save();

            return redirect()->route("tags.show", ["tag" => $tag->id]);

        }

        return redirect()->route("admin");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tag = Tag::where('id', $id)->first();

        if($tag){
            $module = Module::find($tag->module);
        
            if($module->public || $module->author === intval(Auth::id())){
                return view("admin.tags.show", [
                    'tag' => $tag
                ]);
            }

        }else{
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tag = Tag::find($id);
        if($tag){
            $module = Module::find($tag->module);
        
            if($module && $module->author === intval(Auth::id())){
                return view("admin.tags.edit", [
                    'tag' => $tag
                ]);
            }
        }
        return redirect()->route("admin");
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
        $tag = Tag::find($id);

        if($tag){

            $module = Module::find($tag->module);
        
            if($module && $module->author === intval(Auth::id())){
                
                $data = $request->only([
                    'title',
                    'text'
                ]);

                $validator = Validator::make($data, [
                    'title' => ['required', 'string', 'max:100'],
                    'text' => ['string']
                ]);

                if($validator->fails()){
                    return redirect()->route("tags.edit", [
                        'tag' => $id
                    ])
                    ->withErrors($validator)
                    ->withInput();
                }

                $doc = new \DOMDocument();
                $doc->loadHTML($data["text"]);
                $script_tags = $doc->getElementsByTagName('script');
                $length = $script_tags->length;
                for ($i = 0; $i < $length; $i++) {
                    $script_tags->item($i)->parentNode->removeChild($script_tags->item($i));
                }
                $safe_text = $doc->saveHTML();


                $tag->title = strip_tags($data['title']);
                $tag->text = $safe_text;
                
                if(count($validator->errors()) > 0){
                    return redirect()->route("tags.edit", ["tag" => $id])
                    ->withErrors($validator);
                }

                $tag->save();
                return redirect()->route("tags.show", ["tag" => $id]);
            }
        }
        return redirect()->route("admin");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tag::find($id);
        if($tag){
            $module = Module::find($tag->module);
        
            if($module && $module->author === intval(Auth::id())){
                $tag->delete();
                return json_encode(1);
            }

        }
        
        return json_encode(0);

        exit;
    }
}
