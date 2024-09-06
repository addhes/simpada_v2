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

        <div class="row mt-4 view">
            <div class="col">
                {{-- {{ html()->form('PATCH', route("backend.$module_name.update", $$module_name_singular->id))->class('form-horizontal')->open() }}
                {{ csrf_field() }} --}}

                <table class="table table-hover">
                    <tr>
                        <td>Kode Pengajuan</td>
                        <td>:</td>
                        <td><b>{{ $$module_name_singular->submission_code ?? '-' }}</b></td>
                    </tr>
                    <tr>
                        <td>Pengajuan</td>
                        <td>:</td>
                        <td><b>{{ $$module_name_singular->title ?? '-' }}</b></td>
                    </tr>
                    <tr>
                        <td>Nama User Pengaju</td>
                        <td>:</td>
                        <td><b>{{ $$module_name_singular->name_user ?? '-' }}</b></td>
                    </tr>
                    <tr>
                        <td>Company</td>
                        <td>:</td>
                        <td><b>{{ $$module_name_singular->company ?? '-' }}</b></td>
                    </tr>
                    <tr>
                        <td>Deskripsi</td>
                        <td>:</td>
                        <td><b>{{ $$module_name_singular->description ?? '-' }}</b></td>
                    </tr>
                    <tr>
                        <td>Estimasi Harga</td>
                        <td>:</td>
                        <td><b> Rp. {{ number_format($$module_name_singular->estimated_price) ?? '-'  }}</b></td>
                    </tr>
                    <tr>
                        <td>Kategori</td>
                        <td>:</td>
                        <td><b>{{ $$module_name_singular->category_name ?? '-'  }}</b></td>
                    </tr>
                    <tr>
                        <td>Deskripsi Kategori/Channel</td>
                        <td>:</td>
                        <td><b>{{ $$module_name_singular->category_description ?? '-'  }}</b></td>
                    </tr>
                    <tr>
                        <td>Nama Bank</td>
                        <td>:</td>
                        <td><b>{{ $$module_name_singular->bank_name ?? '-'  }}</b></td>
                    </tr>
                    <tr>
                        <td>Nama Rekening Bank</td>
                        <td>:</td>
                        <td><b>{{ $$module_name_singular->destination_account ?? '-' }}</b></td>
                    </tr>
                    <tr>
                        <td>Nomor Bank</td>
                        <td>:</td>
                        <td><b>{{ $$module_name_singular->account_number ?? '-'  }}</b></td>
                    </tr>
                    <tr>
                        <td>Approval Keuangan</td>
                        <td>:</td>
                        <td>
                            @if ($$module_name_singular->finance_app == 1)
                                <span class="badge badge-success">Approved</span>
                            @elseif ($$module_name_singular->finance_app == 2)
                                <span class="badge badge-danger">Rejected</span>
                            @else
                                <span class="badge badge-warning">No action yet</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Approval BOS</td>
                        <td>:</td>
                        <td>
                            @if ($$module_name_singular->director_app == 1)
                                <span class="badge badge-success">Approved</span>
                            @elseif ($$module_name_singular->director_app == 2)
                                <span class="badge badge-danger">Rejected</span>
                            @else
                                <span class="badge badge-warning">No action yet</span>
                            @endif
                        </td>
                        {{-- <td>{{ $$module_name_singular->email }}</td> --}}
                    </tr>
                    <tr>
                        <td>Attachment</td>
                        <td>:</td>
                        <td>
                            <div class="btn-group">
                                @if (!empty($accountability))
                                    <p class="font-italic text-danger">Attachment tidak ada.</p>
                                @else
                                <a href="{{ asset ('storage/accountability-attachment/'.$accountability->accountability_attachment) }}"
                                    target="_blank"
                                    class="{{ $accountability->accountability_attachment == '' ? 'a-disabled' : '' }}"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class='fas fa-file mr-1'></i>Unduh</a>
                                @endif

                            </div>
                        </td>
                    </tr>
                </table>

                <table class="table table-bordered table-hover">
                    <thead>
                        <th>No</th>
                        <th>Keterangan</th>
                        <th>Nominal</th>
                    </thead>
                    <tbody>
                        @php $sum_tot_nominal = 0; @endphp
                        @foreach ($submissiondetail  as $itm => $data)
                            <tr>
                                <td>{{ $itm+1 }}</td>
                                <td>{{ $data->description }}</td>
                                <td>Rp. {{ number_format($data->nominal) }}</td>
                            </tr>
                            <?php $sum_tot_nominal += $data->nominal ?>
                        @endforeach
                        <tr class="table-active">
                            <td colspan="2"><b>Total Nominal</b></td>
                            <td>Rp. {{ number_format($sum_tot_nominal) }}</td>
                          </tr>
                    </tbody>
                </table>

                {{-- {{ html()->form()->close() }} --}}
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
