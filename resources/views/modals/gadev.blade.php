
<div class="modal fade bd-example-modal-xl" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog" id="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

                <form id="gadevForm" name="gadevForm" class="form-horizontal">
                    <input type="hidden" name="gadev_id" id="gadev_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Gmail</label>
                        <div class="col-sm-12">
                            <input type="email" class="form-control" id="gmail" name="gmail" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Email Recover</label>
                        <div class="col-sm-12">
                            <input type="email" class="form-control" id="mailrecovery" name="mailrecovery">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Password</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="pass" name="pass">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label">VPN</label>
                        <div class="col-sm-12">
                            <input type="text" id="vpn_iplogin" class="form-control" name="vpn_iplogin">
                        </div>
                    </div>
                    <div class="form-group input_note">
                        <label class="col-sm-5 control-label">Ghi chú</label>
                        <div class="col-sm-12">
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




