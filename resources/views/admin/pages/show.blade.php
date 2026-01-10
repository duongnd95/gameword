@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('theme::pages.page') }}
@endsection
@section('contentheader_title')
    {{ __('theme::pages.page') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li><a href="{{ url('/admin/pages') }}">{{ __('theme::pages.page') }}</a></li>
        <li class="active">{{ __("message.detail") }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __("message.detail") }}</h3>
            <div class="box-tools">
                <a href="{{ url('/admin/pages') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> <span class="hidden-xs">{{ trans('message.lists') }}</span></a>
                @can('PageController@update')
                    <a href="{{ url('/admin/pages/' . $page->id . '/edit') }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.edit') }}</span></a>
                @endcan
                @can('PageController@destroy')
                    {!! Form::open([
                        'method'=>'DELETE',
                        'url' => ['admin/pages', $page->id],
                        'style' => 'display:inline'
                    ]) !!}
                    {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> <span class="hidden-xs">'.__('message.delete').'</span>', array(
                            'type' => 'submit',
                            'class' => 'btn btn-danger btn-sm',
                            'title' => __('message.delete'),
                            'onclick'=>'return confirm("'.__('message.confirm_delete').'")'
                    ))!!}
                    {!! Form::close() !!}
                @endcan
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <tbody>
                <tr class="row">
                    <th class="col-md-3"> {{ trans('theme::pages.name') }} </th>
                    <td class="col-md-9"> {{ $page->name }} </td>
                </tr>
                <tr class="row">
                    <th class="col-md-3"> {{ trans('theme::pages.slug') }} </th>
                    <td class="col-md-9"> {{ $page->slug }} </td>
                </tr>
                <tr class="row">
                    <th class="col-md-3"> {{ trans('theme::pages.banner') }} </th>
                    <td class="col-md-9">
                        @if(!empty($page->banner))
                            <img width="500" src="{{ $page->banner }}" alt="banner slide"/>
                        @endif
                    </td>
                </tr>
                <tr class="row">
                    <th class="col-md-3"> {{ trans('theme::pages.avatar') }} </th>
                    <td class="col-md-9">
                        @if(!empty($page->avatar))
                            <img width="100" src="{{ $page->avatar }}" alt="ảnh đại diện"/>
                        @endif
                    </td>
                </tr>
                <tr class="row">
                    <th class="col-md-3"> {{ trans('theme::pages.description') }} </th>
                    <td class="col-md-9">{{ $page->description }}</td>
                </tr>
                <tr class="row">
                    <th class="col-md-3"> {{ trans('theme::pages.content') }} </th>
                    <td class="col-md-9">{!! $page->content !!}</td>
                </tr>
                <tr class="row">
                    <th class="col-md-3"> {{ trans('theme::pages.updated_at') }} </th>
                    <td class="col-md-9"> {{ Carbon\Carbon::parse($page->updated_at)->format(config('settings.format.datetime')) }} </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection