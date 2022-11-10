<div class="text-right">
    <x-buttons.show route='{!!route("backend.$module_name.show", $data)!!}' dataid="{{ $data->id }}" id="btnShow"
        title="{{__('Show')}} {{ ucwords(Str::singular($module_name)) }}" small="true" />
    @if($accountability > 0)
    <x-buttons.download route='{!!route("backend.$module_name.download", $data)!!}' dataid="{{ $data->id }}"
        id="btnDownload" title="{{__('Download')}} {{ ucwords(Str::singular($module_name)) }}" small="true" />

    <x-buttons.show route='{!!route("backend.$module_name.accountability", $data)!!}' dataid="{{ $data->id }}" id="btnShow"
        title="{{__('Show')}} {{ ucwords(Str::singular('Pertanggung Jawaban')) }}" small="true" icon="fas fa-file" color="btn-warning"/>
    @endif
</div>