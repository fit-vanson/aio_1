@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('plugins/datatables/autoFill.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('plugins/datatables/keyTable.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- x-editable -->
    <link href="{{ URL::asset('plugins/x-editable/css/bootstrap-editable.css') }}" rel="stylesheet" type="text/css" />


    <style>
        .popover{
            min-width: 50em !important;

        }
    </style>

@endsection


@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="browser_profilesTable" class="table table-editable table-striped table-bordered dt-responsive data-table" style="width: 100%">
                            <thead>
                            <tr>
                                <th style="width: 10%">ID</th>
                                <th style="width: 20%">profile_name_x</th>
                                <th style="width: 10%">uuid</th>
                                <th style="width: 5%">email</th>
                                <th style="width: 5%">ipvpn</th>
                                <th style="width: 5%">open</th>
                                <th style="width: 5%">pcname</th>
                                <th style="width: 10%">time_open</th>
                                <th style="width: 20%">note</th>
                                <th style="width: 10%">action</th>
                            </tr>
                            </thead>
                        </table>



                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

@section('script')
    <!-- Required datatable js -->
    <script src="{{ URL::asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/datatables/dataTables.autoFill.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/datatables/autoFill.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/datatables/dataTables.keyTable.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ URL::asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/x-editable/js/bootstrap-editable.min.js') }}"></script>
{{--    <script src="{{ URL::asset('assets/pages/table-editable.int.js') }}"></script>--}}





    <script type="text/javascript">
        $(function () {

            $('[data-toggle="popover"]').popover({
                html: true
            })

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var browser_profilesTable = $('#browser_profilesTable').dataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('browser_profiles.getIndex') }}",
                    type: "post"
                },
                columns: [
                    {data: 'id'},
                    {data: 'profile_name_x'},
                    {data: 'uuid'},
                    {data: 'email'},
                    {data: 'ipvpn'},
                    {data: 'open'},
                    {data: 'pcname'},
                    {data: 'time_open'},
                    {data: 'note'},
                    {data: 'action'},
                ],

                drawCallback: function (settings) {
                    $.fn.editable.defaults.mode = 'inline';

                    $('.editable').editable({
                        success:function(data,newValue){
                            var _id = $(this).data('pk')
                            var _action = $(this).data('action')
                            $.ajax({
                                url: "{{ asset("browser_profiles/update") }}/" + _id+'?action='+_action+'&value='+newValue,
                                responseTime: 400,
                                success: function (result) {
                                    if(result.success){
                                        $.notify(result.success, "success");
                                    }
                                    if(result.error){
                                        $.notify(result.error['message'], "error");
                                    }
                                }
                            });
                        } ,
                    });
                },
            });


            $(document).on('click','.deleteProject', function (data){
                var project_id = $(this).data("id");
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#02a499",
                    cancelButtonColor: "#ec4561",
                    confirmButtonText: "Yes, delete it!"
                }).then(function (result) {
                    if (result.value) {
                        $.ajax({
                            type: "get",
                            url: "{{ asset("project/delete") }}/" + project_id,
                            success: function (data) {
                                table.draw();
                                $.notify(data.success , "success");
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                    }
                });
            });


            $(document).on('click','.download', function (data){
                var _id = $(this).data("id");


                $.ajax({
                    type: "get",
                    url: "{{ asset("browser_profiles/download") }}/" + _id,
                    success: function (data) {

                        $.notify(data.success , "success");
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });

            });
        })


    </script>




@endsection
