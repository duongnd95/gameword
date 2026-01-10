@extends('theme::front-end.master')
@section('breadcrumb')
<div class="breadcrumb breadcrumb-fixed">
    <div class="col">
        <a href="{{ url('/')}}">{{ trans('theme::frontend.home') }}</a>
        /
        <span>{{ trans('theme::frontend.cart.checkout') }}</span>
    </div>
</div>
@endsection
@section('content')
<div class="container">
    <div class="booking-success">

        @if(Session::has('order_success'))
            <div class="alert alert-success text-uppercase">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                @php($booking = \Modules\Booking\Entities\Booking::find(Session::get('order_success')))
                <i class="fa fa-fw fa-check"></i> {{ trans('theme::frontend.cart.checkout_code') }} <b>#{{ $booking->code }}</b>
            </div>
            <div class="text-alert">
                <p>{{ trans('theme::frontend.cart.content.hello') }} <b>{{ optional($booking->customer)->name }}</b></p>
                <p>Cám ơn Quý khách đã đặt hàng tại {{ $settings['company_website'] }}. Dưới đây là thông tin đơn hàng.</p>
                <p>Chúng tôi sẽ kiểm tra đơn hàng và liên hệ với Quý khách trong thời gian sớm nhất (trong
                    vòng 24h) theo các thông tin mà Quý khách đã cung cấp. Vui lòng kiểm tra email để xem lại thông tin
                    đơn hàng của Quý khách.</p>
            </div>
            <h4 class="title text-uppercase" style="color: #4d9200; font-weight: bold; font-size: 18px; padding-top: 5px;">
                {{ trans('theme::frontend.cart.personal_info') }}
            </h4>
            <div class="info-form" style="border:none;padding: 0px;margin-top: 10px;">
                <ul>
                    <li style="padding:5px; margin:0px; border-bottom-style:dashed;">
                        <span class="label order__label" style="font-weight: bold;">{{ trans('theme::frontend.form.full_name') }}</span>
                        <span class="value">{{ optional($booking->customer)->name }}</span>
                    </li>
                    <li style="padding:5px; margin:0px; border-bottom-style:dashed;">
                        <span class="label order__label" style="font-weight: bold;">{{ trans('theme::frontend.form.address') }}</span>
                        <span class="value">{{ optional($booking->customer)->address }}</span>
                    </li>
                    <li style="padding:5px; margin:0px; border-bottom-style:dashed;">
                        <span class="label order__label" style="font-weight: bold;">{{ trans('theme::frontend.form.phone') }}</span>
                        <span class="value">{{ optional($booking->customer)->phone }}</span>
                    </li>
                    <li style="padding:5px; margin:0px; border-bottom-style:dashed;">
                        <span class="label order__label" style="font-weight: bold;">{{ trans('theme::frontend.form.email') }}</span>
                        <span class="value">{{ optional($booking->customer)->email }}</span>
                    </li>
                </ul>
            </div>
            <h4 class="title" style="color: #4d9200; font-weight: bold; margin-top:20px; font-size: 18px;">{{ trans('theme::frontend.cart.order_info.title') }}</h4>
            <div class="info-form" style="border:none;padding: 0px;margin-top: 10px;">
                <table class="table table-hover table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th style="width:50%">Sản Phẩm</th>
                            <th style="width:20%">Giá</th>
                            <th style="width:10%">Số lượng</th>
                            <th style="width:30%" class="text-center">Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($orders = Session::get('cart'))
                        @php(Session::forget('cart'))
                        @php($total = 0)
                        @foreach($orders as $id => $item)
                        @php($total += $item['item']['price'] * $item['quantity'])
                        <tr id="result{{ $id }}">
                            <td>
                                <img src="{{ $item['item']['image'] }}" width="100" height="100" style="float: left; margin-right: 15px;" />
                                <h6 class="nomargin">{{ $item['item']['name'] }}</h6>
                            </td>
                            <td>{{ number_format($item['item']['price']) }} ₫</td>
                            <td class="text-center">{{ $item['quantity'] }}</td>
                            <td class="text-center itemPrice{{$id}}">{{ number_format($item['item']['price'] * $item['quantity']) }}&nbsp;₫</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="hidden-xs">Tổng Tiền</td>
                            <td class="hidden-xs text-center">
                                <strong style="color: #ee0000;" class="total">{{ number_format($total) }} ₫</strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <p style="margin-top: 20px; font-weight:bold;"><span style="color: #ee0000; align-items: center;">*</span>Lưu ý: Dịch vụ chưa được xác nhận cho tới khi đặt hàng của Quý khách được thanh toán và được xác nhận thành công từ {{ $settings['company_website'] }}.</p>
            @php(Session::forget('order_success'))
        @endif

    </div>
</div>
@endsection