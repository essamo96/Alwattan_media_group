{{-- أيقونة + عدد مشاهدات الخبر بصيغة مختصرة (77K). المتغير المطلوب: $views --}}
<span class="news-views" title="{{ number_format((int) $views) }} {{ __('site.views') }}"><i class="fa fa-eye me-1"></i>{{ Helper::format_views($views) }}</span>
