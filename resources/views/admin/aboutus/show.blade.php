@extends('adminlte::layouts.app')
@section('htmlheader_title')
{{ __('theme::aboutus.aboutus') }}
@endsection
@section('contentheader_title')
{{ __('theme::aboutus.aboutus') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
    <li><a href="{{ url('/admin/aboutus') }}">{{ __('theme::aboutus.aboutus') }}</a></li>
    <li class="active">{{ __("message.detail") }}</li>
</ol>
@endsection
@section('main-content')
<div class="box">
    <div class="box-header">
        <h3 class="box-title">{{ __("message.detail") }}</h3>
        <div class="box-tools">
            <a href="{{ !empty($backUrl) ? $backUrl : url('/admin/about-us') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> <span class="hidden-xs">{{ trans('message.lists') }}</span></a>
            @can('aboutusController@update')
            <a href="{{ url('/admin/about-us/' . $aboutus->id . '/edit') }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.edit') }}</span></a>
            @endcan
            @can('aboutusController@destroy')
            {!! Form::open([
            'method'=>'DELETE',
            'url' => ['admin/aboutus', $aboutus->id],
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
                    <th> {{ trans('theme::aboutus.title') }} </th>
                    <td> {{ $aboutus->title }} </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::aboutus.icon') }} </th>
                    <td><i class="{{ $aboutus->icon }}"></i></td>
                </tr>
                <tr>
                    <th> {{ trans('theme::aboutus.color') }} </th>
                    <td><i class="fas fa-square" style="color:{{ $aboutus->color }}; font-size: 20px;"></i></td>
                </tr>
                <tr>
                    <th> {{ trans('theme::aboutus.content') }} </th>
                    <td>{!! $aboutus->content !!}</td>
                </tr>
                <tr>
                    <th> {{ trans('theme::aboutus.updated_at') }} </th>
                    <td> {{ Carbon\Carbon::parse($aboutus->updated_at)->format(config('settings.format.datetime')) }} </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection