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
    <li><a href="{{ url('/group-products') }}">{{ __('product::groups.group') }}</a></li>
    <li class="active">{{ __("message.detail") }}</li>
</ol>
@endsection
@section('main-content')
<div class="box">
    <div class="box-header">
        <h3 class="box-name">{{ __("message.detail") }}</h3>
        <div class="box-tools">
            <a href="{{ url('/group-products') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"
                    aria-hidden="true"></i> <span class="hidden-xs">{{ trans('message.lists') }}</span></a>
            @can('GroupController@update')
            <a href="{{ url('/group-products/' . $group->id . '/edit') }}" class="btn btn-primary btn-sm"><i
                    class="fa fa-pencil-square-o" aria-hidden="true"></i> <span
                    class="hidden-xs">{{ __('message.edit') }}</span></a>
            @endcan
            @can('GroupController@destroy')
            {!! Form::open([
            'method'=>'DELETE',
            'url' => ['group-products', $group->id],
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
                    <th> {{ trans('product::groups.name') }} </th>
                    <td> {{ $group->name }} </td>
                </tr>
                <tr>
                    <th> {{ trans('product::groups.url') }} </th>
                    <td> <a href="{{ url($group->slug) }}">{{ url($group->slug) }}</a></td>
                </tr>
                <tr>
                    <th> {{ trans('product::groups.updated_at') }} </th>
                    <td> {{ Carbon\Carbon::parse($group->updated_at)->format(config('settings.format.datetime')) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection