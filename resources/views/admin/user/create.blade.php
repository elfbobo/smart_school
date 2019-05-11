@extends('admin.layout.form')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <form role="form" data-parsley-validate>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">人员编号<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <input type="text" name="code" class="form-control"
                                   placeholder="请输入人员编号，长度4-20位"
                                   minlength="4"
                                   maxlength="20" required autocomplete="off">
                            <p class="text-muted font-14 m-t-10">字母或数字组成，不超过20位</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">姓名<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <input type="text" name="name" class="form-control"
                                   placeholder="请输入姓名，不超过10位"
                                   maxlength="10" required autocomplete="off">
                        </div>
                    </div>
                    {{--<div class="form-group row">
                        <label class="col-2 col-form-label">性别<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <div class="radio radio-info form-check-inline">
                                <input type="radio" value="1" name="sex" checked>
                                <label> 男</label>
                            </div>
                            <div class="radio form-check-inline">
                                <input type="radio" value="2" name="sex">
                                <label> 女</label>
                            </div>
                        </div>
                    </div>--}}
                    <div class="form-group row">
                        <label class="col-2 col-form-label">密码</label>
                        <div class="col-10">
                            <input type="password" name="password" class="form-control"
                                   placeholder="可留空，默认123456，长度6-18位"
                                   minlength="6"
                                   maxlength="18" autocomplete="off">
                            <p class="text-muted font-14 m-t-10">字母或数字或其他字符串组合6-18位，默认123456</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">身份</label>
                        <div class="col-10">
                            <select name="type" id="" class="form-control">
                                <option value="0">学生</option>
                                <option value="1">教职工</option>
                            </select>
                        </div>
                    </div>
                    {{--<div class="form-group row">
                        <label class="col-2 col-form-label">手机号</label>
                        <div class="col-10">
                            <input type="text" class="form-control" name="phone" placeholder="可留空" autocomplete="off">
                            <p class="text-muted font-14 m-t-10">必须是合法的手机号</p>
                        </div>
                    </div>--}}
                    {{--<div class="form-group row">
                        <label class="col-2 col-form-label">固话/座机</label>
                        <div class="col-10">
                            <input type="text" class="form-control" name="telephone" placeholder="可留空" autocomplete="off">
                            <p class="text-muted font-14 m-t-10">非必填项，格式：025-88888888</p>
                        </div>
                    </div>--}}

                    {{--<div class="form-group row">
                        <label class="col-2 col-form-label">头像</label>
                        <div class="col-10">
                            <div id="show-img" style="margin-bottom: 10px;">
                            </div>
                            <input type="file"
                                   name="files"
                                   class="filestyle"
                                   data-input="false"
                                   data-btnClass="btn-success"
                                   data-disabled="false"
                                   data-text="上传头像">
                            <p class="text-muted">支持png、jpg、jpeg格式，大小不超过1M</p>
                        </div>
                    </div>--}}
                    
                    <div class="form-group row">
                        <div class="col-10 offset-2">
                            <input type="hidden" name="avatar" id="avatar">
                            <button type="submit" class="btn btn-custom waves-effect waves-light">
                                提交
                            </button>
                        </div>
                    </div>
                </form>
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
                return postFormData(formData, '{{ route('user.store') }}')
            });


            var upload = uploadFile('.filestyle', '/api/upload');
            //上传请求失败时触发的回调函数
            upload.on("fileuploadfail", function(e, data) {
                layer.msg('上传失败');
            })
            //上传请求成功时触发的回调函数
                .on("fileuploaddone", function(e, data) {
                    var res = data.result;
                    if (res.code == 1000) {
                        $('#show-img').children().remove();
                        $('#show-img').append('<img src="' + res.extra.filepath + '" alt="" width="120" class="">');
                        $('#avatar').val(res.extra.filepath);
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