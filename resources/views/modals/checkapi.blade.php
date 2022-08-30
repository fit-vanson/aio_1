
<div class="modal fade" id="checkapiModel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="checkapiHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="checkapiForm" name="checkapiForm" class="form-horizontal">
                    <input type="hidden" name="id" id="id">
                    <div class="row form-group">
                        <label for="name" class="col-sm-5 control-label">Tên</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="checkapi_name" name="checkapi_name" required >
                        </div>
                    </div>
                    <div class="row form-group">
                        <label for="name" class="col-sm-5 control-label">URL</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="checkapi_url" name="checkapi_url" required >
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="d-block mb-3">Type :</label>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="checkapi_json" name="checkapi_type" class="custom-control-input" value="0" checked>
                            <label class="custom-control-label" for="checkapi_json">Json</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="checkapi_text" name="checkapi_type" class="custom-control-input" value="1">
                            <label class="custom-control-label" for="checkapi_text">Text</label>
                        </div>
                    </div>
                    <div class=" row form-group">
                        <label for="name" class="col-sm-5 control-label">Code</label>
                        <div class="col-sm-12">
                            <textarea id="checkapi_code" name="checkapi_code" class="form-control" rows="4" ></textarea>
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

