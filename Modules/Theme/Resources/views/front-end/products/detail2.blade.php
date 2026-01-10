@extends('theme::front-end.master')
@section('title')
    <title>{{ $detail_product->name }}</title>
    <meta name="keywords"
          content="{{ !empty($detail_product->keywords) ? $detail_product->keywords : $settings['meta_keyword'] }}"/>
    <meta name="description"
          content="{{ !empty($detail_product->description) ? \Illuminate\Support\Str::limit($detail_product->description, 200) : $settings['meta_description'] }}"/>
@endsection
@section('facebook')
    <meta property="og:title" content="{{ $detail_product->name }}"/>
    <meta property="og:description"
          content="{{ !empty($detail_product->description) ? $detail_product->description : !empty($settings['meta_description']) ? $settings['meta_description'] : trans('frontend.description') }}"/>
    <meta property="og:image"
          content="{{ !empty($detail_product->image) ? asset($detail_product->image) : asset(Storage::url($settings['company_logo'])) }}"/>
    <meta property="og:image:type" content="image/jpeg"/>
    <meta property="og:image:width" content="600"/>
    <meta property="og:image:height" content="315"/>
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-rating.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery.fancybox.min.css') }}"/>
    <style>
        /*set a border on the images to prevent shifting*/
        #gallery img {
            border: 1px solid white;
        }

        .elevatezoom-gallery.active img {
            border: 1px solid #0fe2cf !important;
        }

        .page-link {
            border-radius: 100%;
            line-height: 1;
            margin: 2px;
        }

        .page-item.disabled .page-link {
            border-radius: 100%;
        }

        .page-item:last-child .page-link {
            border-radius: 100%;
        }

        .page-item:first-child .page-link {
            border-radius: 100%;
        }
    </style>
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
@section('content')
    <div class="row bg-white pt-2">
        <div class="col-lg-5">
            <div class="zoom-left col-lg-12 col-11">
                @if($imagesGalleries->count() > 0)
                    @php($thumbs = $image = '')
                    @for($i = 0; $i < $imagesGalleries->count(); $i++ )
                        @if($i == 0)
                            @php(
                            $image .= '<a href="'.$imagesGalleries[$i]['image'].'" class="fancybox" data-fancybox="gallery">'
                                        . '<img id="img_01" class="img-fluid" style="width: 100%; height: 400px;" src="'.$imagesGalleries[$i]['image'].'"
                                        data-zoom-image="'.$imagesGalleries[$i]['image'].'" alt="'.$detail_product->name.'"/>'
                                    .  '</a>')
                        @endif
                        @php(
                            $thumbs .= '<a href="'.$imagesGalleries[$i]['image'].'" class="elevatezoom-gallery fancybox" data-fancybox="gallery"
                                        data-image="'.$imagesGalleries[$i]['image'].'" data-zoom-image="'.$imagesGalleries[$i]['image'].'">'
                                        . '<img id="img_01" src="'.$imagesGalleries[$i]['image'].'" width="80" height="80"/>'
                                    .  '</a>')
                    @endfor
                    {!! $image !!}
                    <div class="text-photo text-center">
                        <small class="text-muted">
                            <i class="fas fa-search-plus"></i>&nbsp;Rê chuột lên hình ảnh để phóng to
                        </small>
                    </div>
                    <div id="gallery" style="width: 100%;" class="xzoom-thumbs owl-carousel">
                        {!! $thumbs !!}
                    </div>
                @else
                    <img id="img_01" class="img-fluid" style="width: 100%; height: 400px;"
                         src="{{ asset($detail_product->image) }}" alt="{{ asset($detail_product->name) }}"
                         data-zoom-image="{{ asset($detail_product->image) }} "/>
                @endif
            </div>
            <div style="clear:both;"></div>
        </div>
        <div class="col-lg-7">
            <div class="detail-info feature-info">
                <h3 class="text-muted">{{ $detail_product->name }}</h3>
                <div class="rating">
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
                                <div class="rating-symbol" style="display: inline-block; position: relative;">
                                    <div class="rating-symbol-background far fa-star fas-2x"
                                         style="visibility: visible;"></div>
                                    <div class="rating-symbol-foreground"
                                         style="display: inline-block; position: absolute; overflow: hidden; left: 0px; right: 0px; width: {{$w}};">
                                        <span class="fas fa-star fas-2x"></span>
                                    </div>
                                </div>
                            @endfor
                            <span class="review-total text-primary" id="scroll-review" style="cursor: pointer;">
                                (Xem {{$listReviews->total()}} đánh giá)
                            </span>
                        </div>
                    @endif
                </div>
                @empty(!$detail_product->provider)
                    <div class="ranking">
                        <span>Thương hiệu:</span>
                        <span class="text-primary">{{ $detail_product->provider ? $detail_product->provider->name : '' }}</span>
                    </div>
                @endempty
                <p class="fb-like" data-href="{{ Request::fullUrl() }}" data-width="" data-layout="button_count"
                   data-action="like" data-size="small" data-share="true"></p>
                <hr>
                <p>
                    <span class="text-muted">Giá:</span>
                    <span class="text-danger"
                          style="font-weight: bold;">{{ number_format($detail_product->price) }}{{ trans('theme::frontend.unit') }}</span>
                </p>
                <p>
                    <span class="text-muted">Tiết kiệm:</span>
                    @if(isset($detail_product->price_compare))
                        <span class="text-danger" style="font-weight: bold;">
                                {{ round((($detail_product->price_compare - $detail_product->price)/$detail_product->price_compare)*100, 2) }}%
                            </span>
                        <span class="text-muted">
                            ({{ number_format($detail_product->price_compare - $detail_product->price) }}{{ trans('theme::frontend.unit') }})
                            </span>
                    @endif
                </p>
                <p>
                    <span class="text-muted">Giá thị trường:</span>
                    @if(isset($detail_product->price_compare))
                        <span class="price_compare text-muted">
                            {{ number_format($detail_product->price_compare) }}{{ trans('theme::frontend.unit') }}
                        </span>
                    @endif
                </p>
                <hr>
                <div class="product-desc">
                    @if(isset($detail_product->description))
                        <p>{!! nl2br($detail_product->description) !!}</p>
                        <hr>
                    @endif
                </div>

                <div class="qty-cart">
                    <small>Số lượng:</small>
                    <div class="item-qty">
                        <button type="button" class="qty-down qty-btn" onclick="changeQuantity(this, -1, 'qty')">-</button>
                        <input type="text" id="qty" name="quantity" class="form-control input-qty"
                               value="1" min="1" max="100" onchange="validInputQty(this)"
                               onkeypress="if(isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;">
                        <button type="button" class="qty-up qty-btn" onclick="changeQuantity(this, +1, 'qty')">+</button>
                        <button name="add-many-cart" data-id="{{ $detail_product->id }}" type="button"
                                class="btn-add-cart">
                            <span> {{ trans('theme::frontend.cart.add_cart') }}</span>
                        </button>
                        <a href="javascript:" class="add-to-wishlist pl-2" >
                                    <span class="icon-heart" data-toggle="tooltip" data-placement="top"
                                          title="Thêm vào yêu thích">
                                        <i class="far fa-heart" style="font-size:25px;"></i>
                                    </span>
                        </a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="alert alert-danger ml-2" role="alert" style="background-color: #f5eced; display: flex;">
                <div class="icon-alert pr-2">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div class="info-alert">
                    Sản phẩm chỉ được giao ở TP.Huế.
                    Bạn hãy chọn địa chỉ nhận hàng để được dự báo thời gian nhận hàng
                    một cách chính xác nhất.<br>
                    <a href="javascript:void(0)" class="alert-link text-primary">Nhập địa chỉ</a>
                </div>
            </div>
        </div>
    </div>
    <div class="product-other product-view">
        <div class="header-other">
            <h3 class="home-title title-h2 mb-15 mt-15 text-uppercase"><span>Sản phẩm xem cùng</span></h3>
        </div>
        @if($otherProducts->count() > 0)
            @include('theme::front-end.products.other')
        @endif
    </div>
    @empty(!optional($detail_product->provider)->name && !optional($detail_product->provider)->origin && !optional($detail_product->provider)->production &&
        !($detail_product->model) && !($detail_product->sku) && !($detail_product->expiry_date) && !($detail_product->preservation))
    <div class="product-other product-detail">
        <div class="header-other">
            <h3 class="home-title title-h2 mb-15 mt-15 text-uppercase"><span>Chi tiết sản phẩm</span></h3>
        </div>
        <div class="table-detail-product bg-white" style="padding: 20px;">
            <table class="table table-bordered table-detail">
                <tbody>
                    @empty(!optional($detail_product->provider)->name)
                    <tr>
                        <td class="last">Thương hiệu</td>
                        <td>{{ $detail_product->provider ? $detail_product->provider->name : '' }}</td>
                    </tr>
                    @endempty
                    @empty(!optional($detail_product->provider)->origin)
                    <tr>
                        <td class="last">Xuất xứ thương hiệu</td>
                        <td>{{ $detail_product->provider ? $detail_product->provider->origin : '' }}</td>
                    </tr>
                    @endempty
                    @empty(!optional($detail_product->provider)->production)
                    <tr>
                        <td class="last">Nơi sản xuất</td>
                        <td>{{ $detail_product->provider ? $detail_product->provider->production : '' }}</td>
                    </tr>
                    @endempty
                    @empty(!$detail_product->model)
                    <tr>
                        <td class="last">Model</td>
                        <td>{{ $detail_product->model }}</td>
                    </tr>
                    @endempty
                    @empty(!$detail_product->expiry_date)
                    <tr>
                        <td class="last">Hạn sử dụng</td>
                        <td>{{ $detail_product->expiry_date }}</td>
                    </tr>
                    @endempty
                    @empty(!$detail_product->preservation)
                    <tr>
                        <td class="last">Hướng dẫn bảo quản</td>
                        <td>{{ $detail_product->preservation }}</td>
                    </tr>
                    @endempty
                    @empty(!$detail_product->sku)
                    <tr>
                        <td class="last">SKU</td>
                        <td>{{ $detail_product->sku }}</td>
                    </tr>
                    @endempty
                </tbody>
            </table>
        </div>
    </div>
    @endempty
    <div class="product-other product-desc">
        <div class="header-other">
            <h3 class="home-title title-h2 mb-15 mt-15 text-uppercase"><span>Mô tả sản phẩm</span></h3>
        </div>
        <div class="product-content px-4 pt-4 bg-white" style="height: 300px; overflow: hidden;">
            {!! $detail_product->content !!}
        </div>
        <div class="text-center bg-white pb-2">
            <button class="btn btn-sm btn-outline-info btn-read-more">Xem thêm</button>
        </div>
    </div>
    <div class="product-other product-quickbuy">
        <div class="header-other">
            <h3 class="home-title title-h2 mb-15 mt-15 text-uppercase"><span>Đặt hàng nhanh</span></h3>
        </div>
        @include('theme::front-end.products.quickbuy')
    </div>
    <div class="product-other product-review">
        <div class="header-other">
            <h3 class="home-title title-h2 mb-15 mt-15 text-uppercase"><span>Khách hàng nhận xét</span></h3>
        </div>
        <div class="customer-reviews bg-white pt-3">
            @include('theme::front-end.products.review')
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript" src="{{ asset('js/bootstrap-rating.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.ez-plus.min.js') }}"></script>
    <script src="{{ asset('js/jquery.fancybox.min.js') }}"></script>
    <script>
        $('.fancybox').fancybox({
            loop: true,
            protect: true,
            animationEffect: "zoom-in-out",
            transitionEffect: "zoom-in-out",
            slideShow: {
                autoStart: true,
                speed: 3000
            },
            arrows: true,
            buttons: [
                "zoom",
                //"share",
                "slideShow",
                "fullScreen",
                "download",
                "thumbs",
                "close"
            ],
        });
    </script>
    <script>
        $('[data-toggle="tooltip"]').tooltip();
        $('#img_01').ezPlus({
            gallery: 'gallery', cursor: 'pointer', galleryActiveClass: 'active',
            imageCrossfade: true, zoomWindowOffsetX: 1, borderSize: 1, lenszoom: true,
        });
        $(function () {
            $('.xzoom-thumbs').owlCarousel({
                items: 5,
                loop: false,
                autoplay: false,
                autoplayTimeout: 4000,
                responsive: {
                    0: {
                        items: 3,
                        nav: false,
                    },
                    600: {
                        items: 4,
                    },
                    1000: {
                        items: 5,
                    }
                }
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
            $('#review-js').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ url('review/ajax') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function (response) {
                        const data = response;
                        if (response.success == "ok") {
                            $('#review').trigger("reset");
                            $('.review-form').hide();
                            $('.hs-form-success').show();
                        } else {
                            let errors = data.errors;
                            if ($('#name').val() != '') {
                                $('#name').removeClass('border-danger');
                            }
                            if ($('#review').val() != '') {
                                $('#review').removeClass('border-danger');
                            }
                            $.each(errors, (key, index) => {
                                $('#' + key).addClass('border-danger');
                            })
                        }
                    }
                });
            });
        })
    </script>
    <script type="text/javascript">
        $(".btn-add-cart").click(function (e) {
            e.preventDefault();
            $('.btn-add-cart span').replaceWith('<i class="fas fa-spinner fa-pulse"></i>');
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
                    $("html, body").animate({scrollTop: 0}, "slow");
                    $('.btn-add-cart .fa-spinner').replaceWith('<span>Chọn mua</span>');
                    $(".add-cart-success").css("display", "block").delay(2000).fadeOut('slow');
                    $('.cart-quantity').html(res.data);
                }
            });
        });
        function validInputQty(ob) {
            if (ob.value == 0) ob.value = 1;
        }
        function changeQuantity(ob, val, el, price){
            let result = document.getElementById(el);
            let qty = parseInt(result.value);
            if (!isNaN(qty)) result.value = qty+ (val);
            if (result.value < 1) result.value = 1;
            if (price != '' && el === 'qty-quick-buy') {
                let id = $(ob).data('id');
                changeQtyPrice(id, result.value, price);
            }
        }
        function updateQty(ob, id, price) {
            var qty = ob.value;
            if (qty < 1) return alert('Vui lòng nhập số lượng lớn hơn 0!');
            changeQtyPrice(id, qty, price);
        }

        function changeQtyPrice(id, qty, price) {
            var totalPrice = qty * price;
            $('#total-js').html(formatNumber(totalPrice) + '&nbsp;₫');
            $("input[name='totalPrice']").val(totalPrice);
            $("input[name='qty']").val(qty);
        }
        //Quick Buy Ajax
        $("#quick-buy-form").submit(function (e) {
                e.preventDefault();
                $('.btn-quick-buy span').html('<i class="fas fa-spinner fa-pulse"></i>');
                $.ajax({
                    url: "{{ url('submit-quick-buy/product') }}",
                    type: "POST",
                    dataType: 'JSON',
                    data: {
                        _token: $("input[name='_token']").val(),
                        product_id: $("input[name='product_id']").val(),
                        qty: $("input[name='qty']").val(),
                        price: $("input[name='price']").val(),
                        totalPrice: $("input[name='totalPrice']").val(),
                        name: $("input[name='customer[name]']").val(),
                        phone: $("input[name='customer[phone]']").val(),
                        email: $("input[name='customer[email]']").val(),
                        address: $("input[name='customer[address]']").val(),
                        note: $("textarea[name='note']").val(),
                    },
                    success: function (res) {
                        $('.btn-quick-buy .fa-spinner').replaceWith('<span>ĐẶT HÀNG NGAY</span>');
                        if (res.success == "ok") {
                            $('#modalSuccess').modal('show');
                            $("#quick-buy-form")[0].reset();
                            $("#qty-quick-buy").val(1);
                            $("span#total-js").html(formatNumber($("input[name='price']").val()) + '&nbsp;₫');
                            let code = '#'+res.data;
                            $("#code").html(code);
                        } else {
                            clearErrors();
                            if(res.errors) {
                                let err = res.errors;
                                $.each(err, function (key, value) {
                                    $("#buy-" + key).html(value[0]);
                                });
                                return true;
                            }
                        }
                    },
                    error: function (res) {
                        alert("{{ __('frontend.error') }}");
                    }
                });
            });
        function clearErrors() {
            const errorMessages = document.querySelectorAll('small.error');
            errorMessages.forEach((element) => element.textContent = '')
        }

        //Scroll to review
        $('#scroll-review').click(function () {
            $('html, body').animate({
                scrollTop: $("div.product-review").offset().top
            })
        });
        $('.alert-link').click(function () {
            $('html, body').animate({
                scrollTop: $("div.product-quickbuy").offset().top
            })
        });
        //Reviews Ajax Pagination
        $('#list-reviews').on('click', 'a.page-link', function (e) {
            e.preventDefault();
            let id = $('input[name="product_id"]').val();
            let page = $(this).attr('href').split('page=')[1];
            $('#list-reviews').append('<div class="text-center loading" ><i class="fa fa-spinner fa-pulse fa-2x fa-fw text-primary"></i></div>');
            $('html, body').animate({
                scrollTop: $("div#list-reviews").offset().top
            })
            if (!page) return page = 1;
            $.ajax({
                type: 'GET',
                url: '{{ url('/review/ajax/reviewAjax') }}',
                data: {
                    id: id,
                    page: page
                },
                success: function (res) {
                    //console.log(res);
                    $('#list-reviews').html(res);
                },
                error: function () {
                    alert('Có lỗi xảy ra. Vui lòng thử lại!');
                }
            })
        })

        //Read More Content
        $(document).ready(function () {
            $(".btn-read-more").click(function () {
                $('html,body').animate({
                    scrollTop: $('div.product-content').offset().top
                }, 'slow');
                var ele = $(".btn-read-more").text();
                if (ele == "Xem thêm") {
                    //Stuff to do when btn is in the read more state
                    $(".btn-read-more").text("Thu gọn");
                    $(".product-content").animate({
                        height: '100%',
                    }, "slow");
                } else {
                    //Stuff to do when btn is in the read less state
                    $(".btn-read-more").text("Xem thêm");
                    $(".product-content").animate({
                        height: 300,
                    }, "slow");
                }
            });
        });
    </script>
@endsection