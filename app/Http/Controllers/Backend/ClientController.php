<?php

namespace App\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Label;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Log;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    // use Authorizable;

    public function __construct()
    {
        // Page Title
        $this->module_title = 'Dashboard';

        // module name
        $this->module_name = 'dashboards';

        // directory path of the module
        $this->module_path = 'dashboard';

        // module icon
        $this->module_icon = 'c-icon fas fa-music';

        // module model name, path
        $this->module_model = "App\Models\Label";   
    }

    public function index(){
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Dashboard';

        // 3 Bulan Terakhir
        $sixmonthago = (new \DateTime())->modify('first day of this month')->modify('-6 months');
        $fivemonthago = (new \DateTime())->modify('first day of this month')->modify('-5 months');
        $fourmonthago = (new \DateTime())->modify('first day of this month')->modify('-4 months');
        $threemonthago = (new \DateTime())->modify('first day of this month')->modify('-3 months');
        $twomonthago = (new \DateTime())->modify('first day of this month')->modify('-2 months');
        $lastmonth = (new \DateTime())->modify('first day of this month')->modify('-1 months');
        $thismonth = (new \DateTime())->modify('first day of this month');

     
        // Nama Bulan
        $month['thismonth'] = $thismonth->format("M");
        $month['lastmonth'] = $lastmonth->format("M");
        $month['twomonthago'] = $twomonthago->format("M");
        $month['threemonthago'] = $threemonthago->format("M");
        $month['fourmonthago'] = $fourmonthago->format("M");
        $month['fivemonthago'] = $fivemonthago->format("M");
        $month['sixmonthago'] = $sixmonthago->format("M");

        //Konversi Tanggal 3 Bulan Terakhir
        $sixmonthago = $sixmonthago->format("Y").'-'.$sixmonthago->format("m").'-'.$sixmonthago->format("d");
        $fivemonthago = $fivemonthago->format("Y").'-'.$fivemonthago->format("m").'-'.$fivemonthago->format("d");
        $fourmonthago = $fourmonthago->format("Y").'-'.$fourmonthago->format("m").'-'.$fourmonthago->format("d");
        $threemonthago = $threemonthago->format("Y").'-'.$threemonthago->format("m").'-'.$threemonthago->format("d");
        $twomonthago = $twomonthago->format("Y").'-'.$twomonthago->format("m").'-'.$twomonthago->format("d");
        $lastmonth = $lastmonth->format("Y").'-'.$lastmonth->format("m").'-'.$lastmonth->format("d");

        //Pendapatan 3 Bulan Terakhir
        $revenue['thismonth'] = DB::table('summary_reports')
        ->selectRaw('round(sum(partner_revenue),2) as partner_revenue')
        ->where('user_id', Auth::user()->id)
        ->where('accounting_date', $twomonthago)
        ->groupBy('user_id')
        ->first()->partner_revenue ?? 0;

        $revenue['lastmonth'] = DB::table('summary_reports')
        ->selectRaw('round(sum(partner_revenue),2) as partner_revenue')
        ->where('user_id', Auth::user()->id)
        ->where('accounting_date', $threemonthago)
        ->groupBy('user_id')
        ->first()->partner_revenue ?? 0;

        $revenue['twomonthago'] = DB::table('summary_reports')
        ->selectRaw('round(sum(partner_revenue),2) as partner_revenue')
        ->where('user_id', Auth::user()->id)
        ->where('accounting_date', $fourmonthago)
        ->groupBy('user_id')
        ->first()->partner_revenue ?? 0;

        $revenue['threemonthago'] = DB::table('summary_reports')
        ->selectRaw('round(sum(partner_revenue),2) as partner_revenue')
        ->where('user_id', Auth::user()->id)
        ->where('accounting_date', $fivemonthago)
        ->groupBy('user_id')
        ->first()->partner_revenue ?? 0;

        $revenue['fourmonthago'] = DB::table('summary_reports')
        ->selectRaw('round(sum(partner_revenue),2) as partner_revenue')
        ->where('user_id', Auth::user()->id)
        ->where('accounting_date', $sixmonthago)
        ->groupBy('user_id')
        ->first()->partner_revenue ?? 0;

        $pendapatan = DB::table('summary_reports')->selectRaw('sum(partner_revenue) as partner_revenue')->where('user_id', Auth::user()->id)->groupBy('user_id')->first()->partner_revenue ?? null;
        $withdraw = DB::table('withdraws')->selectRaw('sum(amount) as amount')->where('user_id', Auth::user()->id)->groupBy('user_id')->first()->amount ?? null;
        $saldo = $pendapatan - $withdraw;
        $saldo = abs(round($saldo, 2));

        $label = DB::table('labels')->where('user_id',Auth::user()->id)->get();

        $year = Date("Y");
        $totalTransactions = DB::table('summary_reports as sr')->selectRaw('tt.report_date,SUM(partner_revenue) as partner_revenue')
                            ->join('transactions as tt', 'sr.accounting_date', 'tt.date')
                            ->where('user_id', Auth::user()->id)
                            ->groupBy('report_date')
                            ->orderBy('report_date','desc')
                            ->limit(12)
                            ->get();
        $chart  =   [0,0,0,0,0,0,0,0,0,0,0,0];
        $label_chart = ["-","-","-","-","-","-","-","-","-","-","-","-"];
        foreach($totalTransactions as $idx=>$total){ 
            if($total->report_date <= Date("Y-m-d")){
                $monthdiagram = (int)Date("m",strtotime($total->report_date));
                $index = count($totalTransactions)-1-$idx;
                $chart[$index]    = roundDown($total->partner_revenue);
                $label_chart[$index] = Date("M-Y",strtotime($total->report_date));
            }
        }

        $chart = json_encode($chart);
        $label_chart = json_encode($label_chart);
 
        return view('backend.dashboard.client', compact('saldo', 'month', 'revenue', 'label', 'chart', 'label_chart'));
    }


    public function cartdata(){
        $thisyear = (new \DateTime())->modify('first day of this month');
        $thisyear = $thisyear->format("Y");

        $data = DB::table('summary_reports')
        ->selectRaw('MONTH(accounting_date) as date, sum(partner_revenue) as total')
        ->whereYear('accounting_date', $thisyear)
        ->where('user_id', Auth::user()->id)
        ->groupBy('date')
        ->orderBy('date','asc')
        ->get();


        $januari = DB::table('summary_reports')
        ->selectRaw('MONTH(accounting_date) as date, sum(partner_revenue) as total')
        ->whereYear('accounting_date', $thisyear)
        ->where('user_id', Auth::user()->id)
        ->where('accounting_date', '2022-01-01')
        ->groupBy('date')
        ->orderBy('date','asc')
        ->first()->date ?? null;

        $arr=[];

        if(!is_numeric($januari)){
            $arr[]=[
                'date'=>getBulan(1),
                'total'=>0
            ];
        }

        for($i=0; $i<=12; $i++){
            if(is_numeric($data[$i]->date ?? null)){
                $arr[]=[
                    'date'=>getBulan($data[$i]->date),
                    'total'=>$data[$i]->total
                ];
            }else{
                $arr[]=[
                    'date'=>getBulan($i),
                    'total'=>0
                ];
            }
        }
        echo json_encode($arr);
    }   
}