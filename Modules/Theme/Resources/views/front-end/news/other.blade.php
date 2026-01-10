<div class="news__other row">
   <div class="col-12 col-md-9">
       <h5 class="contact__title news__other--title text-left text-uppercase mb-5">{{ trans('theme::frontend.other_news') }}</h5>
       <div class="row">
           @foreach($otherNews as $item)
               <div class="col-12 col-lg-4">
                   <a href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html"
                      class="image-responsive">
                       <img class="image-responsive--lg img-fluid lazyload"
                            data-src="{{ !empty($item->image)?asset($item->image):asset('/images/no_image.jpg') }}"
                            alt="{{ $item->title }}">
                   </a>
                   <div class="item-body">
                       <h5 class="news__title--lg">
                           <a href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html"
                              class="news__title--lg">{{ \Illuminate\Support\Str::limit($item->title, 70) }}</a>
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
   </div>
</div>