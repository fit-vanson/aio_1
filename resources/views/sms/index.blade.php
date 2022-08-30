@extends('layouts.master')

@section('css')

<link href="plugins/datatables/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="plugins/datatables/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<link href="plugins/datatables/responsive.bootstrap4.min.css" rel="stylesheet" type="text/css" />

<!-- Sweet-Alert  -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

/*<!-- Select2 Js  -->*/
/*<link href="plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />*/

@endsection

@section('breadcrumb')
<div class="col-sm-6">
    <h4 class="page-title">Quản lý SMS</h4>
</div>
<div class="col-sm-6">
    <div class="float-right">
{{--        @can('user-add')--}}
{{--        <a class="btn btn-success" href="javascript:void(0)" id="createNewSms"> Thêm mới</a>--}}
{{--        @endcan--}}
    </div>
</div>
{{--@include('modals.sms')--}}
@endsection
@section('content')

    <ul class="nav nav-pills nav-justified" role="tablist">
        @foreach($hubs as $hub)
            <li class="nav-item waves-effect waves-light">
               <button class="btn btn-success waves-effect waves-light" onclick="showHub({{$hub->id}})">{{$hub->hubname}}<br>{{$hub->cocsims ? $hub->cocsims->cocsim : ''}}</button>

            </li>
        @endforeach
    </ul>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div>
                    <div class="tab-content showHub ">
                    </div>
                </div>
                <div class="card-body showAll">
                    <table class="table table-bordered dt-responsive nowrap data-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Hub ID</th>
{{--                            <th>Cọc sim</th>--}}
                            <th>Phone</th>
                            <th>Code</th>
                            <th>SMS</th>
                            <th>Thời gian nhận code</th>
{{--                            <th>Action</th>--}}
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

{{--<script>--}}
{{--    $(".select2").select2({--}}
{{--        placeholder: "Vui lòng chọn",--}}
{{--    });--}}
{{--</script>--}}

<script type="text/javascript">
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('.data-table').DataTable({
            lengthMenu: [[15, 30, 45, -1], [15, 30, 45, "All"]],

            processing: true,
            serverSide: true,
            ajax: "{{ route('sms.index') }}",
            columns: [
                {data: 'hubid', name: 'hubid'},
                // {data: 'cocsim', name: 'cocsim'},
                {data: 'phone', name: 'phone'},
                {data: 'code', name: 'code'},
                {data: 'sms', name: 'sms'},
                {data: 'timecode', name: 'timecode'},
                // {data: 'action',className: "text-center", name: 'action', orderable: false, searchable: false},
            ],
            dom:
                '<"d-flex justify-content-between align-items-center header-actions mx-2 row mt-75"' +
                '<"col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start" l>' +
                '<"col-sm-12 col-lg-8 ps-xl-75 ps-0"<"dt-action-buttons d-flex align-items-center justify-content-center justify-content-lg-end flex-lg-nowrap flex-wrap"<"me-1"f>B>>' +
                '>t' +
                '<"d-flex justify-content-between mx-2 row mb-1"' +
                '<"col-sm-12 col-md-6"i>' +
                '<"col-sm-12 col-md-6"p>' +
                '>',
            buttons: [
                {
                    text: 'Reset',
                    className: 'reset_sms btn-default',
                    attr: {
                        'style': 'color:red',
                    },
                    init: function (api, node, config) {
                        $(node).removeClass('btn-secondary');
                    }
                }
            ],
        });

        // $('#createNewSms').click(function () {
        //     $('#saveBtn').val("create-sms");
        //     $('#id').val('');
        //     $('#smsForm').trigger("reset");
        //     $('#modelHeading').html("Thêm mới");
        //     $('#ajaxModel').modal('show');
        //     $('#cocsim').select2();
        //     $('#phone').select2();
        // });
        {{--$('#smsForm').on('submit',function (event){--}}
        {{--    event.preventDefault();--}}
        {{--    if($('#saveBtn').val() == 'create-sms'){--}}
        {{--        $.ajax({--}}
        {{--            data: $('#smsForm').serialize(),--}}
        {{--            url: "{{ route('sms.create') }}",--}}
        {{--            type: "POST",--}}
        {{--            dataType: 'json',--}}
        {{--            success: function (data) {--}}
        {{--                if(data.errors){--}}
        {{--                    for( var count=0 ; count <data.errors.length; count++){--}}
        {{--                        $("#smsForm").notify(--}}
        {{--                            data.errors[count],"error",--}}
        {{--                            { position:"right" }--}}
        {{--                        );--}}
        {{--                    }--}}
        {{--                }--}}
        {{--                if(data.success){--}}
        {{--                    $.notify(data.success, "success");--}}
        {{--                    $('#smsForm').trigger("reset");--}}
        {{--                    $('#ajaxModel').modal('hide');--}}
        {{--                    table.draw();--}}
        {{--                }--}}
        {{--            },--}}
        {{--        });--}}
        {{--    }--}}
        {{--    --}}{{--if($('#saveBtn').val() == 'edit-sms'){--}}
        {{--    --}}{{--    $.ajax({--}}
        {{--    --}}{{--        data: $('#smsForm').serialize(),--}}
        {{--    --}}{{--        url: "{{ route('sms.update') }}",--}}
        {{--    --}}{{--        type: "post",--}}
        {{--    --}}{{--        dataType: 'json',--}}
        {{--    --}}{{--        success: function (data) {--}}
        {{--    --}}{{--            if(data.errors){--}}
        {{--    --}}{{--                for( var count=0 ; count <data.errors.length; count++){--}}
        {{--    --}}{{--                    $("#smsForm").notify(--}}
        {{--    --}}{{--                        data.errors[count],"error",--}}
        {{--    --}}{{--                        { position:"right" }--}}
        {{--    --}}{{--                    );--}}
        {{--    --}}{{--                }--}}
        {{--    --}}{{--            }--}}
        {{--    --}}{{--            if(data.success){--}}
        {{--    --}}{{--                $.notify(data.success, "success");--}}
        {{--    --}}{{--                $('#smsForm').trigger("reset");--}}
        {{--    --}}{{--                $('#ajaxModel').modal('hide');--}}
        {{--    --}}{{--                table.draw();--}}
        {{--    --}}{{--            }--}}
        {{--    --}}{{--        },--}}
        {{--    --}}{{--    });--}}
        {{--    --}}
        {{--    --}}{{--}--}}

        {{--});--}}
        {{--$(document).on('click','.deleteSms', function (data){--}}
        {{--    var id = $(this).data("id");--}}
        {{--    swal({--}}
        {{--            title: "Bạn có chắc muốn xóa?",--}}
        {{--            text: "Your will not be able to recover this imaginary file!",--}}
        {{--            type: "warning",--}}
        {{--            showCancelButton: true,--}}
        {{--            confirmButtonClass: "btn-danger",--}}
        {{--            confirmButtonText: "Xác nhận xóa!",--}}
        {{--            closeOnConfirm: false--}}
        {{--        },--}}
        {{--        function(){--}}
        {{--            $.ajax({--}}
        {{--                type: "get",--}}
        {{--                url: "{{ asset("sms/delete") }}/" + id,--}}
        {{--                success: function (data) {--}}
        {{--                    table.draw();--}}
        {{--                    $.notify('Thành công', "success");--}}
        {{--                },--}}
        {{--                error: function (data) {--}}
        {{--                    console.log('Error:', data);--}}
        {{--                }--}}
        {{--            });                   --}}
        {{--        });--}}
        {{--});--}}


        $(document).on('click','.reset_sms', function (data){

            swal({
                    title: "Bạn có chắc muốn reset?",
                    text: "Your will not be able to recover this imaginary file!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Xác nhận!",
                    closeOnConfirm: true
                },
                function(){
                    $.ajax({
                        type: "get",
                        url: "{{ asset("sms/reset") }}/",
                        success: function (data) {
                            table.draw();
                            $.notify('Thành công', "success");
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                });
        });
    });

</script>

<script>
        function editSms(id) {
            $.get('{{asset('sms/edit')}}/'+id,function (data) {
                $('#modelHeading').html("Edit");
                $('#saveBtn').val("edit-sms");
                $('#ajaxModel').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
                $('#id').val(data.id);
                $('#hubid').val(data.hubid);
                $('#hubname').val(data.hubname);
                $('#cocsim').val(data.cocsim)
                $('#cocsim').select2();
                $('#phone').val(data.phone)
                $('#phone').select2();
                $('#code').val(data.code)
            })
        }

        function showHub(id){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var id = id;
            $.ajax({
                data: {id:id},
                url: "{{ route('sms.showHub') }}",
                type: "post",
                dataType: 'html',
                success: function (data) {
                    $('.showAll').hide();
                    $('.showHub').html(data)
                },
            });
        }





</script>
@endsection


