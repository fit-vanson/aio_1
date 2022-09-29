
<div class="modal fade bd-example-modal-xl" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog modal-xl" id="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">

                <form id="gadevForm" name="gadevForm" class="form-horizontal">
                    <input type="hidden" name="gadev_id" id="gadev_id">

                    <div class="row">
                        <div class="form-group col-lg-6 ">
                            <label for="name">Gmail </label>
                            <input type="email" id="gmail" name="gmail" class="form-control"/>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="name">Email Recover </label>
                            <input type="email" id="mailrecovery" name="mailrecovery" class="form-control" />
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="name">Password</label>
                            <input type="text" id="pass" name="pass" class="form-control"/>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="name">VPN</label>
                            <input type="text" id="vpn_iplogin" name="vpn_iplogin" class="form-control"/>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="name">Backup Code</label>
                            <textarea id="backupcode" name="backupcode" class="form-control" rows="15" ></textarea>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="name">Note</label>
                            <textarea id="note" name="note" class="form-control" rows="15" ></textarea>
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




