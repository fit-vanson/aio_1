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
                        <table id="reviewForm" class="table table-striped table-bordered dt-responsive data-table"
                               style="width: 100%;">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>User Comment</th>
                                <th>Language</th>
                                <th>Down Count</th>
                                <th>Up Count</th>
                                <th>Star Rating</th>
                                <th>Last Modifie dUser</th>
                                <th>Developer Comment</th>
                                <th>Last Modified Developer</th>
                            </tr>
                            </thead>

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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var groupColumn = 0;
            var reviewForm = $('#reviewForm').dataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('review.getIndex') }}",
                    type: "post"
                },
                columns: [
                    {data: 'project_id'},
                    {data: 'userComment'},
                    {data: 'reviewerLanguage'},
                    {data: 'thumbsDownCount'},
                    {data: 'thumbsUpCount'},
                    {data: 'starRating'},
                    {data: 'lastModifiedUser'},
                    {data: 'developerComment'},
                    {data: 'lastModifiedDeveloper'},
                ],
                rowGroup: {
                    dataSrc: 1
                },
                columnDefs: [{ visible: false, targets: groupColumn }],
                drawCallback: function (settings) {
                    var api = this.api();
                    var rows = api.rows({ page: 'current' }).nodes();
                    var last = null;
                    api
                        .column(groupColumn, { page: 'current' })
                        .data()
                        .each(function (group, i) {
                            if (last !== group) {
                                $(rows)
                                    .eq(i)
                                    .before('<tr class="group"><td colspan="9">' + group + '</td></tr>');

                                last = group;
                            }
                        });
                },

                fnRowCallback: function (nRow, aData, iDisplayIndex, iDisplayIndexFull) {
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
                // "keys" : true
            });

            $('#reviewForm tbody').on( 'click', 'tr.group', function () {
                var currentOrder = reviewForm.order()[0];
                if ( currentOrder[0] === groupColumn && currentOrder[1] === 'asc' ) {
                    reviewForm.order( [ groupColumn, 'desc' ] ).draw();
                }
                else {
                    reviewForm.order( [ groupColumn, 'asc' ] ).draw();
                }
            } );

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
