@extends('adminlte::layouts.app')
@section('htmlheader_title')
{{ __('product::categories.category') }}
@endsection
@section('contentheader_title')
{{ __('product::categories.category') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ url("home") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
    <li><a href="{{ url('/admin/category-products') }}">{{ __('product::categories.category') }}</a></li>
    <li class="active">{{ __("message.detail") }}</li>
</ol>
@endsection
@section('main-content')
<div class="box">
    <div class="box-header">
        <h3 class="box-name">{{ __("message.detail") }}</h3>
        <div class="box-tools">
            <a href="{{ url('/admin/category-products') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"
                    aria-hidden="true"></i> <span class="hidden-xs">{{ trans('message.lists') }}</span></a>
            @can('CategoryProductController@update')
            <a href="{{ url('/admin/category-products/' . $category->id . '/edit') }}" class="btn btn-primary btn-sm"><i
                    class="fa fa-pencil-square-o" aria-hidden="true"></i> <span
                    class="hidden-xs">{{ __('message.edit') }}</span></a>
            @endcan
            @can('CategoryProductController@destroy')
            {!! Form::open([
            'method'=>'DELETE',
            'url' => ['admin/category-products', $category->id],
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
                    <th>{{ trans('theme::categories.name') }}</th>
                    <td>{{ $category->name }} </td>
                </tr>
                <tr>
                    <th>{{ trans('theme::categories.url') }}</th>
                    <td><a target="_blank" href="{{ url('tin-tuc/'.$category->slug) }}">{{ url('tin-tuc/'.$category->slug) }}</a></td>
                </tr>
                <tr>
                    <th>{{ trans('theme::categories.image') }}</th>
                    <td>
                        @if(!empty($category->image))
                        <img width="100" src="{{ asset($category->image) }}" alt="anh" />
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>{{ trans('theme::categories.description') }}</th>
                    <td>{{ $category->description }}</td>
                </tr>
                <tr>
                    <th>{{ trans('theme::categories.updated_at') }}</th>
                    <td>{{ Carbon\Carbon::parse($category->updated_at)->format(config('settings.format.datetime')) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection