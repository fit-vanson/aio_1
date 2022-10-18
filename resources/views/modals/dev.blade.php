
<div class="modal fade bd-example-modal-xl" id="ajaxModelDev" aria-hidden="true">
    <div class="modal-dialog modal-xl" id="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="devForm" name="devForm" class="form-horizontal">
                    <input type="hidden" name="dev_id" id="dev_id">
                    <div data-repeater-list="group-a">
                        <div  class="row">
                            <div class="form-group col-lg-6">
                                <label for="name">Ga Name</label>
                                <select class="form-control" id="ga_id" name="ga_id" ></select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="name">Market</label>
                                <select class="form-control " id="market_id" name="market_id" required></select>
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="name">Dev Name  <span style="color: red">*</span></label>
                                <input type="text" id="dev_name" name="dev_name" class="form-control" required>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="name">Store Name  <span style="color: red">*</span></label>
                                <input type="text" id="store_name" name="store_name" class="form-control" required>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="name">Email 1 <span style="color: red">*</span></label>
                                <select class="form-control " id="mail_id_1" name="mail_id_1" required></select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="name">Email 2 </label>
                                <select class="form-control " id="mail_id_2" name="mail_id_2"></select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="name">Mã hóa đơn </label>
                                <input type="text" id="mahoadon" name="mahoadon" class="form-control">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="name">Mật khẩu </label>
                                <input type="text" id="pass" name="pass" class="form-control">
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="name">Logo</label>
                                <input type="url" id="info_logo" name="info_logo" class="form-control">
                            </div>
                            <div class="form-group col-lg-6 ">
                                <label for="name">Banner</label>
                                <input type="url" id="info_banner" name="info_banner" class="form-control">
                            </div>

                            <div class="form-group col-lg-6 ">
                                <label for="name">Policy</label>
                                <input type="url" id="info_policydev" name="info_policydev" class="form-control">
                            </div>
                            <div class="form-group col-lg-6 ">
                                <label for="name">Fanpage</label>
                                <input type="url" id="info_fanpage" name="info_fanpage" class="form-control">
                            </div>

                            <div class="form-group col-lg-6 ">
                                <label for="name">Số điện thoại</label>
                                <input type="text" id="info_phone" name="info_phone" class="form-control">
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="name">Trạng thái </label>
                                <select class="form-control ma_da" id="status" name="status">
                                    <option value="0">Chưa sử dụng</option>
                                    <option value="1">Đang phát triển</option>
                                    <option value="2">Đóng</option>
                                    <option value="3">Suspend</option>
                                </select>
                            </div>

                            <div class="form-group col-lg-6 ">
                                <label for="name">Profile Info <span style="color: red">*</span></label>
                                <select class="form-control" id="profile_id" name="profile_id" required></select>
                            </div>

                            <div class="form-group col-lg-6">
                                <label class="form-label mb-3 d-flex">Công ty || Cá nhân</label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="company" name="attribute" class="form-check-input" value="1">
                                    <label class="form-check-label" for="company">Công ty</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="person" name="attribute" class="form-check-input" value="0" checked="">
                                    <label class="form-check-label" for="person">Cá nhân</label>
                                </div>
                            </div>

                            <div class="form-group col-lg-6 ">
                                <label for="name">DEV ID</label>
                                <input type="text" id="api_dev_id" name="api_dev_id" class="form-control">
                            </div>

                            <div class="form-group col-lg-6 ">
                                <label for="name">API Client ID</label>
                                <input type="text" id="api_client_id" name="api_client_id" class="form-control">
                            </div>

                            <div class="form-group col-lg-6 ">
                                <label for="name">API Client Secret <code>VIVO: access_secret</code></label>
                                <input type="text" id="api_client_secret" name="api_client_secret" class="form-control">
                            </div>

                            <div class="form-group col-lg-6 ">
                                <label for="name">API Token <code id="get_token">get token</code></label>
                                <input type="text" id="api_token" name="api_token" class="form-control">
                            </div>

                            <div class="form-group col-lg-6 ">
                                <label for="name">API Access Key <code>VIVO: access_key </code></label>
                                <input type="text" id="api_access_key" name="api_access_key" class="form-control">
                            </div>

                            <div class="form-group col-lg-6 ">
                                <label for="name">Ghi chú</label>
                                <textarea id="note" name="note" class="form-control" rows="4" ></textarea>
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




