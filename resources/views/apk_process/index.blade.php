@extends('layouts.master')

@section('css')

    <link href="plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <style>
        .tooltip-inner {
            max-width: 1000px !important;
            text-align:left;
        }
    </style>



    <!-- Sweet-Alert  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>


    <!-- Select2 Js  -->
    <link href="plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title">Apk Process | {{$categories->name}}</h4>
    </div>
    <div class="col-sm-6">
        <div class="float">
{{--            <a class="btn btn-success" href="javascript:void(0)" id="createNewProfile"> Create New</a>--}}

        </div>
    </div>
    @include('modals.profile')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-body scrolling-pagination ">
                    <table class="table table-bordered dt-responsive data-table apk-process" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            {{--                            <th>Logo</th>--}}
                            <th style="width: 10%">Logo</th>
                            <th style="width: 50%">Preview</th>
                            <th style="word-wrap: break-word;width: 30%">Description</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>


                        @if(isset($apk_process))
                            Page: {{$apk_process->currentPage()}}
                            @foreach($apk_process as $item)
                                <tr role="row" class="odd">
                                    <td tabindex="0">
                                        <button type="button" class="btn waves-effect button" data-original-title="{{ $item->id }}" >{{$item->id}} </button>
                                        <a href="{{$item->download}}" target="_blank" ><img class="rounded mx-auto d-block" height="100px" src="{{$item->icon}}"></a>
                                        <br>
                                        <p class="text-muted" style="line-height:0.5; text-align: center">{{\Carbon\Carbon::parse($item->upptime)->format('Y-m-d')}}</p>
                                    </td>
                                    <td>
                                        <p style="line-height:0.5;font-weight: bold">{{$item->title}}</p>
                                        <p class="text-muted" style="line-height:0.5">{{$item->package}}</p>
                                        <div class="">
                                            <?php
                                            $screenshots = explode(';',$item->screenshot);
                                            ?>
                                            @foreach ($screenshots as $sc)
                                                <img class="rounded mr-2 mo-mb-2" alt="200x200" style="height:100px  " src="{{$sc}}" data-holder-rendered="true">
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn waves-effect button" style="text-align: left" data-toggle="tooltip"  data-original-title="{{$item->description}}" data-placement="left" data-container="body">{!!  strlen($item->description) > 300 ? substr($item->description,0,300)." ..." : $item->description !!}  </button>
                                    </td>
                                    <td class=" text-center">
                                        <a href="javascript:void(0)" data-toggle="tooltip" data-id="{{$item->id}}" data-original-title="Delete" class="btn btn-danger deleteApk_process"><i class="ti-trash"></i></a>
                                        @if($item->pss_console == 0 )
                                            <a href="javascript:void(0)" data-toggle="tooltip" data-id="{{$item->id}}" class="btn btn-secondary actionApk_process">Mặc định</a>
                                        @elseif($item->pss_console == 1 )
                                            <span class="btn btn-info">Xử lý</span>
                                        @elseif($item->pss_console == 2 )
                                            <span class="btn btn-warning">Đang xử lý</span>';
                                        @elseif($item->pss_console == 3 )
                                            <?php
                                            $pss_aab = $item->pss_aab !=0  ? 'pss_aab: <span class="badge badge-success"><i class="ti-check"></i></span>' : 'pss_aab: <span class="badge badge-danger"><i class="ti-close"></i></span>';
                                            $pss_rebuild    = $item->pss_rebuild !=0  ? 'pss_rebuild: <span class="badge badge-success"><i class="ti-check"></i></span>' : 'pss_rebuild: <span class="badge badge-danger"><i class="ti-close"></i></span>';
                                            $pss_chplay     = $item->pss_chplay? 'pss chplay: '.$item->pss_chplay : '';
                                            $pss_huawei    = $item->pss_huawei ? 'pss huawei: '.$item->pss_huawei : '';
                                            $pss_sdk    = $item->pss_sdk ? 'pss sdk: '.$item->pss_sdk : '';
                                            $result = '<br>';
                                            if(isset($item->pss_ads)){
                                                $pss_ads = json_decode($item->pss_ads,true);
                                                foreach ($pss_ads as $key=>$ads){
                                                    $result .= $ads != 0 ? $key.': <span class="badge badge-success"><i class="ti-check"></i></span><br>'  : $key.': <span class="badge badge-danger"><i class="ti-close"></i></span><br>';
                                                }
                                            }
                                            $out =
                                                '<p>'.$pss_aab.' </p>'.
                                                '<p>'.$pss_rebuild.' </p>'.
                                                '<p>'.$pss_chplay.' </p>'.
                                                '<p>'.$pss_huawei.' </p>'.
                                                '<p>'.$pss_sdk.' </p>'.
                                                '<p> Ads: '.$result.' </p>'
                                            ;
                                            ?>
                                            <button type="button" class="btn btn-success waves-effect waves-light" style="text-align: left" data-toggle="tooltip" data-html="true" title="<div class='text-justify'>{{$out }}</div>" data-placement="left" data-container="body">Xong  </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif


                        </tbody>
                    </table>

                    {{ $apk_process->appends(request()->query())->links() }}

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

{{--    <script type="text/javascript" src="https://cdn.datatables.net/scroller/2.0.6/js/dataTables.scroller.min.js"></script>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>


    <script src="plugins/select2/js/select2.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $("body").tooltip({ selector: '[data-toggle=tooltip]' });
            $("body").tooltip({ selector: '[data-toggle=popover]' });


            var param = window.location.search;
            {{--var table = $('.apk-process').DataTable({--}}


            {{--    processing: true,--}}
            {{--    serverSide: true,--}}
            {{--    ajax: {--}}
            {{--        url: "{{ route('apk_process.getIndex') }}"+param,--}}
            {{--        type: "post"--}}
            {{--    },--}}
            {{--    columns: [--}}
            {{--        {data: 'icon'},--}}
            {{--        {data: 'screenshot'},--}}
            {{--        {data: 'description'},--}}
            {{--        {data: 'action'},--}}

            {{--    ],--}}
            {{--    --}}{{--columnDefs: [--}}

            {{--        --}}{{--    {--}}
            {{--        --}}{{--        targets: 0,--}}
            {{--        --}}{{--        orderable: false,--}}
            {{--        --}}{{--        responsivePriority: 0,--}}
            {{--        --}}{{--        render: function (data, type, full, meta) {--}}
            {{--        --}}{{--            var $output ='<img src="{{asset('uploads/profile/logo')}}/'+data+'" alt="logo" height="100px">';--}}
            {{--        --}}{{--            return $output;--}}
            {{--        --}}{{--        }--}}
            {{--        --}}{{--    },--}}
            {{--        --}}{{--],--}}
            {{--    order:[0,'asc']--}}
            {{--});--}}



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
                var btn = $(this).parent();
                var url = window.location.href;

                $.ajax({
                    type: "get",
                    url: "/apk_process/update_pss/" + id,
                    success: function (data) {
                        if(data.success){
                           var  html = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'+id+'" data-original-title="Delete" class="btn btn-danger deleteApk_process"><i class="ti-trash"></i></a> <span class="btn btn-info">Xử lý</span>';
                            btn.html(html)
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
            $('ul.pagination').hide();
            $(function() {
                $('.scrolling-pagination').jscroll({
                    autoTrigger: true,
                    padding: 0,
                    nextSelector: '.pagination li.active + li a',
                    contentSelector: 'div.scrolling-pagination',
                    callback: function() {
                        $('ul.pagination').remove();
                    }
                });
            });
        });
    </script>
@endsection






