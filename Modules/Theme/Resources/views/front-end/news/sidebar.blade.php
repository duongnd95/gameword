<!--<div class="col-12 col-lg-3">-->
<!--    <div class="news__viewer">-->
<!--        <h2 class="news__viewer__title">MOST VIEW</h2>-->
<!--        @forelse($news_viewer as $item)-->
<!--            <div class="row p-2">-->
<!--                <div class="col-12 col-md-4 p-0">-->
<!--                    <a href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html"-->
<!--                       class="image-responsive">-->
<!--                        <img class="image-responsive--lg img-fluid lazyload"-->
<!--                             data-src="{{ !empty($item->image)?asset($item->image):asset('/images/no_image.jpg') }}"-->
<!--                             alt="{{ $item->title }}">-->
<!--                    </a>-->
<!--                </div>-->
<!--                <div class="col-12 col-md-8">-->
<!--                    <a class="news__title--small news__title--small--viewer"-->
<!--                       href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html" title="{{ $item->title }}">-->
<!--                        {{ \Illuminate\Support\Str::limit($item->title, 30) }}-->
<!--                    </a>-->
<!--                </div>-->
<!--            </div>-->
<!--        @empty-->
<!--            <div class="text-center">-->
<!--                <small>{{ trans('frontend.data_updated') }}</small>-->
<!--            </div>-->
<!--        @endforelse-->
<!--    </div>-->
<!--</div>-->