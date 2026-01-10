@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('booking::bookings.booking') }}
@endsection
@section('main-content')
    <div class="box">
        <div class="panel panel-default mb-5">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-10 col-md-offset-2">
                        <div class="col-md-4 pull-left">
                           <p>MÃ HÓA ĐƠN:<strong> {{ $booking->code }}</strong></p>
                            {{ trans('message.created_at') }}: {{ Carbon\Carbon::parse($booking->created_at)->format(config('settings.format.date')) }} <br>
                        </div>
                        <div class="col-md-4 center">
                            <img src="http://www.blog.menut.ro/assets/img/download.png" alt="logo" class="" style="width: 30px;">
                        </div>
                        <div class="col-md-4 pull-right">
                            {{ trans('booking::bookings.approved') }}: {{ optional($booking->approve)->name }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="col-md-6 mb-3">
                    <div class="panel panel-default text-right">
                        <div class="panel-heading text-left">
                            Thông tin đơn hàng:
                        </div>
                        <div class="panel-body">
                            <table class="table table-hover">
                                <tbody>
                                <tr>
                                    <th>{{ trans('booking::bookings.code') }} </th>
                                    <td> {{ $booking->code }} </td>
                                </tr>
                                <tr>
                                    <th> {{ trans('message.created_at') }} </th>
                                    <td> {{ $booking->created_at }} </td>
                                </tr>
                                <tr>
                                    <th>{{ trans('booking::bookings.payment') }}</th>
                                    <td> {{ optional($booking->payment)->name }} </td>
                                </tr>
                                <tr>
                                    <th>Phương thức vận chuyển</th>
                                    <td>Giao hàng tận nơi</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="panel panel-default text-right">
                        <div class="panel-heading text-left">
                            {{ __('booking::bookings.customer_info') }}:
                        </div>
                        <div class="panel-body information-customer">
                            <address>
                                <table class="table table-hover">
                                    <tbody>
                                    <tr>
                                        <th> {{ trans('booking::bookings.customer') }} </th>
                                        <td> {{ optional($booking->customer)->name }} </td>
                                    </tr>
                                    <tr>
                                        <th> {{ trans('booking::customers.phone') }} </th>
                                        <td> {{ optional($booking->customer)->phone }} </td>
                                    </tr>
                                    <tr>
                                        <th> {{ trans('booking::customers.email') }} </th>
                                        <td> {{ optional($booking->customer)->email }} </td>
                                    </tr>
                                    <tr>
                                        <th> {{ trans('booking::customers.address') }} </th>
                                        <td> {{ optional($booking->customer)->address }} </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </address>
                        </div>
                    </div>
                </div>
                <div class="detail-invoice">
                    <h5 class="text-danger" style="margin-left: 10px;">Chi tiết đơn hàng</h5>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Mã sản phẩm</th>
                            <th>Sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Thành tiền</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $item)
                                <tr>
                                    <td>
                                        {{ $item[0]['id'] }}
                                    </td>
                                    <td>
                                        {{ $item[0]['name'] }}
                                    </td>
                                    <td>{{ $item[1] }}</td>
                                    <td>{{ number_format($item[0]['price']) }}</td>
                                    <td>
                                        {{ number_format($item[0]['price']*$item[1]) }} đ
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <th colspan="4">Tổng tiền</th>
                                <th>{{ number_format($booking->total_price) }} đ</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer" style="height: 10rem;">
                <p class="legal"><strong>Cảm ơn bạn đã sử dụng dịch vụ chúng tôi !</strong><br>&nbsp;
                    Mọi thắc mắc vui lòng liên hệ qua email: <b class="text-danger">duyksqt1996@gmail.com</b>.
                </p>
            </div>
        </div>
    </div>
@endsection