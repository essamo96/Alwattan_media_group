{{-- تاريخ نشر الخبر، وعند الوقوف عليه يظهر اليوم والتاريخ والوقت كاملاً.
     المتغيرات: $date (مطلوب) ، $with_time = true لعرض الوقت في النص نفسه ، $tip_align = start|end --}}
@php $date_parts = Helper::news_date_parts($date); @endphp
<span class="news-tip news-tip--{{ $tip_align ?? 'start' }} news-date" tabindex="0" aria-label="{{ $date_parts['full'] }}">
    <i class="fa fa-calendar"></i><span class="news-tip__text">{{ !empty($with_time) ? $date_parts['with_time'] : $date_parts['short'] }}</span>
    <span class="news-tip__box" role="tooltip">{{ $date_parts['full'] }}</span>
</span>
