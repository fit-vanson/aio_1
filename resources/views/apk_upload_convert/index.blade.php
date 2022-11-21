@extends('layouts.master')

@section('css')

<link href="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('assets/libs/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('assets/libs/toastr/ext-component-toastr.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ URL::asset('/assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />

<link href="{{ URL::asset('assets/libs/magnific-popup/magnific-popup.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('assets/libs/lightgallery/css/lightgallery.css') }}" rel="stylesheet" type="text/css" />
<!-- Select2 Js  -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />






@endsection

@section('content')
{{--    @include('modals.apk_upload')--}}

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-5">
                        <form id="apk_uploadForm" name="apk_upload_convertForm" class="form-horizontal">
                            <div class="form-group">
                                <label for="name" class="col-sm-5 control-label">APK Upload Convert</label>
                                <div class="col-sm-12">
                                    <div class="dropzone" name="apk_upload_convert" id="apk_upload_convert" data-maxfile="1" data-name="apk_upload_convert" ></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-rep-plugin" >
                        <div class="table-responsive mb-0" data-pattern="priority-columns">
                            <table id="apk_uploadTable" class="table table-striped table-bordered dt-responsive data-table" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th style="display: none">ID</th>
                                    <th style="width: 10%">Logo</th>
                                    <th style="width: 40%">File APK</th>
                                    <th style="width: 40%">File name </th>
                                    <th style="width: 10%">Process </th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
@section('script')

<!-- Plugins js -->
<script src="{{ URL::asset('/assets/libs/rwd-table/rwd-table.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/toastr/toastr.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ URL::asset('/assets/js/table.init.js') }}"></script>
<script src="{{ URL::asset('/assets/js/customs.js') }}"></script>


{{--<!-- Dropzone js -->--}}
<script src="{{ URL::asset('/assets/libs/dropzone/dropzone.min.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="{{ URL::asset('/assets/libs/magnific-popup/magnific-popup.min.js') }}"></script>
<script src="{{ URL::asset('/assets/libs/lightgallery/js/lightgallery-all.js') }}"></script>



<script type="text/javascript">
    Dropzone.autoDiscover = false;
    $(function () {
        $('.table-responsive').responsiveTable({
            // addDisplayAllBtn: 'btn btn-secondary'
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        var table = $('#apk_uploadTable').DataTable({

            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('apk_upload_convert.getIndex') }}",
                type: 'post',
            },
            columns: [
                {data: 'id', name: 'id',visible: false},
                {data: 'logo', name: 'logo',orderable: false},
                {data: 'name', name: 'name'},
                {data: 'filename', name: 'filename'},
                {data: 'process', name: 'process'}
            ],
            order: [ 0, 'desc' ]
        });


        var myDropzoneOptions = {
            url: '{{route('apk_upload_convert.create')}}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            // autoProcessQueue: false,
            addRemoveLinks: true,
            dictRemoveFile: 'Xoá',
            acceptedFiles: ".apk",
            init: function () {
                var _this = this; // For the closure
                this.on('success', function (file, response) {
                    if (response.success) {
                        _this.removeFile(file);
                    }
                    if (response.errors) {
                        for (var count = 0; count < response.errors.length; count++) {
                            toastr['error'](file.name, response.errors[count], {
                                showMethod: 'slideDown',
                                hideMethod: 'slideUp',
                                timeOut: 5000,
                            });
                        }
                    }
                    if(_this.files.length == 0 ){
                        table.clear().draw();
                        toastr['success']('OK', response.success, {
                            showMethod: 'slideDown',
                            hideMethod: 'slideUp',
                            timeOut: 1000,
                        });
                    }
                });
            }

        };

        var myDropzone = new Dropzone('#apk_upload_convert', myDropzoneOptions);

    });


    </script>
@endsection


