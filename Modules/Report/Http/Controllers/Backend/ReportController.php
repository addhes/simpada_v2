<?php

namespace Modules\Report\Http\Controllers\Backend;

use App\Authorizable;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Submission\Entities\Submission;
use Modules\Submission\Entities\SubmissionDetail;
use Modules\Submission\Entities\Accountability;
use Modules\Submission\Entities\AccountabilityDetail;
use Modules\Master\Entities\Category;
use Modules\Master\Entities\Bank;
use Modules\Master\Entities\Channel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Auth;
use Flash;
use File;
use Carbon;
use Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PerSubmissionExport;
use App\Exports\SubmissionExport;

class ReportController extends Controller
{
    use Authorizable;

    public function __construct()
    {
        // Page Title
        $this->module_title = 'Laporan';

        // module name
        $this->module_name = 'reports';

        // directory path of the module
        $this->module_path = 'report';

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
        $module_name_singular = Str::singular($module_name);

        $module_action = 'List';

        $hari_ini = date("Y-m-d");
		$tgl_pertama = date('Y-m-01', strtotime($hari_ini));
		$tgl_terakhir = date('Y-m-t', strtotime($hari_ini));

        return view(
            "$module_path::backend.$module_name.index",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', 'tgl_pertama','tgl_terakhir')
        );
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

        $hari_ini = date("Y-m-d");
		$tgl_pertama = date('Y-m-01', strtotime($hari_ini));
		$tgl_terakhir = date('Y-m-t', strtotime($hari_ini));
        
		$status 	= $_GET['status'] ?? 0;
		$pjb 		= $_GET['pjb'] ?? 0;
		$from_date 	= $_GET['from_date'] ?? $tgl_pertama;
		$to_date 	= $_GET['to_date'] ?? $tgl_terakhir;

		if ($status == 0) {
			$data = Submission::leftJoin('users', 'submissions.user_id', 'users.id')
            ->where('submissions.company_code', auth()->user()->company_code)
			->whereBetween(DB::raw('DATE(submissions.created_at)'), [$from_date, $to_date])
			->where(function($query) {
				$query->where([['finance_app', '<>', null],['director_app', '<>', null]])
				->orWhere([['finance_app', 2],['director_app' , null]]);
			})
			->selectRaw('submissions.*, users.name')
			->orderBy('submissions.created_at','DESC')
			->get();
		}elseif ($status == 1){			
			if ($pjb == 0) {
				// dd(1);
				$data = Submission::leftJoin('users', 'submissions.user_id', 'users.id')
                ->where('submissions.company_code', auth()->user()->company_code)
				->whereBetween(DB::raw('DATE(submissions.created_at)'), [$from_date, $to_date])
				->where('director_app', $status)
				->selectRaw('submissions.*, users.name')
				->orderBy('submissions.created_at','DESC')
				->get();
			}elseif($pjb == 1){
				// dd(2);
				$data = Submission::join('accountabilities','submissions.submission_code','accountabilities.submission_code')
				->leftJoin('users', 'submissions.user_id', 'users.id')
                ->where('submissions.company_code', auth()->user()->company_code)
				->whereBetween(DB::raw('DATE(submissions.created_at)'), [$from_date, $to_date])
				->where('submissions.director_app', $status)
				->selectRaw('submissions.*, users.name')
				->orderBy('submissions.created_at','DESC')
				->get();
			}else{
				// dd(3);
				$data = Submission::selectRaw('submissions.*, users.name')
				->leftJoin('accountabilities','submissions.submission_code','accountabilities.submission_code')
				->leftJoin('users', 'submissions.user_id', 'users.id')
                ->where('submissions.company_code', auth()->user()->company_code)
				->whereBetween(DB::raw('DATE(submissions.created_at)'), [$from_date, $to_date])
				->where('submissions.director_app', $status)
				->where('accountabilities.submission_code', null)
				->selectRaw('submissions.*, users.name')
				->orderBy('submissions.created_at','DESC')
				->get();
			}
		}else{
			$data = Submission::whereBetween(DB::raw('DATE(submissions.created_at)'), [$from_date, $to_date])
			->where(function($query) {
				$query->where('finance_app', 2)
				->orWhere('director_app', 2);
			})
            ->where('submissions.company_code', auth()->user()->company_code)
			->leftJoin('users', 'submissions.user_id', 'users.id')
			->selectRaw('submissions.*, users.name')
			->orderBy('submissions.created_at','DESC')
			->get();
		}

        return Datatables::of($data)
		->addIndexColumn()
		->addColumn('action', function($data){
            $module_name = $this->module_name;
			$accountability = Accountability::where('submission_code', $data->submission_code)->get();
            $accountability = count($accountability);

            return view('backend.includes.action_column_report', compact('module_name', 'data', 'accountability'));
		})
		->addColumn('status', function($data){
			$status = '';
			if ($data->finance_app == 1) {
				$status = 'Approved';
			}elseif ($data->finance_app == 2) {
				$status = 'Rejected';
			}else{
				$status = 'No action yet';
			}

			$statusBar = $status;
			return $statusBar;
		})
		->addColumn('statusbos', function($data){
			$statusbos = '';
			if ($data->director_app == 1) {
				$statusbos = 'Approved';
			}elseif ($data->director_app == 2) {
				$statusbos = 'Rejected';
			}else{
				$statusbos = 'No action yet';
			}

			$statusBar = $statusbos;
			return $statusBar;
		})
		->addColumn('tgl', function($data){
			$statusBar = \Carbon\Carbon::parse($data->created_at);
			return $statusBar->format('d/m/Y');
		})
		->rawColumns(['action'])
		->make(true);
    }
    
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('report::create');
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

        $status = statusChecking($$module_name_singular->submission_code);
        // dd($status);
        return view(
            "$module_path::backend.$module_name.show",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', "$module_name_singular", 'submissiondetail', 'bank', 'channel', 'category', 'status', 'total')
        );
    }

    public function download($id){
		$submission = Submission::find($id);
		ob_end_clean(); 
		ob_start();
		return Excel::download(new PerSubmissionExport($id), $submission->submission_code.'.xlsx');
	}

	public function export_excel(Request $request)
	{
		$status 	= $_GET['category'];
		$pjb	 	= $_GET['pjb'];
		$from_date 	= $_GET['from_date'];
		$to_date 	= $_GET['to_date'];

		if ($status == 1) {
			if ($pjb == 1) {
				$filename = 'Submission Sudah Pertanggung Jawaban Bulan '.$from_date.' sd '.$to_date;
			}elseif ($pjb == 2) {
				$filename = 'Submission Belum Pertanggung Jawaban Bulan '.$from_date.' sd '.$to_date;
			}else{
				$filename = 'Submission '.$from_date.' sd '.$to_date;
			}
		}else{
				$filename = 'Submission '.$from_date.' sd '.$to_date;
		}

		return Excel::download(new SubmissionExport($from_date, $to_date, $status, $pjb), $filename.'.xlsx');
	}

    public function accountability($id)
	{
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular("accountability");
        
        $module_action = 'Pertanggung Jawaban';

        $submission = Submission::where('id', $id)->where('company_code', auth()->user()->company_code)->first();
		$accountability = Accountability::where('submission_code', $submission->submission_code)->first();
		$accountabilitydetail = AccountabilityDetail::where('accountability_code', $accountability->accountability_code)->get();
		$total = $accountabilitydetail->sum("nominal");
        
        return view(
            "$module_path::backend.accountabilities.show",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', "$module_name_singular", 'accountabilitydetail', 'total', 'submission')
        );
	}
    
    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('report::edit');
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