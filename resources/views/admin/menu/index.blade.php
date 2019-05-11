@extends('admin.layout.app')
@section('title', '菜单管理')
@section('css')
    <!--Footable-->
    <link href="{{ asset('plugins/footable/css/footable.core.css') }}" rel="stylesheet">
@endsection
@section('content')
    <!-- Page-Title -->
    @include('admin.layout.location')
    <!-- end page title end breadcrumb -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <h4 class="m-t-0 header-title">菜单管理</h4>
                <p class="text-muted m-b-30 font-13">
                    尽量不要修改菜单的上下级关系、菜单规则（除非是开发人员），可以更改菜单名，菜单排序顺序
                </p>

                <div class="mb-3">
                    <div class="row">
                        <div class="col-12 text-sm-center form-inline">
                            <div class="form-group mr-2">
                                <button class="btn btn-primary" onclick="openIframe('新增', '{{ route('menu.create') }}')"><i class="mdi mdi-plus-circle mr-2"></i> 新增</button>
                            </div>
                            {{--<div class="form-group">
                                <input id="demo-input-search2" type="text" placeholder="Search" class="form-control" autocomplete="off">
                            </div>--}}
                        </div>
                    </div>
                </div>

                <table id="demo-foo-addrow" class="table table-striped table-bordered m-b-0 toggle-circle" data-page-size="100000">
                    <thead>
                    <tr>
                        <th data-sort-ignore="true">菜单名称</th>
                        <th data-sort-ignore="true">图标</th>
                        <th data-sort-ignore="true">菜单规则</th>
                        <th data-sort-ignore="true">是否菜单</th>
                        <th data-sort-ignore="true">是否隐藏</th>
                        <th data-sort-ignore="true">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($menus as $menu)
                    <tr>
                        <td>{{ $menu['title_prefix'] . $menu['title'] }}</td>
                        <td><i class="{{ $menu['icon'] }}"></i></td>
                        <td>{{ $menu['uri'] }}</td>
                        <td>
                            @if($menu['is_menu'] == 1)
                                <label class="badge label-table badge-success">是</label>
                            @else
                                <label class="badge label-table badge-info">否</label>
                            @endif
                        </td>
                        <td>
                            @if($menu['status'] == 1)
                                <label class="badge label-table badge-success">是</label>
                            @else
                                <label class="badge label-table badge-info">否</label>
                            @endif
                        </td>
                        <td class="text-center">
                            <button class="btn btn-custom btn-sm btn-icon" onclick="openIframe('编辑', '{{ route('menu.edit', ['id' => $menu['id']]) }}')"><i class="fa fa-pencil"></i></button>
                            <button class="btn btn-danger btn-sm btn-icon" onclick="removeOne('{{ route('menu.destroy', ['id' => $menu['id']]) }}', this)"><i class="fa fa-times"></i></button>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr class="active">
                        <td colspan="6">
                            <div class="text-right">
                                <ul class="pagination pagination-split justify-content-end footable-pagination m-t-10"></ul>
                            </div>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('before-js')
    <!--FooTable-->
    <script src="{{ asset('plugins/footable/js/footable.all.min.js') }}"></script>
    <!--FooTable Example-->
    <script src="{{ asset('assets/admin/pages/jquery.footable.js') }}"></script>
@endsection