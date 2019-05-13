<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>@yield('title') - 管理系统</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta content="{{ csrf_token() }}" name="_token">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/admin/images/favicon.ico') }}">
    <!-- Plugins css-->
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-duallistbox/dist/bootstrap-duallistbox.min.css') }}">
    <link href="{{ asset('plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/jeDate/skin/jedate.css') }}" rel="stylesheet" type="text/css" />

    <!-- jquery UI -->
    <link href="{{ asset('plugins/jquery-ui/jquery-ui.min.css') }}" rel="stylesheet" type="text/css" />

@yield('css')
<!-- App css -->
    <link href="{{ asset('assets/admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin/css/icons.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin/css/style.css') }}" rel="stylesheet" type="text/css" />

    <script src="{{ asset('assets/admin/js/modernizr.min.js') }}"></script>
    @yield('style')
</head>

<body>

<div class="container-fluid" style="">
    @yield('content')
</div> <!-- end container -->

<!-- jQuery  -->
<script src="{{ asset('assets/admin/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/waves.js') }}"></script>
<script src="{{ asset('assets/admin/js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('plugins/layer/dist/layer.js') }}"></script>

<script src="{{ asset('plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js') }}"></script>
<script src="{{ asset('plugins/select2/js/select2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('plugins/select2/js/i18n/zh-CN.js') }}" type="text/javascript"></script>
<script src="{{ asset('plugins/bootstrap-select/js/bootstrap-select.js') }}" type="text/javascript"></script>
<script src="{{ asset('plugins/bootstrap-select/js/i18n/defaults-zh_CN.js') }}" type="text/javascript"></script>
<script src="{{ asset('plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('plugins/bootstrap-duallistbox/dist/jquery.bootstrap-duallistbox.min.js') }}"></script>

<!-- Parsley js -->
<script type="text/javascript" src="{{ asset('plugins/parsleyjs/dist/parsley.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/parsleyjs/src/i18n/zh_cn.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/jeDate/dist/jedate.min.js') }}"></script>

<!-- Jquery Ui js -->
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>

@yield('before-js')

<!-- KNOB JS -->
<!--[if IE]>
<script type="text/javascript" src="{{ asset('plugins/jquery-knob/excanvas.js') }}"></script>
<![endif]-->
<script src="{{ asset('plugins/jquery-knob/jquery.knob.js') }}"></script>

<!-- App js -->
<script src="{{ asset('assets/admin/js/jquery.core.js') }}"></script>
<script src="{{ asset('assets/admin/js/jquery.app.js') }}"></script>
<script src="{{ asset('assets/admin/js/common.js') }}"></script>
{{--<script src="{{ asset('assets/admin/pages/jquery.form-common.js') }}"></script>--}}

@yield('after-js')
@yield('script')
</body>
</html>