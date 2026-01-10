@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ $title }}
@endsection
@section('contentheader_title')
    {{ $title }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li class="active">{{ $title }}</li>
    </ol>
@endsection

@section('main-content')
    <div class="box">
        <ul class="nav nav-tabs">
            <li id="month-active"><a href="">Thống kê theo tháng</a></li>
            <li id="many-month-active"><a href="">Thống kê các tháng trong năm</a></li>
            <li id="year-active"><a href="">Thống kê theo năm</a></li>
        </ul>
        <div class="tab-content">
            <div id="report-form-month" class="chart tab-pane active">
                <div class="box-header with-border">
                    <h3 class="box-title">

                    </h3>
                    <div class="box-tools">
                        <form action="" method="get" class="pull-left form-inline" id="report-form">
                            <select name="services_id" class="form-control input-sm" style="width: 150px;">
                                <option value="">--{{ __('reports.services') }}--</option>
                                @foreach(\Modules\Booking\Entities\Approve::pluck('name', 'id') as $key => $value)
                                    <option {{ Request::get('services_id') == $key ? "selected" : "" }} value="{{ $key }}"> {{ $value }}</option>
                                @endforeach
                            </select>
                            @if(!isset($date_year))
                                @if(!isset($date_month))
                                    <select name="report_month" class="form-control input-sm" style="width: 140px;">
                                        <option value="">--{{ __('reports.Upper_month') }}--</option>
                                        @foreach(\App\Utils::getMonth() as $value)
                                            <option {{ Request::get('report_month') == $value ? "selected" : "" }} value="{{ $value }}"> Tháng {{ $value }}</option>
                                        @endforeach
                                    </select>
                                @endif
                                <select name="report_year" class="form-control input-sm" style="width: 140px;">
                                    <option value="">--{{ __('reports.Upper_year') }}--</option>
                                    @foreach(\App\Utils::getYear() as $value)
                                        <option {{ Request::get('report_year') == $value ? "selected" : "" }} value="{{ $value }}"> {{ $value }}</option>
                                    @endforeach
                                </select>
                            @endif
                            <button id="report-month" class="btn btn-success btn-sm">{{ __('reports.report_btn') }}</button>
                        </form>
                    </div>
                </div>
                {{ \App\ChartJs::init() }}
                {{ \App\ChartJs::lineChart('report-date', $dataBooking, '70vh') }}
                <div class="box-body">
                    @php($index = ($bookingData->currentPage()-1)*$bookingData->perPage())
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <th class="text-center"> {{ trans('message.index') }} </th>
                            <th> {{ __('reports.customer_name') }} </th>
                            @if(isset($date_month))
                                @foreach($date_month as $key)
                                    <th>{{ 'T'. $key }}</th>
                                @endforeach
                            @elseif(isset($date_year))
                                @foreach($date_year as $key)
                                    <th>{{ __('reports.Upper_year') .' '. $key }}</th>
                                @endforeach
                            @else
                                <th> Số lần đặt</th>
                            @endif
                            <th> {{ __('reports.customer_detail') }} </th>
                        </tr>
                        @foreach($bookingData as $item)
                            <tr>
                                <td class="text-center">{{ ++$index }}</td>
                                <td>{{ optional($item->customer)->name }}</td>
                                @if(isset($bookingArr))
                                    @foreach($bookingArr as $key => $value)
                                        @if($item->customer_id == $key)
                                            @foreach($value as $value_child)
                                                <td>{{ $value_child }}</td>
                                            @endforeach
                                        @endif
                                    @endforeach
                                @else
                                    <td>{{ $item->number }}</td>
                                @endif
                                <td><a href="{{ url('/bookings/customers/' . $item->customer_id) }}" title="{{ __('message.view') }}"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> {{ __('message.view') }}</button></a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="box-footer clearfix">
                        {!! $bookingData->appends(\Request::except('page'))->render() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
@endsection
@section('scripts-footer')
    <script type="text/javascript" src="{{ asset('plugins/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script type="text/javascript">
        $(function () {
            let url = window.location.href;
            let true_url = '{{ url('bookings/reports/statistic') }}';

            $('#month-active a').attr('href', true_url+'/product/month');
            $('#many-month-active a').attr('href', true_url+'/product/many-month');
            $('#year-active a').attr('href', true_url+'/product/year');

            if (url.search('product/month') !== -1) {
                $('#month-active').addClass('active');
                $('#report-form').attr('action', true_url+'/product/month');
            }else if(url.search('product/many-month') !== -1){
                $('#many-month-active').addClass('active');
                $('#report-form').attr('action', true_url+'/product/many-month');
            }else{
                $('#year-active').addClass('active');
                $('#report-form').attr('action', true_url+'/product/year');
            }
        })
    </script>
@endsection