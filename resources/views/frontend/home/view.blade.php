@extends('frontend.layouts.master')
@section('title', $mysettings->{'title_'. trans('site.lang')})
@section('content')
<!-- parallax section -->
{{-- النصوص متموضعة مطلقاً فوق الصور/الموجات (hero-slider.css) حتى لا تدخل
     تدفق التخطيط ولا تتعارض مع الخطوط المتحركة. --}}
<section id="section-hero" class="full-height jarallax text-light">
    {{-- canvas واحد مشترك للموجات المتحركة على كامل السلايدر --}}
    <canvas class="waves" data-speed="5" data-wave-width="150%" data-animation="SineInOut"></canvas>
    @foreach($slides as $item)
    <img src="{{ url('uploads/sliders/'.$item->image) }}" class="jarallax-img{{ $loop->first ? ' is-active' : '' }}" alt="" data-index="{{ $loop->index }}">
    <div class="text-center hero-text-slide{{ $loop->first ? ' is-active' : '' }}" data-index="{{ $loop->index }}">
        <div class="container">
            <div class="row">
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
                    <img class="di-small wow fadeIn" src="{{ (!empty($about->image) && $about->image !== '-') ? asset('uploads/image/'.$about->image) : asset('assets/front/images/misc/2.jpg') }}" alt="" />
                    <img class="di-small-2" src="{{ !empty($about->image2) ? asset('uploads/image/'.$about->image2) : asset('assets/front/images/misc/3.jpg') }}" alt="" />
                    <img class="img-fluid wow fadeInRight" data-wow-delay=".25s" src="{{ !empty($about->image3) ? asset('uploads/image/'.$about->image3) : asset('assets/front/images/misc/1.jpg') }}" alt="" />
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
                <p class="services-intro">@lang('site.services_intro')</p>
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
            <div class="col-md-12 text-center wow fadeInUp mt-4">
                <p class="services-tagline"><strong>@lang('site.services_tagline')</strong></p>
            </div>
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

@if(sizeof($achievements) > 0)
<!-- section begin -->
<section id="section-achievements" aria-label="section-achievements" data-bgimage="url({{ asset('assets/front/images/bg/2.png') }}) top left no-repeat">
    <style>
        #section-achievements .achievement-card {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 14px rgba(20, 26, 62, .07);
            transition: transform .3s ease, box-shadow .3s ease;
            height: 100%;
        }
        #section-achievements .achievement-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 14px 30px rgba(20, 26, 62, .14);
        }
        #section-achievements .achievement-img-wrap {
            position: relative;
            width: 100%;
            padding-top: 62%;
            overflow: hidden;
        }
        #section-achievements .achievement-img-wrap img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s ease;
        }
        #section-achievements .achievement-card:hover .achievement-img-wrap img {
            transform: scale(1.06);
        }
        #section-achievements .achievement-body {
            padding: 22px 20px 24px;
        }
        #section-achievements .achievement-body h5 {
            font-weight: 700;
            margin-bottom: 10px;
        }
        #section-achievements .achievement-desc {
            color: #6b7182;
            font-size: 14.5px;
            line-height: 1.7;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin-bottom: 14px;
        }
        #section-achievements .achievement-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }
        #section-achievements .achievement-tag {
            display: inline-block;
            font-size: 11.5px;
            font-weight: 600;
            padding: 3px 11px;
            border-radius: 20px;
            background: rgba(33, 52, 120, .08);
            color: var(--bs-primary, #213478);
        }
    </style>
    <div class="wm wm-border light wow fadeInDown">@lang('site.achievements')</div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center wow fadeInUp">
                <h2>@lang('site.our_achievements')</h2>
                <div class="separator"><span><i class="fa fa-square"></i></span></div>
                <p class="services-intro">@lang('site.achievements_intro')</p>
                <div class="spacer-single"></div>
            </div>
            <div class="col-md-12">
                <div class="row">
                    @foreach($achievements as $item)
                    @php
                        $achImage = $item->{'image_' . $locale} ?: $item->image_ar;
                        $achTags = $item->tagsArray();
                    @endphp
                    <div class="col-md-4 col-sm-6 mb-4 wow fadeInUp" data-wow-delay="{{ ($loop->index % 3) * .1 }}s">
                        <div class="achievement-card">
                            <div class="achievement-img-wrap">
                                <img src="{{ asset('uploads/achievements/' . $achImage) }}" alt="{{ $item->title }}" loading="lazy">
                            </div>
                            <div class="achievement-body">
                                <h5>{{ $item->title }}</h5>
                                <p class="achievement-desc">{{ $item->short_description }}</p>
                                @if(!empty($achTags))
                                <div class="achievement-tags">
                                    @foreach($achTags as $tag)
                                    <span class="achievement-tag">{{ $tag }}</span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
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

@include('frontend.general.advertisement-banner')

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