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
                    <h4 class="card-title">Gen ICCID</h4>
                    <b>Use: </b>
                        <p class="card-title-desc">Ramdom country: <a target="_blank" href="{{URL::to('/iccid/show_iccid')}}">{{URL::to('/iccid/show_iccid')}}</a></p>
                        <p class="card-title-desc">Ramdom network: <a target="_blank" href="{{URL::to('/iccid/show_iccid?country=viet')}}">{{URL::to('/iccid/show_iccid?country=')}}</a><code class="highlighter-rouge">Việt Nam</code></p>
                        <p class="card-title-desc">Ramdom ICCID: <a target="_blank" href="{{URL::to('/iccid/show_iccid?iccid=898401')}}">{{URL::to('/iccid/show_iccid?iccid=')}}</a><code class="highlighter-rouge">89+ country code + mnc </code> (Max > 19 number,  <a target="_blank" href="https://www.mcc-mnc.com/">MNC ???</a>)</p>
                    <form>
                        <div class="form-group">
                            <label class="control-label">Country</label>
                            <select class="select2 form-select js_country" data-type="country">
                                <option>--Country--</option>
                                @foreach($country as $item)
                                    <option value="{{$item->country}}">{{$item->country.' - '.$item->countrycode}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Network</label>
                            <select class="select2 form-select js_network" id="network" name="network">
                                <option>--Network--</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>


        </div> <!-- end col -->
    </div> <!-- end row -->

    <div class="row" id="list_iccid">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">List ICCID</h4>
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
                        <ul class="list-group" id="iccid_gen">
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
        $('#list_iccid').hide();
        $(".select2").select2();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.js_country').change(function (event){
            event.preventDefault();
            let route = '{{route('iccid.getCountry')}}';
            let $this = $(this);
            let type = $this.attr('data-type');
            let country = $this.val();
            $('#list_iccid').hide();
            $.ajax({
                method : "GET",
                url:route,
                data:{
                    type: type,
                    country:country
                }
            })
            .done(function (msg){
                if(msg.data){
                    let html = '';
                    let element = '';
                    if(type = 'country'){
                        html = '<option>--Network--</option>';
                        element = '#network';
                    }
                    $.each(msg.data,function (index,value){
                        html += "<option value='"+value.countrycode+value.mnc+"'>"+value.network+' - '+value.mnc+"</option>"
                    })
                    $(element).html('').append(html);
                }
            })
        });

        $('.js_network').change(function (event){
            event.preventDefault();
            let route = '{{route('iccid.gen_iccid')}}';
            $('#list_iccid').show();
            let network = $('#network').val();
            let show = $('#show').val();
            $.ajax({
                method : "GET",
                url:route,
                data:{
                    iccid:network,
                    show:show
                }
            })
            .done(function (data){
                if(data.data){
                    let html = '';
                    $.each(data.data,function (index,value){
                        console.log(value);
                        html += "<li class='list-group-item'>"+value+"</li>"
                        // html += "<input type='text' class='form-control' id='copy-to-clipboard-input' value="+value+" />"
                    })
                    $('#iccid_gen').html('').append(html);
                }
                if(data.error){
                    let html = "<div class='alert alert-danger' role='alert'><div class='alert-body'>"+data.error + "</div></div>";
                    $('#iccid_gen').html('').append(html);
                }
            })
        });

        $('.js_show').change(function (event){
            event.preventDefault();
            let route = '{{route('iccid.gen_iccid')}}';
            $('#list_iccid').show();
            let network = $('#network').val();
            let show = $('#show').val();
            $.ajax({
                method : "GET",
                url:route,
                data:{
                    iccid:network,
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
                    $('#iccid_gen').html('').append(html);
                }
                if(data.error){
                    let html = "<div class='alert alert-danger' role='alert'><div class='alert-body'>"+data.error + "</div></div>";
                    $('#iccid_gen').html('').append(html);
                }
            })
        });

    </script>
@endsection

