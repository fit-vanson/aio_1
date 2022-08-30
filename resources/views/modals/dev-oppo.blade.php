
<div class="modal fade bd-example-modal-xl" id="ajaxModelDev" aria-hidden="true">
    <div class="modal-dialog modal-xl" id="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

                <form id="devoppoForm" name="devoppoForm" class="form-horizontal">
                    <input type="hidden" name="id" id="id">
                    <div data-repeater-list="group-a">
                        <div data-repeater-item="" class="row">
                            <div class="form-group col-lg-6">
                                <label for="name">Ga Name</label>
                                <div class="inner row">
                                    <div class="col-md-12 col-12">
                                        <select class="form-control select2js" id="oppo_ga_name" name="oppo_ga_name">
                                            <option value="0">---Vui lòng chọn---</option>
                                            @foreach($ga_name as $item)
                                                <option value="{{$item->id}}">{{$item->ga_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="name">Email <span style="color: red">*</span></label>
                                <div class="inner row">
                                    <div class="col-md-10 col-10">
                                        <select class="form-control select2js" id="oppo_email" name="oppo_email">
                                            <option value="0">---Vui lòng chọn---</option>
                                                @foreach($ga_dev as $item)
                                                    <option value="{{$item->id}}">{{$item->gmail}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addGaDev" style="border-radius: 0 3px 3px 0; box-shadow: none;">...
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div data-repeater-item="" class="row">
                            <div class="form-group col-lg-6">
                                <label for="name">Dev Name  <span style="color: red">*</span></label>
                                <input type="text" id="oppo_dev_name" name="oppo_dev_name" class="form-control" required>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="name">Store Name  <span style="color: red">*</span></label>
                                <input type="text" id="oppo_store_name" name="oppo_store_name" class="form-control" required>
                            </div>
                        </div>

                        <div data-repeater-item="" class="row">
                            <div class="form-group col-lg-6">
                                <label for="name">Số điện thoại </label>
                                <input type="text" id="oppo_phone" name="oppo_phone" class="form-control">
                            </div>

                            <div class="form-group col-lg-6 ">
                                <label for="name">Mật khẩu</label>
                                <input type="text" id="oppo_pass" name="oppo_pass" class="form-control">
                            </div>


                        </div>
                        <div data-repeater-item="" class="row">
                            <div class="form-group col-lg-6">
                                <label for="name">Profile Info </label>
                                {{--                                <input type="text" id="oppo_profile_info" name="oppo_profile_info" class="form-control">--}}
                                <select class="form-control select2js" id="oppo_profile_info" name="oppo_profile_info">
                                    <option value="0">---Vui lòng chọn---</option>
                                    @foreach($profiles as $item)
                                        <option value="{{$item->id}}">{{$item->profile_name}} -   ({{$item->profile_ho_va_ten}} - {{$item->profile_add}})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="name">Trạng thái </label>
                                <div class="inner row">
                                    <div class="col-md-12 col-12">
                                        <select class="form-control" id="oppo_status" name="oppo_status">
                                            <option value="0">Chưa sử dụng</option>
                                            <option value="1">Đang phát triển</option>
                                            <option value="2">Đóng</option>
                                            <option value="3">Suspend</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label class="d-block mb-3">Thuộc tính :</label>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="company" name="attribute" class="custom-control-input" onchange="getit();" value="0">
                                    <label class="custom-control-label" for="company">Công ty</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="individual" name="attribute" class="custom-control-input" onchange="getit();" value="1" checked>
                                    <label class="custom-control-label" for="individual">Cá nhân</label>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="name">Công ty đăng ký </label>
                                <input id="oppo_company" name="oppo_company" class="form-control" />
                            </div>
                        </div>

                        <div data-repeater-item="" class="row">
                            <div class="form-group col-lg-6">
                                <label for="name">Địa chỉ </label>
                                <textarea id="oppo_add" name="oppo_add" class="form-control" rows="4" ></textarea>
                            </div>
                            <div class="form-group col-lg-6 ">
                                <label for="name">Ghi chú</label>
                                <textarea id="oppo_note" name="oppo_note" class="form-control" rows="4" ></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addGaDev" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Thêm mới Tài khoản</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

                <form id="addGaDevForm" name="addDevForm" class="form-horizontal">
                    <input type="hidden" name="gadev_id" id="gadev_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Gmail</label>
                        <div class="col-sm-12">
                            <input type="email" class="form-control" id="gmail" name="gmail" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Email Recover</label>
                        <div class="col-sm-12">
                            <input type="email" class="form-control" id="mailrecovery" name="mailrecovery">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label">VPN</label>
                        <div class="col-sm-12">
                            <input type="text" id="vpn_iplogin" class="form-control" name="vpn_iplogin">
                        </div>
                    </div>
                    <div class="form-group input_note">
                        <label class="col-sm-5 control-label">Ghi chú</label>
                        <div class="col-sm-12">
                            <textarea id="note" name="note" class="form-control" rows="4" ></textarea>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" value="create">Save changes
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>




