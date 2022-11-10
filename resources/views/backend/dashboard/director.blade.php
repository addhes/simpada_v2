@extends('layouts.vertical',['isDark'=>true])


@section('css')
<link href="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />

<!-- <style>
    .row-bottom{
        background-color: white;
    }
</style> -->
@endsection

@section('breadcrumb')
<div class="row page-title align-items-center">
    <div class="col-sm-4 col-xl-6">
        <h4 class="mb-1 mt-0">Dashboard</h4>
    </div>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12 col-xl-16">
        <div class="card">
            <div class="card-body p-0">
                <div class="media p-3">
                    <div class="media-body">
                        <span class="text-muted text-uppercase font-size-12 font-weight-bold">Hello, Welcome back</span>
                        <h2 class="mb-0" style="text-transform: capitalize;">{{ Auth::user()->name }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card">
    <div class="row">
        <div class="col-md-6 col-xl-3">
            <div class="">
                <div class="card-body p-0">
                    <div class="media p-3">
                        <div class="media-body">
                            <span class="text-muted text-uppercase font-size-12 font-weight-bold">Saldo Saat Ini</span>
                            <h4 class="mb-0 text-success warning">Rp.<span
                                    id="premierTotalSum">@currency($balance)</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-16">
            <div class="p-3">
                <h5 class="card-title mt-0 mb-0 header-title">PENGAJUAN BERJALAN</h5>
                <hr>
                <!-- <div class="table-responsive"> -->
                <table id="datatable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Pengajuan</th>
                            <th>Pengajuan</th>
                            <th>User</th>
                            <th>Deskripsi</th>
                            <th>Estimasi Harga</th>
                            <th>Approval Keuangan</th>
                            <th>Approval Bos</th>
                        </tr>
                    </thead>
                </table>
                <!-- </div> -->
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="row">
        <div class="col-md-12 col-xl-16">
            <div class="p-3">
                <h5 class="card-title mt-0 mb-0 header-title">MENUNGGU KONFIRMASI</h5>
                <hr>
                <!-- <div class="table-responsive"> -->
                <table id="datatable-needconfirm" class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Pengajuan</th>
                            <th>Pengajuan</th>
                            <th>User</th>
                            <th>Deskripsi</th>
                            <th>Estimasi Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                </table>
                <!-- </div> -->
            </div>
        </div>
    </div>
</div>
<x-library.datatable isDef="false" />
<!-- row -->

@endsection

@push('before-scripts')
<!-- optional plugins -->
@endpush

@push('after-scripts')
<!-- DataTables Core and Extensions -->
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

table = $('#datatable').DataTable({
    order: [
        [2, 'desc']
    ],
    processing: true,
    serverSide: true,
    autoWidth: true,
    responsive: false,

    ajax: {
        'url': '{{ url("director/index_list") }}',
        'data': function(d) {
            d.bulan = $("#bulan").val()
        },
    },
    columns: [{
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            orderable: false,
            searchable: false,
        },
        {
            data: 'submission_code',
            name: 'submission_code',
        },
        {
            data: 'title',
            name: 'title',
            searchable: false
        },
        {
            data: 'name',
            name: 'name',
            searchable: false
        },
        {
            data: 'description',
            name: 'description',
            searchable: false,
        },
        {
            data: 'estimated_price',
            name: 'estimated_price',
            searchable: false,
            render: $.fn.dataTable.render.number('.', '.', 0, 'Rp.'),
        },
        {
            data: 'status_finance',
            name: 'status_finance',
            searchable: false,
        },
        {
            data: 'status_boss',
            name: 'status_boss',
            searchable: false,
        },
    ],

    "language": {
        "paginate": {
            "previous": "<i class='uil uil-angle-left'>",
            "next": "<i class='uil uil-angle-right'>"
        }
    },
});
</script>

<script type="text/javascript">
table = $('#datatable-needconfirm').DataTable({
    order: [
        [2, 'desc']
    ],
    processing: true,
    serverSide: true,
    autoWidth: true,
    responsive: false,

    ajax: {
        'url': '{{ url("director/index_list_needconfirm") }}',
        'data': function(d) {
            d.bulan = $("#bulan").val()
        },
    },
    columns: [{
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            orderable: false,
            searchable: false,
        },
        {
            data: 'submission_code',
            name: 'submission_code',
        },
        {
            data: 'title',
            name: 'title',
            searchable: false
        },
        {
            data: 'name',
            name: 'name',
            searchable: false
        },
        {
            data: 'description',
            name: 'description',
            searchable: false,
        },
        {
            data: 'estimated_price',
            name: 'estimated_price',
            searchable: false,
            render: $.fn.dataTable.render.number('.', '.', 0, 'Rp.'),
        },
        {
            data: 'status',
            name: 'status',
            searchable: false,
        }
    ],

    "language": {
        "paginate": {
            "previous": "<i class='uil uil-angle-left'>",
            "next": "<i class='uil uil-angle-right'>"
        }
    },
});
</script>

@endpush