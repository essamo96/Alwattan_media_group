@extends('frontend.layouts.master')
@section('title', $page->title)
@section('content')
<div class="tg-innerbanner tg-haslayout tg-parallax tg-bginnerbanner innder-page" data-z-index="-100" data-appear-top-offset="600" data-parallax="scroll" data-image-src="{{ url('assets/site/images/slide.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="tg-innerbannercontent">
                    <h1>{{ $page->title }}</h1>
                    <ol class="tg-breadcrumb">
                        <li><a href="javascript:void(0);">{{ __('site.home') }}</a></li>
                        <li>|</li>
                        <li class="tg-active">{{ $page->title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="tg-sectionspace tg-haslayout about-us">
    <div class="container">
        <div class="tg-aboutus">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="tg-description">
                    {!!$page->details!!}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-0">
                <img src="{{ url('uploads/image/'.$page->image) }}" class="img-fluid left-round-border" alt="image description">
            </div>
        </div>
    </div>
</section>
@stop
