@extends('layouts.master')

@section('css')

<link href="plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<!-- Sweet-Alert  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

<!-- Select2 Js  -->
<link href="plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />

@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title">Tiến trình xử lý Project</h4>
</div>
<div class="modal fade bd-example-modal-xl" id="showMess" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeadingPolicy"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="message-full"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('content')

    <div class="row">
        <div class="col-12">

                <div class="card">
                    <div class="card-body">
                        <div class="button-items console_status_button">
                            <button type="button" class="btn btn-primary waves-effect waves-light" id="all">All</button>
                            <button type="button" class="btn btn-warning waves-effect" id="WaitProcessing" >Chờ xử lý</button>
                            <button type="button" class="btn btn-info waves-effect" id="Processing">Đang xử lý</button>
                            <button type="button" class="btn btn-success waves-effect waves-light" id="End">Kết thúc</button>
                            <button type="button" class="btn btn-danger waves-effect waves-light float-right" id="RemoveA">Submit</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered dt-responsive nowrap data-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                            <tr>
                                <th >ID</th>
                                <th >ID</th>
                                <th style="width:10%">Logo</th>
                                <th style="width:20%">Mã Project</th>
                                <th style="width:30%">Package</th>
                                <th style="width:30%">Message</th>
                                <th style="width:30%">Trạng thái Console</th>
                                <th style="width:10%">Action</th>
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


<!-- Datatable init js -->
<script src="assets/pages/datatables.init.js"></script>
<script src="plugins/select2/js/select2.min.js"></script>

<script>
    $("#template").select2({});
    $("#ma_da").select2({});
    $("#buildinfo_store_name_x").select2({});
    $('#RemoveA').hide();
</script>


<script type="text/javascript">
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('.data-table').DataTable({
            displayLength: 50,
            lengthMenu: [5, 10, 25, 50, 75, 100],
            // processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('project.getIndexBuild') }}",
                type: "post",
                data: function (d){
                    return $.extend({},d,{
                        "console_status": $('.console_status_button').val(),
                        "remove_status": $('#RemoveA').val(),
                    })
                }
            },
            columns: [
                {data: 'created_at', name: 'created_at',},
                {data: 'projectid', name: 'projectid'},
                {data: 'logo', name: 'logo',orderable: false},
                {data: 'projectname', name: 'projectname'},
                {data: 'package', name: 'package',orderable: false},
                {data: 'buildinfo_mess', name: 'buildinfo_mess',orderable: false},
                {data: 'buildinfo_console', render:renderStatus,  name: 'buildinfo_console',orderable: false},
                {data: 'action', name: 'action', className: "text-center", orderable: false, searchable: false},
            ],
            columnDefs: [
                {
                    "targets": [ 0,1 ],
                    "visible": false,
                    "searchable": false
                },
            ],

            order: [[ 0, 'desc' ]],
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                if (aData.buildinfo_console == 3) {
                    $('td', nRow).css('background-color', 'rgb(19 164 2 / 47%)');
                }
                if (aData.buildinfo_console == 6) {
                    $('td', nRow).css('background-color', '#38a4f84f');
                }
                if (aData.buildinfo_console == 7) {
                    $('td', nRow).css('background-color', 'rgb(255 0 0 / 46%)');
                }
                if (aData.buildinfo_console == 8) {
                    $('td', nRow).css('background-color', 'rgb(255 0 0 / 21%)');
                }
            },
        });
        function renderStatus(data, type, row) {
            if (data == 1) {
                return '<span class="badge badge-dark">Build App</span>';
            }
            if (data == 2) {
                return '<span class="badge badge-warning">Đang xử lý Build App</span>';
            }
            if (data == 3) {
                return '<span class="badge badge-info">Build App (Thành công)</span>';
            }
            if (data == 4) {
                return '<span class="badge badge-primary">Check Data Project</span>';
            }
            if (data == 5) {
                return '<span class="badge badge-secondary">Đang xử lý check dữ liệu của Project</span>';
            }
            if (data == 6) {
                return '<span class="badge badge-success">Kết thúc Check</span>';
            }
            if (data == 7) {
                return '<span class="badge badge-danger">Build App (Thất bại)</span>';
            }
            if (data == 8) {
                return '<span class="badge badge-danger">Kết thúc (Dự liệu thiếu) </span>';
            }
        }


        table.on('click', 'td:nth-child(4)', e=> {
            e.preventDefault();
            const row = table.row(e.target.closest('tr'));
            const rowData = row.data();

            $('#modelHeadingPolicy').html(rowData.name_projectname);
            $('#showMess').modal('show');
            $('.message-full').html(rowData.full_mess);

        });
        setInterval( function () {
            table.ajax.reload();
        }, 15000 );

        $('#all').on('click', function () {
            $('.console_status_button').val(null);
            $('#RemoveA').val('');
            $('#RemoveA').hide();
            table.draw();
        });
        $('#WaitProcessing').on('click', function () {
            $('.console_status_button').val('1%4');
            $('#RemoveA').val('');
            $('#RemoveA').hide();
            table.draw();
        });
        $('#Processing').on('click', function () {
            $('.console_status_button').val('2%5');
            $('#RemoveA').val('');
            $('#RemoveA').hide();
            table.draw();
        });
        $('#End').on('click', function () {
            $('.console_status_button').val('3%6%7%8');
            $('#RemoveA').val('');
            $('#RemoveA').show();
            table.draw();
        });
        $('#RemoveA').on('click', function () {
            $('#RemoveA').val('3%6%7%8');
            table.ajax.reload();
        });

        $(document).on('click','.removeProject', function (data){
            var project_id = $(this).data("id");
            $.ajax({
                type: "get",
                url: "{{ asset("project/removeProject") }}/" + project_id,
                success: function (data) {
                    table.draw();
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        });

    });

</script>



@endsection


