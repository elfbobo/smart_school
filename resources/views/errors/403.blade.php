<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>403 - 对不起！您无权限访问此页面</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

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

<div class="wrapper-page">

    <div class="row">
        <div class="col-sm-12">
            <div class="text-center m-t-50">
                <h1 class="text-error">403</h1>
                <h4 class="text-uppercase text-danger mt-3">Forbidden</h4>
                <p class="text-muted mt-3">对不起，您无权限，请联系管理员</p>

                <a class="btn btn-custom waves-effect waves-light mt-3" href="/"> 返回首页</a>
            </div>
        </div>
    </div>

    @include('admin.common.footer')

</div>


</body>
</html>