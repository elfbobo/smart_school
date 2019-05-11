@extends('admin.layout.app')
@section('title', '管理员管理')
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
                <h4 class="m-t-0 header-title">管理员管理</h4>
                <p class="text-muted m-b-30 font-13">
                    管理员列表
                </p>

                <div class="mb-3">
                    <div class="row">
                        <div class="col-12 text-sm-center form-inline">
                            {{--<div class="form-group mr-2">
                                <button class="btn btn-success" onclick="openIframe('导入', '{{ route('user.import') }}')"><i class="fa fa-plus mr-2"></i> 导入</button>
                            </div>--}}
                            <div class="form-group mr-2">
                                <button class="btn btn-primary" onclick="openIframe('新增', '{{ route('user.create') }}')"><i class="fa fa-plus mr-2"></i> 新增</button>
                            </div>
                            <div class="form-group mr-2">
                                <button class="btn btn-danger" onclick="removeAll('{{ route('user.destroy') }}')"><i class="fa fa-times mr-2"></i> 删除</button>
                            </div>
                            <form class="form-inline" id="search-form">

                                <div class="form-group mr-2">
                                    <input  type="text" name="search"
                                            value="{{ request('search') }}"
                                            placeholder="编号/姓名" class="form-control" autocomplete="off">
                                </div>
                                <div class="form-group mr-2">
                                    <button class="btn btn-primary "><i class="fa fa-search mr-2"></i> 搜索</button>
                                </div>
                            </form>
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
                        <th data-sort-ignore="true">头像</th>
                        <th data-sort-ignore="true">人员编号</th>
                        <th data-sort-ignore="true">姓名</th>
                        <th data-sort-ignore="true">身份</th>
                        {{--<th data-sort-ignore="true">手机</th>
                        <th data-sort-ignore="true">固话</th>--}}
                        <th data-sort-ignore="true">操作</th>
                    </tr>
                    </thead>
                    @if($data->isNotEmpty())
                    <tbody>
                    @foreach($data as $item)
                        <tr>
                            <td class="text-center">
                                <div class="checkbox checkbox-success checkbox-single">
                                    <input type="checkbox" value="{{ $item->code }}">
                                    <label></label>
                                </div>
                            </td>
                            <td>
                                <img src="{{ $item->avatar ? $item->avatar : asset('assets/admin/images/users/avatar-1.jpg') }}"
                                     alt="user" class="rounded-circle" width="50">
                            </td>
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->name }}</td>
                            <td>
                                @if($item->type == 0)
                                    <label class="badge badge-custom">学生</label>
                                    @elseif($item->type == 1)
                                    <label class="badge badge-success">教职工</label>
                                @endif
                            </td>
                            {{--<td>{{ $item->phone }}</td>
                            <td>{{ $item->telephone }}</td>--}}
                            <td class="text-center">
                                <button class="btn btn-custom btn-sm btn-icon" onclick="openIframe('编辑', '{{ route('user.edit', ['id' => $item['code']]) }}')"><i class="fa fa-pencil"></i></button>
                                <button class="btn btn-danger btn-sm btn-icon" onclick="removeOne('{{ route('user.destroy', ['id' => $item['code']]) }}')"><i class="fa fa-times"></i></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr class="active">
                        <td colspan="8">
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
                            <td colspan="8">
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
@section('script')
    <script>
        $('#column-id').on('change', function () {
            $('#search-form').submit();
        });
    </script>
@endsection