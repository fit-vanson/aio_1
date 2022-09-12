
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="designForm" name="designForm" class="form-horizontal">
                    @csrf
                    <input type="hidden" name="design_id" id="design_id">
                    <input type="hidden" name="pro_id" id="pro_id">
                    <input type="hidden" name="pro_text" id="pro_text">
                    <div class="form-group project_select">
                        <label for="name" class="col-sm-5 control-label">Tên Project</label>
                        <div class="col-sm-12">
                            <select class="select2 col-sm-12" name="project_id" id="project_id"></select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Logo</label>
                        <div class="col-sm-12">
                            <div class="dropzone" id="logo" data-maxfile="1" data-ext="image/png"  data-name="logo" ></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                @foreach($lags as $key=>$lag)
                                <li class="nav-item">
                                    <a class="nav-link @if($key == 1) active @endif" data-toggle="tab" href="#{{$lag->lang_code}}" role="tab">
                                        <span class="d-block d-sm-none"><i class="fas fa-home"></i></span>
                                        <span class="d-none d-sm-block">{{$lag->lang_name}}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                @foreach($lags as $key=>$lag)
                                <div class="tab-pane @if($key == 1) active @endif p-3" id="{{$lag->lang_code}}" role="tabpanel">
                                    <div class="card">
                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <h5 class="card-title">Banner</h5>
                                                    <div class="dropzone" id="banner" data-maxfile="1" data-ext="image/jpeg,image/png" data-lang="{{$lag->id}}" data-lang_code="{{$lag->lang_code}}" data-name="banner"></div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <h5 class="card-title">Video</h5>
                                                    <div class="dropzone" id="video" data-maxfile="1" data-ext=".mp4" data-lang="{{$lag->id}}" data-lang_code="{{$lag->lang_code}}" data-name="video" ></div>

                                                </div>
                                            </div>

                                            <div class="row">
                                                @for($i=1; $i<=8; $i++)
                                                <div class="col-sm-3">
                                                    <h5 class="card-title">Preview {{$i}}</h5>
                                                    <div class="dropzone" id="preview" data-maxfile="1" data-ext="image/jpeg,image/png" data-lang="{{$lag->id}}" data-lang_code="{{$lag->lang_code}}" data-name="pr{{$i}}"></div>
                                                </div>
                                                @endfor
                                            </div>




{{--                                            <h5 class="card-title">Preview</h5>--}}
{{--                                            <div class="dropzone" id="preview" data-maxfile="8" data-ext="image/jpeg,image/png" data-lang="{{$lag->id}}" data-lang_code="{{$lag->lang_code}}" data-name="preview"></div>--}}
                                        </div>

                                    </div>

                                </div>
                                @endforeach
                            </div>
                    </div>
{{--                    <div class="col-sm-offset-2 col-sm-10">--}}
{{--                        <button type="submit" class="btn btn-primary" id="saveBtn_dropzone" value="create">Save changes--}}
{{--                        </button>--}}
{{--                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
{{--                    </div>--}}
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ajaxModelEdit" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

                <form id="designFormEdit" name="designFormEdit" class="form-horizontal">
                    <div class="row">
                        <input type="hidden" name="design_id_edit" id="design_id_edit">


                        <div class="form-group col-12">
                            <label for="name">Ghi chú</label>
                            <textarea id="notes" name="notes" class="form-control" rows="6" ></textarea>
                        </div>


                        @if( in_array( "Admin" ,array_column(auth()->user()->roles()->get()->toArray(),'name')))
                            <div class="form-group col-12">
                                <label for="name">Trạng thái</label>
                                <div>
                                    <select class="form-control" id="status" name="status">
                                        <option value="0">Gửi chờ duyệt</option>
                                        <option value="1">Đã chỉnh sửa, cần duyệt lại</option>
                                        <option value="2">Fail, cần chỉnh sửa</option>
                                        <option value="3">Fail, Project loại khỏi dự án</option>
                                        <option value="4">Done, Kết thúc Project</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-12">
                                <button type="submit" class="btn btn-primary" id="saveBtnEditDesign">Save changes
                                </button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        @endif





                    </div>





                </form>

            </div>
        </div>
    </div>
</div>



{{--<div class="modal fade" id="ajaxModelEdit" aria-hidden="true">--}}
{{--    <div class="modal-dialog modal-lg">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <h4 class="modal-title">Edit</h4>--}}
{{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
{{--            </div>--}}
{{--            <div class="modal-body">--}}

{{--                <form id="designFormEdit" name="designFormEdit" class="form-horizontal">--}}
{{--                    <div class="row">--}}
{{--                        <input type="hidden" name="design_id_edit" id="design_id_edit">--}}


{{--                        <div class="form-group col-12">--}}
{{--                            <label for="name">Ghi chú</label>--}}
{{--                            <textarea id="notes" name="notes" class="form-control" rows="6" ></textarea>--}}
{{--                        </div>--}}


{{--                        @if( in_array( "Admin" ,array_column(auth()->user()->roles()->get()->toArray(),'name')))--}}
{{--                            <div class="form-group col-12">--}}
{{--                                <label for="name">Trạng thái</label>--}}
{{--                                <div>--}}
{{--                                    <select class="form-control" id="status" name="status">--}}
{{--                                        <option value="0">Gửi chờ duyệt</option>--}}
{{--                                        <option value="1">Đã chỉnh sửa, cần duyệt lại</option>--}}
{{--                                        <option value="2">Fail, cần chỉnh sửa</option>--}}
{{--                                        <option value="3">Fail, Project loại khỏi dự án</option>--}}
{{--                                        <option value="4">Done, Kết thúc Project</option>--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="form-group col-12">--}}
{{--                                <button type="submit" class="btn btn-primary" id="saveBtnEditDesign">Save changes--}}
{{--                                </button>--}}
{{--                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
{{--                            </div>--}}
{{--                        @endif--}}





{{--                    </div>--}}





{{--                </form>--}}

{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

