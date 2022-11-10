@extends ('layouts.vertical')

@section('title') {{ $module_action }} {{ $module_title }} @endsection

@section('breadcrumb')
<x-backend-breadcrumbs>
    <x-backend-breadcrumb-item type="active" data-feather="{{ $module_icon }}">{{ $module_title }}
    </x-backend-breadcrumb-item>
</x-backend-breadcrumbs>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col">
                <h4 class="card-title mb-0">
                    <i class="{{ $module_icon }}"></i> {{ $module_title }} <small
                        class="text-muted">{{ $module_action }}</small>
                </h4>
                <div class="small text-muted">
                    {{ $module_title }} Management
                </div>
            </div>

            <div class="col-6 col-sm-4">
                <div class="float-right">
                    <!-- <x-buttons.create route='{{ route("backend.$module_name.create") }}'
                        title="{{__('Create')}} {{ ucwords(Str::singular($module_name)) }}" /> -->
                </div>
            </div>
            <!--/.col-->
        </div>
        <!--/.row-->

        <div class="row mt-4">
            <div class="col">
                <form method="get" action="report/export_excel" id="form-filter">
                    <div class="container-sm float-left">
                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="category">Status</label>
                                    <select class="custom-select category" name="category" id="category"
                                        onChange="statusFunction()">
                                        <option value="0">All</option>
                                        <option value="1">Approved</option>
                                        <option value="2">Rejected</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm" id="pjb-group">
                                <div class="form-group" id="">
                                    <label for="pjb">Pertanggung Jawaban</label>
                                    <select class="custom-select pjb" name="pjb" id="pjb">
                                        <option value="0">All</option>
                                        <option value="1">Sudah</option>
                                        <option value="2">Belum</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-group mb-sm-0 col-lg-12">
                                    <div class="text-center grup">
                                        <input class="form-control" id="from_date" type="date" name="from_date"
                                            value="{{$tgl_pertama}}">
                                        <span class="sd"> s/d </span>
                                        <input class="form-control" id="to_date" type="date" name="to_date"
                                            value="{{$tgl_terakhir}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-group" id="">
                                    <div style="float: left">
                                        <button type="button" id="reloaddata" class="btn btn-primary mt-4"><i
                                                class="fa fa-sync"></i> Filter</button>
                                        {{ csrf_field() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                    </div>
                </form>
                <div class="table-responsive">
                    <table id="datatable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Pengajuan</th>
                                <th>Pengajuan</th>
                                <th>User</th>
                                <th>Tanggal</th>
                                <th>Estimasi Harga</th>
                                <th>Approval Keuangan</th>
                                <th>Approval Bos</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <br>
                <button class="btn btn-primary mb-2 text-white float-right" id="btnexport"><i
                        class="fa fa-download"></i> Download
                    Laporan</button>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-7">
                <div class="float-left">

                </div>
            </div>
            <div class="col-5">
                <div class="float-right">

                </div>
            </div>
        </div>
    </div>
</div>

<x-library.datatable isDef="false" />
@endsection

@push ('after-scripts')
<script>
function myFunction() {
    if (!confirm("Yakin Ingin Menghapus Label Ini?"))
        event.preventDefault();
}
</script>

<!-- DataTables Core and Extensions -->
<script type="text/javascript">
function loadData() {
    var category = $("select#category option").filter(":selected").val();
    var pjb = $("select#pjb option").filter(":selected").val();
    var from_date = new Date($('#from_date').val());
    var from = [from_date.getFullYear(), from_date.getMonth() + 1, from_date.getDate()].join('-');
    var to_date = new Date($('#to_date').val());
    var to = [to_date.getFullYear(), to_date.getMonth() + 1, to_date.getDate()].join('-');

    if ($.fn.DataTable.isDataTable('#datatable')) {
        $('#datatable').DataTable().destroy();
    }

    $('#datatable').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: true,
        responsive: false,
        ajax: {
            "dataType": 'json',
            "url": "{{ route('backend.reports.index_list') }}",
            "type": "GET",
            data: {
                status: category,
                pjb: pjb,
                from_date: from,
                to_date: to
            },
        },
        columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
            },
            {
                data: 'submission_code',
                name: 'submission_code',
            },
            {
                data: 'title',
                name: 'title',
            },
            {
                data: 'name',
                name: 'name',
            },

            {
                data: 'tgl',
                name: 'tgl'
            },
            {
                data: 'estimated_price',
                name: 'estimated_price',
                render: $.fn.dataTable.render.number('.', '.', 0, 'Rp. '),
            },
            {
                data: 'status',
                name: 'status',
            },
            {
                data: 'statusbos',
                name: 'statusbos',
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                "width": "15%"
            },
        ],
        "language": {
            "paginate": {
                "previous": "<i class='uil uil-angle-left'>",
                "next": "<i class='uil uil-angle-right'>"
            }
        },
        "drawCallback": function drawCallback() {
            $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
        }
    });
}

$("#btnexport").click(function() {
    var category = $("#category").val();
    var pjb = $("#pjb").val();
    var from_date = $("#from_date").val();
    var to_date = $("#to_date").val();

    // alert(from_date);
    window.location = "{{ url('admin/reports/export_excel?category=') }}" + category + '&pjb=' + pjb +
        '&from_date=' + from_date + '&to_date=' + to_date;
});

$(document).ready(function() {
    $('#reloaddata').click(loadData);
});

$(document).ready(function() {
    document.getElementById("pjb-group").style.display = "none";
    loadData();
});

function statusFunction() {
    var x = document.getElementById("category").value;
    if (x == 1) {
        document.getElementById("pjb-group").style.display = "block";
    } else {
        document.getElementById("pjb-group").style.display = "none";
    }
}


// function myFunction() {
//     alert("testing");
// }

// $("#btnexport").click(function() {
//     var category = $("#category").val();
//     var pjb = $("#pjb").val();
//     var from_date = $("#from_date").val();
//     var to_date = $("#to_date").val();


// });


// $(document).on('click', '#btnfilter', function(){
//     var category = $(this).attr('category');
//     var pjb = $(this).attr('pjb');
//     var from_date = $(this).attr('from_date');
//     var to_date = $(this).attr('to_date');
//     var_dump(category);

//     console.log(category);
//     var formData = new FormData();
//     formData.append('category',category);
//     formData.append('pjb',pjb);
//     formData.append('from_date',from_date);
//     formData.append('to_date',to_date);

//     jQuery.ajax({
//         type: "get",
//         url: site_url+"/export_excel",
//         data: formData,
//         contentType:false,
//         processData:false,
//         success: function (res) {
//             const data = res;
//             const link = document.createElement('a');
//             link.setAttribute('href', data);
//             link.setAttribute('download', 'yourfilename.extensionType'); // Need to modify filename ...
//             link.click();
//         }
//     });
// });
</script>
@endpush