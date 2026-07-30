<!-- BEGIN TOP NAVIGATION MENU -->
<div class="top-menu">
    <ul class="nav navbar-nav pull-right">
        <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
        <li class="dropdown dropdown-user">
            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                <img alt="" class="img-circle" src="assets/admin/layouts/layout2/img/avatar3_small.jpg" />
                <span class="username username-hide-on-mobile"> {{ Auth::user()->name}} </span>
                <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-default">
                <li>
                    <a href="{{ route('dashboard.profile') }}">
                        <i class="icon-user"></i> الملف الشخصي </a>
                </li>
                <li>
                    <a href="{{ route('dashboard.password') }}">
                        <i class="icon-lock"></i> تغيير كلمة المرور </a>
                </li>
                <li class="divider"> </li>
                <li>
                    <a href="{{ route('app.logout') }}">
                        <i class="icon-key"></i> تسجيل الخروج </a>
                </li>
            </ul>
        </li>
        <!-- END USER LOGIN DROPDOWN -->
    </ul>
    
</div>
<!-- END TOP NAVIGATION MENU -->
