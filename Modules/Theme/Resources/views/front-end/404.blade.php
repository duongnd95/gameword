@extends('theme::front-end.master')
@section('title')
    <title>{{ '404: Page not found - ' . $settings['meta_title'] }}</title>
    <META NAME="KEYWORDS" content="{{ $settings['meta_keyword'] }}"/>
    <meta name="description" content="{{ $settings['meta_description'] }}"/>
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
             "name": "{{ trans('theme::frontend.error_page.not_found') }}"
           }
          }
         ]
        }
    </script>
@endsection
@section('breadcrumb')
    <div class="bread__crumb clearfix container">
        <a href="{{ url('/')}}"><i class="fa fa-home"></i></a> / <strong>{{ trans('theme::frontend.error_page.not_found') }}</strong>
    </div>
{{--    <div class="breadcrumb breadcrumb-fixed justify-content-center">--}}
{{--        <a href="{{ url('/')}}">{{ trans('theme::frontend.home') }}</a>--}}
{{--        <span class="mr_lr">&nbsp;<i class="fa fa-angle-right"></i>&nbsp;</span>--}}
{{--        <span>{{ trans('theme::frontend.error_page.not_found') }}</span>--}}
{{--    </div>--}}
@endsection
@section('content')
    <div class="container">
        <h5 class="title-h2 title-font text-center pt-30 pb-30">
        {{ trans('theme::frontend.error_page.sorry_page') }}.
        </h5>
    </div>
@endsection

