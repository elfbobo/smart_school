@extends('admin.layout.form')
@section('css')
    <link href="{{ asset('plugins/ztree/css/bootstrapStyle/bootstrapStyle.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <h4 class="m-t-0 header-title">分配节点</h4>
                <p class="text-muted m-b-30 font-13">
                    为当前角色分配节点权限
                </p>
                <ul id="treeDemo" class="ztree"></ul>
                <div style="margin-top: 20px;">
                    <button class="btn btn-custom" id="submit" type="button" onclick="saveNode()" disabled>保存</button>
                </div>
            </div>

        </div> <!-- end col -->
    </div>
@endsection
@section('before-js')
    <script src="{{ asset('plugins/ztree/js/jquery.ztree.core.min.js') }}"></script>
    <script src="{{ asset('plugins/ztree/js/jquery.ztree.excheck.js') }}"></script>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            Parsley.on('form:submit', function(e) {
                var formData = $('form').serializeObject();
                return postFormData(formData, '{{ route('role.store') }}')
            });
            getNodes();
        });
        var setting = {
            view: {
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
                //onCheck:onCheck
            },
        };
        function getNodes() {
            var load = layer.load(2);
            $.ajax({
                type: 'get',
                url: '{{ route('menu.node') }}',
                dataType: 'json',
                timeout: 60000,
                data: {role_id: '{{ $role_id }}'},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                complete: function () {
                    layer.close(load);
                    $('#submit').attr('disabled', false);
                },
                success: function (res) {
                    if (res.data != '') {
                        var treeObj = $.fn.zTree.init($("#treeDemo"), setting, res.data);
                        for(var i = 0; i < res.data.length; i++) {
                            //zTree.checkNode(node, true, true);
                            var node = treeObj.getNodeByParam("menu_id", res.data[i].menu_id);
                            if(node.menu_id != null) {
                                treeObj.checkNode(node, true)
                                treeObj.expandNode(node, true)
                            }
                        }
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

        //保存节点
        function saveNode() {
            var params = {
                ids: [],
                role_id: '{{ $role_id }}'
            };
            var treeObj=$.fn.zTree.getZTreeObj("treeDemo"),
                nodes=treeObj.getCheckedNodes(true);
            for (i = 0; i < nodes.length; i ++) {
                params.ids.push(nodes[i]['id'])
            }
            if (params.ids == '') {
                layer.msg('请至少勾选一个');
                return false;
            }
            var load = layer.load(2);
            $('#submit').attr('disabled', true);
            $.ajax({
                type: 'post',
                url: '{{ route('role.auth') }}',
                dataType: 'json',
                timeout: 60000,
                data: params,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                complete: function () {
                    layer.close(load);
                    $('#submit').attr('disabled', false);
                },
                success: function (res) {
                    if (res.code == 200) {
                        layer.msg(res.msg, {time: 1000}, function () {
                            window.location.reload();
                        })
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