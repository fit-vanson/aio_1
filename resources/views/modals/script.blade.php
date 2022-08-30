
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="scriptForm" name="scriptForm" class="form-horizontal">
                    <div data-repeater-item="" class="row">
                        <div hidden class="form-group col-lg-12 ">
                            <label for="name">ID</label>
                            <input  type="text" class="form-control" id="id" name="id">
                        </div>

                    </div>

                    <div data-repeater-item="" class="row">
                        <div class="form-group col-lg-12 ">
                            <label for="name">Tên Script</label>
                            <input type="text" id="name_script" name="name_script" class="form-control" >
                        </div>

                    </div>

                    <div data-repeater-item="" class="row">

                        <div class="form-group col-lg-12">
                            <label for="name">script</label>
                            <textarea rows="5" type="text" id="script" name="script" class="form-control" > </textarea>
                        </div>
                    </div>
                    <div data-repeater-item="" class="row">
                        <div class="form-group col-lg-12 ">
                            <label for="name">Note</label>
                            <textarea rows="5" type="text" id="note" name="note" class="form-control" > </textarea>
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

