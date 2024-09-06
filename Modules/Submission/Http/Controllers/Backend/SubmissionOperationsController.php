<?php

namespace Modules\Submission\Http\Controllers\Backend;

use DB;
use Auth;
use Flash;
use App\Models\User;
use App\Authorizable;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Modules\Master\Entities\Bank;
use Illuminate\Routing\Controller;
use Modules\Master\Entities\Channel;
use Modules\Master\Entities\Category;
use Illuminate\Support\Facades\Storage;
use Modules\Submission\Entities\Submission;
use Illuminate\Contracts\Support\Renderable;
use Modules\Submission\Entities\Accountability;
use Modules\Submission\Entities\SubmissionDetail;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class SubmissionOperationsController extends Controller
{
    // use Authorizable;

    public function __construct()
    {
        // Page Title
        $this->module_title = 'Submission Operations';

        // module name
        $this->module_name = 'submission_operations';

        // directory path of the module
        $this->module_path = 'submission_operations';

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
        // dd('ada');

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
        ->addColumn('action', function ($data) {
            $module_name = $this->module_name;

            return view('backend.includes.action_column_submission_operations', compact('module_name', 'data'));
        })

		->rawColumns(['action', 'status_finance', 'status_boss', 'submission_code'])
		->make(true);
    }

    /**santai dlu bnang, lagi panas
     * Show the form for creating a new resource.
     * @return Renderable
     */
    // public function create()
    // {
    //     $module_title = $this->module_title;
    //     $module_name = $this->module_name;
    //     $module_path = $this->module_path;
    //     $module_icon = $this->module_icon;
    //     $module_model = $this->module_model;
    //     $module_name_singular = Str::singular($module_name);

    //     $module_action = 'Add';

    //     $bank = Bank::orderBy('name')->get();
    //     if(auth()->user()->company_code == 'bhk'){
    //         $channel = Channel::orderBy('name')->where('is_bhk', 1)->get();
    //         $category = Category::orderBy('name')->where('is_bhk', 1)->get();
    //     }else{
    //         $channel = Channel::orderBy('name')->get();
    //         $category = Category::orderBy('name')->get();
    //     }

    //     return view(
    //         "submission::backend.$module_name.create",
    //         compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', 'bank', 'channel', 'category')
    //     );
    // }

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

    // /**
    //  * Show the specified resource.
    //  * @param int $id
    //  * @return Renderable
    //  */
    public function show($id)
    {
        // dd($id);
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Show';

        // $sa = Submission::where('id', $id)->get();
        // dd($sa);

        // $$module_name_singular = $module_model::findOrFail($id);
        $$module_name_singular = DB::table('submissions as s')
                                ->leftJoin ('users', 's.user_id', '=', 'users.id')
                                ->leftJoin('parameters as p', 's.company_code', 'p.param_key')
                                ->leftJoin('banks as b', 's.bank_id', '=', 'b.id')
                                ->leftJoin('categories as cs', 's.category_id', '=', 'cs.id')
                                ->select('s.*', 'users.name as name_user', 'p.param_text as company', 'b.name as bank_name',
                                    'cs.name as category_name', 'cs.description as category_description')
                                ->where('s.id',$id)
                                ->first();
                                // dd($$module_name_singular);
        // dd('ada');
        $submissiondetail = DB::table('submissiondetails as sd')
                            ->where('submission_code', $$module_name_singular->submission_code)
                            ->get();

        $accountability = Accountability::where('submission_code',$$module_name_singular->submission_code)->get();

        // SubmissionDetail::where('submission_code', $$module_name_singular->submission_code)->withTrashed()->get();
        // dd($submissiondetail);
        return view(
            "submission::backend.$module_name.show",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', "$module_name_singular", 'submissiondetail', 'accountability')
        );
    }

    // /**
    //  * Show the form for editing the specified resource.
    //  * @param int $id
    //  * @return Renderable
    //  */
    public function edit($id)
    {
        // dd($id);
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        // dd($module_path);

        $module_action = 'Edit';

        $$module_name_singular = DB::table('submissions as s')
                                ->leftJoin ('users', 's.user_id', '=', 'users.id')
                                ->leftJoin('parameters as p', 's.company_code', 'p.param_key')
                                ->leftJoin('banks as b', 's.bank_id', '=', 'b.id')
                                ->leftJoin('categories as cs', 's.category_id', '=', 'cs.id')
                                ->select('s.*', 'users.name as name_user', 'p.param_text as company', 'b.name as bank_name',
                                    'b.id as bank_id',
                                    'cs.name as category_name', 'cs.description as category_description')
                                ->where('s.id',$id)
                                ->first();
                                // dd($$module_name_singular);
        // dd('ada');
        $submissiondetail = SubmissionDetail::where('submission_code', $$module_name_singular->submission_code)->get();

        $bank = Bank::orderBy('name')->get();
        // dd($bank[2]);
        // $channel = Channel::orderBy('name')->get();
        // $category = Category::orderBy('name')->get();
        $total = $submissiondetail->sum('nominal');

        return view(
            "submission::backend.$module_name.edit",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action',
                'module_name_singular', "$module_name_singular", 'submissiondetail', 'bank', 'total')
        );
    }

    // /**
    //  * Update the specified resource in storage.
    //  * @param Request $request
    //  * @param int $id
    //  * @return Renderable
    //  */
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
            'kode_pengajuan' => 'required',
            'name_bank' => 'required',
            'name_rek_bank' => 'required',
            'bank_number' => 'required',
        ], [
            'kode_pengajuan.required' => 'Kode Pengajuan Tidak Boleh Kosong',
            'name_bank.required' => 'Nama Bank Tidak Boleh Kosong',
            'name_rek_bank.required' => 'Deskripsi Tidak Boleh Kosong',
            'bank_number.required' => 'Nomor Bank Tidak Boleh Kosong',
        ]);

        // dd($request->all());
        // $Submission = Submission::find($id);
        // dd($Submission);
        // $submissiondetail = SubmissionDetail::where('submission_code', $Submission->submission_code)->first();
        // dd($submissiondetail);
        try{
            DB::beginTransaction();

		   	$Submission = Submission::find($id);
            $submissiondetail = SubmissionDetail::where('submission_code', $Submission->submission_code)->get();

            foreach ($submissiondetail as $data) {
                $data->submission_code = $request->kode_pengajuan;
                $data->save();
            }


            $Submission->submission_code = $request->kode_pengajuan;
		   	$Submission->bank_id = $request->name_bank;
		   	$Submission->destination_account = $request->name_rek_bank;
		   	$Submission->account_number = $request->bank_number;
		   	$Submission->save();

            // $submissiondetail = SubmissionDetail::where('submission_code', $Submission->submission_code)->first();




            DB::commit();
            Flash::success("<i class='fas fa-check'></i>'".Str::singular($module_title)."' Updated")->important();
            return redirect("admin/$module_name");
        } catch(\Exception $e){
            DB::rollBack();
            return $e->getMessage();
        }
    }

    // /**
    //  * Remove the specified resource from storage.
    //  * @param int $id
    //  * @return Renderable
    //  */
    public function destroy($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'destroy';

        try {
        $submission = Submission::find($id);
        $submissiondetail = SubmissionDetail::where('submission_code', $submission->submission_code)->delete();

        // Storage::delete('public/user-attachment/'.$submission->user_attachment);
        $submission->delete();

        return response()->json([
            'success' => 'true'
        ]);

        } catch (\Throwable $th) {
            Flash::success('<i class="fas fa-check"></i> '.label_case($module_name_singular).' Deleted Failed!')->important();

            return redirect("admin/$module_name");
        }

    }

    public function forcedelete(Request $request, $id)
    {
        // dd('ada');

        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'destroy';

        try {
            DB::beginTransaction();
            $submission = Submission::withTrashed()->find($id);
            // dd($submission);
            $submissiondetail = SubmissionDetail::withTrashed()->where('submission_code',$submission->submission_code)->get();
            // dd($submissiondetail);
            foreach ($submissiondetail as $data ) {
                $data->forceDelete();
            }

            Storage::delete('public/user-attachment/'.$submission->user_attachment);
            $submissions = Submission::withTrashed()->find($id);
            $submissions->forceDelete();

            DB::commit();
            return response()->json([
                'success' => 'true'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return $e->getMessage();
        }

    }


    public function trashed()
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Trash';

        $nores = $this->respending();

        return view(
            "submission::backend.$module_name.trash",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', 'nores')
        );
    }

    public function trashed_index_list(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'List';
        // dd('ada');

        $data = $this->list_admin_trash();

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
        ->addColumn('action', function ($data) {
            $module_name = $this->module_name;

            return view('backend.includes.action_column_submission_operations_trash', compact('module_name', 'data'));
        })

		->rawColumns(['action', 'status_finance', 'status_boss', 'submission_code'])
		->make(true);
    }

    public function restore($id)
    {
        // dd($id);
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'restore';

        try {
            DB::beginTransaction();
            $submission = Submission::withTrashed()->find($id);
            // dd($submission);
            $submissiondetail = SubmissionDetail::withTrashed()->where('submission_code',$submission->submission_code)->get();
            foreach ($submissiondetail as $data) {
                $data->restore();
            }

            $submission->restore();

            DB::commit();
            Flash::success("<i class='fas fa-check'></i>'".Str::singular($module_title)."' Restore")->important();
            return redirect("admin/$module_name/trashed");
        } catch(\Exception $e){
            DB::rollBack();
            return $e->getMessage();

        }

        // return to_route('posts.index')->with('success','Post restored successfully');
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

    public function list_admin_trash()
    {
        $data = Submission::join('users', 'submissions.user_id', '=', 'users.id')
        ->leftJoin('parameters as p', 'submissions.company_code', 'p.param_key')
        ->select('submissions.*', 'users.name', 'p.param_text as company')
        ->where(function($query) {
            $query->where('director_app', null)
            ->orWhere('finance_attachment', null);
         })
        ->orderBy('submissions.created_at','DESC')
        ->onlyTrashed()->get();

        $data = $data->where('finance_app', '<>', 2)
        ->where('director_app', '<>', 2);
        return $data;
    }

    public function getsubops(Request $request)
    {
        $submission = Submission::where('submission_code', $request->link)->first();

        return response()->json(['submission' => $submission,]);

    }
}
