
<div class="modal fade bd-example-modal-xl" id="ajaxModel"  role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="projectForm2" name="projectForm2" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="project_id" id="project_id">
                    <input type="hidden" name="buildinfo_console" id="buildinfo_console">


                    <div data-repeater-item="" class="row">
                        <div class="form-group col-lg-4">
                            <label>Logo</label><p></p>
                            <input  id="logo" type="file" name="logo" class="form-control" hidden onchange="changeImg(this)" accept="image/*">
                            <img id="avatar" class="thumbnail" width="100px" src="img/logo.png">
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="name">File</label><p></p>
                            <input type="file" name="project_file" id="project_file" class="filestyle" data-buttonname="btn-secondary" accept=".zip">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs nav-tabs-custom" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link _home active" data-toggle="tab" href="#home1" role="tab" id="nav_link_home">
                                                <span class="d-none d-sm-block">Home</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link a_chplay" data-toggle="tab" href="#chplay"  role="tab">
                                                <span class="d-none d-sm-block">CH Play</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link a_amazon" data-toggle="tab" href="#amazon" role="tab">
                                                <span class="d-none d-sm-block">Amazon</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link a_samsung" data-toggle="tab" href="#samsung" role="tab">
                                                <span class="d-none d-sm-block">SamSung</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link a_xiaomi" data-toggle="tab" href="#xiaomi" role="tab">
                                                <span class="d-none d-sm-block">Xiaomi</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link a_oppo" data-toggle="tab" href="#oppo" role="tab">
                                                <span class="d-none d-sm-block">Oppo</span>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link a_vivo" data-toggle="tab" href="#vivo" role="tab">
                                                <span class="d-none d-sm-block">Vivo</span>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a class="nav-link a_huawei" data-toggle="tab" href="#huawei" role="tab">
                                                <span class="d-none d-sm-block">Huawei</span>
                                            </a>
                                        </li>
                                    </ul>
                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div class="tab-pane active p-3" id="home1" role="tabpanel">
                                            <div data-repeater-item="" class="row">
                                                <div class="form-group col-lg-4">
                                                    <label for="name">Mã dự án <span style="color: red">*</span></label>
                                                    <div class="inner row">
                                                        <div class="col-md-10 col-10">
                                                            <select class="form-control select2" id="ma_da" name="ma_da">
                                                                <option value="0">---Vui lòng chọn---</option>
                                                                @foreach($da as $item)
                                                                    <option value="{{$item->id}}">{{$item->ma_da}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 col-4">
                                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addMaDa" style="border-radius: 0 3px 3px 0; box-shadow: none;">...
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-lg-4">
                                                    <label for="name">Mã template <span style="color: red">*</span></label>
                                                    <div class="inner row">
                                                        <div class="col-md-10 col-10">
                                                            <select class="form-control choose_template" id="template" name="template">
                                                                <option value="0">---Vui lòng chọn---</option>
                                                                @foreach($template as $item)
                                                                    <option value="{{$item->id}}">{{$item->template}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 col-4">
                                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addTemplate" style="border-radius: 0 3px 3px 0; box-shadow: none;">...
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-lg-4">
                                                    <label for="name">Mã Project <span style="color: red">*</span></label>
                                                    <input type="text" id="projectname" name="projectname" class="form-control" required>
                                                </div>
                                            </div>
                                            <div data-repeater-item="" class="row">
                                                <div class="form-group col-lg-4 input_title_app">
                                                    <label for="name">Tiêu đề ứng dụng  <span style="color: red">*</span></label>
                                                    <input type="text" id="title_app" name="title_app" class="form-control" required >
                                                </div>
                                                <div class="form-group col-lg-4">
                                                    <label for="name">Version Number <span style="color: red">*</span></label>
                                                    <input type="number" id="buildinfo_vernum" name="buildinfo_vernum" class="form-control" required >
                                                </div>
                                                <div class="form-group col-lg-4">
                                                    <label for="name">Version String<span style="color: red">*</span></label>
                                                    <input type="text" id="buildinfo_verstr" name="buildinfo_verstr" class="form-control" required >
                                                </div>
                                            </div>
                                            <div data-repeater-item="" class="row input_buildinfo">

                                                <div class="form-group col-lg-4 ">
                                                    <label for="name">App Name (APP_NAME_X)</label>
                                                    <input type="text" id="buildinfo_app_name_x" name="buildinfo_app_name_x" class="form-control" >
                                                </div>
{{--                                                <div class="form-group col-lg-4 ">--}}
{{--                                                    <label for="name">Keystore Profile </label>--}}
{{--                                                    <div class="inner row">--}}
{{--                                                        <div class="col-md-10 col-10">--}}
{{--                                                            <select class="form-control select2" id="buildinfo_keystore" name="buildinfo_keystore">--}}
{{--                                                                <option value="0">---Vui lòng chọn---</option>--}}
{{--                                                                @foreach($keystore as $item)--}}
{{--                                                                    <option value="{{$item->name_keystore}}">{{$item->name_keystore}}</option>--}}
{{--                                                                @endforeach--}}
{{--                                                            </select>--}}
{{--                                                        </div>--}}
{{--                                                        <div class="col-md-2 col-4">--}}
{{--                                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addKeystore" style="border-radius: 0 3px 3px 0; box-shadow: none;">...--}}
{{--                                                            </button>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                    <input type="text" id="buildinfo_keystore" name="buildinfo_keystore" class="form-control" >--}}
{{--                                                </div>--}}
{{--                                                <div class="form-group col-lg-4 ">--}}
{{--                                                    <label for="name">SDK </label>--}}
{{--                                                    <input type="text" id="buildinfo_sdk" name="buildinfo_sdk" class="form-control" >--}}
{{--                                                </div>--}}
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
                                                <div class="form-group col-lg-4 ">
                                                    <label for="name">Key API APP</label>
                                                    <input type="text" id="buildinfo_api_key_x" name="buildinfo_api_key_x" class="form-control" >
                                                </div>
                                                <div class="form-group col-lg-4 ">
                                                    <label for="name">Link Website</label>
                                                    <input type="text" id="buildinfo_link_website" name="buildinfo_link_website" class="form-control" >
                                                </div>
                                                <div class="form-group col-lg-4 ">
                                                    <label for="name">Link Store VietMMO</label>
                                                    <input type="text" id="link_store_vietmmo" name="link_store_vietmmo" class="form-control" >

                                                </div>
                                            </div>
                                            <span class="card-title-desc" id="p_buildinfo_keystore"></span>
                                            <button type="button" class="btn btn-link waves-effect" id="button_buildinfo_keystore"><i class="mdi mdi-content-copy"></i></button>
                                            </td>

                                            <div class="progress m-b-10" style="height: 3px;">
                                                <div class="progress-bar"  role="progressbar" style="background-color: #0b0b0b;width: 100%;"></div>
                                            </div>
                                            <div data-repeater-item="" class="row market_chplay" id="market_chplay">
                                                <div class="form-group col-lg-12 input_package">
                                                    <label for="name">Tên Package CH-Play </label>
                                                    <input type="text" id="Chplay_package"  placeholder=""  name="Chplay_package" class="form-control">
                                                </div>
                                                <div class="form-group col-lg-1"></div>
                                                <div class="form-group col-lg-11">
                                                    <div id="accordion">
                                                        <div class="card mb-1">
                                                            <div class="card-header p-3" id="heading_chplay">
                                                                <h6 class="m-0 font-14">
                                                                    <a href="#collapse_chplay" class="text-dark" data-toggle="collapse"
                                                                       aria-controls="collapse_chplay">
                                                                        Ads
                                                                    </a>
                                                                </h6>
                                                            </div>
                                                            <div id="collapse_chplay" class="collapse" aria-labelledby="heading_chplay"
                                                                 data-parent="#accordion">
                                                                <div class="card-body">
                                                                    <div class="ads_admod">
                                                                        <div class="divider divider-start divider_admod">
                                                                            <div class="divider-text"><b>Admod</b> <span id="chplay_dev_ga"></span></div>
                                                                        </div>
                                                                        <div class="row" >
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Chplay_ads_id" name="Chplay_ads_id" placeholder="id"  class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Chplay_ads_banner" name="Chplay_ads_banner" placeholder="banner"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Chplay_ads_inter" name="Chplay_ads_inter" placeholder="inter"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Chplay_ads_reward" name="Chplay_ads_reward" placeholder="reward"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Chplay_ads_native" name="Chplay_ads_native" placeholder="native"   class="form-control"  style="display: none">
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Chplay_ads_open" name="Chplay_ads_open" placeholder="open"   class="form-control" style="display: none" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ads_start">
                                                                        <div class="divider divider-start divider_start">
                                                                            <div class="divider-text"><b>Start.io</b></div>
                                                                        </div>
                                                                        <div class="row" >
                                                                            <div class="col-sm">
                                                                                <input type="text" id="Chplay_ads_start" name="Chplay_ads_start" placeholder="start"  class="form-control" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ads_huawei">
                                                                        <div class="divider divider-start divider_huawei">
                                                                            <div class="divider-text"><b>Huawei</b></div>
                                                                        </div>
                                                                        <div class="row" >
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Chplay_ads_banner_huawei" name="Chplay_ads_banner_huawei" placeholder="banner"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Chplay_ads_inter_huawei" name="Chplay_ads_inter_huawei" placeholder="inter"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Chplay_ads_reward_huawei" name="Chplay_ads_reward_huawei" placeholder="reward"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Chplay_ads_native_huawei" name="Chplay_ads_native_huawei" placeholder="native"   class="form-control"  style="display: none">
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Chplay_ads_splash_huawei" name="Chplay_ads_splash_huawei" placeholder="splash"   class="form-control" style="display: none" >
                                                                            </div>
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Chplay_ads_roll_huawei" name="Chplay_ads_roll_huawei" placeholder="roll"   class="form-control" style="display: none" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div >

                                            <div data-repeater-item="" class="row market_amazon" id="market_amazon">
                                                <div class="form-group col-lg-12 input_package">
                                                    <label for="name">Tên Package Amazon</label>
                                                    <input type="text" id="Amazon_package" name="Amazon_package" class="form-control">
                                                </div>
                                                <div class="form-group col-lg-1"></div>
                                                <div class="form-group col-lg-11">
                                                    <div id="accordion">
                                                        <div class="card mb-1">
                                                            <div class="card-header p-3" id="heading_amazon">
                                                                <h6 class="m-0 font-14">
                                                                    <a href="#collapse_amazon" class="text-dark" data-toggle="collapse"
                                                                       aria-controls="collapse_amazon">
                                                                        Ads
                                                                    </a>
                                                                </h6>
                                                            </div>
                                                            <div id="collapse_amazon" class="collapse" aria-labelledby="heading_amazon"
                                                                 data-parent="#accordion">
                                                                <div class="card-body">
                                                                    <div class="ads_admod">
                                                                        <div class="divider divider-start divider_admod">
                                                                            <div class="divider-text"><b>Admod</b> <span id="amazon_dev_ga"></span></div>
                                                                        </div>
                                                                        <div class="row" >
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Amazon_ads_id" name="Amazon_ads_id" placeholder="id" class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Amazon_ads_banner" name="Amazon_ads_banner" placeholder="banner"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Amazon_ads_inter" name="Amazon_ads_inter" placeholder="inter"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Amazon_ads_reward" name="Amazon_ads_reward" placeholder="reward"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Amazon_ads_native" name="Amazon_ads_native" placeholder="native"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Amazon_ads_open" name="Amazon_ads_open" placeholder="open"  class="form-control" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ads_start">
                                                                        <div class="divider divider-start divider_start">
                                                                            <div class="divider-text"><b>Start.io</b></div>
                                                                        </div>
                                                                        <div class="row" >
                                                                            <div class="col-sm">
                                                                                <input type="text" id="Amazon_ads_start" name="Amazon_ads_start" placeholder="start"  class="form-control" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ads_huawei">
                                                                        <div class="divider divider-start divider_huawei">
                                                                            <div class="divider-text"><b>Huawei</b></div>
                                                                        </div>
                                                                        <div class="row" >
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Amazon_ads_banner_huawei" name="Amazon_ads_banner_huawei" placeholder="banner"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Amazon_ads_inter_huawei" name="Amazon_ads_inter_huawei" placeholder="inter"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Amazon_ads_reward_huawei" name="Amazon_ads_reward_huawei" placeholder="reward"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Amazon_ads_native_huawei" name="Amazon_ads_native_huawei" placeholder="native"   class="form-control"  style="display: none">
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Amazon_ads_splash_huawei" name="Amazon_ads_splash_huawei" placeholder="open"   class="form-control" style="display: none" >
                                                                            </div>
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Amazon_ads_roll _huawei" name="Amazon_ads_roll _huawei" placeholder="open"   class="form-control" style="display: none" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div data-repeater-item="" class="row market_samsung">
                                                <div class="form-group col-lg-12 input_package">
                                                    <label for="name">Tên Package SamSung </label>
                                                    <input type="text" id="Samsung_package" name="Samsung_package" class="form-control">
                                                </div>
                                                <div class="form-group col-lg-1"></div>
                                                <div class="form-group col-lg-11">
                                                    <div id="accordion">
                                                        <div class="card mb-1">
                                                            <div class="card-header p-3" id="heading_samsung">
                                                                <h6 class="m-0 font-14">
                                                                    <a href="#collapse_samsung" class="text-dark" data-toggle="collapse"
                                                                       aria-controls="collapse_samsung">
                                                                        Ads
                                                                    </a>
                                                                </h6>
                                                            </div>
                                                            <div id="collapse_samsung" class="collapse" aria-labelledby="heading_samsung"
                                                                 data-parent="#accordion">
                                                                <div class="card-body">
                                                                    <div class="ads_admod">
                                                                        <div class="divider divider-start divider_admod">
                                                                            <div class="divider-text"><b>Admod</b> <span id="samsung_dev_ga"></span></div>
                                                                        </div>
                                                                        <div class="row" >
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Samsung_ads_id" name="Samsung_ads_id" placeholder="id" class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Samsung_ads_banner" name="Samsung_ads_banner" placeholder="banner"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Samsung_ads_inter" name="Samsung_ads_inter" placeholder="inter"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Samsung_ads_reward" name="Samsung_ads_reward" placeholder="reward"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Samsung_ads_native" name="Samsung_ads_native" placeholder="native"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Samsung_ads_open" name="Samsung_ads_open" placeholder="open"  class="form-control" >
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    <div class="ads_start">
                                                                        <div class="divider divider-start divider_start">
                                                                            <div class="divider-text"><b>Start.io</b></div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Samsung_ads_start" name="Samsung_ads_start" placeholder="start"  class="form-control" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ads_huawei">
                                                                        <div class="divider divider-start divider_huawei">
                                                                            <div class="divider-text"><b>Huawei</b></div>
                                                                        </div>
                                                                        <div class="row" >
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Samsung_ads_banner_huawei" name="Samsung_ads_banner_huawei" placeholder="banner"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Samsung_ads_inter_huawei" name="Samsung_ads_inter_huawei" placeholder="inter"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Samsung_ads_reward_huawei" name="Samsung_ads_reward_huawei" placeholder="reward"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Samsung_ads_native_huawei" name="Samsung_ads_native_huawei" placeholder="native"   class="form-control"  style="display: none">
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Samsung_ads_splash_huawei" name="Samsung_ads_splash_huawei" placeholder="splash"   class="form-control" style="display: none" >
                                                                            </div>
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Samsung_ads_roll_huawei" name="Samsung_ads_roll_huawei" placeholder="roll"   class="form-control" style="display: none" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div data-repeater-item="" class="row market_xiaomi">
                                                <div class="form-group col-lg-12 input_package">
                                                    <label for="name">Tên Package Xiaomi </label>
                                                    <input type="text" id="Xiaomi_package" name="Xiaomi_package" class="form-control">
                                                </div>
                                                <div class="form-group col-lg-1"></div>
                                                <div class="form-group col-lg-11">

                                                    <div id="accordion">
                                                        <div class="card mb-1">
                                                            <div class="card-header p-3" id="heading_xiaomi">
                                                                <h6 class="m-0 font-14">
                                                                    <a href="#collapse_xiaomi" class="text-dark" data-toggle="collapse"
                                                                       aria-controls="collapse_xiaomi">
                                                                        Ads
                                                                    </a>
                                                                </h6>
                                                            </div>
                                                            <div id="collapse_xiaomi" class="collapse" aria-labelledby="heading_xiaomi"
                                                                 data-parent="#accordion">
                                                                <div class="card-body">
                                                                    <div class="ads_admod">
                                                                        <div class="divider divider-start divider_admod">
                                                                            <div class="divider-text"><b>Admod</b> <span id="xiaomi_dev_ga"></span></div>
                                                                        </div>
                                                                        <div class="row" >
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Xiaomi_ads_id" name="Xiaomi_ads_id" placeholder="id" class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Xiaomi_ads_banner" name="Xiaomi_ads_banner" placeholder="banner"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Xiaomi_ads_inter" name="Xiaomi_ads_inter" placeholder="inter"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Xiaomi_ads_reward" name="Xiaomi_ads_reward" placeholder="reward"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Xiaomi_ads_native" name="Xiaomi_ads_native" placeholder="native"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Xiaomi_ads_open" name="Xiaomi_ads_open" placeholder="open"  class="form-control" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ads_start">
                                                                        <div class="divider divider-start divider_start">
                                                                            <div class="divider-text"><b>Start.io</b></div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Xiaomi_ads_start" name="Xiaomi_ads_start" placeholder="start"  class="form-control" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ads_huawei">
                                                                        <div class="divider divider-start divider_huawei">
                                                                            <div class="divider-text"><b>Huawei</b></div>
                                                                        </div>
                                                                        <div class="row" >
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Xiaomi_ads_banner_huawei" name="Xiaomi_ads_banner_huawei" placeholder="banner"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Xiaomi_ads_inter_huawei" name="Xiaomi_ads_inter_huawei" placeholder="inter"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Xiaomi_ads_reward_huawei" name="Xiaomi_ads_reward_huawei" placeholder="reward"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Xiaomi_ads_native_huawei" name="Xiaomi_ads_native_huawei" placeholder="native"   class="form-control"  style="display: none">
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Xiaomi_ads_splash_huawei" name="Xiaomi_ads_splash_huawei" placeholder="splash"   class="form-control" style="display: none" >
                                                                            </div>
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Xiaomi_ads_roll_huawei" name="Xiaomi_ads_roll_huawei" placeholder="roll"   class="form-control" style="display: none" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div data-repeater-item="" class="row market_oppo">
                                                <div class="form-group col-lg-12 input_package">
                                                    <label for="name">Tên Package Oppo </label>
                                                    <input type="text" id="Oppo_package" name="Oppo_package" class="form-control">
                                                </div>
                                                <div class="form-group col-lg-1"></div>
                                                <div class="form-group col-lg-11">

                                                    <div id="accordion">
                                                        <div class="card mb-1">
                                                            <div class="card-header p-3" id="heading_oppo">
                                                                <h6 class="m-0 font-14">
                                                                    <a href="#collapse_oppo" class="text-dark" data-toggle="collapse"
                                                                       aria-controls="collapse_oppo">
                                                                        Ads
                                                                    </a>
                                                                </h6>
                                                            </div>
                                                            <div id="collapse_oppo" class="collapse" aria-labelledby="heading_oppo"
                                                                 data-parent="#accordion">
                                                                <div class="card-body">
                                                                    <div class="ads_admod">
                                                                        <div class="divider divider-start divider_admod">
                                                                            <div class="divider-text"><b>Admod</b> <span id="oppo_dev_ga"></span></div>
                                                                        </div>
                                                                        <div class="row" >
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Oppo_ads_id" name="Oppo_ads_id" placeholder="id" class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Oppo_ads_banner" name="Oppo_ads_banner" placeholder="banner"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Oppo_ads_inter" name="Oppo_ads_inter" placeholder="inter"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Oppo_ads_reward" name="Oppo_ads_reward" placeholder="reward"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Oppo_ads_native" name="Oppo_ads_native" placeholder="native"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Oppo_ads_open" name="Oppo_ads_open" placeholder="open"  class="form-control" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ads_start">
                                                                        <div class="divider divider-start divider_start">
                                                                            <div class="divider-text"><b>Start.io</b></div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Oppo_ads_start" name="Oppo_ads_start" placeholder="start"  class="form-control" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ads_huawei">
                                                                        <div class="divider divider-start divider_huawei">
                                                                            <div class="divider-text"><b>Huawei</b></div>
                                                                        </div>
                                                                        <div class="row" >
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Oppo_ads_banner_huawei" name="Oppo_ads_banner_huawei" placeholder="banner"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Oppo_ads_inter_huawei" name="Oppo_ads_inter_huawei" placeholder="inter"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Oppo_ads_reward_huawei" name="Oppo_ads_reward_huawei" placeholder="reward"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Oppo_ads_native_huawei" name="Oppo_ads_native_huawei" placeholder="native"   class="form-control"  style="display: none">
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Oppo_ads_splash_huawei" name="Oppo_ads_splash_huawei" placeholder="splash"   class="form-control" style="display: none" >
                                                                            </div>
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Oppo_ads_roll_huawei" name="Oppo_ads_roll_huawei" placeholder="roll"   class="form-control" style="display: none" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div data-repeater-item="" class="row market_vivo" >
                                                <div class="form-group col-lg-12 input_package">
                                                    <label for="name">Tên Package Vivo </label>
                                                    <input type="text" id="Vivo_package" name="Vivo_package" class="form-control">
                                                </div>

                                                <div class="form-group col-lg-1"></div>
                                                <div class="form-group col-lg-11">

                                                    <div id="accordion">
                                                        <div class="card mb-1">
                                                            <div class="card-header p-3" id="heading_vivo">
                                                                <h6 class="m-0 font-14">
                                                                    <a href="#collapse_vivo" class="text-dark" data-toggle="collapse"
                                                                       aria-controls="collapse_vivo">
                                                                        Ads
                                                                    </a>
                                                                </h6>
                                                            </div>
                                                            <div id="collapse_vivo" class="collapse" aria-labelledby="heading_vivo"
                                                                 data-parent="#accordion">
                                                                <div class="card-body">
                                                                    <div class="ads_admod">
                                                                        <div class="divider divider-start divider_admod">
                                                                            <div class="divider-text"><b>Admod</b> <span id="vivo_dev_ga"></span></div>
                                                                        </div>
                                                                        <div class="row" >
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Vivo_ads_id" name="Vivo_ads_id" placeholder="id" class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Vivo_ads_banner" name="Vivo_ads_banner" placeholder="banner"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Vivo_ads_inter" name="Vivo_ads_inter" placeholder="inter"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Vivo_ads_reward" name="Vivo_ads_reward" placeholder="reward"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Vivo_ads_native" name="Vivo_ads_native" placeholder="native"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Vivo_ads_open" name="Vivo_ads_open" placeholder="open"  class="form-control" >
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    <div class="ads_start">
                                                                        <div class="divider divider-start divider_start">
                                                                            <div class="divider-text"><b>Start.io</b></div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Vivo_ads_start" name="Vivo_ads_start" placeholder="start"  class="form-control" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ads_huawei">
                                                                        <div class="divider divider-start divider_huawei">
                                                                            <div class="divider-text"><b>Huawei</b></div>
                                                                        </div>
                                                                        <div class="row" >
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Vivo_ads_banner_huawei" name="Vivo_ads_banner_huawei" placeholder="banner"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Vivo_ads_inter_huawei" name="Vivo_ads_inter_huawei" placeholder="inter"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Vivo_ads_reward_huawei" name="Vivo_ads_reward_huawei" placeholder="reward"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Vivo_ads_native_huawei" name="Vivo_ads_native_huawei" placeholder="native"   class="form-control"  style="display: none">
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Vivo_ads_splash_huawei" name="Vivo_ads_splash_huawei" placeholder="splash"   class="form-control" style="display: none" >
                                                                            </div>
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Vivo_ads_roll_huawei" name="Vivo_ads_roll_huawei" placeholder="roll"   class="form-control" style="display: none" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div data-repeater-item="" class="row market_huawei" >
                                                <div class="form-group col-lg-12 input_package">
                                                    <label for="name">Tên Package Huawei</label>
                                                    <input type="text" id="Huawei_package" name="Huawei_package" class="form-control">
                                                </div>
                                                <div class="form-group col-lg-1"></div>
                                                <div class="form-group col-lg-11">
                                                    <div id="accordion">
                                                        <div class="card mb-1">
                                                            <div class="card-header p-3" id="heading_huawei">
                                                                <h6 class="m-0 font-14">
                                                                    <a href="#collapse_huawei" class="text-dark" data-toggle="collapse"
                                                                       aria-controls="collapse_huawei">
                                                                        Ads
                                                                    </a>
                                                                </h6>
                                                            </div>
                                                            <div id="collapse_huawei" class="collapse" aria-labelledby="heading_huawei"
                                                                 data-parent="#accordion">
                                                                <div class="card-body">
                                                                    <div class="ads_admod">
                                                                        <div class="divider divider-start divider_admod">
                                                                            <div class="divider-text"><b>Admod</b> <span id="huawei_dev_ga"></span></div>
                                                                        </div>
                                                                        <div class="row" >
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Huawei_ads_id" name="Huawei_ads_id" placeholder="id" class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Huawei_ads_banner" name="Huawei_ads_banner" placeholder="banner"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Huawei_ads_inter" name="Huawei_ads_inter" placeholder="inter"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Huawei_ads_reward" name="Huawei_ads_reward" placeholder="reward"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Huawei_ads_native" name="Huawei_ads_native" placeholder="native"  class="form-control" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Huawei_ads_open" name="Huawei_ads_open" placeholder="open"  class="form-control" >
                                                                            </div>

                                                                        </div>
                                                                    </div>
                                                                    <div class="ads_start">
                                                                        <div class="divider divider-start divider_start">
                                                                            <div class="divider-text"><b>Start.io</b></div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Huawei_ads_start" name="Huawei_ads_start" placeholder="start"  class="form-control" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ads_huawei">
                                                                        <div class="divider divider-start divider_huawei">
                                                                            <div class="divider-text"><b>Huawei</b></div>
                                                                        </div>
                                                                        <div class="row" >
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Huawei_ads_banner_huawei" name="Huawei_ads_banner_huawei" placeholder="banner"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Huawei_ads_inter_huawei" name="Huawei_ads_inter_huawei" placeholder="inter"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Huawei_ads_reward_huawei" name="Huawei_ads_reward_huawei" placeholder="reward"   class="form-control" style="display: none" >
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Huawei_ads_native_huawei" name="Huawei_ads_native_huawei" placeholder="native"   class="form-control"  style="display: none">
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Huawei_ads_splash_huawei" name="Huawei_ads_splash_huawei" placeholder="splash"   class="form-control" style="display: none" >
                                                                            </div>
                                                                            <div class="form-group col-sm-4">
                                                                                <input type="text" id="Huawei_ads_roll_huawei" name="Huawei_ads_roll_huawei" placeholder="roll"   class="form-control" style="display: none" >
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane p-3" id="chplay" role="tabpanel">

                                            <div data-repeater-item="" class="row input_buildinfo">
                                                <div class="form-group col-lg-6">
                                                    <label for="name">Store Name (CH Play) </label>
                                                    <select class="form-control select2" id="Chplay_buildinfo_store_name_x" name="Chplay_buildinfo_store_name_x">
                                                        <option value="0" >---Vui lòng chọn---</option>
                                                        @foreach($store_name as $item)
                                                            <option value="{{$item->id}}">{{$item->dev_name}} : {{$item->store_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">Link Store </label>
                                                    <input type="text" id="Chplay_buildinfo_link_store" name="Chplay_buildinfo_link_store" class="form-control" >
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
                                                <div class="form-group col-lg-6">
                                                    <label for="name">Link App</label>
                                                    <input type="text" id="Chplay_buildinfo_link_app" name="Chplay_buildinfo_link_app" class="form-control" >
                                                </div>
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">Link Policy</label>
                                                    <input type="text" id="Chplay_policy" name="Chplay_policy" class="form-control" >
                                                </div>
                                            </div>
                                            <div data-repeater-item="" class="row">
                                                <div class="form-group col-lg-6">
                                                    <label for="name">Keystore Profile</label>
                                                    <div class="inner row">
                                                        <div class="col-md-10 col-10">
                                                            <select class="form-control select2" id="Chplay_keystore_profile" name="Chplay_keystore_profile">
                                                                <option value="0">---Vui lòng chọn---</option>
                                                                @foreach($keystore as $item)
                                                                    <option value="{{$item->name_keystore}}">{{$item->name_keystore}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 col-4">
                                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addKeystore" style="border-radius: 0 3px 3px 0; box-shadow: none;">...
                                                            </button>
                                                        </div>
                                                    </div>
{{--                                                    <input type="text" id="Chplay_keystore_profile" name="Chplay_keystore_profile" class="form-control" >--}}
                                                </div>
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">SDK</label>
                                                    <input type="text" id="Chplay_sdk" name="Chplay_sdk" class="form-control" >
                                                </div>
                                            </div>

                                            <span class="card-title-desc" id="p_buildinfo_keystore_chplay"></span>
                                            <button type="button" class="btn btn-link waves-effect" id="button_buildinfo_keystore_chplay"><i class="mdi mdi-content-copy"></i></button></td>

                                        </div>
                                        <div class="tab-pane p-3" id="amazon" role="tabpanel">
                                            <div data-repeater-item="" class="row input_buildinfo">
                                                <div class="form-group col-lg-6">
                                                    <label for="name">Store Name (Amazon) </label>
                                                    <select class="form-control select2" id="Amazon_buildinfo_store_name_x" name="Amazon_buildinfo_store_name_x">
                                                        <option value="0" >---Vui lòng chọn---</option>
                                                        @foreach($store_name_amazon as $item)
                                                            <option value="{{$item->id}}">{{$item->amazon_dev_name}} : {{$item->amazon_store_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">Link Store </label>
                                                    <input type="text" id="Amazon_buildinfo_link_store" name="Amazon_buildinfo_link_store" class="form-control" >
                                                </div>
                                            </div>
                                            <div data-repeater-item="" class="row">
                                                <div class="form-group col-lg-6 input_buildinfo_keystore">
                                                    <label for="name">Email Dev</label>
                                                    <input type="text" id="Amazon_buildinfo_email_dev_x" name="Amazon_buildinfo_email_dev_x" class="form-control" >
                                                </div>
                                                <div class="form-group col-lg-6 input_status">
                                                    <label for="name">Trạng thái Ứng dụng</label>
                                                    <div>
                                                        <select class="form-control" id="Amazon_status" name="Amazon_status">
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
                                                <div class="form-group col-lg-6 input_buildinfo_keystore">
                                                    <label for="name">Link App</label>
                                                    <input type="text" id="Amazon_buildinfo_link_app" name="Amazon_buildinfo_link_app" class="form-control" >
                                                </div>
                                                <div class="form-group col-lg-6 input_buildinfo_keystore">
                                                    <label for="name">Link Policy</label>
                                                    <input type="text" id="Amazon_policy" name="Amazon_policy" class="form-control" >
                                                </div>
                                            </div>
                                            <div data-repeater-item="" class="row">
                                                <div class="form-group col-lg-6">
                                                    <label for="name">Keystore Profile</label>
                                                    <div class="inner row">
                                                        <div class="col-md-10 col-10">
                                                            <select class="form-control select2" id="Amazon_keystore_profile" name="Amazon_keystore_profile">
                                                                <option value="0">---Vui lòng chọn---</option>
                                                                @foreach($keystore as $item)
                                                                    <option value="{{$item->name_keystore}}">{{$item->name_keystore}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 col-4">
                                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addKeystore" style="border-radius: 0 3px 3px 0; box-shadow: none;">...
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">SDK</label>
                                                    <input type="text" id="Amazon_sdk" name="Amazon_sdk" class="form-control" >
                                                </div>
                                            </div>
                                            <span class="card-title-desc" id="p_buildinfo_keystore_amazon"></span>
                                            <button type="button" class="btn btn-link waves-effect" id="button_buildinfo_keystore_amazon"><i class="mdi mdi-content-copy"></i></button></td>
                                        </div>

                                        <div class="tab-pane p-3" id="samsung" role="tabpanel">
                                            <div data-repeater-item="" class="row input_buildinfo">
                                                <div class="form-group col-lg-6">
                                                    <label for="name">Store Name (Samsung) </label>
                                                    <select class="form-control select2" id="Samsung_buildinfo_store_name_x" name="Samsung_buildinfo_store_name_x">
                                                        <option value="0"  >---Vui lòng chọn---</option>
                                                        @foreach($store_name_samsung as $item)
                                                            <option value="{{$item->id}}">{{$item->samsung_dev_name}} : {{$item->samsung_store_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">Link Store </label>
                                                    <input type="text" id="Samsung_buildinfo_link_store" name="Samsung_buildinfo_link_store" class="form-control" >
                                                </div>
                                            </div>
                                            <div data-repeater-item="" class="row">
                                                <div class="form-group col-lg-6 input_buildinfo_keystore">
                                                    <label for="name">Email Dev</label>
                                                    <input type="text" id="Samsung_buildinfo_email_dev_x" name="Samsung_buildinfo_email_dev_x" class="form-control" >
                                                </div>
                                                <div class="form-group col-lg-6 input_status">
                                                    <label for="name">Trạng thái Ứng dụng</label>
                                                    <div>
                                                        <select class="form-control" id="Samsung_status" name="Samsung_status">
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
                                                <div class="form-group col-lg-6 input_buildinfo_keystore">
                                                    <label for="name">Link App</label>
                                                    <input type="text" id="Samsung_buildinfo_link_app" name="Samsung_buildinfo_link_app" class="form-control" >
                                                </div>
                                                <div class="form-group col-lg-6 input_buildinfo_keystore">
                                                    <label for="name">Link Policy</label>
                                                    <input type="text" id="Samsung_policy" name="Samsung_policy" class="form-control" >
                                                </div>
                                            </div>
                                            <div data-repeater-item="" class="row">
                                                <div class="form-group col-lg-6">
                                                    <label for="name">Keystore Profile</label>
                                                    <div class="inner row">
                                                        <div class="col-md-10 col-10">
                                                            <select class="form-control select2" id="Samsung_keystore_profile" name="Samsung_keystore_profile">
                                                                <option value="0">---Vui lòng chọn---</option>
                                                                @foreach($keystore as $item)
                                                                    <option value="{{$item->name_keystore}}">{{$item->name_keystore}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 col-4">
                                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addKeystore" style="border-radius: 0 3px 3px 0; box-shadow: none;">...
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">SDK</label>
                                                    <input type="text" id="Samsung_sdk" name="Samsung_sdk" class="form-control" >
                                                </div>
                                            </div>
                                            <span class="card-title-desc" id="p_buildinfo_keystore_samsung"></span>
                                            <button type="button" class="btn btn-link waves-effect" id="button_buildinfo_keystore_samsung"><i class="mdi mdi-content-copy"></i></button></td>
                                        </div>
                                        <div class="tab-pane p-3" id="xiaomi" role="tabpanel">
                                            <div data-repeater-item="" class="row input_buildinfo">
                                                <div class="form-group col-lg-6">
                                                    <label for="name">Store Name (Xiaomi) </label>
                                                    <select class="form-control select2" id="Xiaomi_buildinfo_store_name_x" name="Xiaomi_buildinfo_store_name_x">
                                                        <option value="0"  >---Vui lòng chọn---</option>
                                                        @foreach($store_name_xiaomi as $item)
                                                            <option value="{{$item->id}}">{{$item->xiaomi_dev_name}} : {{$item->xiaomi_store_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">Link Store </label>
                                                    <input type="text" id="Xiaomi_buildinfo_link_store" name="Xiaomi_buildinfo_link_store" class="form-control" >
                                                </div>
                                            </div>
                                            <div data-repeater-item="" class="row">
                                                <div class="form-group col-lg-6 input_buildinfo_keystore">
                                                    <label for="name">Email Dev</label>
                                                    <input type="text" id="Xiaomi_buildinfo_email_dev_x" name="Xiaomi_buildinfo_email_dev_x" class="form-control" >
                                                </div>
                                                <div class="form-group col-lg-6 input_status">
                                                    <label for="name">Trạng thái Ứng dụng</label>
                                                    <div>
                                                        <select class="form-control" id="Xiaomi_status" name="Xiaomi_status">
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
                                                <div class="form-group col-lg-6 input_buildinfo_keystore">
                                                    <label for="name">Link App</label>
                                                    <input type="text" id="Xiaomi_buildinfo_link_app" name="Xiaomi_buildinfo_link_app" class="form-control" >
                                                </div>
                                                <div class="form-group col-lg-6 input_buildinfo_keystore">
                                                    <label for="name">Link Policy</label>
                                                    <input type="text" id="Xiaomi_policy" name="Xiaomi_policy" class="form-control" >
                                                </div>
                                            </div>
                                            <div data-repeater-item="" class="row">
                                                <div class="form-group col-lg-6">
                                                    <label for="name">Keystore Profile</label>
                                                    <div class="inner row">
                                                        <div class="col-md-10 col-10">
                                                            <select class="form-control select2" id="Xiaomi_keystore_profile" name="Xiaomi_keystore_profile">
                                                                <option value="0">---Vui lòng chọn---</option>
                                                                @foreach($keystore as $item)
                                                                    <option value="{{$item->name_keystore}}">{{$item->name_keystore}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 col-4">
                                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addKeystore" style="border-radius: 0 3px 3px 0; box-shadow: none;">...
                                                            </button>
                                                        </div>
                                                    </div>
{{--                                                    <input type="text" id="Xiaomi_keystore_profile" name="Xiaomi_keystore_profile" class="form-control" >--}}
                                                </div>
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">SDK</label>
                                                    <input type="text" id="Xiaomi_sdk" name="Xiaomi_sdk" class="form-control" >
                                                </div>
                                            </div>
                                            <span class="card-title-desc" id="p_buildinfo_keystore_xiaomi"></span>
                                            <button type="button" class="btn btn-link waves-effect" id="button_buildinfo_keystore_xiaomi"><i class="mdi mdi-content-copy"></i></button></td>
                                        </div>
                                        <div class="tab-pane p-3" id="oppo" role="tabpanel">
                                            <div data-repeater-item="" class="row input_buildinfo">
                                                <div class="form-group col-lg-6">
                                                    <label for="name">Store Name (OPPO) </label>
                                                    <select class="form-control select2" id="Oppo_buildinfo_store_name_x" name="Oppo_buildinfo_store_name_x">
                                                        <option value="0"  >---Vui lòng chọn---</option>
                                                        @foreach($store_name_oppo as $item)
                                                            <option value="{{$item->id}}">{{$item->oppo_dev_name}} : {{$item->oppo_store_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">Link Store </label>
                                                    <input type="text" id="Oppo_buildinfo_link_store" name="Oppo_buildinfo_link_store" class="form-control" >
                                                </div>
                                            </div>
                                            <div data-repeater-item="" class="row">
                                                <div class="form-group col-lg-6 input_buildinfo_keystore">
                                                    <label for="name">Email Dev</label>
                                                    <input type="text" id="Oppo_buildinfo_email_dev_x" name="Oppo_buildinfo_email_dev_x" class="form-control" >
                                                </div>
                                                <div class="form-group col-lg-6 input_status">
                                                    <label for="name">Trạng thái Ứng dụng</label>
                                                    <div>
                                                        <select class="form-control" id="Oppo_status" name="Oppo_status">
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
                                                <div class="form-group col-lg-6 input_buildinfo_keystore">
                                                    <label for="name">Link App</label>
                                                    <input type="text" id="Oppo_buildinfo_link_app" name="Oppo_buildinfo_link_app" class="form-control" >
                                                </div>
                                                <div class="form-group col-lg-6 input_buildinfo_keystore">
                                                    <label for="name">Link Policy</label>
                                                    <input type="text" id="Oppo_policy" name="Oppo_policy" class="form-control" >
                                                </div>
                                            </div>
                                            <div data-repeater-item="" class="row">
                                                <div class="form-group col-lg-6">
                                                    <label for="name">Keystore Profile</label>
                                                    <div class="inner row">
                                                        <div class="col-md-10 col-10">
                                                            <select class="form-control select2" id="Oppo_keystore_profile" name="Oppo_keystore_profile">
                                                                <option value="0">---Vui lòng chọn---</option>
                                                                @foreach($keystore as $item)
                                                                    <option value="{{$item->name_keystore}}">{{$item->name_keystore}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 col-4">
                                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addKeystore" style="border-radius: 0 3px 3px 0; box-shadow: none;">...
                                                            </button>
                                                        </div>
                                                    </div>
{{--                                                    <input type="text" id="Oppo_keystore_profile" name="Oppo_keystore_profile" class="form-control" >--}}
                                                </div>
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">SDK</label>
                                                    <input type="text" id="Oppo_sdk" name="Oppo_sdk" class="form-control" >
                                                </div>
                                            </div>
                                            <span class="card-title-desc" id="p_buildinfo_keystore_oppo"></span>
                                            <button type="button" class="btn btn-link waves-effect" id="button_buildinfo_keystore_oppo"><i class="mdi mdi-content-copy"></i></button></td>
                                        </div>
                                        <div class="tab-pane p-3" id="vivo" role="tabpanel">
                                            <div data-repeater-item="" class="row input_buildinfo">
                                                <div class="form-group col-lg-6">
                                                    <label for="name">Store Name (Vivo) </label>
                                                    <select class="form-control select2" id="Vivo_buildinfo_store_name_x" name="Vivo_buildinfo_store_name_x">
                                                        <option value="0" >---Vui lòng chọn---</option>
                                                        @foreach($store_name_vivo as $item)
                                                            <option value="{{$item->id}}">{{$item->vivo_dev_name}} : {{$item->vivo_store_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">Link Store </label>
                                                    <input type="text" id="Vivo_buildinfo_link_store" name="Vivo_buildinfo_link_store" class="form-control" >
                                                </div>
                                            </div>
                                            <div data-repeater-item="" class="row">
                                                <div class="form-group col-lg-6 input_buildinfo_keystore">
                                                    <label for="name">Email Dev</label>
                                                    <input type="text" id="Vivo_buildinfo_email_dev_x" name="Vivo_buildinfo_email_dev_x" class="form-control" >
                                                </div>
                                                <div class="form-group col-lg-6 input_status">
                                                    <label for="name">Trạng thái Ứng dụng</label>
                                                    <div>
                                                        <select class="form-control" id="Vivo_status" name="Vivo_status">
                                                            <option value="100">Mặc định</option>
                                                            <option value="0">UnPublished</option>
                                                            <option value="1">Published</option>
                                                            <option value="2">Removed</option>
                                                            <option value="3">To be published</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div data-repeater-item="" class="row">
                                                <div class="form-group col-lg-6 input_buildinfo_keystore">
                                                    <label for="name">Link App</label>
                                                    <input type="text" id="Vivo_buildinfo_link_app" name="Vivo_buildinfo_link_app" class="form-control" >
                                                </div>
                                                <div class="form-group col-lg-6 input_buildinfo_keystore">
                                                    <label for="name">Link Policy</label>
                                                    <input type="text" id="Vivo_policy" name="Vivo_policy" class="form-control" >
                                                </div>
                                            </div>
                                            <div data-repeater-item="" class="row">
                                                <div class="form-group col-lg-6">
                                                    <label for="name">Keystore Profile</label>
                                                    <div class="inner row">
                                                        <div class="col-md-10 col-10">
                                                            <select class="form-control select2" id="Vivo_keystore_profile" name="Vivo_keystore_profile">
                                                                <option value="0">---Vui lòng chọn---</option>
                                                                @foreach($keystore as $item)
                                                                    <option value="{{$item->name_keystore}}">{{$item->name_keystore}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 col-4">
                                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addKeystore" style="border-radius: 0 3px 3px 0; box-shadow: none;">...
                                                            </button>
                                                        </div>
                                                    </div>
{{--                                                    <input type="text" id="Vivo_keystore_profile" name="Vivo_keystore_profile" class="form-control" >--}}
                                                </div>
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">SDK</label>
                                                    <input type="text" id="Vivo_sdk" name="Vivo_sdk" class="form-control" >
                                                </div>
                                            </div>
                                            <span class="card-title-desc" id="p_buildinfo_keystore_vivo"></span>
                                            <button type="button" class="btn btn-link waves-effect" id="button_buildinfo_keystore_vivo"><i class="mdi mdi-content-copy"></i></button></td>
                                        </div>
                                        <div class="tab-pane p-3" id="huawei" role="tabpanel">
                                            <div data-repeater-item="" class="row input_buildinfo">
                                                <div class="form-group col-lg-6">
                                                    <label for="name">Store Name (Huawei) </label>
                                                    <select class="form-control select2" id="Huawei_buildinfo_store_name_x" name="Huawei_buildinfo_store_name_x">
                                                        <option value="0" >---Vui lòng chọn---</option>
                                                        @foreach($store_name_huawei as $item)
                                                            <option value="{{$item->id}}">{{$item->huawei_dev_name}} : {{$item->huawei_store_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">AppID </label>
                                                    <input type="text" id="Huawei_appId" name="Huawei_appId" class="form-control" >
                                                </div>
                                            </div>
                                            <div data-repeater-item="" class="row">
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">Link Store </label>
                                                    <input type="text" id="Huawei_buildinfo_link_store" name="Huawei_buildinfo_link_store" class="form-control" >
                                                </div>
                                                <div class="form-group col-lg-6 input_buildinfo_keystore">
                                                    <label for="name">Email Dev</label>
                                                    <input type="text" id="Huawei_buildinfo_email_dev_x" name="Huawei_buildinfo_email_dev_x" class="form-control" >
                                                </div>

                                            </div>
                                            <div data-repeater-item="" class="row">
                                                <div class="form-group col-lg-6 input_buildinfo_keystore">
                                                    <label for="name">Link App</label>
                                                    <input type="text" id="Huawei_buildinfo_link_app" name="Huawei_buildinfo_link_app" class="form-control" >
                                                </div>
                                                <div class="form-group col-lg-6 input_buildinfo_keystore">
                                                    <label for="name">Link Policy</label>
                                                    <input type="text" id="Huawei_policy" name="Huawei_policy" class="form-control" >
                                                </div>
                                            </div>
                                            <div data-repeater-item="" class="row">
                                                <div class="form-group col-lg-4 ">
                                                    <label for="name">SDK</label>
                                                    <input type="text" id="Huawei_sdk" name="Huawei_sdk" class="form-control" >
                                                </div>
                                                <div class="form-group col-lg-4">
                                                    <label for="name">Keystore Profile</label>
                                                    <div class="inner row">
                                                        <div class="col-md-10 col-10">
                                                            <select class="form-control select2" id="Huawei_keystore_profile" name="Huawei_keystore_profile">
                                                                <option value="0">---Vui lòng chọn---</option>
                                                                @foreach($keystore as $item)
                                                                    <option value="{{$item->name_keystore}}">{{$item->name_keystore}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 col-4">
                                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addKeystore" style="border-radius: 0 3px 3px 0; box-shadow: none;">...
                                                            </button>
                                                        </div>
                                                    </div>
{{--                                                    <input type="text" id="Huawei_keystore_profile" name="Huawei_keystore_profile" class="form-control" >--}}
                                                </div>
                                                <div class="form-group col-lg-4 input_status">
                                                    <label for="name">Trạng thái Ứng dụng</label>
                                                    <div>
                                                        <select class="form-control" id="Huawei_status" name="Huawei_status">
                                                            <option value="100">Mặc định</option>
                                                            <option value="0">Released</option>
                                                            <option value="1">Release Rejected</option>
                                                            <option value="2">Removed (including forcible removal)</option>
                                                            <option value="3">Releasing</option>
                                                            <option value="4">Reviewing</option>
                                                            <option value="5">Updating</option>
                                                            <option value="6">Removal requested</option>
                                                            <option value="7">Draft</option>
                                                            <option value="8">Update rejected</option>
                                                            <option value="9">Removal requested</option>
                                                            <option value="10">Removed by developer</option>
                                                            <option value="11">Release canceled</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="card-title-desc" id="p_buildinfo_keystore_huawei"></span>
                                            <button type="button" class="btn btn-link waves-effect" id="button_buildinfo_keystore_huawei"><i class="mdi mdi-content-copy"></i></button></td>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<div class="modal fade bd-example-modal-xl" id="ajaxPartTimeModel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelPartTimeHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="parttimeprojectForm" name="parttimeprojectForm" class="form-horizontal">
                    <input type="hidden" name="project_id" id="part_time_project_id">
                    <div class="divider Chplay_status">
                        <div class="divider-text"><img src="img/icon/google.png"></div>
                    </div>
                    <div class="row Chplay_status">
                        <div class="form-group col-lg-6 ">
                            <label for="name">Store Name (CH Play) </label>
                            <select class="form-control select2" id="Chplay_buildinfo_store_name_x1" name="Chplay_buildinfo_store_name_x">
                                <option value="0" >---Vui lòng chọn---</option>
                                @foreach($store_name as $item)
                                    <option value="{{$item->id}}">{{$item->dev_name}} : {{$item->store_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-6 ">
                            <label for="name">Trạng thái Ứng dụng (CHPlay)</label>
                            <div>
                                <select class="form-control" id="Chplay_status1" name="Chplay_status">
                                    <option value="0">Mặc định</option>
                                    <option value="1">Publish</option>
                                    <option value="6">Check</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="divider Amazon_status">
                        <div class="divider-text"><img src="img/icon/amazon.png"></div>
                    </div>
                    <div class="row Amazon_status">
                        <div class="form-group col-lg-6 ">
                            <label for="name">Store Name (Amazon) </label>
                            <select class="form-control select2" id="Amazon_buildinfo_store_name_x1" name="Amazon_buildinfo_store_name_x">
                                <option value="0" >---Vui lòng chọn---</option>
                                @foreach($store_name_amazon as $item)
                                    <option value="{{$item->id}}">{{$item->amazon_dev_name}} : {{$item->amazon_store_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-6 ">
                            <label for="name">Trạng thái Ứng dụng (Amazon) </label>
                            <div>
                                <select class="form-control" id="Amazon_status1" name="Amazon_status">
                                    <option value="0">Mặc định</option>
                                    <option value="1">Publish</option>
                                    <option value="6">Check</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="divider Samsung_status">
                        <div class="divider-text"><img src="img/icon/samsung.png"></div>
                    </div>
                    <div class="row Samsung_status">
                        <div class="form-group col-lg-6 ">
                            <label for="name">Store Name (Samsung) </label>
                            <select class="form-control select2" id="Samsung_buildinfo_store_name_x1" name="Samsung_buildinfo_store_name_x">
                                <option value="0"  >---Vui lòng chọn---</option>
                                @foreach($store_name_samsung as $item)
                                    <option value="{{$item->id}}">{{$item->samsung_dev_name}} : {{$item->samsung_store_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-6 ">
                            <label for="name">Trạng thái Ứng dụng (Samsung)</label>
                            <div>
                                <select class="form-control" id="Samsung_status1" name="Samsung_status">
                                    <option value="0">Mặc định</option>
                                    <option value="1">Publish</option>
                                    <option value="6">Check</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="divider Xiaomi_status">
                        <div class="divider-text"><img src="img/icon/xiaomi.png"></div>
                    </div>
                    <div class="row Xiaomi_status">
                        <div class="form-group col-lg-6 ">
                            <label for="name">Store Name (Xiaomi) </label>
                            <select class="form-control select2" id="Xiaomi_buildinfo_store_name_x1" name="Xiaomi_buildinfo_store_name_x">
                                <option value="0"  >---Vui lòng chọn---</option>
                                @foreach($store_name_xiaomi as $item)
                                    <option value="{{$item->id}}">{{$item->xiaomi_dev_name}} : {{$item->xiaomi_store_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-6 ">
                            <label for="name">Trạng thái Ứng dụng (Xiaomi)</label>
                            <div>
                                <select class="form-control" id="Xiaomi_status1" name="Xiaomi_status">
                                    <option value="0">Mặc định</option>
                                    <option value="1">Publish</option>
                                    <option value="6">Check</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="divider Oppo_status">
                        <div class="divider-text"><img src="img/icon/oppo.png"></div>
                    </div>
                    <div  class="row Oppo_status">
                        <div class="form-group col-lg-6 ">
                            <label for="name">Store Name (OPPO) </label>
                            <select class="form-control select2" id="Oppo_buildinfo_store_name_x1" name="Oppo_buildinfo_store_name_x">
                                <option value="0"  >---Vui lòng chọn---</option>
                                @foreach($store_name_oppo as $item)
                                    <option value="{{$item->id}}">{{$item->oppo_dev_name}} : {{$item->oppo_store_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-6 ">
                            <label for="name">Trạng thái Ứng dụng (Oppo)</label>
                            <div>
                                <select class="form-control" id="Oppo_status1" name="Oppo_status">
                                    <option value="0">Mặc định</option>
                                    <option value="1">Publish</option>
                                    <option value="6">Check</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="divider Vivo_status">
                        <div class="divider-text"><img src="img/icon/vivo.png"></div>
                    </div>
                    <div class="row Vivo_status">
                        <div class="form-group col-lg-6 ">
                            <label for="name">Store Name (Vivo) </label>
                            <select class="form-control select2" id="Vivo_buildinfo_store_name_x1" name="Vivo_buildinfo_store_name_x">
                                <option value="0" >---Vui lòng chọn---</option>
                                @foreach($store_name_vivo as $item)
                                    <option value="{{$item->id}}">{{$item->vivo_dev_name}} : {{$item->vivo_store_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-6 ">
                            <label for="name">Trạng thái Ứng dụng (Vivo)</label>
                            <div>
                                <select class="form-control" id="Vivo_status1" name="Vivo_status">
                                    <option value="100">Mặc định</option>
                                    <option value="0">UnPublished</option>
                                    <option value="1">Published</option>
                                    <option value="2">Removed</option>
                                    <option value="3">To be published</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="divider Huawei_status">
                        <div class="divider-text"><img src="img/icon/huawei.png"></div>
                    </div>
                    <div class="row Huawei_status">
                        <div class="form-group col-lg-6 ">
                            <label for="name">Store Name (Huawei) </label>
                            <select class="form-control select2" id="Huawei_buildinfo_store_name_x1" name="Huawei_buildinfo_store_name_x">
                                <option value="0" >---Vui lòng chọn---</option>
                                @foreach($store_name_huawei as $item)
                                    <option value="{{$item->id}}">{{$item->huawei_dev_name}} : {{$item->huawei_store_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-lg-6  ">
                            <label for="name">Trạng thái Ứng dụng (Huawei)</label>
                            <div>
                                <select class="form-control" id="Huawei_status1" name="Huawei_status">
                                    <option value="100">Mặc định</option>
                                    <option value="0">Released</option>
                                    <option value="1">Release Rejected</option>
                                    <option value="2">Removed (including forcible removal)</option>
                                    <option value="3">Releasing</option>
                                    <option value="4">Reviewing</option>
                                    <option value="5">Updating</option>
                                    <option value="6">Removal requested</option>
                                    <option value="7">Draft</option>
                                    <option value="8">Update rejected</option>
                                    <option value="9">Removal requested</option>
                                    <option value="10">Removed by developer</option>
                                    <option value="11">Release canceled</option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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

<div class="modal fade bd-example-modal-xl" id="showPolicy" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeadingPolicy"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="policy-1">
                    <label for="name">Policy 1 <button type="button" onclick="copy1()" class="btn btn-link waves-effect copyboard"><i class="mdi mdi-content-copy"></i></button> </label>
                    <textarea  type="text" id="policy1"  name="policy1" rows="8" class="form-control" > </textarea>
                </div>
                <div class="policy-2">
                    <label  for="name">Policy 2 <button type="button" onclick="copy2()" class="btn btn-link waves-effect copyboard"><i class="mdi mdi-content-copy"></i></button></label>
                    <textarea  type="text" id="policy2"  name="policy2" rows="8" class="form-control" > </textarea>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




<div class="modal fade bd-example-modal-xl" id="editDesEN"  role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelEditDesEN"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="EditDesEN" name="EditDesEN" class="form-horizontal">
                    <input type="hidden" name="project_id" id="project_id_edit_desEN">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div data-repeater-item="" class="row">
                                        <div class="form-group col-lg-12">
                                            <label for="name">Title App   &nbsp; &nbsp; &nbsp;
                                                <span class="font-13 text-muted" id="count_title_app_en"></span>
                                                <button type="button" onclick="copyTitleEN()" class="btn btn-link waves-effect copyboard"><i class="mdi mdi-content-copy"></i></button>
                                            </label>
                                            <input type="text" id="title_app_en" name="title_app" class="form-control">



                                        </div>
                                        <div class="form-group col-lg-12">
                                            <label for="name">Summary &nbsp; &nbsp; &nbsp;
                                                <span class="font-13 text-muted" id="count_summary_en"></span>
                                                <button type="button" onclick="copySumEN()" class="btn btn-link waves-effect copyboard"><i class="mdi mdi-content-copy"></i></button>
                                            </label>
                                            <input type="text" id="summary_en" name="summary_en" class="form-control">
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <label for="name">Description</label><button type="button" onclick="copyDesEN()" class="btn btn-link waves-effect copyboard"><i class="mdi mdi-content-copy"></i></button> </label>
                                            <textarea id="des_en" name="des_en"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- end col -->
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" value="edit-des-en">Save changes</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="editDesVN"  role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelEditDesVN"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="EditDesVN" name="EditDesVN" class="form-horizontal">
                    <input type="hidden" name="project_id" id="project_id_edit_DesVN">


                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div data-repeater-item="" class="row">
                                        <div class="form-group col-lg-12">
                                            <label for="name">Tiêu đề ứng dụng &nbsp; &nbsp; &nbsp;
                                                <span class="font-13 text-muted" id="count_title_app_vn"></span>
                                                <button type="button" onclick="copyTitleVN()" class="btn btn-link waves-effect copyboard"><i class="mdi mdi-content-copy"></i></button> </label>
                                            <input type="text" id="title_app_vn" name="title_app" class="form-control">
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <label for="name">Mô tả ngắn &nbsp; &nbsp; &nbsp;
                                                <span class="font-13 text-muted" id="count_summary_vn"></span>
                                                <button type="button" onclick="copySumVN()" class="btn btn-link waves-effect copyboard"><i class="mdi mdi-content-copy"></i></button> </label>
                                            <input type="text" id="summary_vn" name="summary_vn" class="form-control">
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <label for="name">Mô tả</label><button type="button" onclick="copyDesVN()" class="btn btn-link waves-effect copyboard"><i class="mdi mdi-content-copy"></i></button> </label>
                                            <textarea id="des_vn" name="des_vn"></textarea>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div> <!-- end col -->
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" value="edit-des-en">Save changes</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-xl" id="buildandcheckModel" runat="server" role="dialog">
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
                                            <textarea id="project_data" name="project_data" rows="35" tyle="width: 100%"></textarea>
                                        </div>

                                        <div class="form-group col-lg-8">
                                            <div class="divider Chplay_status">
                                                <div class="divider-text"><img src="img/icon/google.png"></div>
                                            </div>
                                            <div class="row Chplay_status">
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">Store Name (CH Play) </label>
                                                    <select class="form-control select2" id="Chplay_buildinfo_store_name_x2" name="Chplay_buildinfo_store_name_x">
                                                        <option value="0" >---Vui lòng chọn---</option>
                                                        @foreach($store_name as $item)
                                                            <option value="{{$item->id}}">{{$item->dev_name}} : {{$item->store_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">Trạng thái Ứng dụng (CHPlay)</label>
                                                    <div>
                                                        <select class="form-control" id="Chplay_status1" name="Chplay_status">
                                                            <option value="0">Mặc định</option>
                                                            <option value="1">Publish</option>
                                                            <option value="6">Check</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="divider Amazon_status">
                                                <div class="divider-text"><img src="img/icon/amazon.png"></div>
                                            </div>
                                            <div class="row Amazon_status">
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">Store Name (Amazon) </label>
                                                    <select class="form-control select2" id="Amazon_buildinfo_store_name_x2" name="Amazon_buildinfo_store_name_x">
                                                        <option value="0" >---Vui lòng chọn---</option>
                                                        @foreach($store_name_amazon as $item)
                                                            <option value="{{$item->id}}">{{$item->amazon_dev_name}} : {{$item->amazon_store_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">Trạng thái Ứng dụng (Amazon) </label>
                                                    <div>
                                                        <select class="form-control" id="Amazon_status1" name="Amazon_status">
                                                            <option value="0">Mặc định</option>
                                                            <option value="1">Publish</option>
                                                            <option value="6">Check</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="divider Samsung_status">
                                                <div class="divider-text"><img src="img/icon/samsung.png"></div>
                                            </div>
                                            <div class="row Samsung_status">
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">Store Name (Samsung) </label>
                                                    <select class="form-control select2" id="Samsung_buildinfo_store_name_x2" name="Samsung_buildinfo_store_name_x">
                                                        <option value="0"  >---Vui lòng chọn---</option>
                                                        @foreach($store_name_samsung as $item)
                                                            <option value="{{$item->id}}">{{$item->samsung_dev_name}} : {{$item->samsung_store_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">Trạng thái Ứng dụng (Samsung)</label>
                                                    <div>
                                                        <select class="form-control" id="Samsung_status1" name="Samsung_status">
                                                            <option value="0">Mặc định</option>
                                                            <option value="1">Publish</option>
                                                            <option value="6">Check</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="divider Xiaomi_status">
                                                <div class="divider-text"><img src="img/icon/xiaomi.png"></div>
                                            </div>
                                            <div class="row Xiaomi_status">
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">Store Name (Xiaomi) </label>
                                                    <select class="form-control select2" id="Xiaomi_buildinfo_store_name_x2" name="Xiaomi_buildinfo_store_name_x">
                                                        <option value="0"  >---Vui lòng chọn---</option>
                                                        @foreach($store_name_xiaomi as $item)
                                                            <option value="{{$item->id}}">{{$item->xiaomi_dev_name}} : {{$item->xiaomi_store_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">Trạng thái Ứng dụng (Xiaomi)</label>
                                                    <div>
                                                        <select class="form-control" id="Xiaomi_status1" name="Xiaomi_status">
                                                            <option value="0">Mặc định</option>
                                                            <option value="1">Publish</option>
                                                            <option value="6">Check</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="divider Oppo_status">
                                                <div class="divider-text"><img src="img/icon/oppo.png"></div>
                                            </div>
                                            <div  class="row Oppo_status">
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">Store Name (OPPO) </label>
                                                    <select class="form-control select2" id="Oppo_buildinfo_store_name_x2" name="Oppo_buildinfo_store_name_x">
                                                        <option value="0"  >---Vui lòng chọn---</option>
                                                        @foreach($store_name_oppo as $item)
                                                            <option value="{{$item->id}}">{{$item->oppo_dev_name}} : {{$item->oppo_store_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">Trạng thái Ứng dụng (Oppo)</label>
                                                    <div>
                                                        <select class="form-control" id="Oppo_status1" name="Oppo_status">
                                                            <option value="0">Mặc định</option>
                                                            <option value="1">Publish</option>
                                                            <option value="6">Check</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="divider Vivo_status">
                                                <div class="divider-text"><img src="img/icon/vivo.png"></div>
                                            </div>
                                            <div class="row Vivo_status">
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">Store Name (Vivo) </label>
                                                    <select class="form-control select2" id="Vivo_buildinfo_store_name_x2" name="Vivo_buildinfo_store_name_x">
                                                        <option value="0" >---Vui lòng chọn---</option>
                                                        @foreach($store_name_vivo as $item)
                                                            <option value="{{$item->id}}">{{$item->vivo_dev_name}} : {{$item->vivo_store_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">Trạng thái Ứng dụng (Vivo)</label>
                                                    <div>
                                                        <select class="form-control" id="Vivo_status1" name="Vivo_status">
                                                            <option value="100">Mặc định</option>
                                                            <option value="0">UnPublished</option>
                                                            <option value="1">Published</option>
                                                            <option value="2">Removed</option>
                                                            <option value="3">To be published</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="divider Huawei_status">
                                                <div class="divider-text"><img src="img/icon/huawei.png"></div>
                                            </div>
                                            <div class="row Huawei_status">
                                                <div class="form-group col-lg-6 ">
                                                    <label for="name">Store Name (Huawei) </label>
                                                    <select class="form-control select2" id="Huawei_buildinfo_store_name_x2" name="Huawei_buildinfo_store_name_x">
                                                        <option value="0" >---Vui lòng chọn---</option>
                                                        @foreach($store_name_huawei as $item)
                                                            <option value="{{$item->id}}">{{$item->huawei_dev_name}} : {{$item->huawei_store_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group col-lg-6  ">
                                                    <label for="name">Trạng thái Ứng dụng (Huawei)</label>
                                                    <div>
                                                        <select class="form-control" id="Huawei_status1" name="Huawei_status">
                                                            <option value="100">Mặc định</option>
                                                            <option value="0">Released</option>
                                                            <option value="1">Release Rejected</option>
                                                            <option value="2">Removed (including forcible removal)</option>
                                                            <option value="3">Releasing</option>
                                                            <option value="4">Reviewing</option>
                                                            <option value="5">Updating</option>
                                                            <option value="6">Removal requested</option>
                                                            <option value="7">Draft</option>
                                                            <option value="8">Update rejected</option>
                                                            <option value="9">Removal requested</option>
                                                            <option value="10">Removed by developer</option>
                                                            <option value="11">Release canceled</option>

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary"  value="build" >Update</button>

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



<div class="modal fade bd-example-modal-xl" id="changeKeystoreMultiple" runat="server" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Keystore</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <form class="repeater" id="changeKeystoreMultipleForm">
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <p for="name">{ID Project} | {Key C} | (Key A) | (Key S) |  (Key X) |  (Key O) |  (Key V) |  (Key H)</p>
                                            <textarea id="changeKeystoreMultiple" name="changeKeystoreMultiple" rows="20" style="width: 100%"></textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary" >Create</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="changeSdkMultiple" runat="server" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Sdk</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <form class="repeater" id="changeSdkMultipleForm">
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <p for="name">{ID Project} | {Sdk C} | (Sdk A) | (Sdk S) |  (Sdk X) |  (Sdk O) |  (Sdk V) |  (Sdk H)</p>
                                            <textarea id="changeSdkMultiple" name="changeSdkMultiple" rows="20" style="width: 100%"></textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary" >Create</button>
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













