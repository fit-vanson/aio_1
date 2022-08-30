<div class="modal fade" id="dataProfileModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dataProfileLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

                <form id="dataProfileForm" name="dataProfileForm" class="form-horizontal">
                    <div class="row">
                        <div class="form-group col-lg-6">
                            <label for="name">Name</label>
                            <input type="hidden" class="form-control" id="data_id" name="data_id">
                            <input type="text" class="form-control" id="data_name" name="data_name" required>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="name">File</label><br>
                            <input type="file" name="data_file" id="data_file" class="filestyle" data-buttonname="btn-secondary" accept=".zip" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="name">Ghi chú</label>
                            <textarea rows="6" type="text" class="form-control" id="data_note" name="data_note"></textarea>
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
