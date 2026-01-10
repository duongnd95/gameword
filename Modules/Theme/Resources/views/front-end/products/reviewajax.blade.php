@foreach($listReviews as $item)
    <div class="row content">
        <div class="col-sm-3 information-user">
            <div class="avatar">
                <span class="avatar-logo">{{ strtoupper(substr($item->name, 0, 1)) }}</span>
                <p class="name_user">{{ $item->name }}</p>
                <p class="date">{{ $item->created_at->diffForHumans() }}</p>
            </div>
        </div>
        <div class="col-sm-9 comment">
            <div class="information">
                <div class="rating">
                    <p class="star">
                        @for ($i = 0; $i < $item->rating; $i++)
                            <i class="fas fa-star"></i>
                        @endfor
                        <span>{{ $item->title }}</span>
                    </p>
                    <p class="buy-already"> {{ trans('theme::frontend.product.buy_product') . $settings['company_website'] }}</p>
                </div>
                <p class="review-content">{{ $item->review }}</p>
            </div>
        </div>
    </div>
@endforeach
<div class="pagination d-flex justify-content-end">
    {!! $listReviews->appends(\Request::except('page'))->render() !!}
</div>