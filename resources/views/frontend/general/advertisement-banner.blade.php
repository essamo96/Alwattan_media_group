@if(isset($ads_banner) && count($ads_banner) > 0)
<section id="section-ad-banner" class="ad-banner-section" aria-label="section">
    <div class="wm wm-border light wow fadeInDown">@lang('site.advertisements')</div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center wow fadeInUp">
                <h2>@lang('site.advertisements')</h2>
                <div class="separator"><span><i class="fa fa-square"></i></span></div>
                <div class="spacer-single"></div>
            </div>
        </div>
        <div class="row ad-cards-grid">
            @foreach($ads_banner as $index => $ad)
            <div class="col-lg-4 col-sm-6 wow fadeInUp" data-wow-delay="{{ ($index % 3) * 0.1 }}s">
                <a href="{{ $ad->url }}" target="_blank" rel="noopener" class="ad-card" aria-label="{{ $ad->name }}">
                    <span class="ad-card__badge">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 11L19 4L17 12L19 20L3 13V11Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
                        إعلان
                    </span>
                    <div class="ad-card__media">
                        <img src="{{ asset('uploads/advertisements/'.$ad->image) }}" alt="{{ $ad->name }}" loading="lazy">
                        <span class="ad-card__scrim"></span>
                    </div>
                    <div class="ad-card__title">
                        <span>{{ $ad->name }}</span>
                        <svg class="ad-card__arrow" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<style>
    .ad-cards-grid{
        row-gap:28px;
    }
    .ad-card{
        display:block;
        position:relative;
        border-radius:16px;
        overflow:hidden;
        background:#fff;
        box-shadow:0 8px 24px rgba(20,30,70,.10);
        transition:transform .3s ease, box-shadow .3s ease;
    }
    .ad-card:hover{
        transform:translateY(-6px);
        box-shadow:0 16px 34px rgba(20,30,70,.18);
    }
    .ad-card__badge{
        position:absolute;
        top:12px;
        inset-inline-start:12px;
        z-index:3;
        display:inline-flex;
        align-items:center;
        gap:5px;
        background:rgba(28,35,64,.72);
        color:#fff;
        font-family:'Cairo','Tahoma',sans-serif;
        font-size:11.5px;
        font-weight:700;
        padding:4px 10px;
        border-radius:999px;
        letter-spacing:.2px;
        backdrop-filter:blur(2px);
    }
    .ad-card__media{
        position:relative;
        width:100%;
        aspect-ratio:4/3;
        overflow:hidden;
        background:#eef0f6;
    }
    .ad-card__media img{
        width:100%;
        height:100%;
        object-fit:cover;
        display:block;
        transition:transform .45s ease;
    }
    .ad-card:hover .ad-card__media img{
        transform:scale(1.06);
    }
    /* يمزج أسفل الصورة تدريجياً بلون العنوان الأزرق حتى يبدو الانتقال إلى
       شريط العنوان متداخلاً بصرياً بدل القطع المفاجئ. */
    .ad-card__scrim{
        position:absolute;
        inset:auto 0 0 0;
        height:45%;
        background:linear-gradient(to top, #213478 0%, rgba(33,52,120,0) 100%);
        pointer-events:none;
    }
    /* شريط العنوان بتدرج لوني متداخل (أزرق داكن → أحمر العلامة) بدل لون واحد مسطح. */
    .ad-card__title{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:10px;
        padding:14px 18px;
        background:linear-gradient(120deg, #213478 0%, #2c418f 55%, #c0212f 130%);
        color:#fff;
        font-family:'Cairo','Tahoma',sans-serif;
        font-size:15px;
        font-weight:700;
    }
    .ad-card__title span{
        overflow:hidden;
        text-overflow:ellipsis;
        white-space:nowrap;
    }
    .ad-card__arrow{
        flex:none;
        opacity:.85;
        transition:transform .3s ease;
    }
    .ad-card:hover .ad-card__arrow{
        transform:translateX(-4px);
        opacity:1;
    }
    html[dir="ltr"] .ad-card:hover .ad-card__arrow{
        transform:translateX(4px);
    }
</style>
@endif
