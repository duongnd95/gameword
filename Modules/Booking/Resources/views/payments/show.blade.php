@extends('adminlte::layouts.app')
@section('htmlheader_title')
{{ __('booking::payments.payment') }}
@endsection
@section('contentheader_title')
{{ __('booking::payments.payment') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ url("home") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
    <li><a href="{{ url('/payments') }}">{{ __('booking::payments.payment') }}</a></li>
    <li class="active">{{ __("message.detail") }}</li>
</ol>
@endsection
@section('main-content')
<div class="box">
    <div class="box-header">
        <h3 class="box-name">{{ __("message.detail") }}</h3>
        <div class="box-tools">
            <a href="{{ url('/bookings/payments') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"
                    aria-hidden="true"></i> <span class="hidden-xs">{{ trans('message.lists') }}</span></a>
            @can('PaymentController@update')
            <a href="{{ url('/bookings/payments' . $payment->id . '/edit') }}" class="btn btn-primary btn-sm"><i
                    class="fa fa-pencil-square-o" aria-hidden="true"></i> <span
                    class="hidden-xs">{{ __('message.edit') }}</span></a>
            @endcan
            @can('PaymentController@destroy')
            {!! Form::open([
            'method'=>'DELETE',
            'url' => ['bookings/payments', $payment->id],
            'style' => 'display:inline'
            ]) !!}
            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> <span
                class="hidden-xs">'.__('message.delete').'</span>', array(
            'type' => 'submit',
            'class' => 'btn btn-danger btn-sm',
            'name' => __('message.delete'),
            'onclick'=>'return confirm("'.__('message.confirm_delete').'")'
            ))!!}
            {!! Form::close() !!}
            @endcan
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-striped">
            <tbody>
                <tr>
                    <th> {{ trans('booking::payments.name') }} </th>
                    <td> {{ $payment->name }} </td>
                </tr>
                <tr>
                    <th> {{ trans('booking::payments.image') }} </th>
                    <td>
                        @if(!empty($payment->image))
                            <img width="100" src="{{ asset($payment->image) }}" alt="{{ $payment->name }}"/>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th> {{ trans('booking::payments.name') }} </th>
                    <td> {{ $payment->type }} </td>
                </tr>
                <tr>
                    <th> {{ trans('booking::payments.updated_at') }} </th>
                    <td> {{ Carbon\Carbon::parse($payment->updated_at)->format(config('settings.format.datetime')) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection