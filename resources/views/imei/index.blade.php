@extends('layouts.master')

@section('title') @lang('translation.Form_Advanced') @endsection

@section('css')
    <!-- Plugin css -->
    <link href="assets/libs/select2/select2.min.css" rel="stylesheet" type="text/css" />

@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Gen Imei</h4>
                    <b>Use: </b>
                        <p class="card-title-desc">Ramdom brand: <a target="_blank" href="{{URL::to('/imei/show_imei')}}">{{URL::to('/imei/show_imei')}}</a></p>
                        <p class="card-title-desc">Ramdom model: <a target="_blank" href="{{URL::to('/imei/show_imei?brand=samsung')}}">{{URL::to('/imei/show_imei?brand=')}}</a><code class="highlighter-rouge">samsung</code></p>
                        <p class="card-title-desc">Ramdom imei by TAC : <a target="_blank" href="{{URL::to('/imei/show_imei?tac_code=12345678')}}">{{URL::to('/imei/show_imei?tac_code=')}}</a><code class="highlighter-rouge">12345678</code> (Min > 8 number,  <a target="_blank" href="https://en.wikipedia.org/wiki/Type_Allocation_Code">tac_code ???</a>)</p>
                    <form>
                        <div class="form-group">
                            <label class="control-label">Hãng</label>
                            <select class="select2 form-select js_brand" data-type="brand">
                                <option>--Chọn Hãng--</option>
                                @foreach($brand as $item)
                                    <option value="{{$item->brand}}">{{$item->brand}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Model</label>
                            <select class="select2 form-select js_model" id="model" name="model">
                                <option>--Chọn Model--</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>


        </div> <!-- end col -->
    </div> <!-- end row -->

    <div class="row" id="list_imei">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">List Imei</h4>
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_length" id="datatable_length">
                                <label>Show
                                    <select class="form-select js_show" id="show">
                                        <option value="1">1</option>
                                        <option value="3">3</option>
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                    </select> entries</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-body d-flex justify-content-center">
                        <ul class="list-group" id="imei_gen">
                        </ul>
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
    <!-- end page title -->
@endsection

@section('script')
    <!-- Summernote js -->
    <script src="assets/libs/select2/select2.min.js"></script>

    <!-- demo js -->
{{--    <script src="assets/js/pages/form-advanced.init.js"></script>--}}

    <script>
        $('#list_imei').hide();
        $(".select2").select2();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.js_brand').change(function (event){
            event.preventDefault();
            let route = '{{route('imei.getBrand')}}';
            let $this = $(this);
            let type = $this.attr('data-type');
            let brand = $this.val();
            $('#list_imei').hide();
            $.ajax({
                method : "GET",
                url:route,
                data:{
                    type: type,
                    brand:brand
                }
            })
                .done(function (msg){
                    if(msg.data){
                        let html = '';
                        let element = '';
                        if(type = 'brand'){
                            html = '<option>--Chọn Model--</option>';
                            element = '#model';
                        }
                        $.each(msg.data,function (index,value){
                            html += "<option value='"+value.tac_code+"'>"+value.model+"</option>"
                        })
                        $(element).html('').append(html);
                    }
                })
        });

        $('.js_model').change(function (event){
            event.preventDefault();
            let route = '{{route('imei.gen_imei')}}';
            $('#list_imei').show();
            let model = $('#model').val();
            let show = $('#show').val();
            $.ajax({
                method : "GET",
                url:route,
                data:{
                    tac_code:model,
                    show:show
                }
            })
            .done(function (data){
                if(data.data){
                    let html = '';
                    $.each(data.data,function (index,value){
                        html += "<li class='list-group-item'>"+value+"</li>"
                        // html += "<input type='text' class='form-control' id='copy-to-clipboard-input' value="+value+" />"
                    })
                    $('#imei_gen').html('').append(html);
                }
                if(data.error){
                    let html = "<div class='alert alert-danger' role='alert'><div class='alert-body'>"+data.error + "</div></div>";
                    $('#imei_gen').html('').append(html);
                }
            })
        });

        $('.js_show').change(function (event){
            event.preventDefault();
            let route = '{{route('imei.gen_imei')}}';
            $('#list_imei').show();
            let model = $('#model').val();
            let show = $('#show').val();
            $.ajax({
                method : "GET",
                url:route,
                data:{
                    tac_code:model,
                    show:show
                }
            })
            .done(function (data){
                console.log(data)
                if(data.data){
                    let html = '';
                    $.each(data.data,function (index,value){
                        html += "<li class='list-group-item'>"+value+"</li>"
                        // html += "<input type='text' class='form-control' id='copy-to-clipboard-input' value="+value+" />"
                    })
                    $('#imei_gen').html('').append(html);
                }
                if(data.error){
                    let html = "<div class='alert alert-danger' role='alert'><div class='alert-body'>"+data.error + "</div></div>";
                    $('#imei_gen').html('').append(html);
                }
            })
        });

    </script>
@endsection

