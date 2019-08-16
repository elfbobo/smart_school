@extends('admin.layout.app')
@section('title', '首页')
@section('css')
    <!--chartist Chart CSS -->
    <link rel="stylesheet" href="{{ asset('plugins/chartist/css/chartist.min.css') }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box" style="padding: 30px 0;">
                <div class="btn-group pull-left">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item active">首页</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title end breadcrumb -->

    <div class="row">
        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card-box tilebox-one">
                <i class="icon-people float-right text-muted"></i>
                <h6 class="text-muted text-uppercase mt-0">教职工总数</h6>
                <h2 class="m-b-20" data-plugin="counterup">{{ $teacherCount }}</h2>
            </div>
        </div>
        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card-box tilebox-one">
                <i class="icon-graduation float-right text-muted"></i>
                <h6 class="text-muted text-uppercase mt-0">学生总数</h6>
                <h2 class="m-b-20" data-plugin="counterup">{{ $studentCount }}</h2>
            </div>
        </div>
        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card-box tilebox-one">
                <i class="icon-tag float-right text-muted"></i>
                <h6 class="text-muted text-uppercase mt-0">应用数量</h6>
                <h2 class="m-b-20" data-plugin="counterup">{{ $appCount }}</h2>
            </div>
        </div>
        <div class="col-xs-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card-box tilebox-one">
                <i class="icon-layers float-right text-muted"></i>
                <h6 class="text-muted text-uppercase mt-0">访问量</h6>
                <h2 class="m-b-20" data-plugin="counterup">0</h2>
            </div>
        </div>
    </div>
@endsection
@section('before-js')
    <!-- Flot chart -->
    {{--<script src="{{ asset('plugins/flot-chart/jquery.flot.min.js') }}"></script>
    <script src="{{ asset('plugins/flot-chart/jquery.flot.time.js') }}"></script>
    <script src="{{ asset('plugins/flot-chart/jquery.flot.tooltip.min.js') }}"></script>
    <script src="{{ asset('plugins/flot-chart/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('plugins/flot-chart/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('plugins/flot-chart/jquery.flot.crosshair.js') }}"></script>
    <script src="{{ asset('plugins/flot-chart/curvedLines.js') }}"></script>
    <script src="{{ asset('plugins/flot-chart/jquery.flot.axislabels.js') }}"></script>--}}
    <!--Chartist Chart-->
    <script src="{{ asset('plugins/chartist/js/chartist.min.js') }}"></script>
    <script src="{{ asset('plugins/chart.js/chart.bundle.js') }}"></script>
    {{--<script src="{{ asset('assets/admin/pages/jquery.chartjs.init.js') }}"></script>--}}

@endsection