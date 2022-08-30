
<div class="modal fade bd-example-modal-xl" id="template_text_previewModel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="templateTextPreviewForm" name="templateTextPreviewForm" enctype="multipart/form-data" class="form-horizontal">
                    <input type="hidden" name="tt_id" id="tt_id">

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
                                <label for="name">Category Parent<span style="color: red">*</span></label>
                                <div class="inner row">
                                    <div class="col-md-10 col-10">
                                        <select class="form-control select2" id="category_template_parent" name="category_template_parent" required>
                                            <option value="">---Vui lòng chọn---</option>
                                            @foreach($categoyTemplate as $item)
                                                <option value="{{$item->id}}">{{$item->category_template_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#categoryTemplate" style="border-radius: 0 3px 3px 0; box-shadow: none;">...
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="name">Category Child<span style="color: red">*</span></label>
                                <div class="inner row">
                                    <div class="col-md-10 col-10">
                                        <select class="form-control select2" id="category_template_child" name="category_template_child" required>
                                            <option value="">---Vui lòng chọn---</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-4">
                                        <a class="btn btn-primary" href="javascript:void(0)" id="categoryTemplateChild"> ...</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label for="name">Tên Template Text <span style="color: red">*</span></label>
                                <input type="text" id="tt_name" name="tt_name" class="form-control" required>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="name">File SC</label><p></p>
                                <input type="file" name="tt_file" id="tt_file" class="filestyle" data-buttonname="btn-secondary" accept=".zip" />
                            </div>
                        </div>
                        <div class="row">
                            <?php
                                for($i=1; $i<=8 ; $i++){
                            ?>
                            <div class="form-group col-lg-6">
                                <label for="name">Text {{$i}} </label>
                                <textarea id="tt_text_{{$i}}" name="tt_text_{{$i}}" class="form-control" rows="4" ></textarea>
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
@include('modals.categoryTemplate')




