<div class="text-right">
    @can('edit_'.$module_name)
    
    @if($diffinday <= 2)
    <x-buttons.edit route='{!!route("backend.$module_name.edit", $data->id)!!}' dataid="{{ $data->id }}" id="btnEdit"
        title="{{__('Edit')}} {{ ucwords(Str::singular($module_name)) }}" small="true" />
    @endif
    @endcan
    <x-buttons.show route='{!!route("backend.$module_name.show", $data->id)!!}' dataid="{{ $data->id }}" id="btnShow"
        title="{{__('Show')}} {{ ucwords(Str::singular($module_name)) }}" small="true" />
</div>