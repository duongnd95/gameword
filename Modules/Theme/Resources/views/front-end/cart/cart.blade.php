@extends('theme::front-end.master')
@section('breadcrumb')
<div class="breadcrumb breadcrumb-fixed">
    <div class="col">
        <a href="{{ url('/')}}">{{ trans('theme::frontend.home') }}</a>
        /
        <span>{{ trans('theme::frontend.cart.title') }}</span>
    </div>
</div>
@endsection
@section('content')

<div class="cart-product">
    <div class="container article article-detail">
        <h1 class="title-h2 title-font text-center pt-30 pb-30 d-none">{{ trans('theme::frontend.cart.title')  }}</h1>
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        @if(Session::has('flash_message'))
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="fa fa-fw fa-check"></i> {{ Session::get('flash_message') }}
            <a href="{{ url('/') }}"><i class="fas fa-undo"></i> Trang chủ</a>
        </div>
        @endif
        @if(session('cart'))
        <div class="alert alert-success alert-delete"></div>
        <div class="cart-order">
            <div class="row">
                <div class="col-md-8">
                    <div class="table-responsive-xl border-box">
                        <table class="table table-hover">
                            <thead>
                            </thead>
                            <tbody>
                                @php($cart = session()->get('cart'))
                                @php($amount = 0)
                                @foreach($cart as $key => $item)
                                @php($amount += $item['quantity'])
                                @endforeach
                                <p class="title-number-cart">
                                    Bạn đang có <strong class="count-cart text-primary">{{ $amount }}</strong>
                                    sản phẩm trong giỏ hàng
                                </p>
                                <?php $total = 0 ?>
                                @foreach(session('cart') as $id => $details)
                                @php($total += $details['item']['price'] * $details['quantity'])
                                <tr id="result{{ $id }}">
                                    <td>
                                        <div class="media-line-item">
                                            <div class="media-left">
                                                <div class="item-img">
                                                    <img src="{{ $details['item']['image'] }}" class="img-responsive">
                                                </div>
                                            </div>
                                            <div class="media-right">
                                                <div class="item-info">
                                                    <h3 class="item--title">{{ $details['item']['name'] }}</h3>
                                                </div>
                                                <div class="item-qty">
                                                    <div class="qty quantity-partent">
                                                        <button type="button" class="qty-down qty-btn"
                                                            onclick="qtyDown(this, {{ $id }})">-</button>
                                                        <input type="text" data-id="{{ $id }}" value="{{ $details['quantity'] }}" 
                                                            class="form-control quantity update-cart input-number"   
                                                            min="1" onkeyup="updateCart(this,{{ $id }})" onchange="validInputQty(this)" 
                                                            onkeypress="if(isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;"
                                                            >
                                                        <button type="button" class="qty-up qty-btn"
                                                            onclick="qtyUp(this, {{ $id }})">+</button>
                                                    </div>
                                                </div>
                                                <div class="item-price">
                                                    <p>
                                                        <span>{{ number_format($details['item']['price']) }}₫</span>
                                                    </p>
                                                </div>

                                            </div>
                                            <div class="item-total-price">
                                                <div class="price">
                                                    <span class="text">Thành tiền:</span>
                                                    <span class="line-item-total itemPrice{{$id}}">
                                                        {{ number_format($details['item']['price'] * $details['quantity']) }}&nbsp;₫
                                                    </span>
                                                </div>
                                                <div class="remove">
                                                    <button class="btn btn-primary btn-sm remove-from-cart"
                                                        data-id="{{ $id }}">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                        <a href="{{ url('/san-pham') }}" class="btn-cart">
                            Tiếp tục mua hàng
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="btn-order border-box">
                        <div class="order-summary-block">
                            <h4 class="summary-title">Thông tin đơn hàng</h4>
                            <hr>                        
                            <div class="summary-total">
                                <p>Tạm tính
                                    (<strong class="count-cart">{{ $amount }}</strong> sản phẩm) 
                                    <span class="total tt">{{ number_format($total) }} ₫</span>
                                </p>
                            </div>
                            <hr>
                            <div class="summary-total pb-2">
                                <p>Tổng tiền: <span class="total">{{ number_format($total) }} ₫</span></p>
                                <small class="float-right">Đã bao gồm VAT(nếu có)</small>
                            </div>
                            <hr>
                            <div class="summary-action text-center">
                                <a href="{{ url('/dat-hang') }}" class="btn-cart">
                                    Tiến hành đặt hàng
                                </a>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="alert alert-warning text-center">
            Không có sản phẩm nào trong giỏ hàng của bạn!
            <a href="{{ url('/san-pham') }}" class="btn btn-warning btn-sm">
                Tiếp tục mua hàng
            </a>
        </div>
        @endif

    </div>
</div>
@endsection
@section('script')
<script type="text/javascript">

    function validInputQty(ob) {
        if (ob.value == 0) ob.value = 1;
    }

    function qtyDown(ob, id) {
        var qty = ob.nextElementSibling.value;
        qty--;
        document.querySelector('[data-id="' + id + '"]').value = qty;
        if(qty < 1){
            document.querySelector('[data-id="' + id + '"]').value = 1;
        }
        changeCartAjax(id, qty);
    }

    function qtyUp(ob, id) {
        var qty = ob.previousElementSibling.value;
        qty++;
        document.querySelector('[data-id="' + id + '"]').value = qty;
        changeCartAjax(id, qty);
    }
</script>
@endsection()