<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" dir="rtl">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title>@yield('title')</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin RTL Theme #2 for blank page layout" name="description" />
        <meta content="" name="author" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <base href="{{ asset('/') }}" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
              type="text/css" />
        <link href="assets/admin/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/admin/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet"
              type="text/css" />
        <link href="assets/admin/global/plugins/bootstrap/css/bootstrap-rtl.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/admin/global/plugins/bootstrap-switch/css/bootstrap-switch-rtl.min.css" rel="stylesheet"
              type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="assets/admin/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/admin/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap-rtl.css" rel="stylesheet"
              type="text/css" />
        <link href="assets/admin/global/plugins/bootstrap-toastr/toastr-rtl.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/admin/global/plugins/icheck/skins/all.css" rel="stylesheet" type="text/css" />
        <link href="assets/admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet"
              type="text/css" />
        <link href="assets/admin/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" rel="stylesheet"
              type="text/css" />
        <link href="assets/admin/global/plugins/bootstrap-select/css/bootstrap-select-rtl.css" rel="stylesheet"
              type="text/css" />
        <link href="assets/admin/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet"
              type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="assets/admin/global/css/components-rounded-rtl.min.css" rel="stylesheet" id="style_components"
              type="text/css" />
        <!-- Datetime Picker Style CSS -->
        <link rel="stylesheet" href="{{ asset('assets/admin/date/jquery.datetimepicker.css') }}">
        <link href="assets/admin/global/css/plugins-rtl.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <link href="assets/admin/layouts/layout2/css/layout-rtl.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/admin/layouts/layout2/css/themes/dark-rtl.min.css" rel="stylesheet" type="text/css"
              id="style_color" />
        <link href="assets/admin/layouts/layout2/css/custom-rtl.min.css?1" rel="stylesheet" type="text/css" />
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2/dist/sweetalert2.all.min.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2/dist/sweetalert2.min.css" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- END THEME LAYOUT STYLES -->


        @yield('css')
        <style>
            * {

                font-family: 'Cairo', sans-serif;
            }

            .portlet.box.dark>.portlet-title>.caption>i {

                color: #9e3df8;
            }

            .page-header.navbar .page-logo {
                background: #760bd8;
            }

            .page-sidebar .page-sidebar-menu>li.active>a>i {
                color: #8344ff;
            }

            .page-sidebar .page-sidebar-menu>li>a {
                background-color: #343a40;
            }

            .page-sidebar .page-sidebar-menu>li.active>a:hover {
                background: #060606e0;
            }

            .page-footer {
                background-color: #343a40;

            }

            .page-content-wrapper {

                background: #343a40;
            }

            .page-sidebar.navbar-collapse {
                background: #343a40;
            }

            .portlet.box.dark>.portlet-title>.actions .btn-default {
                background: 0 0 !important;
                border: 1px solid #9e3df8;
                color: #9e3df8;
            }

            .portlet.box.dark>.portlet-title>.actions .btn-default>i {
                color: #760bd8;
            }
        </style>
        <link rel="shortcut icon" href="{{ url('assets/front/images/favicon.ico')}}" /> 
    </head>
    <!-- END HEAD -->

    <body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid">
        <!-- BEGIN HEADER -->
        <div class="page-header navbar navbar-fixed-top">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner ">
                <!-- BEGIN LOGO -->
                @include('admin.layout.page-logo')
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
                   data-target=".navbar-collapse"> </a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                <!-- BEGIN PAGE ACTIONS -->
                <!-- DOC: Remove "hide" class to enable the page header actions -->
                @include('admin.layout.page-actions')
                <!-- END PAGE ACTIONS -->
                <div class="page-top">

                    <!-- BEGIN TOP NAVIGATION MENU -->
                    @include('admin.layout.top-menu')
                    <!-- END TOP NAVIGATION MENU -->
                </div>
            </div>
            <!-- END HEADER INNER -->
        </div>
        <!-- END HEADER -->
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
        <!-- BEGIN CONTAINER -->
        <div class="page-container">
            <!-- BEGIN SIDEBAR -->
            @include('admin.layout.page-sidebar')
            <!-- END SIDEBAR -->
            <!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
                <!-- BEGIN CONTENT BODY -->
                <div class="page-content">
                    <!-- BEGIN PAGE HEADER-->
                    @yield('modal')
                    @yield('page-title')
                    <div class="page-bar">
                        @yield('page-breadcrumb')
                    </div>
                    <!-- END PAGE HEADER-->
                    @yield('page-content')
                </div>
                <!-- END CONTENT BODY -->
            </div>
            <!-- END CONTENT -->
        </div>
        <!-- END CONTAINER -->
        <!-- BEGIN FOOTER -->
        <div class="page-footer">
            <div class="page-footer-inner"> {{ date('Y') }} &copy; Website By
                <a href="#">MDIT</a>
                <div class="scroll-to-top">
                    <i class="icon-arrow-up"></i>
                </div>
            </div>
        </div>
        <!-- END FOOTER -->
        <!--[if lt IE 9]>
            <script src="assets/admin/global/plugins/respond.min.js"></script>
            <script src="assets/admin/global/plugins/excanvas.min.js"></script>
            <script src="assets/admin/global/plugins/ie8.fix.min.js"></script>
            <![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="assets/admin/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="assets/admin/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="assets/admin/global/plugins/js.cookie.min.js" type="text/javascript"></script>
        <script src="assets/admin/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="assets/admin/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="assets/admin/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="assets/admin/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="assets/admin/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript">
        </script>
        <script src="assets/admin/global/plugins/icheck/icheck.min.js" type="text/javascript"></script>
        <script src="assets/admin/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
        <script src="assets/admin/global/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js" type="text/javascript">
        </script>
        <script src="assets/admin/global/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>
        <script src="assets/admin/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js" type="text/javascript">
        </script>
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="assets/admin/global/scripts/app.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <script src="assets/admin/global/scripts/datatable.js" type="text/javascript"></script>
        <!-- END PAGE LEVEL SCRIPTS -->

        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="assets/admin/layouts/layout2/scripts/layout.min.js" type="text/javascript"></script>
        <script src="assets/admin/global/plugins/bootstrap-toastr/toastr.min.js" type="text/javascript"></script>
        <script src="assets/admin/layouts/layout2/scripts/demo.min.js" type="text/javascript"></script>
        <script src="assets/admin/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <script src="assets/admin/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->

        @yield('js')
        <script>
$(document).ready(function (e) {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "positionClass": "toast-top-right",
        "onclick": null,
        "showDuration": "1000",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
    ///////////////////////////////
    $('.bs-select').selectpicker({
        iconBase: 'fa',
        tickIcon: 'fa-check'
    });
    ///////////////////////////
    $('.datepicker').datepicker();
});
        </script>
        <script>
            $(document).ready(function () {
                $('#clickmewow').click(function () {
                    $('#radio1003').attr('checked', 'checked');
                });
            })
        </script>
    </body>

</html>
