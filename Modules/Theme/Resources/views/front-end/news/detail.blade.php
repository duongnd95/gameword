@extends('theme::front-end.master')
@section('title')
    <title>{!! $news->title !!}</title>
    <META NAME="KEYWORDS" content="{{ !empty($news->meta_keyword) ? $news->meta_keyword : "" }}"/>
    <meta name="description" content="{{ !empty($news->description) ? strip_tags(Str::limit($news->description, 200)) : strip_tags(Str::limit($news->content, 200)) }}"/>
@endsection
@section('facebook')
    <meta property="og:title" content="{!! $news->title !!}"/>
    <meta property="og:description" content="{{ !empty($news->description) ? strip_tags(Str::limit($news->description, 200)) : strip_tags(Str::limit($news->content, 200)) }}"/>
    <meta property="og:image" content="{{ !empty($news->image) ? asset($news->image) : asset(Storage::url($settings['company_logo'])) }}"/>
    <meta property="og:image:secure_url" content="{{ !empty($news->image) ? asset($news->image) : asset(Storage::url($settings['company_logo'])) }}"/>
    <meta property="og:url" content="{{ !empty($news->image) ? asset($news->image) : asset(Storage::url($settings['company_logo'])) }}"/>
   <meta property="og:image:type" content="image/jpg"/>
   <meta property="og:image:width" content="800"/>
   <meta property="og:image:height" content="1200"/>
@endsection
@section('style')
    <link rel="stylesheet" href="{{ url('audio/audioplayer.css') }}">
    <style>
        .article-content  img, iframe {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 100%;
            height: auto;
        }

    </style>
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
                    "@id": "{{ url(optional($category->parent)->slug . '/' . $category->slug) }}",
                    "name": "{{ $category->title }}"
                }
            },
            {
                "@type": "ListItem",
                "position": 3,
                "item": {
                    "@id": "{{ Request::fullUrl() }}",
                    "name": "{{ $news->title }}"
                }
            }
        ]
    }

    </script>
@endsection

@section('content')
   <div class="container mt-4">
       <div class="row">
            {!! $settings['google_mgi_title'] !!}
           <div class="col-12 col-lg-9">
               <h5 class="news__title--lg text-uppercase">{{ $news->title }}</h5>
               @if($news->file != null)
               <audio preload="auto" controls>
                   <source src="{{ url($news->file) }}">
               </audio>
               @endif
               <div class="news__detail--title">
                   <a class="item-link" href="{{ url(optional($news->category)->slug) }}">{{ optional($news->category)->title }}</a>
                   <span>
                        &nbsp;<i class="far fa-calendar-alt" aria-hidden="true"></i>&nbsp;{{ Carbon\Carbon::parse($news->updated_at)->format(config('settings.format.date')) }}
                    </span>
                   <span>&nbsp;<i class="fa fa-eye"></i>&nbsp;{{ $news->view }}</span>
               </div>
               <div>
                   @if(!empty($news->description))
                       <p class="article-summary">
                           <i>{!! $news->description !!}</i>
                       </p>
                   @endif
                    {!! $settings['google_mgi_body'] !!}
                  
                   <div class="article-content">
                       {!! \App\News::insertAds($news->content) !!}
                       <div class="social-media"
                            data-permalink="{{ url('tin-tuc/'.$news->slug.".html") }}">
                           <span class="inline"><small>Chia sẻ:</small> </span>
                           <a target="_blank"
                              href="//www.facebook.com/sharer.php?u={{ url(optional($news->category)->slug.'/'.$news->slug.".html") }}"
                              class="share-facebook" title="Chia sẻ lên Facebook">
                               <i class="fab fa-facebook fa-2x"></i>
                           </a>
                       </div>
                   </div>

               </div>
           </div>
           @include('theme::front-end.news.sidebar')
       </div>
       <hr>
       @if($otherNews->count() > 0)
           @include('theme::front-end.news.other')
       @endif
      <!-- Composite Start --> 
      {!! str_replace('<br />','',$settings['google_mgi']) !!}
     <!-- Composite End -->
   </div>
@endsection
@section('script')
    <script type="text/javascript" src="{{ url('audio/audioplayer.js') }}"></script>
    <script type="text/javascript">
        $(function() {
            $('audio').audioPlayer();
        });
    </script>
@endsection