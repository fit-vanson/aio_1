
<div class="modal fade bd-example-modal-xl" id="ajaxModelProfile" aria-hidden="true">
    <div class="modal-dialog modal-xl" id="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="ProfileForm" name="ProfileForm" class="form-horizontal">
                    <input type="hidden" name="Profile_id" id="Profile_id">
                    <div data-repeater-list="group-a">
                        <div data-repeater-item="" class="row">
                            <div class="form-group col-lg-4">
                                <label for="name">Logo</label>
                                <input  id="logo" type="file" name="logo" class="form-control" hidden onchange="changeImg(this)" accept="image/png, image/jpeg">
                                <img id="avatar" class="thumbnail" width="100px" src="img/logo.png">
                            </div>

                            <div class="form-group col-lg-4">
                                <input type="file" name="profile_file" id="profile_file" class="filestyle" data-buttonname="btn-secondary" accept=".zip,.rar,.7zip">
                            </div>
                            <div class="form-group col-lg-4">
                                <label for="name">File đính kèm chứa</label>
                                <div class="checkbox-group required">
                                    <input type="checkbox" name="profile_anh_cccd" id="profile_anh_cccd"> : CCCD<br>
                                    <input type="checkbox" name="profile_anh_bang_lai" id="profile_anh_bang_lai"> : Bằng lái xe<br>
                                    <input type="checkbox" name="profile_anh_ngan_hang" id="profile_anh_ngan_hang"> :Thẻ Ngân hàng
                                </div>
                            </div>
                        </div>
                        <div data-repeater-item="" class="row">

                            <div class="form-group col-lg-6">
                                <label for="name">Name  <span style="color: red">*</span></label>
                                <input type="text" id="profile_name" name="profile_name" class="form-control" required>
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="name">Họ tên</label>
                                <input type="text" id="profile_ho_ten" name="profile_ho_ten" class="form-control">
                            </div>
                        </div>
                        <div data-repeater-item="" class="row">
                            <div class="form-group col-lg-6">
                                <label for="name">CCCD/CMND </label>
                                <input type="text" id="profile_cccd" name="profile_cccd" class="form-control">
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="name">SĐT</label>
                                <input type="text" id="profile_sdt" name="profile_sdt" class="form-control">
                            </div>
                        </div>
                        <div data-repeater-item="" class="row">

                            <div class="form-group col-lg-6">
                                <label for="name">Địa chỉ</label>
                                <textarea id="profile_dia_chi" name="profile_dia_chi" class="form-control" rows="4"></textarea>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="name">Ghi chú</label>
                                <textarea id="profile_note" name="profile_note" class="form-control" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <label class="d-block mb-3">Thuộc tính :</label>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="company" name="attribute" class="custom-control-input" value="0">
                                    <label class="custom-control-label" for="company">Công ty</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="individual" name="attribute" class="custom-control-input" value="1" checked>
                                    <label class="custom-control-label" for="individual">Cá nhân</label>
                                </div>
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

<div class="modal fade bd-example-modal-xl" id="ajaxModelProfileMultiple" aria-hidden="true">
    <div class="modal-dialog modal-xl" id="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeadingMultiple"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="ProfileMultipleForm" name="ProfileMultipleForm" class="form-horizontal">
                    <div class="row">
                        <div class="form-group col-lg-4">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="company1" name="attribute1" class="custom-control-input" onchange="getit();" value="0">
                                <label class="custom-control-label" for="company1">Công ty</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="individual1" name="attribute1" class="custom-control-input" onchange="getit();" value="1" checked="">
                                <label class="custom-control-label" for="individual1">Cá nhân</label>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <p id="getit" class="text-muted">{Mã ID Profile} | {số cccd hoặc cmt} | {Họ tên} | {ngày tháng năm sinh} | {giới tính} | {quê quán} | {ngày cấp} </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="name">Data</label>
                            <textarea id="profile_multiple" name="profile_multiple" class="form-control" rows="10"></textarea>
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





