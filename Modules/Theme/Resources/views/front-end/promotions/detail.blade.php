@extends('frontend.master')
@section('title')
    <title>{{ $promotion->title . " - " . strip_tags(Config("settings.app_logo")) }}</title>
@endsection

@section('content')
    <div class="container hs-article-detail">
        <div class="hs-order">
            <h1 class="order-1 hs-title-detail">{{ $promotion->title }}</h1>
            <h2 class="order-0 hs-title-article hs-title text-uppercase"><span>{{ __('Khuyến mãi từ ') }}</span><span class="text-orange">{{ __('XeHue.vn') }}</span></h2>
        </div>
        <p class="hs-postdate">
            <i class="fa fa-clock-o" aria-hidden="true"></i> {{ Carbon\Carbon::parse($promotion->updated_at)->format(config('settings.format.date')) }}
        </p>
        <div class="hs-summary">
            {!! $promotion->description !!}
        </div>
        <div class="hs content">
            {!! $promotion->content !!}
        </div>
        <div class="hs-other">
            <h3 class="hs-title-article hs-title text-uppercase"><span>{{ trans('frontend.other_news') }}</span></h3>
            <ul class="hs-list-other">
                @foreach($otherNews as $item)
                    <li><a href="{{ url('/'. trans('frontend.slug_promotion') . '/' . $item->slug. '.html') }}">{{ $item->title }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection