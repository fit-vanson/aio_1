@extends('layouts.master')

@section('css')

    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" />
    <link href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" />
    <link href="https://cdn.datatables.net/scroller/2.0.6/css/scroller.bootstrap4.min.css" />


    <link href="plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <style>
        .tooltip-inner {
            max-width: 1000px !important;
            text-align:left;
        }
        div.dataTables_wrapper div.dataTables_scrollBody {
            min-height: 800px;
        }

    </style>


    /*<link rel="stylesheet" type="text
    /css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/af-2.4.0/b-2.2.3/cr-1.5.6/fc-4.1.0/fh-3.2.3/kt-2.7.0/r-2.3.0/sc-2.0.6/datatables.min.css"/>*/

    <!-- Sweet-Alert  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    <!-- Select2 Js  -->
    <link href="plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title">Apk Process</h4>
    </div>
    @include('modals.profile')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body scrolling-pagination ">
                    <table class="table table-bordered dt-responsive data-table apk-process-success" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            {{--                            <th>Logo</th>--}}
                            <th style="width: 10%">Logo</th>
                            <th style="width: 40px">Thông tin</th>
                            <th style="width: 5px">Admo</th>
                            <th style="width: 5px">Face</th>
                            <th style="width: 5px">StaA</th>
                            <th style="width: 5px">HuW</th>
                            <th style="width: 5px">Iron</th>
                            <th style="width: 5px">Alvo</th>
                            <th style="width: 5px">Abra</th>
                            <th style="width: 5px">Unit</th>
                            <th style="width: 5px">Rebu</th>
                            <th style="width: 5px">Aab</th>
                            <th style="width: 5px">Lauch</th>
                            <th >Ads Str</th>
                            <th style="width: 8%" ></th>
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

    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/scroller/2.0.6/js/dataTables.scroller.min.js"></script>


    <!-- Required datatable js -->
{{--    <script src="plugins/datatables/jquery.dataTables.min.js"></script>--}}
{{--    <script src="plugins/datatables/dataTables.bootstrap4.min.js"></script>--}}
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

{{--    <!-- Datatable init js -->--}}
{{--    <script src="assets/pages/datatables.init.js"></script>--}}


{{--    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>--}}


{{--    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/af-2.4.0/b-2.2.3/cr-1.5.6/fc-4.1.0/fh-3.2.3/kt-2.7.0/r-2.3.0/sc-2.0.6/datatables.min.js"></script>--}}

    <script type="text/javascript">
        $(document).ready(function() {
            $(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('.apk-process-success').DataTable({
                orderCellsTop: true,
                fixedHeader: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('apk_process.getIndex') }}",
                    type: "post"
                },
                columns: [
                    {data: 'icon'},
                    {data: 'title'},
                    {data: 'pss_ads->Admob',"className": "text-center",},
                    {data: 'pss_ads->Facebook',"className": "text-center"},
                    {data: 'pss_ads->StartApp',"className": "text-center"},
                    {data: 'pss_ads->Huawei',"className": "text-center"},
                    {data: 'pss_ads->Iron',"className": "text-center"},
                    {data: 'pss_ads->Applovin',"className": "text-center"},
                    {data: 'pss_ads->Appbrain',"className": "text-center"},
                    {data: 'pss_ads->Unity3d',"className": "text-center"},

                    {data: 'pss_rebuild',"className": "text-center"},
                    {data: 'pss_aab',"className": "text-center"},
                    {data: 'pss_lauch',"className": "text-center"},
                    {data: 'pss_ads_str',"className": "text-center"},
                    {data: 'action',"className": "text-center"},
                ],

                deferRender:    true,
                scrollY:        800,
                scroller: true,
                // scrollResize: true,

                // scrollX: true,
                scrollCollapse: true,
                order:[0,'asc']
            });

            $(document).on('click','.deleteApk_process', function (data){
                var url = window.location.href;
                var id = $(this).data("id");
                var parent = $(this).parent().parent();
                $.ajax({
                    type: "get",
                    url: '/apk_process/delete/' + id,
                    success: function (data) {
                        if(data.success){
                            parent.slideUp(300,function() {
                                parent.remove();
                            })
                        }
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            });


            $(document).on('click','.actionApk_process', function (data){
                    var id = $(this).data("id");
                    var btn = $(this).parent().parent();
                    console.log(btn)
                    $.ajax({
                        type: "get",
                        url: "/apk_process/update_pss/" + id,
                        success: function (data) {
                            console.log(data)
                            if(data.success){
                                btn.remove()
                                // var  html = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'+id+'" data-original-title="Delete" class="btn btn-danger deleteApk_process"><i class="ti-trash"></i></a> <span class="btn btn-info">Xử lý</span>';
                                // btn.html(html)
                            }
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                });

            $(document).on("click", ".button", function(){
                var copyText = $(this).data("original-title");
                var textarea = document.createElement('textarea');
                textarea.id = 'temp_element';
                textarea.style.height = 0;
                document.body.appendChild(textarea);
                textarea.value = copyText;
                var selector = document.querySelector('#temp_element')
                selector.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
            });

        });
        } );
    </script>
@endsection






