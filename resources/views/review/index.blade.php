@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('plugins/datatables/autoFill.bootstrap4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('plugins/datatables/keyTable.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ URL::asset('plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- x-editable -->
    <link href="{{ URL::asset('plugins/x-editable/css/bootstrap-editable.css') }}" rel="stylesheet" type="text/css" />
@endsection


@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="reviewForm" class="table">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Age (AutoFill)</th>
                                <th>Qty (AutoFill and Editable)</th>
                                <th>Cost (Editable)</th>
                            </tr>
                            </thead>
                            <tr>
                                <td>1</td>
                                <td data-original-value="11">11</td>
                                <td data-original-value="1"><a href="#" data-type="text" data-pk="1" class="editable" data-url="" data-title="Edit Quantity">1</a></td>
                                <td data-original-value="1.99">12</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td data-original-value="22">22</td>
                                <td data-original-value="2"><a href="#" data-type="text" data-pk="2" class="editable" data-url="" data-title="Edit Quantity">2</a></td>
                                <td data-original-value="2.99"><a href="#" data-type="text" data-pk="1" class="" data-url="" data-title="Edit Quantity">2.99</a></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td data-original-value="33">33</td>
                                <td data-original-value="3"><a href="#" data-type="text" data-pk="3" class="editable" data-url="" data-title="Edit Quantity">3</a></td>
                                <td data-original-value="3.99"><a href="#" data-type="text" data-pk="1" class="editable" data-url="" data-title="Edit Quantity">3.99</a></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td data-original-value="44">44</td>
                                <td data-original-value="4"><a href="#" data-type="text" data-pk="4" class="editable" data-url="" data-title="Edit Quantity">4</a></td>
                                <td data-original-value="4.99"><a href="#" data-type="text" data-pk="1" class="editable" data-url="" data-title="Edit Quantity">4.99</a></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td data-original-value="55">55</td>
                                <td data-original-value="5"><a href="#" data-type="text" data-pk="5" class="editable" data-url="" data-title="Edit Quantity">5</a></td>
                                <td data-original-value="5.99"><a href="#" data-type="text" data-pk="1" class="editable" data-url="" data-title="Edit Quantity">5.99</a></td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection

@section('script')
    <!-- Required datatable js -->
    <script src="{{ URL::asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/datatables/dataTables.autoFill.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/datatables/autoFill.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/datatables/dataTables.keyTable.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ URL::asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('plugins/x-editable/js/bootstrap-editable.min.js') }}"></script>
{{--    <script src="{{ URL::asset('assets/pages/table-editable.int.js') }}"></script>--}}


    <script type="text/javascript">
        $(function () {

            var datatable = $('#reviewForm').dataTable({
                "columns": [
                    { "name": "id" },
                    { "name": "age" },
                    { "name": "qty" },
                    { "name": "cost" },
                ],

                "fnRowCallback": function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                    var setCell = function(response, newValue) {
                        var table = new $.fn.dataTable.Api('.table');
                        var cell = table.cell('td.focus');
                        var cellData = cell.data();

                        var div = document.createElement('div');
                        div.innerHTML = cellData;
                        var a = div.childNodes;
                        a.innerHTML = newValue;


                        cell.data(div.innerHTML);

                        console.log('jml a new ' + div.innerHTML);
                        console.log(a.innerHTML);
                        highlightCell($(cell.node()));

                        // This is huge cheese, but the a has lost it's editable nature.  Do it again.
                        $('td.focus a').editable({
                            'mode': 'inline',
                            'success' : setCell
                        });
                    };
                    $('.editable').editable(
                        {
                            'mode': 'inline',
                            'success' : setCell
                        }
                    );
                },
                // "autoFill" : {
                //     "columns" : [1]
                // },
                "keys" : true
            });

            addCellChangeHandler();
            addAutoFillHandler();

            function highlightCell($cell) {
                var originalValue = $cell.attr('data-original-value');
                if (!originalValue) {
                    return;
                }
                var actualValue = $cell.text();
                if (!isNaN(originalValue)) {
                    originalValue = parseFloat(originalValue);
                }
                if (!isNaN(actualValue)) {
                    actualValue = parseFloat(actualValue);
                }
                if ( originalValue === actualValue ) {
                    $cell.removeClass('cat-cell-modified').addClass('cat-cell-original');
                } else {
                    $cell.removeClass('cat-cell-original').addClass('cat-cell-modified');
                }
            }

            function addCellChangeHandler() {
                $('a[data-pk]').on('hidden', function (e, editable) {
                    var $a = $(this);
                    var $cell = $a.parent('td');
                    highlightCell($cell);
                });
            }

            function addAutoFillHandler() {
                var table = $('.table').DataTable();
                table.on('autoFill', function (e, datatable, cells) {
                    var datatableCellApis = $.each(cells, function(index, row) {
                        var datatableCellApi = row[0].cell;
                        var $jQueryObject = $(datatableCellApi.node());
                        highlightCell($jQueryObject);
                    });
                });
            }
        })
    </script>
@endsection
