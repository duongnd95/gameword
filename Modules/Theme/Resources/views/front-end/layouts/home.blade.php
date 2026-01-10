@extends('theme::front-end.master')
@section('content')
    <section class="home__slider container mt-2 ">
        <div class="news-slider owl-carousel">
            @for($i = 0; $i < $sliders->count();$i++)
                <div class="item">
                    <img class="img-fluid lazyload" data-src="{{ asset($sliders[$i]['image']) }}"
                         alt="{{ $sliders[$i]['name'] }}">
                </div>
            @endfor
        </div>
    </section>
    <section class="news__hot container mt-2">
        <div class="row">
            @foreach($newsHot as $item)
                <div class="col-12 col-md-4">
                    <div class="news__item">
                        <a href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html"
                           class="image-responsive item-link">
                            <img class="image-responsive--lg lazyload"
                                 data-src="{{ !empty($item->image)?asset($item->image):asset('/images/no_image.jpg') }}"
                                 alt="{{ $item->title }}">
                        </a>
                        <a class="news__content news__title--top"
                           href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html"
                        ><span>{{ \Illuminate\Support\Str::limit($item->title, 70)}}</span></a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    <section class="news__market container py-4">
        @foreach($categories as $key => $item)
            @if(($item->gallery)->count() >0)
                <div class="row">
                    <div class="col-12 col-md-9">
                        <div class="news-slider owl-carousel">
                            @foreach($item->gallery as $itemX)
                                <div class="item">
                                    <img class="img-fluid lazyload"
                                         data-src="{{ !empty($itemX->image)?asset($itemX->image):asset('/images/no_image_banner.jpg') }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @if($key == 0)
                        @include('theme::front-end.news.sidebar')
                    @endif
                </div>
            @endif
            <div class="row">
                <div class="col-md-9">
                    <div class="news__title">
                        <a href="{{ url($item->slug) }}" class="news__title--active">{{ $item->title }}</a>
                        @php($categories_sub = \App\Category::with('parent')->where('parent_id', $item->id)->get())
                        @foreach($categories_sub as $itemSub)
                            <a href="{{ url($item->slug."/".$itemSub->slug) }}">{{ $itemSub->title }}</a>
                        @endforeach
                    </div>
                    @php($categoryIds = \App\Category::with('parent')->where('parent_id', $item->id)->pluck('id')->toArray())
                    @php($news = \App\News::with('category')->whereIn('category_id', $categoryIds)->orWhere('category_id', $item->id)->latest()->take(6)->get())
                    @php($news_small = $news->filter(function($news, $key){
                         return $key != 0;
                    }))
                    @if($news->count() >0)
                        <div class="news__market--wrap">
                            <div class="row">
                                <div class="col-md-6">
                                    @if($news != null)
                                        <a href="{{ url(optional($news[0]->category)->slug . '/' .$news[0]->slug) }}.html"
                                           class="image-responsive">
                                            <img class="img-fluid image-responsive--lg lazyload"
                                                 data-src="{{ !empty($news[0]->image)?asset($news[0]->image):asset('/images/no_image.jpg') }}"
                                                 alt="{{ $news[0]->title }}">
                                        </a>
                                        <a href="{{ url($news[0]->category->slug . '/' .$news[0]->slug) }}.html"
                                           class="news__title--lg">{{ $news[0]->title }}</a>
                                        <div class="news__date">
                                            <a class="item-link"
                                               href="{{ url(optional($news[0]->category)->slug) }}">{{ optional($news[0]->category)->title }}</a>
                                            <span>-&nbsp;&nbsp;</span>
                                            <span>{{ _("Update ").Carbon\Carbon::parse($news[0]->updated_at)->format(config('settings.format.date')) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    @foreach($news_small as $item)
                                        <div class="row p-2">
                                            <div class="col-md-3 p-0">
                                                <a href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html"
                                                   class="image-responsive">
                                                    <img class="img-fluid image-responsive--lg lazyload"
                                                         data-src="{{ !empty($item->image)?asset($item->image):asset('/images/no_image.jpg') }}"
                                                         alt="{{ $item->title }}">
                                                </a>
                                            </div>
                                            <div class="col-md-9">
                                                <a href="{{ url($item->category->slug . '/' .$item->slug) }}.html"
                                                   class="news__title--small">{{ \Illuminate\Support\Str::limit($item->title, 60) }}</a>
                                                <span class="news__date">
                                                 <i>{{ _("Update ").Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.date')) }}</i>
                                            </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-md-12 text-center">
                            <div>
                                <img class="img-fluid lazyload" data-src="{{ asset('images/empty.gif') }}">
                            </div>
                        </div>
                    @endif


                </div>
            </div>
        @endforeach
    </section>
@endsection

