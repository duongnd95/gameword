@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('message.user.profile') }}
@endsection
@section('contentheader_title')
    {{ __('message.user.profile') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="/home"><i class="fa fa-home"></i> {{ __('message.dashboard') }}</a></li>
        <li class="active">{{ __('message.user.profile') }}</li>
    </ol>
@endsection

@section('main-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{ __('message.edit_title') }}</h3>
        </div>

        {!! Form::model($user, [
            'method' => 'POST',
            'url' => ['/profile'],
            'files' => true,
            'class' => 'form-horizontal'
        ]) !!}

        @include ('admin.users.form-profile', ['submitButtonText' => __('message.update'), 'isProfile'=> true])

        {!! Form::close() !!}
    </div>
@endsection