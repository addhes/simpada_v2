<?php

namespace Modules\Income\Http\Controllers\Backend;

use App\Authorizable;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Income\Entities\Income;
use Modules\Income\Entities\IncomeDetail;
use Modules\Income\Entities\BalanceTransaction;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
use Auth;
use Flash;
use Carbon;


class IncomeController extends Controller
{
    use Authorizable;

    public function __construct()
    {
        // Page Title
        $this->module_title = 'Pendapatan';

        // module name
        $this->module_name = 'incomes';

        // directory path of the module
        $this->module_path = 'income';

        // module icon
        $this->module_icon = 'c-icon far fa-file';

        // module model name, path
        $this->module_model = "Modules\Income\Entities\Income";
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

		$data = Income::orderByDesc('created_at')->where('company_code', auth()->user()->company_code)->get();
                
        $last_trans = BalanceTransaction::select('trans_code')
        ->where('company_code', auth()->user()->company_code)
        ->orderBy('created_at','DESC')
        ->first();

        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function ($data) use($last_trans) {
            $module_name = $this->module_name;

            return view('backend.includes.action_column_income', compact('module_name', 'data', 'last_trans'));
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

        $module_action = 'List';
        $today = date("Y-m-d");

        return view(
            "$module_path::backend.$module_name.create",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', 'today')
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
		], [
			'title.required' => 'Pendapatan harus diisi',
			'description.required' => 'Deskripsi harus diisi',
		]);

		$income = Income::all();
		$table_no = $income->count();
		$tgl = substr(str_replace( '-', '', Carbon\carbon::now()), 0,8);

		$no= $tgl.$table_no;
		$auto=substr($no,8);
		$auto=intval($auto)+1;
		$auto_number='PP'.auth()->user()->id.substr($no,0,8).str_repeat(0,(4-strlen($auto))).$auto;

		try{
			DB::beginTransaction();
			Income::create([
				'income_code' =>  $auto_number,
				'title' => $request->title,
				'date' => $request->date,
				'nominal' => preg_replace( '/[^0-9]/', '', $request->total),
				'description' => $request->description,
                'company_code' => auth()->user()->company_code
			]);

			$balance = BalanceTransaction::select('last_balance')
            ->where('company_code', auth()->user()->company_code)
            ->orderBy('created_at','DESC')
            ->first();
            
			$last_balance = $balance == null ? 0 : $balance->last_balance;

			BalanceTransaction::create([
				'post_from' =>  'Pendapatan',
				'trans_code' => $auto_number,
				'trans_type' => 'debit',
				'nominal' => preg_replace( '/[^0-9]/', '', $request->total),
				'last_balance' => ($last_balance + preg_replace( '/[^0-9]/', '', $request->total)),
                'company_code' => auth()->user()->company_code
            ]);

			$descriptiondetail = $request->input('descriptiondetail', []);
			$nominal = $request->input('nominal', []);

			for ($i=0; $i < count($nominal); $i++) {
				if ($nominal[$i] != '') {
					IncomeDetail::create([
						'income_code' =>  $auto_number,
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
        
        $$module_name_singular = $module_model::findOrFail($id);
        $incomedetail = Incomedetail::where('income_code', $income->income_code)
        ->where('company_code', auth()->user()->company_code)
        ->get();

        $module_action = 'List';
        $today = date("Y-m-d");
        
        return view(
            "$module_path::backend.$module_name.show",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', "$module_name_singular", 'incomedetail', 'today')
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
        
        $$module_name_singular = $module_model::findOrFail($id);
        $incomedetail = Incomedetail::where('income_code', $income->income_code)
        ->where('company_code', auth()->user()->company_code)
        ->get();

        $module_action = 'List';
        $today = date("Y-m-d");
        
        return view(
            "$module_path::backend.$module_name.edit",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', "$module_name_singular", 'incomedetail', 'today')
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
		], [
			'title.required' => 'Pengajuan harus diisi',
			'description.required' => 'Deskripsi harus diisi',
		]);

		try{
			DB::beginTransaction();
			$income = Income::find($id);
			$income->title = $request->title;
			$income->description = $request->description;
			$income->nominal = preg_replace( '/[^0-9]/', '', $request->total);
			$income->save();

			$balance = BalanceTransaction::select('last_balance')
            ->where('company_code', auth()->user()->company_code)
			->whereNotIn('trans_code', [$income->income_code])
			->orderBy('created_at','DESC')
			->first();

			$last_balance = $balance == null ? 0 : $balance->last_balance;

			$balancetrans = BalanceTransaction::where('trans_code', $income->income_code)
            ->where('company_code', auth()->user()->company_code)
            ->first();
			
            $balancetrans->nominal = preg_replace( '/[^0-9]/', '', $request->total);
			$balancetrans->last_balance = ($last_balance + preg_replace( '/[^0-9]/', '', $request->total));
			$balancetrans->save();

			$incomedetail=Incomedetail::where('income_code', $income->income_code)->delete();

			$descriptiondetail = $request->input('descriptiondetail', []);
			$nominal = $request->input('nominal', []);

			for ($i=0; $i < count($nominal); $i++) {
				if ($nominal[$i] != '') {
					Incomedetail::create([
						'income_code' =>  $income->income_code,
						'description' => $descriptiondetail[$i],
						'nominal' => preg_replace( '/[^0-9]/', '', $nominal[$i]),
						'company_code' =>  auth()->user()->company_code,
					]);
				}
			}
			DB::commit();
            Flash::success("<i class='fas fa-check'></i> '".$module_name.' '.Str::singular($module_title)."' Updated")->important();
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