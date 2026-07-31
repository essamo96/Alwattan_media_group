@extends('frontend.layouts.master')
@section('title', $mysettings->{'title_'. trans('site.lang')})
@section('content')
<!-- parallax section -->
<section id="section-hero" class="full-height v-center jarallax text-light has-hero-slideshow">
    @include('frontend.general.hero-slider')
    @foreach($slides as $item)
    <img src="{{ url('uploads/sliders/'.$item->image) }}" class="jarallax-img">
    <div class="text-center">
        <div class="container">
            <div class="row">
                <!-- Waves-->
                <canvas class="waves" data-speed="5" data-wave-width="150%" data-animation="SineInOut"></canvas>
                <div class="col-md-12">
                    <h1 class="title-3">
                        {!! $item->name !!}
                        <span class="id-color">{{ $item->name1 }}</span>
                    </h1>
                    <p class="fs-5 text-white">{{ $item->name2 }}</p>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</section>
<!-- section close -->
<section id="section-about" data-bgimage="url({{ asset('assets/front/images/bg/1.png') }}) top left no-repeat">
    <div class="wm wm-border light wow fadeInDown">@lang('site.back_welcome')</div>
    <div class="container">
        <div class="row align-items-center">

            <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0s">
                {!!$about->details!!}
            </div>

            <div class="col-lg-6 mb-sm-30 text-center wow fadeInRight">
                <div class="de-images">
                    <img class="di-small wow fadeIn" src="{{ asset('assets/front/images/misc/2.jpg') }}" alt="" />
                    <img class="di-small-2" src="{{ asset('assets/front/images/misc/3.jpg') }}" alt="" />
                    <img class="img-fluid wow fadeInRight" data-wow-delay=".25s" src="{{ asset('assets/front/images/misc/1.jpg') }}" alt="" />
                </div>
            </div>

        </div>
    </div>
</section>

<!-- section begin -->
<section id="section-services">
    <div class="wm wm-border light wow fadeInDown ">@lang('site.services')</div>
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-md-12 text-center wow fadeInUp">
                <h2>@lang('site.creative_solutions')</h2>
                <div class="separator"><span><i class="fa fa-square"></i></span></div>
                <div class="spacer-single"></div>
            </div>

            @foreach($services as $service)
            <div class="col-lg-4 wow fadeIn mt-3" data-wow-delay="0s">
                <div class="box-number square">
                    @if($service->url!='')
                    <a href="{{ $service->url}}" target="_blank">
                        <i class="bg-color hover-color-2 fa {{ $service->image }} text-light"></i>
                    </a>
                    <div class="text">
                        <a href="{{ $service->url}}" target="_blank">
                            <h3><span>{{ $service->title }}</span></h3>
                        </a>
                        {!! $service->details !!}
                    </div>
                    @else
                    <i class="bg-color hover-color-2 fa {{ $service->image }} text-light"></i>
                    <div class="text">
                        <h3><span>{{ $service->title }}</span></h3>
                        {!! $service->details !!}
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- section close -->

@if(sizeof($news)>0)
<!-- section begin -->
<section id="section-schedule" aria-label="section-services-tab" data-bgimage="url({{ asset('assets/front/images/bg/6.png') }}) top right no-repeat">
    <div class="wm wm-border light wow fadeInDown ">@lang('site.news')</div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6  text-center wow fadeInUp">
                <h2>{{ $news[0]->category->name }}</h2>
                <div class="separator"><span><i class="fa fa-square"></i></span></div>
                <div class="spacer-single"></div>
            </div>
            <div class="col-md-12 wow fadeInUp">
                <div class="de_tab tab_style_4">
                    <div class="de_tab_content">
                        <div id="tab1" class="tab_single_content">
                            <div class="row">
                                @foreach($news as $item)
                                <div class="col-md-3">
                                    <div class="card">
                                        <img src="{{ url( Helper::get_image_thumb($item->image, 300, 200))}}" class="card-img-top" alt="...">
                                        <div class="card-body">
                                            <h5 class="card-title">{{$item->title}}</h5>
                                            <p class="card-text">{!! $item->sub !!}.</p>
                                            <a href="{{ url('post/'.$item->slug) }}" class="text-primary stretched-link">@lang('site.read_more')</a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-5"> 
                        <a href="{{ url('category/blog') }}" class="btn-custom text-white">@lang('site.more')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- section close -->
@endif
<!-- section begin -->
<section id="section-partners" data-bgimage="url({{ asset('assets/front/images/bg/5.png') }})) top right no-repeat">
    <div class="wm wm-border light wow fadeInDown ">@lang('site.partners')</div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center wow fadeInUp">
                <h2>@lang('site.success_partners')</h2>
                <div class="separator"><span><i class="fa fa-square"></i></span></div>
                <div class="spacer-single"></div>
            </div>
            <div class="col-md-12 text-center partners-logos">
                <div class="spacer-single"></div>
                @foreach($partners as $item)
                <img src="{{ url($item->image)}}" alt="" class="grey-hover">
                @if($loop->iteration%6==0)
                <div class="spacer-double" style="background-size: cover;"></div>
                @endif
                @endforeach
                <div class="spacer-double"></div>
            </div>
        </div>
    </div>
</section>
<!-- section close -->

@if(sizeof($testimonials)>0)
<!-- section begin -->
<section id="section-testimonials" aria-label="section">
    <div class="wm wm-border light wow fadeInDown">@lang('site.testimonials')</div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div id="testimonial-carousel-single" class="owl-carousel owl-theme wow fadeInUp">
                    @foreach($testimonials as $item)
                    <blockquote class="testimonial-big">
                        <span class="title"> {{ $item->title }}</span>
                        {{ $item->descs }}
                        <span class="name">{{ $item->name }}</span>
                    </blockquote>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!-- section close -->
@endif
@stop