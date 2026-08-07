{{-- Metronic v8.1.8 Demo1 (light-sidebar) sidebar partial --}}
{{-- KTDrawer's "start"/"end" map to hardcoded left/right CSS classes (not RTL-aware),
     so the physical side must be picked explicitly per locale to slide in from the
     side the sidebar actually occupies on desktop. --}}
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="{{ in_array(app()->getLocale(), ['ar','he','fa','ur']) ? 'end' : 'start' }}" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    {{-- Logo --}}
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <a href="{{ url('/admin') }}">
            <img alt="Logo" src="{{ asset('assets/metronic/media/logos/default.svg') }}" class="h-25px app-sidebar-logo-default theme-light-show" />
            <img alt="Logo" src="{{ asset('assets/metronic/media/logos/default-dark.svg') }}" class="h-25px app-sidebar-logo-default theme-dark-show" />
            <img alt="Logo" src="{{ asset('assets/metronic/media/logos/default-small.svg') }}" class="h-20px app-sidebar-logo-minimize" />
        </a>
        <div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-duotone ki-double-left fs-2 rotate-180">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
    </div>

    {{-- Sidebar menu --}}
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
            <div class="menu menu-column menu-rounded menu-sub-indention px-3" id="kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">

                {{--
                    Reference-only example of Metronic's stock menu markup, kept commented out.
                    Do NOT uncomment/use as-is — it is unrelated demo content (Dashboards accordion, etc).

                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                        <span class="menu-link">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-element-11 fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </span>
                            <span class="menu-title">Dashboards</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a class="menu-link" href="#">
                                    <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                    <span class="menu-title">Example</span>
                                </a>
                            </div>
                        </div>
                    </div>
                --}}

                @php($active_menu = $active_menu ?? '')

                <div class="menu-item">
                    <a class="menu-link {{ $active_menu == 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard.view') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-element-11 fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></span>
                        <span class="menu-title">{{ __('الرئيسية') }}</span>
                    </a>
                </div>

                @if(auth()->user()->can('admin.contact.dalay'))
                <div class="menu-item">
                    <a class="menu-link {{ $active_menu == 'notification' ? 'active' : '' }}" href="{{ route('Today.notifcation') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-notification-bing fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></span>
                        <span class="menu-title">{{ __('ادارة الاشعارات') }}</span>
                        @if(($contact_count ?? 0) > 0)
                            <span class="menu-badge"><span class="badge badge-circle badge-danger">{{ $contact_count }}</span></span>
                        @endif
                    </a>
                </div>
                @endif

                @if(auth()->user()->can('admin.news.view') || auth()->user()->can('admin.news.add') || auth()->user()->can('admin.news.edit') || auth()->user()->can('admin.news.delete') || auth()->user()->can('admin.news.status'))
                <div class="menu-item">
                    <a class="menu-link {{ $active_menu == 'news' ? 'active' : '' }}" href="{{ route('news.view') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-book-open fs-2"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="menu-title">{{ __('الأخبار') }}</span>
                    </a>
                </div>
                @endif

                @if(auth()->user()->can('admin.contact.view') || auth()->user()->can('admin.contact.add') || auth()->user()->can('admin.contact.edit') || auth()->user()->can('admin.contact.delete') || auth()->user()->can('admin.contact.status') || auth()->user()->can('admin.contact.viewAll') || auth()->user()->can('admin.contact.remember'))
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ in_array($active_menu, ['contact', 'contact_me', 'remember_date']) ? 'here show' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon"><i class="ki-duotone ki-book-open fs-2"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="menu-title">{{ __('ادارة جهات الاتصال') }}</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        @if(auth()->user()->can('admin.contacts.view'))
                        <div class="menu-item">
                            <a class="menu-link {{ $active_menu == 'contact' ? 'active' : '' }}" href="{{ route('contact.view') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">{{ __('جهات الاتصال') }}</span>
                            </a>
                        </div>
                        @endif
                        @if(auth()->user()->can('admin.contact.remember'))
                        <div class="menu-item">
                            <a class="menu-link {{ $active_menu == 'teachers' ? 'active' : '' }}" href="{{ route('contact.remember') }}">
                                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                                <span class="menu-title">{{ __('قائمة التنبيهات') }}</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                @if(auth()->user()->can('admin.categories.view') || auth()->user()->can('admin.categories.add') || auth()->user()->can('admin.categories.edit') || auth()->user()->can('admin.categories.delete') || auth()->user()->can('admin.categories.status'))
                <div class="menu-item">
                    <a class="menu-link {{ $active_menu == 'categories' ? 'active' : '' }}" href="{{ route('categories.view') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-category fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></span>
                        <span class="menu-title">{{ __('الأقسام') }}</span>
                    </a>
                </div>
                @endif

                @if(auth()->user()->can('admin.sliders.view') || auth()->user()->can('admin.sliders.edit'))
                <div class="menu-item">
                    <a class="menu-link {{ $active_menu == 'sliders' ? 'active' : '' }}" href="{{ route('sliders.view') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-picture fs-2"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="menu-title">{{ __('السلايدات') }}</span>
                    </a>
                </div>
                @endif

                @if(auth()->user()->can('admin.menus.view') || auth()->user()->can('admin.menus.add') || auth()->user()->can('admin.menus.edit') || auth()->user()->can('admin.menus.delete') || auth()->user()->can('admin.menus.status') || auth()->user()->can('admin.menus.sort'))
                <div class="menu-item">
                    <a class="menu-link {{ $active_menu == 'menus' ? 'active' : '' }}" href="{{ route('menus.view') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-row-horizontal fs-2"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="menu-title">{{ __('قوائم الموقع') }}</span>
                    </a>
                </div>
                @endif

                @if(auth()->user()->can('admin.pages.view') || auth()->user()->can('admin.pages.edit'))
                <div class="menu-item">
                    <a class="menu-link {{ $active_menu == 'pages' ? 'active' : '' }}" href="{{ route('pages.view') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-document fs-2"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="menu-title">{{ __('الصفحات الثابته') }}</span>
                    </a>
                </div>
                @endif

                @if(auth()->user()->can('admin.services.view') || auth()->user()->can('admin.services.edit'))
                <div class="menu-item">
                    <a class="menu-link {{ $active_menu == 'services' ? 'active' : '' }}" href="{{ route('services.view') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-briefcase fs-2"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="menu-title">{{ __('الخدمات') }}</span>
                    </a>
                </div>
                @endif

                @if(auth()->user()->can('admin.testimonials.view'))
                <div class="menu-item">
                    <a class="menu-link {{ $active_menu == 'testimonials' ? 'active' : '' }}" href="{{ route('testimonials.view') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-message-text-2 fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i></span>
                        <span class="menu-title">{{ __('قالوا عنا') }}</span>
                    </a>
                </div>
                @endif

                @if(auth()->user()->can('admin.registrations.view'))
                <div class="menu-item">
                    <a class="menu-link {{ $active_menu == 'course_registrations' ? 'active' : '' }}" href="{{ route('course_registrations.view') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-profile-user fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i></span>
                        <span class="menu-title">{{ __('تسجيلات الدورة') }}</span>
                        @if(($registrations_count ?? 0) > 0)
                        <span class="badge badge-circle badge-danger ms-2" id="registrations_badge" style="font-size:0.65rem;">{{ $registrations_count }}</span>
                        @endif
                    </a>
                </div>
                @endif

                @if(auth()->user()->can('admin.partners.view'))
                <div class="menu-item">
                    <a class="menu-link {{ $active_menu == 'partners' ? 'active' : '' }}" href="{{ route('partners.view') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-people fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></span>
                        <span class="menu-title">{{ __('الشركاء') }}</span>
                    </a>
                </div>
                @endif

                @can('admin.faq.view')
                <div class="menu-item">
                    <a class="menu-link {{ $active_menu == 'faq' ? 'active' : '' }}" href="{{ route('faq.view') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-question-2 fs-2"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="menu-title">{{ __('الاسئلة الشائعة') }}</span>
                    </a>
                </div>
                @endcan

                @can('admin.settings.view')
                <div class="menu-item">
                    <a class="menu-link {{ $active_menu == 'settings' ? 'active' : '' }}" href="{{ route('settings.view') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-setting-2 fs-2"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="menu-title">{{ __('الإعدادات') }}</span>
                    </a>
                </div>
                @endcan

                @can('admin.social.view')
                <div class="menu-item">
                    <a class="menu-link {{ $active_menu == 'socials' ? 'active' : '' }}" href="{{ route('socials.view') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-share fs-2"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="menu-title">{{ __('الشبكات الإجتماعية') }}</span>
                    </a>
                </div>
                @endcan

                @if(auth()->user()->can('admin.users.view') || auth()->user()->can('admin.users.add') || auth()->user()->can('admin.users.edit') || auth()->user()->can('admin.users.delete') || auth()->user()->can('admin.users.status') || auth()->user()->can('admin.users.password'))
                <div class="menu-item">
                    <a class="menu-link {{ $active_menu == 'users' ? 'active' : '' }}" href="{{ route('users.view') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-people fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i></span>
                        <span class="menu-title">{{ __('المستخدمين') }}</span>
                    </a>
                </div>
                @endif

                @if(auth()->user()->can('admin.roles.view') || auth()->user()->can('admin.roles.add') || auth()->user()->can('admin.roles.edit') || auth()->user()->can('admin.roles.delete') || auth()->user()->can('admin.roles.status') || auth()->user()->can('admin.roles.permissions'))
                <div class="menu-item">
                    <a class="menu-link {{ $active_menu == 'roles' ? 'active' : '' }}" href="{{ route('roles.view') }}">
                        <span class="menu-icon"><i class="ki-duotone ki-shield-tick fs-2"><span class="path1"></span><span class="path2"></span></i></span>
                        <span class="menu-title">{{ __('الصلاحيات') }}</span>
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Sidebar footer --}}
    <div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
        <div class="text-center text-muted fs-8">
            {{ config('app.name', 'Admin') }}
        </div>
    </div>
</div>
