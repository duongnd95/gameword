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
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li class="active">{{ __('versions.title') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __('message.lists') }}</h3>

        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr class="bg-info">
                    <th class="text-center">{{ trans('versions.version') }}</th>
                    <th class="text-center">{{ trans('versions.version_number') }}</th>
                    <th class="text-center">{{ trans('versions.version_ios') }}</th>
                    <th class="text-center">{{ trans('versions.version_number_ios') }}</th>
                    <th class="text-center">{{ trans('versions.enable_ads') }}</th>
                    <th class="text-center">@sortablelink('updated_at',trans('versions.updated_at'))</th>
                    <th></th>
                </tr>
                <tr>
                    <td class="text-center">{{ $versions->version }}</td>
                    <td class="text-center">{{ $versions->version_number }}</td>
                    <td class="text-center">{{ $versions->version_ios }}</td>
                    <td class="text-center">{{ $versions->version_number_ios }}</td>
                    <td class="text-center">{!! ($versions['enable_ads']==1)? '<i class="fa fa-check text-primary"></i>' : '' !!}</td>
                    <td class="text-center">{{ Carbon\Carbon::parse($versions->updated_at)->format(config('settings.format.datetime')) }}</td>
                    <td style="display: flex">
                        @can('NewsController@show')
                            {!! Form::open(['method' => 'GET', 'url' => '/admin/versions/' . $versions->id, 'class' => 'pd-2'])  !!}
                            <input type="hidden" name="back_url" value="{{ url()->full() }}">
                            {!! Form::button('<i class="fa fa-eye" aria-hidden="true"></i> ', array(
                                'type' => 'submit',
                                'class' => 'btn btn-info btn-xs',
                                'title' => __('message.view')
                            )) !!}
                            {!! Form::close() !!}
                        @endcan
                        @can('NewsController@update')
                            {!! Form::open(['method' => 'GET', 'url' => '/admin/versions/'. $versions->id . '/edit', 'class' => 'pd-2'])  !!}
                            <input type="hidden" name="back_url" value="{{ url()->full() }}">
                            {!! Form::button('<i class="fa fa-pencil-square-o" aria-hidden="true"></i> ', array(
                                'type' => 'submit',
                                'class' => 'btn btn-primary btn-xs',
                                'title' => __('message.edit')
                        )) !!}
                            {!! Form::close() !!}
                        @endcan
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
