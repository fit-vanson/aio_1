
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="hubForm" name="hubForm" class="form-horizontal">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group" hidden>
                        <label for="name" class="col-sm-5 control-label">Hub Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control"  id="hubname" name="hubname" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-5 control-label">Cọc sim</label>
                        <div class="col-sm-12">
                            <select class="form-control" id="cocsim" name="cocsim">
                                <option>---Vui lòng chọn---</option>
                                @foreach($cocsim as $item)
                                    <option value="{{$item->id}}">{{$item->cocsim}}</option>
                                @endforeach
                            </select>
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

