@extends('theme::front-end.master')
@section('breadcrumb')
<div class="breadcrumb breadcrumb-fixed">
    <div class="col">
        <a href="{{ url('/')}}">{{ trans('theme::frontend.home') }}</a>
        /
        <span>{{ trans('theme::frontend.cart.order') }}</span>
    </div>
</div>
@endsection
@section('content')
<div class="container article order-cart">
    @if(Session::has('cart'))
    <div class="row">
        <div class="col-md-6 order-2 order-md-1">
            <div class="border-box">
                <form action="{{url('/submit-order/product')}}" method="post" class="form-horizontal order-form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <legend class="text-danger">Thông tin đặt hàng</legend>
                    <div class="form-group">
                        <input type="text" class="form-control form-control-sm input-text {{ $errors->has('customer.name') ? 'border border-danger' : '' }}" 
                            name="customer[name]" placeholder="Họ và tên" value="{{ old('customer.name') }}">
                        <span class="text-danger">{{$errors->first('customer.name')}}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control form-control-sm input-text {{ $errors->has('customer.email') ? 'border border-danger' : '' }}" 
                        name="customer[email]" placeholder="Địa chỉ email" value="{{ old('customer.email') }}">
                        <span class="text-danger">{{$errors->first('customer.email')}}</span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control form-control-sm input-text {{ $errors->has('customer.phone') ? 'border border-danger' : '' }}" 
                        name="customer[phone]" placeholder="Số điện Thoại" value="{{ old('customer.phone') }}">
                        <span class="text-danger">{{$errors->first('customer.phone')}}</span>
                    </div>
                    {{-- <div class="form-group">
                        <select name="customer[permanent_address]" class="form-control">
                            <option value="" selected="selected" hidden>--- Chọn tỉnh thành ---</option>
                            @foreach($city as $item)
                                <option value="{{$item->_name}}" >{{$item->_name}}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="form-group {{$errors->has('customer.address')?'has-error':''}}">
                        <input type="text" class="form-control form-control-sm input-text {{ $errors->has('customer.address') ? 'border border-danger' : '' }}"  
                        name="customer[address]" placeholder="Địa chỉ giao hàng"  value="{{ old('customer.address') }}">
                        <span class="text-danger">{{$errors->first('customer.address')}}</span>
                    </div>
                     <div class="form-group">
                        <textarea name="note" cols="3" rows="5" class="form-control text-area"
                            placeholder="Ghi chú"></textarea>
                        <span class="text-danger">{{$errors->first('note')}}</span>
                    </div>
                    <button type="submit" class="btn btn-danger">
                        Thanh toán&nbsp;<i class="fas fa-angle-right"></i>
                    </button>
                </form>
            </div>
        </div>
        <div class="col-md-6 order-1 order-md-2">
           <div class="border-box">
            <legend class="text-danger">Thông tin đơn hàng</legend>
            <table class="table table-hover">
                <thead>
                  
                </thead>
                <tbody>
                    <?php $total = 0 ?>
                    @if(session('cart'))
                    @foreach(session('cart') as $id => $details)
                    @php($total += $details['item']['price'] * $details['quantity'])
                    <tr>
                        <td>
                                <div class="info_product">
                                    <img src="{{ $details['item']['image'] }}" width="50" height="50"
                                        class="img-responsive" />
                                    <span class="amount">{{ $details['quantity'] }}</span>
                                </div> 
                        </td>
                        <td>
                            <div class="name_product">
                                <p>{{ $details['item']['name'] }}</p>
                            </div>
                        </td>             
                        <td>{{ number_format($details['item']['price'] * $details['quantity']) }} ₫</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">Tổng tiền</th>
                        <td>
                            <strong class="total text-danger">{{ number_format($total) }} ₫</strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
           </div>
        </div>
    </div>
    @endif
</div>
@endsection