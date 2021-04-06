<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cards_history;
use App\Models\Cards_queue;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $loggedId =  intval(Auth::id());
        $modules_count = Module::where("author", $loggedId)->count();

        $solved_count = Cards_history::where([
            ['user', '=', $loggedId],
            ['created_at', '>=', date("Y-m-d H:i:s", strtotime('-7 days'))]
        ])->count();

        $review_count = Cards_queue::where([
            ['user', '=', $loggedId],
            ['show_timestamp', '<=', date("Y-m-d H:i:s", time())]
        ])->count();

        $cardsByDay = Cards_history::selectRaw("day(created_at) as d, month(created_at) as m, year(created_at) as y, count(*) as c")
        ->where([
            ['user', '=', $loggedId],
            ['created_at', '>=', date("Y-m-d H:i:s", strtotime('-180 days'))]
        ])
        ->groupBy("m")
        ->groupBy("d")
        ->groupBy("y")
        ->get();

        $weekCardsByDay = Cards_history::selectRaw("day(created_at) as d, month(created_at) as m, year(created_at) as y, count(*) as c")
        ->where([
            ['user', '=', $loggedId],
            ['created_at', '>=', date("Y-m-d H:i:s", strtotime('-7 days'))]
        ])
        ->groupBy("m")
        ->groupBy("d")
        ->groupBy("y")
        ->get();

        $graph = [];
        $startDate = strtotime("-7 days");
        $endDate = strtotime("today");
        while($startDate <= $endDate){
            $graph[date("d/m", $startDate)] = 0;
            $startDate+=86400;
        }

        foreach($weekCardsByDay as $cardDay){
            $graph[$cardDay['d'] . "/" . $cardDay['m']] = intval($cardDay['c']);
        }

        $graphLabels = json_encode(array_keys($graph));
        $graphValues = json_encode(array_values($graph));

        return view('admin.home',[
            'modules_count' => $modules_count,
            'solved_count' => $solved_count,
            'review_count' => $review_count,
            'graphLabels' => $graphLabels,
            'graphValues' => $graphValues,
            'cardsSolved' => json_encode($cardsByDay)
        ]);
    }
        
}
