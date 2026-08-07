<!DOCTYPE html>
{{-- Master admin layout based on Metronic v8.1.8 Demo1 "light-sidebar" layout. --}}
{{-- New/parallel layout — resources/views/admin/layout/* is left untouched. --}}
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['ar','he','fa','ur']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8" />
    <title>@yield('title', config('app.name', 'Admin'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    {{-- Tells the browser this page manages its own light/dark styling, so Chrome/Edge's
         automatic content-darkening (triggered by OS/browser dark mode) doesn't try to
         auto-invert colors on its own — that heuristic mishandles fixed-position bars
         (header/footer). Kept in sync with the real theme toggle via the inline script
         below (which sets documentElement.style.colorScheme to match data-bs-theme). --}}
    <meta name="color-scheme" content="light dark" />
    <link rel="shortcut icon" href="{{ asset('assets/metronic/media/logos/favicon.ico') }}" />

    {{-- Fonts (mandatory for all pages) --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    @if(in_array(app()->getLocale(), ['ar','he','fa','ur']))
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" />
    @endif

    {{-- Global stylesheets bundle (mandatory for all pages) --}}
    {{-- Cache-busted via filemtime (same pattern used for CKEditor's config.js) so browsers
         always pick up edits to these files instead of serving a stale disk-cached copy. --}}
    <link href="{{ asset('assets/metronic/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/metronic/css/style.bundle.css') }}?v={{ filemtime(public_path('assets/metronic/css/style.bundle.css')) }}" rel="stylesheet" type="text/css" />
    @if(in_array(app()->getLocale(), ['ar','he','fa','ur']))
    <link href="{{ asset('assets/metronic/css/rtl-overrides.css') }}?v={{ filemtime(public_path('assets/metronic/css/rtl-overrides.css')) }}" rel="stylesheet" type="text/css" />
    @endif
    <link href="{{ asset('assets/metronic/css/admin-custom.css') }}?v={{ filemtime(public_path('assets/metronic/css/admin-custom.css')) }}" rel="stylesheet" type="text/css" />

    @stack('styles')
</head>
<body id="kt_app_body" data-kt-app-layout="light-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
    {{-- Theme mode setup on page load --}}
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
            document.documentElement.style.colorScheme = themeMode;
        }
    </script>

    {{-- App --}}
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid" id="kt_app_page">

            @include('layouts.partials.header')

            <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">

                @include('layouts.partials.sidebar')

                <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                    <div class="d-flex flex-column flex-column-fluid">

                        @include('layouts.partials.toolbar')

                        <div id="kt_app_content" class="app-content flex-column-fluid">
                            <div id="kt_app_content_container" class="app-container container-fluid">
                                @yield('content')
                            </div>
                        </div>

                        @yield('modals')
                    </div>

                    @include('layouts.partials.footer')
                </div>
            </div>
        </div>
    </div>

    @include('layouts.partials.scrolltop')

    {{-- Global javascript bundles (mandatory for all pages) --}}
    <script src="{{ asset_v('assets/metronic/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset_v('assets/metronic/js/scripts.bundle.js') }}"></script>

    @stack('scripts')
</body>
</html>
