@if(isset($ads_banner) && count($ads_banner) > 0)
<section id="section-ad-banner" class="ad-banner-section">
    <div class="container">
        <div class="ad-banner-carousel" data-ad-carousel>
            @foreach($ads_banner as $index => $ad)
            <a href="{{ $ad->url }}" target="_blank" rel="noopener" class="ad-banner-slide {{ $index == 0 ? 'is-active' : '' }}" aria-label="{{ $ad->name }}">
                <span class="ad-banner-badge">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 11L19 4L17 12L19 20L3 13V11Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
                    إعلان
                </span>
                <img src="{{ asset('uploads/advertisements/'.$ad->image) }}" alt="{{ $ad->name }}" loading="lazy">
            </a>
            @endforeach

            @if(count($ads_banner) > 1)
            <div class="ad-banner-dots">
                @foreach($ads_banner as $index => $ad)
                <button type="button" class="ad-banner-dot {{ $index == 0 ? 'is-active' : '' }}" data-ad-dot="{{ $index }}" aria-label="إعلان {{ $index + 1 }}"></button>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</section>

<style>
    .ad-banner-section{padding:34px 0;}
    .ad-banner-carousel{
        position:relative;
        width:100%;
        border-radius:16px;
        overflow:hidden;
        box-shadow:0 10px 34px rgba(20,30,70,.12);
        background:#eef0f6;
        aspect-ratio:16/4.2;
        max-height:260px;
    }
    @media (max-width:768px){
        .ad-banner-carousel{aspect-ratio:16/9;max-height:220px;}
    }
    .ad-banner-slide{
        position:absolute;
        inset:0;
        display:block;
        opacity:0;
        visibility:hidden;
        transition:opacity .5s ease;
        z-index:1;
    }
    .ad-banner-slide.is-active{
        opacity:1;
        visibility:visible;
        z-index:2;
    }
    .ad-banner-slide img{
        width:100%;
        height:100%;
        object-fit:cover;
        display:block;
        transition:transform .35s ease;
    }
    .ad-banner-slide:hover img{transform:scale(1.02);}
    .ad-banner-badge{
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
    .ad-banner-dots{
        position:absolute;
        bottom:12px;
        left:50%;
        transform:translateX(-50%);
        z-index:4;
        display:flex;
        gap:7px;
    }
    .ad-banner-dot{
        width:8px;
        height:8px;
        border-radius:50%;
        border:none;
        background:rgba(255,255,255,.55);
        cursor:pointer;
        padding:0;
        transition:background .25s ease, transform .25s ease;
    }
    .ad-banner-dot.is-active{
        background:#c0212f;
        transform:scale(1.2);
    }
</style>

<script>
(function(){
    var carousel = document.querySelector('[data-ad-carousel]');
    if(!carousel) return;
    var slides = carousel.querySelectorAll('.ad-banner-slide');
    var dots = carousel.querySelectorAll('.ad-banner-dot');
    if(slides.length < 2) return;
    var current = 0;
    var timer;

    function goTo(index){
        slides[current].classList.remove('is-active');
        dots.length && dots[current].classList.remove('is-active');
        current = index % slides.length;
        slides[current].classList.add('is-active');
        dots.length && dots[current].classList.add('is-active');
    }

    function next(){ goTo(current + 1); }

    function start(){ timer = setInterval(next, 5000); }
    function stop(){ clearInterval(timer); }

    dots.forEach(function(dot){
        dot.addEventListener('click', function(){
            stop();
            goTo(parseInt(dot.getAttribute('data-ad-dot'), 10));
            start();
        });
    });

    carousel.addEventListener('mouseenter', stop);
    carousel.addEventListener('mouseleave', start);

    start();
})();
</script>
@endif
