@extends('adminlte::layouts.app')
@section('htmlheader_title')
{{ __('product::providers.provider') }}
@endsection
@section('contentheader_title')
{{ __('product::providers.provider') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ url("home") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
    <li class="active">{{ __('product::providers.provider') }}</li>
</ol>
@endsection
@section('main-content')
<div class="box">
    <div class="box-header">
        <h3 class="box-title">{{ __('message.lists') }}</h3>
        <div class="box-tools">
            {!! Form::open(['method' => 'GET', 'url' => '/admin/provider-products', 'class' => 'pull-left', 'role' =>
            'search']) !!}
            <div class="input-group" style="width: 300px;">
                <input type="text" value="{{\Request::get('search')}}" class="form-control input-sm" name="search"
                    placeholder="{{ __('message.search_keyword') }}">
                <span class="input-group-btn">
                    <button class="btn btn-info btn-sm" type="submit">
                        <i class="fa fa-search"></i> {{ __('message.search') }}
                    </button>
                </span>
            </div>
            {!! Form::close() !!}     
            @can('ProviderController@store') 
            <a href="{{ url('/admin/provider-products/create') }}" class="btn btn-success btn-sm"
                name="{{ __('message.new_add') }}">
                <i class="fa fa-plus" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.new_add') }}</span>
            </a>
            @endcan    
        </div>
    </div>
    @php($index = ($provider->currentPage()-1)*$provider->perPage())
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover table-bordered">
            <tbody>
                <tr class="bg-info">
                    <th class="text-center">{{ trans('message.index') }}</th>
                    <th>@sortablelink('name',trans('product::providers.name'))</th>
                    <th>{{ trans('product::providers.slug') }}</th>
                    <th class="text-center">{{ trans('product::providers.active') }}</th>
                    <th>@sortablelink('updated_at',trans('product::providers.updated_at'))</th>
                    <th></th>
                </tr>
                @foreach($provider as $item)
                <tr>
                    <td class="text-center">{{ ++$index }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->slug }}</td>
                    <td class="text-center">{!! $item->active == config('settings.active') ? '<i class="fa fa-check text-primary"></i>' : ''  !!}</td>
                    <td>{{ Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.datetime')) }}</td>
                    <td>
                        @can('ProviderController@show') 
                        <a href="{{ url('/admin/provider-products/' . $item->id) }}" name="{{ __('message.view') }}"><button
                                class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i>
                                </button></a>
                        @endcan
                        @can('ProviderController@update') 
                        <a href="{{ url('/admin/provider-products/' . $item->id . '/edit') }}"
                            name="{{ __('message.edit') }}"><button class="btn btn-primary btn-xs"><i
                                    class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                </button></a>
                        @endcan
                        @can('ProviderController@destroy') 
                        {!! Form::open([
                        'method'=>'DELETE',
                        'url' => ['/admin/provider-products', $item->id],
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
            {!! $provider->appends(\Request::except('page'))->render() !!}
        </div>
    </div>
</div>
@endsection