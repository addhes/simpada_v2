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
                    <small class="text-muted">{{ __('labels.backend.users.edit.action') }} </small>
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
                {{ html()->form('PATCH', route("backend.$module_name.update", $$module_name_singular->id))->class('form-horizontal')->open() }}
                {{ csrf_field() }}

                <table class="table table-hover">
                    <tr>
                        <td>Kode Pengajuan</td>
                        <td>:</td>
                        <td><input id="kode_pengajuan" name="kode_pengajuan" class="form-control" value="{{ $$module_name_singular->submission_code ?? '-'  }}">
                            <span class="error link_error_texts" id="link_error_texts" style="display:none; color: red;">Link Content Tidak Boleh Sama</span>
                            @error('link_content')
                                <p style="color: red;">{{ $message }}</p>
                            @enderror
                        </td>
                        <td style="display: none">
                            <input id="kode_pengajuan_cek" value="{{ $$module_name_singular->submission_code ?? '-'  }}">
                        </td>
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
                        <td>Nama Bank</td> <input class="form-control" value="{{ $$module_name_singular->bank_name ?? '-'  }}">
                        <td>:</td>
                        <td>
                            <select class="form-control" id="name_bank" name="name_bank">
                                @foreach ($bank as $data)
                                <option value="{{ $data->id }}" {{ ($data->id == $$module_name_singular->bank_id) ? 'selected' : ''  }} >{{ $data->name }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Nama Rekening Bank</td>
                        <td>:</td>
                        <td><input name="name_rek_bank" class="form-control" value="{{ $$module_name_singular->destination_account ?? '-'  }}"></td>
                    </tr>
                    <tr>
                        <td>Nomor Bank</td>
                        <td>:</td>
                        <td><input name="bank_number" class="form-control" value="{{ $$module_name_singular->account_number ?? '-'  }}"></td>
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
                            <td>Rp. {{ number_format($total) }}</td>
                          </tr>
                    </tbody>
                </table>
                <div>
                    <button class="btn btn-primary" id="submit-btn" type="submit">Save</button>
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

@push('after-styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push ('after-scripts')
<script src="{{ url(asset('assets/js/custom.js')) }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function(){
        $('#name_bank').select2({
            theme:"bootstrap",
        });

        $('#kode_pengajuan').on('keyup', function() {
                    // var id = this.id
                    kodepengajuan = $("#kode_pengajuan").val();
                    kodepengajuancek = $("#kode_pengajuan_cek").val();
                    proceed = false;
                    $.ajax({
                        url: "{{ route('backend.submission_operations.getsubops') }}",
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        data: {link: kodepengajuan},
                        success: function(response) {
                            console.log(response)
                            if (response.submission == null || response.submission == '') {
                                proceed = true;
                                $('#link_error_texts').hide();
                                $('#kode_pengajuan').removeClass("is-invalid");
                                $('#submit-btn').prop('disabled', false);
                            } else {

                                if (response.submission.submission_code == kodepengajuan && response.submission.submission_code != kodepengajuancek) {
                                    // alert('sama')
                                    $('#link_error_texts').html("Kode Pengajuan ini Sudah Terdaftar");
                                    $('#link_error_texts').show();
                                    $('#kode_pengajuan').addClass("is-invalid");
                                    $('#submit-btn').prop('disabled', true);

                                } else {
                                    $('#link_error_texts').hide();
                                    $('#kode_pengajuan').removeClass("is-invalid");
                                    $('#submit-btn').prop('disabled', false);

                                }
                            }
                        },
                    });
                    console.log(kodepengajuan)
                })
    })
</script>
@endpush
