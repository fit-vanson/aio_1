
<div class="modal fade bd-example-modal-xl" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog modal-xl" id="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="daForm" name="gadevForm" class="form-horizontal">
                    <div data-repeater-item="" class="row">
                        <div class="form-group col-lg-6 ">
                            <label for="name">Tên dự án</label>
                            <input type="hidden" name="da_id" id="da_id">
                            <input type="text" class="form-control" id="ma_da" name="ma_da" required>
                        </div>
                        <div class="form-group col-lg-6 ">
                            <label for="name">Link Store VietMMO</label>
                            <input type="url" id="link_store_vietmmo" name="link_store_vietmmo" class="form-control" >
                        </div>
                    </div>

                    <div data-repeater-item="" class="row">
                        <div class="form-group col-lg-6 ">
                            <label for="name">Chủ đề </label>
                            <textarea id="chude" name="chude" class="form-control" rows="4" ></textarea>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="name">keywords</label>
                            <textarea id="keywords" name="keywords" class="form-control" rows="4" ></textarea>
                        </div>
                    </div>
                    <div data-repeater-item="" class="row">
                        <div class="form-group col-lg-6 ">
                            <label for="name">Ghi chú</label>
                            <textarea id="note" name="note" class="form-control" rows="4" ></textarea>
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








