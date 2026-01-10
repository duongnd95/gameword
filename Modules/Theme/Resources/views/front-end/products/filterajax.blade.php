<div class="row">
    @foreach($products as $item)
        <div class="col-md-4 col-lg-3 mb-20">
            <div class="item-product">
                <div class="product-thumb image-responsive">
                    @empty(!$item->price_compare)
                        <span class="product-compare">-{{ round((($item->price_compare - $item->price)/$item->price_compare)*100) }}%</span>
                    @endempty
                    <img data-src="{{ $item->image }}" alt="{{ $item->image }}"
                         class="image-responsive--lg lazyload">
                </div>
                <div class="product-info">
                    <h3 class="title14 product-title">
                        <a href="{{ url(optional($item->category)->slug . '/' . $item->slug) }}.html"
                           title="{{ $item->name }}">{{ $item->name }}</a>
                    </h3>
                    <div class="rating-product">
                        @php($average = Modules\Review\Entities\Review::with('product')->where(['product_id' => $item->id, 'active' => config('settings.active')])->avg('rating'))
                        @if(!empty($average))
                            @php($average = number_format($average,1))
                            @php($arr = explode('.',$average))
                            <div class="review-star">
                                @for($i = 0; $i < 5; $i++)
                                    @php($w = '0%')
                                    @if($i < $arr[0])
                                        @php($w = 'auto')
                                    @elseif($i == $arr[0] && '0.'.$arr[1])
                                        @php($w = ('0.'.$arr[1])*100..'%')
                                    @endif
                                    <div class="rating-symbol"
                                         style="display: inline-block; position: relative;">
                                        <div class="rating-symbol-background far fa-star"
                                             style="visibility: visible;"></div>
                                        <div class="rating-symbol-foreground"
                                             style="display: inline-block; position: absolute; overflow: hidden; left: 0px; right: 0px; width: {{$w}};">
                                            <span class="fas fa-star "></span>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        @else
                            <div class="review-star">
                                <div class="rating-symbol"
                                     style="display: inline-block; position: relative;">
                                    <div class="rating-symbol-background far fa-star"
                                         style="visibility: visible;"></div>
                                    <div class="rating-symbol-foreground"
                                         style="display: inline-block; position: absolute; overflow: hidden; left: 0px; right: 0px; width: auto;">
                                        <span class="fas fa-star "></span>
                                    </div>
                                </div>
                                <div class="rating-symbol"
                                     style="display: inline-block; position: relative;">
                                    <div class="rating-symbol-background far fa-star"
                                         style="visibility: visible;"></div>
                                    <div class="rating-symbol-foreground"
                                         style="display: inline-block; position: absolute; overflow: hidden; left: 0px; right: 0px; width: auto;">
                                        <span class="fas fa-star "></span>
                                    </div>
                                </div>
                                <div class="rating-symbol"
                                     style="display: inline-block; position: relative;">
                                    <div class="rating-symbol-background far fa-star"
                                         style="visibility: visible;"></div>
                                    <div class="rating-symbol-foreground"
                                         style="display: inline-block; position: absolute; overflow: hidden; left: 0px; right: 0px; width: auto;">
                                        <span class="fas fa-star "></span>
                                    </div>
                                </div>
                                <div class="rating-symbol"
                                     style="display: inline-block; position: relative;">
                                    <div class="rating-symbol-background far fa-star"
                                         style="visibility: visible;"></div>
                                    <div class="rating-symbol-foreground"
                                         style="display: inline-block; position: absolute; overflow: hidden; left: 0px; right: 0px; width: auto;">
                                        <span class="fas fa-star "></span>
                                    </div>
                                </div>
                                <div class="rating-symbol"
                                     style="display: inline-block; position: relative;">
                                    <div class="rating-symbol-background far fa-star"
                                         style="visibility: visible;"></div>
                                    <div class="rating-symbol-foreground"
                                         style="display: inline-block; position: absolute; overflow: hidden; left: 0px; right: 0px; width: auto;">
                                        <span class="fas fa-star "></span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="product-price">
                        {!! !empty($item->price_compare) ? '<span style="text-decoration: line-through; color: #333;"><small style="font-weight: 600;">' . number_format($item->price_compare) . trans('theme::frontend.unit').'</small></span> ' : '' !!}
                        <span class="mount">{{ number_format($item->price) }}{{ trans('theme::frontend.unit') }}</span>
                    </div>
                </div>
            </div>
            <div class="btn-add-cart">
                <button type="button" data-id="{{ $item->id }}" class="btn-cart add_cart" id="add_cart">
                    {{ trans('theme::frontend.product.add_cart') }}
                </button>
            </div>
        </div>
    @endforeach
</div>
<div class="pagination-fixed d-flex justify-content-center mt-15" id="pagination-filter">
    {!! $products->appends(\Request::except('page'))->render() !!}
</div>






