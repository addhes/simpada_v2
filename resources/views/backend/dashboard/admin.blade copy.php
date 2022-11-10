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
                <div class="table-responsive">
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
                </div>
            </div>
        </div>
    </div>
</div>

<x-library.datatable isDef="false" />
<!-- row -->

@endsection

@push('before-scripts')
<!-- optional plugins -->
<script src="{{ URL::asset('assets/libs/moment/moment.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js"
    integrity="sha512-sW/w8s4RWTdFFSduOTGtk4isV1+190E/GghVffMA9XczdJ2MDzSzLEubKAs5h0wzgSJOQTRYyaz73L3d6RtJSg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
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
    ajax: '{{ url("admin/index_list") }}',

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
            searchable: false,
            render: $.fn.dataTable.render.number('', '.', 2, ''),
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
            render: $.fn.dataTable.render.number('', '.', 2, '')
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
    "drawCallback": function drawCallback() {
        var sumGross = this.api().ajax.json().gross_revenue;
        var sumPartner = this.api().ajax.json().partner_revenue;
        var sumPremier = this.api().ajax.json().premier_revenue;

        $('#grossTotalSum').html(sumGross.toFixed(2));
        $('#partnerTotalSum').html(sumPartner.toFixed(2));
        $('#premierTotalSum').html(sumPremier.toFixed(2));

        $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
    }
});

$("#btnfilter").click(function() {
    table.draw();
});
</script>
@endpush