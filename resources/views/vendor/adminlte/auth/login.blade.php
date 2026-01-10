@extends('adminlte::layouts.auth')

@section('htmlheader_title')
    Log in
@endsection

@section('content')
    <body class="hold-transition login-page">
    <div id="app" class="">
        <div class="login-box">
            <div class="login-logo">
                <a href="{{ url('/') }}">{!! Config("settings.app_logo") !!}</a>
            </div><!-- /.login-logo -->
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="login-box-body">
                <form action="{{ url('/login') }}" method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group has-feedback">
                        @php
                        $type = config('auth.providers.users.field','email') == "email"? "email": "text";
                        @endphp
                    <input type="{{ $type }}" class="form-control" placeholder="{{ trans('adminlte_lang::message.username') }}" name="{{ config('auth.providers.users.field','email') }}"/>
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" placeholder="{{ trans('adminlte_lang::message.password') }}" name="password"/>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-7">
                            <div class="checkbox icheck">
                                <label>
                                    <input style="display:none;" type="checkbox" name="remember"> {{ trans('adminlte_lang::message.remember') }}
                                </label>
                            </div>
                        </div><!-- /.col -->
                        <div class="col-xs-5">
                            <button type="submit" class="btn btn-primary btn-block btn-flat">{{ trans('adminlte_lang::message.buttonsign') }}</button>
                        </div><!-- /.col -->
                    </div>
                </form>

                <a href="{{ url('/password/reset') }}">{{ trans('adminlte_lang::message.forgotpassword') }}</a><br>
                <a href="{{ url('/register') }}" class="text-center">{{ trans('adminlte_lang::message.registermember') }}</a>

            </div><!-- /.login-box-body -->
        </div><!-- /.login-box -->
    </div>
    @include('adminlte::layouts.partials.scripts_auth')

    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%'
        });
      });
    </script>
    </body>

@endsection