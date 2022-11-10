@extends('layouts.vertical')

@section('title') {{ __($module_action) }} {{ $module_title }} @endsection

@section('breadcrumb')
<x-backend-breadcrumbs title="{{ $module_title }}">
<x-backend-breadcrumb-item type="active" icon='{{ $module_icon }}'>{{ $module_title }}</x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <h4 class="card-title mb-0">
                <i class="{{ $module_icon }}"></i> {{ $module_title }}
                    <small class="text-muted">{{ __($module_action) }}</small>
                </h4>
                <div class="small text-muted">
                    @lang(":module_name Management Dashboard", ['module_name'=>Str::title($module_name)])
                </div>
            </div>
            <!--/.col-->
            <div class="col-4">
                <div class="float-right">
                    <a href="{{ route("backend.$module_name.markAllAsRead") }}" class="btn btn-success mt-1"
                        data-toggle="tooltip" title="@lang('Mark All As Read')"> <i class="fas fa-plus"> </i> @lang('Tambah')</a>
                </div>
            </div>
        </div>
        <!--/.row-->

        <div class="row mt-4">
            <div class="col">
                <table id="datatable" class="table">
                    <thead>
                        <tr>
                            <th>
                                @lang('No')
                            </th>
                            <th>
                                @lang('Email')
                            </th>
                            <th>
                                @lang('Nama')
                            </th>
                            <th>
                                @lang('Phone')
                            </th>
                            <th>
                                @lang('Role')
                            </th>
                            <th>
                                @lang('Rekening')
                            </th>
                            <th class="text-right">
                                @lang('#')
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($$module_name as $module_name_singular)
                        <?php
                        $row_class = '';
                        $span_class = '';
                        ?>
                        <tr class="{{$row_class}}">
                            <td>
                            {{ $loop->index + 1 }}
                            </td>
                            <td>
                                {{ $module_name_singular->email }}
                            </td>
                            <td>
                                {{ $module_name_singular->name }}
                            </td>
                            <td>
                                {{ $module_name_singular->mobile }}
                            </td>
                            <td>
                                <!-- {{ $module_name_singular->name }} -->
                                Admin
                            </td>
                            <td class="text-right">
                                <a href='{!!route("backend.$module_name.show", $module_name_singular)!!}'
                                    class='btn btn-sm btn-warning mt-1' data-toggle="tooltip"
                                    title="@lang('Show') {{ ucwords(Str::singular($module_name)) }}">
                                    <i class="fas fa-money-check"></i>
                                </a>
                            </td>
                            <td class="text-right">
                                <a href='{!!route("backend.$module_name.show", $module_name_singular)!!}'
                                    class='btn btn-sm btn-primary mt-1' data-toggle="tooltip"
                                    title="@lang('Show') {{ ucwords(Str::singular($module_name)) }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href='{!!route("backend.$module_name.show", $module_name_singular)!!}'
                                    class='btn btn-sm btn-primary mt-1' data-toggle="tooltip"
                                    title="@lang('Show') {{ ucwords(Str::singular($module_name)) }}">
                                    <i class="fas fa-users"></i>
                                </a>
                                <a href='{!!route("backend.$module_name.show", $module_name_singular)!!}'
                                    class='btn btn-sm btn-warning mt-1' data-toggle="tooltip"
                                    title="@lang('Show') {{ ucwords(Str::singular($module_name)) }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href='{!!route("backend.$module_name.show", $module_name_singular)!!}'
                                    class='btn btn-sm btn-danger mt-1' data-toggle="tooltip"
                                    title="@lang('Show') {{ ucwords(Str::singular($module_name)) }}">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-7">
                <div class="float-left">
                    @lang('Total') {{ $$module_name->total() }} {{ ucwords($module_name) }}
                </div>
            </div>
            <div class="col-5">
                <div class="float-right">
                    {!! $$module_name->render() !!}
                </div>
            </div>
        </div>
    </div>
</div>
<x-library.datatable />
@endsection