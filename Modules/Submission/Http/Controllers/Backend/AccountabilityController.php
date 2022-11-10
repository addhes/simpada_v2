<?php

namespace Modules\Submission\Http\Controllers\Backend;

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
use Carbon\Carbon;
use Storage;

class AccountabilityController extends Controller
{
    use Authorizable;

    public function __construct()
    {
        // Page Title
        $this->module_title = 'Pertanggung Jawaban';

        // module name
        $this->module_name = 'accountabilities';

        // directory path of the module
        $this->module_path = 'submission';

        // module icon
        $this->module_icon = 'c-icon far fa-file';

        // module model name, path
        $this->module_model = "Modules\Submission\Entities\Accountability";
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

        return view(
            "$module_path::backend.$module_name.index",
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
        $module_name_singular = Str::singular($module_name);

        $module_action = 'List';

        $data = DB::table('accountabilities')
		->join('submissions', 'accountabilities.submission_code', '=', 'submissions.submission_code')
		->select('accountabilities.*', 'submissions.estimated_price', 'submissions.title')
		->where('submissions.user_id', Auth::user()->id)
		->orderBy('accountabilities.created_at','DESC')
		->get();

		return Datatables::of($data)
		->addIndexColumn()
		->addColumn('action', function($data){
			$diffinday = Carbon::parse(Carbon::now())->diffInDays($data->created_at);

            $module_name = $this->module_name;
            return view('backend.includes.action_column_accountability', compact('module_name', 'data', 'diffinday'));
		})
		->addColumn('total', function($data){
			$total = AccountabilityDetail::where('accountability_code', $data->accountability_code)->sum('nominal');
            
			$totalBar = $total;
			return $totalBar;
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
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Add';

        $today = date("Y-m-d");

		$submissions = Submission::leftJoin('accountabilities', function($join) {
			$join->on('submissions.submission_code', '=', 'accountabilities.submission_code');
		})
		->whereNull('accountabilities.submission_code')
		->where([['finance_app', 1], ['director_app', 1], ['finance_attachment','!=',''], ['submissions.user_id', Auth::user()->id]])
		->select('submissions.*')
		->get();

        return view(
            "$module_path::backend.$module_name.create",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', 'submissions', 'today')
        );
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function getdata($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Edit';
		$data = Submission::where('submission_code', $id)->first();
        
        return response()->json($data);
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
			'submission_code' => 'required',
			'description' => 'required',
			'accountability_attachment' =>['required', function ($attribute, $value, $fail) {
				if ($value->getClientOriginalExtension() != 'pdf') {
					$fail(':attribute must be .pdf!');
				}
			}]
		], [
			'submission_code.required' => 'Kode pengajuan harus pilih',
			'description.required' => 'Deskripsi harus isi',
			'accountability_attachment.required' => 'Attachment harus isi',
		]);

        try{
            DB::beginTransaction();

            $accountability = Accountability::all();

            $accountability_attachment = "";
            if($request->hasFile('accountability_attachment')){			
                $this->validate($request, ['accountability_attachment' => 'required|file|max:5000']);
                $extension = $request->file('accountability_attachment')->extension();
                $accountability_attachment = date('dmyHis').'.'.$extension;
                $path = Storage::putFileAs('public/accountability-attachment', $request->file('accountability_attachment'), $accountability_attachment);
            }

            $table_no = $accountability->count();
            $tgl = substr(str_replace( '-', '', Carbon::now()), 0,8);

            $no= $tgl.$table_no;
            $auto=substr($no,8);
            $auto=intval($auto)+1;
            $auto_number='PTJB'.auth()->user()->id.substr($no,0,8).str_repeat(0,(4-strlen($auto))).$auto;

            Accountability::create([
                'accountability_code' =>  $auto_number,
                'submission_code' =>  $request->submission_code,
                'user_id' =>  auth()->user()->id,
                'date' => $request->date,
                'description' => $request->description,
                'accountability_attachment' => $accountability_attachment,
                'company_code' => auth()->user()->company_code
            ]);

            $datedetail = $request->input('datedetail', []);
            $descriptiondetail = $request->input('descriptiondetail', []);
            $nominal = $request->input('nominal', []);
    
            for ($i=0; $i < count($nominal); $i++) {
                if ($nominal[$i] != '') {
                    AccountabilityDetail::create([
                        'accountability_code' =>  $auto_number,
                        'date' => $datedetail[$i],
                        'description' => $descriptiondetail[$i],
                        'nominal' => preg_replace( '/[^0-9]/', '', $nominal[$i]),
                        'company_code' => auth()->user()->company_code
                    ]);
                }
            }
                    
            DB::commit();
            Flash::success("<i class='fas fa-check'></i> New '".Str::singular($module_title)."' Added")->important();
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
        $module_name_singular = Str::singular($module_name);
        
        $module_action = 'Show';

		$submissions = Submission::where('submissions.user_id', Auth::user()->id)->get();
		$$module_name_singular = Accountability::find($id);
		$accountabilitydetail = AccountabilityDetail::where('accountability_code', $$module_name_singular->accountability_code)->get();
		$total = $accountabilitydetail->sum("nominal");
		$submission = Submission::where('submission_code', $$module_name_singular->submission_code)->first();
        
        return view(
            "$module_path::backend.$module_name.show",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', "$module_name_singular", 'accountabilitydetail', 'total', 'submission', 'submissions')
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

		$submissions = Submission::where('submissions.user_id', Auth::user()->id)->get();
		$$module_name_singular = Accountability::find($id);
		$accountabilitydetail = AccountabilityDetail::where('accountability_code', $$module_name_singular->accountability_code)->get();
		$total = $accountabilitydetail->sum("nominal");
		$submission = Submission::where('submission_code', $$module_name_singular->submission_code)->first();
        
        return view(
            "$module_path::backend.$module_name.edit",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', "$module_name_singular", 'accountabilitydetail', 'total', 'submission', 'submissions')
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
			'description' => 'required',
			'accountability_attachment' =>['nullable', function ($attribute, $value, $fail) {
				if ($value->getClientOriginalExtension() != 'pdf') {
					$fail(':attribute must be .pdf!');
				}
			}]
		], [
			'description.required' => 'Deskripsi harus isi'
		]);

        try{
            DB::beginTransaction();

            $accountability_attachment = "";
            if($request->hasFile('accountability_attachment')){			
                $accountability = Accountability::find($id);
                Storage::delete('public/accountability-attachment/'.$accountability->accountability_attachment);
    
                $extension = $request->file('accountability_attachment')->extension();
                $accountability_attachment = date('dmyHis').'.'.$extension;
                $path = Storage::putFileAs('public/accountability-attachment', $request->file('accountability_attachment'), $accountability_attachment);
            }
            
            $accountability = Accountability::find($id);
            $accountability->description = $request->description;
            $accountability->accountability_attachment = ($accountability_attachment <> "") ? $accountability_attachment : $accountability->accountability_attachment;;
            $accountability->save();
    
            $accountabilitydetail= AccountabilityDetail::where('accountability_code', $accountability->accountability_code)->delete();

            $datedetail = $request->input('datedetail', []);
            $descriptiondetail = $request->input('descriptiondetail', []);
            $nominal = $request->input('nominal', []);

            for ($i=0; $i < count($nominal); $i++) {
                if ($nominal[$i] != '') {
                    AccountabilityDetail::create([
                        'accountability_code' =>  $accountability->accountability_code,
                        'date' => $datedetail[$i],
                        'description' => $descriptiondetail[$i],
                        'nominal' => preg_replace( '/[^0-9]/', '', $nominal[$i]),
                        'company_code' => auth()->user()->company_code
                    ]);
                }
            }

            DB::commit();
            Flash::success("<i class='fas fa-check'></i> '".Str::singular($module_title)."' Updated")->important();
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