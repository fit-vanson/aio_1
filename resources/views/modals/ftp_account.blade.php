
<div class="modal fade bd-example-modal-xl" id="ftp_accountModel" aria-hidden="true">
    <div class="modal-dialog" id="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="ftp_accountForm" name="ftp_accountForm" class="form-horizontal">
                    <div class="row">
                        <div class="form-group col-lg-12 ">
                            <label for="name">Tên server</label>
                            <input type="hidden" name="ftp_id" id="ftp_id">
                            <input type="text" class="form-control" id="ftp_name" name="ftp_name" required>
                        </div>
                        <div class="form-group col-lg-9 ">
                            <label for="name"> IP </label>
                            <input type="text" class="form-control" id="ftp_server" name="ftp_server" required>
                        </div>
                        <div class="form-group col-lg-3 ">
                            <label for="name"> Port </label>
                            <input type="number" class="form-control" id="ftp_port" name="ftp_port" value="21">
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="name">Account</label>
                            <input type="text" class="form-control" id="ftp_account" name="ftp_account" required>
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="name">Password</label>
                            <input type="text" class="form-control" id="ftp_password" name="ftp_password" required>
                        </div>
                        <div class="form-group col-lg-12">
                            <label for="name">Ghi chú</label>
                            <textarea class="form-control" rows="5" id="ftp_note" name="ftp_note"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <a href="javascript:void(0)" class="btn btn-success" id="saveBtn_check" style="display: none" >Check Connect</a>
                        <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>








