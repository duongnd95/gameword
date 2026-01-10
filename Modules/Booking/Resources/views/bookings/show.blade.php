@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('booking::bookings.booking') }}
@endsection
@section('contentheader_title')
    {{ __('booking::bookings.booking') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li><a href="{{ url('/bookings/'.Route::input('module')) }}">{{ __('booking::bookings.booking') }}</a></li>
        <li class="active">{{ __("message.detail") }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __("message.detail") }}</h3>
            <div class="box-tools">
                <a href="{{ !empty($backUrl) ? $backUrl : url('/bookings/'.Route::input('module')) }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.lists') }}</span></a>
{{--                @can('BookingController@update')--}}
{{--                <a href="{{ url('/bookings/'.Route::input('module').'/' . $booking->id . '/edit') }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.edit') }}</span></a>--}}
{{--                @endcan--}}
                @can('BookingController@destroy')
                {!! Form::open([
                    'method'=>'DELETE',
                    'url' => ['/bookings/'.Route::input('module'), $booking->id],
                    'style' => 'display:inline'
                ]) !!}
                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> <span class="hidden-xs">'.__('message.delete').'</span>', array(
                        'type' => 'submit',
                        'class' => 'btn btn-danger btn-sm',
                        'title' => __('message.delete'),
                        'onclick'=>'return confirm("'.__('message.confirm_delete').'")'
                ))!!}
                {!! Form::close() !!}
                @endcan
                <a href="{{ url('/bookings/product/invoice/'.$booking->id) }}" class="btn btn-info btn-sm btnPrint" ><i class="fas fa-print"></i> In</a>
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th colspan="2" class="text-danger">
                            {{ __('booking::bookings.customer_info') }}
                        </th>
                    </tr>
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
                    <tr>
                        <th> {{ trans('booking::customers.permanent_address') }} </th>
                        <td> {{ optional($booking->customer)->permanent_address }} </td>
                    </tr>
                    <tr>
                        <th> {{ trans('booking::customers.gender') }} </th>
                        <td> {{ optional($booking->customer)->textGender }} </td>
                    </tr>
                    <tr>
                        <th> {{ trans('booking::customers.facebook') }} </th>
                        <td> {{ optional($booking->customer)->facebook }} </td>
                    </tr>
                    <tr>
                        <th> {{ trans('booking::customers.zalo') }} </th>
                        <td> {{ optional($booking->customer)->zalo }} </td>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-danger">
                            {{ __('booking::bookings.booking_info') }}
                        </th>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <table class="table table-hover table-bordered" style="margin-bottom: 0">
                                <thead>
                                <tr>
                                    <th class="col-md-6">Sản Phẩm</th>
                                    <th class="col-md-2">Giá</th>
                                    <th class="col-md-2 text-center">Số Lượng</th>
                                    <th class="col-md-2 text-center">Tổng</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($booking->services as $item)
                                    <tr>
                                        <td>
                                            {{ $item->name }}
                                        </td>
                                        <td>{{ number_format($item->price) }}</td>
                                        <td class="text-center">{{ $item->pivot->quantity }}</td>
                                        <td class="text-center">
                                            {{ number_format($item->price*$item->pivot->quantity) }} đ
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th colspan="3">Tổng tiền</th>
                                    <th class="text-center">{{ number_format($booking->total_price) }} đ</th>
                                </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th> {{ trans('booking::bookings.creator_id') }} </th>
                        <td> {{ optional($booking->creator)->name }} </td>
                    </tr>
                    <tr>
                        <th> {{ trans('message.updated_at') }} </th>
                        <td> {{ Carbon\Carbon::parse($booking->departure_date)->format(config('settings.format.date')) }} </td>
                    </tr>
                    <tr>
                        <th> {{ trans('booking::bookings.approved') }} </th>
                        <td><span  class="{{ optional($booking->approve)->color }}">{{ optional($booking->approve)->name }}</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection
@section('scripts-footer')
    <script src="{{ asset('js/print.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".btnPrint").printPage({
                message:"Hóa đơn đang được khởi tạo..."
            });
        });
    </script>
@endsection