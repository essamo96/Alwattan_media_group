<!DOCTYPE html>
<html lang="{{ trans('site.dir') }}">
    <head>
        @include('frontend.general.head')
    </head>
    <body>
        <!-- page wrapper start -->
        <div class="page-wrapper">
            @include('frontend.general.menu')
            <div class="page-content ">
                <section class="error-page section-padding-100-0 mb-70">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-8 ml-auto mr-auto text-center text-black">
                                <img class="img-center mb-5" src="{{ url('assets/site/images/404.png') }}" alt="">
                                <h3 class="title text-uppercase">{{ __('site.error-title1') }} <span>{{ __('site.error-title2') }}</span></h3>
                                <h5 class="mb-4 text-capitalize mt-3"> {{ __('site.error-desc') }}</h5>
                                <a class="btn btn-theme btn-radius" href="{{ url('/') }}"><i class="fas fa-long-arrow-alt-left mr-2"></i> {{ __('site.back-home') }}</a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            @include('frontend.general.footer')
        </div>
    </body>
</html>