@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('notifications.notification') }}
@endsection
@section('contentheader_title')
    {{ __('notifications.notification') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li class="active">{{ __('notifications.notification') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __('message.lists') }}</h3>
            <div class="box-tools">
                {!! Form::open(['method' => 'GET', 'url' => '/admin/notifications', 'class' => 'pull-left', 'role' => 'search']) !!}
                <div class="input-group" style="width: 300px;">
                    <input type="text" value="{{\Request::get('search')}}" class="form-control input-sm" name="search"
                           placeholder="{{ __('message.search_keyword') }}" style="width: 250px;">
                    <span class="input-group-btn">
                    <button class="btn btn-info btn-sm" type="submit">
                        <i class="fa fa-search"></i> {{ __('message.search') }}
                    </button>
                </span>
                </div>
                {!! Form::close() !!}
                @can('CategoryController@store')
                    <a href="{{ url('/admin/notifications/create') }}" class="btn btn-success btn-sm"
                       title="{{ __('message.new_add') }}">
                        <i class="fa fa-plus" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.new_add') }}</span>
                    </a>
                @endcan
            </div>
        </div>
        @php($index = ($notifications->currentPage()-1)*$notifications->perPage())
        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr class="bg-info">
                    <th class="text-center" style="width: 3.5%">{{ trans('message.index') }}</th>
                    <th>@sortablelink('title',trans('notifications.title'))</th>
                    <th>{{ trans('notifications.image') }}</th>
                    <th>{{ trans('notifications.description') }}</th>
                    <th>{{ trans('notifications.content') }}</th>
                    <th class="text-center">@sortablelink('updated_at',trans('notifications.updated_at'))</th>
                    <th></th>
                </tr>
                @foreach($notifications as $item)
                    <tr>
                        <td class="text-center">{{ ++$index }}</td>
                        <td>{{ $item->title }}</td>
                        <td class="text-center">{!! $item->image ? '<img width="40" height="40" src="'.asset($item->image).'">' : '' !!}</td>
                        <td>{{ Str::limit($item->description, 50) }}</td>
                        <td>{!! Str::limit($item->content, 50) !!}</td>
                        <td>{{ Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.datetime')) }}</td>
                        <td style="display: flex">
                            @can('NewsController@show')
                                {!! Form::open(['method' => 'GET', 'url' => '/admin/notifications/' . $item->id, 'class' => 'pd-2'])  !!}
                                <input type="hidden" name="back_url" value="{{ url()->full() }}">
                                {!! Form::button('<i class="fa fa-eye" aria-hidden="true"></i> ', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-info btn-xs',
                                    'title' => __('message.view')
                                )) !!}
                                {!! Form::close() !!}
                            @endcan
                            @can('NewsController@update')
                                {!! Form::open(['method' => 'GET', 'url' => '/admin/notifications/'. $item->id . '/edit', 'class' => 'pd-2'])  !!}
                                <input type="hidden" name="back_url" value="{{ url()->full() }}">
                                {!! Form::button('<i class="fa fa-pencil-square-o" aria-hidden="true"></i> ', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-primary btn-xs',
                                    'title' => __('message.edit')
                            )) !!}
                                {!! Form::close() !!}
                            @endcan
                            @can('NewsController@destroy')
                                {!! Form::open([
                                    'method'=>'DELETE',
                                    'url' => ['/admin/notifications', $item->id],
                                    'class' => 'pd-2'
                                ]) !!}
                                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> ', array(
                                        'type' => 'submit',
                                        'class' => 'btn btn-danger btn-xs',
                                        'title' => __('message.delete'),
                                        'onclick'=>'return confirm("'.__('message.confirm_delete').'")'
                                )) !!}
                                {!! Form::close() !!}
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection