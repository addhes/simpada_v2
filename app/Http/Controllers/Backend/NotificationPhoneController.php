<?php

namespace App\Http\Controllers\Backend;

use App\Authorizable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Str;
use Log;
use App\Models\User;
use App\Models\Parameter;
use App\Models\NotificationPhone;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Flash;
use Storage;

class NotificationPhoneController extends Controller
{

    use Authorizable;

    public function __construct()
    {
        // Page Title
        $this->module_title = 'Notification Phone Number';

        // module name
        $this->module_name = 'notification_phones';

        // directory path of the module
        $this->module_path = 'notification_phones';

        // module icon
        $this->module_icon = 'c-icon far fa-address-book';

        // module model name, path
        $this->module_model = "App\Models\NotificationPhone";
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

        $module_action = 'List';

        $page_heading = ucfirst($module_title);
        $title = $page_heading.' '.ucfirst($module_action);

        Log::info(label_case($module_title.' '.$module_action).' | User:'.auth()->user()->name.'(ID:'.auth()->user()->id.')');
     
        return view(
            "backend.$module_name.index",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular')
        );
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

        $module_action = 'Create';

        $companies = Parameter::select('param_key', 'param_text')->where('param_group', 'Company')->orderBy('param_key','DESC')->get();
        $categories = Parameter::select('param_key', 'param_text')->where('param_group', 'Notif Category')->orderBy('param_key','DESC')->get();
        $users = User::orderBy('name')->get();


        Log::info(label_case($module_title.' '.$module_action).' | User:'.auth()->user()->name.'(ID:'.auth()->user()->id.')');

        return view(
            "backend.$module_name.create",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', 'companies', 'categories', 'users')
        );
    }

    public function store(Request $request)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Store';

        $data = $request->all();
        $$module_name_singular = $module_model::create($data);
                
        Flash::success("<i class='fas fa-check'></i> New '".Str::singular($module_title)."' Added")->important();

        return redirect("admin/$module_name");
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

        $data = $module_model::all();
                
        return Datatables::of($data)
        ->addIndexColumn()
        ->addColumn('action', function ($data) {
            $module_name = $this->module_name;

            return view('backend.includes.action_column', compact('module_name', 'data'));
        })
        ->addColumn('name', function ($data) {
            $module_name = User::find($data->user_id);

            return $module_name->name;
        })
        ->addColumn('company', function ($data) {
            $module_name = Parameter::where('param_group', 'Company')->where('param_key', $data->company_code)->first();

            return $module_name->param_text;
        })
        ->rawColumns(['action', 'name', 'company'])
        ->make(true);
    }

        /**
     * Show the form for editing the specified resource.
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

        $companies = Parameter::select('param_key', 'param_text')->where('param_group', 'Company')->orderBy('param_key','DESC')->get();
        $categories = Parameter::select('param_key', 'param_text')->where('param_group', 'Notif Category')->orderBy('param_key','DESC')->get();
        $users = User::orderBy('name')->get();

        return view(
            "backend.$module_name.show",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', "$module_name_singular", 'companies', 'categories', 'users')
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
        // $companies = $module_model::select('param_key')->where('param_group', 'Company')->orderBy('param_key','DESC')->get();
        
        $companies = Parameter::select('param_key', 'param_text')->where('param_group', 'Company')->orderBy('param_key','DESC')->get();
        $categories = Parameter::select('param_key', 'param_text')->where('param_group', 'Notif Category')->orderBy('param_key','DESC')->get();
        $users = User::orderBy('name')->get();

        return view(
            "backend.$module_name.edit",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', "$module_name_singular", 'companies', 'categories', 'users')
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

        $$module_name_singular = $module_model::findOrFail($id);
        $$module_name_singular->user_id = $request->user_id;
        $$module_name_singular->company_code = $request->company_code;
        $$module_name_singular->category_code = $request->category_code;
        $$module_name_singular->save();
        
        Flash::success("<i class='fas fa-check'></i> Youtube '".Str::singular($module_title)."' Updated")->important();

        Log::info(label_case($module_title.' '.$module_action)." | '".$$module_name_singular->name.'(ID:'.$$module_name_singular->id.") ' by User:".auth()->user()->name.'(ID:'.auth()->user()->id.')');

        return redirect("admin/$module_name");
    }

         /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'destroy';

        $$module_name_singular = $module_model::findOrFail($id);

        $$module_name_singular->delete();

        Flash::success('<i class="fas fa-check"></i> '.label_case($module_name_singular).' Deleted Successfully!')->important();

        Log::info(label_case($module_title.' '.$module_action)." | '".$$module_name_singular->name.', ID:'.$$module_name_singular->id." ' by User:".auth()->user()->name.'(ID:'.auth()->user()->id.')');

        return redirect("admin/$module_name");
    }
}
