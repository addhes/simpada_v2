@props(['isDef' => "true"])
@push ('after-styles')
<!-- DataTables Core and Extensions -->
<link rel="stylesheet" href="{{ asset('assets/libs/datatables/datatables.min.css') }}">
@endpush
@push ('after-scripts')
<!-- DataTables Core and Extensions -->
<script type="text/javascript" src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
<script type="text/javascript">
    @if($isDef=="true")
        $('#datatable').DataTable({
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
    @endif
</script>
@endpush