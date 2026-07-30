@extends('frontend.layouts.master')
@section('title', $post_news->title)
@section('content')
    <!-- parallax section -->
    <section id="section-hero" class=" jarallax text-light">
        <img src="{{ asset('assets/front/images/bg/3.jpg') }}" class="jarallax-img">
    </section>
    <!-- section begin -->
    <section id="section-schedule" aria-label="section-services-tab"
        data-bgimage="url({{ asset('assets/front/images/bg/6.png') }}) top right no-repeat">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-lg-8 col-12" id="new_info" >
                    <article>
                        <div class="post-item-2">
                            <div class="post-inner">
                                <h1 class="mb-4">{{ $post_news->title }}</h1>

                                <div class="post-thumb rounded">
                                    <img src="{{ url($post_news->image) }}" class="rounded-lg img-fluid " alt="blog">
                                </div>
                                <div class="post-content">
                                    <ul class="lab-ul post-date my-3">
                                        <li><span><i class="fa fa-calendar me-2"></i> {{ $post_news->pub_date }}</span></li>
                                    </ul>
                                    {!! $post_news->descs !!}
                                    <div class="tags-area">
                                        @if ($post_news->tags != '')
                                            <?php
                                            $tags = explode(',', $post_news->tags);
                                            ?>
                                            <ul class="tags lab-ul justify-content-center">
                                                <?php
                                                $tags = explode(',', $post_news->tags);
                                                ?>
                                                @foreach ($tags as $tag)
                                                    <li>
                                                        <a href="#">{{ $tag }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        <ul class="share lab-ul justify-content-center">
                                            <li>
                                                <a href="#"
                                                    class="facebook d-flex align-items-center justify-content-center rounded"><i
                                                        class="fa fa-facebook-f"></i></a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    class="dribble d-flex align-items-center justify-content-center rounded"><i
                                                        class="fa fa-instagram"></i></a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    class="twitter d-flex align-items-center justify-content-center rounded"><i
                                                        class="fa fa-twitter"></i></a>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </article>
                </div>
                <div class="col-lg-4 col-md-7 col-12">
                    <aside>
                        <div class="widget widget-post">
                            <div class="widget-header">
                                <h5>{{ __('site.last-news') }}</h5>
                            </div>
                            <ul class="lab-ul widget-wrapper">
                                @foreach ($last_news as $item)
                                    <li class="d-flex flex-wrap justify-content-between">
                                        <div class="card mb-3">
                                            <div class="row g-0">
                                                <div class="col-md-4">
                                                    <img src="{{ url( Helper::get_image_thumb($item->image, 300, 200))}}" class="card-img-top"
                                                        alt="...">
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="card-body">
                                                        <a href="{{ url('post/'.$item->slug) }}" data-id="{{ $item->id }}"
                                                            data-slug="{{ $item->slug }}" class="stretched-link">
                                                            <h6 class="card-title">{{ $item->title }}</h6>
                                                        </a>
                                                        <p class="card-text"><small
                                                                class="text-muted">{{ $item->pub_date }}</small></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>
    <!-- section close -->
@stop