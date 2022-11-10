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
                    <button type="button" data-toggle="modal" class="btn btn-primary" id="btnAdd"><i
                            class="fa fa-plus"></i></button>
                </div>
            </div>
            <!--/.col-->
        </div>
        <!--/.row-->

        <div class="row mt-4">
            <div class="col">
                <div class="">
                    <table id="datatable" class="table table-hover  ">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Bank</th>
                                <th>Aksi</th>
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

<div class="modal fade" id="modalSection" tabindex="-1" aria-labelledby="modalSection" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!--FORM ADD CATEGORY-->
                <form method="POST" action='{{ route("backend.$module_name.store") }}'>
                    <input type="hidden" name="_method" id="_method" value="">
                    {{ csrf_field() }}
                    @include("master::backend.$module_name.form")
                    <button type="submit" class="btn btn-sm btn-primary float-right" id="btnSubmit">Simpan</button>
                </form>
                <!--END FORM-->
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
$('#datatable').DataTable({
    processing: true,
    serverSide: true,
    autoWidth: true,
    responsive: false,
    ajax: '{{ route("backend.$module_name.index_list") }}',
    columns: [{
            data: 'DT_RowIndex',
            name: 'DT_RowIndex',
            "width": "1%"
        },
        {
            data: 'name',
            name: 'name',
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


$('body').on('click', '#btnAdd', function(event) {
    $(".modal-title").text("Add {{ $module_title }}");
    $('#modalSection').modal('show');
});

$('body').on('click', '#btnEdit', function(event) {
    event.preventDefault();
    var id = $(this).data('id');
    $.get('{{ route("backend.$module_name.getdata", "") }}' + "/" + id, function(data) {
        $('#_method').val('PUT');
        $('#name').val(data.name);
        $(".modal-title").text("Edit {{ $module_title }}");
        $('form').attr('action', '{{ route("backend.$module_name.update", "") }}' + "/" + id);
        $('#modalSection').modal('show');
    })
});

$('body').on('click', '#btnShow', function(event) {
    event.preventDefault();
    var id = $(this).data('id');
    $.get('{{ route("backend.$module_name.getdata", "") }}' + "/" + id, function(data) {
        $('#name').val(data.name);
        $('#name').attr('disabled', 'disabled');
        $('#btnSubmit').hide();
        $(".modal-title").text("Show {{ $module_title }}");
        $('#modalSection').modal('show');
    })
});

$('#modalSection').on('hidden.bs.modal', function() {
    $('#_method').val(null);
    $('#name').val(null);
    $('#name').removeAttr('disabled');
    $('form').attr('action', '{{ route("backend.$module_name.store", "") }}');
})
</script>


@endpush