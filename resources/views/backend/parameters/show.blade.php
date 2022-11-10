@extends('layouts.vertical')

@section('title') {{ __($module_action) }} {{ $module_title }} @endsection

@section('breadcrumb')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item route='{{route("backend.$module_name.index")}}' icon='{{ $module_icon }}'>
        {{ $module_title }}
    </x-backend-breadcrumb-item>

    <x-backend-breadcrumb-item type="active">{{ __($module_action) }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <h4 class="card-title mb-0">
                    <i class="{{$module_icon}}"></i> {{ $module_title }}
                    <small class="text-muted">{{ __('labels.backend.users.show.action') }} </small>
                </h4>
                <div class="small text-muted">
                    {{ $module_title }} Management
                </div>
            </div>
            <!--/.col-->
            <div class="col-4">
                <div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">
                    <x-buttons.return-back />
                </div>
            </div>
            <!--/.col-->
        </div>
        <!--/.row-->

        <hr>

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tr>
                            <td>Param Group</td>
                            <td>:</td>
                            <td>{{ $$module_name_singular->param_group }}</td>
                        </tr>
                        <tr>
                            <td>Param Key</td>
                            <td>:</td>
                            <td>{{ $$module_name_singular->param_key }}</td>
                        </tr>
                        <tr>
                            <td>Param Text</td>
                            <td>:</td>
                            <td>{{ $$module_name_singular->param_text }}</td>
                        </tr>
                        <tr>
                            <td>Param Memo</td>
                            <td>:</td>
                            <td>{{ $$module_name_singular->param_memo }}</td>
                        </tr>
                    </table>
                </div>
                @can('delete_'.$module_name)
                <form id="delete-user-form"
                    action='{{route("backend.$module_name.destroy",$$module_name_singular->id)}}' method="POST">
                    @csrf
                    @method('DELETE')
                    <x-buttons.delete title="{{__('Delete')}} {{ ucwords(Str::singular($module_name)) }}">
                        {{__('Delete')}}
                    </x-buttons.delete>
                </form>
                @endcan
            </div>
        </div>

    </div>

    <div class="card-footer">
        <div class="row">
            <div class="col">
                <small class="float-right text-muted">

                </small>
            </div>
        </div>
    </div>
</div>

@endsection

@push ('after-scripts')

@endpush