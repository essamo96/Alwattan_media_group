@extends('frontend.layouts.master')
@section('title', $category_info->name)
@section('content')
<!-- parallax section -->
<section id="section-hero" class=" jarallax text-light">
    <img src="{{ asset('assets/front/images/bg/3.jpg') }}" class="jarallax-img">
    <div class="text-center">
        <div class="container">
            <div class="row">
                <!-- Waves-->
                <div class="col-md-12">
                    <h1 class="title-3">
                        {!! __('site.alhadath-news') !!}
                    </h1>
                    <p class="fs-5 text-white">{{ $category_info->name }}</p>
                </div>

            </div>
        </div>
    </div>
</section>
<!-- section begin -->
<section id="section-schedule" aria-label="section-services-tab" data-bgimage="url({{ asset('assets/front/images/bg/3.png') }}) top right no-repeat">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="de_tab tab_style_4">
                    <div class="de_tab_content">
                        <div id="tab1" class="tab_single_content">
                            <div class="row">
                                <section>
                                    <div class="container">
                                        <div class="row">
                                            @foreach($category_news as $item)
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
                                    <div class="row">
                                        <div class="col-sm-12">
                                            {!! $category_news->render() !!}
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop