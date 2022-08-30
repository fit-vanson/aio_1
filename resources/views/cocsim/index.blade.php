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
    <h4 class="page-title">Quản lý tài khoản</h4>
</div>
<div class="col-sm-6">
    <div class="float-right">
        @can('cocsim-add')
        <a class="btn btn-success" href="javascript:void(0)" id="createNewCocsim"> Thêm mới</a>
        @endcan
    </div>
</div>
@include('modals.cocsim')
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
                            <th>Tên cọc sim</th>
                            <th>Time</th>
                            <th>Ghi chú</th>
                            <th width="20px">Action</th>
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
    <script src="plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
    <script src="plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script src="plugins/select2/js/select2.min.js"></script>
    <script src="plugins/bootstrap-maxlength/bootstrap-maxlength.min.js"></script>
    <script src="plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js"></script>
    <script src="plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js"></script>
    <!-- Plugins Init js -->
    <script src="assets/pages/form-advanced.js"></script>





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
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],

            processing: true,
            serverSide: true,
            ajax: "{{ route('cocsim.index') }}",
            columns: [
                { "data": null,"sortable": true,
                    render: function (data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },

                {data: 'cocsim', name: 'cocsim'},
                {data: 'time', name: 'time'},
                {data: 'note', name: 'note'},

                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        $('#createNewCocsim').click(function () {
            $('#saveBtn').val("create-cocsim");
            $('#id').val('');
            $('#cocsimForm').trigger("reset");
            $('#modelHeading').html("Thêm mới");
            $('#ajaxModel').modal('show');
        });
        $('#cocsimForm').on('submit',function (event){
            event.preventDefault();
            if($('#saveBtn').val() == 'create-cocsim'){
                $.ajax({
                    data: $('#cocsimForm').serialize(),
                    url: "{{ route('cocsim.create') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#cocsimForm").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#cocsimForm').trigger("reset");
                            $('#ajaxModel').modal('hide');
                            table.draw();
                        }
                    },
                });
            }
            if($('#saveBtn').val() == 'edit-cocsim'){
                $.ajax({
                    data: $('#cocsimForm').serialize(),
                    url: "{{ route('cocsim.update') }}",
                    type: "post",
                    dataType: 'json',
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#cocsimForm").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#cocsimForm').trigger("reset");
                            $('#ajaxModel').modal('hide');
                            table.draw();
                        }
                    },
                });

            }

        });
        $(document).on('click','.deleteCocsim', function (data){
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
                        url: "{{ asset("cocsim/delete") }}/" + id,
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
    function editCocsim(id) {

        $.get('{{asset('cocsim/edit')}}/'+id,function (data) {
            $('#modelHeading').html("Edit");
            $('#saveBtn').val("edit-cocsim");
            $('#ajaxModel').modal('show');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });




            $('#id').val(data.id);
            $('#cocsim').val(data.cocsim);
            $('#note').val(data.note)
            var phones = data.khosim;
            var phone = [];
            var phone_id = [];

            $.each(phones, function(idx2,val2) {

                var phone_val =  val2.phone;

                var phone_id_val =  val2.id;
                phone.push(phone_val);
                phone_id.push(phone_id_val);
                <?php
                for($i=1; $i<=15;$i++) { ?>
                $('#phone_<?php echo $i;?>').val(phone[<?php echo $i-1;?>])
                $('#phone_id_<?php echo $i;?>').val(phone_id[<?php echo $i-1;?>])
                <?php }?>
            });
        })
    }
</script>
@endsection


