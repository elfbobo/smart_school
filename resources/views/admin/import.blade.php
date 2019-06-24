@extends('admin.layout.form')
@section('title', '导入')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="alert alert-custom" role="alert">
                    <ol>
                        <li>导入前，先下载模板，按模板格式导入数据</li>
                        <li>数据越多导入时间就会越长，请耐心等待导入完成</li>
                        {!! $tips ?? null !!}
                    </ol>
                    <a href="{{ route('download.file', ['file' => $template]) }}" class="btn btn-custom btn-sm">下载模板</a>
                </div>
                <form data-parsley-validate>
                    <div class="form-group">
                        <label class="">选择excel文件</label>
                        <input type="file"
                               name="files"
                               class="filestyle"
                               data-input="false"
                               data-btnClass="btn-success"
                               data-disabled="false"
                               data-text="上传">
                        <p class="text-muted">仅支持xls、xlsx格式</p>
                    </div>
                </form>
            </div>

        </div> <!-- end col -->
    </div>
@endsection
@section('before-js')
    <script src="{{ asset('plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js') }}"></script>
    <!--上传插件-->
    <script src="{{ asset('plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/jquery-file-upload/js/jquery.iframe-transport.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/jquery-file-upload/js/jquery.fileupload.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/jquery-file-upload/js/jquery.fileupload-process.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/jquery-file-upload/js/jquery.fileupload-validate.js') }}" type="text/javascript"></script>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            var upload = new uploadFile('.filestyle', '{{ $export_url }}');
            var load;
            upload.setAcceptFileTypes(/(xls|xlsx)$/i);
            upload.init();
            upload = upload.btInstance
            //图片添加完成后触发的事件
            upload.on("fileuploadadd", function(e, data) {
                load = layer.msg('正在处理，请稍等片刻...', {time:0, icon: 16, shade:0.3});
            })
            //当一个单独的文件处理队列结束触发(验证文件格式和大小)
            upload.on("fileuploadprocessalways", function(e, data) {
                //获取文件
                file = data.files[0];
                //获取错误信息
                if (file.error) {
                    layer.msg(file.error);
                }
            })
            //显示上传进度条
            upload.on("fileuploadprogressall", function(e, data) {

            })
            //上传请求失败时触发的回调函数
            upload.on("fileuploadfail", function(e, data) {
                layer.msg('上传失败');
            })
            //上传请求成功时触发的回调函数
                .on("fileuploaddone", function(e, data) {
                    var res = data.result;
                    if (res.code == 200) {
                        if (res.data.errors != '') {
                            var _html = '<table class="table table-bordered"><tr><td>行号</td><td>错误说明</td></tr>'
                            for (var index in res.data.errors) {
                                _html += '<tr><td>'+(parseInt(index)+1)+'</td><td>'+res.data.errors[index]+'</td></tr>'
                            }
                            _html += '</table>';
                            parent.layer.open({
                                type: 1,
                                title: res.msg,
                                area:['600px', '500px'],
                                content: _html,
                                btn: ['确定'],
                                yes: function () {
                                    parent.window.location.reload();
                                }
                            });
                        } else {
                            layer.msg(res.msg, {time: 1500}, function () {
                                parent.window.location.reload();
                            });
                        }

                    }  else {
                        layer.msg(res.msg);
                    }
                })
                //上传请求结束后，不管成功，错误或者中止都会被触发
                .on("fileuploadalways", function(e, data) {
                    layer.close(load)
                })
        });



        //周期服务时间选择框
        $('input[name="is_cycle"]').on('click', function () {
            if ($(this).val() == 1) {
                $('#cycle').show();
            } else {
                $('#cycle').hide();
            }
        })

        //多选框
        $("#my-select").bootstrapDualListbox({
            nonSelectedListLabel: false,
            selectedListLabel: false,
            filterTextClear: '全部',
            filterPlaceHolder: '过滤条件',
            preserveSelectionOnMove: 'moved',
            moveOnSelect: false,
            moveAllLabel: '添加全部',
            moveSelectedLabel: '添加选中',
            removeAllLabel: '移除全部',
            removeSelectedLabel: '移除选中',
            infoText: '选中/未选中共 {0} 项',
            infoTextFiltered: '从 {1} 项 筛选 {0} 项',
            infoTextEmpty: '空',
        });
    </script>
@endsection