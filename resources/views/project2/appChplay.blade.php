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
    <h4 class="page-title">Market CHPlay</h4>
</div>
<div class="col-sm-6 ">
    <div class="float-right d-none d-md-block">
        <a class="btn btn-outline btn-info" href="../../cronProject/ch-play" >Cập nhật</a>
    </div>
</div>
<div class="modal fade bd-example-modal-xl" id="showMess" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeadingPolicy"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="message-full"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@include('modals.detailApps')
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="button-items status_app_button">
                        <button type="button" class="btn btn-primary waves-effect waves-light" id="All">All</button>
                        <button type="button" class="btn btn-success waves-effect" id="Public" >Public</button>
                        <button type="button" class="btn btn-warning waves-effect"id="Pending">Pending</button>
                        <button type="button" class="btn btn-danger waves-effect waves-light" id="Check">Check</button>
                        <button type="button" class="btn btn-info waves-effect waves-light" id="UbPublish">UbPublish</button>
                        <button type="button" class="btn btn-secondary waves-effect waves-light" id="Remove">Remove</button>
                        <button type="button" class="btn btn-dark waves-effect waves-light" id="Reject">Reject</button>
                        <button type="button" class="btn btn-link waves-effect waves-light" id="Suppend">Suppend</button>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered dt-responsive nowrap data-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Logo</th>
                            <th>Mã dự án</th>
                            <th>Install</th>
                            <th>Voters</th>
                            <th>Review</th>
                            <th>Score</th>
                            <th>Message</th>
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
<script src="plugins/select2/js/select2.min.js"></script>

{{--<script>--}}
{{--    $("#template").select2({});--}}
{{--    $("#ma_da").select2({});--}}
{{--    $("#buildinfo_store_name_x").select2({});--}}
{{--</script>--}}


<script type="text/javascript">

    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    var table = $('.data-table').DataTable({
            displayLength: 50,
            lengthMenu: [5, 10, 25, 50, 75, 100],
            serverSide: true,
            ajax: {
                url: "{{ route('project.getChplay') }}",
                type: "post",
                data: function (d){
                    return $.extend({},d,{
                        "status_app": $('.status_app_button').val(),
                    })
                }
            },
            columns: [
                {data: 'updated_at', name: 'updated_at',},
                {data: 'logo', name: 'logo',orderable: false},
                {data: 'ma_da', name: 'ma_da'},
                {data: 'Chplay_bot->installs', name: 'install'},
                {data: 'Chplay_bot->numberVoters', name: 'numberVoters'},
                {data: 'Chplay_bot->numberReviews', name: 'numberReviews'},
                {data: 'Chplay_bot->score', name: 'score'},
                {data: 'buildinfo_mess', name: 'buildinfo_mess',orderable: false},
                {data: 'status', name: 'status',orderable: false},
                {data: 'action', className: "text-center",name: 'action', orderable: false, searchable: false},
            ],
            columnDefs: [
                    {
                        "targets": [ 0],
                        "visible": false,
                        "searchable": false
                    },
                ],
            order: [[ 0, 'desc' ]],
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                if (aData.status.includes('Chưa Publish')) {
                    $('td', nRow).css('background-color', 'rgb(187 187 187 / 27%)');
                }

                if (aData.status.includes('Check')) {
                    $('td', nRow).css('background-color', 'rgb(250 68 68 / 30%)');
                }
                if (aData.status.includes('Public')) {
                    $('td', nRow).css('background-color', 'rgb(255 255 255)');
                }
                if (aData.status.includes('Reject')) {
                    $('td', nRow).css('background-color', 'rgb(255 0 0 / 45%)');
                }
                if (aData.status.includes('UnPublish')) {
                    $('td', nRow).css('background-color', 'rgb(56 164 248 / 69%)');
                }
                if (aData.status.includes('Remove')) {
                    $('td', nRow).css('background-color', 'rgb(255 0 0 / 45%)');
                }
                if (aData.status.includes('Pending')) {
                    $('td', nRow).css('background-color', 'rgb(253 222 114)');
                }
                if (aData.status.includes('Suppend')) {
                    $('td', nRow).css('background-color', 'rgb(255 0 0 / 72%)');
                }
            },
        });
    table.on('click', 'td:nth-child(7)', e=> {
        e.preventDefault();
        const row = table.row(e.target.closest('tr'));

        const rowData = row.data();
        console.log(rowData)

        $('#modelHeadingPolicy').html(rowData.name_projectname);
        $('#showMess').modal('show');
        // console.log(table.row(this).data())

        $('.message-full').html(rowData.full_mess);

    });
    $('#All').on('click', function () {
        $('.status_app_button').val(null);
        table.draw();
    });
    $('#Public').on('click', function () {
        $('.status_app_button').val('1');
        table.draw();
    });
    $('#Pending').on('click', function () {
        $('.status_app_button').val('7');
        table.draw();
    });
    $('#Check').on('click', function () {
        $('.status_app_button').val('6');
        table.draw();
    });

    $('#UbPublish').on('click', function () {
            $('.status_app_button').val('3');
            table.draw();
    });

    $('#Remove').on('click', function () {
            $('.status_app_button').val('4');
            table.draw();
    });

    $('#Reject').on('click', function () {
            $('.status_app_button').val('5');
            table.draw();
    });

    $('#Suppend').on('click', function () {
            $('.status_app_button').val('2');
            table.draw();
    });

    $('#detailApp').on('submit',function (event){
        event.preventDefault();
        var formData = new FormData($("#detailApp")[0]);
        if($('#saveBtn').val() == 'update'){
            $.ajax({
                // data: $('#detailApp').serialize(),
                data: formData,
                url: "{{ route('project.updateStatus') }}",
                type: "post",
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function (data) {

                    if(data.errors){
                        for( var count=0 ; count <data.errors.length; count++){
                            $("#detailApp").notify(
                                data.errors[count],"error",
                                { position:"right" }
                            );
                        }
                    }
                    if(data.success){
                        $.notify(data.success, "success");
                        $('#detailApp').trigger("reset");
                        $('#ajaxModel').modal('hide');
                        table.draw();
                    }
                },
            });
        }
    });


    function detailChplay(id) {
        $.get('{{asset('project/edit')}}/'+id,function (data) {
            $("#projectForm input").prop("disabled", true);
            if(data[4] == null) { data[4] = {template: "Chưa có template"}}
            if(data[3] == null) { data[3] = {ma_da: "Chưa có mã dự án"}}
            if(data[2] == null) { data[2] = {store_name: "Chưa có Store Name"}}
            var Chplay_ads = '';
            if(data[0].Chplay_ads) {
                Chplay_ads = data[0].Chplay_ads;
                Chplay_ads = JSON.parse(Chplay_ads);
            }
            if(data[0].logo) {
                $("#avatar").attr("src","../uploads/project/"+data[0].projectname+"/thumbnail/"+data[0].logo);
            }else {
                $("#avatar").attr("src","img/logo.png");
            }
            $('#project_id').val(data[0].projectid);
            $('#projectname').val(data[0].projectname);
            $('#template').val(data[4].template);
            $('#ma_da').val(data[3].ma_da);
            $('#title_app').val(data[0].title_app);
            $('#buildinfo_vernum').val(data[0].buildinfo_vernum);
            $('#buildinfo_verstr').val(data[0].buildinfo_verstr);
            $('#buildinfo_app_name_x').val(data[0].buildinfo_app_name_x);
            $('#buildinfo_keystore').val(data[0].buildinfo_keystore);
            $('#buildinfo_sdk').val(data[0].buildinfo_sdk);
            $('#buildinfo_link_policy_x').val(data[0].buildinfo_link_policy_x);
            $('#buildinfo_link_youtube_x').val(data[0].buildinfo_link_youtube_x);
            $('#buildinfo_link_fanpage').val(data[0].buildinfo_link_fanpage);
            $('#buildinfo_api_key_x').val(data[0].buildinfo_api_key_x);
            $('#buildinfo_link_website').val(data[0].buildinfo_link_website);


            $('#package').val(data[0].Chplay_package);
            $('#buildinfo_store_name_x').val(data[2].store_name);
            $('#ads_id').val(Chplay_ads.ads_id);
            $('#banner').val(Chplay_ads.ads_banner);
            $('#ads_inter').val(Chplay_ads.ads_inter);
            $('#ads_reward').val(Chplay_ads.ads_reward);
            $('#ads_native').val(Chplay_ads.ads_native);
            $('#ads_open').val(Chplay_ads.ads_open);

            $('#Chplay_buildinfo_link_store').val(data[0].Chplay_buildinfo_link_store);
            $('#Chplay_buildinfo_link_app').val(data[0].Chplay_buildinfo_link_app);
            $('#Chplay_buildinfo_email_dev_x').val(data[0].Chplay_buildinfo_email_dev_x);
            $('#Chplay_status').val(data[0].Chplay_status);
            $('#modelHeading').html("Chi tiết");
            $('#ajaxModel').modal('show');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });
        })
    }


</script>



@endsection


