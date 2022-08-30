@extends('layouts.master')

@section('css')

<link href="plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

<!-- Sweet-Alert  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>


@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title">Quản lý Mail Reg</h4>
</div>
<div class="col-sm-6">
    <div class="float-right">
        @can('mail_reg-add')
        <a class="btn btn-success" href="javascript:void(0)" id="createNewMail">Thêm mới</a>
        @endcan
    </div>
</div>
{{--@include('modals.mailmanage')--}}
@endsection
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered dt-responsive nowrap data-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Gmail Child</th>
                            <th>Pass Child</th>
                            <th>Gmail Parent</th>
                            <th>Pass Parent</th>
                            <th>Phone Parent</th>
                            <th>Mailrecovery</th>
                            <th>Họ và tên</th>
                            <th>Sinh nhật</th>


                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
@section('script')

<!-- Required datatable js -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables/dataTables.bootstrap4.min.js"></script>
<!-- Buttons examples -->
<script src="plugins/datatables/dataTables.buttons.min.js"></script>
<script src="plugins/datatables/buttons.bootstrap4.min.js"></script>
<script src="plugins/datatables/jszip.min.js"></script>
<script src="plugins/datatables/pdfmake.min.js"></script>
<script src="plugins/datatables/vfs_fonts.js"></script>
<script src="plugins/datatables/buttons.html5.min.js"></script>
<script src="plugins/datatables/buttons.print.min.js"></script>
<script src="plugins/datatables/buttons.colVis.min.js"></script>
<!-- Responsive examples -->
<script src="plugins/datatables/dataTables.responsive.min.js"></script>
<script src="plugins/datatables/responsive.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/plug-ins/1.11.3/pagination/input.js"></script>

<!-- Datatable init js -->
<script src="assets/pages/datatables.init.js"></script>

<script src="plugins/select2/js/select2.min.js"></script>


<script type="text/javascript">
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('.data-table').DataTable({
            searching: true,
            pagingType: "input",
            displayLength: 500,
            lengthMenu: [500, 1000, 2000, 5000],
            serverSide: true,
            processing: true,
            ajax: {
                url: '{{ route('mail_reg.getMailRegs') }}',
                type: "post",
            },
            columns: [
                {data: 'gmail', name: 'gmail'},
                {data: 'pass', name: 'pass'},
                {data: 'mailrecovery', name: 'mailrecovery'},
                {data: 'parent_pass', name: 'parent_pass'},
                {data: 'phone', name: 'phone'},
                {data: 'parent_mailrecovery', name: 'parent_mailrecovery'},
                {data: 'hovaten', name: 'hovaten'},
                {data: 'birth', name: 'birth'},
            ],
            rowGroup: {
                dataSrc:  'mailrecovery'
            },

            ordering: true,
            order: [[ 2, "asc" ], [ 0, "asc" ]],
        });
    });

</script>
@endsection


