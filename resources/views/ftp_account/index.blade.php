@extends('layouts.master')

@section('css')

<link href="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('assets/libs/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('assets/libs/toastr/ext-component-toastr.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css"/>

<!-- Select2 Js  -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />






@endsection

@section('content')
    @include('modals.ftp_account')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-rep-plugin" >
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="FtpAcountTable" class="table table-striped table-bordered dt-responsive data-table" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th style="width: 20%">Name</th>
                                    <th style="width: 20%">Server External</th>
                                    <th style="width: 20%">Server Internal</th>
                                    <th style="width: 15%">Account</th>
                                    <th style="width: 15%">Password </th>
                                    <th style="width: 10%">Action </th>
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
    </div>
 <!-- end row -->

@endsection
@section('script')

<!-- Plugins js -->
<script src="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/toastr/toastr.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/table.init.js') }}"></script>
<script src="{{ URL::asset('/assets/js/customs.js') }}"></script>




<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script type="text/javascript">
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#FtpAcountTable').DataTable({

            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('ftp_account.getIndex') }}",
                type: 'post',
            },
            columns: [
                {data: 'ftp_name', name: 'ftp_name'},
                {data: 'ftp_server', name: 'ftp_server'},
                {data: 'ftp_server_internal', name: 'ftp_server_internal'},
                {data: 'ftp_account', name: 'ftp_account'},
                {data: 'ftp_password', name: 'ftp_password'},
                {data: 'action', name: 'action'},

            ],
            columnDefs: [
                {
                    targets:1 ,
                    responsivePriority: 1,
                    render: function (data,display,full) {
                        if(full.status === 1){
                            // return data;
                            return '<a href="{{asset('ftp-account/show')}}/'+full.id+'?server_ftp='+full.ftp_server+'&port='+full.ftp_port+'"><span style="color: #00bb00" >'+full.ftp_server +':'+full.ftp_port+'</span></a>'

                        }else {
                            return '<span style="color: #e6183a" >'+full.ftp_server +':'+full.ftp_port+'</span>'
                        }
                    }
                },
                {
                    targets:2 ,
                    responsivePriority: 1,
                    render: function (data,display,full) {
                        if(full.status_internal === 1){
                            return '<a href="{{asset('ftp-account/show')}}/'+full.id+'?server_ftp='+full.ftp_server_internal+'&port='+full.ftp_port_internal+'"><span style="color: #00bb00" >'+full.ftp_server_internal +':'+full.ftp_port_internal+'</span></a>'
                        }else {
                            return '<span style="color: #e6183a" >'+full.ftp_server_internal +':'+full.ftp_port_internal+'</span>'
                        }
                    }
                },
            ],
            order: [ 1, 'desc' ]
        });

        $('#createNew').click(function () {
            $('#saveBtn').val("create-account");
            $('#ftp_id').val('');
            $('#ftp_accountForm').trigger("reset");
            $('#modelHeading').html("Thêm mới");
            $('#ftp_accountModel').modal('show');
            $('#saveBtn_check').hide();
        });

        $('#ftp_accountForm').on('submit',function (event){
            event.preventDefault();
            if($('#saveBtn').val() == 'create-account'){
                $.ajax({
                    data: $('#ftp_accountForm').serialize(),
                    url: "{{ route('ftp_account.create') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#ftp_accountForm").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#ftp_accountForm').trigger("reset");
                            $('#ftp_accountModel').modal('hide');
                            table.draw();
                        }
                    },
                });
            }
            if($('#saveBtn').val() == 'edit-account'){
                $.ajax({
                    data: $('#ftp_accountForm').serialize(),
                    url: "{{ route('ftp_account.update') }}",
                    type: "post",
                    dataType: 'json',
                    success: function (data) {
                        if(data.errors){
                            for( var count=0 ; count <data.errors.length; count++){
                                $("#ftp_accountForm").notify(
                                    data.errors[count],"error",
                                    { position:"right" }
                                );
                            }
                        }
                        if(data.success){
                            $.notify(data.success, "success");
                            $('#ftp_accountForm').trigger("reset");
                            $('#ftp_accountModel').modal('hide');
                            table.draw();
                        }
                    },
                });
            }
        });

        $('#ftp_server, #ftp_port, #ftp_account, #ftp_password ').change( function() {
            if ( $('#ftp_server').val()!="" && $('#ftp_port').val()!="" && $('#ftp_account').val()!="" && $('#ftp_password').val()!=""   ){
                $('#saveBtn_check').show();
            }else {
                $('#saveBtn_check').hide();
            }

        });

        $(document).on('click','#saveBtn_check', function (data){
            $.ajax({
                data: $('#ftp_accountForm').serialize(),
                url: "{{ route('ftp_account.check') }}",
                type: "post",
                dataType: 'json',
                success: function (data) {
                    if(data.success){
                        $.notify(data.success, "success");
                        $('#ftp_accountForm').trigger("reset");
                        $('#ftp_accountModel').modal('hide');
                        table.draw();
                    }
                    if(data.error){
                        $.notify(data.error, "error");
                    }
                }
            });

        });
        $(document).on('click','.editFtpAccount', function (data){
            var _id = $(this).data('id');

            $('#modelHeading').html("Edit");
            $('#saveBtn').val("edit-account");
            $('#ftp_accountModel').modal('show');
            $('#saveBtn_check').show();
            $.ajax({
                data: $('#ftp_accountForm').serialize(),
                url: "{{ asset("ftp-account/edit") }}/" + _id,
                type: "get",
                dataType: 'json',
                success: function (data) {
                    $('#ftp_id').val(data.id);
                    $('#ftp_name').val(data.ftp_name);
                    $('#ftp_server').val(data.ftp_server);
                    $('#ftp_port').val(data.port);
                    $('#ftp_server_internal').val(data.ftp_server_internal);
                    $('#ftp_port_internal').val(data.ftp_port_internal);
                    $('#ftp_account').val(data.ftp_account);
                    $('#ftp_password').val(data.ftp_password);
                    $('#ftp_note').val(data.ftp_note);
                }
            });

        });
        $(document).on('click','.deleteFtpAccount', function (data){
            var _id = $(this).data("id");
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
                        url: "{{ asset("ftp-account/delete") }}/" + _id,
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


