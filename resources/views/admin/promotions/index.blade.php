@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('theme::promotions.promotions') }}
@endsection
@section('contentheader_title')
    {{ __('theme::promotions.promotions') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li class="active">{{ __('theme::promotions.promotions') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __('message.lists') }}</h3>
            <div class="box-tools">
                {!! Form::open(['method' => 'GET', 'url' => '/promotions', 'class' => 'pull-left', 'role' => 'search'])  !!}
                <div class="input-group" style="width: 200px;">
                    <input type="text" value="{{\Request::get('search')}}" class="form-control input-sm" name="search" placeholder="{{ __('message.search_keyword') }}">
                    <span class="input-group-btn">
                        <button class="btn btn-default btn-sm" type="submit">
                            <i class="fa fa-search"></i> {{ __('message.search') }}
                        </button>
                    </span>
                </div>
                {!! Form::close() !!}
                @can('PromotionController@store')
                    <a href="{{ url('/promotions/create') }}" class="btn btn-success btn-sm" title="{{ __('message.new_add') }}">
                        <i class="fa fa-plus" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.new_add') }}</span>
                    </a>
                @endcan
            </div>
        </div>
        @php($index = ($promotions->currentPage()-1)*$promotions->perPage())
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <th class="text-center">{{ trans('message.index') }}</th>
                    <th class="text-center">{{ trans('theme::promotions.avatar') }}</th>
                    <th>@sortablelink('title',trans('theme::promotions.title'))</th>
                    <th>{{ trans('theme::promotions.positive') }}</th>
                    <th>{{ trans('theme::promotions.date_start') }}</th>
                    <th>{{ trans('theme::promotions.date_end') }}</th>
                    <th>@sortablelink('updated_at',trans('theme::promotions.updated_at'))</th>
                    <th></th>
                </tr>
                @foreach($promotions as $item)
                    <tr>
                        <td class="text-center">{{ ++$index }}</td>
                        <th class="text-center">{!! $item->avatar ? '<img width="40" height="40" src="'.asset(Storage::url($item->avatar)).'">' : '' !!}</th>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->positive }}</td>
                        <td>{{ Carbon\Carbon::parse($item->date_start)->format(config('settings.format.date')) }}</td>
                        <td>{{ Carbon\Carbon::parse($item->date_end)->format(config('settings.format.date')) }}</td>
                        <td>{{ Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.datetime')) }}</td>
                        <td>
                            @can('PromotionController@show')
                                <a href="{{ url('/promotions/' . $item->id) }}" title="{{ __('message.view') }}"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> {{ __('message.view') }}</button></a>
                            @endcan
                            @can('PromotionController@update')
                                <a href="{{ url('/promotions/' . $item->id . '/edit') }}" title="{{ __('message.edit') }}"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ __('message.edit') }}</button></a>
                            @endcan
                            @can('PromotionController@destroy')
                                {!! Form::open([
                                    'method'=>'DELETE',
                                    'url' => ['/promotions', $item->id],
                                    'style' => 'display:inline'
                                ]) !!}
                                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> '.__('message.delete'), array(
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
                {!! $promotions->appends(\Request::except('page'))->render() !!}
            </div>
        </div>
    </div>
@endsection
