@extends('theme::front-end.master')
@section('title')
    <title>{{ $category->title . ' | ' . $settings['meta_title'] }}</title>
    <meta name="description"
          content="{{ !empty($category->description) ? $category->description : trans('frontend.description') }}"/>
    <meta name="keywords"
          content="{{ !empty($category->keywords) ? $category->keywords : $settings['meta_keyword'] }}"/>
@endsection
@section('schema')
    <script type="application/ld+json">
        {
         "@context": "http://schema.org",
         "@type": "BreadcrumbList",
         "itemListElement":
         [
          {
           "@type": "ListItem",
           "position": 1,
           "item":
           {
            "@id": "{{ url('/')}}",
            "name": "{{ trans('theme::frontend.home.home') }}"
            }
          },
          {
           "@type": "ListItem",
          "position": 2,
          "item":
           {
             "@id": "{{ Request::fullUrl() }}",
             "name": "{{ $category->title }}"
           }
          }
         ]
        }
    </script>
@endsection
@section('breadcrumb')
    <div class="bread__crumb clearfix container">
        <h5 class="news__title--lg text-uppercase">{{ $category->title }}</h5>
        <a href="{{ url('/')}}"><i class="fa fa-home"></i></a> / <strong>{{ $category->title }}</strong>
    </div>
@endsection
@section('content')
    @if($categories->count() == 0)
        <div class="article article-list container">
            <div class="row ">
                @php($news = \App\News::with('category')->where([['category_id', $category->id], ['active', config('settings.active')]])->orderBy('updated_at', 'DESC')->paginate(config('settings.paginate.page12')))
                @if($news->count() > 0)
                    <div class="col-12 col-lg-9">
                        <div class="row news-items">
                            @foreach($news as $item)
                                <div class="col-12 col-md-6 mb-15">
                                    <a href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html"
                                       class="image-responsive">
                                        <img class="image-responsive--lg img-fluid lazyload"
                                             data-src="{{ !empty($item->image)?asset($item->image):asset('/images/no_image.jpg') }}"
                                             alt="{{ $item->title }}">
                                    </a>
                                    <div class="item-body">
                                        <h5 class="news__title--lg">
                                            <a href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html"
                                               class="news__title--lg">{{ $item->title }}</a>
                                        </h5>
                                        <span class="news__date">
                                            <a class="item-link"
                                               href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html">{{ optional($item->category)->title }}</a>
                                            <span>-&nbsp;&nbsp;</span>
                                            <span>{{ Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.date')) }}</span>
                                        </span>
                                        @empty(!$item->description)
                                            <p class="news__description">{{ \Illuminate\Support\Str::limit($item->description, 70) }}</p>
                                        @endempty
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="pagination-fixed d-flex justify-content-center">
                            {!! $news->appends(\Request::except('page'))->render() !!}
                        </div>
                    </div>
                    @include('theme::front-end.news.sidebar')
                @else
                    <div class="col-12 col-lg-9 text-center">
                        <small>{{ trans('frontend.data_updated') }}</small>
                        <div>
                            <img class="img-fluid lazyload" data-src="{{ asset('images/empty.gif') }}" height="50" width="150">
                        </div>
                    </div>
                    @include('theme::front-end.news.sidebar')
                @endif
            </div>
        </div>
    @else
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-9">
                    @foreach($categories as $item)
                        <div class="news__title">
                            <a href="{{ url($item->slug) }}" class="news__title--active">{{ $item->title }}</a>
                        </div>
                        <div class="row">
                            @php($news = \App\News::with('category')->where([['category_id', $item->id], ['active', config('settings.active')]])->latest()->get())
                            @php($news_large = $news->filter(function($news, $key){
                                return $key == 0 || $key == 1;
                            }))
                            @php($news_small = $news->filter(function($news, $key){
                                 return $key != 0 && $key != 1;
                            }))
                            @if($news->count() > 0)
                                <div class="col-12 col-md-12">
                                    <div class="row news__market--wrap">
                                        @foreach($news_large as $itemX)
                                            <div class="col-12 col-md-6 mb-15">
                                                <a href="{{ url(optional($itemX->category)->slug . '/' .$itemX->slug) }}.html"
                                                   class="image-responsive">
                                                    <img class="image-responsive--lg img-fluid lazyload"
                                                         data-src="{{ !empty($itemX->image)?asset($itemX->image):asset('/images/no_image.jpg') }}"
                                                         alt="{{ $itemX->title }}">
                                                </a>
                                                <div class="item-body">
                                                    <h5 class="news__title--lg">
                                                        <a href="{{ url(optional($itemX->category)->slug . '/' .$itemX->slug) }}.html"
                                                           class="news__title--lg">{{ $itemX->title }}</a>
                                                    </h5>
                                                    <p class="news__date">
                                                        <a class="item-link"
                                                           href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html">{{ $item->title }}</a>
                                                        <span>-&nbsp;&nbsp;</span>
                                                        <span>{{ Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.date')) }}</span>
                                                    </p>
                                                    @empty(!$itemX->description)
                                                        <p class="news__description">{{ \Illuminate\Support\Str::limit($itemX->description, 70) }}</p>
                                                    @endempty
                                                </div>
                                            </div>
                                        @endforeach
                                        <div class="col-12 col-md-12">
                                            <hr>
                                        </div>
                                        @foreach($news_small as $item)
                                            <div class="col-md-6 mb-4">
                                                <div class="row">
                                                    <div class="col-12 col-md-4">
                                                        <a href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html"
                                                           class="image-responsive">
                                                            <img class="image-responsive--lg img-fluid lazyload"
                                                                 data-src="{{ !empty($item->image)?asset($item->image):asset('/images/no_image.jpg') }}"
                                                                 alt="{{ $item->title }}">
                                                        </a>
                                                    </div>
                                                    <div class="col-12 col-md-8">
                                                        <h5 class="news__title--small">
                                                            <a href="{{ url($item->slug) }}.html">
                                                                {{ \Illuminate\Support\Str::limit($item->title, 70) }}
                                                            </a>
                                                        </h5>
                                                        <div class="news__date">
                                                            <span>{{ _("Update ").Carbon\Carbon::parse($news[0]->updated_at)->format(config('settings.format.date')) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            @else
                                <div class="col-12 col-md-12 text-center">
{{--                                    <small>{{ trans('frontend.data_updated') }}</small>--}}
                                    <div>
                                        <img class="img-fluid lazyload" data-src="{{ asset('images/empty.gif') }}" >
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                @include('theme::front-end.news.sidebar')
            </div>
        </div>
    @endif
@endsection