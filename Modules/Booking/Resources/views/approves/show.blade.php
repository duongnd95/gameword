@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('booking::approves.approves') }}
@endsection
@section('contentheader_title')
    {{ __('booking::approves.approves') }}
@endsection
@section('contentheader_description')
    
@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li><a href="{{ url('/bookings/approves') }}">{{ __('booking::approves.approves') }}</a></li>
        <li class="active">{{ __("message.detail") }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __("message.detail") }}</h3>
            <div class="box-tools">
                <a href="{{ url('/bookings/approves') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> <span class="hidden-xs">{{ trans('message.lists') }}</span></a>
                @can('ApproveController@update')
                <a href="{{ url('/bookings/approves/' . $approve->id . '/edit') }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.edit') }}</span></a>
                @endcan
                @can('ApproveController@destroy')
                {!! Form::open([
                    'method'=>'DELETE',
                    'url' => ['/bookings/approves', $approve->id],
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
                    <th> {{ trans('booking::approves.name') }} </th>
                    <td> {{ $approve->name }} </td>
                </tr>
                <tr>
                    <th> {{ trans('booking::approves.number') }} </th>
                    <td> {{ $approve->number }} </td>
                </tr>
                <tr>
                    <th> {{ trans('booking::approves.color') }} </th>
                    <td> <span class="label" style="background-color: {{ $approve->color }}">{{ $approve->name }}</span> </td>
                </tr>
                <tr>
                    <th> {{ trans('booking::approves.created_at') }} </th>
                    <td> {{ $approve->created_at }} </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection