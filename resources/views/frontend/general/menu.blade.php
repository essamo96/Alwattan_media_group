<!-- header begin -->
<header class="transparent">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- logo begin -->
                <div id="logo">
                    <a href="{{ url('/')}}">
                        <img class="logo" src="{{ asset('assets/front/images/wattan3.png') }}" alt="">
                    </a>
                </div>
                <!-- logo close -->

                <!-- small button begin -->
                <span id="menu-btn"></span>
                <!-- small button close -->

                <div class="header-extra">
                    <div class="v-center">
                        <span id="b-menu">
                            <span></span>
                            <span></span>
                            <span></span>
                        </span>
                    </div>
                </div>

                <!-- mainmenu begin -->
                <ul id="mainmenu" class="ms-2">
                    <li><a href="<?= $active_menu != 'home' ? url('/') . '/' : '' ?>#section-hero">@lang('site.home')<span></span></a></li>
                    <li><a href="<?= $active_menu != 'home' ? url('/') . '/' : '' ?>#section-about">@lang('site.about-us')<span></span></a></li>
                    <li><a href="<?= $active_menu != 'home' ? url('/') . '/' : '' ?>#section-services">@lang('site.our-services')<span></span></a></li>
                    <li><a href="<?= $active_menu != 'home' ? url('/') . '/' : '' ?>#section-schedule">@lang('site.blog')<span></span></a></li>
                    <li><a href="<?= $active_menu != 'home' ? url('/') . '/' : '' ?>#section-partners">@lang('site.partners')<span></span></a></li>
                    <li><a href="<?= $active_menu != 'home' ? url('/') . '/' : '' ?>#section-contact">@lang('site.contact')<span></span></a></li>
                    <?php if (trans('site.lang') == 'ar') { ?>
                        <li><a hreflang="en"  title="English" href="{{ LaravelLocalization::getLocalizedURL('en') }}"`>En<span></span></a></li>
                    <?php } else { ?>
                        <li><a hreflang="ar"  title="Arabic" href="{{ LaravelLocalization::getLocalizedURL('ar') }}"`>AR<span></span></a></li>
                    <?php } ?>
                </ul>
                <!-- mainmenu close -->
            </div>
        </div>
    </div>
</header>
<!-- header close -->