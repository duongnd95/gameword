@extends('adminlte::layouts.app')
@section('htmlheader_title')
{{ __('product::groups.group') }}
@endsection
@section('contentheader_title')
{{ __('product::groups.group') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ url("home") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
    <li class="active">{{ __('product::groups.group') }}</li>
</ol>
@endsection
@section('main-content')
<div class="box">
    <div class="box-header">
        <h3 class="box-name">{{ __('message.lists') }}</h3>
        <div class="box-tools">
            {!! Form::open(['method' => 'GET', 'url' => '/group-products', 'class' => 'pull-left', 'role' =>
            'search']) !!}
            <div class="input-group" style="width: 200px;">
                <input type="text" value="{{\Request::get('search')}}" class="form-control input-sm" name="search"
                    placeholder="{{ __('message.search_keyword') }}">
                <span class="input-group-btn">
                    <button class="btn btn-default btn-sm" type="submit">
                        <i class="fa fa-search"></i> {{ __('message.search') }}
                    </button>
                </span>
            </div>
            {!! Form::close() !!}
            @can('GroupController@store')       
            <a href="{{ url('/group-products/create') }}" class="btn btn-success btn-sm"
                name="{{ __('message.new_add') }}">
                <i class="fa fa-plus" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.new_add') }}</span>
            </a>
            @endcan    
        </div>
    </div>
    @php($index = ($group->currentPage()-1)*$group->perPage())
    <div class="box-body table-responsive no-padding">
        <table class="table table-striped">
            <tbody>
                <tr>
                    <th class="text-center">{{ trans('message.index') }}</th>
                    <th>@sortablelink('name',trans('product::groups.name'))</th>
                    <th>{{ trans('product::groups.slug') }}</th>
                    <th class="text-center">{{ trans('product::groups.active') }}</th>
                    <th>@sortablelink('updated_at',trans('product::groups.updated_at'))</th>
                    <th></th>
                </tr>
                @foreach($group as $item)
                <tr>
                    <td class="text-center">{{ ++$index }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->slug }}</td>
                    <td class="text-center">{!! $item->active == config('settings.active') ? '<i class="fa fa-check text-primary"></i>' : ''  !!}</td>
                    <td>{{ Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.datetime')) }}</td>
                    <td>
                        @can('GroupController@show')
                        <a href="{{ url('/group-products/' . $item->id) }}" name="{{ __('message.view') }}"><button
                                class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i>
                               </button></a>
                        @endcan
                        @can('GroupController@update')
                        <a href="{{ url('/group-products/' . $item->id . '/edit') }}"
                            name="{{ __('message.edit') }}"><button class="btn btn-primary btn-xs"><i
                                    class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </button></a>
                        @endcan
                        @can('GroupController@destroy')
                        {!! Form::open([
                        'method'=>'DELETE',
                        'url' => ['/group-products', $item->id],
                        'style' => 'display:inline'
                        ]) !!}
                        {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> ',
                        array(
                        'type' => 'submit',
                        'class' => 'btn btn-danger btn-xs',
                        'name' => __('message.delete'),
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
            {!! $group->appends(\Request::except('page'))->render() !!}
        </div>
    </div>
</div>
@endsection