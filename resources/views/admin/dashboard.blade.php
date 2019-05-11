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

   {{-- <div class="row">
        <div class="col-lg-6">
            <div class="card-box">
                <h4 class="header-title">Line Chart</h4>

                <canvas id="lineChart" height="350" class="mt-4"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card-box">
                <h4 class="header-title">Bar Chart</h4>

                <canvas id="bar" height="350" class="mt-4"></canvas>
            </div>
        </div>
    </div>--}}
    <!-- end row -->

    <div class="row">
        <div class="col-lg-4">
            <div class="card-box">
                <h4 class="header-title">本月节目数（单位：条）</h4>

                <canvas id="pie" height="350" class="mt-4"></canvas>

            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-box">
                <h4 class="header-title">本月节目时长（单位：秒）</h4>
                <canvas id="doughnut" height="350" class="mt-4"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-box">
                <h4 class="header-title">本月最高得分排名</h4>
                <div style="height: 365px;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>名字</th>
                                <th>时长</th>
                                <th>编导</th>
                                <th>得分</th>
                            </tr>
                        </thead>
                        <tbody>
                        {{--@if($data->isNotEmpty())
                            @foreach($data as $item)
                            <tr>
                                <td data-toggle="tooltip"
                                    data-placement="top"
                                    data-original-title="{{ $item->title }}">{{ str_limit($item->title, 15, '...') }}
                                </td>
                                <td>{{ secToTime($item->duration) }}</td>
                                <td>{{ $item->director_name }}</td>
                                <td>{{ $item->total_score }}</td>
                            </tr>
                            @endforeach
                        @endif--}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- end row -->


    <div class="row">

        <div class="col-lg-6">

            <div class="card-box">
                <h4 class="header-title">本月记者得分</h4>

                <canvas id="bar" height="350" class="mt-4"> </canvas>

            </div>
        </div>

        <div class="col-lg-6">
            <div class="card-box">
                <h4 class="header-title">本月团队得分</h4>

                <canvas id="bar1" height="350" class="mt-4"></canvas>

            </div>
        </div>

    </div>
    <!-- end row -->
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