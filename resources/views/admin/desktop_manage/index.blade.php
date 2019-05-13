@extends('admin.layout.app')
@section('title', '桌面模板管理')
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
                <h4 class="m-t-0 header-title">桌面模板管理</h4>
                <p class="text-muted m-b-30 font-13">
                    桌面列表
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
                            {{--<div class="form-group mr-2">
                                <button class="btn btn-danger" onclick="removeAll('{{ route('app_manage.destroy') }}')"><i class="fa fa-times mr-2"></i> 删除</button>
                            </div>--}}
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

                {{--<table class="table table-striped table-bordered m-b-0 toggle-circle">
                    <thead>
                    <tr>
                        <th style="text-align: center;">
                            <div class="checkbox checkbox-success checkbox-single">
                                <input type="checkbox" class="check-all" value="">
                                <label></label>
                            </div>
                        </th>
                        <th data-sort-ignore="true">应用图标</th>
                        <th data-sort-ignore="true">应用类型</th>
                        <th data-sort-ignore="true">应用名称</th>
                        <th data-sort-ignore="true">周期服务</th>
                        --}}{{--<th data-sort-ignore="true">PC应用</th>
                        <th data-sort-ignore="true">移动应用</th>--}}{{--
                        <th data-sort-ignore="true">推荐应用</th>
                        <th data-sort-ignore="true">业务域</th>
                        <th data-sort-ignore="true">上线状态</th>
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
                            <td>
                                <img src="{{ $item->icon_url ? $item->icon_url : asset('assets/admin/images/users/avatar-1.jpg') }}"
                                     alt="user" class="rounded-circle" width="50">
                            </td>
                            <td>{{ $app_type[$item->category] }}</td>
                            <td>{{ $item->name }}</td>
                            <td>
                                @if($item->is_cycle)
                                    <label class="badge badge-custom">是</label>
                                @else
                                    <label class="badge badge-info">否</label>
                                @endif
                            </td>
                            --}}{{--<td>
                                @if($item->is_pc_app)
                                    <label class="badge badge-custom">是</label>
                                @else
                                    <label class="badge badge-info">否</label>
                                @endif
                            </td>--}}{{--
                            --}}{{--<td>
                                @if($item->is_mobile_app)
                                    <label class="badge badge-custom">是</label>
                                @else
                                    <label class="badge badge-info">否</label>
                                @endif
                            </td>--}}{{--
                            <td>
                                @if($item->is_recommended)
                                    <label class="badge badge-custom">是</label>
                                @else
                                    <label class="badge badge-info">否</label>
                                @endif
                            </td>
                            <td>{{ $item->business_code }}</td>
                            <td>
                                @switch($item->online_status)
                                    @case(0)
                                    <label class="badge badge-danger">未上线</label>
                                    @break
                                    @case(1)
                                    <label class="badge badge-custom">运行中</label>
                                    @break
                                    @case(2)
                                    <label class="badge badge-secondary">已下线</label>
                                    @break
                                    @endswitch
                            </td>
                            <td class="text-center">
                                <button class="btn btn-custom btn-sm btn-icon" onclick="openIframe('编辑', '{{ route('app_manage.edit', ['id' => $item['id']]) }}')"><i class="fa fa-pencil"></i></button>
                                <button class="btn btn-success btn-sm btn-icon" onclick="openIframe('授权', '{{ route('app_manage.auth', ['id' => $item['id']]) }}')"><i class="fa fa-lock"></i></button>
                                <button class="btn btn-danger btn-sm btn-icon" onclick="removeOne('{{ route('app_manage.destroy', ['id' => $item['id']]) }}')"><i class="fa fa-times"></i></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr class="active">
                        <td colspan="100">
                            <div class="pull-left">
                                <span class="text-muted">共有数据{{ $data->total() }}条，每页显示</span>
                                <div class="btn-group mb-2">
                                    <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown">{{ request()->get('perpage') }}</button>
                                    <div class="dropdown-menu" x-placement="bottom-start" style="">
                                        <a class="dropdown-item" href="{{ route('user.index', array_merge($params, ['perpage' => 20])) }}">20</a>
                                        <a class="dropdown-item" href="{{ route('user.index', array_merge($params, ['perpage' => 50])) }}">50</a>
                                        <a class="dropdown-item" href="{{ route('user.index', array_merge($params, ['perpage' => 100])) }}">100</a>
                                    </div>
                                </div>
                            </div>
                            <div class="pull-right">
                                {!! $data->appends($params)->links() !!}
                            </div>
                        </td>
                    </tr>
                    </tfoot>
                        @else
                        <tfoot>
                        <tr class="active">
                            <td colspan="100">
                                <p class="text-muted text-center">暂无数据</p>
                            </td>
                        </tr>
                        </tfoot>
                    @endif
                </table>--}}
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
@section('script')
    <script>
        function searchValue(value, key)
        {
            var params = {!! $buildParams ?? '' !!};
            if ('undefined' !== params[key]) {
                params[key] = value;
            }

            params = parseParams(params);
            window.location.href = '{{ route('app_manage.index') }}?' + params;
        }
    </script>
@endsection