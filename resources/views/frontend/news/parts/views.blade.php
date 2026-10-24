{{-- أيقونة + عدد مشاهدات الخبر مختصراً (77K)، وعند الوقوف عليه يظهر الرقم الكامل.
     المتغيرات: $views (مطلوب) ، $tip_align = start|end (اختياري: جهة محاذاة الفقاعة) --}}
<span class="news-tip news-tip--{{ $tip_align ?? 'start' }} news-views" tabindex="0" aria-label="{{ (int) $views }} {{ __('site.views') }}">
    <i class="fa fa-eye"></i><span class="news-tip__text">{{ Helper::format_views($views) }}</span>
    <span class="news-tip__box" role="tooltip"><strong>{{ (int) $views }}</strong> {{ __('site.views') }}</span>
</span>
