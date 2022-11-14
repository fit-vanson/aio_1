<?php
$markets = \App\Models\Markets::all();
?>
<div class="modal fade bd-example-modal-xl" id="ajaxModel"  role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="projectForm" name="projectForm" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="project_id" id="project_id">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">

                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs nav-tabs-custom" role="tablist" id="nav_tabs_market" >
                                        <li class="nav-item " role="presentation">
                                            <a class="nav-link active" data-toggle="tab" href="#tab_home" role="tab" id="nav_link_home">
                                                <span class="d-none d-sm-block">Home</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div class="tab-pane active p-3" id="tab_home" role="tabpanel">
                                            <div class="row">
                                                <div class="form-group col-lg-4">
                                                    <label for="name">Mã dự án <span style="color: red">*</span></label>
                                                    <select class="form-control choose_da" id="ma_da" name="ma_da"></select>
                                                </div>
                                                <div class="form-group col-lg-4">
                                                    <label for="name">Mã template <span style="color: red">*</span></label>
                                                    <select class="form-control choose_template" id="template" name="template"></select>
                                                </div>
                                                <div class="form-group col-lg-4">
                                                    <label for="name">Mã Project <span style="color: red">*</span></label>
                                                        <input type="text" hidden id="project_da_name" name="project_da_name" class="form-control"/>
                                                        <input type="text" id="projectname" name="projectname" class="form-control" required />


                                                </div>
                                                <div class="form-group col-lg-4 ">
                                                    <label for="name">Tiêu đề ứng dụng  </label>
                                                    <input type="text" id="title_app" name="title_app" class="form-control"/>
                                                </div>
                                                <div class="form-group col-lg-4">
                                                    <label for="name">Version Number <span style="color: red">*</span></label>
                                                    <input type="number" id="buildinfo_vernum" name="buildinfo_vernum" class="form-control" required />
                                                </div>
                                                <div class="form-group col-lg-4">
                                                    <label for="name">Version String<span style="color: red">*</span></label>
                                                    <input type="text" id="buildinfo_verstr" name="buildinfo_verstr" class="form-control" required />
                                                </div>

                                                <div class="form-group col-lg-4 ">
                                                    <label for="name">Link Fanpage </label>
                                                    <input type="text" id="buildinfo_link_fanpage" name="buildinfo_link_fanpage" class="form-control" >
                                                </div>

                                                <div class="form-group col-lg-4 ">
                                                    <label for="name">Key API APP</label>
                                                    <input type="text" id="buildinfo_api_key_x" name="buildinfo_api_key_x" class="form-control" >
                                                </div>
                                                <div class="form-group col-lg-4 ">
                                                    <label for="name">Link Website</label>
                                                    <input type="text" id="buildinfo_link_website" name="buildinfo_link_website" class="form-control" >
                                                </div>


                                                <div class="form-group col-lg-4">
                                                    <label class="d-block ">Data on/off :</label>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="data_online" name="data_status" class="custom-control-input" value="1">
                                                        <label class="custom-control-label" for="data_online">Online</label>
                                                    </div>
                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="data_offline" name="data_status" class="custom-control-input" value="2">
                                                        <label class="custom-control-label" for="data_offline">Offline</label>
                                                    </div>

                                                    <div class="custom-control custom-radio custom-control-inline">
                                                        <input type="radio" id="data_all" name="data_status" class="custom-control-input" value="3">
                                                        <label class="custom-control-label" for="data_all">All</label>
                                                    </div>
                                                </div>
                                                <div class="form-group col-lg-4">
                                                    <label for="name">File</label><p></p>
                                                    <input type="file" name="project_file" id="project_file" class="filestyle" data-buttonname="btn-secondary" accept=".zip">
                                                </div>
                                                <div class="form-group col-lg-4">
                                                    <label>Logo</label><p></p>
                                                    <input  id="logo" type="file" name="logo" class="form-control" hidden onchange="changeImg(this)" accept="image/*">
                                                    <img id="avatar" class="thumbnail" width="50px" src="img/logo.png">
                                                </div>
                                            </div>
                                            <div class="progress m-b-10" style="height: 3px;">
                                                <div class="progress-bar"  role="progressbar" style="background-color: #0b0b0b;width: 100%;"></div>
                                            </div>

                                            <div class="package_ads" id="package_ads">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="saveBtn">Save changes</button>
{{--                            <button type="submit" class="btn btn-secondary" id="saveBtnCopy">Copy Project</button>--}}
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
                                <option value="5" hidden>Đang xử lý check dữ liệu của Project</option>
                                <option value="6" hidden>Kết thúc Check</option>
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

<div class="modal fade bd-example-modal-xl" id="buildcheckModel" runat="server" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Build and Check</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <form class="repeater" id="buildcheckForm" enctype="multipart/form-data">

                                        <div class="row">
                                            <div class="form-group col-lg-4">
                                                <p for="name">Mã Project</p>
                                                <textarea id="projectname" name="projectname" onchange="getIndex(this)" rows="20" tyle="width: 100%"></textarea>
                                            </div>
                                            <div class="form-group col-lg-8">
                                                <p for="email">VersionCode</p>
                                                <textarea  id="buildinfo_vernum" name="buildinfo_vernum" rows="20" style="width: 100%"></textarea>
                                            </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary"  value="build" >Build</button>
                                    <button type="submit" class="btn btn-warning"  value="check" >Check</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
{{--                </form>--}}
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="dev_statusModel" runat="server" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Dev and Status</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <form class="repeater" id="dev_statusForm" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <p for="name">Mã Project</p>
                                            <textarea id="project_data" name="project_data" rows="35" style="width: 100%"></textarea>
                                        </div>

                                        <div class="form-group col-lg-8">

                                            @foreach($markets as $market)
                                            <div class="divider">
                                                <div class="divider-text"><img src="img/icon/{{$market->market_logo}}" width="24"></div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">Store Name {{$market->market_name}} </label>
                                                    <select class="form-control select2" id="_{{$market->market_name}}_dev_id"  name="market[{{$market->id}}][dev_id]">
                                                        <option value="0" >---Vui lòng chọn---</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">Trạng thái {{$market->market_name}}</label>
                                                    <div>
                                                        <select class="form-control"  name="market[{{$market->id}}][status_app]">
                                                            <option value="0">Mặc định</option>
                                                            <option value="1">Publish</option>
                                                            <option value="6">Check</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary"  value="build" >Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="changeMultiple" runat="server" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="changeMultipleTitle"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <form id="changeMultipleForm">
                                    <div class="col-lg-12" id="market_upload" style="display: none">
                                        <h3 class="mt-0 header-title">Market</h3>
                                        <div class="row ">
                                            <div class="form-group col-lg-2">
                                                <span class="text-muted m-l-30 ">All Market</span>
                                                <input type="checkbox" class="control-input" id="Check_all">
                                            </div>
                                            @foreach($markets as $market)
                                            <div class="form-group col-lg-2">
                                                <span class="text-muted m-l-30 ">{{$market->market_name}}</span>
                                                <input type="checkbox" class="control-input cb-element" name="market_upload[]" value="{{$market->id}}" required>
                                            </div>
                                            @endforeach
                                        </div>
                                        <h3 class="mt-0 header-title">Trạng thái</h3>
                                        <div class="row ">
                                            <?php
                                            $status_upload = ['Mặc đinh', 'Upload','Update','Hoàn thành']
                                            ?>
                                            @foreach($status_upload as $key=>$status)
                                                <div class="form-group col-lg-2">
                                                    <span class="text-muted m-l-30 ">{{$status}}</span>
                                                    <input type="radio" class="control-input" name="status_upload" value="{{$key}}" required>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <p id="changeMultiple_code" for="name"><code> ID Project @foreach($markets as $market) |   {{$market->market_name}}   @endforeach </code></p>
                                            <textarea id="changeMultiple" name="changeMultiple" rows="20" style="width: 100%"></textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success" id="changeMultipleBtn" >Update</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="fakeProjectModel"  role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelFakeHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="fakeprojectForm" name="fakeprojectForm" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="project_id_fake" id="project_id_fake">
                    <div class="row">
                        <div class="form-group col-lg-4">
                            <label>Logo</label><p></p>
                            <input  id="logo_fake" type="file" name="logo_fake" class="form-control" hidden onchange="changeImg(this)" accept="image/*">
                            <img id="avatar_fake" class="thumbnail" width="100px" src="img/logo.png">
                        </div>

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="form-group col-lg-4">
                                            <label for="name">Tiêu đề ứng dụng  </label>
                                            <input type="text" id="title_app_fake" name="title_app_fake" class="form-control" required >
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label for="name">Version Number </label>
                                            <input type="number" id="buildinfo_vernum_fake" name="buildinfo_vernum_fake" class="form-control" required >
                                        </div>
                                        <div class="form-group col-lg-4">
                                            <label for="name">Version String</label>
                                            <input type="text" id="buildinfo_verstr_fake" name="buildinfo_verstr_fake" class="form-control" required >
                                        </div>
                                        <div class="form-group col-lg-4 ">
                                            <label for="name">App Name (APP_NAME_X)</label>
                                            <input type="text" id="buildinfo_app_name_x_fake" name="buildinfo_app_name_x_fake" class="form-control" >
                                        </div>

                                        <div class="form-group col-lg-8 input_package">
                                            <label for="name">Tên Package CH-Play </label>
                                            <input type="text" id="Chplay_package_fake"  placeholder=""  name="Chplay_package_fake" class="form-control">
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10">
                            <a href="javascript:void(0)" class="btn btn-primary dashboard">Dashboard </a>
{{--                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes</button>--}}
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="copyProjectModel"  role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelCopyHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="copyProjectForm" name="copyProjectForm" class="form-horizontal" >
                    <input type="hidden" name="project_id_origin" id="project_id_origin">
                    <div class="row">
                        <div class="form-group col-sm-10 ">
                            <label for="name">Project name copy  </label>
                            <input type="text" id="project_name_copy" name="projectname" class="form-control" required >
                        </div>
                        <div class="col-sm-offset-2 col-sm-10">
{{--                            <a href="javascript:void(0)" class="btn btn-primary dashboard">Dashboard </a>--}}
                            <button type="submit" class="btn btn-primary" id="saveBtnCopy" >Copy Project</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>













