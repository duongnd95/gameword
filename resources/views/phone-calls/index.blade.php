@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __("phone.phone") }}
@endsection
@section('contentheader_title')
    {{ __("phone.phone") }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li class="active">{{ __("phone.phone") }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __('message.lists') }}</h3>
            <div class="box-tools">
                {!! Form::open(['method' => 'GET', 'url' => '/phone-calls', 'class' => 'pull-left form-inline', 'role' => 'search'])  !!}
                <div class="input-group" style="width: auto;">
                    <div class="form-group">
                        <input type="text" value="{{\Request::get('search')}}" class="form-control input-sm" name="search" placeholder="{{ __('message.search_keyword') }}">
                    </div>
                    <span class="input-group-btn">
                        <button class="btn btn-default btn-sm" type="submit">
                            <i class="fa fa-search"></i> {{ __('message.search') }}
                        </button>
                    </span>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        @php($index = ($phones->currentPage()-1)*$phones->perPage())
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th class="text-center">{{ trans('message.index') }}</th>
                        <th>{{ trans('phone.phone') }}</th>
                        <th>{{ trans('phone.check_call') }}</th>
                        <th>{{ trans('phone.user_update_id') }}</th>
                        <th>{{ trans('message.created_at') }}</th>
                        <th>{{ trans('message.updated_at') }}</th>
                        <th></th>
                    </tr>
                @foreach($phones as $item)
                    <tr>
                        <td class="text-center">{{ ++$index }}</td>
                        <td>{{ $item->phone }}</td>
                        <td>
                            {!! Form::open(['method'=>'PUT', 'url' => '/phone-calls/' . $item->id , 'style' => 'display:inline', 'class' => 'add-booking']) !!}
                                <input type="checkbox" {{ !empty($item->check_call) ? 'checked' : '' }} class="toggle-toggle" id="toggle-event" data-toggle="toggle" data-on="Đã gọi" data-off="Chưa gọi" data-onstyle="success" data-offstyle="danger">
                                <input type="hidden" name="check_call" value="{{ !empty($item->check_call) ? 0 : 1 }}">
                            {!! Form::close() !!}
                        </td>
                        <td>{{ optional($item->user)->name }}</td>
                        <td>{{ Carbon\Carbon::parse($item->created_at)->format(config('settings.format.datetime')) }}</td>
                        <td>{{ Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.datetime')) }}</td>
                        <td>
                            <a href="{{ url('/bookings/bus/create?phone_id='.$item->id) }}" id="add-booking" title="{{ __('message.edit') }}"><button class="btn btn-success btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Đặt booking</button></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="box-footer clearfix">
                {!! $phones->appends(\Request::except('page'))->render() !!}
            </div>
        </div>
    </div>
@endsection
@section('scripts-footer')
    <link href="{{ asset('plugins/bootstrap-toogle/bootstrap-toggle.min.css') }}" rel="stylesheet">
    <script src="{{ asset('plugins/bootstrap-toogle/bootstrap-toggle.min.js') }}"></script>
    <style>
        .toggle {
            width: 75px !important;
            height: 26px !important;
        }
        .toggle.btn label {
            font-size: 12px;
        }
        .toggle-group .toggle-off {
            padding-left: 18px;
        }
    </style>
    <script type="text/javascript">
        $(function () {
            $('.toggle-toggle').change(function () {
                $(this).parents('form').submit();
            })
        })
    </script>
@endsection
