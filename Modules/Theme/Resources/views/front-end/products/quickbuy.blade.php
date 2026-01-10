<div class="form-quick-buy bg-white pb-2">
    <div class="row">
        <div class="col-md-6">
            <div class="infor-quickbuy">
                <div class="img-quickbuy">
                    <img class="img-fluid img-product" width="300px" height="250px" src="{{ asset($detail_product->image) }}" alt="Responsive image"/>
                    <div class="info-product">
                        <h5 class="title-font mt-1">{{ $detail_product->name }}</h5>
                        <div class="qty-cart quickbuy" style="display: inline-block">
                            <div class="item-qty">
                                <button type="button" data-id="{{ $detail_product->id }}" class="qty-down qty-btn" onclick="changeQuantity(this, -1, 'qty-quick-buy',{{ $detail_product->price}})">-</button>
                                <input type="text" id="qty-quick-buy" name="quantity" class="form-control input-qty"
                                       value="1" min="1" max="100" onkeypress="if ( isNaN(this.value + String.fromCharCode(event.keyCode) )) return false;"
                                       onchange="if(this.value == 0)this.value=1;"
                                       onkeyup="updateQty(this, {{ $detail_product->id }}, {{ $detail_product->price}} )"
                                       onclick="updateQty(this, {{ $detail_product->id }}, {{ $detail_product->price}})">
                                <button type="button" data-id="{{ $detail_product->id }}" class="qty-up qty-btn" onclick="changeQuantity(this, +1, 'qty-quick-buy',{{ $detail_product->price }})">+</button>
                            </div>
                        </div>
                        <p class="product-price mt-2">{{ number_format($detail_product->price) }}
                            {{ trans('theme::frontend.unit') }}</p>
                    </div>
                </div>

            </div>
            <div class="item-total-price mt-2">
                <div class="price">
                    <span class="text-danger">Thành tiền:</span>
                    <span id="total-js" class="product-price">
                        {{ number_format($detail_product['price']) }}&nbsp;₫
                    </span>
                </div>
            </div>
            <div class="alert alert-danger mt-2" role="alert">
                <small class="text-muted">Bạn vui lòng nhập đúng thông tin đơn hàng để chúng tôi sẽ gọi xác nhận đơn hàng trước khi giao hàng. Xin cảm ơn!</small>
            </div>
        </div>
        <div class="col-md-6 mx-auto">
            <form id="quick-buy-form" method="POST" action="">
                <legend class="text-danger text-center ">Thông tin đặt hàng</legend>
                <div class="row order-form" id="quick-buy-form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="product_id" value="{{ $detail_product['id'] }}">
                <input type="hidden" name="price" value="{{ $detail_product['price'] }}">
                <input type="hidden" name="totalPrice" value="{{ $detail_product['price'] }}">
                <input type="hidden" name="qty" value="1">
                <div class="form-group col-sm-6">
                    <input id="buy-js-name" type="text" class="form-control form-control-sm input-text {{ $errors->has('customer.name') ? 'border border-danger' : '' }}"
                           name="customer[name]" placeholder="Họ và tên" value="{{ old('customer.name') }}">
                    <small id="buy-name" class="error text-danger"></small>
                </div>
                <div class="form-group col-sm-6">
                    <input id="buy-js-phone" type="text" class="form-control form-control-sm input-text {{ $errors->has('customer.phone') ? 'border border-danger' : '' }}"
                           name="customer[phone]" placeholder="Số điện Thoại" value="{{ old('customer.phone') }}">
                    <small id="buy-phone" class="error text-danger"></small>
                </div>
                <div class="form-group col-12">
                    <input id="buy-js-email" type="text" class="form-control form-control-sm input-text {{ $errors->has('customer.email') ? 'border border-danger' : '' }}"
                           name="customer[email]" placeholder="Địa chỉ email" value="{{ old('customer.email') }}">
                    <small id="buy-email" class="error text-danger"></small>
                </div>
                <div class="form-group col-12 {{$errors->has('customer.address')?'has-error':''}}">
                    <input id="buy-js-address" type="text" class="form-control form-control-sm input-text {{ $errors->has('customer.address') ? 'border border-danger' : '' }}"
                           name="customer[address]" placeholder="Địa chỉ giao hàng"  value="{{ old('customer.address') }}">
                    <small id="buy-address" class="error text-danger"></small>
                </div>
                <div class="form-group col-12">
                    <textarea name="note" cols="3" rows="3" class="form-control text-area"
                                          placeholder="Ghi chú"></textarea>
                    <small class="error text-danger">{{$errors->first('note')}}</small>
                </div>
                <div class="d-flex justify-content-center col-12">
                    <button type="submit" class="btn btn-sm btn-danger btn-cart btn-quick-buy" >
                        <span>ĐẶT HÀNG NGAY</span>
                    </button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Quick Buy Success -->
<div id="modalSuccess" class="modal fade">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content" style="margin-top: 50%;">
            <div class="modal-header" style="justify-content: center;">
                <div class="icon-box">
                    <i class="fas fa-check"></i>
                </div>
                <h4 class="modal-title">Đặt hàng thành công!</h4>
            </div>
            <div class="modal-body" style="height: 100px">
                <p class="text-center text-muted">Đặt hàng của bạn đã được gửi đi.<br>
                    Mã đơn hàng: <b id="code" class="text-danger code"></b><br>
                    Vui lòng vào email để kiểm tra lại thông tin đơn hàng.
                </p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success btn-block" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>