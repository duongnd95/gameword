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
        <li class="active">{{ __('theme::aboutus.aboutus') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __('message.lists') }}</h3>
            <div class="box-tools">
                {!! Form::open(['method' => 'GET', 'url' => '/admin/about-us', 'class' => 'pull-left', 'role' => 'search'])  !!}
                <div class="input-group" style="width: 200px;">
                    <input type="text" value="{{\Request::get('search')}}" class="form-control input-sm" name="search" placeholder="{{ __('message.search_keyword') }}">
                    <span class="input-group-btn">
                        <button class="btn btn-default btn-sm" type="submit">
                            <i class="fa fa-search"></i> {{ __('message.search') }}
                        </button>
                    </span>
                </div>
                {!! Form::close() !!}
                @can('AboutUsController@store')
                    <a href="{{ url('/admin/about-us/create') }}" class="btn btn-success btn-sm" title="{{ __('message.new_add') }}">
                        <i class="fa fa-plus" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.new_add') }}</span>
                    </a>
                @endcan
            </div>
        </div>
        @php($index = ($aboutus->currentPage()-1)*$aboutus->perPage())
        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr class="bg-info">
                    <th class="text-center" style="width: 3.5%">{{ trans('message.index') }}</th>
                    <th width="15%">@sortablelink('title', trans('theme::aboutus.title'))</th>
                    <th width="5%">{{ trans('theme::aboutus.icon') }}</th>
                    <th width="5%">{{ trans('theme::aboutus.color') }}</th>
                    <th width="20%">{{ trans('theme::aboutus.content') }}</th>
                    <th class="text-center" width="8%">{{ trans('theme::aboutus.active') }}</th>
                    <th width="15%">@sortablelink('updated_at',trans('theme::aboutus.updated_at'))</th>
                    <th style="width: 7%"></th>
                </tr>
                @foreach($aboutus as $item)
                    <tr>
                        <td class="text-center">{{ ++$index }}</td>
                        <td>{{ $item->title }}</td>
                        <td class="text-center"><i class="{{ $item->icon }} text-info"></i></td>
                        <td class="text-center"><i class="fas fa-square" style="color: {{ $item->color }}; font-size: 20px;"></i></td>
                        <td>{!! Str::limit($item->content) !!}</td>
                        <td class="text-center">{!! $item->active == config('settings.active') ? '<i class="fa fa-check text-primary"></i>' : ''  !!}</td>
                        <td>{{ Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.datetime')) }}</td>
                        <td style="display: flex">
                            @can('AboutUsController@show')
                                {!! Form::open(['method' => 'GET', 'url' => '/admin/about-us/' . $item->id, 'class' => 'pd-2'])  !!}
                                <input type="hidden" name="back_url" value="{{ url()->full() }}">
                                {!! Form::button('<i class="fa fa-eye" aria-hidden="true"></i> ', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-info btn-xs',
                                    'title' => __('message.view')
                                )) !!}
                                {!! Form::close() !!}
                            @endcan
                            @can('AboutUsController@update')
                                {!! Form::open(['method' => 'GET', 'url' => '/admin/about-us/'. $item->id . '/edit', 'class' => 'pd-2'])  !!}
                                <input type="hidden" name="back_url" value="{{ url()->full() }}">
                                {!! Form::button('<i class="fa fa-pencil-square-o" aria-hidden="true"></i> ', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-primary btn-xs',
                                    'title' => __('message.edit')
                            )) !!}
                                {!! Form::close() !!}
                            @endcan
                            @can('AboutUsController@destroy')
                                {!! Form::open([
                                    'method'=>'DELETE',
                                    'url' => ['/admin/about-us', $item->id],
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
            <div class="box-footer clearfix">
                {!! $aboutus->appends(\Request::except('page'))->render() !!}
            </div>
        </div>
    </div>
@endsection

