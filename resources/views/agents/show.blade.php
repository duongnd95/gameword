@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __("agents.agent") }}
@endsection
@section('contentheader_title')
    {{ __("agents.agent") }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li><a href="{{ url('/agents') }}">{{ __("agents.agent") }}</a></li>
        <li class="active">{{ __("message.detail") }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __("message.detail") }}</h3>
            <div class="box-tools">
                <a href="{{ url('/agents') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> <span class="hidden-xs">{{ trans('message.lists') }}</span></a>
                @can('AgentsController@update')
                <a href="{{ url('/agents/' . $agent->id . '/edit') }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.edit') }}</span></a>
                @endcan
                @can('AgentsController@destroy')
                {!! Form::open([
                    'method'=>'DELETE',
                    'url' => ['agents', $agent->id],
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
                    <tr><th> {{ trans('agents.name') }} </th><td> {{ $agent->name }} </td></tr>
                    <tr><th> {{ trans('agents.phone') }} </th><td> {{ $agent->phone }} </td></tr>
                    <tr><th> {{ trans('agents.email') }} </th><td> {{ $agent->email }} </td></tr>
                    <tr><th> {{ trans('agents.address') }} </th><td> {{ $agent->address }} </td></tr>
                    <tr><th> {{ trans('agents.birthday') }} </th><td> {{ isset($agent->birthday)?Carbon\Carbon::parse($agent->birthday)->format(config('settings.format.date')): "" }} </td></tr>
                    <tr><th> {{ trans('agents.logo') }} </th><td>@if(!empty($agent->logo))<img style="width: 100px; height: 100px;" src="{{ asset(Storage::url($agent->logo)) }}">@endif</td></tr>
                    <tr><th> {{ trans('agents.email') }} </th><td> {{ $agent->email }} </td></tr>
                    <tr><th> {{ trans('agents.updated') }} </th><td> {{ Carbon\Carbon::parse($agent->updated_at)->format(config('settings.format.datetime')) }} </td></tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection