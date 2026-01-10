@extends('theme::front-end.master')
@if(!empty($page->content))
@section('title')
    <title>{{ $page->name . " | " . $settings['meta_title'] }}</title>
    <meta name="description"
          content="{{ !empty($page->description) ? \Illuminate\Support\Str::limit($page->description, 200) : $settings['meta_description'] }}"/>
    <meta name="keywords" content="{{ !empty($page->keywords) ? $page->keywords : $settings['meta_keyword'] }}"/>
@endsection
@section('schema')
    <script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [{
                "@type": "ListItem",
                "position": 1,
                "item": {
                    "@id": "{{ url('/')}}",
                    "name": "{{ trans('theme::frontend.home.home') }}"
                }
            },
            {
                "@type": "ListItem",
                "position": 2,
                "item": {
                    "@id": "{{ Request::fullUrl() }}",
                    "name": "{{ $page->name }}"
                }
            }
        ]
    }

    </script>
@endsection
@section('slider')
    @if(!empty($page->banner))
        <div class="slide-banner">
            <img src="{{ asset($page->banner) }}" width="100%" alt="{{ $page->name }}">
            <h1 class="title-slide text-center text-uppercase">
                {{ $page->name }}
            </h1>
        </div>
    @else
        <h1 class="title-slide text-center text-uppercase d-none">
            {{ $page->name }}
        </h1>
    @endif
@endsection
@section('content')
    <div class="article article-detail">
        <div class="breadcrumb breadcrumb-fixed justify-content-center">
            <a href="{{ url('/')}}">{{ trans('theme::frontend.home') }}</a>
            <span class="mr_lr">&nbsp;<i class="fa fa-angle-right"></i>&nbsp;</span>
            {{ $menu->title }}
        </div>
        <div class="pl-30">
            <p class="fb-like" data-href="{{ Request::fullUrl() }}" data-width="" data-layout="button_count"
               data-action="like" data-size="small" data-share="true"></p>
            <p class="article-postdate">
                <i class="fas fa-calendar-alt"></i> {{ Carbon\Carbon::parse($page->updated_at)->format(config('settings.format.date')) }}
            </p>
            @if(!empty($page->description))
                <p class="article-summary">
                    <i>{!! $page->description !!}</i>
                </p>
            @endif
            <div class="article-content">
                {!! $page->content !!}
                <div class="fb-comments" data-href="{{ Request::fullUrl() }}" data-width="100%" data-numposts="5"></div>
            </div>
        </div>
    </div>
@endsection
@else
@section('content')
    <div class="page__detail container">
              <div class="bread__crumb clearfix">
            <a href="{{ url('/')}}"><i class="fa fa-home"></i></a> / <strong> {{ $menu->title }}</strong>
        </div>

        <h5 class="title-h2 title-font text-center pt-30 pb-30">
            {{ trans('frontend.data_updated') }}
        </h5>
    </div>
@endsection
@endif