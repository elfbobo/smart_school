@extends('admin.layout.form')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class="alert alert-danger" role="alert">
                    <h4>注意事项：</h4>
                    <ul>
                        <li>请先下载好模板，按模板格式填充好数据</li>
                        <li>不要随意改变表头顺序，必须严格按照表头格式</li>
                        <li>一次性导入数据最好不要超过1000条，防止浏览器长时间无响应、卡死</li>
                        @if(isset($tips))
                            {!! $tips !!}
                        @endif
                        <li>下载模板：<a href="{{ route('download.file', ['file' => $template]) }}" class="btn btn-custom">点击下载</a></li>
                    </ul>
                </div>
                <input type="file" name="file" class="filestyle" data-buttontext="Select file"
                       data-btnClass="btn-success" data-text="选择文件">
            </div>
        </div> <!-- end col -->
    </div>
@endsection
@section('before-js')
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
            Parsley.on('form:submit', function(e) {
                var formData = $('form').serializeObject();
                return postFormData(formData, '{{ route('column.store') }}')
            });

            var upload = uploadFile('.filestyle', '', /(xls|xlsx|csv)$/i, 1, 10*1024*1024);
            //上传请求失败时触发的回调函数
            upload.on("fileuploadfail", function(e, data) {
                layer.msg('上传失败');
            })
            //上传请求成功时触发的回调函数
                .on("fileuploaddone", function(e, data) {
                    var res = data.result;
                    if (res.data.errors != '') {
                        var _html = '';
                        var num = 0;
                        $.each(res.data.errors, function (index, value) {
                            _html += '<p style="padding:15px;">' + value + '</p>'
                            num++;
                        })
                        layer.msg(res.msg, {time: 1500}, function () {
                            layer.open({
                                type: 1,
                                title: '导入失败：' + num + '条',
                                area: ['600px', '500px'],
                                content: _html,
                            })
                        })
                    } else {
                        layer.msg(res.msg, {time: 1500}, function () {
                            parent.window.location.reload();
                        });
                    }
                })
        });
        
    </script>
@endsection