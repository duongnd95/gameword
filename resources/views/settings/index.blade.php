@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ trans('settings.setting_management') }}
@endsection
@section('contentheader_title')
    {{ trans('settings.setting_management') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("home") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li class="active">{{ trans('settings.setting_management') }}</li>
    </ol>
@endsection
@section('main-content')
    <div id="alert"></div>
    <div class="box">
        <ul class="nav nav-tabs" id="nav-tabs-settings" role="tablist">
            @for($i = 0;$i < count($tabs);$i++)
                <li role="presentation" class="{{ $i == 0 ? 'active' : ''}}">
                    <a href="#{{$tabs[$i]}}" aria-controls="{{$tabs[$i]}}" role="tab" data-toggle="tab">
                        {{ trans('settings.'.$tabs[$i]) }}
                    </a>
                </li>
            @endfor
        </ul>
        {!! Form::open([
            'method' => 'PATCH',
            'url' => ['admin/settings'],
            'class' => 'form-horizontal',
            'files' => true,
            'id' => 'settings'
        ]) !!}
        <div class="tab-content">
            @for($i = 0;$i < count($tabs);$i++)
                <div role="tabpanel" class="tab-pane {{$i == 0 ? 'active' : ''}}" id="{{$tabs[$i]}}">
                    <div class="box-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
                                @endforeach
                            </div>
                        @endif
                        @foreach ($data[$tabs[$i]] as $item)
                            <div class="form-group {{ $errors->has('value') ? 'has-error' : ''}}">
                                {!! Form::label('description', $item['description'], ['class' => 'col-md-3 control-label']) !!}
                                <div class="col-md-6">
                                    @if($item['type'] == 'image')
                                        @if($item['value'])
                                            <img src={{asset(($item['value']))}} alt="logo" width="80">
                                        @endif
                                        <input name={{$item['key']}} type="file" accept="image/*">
                                    @elseif($item['type'] == 'number')
                                        {!! Form::number($item['key'], $item['value'], ['class' => 'form-control input-sm', 'id' => $item['key']]) !!}
                                    @elseif($item['type'] == 'checkbox')
                                        {!! Form::checkbox($item['key'], config('settings.active'), $item['value'], ['class' => 'flat-blue', 'id' => $item['key']]) !!}
                                    @elseif($item['type'] == 'textarea')
                                        <textarea name={{$item['key']}} rows="5"
                                                  class="form-control">{{$item['value']}}</textarea>
                                    @elseif($item['type'] == 'select')
                                        <select name="{{ $item['key'] }}" class="form-control input-sm">
                                            @foreach(config('theme.option_code') as $key => $val)
                                                <option value="{{ $key }}" {{ $item['value'] === $key ? 'selected' : '' }}>{{ $val }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        {!! Form::text($item['key'], $item['value'], ['class' => 'form-control input-sm', 'id' => $item['key']]) !!}
                                    @endif
                                    {!! $errors->first($item['key'], '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endfor
            <div class="box-footer">
                {!! Form::submit(__('message.update'), ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
@endsection

