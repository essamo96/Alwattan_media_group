<div id="de-extra-wrap" class="de_light">
    <span id="b-menu-close">
        <span></span>
        <span></span>
    </span>
    <div class="de-extra-content">
        <h3>{{ $info->title }}</h3>
        {!!$info->details!!}        

        <div class="spacer-single"></div>

        <h3>@Lang('site.where-when')</h3>
        <div class="h6 padding10 pt0 pb0"><i class="i_h3 fa fa-map-marker id-color"></i>{{ $mysettings->address }}</div>
        <div class="h6 padding10 pt0 pb0"><i class="i_h3 fa fa-phone id-color"></i>{{ $mysettings->phone1 }}</div>
        <div class="h6 padding10 pt0 pb0"><i class="i_h3 fa fa-envelope-o id-color"></i><a href="mailto:{{ $mysettings->contact_email }}">{{ $mysettings->contact_email }}</a></div>
    </div>
</div>
<div id="de-overlay"></div>
<!-- Javascript Files
================================================== -->
<script src="{{ asset('assets/front/js/plugins.js?v=3') }}"></script>
<script src="{{ asset('assets/front/js/default.js?v=4') }}"></script>
<script src="{{ asset('assets/front/js/hero-slider.js?v=1') }}"></script>
<script src="{{ asset('assets/front/js/validation.js?v=3') }}"></script>
<script src="{{ asset('assets/front/js/countdown-custom.js?v=3') }}"></script>
<script src="{{ asset('assets/front/js/nav-priority.js?v=1') }}"></script>