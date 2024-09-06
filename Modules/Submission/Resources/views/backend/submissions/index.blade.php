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
                    {{-- <div class="container" id="urgentGroup">
                        <input type="checkbox" id="urgent" name="urgent" value="urgent" class="ml-3"
                            onclick="urgentFunction()">
                        <label for="urgent"> <b class="text-danger">PERMINTAAN URGENT ??</b> </label>
                    </div> --}}
                    <div class="container">
                        <x-buttons.create route='{{ route("backend.$module_name.create") }}'
                            title="{{__('Create')}} {{ ucwords(Str::singular($module_name)) }}" id="btnPengajuan" class="float-right" />
                    </div>
                </div>
            </div>
            <!--/.col-->
        </div>
        <!--/.row-->

        <div class="row mt-4">
            <div class="col">

                <h4 class="header-title mt-0 mb-1">Note:</h4>
                <span class="text text-danger">
                    Dana langsung dikirim ke rekening vendor atau supplier pembelian barang atau jasa untuk
                    pengajuan
                    yang dilakukan.
                </span>

                <br>
                <p id="urgentMessage" style="display:none; text-align: center;"
                    class="text-danger justify-content-center"><b>Dengan Mencentang PERMINTAAN URGENT, maka Anda bisa
                        melakukan Pengajuan Baru tanpa melengkapi Pertanggung Jawaban terlebih dahulu. Namun permintaan
                        Anda tersebut akan langsung ke Direktur untuk Dipertimbangkan.</b></p>

                <!-- batas -->
                <div class="container-fluid" style="padding: auto;">
                    @if ($message = Session::get('success'))
                    <div class="alert alert-info alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                    @endif
                </div>

                <div class="table-responsive mt-3">
                    <table id="datatable" class="table table-hover ">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode Pengajuan</th>
                                <th>Pengajuan</th>
                                <th>Tgl Pengajuan</th>
                                <th>Estimasi Harga</th>
                                <th>Approval Keuangan</th>
                                <th>Approval Bos</th>
                                <th class="text-right">{{ __('labels.backend.action') }}</th>
                            </tr>
                        </thead>
                    </table>
                </div>
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

// $(document).ready(function() {
//     var urgentGroup = document.getElementById("urgentGroup");
//     var checkBox = document.getElementById("urgent");
//     var urgentMessage = document.getElementById("urgentMessage");
//     var btnPengajuan = document.getElementById("btnPengajuan");

//     var nores = "{{ $nores }}";
//     var cab = "{{ auth()->user()->company_code }}";

//     // alert(cab);

//     if (cab == 'wbb') {
//         if (nores > 1) {
//             btnPengajuan.style.display = "none";
//             urgentGroup.style.display = "block";
//         } else {
//             btnPengajuan.style.display = "block";
//             urgentGroup.style.display = "none";
//         }
//     } else {
//         if (nores > 0) {
//             btnPengajuan.style.display = "none";
//             urgentGroup.style.display = "block";
//         } else {
//             btnPengajuan.style.display = "block";
//             urgentGroup.style.display = "none";
//         }
//     }


//     // if (nores > 0) {
//     //     btnPengajuan.style.display = "none";
//     //     urgentGroup.style.display = "block";
//     // } else {
//     //     btnPengajuan.style.display = "block";
//     //     urgentGroup.style.display = "none";
//     // }

//     $("#urgent").click(function() {
//         if (checkBox.checked == true) {
//             urgentMessage.style.display = "block";
//             btnPengajuan.style.display = "inline";
//         } else {
//             urgentMessage.style.display = "none";
//             btnPengajuan.style.display = "none";
//         }
//     });

// });
</script>

<!-- DataTables Core and Extensions -->
<script type="text/javascript">
$('#datatable').DataTable({
    processing: true,
    serverSide: true,
    autoWidth: true,
    responsive: false,
    ajax: '{{ route("backend.$module_name.index_list") }}',
    columns: [{
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            orderable: false,
            searchable: false
        },
        {
            data: 'submission_code',
            name: 'submission_code',
            "width": "10%"
        },
        {
            data: 'title',
            name: 'title',
            "width": "15%"
        },
        {
            data: 'created_at',
            name: 'created_at',
            "width": "10%"
        },
        {
            data: 'estimated_price',
            name: 'estimated_price',
            render: $.fn.dataTable.render.number('.', '.', 0, 'Rp.'),
            "width": "15%"
        },
        {
            data: 'finance_app',
            name: 'finance_app',
            "width": "15%"
        },
        {
            data: 'director_app',
            name: 'director_app',
            "width": "15%"
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
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

$(document).on('ajaxComplete ready', function() {
    $('.modalMd').off('click').on('click', function() {
        $('#form-pencipta').load($(this).attr('value'));
    });
});
</script>


@endpush
