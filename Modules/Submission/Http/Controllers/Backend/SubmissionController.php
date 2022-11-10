<?php

namespace Modules\Submission\Http\Controllers\Backend;

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
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Auth;
use Flash;
use File;
use Carbon;
use Storage;

class SubmissionController extends Controller
{
    use Authorizable;

    public function __construct()
    {
        // Page Title
        $this->module_title = 'Pengajuan';

        // module name
        $this->module_name = 'submissions';

        // directory path of the module
        $this->module_path = 'submission';

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

        $nores = $this->respending();

        return view(
            "submission::backend.$module_name.index",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', 'nores')
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

        $data = Submission::where('user_id', Auth::user()->id)
        ->orderBy('submissions.created_at','DESC');
            
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function ($data) {
            $module_name = $this->module_name;

            return view('backend.includes.action_column_submission', compact('module_name', 'data'));
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
    
    /**santai dlu bnang, lagi panas
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Add';

        $bank = Bank::orderBy('name')->get();
		$channel = Channel::orderBy('name')->get();
		$category = Category::orderBy('name')->get();

        return view(
            "submission::backend.$module_name.create",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', 'bank', 'channel', 'category')
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);
        
        $module_action = 'Store';

		$request->validate([
			'title' => 'required',
			'description' => 'required',
			'category_id' => 'required',
			'channel_id' => 'required',
			'estimated_price' => 'required',
			'bank_id' => 'required',
			'destination_account' => 'required',
			'account_number' => 'required',
			'user_attachment' =>['nullable','max:5000',function ($attribute, $value, $fail) {
				if ($value->getClientOriginalExtension() != 'pdf') {
					$fail(':attribute must be .pdf!');
				}
			}]
		], [
			'title.required' => 'Pengajuan harus diisi',
			'description.required' => 'Deskripsi harus diisi',
			'category_id.required' => 'Kategori harus dipilih',
			'channel_id.required' => 'Channel harus dipilih',
			'estimated_price.required' => 'Estimasi harus diisi',
			'bank_id.required' => 'Bank harus dipilih',
			'destination_account.required' => 'Rekening Tujuan harus diisi',
			'account_number.required' => 'Nomor Rekening Tujuan harus diisi',
		]);

		$submission = Submission::all();
        $table_no = $submission->count(); // nantinya menggunakan database dan table sungguhan
        $tgl = substr(str_replace( '-', '', Carbon\carbon::now()), 0,8);

        $no= $tgl.$table_no;
        $auto=substr($no,8);
        $auto=intval($auto)+1;
        $auto_number='PJN'.auth()->user()->id.substr($no,0,8).str_repeat(0,(4-strlen($auto))).$auto;

        $resp = $this->respending() > 0 ? 1 : 0;
        $mytime = Carbon\Carbon::now();

        try{
        DB::beginTransaction();
        $user_attachment = "";
		if($request->hasFile('user_attachment')){			
			$extension = $request->file('user_attachment')->extension();
			$resource = $request->file('user_attachment');
			$user_attachment = date('dmyHis').'.'.$extension;
			$path = Storage::putFileAs('public/user-attachment', $request->file('user_attachment'), $user_attachment);
			$resource->move(\base_path() ."/public/storage/user-attachment", $user_attachment);
		}

        Submission::create([
            'user_id' =>  auth()->user()->id,
            'submission_code' =>  $auto_number,
            'title' => $request->title,
            'description' => $request->description,
            'channel_id' => $request->channel_id,
            'category_id' => $request->category_id,		       		
            'estimated_price' => preg_replace( '/[^0-9]/', '', $request->estimated_price),
            'category_id' =>$request->category_id,
            'bank_id' => $request->bank_id,
            'destination_account' => $request->destination_account,
            'account_number' => $request->account_number,
            'user_attachment' => $user_attachment,
            'status' => $resp,
            'status_date' => $mytime->toDateTimeString(),
            'company_code' => auth()->user()->company_code
        ]);

        $descriptiondetail = $request->input('descriptiondetail', []);
        $nominal = $request->input('nominal', []);

        for ($i=0; $i < count($nominal); $i++) {
            if ($nominal[$i] != '') {
                SubmissionDetail::create([
                    'submission_code' =>  $auto_number,
                    'description' => $descriptiondetail[$i],
                    'nominal' => preg_replace( '/[^0-9]/', '', $nominal[$i]),
                    'company_code' => auth()->user()->company_code
                ]);
            }
        }
        
        // $phone_number = ['082272518485'];

        // if ($this->respending() > 0) {
        //     // $phone_number = ['082165518008','082272518485'];
        //     $msg = "Mohon diproses, untuk Ijin Pengajuan ".$request->title." dari user ".ucwords(auth()->user()->name)." dengan kode pengajuan ".$auto_number."\nTerimakasih \n\nLink Approval: https://pengajuan.wahanaproduction.com";

        //     foreach ($phone_number as $item) {
        //         $response = \GeneralHelper::sendWA($item, $msg);
        //     }
        // }else{
        //     // $phone_number = ['081281234788','082273102196'];
        //     $msg = "Mohon diproses, untuk pengajuan ".$request->title." dari user ".ucwords(auth()->user()->name)." dengan kode pengajuan ".$auto_number."\nTerimakasih \n\nLink Approval: https://pengajuan.wahanaproduction.com";

        //     foreach ($phone_number as $item) {
        //         $response = \GeneralHelper::sendWA($item, $msg);
        //     }
        // }

            DB::commit();
            Flash::success("<i class='fas fa-check'></i> New '".Str::singular($module_title)."' Added")->important();
            return redirect("admin/$module_name");
        } catch(\Exception $e){
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function respending(){
		$submissionNores = Submission::leftJoin('accountabilities', function($join) {
			$join->on('submissions.submission_code', '=', 'accountabilities.submission_code');
		})
		->whereNull('accountabilities.submission_code')
		->where('submissions.user_id', Auth::user()->id)
		->where('finance_attachment', '<>', '')
		->orderBy('submissions.created_at','DESC')
		->get();

		$nores = count($submissionNores);
		return $nores;
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
        $module_name_singular = Str::singular($module_name);    

        $module_action = 'Show';
        
        // $$module_name_singular = $module_model::findOrFail($id);
        $$module_name_singular = DB::table('submissions as s')
                                ->leftjoin('categories as cg', 's.category_id', 'cg.id')
                                ->leftjoin('channels as cn', 's.channel_id', 'cn.id')
                                ->leftjoin('banks as b', 's.bank_id', 'b.id')
                                ->selectRaw('s.*, cg.name as category, cn.name as channel, b.name as bank')
                                ->where('s.id',$id)
                                ->where('s.user_id', auth()->user()->id)
                                ->first();

                                // dd($$module_name_singular);
        $submissiondetail = Submissiondetail::where('submission_code', $$module_name_singular->submission_code)->get();
        $bank = Bank::orderBy('name')->get();
        $channel = Channel::orderBy('name')->get();
        $category = Category::orderBy('name')->get();
        $total = $submissiondetail->sum('nominal');

        $status = statusChecking($$module_name_singular->submission_code);
        return view(
            "$module_path::backend.$module_name.show",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', "$module_name_singular", 'submissiondetail', 'bank', 'channel', 'category', 'status', 'total')
        );
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);    

        $module_action = 'Edit';
        
        $$module_name_singular = $module_model::where('id', $id)
        ->where('user_id', auth()->user()->id) 
        ->first();

        $submissiondetail = Submissiondetail::where('submission_code', $$module_name_singular->submission_code)->get();
        $bank = Bank::orderBy('name')->get();
        $channel = Channel::orderBy('name')->get();
        $category = Category::orderBy('name')->get();

        return view(
            "$module_path::backend.$module_name.edit",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', "$module_name_singular", 'submissiondetail', 'bank', 'channel', 'category')
        );
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Update';

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'channel_id' => 'required',
            'estimated_price' => 'required',
            'bank_id' => 'required',
            'destination_account' => 'required',
            'account_number' => 'required',
            'user_attachment' =>['nullable','max:5000',function ($attribute, $value, $fail) {
				if ($value->getClientOriginalExtension() != 'pdf') {
					$fail(':attribute must be .pdf!');
				}

			}]
        ], [
            'title.required' => 'Pengajuan harus diisi',
            'description.required' => 'Deskripsi harus diisi',
            'category_id.required' => 'Kategori harus dipilih',
            'channel_id.required' => 'Channel harus dipilih',
            'estimated_price.required' => 'Estimasi harus diisi',
            'bank_id.required' => 'Bank harus dipilih',
            'destination_account.required' => 'Rekening Tujuan harus diisi',
            'account_number.required' => 'Nomor Rekening Tujuan harus diisi',
        ]);

        try{
            DB::beginTransaction();

            $submission = Submission::find($id);
		   	$user_attachment = "";
		   	if($request->hasFile('user_attachment')){	
		   		Storage::delete('public/user-attachment/'.$submission->user_attachment);
		   		$resource = $request->file('user_attachment');
		   		$extension = $request->file('user_attachment')->extension();
		   		$user_attachment = date('dmyHis').'.'.$extension;
		   		$path = Storage::putFileAs('public/user-attachment', $request->file('user_attachment'), $user_attachment);
		   		$resource->move(\base_path() ."/public/storage/user-attachment", $user_attachment);
		   	}

		   	$Submission = Submission::find($id);
		   	$Submission->title = $request->title;
		   	$Submission->description = $request->description;
		   	$Submission->channel_id = $request->channel_id;
		   	$Submission->estimated_price = preg_replace( '/[^0-9]/', '', $request->estimated_price);
		   	$Submission->bank_id = $request->bank_id;
		   	$Submission->destination_account = $request->destination_account;
		   	$Submission->account_number = $request->account_number;
		   	$Submission->user_attachment =  ($user_attachment <> "") ? $user_attachment : $Submission->user_attachment;
		   	$Submission->save();

		   	$submissiondetail=SubmissionDetail::where('submission_code', $submission->submission_code)->delete();

		   	$descriptiondetail = $request->input('descriptiondetail', []);
		   	$nominal = $request->input('nominal', []);

		   	for ($i=0; $i < count($nominal); $i++) {
		   		if ($nominal[$i] != '') {
		   			SubmissionDetail::create([
		   				'submission_code' =>  $submission->submission_code,
		   				'description' => $descriptiondetail[$i],
		   				'nominal' => preg_replace( '/[^0-9]/', '', $nominal[$i]),
                        'company_code' => auth()->user()->company_code
		   			]);
		   		}
		   	}

            DB::commit();
            Flash::success("<i class='fas fa-check'></i>'".Str::singular($module_title)."' Updated")->important();
            return redirect("admin/$module_name");
        } catch(\Exception $e){
            DB::rollBack();
            return $e->getMessage();
        } 
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