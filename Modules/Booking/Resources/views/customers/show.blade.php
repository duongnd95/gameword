@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('booking::customers.customer') }}
@endsection
@section('contentheader_title')
    {{ __('booking::customers.customer') }}
@endsection
@section('contentheader_description')
    
@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li><a href="{{ url('/bookings/customers') }}">{{ __('booking::customers.customer') }}</a></li>
        <li class="active">{{ __("message.detail") }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __("message.detail") }}</h3>
            <div class="box-tools">
                <a href="{{ url('/bookings/customers') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> <span class="hidden-xs">{{ trans('message.lists') }}</span></a>
                @can('CustomerController@update')
                <a href="{{ url('/bookings/customers/' . $customer->id . '/edit') }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.edit') }}</span></a>
                @endcan
                @can('CustomerController@destroy')
                {!! Form::open([
                    'method'=>'DELETE',
                    'url' => ['/bookings/customers', $customer->id],
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
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <th> {{ trans('booking::customers.name') }} </th>
                    <td> {{ $customer->name }} </td>
                </tr>
                <tr>
                    <th> {{ trans('booking::customers.email') }} </th>
                    <td> {{ $customer->email }} </td>
                </tr>
                <tr>
                    <th> {{ trans('booking::customers.phone') }} </th>
                    <td> {{ $customer->phone }} </td>
                </tr>
                <tr>
                    <th> {{ trans('booking::customers.phone_other') }} </th>
                    <td> {{ $customer->getOriginal('phone_other') }} </td>
                </tr>
                <tr>
                    <th> {{ trans('booking::customers.gender') }} </th>
                    <td> {{ $customer->textGender }} </td>
                </tr>
                <tr>
                    <th> {{ trans('booking::customers.permanent_address') }} </th>
                    <td> {{ $customer->permanent_address }} </td>
                </tr>
                <tr>
                    <th> {{ trans('booking::customers.facebook') }} </th>
                    <td> {{ $customer->facebook }} </td>
                </tr>
                <tr>
                    <th> {{ trans('booking::customers.zalo') }} </th>
                    <td> {{ $customer->zalo }} </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection