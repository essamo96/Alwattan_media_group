<!-- section begin -->
<section id="section-portfolio" class="no-top no-bottom" aria-label="section">                
    <div id="gallery" class="gallery zoom-gallery full-gallery de-gallery pf_full_width wow fadeInUp" data-wow-delay=".3s">

        <!-- gallery item -->
        <div class="item residential">
            <div class="picframe">
                <a href="{{ asset('assets/front/images/portfolio/1.jpg') }}" title="View Details">
                    <span class="overlay">
                        <span class="pf_text">
                            <span class="project-name">@lang('site.view_image')</span>
                        </span>
                    </span>
                    <img src="{{ asset('assets/front/images/portfolio/1.jpg') }}" alt="" />
                </a>
            </div>
        </div>
        <!-- close gallery item -->

        <!-- gallery item -->
        <div class="item hospitaly">
            <div class="picframe">
                <a href="{{ asset('assets/front/images/portfolio/2.jpg') }}" title="View Details">
                    <span class="overlay">
                        <span class="pf_text">
                            <span class="project-name">@lang('site.view_image')</span>
                        </span>
                    </span>
                    <img src="{{ asset('assets/front/images/portfolio/2.jpg') }}" alt="" />
                </a>
            </div>
        </div>
        <!-- close gallery item -->

        <!-- gallery item -->
        <div class="item hospitaly">
            <div class="picframe">
                <a href="{{ asset('assets/front/images/portfolio/3.jpg') }}" title="View Details">
                    <span class="overlay">
                        <span class="pf_text">
                            <span class="project-name">@lang('site.view_image')</span>
                        </span>
                    </span>
                    <img src="{{ asset('assets/front/images/portfolio/3.jpg') }}" alt="" />
                </a>
            </div>
        </div>
        <!-- close gallery item -->

        <!-- gallery item -->
        <div class="item residential">
            <div class="picframe">
                <a href="{{ asset('assets/front/images/portfolio/4.jpg') }}" title="View Details">
                    <span class="overlay">
                        <span class="pf_text">
                            <span class="project-name">@lang('site.view_image')</span>
                        </span>
                    </span>
                    <img src="{{ asset('assets/front/images/portfolio/4.jpg') }}" alt="" />
                </a>
            </div>
        </div>
        <!-- close gallery item -->
    </div>             
</section>
<!-- section close -->


<!-- section begin -->
<section id="section-contact" class="text-light" data-bgcolor="#213478">
    <div class="wm wm-border dark wow fadeInDown">@lang('site.contact')</div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center wow fadeInUp">
                <h2>@lang('site.get_in_touch')</h2>
                <div class="separator"><span><i class="fa fa-square"></i></span></div>
                <div class="spacer-single"></div>
            </div>
            <div class="col-md-8 wow fadeInUp">
                <form name="contactForm" id='contact_form' method="post" action='{{route('contact.view')}}'>
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div id='name_error' class='error'>@lang('site.your_name')</div>
                            <div>
                                <input type='text' name='name' id='name' class="form-control" placeholder="@lang('site.your_name')">
                            </div>

                            <div id='email_error' class='error'>@lang('site.your_valid')</div>
                            <div>
                                <input type='text' name='email' id='email' class="form-control" placeholder="@lang('site.your_valid')">
                            </div>

                            <div id='phone_error' class='error'>@lang('site.your_phone')</div>
                            <div>
                                <input type='text' name='phone' id='phone' class="form-control" placeholder="@lang('site.your_phone')">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id='message_error' class='error'>@lang('site.your_message')</div>
                            <div>
                                <textarea name='message' id='message' class="form-control" placeholder="@lang('site.your_message')"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12 text-center">
                            <p id='submit'>
                                <input type='submit' id='send_message' value=" @lang('site.send')" class="btn btn-line">
                            </p>
                            <div id='mail_success' class='success'>@lang('site.mail_success')</div>
                            <div id='mail_fail' class='error'>@lang('site.mail_fail')</div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-3 col-xs-6 col-12  wow fadeIn" data-wow-delay=".5s">
            <div class="de_count" >
                <i class="fa fa-map-marker  id-color-2"></i>
                <h6 class="text-white">{{ trans('site.address')}}</h6>
                <span class="text-white">{{ $mysettings->address }}</span>
            </div>
        </div>

        <div class="col-md-3 col-xs-6  col-6 wow fadeIn" data-wow-delay=".5s">
            <div class="de_count">
                <i class="fa fa-phone id-color-2"></i>
                <h6 class="text-white">{{ trans('site.phone')}}</h6>
                <span><a href="tel:{{ $mysettings->phone1 }}" class="text-white">{{ $mysettings->phone1 }}</a></span><br> 
                <span><a href="tel:{{ $mysettings->phone2 }}" class="text-white">{{ $mysettings->phone2 }}</a></span>
            </div>
        </div>

        <div class="col-md-3 col-xs-6  col-6 wow fadeIn" data-wow-delay=".5s">
            <div class="de_count">
                <i class=" fa fa-envelope-o id-color-2"></i>
                <h6 class="text-white">{{ trans('site.email')}}</h6>
                <span class="text-white"><a href="mailto:{{ $mysettings->contact_email }}">{{ $mysettings->contact_email }}</a></span>
            </div>
        </div>
    </div>
</section>
<!-- section close -->

<!-- section begin -->
<section id="call-to-action" class="gradient-to-right text-light call-to-action" aria-label="cta">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 col-md-7">
                <h3 class="size-2 no-margin">{{ $call->title ?? '' }}</h3>
            </div>

            <div class="col-lg-4 col-md-5 d-flex justify-content-end">
                <a href="tel:{{ $mysettings->phone1 }}" class="btn-custom text-white scroll-to">{{ trans('site.call-us')}}</a>
            </div>
        </div>
    </div>
</section>
<!-- logo carousel section close -->

<section id="section-fun-facts" class="jarallax text-light">
    <img src="{{ asset('assets/front/images/bg/7.jpg') }}" class="jarallax-img" alt="">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-xs-6 wow fadeIn" data-wow-delay=".5s">
                <div class="de_count" >
                    <i class="icon_id-2 id-color-2"></i>
                    <h3>{{ $mysettings->projects }}+</h3>
                    <span class="text-white">{{ trans('site.project-complate')}}</span>
                </div>
            </div>

            <div class="col-md-3 col-xs-6 wow fadeIn" data-wow-delay=".5s">
                <div class="de_count">
                    <i class="icon_easel id-color-2"></i>
                    <h3>{{ $mysettings->idea }}+</h3>
                    <span class="text-white">{{ trans('site.creative-idea')}}</span>
                </div>
            </div>

            <div class="col-md-3 col-xs-6 wow fadeIn" data-wow-delay=".5s">
                <div class="de_count">
                    <i class="icon_star id-color-2"></i>
                    <h3>{{ $mysettings->experinace }}+</h3>
                    <span class="text-white">{{ trans('site.years-experience')}}</span>
                </div>
            </div>

            <div class="col-md-3 col-xs-6 wow fadeIn" data-wow-delay=".5s">
                <div class="de_count">
                    <i class="icon_globe id-color-2"></i>
                    <h3>{{ $mysettings->award }}+</h3>
                    <span class="text-white">{{ trans('site.awards-winning')}}</span>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- footer begin -->
<footer class="style-2">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-3">
                <img src="{{ asset('assets/front/images/mediagrope.png') }}" class="logo-small" alt="Media Group"><br>
            </div>

            <div class="col-md-6 text-white copyright">
                &copy; {{ trans('site.copyright') }} {{ date('Y') }} - {{ $mysettings->{'title_'. trans('site.lang')} }}
            </div>

            <div class="col-md-3 text-end text-right">
                <div class="social-icons">
                    @foreach($social as $item)
                        @if(empty($item->link) || empty($item->icon))
                            @continue
                        @endif
                        @php
                            $icon = trim($item->icon);
                            $icon = preg_replace('/^(fa\s+)+/', '', $icon);
                            $icon = ltrim($icon, '-');
                            if (strpos($icon, 'fa-') !== 0) {
                                $icon = 'fa-' . $icon;
                            }
                        @endphp
                        <a href="{{ $item->link }}" target="_blank" rel="noopener noreferrer" title="{{ $item->name }}" aria-label="{{ $item->name }}">
                            <i class="fa {{ $icon }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <a href="#" id="back-to-top" class="custom-1"></a>
</footer>