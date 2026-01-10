@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('theme::categories.category') }}
@endsection
@section('contentheader_title')
    {{ __('theme::categories.category') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li class="active">{{ __('theme::categories.category') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __('message.lists') }}</h3>
            <div class="box-tools">
                {!! Form::open(['method' => 'GET', 'url' => '/admin/categories', 'class' => 'pull-left', 'role' => 'search']) !!}
                <div class="input-group" style="width: 200px;">
                    <input type="text" value="{{\Request::get('search')}}" class="form-control input-sm" name="search"
                           placeholder="{{ __('message.search_keyword') }}" style="width: 250px;">
                    <span class="input-group-btn">
                    <button class="btn btn-default btn-sm" type="submit">
                        <i class="fa fa-search"></i> {{ __('message.search') }}
                    </button>
                </span>
                </div>
                {!! Form::close() !!}
                @can('CategoryController@store')
                    <a href="{{ url('/admin/categories/create') }}" class="btn btn-success btn-sm"
                       title="{{ __('message.new_add') }}">
                        <i class="fa fa-plus" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.new_add') }}</span>
                    </a>
                @endcan
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr class="bg-info">
                    <th>@sortablelink('title',trans('theme::categories.title'))</th>
                    <th>{{ trans('theme::categories.slug') }}</th>
                    <th>{{ trans('theme::categories.description') }}</th>
                    <th class="text-center">{{ trans('theme::categories.active') }}</th>
                    <th class="text-center">@sortablelink('updated_at',trans('theme::categories.updated_at'))</th>
                    <th></th>
                </tr>
                @php
                    $listCategories = new \App\Category();
                    $listCategories->showListCategories($categories);
                @endphp
                </tbody>
            </table>
        </div>
    </div>
@endsection