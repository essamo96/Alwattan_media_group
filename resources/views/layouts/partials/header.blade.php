{{-- Metronic v8.1.8 Demo1 (light-sidebar) header partial --}}
<div id="kt_app_header" class="app-header">
    <div class="app-container container-fluid d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
        {{-- Sidebar mobile toggle --}}
        <div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
                <i class="ki-duotone ki-abstract-14 fs-2 fs-md-1">
                    <span class="path1"></span>
                    <span class="path2"></span>
                </i>
            </div>
        </div>

        {{-- Mobile logo --}}
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
            <a href="{{ url('/admin') }}" class="d-lg-none">
                <img alt="Logo" src="{{ asset('assets/metronic/media/logos/default-small.svg') }}" class="h-30px" />
            </a>
        </div>

        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
            {{-- Header menu (kept minimal - real navigation lives in the sidebar) --}}
            <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true" data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="{{ in_array(app()->getLocale(), ['ar','he','fa','ur']) ? 'start' : 'end' }}" data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="{default: 'append', lg: 'prepend'}" data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
                <div class="menu menu-rounded menu-column menu-lg-row my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0" id="kt_app_header_menu" data-kt-menu="true">
                    {{-- Intentionally left blank: top header menu is not used in this admin layout --}}
                </div>
            </div>

            {{-- Navbar (right-hand actions) --}}
            <div class="app-navbar flex-shrink-0">

                @if(auth()->check() && auth()->user()->can('admin.contact.dalay'))
                {{-- Notifications --}}
                <div class="app-navbar-item ms-1 ms-md-3">
                    <a href="{{ route('Today.notifcation') }}" class="btn btn-icon btn-custom btn-icon-muted btn-active-icon-primary w-35px h-35px w-md-40px h-md-40px position-relative">
                        <i class="ki-duotone ki-notification-bing fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                        @if(($contact_count ?? 0) > 0)
                        <span class="badge badge-circle badge-danger position-absolute top-0 start-0 translate-middle" style="font-size:0.65rem;">{{ $contact_count }}</span>
                        @endif
                    </a>
                </div>
                @endif

                @if(auth()->check() && auth()->user()->can('admin.registrations.view'))
                {{-- Course registrations live notifications --}}
                <div class="app-navbar-item ms-1 ms-md-3">
                    <a href="{{ route('course_registrations.view') }}" class="btn btn-icon btn-custom btn-icon-muted btn-active-icon-primary w-35px h-35px w-md-40px h-md-40px position-relative">
                        <i class="ki-duotone ki-profile-user fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                        <span id="header_registrations_badge" class="badge badge-circle badge-danger position-absolute top-0 start-0 translate-middle" style="font-size:0.65rem; {{ ($registrations_count ?? 0) > 0 ? '' : 'display:none;' }}">{{ $registrations_count ?? 0 }}</span>
                    </a>
                </div>
                @endif

                {{-- Theme mode toggle --}}
                <div class="app-navbar-item ms-1 ms-md-3">
                    <div class="btn btn-icon btn-custom btn-icon-muted btn-active-icon-primary w-35px h-35px w-md-40px h-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end" id="kt_theme_mode_toggle">
                        <i class="ki-duotone ki-night-day theme-light-show fs-1">
                            <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                            <span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span>
                            <span class="path9"></span><span class="path10"></span>
                        </i>
                        <i class="ki-duotone ki-moon theme-dark-show fs-1">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                    </div>
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px" data-kt-menu="true" data-kt-element="theme-mode-menu">
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="ki-duotone ki-night-day fs-2">
                                        <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                                        <span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span>
                                        <span class="path9"></span><span class="path10"></span>
                                    </i>
                                </span>
                                <span class="menu-title">{{ __('Light') }}</span>
                            </a>
                        </div>
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="ki-duotone ki-moon fs-2">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                </span>
                                <span class="menu-title">{{ __('Dark') }}</span>
                            </a>
                        </div>
                        <div class="menu-item px-3 my-0">
                            <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                                <span class="menu-icon" data-kt-element="icon">
                                    <i class="ki-duotone ki-screen fs-2">
                                        <span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span>
                                    </i>
                                </span>
                                <span class="menu-title">{{ __('System') }}</span>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- User menu --}}
                <div class="app-navbar-item ms-1 ms-md-3" id="kt_header_user_menu_toggle">
                    <div class="cursor-pointer symbol symbol-circle symbol-30px symbol-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                        @if(auth()->check() && auth()->user()->avatar ?? false)
                            <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" />
                        @else
                            <span class="symbol-label bg-light-primary text-primary fw-bold fs-6">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </span>
                        @endif
                    </div>

                    {{-- User account menu --}}
                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
                        <div class="menu-item px-3">
                            <div class="menu-content d-flex align-items-center px-3">
                                <div class="symbol symbol-circle symbol-50px me-5">
                                    <span class="symbol-label bg-light-primary text-primary fw-bold fs-3">
                                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                                    </span>
                                </div>
                                <div class="d-flex flex-column">
                                    <div class="fw-bold d-flex align-items-center fs-5">
                                        {{ auth()->user()->name ?? __('Guest') }}
                                    </div>
                                    <span class="fw-semibold text-muted text-hover-primary fs-7">{{ auth()->user()->email ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="separator my-2"></div>
                        <div class="menu-item px-5">
                            <a href="#" class="menu-link px-5">{{ __('Account Settings') }}</a>
                        </div>
                        <div class="menu-item px-5">
                            <a href="{{ route('app.logout') }}" class="menu-link px-5">
                                {{ __('Sign Out') }}
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Header menu toggle (mobile) --}}
                <div class="app-navbar-item d-lg-none ms-2 me-n2" title="Show header menu">
                    <div class="btn btn-flex btn-icon btn-active-color-primary w-30px h-30px" id="kt_app_header_menu_toggle">
                        <i class="ki-duotone ki-element-4 fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(auth()->check() && auth()->user()->can('admin.registrations.view'))
<script src="https://js.pusher.com/8.4/pusher.min.js"></script>
<script src="{{ asset('assets/js/admin-notifications.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.initAdminNotifications) {
            window.initAdminNotifications({
                key: '{{ config('broadcasting.connections.pusher.key') }}',
                cluster: '{{ config('broadcasting.connections.pusher.options.cluster', env('PUSHER_APP_CLUSTER')) }}',
                authEndpoint: '{{ url('/broadcasting/auth') }}',
                csrfToken: '{{ csrf_token() }}'
            });
        }
    });
</script>
@endif
