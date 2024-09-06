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
                    <small class="text-muted">{{ $module_action }} </small>
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
                <div class="container-fluid" style="padding: auto;">
                    @if ($message = Session::get('msg'))
                    <div class="alert alert-warning alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif
                </div>

                <div class="container-fluid" style="padding: auto;">
                    <strong class="float-left">SALDO: <span class="text-success"> @currency($last_balance)
                        </span></strong> <br>
                        @if ($nores > 0)
                        <span class="text text-danger mb-4">
                            {{ $$module_name_singular->name }} Memiliki {{ $nores }} pertanggung jawaban yang belum di isi
                        </span>
                        @else
                        @endif
                    <strong class="float-right">STATUS: <span
                            class="{!! $status['class'] !!}">{{ strtoupper($status['status'])  }}</span></strong>
                </div>
                <br>
                <hr>


                {{ html()->form('PATCH', route("backend.$module_name.approve", $$module_name_singular->id))->class('form-horizontal')->attribute('enctype','multipart/form-data')->open() }}
                {{ csrf_field() }}

                @include("submission::$module_role.$module_name.form")

                <div class="row">
                    <div class="col-6">
                        <div class="form-group text-left mb-0">
                            <button class="btn btn-primary mr-1" type="submit">
                                Approve
                            </button>
                            <a href="{{ url('appfinance') }}" type="reset" class="btn btn-secondary">
                                Cancel
                            </a>
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="PUT">
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

@push ('after-scripts')
<script src="{{ url(asset('assets/js/custom.js')) }}"></script>
@endpush
