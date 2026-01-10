@extends('theme::front-end.master')
@section('title')
    <title>{{ $category->name . ' | ' . $settings['meta_title'] }}</title>
    <meta name="description"
          content="{{ !empty($category->description) ? $category->description : $settings['meta_description'] }}"/>
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
            "@id": "{{ url('/san-pham') }}",
            "name": "{{ trans('theme::frontend.product.title') }}"
            }
          },
          {
           "@type": "ListItem",
          "position": 3,
          "item":
           {
             "@id": "{{ Request::fullUrl() }}",
             "name": "{{ $category->name }}"
           }
          }
         ]
        }

    </script>
@endsection
@section('breadcrumb')
    <div class="breadcrumb breadcrumb-fixed">
        <div class="col-xl-10 col-lg-9 col-md-7 col-sm-8 col-12">
            <a href="{{ url('/')}}">{{ trans('theme::frontend.home') }}</a>
            /
            <a href="{{ url('/san-pham') }}">{{ trans('theme::frontend.product.title') }}</a>
            / <span>{{ $category->name }}</span>
        </div>
        @include('theme::front-end.products.search-list')
    </div>
@endsection
@section('content')
    <div class="container article list-product">
        <h1 class="title-h2 title-font text-center d-none">{{ trans('theme::frontend.product.title') }}</h1>
        <h5 style="font-size: 17px;">Danh mục sản phẩm</h5>
        <div class="row">
            <div class="col-xl-2 col-lg-12">
                <ul class="list-unstyled mt-2" id="list">
                    @foreach($menuProductCategories as  $item)
                        @php($product = Modules\Product\Entities\Product::where('category_id', $item->id)->count())
                        <li class="mb-2" data-id="{{ $item->id }}">
                            <a href="{{ url('san-pham'.'/'.$item->slug) }}">{{ $item->name }}</a>
                            <span class="count_category text-muted float-right">({{ $product }})</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-xl-10 col-lg-12" id="list-filter">
                @include('theme::front-end.products.filterajax')
            </div>
        </div>
@endsection
@section('script')
    @include('theme::front-end.products.script')
@endsection