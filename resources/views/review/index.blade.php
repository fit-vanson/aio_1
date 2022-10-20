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
@endsection


@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="reviewTable" class="table table-editable table-striped table-bordered dt-responsive data-table" style="width: 100%">
                            <thead>
                            <tr>
                                <th style="width: 10%">Project ID</th>
                                <th style="width: 25%">User Comment</th>
                                <th style="width: 5%">Language</th>
                                <th style="width: 5%">Down Count</th>
                                <th style="width: 5%">Up Count</th>
                                <th style="width: 5%">Star Rating</th>
                                <th style="width: 10%">Last Modifie dUser</th>
                                <th style="width: 25%">Developer Comment</th>
                                <th style="width: 10%">Last Modified Developer</th>
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var groupColumn = 0;
            var reviewTable = $('#reviewTable').dataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('review.getIndex') }}",
                    type: "post"
                },
                columns: [
                    {data: 'project_id'},
                    {data: 'userComment'},
                    {data: 'reviewerLanguage'},
                    {data: 'thumbsDownCount'},
                    {data: 'thumbsUpCount'},
                    {data: 'starRating'},
                    {data: 'lastModifiedUser'},
                    {data: 'developerComment'},
                    {data: 'lastModifiedDeveloper'},
                ],

                drawCallback: function (settings) {
                    $.fn.editable.defaults.mode = 'inline';
                    $('.editable').editable({
                        success:function(data,newValue){
                            var _id = $(this).data('pk')
                            $.ajax({
                                url: "{{ asset("review/get_postReview") }}?id=" + _id+'&replyText='+newValue,
                                responseTime: 400,
                                success: function (result) {
                                    if(result.success){
                                        $.notify(data.success, "success");
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

        })





        // function get_editable() {
        //     $.fn.editable.defaults.mode = 'inline';
        //     $('.editable').editable({
        //         success:function(data){
        //             console.log(data);
        //         } ,
        //     });
        // }
    </script>




@endsection
