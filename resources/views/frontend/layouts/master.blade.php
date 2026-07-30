<html dir="{{ trans('site.dir') }}" lang="{{ trans('site.lang') }}">
    <head>
        @include('frontend.general.head')
    </head>
    <body id="homepage" class="de_light">
        <div id="wrapper">
            @include('frontend.general.menu')
            <div id="content" class="no-bottom no-top">
                @yield('content')
                @include('frontend.general.footer')
            </div>
        </div>
        @include('frontend.general.subfooter')
        @yield('js')
        @include('cookieConsent::index')
    </body>
</html>