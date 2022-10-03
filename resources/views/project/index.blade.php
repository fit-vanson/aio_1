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


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-rep-plugin" >
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="projectTable" class="table table-striped table-bordered dt-responsive data-table"
                                   style="width: 100%;">
                            <thead>
                            <tr>

                                <th style="display: none">ID</th>
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
    <script src="{{ URL::asset('/assets/js/customs.js') }}"></script>


{{--    <script src="plugins/select2/js/select2.min.js"></script>--}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(function () {
            $('.table-responsive').responsiveTable({
                // addDisplayAllBtn: 'btn btn-secondary'
            });
            $('.select2').select2();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#template').select2({
                // initialValue:true,
                placeholder: "Select a customer",
                minimumInputLength: 2,
                ajax: {
                    url: '{{route('api.getTemplate')}}',
                    dataType: 'json',
                    type: "GET",
                    // quietMillis: 50,
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: false
                },
                initSelection : function (element, callback) {

                    var data = [];
                    $(element.val()).each(function () {
                        data.push({id: this, text: this});
                    });
                    callback(data);
                }
            });

            $('#ma_da').select2({
                // initialValue:true,
                placeholder: "Select a customer",
                minimumInputLength: 2,
                ajax: {
                    url: '{{route('api.getDa')}}',
                    dataType: 'json',
                    type: "GET",
                    // quietMillis: 50,
                    data: function(params) {

                        return {
                            q: params.term, // search term
                            page: params.page
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            })
                        };
                    },
                    // cache: false
                },
                initSelection : function (element, callback) {
                    var data = [];
                    $(element.val()).each(function () {
                        data.push({id: this, text: this});
                    });
                    callback(data);
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
                    {{--url: "{{ route('project.getIndex')}}?"+hash,--}}
                    url: "{{ route('project.getIndex')}}",
                    type: "post"
                },
                columns: [

                    {data: 'projectid', name: 'projectid',orderable: false,visible: false},
                    {data: 'logo', name: 'logo',orderable: false},
                    {data: 'projectname', name: 'projectname'},
                    {data: 'markets', name: 'markets'},
                    {data: 'status', name: 'status'},
                    // {data: 'Chplay_package', name: 'Chplay_package'},
                    // {data: 'status', name: 'status',orderable: false},
                    {data: 'action', name: 'action',className: "text-center", orderable: false, searchable: false},
                ],
                order: [[ 0, 'desc' ]]
            });


            $('#createNewProject').click(function () {
                $('#saveBtn').val("create-project");
                $('#project_id').val('');
                $("#avatar").attr("src","img/logo.png");
                $('#modelHeading').html("Thêm mới Project");
                $('#ajaxModel').modal({});


                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');

                });

                $('#ajaxModel a').hide();
                $('#ajaxModel a:first').show();
                $('#ajaxModel a:first').tab('show');
                $('#package_ads').hide();


                $('#template').val('');
                $('#template').trigger('change.select2');
                $('#ma_da').val('');
                $('#ma_da').trigger('change.select2');
                $("#ma_da").select2('enable');
            });

            $('#projectForm').on('submit',function (event){
                event.preventDefault();
                var formData = new FormData($("#projectForm")[0]);
                if($('#saveBtn').val() == 'create-project'){
                    $.ajax({
                        // data: $('#projectForm2').serialize(),
                        data: formData,
                        url: "{{ route('project.create') }}",
                        type: "POST",
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    $("#projectForm").notify(
                                        data.errors[count],"error",
                                        { position:"right" }
                                    );
                                }
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#projectForm').trigger("reset");
                                $('#ajaxModel').modal('hide');
                                table.draw();
                            }
                        },
                    });
                }
                if($('#saveBtn').val() == 'edit-project'){
                    $.ajax({
                        // data: $('#projectForm2').serialize(),
                        data: formData,
                        url: "{{ route('project.update') }}",
                        type: "post",
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    $("#projectForm2").notify(
                                        data.errors[count],"error",
                                        { position:"right" }
                                    );
                                }
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#projectForm2').trigger("reset");
                                $('#ajaxModel').modal('hide');
                                table.draw();
                            }
                        },
                    });
                }
            });

            $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
                e.preventDefault();
                var ref = $(this).attr('href').replace('#', '');
                var project_id = $('#project_id').val();
                var market_id = $(this).data('market_id');
                var market_name = $(this).data('market_name');
                var tabContent = $('.tab-content');

                if (!$('#' + ref).length) {
                    // Execute the AJAX here to get the tab content
                    var html =
                        '<div class="tab-pane p-3" id="' + ref + '" role="tabpanel">'+
                            '<div  class="row">' +
                                '<div class="form-group col-lg-6">' +
                                    '<label for="name">Store Name ('+market_name+') </label>' +
                                        '<select class="form-control select2" id="'+market_id+'_dev_id"  name="market['+market_id+'][dev_id]"></select>' +
                                        // '<input class="form-control" id="'+market_name+'_dev_id"  name="market['+market_id+'][dev_id]"></input>' +
                                '</div>' +
                                '<div class="form-group col-lg-6">' +
                                    '<label for="name">Keystore Profile</label>' +
                                        '<select class="form-control select2" id="'+market_id+'_keystore" name="market['+market_id+'][keystore]"></select>' +
                                '</div>' +
                                '<div class="form-group col-lg-6">' +
                                    '<label for="name">Link App</label>' +
                                    '<input type="text" id="market_'+market_id+'_app_link" name="market['+market_id+'][app_link]" class="form-control" value="" >' +
                                '</div>'+
                                '<div class="form-group col-lg-6">' +
                                    '<label for="name">Link Policy </label>' +
                                    '<input type="text" id="market_'+market_id+'_policy_link" name="market['+market_id+'][policy_link]" class="form-control" >' +
                                '</div>'+
                                '<div class="form-group col-lg-6">' +
                                    '<label for="name">AppID</label>' +
                                    '<input type="text" id="market_'+market_id+'_app_id" name="market['+market_id+'][appID]" class="form-control" >' +
                                '</div>'+
                                    '<div class="form-group col-lg-6">' +
                                    '<label for="name">App Name X</label>' +
                                '<input type="text" id="market_'+market_id+'_app_name_x" name="market['+market_id+'][app_name_x]" class="form-control" >' +
                                '</div>'+
                                '<div class="form-group col-lg-6">' +
                                    '<label for="name">SDK</label>' +
                                    '<input type="text" id="market_'+market_id+'_sdk" name="market['+market_id+'][sdk]" class="form-control" >' +
                                '</div>'+
                                '<div class="form-group col-lg-6">' +
                                    '<label for="name">Link Video</label>' +
                                    '<input type="text" id="market_'+market_id+'_video_link" name="market['+market_id+'][video_link]" class="form-control" >' +
                                '</div>'+
                        '</div>';
                    tabContent.append(html);
                    getProjectMarket(project_id,market_id)
                    $('#'+market_id+'_dev_id').select2({
                        minimumInputLength: 2,
                        ajax: {
                            url: '{{route('api.getDev')}}',
                            dataType: 'json',
                            type: "GET",
                            // quietMillis: 50,
                            data: function(params) {

                                return {
                                    q: params.term, // search term
                                    dev_id: market_id,
                                    page: params.page
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function (item) {
                                        return {
                                            text: item.name + ' : ' + item.store,
                                            id: item.id
                                        }
                                    })
                                };
                            },
                            // cache: false
                        },
                    });
                    $('#'+market_id+'_keystore').select2({
                        minimumInputLength: 2,
                        ajax: {
                            url: '{{route('api.getKeystore')}}',
                            dataType: 'json',
                            type: "GET",
                            // quietMillis: 50,
                            data: function(params) {
                                return {
                                    q: params.term, // search term
                                    page: params.page
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function (item) {
                                        return {
                                            text: item.name,
                                            id: item.name
                                        }
                                    })
                                };
                            },

                        },
                    });
                }
                tabContent.find('.tab-pane').hide();
                tabContent.find('#' + ref).show();
            })

            $(document).on('change', '.choose_da', function () {
                var _text = $(this).select2('data')[0].text;
                $('#projectname').val(_text+'-');
                $('#project_da_name').val(_text);
            })

            $(document).on('change', '#buildinfo_vernum', function () {
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();
                var num = $(this).val()
                $('#buildinfo_verstr').val(num + '.'+dd+mm+'.'+yyyy)
            })

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

            $(document).on('click','.editProject', function (data){
                var project_id = $(this).data("id");
                $.get('{{asset('project/edit')}}/'+project_id,function (data) {

                    $('#modelHeading').html("Edit Project " + data.projectname);
                    $('#saveBtn').val("edit-project");
                    $('#ajaxModel').modal('show');
                    $('.modal').on('hidden.bs.modal', function (e) {
                        $('body').addClass('modal-open');
                    });
                    $('#ajaxModel a:first').tab('show');
                    $('#package_ads').show();

                    $("#ma_da").select2("trigger", "select", {
                        data: { id: data.ma_da,text: data.da.ma_da }
                    });
                    $("#ma_da").select2('enable', false);

                    $("#template").select2("trigger", "select", {
                        data: {
                            id: data.template,
                            text: data.ma_template.template,
                            project: data.projectid,
                        }
                    });


                    $('#project_id').val(data.projectid)
                    $('#projectname').val(data.projectname)
                    $('#title_app').val(data.title_app)
                    $('#buildinfo_vernum').val(data.buildinfo_vernum)
                    $('#buildinfo_verstr').val(data.buildinfo_verstr)
                    $('#buildinfo_link_fanpage').val(data.buildinfo_link_fanpage)
                    $('#buildinfo_api_key_x').val(data.buildinfo_api_key_x)
                    $('#buildinfo_link_website').val(data.buildinfo_link_website)

                    if(data.logo) {
                        $("#avatar").attr("src","../storage/projects/"+data.da.ma_da+"/"+data.projectname+"/lg114.png");
                    }else {
                        $("#avatar").attr("src","img/logo.png");
                    }
                    if(data.data_onoff == 1){
                        $("#data_online").prop('checked', true);
                    }else if (data.data_onoff == 2){
                        $("#data_offline").prop('checked', true);
                    }else if (data.data_onoff == 3){
                        $("#data_all").prop('checked', true);
                    }


                });
            });

            $('.choose_template').on('select2:selecting', function(e) {
                var project_id = '';
                var _id = e.params.args.data.id;
                if(e.params.args.data.project){
                    project_id = e.params.args.data.project;
                }else {
                    project_id = $('#project_id').val();
                }
                $('#package_ads').show();
                $.get('{{asset('template/edit')}}/'+_id,function (data) {
                    var nav_market = '<li class="nav-item " role="presentation">'+
                        '<a class="nav-link active" data-toggle="tab" href="#tab_home" role="tab" id="nav_link_home">'+
                        ' <span class="d-none d-sm-block">Home</span>'+
                        '</a></li>';
                    var package_ads ='';
                    $.each(data.markets, function (k,v){
                        getProjectMarket(project_id,v.id)
                        nav_market +=
                            '<li class="nav-item" role="presentation">'+
                            '<a class="nav-link " data-toggle="tab" data-market_id="'+v.id+'" data-market_name="'+v.market_name+'" href="#tab_'+v.market_name+'" role="tab" id="nav_'+v.market_name+'">'+
                            '<span class="d-none d-sm-block">'+v.market_name+'</span>'+
                            '</a>'+
                            '</li>';

                        package_ads +=
                            '<div id="package_'+v.market_name+'">'+
                                '<div class="form-group col-lg-12">'+
                                '<h4 class="mt-0 header-title">Package '+v.market_name+'</h4>'+
                                '<input type="text" id="market_'+v.id+'_package" name="market['+v.id+'][package]" class="form-control" value="" >'+
                                '</div>'+
                                '<div class="form-group col-lg-11" style="margin-left: auto;">'+
                                    '<div id="accordion_'+v.id+'">'+
                                        '<div class="card mb-0">'+
                                            '<div class="card-header" id="heading_'+v.id+'">'+
                                            '<a href="#collapse_'+v.id+'" class="text-dark collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="collapse_'+v.id+'">ADS '+v.market_name+'</a>'+
                                            '</div>'+
                                            '<div id="collapse_'+v.id+'" class="collapse" aria-labelledby="heading_'+v.id+'" data-parent="#accordion_'+v.id+'" style="">'+
                                                '<div class="card-body">' +
                                                    '<div class="divider">' +
                                                    '<div class="divider-text"><b>Admod</b></div>' +
                                                    '</div>'+

                                                    '<div class="row">' +
                                                        '<div class="form-group col-sm-4">' +
                                                            '<input type="text" id="market_'+v.id+'_ads_id" name="market['+v.id+'][ads][ads_id]" placeholder="ads_id"  class="form-control"/>' +
                                                        '</div>'+
                                                        '<div class="form-group col-sm-4">' +
                                                        '<input type="text" id="market_'+v.id+'_ads_banner" name="market['+v.id+'][ads][ads_banner]" placeholder="ads_banner"  class="form-control"/>' +
                                                        '</div>'+
                                                        '<div class="form-group col-sm-4">' +
                                                        '<input type="text" id="market_'+v.id+'_ads_inter" name="market['+v.id+'][ads][ads_inter]" placeholder="ads_inter"  class="form-control"/>' +
                                                        '</div>'+
                                                        '<div class="form-group col-sm-4">' +
                                                        '<input type="text" id="market_'+v.id+'_ads_reward" name="market['+v.id+'][ads][ads_reward]" placeholder="ads_reward"  class="form-control"/>' +
                                                        '</div>'+
                                                        '<div class="form-group col-sm-4">' +
                                                        '<input type="text" id="market_'+v.id+'_ads_native" name="market['+v.id+'][ads][ads_native]" placeholder="ads_native"  class="form-control"/>' +
                                                        '</div>'+
                                                        '<div class="form-group col-sm-4">' +
                                                        '<input type="text" id="market_'+v.id+'_ads_open" name="market['+v.id+'][ads][ads_open]" placeholder="ads_open"  class="form-control"/>' +
                                                        '</div>'+
                                                    '</div>'+
                                                    '<div class="divider">' +
                                                    '<div class="divider-text"><b>Start.io</b></div>' +
                                                    '</div>'+
                                                    '<div class="row">' +
                                                        '<div class="form-group col-sm-4">' +
                                                        '<input type="text" id="market_'+v.id+'_ads_start" name="market['+v.id+'][ads][ads_start]" placeholder="ads_start"  class="form-control"/>' +
                                                        '</div>'+
                                                    '</div>'+
                                                    '<div class="divider">' +
                                                    '<div class="divider-text"><b>Huawei</b></div>' +
                                                    '</div>'+
                                                    '<div class="row">' +
                                                        '<div class="form-group col-sm-4">' +
                                                            '<input type="text" id="market_'+v.id+'_ads_banner_huawei" name="market['+v.id+'][ads][ads_banner_huawei]" placeholder="ads_banner_huawei"  class="form-control"/>' +
                                                        '</div>'+
                                                        '<div class="form-group col-sm-4">' +
                                                            '<input type="text" id="market_'+v.id+'_ads_inter_huawei" name="market['+v.id+'][ads][ads_inter_huawei]" placeholder="ads_inter_huawei"  class="form-control"/>' +
                                                        '</div>'+
                                                        '<div class="form-group col-sm-4">' +
                                                            '<input type="text" id="market_'+v.id+'_ads_reward_huawei" name="market['+v.id+'][ads][ads_reward_huawei]" placeholder="ads_reward_huawei"  class="form-control"/>' +
                                                        '</div>'+
                                                        '<div class="form-group col-sm-4">' +
                                                            '<input type="text" id="market_'+v.id+'_ads_native_huawei" name="market['+v.id+'][ads][ads_native_huawei]" placeholder="ads_native_huawei"  class="form-control"/>' +
                                                        '</div>'+
                                                        '<div class="form-group col-sm-4">' +
                                                            '<input type="text" id="market_'+v.id+'_ads_splash_huawei" name="market['+v.id+'][ads][ads_splash_huawei]" placeholder="ads_splash_huawei"  class="form-control"/>' +
                                                        '</div>'+
                                                        '<div class="form-group col-sm-4">' +
                                                            '<input type="text" id="market_'+v.id+'_ads_roll_huawei" name="market['+v.id+'][ads][ads_roll_huawei]" placeholder="ads_roll_huawei"  class="form-control"/>' +
                                                        '</div>'+

                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>';
                    });


                    $('#nav_tabs_market').html(nav_market);
                    $('#package_ads').html(package_ads);
                })



            });

            $(document).on('click','.fakeProject', function (data){
                var project_id = $(this).data("id");

                $('#modelFakeHeading').html("Fake Image Project");
                $('#saveBtn').val("fake-project");
                $('#fakeProjectModel').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });

                $.ajax({
                    type: "get",
                    url: "{{ asset("project/edit") }}/" + project_id,
                    success: function (data) {

                        $("#avatar_fake").attr("src","../storage/projects/"+data.da.ma_da+"/"+data.projectname+"/lg114.png");
                        $("#project_id_fake").val(data.projectid);
                        $("#title_app_fake").val(data.title_app);
                        $("#buildinfo_vernum_fake").val(data.buildinfo_vernum);
                        $("#buildinfo_verstr_fake").val(data.buildinfo_verstr);



                        $("#buildinfo_app_name_x_fake").val(data.markets[0].pivot.app_name_x);
                        $("#Chplay_package_fake").val(data.markets[0].pivot.package);
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });

            });

            $("a.dashboard").on("click",function(){
                var  form = $('#fakeprojectForm').serialize();
                var img = $('#avatar_fake').attr('src');
                window.open('/fakeimage?action=dashboard&'+form+'&img='+img,'_blank');
            });

            $('#buildcheckForm button').click(function (event){
                event.preventDefault();
                var data = $('textarea#buildinfo_vernum').val()
                var myArray = data.split("\n");
                if($(this).attr("value") == "build"){
                    $.ajax({
                        data: {data: myArray},
                        url: "{{ route('project.updateConsole')}}?buildinfo_console=1",
                        type: "post",
                        dataType: 'json',
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    $("#buildcheckForm").notify(
                                        data.errors[count],"error",
                                        { position:"right" }
                                    );
                                }
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#buildcheckForm').trigger("reset");
                                $('#buildcheckModel').modal('hide');
                                $('textarea#buildinfo_vernum').html('')
                                table.draw();
                            }
                        },
                    });
                }
                if($(this).attr("value") == "check"){
                    $.ajax({
                        data: {data: myArray},
                        url: "{{ route('project.updateConsole')}}?buildinfo_console=4",
                        type: "post",
                        dataType: 'json',
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    $("#buildcheckForm").notify(
                                        data.errors[count],"error",
                                        { position:"right" }
                                    );
                                }
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#buildcheckForm').trigger("reset");
                                $('#buildandcheckModel').modal('hide');
                                $('textarea#buildinfo_vernum').html('')
                                table.draw();
                            }
                        },
                    });

                }

            });

            $('#changeMultipleForm button').click(function (event){
                event.preventDefault();
                if($('#changeMultipleBtn').val() == 'change_keystore'){
                    $.ajax({
                        data: $('#changeMultipleForm').serialize(),
                        url: "{{ route('project.updateMultiple')}}?action=keystore",
                        type: "post",
                        dataType: 'json',
                        success: function (data) {
                            if(data.errors){
                                $.notify(data.errors, "error");
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#changeMultipleForm').trigger("reset");
                                $('#changeMultiple').modal('hide');
                                table.draw();
                            }
                        },
                    });
                }
                if($('#changeMultipleBtn').val() == 'change_sdk'){
                    $.ajax({
                        data: $('#changeMultipleForm').serialize(),
                        url: "{{ route('project.updateMultiple')}}?action=sdk",
                        type: "post",
                        dataType: 'json',
                        success: function (data) {

                            if(data.errors){
                                $.notify(data.errors, "error");
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#changeMultipleForm').trigger("reset");
                                $('#changeMultiple').modal('hide');
                                table.draw();
                            }
                        },
                    });
                }
                if($('#changeMultipleBtn').val() == 'change_upload_status'){
                    $.ajax({
                        data: $('#changeMultipleForm').serialize(),
                        url: "{{ route('project.updateMultiple')}}?action=upload_status",
                        type: "post",
                        dataType: 'json',
                        success: function (data) {

                            if(data.errors){
                                $.notify(data.errors, "error");
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#changeMultipleForm').trigger("reset");
                                $('#changeMultiple').modal('hide');
                                table.draw();
                            }
                        },
                    });
                }

            });

            $('#dev_statusForm button').click(function (event){
                event.preventDefault();
                $.ajax({
                    data: $('#dev_statusForm').serialize(),
                    url: "{{ route('project.updateDev_status')}}",
                    type: "post",
                    dataType: 'json',
                    success: function (data) {
                        if(data.errors){
                            $.notify(data.errors, "error");
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#dev_statusForm').trigger("reset");
                            $('#dev_statusModel').modal('hide');
                            table.draw();
                        }
                    },
                });



            });

            $('#Check_all').change(function () {
                $('.cb-element').prop('checked',this.checked);
            });

            $('.cb-element').change(function () {
                if ($('.cb-element:checked').length == $('.cb-element').length){
                    $('#checkall').prop('checked',true);
                }
                else {
                    $('#checkall').prop('checked',false);
                }
            });
        });

        $('#build_check').on('click', function () {
            $('#buildcheckModel').modal('show');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });
        });

        $('#change_keystore').on('click', function () {
            $('#changeMultiple').modal('show');
            $('#market_upload').hide();
            $('.cb-element').prop('checked',true);
            $('#changeMultipleTitle').html('KeyStore');
            // $('#changeMultipleName').html('ID Project | Key C| Key A | Key S |  Key X |  Key O |  Key V |  Key H');
            $('#changeMultipleBtn').val('change_keystore');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });
        });

        $('#change_sdk').on('click', function () {
            $('#changeMultiple').modal('show');
            $('#market_upload').hide();
            $('.cb-element').prop('checked',true);

            $('#changeMultipleTitle').html('SDK');
            // $('#changeMultipleName').html('ID Project | SDK C | SDK A | SDK S |  SDK X |  SDK O |  SDK V |  SDK H');
            $('#changeMultipleBtn').val('change_sdk');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });
        });

        $('#change_upload_status').on('click', function () {
            $('#changeMultiple').modal('show');
            $('#market_upload').show();
            $('#changeMultipleTitle').html('Upload Project');
            // $('#changeMultipleName').html('ID Project');
            $('#changeMultipleBtn').val('change_upload_status');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });
        });

        $('#dev_status').on('click', function () {
            $('#dev_statusModel').modal('show');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });

            <?php
                $markets = \App\Models\Markets::all();
                foreach ($markets as $market){
                ?>

                $('#_{{$market->market_name}}_dev_id').select2(
                    {
                        minimumInputLength: 2,
                        ajax: {
                            url: '{{route('api.getDev')}}',
                            dataType: 'json',
                            type: "GET",
                            // quietMillis: 50,
                            data: function(params) {

                                return {
                                    q: params.term, // search term
                                    dev_id: {{$market->id}},
                                    page: params.page
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function (item) {
                                        return {
                                            text: item.name + ' : ' + item.store,
                                            id: item.id
                                        }
                                    })
                                };
                            },
                            // cache: false
                        },
                    }
                );

            <?php
            }
            ?>

        });

        function getIndex(item){
            let index = $(item).val();
            let myArray = index.split("\n");
            let data = {
                projectname : myArray
            }
            let text = "";
            $.ajax({
                type: 'get',
                url: '{{asset('project/check_build')}}',
                data: data,
                success: function (data) {
                    data.forEach(element =>{
                        text += element.projectname + " | " + element.buildinfo_vernum + " | " + element.buildinfo_verstr +"\n";
                    });
                    $("textarea#buildinfo_vernum").html(text)
                },
            });
        }

        function getProjectMarket(projectID,marketID){
            $.ajax({
                type: 'get',
                url: '{{asset('api/getProject')}}?projectID='+projectID+'&marketID='+marketID,
                success: function (data) {
                    $('#market_'+marketID+'_package').val(data.package)
                    $('#market_'+marketID+'_app_link').val(data.app_link)
                    $('#market_'+marketID+'_policy_link').val(data.policy_link)
                    $('#market_'+marketID+'_app_id').val(data.appID)
                    $('#market_'+marketID+'_app_name_x').val(data.app_name_x)
                    $('#market_'+marketID+'_sdk').val(data.sdk)
                    $('#market_'+marketID+'_video_link').val(data.video_link)
                    var ads = JSON.parse(data.ads);
                    $.each(ads, function (k,v){
                        $('#market_'+marketID+'_'+k).val(v)
                    })
                    $('#'+marketID+'_dev_id').select2("trigger", "select", {
                        data: {
                            id: data.dev_id,
                            text: data.dev_id ? data.dev.dev_name + ': ' + data.dev.store_name : ''
                        }
                    });
                    $('#'+marketID+'_keystore').select2("trigger", "select", {
                        data: {
                            id: data.keystore,
                            text: data.keystore
                        }
                    });
                },
            });
        }


    </script>

@endsection
