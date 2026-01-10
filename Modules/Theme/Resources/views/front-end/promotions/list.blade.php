@extends('frontend.master')

@section('title')
    <title>{{ trans('frontend.promotions') . " - " . strip_tags(Config("settings.app_logo")) }}</title>
@endsection
@section('content')
    <div class="container hs-article-list">
        <h1 class="hs-title-article hs-title text-uppercase"><span>{{ __('Khuyến mãi từ ') }}</span><span class="text-orange">{{ __('XeHue.vn') }}</span></h1>
        @forelse($promotions as $item)
            <div class="row hs-row">
                <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3">
                    <div class="hs-entry-thumbs">
                        <a href="{{ url('/' . trans('frontend.slug_promotion') . '/' . $item->slug . ".html") }}" class="hs-link">
                            <img src="{{ $item->avatar ? asset(Storage::url($item->avatar)) : asset('images/noimage.gif') }}" alt="{{ $item->title }}" width="100%">
                        </a>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-7 col-md-8 col-lg-9">
                    <h2 class="hs-entry-title">
                        <a href="{{ url('/' . trans('frontend.slug_promotion') . '/' .$item->slug . ".html") }}" class="hs-link">{{ $item->title }}</a>
                    </h2>
                    <p class="hs-postdate">
                        <i class="fa fa-clock-o" aria-hidden="true"></i> {{ Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.date')) }}
                    </p>
                    <div class="hs-entry-description">
                        {{ str_limit($item->description, 250)  }}
                    </div>
                    <div class="hs-foot">
                        <a href="{{url('/' . trans('frontend.slug_promotion') . '/' . $item->slug . ".html")}}">{{ trans('frontend.view_more') }}</a>
                    </div>
                </div>
            </div>
            <hr>
        @empty
            <div class="col-xs-12 text-center">
                {{ trans('frontend.data_updated') }}
            </div>
        @endforelse
        <div class="text-right">
            {!! $promotions->appends(\Request::except('page'))->render() !!}
        </div>
    </div>
@endsection