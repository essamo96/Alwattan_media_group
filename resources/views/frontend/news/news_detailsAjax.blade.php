<article>
    <div class="post-item-2">
        <div class="post-inner">
            <h1 class="mb-4">{{$post_news->title}}</h1>

            <div class="post-thumb rounded">
                <img src="{{ url($post_news->image)}}" class="rounded-lg" alt="blog">
            </div>
            <div class="post-content">
                <ul class="lab-ul post-date my-3">
                    <li><span><i class="fa fa-calendar me-2"></i> {{$post_news->pub_date}}</span></li>
                </ul>
                {!! $post_news->descs !!}
                <div class="tags-area">
                    @if($post_news->tags!='')
                    <?php
                    $tags = explode(',', $post_news->tags);
                    ?>
                    <ul class="tags lab-ul justify-content-center">
                        <?php
                        $tags = explode(',', $post_news->tags);
                        ?>
                        @foreach($tags as $tag)
                        <li>
                            <a href="#">{{ $tag }}</a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                    <ul class="share lab-ul justify-content-center">
                        <li>
                            <a href="#" class="facebook d-flex align-items-center justify-content-center rounded"><i class="fa fa-facebook-f"></i></a>
                        </li>
                        <li>
                            <a href="#" class="dribble d-flex align-items-center justify-content-center rounded"><i class="fa fa-instagram"></i></a>
                        </li>
                        <li>
                            <a href="#" class="twitter d-flex align-items-center justify-content-center rounded"><i class="fa fa-twitter"></i></a>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>


</article>