<?php

namespace App\Http\Controllers\Backend;
use App\Authorizable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Parameter;
use Auth;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Log;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Modules\Income\Entities\BalanceTransaction;
use Modules\Submission\Entities\Submission;


class BackendController extends Controller
{
        // use Authorizable;
        protected $financeSub;

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
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Dashboard';


        if (Auth::user()->hasAnyRole(['label']) ) {// do your margic here
            return redirect()->route('label.dashboard');
        }
        elseif (Auth::user()->hasAnyRole(['finance']) ) {// do your margic here
            return redirect()->route('finance.dashboard');
        }
        elseif (Auth::user()->hasAnyRole(['director']) ) {// do your margic here
            return redirect()->route('director.dashboard');
        }

        $balance = BalanceTransaction::select('last_balance')->orderBy('created_at','DESC')->first()->last_balance ?? 0;
        $mytime = Carbon::now()->subDays(2)->toDateTimeString();

        $companies = Parameter::where('param_group', 'Company')->get();

        $arrCompanies = array();

        foreach($companies as $item){
            $balance_com = BalanceTransaction::select('last_balance')
            ->where('company_code', $item->param_key)
            ->orderBy('created_at','DESC')
            ->first()->last_balance ?? 0;

            $arrCompanies[] = array("company" => $item->param_text, "balance" => $balance_com);
        }
    
        return view('backend.dashboard.admin', compact('balance', 'arrCompanies'));
    }

    public function index_list_needconfirm()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'List';

        // dd("test");
        $mytime = Carbon::now()->subDays(2)->toDateTimeString();

        // if(Auth::user()->hasRole('super admin')){
            $data = $this->list_admin();
            $data = $data->whereIn('status', [1, 3])->where('status_date','>=',$mytime);
        // }elseif(Auth::user()->hasRole('administrator')){
        //     $data = $this->list_admin();
        //     $data = $data->whereIn('status', [1, 3])->where('status_date','>=',$mytime);
        // }elseif(Auth::user()->hasRole('employee')){
        //     $data = $this->list_admin();
        //     $data = $data->whereIn('status', [1, 3])->where('status_date','>=',$mytime);
        // }

        return Datatables::of($data)
		->addIndexColumn()
		->addColumn('status', function($data){
            if ($data->status == 1) {
                $appadmin = 'Pending';
                $badgeadmin = 'warning';
            }elseif ($data->status == 2) {
                $appadmin = 'Accepted';
                $badgeadmin = 'success';
            }else{
                $appadmin = 'Rejected';
                $badgeadmin = 'warning';
            }

            return "<span class='badge badge-soft-$badgeadmin py-1'>$appadmin </span>";
		})
		->rawColumns(['action', 'status'])
		->make(true);
    }

    public function index_list()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'List';

        $data = $this->list_admin();

        return Datatables::of($data)
		->addIndexColumn()
		->addColumn('status_finance', function($data){
            if ($data->finance_app == 1) {
                $appfinance = 'Approved';
                $badgefinance = 'success';
            }elseif ($data->finance_app == 2) {
                $appfinance = 'Rejected';
                $badgefinance = 'danger';
            }else{
                $appfinance = 'No action yet';
                $badgefinance = 'warning';
            }

            return "<span class='badge badge-soft-$badgefinance py-1'>$appfinance </span>";
		})
        ->addColumn('status_boss', function($data){
            if ($data->director_app == 1) {
                $appadmin = 'Approved';
                $badgeadmin = 'success';
            }elseif ($data->director_app == 2) {
                $appadmin = 'Rejected';
                $badgeadmin = 'danger';
            }else{
                $appadmin = 'No action yet';
                $badgeadmin = 'warning';
            } 

            return "<span class='badge badge-soft-$badgeadmin py-1'>$appadmin </span>";
		})
		->rawColumns(['action', 'status_finance', 'status_boss'])
		->make(true);
    }

    public function list_admin()
    {
        $data = Submission::join('users', 'submissions.user_id', '=', 'users.id')
        ->leftJoin('parameters as p', 'submissions.company_code', 'p.param_key')
        ->select('submissions.*', 'users.name', 'p.param_text as company')
        ->where(function($query) {
            $query->where('director_app', null)
            ->orWhere('finance_attachment', null);
         })
        ->orderBy('submissions.created_at','DESC')
        ->get();

        $data = $data->where('finance_app', '<>', 2)
        ->where('director_app', '<>', 2);
        return $data;   
    }
}