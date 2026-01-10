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
        <li class="active">{{ __('message.edit_title') }}</li>
    </ol>
@endsection

@section('main-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ __('message.edit_title') }}</h3>
            <div class="box-tools">
                <a href="{{ url('/agents') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.lists') }}</span></a>
            </div>
        </div>

        {!! Form::model($agent, [
            'method' => 'PATCH',
            'url' => ['/agents', $agent->id],
            'class' => 'form-horizontal',
            'files' => true
        ]) !!}

        @include ('agents.form', ['submitButtonText' => __('message.update')])

        {!! Form::close() !!}
    </div>
@endsection