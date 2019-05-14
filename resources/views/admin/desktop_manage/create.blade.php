@extends('admin.layout.form')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <form role="form" data-parsley-validate>
                    <div class="form-group">
                        <label>桌面名称<span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control"
                               placeholder="请输入桌面名称，长度4-50位"
                               minlength="4"
                               maxlength="50" required autocomplete="off">
                        <p class="text-muted font-14 m-t-10">请输入桌面名称，长度4-50位</p>
                    </div>
                    <div class="form-group">
                        <label>桌面英文名称</label>
                        <input type="text" name="name_eng" class="form-control"
                               placeholder="请输入桌面英文名称，长度4-50位"
                               minlength="4"
                               maxlength="50" autocomplete="off">
                        <p class="text-muted font-14 m-t-10">请输入桌面名称，长度4-50位，可留空</p>
                    </div>
                    <div class="form-group">
                        <label for="">添加卡片（上下拖拽可以排序）</label>
                        <ul class="sortable-list taskList list-unstyled" id="inprogress">

                        </ul>
                        <a onclick="opentab('{{ url('get-app-list') }}')" class="btn btn-custom btn-block mt-3 waves-effect waves-light"><i class="mdi mdi-plus-circle"></i> 点击添加</a>
                    </div>
                    <div class="form-group">
                        <label for="">选择角色</label>
                        <br>
                        <select name="role_ids[]" id="my-select" multiple>
                            @foreach($roles as $k => $role)
                                <option value="{{ $k }}">{{ $role }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-custom waves-effect waves-light">
                            提交
                        </button>
                    </div>
                </form>
            </div>

        </div> <!-- end col -->
    </div>
@endsection
@section('before-js')

@endsection
@section('script')
    <script type="text/javascript">
        var selectApp = [];
        var app_ids = [];
        $(document).ready(function() {
            //app_ids = $("#inprogress").sortable("toArray");
            $("#upcoming, #inprogress, #completed").sortable({
                connectWith: ".taskList",
                placeholder: 'task-placeholder',
                forcePlaceholderSize: true,
                update: function (event, ui) {
                    app_ids = $("#inprogress").sortable("toArray");
                }
            }).disableSelection();
            Parsley.on('form:submit', function (e) {
                if ('' == app_ids) { //这个为空，表示没有拖拽事件，直接获取卡片id数组
                    app_ids = $("#inprogress").sortable("toArray");
                }
                var formData = $('form').serializeObject();
                formData['app_ids'] = JSON.stringify(app_ids);
                return postFormData(formData, '{{ route('desktop_manage.store') }}', 'post', true)
            });

            //多选框
            $("#my-select").bootstrapDualListbox({
                nonSelectedListLabel: false,
                selectedListLabel: false,
                filterTextClear: '全部',
                filterPlaceHolder: '过滤条件',
                preserveSelectionOnMove: 'moved',
                moveOnSelect: true,
                moveAllLabel: '添加全部',
                moveSelectedLabel: '添加选中',
                removeAllLabel: '移除全部',
                removeSelectedLabel: '移除选中',
                infoText: '选中/未选中共 {0} 项',
                infoTextFiltered: '从 {1} 项 筛选 {0} 项',
                infoTextEmpty: '空',
            });
        });
        function opentab(url) {
            layer.open({
                type:2,
                area:['600px', '500px'],
                content:url,
                btn: ['确定', '取消'],
                yes: function (index, layero) {
                    //获取子级window对象
                    selectApp = [];
                    var formData = layer.getChildFrame('body');
                    $(formData).find('select option').each(function () {
                        if($(this).is(':selected')) {
                            arr = {
                                'app_id': $(this).val(),
                                'app_name': $(this).text(),
                            },
                            selectApp.push(arr);
                        }
                    });

                    var html = '';
                    if (selectApp) {
                        for (i=0;i<selectApp.length;i++) {
                            html += '<li id="' + selectApp[i]['app_id'] + '">\n' +
                            '' + selectApp[i]['app_name'] + '\n' +
                            '<div class="mt-2">\n' +
                            '<p class="mb-0">\n' +
                            '<button type="button" class="btn btn-danger btn-sm" onclick="trashThis(this, ' + selectApp[i]['app_id'] + ')">删除</button>\n' +
                            '</p>\n' +
                            '</div>\n' +
                            '</li>';
                        }
                    }
                    $('#inprogress').children().remove();
                    $('#inprogress').append(html);
                    layer.close(index);
                },
                btn2: function (index) {
                    layer.close(index)
                }
            });
        }

        //删除卡片
        function trashThis(obj, id) {
            app_ids = []; //清空已获取的，防止第二次提交还保留了上次已删除的卡片id
            var newData = selectApp.filter(function(item) { //将本次删除的卡片id从已选中的数组中过滤掉
                return item['app_id'] != id;
            });
            selectApp = newData;
            $(obj).parents('li').remove();
        }
    </script>
@endsection