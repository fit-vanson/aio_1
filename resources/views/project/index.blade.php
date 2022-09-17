@extends('layouts.master')

@section('title') @lang('translation.Responsive_Table') @endsection

@section('css')
    <!-- datatables css -->
    <link href="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/libs/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/libs/toastr/ext-component-toastr.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css"/>

    <link href="plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
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
                                    <th style="width: 10%">Logo</th>
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

    <script src="plugins/select2/js/select2.min.js"></script>

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
                displayLength: 5,
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
                    {data: 'projectid', name: 'projectid'},
                    {data: 'logo', name: 'logo',orderable: false},
                    {data: 'projectname', name: 'projectname'},
                    {data: 'markets', name: 'markets'},
                    {data: 'status', name: 'status'},
                    // {data: 'Chplay_package', name: 'Chplay_package'},
                    // {data: 'status', name: 'status',orderable: false},
                    {data: 'action', name: 'action',className: "text-center", orderable: false, searchable: false},
                ],
                columnDefs: [
                    {
                        "targets": [ 0 ],
                        "visible": false,
                        "searchable": false
                    }
                ],
                order: [[ 0, 'desc' ]]
            });


            $('#createNewProject').click(function () {
                $('#saveBtn').val("create-project");
                $('#project_id').val('');
                $("#avatar").attr("src","img/logo.png");
                $('#modelHeading').html("Thêm mới Project");
                $('#ajaxModel').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
                // $('#projectForm').trigger("reset");
                $('#projectForm')[0].reset();



                $('#template').select2(
                    {
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
                    }
                );

                $('#ma_da').select2(
                    {
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
                    }
                );
                // $('#ma_da').val('').trigger('change');
                // $('#template').val('').trigger('change');


                <?php
                $markets = \App\Models\Markets::all();
                foreach ($markets as $market){
                ?>
                    $('#nav_{{$market->market_name}}').hide()
                    $('#package_{{$market->market_name}}').hide()
                    {{--$('#tab_{{$market->market_name}}').hide()--}}

                    $('#tab_{{$market->market_name}}').removeClass( 'active' );
{{--                    $('#nav_{{$market->market_name}}').removeClass( 'active' );--}}
{{--                    $('#package_{{$market->market_name}}').removeClass( 'active' );--}}


                <?php
                }
                ?>
               //
               //  $('#ma_da').val(null).trigger('change');
               //  $('#template').val(null).trigger('change');



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
                                // table.draw();
                            }
                        },
                    });
                }
            });

            $(document).on('change', '.choose_template', function () {
                var _id = $(this).select2('data')[0].id;
                $.get('{{asset('template/edit')}}/'+_id,function (data) {
                    <?php
                        $markets = \App\Models\Markets::all();
                        foreach ($markets as $market){
                    ?>
                        if(data.{{ucfirst(strtolower($market->market_name))}}_category){
                            $('#nav_{{$market->market_name}}').show();
                            $('#package_{{$market->market_name}}').show();
                            {{--$('#tab_{{$market->market_name}}').show()--}}

                            $('#{{$market->market_name}}_dev_id').select2(
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
                        $('#{{$market->market_name}}_keystore').select2(
                            {
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
                                    // cache: false
                                },
                            }
                        );
                        }else {
                            $('#nav_{{$market->market_name}}').hide()
                            $('#package_{{$market->market_name}}').hide()
                        }
                    <?php
                        }
                    ?>
                })

            });

            $(document).on('change', '.choose_da', function () {
                var _text = $(this).select2('data')[0].text;
                $('#projectname').val(_text+'-');

            })

            $(document).on('change', '#buildinfo_vernum', function () {
                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                var yyyy = today.getFullYear();
                var num = $(this).val()
                $('#buildinfo_verstr').val(num + '.'+dd+mm+'.'+yyyy)
            })
        });

        function editProject(id) {
            $.get('{{asset('project/edit')}}/'+id,function (data) {

                console.log(data)

                $('#modelHeading').html("Edit Project " + data.projectname);
                $('#saveBtn').val("edit-project");
                $('#ajaxModel').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });

                $('#projectname').val(data.projectname)
                $('#title_app').val(data.title_app)
                $('#buildinfo_vernum').val(data.buildinfo_vernum)
                $('#buildinfo_verstr').val(data.buildinfo_verstr)
                $('#buildinfo_link_youtube_x').val(data.buildinfo_link_youtube_x)
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


                <?php
                    $markets = \App\Models\Markets::all();
                    foreach ($markets as $market){
                    ?>
                if(data.ma_template.{{ucfirst(strtolower($market->market_name))}}_category){
                    $('#nav_{{$market->market_name}}').show();
                    $('#package_{{$market->market_name}}').show();
                    {{--$('#tab_{{$market->market_name}}').show()--}}

                    $('#{{$market->market_name}}_dev_id').select2(
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
                    $('#{{$market->market_name}}_keystore').select2(
                        {
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
                                // cache: false
                            },
                        }
                    );
                }else {
                    $('#nav_{{$market->market_name}}').hide()
                    $('#package_{{$market->market_name}}').hide()
                }
                <?php
                }
                ?>

                $('#template').val(data.template);
                $('#template').select2();



            })
        }
    </script>

@endsection
