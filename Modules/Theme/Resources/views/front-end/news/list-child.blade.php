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
            "@id": "{{ url(optional($category->parent)->slug) }}",
            "name": "{{ optional($category->parent)->title }}"
            }
          },
          {
           "@type": "ListItem",
          "position": 3,
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
        <a href="{{ url('/')}}"><i class="fa fa-home"></i></a> /
        <a href="{{ url(optional($category->parent)->slug) }}"><strong>{{ optional($category->parent)->title }}</strong></a> /
        <strong>{{ $category->title }}</strong>
    </div>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            @if($news->count() > 0)
                <div class="col-12 col-lg-9">
                    <div class="row news-items">
                        @foreach($news as $item)
                            <div class="col-12 col-sm-6 col-md-4 item mb-15">
                                <a href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html"
                                   class="image-responsive">
                                    <img class="image-responsive--lg img-fluid lazyload"
                                         data-src="{{ !empty($item->image)?asset($item->image):asset('/images/no_image.jpg') }}"
                                         alt="{{ $item->title }}">
                                </a>
                                <div class="item-body">
                                    <h5 class="news__title--lg">
                                        <a href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html"
                                           class="news__title--lg" title="{{ $item->title }}">{{ \Illuminate\Support\Str::limit($item->title, 50) }}</a>
                                    </h5>
                                    <span class="news__date">
                                        {{ Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.date')) }}
                                    </span>
                                    @empty(!$item->description)
                                        <p class="news__description">{{ \Illuminate\Support\Str::limit($item->description, 70) }}</p>
                                    @endempty
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="pagination-fixed d-flex justify-content-start">
                        {!! $news->appends(\Request::except('page'))->render() !!}
                    </div>
                </div>
                @include('theme::front-end.news.sidebar')
            @else
                <div class="col-12 col-lg-9 text-center">
                    <div>
                        <img class="img-fluid lazyload" data-src="{{ asset('images/empty.gif') }}" >
                    </div>
                </div>
                @include('theme::front-end.news.sidebar')
            @endif
        </div>
    </div>
@endsection