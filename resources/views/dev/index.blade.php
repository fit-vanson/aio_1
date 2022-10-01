@extends('layouts.master')

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
@section('breadcrumb')
{{--    <div class="col-sm-6">--}}
{{--        <h4 class="page-title">Quản lý Dev</h4>--}}
{{--    </div>--}}
{{--    <div class="col-sm-6">--}}
{{--        <div class="float-right">--}}
{{--            <a class="btn btn-success" href="javascript:void(0)" id="createNewDev"> Create New</a>--}}
{{--        </div>--}}
{{--    </div>--}}
    @include('modals.dev')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-rep-plugin" >
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="devTable" class="table table-striped table-bordered dt-responsive data-table"
                                   style="width: 100%;">
                                <thead>
                                <tr>
                                    <th style="display: none">ID</th>
                                    <th style="width: 10%;">Ga name</th>
                                    <th style="width: 20%;">Dev name</th>
                                    <th style="width: 20%;">Gmail </th>
                                    <th style="width: 10%;">Thuộc tính</th>
                                    <th style="width: 10%;">Link | Fanpage |Policy</th>
                                    <th style="width: 10%">Market</th>
                                    <th style="width: 10%;">Trạng thái</th>
                                    <th style="width: 10%;">Action</th>
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
    <script src="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>

    <script src="{{ URL::asset('/assets/libs/toastr/toastr.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/table.init.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script type="text/javascript">
        $(function () {
            $('.table-responsive').responsiveTable({
                // addDisplayAllBtn: 'btn btn-secondary'
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#ga_id').select2({
                // initialValue:true,
                placeholder: "Select a customer",
                minimumInputLength: 2,
                ajax: {
                    url: '{{route('api.getGa')}}',
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
            $('#market_id').select2({
                // initialValue:true,
                placeholder: "Select a customer",
                // minimumInputLength: 2,
                ajax: {
                    url: '{{route('api.getMarket')}}',
                    dataType: 'json',
                    type: "GET",
                    // quietMillis: 50,
                    // data: function(params) {
                    //     return {
                    //         q: params.term, // search term
                    //         page: params.page
                    //     };
                    // },
                    processResults: function(data) {

                        // console.log(data)
                        // return {
                        //     results: data.items
                        // };
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
            $('#mail_id_1').select2({
                // initialValue:true,
                placeholder: "Select a customer",
                minimumInputLength: 2,
                ajax: {
                    url: '{{route('api.getGmailDev')}}',
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
            $('#mail_id_2').select2({
                // initialValue:true,
                placeholder: "Select a customer",
                minimumInputLength: 2,
                ajax: {
                    url: '{{route('api.getGmailDev')}}',
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
            $('#profile_id').select2({
                // initialValue:true,
                placeholder: "Select a customer",
                minimumInputLength: 2,
                ajax: {
                    url: '{{route('api.getProfile')}}',
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
                                    text: item.name + ': ' + item.ho_ten + ' - '+ item.add,
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


            var marketAll = <?php echo \App\Models\Markets::all() ?>;
            var table = $('#devTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('dev.getIndex') }}",
                    type: "post"
                },
                columns: [
                    {data: 'id',visible: false},
                    {data: 'ga_id'},
                    {data: 'dev_name'},
                    {data: 'mail_id_1'},
                    {data: 'company_pers',className: "text-center"},
                    {data: 'info_url',orderable: false},
                    {data: 'market_id',orderable: false},
                    {data: 'status',orderable: false},
                    {data: 'action', className: "text-center",name: 'action', orderable: false, searchable: false},
                ],
                order:[0,'desc'],
                initComplete: function () {
                    this.api().columns([5]).every( function () {
                        var column = this;
                        var select = $('<select class="form-control"><option value="">Market</option></select>')
                            .appendTo( $(column.header()).empty() )
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search( val ? val : '', true, false )
                                    .draw();
                            } );

                        $.each(marketAll, function ( d, j ) {
                            select.append( '<option value="'+j.id+'">'+j.market_name+'</option>' )
                        } );
                    } );
                    this.api().columns([6]).every( function () {
                        var column = this;
                        var select = $('<select class="form-control"><option value="">Status</option></select>')
                            .appendTo( $(column.header()).empty() )
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
                                column
                                    .search( val ? val : '', true, false )
                                    .draw();
                            } );

                        $.each([0,1,2,3], function ( d, j ) {
                            var status ='';
                            switch (j){
                                case 0:
                                    status ='Chưa xử dụng' ;
                                    break;
                                case 1:
                                    status = 'Đang phát triển';
                                    break;
                                case 2:
                                    status = 'Đóng';
                                    break;
                                case 3:
                                    status = 'Suspend';
                                    break;
                            }
                            select.append( '<option value="'+j+'">'+status+'</option>' )
                        } );
                    } );
                },
            });
            $('#createNewDev').click(function () {
                $('#saveBtn').val("create");
                $('#id').val('');
                $('#devForm').trigger("reset");
                $('#modelHeading').html("Thêm mới");
                $('#ajaxModelDev').modal('show');


            });
            $('#devForm').on('submit',function (event){
                event.preventDefault();
                if($('#saveBtn').val() == 'create'){
                    $.ajax({
                        data: $('#devForm').serialize(),
                        url: "{{ route('dev.create') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    $("#devForm").notify(
                                        data.errors[count],"error",
                                        { position:"right" }
                                    );
                                }
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#devForm').trigger("reset");
                                $('#ajaxModelDev').modal('hide');
                                table.draw();
                            }
                        },
                    });
                }
                if($('#saveBtn').val() == 'edit-dev'){
                    $.ajax({
                        data: $('#devForm').serialize(),
                        url: "{{ route('dev.update') }}",
                        type: "post",
                        dataType: 'json',
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    $("#devForm").notify(
                                        data.errors[count],"error",
                                        { position:"right" }
                                    );
                                }
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#devForm').trigger("reset");
                                $('#ajaxModelDev').modal('hide');
                                table.draw();
                            }
                        },
                    });
                }

            });

            $(document).on('click','.deleteDev', function (data){
                var id = $(this).data("id");

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
                            url: "{{ asset("dev/delete") }}/" + id,
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
        });
    </script>
    <script>
        function editDev(id) {
            $.get('{{asset('dev/edit')}}/'+id,function (data) {
                console.log(data)

                $('#dev_id').val(data.id);
                $('#store_name').val(data.store_name);
                $('#dev_name').val(data.dev_name);

                if(data.ga_id){
                    $("#ga_id").select2("trigger", "select", {
                        data: { id: data.ga_id,text: data.ga.ga_name }
                    });
                }
                if(data.market_id){
                    $("#market_id").select2("trigger", "select", {
                        data: { id: data.market_id,text: data.markets.market_name }
                    });
                }

                if(data.mail_id_1){
                    $("#mail_id_1").select2("trigger", "select", {
                        data: { id: data.mail_id_1,text: data.gmail_dev1.gmail }
                    });
                    }
                if(data.mail_id_2){
                    $("#mail_id_2").select2("trigger", "select", {
                        data: { id: data.mail_id_2,text: data.gmail_dev2.gmail }
                    });
                }
                if(data.profile_id){
                    $("#profile_id").select2("trigger", "select", {
                        data: { id: data.profile_id,text: data.profile.profile_name + ': ' +data.profile.profile_ho_va_ten + ' - ' +data.profile.profile_add  }
                    });
                }


                $('#mahoadon').val(data.mahoadon);
                $('#pass').val(data.pass);
                $('#info_logo').val(data.logo);
                $('#info_banner').val(data.banner);
                $('#info_policydev').val(data.url_policy);
                $('#info_fanpage').val(data.url_fanpage);
                $('#info_phone').val(data.phone);
                $('#api_dev_id').val(data.dev_id);
                $('#api_client_id').val(data.api_client_id);
                $('#api_client_secret').val(data.api_client_secret);
                $('#api_token').val(data.api_token);
                $('#api_access_key').val(data.api_access_key);



                $('#status').val(data.status);
                $('#note').val(data.note);

                if(data.company_pers == 1){
                    $("#company").prop('checked', true);
                }else{
                    $("#person").prop('checked', true);
                }

                $('#modelHeading').html("Edit");
                $('#saveBtn').val("edit-dev");
                $('#ajaxModelDev').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            })
        }
    </script>

@endsection






