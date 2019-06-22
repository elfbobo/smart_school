@extends('admin.layout.app')
@section('title', '部门管理')
@section('css')
    <!-- jquery UI -->
    <link href="{{ asset('plugins/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet" type="text/css" />
    <!--Footable-->
    <link href="{{ asset('plugins/ztree/css/bootstrapStyle/bootstrapStyle.css') }}" rel="stylesheet">
@endsection
@section('content')
    <!-- Page-Title -->
    @include('admin.layout.location')
    <!-- end page title end breadcrumb -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <h4 class="m-t-0 header-title">部门管理</h4>
                <ul class="nav nav-tabs tabs-bordered">
                    <li class="nav-item">
                        <a href="#category" data-toggle="tab" aria-expanded="false"
                           class="nav-link {{ !request('tab') || request('tab')=='category' ? 'active' : '' }}">
                            <i class="fi-monitor mr-2"></i> 部门类别
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#bb" data-toggle="tab" aria-expanded="true"
                           class="nav-link {{ request('tab')=='bb' ? 'active' : '' }}">
                            <i class="fi-head mr-2"></i> 部门办别
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#dept" data-toggle="tab" aria-expanded="false"
                           class="nav-link {{ request('tab')=='dept' ? 'active' : '' }}">
                            <i class="fi-mail mr-2"></i> 部门信息
                        </a>
                    </li>
                </ul>

                {{--<div class="mb-3">
                    <div class="row">
                        <div class="col-12 text-sm-center form-inline">
                            <div class="form-group mr-2">
                                <button class="btn btn-primary" onclick="openIframe('新增', '{{ route('user.create') }}')"><i class="fa fa-plus mr-2"></i> 新增</button>
                            </div>
                        </div>
                    </div>
                </div>--}}
                <div class="tab-content">
                    <div class="tab-pane {{ !request('tab') || request('tab')=='category' ? 'show active' : '' }}" id="category">
                        <div class="form-group mr-1">
                            <button class="btn btn-custom" disabled id="sortable1" onclick="sortable('{{ route('dept-cate.sortable') }}')">保存排序</button>
                        </div>
                        <p class="text-muted font-13">按住鼠标左键不放，可以进行上下拖拽排序</p>
                        <ul class="sortable-list taskList list-unstyled ui-sortable" id="upcoming1">
                            @foreach($category as $cate)
                            <li class="task-warning ui-sortable-handle" id="{{ $cate->id }}">
                                <h4>{{ $cate->name }}</h4>
                                <div class="clearfix"></div>
                                <div class="mt-3">
                                    <p class="text-muted">部门类别代码：{{ $cate->code }}</p>
                                    <p class="text-muted">是否使用：
                                    @if($cate->status)
                                            <label class="badge badge-custom">是</label>
                                        @else
                                            <label class="badge badge-info">否</label>
                                        @endif
                                    </p>
                                    <p class="mb-0 mt-2">
                                        <button type="button" class="btn btn-success btn-sm waves-effect waves-light" onclick="openIframe('编辑', '{{ route('dept-cate.edit', ['id' => $cate->id]) }}')">编辑</button>
                                        <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" onclick="removeOne('{{ route('dept-cate.destroy', ['id' => $cate->id]) }}')">删除</button>
                                    </p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        <a href="javascrit:;" onclick="openIframe('新增部门类别', '{{ route('dept-cate.create') }}')" class="btn btn-custom btn-block mt-3 waves-effect waves-light"><i class="mdi mdi-plus-circle"></i> 新增</a>
                    </div>
                    <div class="tab-pane {{ request('tab')=='bb' ? 'show active' : '' }}" id="bb">
                        <div class="form-group mr-1">
                            <button class="btn btn-custom" disabled id="sortable1" onclick="sortable('{{ route('dept-bb.sortable') }}')">保存排序</button>
                        </div>
                        <p class="text-muted font-13">按住鼠标左键不放，可以进行上下拖拽排序</p>
                        @if(!$bb->isEmpty())
                        <ul class="sortable-list taskList list-unstyled ui-sortable" id="upcoming">
                            @foreach($bb as $b)
                            <li class="task-warning ui-sortable-handle" id="{{ $b->id }}">
                                <h4>{{ $b->name }}</h4>
                                <div class="clearfix"></div>
                                <div class="mt-3">
                                    <p class="text-muted">部门类别代码：{{ $b->code }}</p>
                                    <p class="text-muted">是否使用：
                                        @if($b->status)
                                            <label class="badge badge-custom">是</label>
                                        @else
                                            <label class="badge badge-info">否</label>
                                        @endif
                                    </p>
                                    <p class="mb-0 mt-2">
                                        <button type="button" class="btn btn-success btn-sm waves-effect waves-light" onclick="openIframe('编辑', '{{ route('dept-bb.edit', ['id' => $b->id]) }}')">编辑</button>
                                        <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" onclick="removeOne('{{ route('dept-bb.destroy', ['id' => $b->id]) }}')">删除</button>
                                    </p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @else
                        <p class="text-muted text-center">暂无数据！</p>
                        @endif
                        <a href="javascript:;" onclick="openIframe('新增部门办别', '{{ route('dept-bb.create') }}')" class="btn btn-custom btn-block mt-3 waves-effect waves-light"><i class="mdi mdi-plus-circle"></i> 新增</a>
                    </div>
                    <div class="tab-pane {{ request('tab')=='dept' ? 'show active' : '' }}" id="dept">
                        <div class="row">
                            <div class="col-sm-6">
                                <ul id="treeDemo" class="ztree"></ul>
                            </div>
                            <div class="col-sm-6">
                                <form action="#" data-parsley-validate="">
                                    <div class="form-group">
                                        <label>上级部门<span class="text-danger">*</span></label>
                                        <select name="parent_id" id="" class="form-control select2" required>
                                            <option value="0">一级部门</option>
                                            @foreach($dpts as $dpt)
                                                <option value="{{ $dpt['id'] }}">{!! $dpt['title_prefix'] . $dpt['title'] !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>部门编号<span class="text-danger">*</span></label>
                                        <input type="text" name="code" required=""
                                               placeholder="请输入部门编号，不超过10位"
                                               maxlength="10"
                                               class="form-control"
                                               autocomplete="off"
                                        >
                                        <p class="form-text text-muted">部门编号不超过10位，必须是字母或数字组成</p>
                                    </div>
                                    <div class="form-group">
                                        <label>部门名称<span class="text-danger">*</span></label>
                                        <input type="text" name="name" required=""
                                               placeholder="请输入部门名称，不超过30位"
                                               maxlength="30"
                                               class="form-control"
                                               autocomplete="off"
                                        >
                                        <p class="form-text text-muted">部门名称不超过30位</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="">部门类别</label>
                                        <select name="category" id="" class="form-control" required>
                                            <option value="">选择部门类别</option>
                                            @foreach($category as $item)
                                                <option value="{{ $item->code }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">部门办别</label>
                                        <select name="bbdm" id="" class="form-control">
                                            <option value="">选择部门办别</option>
                                            @foreach($bb as $item)
                                                <option value="{{ $item->code }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="">部门负责人</label>
                                    </div>

                                    <div class="form-group">
                                        <label for="">备注</label>
                                        <textarea name="remark" id="" class="form-control" rows="5" maxlength="200" placeholder="不超过200字"></textarea>
                                    </div>

                                    <div class="form-group m-b-0">
                                        <button class="btn btn-custom waves-effect waves-light" type="submit">
                                            提交
                                        </button>
                                    </div>

                                </form>
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

    <!-- Parsley js -->
    <script type="text/javascript" src="{{ asset('plugins/parsleyjs/dist/parsley.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/parsleyjs/src/i18n/zh_cn.js') }}"></script>

    <!--FooTable-->
    <script src="{{ asset('plugins/ztree/js/jquery.ztree.core.min.js') }}"></script>
    <script src="{{ asset('plugins/ztree/js/jquery.ztree.exedit.min.js') }}"></script>
    <!--FooTable Example-->
@endsection

@section('script')
    <script>
        var setting = {
            view: {
                addHoverDom: addHoverDom,
                removeHoverDom: removeHoverDom,
                selectedMulti: false
            },
            check: {
                enable: true
            },
            data: {
                simpleData: {
                    enable: true
                }
            },
            edit: {
                enable: true,
                removeTitle: "删除",
                renameTitle: "编辑",
            },
            callback: {
                beforeEditName: editHoverDom,
                beforeRemove: beforeRemove,
                onDrop: onDrop,
            },
        };

        var zNodes = {!! $data !!};
        var ids = [];
        $(document).ready(function() {
            $("#upcoming1").sortable({
                connectWith: ".taskList",
                placeholder: 'task-placeholder',
                forcePlaceholderSize: true,
                update: function (event, ui) {
                    $('#sortable1').attr('disabled', false);
                    ids = $('#upcoming1').sortable("toArray");
                }
            }).disableSelection();


            Parsley.on('form:submit', function(e) {
                var formData = $('form').serializeObject();
                return postFormData(formData, '{{ route('department.store') }}')
            });

            var treeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            treeObj.expandAll(true);
        });

        function addHoverDom(treeId, treeNode) {
            var sObj = $("#" + treeNode.tId + "_span");
            if (treeNode.editNameFlag || $("#addBtn_" + treeNode.tId).length > 0) {
                    return;
            }

            var addStr = "<span class='button add' id='addBtn_" + treeNode.tId +
                "' title='添加子节点' onfocus='this.blur();'></span>";
            sObj.after(addStr);
            var btn = $("#addBtn_" + treeNode.tId);
            if (btn) btn.bind("click", function() {
                //var zTree = $.fn.zTree.getZTreeObj("treeDemo");
                layer.open({
                    type: 2,
                    title: '新增子节点',
                    area:['800px', '600px'],
                    content:'{{ route('department.create') }}?pid=' + treeNode.id,
                });
                return false;
                /*zTree.addNodes(treeNode, {
                    id: (100 + newCount),
                    pId: treeNode.id,
                    name: "new node" + (newCount++)
                });*/
                return false;
            });
        };
        function removeHoverDom(treeId, treeNode) {
            $("#addBtn_" + treeNode.tId).unbind().remove();
        };

        function editHoverDom(treeId, treeNode) {
            openIframe('编辑节点', '/admin/department/' + treeNode.id + '/edit')
            return false;
        }
        function beforeRemove(treeId, treeNode) {
            removeOne('/admin/department/' + treeNode.id)
            return false;
        }
        function onDrop(srcEvent, treeId, treeNode, targetNode, moveType, isCopy) {
            var load = layer.load(2);
            $.ajax({
                type: 'post',
                url: '{{ route('save.dept.node') }}',
                dataType: 'json',
                timeout: 60000,
                data: {id:treeNode[0].id,parent_id:treeNode[0].pId,targetId:targetNode.id,moveType:moveType},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                complete: function () {
                    layer.close(load);
                },
                success: function (res) {
                    if (res.code == 200) {
                        layer.msg(res.msg, {time:1000}, function () {
                            window.location.reload();
                        });
                    } else {
                        layer.msg(res.msg);
                    }
                },
                error: function (xhr, status) {
                    if (status == 'timeout') {
                        layer.msg('服务器超时，请稍后重试');
                    } else {
                        layer.msg('服务器错误，请稍后重试');
                    }
                }
            });
        }

        function sortable(url) {
            if (!ids) {
                layer.msg('没有要保存的数据');
                return false;
            }
            var load = layer.load(2);
            $.ajax({
                type: 'post',
                url: url,
                dataType: 'json',
                timeout: 30000,
                data: {ids: ids},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                complete: function () {
                    layer.close(load);
                },
                success: function (res) {
                    if (res.code == 200) {
                        layer.msg(res.msg, {time:1000}, function () {
                            window.location.reload();
                        });
                    } else {
                        layer.msg(res.msg);
                    }
                },
                error: function (xhr, status) {
                    if (status == 'timeout') {
                        layer.msg('服务器超时，请稍后重试');
                    } else {
                        layer.msg('服务器错误，请稍后重试');
                    }
                }
            });
        }
    </script>
@endsection