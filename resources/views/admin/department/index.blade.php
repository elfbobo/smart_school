@extends('admin.layout.app')
@section('title', '部门管理')
@section('css')
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
                <p class="text-muted m-b-30 font-13">
                    选中一个节点，可以自由拖拽排序
                </p>

                {{--<div class="mb-3">
                    <div class="row">
                        <div class="col-12 text-sm-center form-inline">
                            <div class="form-group mr-2">
                                <button class="btn btn-primary" onclick="openIframe('新增', '{{ route('user.create') }}')"><i class="fa fa-plus mr-2"></i> 新增</button>
                            </div>
                        </div>
                    </div>
                </div>--}}
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
@endsection
@section('before-js')
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

        var zNodes = {!! $data !!}
        $(document).ready(function() {
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
    </script>
@endsection