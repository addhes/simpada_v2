<div class="text-right">
    @can('edit_'.$module_name)
    
    @if($data->disbursement_code == $last_trans->trans_code)
    <x-buttons.edit route='{!!route("backend.$module_name.edit", $data)!!}' dataid="{{ $data->id }}" id="btnEdit"
        title="{{__('Edit')}} {{ ucwords(Str::singular($module_name)) }}" small="true" />
    @endif
    @endcan
    <x-buttons.show route='{!!route("backend.$module_name.show", $data)!!}' dataid="{{ $data->id }}" id="btnShow"
        title="{{__('Show')}} {{ ucwords(Str::singular($module_name)) }}" small="true" />
</div>