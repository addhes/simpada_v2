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
            <div class="p-3">
                <label for="">Report Date</label>
                <form id="frm-filter" class="form-inline" action="#">
                    <div class="form-group mb-2 ">
                        <label class="sr-only">Bulan</label>
                        <select class="form-control" name="bulan" id="bulan">
                            @foreach($transactions as $item)
                            <option value="{{ $item->date }}">{{ $item->report_date }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" id="btnfilter" class="btn btn-primary mb-2 mx-3"><i class="fa fa-sync"></i>
                        Filter</button>
                </form>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body p-0">
                    <div class="media p-3">
                        <div class="media-body">
                            <span class="text-muted text-uppercase font-size-12 font-weight-bold">Gross Total</span>
                            <h2 class="mb-0 text-primary">$<span id="grossTotalSum"></span></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body p-0">
                    <div class="media p-3">
                        <div class="media-body">
                            <span class="text-muted text-uppercase font-size-12 font-weight-bold">Partner Total</span>
                            <h2 class="mb-0 text-info">$<span id="partnerTotalSum"></span></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card">
                <div class="card-body p-0">
                    <div class="media p-3">
                        <div class="media-body">
                            <span class="text-muted text-uppercase font-size-12 font-weight-bold">Premier Total</span>
                            <h2 class="mb-0 text-warning">$<span id="premierTotalSum"></span></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-16">
            <div class="p-3">
                <!-- <div class="table-responsive"> -->
                <table id="datatable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Label Name</th>
                            <th>Gross Revenue</th>
                            <th>Partner Revenue</th>
                            <th>Premier Revenue</th>
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
    ajax: {
        'url': '{{ url("admin/index_list") }}',
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
            data: 'label_name',
            name: 'sr.label_name',
        },
        {
            data: 'gross_revenue',
            name: 'gross_revenue',
            searchable: false,
            render: $.fn.dataTable.render.number('', '.', 2, ''),
        },
        {
            data: 'partner_revenue',
            name: 'partner_revenue',
            searchable: false,
            render: $.fn.dataTable.render.number('', '.', 2, ''),
        },
        {
            data: 'premier_revenue',
            name: 'premier_revenue',
            searchable: false,
            render: $.fn.dataTable.render.number('', '.', 2, ''),
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

<!-- init js -->
<!-- <script src="{{ URL::asset('assets/js/pages/dashboard.init.js') }}"></script> -->

<script>
const chart = <?= $chart ?>;

const label_chart = <?= $label_chart ?>;
const barChart = new Chart(document.getElementById('report-chart'), {
    type: 'bar',
    data: {
    labels : label_chart,
    datasets : [
      {
        label:'Income',
        backgroundColor : 'rgb(60, 155, 120, 1)',
        borderColor : 'rgba(151, 187, 205, 0.8)',
        highlightFill : 'rgba(151, 187, 205, 0.75)',
        highlightStroke : 'rgba(151, 187, 205, 1)',
        data : chart
      }
    ]
  },
    options: {
        responsive: true,
        maintainAspectRatio: true
    }
})
</script>
@endpush