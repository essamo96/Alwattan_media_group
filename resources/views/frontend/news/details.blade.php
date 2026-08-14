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
                                    @if($post_news->type == 'video' && !empty($post_news->video))
                                    <?php
                                        $video_url = $post_news->video;
                                        $embed_url = $video_url;
                                        if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/))([a-zA-Z0-9_-]{6,})/', $video_url, $m)) {
                                            $embed_url = 'https://www.youtube.com/embed/' . $m[1];
                                        } elseif (preg_match('/vimeo\.com\/(\d+)/', $video_url, $m)) {
                                            $embed_url = 'https://player.vimeo.com/video/' . $m[1];
                                        }
                                    ?>
                                    <div class="ratio ratio-16x9 rounded-lg">
                                        <iframe src="{{ $embed_url }}" title="{{ $post_news->title }}" allowfullscreen></iframe>
                                    </div>
                                    @elseif(!empty($post_news->image) && file_exists(public_path($post_news->image)))
                                    <img src="{{ url($post_news->image) }}" class="rounded-lg img-fluid " alt="{{ $post_news->title }}">
                                    @else
                                    {{-- الصورة المرفقة بالخبر غير موجودة (لم تُرفع أصلاً، أو فُقدت على السيرفر) —
                                         نعرض صورة بديلة بدل رابط صورة مكسور بدون أي مؤشر بصري. --}}
                                    <img src="{{ asset('assets/front/images/misc/1.jpg') }}" class="rounded-lg img-fluid " alt="{{ $post_news->title }}">
                                    @endif
                                </div>
                                @if($post_news->media && $post_news->media->count() > 0)
                                <div class="news-gallery-repeater mt-4">
                                    <div class="row g-3">
                                        @foreach($post_news->media as $media_item)
                                        <div class="col-6 col-md-4">
                                            @if($media_item->type == 'image' && $media_item->path && file_exists(public_path($media_item->path)))
                                            <a href="{{ url($media_item->path) }}" target="_blank" rel="noopener">
                                                <img src="{{ url($media_item->path) }}" class="img-fluid rounded" alt="{{ $post_news->title }}">
                                            </a>
                                            @elseif($media_item->type == 'video' && $media_item->video_url)
                                            <?php
                                                $g_url = $media_item->video_url;
                                                $g_embed = $g_url;
                                                if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/))([a-zA-Z0-9_-]{6,})/', $g_url, $gm)) {
                                                    $g_embed = 'https://www.youtube.com/embed/' . $gm[1];
                                                } elseif (preg_match('/vimeo\.com\/(\d+)/', $g_url, $gm)) {
                                                    $g_embed = 'https://player.vimeo.com/video/' . $gm[1];
                                                }
                                            ?>
                                            <div class="ratio ratio-16x9 rounded">
                                                <iframe src="{{ $g_embed }}" title="{{ $post_news->title }}" allowfullscreen></iframe>
                                            </div>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
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
                        @include('frontend.general.advertisement-sidebar')
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