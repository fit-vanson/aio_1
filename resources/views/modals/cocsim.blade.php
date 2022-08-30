
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="cocsimForm" name="cocsimForm" class="form-horizontal">
                    <input type="hidden" name="id" id="id">
                    <div class="row form-group">
                        <label for="name" class="col-sm-5 control-label">Tên Cọc sim</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="cocsim" name="cocsim" required >
                        </div>
                    </div>

                    <div  class="row form-group" >
                        @for($i=1;$i<=15 ; $i++)
                        <div class=" form-group col-lg-4 ">
                            <label for="name" class="col-sm-5 control-label">Phone {{$i}} </label>
                            <input type="hidden" name="phone_id[]" id="phone_id_{{$i}}">
                            <input type="text" class="form-control" id="phone_{{$i}}" name="phone[]" required >
                        </div>
                        @endfor
                    </div>


                    <div class=" row form-group">
                        <label for="name" class="col-sm-5 control-label">Ghi chú</label>
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

