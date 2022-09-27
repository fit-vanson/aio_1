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
{{--    <script src="{{ URL::asset('/assets/js/table.init.js') }}"></script>--}}

    {{--    <script src="plugins/select2/js/select2.min.js"></script>--}}
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


            var marketAll = <?php echo \App\Models\Markets::all() ?>;
            var table = $('#devTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('dev.getIndex') }}",
                    type: "post"
                },
                columns: [
                    // {data: 'info_logo'},
                    {data: 'ga_id'},
                    {data: 'dev_name'},
                    {data: 'mail_id_1'},
                    {data: 'company_pers',className: "text-center"},
                    {data: 'info_url'},
                    {data: 'market_id',orderable: false},
                    {data: 'status',orderable: false},
                    {data: 'action', className: "text-center",name: 'action', orderable: false, searchable: false},
                ],
                order:[1,'asc'],
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
                $('#devAmazonForm').trigger("reset");
                $('#modelHeading').html("Thêm mới");
                $('#ajaxModelDev').modal('show');
                $('#id_ga').select2();
                $('#gmail_gadev_chinh').select2();
                $('#gmail_gadev_phu_1').select2();
                $('#gmail_gadev_phu_2').select2();
                $('#profile_info').select2();

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
                            url: "{{ asset("dev/delete") }}/" + id,
                            success: function (data) {
                                table.draw();
                            },
                            error: function (data) {
                                console.log('Error:', data);
                            }
                        });
                        swal("Đã xóa!", "Your imaginary file has been deleted.", "success");
                    });
            });
        });
    </script>
    <script>
        function editDev(id) {
            $.get('{{asset('dev/edit')}}/'+id,function (data) {
                $('#dev_id').val(data.id);
                $('#store_name').val(data.store_name);
                $('#dev_name').val(data.dev_name);
                $('#ma_hoa_don').val(data.ma_hoa_don);

                $('#id_ga').val(data.id_ga);
                $('#id_ga').select2();


                $('#gmail_gadev_chinh').val(data.gmail_gadev_chinh);
                $('#gmail_gadev_chinh').select2();

                $('#gmail_gadev_phu_1').val(data.gmail_gadev_phu_1);
                $('#gmail_gadev_phu_1').select2();

                $('#gmail_gadev_phu_2').val(data.gmail_gadev_phu_2);
                $('#gmail_gadev_phu_2').select2();

                $('#info_phone').val(data.info_phone);
                $('#pass').val(data.pass);
                $('#info_andress').val(data.info_andress);
                $('#info_company').val(data.info_company);
                $('#profile_info').val(data.profile_info);
                $('#profile_info').select2();
                $('#info_url').val(data.info_url);
                $('#info_logo').val(data.info_logo);
                $('#info_banner').val(data.info_banner);
                $('#info_policydev').val(data.info_policydev);
                $('#info_fanpage').val(data.info_fanpage);
                $('#info_web').val(data.info_web);
                $('#status').val(data.status);
                $('#note').val(data.note);

                if(data.thuoc_tinh == 1){
                    $('.thuoc_tinh').show();
                    $('.info_company').hide();
                    $("#individual1").prop('checked', true);
                }else{
                    $('.thuoc_tinh').show();
                    $('.info_company').show();

                    $("#company1").prop('checked', true);
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
    <script>
        $("#addGaDevForm").submit(function (e) {
            e.preventDefault();
            let data = new FormData(document.getElementById('addGaDevForm'));
            $.ajax({
                url:"{{route('gadev.create')}}",
                type: "post",
                data:data,
                processData: false,
                contentType: false,
                dataType: 'json',
                beForeSend : () => {

                },
                success:function (data) {
                    if(data.errors){
                        for( var count=0 ; count <data.errors.length; count++){
                            $("#addGaDevForm").notify(
                                data.errors[count],"error",
                                { position:"right" }
                            );
                        }
                    }
                    $.notify(data.success, "success");
                    $('#addGaDevForm').trigger("reset");
                    $('#addGaDev').modal('hide');
                    if(typeof data.allGa_dev == 'undefined'){
                        data.allGa_dev = {};                    }
                    if(typeof rebuildMailOption == 'function'){
                        rebuildMailOption(data.allGa_dev)
                    }
                }
            });
        });
        function getit(){
            var select = $('#profile_info').val();
            var radio = document.querySelector('input[name="attribute1"]:checked').value;
            if(radio == 1) {
                $.get('{{asset('profile/show?ID=')}}'+select,function (data) {
                    $('#info_company').val('');
                    $('.info_company').hide();
                    $('#info_andress').val(data.profile.profile_add);
                });
            }else {
                $.get('{{asset('profile/show?ID=')}}'+select,function (data) {
                    if(data.profile.company[0]){
                        $('.info_company').show();
                        $('#info_company').val(data.profile.company[0].name_en);
                        $('#info_andress').val(data.profile.company[0].dia_chi);
                    }
                });
            }
        }

        {{--$('select').on('change', function() {--}}
        {{--    var radio = document.querySelector('input[name="attribute1"]:checked').value;--}}
        {{--    $.get('{{asset('profile/show?ID=')}}' + this.value, function (data) {--}}
        {{--        if(radio != 1){--}}
        {{--            if (data.profile.company[0]) {--}}
        {{--                $('.info_company').show();--}}
        {{--                $('#info_company').val(data.profile.company[0].name_en);--}}
        {{--                $('#info_andress').val(data.profile.company[0].dia_chi);--}}
        {{--            } else {--}}
        {{--                $('.info_company').hide();--}}
        {{--                $('#info_company').val('');--}}
        {{--                $('#info_andress').val(data.profile.profile_add);--}}
        {{--            }--}}
        {{--        }else {--}}
        {{--            $('.info_company').hide();--}}
        {{--            $('#info_company').val('');--}}
        {{--            $('#info_andress').val(data.profile.profile_add);--}}
        {{--        }--}}
        {{--    });--}}
        {{--});--}}

    </script>
    <script>
        function rebuildMailOption(mails){
            var elementSelect = $("#amazon_email");
            if(elementSelect.length <= 0){
                return false;
            }
            elementSelect.empty();
            for(var m of mails){
                elementSelect.append(
                    $("<option></option>", {
                        value : m.id
                    }).text(m.gmail)
                );
            }
        }
    </script>
@endsection






