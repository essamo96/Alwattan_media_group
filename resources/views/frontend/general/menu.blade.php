<!-- header begin -->
<header class="transparent">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <!-- logo begin -->
                <div id="logo">
                    <a href="{{ url('/')}}">
                        <img class="logo" src="{{ asset('assets/front/images/mediagrope.png') }}" alt="Media Group">
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
                    @if(isset($site_menus) && count($site_menus) > 0)
                        @foreach($site_menus as $site_menu)
                            <?php
                            $has_children = isset($site_menu->children) && count($site_menu->children) > 0;

                            $build_url = function ($menu) use ($active_menu) {
                                $menu_url = $menu->url;
                                if (empty($menu_url) || $menu_url === '#') {
                                    return 'javascript:void(0)';
                                }
                                if (strpos($menu_url, '#') === 0) {
                                    // قسم داخل الصفحة الرئيسية
                                    return ($active_menu != 'home' ? url('/') . '/' : '') . $menu_url;
                                }
                                if (!preg_match('#^(https?:)?//#i', $menu_url) && strpos($menu_url, 'mailto:') !== 0 && strpos($menu_url, 'tel:') !== 0) {
                                    return url($menu_url);
                                }
                                return $menu_url;
                            };
                            $menu_url = $build_url($site_menu);
                            $menu_title = (trans('site.lang') == 'en' && !empty($site_menu->name_en)) ? $site_menu->name_en : $site_menu->name_ar;
                            ?>
                            <li class="mainmenu-item {{ $has_children ? 'has-children' : '' }}">
                                <a href="{{ $menu_url }}" target="{{ $site_menu->target ?: '_self' }}">
                                    @if(!empty($site_menu->image))
                                        <img src="{{ asset('uploads/menus/'.$site_menu->image) }}" alt="{{ $menu_title }}" class="menu-logo" style="height: 22px;width: auto;vertical-align: middle;margin-inline-end: 6px;">
                                    @elseif(!empty($site_menu->icon))
                                        <i class="{{ $site_menu->icon }}" style="margin-inline-end: 6px;"></i>
                                    @endif
                                    {{ $menu_title }}<span></span>
                                </a>
                                @if($has_children)
                                <ul>
                                    @foreach($site_menu->children as $child_menu)
                                        <?php
                                        $child_url = $build_url($child_menu);
                                        $child_title = (trans('site.lang') == 'en' && !empty($child_menu->name_en)) ? $child_menu->name_en : $child_menu->name_ar;
                                        ?>
                                        <li>
                                            <a href="{{ $child_url }}" target="{{ $child_menu->target ?: '_self' }}">
                                                @if(!empty($child_menu->image))
                                                    <img src="{{ asset('uploads/menus/'.$child_menu->image) }}" alt="{{ $child_title }}" class="menu-logo" style="height: 18px;width: auto;vertical-align: middle;margin-inline-end: 6px;">
                                                @elseif(!empty($child_menu->icon))
                                                    <i class="{{ $child_menu->icon }}" style="margin-inline-end: 6px;"></i>
                                                @endif
                                                {{ $child_title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                                @endif
                            </li>
                        @endforeach
                    @else
                        <li class="mainmenu-item"><a href="<?= $active_menu != 'home' ? url('/') . '/' : '' ?>#section-hero">@lang('site.home')<span></span></a></li>
                        <li class="mainmenu-item"><a href="<?= $active_menu != 'home' ? url('/') . '/' : '' ?>#section-about">@lang('site.about-us')<span></span></a></li>
                        <li class="mainmenu-item"><a href="<?= $active_menu != 'home' ? url('/') . '/' : '' ?>#section-services">@lang('site.our-services')<span></span></a></li>
                        <li class="mainmenu-item"><a href="<?= $active_menu != 'home' ? url('/') . '/' : '' ?>#section-schedule">@lang('site.blog')<span></span></a></li>
                        <li class="mainmenu-item"><a href="<?= $active_menu != 'home' ? url('/') . '/' : '' ?>#section-partners">@lang('site.partners')<span></span></a></li>
                        <li class="mainmenu-item"><a href="<?= $active_menu != 'home' ? url('/') . '/' : '' ?>#section-contact">@lang('site.contact')<span></span></a></li>
                    @endif

                    <!-- overflow "show more" item, populated by JS when the menu doesn't fit on one line -->
                    <li id="menu-more-item" class="has-children" style="display:none;">
                        <a href="javascript:void(0)">{{ trans('site.lang') == 'ar' ? 'المزيد' : 'More' }}<span></span></a>
                        <ul id="menu-more-list"></ul>
                    </li>
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