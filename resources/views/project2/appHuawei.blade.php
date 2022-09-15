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
    <h4 class="page-title">Market Huawei</h4>
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
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered dt-responsive nowrap data-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th >ID</th>
                            <th width="10%">Logo</th>
                            <th width="20%">Mã dự án</th>
                            <th width="20%">Installs</th>
                            <th width="30%">Package</th>
                            <th width="30%">Trạng thái Console</th>
                            <th width="10%">Action</th>
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
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('.data-table').DataTable({
            displayLength: 50,
            lengthMenu: [5, 10, 25, 50, 75, 100],
            // processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('project.getHuawei') }}",
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
                {data: 'install', name: 'install',orderable: false},
                {data: 'package', name: 'package',orderable: false},
                // {data: 'buildinfo_mess', name: 'buildinfo_mess',orderable: false},
                {data: 'Huawei_bot->time_bot', name: 'Huawei_bot->time_bot'},
                {data: 'action',className: "text-center", name: 'action', orderable: false, searchable: false},
            ],
            columnDefs: [
                {
                    "targets": [ 0 ],
                    "visible": false,
                    "searchable": false
                }
            ],
            fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                if (aData['Huawei_bot->time_bot'].includes('Released')) {
                    $('td', nRow).css('background-color', 'rgb(255 255 255)');
                }
                if (aData['Huawei_bot->time_bot'].includes('Release Rejected')) {
                    $('td', nRow).css('background-color', 'rgb(250 68 68 / 30%)');
                }
                if (aData['Huawei_bot->time_bot'].includes('Removed (including forcible removal)')) {
                    $('td', nRow).css('background-color', 'rgb(255 255 255)');
                }
                if (aData['Huawei_bot->time_bot'].includes('Releasing')) {
                    $('td', nRow).css('background-color', 'rgb(255 0 0 / 45%)');
                }
                if (aData['Huawei_bot->time_bot'].includes('Reviewing')) {
                    $('td', nRow).css('background-color', 'rgb(56 164 248 / 69%)');
                }
                if (aData['Huawei_bot->time_bot'].includes('Updating')) {
                    $('td', nRow).css('background-color', 'rgb(255 0 0 / 45%)');
                }
                if (aData['Huawei_bot->time_bot'].includes('Removal requested')) {
                    $('td', nRow).css('background-color', 'rgb(227 211 66 / 36%)');
                }
                if (aData['Huawei_bot->time_bot'].includes('Draft')) {
                    $('td', nRow).css('background-color', 'rgb(255 0 0 / 72%)');
                }
                if (aData['Huawei_bot->time_bot'].includes('Update rejected')) {
                    $('td', nRow).css('background-color', 'rgb(227 211 66 / 36%)');
                }
                if (aData['Huawei_bot->time_bot'].includes('Removal requested')) {
                    $('td', nRow).css('background-color', 'rgb(227 211 66 / 36%)');
                }
                if (aData['Huawei_bot->time_bot'].includes('Removed by developer')) {
                    $('td', nRow).css('background-color', 'rgb(255 0 0 / 72%)');
                }
                if (aData['Huawei_bot->time_bot'].includes('Release canceled')) {
                    $('td', nRow).css('background-color', 'rgb(255 0 0 / 72%)');
                }
            },
            order: [[ 0, 'desc' ]]
        });

        $('#All').on('click', function () {
            $('.status_app_button').val(null);
            table.draw();
        });
        $('#Public').on('click', function () {
            $('.status_app_button').val('0,100,3');
            table.draw();
        });
        $('#Pending').on('click', function () {
            $('.status_app_button').val('2,4,5,6,8');
            table.draw();
        });
        $('#Check').on('click', function () {
            $('.status_app_button').val('1,7,9,10,11');
            table.draw();
        });
    });
    function detailHuawei(id) {
        $.get('{{asset('project/edit')}}/'+id,function (data) {
            $("#projectForm input").prop("disabled", true);
            if(data[4] == null) { data[4] = {template: "Chưa có template"}}
            if(data[3] == null) { data[3] = {ma_da: "Chưa có mã dự án"}}
            if(data[2] == null) { data[2] = {store_name: "Chưa có Store Name"}}
            var Huawei_ads = '';
            if(data[0].Huawei_ads) {
                Huawei_ads = data[0].Huawei_ads;
                Huawei_ads = JSON.parse(Huawei_ads);
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


            $('#package').val(data[0].Huawei_package);
            $('#buildinfo_store_name_x').val(data[2].store_name);
            $('#ads_id').val(Huawei_ads.ads_id);
            $('#banner').val(Huawei_ads.ads_banner);
            $('#ads_inter').val(Huawei_ads.ads_inter);
            $('#ads_reward').val(Huawei_ads.ads_reward);
            $('#ads_native').val(Huawei_ads.ads_native);
            $('#ads_open').val(Huawei_ads.ads_open);

            $('#Huawei_buildinfo_link_store').val(data[0].Huawei_buildinfo_link_store);
            $('#Huawei_buildinfo_link_app').val(data[0].Huawei_buildinfo_link_app);
            $('#Huawei_buildinfo_email_dev_x').val(data[0].Huawei_buildinfo_email_dev_x);
            $('#Huawei_status').val(data[0].Huawei_status);
            $('#modelHeading').html("Chi tiết");
            $('#ajaxModel').modal('show');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });
        })
    }
</script>



@endsection


