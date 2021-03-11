<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Cards_history;
use App\Models\Cards_queue;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $error = 1;
        $result = "";
        $destroy_link = "";

        $front = strip_tags($request->input('front'));
        $back = strip_tags($request->input('back'));
        $module_id = intval($request->input('module'));
        
        if(strlen($front) && strlen($back) && $module_id){
            
            $module = Module::find($module_id);
            if($module){
                if(intval($module->author) === intval(Auth::id())){
                    $card = new Card;
                    $card->front = $front;
                    $card->back = $back;
                    $card->module = $module_id;
                    $card->type = 1;
                    $card->save();

                    $result = $card;
                    $error = 0;
                    $destroy_link = route('cards.destroy', ['card' => $card->id]);
                }else{
                    $result = "Você não pode inserir cartas nesse módulo";
                }
            } else{
                $result = "Módulo não encontrado";
            }

            
        }else{
            $result = "Você precisa preencher todos os campos";
        }

        return json_encode([
            "ERROR" => $error, 
            "RESULT" => $result,
            "DLINK" => $destroy_link
        ]);

    }

    public function imgstore(Request $request)
    {
        $error = 1;
        $result = "";
        $destroy_link = "";

        $validator  = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'front' => 'required|string|max:100',
            'back' => 'required|string|max:100',
            'module' => 'required'
        ]);

        if(!$validator->fails()){
            $back = strip_tags($request->input('back'));
            $front = strip_tags($request->input('front'));
            $module_id = intval($request->input('module'));

            $module = Module::find($module_id);
            if($module){
                if(intval($module->author) === intval(Auth::id())){

                    $imageName = rand(100, 999) . time() . '.' . $request->file->extension();
                    $request->file->move(public_path('media/cards/bg'), $imageName);
                    $imageLink = asset('media/cards/bg/' . $imageName);

                    $card = new Card;
                    $card->front = $front;
                    $card->back = $back;
                    $card->bgimg = $imageLink;
                    $card->module = $module_id;
                    $card->type = 2;
                    $card->save();            

                    $result = $card;
                    $error = 0;
                    $destroy_link = route('cards.destroy', ['card' => $card->id]);
                }else{
                    $result = "Você não pode inserir cartas nesse módulo";
                }
            } else{
                $result = "Módulo não encontrado";
            }

        }else{
            $result = "Há campos não preenchidos ou o tamanho da imagem está incorreto";
        }

        echo json_encode([
            "ERROR" => $error, 
            "RESULT" => $result,
            "DLINK" => $destroy_link
        ]);
        exit;

    }

    public function txtstore(Request $request){
        $error = 1;
        $result = "";
        $destroy_link = "";

        $validator  = Validator::make($request->all(), [
            'text' => 'required|string',
            'module' => 'required'
        ]);

        if(!$validator->fails()){
            $text = strip_tags($request->input('text'));
            $module_id = intval($request->input('module'));

            $module = Module::find($module_id);
            if($module){
                if(intval($module->author) === intval(Auth::id())){

                    $back = str_replace(["[[", "]]"], "", $text);

                    $card = new Card;
                    $card->front = $text;
                    $card->back = $back;
                    $card->module = $module_id;
                    $card->type = 3;
                    $card->save();            

                    $result = $card;
                    $error = 0;
                    $destroy_link = route('cards.destroy', ['card' => $card->id]);
                }else{
                    $result = "Você não pode inserir cartas nesse módulo";
                }
            } else{
                $result = "Módulo não encontrado";
            }

        }else{
            $result = "Há campos não preenchidos ou o tamanho da imagem está incorreto";
        }

        echo json_encode([
            "ERROR" => $error, 
            "RESULT" => $result,
            "DLINK" => $destroy_link
        ]);
        exit;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $card = Card::find($id);
        if($card){
            $module = Module::find($card->module);
        
            if($module && $module->author === intval(Auth::id())){
                Cards_queue::where('card', $id)->delete();
                $card->delete();
                return json_encode(1);
            }

        }
        
        return json_encode(0);
    }

    /**
    *
    *  Realiza a leitura da carta e atualiza o elo
    *
    *
    */

    public function updateElo(Request $request){
        $id = intval($request->input("id"));
        $result = intval($request->input("result"));
        if($result && ($result === 1 || $result === 2 || $result === 3)){
            $result = ($result-1)/2;
            $card = Card::find($id);
            if($card){

                switch($result){
                    case 0:
                        $minutes = 10;
                        break;
                    case 0.5:
                        $minutes = 60*12;
                        break;
                    case 1:
                        $minutes = 60*24;
                        break;
                }

                $loggedId = intval(Auth::id());

                //Pega o elo da carta
                $elo_card = $card->elo;
                $elo_user = 1500;

                //Pega o elo do usuário nesse módulo
                $history = Cards_history::where([
                    ["module", '=', $card->module],
                    ['user', '=', $loggedId]
                    ])
                    ->orderBy('created_at', 'DESC')
                    ->first();
                if($history){
                    $elo_user = $history->elo_user;
                }

                $expected_user = 1 / (1 + pow(10, (($elo_card-$elo_user)/400)));

                $elo_user = $elo_user + 20 * ($result - $expected_user);
                $elo_card = $elo_card + 20 * ((1-$result) - (1-$expected_user));
                
                //Atualiza o elo da carta
                $card->elo = $elo_card;
                $card->save();

                /**
                 * 
                 * Multiplicador de tempo: quanto maior a diferença de rating da carta para o usuário
                 * menor o espaçamento entre as repetições. Se um usuário tem 400 pts a menos que a carta
                 * ele a verá em 1/10 do tempo programado.
                 * 
                 **/
                $elo_diff = $elo_card - $elo_user;
                $multiplier = pow(10, -$elo_diff/400);
                $minutes = $minutes * $multiplier;

                //Insere no histórico
                $card_history = new Cards_history;
                $card_history->user = $loggedId;
                $card_history->card = $card->id;
                $card_history->module = $card->module;
                $card_history->result = $result;
                $card_history->elo_user = $elo_user;
                $card_history->elo_card = $elo_card;
                $card_history->save();

                

                //Remove da fila
                Cards_queue::where([
                    ['user', '=', $loggedId],
                    ['card', '=', $card->id]
                ])->delete();

                //Adiciona à fila
                $card_queue = new Cards_queue;
                $card_queue->user = $loggedId;
                $card_queue->card = $card->id;
                $card_queue->module = $card->module;
                $card_queue->show_timestamp = date("Y-m-d H:i:s", (time() + 60 * $minutes));
                $card_queue->save();

                echo json_encode($elo_user);
            }
        }
    }

    public function queueNext($id){
        $module = Module::find($id);
        if($module){
            $loggedId = intval(Auth::id());
            if($module->public || $loggedId === $module->author){

                $next = Cards_queue::where([
                    ['user', '=', $loggedId],
                    ['module', '=', $id],
                ])
                ->orderBy('show_timestamp', 'ASC')
                ->first();
    
                if($next){
                    $dt = new \DateTime();
                    $show_timestamp = $dt->createFromFormat("Y-m-d H:i:s", $next->show_timestamp)->format("U");
                    if($show_timestamp <= time()){
                        $card = Card::find($next);
                        if($card){
                            echo json_encode($card); exit;
                        }else{
                            $next->delete();
                            return $this->queueNext($id);
                        }
                    }else{
                        
                        $card_count = Card::where('module', $id)->count();
                        $queue_card_count = Cards_queue::where([
                            ['user', '=', $loggedId],
                            ['module', '=', $id],
                        ])->count();
                        
                        if($card_count !== $queue_card_count){
                            $cards = $cards = Card::where('module', $id)->get();
                            $queue_cards_arrays = Cards_queue::select("card")->where("user", $loggedId)->where("module", $id)->get()->toArray();
                            $queue_cards = [];
                            foreach($queue_cards_arrays as $queue_card){
                                $queue_cards[] = $queue_card['card'];
                            }
                            foreach($cards as $card){
                                if(!in_array($card->id, $queue_cards)){
                                    $queue = new Cards_queue;
                                    $queue->user = $loggedId;
                                    $queue->card = $card->id;
                                    $queue->module = $card->module;
                                    $queue->show_timestamp = date("Y-m-d H:i:s", time());
                                    $queue->save();
                                }
                            }
                            return $this->queueNext($id);
                        }
                        
                        echo json_encode(0); exit;
                    }
                    
                    
                }else{
                    $cards = Card::where('module', $id)->orderBy('elo', 'ASC')->get();
                    if(count($cards) > 0){
                        foreach($cards as $card){
                            $queue = new Cards_queue;
                            $queue->user = $loggedId;
                            $queue->card = $card->id;
                            $queue->module = $card->module;
                            $queue->show_timestamp = date("Y-m-d H:i:s", time());
                            $queue->save();
                        }
                        return $this->queueNext($id);
                    }
                }

            }            
        }

        echo json_encode(0); exit;
    }
}
