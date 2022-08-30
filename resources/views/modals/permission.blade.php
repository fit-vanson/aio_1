
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="permissionForm" name="permissionForm" class="form-horizontal">
                    <input type="hidden" name="permission_id" id="permission_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Tên Module</label>
                        <div class="col-sm-12">
                            <select class="select2 col-sm-12" name="module_parent" id="module_parent">
                                <option value="">Chọn tên Modul</option>
                                @foreach(config('permissions.table-module') as $table)
                                    <option value="{{$table}}">{{$table}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Mô tả Phân quyền</label>
                        <div class="col-sm-12">
                            @foreach(config('permissions.module-child') as $item)

                            <div class="custom-control custom-checkbox">
                                <label for="">
                                    <input name="module_child[]" type="checkbox" checked="checked" value="{{$item}}"> {{$item}}
                                </label>
                            </div>
                            @endforeach

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

