<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:use-dashboard');
    }

    public function index(Request $request){
        $visitsCount = 0;
        $onlineCount = 0;
        $pageCount = 0;
        $userCount = 0;
        $interval = intval($request->input('interval', 30));

        $intervalArray = [30, 60, 90, 120];

        //Contagem de acessos totais (nao unicos)
        if(!in_array($interval, $intervalArray)) $interval = 30;
        $dateInterval = date("Y-m-d H:i:s", strtotime('-' . $interval . " days"));
        $visitsCount = Visitor::where('date_access', '>=', $dateInterval)->count();

        //Contagem de usuários online
        $datelimit = date("Y-m-d H:i:s", strtotime("-5 minutes"));
        $onlineList = Visitor::select('ip')->where('date_access', ">=", $datelimit)->groupBy('ip')->get();
        $onlineCount = count($onlineList);

        //Contagem de páginas 
        $pageCount = Page::count();

        //Contagem de usuários
        $userCount = User::count();

        //Contagem para o gráfico
        $visitsAll = Visitor::selectRaw('page, count(page) as c')
        ->where('date_access', '>=', $dateInterval)
        ->groupBy('page')
        ->get();
        $pagePie = [];
        $pagePieColors = [];
        foreach($visitsAll as $visit){
            $pagePie[$visit['page']] = intval($visit['c']);
            $pagePieColors[] = 'rgba('.rand(0, 255).', '.rand(0, 255).', '.rand(0, 255).')';
        }

        $pageLabels = json_encode(array_keys($pagePie));
        $pageValues = json_encode(array_values($pagePie));
        $pagePieColors = json_encode(array_values($pagePieColors));

        return view("admin.dashboard.home", [
            'visitsCount' => $visitsCount,
            'onlineCount' => $onlineCount,
            'pageCount' => $pageCount,
            'userCount' => $userCount,
            'pageLabels' => $pageLabels,
            'pageValues' => $pageValues,
            'pagePieColors' => $pagePieColors,
            'dateInterval' => $interval
        ]);
    }
}
