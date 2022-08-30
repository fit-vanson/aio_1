@extends('layouts.master')

@section('css')

    <link href="plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />



    <!-- Sweet-Alert  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>


    <!-- Select2 Js  -->
    <link href="plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title">Quản lý Ga</h4>
    </div>
    <div class="col-sm-6">
        <div class="float-right">
            <a class="btn btn-success" href="javascript:void(0)" id="createNewGa"> Create New</a>
        </div>
    </div>
    @include('modals.ga')
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <table class="table table-bordered dt-responsive nowrap data-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Ga name</th>
                            <th>Gmail </th>
                            <th>Điện thoại</th>
                            <th>Phương thức thanh tóan</th>
                            <th>Trạng thái</th>
                            <th>Action</th>
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
    <!-- Moment.js: -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/plug-ins/1.10.20/sorting/datetime-moment.js"></script>

    <script src="plugins/select2/js/select2.min.js"></script>
    <script type="text/javascript">
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('ga.index') }}",
                columns: [
                    {data: 'ga_name'},
                    {data: 'gmail_gadev_chinh'},
                    {data: 'info_phone'},
                    {data: 'payment'},
                    {
                        "data": "status",
                        "render" : function(data)
                        {
                            if(data == 0){
                                return '<span class="badge badge-dark">Chưa xử dụng</span>';
                            }
                            if(data == 1){
                                return '<span class="badge badge-primary">Đang sử dụng</span>';
                            }
                            if(data == 2){
                                return '<span class="badge badge-warning">Tụt Match Rate</span>';
                            }
                            if(data == 3){
                                return '<span class="badge badge-danger">Disable</span>';
                            }
                        },

                        "name": "status", "autoWidth": true
                    },
                    {data: 'action',className: "text-center", name: 'action', orderable: false, searchable: false},
                ],
            });
            $('#createNewGa').click(function () {
                $('#saveBtn').val("create-ga");
                $('#ga_id').val('');
                $('#gaForm').trigger("reset");
                $('#modelHeading').html("Thêm mới");
                $('#ajaxModel').modal('show');
                $("#gmail_gadev_chinh").select2({});
                $("#gmail_gadev_phu_1").select2({});
                $("#gmail_gadev_phu_2").select2({});
            });
            $('#gaForm').on('submit',function (event){
                event.preventDefault();
                if($('#saveBtn').val() == 'create-ga'){
                    $.ajax({
                        data: $('#gaForm').serialize(),
                        url: "{{ route('ga.create') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    $("#gaForm").notify(
                                        data.errors[count],"error",
                                        { position:"right" }
                                    );
                                }
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#gaForm').trigger("reset");
                                $('#ajaxModel').modal('hide');
                                table.draw();
                            }
                        },
                    });
                }
                if($('#saveBtn').val() == 'edit-ga'){
                    $.ajax({
                        data: $('#gaForm').serialize(),
                        url: "{{ route('ga.update') }}",
                        type: "post",
                        dataType: 'json',
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    $("#gaForm").notify(
                                        data.errors[count],"error",
                                        { position:"right" }
                                    );
                                }
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#gaForm').trigger("reset");
                                $('#ajaxModel').modal('hide');
                                table.draw();
                            }
                        },
                    });
                }

            });

            $(document).on('click','.deleteGa', function (data){
                var ga_id = $(this).data("id");

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
                            url: "{{ asset("ga/delete") }}/" + ga_id,
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
        function editGa(id) {
            $.get('{{asset('ga/edit')}}/'+id,function (data) {
                $('#ga_id').val(data.id);
                $('#ga_name').val(data.ga_name);
                $('#gmail_gadev_chinh').val(data.gmail_gadev_chinh);
                $('#gmail_gadev_chinh').select2();
                $('#gmail_gadev_phu_1').val(data.gmail_gadev_phu_1);
                $('#gmail_gadev_phu_1').select2();
                $('#gmail_gadev_phu_2').val(data.gmail_gadev_phu_2);
                $('#gmail_gadev_phu_2').select2();
                $('#info_phone').val(data.info_phone);
                $('#info_andress').val(data.info_andress);
                $('#payment').val(data.payment);
                $('#app_ads').val(data.app_ads);
                $('#note').val(data.note);
                $('#status').val(data.status);

                $('#modelHeading').html("Edit");
                $('#saveBtn').val("edit-ga");
                $('#ajaxModel').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
            })
        }
        function showGa(id) {
            $.get('{{asset('ga/showDev')}}/'+id,function (data) {
                console.log(data.length)
                if(data.length == 0){
                    swal({
                        title: "Thông báo!",
                        text: "Ga chưa được sử dụng!",
                        timer: 1500,
                        type: 'warning',
                        showConfirmButton: false

                    });
                }else {
                    var html_row ='<ol>';
                    $.each(data, function(key, val){
                        html_row += '<li>'+ val.dev_name +'</li>';
                    });
                    html_row += '</ol>';
                    var html_content = '<p>'+ html_row +'</p>';
                    $('#showDev_detail').html(html_content);
                    $('#showDev').modal('show');
                    $('.modal').on('hidden.bs.modal', function (e) {
                        $('body').addClass('modal-open');
                    });
                }
            })
        }


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
                    console.log(data)

                    if(typeof data.allGa_dev == 'undefined'){
                        data.allGa_dev = {};
                    }
                    if(typeof rebuildMailOption == 'function'){
                        rebuildMailOption(data.allGa_dev)
                    }
                }
            });

        });

    </script>
    <script>
        function rebuildMailOption(mails){
            var elementSelect = $("#gmail_gadev_chinh");
            var elementSelect1 = $("#gmail_gadev_phu_1");
            var elementSelect2 = $("#gmail_gadev_phu_2");

            if(elementSelect.length <= 0 || elementSelect1.length <= 0  || elementSelect2.length <= 0){
                return false;
            }
            elementSelect.empty();
            elementSelect1.empty();
            elementSelect2.empty();
            for(var m of mails){
                elementSelect.append(
                    $("<option></option>", {
                        value : m.id
                    }).text(m.gmail)
                );
                elementSelect1.append(
                    $("<option></option>", {
                        value : m.id
                    }).text(m.gmail)
                );
                elementSelect2.append(
                    $("<option></option>", {
                        value : m.id
                    }).text(m.gmail)
                );
            }
        }
    </script>
@endsection






