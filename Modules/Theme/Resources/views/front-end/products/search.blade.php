@extends('theme::front-end.master')
@section('title')
    <title>{{ trans('theme::frontend.product.search') }}</title>
    <meta name="description" content="{{ !empty($category->description) ? $category->description : $settings['meta_description']}}"/>
    <meta name="keywords" content="{{ !empty($category->keywords) ? $category->keywords : $settings['meta_keyword'] }}" />
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
             "name": "{{ trans('theme::frontend.product.search') }}"
           }
          }
         ]
        }
    </script>
@endsection
@section('breadcrumb')
    <div class="bread__crumb clearfix container">
        <a href="{{ url('/')}}"><i class="fa fa-home"></i></a>
        / <strong>{{ trans('theme::frontend.product.search') }}</strong>
    </div>

@endsection
@section('content')
<div class="container">
    <p class="news__title--small text-center">
        Kết quả tìm kiếm với từ khóa <strong class="text-danger">'{{ $query }}'</strong> có {{ $news->total() }} kết quả
    </p>
    <div class="row">
        @foreach($news as $item)
            <div class="col-md-4 col-lg-3 mb-20">
                <a href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html"
                   class="image-responsive">
                    <img class="image-responsive--lg img-fluid lazyload"
                         data-src="{{ !empty($item->image)?asset($item->image):asset('/images/no_image.jpg') }}"
                         alt="{{ $item->title }}">
                </a>
                <div class="item-body">
                    <h5 class="news__title--lg">
                        <a href="{{ url($item->slug) }}.html"
                           class="news__title--lg">{{ \Illuminate\Support\Str::limit($item->title, 70) }}</a>
                    </h5>
                    <span class="news__date">
                        <a class="item-link"
                           href="{{ url(optional($item->category)->slug) }}">{{ optional($item->category)->title }}</a>
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
    <div class="pagination-fixed d-flex justify-content-center mt-15" >
        {!! $news->appends(\Request::except('page'))->render() !!}
    </div>
</div>
@endsection