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

@endsection
@section('content')


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">


                    <div class="table-rep-plugin" >
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="gadevTable" class="table table-striped table-bordered dt-responsive data-table"
                                   style="width: 100%;">
                                <thead>
                                <tr>
                                    <th style="display: none" >ID</th>
                                    <th style="width: 20%">Gmail</th>
                                    <th style="width: 10%">Pass (DES Encrypt)</th>
                                    <th style="width: 20%">Gmail Recover</th>
                                    <th style="width: 10%">VPN</th>
                                    <th style="width: 10%"><div class="truncate">Backup Code (DES Encrypt)</div></th>
                                    <th style="width: 20%">Ghi chú</th>
                                    <th style="width: 10%">Action</th>
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
    @include('modals.gadev')
@endsection
@section('script')
    <!-- Plugins js -->
    <script src="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>

    <script src="{{ URL::asset('/assets/libs/toastr/toastr.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/table.init.js') }}"></script>

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
            var table = $('#gadevTable').DataTable({
                displayLength: 50,
                lengthMenu: [5, 10, 25, 50, 75, 100],
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('gadev.getIndex') }}",
                    type: "post"
                },
                columns: [
                    {data: 'id',visible: false},
                    {data: 'gmail',className: "copyButton"},
                    {data: 'pass',className: "copyButton"},
                    {data: 'mailrecovery',className: "copyButton"},
                    {data: 'vpn_iplogin',className: "copyButton"},
                    {data: 'backupcode',className: "copyButton truncate"},
                    {data: 'note'},
                    {data: 'action',className: "text-center", name: 'action', orderable: false, searchable: false},
                ],
                order:[0,'desc'],

            });

            $('#createNewGadev').click(function () {
                $('#saveBtn').val("create-gedev");
                $('#gedev_id').val('');
                $('#gadevForm').trigger("reset");
                $('#modelHeading').html("Thêm mới");
                $('#ajaxModel').modal('show');
            });
            $('#gadevForm').on('submit',function (event){
                event.preventDefault();
                if($('#saveBtn').val() == 'create-gedev'){
                    $.ajax({
                        data: $('#gadevForm').serialize(),
                        url: "{{ route('gadev.create') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    $("#gadevForm").notify(
                                        data.errors[count],"error",
                                        { position:"right" }
                                    );
                                }
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#gadevForm').trigger("reset");
                                $('#ajaxModel').modal('hide');
                                table.draw();
                            }
                        },
                    });
                }
                if($('#saveBtn').val() == 'edit-gedev'){
                    $.ajax({
                        data: $('#gadevForm').serialize(),
                        url: "{{ route('gadev.update') }}",
                        type: "post",
                        dataType: 'json',
                        success: function (data) {
                            if(data.errors){
                                for( var count=0 ; count <data.errors.length; count++){
                                    $("#gadevForm").notify(
                                        data.errors[count],"error",
                                        { position:"right" }
                                    );
                                }
                            }
                            if(data.success){
                                $.notify(data.success, "success");
                                $('#gadevForm').trigger("reset");
                                $('#ajaxModel').modal('hide');
                                table.draw();
                            }
                        },
                    });
                }

            });
            $(document).on('click','.editGadev', function (data){
                var gadev_id = $(this).data('id');
                $('#modelHeading').html("Edit");
                $('#saveBtn').val("edit-gedev");
                $('#ajaxModel').modal('show');
                $.ajax({
                    data: $('#gadevForm').serialize(),
                    url: "{{ asset("ga_dev/edit") }}/" + gadev_id,
                    type: "get",
                    dataType: 'json',
                    success: function (data) {
                        $('#gadev_id').val(data.id);
                        $('#gmail').val(data.gmail);
                        $('#mailrecovery').val(data.mailrecovery);
                        $('#pass').val(data.pass);
                        $('#vpn_iplogin').val(data.vpn_iplogin);
                        $('#note').val(data.note);
                        $('#backupcode').val(data.backupcode);
                    }
                });

            });

            $(document).on('click','.deleteGadev', function (data){
                var gadev_id = $(this).data("id");
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
                            url: "{{ asset("ga_dev/delete") }}/" + gadev_id,
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



@endsection






