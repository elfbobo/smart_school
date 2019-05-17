@extends('admin.layout.app')
@section('title', '应用文件夹管理')
@section('css')
    <!-- jquery UI -->
    <link href="{{ asset('plugins/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <!-- Page-Title -->
    @include('admin.layout.location')
    <!-- end page title end breadcrumb -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <h4 class="m-t-0 header-title">应用文件夹管理</h4>
                <p class="text-muted m-b-30 font-13">
                    应用文件夹列表
                </p>

                <div class="mb-3">
                    <div class="row">
                        <div class="col-12 text-sm-center form-inline">
                            {{--<div class="form-group mr-2">
                                <button class="btn btn-success" onclick="openIframe('导入', '{{ route('user.import') }}')"><i class="fa fa-plus mr-2"></i> 导入</button>
                            </div>--}}
                            <div class="form-group mr-2">
                                <button class="btn btn-primary" onclick="openIframe('新增', '{{ route('app_folder_manage.create') }}')"><i class="fa fa-plus mr-2"></i> 新增</button>
                            </div>
                            <form class="form-inline" id="search-form">

                                <div class="form-group mr-2">
                                    <input  type="text" name="search"
                                            value="{{ request('search') }}"
                                            placeholder="桌面名称" class="form-control" autocomplete="off">
                                </div>
                                <div class="form-group mr-2">
                                    <button class="btn btn-primary "><i class="fa fa-search mr-2"></i> 搜索</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="file-man-box">
                            <a href="" class="file-close"><i class="mdi mdi-close-circle"></i></a>
                            <div>
                                <a><img class="rounded-circle thumb-sm" alt="64x64" src="{{ asset('assets/admin/images/users/avatar-2.jpg') }}" style="margin:5px;"></a>
                            </div>
                            <a href="#" class="file-download" style="font-size:18px;bottom: 0; right: 45px;"><i class="fa fa-pencil"></i> </a>
                            <a href="#" class="file-download" style="font-size:18px;bottom: 0;"><i class="fa fa-eye"></i> </a>
                            <div class="file-man-title">
                                <p>文件夹名称</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('before-js')
    <!-- Jquery Ui js -->
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
@endsection
@section('script')
    <script>
        var app_ids = {};
        $("#upcoming, #inprogress, #completed").sortable({
            connectWith: ".taskList",
            placeholder: 'task-placeholder',
            forcePlaceholderSize: true,
            update: function (event, ui) {
                $('#order').attr('disabled', false);
                app_ids = $("#inprogress").sortable("toArray");
            }
        }).disableSelection();

        function saveOrder() {
            $.ajax({
                type: 'post',
                url: '{{ route('desktop_manage.disp-order') }}',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN' : '{{ csrf_token() }}'
                },
                data: {app_ids: app_ids},
                success: function (res) {
                    if (res.code ==200) {
                        layer.msg(res.msg, {time:1500}, function () {
                            window.location.reload();
                        });
                    } else {
                        layer.msg(res.msg);
                    }
                },
                error: function () {
                    layer.msg('服务器错误');
                }
            });
        }
    </script>
@endsection