<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-hover-submenu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <li class="nav-item {{ $active_menu == 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('dashboard.view') }}" class="nav-link nav-toggle">
                    <i class="icon-home"></i>
                    <span class="title">الرئيسية</span>
                    <span class="arrow"></span>
                </a>
            </li>
             @if(auth()->user()->can('admin.contact.dalay'))
              <li class="nav-item {{ $active_menu == 'notification' ? 'active' : '' }}">
                {{-- <a href="{{route('dashboard.view.membership')}}" class="nav-link nav-toggle"> --}}
                <a class="nav-link nav-toggle" href="{{route('Today.notifcation')}}">
                    <i class="bi bi-bell"></i>
                    <span class="title">ادارة الاشعارات</span>
                    <span class="icon-button__badge"
                        style="
                           top: -57px;
            position: relative;
            align-items: center;
            width: 26px;
            height: 26px;
            left: -107px;
            background: #fd6f69;
            border: none;
            outline: none;
            border-radius: 50%;
            justify-content: center;
            display: flex;
            color: white;">
                        {{$contact_count!=null ?$contact_count : 0}}
                    </span>
                    <span class="arrow"></span>
                </a>
            </li>
            @endif
            @if(auth()->user()->can('admin.news.view') || auth()->user()->can('admin.news.add') || auth()->user()->can('admin.news.edit') || auth()->user()->can('admin.news.delete') || auth()->user()->can('admin.news.status'))
            <li class="nav-item {{ $active_menu == 'news' ? 'active' : '' }}">
                <a href="{{ route('news.view') }}" class="nav-link nav-toggle">
                    <i class="icon-book-open"></i>
                    <span class="title">الأخبار</span>
                    <span class="arrow"></span>
                </a>
            </li>
            @endif
            @if(auth()->user()->can('admin.contact.view') || auth()->user()->can('admin.contact.add') || auth()->user()->can('admin.contact.edit') || auth()->user()->can('admin.contact.delete') || auth()->user()->can('admin.contact.status')
            || auth()->user()->can('admin.contact.status') || auth()->user()->can('admin.contact.viewAll') || auth()->user()->can('admin.contact.remember'))

            <li class="nav-item {{  in_array($active_menu,array('contact','contact_me','remember_date')) ? 'active' : '' }}">
                <a href="javascript:void(0)" class="nav-link nav-toggle">
                    <i class="icon-book-open"></i>
                    <span class="title">ادارة جهات الاتصال</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    @if(auth()->user()->can('admin.contacts.view'))
                    <li class="nav-item {{ $active_menu == 'contact' ? 'active' : '' }}">
                        <a href="{{ route('contact.view') }}" class="nav-link nav-toggle">
                            <i class="icon-book-open"></i>
                            <span class="title">جهات الاتصال</span>
                            <span class="arrow"></span>
                        </a>
                    </li>
                    @endif
                    @if(auth()->user()->can('admin.contact.remember'))
                    <li class="nav-item {{ $active_menu == 'teachers' ? 'active' : '' }}">
                        <a href="{{ route('contact.remember') }}" class="nav-link nav-toggle">
                            <i class="icon-book-open"></i>
                            <span class="title">قائمة التنبيهات</span>
                            <span class="arrow"></span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            @if(auth()->user()->can('admin.categories.view') || auth()->user()->can('admin.categories.add') || auth()->user()->can('admin.categories.edit') || auth()->user()->can('admin.categories.delete') || auth()->user()->can('admin.categories.status'))
            <li class="nav-item {{ $active_menu == 'categories' ? 'active' : '' }}">
                <a href="{{ route('categories.view') }}" class="nav-link nav-toggle">
                    <i class="icon-grid"></i>
                    <span class="title">الأقسام</span>
                    <span class="arrow"></span>
                </a>
            </li>
            @endif

            @if(auth()->user()->can('admin.sliders.view') ||  auth()->user()->can('admin.sliders.edit'))
            <li class="nav-item {{ $active_menu == 'sliders' ? 'active' : '' }}">
                <a href="{{ route('sliders.view') }}" class="nav-link nav-toggle">
                    <i class="icon-grid"></i>
                    <span class="title">السلايدات</span>
                    <span class="arrow"></span>
                </a>
            </li>
            @endif
            @if(auth()->user()->can('admin.pages.view') ||  auth()->user()->can('admin.pages.edit'))
            <li class="nav-item {{ $active_menu == 'pages' ? 'active' : '' }}">
                <a href="{{ route('pages.view') }}" class="nav-link nav-toggle">
                    <i class="icon-grid"></i>
                    <span class="title">الصفحات الثابته</span>
                    <span class="arrow"></span>
                </a>
            </li>
            @endif
            @if(auth()->user()->can('admin.services.view') ||  auth()->user()->can('admin.services.edit'))
            <li class="nav-item {{ $active_menu == 'services' ? 'active' : '' }}">
                <a href="{{ route('services.view') }}" class="nav-link nav-toggle">
                    <i class="icon-grid"></i>
                    <span class="title">الخدمات</span>
                    <span class="arrow"></span>
                </a>
            </li>
            @endif
            @if(auth()->user()->can('admin.testimonials.view'))
            <li class="nav-item {{ $active_menu == 'testimonials' ? 'active' : '' }}">
                <a href="{{ route('testimonials.view') }}" class="nav-link nav-toggle">
                    <i class="icon-film"></i>
                    <span class="title">قالوا عنا</span>
                    <span class="arrow"></span>
                </a>
            </li>
            @endif




            @if(auth()->user()->can('admin.partners.view'))
            <li class="nav-item {{ $active_menu == 'partners' ? 'active' : '' }}">
                <a href="{{ route('partners.view') }}" class="nav-link nav-toggle">
                    <i class="icon-film"></i>
                    <span class="title">الشركاء</span>
                    <span class="arrow"></span>
                </a>
            </li>
            @endif
            @can('admin.faq.view')
            <li class="nav-item {{ $active_menu == 'faq' ? 'active' : '' }}">
                <a href="{{ route('faq.view') }}" class="nav-link nav-toggle">
                    <i class="icon-social-twitter"></i>
                    <span class="title">الاسئلة الشائعة</span>
                    <span class="arrow"></span>
                </a>
            </li>
            @endcan
            @can('admin.settings.view')
            <li class="nav-item {{ $active_menu == 'settings' ? 'active' : '' }}">
                <a href="{{ route('settings.view') }}" class="nav-link nav-toggle">
                    <i class="icon-settings"></i>
                    <span class="title">الإعدادات</span>
                    <span class="arrow"></span>
                </a>
            </li>
            @endcan
            @can('admin.social.view')
            <li class="nav-item {{ $active_menu == 'socials' ? 'active' : '' }}">
                <a href="{{ route('socials.view') }}" class="nav-link nav-toggle">
                    <i class="icon-social-twitter"></i>
                    <span class="title">الشبكات الإجتماعية</span>
                    <span class="arrow"></span>
                </a>
            </li>
            @endcan

            @if(auth()->user()->can('admin.users.view') || auth()->user()->can('admin.users.add') || auth()->user()->can('admin.users.edit') || auth()->user()->can('admin.users.delete') || auth()->user()->can('admin.users.status') || auth()->user()->can('admin.users.password'))
            <li class="nav-item {{ $active_menu == 'users' ? 'active' : '' }}">
                <a href="{{ route('users.view') }}" class="nav-link nav-toggle">
                    <i class="icon-users"></i>
                    <span class="title">المستخدمين</span>
                    <span class="arrow"></span>
                </a>
            </li>
            @endif
            @if(auth()->user()->can('admin.roles.view') || auth()->user()->can('admin.roles.add') || auth()->user()->can('admin.roles.edit') || auth()->user()->can('admin.roles.delete') || auth()->user()->can('admin.roles.status') || auth()->user()->can('admin.roles.permissions'))
            <li class="nav-item {{ $active_menu == 'roles' ? 'active' : '' }}">
                <a href="{{ route('roles.view') }}" class="nav-link nav-toggle">
                    <i class="icon-directions"></i>
                    <span class="title">الصلاحيات</span>
                    <span class="arrow"></span>
                </a>
            </li>
            @endif
        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
</div>