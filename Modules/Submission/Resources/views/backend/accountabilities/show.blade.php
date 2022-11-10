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
                    <i class="{{$module_icon}}"></i> {{ $module_title }}
                    <small class="text-muted">{{ __('labels.backend.users.show.action') }} </small>
                </h4>
                <div class="small text-muted">
                    {{ $module_name }} Management
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
                    <strong>DETAIL PENGAJUAN</strong>
                </div>

                <table class="table table-responsive  tbl-show">
                    <tr>
                        <td><strong>Kode Pengajuan</strong></td>
                        <td>:</td>
                        <td>{{ $$module_name_singular->submission_code }}</td>
                    </tr>
                    <tr>
                        <td><strong>Date</strong></td>
                        <td>:</td>
                        <td>{{ $$module_name_singular->date }}</td>
                    </tr>
                    <tr>
                        <td><strong>Deskripsi</strong></td>
                        <td>:</td>
                        <td>{{ $$module_name_singular->description }}</td>
                    </tr>
                    <tr>
                        <td><strong>Attachment</strong></td>
                        <td>:</td>
                        <td>
                            <div class="btn-group">
                                @if(($accountability->accountability_attachment ?? '') == '')
                                <p class="font-italic text-danger">Attachment tidak ada.</p>
                                @else
                                <a href="{{ asset ('storage/accountability-attachment/'.$accountability->accountability_attachment) }}"
                                    target="_blank"
                                    class="{{ $accountability->accountability_attachment == '' ? 'a-disabled' : '' }}"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class='uil uil-file-alt mr-1'></i>Unduh</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                </table>

                <hr>
                <div class="container-fluid" style="padding: auto;">
                    <strong>DETAIL BIAYA </strong>
                </div>

                <table class="table table-responsive" id="tbl_posts">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>
                    <tbody id="tbl_posts_body">
                        @foreach($accountabilitydetail ?? [] as $index => $item)
                        @php
                        $index += 1;
                        @endphp
                        <tr id="rec-{{ $index }}">
                            <td><span class="sn">{{ $index }}</span>.</td>
                            <td><span class="sn">{{ $item->date }}</span></td>
                            <td>
                                {{ $item->description}}
                            </td>
                            <td>
                                Rp.@currency($item->nominal)
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr id="rec-1">
                            <td></td>
                            <td class="font-weight-bold">Total</td>
                            <td></td>
                            <td> Rp. @currency($total) </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                <hr>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <x-buttons.return-back title="{{__('Update')}} {{ ucwords(Str::singular($module_name)) }}">
                            </x-buttons.return-back>
                        </div>
                    </div>
                </div>

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