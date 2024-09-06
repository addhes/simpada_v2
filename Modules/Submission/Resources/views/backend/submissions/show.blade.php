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
                    <strong>STATUS: <span
                            class="{!! $status['class'] !!}">{{ strtoupper($status['status'])  }}</span></strong>
                </div>

                <table class="table table-responsive  tbl-show">
                    <tr>
                        <td>Pengajuan</td>
                        <td>:</td>
                        <td>{{ $$module_name_singular->title }}</td>
                    </tr>
                    <tr>
                        <td>Deskripsi</td>
                        <td>:</td>
                        <td>{{ $$module_name_singular->description }}</td>
                    </tr>
                    <tr>
                        <td>Category</td>
                        <td>:</td>
                        <td>{{ $$module_name_singular->category }}</td>
                    </tr>
                    <tr>
                        <td>Channel</td>
                        <td>:</td>
                        <td>{{ $$module_name_singular->channel }}</td>
                    </tr>
                    <tr>
                        <td>Bank Tujuan</td>
                        <td>:</td>
                        <td>{{ $$module_name_singular->bank }}</td>
                    </tr>
                    <tr>
                        <td>Rekening Tujuan</td>
                        <td>:</td>
                        <td>{{ $$module_name_singular->destination_account }}</td>
                    </tr>
                    <tr>
                        <td>Nomor Rekening Tujuan</td>
                        <td>:</td>
                        <td>{{ $$module_name_singular->account_number }}</td>
                    </tr>
                    <tr>
                        <td>Ket. Keuangan</td>
                        <td>:</td>
                        <td>{{ $$module_name_singular->finance_desc ?? '-'}}</td>
                    </tr>
                    <tr>
                        <td>Ket. Boss</td>
                        <td>:</td>
                        <td>{{ $$module_name_singular->director_desc ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Attachment</td>
                        <td>:</td>
                        <td>
                            <div class="btn-group">
                                @if($submission->user_attachment == '')
                                    <p class="font-italic text-danger">Attachment tidak ada.</p>
                                @else

                                    @if (Storage::exists('public/user-attachment/'.$$module_name_singular->user_attachment))
                                    <a href="{{ asset ('storage/user-attachment/'.$submission->user_attachment) }}"
                                        target="_blank" class="{{ $submission->user_attachment == '' ? 'a-disabled' : '' }}"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class='uil uil-file-alt mr-1'></i>Unduh</a>
                                    @else
                                    <a href="{{ $submission->user_attachment }}"
                                        target="_blank" class="{{ $submission->user_attachment == '' ? 'a-disabled' : '' }}"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class='uil uil-file-alt mr-1'></i>Unduh</a>
                                    @endif
                                @endif



                                {{-- @if($submission->user_attachment == '' )
                                <p class="font-italic text-danger">Attachment tidak ada.</p>
                                @else
                                    @if (asset ('storage/user-attachment/'.$submission->user_attachment))
                                        <a href="{{ $submission->user_attachment }}"
                                            target="_blank" class="{{ $submission->user_attachment == '' ? 'a-disabled' : '' }}"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class='uil uil-file-alt mr-1'></i>Unduh</a>
                                    @else
                                        <a href="{{ asset ('storage/user-attachment/'.$submission->user_attachment) }}"
                                        target="_blank" class="{{ $submission->user_attachment == '' ? 'a-disabled' : '' }}"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class='uil uil-file-alt mr-1'></i>Unduh</a>
                                    @endif
                                @endif --}}
                            </div>
                        </td>
                    </tr>
                </table>

                <hr>
                <div class="container-fluid" style="padding: auto;">
                    <strong>ESTIMASI BIAYA </strong>
                </div>

                <table class="table table-responsive" id="tbl_posts">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Keterangan</th>
                            <th>Nominal</th>
                        </tr>
                    </thead>
                    <tbody id="tbl_posts_body">
                        @foreach($submissiondetail ?? [] as $index => $item)
                        @php
                        $index += 1;
                        @endphp
                        <tr id="rec-{{ $index }}">
                            <td><span class="sn">{{ $index }}</span>.</td>
                            <td>
                                {{ $item->description}}
                            </td>
                            <td>
                                Rp. @currency($item->nominal)
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr id="rec-1">
                            <td></td>
                            <td class="font-weight-bold">Total Estimasi</td>
                            <td> Rp. @currency($total) </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                <hr>

                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <!-- <x-buttons.return-back title="{{__('Update')}} {{ ucwords(Str::singular($module_name)) }}">
                            </x-buttons.return-back> -->

                            @if($$module_name_singular->finance_app == null && $$module_name_singular->director_app ==
                            null)
                            @can('delete_'.$module_name)
                            <form id="delete-user-form"
                                action='{{route("backend.$module_name.destroy",$$module_name_singular->id)}}'
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <x-buttons.delete title="{{__('Delete')}} {{ ucwords(Str::singular($module_name)) }}">
                                    {{__('Delete')}}
                                </x-buttons.delete>
                            </form>
                            @endcan
                            @endif
                        </div>
                    </div>
                </div>

                @if($status['status'] == 'Finish')
                <div class="form-group row">
                    <div class="col-lg-12">
                        <div class="btn-group">
                            <a href="{{ asset ('storage/finance-attachment/'.$submission->finance_attachment) }}"
                                target="_blank" class="{{ $submission->finance_attachment == '' ? 'a-disabled' : '' }}"
                                aria-haspopup="true" aria-expanded="false">
                                <i class='uil uil-file-alt mr-1'></i>Download Attachment Keuangan</a>
                        </div>
                        @if($submission->finance_attachment == '')
                        <p class="font-italic text-danger">Attachment belum ada.</p>
                        @endif
                    </div>
                </div>
                @endif

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
