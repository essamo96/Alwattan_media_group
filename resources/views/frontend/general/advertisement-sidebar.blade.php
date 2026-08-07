@if(isset($ads_sidebar) && count($ads_sidebar) > 0)
<div class="widget widget-ad ad-sidebar-widget">
    @foreach($ads_sidebar as $ad)
    <a href="{{ $ad->url }}" target="_blank" rel="noopener" class="ad-sidebar-card" aria-label="{{ $ad->name }}">
        <span class="ad-sidebar-badge">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 11L19 4L17 12L19 20L3 13V11Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/></svg>
            إعلان
        </span>
        <img src="{{ asset('uploads/advertisements/'.$ad->image) }}" alt="{{ $ad->name }}" loading="lazy">
    </a>
    @endforeach
</div>

<style>
    .ad-sidebar-widget{margin-bottom:24px;}
    .ad-sidebar-card{
        position:relative;
        display:block;
        border-radius:12px;
        overflow:hidden;
        margin-bottom:18px;
        box-shadow:0 6px 20px rgba(20,30,70,.10);
        transition:box-shadow .25s ease, transform .2s ease;
    }
    .ad-sidebar-card:last-child{margin-bottom:0;}
    .ad-sidebar-card:hover{
        box-shadow:0 10px 28px rgba(20,30,70,.16);
        transform:translateY(-2px);
    }
    .ad-sidebar-card img{
        width:100%;
        height:auto;
        display:block;
        transition:transform .3s ease;
    }
    .ad-sidebar-card:hover img{transform:scale(1.03);}
    .ad-sidebar-badge{
        position:absolute;
        top:10px;
        inset-inline-start:10px;
        z-index:2;
        display:inline-flex;
        align-items:center;
        gap:4px;
        background:rgba(28,35,64,.72);
        color:#fff;
        font-family:'Cairo','Tahoma',sans-serif;
        font-size:11px;
        font-weight:700;
        padding:3px 9px;
        border-radius:999px;
    }
</style>
@endif
