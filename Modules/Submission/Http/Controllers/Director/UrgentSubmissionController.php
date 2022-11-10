<?php

namespace Modules\Submission\Http\Controllers\Director;

use App\Authorizable;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Submission\Entities\Submission;
use Modules\Submission\Entities\SubmissionDetail;
use Modules\Master\Entities\Category;
use Modules\Master\Entities\Bank;
use Modules\Master\Entities\Channel;
use Modules\Income\Entities\BalanceTransaction;
use App\Models\User;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Auth;
use Flash;
use File;
use Carbon;
use Storage;

class UrgentSubmissionController extends Controller
{
    use Authorizable;

    public function __construct()
    {
        // Page Title
        $this->module_title = 'Approval Pengajuan Urgent';

        // module name
        $this->module_name = 'approvalurgents';

        // directory path of the module
        $this->module_path = 'submission';

        // role of the module
        $this->module_role = 'director';

        // module icon
        $this->module_icon = 'c-icon far fa-file';

        // module model name, path
        $this->module_model = "Modules\Submission\Entities\Submission";
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_role = $this->module_role;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'List';

        return view(
            "$module_path::$module_role.$module_name.index",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular')
        );
    }

    public function index_list()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_role = $this->module_role;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'List';
        
		// 0 = No Action
		// 1 = Approved
		// 2 = Rejected

        $data = DB::table('submissions')
        ->join('users', 'submissions.user_id', '=', 'users.id')
        ->where('submissions.company_code', auth()->user()->company_code)
        ->select('submissions.*', 'users.name')
        ->where('submissions.status', '<>', '')
        ->orderBy('submissions.created_at','DESC')
        ->get();

        // dd($data);
        // $data = $data->where('finance_app', '<>', 2)
        // ->where('director_app', '<>', 2);
            
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function ($data) {
            $module_name = $this->module_name;

            return view('backend.includes.action_column_approval_urgent', compact('module_name', 'data'));
        })
        ->editColumn('created_at', function ($data) {
            return date('d-m-Y', strtotime($data->created_at));
        })
        ->addColumn('statusdes', function($data){
            $status = '';
            if ($data->status == 1) {
                $status = 'Waiting';
            }elseif ($data->status == 2) {
                $status = 'Accepted';
            }elseif ($data->status == 3){
                $status = 'Rejected';
            }

            $statusBar = $status;
            return $statusBar;
        })

        ->rawColumns(['action', 'finance_app', 'director_app'])
        ->make(true);
    }

        /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function approval($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_role = $this->module_role;
        $module_name_singular = Str::singular('submission');

        $module_action = 'Approve';
        
        // $$module_name_singular = $module_model::findOrFail($id);
        $$module_name_singular = DB::table('submissions as s')
                                ->leftjoin('categories as cg', 's.category_id', 'cg.id')
                                ->leftjoin('channels as cn', 's.channel_id', 'cn.id')
                                ->leftjoin('banks as b', 's.bank_id', 'b.id')
                                ->leftjoin('users as u', 's.user_id', 'u.id')
                                ->selectRaw('s.*, IFNULL(cg.name, "-") as category, IFNULL(cn.name, "-") as channel, b.name as bank, u.name as name')
                                ->where('s.company_code', auth()->user()->company_code)
                                ->where('s.id',$id)->first();

                                // dd($$module_name_singular);
        $submissiondetail = Submissiondetail::where('submission_code', $$module_name_singular->submission_code)->get();
        $bank = Bank::orderBy('name')->get();
        $channel = Channel::orderBy('name')->get();
        $category = Category::orderBy('name')->get();
        $total = $submissiondetail->sum('nominal');
		$balance = BalanceTransaction::select('last_balance')->orderBy('created_at','DESC')->first();
		$last_balance = $balance->last_balance == null ? 0 : $balance->last_balance;

        $status = statusChecking($$module_name_singular->submission_code);
        
        return view(
            "$module_path::$module_role.$module_name.approve",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_role', 'module_name_singular', "$module_name_singular", 'submissiondetail', 'bank', 'channel', 'category', 'status', 'total', 'last_balance')
        );
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function approve(Request $request ,$id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_role = $this->module_role;
        $module_name_singular = Str::singular('submission');    

        $module_action = 'Approve';

        $mytime = Carbon\Carbon::now();

        $balance = BalanceTransaction::select('last_balance')->orderBy('created_at','DESC')->first();
        $last_balance = $balance->last_balance == null ? 0 : $balance->last_balance;

		try{
			DB::beginTransaction();
            $Submission = Submission::find($id);
            $Submission->status = 2;
            $Submission->finance_app = 1;
            $Submission->finance_desc = "Approved Urgent";
            $Submission->director_app = 1;
            $Submission->director_desc = "Approved Urgent";
            $Submission->last_balance = preg_replace( '/[^0-9]/', '', $last_balance);
            $Submission->status_date = $mytime->toDateTimeString();
            $Submission->updated_at = $mytime->toDateTimeString();
            $Submission->save();
			
			DB::commit();
            Flash::success("<i class='fas fa-check'></i>'".Str::singular($module_title)."' Approved")->important();
            return redirect("admin/$module_name");
		} catch(\Exception $e){
			DB::rollBack();
			return $e->getMessage();
		}
    }
    
    public function reject($id)
    {
       $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_role = $this->module_role;
        $module_name_singular = Str::singular('submission');    

        $module_action = 'Reject';
        
        // $$module_name_singular = $module_model::findOrFail($id);
        $$module_name_singular = DB::table('submissions as s')
                                ->leftjoin('categories as cg', 's.category_id', 'cg.id')
                                ->leftjoin('channels as cn', 's.channel_id', 'cn.id')
                                ->leftjoin('banks as b', 's.bank_id', 'b.id')
                                ->leftjoin('users as u', 's.user_id', 'u.id')
                                ->selectRaw('s.*, IFNULL(cg.name, "-") as category, IFNULL(cn.name, "-") as channel, b.name as bank, u.name as name')
                                ->where('s.company_code', auth()->user()->company_code)
                                ->where('s.id',$id)->first();

        $submissiondetail = Submissiondetail::where('submission_code', $$module_name_singular->submission_code)->get();
        $bank = Bank::orderBy('name')->get();
        $channel = Channel::orderBy('name')->get();
        $category = Category::orderBy('name')->get();
        $total = $submissiondetail->sum('nominal');
		$balance = BalanceTransaction::select('last_balance')->orderBy('created_at','DESC')->first();
		$last_balance = $balance->last_balance == null ? 0 : $balance->last_balance;

        $status = statusChecking($$module_name_singular->submission_code);

        return view(
            "$module_path::$module_role.$module_name.reject",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_role', 'module_name_singular', "$module_name_singular", 'submissiondetail', 'bank', 'channel', 'category', 'status', 'total', 'last_balance')
        );
    }
    
    public function rejecting(Request $request ,$id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_role = $this->module_role;
        $module_name_singular = Str::singular('submission');    

        $module_action = 'Rejected';

        $mytime = Carbon\Carbon::now();

		try{
			DB::beginTransaction();
            $Submission = Submission::find($id);
            $Submission->status = 3;
            $Submission->status_date = $mytime->toDateTimeString();
            $Submission->updated_at = $mytime->toDateTimeString();
            $Submission->save();

			DB::commit();
            Flash::success("<i class='fas fa-check'></i>'".Str::singular($module_title)."' Rejected")->important();
            return redirect("admin/$module_name");
		} catch(\Exception $e){
			DB::rollBack();
			return $e->getMessage();
		}
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_role = $this->module_role;
        $module_name_singular = Str::singular('submission');    

        $module_action = 'Show';
        
        // $$module_name_singular = $module_model::findOrFail($id);
        $$module_name_singular = DB::table('submissions as s')
                                ->leftjoin('categories as cg', 's.category_id', 'cg.id')
                                ->leftjoin('channels as cn', 's.channel_id', 'cn.id')
                                ->leftjoin('banks as b', 's.bank_id', 'b.id')
                                ->leftjoin('users as u', 's.user_id', 'u.id')
                                ->selectRaw('s.*, IFNULL(cg.name, "-") as category, IFNULL(cn.name, "-") as channel, b.name as bank, u.name as name')
                                ->where('s.company_code', auth()->user()->company_code)
                                ->where('s.id',$id)->first();

        $submissiondetail = Submissiondetail::where('submission_code', $$module_name_singular->submission_code)->get();
        $bank = Bank::orderBy('name')->get();
        $channel = Channel::orderBy('name')->get();
        $category = Category::orderBy('name')->get();
        $total = $submissiondetail->sum('nominal');
		$balance = BalanceTransaction::select('last_balance')->orderBy('created_at','DESC')->first();
		$last_balance = $balance->last_balance == null ? 0 : $balance->last_balance;

        $status = statusChecking($$module_name_singular->submission_code);

        return view(
            "$module_path::$module_role.$module_name.show",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_role', 'module_name_singular', "$module_name_singular", 'submissiondetail', 'bank', 'channel', 'category', 'status', 'total', 'last_balance')
        );
    }
    
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('submission::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('submission::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}