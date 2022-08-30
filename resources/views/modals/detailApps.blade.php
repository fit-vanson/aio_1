
<div class="modal fade bd-example-modal-xl" id="ajaxModel" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="detailApp" name="detailApp" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" disabled="" name="project_id" id="project_id">
                    <input type="hidden" name="buildinfo_console" id="buildinfo_console">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Tab panes -->
                                    <div class="tab-content" >
                                        <div data-repeater-item="" class="row">
                                            <div class="form-group col-lg-4">
                                                <label>Logo</label>
                                                <input  id="logo" type="file" name="logo" class="form-control" disabled hidden>
                                                <img id="avatar" class="thumbnail" width="100px" src="img/logo.png">
                                            </div>
                                            <div class="col-lg-6">
                                                <h4 class="mt-0 header-title text-right">Old Status: </h4>
                                            </div>
                                            <div class="col-lg-2" id="log_status">
                                            </div>
                                        </div>
                                        <div data-repeater-item="" class="row">
                                            <div class="form-group col-lg-4">
                                                <label for="name">Mã dự án </label>
                                                <input type="text" id="ma_da" name="ma_da" class="form-control">
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <label for="name">Mã template</label>
                                                <input type="text" id="template" name="template" class="form-control">
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <label for="name">Mã Project </label>
                                                <input type="text" id="projectname" name="projectname" class="form-control">
                                            </div>
                                        </div>
                                        <div data-repeater-item="" class="row">
                                            <div class="form-group col-lg-4 input_title_app">
                                                <label for="name">Tiêu đề ứng dụng </label>
                                                <input type="text" id="title_app" name="title_app" class="form-control">
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <label for="name">Version Number</label>
                                                <input type="number" id="buildinfo_vernum" name="buildinfo_vernum" class="form-control" >
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <label for="name">Version String</label>
                                                <input type="text" id="buildinfo_verstr" name="buildinfo_verstr" class="form-control" >
                                            </div>
                                        </div>
                                        <div data-repeater-item="" class="row">

                                        </div>
                                        <div data-repeater-item="" class="row input_buildinfo">

                                            <div class="form-group col-lg-4 ">
                                                <label for="name">App Name (APP_NAME_X)</label>
                                                <input type="text" id="buildinfo_app_name_x" name="buildinfo_app_name_x" class="form-control" >
                                            </div>
                                            <div class="form-group col-lg-4 ">
                                                <label for="name">Keystore Profile </label>
                                                <input type="text" id="buildinfo_keystore" name="buildinfo_keystore" class="form-control" >
                                            </div>
                                            <div class="form-group col-lg-4 ">
                                                <label for="name">SDK </label>
                                                <input type="text" id="buildinfo_sdk" name="buildinfo_sdk" class="form-control" >
                                            </div>
                                        </div>

                                        <div data-repeater-item="" class="row input_buildinfo">

                                            <div class="form-group col-lg-4 ">
                                                <label for="name">Link Policy</label>
                                                <input type="text" id="buildinfo_link_policy_x" name="buildinfo_link_policy_x" class="form-control" >
                                            </div>
                                            <div class="form-group col-lg-4 ">
                                                <label for="name">Link Youtube </label>
                                                <input type="text" id="buildinfo_link_youtube_x" name="buildinfo_link_youtube_x" class="form-control" >
                                            </div>
                                            <div class="form-group col-lg-4 ">
                                                <label for="name">Link Fanpage </label>
                                                <input type="text" id="buildinfo_link_fanpage" name="buildinfo_link_fanpage" class="form-control" >
                                            </div>


                                        </div>
                                        <div data-repeater-item="" class="row input_buildinfo">
                                            <div class="form-group col-lg-6 ">
                                                <label for="name">Key API APP</label>
                                                <input type="text" id="buildinfo_api_key_x" name="buildinfo_api_key_x" class="form-control" >
                                            </div>
                                            <div class="form-group col-lg-6 ">
                                                <label for="name">Link Website</label>
                                                <input type="text" id="buildinfo_link_website" name="buildinfo_link_website" class="form-control" >
                                            </div>
                                        </div>
                                        <div data-repeater-item="" class="row input_buildinfo">
                                        </div>
                                        <div data-repeater-item="" class="row">
                                            <div class="form-group col-lg-6 input_package">
                                                <label for="name">Tên Package</label>
                                                <input type="text" id="package" name="package" class="form-control">
                                            </div>
                                            <div class="form-group col-lg-6">
                                                <label for="name">Store Name  </label>
                                                <input type="text" id="buildinfo_store_name_x" name="buildinfo_store_name_x" class="form-control">
                                            </div>
                                        </div>
                                        <div data-repeater-item="" class="row input_buildinfo">
                                            <div class="form-group col-lg-6 ">
                                                <label for="name">Link Store </label>
                                                <input type="text" id="Chplay_buildinfo_link_store" name="Chplay_buildinfo_link_store" class="form-control" >
                                            </div>
                                            <div class="form-group col-lg-6 input_buildinfo_keystore">
                                                <label for="name">Link App</label>
                                                <input type="text" id="Chplay_buildinfo_link_app" name="Chplay_buildinfo_link_app" class="form-control" >
                                            </div>
                                        </div>
                                        <div data-repeater-item="" class="row">
                                            <div class="form-group col-lg-6 input_buildinfo_keystore">
                                                <label for="name">Email Dev</label>
                                                <input type="text" id="Chplay_buildinfo_email_dev_x" name="Chplay_buildinfo_email_dev_x" class="form-control" >
                                            </div>
                                            <div class="form-group col-lg-6 input_status">
                                                <label for="name">Trạng thái Ứng dụng</label>
                                                <div>
                                                    <select class="form-control" id="Chplay_status" name="Chplay_status">
                                                        <option value="0">Mặc định</option>
                                                        <option value="1">Publish</option>
                                                        <option value="2">Suppend</option>
                                                        <option value="3">UnPublish</option>
                                                        <option value="4">Remove</option>
                                                        <option value="5">Reject</option>
                                                        <option value="6">Check</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div data-repeater-item="" class="row">
                                            <div class="form-group col-lg-4">
                                                <label for="name">Ads Id</label>
                                                <input type="text" id="ads_id" name="ads_id" class="form-control" >
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <label for="name">Ads banner</label>
                                                <input type="text" id="banner" name="banner" class="form-control" >
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <label for="name">Ads inter</label>
                                                <input type="text" id="ads_inter" name="ads_inter" class="form-control" >
                                            </div>
                                        </div>

                                        <div data-repeater-item="" class="row">
                                            <div class="form-group col-lg-4">
                                                <label for="name">Ads reward</label>
                                                <input type="text" id="ads_reward" name="ads_reward" class="form-control" >
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <label for="name">Ads native</label>
                                                <input type="text" id="ads_native" name="ads_native" class="form-control" >
                                            </div>
                                            <div class="form-group col-lg-4">
                                                <label for="name">Ads Open</label>
                                                <input type="text" id="ads_open" name="ads_open" class="form-control" >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="update">Save changes
                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-xl" id="ajaxQuickModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelQuickHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="projectQuickForm" name="projectQuickForm" class="form-horizontal">
                    <input type="hidden" name="project_id" id="quick_project_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Version Number</label>
                        <div class="col-sm-12">
                            <input type="number" id="quick_buildinfo_vernum" name="buildinfo_vernum" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Version String</label>
                        <div class="col-sm-12">
                            <input type="text" id="quick_buildinfo_verstr" name="buildinfo_verstr" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Trạng thái Console</label>
                        <div class="col-sm-12">
                            <select class="form-control" id="quick_buildinfo_console" name="buildinfo_console">
                                <option value="0">Trạng thái tĩnh</option>
                                <option value="1">Build App</option>
                                <option value="2" hidden>Đang xử lý Build App</option>
                                <option value="3" hidden >Kết thúc Build App</option>
                                <option value="4">Check Data Project</option>
                                <option value="5"hidden>Đang xử lý check dữ liệu của Project</option>
                                <option value="6"hidden>Kết thúc Check</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="saveQBtn" value="create">Save changes
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


{{--<div class="modal fade" id="addMaDa" tabindex="-1" role="dialog">--}}
{{--    <div class="modal-dialog">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <h5 class="modal-title" id="exampleModalLabel">Thêm mới Mã dự án</h5>--}}
{{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
{{--            </div>--}}
{{--            <div class="modal-body">--}}

{{--                <form id="AddDaForm" name="AddDaForm" class="form-horizontal">--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="name" class="col-sm-5 control-label">Tên dự án</label>--}}
{{--                        <div class="col-sm-12">--}}
{{--                            <input type="hidden" name="da_id" id="add_da_id">--}}
{{--                            <input type="text" class="form-control" id="add_ma_da" name="ma_da" placeholder="Mã dự án" required>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-sm-offset-2 col-sm-10">--}}
{{--                        <button type="submit" class="btn btn-primary">Thêm mới</button>--}}
{{--                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
{{--                    </div>--}}
{{--                </form>--}}

{{--            </div>--}}

{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}


{{--<div class="modal fade" id="addTemplate" tabindex="-1" role="dialog">--}}
{{--    <div class="modal-dialog">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <h5 class="modal-title" id="exampleModalLabel">Thêm mới Template</h5>--}}
{{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
{{--            </div>--}}
{{--            <div class="modal-body">--}}

{{--                <form id="AddTempForm" name="AddTempForm" class="form-horizontal">--}}
{{--                    <input type="hidden" name="template_id" id="template_id">--}}

{{--                        <div data-repeater-item="" class="row">--}}
{{--                            <div class="form-group col-lg-12 ">--}}
{{--                                <label for="name">Tên Template <span style="color: red">*</span></label>--}}
{{--                                <input type="text" id="add_template" name="template" class="form-control" required>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                    <div class="col-sm-offset-2 col-sm-10">--}}
{{--                        <button type="submit" class="btn btn-primary">Thêm mới</button>--}}
{{--                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
{{--                    </div>--}}
{{--                </form>--}}

{{--            </div>--}}

{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

{{--<div class="modal fade bd-example-modal-xl" id="showPolicy" aria-hidden="true">--}}
{{--    <div class="modal-dialog modal-lg">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <h4 class="modal-title" id="modelHeadingPolicy"></h4>--}}
{{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
{{--            </div>--}}
{{--            <div class="modal-body">--}}
{{--                <div class="policy-1">--}}
{{--                    <label for="name">Policy 1 <button type="button" onclick="copy1()" class="btn btn-link waves-effect copyboard"><i class="mdi mdi-content-copy"></i></button> </label>--}}
{{--                    <textarea  type="text" id="policy1"  name="policy1" rows="8" class="form-control" > </textarea>--}}
{{--                </div>--}}
{{--                <div class="policy-2">--}}
{{--                    <label  for="name">Policy 2 <button type="button" onclick="copy2()" class="btn btn-link waves-effect copyboard"><i class="mdi mdi-content-copy"></i></button></label>--}}
{{--                    <textarea  type="text" id="policy2"  name="policy2" rows="8" class="form-control" > </textarea>--}}

{{--                </div>--}}

{{--            </div>--}}
{{--            <div class="modal-footer">--}}
{{--                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}











