@extends('adminlte::layouts.app')
@section('htmlheader_title')
{{ __('product::attributes.attribute') }}
@endsection
@section('contentheader_title')
{{ __('product::attributes.attribute') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ url("home") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
    <li class="active">{{ __('product::attributes.attribute') }}</li>
</ol>
@endsection
@section('main-content')
<div class="box">
    <div class="box-header">
        <h3 class="box-name">{{ __('message.lists') }}</h3>
        <div class="box-tools">
            {!! Form::open(['method' => 'GET', 'url' => '/attribute-products', 'class' => 'pull-left', 'role' => 'search']) !!}
            <div class="input-group input-group-sm hidden-xs" style="width: 350px;">
                <input type="text" value="{{\Request::get('search')}}" class="form-control input-sm" name="search"
                    placeholder="{{ __('message.search_keyword') }}">
                <span class="input-group-btn">
                    <button class="btn btn-default btn-sm" type="submit">
                        <i class="fa fa-search"></i> {{ __('message.search') }}
                    </button>
                </span>
            </div>
            {!! Form::close() !!} 
            @can('AttributeController@store')
            <a href="{{ url('/attribute-products/create') }}" class="btn btn-success btn-sm"
                name="{{ __('message.new_add') }}">
                <i class="fa fa-plus" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.new_add') }}</span>
            </a>
            @endcan    
        </div>
    </div>
    @php($index = ($attrs->currentPage()-1)*$attrs->perPage())
    <div class="box-body table-responsive no-padding">
        <table class="table table-striped">
            <tbody>
                <tr>
                    <th class="text-center">{{ trans('message.index') }}</th>
                    <th>@sortablelink('name',trans('product::attributes.type'))</th>
                    <th>@sortablelink('name',trans('product::attributes.name'))</th>
                    <th class="text-center">{{ trans('product::attributes.active') }}</th>
                    <th>@sortablelink('updated_at',trans('product::attributes.updated_at'))</th>
                    <th></th>
                </tr>
                @foreach($attrs as $item)
                <tr>
                    <td class="text-center">{{ ++$index }}</td>
                    <td>{{ $item->key }}</td>
                    <td>
                        @php($values = json_decode($item->value))
                        @foreach($values as $val)
                            {{ $val . ',' }}
                        @endforeach
                    </td>
                    <td class="text-center">{!! $item->active == config('settings.active') ? '<i class="fa fa-check text-primary"></i>' : ''  !!}</td>
                    <td>{{ Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.datetime')) }}</td>
                    <td>
                        @can('AttributeController@show')
                        <a href="{{ url('/attribute-products/' . $item->id) }}" name="{{ __('message.view') }}"><button
                                class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i>
                                </button></a>
                        @endcan
                        @can('AttributeController@update')
                        <a href="{{ url('/attribute-products/' . $item->id . '/edit') }}"
                            name="{{ __('message.edit') }}"><button class="btn btn-primary btn-xs"><i
                                    class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </button></a>
                        @endcan
                        @can('AttributeController@destroy')
                        {!! Form::open([
                        'method'=>'DELETE',
                        'url' => ['/attribute-products', $item->id],
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
            {!! $attrs->appends(\Request::except('page'))->render() !!}
        </div>
    </div>
</div>
@endsection