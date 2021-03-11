<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ChessController extends Controller
{
    public function index(){
        $fen = "rn1qr1k1/1b1nppb1/p2p2pp/1ppP4/4P3/2NB1NB1/PPP2PPP/R2QR1K1 w - - 0 16";
        
        $process = new Process(['python3', 'F:\Documentos\Desktop\Projetos\CMS\chess_script.py 2>&1']);
        
        try{
            $process->run();
        
            if (!$process->isSuccessful()) {
                //throw new ProcessFailedException($process);
            }
        } catch (Exception $e){
            echo $e->getMessage();
        }
        
        echo $process->getOutput();
        echo "<pre>";
        print_r($process);
        echo "</pre>";
        exit;

        return view('admin.chess.game', [
            'fen' => $fen
        ]);
        
    }
}
