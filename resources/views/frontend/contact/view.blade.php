@extends('frontend.layouts.master')
@section('title', __('site.contact'))
@section('content')
<div class="tg-innerbanner tg-haslayout tg-parallax tg-bginnerbanner innder-page" data-z-index="-100" data-appear-top-offset="600" data-parallax="scroll" data-image-src="{{ url('assets/site/images/slide.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="tg-innerbannercontent">
                    <h1>{{ __('site.contact') }}</h1>
                    <ol class="tg-breadcrumb">
                        <li><a href="javascript:void(0);">{{ __('site.home') }}</a></li>
                        <li>|</li>
                        <li class="tg-active">{{ __('site.contact') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<main id="tg-main" class="tg-main tg-haslayout">
    <div class="tg-sectionspace tg-haslayout">
        <div class="container">
            <div class="row">
                <div class="tg-contactus">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <?php /* <div class="tg-sectionhead">
                          <h2>@lang('site.contact-head')</h2>
                          </div> */ ?>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                        @include('frontend.layouts.error')
                        <form class="tg-formtheme tg-formcontactus"  method="post" action="{{ route('contact.view') }}">
                            @csrf
                            <fieldset>
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" placeholder="{{ __('site.contact-name') }}">
                                </div>
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" placeholder="{{ __('site.email') }}">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="phone" class="form-control" placeholder="{{ __('site.phone') }}">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="subject" class="form-control" placeholder="{{ __('site.subject') }}">
                                </div>
                                <div class="form-group tg-hastextarea">
                                    <textarea placeholder="  @lang('site.message') "></textarea>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn-default">{{ __('site.send') }}</button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                        <div class="tg-contactdetail">

                            <ul class="tg-contactinfo">
                                <li>
                                    <i class="icon-apartment"></i>
                                    <address>{{ $mysettings->address }}</address>
                                </li>
                                <li>
                                    <i class="icon-phone-handset"></i>
                                    <span>
                                        <em>{{ $mysettings->phone1 }}</em>
                                        <em>{{ $mysettings->phone2 }}</em>
                                    </span>
                                </li>
                                <li>
                                    <i class="icon-envelope"></i>
                                    <span>
                                        <em><a href="mailto:{{ $mysettings->contact_email }}">{{ $mysettings->contact_email }}</a></em>
                                    </span>
                                </li>
                            </ul>
                            <ul class="tg-socialicons">
                                @foreach($social as $item)
                                <li class="tg-{{ $item->icon }}"><a href="{{$item->link }}" target="_blank"><i class="fa fa-{{ $item->icon }}"></i></a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

@stop
@section('js')
<script>
    $('#newsletterForm').submit(function (event) {
//    event.preventDefault();
//    var email = $('#form_email1').val();
//    grecaptcha.ready(function () {
//        grecaptcha.execute('6LeQyuwZAAAAAB31eedgtOiNdgDOE-e5_G1-4D5R', {action: 'contact'}).then(function (token) {
//            $('#newsletterForm').prepend('<input type="hidden" name="token" value="' + token + '">');
//            $('#newsletterForm').prepend('<input type="hidden" name="action" value="contact">');
//            $('#newsletterForm').unbind('submit').submit();
//        });
//        ;
//    });
    });
</script>
@stop