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
    <h4 class="page-title">Market Xiaomi</h4>
</div>
@include('modals.detailApps')
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered dt-responsive nowrap data-table" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th >ID</th>
                            <th>Logo</th>
                            <th>Mã dự án</th>
                            <th>Package</th>
                            <th>Trạng thái Console</th>
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
                url: "{{ route('project.getXiaomi') }}",
                type: "post"
            },
            columns: [
                {data: 'updated_at', name: 'updated_at',},
                {data: 'logo', name: 'logo',orderable: false},
                {data: 'ma_da', name: 'ma_da'},
                {data: 'package', name: 'package',orderable: false},
                // {data: 'buildinfo_mess', name: 'buildinfo_mess',orderable: false},
                {data: 'buildinfo_console', name: 'buildinfo_console',orderable: false},
                {data: 'action', className: "text-center",name: 'action', orderable: false, searchable: false},
            ],
            columnDefs: [
                {
                    "targets": [ 0 ],
                    "visible": false,
                    "searchable": false
                }
            ],
            order: [[ 0, 'desc' ]]
        });
    });
    function detailXiaomi(id) {
        $.get('{{asset('project/edit')}}/'+id,function (data) {
            $("#projectForm input").prop("disabled", true);
            if(data[4] == null) { data[4] = {template: "Chưa có template"}}
            if(data[3] == null) { data[3] = {ma_da: "Chưa có mã dự án"}}
            if(data[2] == null) { data[2] = {store_name: "Chưa có Store Name"}}
            var Xiaomi_ads = '';
            if(data[0].Xiaomi_ads) {
                Xiaomi_ads = data[0].Xiaomi_ads;
                Xiaomi_ads = JSON.parse(Xiaomi_ads);
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


            $('#package').val(data[0].Xiaomi_package);
            $('#buildinfo_store_name_x').val(data[2].store_name);
            $('#ads_id').val(Xiaomi_ads.ads_id);
            $('#banner').val(Xiaomi_ads.ads_banner);
            $('#ads_inter').val(Xiaomi_ads.ads_inter);
            $('#ads_reward').val(Xiaomi_ads.ads_reward);
            $('#ads_native').val(Xiaomi_ads.ads_native);
            $('#ads_open').val(Xiaomi_ads.ads_open);

            $('#Xiaomi_buildinfo_link_store').val(data[0].Xiaomi_buildinfo_link_store);
            $('#Xiaomi_buildinfo_link_app').val(data[0].Xiaomi_buildinfo_link_app);
            $('#Xiaomi_buildinfo_email_dev_x').val(data[0].Xiaomi_buildinfo_email_dev_x);
            $('#Xiaomi_status').val(data[0].Xiaomi_status);
            $('#modelHeading').html("Chi tiết");
            $('#ajaxModel').modal('show');
            $('.modal').on('hidden.bs.modal', function (e) {
                $('body').addClass('modal-open');
            });
        })
    }
</script>



@endsection


