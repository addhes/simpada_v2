<div class="text-right">
    @can('approval_'.$module_name)

    @if($data->director_app == null)
    <x-buttons.approval route='{!!route("backend.$module_name.approval", $data->id)!!}' dataid="{{ $data->id }}"
        id="btnApprove" title="{{__('Approval')}} {{ ucwords(Str::singular($module_name)) }}" small="true" />

    <x-buttons.reject route='{!!route("backend.$module_name.reject", $data->id)!!}' dataid="{{ $data->id }}" id="btnReject"
        title="{{__('Reject')}} {{ ucwords(Str::singular($module_name)) }}" small="true" />
    @endif
    @endcan
    <x-buttons.show route='{!!route("backend.$module_name.show", $data->id)!!}' dataid="{{ $data->id }}" id="btnShow"
        title="{{__('Show')}} {{ ucwords(Str::singular($module_name)) }}" small="true" /> 
</div>