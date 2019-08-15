@extends('admin.layout.app')
@section('title', '角色管理')
@section('css')

@endsection
@section('content')
    <!-- Page-Title -->
    @include('admin.layout.location')
    <!-- end page title end breadcrumb -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <h4 class="m-t-0 header-title">系统配置</h4>
                <p class="text-muted m-b-30 font-13">
                    系统配置
                </p>
                <form action="" data-parsley-validate>
                    <div class="form-group">
                        <label for="">网站标题</label>
                        <input type="text" name="title" class="form-control"
                               value="{{ isset($web_config['title']) ? $web_config['title'] : '' }}"
                               placeholder="请输入网站标题，不超过20个字" maxlength="20">
                    </div>
                    <div class="form-group">
                        <label for="">网站Logo</label>
                        <div id="show-img" style="margin-bottom: 10px;">
                            @if(isset($web_config['logo']) && $web_config['logo'] != '')
                                <img src="{{ $web_config['logo'] }}" alt="" width="120">
                            @endif
                        </div>
                        <input type="file"
                               name="files"
                               class="filestyle"
                               data-input="false"
                               data-btnClass="btn-success"
                               data-disabled="false"
                               data-text="选择图片">
                        <p class="form-text text-muted">支持png、jpg、jpeg格式，大小不超过1M；宽高比：162*34</p>
                    </div>
                    <div class="form-group">
                        <label for="">网站版权（copyright）</label>
                        <input type="text" name="copyright" class="form-control"
                               value="{{ isset($web_config['copyright']) ? $web_config['copyright'] : '' }}"
                               placeholder="请输入网站版权，不超过100个字符" maxlength="100">
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="logo" id="logo" value="{{ isset($web_config['logo']) ? $web_config['logo'] : '' }}">
                        <button type="submit" class="btn btn-primary">提交</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('before-js')
    <!-- Parsley js -->
    <script type="text/javascript" src="{{ asset('plugins/parsleyjs/dist/parsley.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/parsleyjs/src/i18n/zh_cn.js') }}"></script>

    <script src="{{ asset('plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js') }}" type="text/javascript"></script>
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
                return postFormData(formData, '{{ route('setting.store') }}')
            });

            var upload = new uploadFile('.filestyle', '/api/upload');
            upload.setMaxFileSize(1024*1024);
            upload.init();
            //上传请求失败时触发的回调函数
            upload.btInstance.on("fileuploadfail", function(e, data) {
                layer.msg('上传失败');
            })
            //上传请求成功时触发的回调函数
                .on("fileuploaddone", function(e, data) {
                    var res = data.result;
                    if (res.code == 1000) {
                        $('#show-img').children().remove();
                        $('#show-img').append('<img src="' + res.extra.filepath + '" alt="" width="120" class="">');
                        $('#logo').val(res.extra.filepath);
                    }  else {
                        layer.msg(res.msg);
                    }
                })
                //上传请求结束后，不管成功，错误或者中止都会被触发
                .on("fileuploadalways", function(e, data) {
                    $('button[type=submit]').attr('disabled', false);
                })
        });
    </script>
@endsection