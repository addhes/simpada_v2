<?php

namespace App\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
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

class EmployeeController extends Controller
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
        $this->module_model = "App\Models\User"; 
            
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd("Dashboard Employee");
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Dashboard';

        $mytime = Carbon::now()->subDays(2)->toDateTimeString();

        return view('backend.dashboard.employee');
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

        $data = $this->list_data();
        $data = $data->whereIn('submissions.status', [1, 3])->where('status_date','>=',$mytime);

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


        $data = $this->list_data()
        ->whereNotIn('submissions.status', [1,3])
        ->whereRaw('IFNULL(finance_app, 0) <> 2')
        ->whereRaw('IFNULL(director_app, 0) <> 2');
        

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

    public function list_data()
    {
        $data = Submission::join('users', 'submissions.user_id', '=', 'users.id')
        ->select('submissions.*', 'users.name')
        ->where('user_id', Auth::user()->id)
        ->where(function($query) {
            $query->where('finance_app', null)
            ->orWhere('director_app', null)
            ->orWhere('finance_attachment', null);
        })
        ->orderBy('submissions.created_at','DESC');
        return $data;   
    }

}