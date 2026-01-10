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
    <li><a href="{{ url('/admin/provider-products') }}">{{ __('product::providers.provider') }}</a></li>
    <li class="active">{{ __("message.detail") }}</li>
</ol>
@endsection
@section('main-content')
<div class="box">
    <div class="box-header">
        <h3 class="box-name">{{ __("message.detail") }}</h3>
        <div class="box-tools">
            <a href="{{ url('/admin/provider-products') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"
                    aria-hidden="true"></i> <span class="hidden-xs">{{ trans('message.lists') }}</span></a>
            @can('ProviderController@update') 
            <a href="{{ url('/admin/provider-products/' . $provider->id . '/edit') }}" class="btn btn-primary btn-sm"><i
                    class="fa fa-pencil-square-o" aria-hidden="true"></i> <span
                    class="hidden-xs">{{ __('message.edit') }}</span></a>
            @endcan
            @can('ProviderController@destroy') 
            {!! Form::open([
            'method'=>'DELETE',
            'url' => ['provider-products', $provider->id],
            'style' => 'display:inline'
            ]) !!}
            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> <span
                class="hidden-xs">'.__('message.delete').'</span>', array(
            'type' => 'submit',
            'class' => 'btn btn-danger btn-sm',
            'name' => __('message.delete'),
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
                    <th> {{ trans('product::providers.name') }} </th>
                    <td> {{ $provider->name }} </td>
                </tr>
                <tr>
                    <th> {{ trans('product::providers.production') }} </th>
                    <td> {{ $provider->production }} </td>
                </tr>
                <tr>
                    <th> {{ trans('product::providers.origin') }} </th>
                    <td> {{ $provider->origin }} </td>
                </tr>
                <tr>
                    <th> {{ trans('product::providers.url') }} </th>
                    <td> <a href="{{ url($provider->slug) }}">{{ url($provider->slug) }}</a></td>
                </tr>
                <tr>
                    <th> {{ trans('product::providers.updated_at') }} </th>
                    <td> {{ Carbon\Carbon::parse($provider->updated_at)->format(config('settings.format.datetime')) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection