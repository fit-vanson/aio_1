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
                                    <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#tab_home" role="tab" id="nav_link_home">
                                                <span class="d-none d-sm-block">Home</span>
                                            </a>
                                        </li>

                                        <li class="nav_market">
                                            @foreach($markets as $market)
                                                <li class="nav-item">
                                                    <a class="nav-link"  data-toggle="tab" href="#tab_{{$market->market_name}}" role="tab" id="nav_{{$market->market_name}}">
                                                        <span class="d-none d-sm-block">{{$market->market_name}}</span>
                                                    </a>
                                                </li>
                                            @endforeach
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
                                                    <input type="text" id="projectname" name="projectname" class="form-control" required />
                                                </div>
                                                <div class="form-group col-lg-4 input_title_app">
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

                                            <div class="package_ads">
                                                @foreach($markets as $market)
                                                    <div id="package_{{$market->market_name}}">
                                                        <div class="form-group col-lg-12">
                                                            <h4 class="mt-0 header-title">Package {{$market->market_name}}</h4>
                                                            <input type="text" id="market_{{$market->id}}_package" name="market[{{$market->id}}][package]" class="form-control">
                                                        </div>
                                                        <div class="form-group col-lg-11" style="margin-left: auto;">
                                                            <div id="accordion_{{$market->id}}">
                                                                <div class="card mb-0">
                                                                    <div class="card-header" id="heading_{{$market->id}}">
                                                                        <a href="#collapse_{{$market->id}}" class="text-dark collapsed" data-toggle="collapse" aria-expanded="false" aria-controls="collapse_{{$market->id}}">
                                                                            ADS {{$market->market_name}}
                                                                        </a>
                                                                    </div>
                                                                    <div id="collapse_{{$market->id}}" class="collapse" aria-labelledby="heading_{{$market->id}}" data-parent="#accordion_{{$market->id}}" style="">
                                                                        <div class="card-body">
                                                                            <div class="divider">
                                                                                <div class="divider-text"><b>Admod</b></div>
                                                                            </div>
                                                                            <div class="row">
                                                                                <div class="form-group col-sm-4">
                                                                                    <input type="text" id="market_{{$market->id}}_ads_id" name="market[{{$market->id}}][ads][ads_id]" placeholder="id"  class="form-control"/>
                                                                                </div>

                                                                                <div class="form-group col-sm-4">
                                                                                    <input type="text" id="market_{{$market->id}}_ads_banner" name="market[{{$market->id}}][ads][ads_banner]" placeholder="banner"   class="form-control"/>
                                                                                </div>

                                                                                <div class="form-group col-sm-4">
                                                                                    <input type="text" id="market_{{$market->id}}_ads_inter" name="market[{{$market->id}}][ads][ads_inter]" placeholder="inter"   class="form-control">
                                                                                </div>

                                                                                <div class="form-group col-sm-4">
                                                                                    <input type="text" id="market_{{$market->id}}_ads_reward" name="market[{{$market->id}}][ads][ads_reward]" placeholder="reward"   class="form-control"/>
                                                                                </div>

                                                                                <div class="form-group col-sm-4">
                                                                                    <input type="text" id="market_{{$market->id}}_ads_native" name="market[{{$market->id}}][ads][ads_native]" placeholder="native"   class="form-control"/>
                                                                                </div>

                                                                                <div class="form-group col-sm-4">
                                                                                    <input type="text" id="market_{{$market->id}}_ads_open" name="market[{{$market->id}}][ads][ads_open]" placeholder="open"   class="form-control"/>
                                                                                </div>
                                                                            </div>
                                                                            <div class="divider">
                                                                                <div class="divider-text"><b>Start.io</b></div>
                                                                            </div>
                                                                            <div class="row" >
                                                                                <div class="col-sm">
                                                                                    <input type="text" id="market_{{$market->id}}_ads_start" name="market[{{$market->id}}][ads][ads_start]" placeholder="start"  class="form-control" />
                                                                                </div>
                                                                            </div>
                                                                            <div class="divider">
                                                                                <div class="divider-text"><b>Huawei</b></div>
                                                                            </div>
                                                                            <div class="row" >
                                                                                <div class="form-group col-sm-4">
                                                                                    <input type="text" id="market_{{$market->id}}_ads_banner_huawei" name="market[{{$market->id}}][ads][ads_banner_huawei]" placeholder="banner"   class="form-control"/>
                                                                                </div>

                                                                                <div class="form-group col-sm-4">
                                                                                    <input type="text" id="market_{{$market->id}}_ads_inter_huawei" name="market[{{$market->id}}][ads][ads_inter_huawei]" placeholder="inter"   class="form-control"/>
                                                                                </div>

                                                                                <div class="form-group col-sm-4">
                                                                                    <input type="text" id="market_{{$market->id}}_ads_reward_huawei" name="market[{{$market->id}}][ads][ads_reward_huawei]" placeholder="reward"   class="form-control" />
                                                                                </div>

                                                                                <div class="form-group col-sm-4">
                                                                                    <input type="text" id="market_{{$market->id}}_ads_native_huawei" name="market[{{$market->id}}][ads][ads_native_huawei]" placeholder="native"   class="form-control"/>
                                                                                </div>

                                                                                <div class="form-group col-sm-4">
                                                                                    <input type="text" id="market_{{$market->id}}_ads_splash_huawei" name="market[{{$market->id}}][ads][ads_splash_huawei]" placeholder="splash"   class="form-control" />
                                                                                </div>
                                                                                <div class="form-group col-sm-4">
                                                                                    <input type="text" id="market_{{$market->id}}_ads_roll_huawei" name="market[{{$market->id}}][ads][ads_roll_huawei]" placeholder="roll"   class="form-control"/>
                                                                                </div>
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div >
                                                @endforeach


                                            </div>
                                        </div>
{{--                                        <div class="tab_market">--}}
                                            @foreach($markets as $market)
                                                <div class="tab-pane p-3" id="tab_{{$market->market_name}}" role="tabpanel">
                                                    <div  class="row">
                                                        <div class="form-group col-lg-6">
                                                            <label for="name">Store Name ({{$market->market_name}}) </label>
                                                            <input type="hidden" name="market[{{$market->id}}][dev_id]" id="_market_{{$market->id}}_dev_id" >
                                                            <select class="form-control select2" id="{{$market->market_name}}_dev_id"  name="market[{{$market->id}}][dev_id]">
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-lg-6">
                                                            <label for="name">Keystore Profile</label>
                                                            <input type="hidden" name="market[{{$market->id}}][keystore]" id="_market_{{$market->id}}_keystore" >
                                                            <select class="form-control select2" id="{{$market->market_name}}_keystore" name="market[{{$market->id}}][keystore]"></select>
                                                        </div>
                                                        <div class="form-group col-lg-6">
                                                            <label for="name">Link App</label>
                                                            <input type="text" id="market_{{$market->id}}_app_link" name="market[{{$market->id}}][app_link]" class="form-control" >
                                                        </div>
                                                        <div class="form-group col-lg-6 ">
                                                            <label for="name">Link Policy</label>
                                                            <input type="text" id="market_{{$market->id}}_policy_link" name="market[{{$market->id}}][policy_link]" class="form-control" >
                                                        </div>

                                                        <div class="form-group col-lg-6">
                                                            <label for="name">AppID</label>
                                                            <input type="text" id="market_{{$market->id}}_app_id" name="market[{{$market->id}}][appID]" class="form-control" >
                                                        </div>

                                                        <div class="form-group col-lg-6">
                                                            <label for="name">App Name X</label>
                                                            <input type="text" id="market_{{$market->id}}_app_name_x" name="market[{{$market->id}}][app_name_x]" class="form-control" >
                                                        </div>

                                                        <div class="form-group col-lg-6 ">
                                                            <label for="name">SDK</label>
                                                            <input type="text" id="market_{{$market->id}}_sdk" name="market[{{$market->id}}][sdk]" class="form-control" >
                                                        </div>
                                                        <div class="form-group col-lg-6 ">
                                                            <label for="name">Link Video</label>
                                                            <input type="text" id="market_{{$market->id}}_video_link" name="market[{{$market->id}}][video_link]" class="form-control" >
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
{{--                                        </div>--}}


                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="saveBtn" >Save changes</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{--<div class="modal fade bd-example-modal-xl" id="ajaxPartTimeModel" aria-hidden="true">--}}
{{--    <div class="modal-dialog modal-xl">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <h4 class="modal-title" id="modelPartTimeHeading"></h4>--}}
{{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
{{--            </div>--}}
{{--            <div class="modal-body">--}}
{{--                <form id="parttimeprojectForm" name="parttimeprojectForm" class="form-horizontal">--}}
{{--                    <input type="hidden" name="project_id" id="part_time_project_id">--}}
{{--                    <div class="divider Chplay_status">--}}
{{--                        <div class="divider-text"><img src="img/icon/google.png"></div>--}}
{{--                    </div>--}}
{{--                    <div class="row Chplay_status">--}}
{{--                        <div class="form-group col-lg-6 ">--}}
{{--                            <label for="name">Store Name (CH Play) </label>--}}
{{--                            <select class="form-control select2" id="Chplay_buildinfo_store_name_x1" name="Chplay_buildinfo_store_name_x">--}}
{{--                                <option value="0" >---Vui lòng chọn---</option>--}}
{{--                                @foreach($store_name as $item)--}}
{{--                                    <option value="{{$item->id}}">{{$item->dev_name}} : {{$item->store_name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-lg-6 ">--}}
{{--                            <label for="name">Trạng thái Ứng dụng (CHPlay)</label>--}}
{{--                            <div>--}}
{{--                                <select class="form-control" id="Chplay_status1" name="Chplay_status">--}}
{{--                                    <option value="0">Mặc định</option>--}}
{{--                                    <option value="1">Publish</option>--}}
{{--                                    <option value="6">Check</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="divider Amazon_status">--}}
{{--                        <div class="divider-text"><img src="img/icon/amazon.png"></div>--}}
{{--                    </div>--}}
{{--                    <div class="row Amazon_status">--}}
{{--                        <div class="form-group col-lg-6 ">--}}
{{--                            <label for="name">Store Name (Amazon) </label>--}}
{{--                            <select class="form-control select2" id="Amazon_buildinfo_store_name_x1" name="Amazon_buildinfo_store_name_x">--}}
{{--                                <option value="0" >---Vui lòng chọn---</option>--}}
{{--                                @foreach($store_name_amazon as $item)--}}
{{--                                    <option value="{{$item->id}}">{{$item->amazon_dev_name}} : {{$item->amazon_store_name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-lg-6 ">--}}
{{--                            <label for="name">Trạng thái Ứng dụng (Amazon) </label>--}}
{{--                            <div>--}}
{{--                                <select class="form-control" id="Amazon_status1" name="Amazon_status">--}}
{{--                                    <option value="0">Mặc định</option>--}}
{{--                                    <option value="1">Publish</option>--}}
{{--                                    <option value="6">Check</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="divider Samsung_status">--}}
{{--                        <div class="divider-text"><img src="img/icon/samsung.png"></div>--}}
{{--                    </div>--}}
{{--                    <div class="row Samsung_status">--}}
{{--                        <div class="form-group col-lg-6 ">--}}
{{--                            <label for="name">Store Name (Samsung) </label>--}}
{{--                            <select class="form-control select2" id="Samsung_buildinfo_store_name_x1" name="Samsung_buildinfo_store_name_x">--}}
{{--                                <option value="0"  >---Vui lòng chọn---</option>--}}
{{--                                @foreach($store_name_samsung as $item)--}}
{{--                                    <option value="{{$item->id}}">{{$item->samsung_dev_name}} : {{$item->samsung_store_name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-lg-6 ">--}}
{{--                            <label for="name">Trạng thái Ứng dụng (Samsung)</label>--}}
{{--                            <div>--}}
{{--                                <select class="form-control" id="Samsung_status1" name="Samsung_status">--}}
{{--                                    <option value="0">Mặc định</option>--}}
{{--                                    <option value="1">Publish</option>--}}
{{--                                    <option value="6">Check</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="divider Xiaomi_status">--}}
{{--                        <div class="divider-text"><img src="img/icon/xiaomi.png"></div>--}}
{{--                    </div>--}}
{{--                    <div class="row Xiaomi_status">--}}
{{--                        <div class="form-group col-lg-6 ">--}}
{{--                            <label for="name">Store Name (Xiaomi) </label>--}}
{{--                            <select class="form-control select2" id="Xiaomi_buildinfo_store_name_x1" name="Xiaomi_buildinfo_store_name_x">--}}
{{--                                <option value="0"  >---Vui lòng chọn---</option>--}}
{{--                                @foreach($store_name_xiaomi as $item)--}}
{{--                                    <option value="{{$item->id}}">{{$item->xiaomi_dev_name}} : {{$item->xiaomi_store_name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-lg-6 ">--}}
{{--                            <label for="name">Trạng thái Ứng dụng (Xiaomi)</label>--}}
{{--                            <div>--}}
{{--                                <select class="form-control" id="Xiaomi_status1" name="Xiaomi_status">--}}
{{--                                    <option value="0">Mặc định</option>--}}
{{--                                    <option value="1">Publish</option>--}}
{{--                                    <option value="6">Check</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="divider Oppo_status">--}}
{{--                        <div class="divider-text"><img src="img/icon/oppo.png"></div>--}}
{{--                    </div>--}}
{{--                    <div  class="row Oppo_status">--}}
{{--                        <div class="form-group col-lg-6 ">--}}
{{--                            <label for="name">Store Name (OPPO) </label>--}}
{{--                            <select class="form-control select2" id="Oppo_buildinfo_store_name_x1" name="Oppo_buildinfo_store_name_x">--}}
{{--                                <option value="0"  >---Vui lòng chọn---</option>--}}
{{--                                @foreach($store_name_oppo as $item)--}}
{{--                                    <option value="{{$item->id}}">{{$item->oppo_dev_name}} : {{$item->oppo_store_name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-lg-6 ">--}}
{{--                            <label for="name">Trạng thái Ứng dụng (Oppo)</label>--}}
{{--                            <div>--}}
{{--                                <select class="form-control" id="Oppo_status1" name="Oppo_status">--}}
{{--                                    <option value="0">Mặc định</option>--}}
{{--                                    <option value="1">Publish</option>--}}
{{--                                    <option value="6">Check</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="divider Vivo_status">--}}
{{--                        <div class="divider-text"><img src="img/icon/vivo.png"></div>--}}
{{--                    </div>--}}
{{--                    <div class="row Vivo_status">--}}
{{--                        <div class="form-group col-lg-6 ">--}}
{{--                            <label for="name">Store Name (Vivo) </label>--}}
{{--                            <select class="form-control select2" id="Vivo_buildinfo_store_name_x1" name="Vivo_buildinfo_store_name_x">--}}
{{--                                <option value="0" >---Vui lòng chọn---</option>--}}
{{--                                @foreach($store_name_vivo as $item)--}}
{{--                                    <option value="{{$item->id}}">{{$item->vivo_dev_name}} : {{$item->vivo_store_name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-lg-6 ">--}}
{{--                            <label for="name">Trạng thái Ứng dụng (Vivo)</label>--}}
{{--                            <div>--}}
{{--                                <select class="form-control" id="Vivo_status1" name="Vivo_status">--}}
{{--                                    <option value="100">Mặc định</option>--}}
{{--                                    <option value="0">UnPublished</option>--}}
{{--                                    <option value="1">Published</option>--}}
{{--                                    <option value="2">Removed</option>--}}
{{--                                    <option value="3">To be published</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="divider Huawei_status">--}}
{{--                        <div class="divider-text"><img src="img/icon/huawei.png"></div>--}}
{{--                    </div>--}}
{{--                    <div class="row Huawei_status">--}}
{{--                        <div class="form-group col-lg-6 ">--}}
{{--                            <label for="name">Store Name (Huawei) </label>--}}
{{--                            <select class="form-control select2" id="Huawei_buildinfo_store_name_x1" name="Huawei_buildinfo_store_name_x">--}}
{{--                                <option value="0" >---Vui lòng chọn---</option>--}}
{{--                                @foreach($store_name_huawei as $item)--}}
{{--                                    <option value="{{$item->id}}">{{$item->huawei_dev_name}} : {{$item->huawei_store_name}}</option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <div class="form-group col-lg-6  ">--}}
{{--                            <label for="name">Trạng thái Ứng dụng (Huawei)</label>--}}
{{--                            <div>--}}
{{--                                <select class="form-control" id="Huawei_status1" name="Huawei_status">--}}
{{--                                    <option value="100">Mặc định</option>--}}
{{--                                    <option value="0">Released</option>--}}
{{--                                    <option value="1">Release Rejected</option>--}}
{{--                                    <option value="2">Removed (including forcible removal)</option>--}}
{{--                                    <option value="3">Releasing</option>--}}
{{--                                    <option value="4">Reviewing</option>--}}
{{--                                    <option value="5">Updating</option>--}}
{{--                                    <option value="6">Removal requested</option>--}}
{{--                                    <option value="7">Draft</option>--}}
{{--                                    <option value="8">Update rejected</option>--}}
{{--                                    <option value="9">Removal requested</option>--}}
{{--                                    <option value="10">Removed by developer</option>--}}
{{--                                    <option value="11">Release canceled</option>--}}

{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-sm-offset-2 col-sm-10">--}}
{{--                        <button type="submit" class="btn btn-primary">Save changes</button>--}}
{{--                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

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

<div class="modal fade" id="addMaDa" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm mới Mã dự án</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

                <form id="AddDaForm" name="AddDaForm" class="form-horizontal">
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Tên dự án</label>
                        <div class="col-sm-12">
                            <input type="hidden" name="da_id" id="add_da_id">
                            <input type="text" class="form-control" id="add_ma_da" name="ma_da" placeholder="Mã dự án" required>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Thêm mới</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="addTemplate" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm mới Template</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

                <form id="AddTempForm" name="AddTempForm" class="form-horizontal">
                    <input type="hidden" name="template_id" id="template_id">

                    <div data-repeater-item="" class="row">
                            <div class="form-group col-lg-12 ">
                                <label for="name">Tên Template <span style="color: red">*</span></label>
                                <input type="text" id="add_template" name="template" class="form-control" required>
                            </div>
                        </div>
                    <div data-repeater-item="" class="row">
                        <div class="form-group col-lg-6 ">
                            <label for="name">Convert Aab</label>
                            <div>
                                <select class="form-control" id="convert_aab" name="convert_aab">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-lg-6 input_status">
                            <label for="name">Trạng thái</label>
                            <div>
                                <select class="form-control" id="startus" name="startus">
                                    <option value="0">Mở</option>
                                    <option value="1">Tắt</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div data-repeater-item="" class="row">
                        <div class="form-group col-lg-2">
                            <label for="name">Ads ID</label>
                            <input type="checkbox" class="control-input" name="Check_ads_id" id="Check_ads_id" value="1">
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="name">Ads banner</label>
                            <input type="checkbox" class="control-input" name="Check_ads_banner" id="Check_ads_banner" value="1">

                        </div>
                        <div class="form-group col-lg-2 ">
                            <label for="name">Ads inter</label>
                            <input type="checkbox" class="control-input" name="Check_ads_inter" id="Check_ads_inter" value="1">

                        </div>
                        <div class="form-group col-lg-2">
                            <label for="name">Ads reward</label>
                            <input type="checkbox" class="control-input" name="Check_ads_reward" id="Check_ads_reward" value="1">
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="name">Ads native</label>
                            <input type="checkbox" class="control-input" name="Check_ads_native" id="Check_ads_native" value="1">
                        </div>
                        <div class="form-group col-lg-2 ">
                            <label for="name">Ads open</label>
                            <input type="checkbox" class="control-input" name="Check_ads_open" id="Check_ads_open" value="1">
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Thêm mới</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="addKeystore" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm mới Keystore</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="keystoreForm" name="keystoreForm" class="form-horizontal">
                    <div data-repeater-item="" class="row">
                        <div class="form-group col-lg-6 ">
                            {{--                            <label for="name">Tên Keystore</label>--}}
                            {{--                            <input type="text" class="form-control" id="name_keystore" name="name_keystore" required>--}}
                            <div class="fallback">
                                <input name="keystore_file" id="keystore_file" type="file"  multiple="multiple">
                            </div>
                        </div>
                        <div class="form-group col-lg-6 ">
                            <label for="name">Tên Keystore</label>
                            <input type="hidden" name="keystore_id" id="keystore_id">
                            <input type="text" class="form-control" id="name_keystore" name="name_keystore" required>
                        </div>

                    </div>

                    <div data-repeater-item="" class="row">
                        <div class="form-group col-lg-6 ">
                            <label for="name">Pass Keystore</label>
                            <input type="text" id="pass_keystore" name="pass_keystore" class="form-control" >
                        </div>
                        <div class="form-group col-lg-6 ">
                            <label for="name">Aliases Keystore</label>
                            <input type="text" id="aliases_keystore" name="aliases_keystore" class="form-control" >
                            {{--                            <textarea id="aliases_keystore" name="aliases_keystore" class="form-control" rows="4" ></textarea>--}}
                        </div>

                    </div>
                    <div data-repeater-item="" class="row">
                        <div class="form-group col-lg-6">
                            <label for="name">SHA_256 Keystore</label>
                            <textarea id="SHA_256_keystore" name="SHA_256_keystore" class="form-control" rows="4" ></textarea>
                        </div>
                        <div class="form-group col-lg-6 ">
                            <label for="name">Ghi chú</label>
                            <textarea id="note" name="note" class="form-control" rows="4" ></textarea>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>

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

{{--<div class="modal fade bd-example-modal-xl" id="editDesEN"  role="dialog">--}}
{{--    <div class="modal-dialog modal-xl">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <h4 class="modal-title" id="modelEditDesEN"></h4>--}}
{{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
{{--            </div>--}}
{{--            <div class="modal-body">--}}
{{--                <form id="EditDesEN" name="EditDesEN" class="form-horizontal">--}}
{{--                    <input type="hidden" name="project_id" id="project_id_edit_desEN">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-12">--}}
{{--                            <div class="card">--}}
{{--                                <div class="card-body">--}}
{{--                                    <div data-repeater-item="" class="row">--}}
{{--                                        <div class="form-group col-lg-12">--}}
{{--                                            <label for="name">Title App   &nbsp; &nbsp; &nbsp;--}}
{{--                                                <span class="font-13 text-muted" id="count_title_app_en"></span>--}}
{{--                                                <button type="button" onclick="copyTitleEN()" class="btn btn-link waves-effect copyboard"><i class="mdi mdi-content-copy"></i></button>--}}
{{--                                            </label>--}}
{{--                                            <input type="text" id="title_app_en" name="title_app" class="form-control">--}}



{{--                                        </div>--}}
{{--                                        <div class="form-group col-lg-12">--}}
{{--                                            <label for="name">Summary &nbsp; &nbsp; &nbsp;--}}
{{--                                                <span class="font-13 text-muted" id="count_summary_en"></span>--}}
{{--                                                <button type="button" onclick="copySumEN()" class="btn btn-link waves-effect copyboard"><i class="mdi mdi-content-copy"></i></button>--}}
{{--                                            </label>--}}
{{--                                            <input type="text" id="summary_en" name="summary_en" class="form-control">--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group col-lg-12">--}}
{{--                                            <label for="name">Description</label><button type="button" onclick="copyDesEN()" class="btn btn-link waves-effect copyboard"><i class="mdi mdi-content-copy"></i></button> </label>--}}
{{--                                            <textarea id="des_en" name="des_en"></textarea>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div> <!-- end col -->--}}
{{--                        <div class="col-sm-offset-2 col-sm-10">--}}
{{--                            <button type="submit" class="btn btn-primary" value="edit-des-en">Save changes</button>--}}
{{--                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

{{--<div class="modal fade bd-example-modal-xl" id="editDesVN"  role="dialog">--}}
{{--    <div class="modal-dialog modal-xl">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <h4 class="modal-title" id="modelEditDesVN"></h4>--}}
{{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
{{--            </div>--}}
{{--            <div class="modal-body">--}}
{{--                <form id="EditDesVN" name="EditDesVN" class="form-horizontal">--}}
{{--                    <input type="hidden" name="project_id" id="project_id_edit_DesVN">--}}


{{--                    <div class="row">--}}
{{--                        <div class="col-12">--}}
{{--                            <div class="card">--}}
{{--                                <div class="card-body">--}}
{{--                                    <div data-repeater-item="" class="row">--}}
{{--                                        <div class="form-group col-lg-12">--}}
{{--                                            <label for="name">Tiêu đề ứng dụng &nbsp; &nbsp; &nbsp;--}}
{{--                                                <span class="font-13 text-muted" id="count_title_app_vn"></span>--}}
{{--                                                <button type="button" onclick="copyTitleVN()" class="btn btn-link waves-effect copyboard"><i class="mdi mdi-content-copy"></i></button> </label>--}}
{{--                                            <input type="text" id="title_app_vn" name="title_app" class="form-control">--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group col-lg-12">--}}
{{--                                            <label for="name">Mô tả ngắn &nbsp; &nbsp; &nbsp;--}}
{{--                                                <span class="font-13 text-muted" id="count_summary_vn"></span>--}}
{{--                                                <button type="button" onclick="copySumVN()" class="btn btn-link waves-effect copyboard"><i class="mdi mdi-content-copy"></i></button> </label>--}}
{{--                                            <input type="text" id="summary_vn" name="summary_vn" class="form-control">--}}
{{--                                        </div>--}}
{{--                                        <div class="form-group col-lg-12">--}}
{{--                                            <label for="name">Mô tả</label><button type="button" onclick="copyDesVN()" class="btn btn-link waves-effect copyboard"><i class="mdi mdi-content-copy"></i></button> </label>--}}
{{--                                            <textarea id="des_vn" name="des_vn"></textarea>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}


{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div> <!-- end col -->--}}
{{--                        <div class="col-sm-offset-2 col-sm-10">--}}
{{--                            <button type="submit" class="btn btn-primary" value="edit-des-en">Save changes</button>--}}
{{--                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}


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
                                                <div class="divider-text"><img src="img/icon/{{$market->market_logo}}"></div>
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
                                <form class="repeater" id="changeMultipleForm">
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <p for="name" id="changeMultipleName"></p>
                                            <textarea id="changeMultiple" name="changeMultiple" rows="20" style="width: 100%"></textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary" id="changeMultipleBtn" >Create</button>
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
                    <div data-repeater-item="" class="row">
                        <div class="form-group col-lg-4">
                            <label>Logo</label><p></p>
                            <input  id="logo_fake" type="file" name="logo_fake" class="form-control" hidden onchange="changeImg(this)" accept="image/*">
                            <img id="avatar_fake" class="thumbnail" width="100px" src="img/logo.png">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div data-repeater-item="" class="row">
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













