<meta charset="utf-8">
<link rel="icon" href="{{ asset('assets/front/images/mediagrope.png') }}" type="image/png">
<link rel="apple-touch-icon" href="{{ asset('assets/front/images/mediagrope.png') }}">
<title>@yield('title')</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="{{$mysettings->{'description_'. trans('site.lang')} }}">
<meta name="keywords" content="{{$mysettings->{'tags_' . trans('site.lang')} }}">

<!-- CSS Files
================================================== -->
<link href="{{ asset('assets/front/css/bootstrap.min.css?v=3') }}" rel="stylesheet" type="text/css" id="bootstrap" />
<link href="{{ asset('assets/front/css/bootstrap-grid.min.css?v=3') }}" rel="stylesheet" type="text/css" id="bootstrap-grid" />
<link href="{{ asset('assets/front/css/bootstrap-reboot.min.css?v=3') }}" rel="stylesheet" type="text/css" id="bootstrap-reboot" />
<link href="{{ asset('assets/front/css/plugins.css?v=3') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/front/css/style.css?v=7') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/front/css/rev-settings.css?v=3') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('assets/front/css/color.css?v=3') }}" rel="stylesheet" type="text/css">

<!-- color scheme -->
<link rel="stylesheet" href="{{ asset('assets/front/css/colors/magenta.css?v=3') }}" type="text/css" id="colors">

<!-- سلايدر القسم الرئيسي -->
<link href="{{ asset('assets/front/css/hero-slider.css?v=1') }}" rel="stylesheet" type="text/css">

<!-- custom font -->
<link rel="stylesheet" href="{{ asset('assets/front/css/font-style.css?v=4') }}" type="text/css">
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@if(trans('site.lang')=='ar')
<style>
    .waves {
        z-index: -1;
    }
    #mainmenu a {
        font-size: 16px;
    }
    @media only screen and (max-width: 992px){
        #menu-btn {
            float: left;
        }
        .copyright{
            font-size: 13px;
        }
    }
</style>
@endif
<style>
    .waves {
        z-index: -1;
    }
    #mainmenu a {
        font-size: 16px;
    }
</style>
@yield('og')
@yield('css')