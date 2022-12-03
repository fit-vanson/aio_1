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
                                <label for="name" class="col-sm-5 control-label">Upload Image</label>
                                <div class="col-sm-12">
                                    <div class="dropzone" name="upload_image" id="upload_image" data-maxfile="1" data-name="upload_image" ></div>
                                </div>

                                <label for="name" class="col-sm-5 control-label">Upload Zip</label>
                                <div class="col-sm-12">
                                    <div class="dropzone" name="upload_zip" id="upload_zip" data-maxfile="1" data-name="upload_zip" ></div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });





        var myDropzoneOptions = {
            url: '{{route('exiftool.create')}}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            // autoProcessQueue: false,
            addRemoveLinks: true,
            dictRemoveFile: 'Xoá',
            acceptedFiles: ".jpg,.png",

            parallelUploads: 30,
            uploadMultiple: true,

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

                        toastr['success']('OK', response.success, {
                            showMethod: 'slideDown',
                            hideMethod: 'slideUp',
                            timeOut: 1000,
                        });
                    }
                });
            }

        };

        var myDropzone = new Dropzone('#upload_image', myDropzoneOptions);

        new Dropzone('#upload_zip', {
            paramName: "zip",




            url: '{{route('exiftool.create')}}',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            // autoProcessQueue: false,
            addRemoveLinks: true,
            dictRemoveFile: 'Xoá',
            acceptedFiles: ".zip",

            // parallelUploads: 30,
            uploadMultiple: false,

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

                        toastr['success']('OK', response.success, {
                            showMethod: 'slideDown',
                            hideMethod: 'slideUp',
                            timeOut: 1000,
                        });
                    }
                });
            }



        });

    });


    </script>
@endsection


