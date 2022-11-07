
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="apk_uploadForm" name="apk_uploadForm" class="form-horizontal">
                    @csrf
{{--                    <input type="hidden" name="design_id" id="design_id">--}}

                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Logo</label>
                        <div class="col-sm-12">
                            <div class="dropzone" id="apk_upload" data-maxfile="1" data-ext="image/png"  data-name="apk_upload" ></div>
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary" id="saveBtn_design" value="create">Save changes
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


