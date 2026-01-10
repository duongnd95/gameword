<div class="article-other bg-white">
{{--    <h4 class="other-title title-font text-center text-uppercase">{{ trans('theme::frontend.product.other') }}</h4>--}}
    <div class="featured-slider owl-carousel">
        @foreach($otherProducts as $item)
            <div class="feature-item">
                <a href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html" class="image-responsive image-responsive--xl">
                    <img class="image-responsive--lg owl-lazy" data-src="{{ asset($item->image) }}" alt="{{ $item->name }}"/>
                </a>
                <div class="feature-info">
                    <h3 class="feature-title" title="{{ $item->name }}">
                        <a href="{{ url(optional($item->category)->slug . '/' .$item->slug) }}.html">{{ $item->name }}</a>
                    </h3>
                    <span class="feature-price">{{ number_format($item->price) }} {{ trans('theme::frontend.unit') }}</span>
                    @empty(!$item->price_compare)
                        <small>-{{ round((($item->price_compare - $item->price)/$item->price_compare)*100, 2) }}%</small>
                    @endempty
                    <br>
                    {!! !empty($item->price_compare) ? '<span style="text-decoration: line-through; color: #333;"><small style="font-weight: 600;">' . number_format($item->price_compare) . trans('theme::frontend.unit').'</small></span> ' : '' !!}
                </div>
            </div>
        @endforeach
    </div>
</div>