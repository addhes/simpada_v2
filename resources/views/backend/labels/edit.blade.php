@extends('layouts.vertical')

@section('title') {{ __($module_action) }} {{ $module_title }} @endsection

@section('breadcrumb')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item route='{{route("backend.$module_name.index", $$module_name_singular->id)}}'
        icon='{{ $module_icon }}'>
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
                    <i class="{{$module_icon}}"></i> {{ __('labels.backend.users.index.title') }}
                    <small class="text-muted">{{ __('labels.backend.users.edit.action') }} </small>
                </h4>
                <div class="small text-muted">
                    {{ __('labels.backend.users.index.sub-title') }}
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
                {{ html()->form('PATCH', route('backend.labels.update', $$module_name_singular->id))->class('form-horizontal')->open() }}
                {{ csrf_field() }}
                <div class="form-group">
                    {{ html()->label(__('labels.backend.users.fields.label'))->class('form-control-label')->for('label') }}
                    {{ html()->text('label')
                                ->class('form-control')
                                ->attribute('maxlength', 191)
                                ->value($$module_name_singular->name)
                                ->required() }}
                    <br>
                    {{ html()->label(__('labels.backend.users.fields.percentage'))->class('form-control-label')->for('percentage') }}
                    {{ html()->text('percentage')
                                ->class('form-control')
                                ->attribute('maxlength', 191)
                                ->value($$module_name_singular->percentage)
                                ->required() }}
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <x-buttons.update title="{{__('Update')}} {{ ucwords(Str::singular($module_name)) }}">
                                {{__('Update')}}
                            </x-buttons.update>
                        </div>
                    </div>
                </div>
                {{ html()->form()->close() }}
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