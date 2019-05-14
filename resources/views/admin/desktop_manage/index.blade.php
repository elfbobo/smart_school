@extends('admin.layout.app')
@section('title', '桌面模板管理')
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
                <h4 class="m-t-0 header-title">桌面模板管理</h4>
                <p class="text-muted m-b-30 font-13">
                    桌面列表，上下拖拽可以进行排序
                </p>

                <div class="mb-3">
                    <div class="row">
                        <div class="col-12 text-sm-center form-inline">
                            {{--<div class="form-group mr-2">
                                <button class="btn btn-success" onclick="openIframe('导入', '{{ route('user.import') }}')"><i class="fa fa-plus mr-2"></i> 导入</button>
                            </div>--}}
                            <div class="form-group mr-2">
                                <button class="btn btn-primary" onclick="openIframe('新增', '{{ route('desktop_manage.create') }}')"><i class="fa fa-plus mr-2"></i> 新增</button>
                            </div>
                            <div class="form-group mr-2">
                                <button class="btn btn-custom" id="order" onclick="saveOrder()" disabled><i class="fa fa-save mr-2"></i> 排序</button>
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

                <ul class="sortable-list taskList list-unstyled" id="inprogress">
                    @foreach($data as $item)
                    <li class="task-info" id="{{ $item->id }}">
                        <p class="text-muted">桌面名称：{{ $item->name }}</p>
                        <p class="text-muted">桌面英文名称：{{ $item->name_eng }}</p>
                        <p class="text-muted">卡片数量：{{ $item->total ?: 0 }}</p>
                        <div class="mt-3">
                            <p class="pull-right mb-0" style="margin-top: -25px;">
                                <button type="button" class="btn btn-success btn-sm waves-effect waves-light">编辑</button>
                                <button type="button"
                                        onclick="removeOne('{{ route('desktop_manage.destroy', ['id' => $item->id]) }}')"
                                        class="btn btn-danger btn-sm waves-effect waves-light">删除</button>
                            </p>
                        </div>
                    </li>
                    @endforeach
                </ul>
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
        $("#upcoming, #inprogress, #completed").sortable({
            connectWith: ".taskList",
            placeholder: 'task-placeholder',
            forcePlaceholderSize: true,
            update: function (event, ui) {
                $('#order').attr('disabled', false);
                app_ids = $("#inprogress").sortable("toArray");
            }
        }).disableSelection();
    </script>
@endsection