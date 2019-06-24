@extends('admin.layout.app')
@section('title', '专业管理')
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
                <ul class="nav nav-tabs tabs-bordered">
                    <li class="nav-item">
                        <a href="#home-b1" onclick="window.location.href='{{ route('professional.index') }}'"
                           data-toggle="tab" aria-expanded="false" class="nav-link active">
                            <i class="fi-monitor mr-2"></i> 专业管理
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#profile-b1" onclick="window.location.href='{{ route('year-professional.index') }}'"
                           data-toggle="tab" aria-expanded="true" class="nav-link">
                            <i class="fi-head mr-2"></i> 年度专业
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#messages-b1" onclick="window.location.href='{{ route('class.index') }}'" data-toggle="tab" aria-expanded="false" class="nav-link">
                            <i class="fi-mail mr-2"></i> 班级管理
                        </a>
                    </li>
                </ul>
                <h4 class="header-title" style="margin-top: 15px;">专业管理</h4>
                <p class="text-muted m-b-30 font-13">
                    专业列表
                </p>

                <div class="mb-3">
                    <div class="row">
                        <div class="col-12 text-sm-center form-inline">
                            <div class="form-group mr-2">
                                <button class="btn btn-success" onclick="openIframe('导入', '{{ route('professional.import') }}')"><i class="fa fa-upload mr-2"></i> 导入</button>
                            </div>
                            <div class="form-group mr-2">
                                <a class="btn btn-primary" href="javascript:;" onclick="openIframe('新增', '{{ route('professional.create') }}')"><i class="fa fa-plus mr-2"></i> 新增</a>
                            </div>
                            <div class="form-group mr-2">
                                <button class="btn btn-danger" onclick="removeAll('{{ route('professional.destroy') }}')"><i class="fa fa-times mr-2"></i> 删除</button>
                            </div>
                            <form class="form-inline" id="search-form">
                                {{--<div class="form-group mr-2">
                                    <select name="is_prepare" id="" class="form-control" onchange="searchValue(this.value, 'is_prepare')">
                                        <option value="">是否在编</option>
                                        <option value="1" {{ request('is_prepare')=='1'?'selected':'' }}>在编</option>
                                        <option value="0" {{ request('is_prepare')=='0'?'selected':'' }}>非在编</option>
                                    </select>
                                </div>--}}
                                <div class="form-group mr-2">
                                    <input  type="text" name="search"
                                            value="{{ request('search') }}"
                                            placeholder="专业代码/名称" class="form-control" autocomplete="off">
                                </div>
                                <div class="form-group mr-2">
                                    <button class="btn btn-primary "><i class="fa fa-search mr-2"></i> 搜索</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <table class="table table-striped table-bordered m-b-0 toggle-circle" >
                    <thead>
                    <tr>
                        <th style="text-align: center;">
                            <div class="checkbox checkbox-success checkbox-single">
                                <input type="checkbox" class="check-all" value="">
                                <label></label>
                            </div>
                        </th>
                        <th data-sort-ignore="true">专业代码</th>
                        <th data-sort-ignore="true">专业名称</th>
                        <th data-sort-ignore="true">学制</th>
                        <th data-sort-ignore="true">专业负责人</th>
                        <th data-sort-ignore="true">是否使用</th>
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
                            <td>{{ $item->code }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->schooling_length }}</td>
                            <td>{{ $item->leader }}</td>
                            <td>
                                @if($item->status == 1)
                                    <label class="badge badge-success">是</label>
                                @else
                                    <label class="badge badge-info">否</label>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="javascript:;" onclick="openIframe('编辑', '{{ route('professional.edit', ['id' => $item['id']]) }}')" class="btn btn-custom btn-sm btn-icon"><i class="fa fa-pencil"></i></a>
                                <button class="btn btn-danger btn-sm btn-icon" onclick="removeOne('{{ route('professional.destroy', ['id' => $item['id']]) }}')"><i class="fa fa-times"></i></button>
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
                                        <a class="dropdown-item" href="javascript:;" onclick="searchValue(20,'perpage')">20</a>
                                        <a class="dropdown-item" href="javascript:;" onclick="searchValue(50, 'perpage')">50</a>
                                        <a class="dropdown-item" href="javascript:;" onclick="searchValue(100, 'perpage')">100</a>
                                    </div>
                                </div>
                            </div>
                            <div class="pull-right">
                                {!! $pagelist !!}
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
        $(document).ready(function () {
            /*$('#status').select2({
                width: "100%",
            });

            $('#dept-id').select2({
                width: "100%",
            });*/
        });
        /*$('.dept-id').select2({
            placeholder: '所属部门'
        });*/
        function searchValue(value, key)
        {
            var params = {!! $params !!};
            if ('undefined' !== params[key]) {
                params[key] = value;
            }

            params = parseParams(params);
            window.location.href = '{{ route('professional.index') }}?' + params;
        }
    </script>
@endsection