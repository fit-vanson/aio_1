<div class="modal fade" id="buildpreviewModal" role="dialog">
    <div class="modal-dialog mw-100 w-75">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buildpreviewModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

                <form id="buildpreviewForm" name="buildpreviewForm" class="form-horizontal">
                    <input type="hidden" name="template_frame" id="template_frame">
                    <div class="row buildPreviewTemplateFrame">
                        <div class="form-group col-lg-3">
                            <label for="name">Category Frame</label>
                            <select class="form-control select2" id="category_template_frame" name="category_template_frame" required>
                                <option value="">--Vui lòng chọn--</option>
                                @if(isset($categoyTemplateFrame))
                                    @foreach($categoyTemplateFrame as $item)
                                        <option value="{{$item->id}}">{{$item->category_template_frames_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="name">Template Frame Preview</label>
                            <select class="form-control select2" id="template_frame_preview" name="template_frame_preview">
                            </select>
                        </div>

                        <div class="form-group col-lg-3">
                            <p for="name"> Preview</p>
                            <img id="preview_frame" class="thumbnail" width="200px">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="template_availavble" name="template123" class="custom-control-input" value="template_availavble" checked>
                            <label class="custom-control-label" for="template_availavble">Template mẫu có sẵn</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="template_custom" name="template123" class="custom-control-input" value="template_custom">
                            <label class="custom-control-label" for="template_custom">Template tuỳ chỉnh</label>
                        </div>
                    </div>

                    <div class="row template_availavble" >
                        <div class="form-group col-lg-12 " >
                            <label for="name"> Color</label>
                            <div class="form-check" id="color_frame"></div>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="name">Category Text</label>
                            <select class="form-control select2" id="category_template_text" name="category_template_text" required>
                                <option value="">--Vui lòng chọn--</option>
                                @if(isset($categoyTemplateText))
                                    @foreach($categoyTemplateText as $item)
                                        <option value="{{$item->id}}">{{$item->category_template_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="name">Category Text Child</label>
                            <select class="form-control select2" id="category_child_template_text" name="category_child_template_text" required>
                            </select>
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="name">Template Text Preview</label>
                            <select class="form-control select2" id="template_text_preview" name="template_text_preview" required>
                            </select>
                        </div>
                        <div class="form-group col-lg-3">
                            <p for="name"> Preview</p>
                            <img id="preview_text" class="thumbnail" width="200px">
                        </div>
                    </div>

                    <div class="row template_custom">
{{--                        <div class="form-group row col-lg-6">--}}
                            <div class="col-lg-3">
                                <label for="name">Font Chữ to</label>
                                <select class="form-control select2" id="font_name" name="font_name" required>
                                    @if(isset($fonts))
                                        @foreach($fonts as $item)
                                            <option value="{{$item->id}}" style=" font-family: '{{$item->name}}';font-weight: 400;">{{$item->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label for="name">Size</label>
                                <select class="form-control select2" id="font_size" name="font_size" required>
                                    @for( $i = 80; $i <= 120; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-lg-1 " >
                                <label for="name"> Color</label><br>
                                <input type="color" id="colorpicker" name="colorpicker">
                            </div>

                            <div class=" col-lg-3">
                                <label for="name">Font Chữ nhỏ</label>
                                <select class="form-control select2" id="font_name" name="font_name_small" required>
                                    @if(isset($fonts))
                                        @foreach($fonts as $item)
                                            <option value="{{$item->id}}" style=" font-family: '{{$item->name}}';font-weight: 400;">{{$item->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-lg-2">
                                <label for="name">Size</label>
                                <select class="form-control select2" id="font_size_small" name="font_size_small" required>

                                    @for( $i = 80; $i <= 120; $i++)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-group col-lg-1" >
                                <label for="name"> Color</label><br>
                                <input type="color" id="colorpicker" name="colorpicker_small">
                            </div>
{{--                        </div>--}}


                        <?php
                        for ($i= 1 ; $i<=6; $i++)
                        {
                        ?>

                        <div class="form-group col-lg-6">
                            <label for="name">Text to SC {{$i}}</label>
                            <input type="text" class="form-control" id="text_to_{{$i}}" name="text_to[]" value="Text demo {{$i}}" >
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="name">Text nhỏ SC {{$i}}</label>
                            <input type="text" class="form-control" id="text_nho_{{$i}}" name="text_nho[]"  value="small size {{$i}}" >
                        </div>
                        <?php } ?>

                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="data_availavble" name="data123" class="custom-control-input" value="data_availavble" checked>
                            <label class="custom-control-label" for="data_availavble">Data mẫu có sẵn</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="data_custom" name="data123" class="custom-control-input" value="data_custom">
                            <label class="custom-control-label" for="data_custom">Data mẫu tuỳ chỉnh</label>
                        </div>
                    </div>


                    <div class="row data_custom">
                        <div class="form-group col-lg-6">
                            <label for="name">File Data mẫu</label><p></p>
                            <input type="file" name="file_data" id="file_data" class="filestyle" data-buttonname="btn-secondary" accept=".zip" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <p for="name"> Preview Out</p>
                            <center><img id="preview_out" class="thumbnail" width="1000px"></center>
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="saveBtn">Create</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
</div>


{{--<div class="modal fade bd-example-modal-xl" id="categoryTemplateChildModel" aria-hidden="true">--}}
{{--    <div class="modal-dialog">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-header">--}}
{{--                <h4 class="modal-title" id="childModalLabel">Thêm mới Category Template Child</h4>--}}
{{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
{{--            </div>--}}
{{--            <div class="modal-body">--}}
{{--                <form id="CategoryTemplateChildForm" name="CategoryTemplateChildForm" class="form-horizontal">--}}
{{--                    <input type="hidden" name="category_template_id" id="category_template_child_id">--}}
{{--                    <div class="form-group">--}}
{{--                        <label for="name">Category Parent</label>--}}
{{--                        <select class="form-control select2" id="category_template_parent_child" name="category_template_parent">--}}
{{--                            <option value="">--None--</option>--}}
{{--                            @if(isset($categoyTemplate))--}}
{{--                                @foreach($categoyTemplate as $item)--}}
{{--                                    <option value="{{$item->id}}">{{$item->category_template_name}}</option>--}}
{{--                                @endforeach--}}
{{--                            @endif--}}
{{--                        </select>--}}
{{--                    </div>--}}


{{--                    <div class="form-group">--}}
{{--                        <label for="name">Template Text <span style="color: red">*</span></label>--}}
{{--                        <input type="text" id="category_template_child_child" name="category_template_name" class="form-control" required>--}}
{{--                    </div>--}}
{{--                    <div class="col-sm-offset-2 col-sm-10">--}}
{{--                        <button type="submit" class="btn btn-primary" id="saveBtnChild">Save</button>--}}
{{--                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
