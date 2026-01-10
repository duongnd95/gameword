@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('versions.version') }}
@endsection
@section('contentheader_title')
    {{ __('versions.version') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin/versions") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li><a href="{{ url('/admin/versions') }}">{{ __('versions.version') }}</a></li>
        <li class="active">{{ __("message.detail") }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __("message.detail") }}</h3>
            <div class="box-tools">
                <a href="{{ !empty($backUrl) ? $backUrl : url('/versions') }}" class="btn btn-warning btn-sm"><i
                            class="fa fa-arrow-left" aria-hidden="true"></i> <span
                            class="hidden-xs">{{ trans('message.lists') }}</span></a>
                @can('NewsController@update')
                    <a href="{{ url('/admin/versions/' . $versions->id . '/edit') }}" class="btn btn-primary btn-sm"><i
                                class="fa fa-pencil-square-o" aria-hidden="true"></i> <span
                                class="hidden-xs">{{ __('message.edit') }}</span></a>
                @endcan
                @can('NewsController@destroy')
                    {!! Form::open([
                        'method'=>'DELETE',
                        'url' => ['admin/versions', $versions->edid],
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
                    <th> {{ trans('versions.version') }} </th>
                    <td> {{ $versions->version }} </td>
                </tr>
                <tr>
                    <th> {{ trans('versions.version_number') }} </th>
                    <td> {{ $versions->version_number }} </td>
                </tr>
                <tr>
                    <th> {{ trans('versions.version_ios') }} </th>
                    <td> {{ $versions->version_ios }} </td>
                </tr>
                <tr>
                    <th> {{ trans('versions.version_number_ios') }} </th>
                    <td> {{ $versions->version_number_ios }} </td>
                </tr>
                <tr>
                    <th> {{ trans('versions.required_version_android') }} </th>
                    <td> {{ $versions->required_version_android }} </td>
                </tr>
                <tr>
                    <th> {{ trans('versions.required_version_ios') }} </th>
                    <td> {{ $versions->required_version_ios }} </td>
                </tr>
                <tr>
                    <th> {{ trans('versions.store_android') }} </th>
                    <td> {{ $versions->store_android }} </td>
                </tr>
                <tr>
                    <th> {{ trans('versions.store_ios') }} </th>
                    <td> {{ $versions->store_ios }} </td>
                </tr>
                <tr>
                    <th> {{ trans('versions.enable_ads') }} </th>
                    <td>{!! ($versions['enable_ads']==1)? '<i class="fa fa-check text-primary"></i>' : '' !!}</td>
                </tr>
                <tr>
                    <th> {{ trans('versions.contact') }} </th>
                    <td> {{ $versions->contact }} </td>
                </tr>
                <tr>
                    <th> {{ trans('versions.updated_at') }} </th>
                    <td> {{ Carbon\Carbon::parse($versions->updated_at)->format(config('settings.format.datetime')) }} </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection