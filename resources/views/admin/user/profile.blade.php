@extends('admin.layout.app')
@section('title', '个人设置')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group">
                    <ol class="breadcrumb hide-phone p-0 m-0">
                        <li class="breadcrumb-item"><a href="/">首页</a></li>
                        <li class="breadcrumb-item active">个人设置</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <!-- meta -->
            <div class="profile-user-box card-box bg-custom">
                <div class="row">
                    <div class="col-sm-6">
                        <span class="pull-left mr-3"><img src="{{ $userinfo->avatar ? $userinfo->avatar : asset('assets/admin/images/users/avatar-1.jpg') }}" alt="" class="thumb-lg rounded-circle"></span>
                        <div class="media-body text-white">
                            <h4 class="mt-1 mb-1 font-18">{{ $userinfo->name }}</h4>
                            <p class="font-13 text-light">{{ getGreetings() }}</p>
                            <p class="text-light mb-0">手机号：{{ $userinfo->phone }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ meta -->
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box ribbon-box">
                <div class="ribbon ribbon-primary">个人信息</div>
                <div class="clearfix"></div>
                <form role="form" data-parsley-validate>
                    <div class="form-group">
                        <label for="exampleInputEmail1">人员编号</label>
                        <input type="text" class="form-control" value="{{ $userinfo->code }}" disabled>
                        <small id="emailHelp" class="form-text text-muted">人员编号不可修改</small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">姓名</label>
                        <input type="text" name="name" class="form-control"
                               placeholder="请输入姓名"
                               value="{{ $userinfo->name }}"
                               minlength="2" maxlength="20" required>
                        <small id="emailHelp" class="form-text text-muted">不超过20个字</small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">昵称</label>
                        <input type="text" name="nickname" class="form-control"
                               placeholder="可为空，不超过20个字"
                               value="{{ $userinfo->nickname }}"
                               maxlength="20">
                        <small id="emailHelp" class="form-text text-muted">不超过20个字</small>
                    </div>
                    <div class="form-group">
                        <label for="">性别</label><br>
                        <div class="radio radio-info form-check-inline">
                            <input type="radio" value="1" name="sex" {{ $userinfo->sex == 1 ? 'checked' : '' }}>
                            <label for="inlineRadio1"> 男</label>
                        </div>
                        <div class="radio radio-custom form-check-inline">
                            <input type="radio" value="2" name="sex" {{ $userinfo->sex == 2 ? 'checked' : '' }}>
                            <label for="inlineRadio1"> 女</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">密码</label>
                        <input type="password" name="password" class="form-control" placeholder="密码留空表示不修改">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">手机号</label>
                        <input type="text" name="phone" class="form-control"
                               placeholder="可为空，不超过11个字符"
                               value="{{ $userinfo->phone }}"
                               maxlength="11">
                        <small id="emailHelp" class="form-text text-muted">必须是11位有效的手机号</small>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">固话/座机</label>
                        <input type="text" name="telephone"
                               class="form-control"
                               placeholder="可留空"
                               value="{{ $userinfo->telephone }}"
                               maxlength="20">
                        <small id="emailHelp" class="form-text text-muted">正确格式为：025-88888888</small>
                    </div>

                    <button type="submit" class="btn btn-primary">提交</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('before-js')
    <!-- Parsley js -->
    <script type="text/javascript" src="{{ asset('plugins/parsleyjs/dist/parsley.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/parsleyjs/src/i18n/zh_cn.js') }}"></script>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            Parsley.on('form:submit', function(e) {
                var formData = $('form').serializeObject();
                return postFormData(formData, '{{ route('user.profile') }}')
            });
        });
    </script>
@endsection