
<div class="modal fade bd-example-modal-xl" id="template_previewModel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="templatePreviewForm" name="templatePreviewForm" enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" name="tp_id" id="tp_id">

                    <div>
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <label>Logo</label><p></p>
                                <input  id="logo" type="file" name="logo" class="form-control" hidden onchange="changeImg(this)" accept="image/*">
                                <img id="avatar" class="thumbnail" width="100%">
                            </div>
                        </div>

                        <div  class="row">
                            <div class="form-group col-lg-6">
                                <label for="name">Category<span style="color: red">*</span></label>
                                <div class="inner row">
                                    <div class="col-md-10 col-10">
                                        <select class="form-control select2" id="category_template" name="category_template" required>
                                            <option value="">---Vui lòng chọn---</option>
                                            @foreach($categoyTemplateFrame as $item)
                                                <option value="{{$item->id}}">{{$item->category_template_frames_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#categoryTemplateFrame" style="border-radius: 0 3px 3px 0; box-shadow: none;">...
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="name">Tên Template <span style="color: red">*</span></label>
                                <input type="text" id="tp_name" name="tp_name" class="form-control" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label for="name">Data mẫu</label><p></p>
                                <select class="form-control select2" id="tp_data" name="tp_data" required>
                                    <option value="">---Vui lòng chọn---</option>
                                    @foreach($dataFiles as $item)
                                        <option value="{{$item->id}}">{{$item->data_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="name">File Preview</label><p></p>
                                <input type="file" name="tp_sc" id="tp_sc" class="filestyle" data-buttonname="btn-secondary" accept=".zip" />
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-lg-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="tp_black" name="tp_black"  >
                                    <label class="custom-control-label" for="tp_black"> Black</label>
                                </div>
                            </div>
                            <div class="form-group col-lg-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="tp_blue" name="tp_blue">
                                    <label class="custom-control-label" for="tp_blue"> Blue</label>
                                </div>
                            </div>
                            <div class="form-group col-lg-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="tp_while" name="tp_while" >
                                    <label class="custom-control-label" for="tp_while"> While</label>
                                </div>
                            </div>
                            <div class="form-group col-lg-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="tp_pink" name="tp_pink" >
                                    <label class="custom-control-label" for="tp_pink"> Pink</label>
                                </div>
                            </div>

                            <div class="form-group col-lg-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="tp_yellow" name="tp_yellow" >
                                    <label class="custom-control-label" for="tp_yellow"> Yellow</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-lg-12 ">
                                <label for="name">SCRIPT </label>
                                <textarea id="tp_script" name="tp_script" class="form-control" rows="6" ></textarea>
                            </div>
                            <?php
                            for ($i = 1; $i<=8 ;$i++){
                            ?>
                                <div class="form-group col-lg-6 ">
                                    <label for="name">SCRIPT {{$i}} </label>
                                    <textarea id="tp_script_{{$i}}" name="tp_script_{{$i}}" class="form-control" rows="4" ></textarea>
                                </div>
                            <?php }?>
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

@include('modals.categoryTemplateFrame')




