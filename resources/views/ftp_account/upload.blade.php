@extends('layouts.master')

@section('css')



    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/file-manager/css/file-manager.css') }}">




@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title">File Manager</h4>
    </div>
@endsection
@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-datatable table-responsive pt-0">
                    <div style="height: 900px;">
                        <div id="fm"></div>
                    </div>
                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->
@endsection
@section('script')
    <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>

@endsection






