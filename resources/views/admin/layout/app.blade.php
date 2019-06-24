<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>@yield('title') - {{ $web_config['title'] or '管理系统' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta content="{{ csrf_token() }}" name="_token">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/admin/images/favicon.ico') }}">
    <link href="{{ asset('plugins/bootstrap-select/css/bootstrap-select.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Toastr css -->
    <link href="{{ asset('plugins/jquery-toastr/jquery.toast.min.css') }}" rel="stylesheet" />
    @yield('css')
    <!-- App css -->
    <link href="{{ asset('assets/admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin/css/icons.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/admin/css/style.css') }}" rel="stylesheet" type="text/css" />

    <script src="{{ asset('assets/admin/js/modernizr.min.js') }}"></script>
    @yield('style')
    <style>
        .select2-container .select2-selection--single .select2-selection__rendered {
            padding-right: 32px;
        }
    </style>
</head>

<body>

<!-- Navigation Bar-->
<header id="topnav">
    <div class="topbar-main">
        <div class="container-fluid">

            <!-- Logo container-->
            <div class="logo">
                <!-- Text Logo -->
                <!-- <a href="index.html" class="logo">
                    <span class="logo-small"><i class="mdi mdi-radar"></i></span>
                    <span class="logo-large"><i class="mdi mdi-radar"></i> Highdmin</span>
                </a> -->
                <!-- Image Logo -->
                <a href="index.html" class="logo">
                    <img src="{{ $web_config['logo'] ?? asset('assets/admin/images/logo.png') }}" alt="" class="logo-large" style="width: 162px;height: 34px;">
                </a>

            </div>
            <!-- End Logo container-->


            <div class="menu-extras topbar-custom">

                <ul class="list-unstyled topbar-right-menu float-right mb-0">

                    <li class="menu-item">
                        <!-- Mobile menu toggle-->
                        <a class="navbar-toggle nav-link">
                            <div class="lines">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </a>
                        <!-- End mobile menu toggle-->
                    </li>
                    {{--<li class="dropdown notification-list hide-phone">
                        <a class="nav-link dropdown-toggle waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
                           aria-haspopup="false" aria-expanded="false">
                            <i class="mdi mdi-earth"></i> English  <i class="mdi mdi-chevron-down"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">
                                Spanish
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">
                                Italian
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">
                                French
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item">
                                Russian
                            </a>

                        </div>
                    </li>--}}
                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect" href="javascript:;"
                           onclick="cacheClear()"
                            data-toggle="tooltip" data-placement="bottom" data-original-title="清空缓存"
                        >
                            <i class="fi-trash noti-icon"></i>
                        </a>
                    </li>
                    {{--<li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
                           aria-haspopup="false" aria-expanded="false">
                            <i class="fi-bell noti-icon"></i>
                            <span class="badge badge-danger badge-pill noti-icon-badge">4</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-lg">

                            <!-- item-->
                            <div class="dropdown-item noti-title">
                                <h6 class="m-0"><span class="float-right"><a href="" class="text-dark"><small>Clear All</small></a> </span>Notification</h6>
                            </div>

                            <div class="slimscroll" style="max-height: 230px;">
                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-success"><i class="mdi mdi-comment-account-outline"></i></div>
                                    <p class="notify-details">Caleb Flakelar commented on Admin<small class="text-muted">1 min ago</small></p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-info"><i class="mdi mdi-account-plus"></i></div>
                                    <p class="notify-details">New user registered.<small class="text-muted">5 hours ago</small></p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-danger"><i class="mdi mdi-heart"></i></div>
                                    <p class="notify-details">Carlos Crouch liked <b>Admin</b><small class="text-muted">3 days ago</small></p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-warning"><i class="mdi mdi-comment-account-outline"></i></div>
                                    <p class="notify-details">Caleb Flakelar commented on Admin<small class="text-muted">4 days ago</small></p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-purple"><i class="mdi mdi-account-plus"></i></div>
                                    <p class="notify-details">New user registered.<small class="text-muted">7 days ago</small></p>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <div class="notify-icon bg-custom"><i class="mdi mdi-heart"></i></div>
                                    <p class="notify-details">Carlos Crouch liked <b>Admin</b><small class="text-muted">13 days ago</small></p>
                                </a>
                            </div>

                            <!-- All-->
                            <a href="javascript:void(0);" class="dropdown-item text-center text-primary notify-item notify-all">
                                View all <i class="fi-arrow-right"></i>
                            </a>

                        </div>
                    </li>--}}

                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle waves-effect nav-user" data-toggle="dropdown" href="#" role="button"
                           aria-haspopup="false" aria-expanded="false">
                            <img src="{{ asset('assets/admin/images/users/avatar-1.jpg') }}" alt="user" class="rounded-circle"> <span class="ml-1 pro-user-name">{{ session('user_auth.username') }} <i class="mdi mdi-chevron-down"></i> </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                            <!-- item-->
                            <div class="dropdown-item noti-title">
                                <h6 class="text-overflow m-0">欢迎您 !</h6>
                            </div>

                            <!-- item-->
                            <a href="{{ route('user.profile') }}" class="dropdown-item notify-item">
                                <i class="fi-cog"></i> <span>设置</span>
                            </a>

                            <!-- item-->
                            {{--<a href="javascript:void(0);" class="dropdown-item notify-item">
                                <i class="fi-lock"></i> <span>锁屏</span>
                            </a>--}}

                            <!-- item-->
                            <a href="{{ route('admin.logout') }}" class="dropdown-item notify-item">
                                <i class="fi-power"></i> <span>登出</span>
                            </a>

                        </div>
                    </li>
                </ul>
            </div>
            <!-- end menu-extras -->

            <div class="clearfix"></div>

        </div> <!-- end container -->
    </div>
    <!-- end topbar-main -->

    <div class="navbar-custom">
        <div class="container-fluid">
            <div id="navigation">
                <!-- Navigation Menu-->
                <ul class="navigation-menu">

                    {!! $menus !!}

                    {{--<li class="has-submenu">
                        <a href="#"><i class="icon-briefcase"></i>系统管理</a>
                        <ul class="submenu megamenu">
                            <li>
                                <ul>
                                    <li><a href="ui-typography.html">Typography</a></li>
                                    <li><a href="ui-cards.html">Cards</a></li>
                                    <li><a href="ui-buttons.html">Buttons</a></li>
                                    <li><a href="ui-modals.html">Modals</a></li>
                                    <li><a href="ui-spinners.html">Spinners</a></li>
                                </ul>
                            </li>
                            <li>
                                <ul>
                                    <li><a href="ui-ribbons.html">Ribbons</a></li>
                                    <li><a href="ui-tooltips-popovers.html">Tooltips & Popover</a></li>
                                    <li><a href="ui-checkbox-radio.html">Checkboxs-Radios</a></li>
                                    <li><a href="ui-tabs.html">Tabs</a></li>
                                    <li><a href="ui-progressbars.html">Progress Bars</a></li>
                                </ul>
                            </li>
                            <li>
                                <ul>
                                    <li><a href="ui-notifications.html">Notification</a></li>
                                    <li><a href="ui-grid.html">Grid</a></li>
                                    <li><a href="ui-sweet-alert.html">Sweet Alert</a></li>
                                    <li><a href="ui-bootstrap.html">Bootstrap UI</a></li>
                                    <li><a href="ui-range-slider.html">Range Slider</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>--}}
                </ul>
                <!-- End navigation menu -->
            </div> <!-- end #navigation -->
        </div> <!-- end container -->
    </div> <!-- end navbar-custom -->
</header>
<!-- End Navigation Bar-->


<div class="wrapper">
    <div class="container-fluid">
        @yield('content')
    </div> <!-- end container -->
</div>
<!-- end wrapper -->


<!-- Footer -->
@include('admin.common.footer')
<!-- End Footer -->


<!-- jQuery  -->
<script src="{{ asset('assets/admin/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/waves.js') }}"></script>
<script src="{{ asset('assets/admin/js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('plugins/layer/dist/layer.js') }}"></script>

<script src="{{ asset('plugins/select2/js/select2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('plugins/select2/js/i18n/zh-CN.js') }}" type="text/javascript"></script>

<!-- Toastr js -->
<script src="{{ asset('plugins/jquery-toastr/jquery.toast.min.js') }}" type="text/javascript"></script>
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

@yield('after-js')
@yield('script')
<script>
    //清空缓存
    function cacheClear() {
        $.ajax({
            type: 'get',
            url: '{{ route('cache-clear') }}',
            dataType: 'json',
            success: function (res) {
                $.toast({
                    heading: '操作成功',
                    text: '清空缓存成功',
                    position: 'top-right',
                    allowToastClose: false,
                    loaderBg: '#5ba035',
                    icon: 'success',
                    hideAfter: 1500,
                    stack: 1,
                    afterShown: function () {
                        window.location.reload();
                    }
                });
            },
            error: function (xhr, state) {
                layer.msg('服务器错误');
            }
        });
    }
</script>
</body>
</html>