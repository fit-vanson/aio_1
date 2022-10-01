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


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-rep-plugin" >
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="manageTable" class="table table-striped table-bordered dt-responsive data-table"
                                   style="width: 100%;">
                                <thead>
                                <tr>
                                    <th style="width:10%">Logo</th>
                                    <th style="width:20%">Mã Project</th>
                                    <th style="width:5%">Install</th>
                                    <th style="width:5%">Review</th>
                                    <th style="width:5%">Vote</th>
                                    <th style="width:5%">Score</th>
                                    <th style="width:30%">Status app</th>
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
    <script src="{{ URL::asset('/assets/js/customs.js') }}"></script>


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
            var search = window.location.search;
            $.fn.dataTable.ext.errMode = 'none';
            var table = $('#manageTable').DataTable({
                displayLength: 50,
                lengthMenu: [5, 10, 25, 50, 75, 100],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('project.getManage')}}"+search,
                    type: "post",
                },
                columns: [
                    {data: 'logo', name: 'logo',orderable: false},
                    {data: 'projectname', name: 'projectname'},
                    {data: 'bot_installs', name: 'bot_installs'},
                    {data: 'bot_numberReviews', name: 'bot_numberReviews'},
                    {data: 'bot_numberVoters', name: 'bot_numberVoters'},
                    {data: 'bot_score', name: 'bot_score'},
                    {data: 'status_app', name: 'status_app',searchable: false, orderable: false},
                    {data: 'action', name: 'action',className: "text-center", orderable: false, searchable: false},
                ],
                initComplete: function (nRow,aData) {
                    this.api().columns([6]).every( function () {
                        var column = this;
                        var select = $('<select class="form-control"><option value="">Status</option></select>')
                            .appendTo( $(column.header()).empty() )
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                column
                                    .search( val ? val : '', true, false )
                                    .draw();
                            } );

                        $.each([0,1,2,3,4,5,6,7], function ( d, j ) {
                            var status ='';
                            switch (j){
                                case 0:
                                    status ='Mặc định' ;
                                    break;
                                case 1:
                                    status = 'Public';
                                    break;
                                case 2:
                                    status = 'Suppend';
                                    break;
                                case 3:
                                    status = 'UnPublish';
                                    break;
                                case 4:
                                    status = 'UnPublish';
                                    break;
                                case 5:
                                    status = 'Reject';
                                    break;
                                case 6:
                                    status = 'Check';
                                    break;
                                case 7:
                                    status = 'Pending';
                                    break;
                            }
                            select.append( '<option value="'+j+'">'+status+'</option>' )
                        } );
                    } );
                },
                fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {


                    switch (aData.buildinfo_console){
                        case 3:
                            $('td', nRow).css('background-color', 'rgb(19 164 2 / 47%)');
                            break;
                        case 6:
                            $('td', nRow).css('background-color', '#38a4f84f');
                            break;
                        case 7:
                            $('td', nRow).css('background-color', 'rgb(255 0 0 / 46%)');
                            break;
                        case 8:
                            $('td', nRow).css('background-color', 'rgb(255 0 0 / 21%)');
                            break;
                        // default:
                        //     $('td', nRow).css('background-color', 'rgb(255 0 0 / 21%)');
                        //     break;

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

            // table.on('click', 'td:nth-child(4)', e=> {
            //     e.preventDefault();
            //     const row = table.row(e.target.closest('tr'));
            //     const rowData = row.data();
            //
            //     $('#modelHeadingPolicy').html(rowData.name_projectname);
            //     $('#showMess').modal('show');
            //     $('.message-full').html(rowData.full_mess);
            //
            // });
            // setInterval( function () {
            //     table.ajax.reload();
            // }, 5000 );

            //
            // $('#all').on('click', function () {
            //     $('.Process_button').val(null);
            //     $('#RemoveA').val('');
            //     $('#RemoveA').hide();
            //     table.draw();
            // });
            // $('#WaitProcessing').on('click', function () {
            //     $('.Process_button').val('1%4');
            //     $('#RemoveA').val('');
            //     $('#RemoveA').hide();
            //     table.draw();
            // });
            // $('#Processing').on('click', function () {
            //     $('.Process_button').val('2%5');
            //     $('#RemoveA').val('');
            //     $('#RemoveA').hide();
            //     table.draw();
            // });
            // $('#End').on('click', function () {
            //     $('.Process_button').val('3%6%7%8');
            //     $('#RemoveA').val('');
            //     $('#RemoveA').show();
            //     table.draw();
            // });
            // $('#RemoveA').on('click', function () {
            //     $('#RemoveA').val('3%6%7%8');
            //     table.ajax.reload();
            // });


            {{--$(document).on('click','.removeProject', function (data){--}}
            {{--    var project_id = $(this).data("id");--}}
            {{--    $.ajax({--}}
            {{--        type: "post",--}}
            {{--        url: "{{ route('project.updateConsole')}}?buildinfo_console=0&projectID="+project_id,--}}
            {{--        success: function (data) {--}}
            {{--            table.draw();--}}
            {{--        },--}}
            {{--        error: function (data) {--}}
            {{--            console.log('Error:', data);--}}
            {{--        }--}}
            {{--    });--}}
            {{--});--}}
        });
    </script>

@endsection
