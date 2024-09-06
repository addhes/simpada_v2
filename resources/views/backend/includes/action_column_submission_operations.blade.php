<div class="text-right btn-group">
    @can('edit_'.$module_name)
    <x-buttons.editsops route='{!!route("backend.$module_name.edit", $data)!!}' dataid="{{ $data->id }}" id="btnEdit"
        title="{{__('Edit')}} {{ ucwords(Str::singular($module_name)) }}" small="true" class="mr-2"/>
    @endcan
    <x-buttons.show route='{!!route("backend.$module_name.show", $data)!!}' dataid="{{ $data->id }}" id="btnShow"
        title="{{__('Show')}} {{ ucwords(Str::singular($module_name)) }}" small="true" class="mr-2" />


    <button class="btn btn-danger btn-sm submiops" data-id="{{ $data->id }}" id="submiops"><i class="fa fa-trash"></i></button>
    {{-- <x-buttons.delete dataid="{{ $data->id }}" class="btnDelOPS" {{ ucwords(Str::singular($module_name)) }}" small="true" /> --}}

    {{-- <x-buttons.delete route='{!!route("backend.$module_name.show", $data)!!}' dataid="{{ $data->id }}" id="btnShow"
            title="{{__('Delete')}} {{ ucwords(Str::singular($module_name)) }}" small="true" /> --}}
</div>
