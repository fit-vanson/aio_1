
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="userForm" name="userForm" class="form-horizontal">
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Tên tài khoản</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Tài khoản" value="" maxlength="50" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Email</label>
                        <div class="col-sm-12">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="" maxlength="50" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label">Mật khẩu</label>
                        <div class="col-sm-12">
                            <input type="text" id="password" class="form-control" name="password" placeholder="Password">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-5 control-label">Vai trò</label>
                        <div class="col-sm-12">
                        <select class="select2 col-sm-12" name="role_id[]" id="role_id" multiple>
                                @foreach($roles as $ro)
                                    <option
                                        value="{{$ro->id}}">{{$ro->name}}</option>
                                @endforeach
                        </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <label class="col-sm-5 control-label">
                                <input id="part_time" name="part_time" type="checkbox" checked="checked"> Part Time
                            </label>
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

