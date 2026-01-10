<div class="row text-center review-info">
    <div class="col-lg-3 col-sm-6">
        <p class="review-title">Đánh giá trung bình</p>
        @if(!empty($average))
            @php($average = number_format($average,1))
            <p class="review-total-star">{{ $average }}/5</p>
            @php($arr = explode('.',$average))
            <div class="review-star">
                @for($i = 0; $i < 5; $i++)
                    @php($w = '0%')
                    @if($i < $arr[0])
                        @php($w = 'auto')
                    @elseif($i == $arr[0] && '0.'.$arr[1])
                        @php($w = ('0.'.$arr[1])*100..'%')
                    @endif
                    <div class="rating-symbol" style="display: inline-block; position: relative;">
                        <div class="rating-symbol-background far fa-star fas-2x" style="visibility: visible;"></div>
                        <div class="rating-symbol-foreground" style="display: inline-block; position: absolute; overflow: hidden; left: 0px; right: 0px; width: {{ $w }};">
                            <span class="fas fa-star fas-2x"></span>
                        </div>
                    </div>
                @endfor

            </div>
        @endif
        <p class="review-total">({{ $listReviews->total() }} Nhận xét)</p>

    </div>
    <div class="col-lg-6 col-sm-6 rating">
        @empty(!$rating)
        <div class="rate-5 mb-2" style="display: flex;">
            <span class="rating-num">5&nbsp;</span>
            <span class="fas fa-star"></span>&nbsp;
            <div class="progress rounded-pill" style="width: 90%">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ round(count(array_keys($rating, 5))/count($rating)*100, 0) }}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="60"></div>
            </div>
            <span class="rating-num-total" style="width: 39px;">&nbsp;{{ round(count(array_keys($rating, 5))/count($rating)*100, 0) }}%</span>
        </div>
        <div class="rate-4 mb-2" style="display: flex;">
            <span class="rating-num">4&nbsp;</span>
            <span class="fas fa-star"></span>&nbsp;
            <div class="progress rounded-pill" style="width: 90%">
                <div class="progress-bar bg-success" role="progressbar" style="width:{{ round(count(array_keys($rating, 4))/count($rating)*100, 0) }}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="60"></div>
            </div>
            <span class="rating-num-total" style="width: 39px;">{{ round(count(array_keys($rating, 4))/count($rating)*100, 0) }}%</span>
        </div>
        <div class="rate-3 mb-2" style="display: flex;">
            <span class="rating-num">3&nbsp;</span>
            <span class="fas fa-star"></span>&nbsp;
            <div class="progress rounded-pill" style="width: 90%">
                <div class="progress-bar bg-success" role="progressbar" style="width:{{ round(count(array_keys($rating, 3))/count($rating)*100, 0) }}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="60"></div>
            </div>
            <span class="rating-num-total" style="width: 39px;">{{ round(count(array_keys($rating, 3))/count($rating)*100, 0) }}%</span>
        </div>
        <div class="rate-2 mb-2" style="display: flex;">
            <span class="rating-num">2&nbsp;</span>
            <span class="fas fa-star"></span>
            <div class="progress rounded-pill" style="width: 90%">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ round(count(array_keys($rating, 2))/count($rating)*100, 0) }}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="60"></div>
            </div>
            <span class="rating-num-total" style="width: 39px;">{{ round(count(array_keys($rating, 2))/count($rating)*100, 0) }}%</span>
        </div>
        <div class="rate-1 mb-2" style="display: flex;">
            <span class="rating-num">1&nbsp;</span>
            <span class="fas fa-star"></span>&nbsp;
            <div class="progress rounded-pill" style="width: 90%">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ round(count(array_keys($rating, 1))/count($rating)*100, 0) }}%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="60"></div>
            </div>
            <span class="rating-num-total" style="width: 39px;">&nbsp;{{ round(count(array_keys($rating, 1))/count($rating)*100, 0) }}%</span>
        </div>
        @else
            <p class="text-danger text-center">Không có đánh giá nào !</p>
        @endempty
    </div>
    <div class="col-lg-3 col-sm-12 d-flex justify-content-center align-items-center">
        <div class="review-share">
            <p>Chia sẻ nhận xét về sản phẩm</p>
            <button type="button" class="btn btn-sm intro-btn review-btn btn-text" id="btn-review">
                Viết nhận xét của bạn
            </button>
        </div>
    </div>
</div>
<div id="review-form" class="review review-bb" style="display: none;">
    <h5 class="review-form--title text-center">Gửi nhận xét của bạn</h5>
    {!! Form::open(['method' => 'POST', 'url' => '', 'role' => 'review', 'id' => 'review-js']) !!}
    <input type="hidden" name="product_id" value="{{ $detail_product->id }}">
    <div class="hs-form-success text-danger text-center" style="display: none">
        <p>
            Cám ơn bạn đã tin tưởng sản phẩm của chúng tôi!<br/>
            Thông tin nhận xét của bạn đã gửi thành công!
        </p>
    </div>
    <div class="review-form col-md-8 mx-auto">
        <div class="row">
            <div class="form-group col-sm-6">
                {!! Form::text('name', null, ['class' => 'form-control form-control-sm border-red','placeholder' => 'Nhập họ tên của bạn', 'id' => 'name']) !!}
            </div>
            <div class="form-group col-sm-6">
                {!! Form::email('email', null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Nhập email của bạn (không bắt buộc)']) !!}
            </div>
            <div class="form-group col-12">
                {!! Form::text('title', null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Nhập tiêu đề nhận xét (không bắt buộc)']) !!}
            </div>
            <div class="form-group col-12">
                {!! Form::textarea('review', null, ['class' => 'form-control', 'cols' => '5',
                        'rows' =>'3', 'placeholder' => 'Nhận xét của bạn về sản phẩm này', 'id' => 'review']) !!}
            </div>
            <div class="form-group col-12 text-center">
                <input type="hidden" class="rating-tooltip" name="rating"
                       data-filled="fas fa-star fas-2x" data-empty="far fa-star fas-2x" id="rating"/>
            </div>
            <div class="d-flex justify-content-center col-12">
                {!! Form::submit( trans('theme::frontend.form.send_review'), ['class' => 'btn btn-sm btn-review intro-btn']) !!}
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
<div id="list-reviews" class="reviews">
    @include('theme::front-end.products.reviewajax')
</div>