@extends('admin.layout.form')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <form role="form" data-parsley-validate>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">应用图标</label>
                        <div class="col-10">
                            <div id="show-img" style="margin-bottom: 10px;">
                                <img src="{{ $info->icon_url }}" alt="" width="120">
                            </div>
                            <input type="file"
                                   name="files"
                                   class="filestyle"
                                   data-input="false"
                                   data-btnClass="btn-success"
                                   data-disabled="false"
                                   data-text="上传">
                            <p class="text-muted">支持png、jpg、jpeg格式，大小不超过1M</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-2 col-form-label">应用类型<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <select name="category" class="form-control">
                                @foreach($category as $k => $item)
                                    <option value="{{ $k }}" {{ $info->category == $k ? 'selected' : '' }}>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-2 col-form-label">应用名称<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <input type="text" name="name" class="form-control"
                                   placeholder="请输入应用名称，长度4-30位"
                                   minlength="4"
                                   value="{{ $info->name }}"
                                   maxlength="30" required autocomplete="off">
                            <p class="text-muted font-14 m-t-10">请输入应用名称，长度4-30位</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">应用说明</label>
                        <div class="col-10">
                            <textarea name="description" id="" rows="6" class="form-control">{{ $info->description }}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-2 col-form-label">入口地址<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <input type="text" name="entry_url" class="form-control"
                                   value="{{ $info->entry_url }}"
                                   placeholder="请输入应用入口地址"
                                   required autocomplete="off">
                            {{--<p class="text-muted font-14 m-t-10">请输入应用名称，长度4-30位</p>--}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">版本号<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <input type="number" name="version_code" class="form-control"
                                   value="{{ $info->version_code }}"
                                   placeholder="请输入版本号，必须是数字"
                                   min="0"
                                   required autocomplete="off">
                            <p class="text-muted font-14 m-t-10">请输入版本号，必须是数字</p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">版本名称</label>
                        <div class="col-10">
                            <input type="text" name="version_name" class="form-control"
                                   value="{{ $info->version_name }}"
                                   placeholder="请输入版本名称，不超过50个字符"
                                   maxlength="50"
                                   autocomplete="off">
                            <p class="text-muted font-14 m-t-10">请输入版本名称，不超过50个字符</p>
                        </div>
                    </div>
                    {{--<div class="form-group row">
                        <label class="col-2 col-form-label">PC应用<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <div class="radio radio-info form-check-inline">
                                <input type="radio" value="0" name="is_pc_app" {{ $info->is_pc_app == 0 ? 'checked' : '' }}>
                                <label> 否</label>
                            </div>
                            <div class="radio form-check-inline">
                                <input type="radio" value="1" name="is_pc_app" {{ $info->is_pc_app == 1 ? 'checked' : '' }}>
                                <label> 是</label>
                            </div>
                        </div>
                    </div>--}}
                    {{--<div class="form-group row">
                        <label class="col-2 col-form-label">移动应用<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <div class="radio radio-info form-check-inline">
                                <input type="radio" value="0" name="is_mobile_app" {{ $info->is_mobile_app == 0 ? 'checked' : '' }}>
                                <label> 否</label>
                            </div>
                            <div class="radio form-check-inline">
                                <input type="radio" value="1" name="is_mobile_app" {{ $info->is_mobile_app == 1 ? 'checked' : '' }}>
                                <label> 是</label>
                            </div>
                        </div>
                    </div>--}}
                    {{--<div class="form-group row">
                        <label class="col-2 col-form-label">PC端卡片<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <div class="radio radio-info form-check-inline">
                                <input type="radio" value="0" name="has_pc_card" {{ $info->has_pc_card == 0 ? 'checked' : '' }}>
                                <label> 否</label>
                            </div>
                            <div class="radio form-check-inline">
                                <input type="radio" value="1" name="has_pc_card" {{ $info->has_pc_card == 1 ? 'checked' : '' }}>
                                <label> 是</label>
                            </div>
                        </div>
                    </div>--}}

                    {{--<div class="form-group row">
                        <label class="col-2 col-form-label">移动端卡片<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <div class="radio radio-info form-check-inline">
                                <input type="radio" value="0" name="has_mobile_card" {{ $info->has_mobile_card == 0 ? 'checked' : '' }}>
                                <label> 否</label>
                            </div>
                            <div class="radio form-check-inline">
                                <input type="radio" value="1" name="has_mobile_card" {{ $info->has_mobile_card == 1 ? 'checked' : '' }}>
                                <label> 是</label>
                            </div>
                        </div>
                    </div>--}}

                    <div class="form-group row">
                        <label class="col-2 col-form-label">新应用<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <div class="radio radio-info form-check-inline">
                                <input type="radio" value="0" name="is_new" {{ $info->is_new == 0 ? 'checked' : '' }}>
                                <label> 否</label>
                            </div>
                            <div class="radio form-check-inline">
                                <input type="radio" value="1" name="is_new" {{ $info->is_new == 1 ? 'checked' : '' }}>
                                <label> 是</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-2 col-form-label">推荐应用<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <div class="radio radio-info form-check-inline">
                                <input type="radio" value="0" name="is_recommended" {{ $info->is_recommended == 0 ? 'checked' : '' }}>
                                <label> 否</label>
                            </div>
                            <div class="radio form-check-inline">
                                <input type="radio" value="1" name="is_recommended" {{ $info->is_recommended == 1 ? 'checked' : '' }}>
                                <label> 是</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-2 col-form-label">上线状态<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <div class="radio radio-info form-check-inline">
                                <input type="radio" value="0" name="online_status" {{ $info->online_status == 0 ? 'checked' : '' }}>
                                <label> 未上线</label>
                            </div>
                            <div class="radio form-check-inline">
                                <input type="radio" value="1" name="online_status" {{ $info->online_status == 1 ? 'checked' : '' }}>
                                <label> 运行中</label>
                            </div>
                            <div class="radio form-check-inline">
                                <input type="radio" value="2" name="online_status" {{ $info->online_status == 2 ? 'checked' : '' }}>
                                <label> 已下线</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-2 col-form-label">周期服务<span class="text-danger">*</span></label>
                        <div class="col-10">
                            <div class="radio radio-info form-check-inline">
                                <input type="radio" value="0" name="is_cycle" {{ $info->is_cycle == 0 ? 'checked' : '' }}>
                                <label> 否</label>
                            </div>
                            <div class="radio form-check-inline">
                                <input type="radio" value="1" name="is_cycle" {{ $info->is_cycle == 1 ? 'checked' : '' }}>
                                <label> 是</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row" id="cycle" style="display: none;">
                        <label class="col-2 col-form-label">周期服务时间</label>
                        <div class="col-10">
                            <input type="text"
                                   name="cycle_begin_end_time"
                                   class="form-control"
                                   id="begin-end-time"
                                   placeholder="请选择服务开始时间/结束时间"
                                   value="{{ $info->cycle_begin_time . '~' . $info->cycle_end_time }}"
                                   readonly
                                   autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">提供商</label>
                        <div class="col-10">
                            <input type="text" class="form-control"
                                   name="vender"
                                   placeholder="请输入提供商"
                                   value="{{ $info->vender }}"
                                   autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-10 offset-2">
                            <input type="hidden" name="icon_url" id="avatar" value="{{ $info->icon_url }}">
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
            @if($info->is_cycle)
            $('#cycle').show();
            @endif

            Parsley.on('form:submit', function(e) {
                var formData = $('form').serializeObject();
                return postFormData(formData, '{{ route('app_manage.update', ['id' => $info->id]) }}', 'put', true)
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



        //周期服务时间选择框
        $('input[name="is_cycle"]').on('click', function () {
            if ($(this).val() == 1) {
                $('#cycle').show();
            } else {
                $('#cycle').hide();
            }
        })

        //年月日时分秒
        jeDate("#begin-end-time",{
            range:"~",
            minDate:"1900-01-01 00:00:00",
            maxDate:"2099-12-31 23:59:59",
            format: 'YYYY-MM-DD hh:mm:ss',
            multiPane: false,
        });
    </script>
@endsection