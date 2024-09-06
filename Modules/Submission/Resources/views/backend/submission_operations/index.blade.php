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
            <div class="col">
                <div class="float-right">
                    <a href="{{ route('backend.submission_operations.trashed') }}" class="btn" style="background-color: #c3c3c3"><i class="fa fa-archive"></i></a>
                </div>
            </div>
            <!--/.col-->
        </div>
        <!--/.row-->

        <div class="row mt-4">
            <div class="col">

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
                                <th>User</th>
                                <th>Company</th>
                                <th>Deskripsi</th>
                                <th>Estimasi Harga</th>
                                <th>Approval Keuangan</th>
                                <th>Approval BOS</th>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('body').on('click', '.submiops', function(event) {
        var dataId = $(this).attr('data-id');
        console.log(dataId)

        Swal.fire({
        title: "Apa Anda yakin?",
        text: "Data anda akan terhapus!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Iya, Hapus!"
        }).then((result) => {
        // console.log(result)
            if (result.isConfirmed) {
                $.ajax({
                type: 'DELETE',
                url: '/admin/submission_operations/' + dataId,
                success: function (data) {
                    console.log(data.success);
                    if (data.success == 'true') {
                        Swal.fire('Deleted!', 'Data Berhasil Terhapus', 'success');
                        $('#datatable').DataTable().ajax.reload();
                        // You can also remove the deleted item from the DOM if needed
                        // $(this).closest('div').remove();
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
            }
        });

            // event.preventDefault();
            // var id = $(this).data('id');
            // $.get('{{ route("backend.$module_name.getdata", "") }}' + "/" + id, function(data) {
            //     $('#_method').val('PUT');
            //     $('#name').val(data.name);
            //     $(".modal-title").text("Edit {{ $module_title }}");
            //     $('form').attr('action', '{{ route("backend.$module_name.update", "") }}' + "/" + id);
            //     $('#modalSection').modal('show');
            // })
        });

    });
    </script>

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
            'url': '{{ url("admin/submission_operations/index_list") }}',
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
                render: $.fn.dataTable.render.number('.', '.', 0, ''),
            },
            {
                data: 'name',
                name: 'name',
                searchable: false
            },
            {
                data: 'company',
                name: 'company',
                searchable: false
            },
            {
                data: 'description',
                name: 'description',
            },
            {
                data: 'estimated_price',
                name: 'estimated_price',
                searchable: false,
                render: $.fn.dataTable.render.number('.', '.', 0, '')
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
            {
                data: 'action',
                name: 'action',
                orderable: false,
            searchable: false
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
