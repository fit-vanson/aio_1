
<div class="modal fade bd-example-modal-xl" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

                <form id="templateForm" name="templateForm" enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" name="template_id" id="template_id">

                        <div  class="row">
                            <div class="form-group col-lg-3">
                                <label>Logo</label>
                                <input  id="logo" type="file" name="logo" class="form-control" hidden onchange="changeImg(this)" accept="image/*">
                                <img id="avatar" class="thumbnail" width="100px" src="img/logo.png">
                            </div>

                            <div class="form-group col-lg-3">
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

                            <div class="form-group col-lg-3 ">
                                <label for="name">Type</label>
                                <div>
                                    <select class="form-control" id="template_type" name="template_type">
                                        <option value="0">Chưa phân loại</option>
                                        <option value="1">App</option>
                                        <option value="2">Game</option>
                                        <option value="3">Laucher & Theme</option>
                                    </select>
                                </div>
                            </div>

{{--                            <div class="form-group col-lg-3">--}}
{{--                                <label for="name">DATA</label><p></p>--}}
{{--                                <input type="file" name="template_data" id="template_data" class="filestyle" data-buttonname="btn-secondary" accept=".zip">--}}
{{--                            </div>--}}
{{--                            <div class="form-group col-lg-3">--}}
{{--                                <label for="name">APK</label><p></p>--}}
{{--                                <input type="file" name="template_apk" id="template_apk" class="filestyle" data-buttonname="btn-secondary" accept=".apk">--}}
{{--                            </div>--}}

                            <div class="form-group col-lg-3 ">
                                <label for="name">Tên Template</label>
                                <input type="text" id="template_name" name="template_name" class="form-control">
                            </div>

                            <div class="form-group col-lg-3">
                                <label for="name">Mã Template <span style="color: red">*</span></label>
                                <input type="text" id="template" name="template" class="form-control" required>
                            </div>
                            <div class="form-group col-lg-3">
                                <label class="control-label">Ver Build</label>
                                <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <span class="input-group-addon bootstrap-touchspin-prefix input-group-prepend">
                                        <span class="input-group-text" id="template_ver"></span>
                                    </span>
                                    <input type="text" id="ver_build" name="ver_build" class="form-control">
                                </div>
                            </div>
                            <div class="form-group col-lg-3 ">
                                <label for="name">Convert Aab</label>
                                <div>
                                    <select class="form-control" id="convert_aab" name="convert_aab">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="name">Trạng thái</label>
                                <div>
                                    <select class="form-control" id="status" name="status">
                                        <option value="0">Mở</option>
                                        <option value="1">Tắt</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="name">Package</label>
                                <input type="text" id="package" name="package" class="form-control" >
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="name">Link của ứng dụng</label>
                                <input type="text" id="link" name="link" class="form-control" >
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="name">SDK</label>
                                <input type="text" id="sdk" name="sdk" class="form-control" >
                            </div>

                            <div class="form-group col-lg-12">
                                <label for="name">FILE <code> (APK, ZIP, JPG, WEBP)</code></label>
                                <div class="col-sm-12">
                                    <div class="dropzone" id="file_template"></div>
{{--                                    <input  class="dropzone" name="preview_template" type="file" multiple="multiple">--}}
{{--                                    <div  id="preview_template" data-maxfile="1" data-ext="image/png"  data-name="logo" ></div>--}}
                                </div>
{{--                                <input type="file" id="preview_template" name="preview_template[]" class="form-control" >--}}
{{--                                <input id="fileupload" type="file" name="files[]" data-url="server/php/" multiple>--}}
                            </div>
                        </div>

                        <div class="divider divider-start">
                            <div class="divider-text"><b>Admod</b></div>
                        </div>
                        <div>
                            <div class="col-lg-11 text-right">
                                <div class="row">
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
                            </div>
                        </div>
                        <div class="divider divider-start">
                            <div class="divider-text"><b>Start.io</b></div>
                        </div>
                        <div>
                            <div class="col-lg-11 text-right">
                                <div class="row">
                                    <div class="form-group col-lg-2 ">
                                        <label for="name" style="color:#00d986">Ads Start</label>
                                        <input type="checkbox" class="control-input" name="Check_ads_start" id="Check_ads_start" value="1">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="divider divider-start">
                            <div class="divider-text"><b>Huawei</b></div>
                        </div>
                        <div>
                            <div class="col-lg-11 text-right">
                                <div class="row">
                                    <div class="form-group col-lg-2">
                                        <label for="name">Ads banner</label>
                                        <input type="checkbox" class="control-input" name="Check_ads_banner_huawei" id="Check_ads_banner_huawei" value="1">
                                    </div>
                                    <div class="form-group col-lg-2 ">
                                        <label for="name">Ads inter</label>
                                        <input type="checkbox" class="control-input" name="Check_ads_inter_huawei" id="Check_ads_inter_huawei" value="1">

                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label for="name">Ads reward</label>
                                        <input type="checkbox" class="control-input" name="Check_ads_reward_huawei" id="Check_ads_reward_huawei" value="1">
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <label for="name">Ads native</label>
                                        <input type="checkbox" class="control-input" name="Check_ads_native_huawei" id="Check_ads_native_huawei" value="1">
                                    </div>
                                    <div class="form-group col-lg-2 ">
                                        <label for="name">Ads Splash</label>
                                        <input type="checkbox" class="control-input" name="Check_ads_splash_huawei" id="Check_ads_splash_huawei" value="1">
                                    </div>
                                    <div class="form-group col-lg-2 ">
                                        <label for="name">Ads Roll</label>
                                        <input type="checkbox" class="control-input" name="Check_ads_roll_huawei" id="Check_ads_roll_huawei" value="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6 ">
                                <label for="name">SCRIPT: Copy File & Folder </label>
                                <textarea id="script_copy" name="script_copy" class="form-control" rows="4" ></textarea>
                            </div>
                            <div class="form-group col-lg-6 ">
                                <label for="name">SCRIPT: IMG</label>
                                <textarea id="script_img" name="script_img" class="form-control" rows="4" ></textarea>
                            </div>

                            <div class="form-group col-lg-6 input_package">
                                <label for="name">SCRIPT: Convert SVG to XML </label>
                                <textarea id="script_svg2xml" name="script_svg2xml" class="form-control" rows="4" ></textarea>
                            </div>
                            <div class="form-group col-lg-6 input_title_app">
                                <label for="name">SCRIPT: Files</label>
                                <textarea id="script_file" name="script_file" class="form-control" rows="4" ></textarea>
                            </div>
                            <div class="form-group col-lg-6 input_package">
                                <label for="name">Permissions</label>
                                <textarea id="permissions" name="permissions" class="form-control" rows="4" ></textarea>
                            </div>
                            <div class="form-group col-lg-6 input_title_app">
                                <label for="name">Ghi chú</label>
                                <textarea id="note" name="note" class="form-control" rows="4" ></textarea>
                            </div>
                            <div class="form-group col-lg-6 input_package">
                                <label for="name">Policy 1 </label>
                                <textarea id="policy1" name="policy1" class="form-control" rows="4" ></textarea>
                            </div>
                            <div class="form-group col-lg-6 input_title_app">
                                <label for="name">Policy 2</label>
                                <textarea id="policy2" name="policy2" class="form-control" rows="4" ></textarea>
                            </div>
                            @foreach(\App\Models\Markets::all() as $market)
                            <div class="form-group col-lg-6">
                                <label for="name">Category {{$market->market_name}}</label>
{{--                                <input type="text" id="{{ucfirst(strtolower($market->market_name))}}_category" name="{{ucfirst(strtolower($market->market_name))}}_category" class="form-control" />--}}
                                <input type="text" id="category_{{$market->id}}" name="category[{{$market->id}}]" class="form-control" />
{{--                                <input type="text" id="category_{{$market->id}}_value" name="category[{{$market->id}}][value]" class="form-control" />--}}
{{--                                <input type="text" hidden id="category_{{$market->id}}" name="category[{{$market->id}}][market_id]" value="{{$market->id}}" class="form-control" />--}}
                            </div>
                            @endforeach

                        </div>


                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="saveBtn_template" value="create">Save changes
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




