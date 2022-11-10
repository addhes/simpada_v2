<div class="text-right w-100">
    @can('approval_'.$module_name)

    @if($data->director_app == null)
    @if($data->status == 2 || $data->status == 0)
    <x-buttons.approval route='{!!route("backend.$module_name.approval", $data)!!}' dataid="{{ $data->id }}"
        id="btnApprove" title="{{__('Approval')}} {{ ucwords(Str::singular($module_name)) }}" small="true" />

    <x-buttons.reject route='{!!route("backend.$module_name.reject", $data)!!}' dataid="{{ $data->id }}" id="btnReject"
        title="{{__('Reject')}} {{ ucwords(Str::singular($module_name)) }}" small="true" />
    @endif
    @endif
    @endcan
    <x-buttons.show route='{!!route("backend.$module_name.show", $data)!!}' dataid="{{ $data->id }}" id="btnShow"
        title="{{__('Show')}} {{ ucwords(Str::singular($module_name)) }}" small="true" />
    @if($data->finance_app == 1 && $data->director_app == 1)
    <x-buttons.upload route='{!!route("backend.$module_name.upload", $data)!!}' dataid="{{ $data->id }}" id="btnUpload"
        title="{{__('Show')}} {{ ucwords(Str::singular($module_name)) }}" small="true" />
    @endif
</div>