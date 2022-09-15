@extends('layouts.master')

@section('title') @lang('translation.Responsive_Table') @endsection

@section('css')
    <!-- datatables css -->
    <link href="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/libs/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/libs/toastr/ext-component-toastr.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')

    <!-- start page title -->
{{--    <div class="row align-items-center">--}}
{{--        <div class="col-sm-6">--}}
{{--            <div class="page-title-box">--}}
{{--                <h4 class="font-size-18">Project</h4>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--    </div>--}}
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-rep-plugin" >
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
{{--                            <table id="projectTable" class="table table-striped" style="width: 100%">--}}
                                <table id="projectTable" class="table table-striped table-bordered dt-responsive data-table"
                                       style="width: 100%;">
                                <thead>
                                <tr>
                                    <th style="width: 10%">Logo</th>
                                    <th style="width: 20%">Mã Project</th>
                                    <th style="width: 30%">Package</th>
                                    <th style="width: 30%">Trạng thái Ứng dụng | Policy</th>
                                    <th>Action</th>
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
            var table = $('#projectTable').DataTable({
                displayLength: 50,
                lengthMenu: [5, 10, 25, 50, 75, 100],
                // orderCellsTop: true,
                // fixedHeader: true,
                processing: true,
                serverSide: true,

                ajax: {
                    url: "{{ route('project.getIndex')}}?"+hash,
                    type: "post"
                },
                columns: [
                    {data: 'logo', name: 'logo',orderable: false},
                    {data: 'projectname', name: 'projectname'},
                    {data: 'markets', name: 'markets'},
                    {data: 'status', name: 'status'},
                    // {data: 'Chplay_package', name: 'Chplay_package'},
                    // {data: 'status', name: 'status',orderable: false},
                    {data: 'action', name: 'action',className: "text-center", orderable: false, searchable: false},
                ],
                // dom:
                //     '<"d-flex justify-content-between mx-2 row mt-75"' +
                //     // '<" col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start" l>' +
                //     '<"button-items"B>'+
                //     '<"col-sm-12 col-lg-4 ps-xl-75 ps-0"<" d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1"f>>>' +
                //     '>t' +
                //     '<"d-flex justify-content-between mx-2 row mb-1"' +
                //     '<"col-sm-12 col-md-3"l>' +
                //     '<"col-sm-12 col-md-3"i>' +
                //     '<"col-sm-12 col-md-6"p>' +
                //     '>',

                // buttons: [
                //     {
                //         text: 'Add New',
                //         className: 'btn-success',
                //         attr: {
                //             'id' : 'createNewProject',
                //         },
                //         init: function (api, node, config) {
                //             $(node).removeClass('btn-secondary');
                //         }
                //     },
                //     {
                //         text: 'Buil and Check',
                //         className: 'btn btn-success ',
                //         attr: {
                //             'id' : 'buildandcheck',
                //         },
                //         init: function (api, node, config) {
                //             $(node).removeClass('btn-secondary');
                //         }
                //     },
                //     {
                //         text: 'Status',
                //         className: 'btn btn-success',
                //         attr: {
                //             'id' : 'dev_status',
                //         },
                //         init: function (api, node, config) {
                //             $(node).removeClass('btn-secondary');
                //         }
                //     },
                //     {
                //         text: 'Keystore',
                //         className: 'btn btn-success',
                //         attr: {
                //             'id' : 'change_keystore',
                //         },
                //         init: function (api, node, config) {
                //             $(node).removeClass('btn-secondary');
                //         }
                //     },
                //     {
                //         text: 'SDK',
                //         className: 'btn btn-success',
                //         attr: {
                //             'id' : 'change_sdk',
                //         },
                //         init: function (api, node, config) {
                //             $(node).removeClass('btn-secondary');
                //         }
                //     }
                // ],

                // deferRender:    true,
                // scrollY:       '78vh',
                // scroller: true,
                // scrollCollapse: true,
                // order: [[ 2, 'desc' ]]
            });
        });
    </script>

@endsection
