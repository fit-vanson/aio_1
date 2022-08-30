
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form id="deviceForm" name="deviceForm" class="form-horizontal">
                    <div data-repeater-item="" class="row">
                        <div class="form-group col-lg-12 ">
                            <label for="name">ID</label>
                            <input type="text" class="form-control" id="id" name="id" required>
                        </div>

                    </div>

                    <div data-repeater-item="" class="row">
                        <div class="form-group col-lg-3 ">
                            <label for="name">Android</label>
                            <input type="text" id="android" name="android" class="form-control" >
                        </div>
                        <div class="form-group col-lg-3">
                            <label for="name">Verrelease</label>
                            <input type="text" class="form-control" id="verrelease" name="verrelease">
                        </div>
                        <div class="form-group col-lg-3 ">
                            <label for="name">Build ID</label>
                            <input type="text" id="buildid" name="buildid" class="form-control" >
                        </div>
                        <div class="form-group col-lg-3 ">
                            <label for="name">Display ID</label>
                            <input type="text" class="form-control" id="displayid" name="displayid">
                        </div>
                    </div>

                    <div data-repeater-item="" class="row">

                        <div class="form-group col-lg-3 ">
                            <label for="name">Incremental</label>
                            <input type="text" id="incremental" name="incremental" class="form-control" >
                        </div>
                        <div class="form-group col-lg-3 ">
                            <label for="name">SDK</label>
                            <input type="text" class="form-control" id="sdk" name="sdk">
                        </div>
                        <div class="form-group col-lg-3 ">
                            <label for="name">Build Date</label>
                            <input type="text" id="builddate" name="builddate" class="form-control" >
                        </div>
                        <div class="form-group col-lg-3 ">
                            <label for="name">Build Date UTC</label>
                            <input type="text" class="form-control" id="builddateutc" name="builddateutc">
                        </div>
                    </div>

                    <div data-repeater-item="" class="row">

                        <div class="form-group col-lg-4 ">
                            <label for="name">Product Model</label>
                            <input type="text" id="productmodel" name="productmodel" class="form-control" >
                        </div>
                        <div class="form-group col-lg-4 ">
                            <label for="name">Product Brand</label>
                            <input type="text" class="form-control" id="productbrand" name="productbrand">
                        </div>
                        <div class="form-group col-lg-4 ">
                            <label for="name">Product Name</label>
                            <input type="text" id="productname" name="productname" class="form-control" >
                        </div>
                    </div>

                    <div data-repeater-item="" class="row">
                        <div class="form-group col-lg-4 ">
                            <label for="name">Product Device</label>
                            <input type="text" class="form-control" id="productdevice" name="productdevice">
                        </div>
                        <div class="form-group col-lg-4 ">
                            <label for="name">Product Board</label>
                            <input type="text" id="productboard" name="productboard" class="form-control" >
                        </div>
                        <div class="form-group col-lg-4 ">
                            <label for="name">Product Manufacturer</label>
                            <input type="text" class="form-control" id="productmanufacturer" name="productmanufacturer">
                        </div>
                    </div>

                    <div data-repeater-item="" class="row">
                        <div class="form-group col-lg-4 ">
                            <label for="name">Description</label>
                            <input type="text" id="description" name="description" class="form-control" >
                        </div>
                        <div class="form-group col-lg-4 ">
                            <label for="name">Fingerprint</label>
                            <input type="text" class="form-control" id="fingerprint" name="fingerprint">
                        </div>
                        <div class="form-group col-lg-4 ">
                            <label for="name">Characteristics</label>
                            <input type="text" id="characteristics" name="characteristics" class="form-control" >
                        </div>
                    </div>

                    <div data-repeater-item="" class="row">
                        <div class="form-group col-lg-6 ">
                            <label for="name">Data Goc</label>
                            <textarea rows="5" type="text" class="form-control" id="datagoc" name="datagoc"> </textarea>
                        </div>
                        <div class="form-group col-lg-6 ">
                            <label for="name">Note</label>
                            <textarea rows="5" type="text" id="note" name="note" class="form-control" > </textarea>
                        </div>
                    </div>
                    <div data-repeater-item="" class="row">
                        <div class="form-group col-lg-6 ">
                            <label for="name">Status</label>
                            <div>
                                <select class="form-control" id="status" name="status">
                                    <option value="0">0</option>
                                    <option value="1">1</option>
                                </select>
                            </div>
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

