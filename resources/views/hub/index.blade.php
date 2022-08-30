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
    <h4 class="page-title">Quản lý Hub</h4>
</div>
<div class="col-sm-6">
    <div class="float-right">
        @can('hub-add')
{{--        <a class="btn btn-success" href="javascript:void(0)" id="createNewHub">Thêm mới</a>--}}
        @endcan
    </div>
</div>
@include('modals.hub')
@endsection
@section('content')


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered dt-responsive nowrap data-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>STT</th>
                            <th>Hub Name</th>
                            <th>Cọc Sim</th>
                            <th>Numerady</th>

                            <th>Update</th>
                            <th>Lock Auto <input id="checkAll" type="checkbox"></th>
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

<script src="plugins/select2/js/select2.min.js"></script>
<script>
    $(".select2").select2({
        placeholder: "Vui lòng chọn",
    });
</script>

<script type="text/javascript">
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('.data-table').DataTable({
            lengthMenu: [[15, 30, 45, -1], [15, 30, 45, "All"]],
            columnDefs: [ {
                'targets': [5], /* column index */
                'orderable': false, /* true or false */
            }],

            processing: true,
            serverSide: true,
            ajax: "{{ route('hub.index') }}",
            columns: [
                { "data": null,"sortable": true,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                {data: 'hubname', name: 'hubname'},
                {data: 'cocsim', name: 'cocsim'},
                {data: 'numready', name: 'numready'},

                {data: 'timeupdate', name: 'timeupdate'},
                {data: 'lockauto', name: 'lockauto'},
                {data: 'action',className: "text-center", name: 'action', orderable: false, searchable: false},
            ]
        });




        {{--$('#createNewHub').click(function () {--}}
        {{--    $('#saveBtn').val("create-hub");--}}
        {{--    $('#id').val('');--}}
        {{--    $('#hubForm').trigger("reset");--}}
        {{--    $('#modelHeading').html("Thêm mới");--}}
        {{--    $('#ajaxModel').modal('show');--}}
        {{--    $("#cocsim").select2({});--}}
        {{--    $("#hubname").prop('disabled', false);--}}
        {{--});--}}
        {{--$('#hubForm').on('submit',function (event){--}}
        {{--    event.preventDefault();--}}
        {{--    if($('#saveBtn').val() == 'create-hub'){--}}
        {{--        $.ajax({--}}
        {{--            data: $('#hubForm').serialize(),--}}
        {{--            url: "{{ route('hub.create') }}",--}}
        {{--            type: "POST",--}}
        {{--            dataType: 'json',--}}
        {{--            success: function (data) {--}}
        {{--                if(data.errors){--}}
        {{--                    for( var count=0 ; count <data.errors.length; count++){--}}
        {{--                        $("#hubForm").notify(--}}
        {{--                            data.errors[count],"error",--}}
        {{--                            { position:"right" }--}}
        {{--                        );--}}
        {{--                    }--}}
        {{--                }--}}
        {{--                if(data.success){--}}
        {{--                    $.notify(data.success, "success");--}}
        {{--                    $('#hubForm').trigger("reset");--}}
        {{--                    $('#ajaxModel').modal('hide');--}}
        {{--                    table.draw();--}}
        {{--                }--}}
        {{--            },--}}
        {{--        });--}}
        {{--    }--}}
        {{--    if($('#saveBtn').val() == 'edit-hub'){--}}
        {{--        $.ajax({--}}
        {{--            data: $('#hubForm').serialize(),--}}
        {{--            url: "{{ route('hub.update') }}",--}}
        {{--            type: "post",--}}
        {{--            dataType: 'json',--}}
        {{--            success: function (data) {--}}
        {{--                if(data.errors){--}}
        {{--                    for( var count=0 ; count <data.errors.length; count++){--}}
        {{--                        $("#hubForm").notify(--}}
        {{--                            data.errors[count],"error",--}}
        {{--                            { position:"right" }--}}
        {{--                        );--}}
        {{--                    }--}}
        {{--                }--}}
        {{--                if(data.success){--}}
        {{--                    $.notify(data.success, "success");--}}
        {{--                    $('#hubForm').trigger("reset");--}}
        {{--                    $('#ajaxModel').modal('hide');--}}
        {{--                    table.draw();--}}
        {{--                }--}}
        {{--            },--}}
        {{--        });--}}

        {{--    }--}}

        {{--});--}}
        {{--$(document).on('click','.deleteHub', function (data){--}}
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
        {{--                url: "{{ asset("hub/delete") }}/" + id,--}}
        {{--                success: function (data) {--}}
        {{--                    console.log(data)--}}
        {{--                    table.draw();--}}
        {{--                },--}}
        {{--                error: function (data) {--}}
        {{--                    console.log('Error:', data);--}}
        {{--                }--}}
        {{--            });--}}
        {{--            swal("Đã xóa!", "Your imaginary file has been deleted.", "success");--}}
        {{--        });--}}
        {{--});--}}
    });

</script>

<script>
        function editHub(id) {
            $.get('{{asset('hub/edit')}}/'+id,function (data) {
                $('#modelHeading').html("Edit - "+ (data.hubname));
                $('#saveBtn').val("edit-hub");
                $('#ajaxModel').modal('show');
                $('.modal').on('hidden.bs.modal', function (e) {
                    $('body').addClass('modal-open');
                });
                $('#id').val(data.id);
                $('#hubname').val(data.hubname);
                $('#cocsim').val(data.cocsim)
                $('#cocsim').select2();
            })
        }

        function checkbox(id) {
            $.get('{{asset('hub/checkbox')}}/'+id,function (data) {
                console.log(data)
                if(data.success){
                    $.notify(data.success, "success");
                }
                if(data.errors){
                    $.notify(data.errors,"error");
                }
            })
        }


        $("#checkAll").click(function(){
            var isCheckAll = $('#checkAll').is(":checked");
            console.log(isCheckAll)
            $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
            $.get('{{asset('hub/checkboxAll')}}/'+isCheckAll,function (data) {
                if(data.success){
                    $.notify(data.success, "success");
                }
                if(data.errors){
                    $.notify(data.errors,"error");
                }
            })

        });

    </script>
@endsection


