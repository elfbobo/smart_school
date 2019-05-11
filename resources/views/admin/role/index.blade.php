@extends('admin.layout.app')
@section('title', '角色管理')
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
                <h4 class="m-t-0 header-title">角色管理</h4>
                <p class="text-muted m-b-30 font-13">
                    角色列表
                </p>

                <div class="mb-3">
                    <div class="row">
                        <div class="col-12 text-sm-center form-inline">
                            <div class="form-group mr-2">
                                <button class="btn btn-primary" onclick="openIframe('新增', '{{ route('role.create') }}')"><i class="fa fa-plus mr-2"></i> 新增</button>
                            </div>
                            <div class="form-group mr-2">
                                <button class="btn btn-danger" onclick="removeAll('{{ route('role.destroy') }}')"><i class="fa fa-times mr-2"></i> 删除</button>
                            </div>
                            {{--<div class="form-group">
                                <input id="demo-input-search2" type="text" placeholder="搜索" class="form-control" autocomplete="off">
                            </div>--}}
                        </div>
                    </div>
                </div>

                <table {{--id="demo-foo-addrow"--}} class="table table-striped table-bordered m-b-0 toggle-circle" data-page-size="100000">
                    <thead>
                    <tr>
                        <th style="text-align: center;">
                            <div class="checkbox checkbox-success checkbox-single">
                                <input type="checkbox" class="check-all" value="">
                                <label></label>
                            </div>
                        </th>
                        <th data-sort-ignore="true">角色名称</th>
                        <th data-sort-ignore="true">角色代码</th>
                        <th data-sort-ignore="true">角色描述</th>
                        <th data-sort-ignore="true">系统角色</th>
                        {{--<th data-sort-ignore="true">状态</th>--}}
                        <th data-sort-ignore="true">操作</th>
                    </tr>
                    </thead>
                    @if($data->isNotEmpty())
                        <tbody>
                        @foreach($data as $item)
                            <tr>
                                <td class="text-center">
                                    <div class="checkbox checkbox-success checkbox-single">
                                        <input type="checkbox" value="{{ $item->id }}">
                                        <label></label>
                                    </div>
                                </td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->description }}</td>
                                <td>
                                    @if($item->is_sys_role == 1)
                                        <label class="badge badge-success">是</label>
                                    @else
                                        <label class="badge badge-info">否</label>
                                    @endif
                                </td>
                                {{--<td>
                                    @if($item->status == 1)
                                        <label class="badge badge-success">启用</label>
                                    @else
                                        <label class="badge badge-info">禁用</label>
                                    @endif
                                </td>--}}
                                <td class="text-center">
                                    <button class="btn btn-primary btn-sm btn-icon" title="分配节点"
                                            onclick="openIframe('分配节点', '{{ route('role.auth', ['role_id' => $item['id']]) }}')"
                                    ><i class="fa fa-cog"></i></button>
                                    <button class="btn btn-custom btn-sm btn-icon" onclick="openIframe('编辑', '{{ route('role.edit', ['id' => $item['id']]) }}')"><i class="fa fa-pencil"></i></button>
                                    @if($item['is_sys_role'] == 0)
                                    <button class="btn btn-danger btn-sm btn-icon" onclick="removeOne('{{ route('role.destroy', ['id' => $item['id']]) }}')"><i class="fa fa-times"></i></button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr class="active">
                            <td colspan="7">
                                <div class="pull-left">
                                    <span class="text-muted">共有数据{{ $data->total() }}条，每页显示</span>
                                    <div class="btn-group mb-2">
                                        <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown">{{ request()->get('perpage') }}</button>
                                        <div class="dropdown-menu" x-placement="bottom-start" style="">
                                            <a class="dropdown-item" href="{{ route('user.index', ['perpage' => 20]) }}">20</a>
                                            <a class="dropdown-item" href="{{ route('user.index', ['perpage' => 50]) }}">50</a>
                                            <a class="dropdown-item" href="{{ route('user.index', ['perpage' => 100]) }}">100</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="pull-right">
                                    {!! $data->links() !!}
                                </div>
                            </td>
                        </tr>
                        </tfoot>
                    @else
                        <tfoot>
                        <tr class="active">
                            <td colspan="7">
                                <p class="text-muted text-center">暂无数据</p>
                            </td>
                        </tr>
                        </tfoot>
                    @endif
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