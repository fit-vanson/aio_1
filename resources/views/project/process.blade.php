@extends('layouts.master')

@section('title') @lang('translation.Responsive_Table') @endsection

@section('css')
    <!-- datatables css -->
    <link href="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/libs/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/libs/toastr/ext-component-toastr.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css"/>

    {{--    <link href="plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />--}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
    @include('modals.project')
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


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-rep-plugin" >
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="processTable" class="table table-striped table-bordered dt-responsive data-table"
                                   style="width: 100%;">
                                <thead>
                                <tr>
                                    <th style="width:10%">Logo</th>
                                    <th style="width:20%">Mã Project</th>
                                    <th style="width:20%">Package</th>
                                    <th style="width:20%">Message</th>
                                    <th style="width:20%">Trạng thái Console</th>
                                    <th style="width:10%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

@endsection

@section('script')
    <!-- Plugins js -->
    <script src="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>

    <script src="{{ URL::asset('/assets/libs/toastr/toastr.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/table.init.js') }}"></script>

    {{--    <script src="plugins/select2/js/select2.min.js"></script>--}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(function () {
            $('.table-responsive').responsiveTable({
                // addDisplayAllBtn: 'btn btn-secondary'
            });


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var url = window.location.href;
            var hash = url.substring(url.indexOf('?')+1);
            $.fn.dataTable.ext.errMode = 'none';
            var table = $('#processTable').DataTable({
                displayLength: 5,
                lengthMenu: [5, 10, 25, 50, 75, 100],

                processing: true,
                serverSide: true,

                ajax: {
                    {{--url: "{{ route('project.getIndex')}}?"+hash,--}}
                    url: "{{ route('project.getProcess')}}",
                    type: "post",
                    data: function (d){
                        return $.extend({},d,{
                            "console_status": $('.Process_button').val(),
                            "remove_status": $('#RemoveA').val(),
                        })
                    }
                },
                columns: [

                    {data: 'logo', name: 'logo',orderable: false},
                    {data: 'projectname', name: 'projectname'},
                    {data: 'markets', name: 'markets'},
                    {data: 'buildinfo_mess', name: 'buildinfo_mess',orderable: false},
                    {data: 'buildinfo_console', render:renderStatus,  name: 'buildinfo_console',orderable: false},
                    {data: 'action', name: 'action',className: "text-center", orderable: false, searchable: false},
                ],
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
                order: [[ 0, 'desc' ]],


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
            }, 5000 );


            $('#all').on('click', function () {
                $('.Process_button').val(null);
                $('#RemoveA').val('');
                $('#RemoveA').hide();
                table.draw();
            });
            $('#WaitProcessing').on('click', function () {
                $('.Process_button').val('1%4');
                $('#RemoveA').val('');
                $('#RemoveA').hide();
                table.draw();
            });
            $('#Processing').on('click', function () {
                $('.Process_button').val('2%5');
                $('#RemoveA').val('');
                $('#RemoveA').hide();
                table.draw();
            });
            $('#End').on('click', function () {
                $('.Process_button').val('3%6%7%8');
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
                    type: "post",
                    url: "{{ route('project.updateConsole')}}?buildinfo_console=0&projectID="+project_id,
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
