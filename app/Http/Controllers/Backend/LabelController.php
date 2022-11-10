<?php

namespace App\Http\Controllers\Backend;
use Illuminate\Http\Request;
use App\Authorizable;
use Modules\Catalog\Entities\Catalog;
use App\Http\Controllers\Controller;
use App\Models\Label;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Log;
use Yajra\DataTables\DataTables;

class LabelController extends Controller
{
    use Authorizable;

    public function __construct()
    {
        // Page Title
        $this->module_title = 'Labels';

        // module name
        $this->module_name = 'labels';

        // directory path of the module
        $this->module_path = 'labels';

        // module icon
        $this->module_icon = 'c-icon fas fa-music';

        // module model name, path
        $this->module_model = "App\Models\Label";   
    }

    public function index($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Labels';

        if (!auth()->user()->can('edit_users')) {
            $id = auth()->user()->id;
        }
        
        // $$module_name_singular = $module_model::findOrFail($id);

        $labels = Label::where('user_id', $id)->get();
        $id_user = $id;
        
        
        $link = 'backend';
        if(Auth::user()->hasRole('label')){
            $link = 'label';
        }
    

        Log::info(label_case($module_title.' '.$module_action).' | User:'.auth()->user()->name.'(ID:'.auth()->user()->id.')');
     
        return view(
            "backend.$module_name.index",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular','labels',"link", "id_user")
        );
    }

    public function index_list($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'List';

        $$module_name = $module_model::select('id', 'name', 'user_id','is_ban','percentage')->where('user_id', $id);

        $data = $$module_name;

        return Datatables::of($$module_name)
        ->addIndexColumn()
        ->addColumn('action', function ($data) {
            $btn = "<div class='text-right'>
            <a href='".url("admin/labels/".$data->id)."/edit' class='btn btn-warning btn-sm mt-1' data-toggle='tooltip' title=''><i class='fas fa-edit'></i></a> <a href='".url("admin/labels/".$data->id)."/destroy' class='btn btn-danger btn-sm mt-1' data-toggle='tooltip' title='' onclick='return myFunction()';><i class='fas fa-trash'></i></a>
            </div>";

            return $btn;
        })
        ->rawColumns(['action'])
        ->orderColumns(['id'], '-:column $1')
        ->make(true);
    }

        /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Create';

        $id_user = $id;

        Log::info(label_case($module_title.' '.$module_action).' | User:'.auth()->user()->name.'(ID:'.auth()->user()->id.')');

        return view(
            "backend.$module_name.create",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', 'id_user')
        );
    }

    public function store(Request $request,  $id)
    {
        // dd("test");
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Store';

        $request->validate([
            'label'=> 'required|min:3|max:191',
            'percentage' => 'required|min:2|max:191',
        ]);

        $data = ['user_id' => $id,'name' => $request->label, 'percentage' => $request->percentage];
        $$module_name_singular = $module_model::create($data);

        Flash::success("<i class='fas fa-check'></i> New '".Str::singular($module_title)."' Added")->important();
        
        Log::info(label_case($module_title.' '.$module_action)." | '".$$module_name_singular->name.'(ID:'.$$module_name_singular->id.") ' by User:".auth()->user()->name.'(ID:'.auth()->user()->id.')');

        return redirect("admin/$module_name/$id");
    }

    public function edit($id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Edit';

        $$module_name_singular = $module_model::select('id', 'name', 'user_id','is_ban','percentage')->where('id', $id)->first();

        return view(
            "backend.$module_name.edit",
            compact('module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', "$module_name_singular")
        );
    }

    public function update(Request $request,  $id)
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);

        $module_action = 'Update';

        $request->validate([
            'label'=> 'required|min:3|max:191',
            'percentage' => 'required|min:2|max:191',
        ]);

        $$module_name_singular = $module_model::findOrFail($id);

        $data_array['name'] = $request->label;
        $data_array['percentage'] = $request->percentage;

        $label = $module_model::where('id', '=', $id)->first();
        $label->update($data_array);

        Flash::success("<i class='fas fa-check'></i> New '".Str::singular($module_title)."' Added")->important();

        Log::info(label_case($module_title.' '.$module_action)." | '".$$module_name_singular->name.'(ID:'.$$module_name_singular->id.") ' by User:".auth()->user()->name.'(ID:'.auth()->user()->id.')');

        return redirect("admin/$module_name/".$$module_name_singular->user_id);
    }

     /**
     * List of trashed ertries
     * works if the softdelete is enabled.
     *
     * @return Response
     */
    public function destroy($id)
    {
        $module_name = $this->module_name;
        $module_title = $this->module_title;
        $module_name_singular = Str::singular($this->module_name);
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;

        $module_action = 'Delete';

        
        $$module_name_singular = $module_model::where('id', '=', $id)->first();
        $$module_name_singular->delete();

        Flash::success("<i class='fas fa-check'></i> '".Str::singular($module_title)."' Deleted")->important();

        Log::info(label_case($module_title.' '.$module_action)." | '".$$module_name_singular->name.'(ID:'.$$module_name_singular->id.") ' by User:".auth()->user()->name.'(ID:'.auth()->user()->id.')');

        return redirect("admin/$module_name/".$$module_name_singular->user_id);
    }
}