@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('notifications.notifications') }}
@endsection
@section('contentheader_title')

@endsection
@section('contentheader_description')
    
@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li class="active">{{ __('notifications.notifications') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ __('notifications.notifications') }}</h3>
        </div>
        <div class="box-body">
            @include('notifications.list')
            <div class="box-footer clearfix">
                {!! $notifications->appends(\Request::except('page'))->render() !!}
            </div>
        </div>
    </div>
@endsection