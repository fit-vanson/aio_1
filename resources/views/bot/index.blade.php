@extends('layouts.master')

@section('css')

    <!-- Sweet-Alert  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>


    <!-- Select2 Js  -->
    <link href="plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title">Quản lý BOT</h4>
    </div>
    <div class="col-sm-6">
        <div class="float-right">
            <a class="btn btn-danger" href="javascript:void(0)" id="truncateBot"> Truncate </a>
        </div>
    </div>
    {{--    @include('modals.bot')--}}
@endsection

@section('content')

    <div id="content"></div>

{{--    <div class="infinite-scroll">--}}
        <div class="row" id="load_ajax">
{{--            @foreach($bot as $item)--}}

{{--                <div class="col-lg-3 " id="botmess_{{$item->id}}" data-id="{{$item->id}}" >--}}
{{--                    <div class="card">--}}
{{--                        <h4 class="card-header mt-0" style="background-color: {!! $color !!}">{{$item->id}}</h4>--}}
{{--                        <div class="card-body">--}}
{{--                            <blockquote class="card-blockquote mb-0">--}}
{{--                                <p>Type: {{$item->type}}</p>--}}
{{--                                <p>Console: {{$item->console}}</p>--}}
{{--                                @foreach($logs as $log )--}}
{{--                                    <footer class="blockquote-footer font-14">--}}
{{--                                        {{date('m/d/Y H:i:s', $log['time'] )}} <cite title="Source Title" style="color:{!! $color !!}"> {{$log['mess']}}</cite>--}}
{{--                                    </footer>--}}
{{--                                @endforeach--}}
{{--                            </blockquote>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            @endforeach--}}
{{--                {{ $bot->links() }}--}}

        </div>
{{--    </div>--}}
    <!-- end row -->




@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jscroll/2.4.1/jquery.jscroll.min.js"></script>

    <script>

        $(document).ready(function() {


            function loadlink(){
                $.ajax({
                    type: "get",
                    url: "{{route('bot.load_ajax')}}",
                    success: function(result) {
                        let html = '' ;
                        let today = new Date();

                        result.data.forEach(function(data, index) {
                            let logs = '';
                            data.messlog.forEach(function(dataMess, indexMess) {
                                let date = new Date(dataMess.time*1000).toLocaleString();

                                logs += '<footer class="blockquote-footer font-14">'+
                                    date+':<cite title="Source Title" style="color: '+data.color+'" > '+dataMess.mess + '</cite>'
                                    '</footer>';
                            })
                            html += '<div class="col-lg-3 " >'+
                                        '<div class="card">'+
                                            '<h4 class="card-header mt-0" style="background-color: '+data.color+'">'+data.botname+'<cite class="font-14 float-right" style="color:white">'+today.toLocaleString()+'</cite></h4>'+
                                        '<div class="card-body">'+
                                            '<blockquote class="card-blockquote mb-0">'+
                                                '<p>Type: '+data.type+'</p>'+
                                            '<p>Console: '+data.console+'</p>'+logs+
                                            '</blockquote>'+
                                        '</div>'+
                                    '</div>'+
                                    '</div>';
                        });

                        $("#load_ajax").html(html)
                    },
                });
            }

            loadlink(); // This will run on page load
            setInterval(function(){
                loadlink() // this will run after every 5 seconds
            }, 5000);


            $(document).on('click','#truncateBot', function (data){
                swal({
                        title: "Bạn có chắc muốn xóa?",
                        text: "Your will not be able to recover this imaginary file!",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "Xác nhận xóa!",
                        closeOnConfirm: false
                    },
                    function(){
                        $.ajax({
                            type: "get",
                            url: "{{ route('bot.truncate')}}",
                            success: function (data) {
                                $.notify(data.success, "success");
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                        swal("Đã xóa!", "Your imaginary file has been deleted.", "success");
                    });
            });




        })
    </script>
@endsection






