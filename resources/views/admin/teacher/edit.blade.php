@extends('admin.layout.app')
@section('title', '新增教职工信息')
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
@endsection
@section('content')
    @include('admin.layout.location')
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <form data-parsley-validate>
                    <div class="form-group">
                        <label class="">头像</label>
                        <div id="show-img" style="margin-bottom: 10px;">
                            @if($info->avatar)
                                <img src="{{ $info->avatar }}" alt="" width="120" class="">
                            @endif
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
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label class="">职工号<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="union_id"
                                   placeholder="请输入职工号，小写字母或数字组成"
                                   minlength="4"
                                   maxlength="20"
                                   value="{{ $info->union_id }}"
                                   required
                                   autocomplete="off"
                            >
                            <p class="form-text text-muted">职工号由小写字母或数字组成，不超过20位</p>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="">姓名<span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control"
                                   placeholder="请输入姓名，长度2-30位"
                                   minlength="2"
                                   maxlength="30"
                                   value="{{ $info->name }}"
                                   required autocomplete="off">
                            <p class="text-muted m-t-10">请输入姓名，长度2-30位</p>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="">手机号</label>
                            <input type="text" name="phone" class="form-control"
                                   placeholder="请输手机号"
                                   maxlength="11"
                                   value="{{ $info->phone }}"
                                   autocomplete="off"
                            >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="">性别</label>
                            <select name="gender" id="" class="form-control">
                                <option value="1" {{ $info->gender == 1 ? 'selected' : '' }}>男</option>
                                <option value="2" {{ $info->gender == 2 ? 'selected' : '' }}>女</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">出生日期</label>
                            <input type="text" name="birthday" class="form-control datetime"
                                   placeholder="选择出生日期"
                                   value="{{ $info->birthday }}"
                                   autocomplete="off"
                            >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="">所属部门<span class="text-danger">*</span></label>
                            <select name="dept_id" id="" class="form-control select2">
                                <option value=""></option>
                                @foreach($dept as $item)
                                    <option value="{{ $item['id'] }}" {{ $info->dept_id == $item['id'] ? 'selected' : '' }}>{!! $item['title_display'] !!}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">政治面貌</label>
                            <select name="politics_status" id="" class="form-control select2">
                                <option value=""></option>
                                @foreach($politics_status as $k => $v)
                                    <option value="{{ $k.'|'.$v }}" {{ $info->politics_status == $k ? 'selected' : '' }}>[{{ $k }}]{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="">证件类型</label>
                            <select name="id_type" id="" class="form-control select2">
                                <option value=""></option>
                                @foreach($id_type as $k => $v)
                                    <option value="{{ $k.'|'.$v }}" {{ $info->id_type == $k ? 'selected' : '' }}>[{{ $k }}]{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">证件号码</label>
                            <input type="text" class="form-control"
                                   name="id_card"
                                   placeholder="请输入证件号码，不超过30位"
                                   minlength="6"
                                   value="{{ $info->id_card }}"
                                   maxlength="30">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="">最高学位</label>
                            <select name="degree" id="" class="form-control select2">
                                <option value=""></option>
                                @foreach($degree as $k => $v)
                                    <option value="{{ $k.'|'.$v }}" {{ $info->degree == $k ? 'selected' : '' }}>[{{ $k }}]{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">最高学历</label>
                            <select name="education" id="" class="form-control select2">
                                <option value=""></option>
                                @foreach($education as $k => $v)
                                    <option value="{{ $k.'|'.$v }}" {{ $info->education == $k ? 'selected' : '' }}>[{{ $k }}]{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="">职称级别</label>
                            <select name="titles" id="" class="form-control select2">
                                <option value=""></option>
                                @foreach($titles as $k => $v)
                                    <option value="{{ $k.'|'.$v }}" {{ $info->titles == $k ? 'selected' : '' }}>[{{ $k }}]{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="">任职资格名称</label>
                            <select name="qualification" id="" class="form-control select2">
                                <option value=""></option>
                                @foreach($qualification as $k => $v)
                                    <option value="{{ $k.'|'.$v }}" {{ $info->qualification == $k ? 'selected' : '' }}>[{{ $k }}]{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label class="">籍贯（省/市/区）</label>
                            <select name="native_place" id="province" class="form-control select2">
                                <option value=""></option>
                                @foreach($region as $code => $area)
                                    <option value="{{ $area }}" {{ $info->native_place == $area ? 'selected' : '' }}>{{ '['. $code .']' . $area }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="">当前状态<span class="text-danger">*</span></label>
                            <select name="status" id="province" class="form-control select2" required>
                                <option value=""></option>
                                @foreach($status as $code => $v)
                                    <option value="{{ $code . '|' . $v }}" {{ $info->status == $code ? 'selected' : '' }}>{{ '['. $code .']' . $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">备注</label>
                        <textarea name="remark" rows="5" class="form-control" placeholder="不超过200字" maxlength="200">{{ $info->remark }}</textarea>
                    </div>


                    <div class="form-group text-center">
                        <input type="hidden" name="avatar" id="avatar" value="{{ $info->avatar }}">
                        <button type="submit" class="btn btn-custom waves-effect waves-light">
                            提交
                        </button>
                        <button type="button" class="btn btn-info waves-effect waves-light"
                                onclick="window.history.back(-1)">取消</button>
                    </div>
                </form>
            </div>

        </div> <!-- end col -->
    </div>
@endsection
@section('before-js')
    <script src="{{ asset('plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-datepicker/locales/bootstrap-datepicker.zh-CN.min.js') }}"></script>
    <!--上传插件-->
    <script src="{{ asset('plugins/jquery-file-upload/js/vendor/jquery.ui.widget.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/jquery-file-upload/js/jquery.iframe-transport.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/jquery-file-upload/js/jquery.fileupload.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/jquery-file-upload/js/jquery.fileupload-process.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/jquery-file-upload/js/jquery.fileupload-validate.js') }}" type="text/javascript"></script>

    <!-- Parsley js -->
    <script type="text/javascript" src="{{ asset('plugins/parsleyjs/dist/parsley.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/parsleyjs/src/i18n/zh_cn.js') }}"></script>
@endsection
@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            initDatePicker('.datetime'); //初始化日期控件
            Parsley.on('form:submit', function(e) {
                var formData = $('form').serializeObject();
                return postFormData(formData, '{{ route('teacher.update', ['id' => $info->id]) }}', 'put', false)
            });


            var upload = new uploadFile('.filestyle', '/api/upload');
            var load;
            upload.setMaxFileSize(1024*1024);
            upload.init();
            upload = upload.btInstance;
            //图片添加完成后触发的事件
            upload.on("fileuploadadd", function(e, data) {
                load = layer.msg('正在上传，请稍等片刻...', {time:0, icon: 16, shade:0.3});
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
                    layer.close(load);
                    $('button[type=submit]').attr('disabled', false);
                })

        });


        //多选框
        /*$("#my-select").bootstrapDualListbox({
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
        });*/
    </script>
@endsection