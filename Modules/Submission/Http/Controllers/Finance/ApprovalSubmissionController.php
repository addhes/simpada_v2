<?php

namespace Modules\Submission\Http\Controllers\Finance;

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

class ApprovalSubmissionController extends Controller
{
    use Authorizable;

    public function __construct()
    {
        // Page Title
        $this->module_title = 'Approval Pengajuan';

        // module name
        $this->module_name = 'approvalfinances';

        // directory path of the module
        $this->module_path = 'submission';

        // role of the module
        $this->module_role = 'finance';

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
        
        $data = Submission::join('users', 'submissions.user_id', '=', 'users.id')
        ->select('submissions.*', 'users.name')
        ->where(function($query) {
            $query->where('director_app', null)
            ->orWhere('finance_attachment', null);
         })
        ->where('submissions.company_code', auth()->user()->company_code)
        ->orderBy('submissions.created_at','DESC')
        ->get();

        $data = $data->where('finance_app', '<>', 2)
        ->where('director_app', '<>', 2);
            
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function ($data) {
            $module_name = $this->module_name;

            return view('backend.includes.action_column_approval_finance', compact('module_name', 'data'));
        })
        ->editColumn('created_at', function ($data) {
            return date('d-m-Y', strtotime($data->created_at));
        })
        ->editColumn('finance_app', function($data){
			$status = '';
			if ($data->finance_app == 1) {
				$status = 'Approved';
			}elseif ($data->finance_app == 2) {
				$status = 'Rejected';
			}else{
				$status = 'No action yet';
			}

			return $status;
		})
        ->editColumn('director_app', function($data){
			$status = '';
			if ($data->director_app == 1) {
				$status = 'Approved';
			}elseif ($data->director_app == 2) {
				$status = 'Rejected';
			}else{
				$status = 'No action yet';
			}

			return $status;
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

        $request->validate([
			'finance_desc' => 'required',
			'last_balance' => 'required',
		], [
			'finance_desc.required' => 'Keterangan harus diisi',
			'last_balance.required' => 'Saldo Akhir harus diisi',
		]);

		//validasi pengeluaran > saldo
		$Submission = Submission::find($id);
		$userpengaju = User::find($Submission->user_id);
		$balance = BalanceTransaction::select('last_balance')->orderBy('created_at','DESC')->first();
		$last_balance = $balance->last_balance == null ? 0 : $balance->last_balance;

		if ($last_balance <  $Submission->estimated_price) {
			return redirect()->back()->with('msg', 'Saldo tidak mencukupi, sisa saldo saat ini Rp.'.number_format($last_balance,0,',','.'));   
		}

		$mytime = Carbon\Carbon::now();
		// echo $mytime->toDateTimeString();

		try{
			DB::beginTransaction();
			$Submission = Submission::find($id);
			$Submission->finance_app = 1;
			$Submission->finance_desc = $request->finance_desc;
			$Submission->last_balance = preg_replace( '/[^0-9]/', '', $request->last_balance);
			$Submission->finance_app_at = $mytime->toDateTimeString();
			$Submission->updated_at = $mytime->toDateTimeString();
			$Submission->save();

			// $phone_number = ['082165518008','082272518485'];
			// $msg = "Mohon diproses, untuk pengajuan ".$Submission->title." dari user ".ucwords($userpengaju->name)." dengan kode pengajuan ".$Submission->submission_code."\nTerimakasih \n\nLink Approval: https://pengajuan.wahanaproduction.com";

			// foreach ($phone_number as $item) {
			// 	$response = \GeneralHelper::sendWA($item, $msg);
			// }
			
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
                                ->selectRaw('s.*,  IFNULL(cg.name, "-") as category, IFNULL(cn.name, "-") as channel, b.name as bank, u.name as name')
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
    
    public function Rejecting(Request $request ,$id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_role = $this->module_role;
        $module_name_singular = Str::singular('submission');    

        $module_action = 'Approve';

		$request->validate([
			'finance_desc' => 'required',
		], [
			'finance_desc.required' => 'Keterangan harus diisi',
		]);

        $mytime = Carbon\Carbon::now();

        // dd("testing");

		try{
			DB::beginTransaction();
            $Submission = Submission::find($id);
            $Submission->finance_app = 2;
            $Submission->finance_desc = $request->finance_desc <> ''? $request->finance_desc : '';
            $Submission->finance_app_at = $mytime->toDateTimeString();
            $Submission->save();

			DB::commit();
            Flash::success("<i class='fas fa-check'></i>'".Str::singular($module_title)."' Rejected")->important();
            return redirect("admin/$module_name");
		} catch(\Exception $e){
			DB::rollBack();
			return $e->getMessage();
		}
    }

    public function upload($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_role = $this->module_role;
        $module_name_singular = Str::singular('submission');    

        $module_action = 'Upload';
        
        // $$module_name_singular = $module_model::findOrFail($id);
        $$module_name_singular = DB::table('submissions as s')
                                ->leftjoin('categories as cg', 's.category_id', 'cg.id')
                                ->leftjoin('channels as cn', 's.channel_id', 'cn.id')
                                ->leftjoin('banks as b', 's.bank_id', 'b.id')
                                ->leftjoin('users as u', 's.user_id', 'u.id')
                                ->selectRaw('s.*,  IFNULL(cg.name, "-") as category, IFNULL(cn.name, "-") as channel, b.name as bank, u.name as name')
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
            "$module_path::$module_role.$module_name.upload",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_role', 'module_name_singular', "$module_name_singular", 'submissiondetail', 'bank', 'channel', 'category', 'status', 'total', 'last_balance')
        );
    }

    public function uploading(Request $request ,$id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_role = $this->module_role;
        $module_name_singular = Str::singular('submission');    

        $module_action = 'Upload';
        
        $request->validate([
			'finance_attachment' =>['required', 'max:5000', function ($attribute, $value, $fail) {
				if ($value->getClientOriginalExtension() != 'pdf') {
					$fail(':attribute must be .pdf!');
				}
			}]
		]);

		$mytime = Carbon\Carbon::now();
		
		$Submission = Submission::find($id);
		$balance = BalanceTransaction::select('last_balance')->orderBy('created_at','DESC')->first();
		$last_balance = $balance == null ? 0 : $balance->last_balance;

		if ($last_balance <  $Submission->estimated_price) {
			return redirect()->back()->with('msg', 'Saldo tidak mencukupi, sisa saldo saat ini Rp.'.number_format($last_balance,0,',','.'));   
		}

		try{
			DB::beginTransaction();
            $finance_attachment = "";
            if($request->hasFile('finance_attachment')){			
                $submission = Submission::find($id);
                Storage::delete('public/finance-attachment/'.$submission->finance_attachment);
    
                $extension = $request->file('finance_attachment')->extension();
                $finance_attachment = date('dmyHis').'.'.$extension;
                $path = Storage::putFileAs('public/finance-attachment', $request->file('finance_attachment'), $finance_attachment);
            }

			$Submission = Submission::find($id);
			$Submission->finance_attachment =  ($finance_attachment <> "") ? $finance_attachment : $Submission->finance_attachment;
			$Submission->updated_at = $mytime->toDateTimeString();
			$Submission->save();

			$balance_trans = BalanceTransaction::where('trans_code', $Submission->submission_code)
			->get();

			// dd(count($balance_trans));
			if (count($balance_trans) == 0) {
				BalanceTransaction::create([
					'post_from' =>  'Pengajuan',
					'trans_code' => $Submission->submission_code,
					'trans_type' => 'credit',
					'nominal' => preg_replace( '/[^0-9]/', '', $Submission->estimated_price),
					'last_balance' => ($last_balance - $Submission->estimated_price),
					'company_code' => auth()->user()->company_code
				]);
			}

			// $user_pengaju = User::find($Submission->user_id);
			// $phone_number = [$user_pengaju->phone_number];

			// $msg = "Selamat, Pengajuan anda dengan Kode Pengajuan ".$Submission->submission_code." Sudah dikirim ke Rekening ".$Submission->destination_account."\nTerimakasih \n\nLink Pengajuan: https://pengajuan.wahanaproduction.com";

			// foreach ($phone_number as $item) {
			// 	$response = \GeneralHelper::sendWA($item, $msg);
			// }
			DB::commit();
            Flash::success("<i class='fas fa-check'></i>'".Str::singular($module_title)."' Attachment Uploaded")->important();
            return redirect("admin/$module_name");
		} catch(\Exception $e){
			DB::rollBack();
			return $e->getMessage();
		}
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
                                ->selectRaw('s.*,  IFNULL(cg.name, "-") as category, IFNULL(cn.name, "-") as channel, b.name as bank, u.name as name')
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