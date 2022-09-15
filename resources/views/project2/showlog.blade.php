@extends('layouts.master')

@section('css')

@endsection

@section('breadcrumb')
    <div class="col-sm-6">
        <h4 class="page-title">Show log {{$record->projectname}} </h4>
    </div>
    <div class="col-sm-6">
        <div class="float-right">
            <a class="btn btn-secondary" href=""> Back</a>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-6 col-md-6">
            <div class="card directory-card">
                <div class="card-body">
                    <div class="float-left mr-4">
                        <img src="../uploads/project/{{$record->projectname}}/thumbnail/{{$record->logo}}" alt="" class="img-fluid img-thumbnail rounded-circle thumb-lg">
                    </div>
                    <div class="float-left">
                        <h5 class="text-primary font-18 mt-0 mb-1">Mã Project: {{$record->projectname}}</h5>
                        <p>Mã Dự án: {{$record->da->ma_da}}</p>
                        <p>Mã template: {{$record->matemplate->template}}</p>
                        <p>Title app: {{$record->title_app}}</p>
                    </div>
                    <div class="clearfix"></div>
                    <hr>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-md-6">
            <div class="card directory-card">
                <div class="card-body">
                    <h5 class="text-primary font-18 mt-0 mb-1">Package</h5>
                    <p>ChPlay: {{$record->Chplay_package}}</p>
                    <p>Amazon: {{$record->Amazon_package}}</p>
                    <p>Samsung: {{$record->Samsung_package}}</p>
                    <p>Xiaomi: {{$record->Xiaomi_package}}</p>
                    <p>Oppo: {{$record->Oppo_package}}</p>
                    <p>Vivo: {{$record->Vivo_package}}</p>
                    <div class="clearfix"></div>
                    <hr>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card directory-card">
                <div class="card-body">
                    <h5 class="text-primary font-18 mt-0 mb-1">Log</h5>
                    <ul class="message-list">
                        @foreach($data_logs as $data_log)
                        <li>
                            <div class="col-mail col-mail-1">
                                <span>{{$data_log['time']}}</span>
                            </div>
                            <div class="col-mail col-mail-2">
                                <span class="teaser">{{$data_log['mess']}}</span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <div class="clearfix"></div>
                    <hr>
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
@endsection

@section('script')
@endsection
