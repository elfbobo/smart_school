<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>登录</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta content="{{ csrf_token() }}" name="_token">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/admin/images/favicon.ico') }}">

    <!-- App css -->
    <link href="{{ asset('assets/admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin/css/icons.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin/css/style.css') }}" rel="stylesheet" type="text/css" />

    <script src="{{ asset('assets/admin/js/modernizr.min.js') }}"></script>

</head>

<body>

<!-- Begin page -->
<div class="accountbg" style="background: url('{{ asset('assets/admin/images/bg.png') }}'); background-repeat: no-repeat"></div>

<div class="wrapper-page">
    <div class="card p-5" style="position: relative; width: 400px;margin: 0 auto;margin-top:230px;">
        <h2 class="text-uppercase text-center pb-4">
            <a href="/" class="text-success">
                <span><img src="{{ asset('assets/admin/images/logo.png') }}" alt="" style="width: 162px;"></span>
            </a>
        </h2>

        <form class="" action="#" data-parsley-validate>

            <div class="form-group m-b-30 row">
                <div class="col-12">
                    <input class="form-control" type="text" name="username" id="username" required="" placeholder="请输入账号">
                </div>
            </div>

            <div class="form-group row m-b-30">
                <div class="col-12">
                    <input class="form-control" type="password" name="password" required="" id="password" placeholder="请输入密码">
                    <a href="" class="text-muted pull-right" style="margin-top: 10px;"><small>忘记密码？</small></a>
                </div>
            </div>

            {{--<div class="form-group row m-b-20">
                <div class="col-12">

                    <div class="checkbox checkbox-custom">
                        <input id="remember" type="checkbox" checked="">
                        <label for="remember">
                            记住我
                        </label>
                    </div>

                </div>
            </div>--}}

            <div class="form-group row text-center m-t-10">
                <div class="col-12">
                    <button class="btn btn-block btn-custom waves-effect waves-light" type="submit">登录</button>
                </div>
            </div>

        </form>

        {{--<div class="row m-t-50">
            <div class="col-sm-12 text-center">
                <p class="text-muted">Don't have an account? <a href="page-register.html" class="text-dark m-l-5"><b>Sign Up</b></a></p>
            </div>
        </div>--}}

    </div>

    @include('admin.common.footer')

</div>


<!-- jQuery  -->
<script src="{{ asset('assets/admin/js/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/layer/dist/layer.js') }}"></script>

<!-- Parsley js -->
<script type="text/javascript" src="{{ asset('plugins/parsleyjs/dist/parsley.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/parsleyjs/src/i18n/zh_cn.js') }}"></script>

<script src="{{ asset('assets/admin/js/login.js') }}"></script>
<script>
    $(document).ready(function() {
        Parsley.on('form:submit', function(e) {
            var formData = $('form').serializeObject();
            return login(formData, '{{ url('login') }}');
        });
    });
</script>
</body>
</html>