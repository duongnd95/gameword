@extends('theme::front-end.master')
@section('title')
    <title>{{ $detail_product->name }}</title>
    <meta name="keywords" content="{{ !empty($detail_product->keywords) ? $detail_product->keywords : $settings['meta_keyword'] }}" />
    <meta name="description" content="{{ !empty($detail_product->description) ? \Illuminate\Support\Str::limit($detail_product->description, 200) : $settings['meta_description'] }}"/>
@endsection
@section('facebook')
    <meta property="og:title" content="{{ $detail_product->name }}" />
    <meta property="og:description" content="{{ !empty($detail_product->description) ? $detail_product->description : !empty($settings['meta_description']) ? $settings['meta_description'] : trans('frontend.description') }}" />
    <meta property="og:image" content="{{ !empty($detail_product->image) ? asset($detail_product->image) : asset(Storage::url($settings['company_logo'])) }}" />
    <meta property="og:image:type" content="image/jpeg" />
    <meta property="og:image:width" content="600" />
    <meta property="og:image:height" content="315" />
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
            "@id": "{{ url('/san-pham/' . $category->slug) }}",
            "name": "{{ $category->name }}"
            }
          },
          {
           "@type": "ListItem",
          "position": 3,
          "item":
           {
             "@id": "{{ Request::fullUrl() }}",
             "name": "{{ $detail_product->name }}"
           }
          }
         ]
        }
    </script>
@endsection
@section('breadcrumb')
<div class="breadcrumb breadcrumb-fixed justify-content-center">
    <a href="{{ url('/')}}">{{ trans('theme::frontend.home') }}</a>
    <i class="fas fa-long-arrow-alt-right" aria-hidden="true"></i>
    <a href="{{ url('/san-pham/' . $category->slug) }}">{{ $category->name }}</a>
    <i class="fas fa-long-arrow-alt-right" aria-hidden="true"></i> <span>{{ $detail_product->name }}</span>
</div>
@endsection
@section('schema')
@section('style')
<link rel="stylesheet" href="{{ asset('css/bootstrap-rating.css') }}">
<link rel="stylesheet" href="{{ asset('css/xzoom.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/fancybox/source/jquery.fancybox.css') }}">
<style>
    .xzoom-thumbs {
        display: flex;
    }

    .xzoom-gallery4 {
        height: 80px;
        object-fit: cover;
        margin: 10px 5px;
    }
</style>
@endsection
@section('content')
<div class="container article list-product">
    <div class="row">
        <div class="col-md-5">
            <div class="row zoom-product">
                <div class="large-5 column">
                    <div class="xzoom-container">
                        <img class="xzoom4" id="xzoom-fancy" src="{{ asset($detail_product->image) }}"
                            xoriginal="{{ asset($detail_product->image) }}" />
                        <div class="xzoom-thumbs owl-carousel">
                            <a href="{{ asset($detail_product->image) }}">
                                <img class="xzoom-gallery4" width="80" src="{{ asset($detail_product->image) }}"
                                    xpreview="{{ asset($detail_product->image) }}" />
                            </a>
                            @foreach($imagesGalleries as $item)
                            <a href="{{ asset($item->image) }}">
                                <img class="xzoom-gallery4" width="80" src="{{ asset( $item->image) }}" />
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="large-7 column"></div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="detail-info feature-info">
                <h1 class="title-h2 title-font pt-30 pb-30">{{ $detail_product->name }}</h1>
                <p class="product-price feature-price">{{ number_format($detail_product->price) }}
                    {{ trans('theme::frontend.unit') }}</p>
                <div class="product-desc">
                    <p class="desc">{{ nl2br($detail_product->description) }}</p>
                </div>
                <div class="qty-cart">
                    <div class="item-qty">
                        <label>Số lượng:</label>
                        <button type="button" class="qty-down qty-btn" onclick="qtyDown(this)">-</button>
                        <input type="text" id="qty" name="quantity" class="form-control input-qty" 
                            value="1" min="1" max="100" onchange="validInputQty(this)"    
                            onkeypress="if(isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;">
                        <button type="button" class="qty-up qty-btn" onclick="qtyUp(this)">+</button>

                    </div>
                    <button name="add-many-cart" data-id="{{ $detail_product->id }}" type="button" class="btn-add-cart">
                        <img src="{{ url('images/add-cart.png') }}" alt="add-cart" class="img-add-cart">
                        {{ trans('theme::frontend.cart.add_cart') }}
                    </button>
                </div>
                <div class="fb-like mt-2"  data-width="100" data-layout="button_count" data-action="like" data-size="large" data-share="true"></div>
              
            </div>
        </div>
    </div>
    <hr />
    <ul class="tabs-product nav nav-tabs justify-content-center scrollbar" id="myTab" role="tablist">
        <li class="nav-item ">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#description" role="tab"
                aria-controls="home" aria-selected="true">
                {{ trans('theme::frontend.product.description') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="home-tab" data-toggle="tab" href="#review" role="tab" aria-controls="home"
                aria-selected="true">
                {{ trans('theme::frontend.product.review') }}
            </a>
        </li>
    </ul>
    <div class="content-product tab-content list-product article-detail">
        <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="home-tab">
            {!! $detail_product->content !!}
        </div>
        <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="home-tab">
            @include('theme::front-end.products.review')
        </div>
    </div>
    @include('theme::front-end.products.quickbuy')
    @if($otherProducts->count() > 0)
    @include('theme::front-end.products.other')
    @endif
</div>
@endsection
@section('script')
<script type="text/javascript" src="{{ asset('js/bootstrap-rating.min.js') }}"></script>
<!-- XZOOM JQUERY PLUGIN  -->
<script type="text/javascript" src="{{ asset('js/xzoom.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/foundation.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/setup.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/fancybox/source/jquery.fancybox.js') }}"></script>
<script>
    //Rating
    $(function () {
        $('.xzoom-thumbs').owlCarousel({
            items: 5,
            loop: false,
            autoplay: false,
            autoplayTimeout: 4000,
            margin: 5,
            nav: true,
            dots: false,
        });
        $('#btn-review').on('click', function () {
            if ($(this).hasClass('btn-text')) {
                $(this).text('Đóng');
                $(this).removeClass('btn-text');
            } else {
                $(this).text('Viết nhận xét của bạn');
                $(this).addClass('btn-text');
            }
            $('#review-form').fadeToggle();
        });
        $('.rating-tooltip').rating();
        $('#review').submit(function (e) {
            e.preventDefault();
            //Get data 
            var product_id = $('[name="product_id"]').val();
            var name = $('[name="name"]').val();
            var email = $('[name="email"]').val();
            var rating = $('[name="rating"]').val();
            var review = $('[name="review"]').val();

            $.ajax({
                url: "{{ url('review/ajax') }}",
                type: "POST",
                data: {
                    product_id: product_id,
                    name: name,
                    email: email,
                    rating: rating,
                    review: review,
                },
                beforeSend: function (xhr) { // Add this line
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_token"]').val());
                },
                success: function (response) {
                    console.log(response);
                    const data = response;
                    if (response.success == "ok") {
                        $('.review-form').hide();
                        $('.hs-form-success').show();
                    } else {
                        console.log(data.errors);
                    }
                }
            });
        });
    })
</script>

<div id="fb-root"></div>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v6.0&appId=561886010884985&autoLogAppEvents=1"></script>
<script type="text/javascript">
    
    //Add many cart
    $(".btn-add-cart").click(function (e) {
        e.preventDefault();
        $('.img-add-cart').replaceWith('<i class="fas fa-spinner fa-pulse"></i>');
        var id = $(this).attr("data-id");
        var qty = $("input[name='quantity']").val();

        $.ajax({
            url: "{{ url('add-many-cart') }}",
            type: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
                qty: qty
            },
            success: function (res) {
                //console.log(res);
                $("html, body").animate({scrollTop: 0}, "slow");
                $('.fa-spinner').replaceWith('<img src="{{ url('images/add-cart.png') }}" alt="add-cart" class="img-add-cart">');
                $(".add-cart-success").css("display", "block").delay(2000).fadeOut('slow');
                $('.cart-quantity').html(res.data);
            }

        });
    });

    function validInputQty(ob) {
        if (ob.value == 0) ob.value = 1;
    }

    function qtyDown(ob) {
        var result = document.getElementById('qty');
        var qty = result.value;
        if (!isNaN(qty) && qty > 1)
            result.value--;
        return false;
    }

    function qtyUp(ob) {
        var result = document.getElementById('qty');
        var qty = result.value;
        if (!isNaN(qty))
            result.value++;
        return false;
    }
    
    
     //Update Qty
     function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    }
    
     function updateQty(ob,id,price){
        var qty = ob.value;
        if(qty < 1) return alert('Vui lòng nhập số lượng lớn hơn 0!');
        changeQtyPrice(id,qty,price);
    }

    function changeQtyPrice(id,qty,price){
        var totalPrice = qty*price;
        $('#total-js').html(formatNumber(totalPrice) + '&nbsp;₫');
        $( "input[name='totalPrice']").val(totalPrice);
        $( "input[name='qty']").val(qty);
    }
    function qtyBuyDown(ob,id,price){
        var result = document.getElementById('qty-quick-buy'); 
        var qtyqv = result.value; 
        if( !isNaN( qtyqv ) && qtyqv > 1 ) 
        result.value--;
        var qty = result.value;
        changeQtyPrice(id,qty,price);
    }
    function qtyBuyUp(ob,id,price){
        var result = document.getElementById('qty-quick-buy'); 
        var qtyqv = result.value; 
        if( !isNaN( qtyqv )) 
        result.value++;
        var qty = result.value;
        changeQtyPrice(id,qty,price);
    }
    //Quick Buy Ajax
    $("#quick-buy-form").submit(function (e) {
        e.preventDefault();
        var product_id = $("input[name='product_id']").val();
        var qty = $("input[name='qty']").val();
        var price = $("input[name='price']").val();
        var totalPrice = $("input[name='totalPrice']").val();
        var name = $("input[name='customer[name]']").val();
        var phone = $("input[name='customer[phone]']").val();
        var email = $("input[name='customer[email]']").val();
        var address = $("input[name='customer[address]']").val();
        var note = $("textarea[name='note']").val();

        $.ajax({
            url: "{{ url('submit-quick-buy/product') }}",
            type: "POST",
            dataType: 'JSON',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: product_id,
                qty: qty,
                price: price,
                totalPrice: totalPrice,
                name: name,
                phone: phone,
                email: email,
                address: address,
                note: note,
            },
            success: function (res) {
                //console.log(res.data.code);
                if (res.success == "ok"){
                    $('#modalSuccess').modal('show');
                    $("#quick-buy-form")[0].reset();
                    $("#qty-quick-buy").val(1);
                    $("span#total-js").html(formatNumber($("input[name='price']").val()) + '&nbsp;₫');
                    $("#code").html(res.data.code);
                    clearErrors();
                }else{
                        let err = res.errors;
                        //console.log(err);
                        $.each(err, function(key, value){
                            $("#buy-" + key).html(value[0]);

                        });
                }
            },
            error: function(res){
                alert("{{ __('frontend.error') }}");
            }
        });
    });
    function clearErrors() {
        // remove all error messages
        const errorMessages = document.querySelectorAll('small.text-danger')
        errorMessages.forEach((element) => element.textContent = '')
        // remove all form controls with highlighted error text box
        const formControls = document.querySelectorAll('.form-control')
        formControls.forEach((element) => element.classList.remove('border', 'border-danger'))
    }

</script>

@endsection