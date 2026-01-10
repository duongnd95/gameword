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
            <li id="day-active"><a href="">Thống kê theo ngày</a></li>
            <li id="month-active"><a href="">Thống kê theo tháng</a></li>
            <li id="year-active"><a href="">Thống kê theo năm</a></li>
        </ul>
        <div class="tab-content">
            <div id="report-form-day" class="chart tab-pane active">
                <div class="box-header with-border">
                    <h3 class="box-title">

                    </h3>
                    <div class="box-tools">
                        <form action="" method="get" class="pull-left form-inline" id="report-form">
                            @if(Auth::user()->isAdminCompany() || Auth::user()->isCompanyManagerBooking())
                                <select name="agent_id" class="form-control input-sm">
                                    <option value="">--{{ trans('booking::bookings.agent') }}--</option>
                                    @foreach(\App\Agent::pluck('name', 'id') as $id => $name)
                                        <option {{ Request::get('agent_id') == $id ? "selected" : "" }} value="{{ $id }}"> {{ $name }}</option>
                                    @endforeach
                                </select>
                                <select name="employee_id" class="form-control input-sm">
                                    <option value="">--{{ trans('booking::bookings.creator_id') }}--</option>
                                    @foreach(\App\User::getUsersByOnlyCompany() as $id => $name)
                                        <option {{ Request::get('employee_id') == $id ? "selected" : "" }} value="{{ $id }}"> {{ $name }}</option>
                                    @endforeach
                                </select>
                            @endif
                            @if(Auth::user()->isAdminAgent())
                                <select name="employee_id" class="form-control input-sm">
                                    <option value="">--{{ trans('booking::bookings.creator_id') }}--</option>
                                    @foreach(\App\User::getUsersByOnlyAgent() as $id => $name)
                                        <option {{ Request::get('employee_id') == $id ? "selected" : "" }} value="{{ $id }}"> {{ $name }}</option>
                                    @endforeach
                                </select>
                            @endif
                            <select name="services_id" class="form-control input-sm" style="width: 150px;">
                                <option value="">--{{ __('reports.services') }}--</option>
                                @foreach(\Modules\Booking\Entities\Approve::pluck('name', 'id') as $key => $value)
                                    <option {{ Request::get('services_id') == $key ? "selected" : "" }} value="{{ $key }}"> {{ $value }}</option>
                                @endforeach
                            </select>
                            @if(isset($dataBookingByDate))
                                <div class="input-group">
                                    <button type="button" class="btn btn-default btn-sm pull-right" id="daterange-btn">
                                        <span>
                                          <i class="fa fa-calendar"></i> {{ __('reports.date_range') }}
                                        </span>
                                        <i class="fa fa-caret-down"></i>
                                    </button>
                                    <input type="hidden" name="from" value="{{ Request::get('from') }}"/>
                                    <input type="hidden" name="to" value="{{ Request::get('to') }}"/>
                                </div>
                            @elseif(isset($dataBookingByMonth))
                                <select name="report_year" class="form-control input-sm" style="width: 140px;">
                                    <option value="">--{{ __('reports.Upper_year') }}--</option>
                                    @foreach(\App\Utils::getYear() as $value)
                                        <option {{ Request::get('report_year') == $value ? "selected" : "" }} value="{{ $value }}"> {{ $value }}</option>
                                    @endforeach
                                </select>
                            @endif
                            <button id="report-day" type="submit"
                                    class="btn btn-success btn-sm">{{ __('reports.report_btn') }}</button>
                        </form>
                    </div>
                </div>

                <div class="box-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    {{ \App\ChartJs::init() }}
                    {{ \App\ChartJs::lineChart('report-date', $dataBooking, '70vh') }}
                    <hr>
                    <div class="btn-export">
                        <a class="btn btn-info btn-sm" href="{{ url('bookings/product/exportExcel') }}">
                            <i class="fas fa-file-export"></i> Export Excel</a>
                    </div>
                    <div class="table-responsive">
                        @include('booking::reports.booking_line_reports')
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
        let url = window.location.href;
        if (url.search('booking-number') !== -1) {
            var true_url = '{{ url('bookings/reports/booking-number') }}';
        } else if (url.search('booking-people') !== -1) {
            var true_url = '{{ url('bookings/reports/booking-people') }}';
        } else {
            var true_url = '{{ url('bookings/reports/finance') }}';
        }

        $('#day-active a').attr('href', true_url + '/product');
        $('#month-active a').attr('href', true_url + '/product/month');
        $('#year-active a').attr('href', true_url + '/product/year');

        if (url.search('product/month') !== -1) {
            $('#month-active').addClass('active');
            $('#report-form').attr('action', true_url + '/product/month');
        } else if (url.search('product/year') !== -1) {
            $('#year-active').addClass('active');
            $('#report-form').attr('action', true_url + '/product/year');
        } else {
            $('#day-active').addClass('active');
            $('#report-form').attr('action', true_url + '/product');
        }

    </script>
    <script type="text/javascript">
        $(function () {
            let from = moment().subtract(29, 'days');
            @if(!empty(Request::get('from')))
                from = moment('{{ Request::get('from') }}');
                    @endif
            let to = moment();
            @if(!empty(Request::get('to')))
                to = moment('{{ Request::get('to') }}');
            @endif
            $('#daterange-btn').daterangepicker(
                {
                    ranges: {
//                        'Hôm nay'   : [moment(), moment()],
//                        'Hôm qua'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        '{{ __('reports.last_7_days') }}': [moment().subtract(6, 'days'), moment()],
                        '{{ __('reports.last_30_days') }}': [moment().subtract(29, 'days'), moment()],
                        '{{ __('reports.this_month') }}': [moment().startOf('month'), moment().endOf('month')],
                        '{{ __('reports.last_month') }}': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    startDate: from,
                    endDate: to,
                    "locale": {
                        "format": "{{ strtoupper(config('settings.format.date_js')) }}",
                        "separator": " - ",
                        "applyLabel": "{{ __('reports.apply') }}",
                        "cancelLabel": "{{ __('reports.close') }}",
                        "fromLabel": "{{ __('reports.from') }}",
                        "toLabel": "{{ __('reports.to') }}",
                        "customRangeLabel": "{{ __('reports.custom') }}",
                        "daysOfWeek": [
                            "{{ __('reports.Su') }}",
                            "{{ __('reports.Mo') }}",
                            "{{ __('reports.Tu') }}",
                            "{{ __('reports.We') }}",
                            "{{ __('reports.Th') }}",
                            "{{ __('reports.Fr') }}",
                            "{{ __('reports.Sa') }}"
                        ],
                        "monthNames": [
                            "{{ __('reports.january') }}",
                            "{{ __('reports.february') }}",
                            "{{ __('reports.march') }}",
                            "{{ __('reports.april') }}",
                            "{{ __('reports.may') }}",
                            "{{ __('reports.june') }}",
                            "{{ __('reports.july') }}",
                            "{{ __('reports.august') }}",
                            "{{ __('reports.september') }}",
                            "{{ __('reports.october') }}",
                            "{{ __('reports.november') }}",
                            "{{ __('reports.december') }}"
                        ],
                        "firstDay": 1
                    }
                },
                function (start, end) {
                    $('#daterange-btn span').html(start.format('{{ strtoupper(config('settings.format.date_js')) }}') + ' - ' + end.format('{{ strtoupper(config('settings.format.date_js')) }}'));
                    $('[name=from]').val(start.format('YYYY-MM-DD'));
                    $('[name=to]').val(end.format('YYYY-MM-DD'));
                }
            )
        });
    </script>
@endsection