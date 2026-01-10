@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('theme::sliders.slider') }}
@endsection
@section('contentheader_title')
    {{ __('theme::sliders.slider') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li><a href="{{ url('admin/sliders') }}">{{ __('theme::sliders.slider') }}</a></li>
        <li class="active">{{ __("message.detail") }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __("message.detail") }}</h3>
            <div class="box-tools">
                <a href="{{ url('admin/sliders') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> <span class="hidden-xs">{{ trans('message.lists') }}</span></a>
                @can('SliderController@update')
                    <a href="{{ url('admin/sliders/' . $slider->id . '/edit') }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.edit') }}</span></a>
                @endcan
                @can('SliderController@destroy')
                    {!! Form::open([
                        'method'=>'DELETE',
                        'url' => ['admin/sliders', $slider->id],
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
                <tr>
                    <th> {{ trans('theme::sliders.name') }} </th>
                    <td> {{ $slider->name }} </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::sliders.link') }} </th>
                    <td> <a href="{{ url($slider->link) }}">{{ url($slider->link) }}</a></td>
                </tr>
                <tr>
                    <th> {{ trans('theme::sliders.image') }} </th>
                    <td>
                        @if(!empty($slider->image))
                            <img width="100" src="{{ $slider->image }}" alt="anh"/>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::sliders.active') }} </th>
                    <td>{!! $slider->active == config('settings.active') ? '<i class="fa fa-check text-primary"></i>' : ''  !!}</td>
                </tr>
                <tr>
                    <th> {{ trans('theme::sliders.updated_at') }} </th>
                    <td> {{ Carbon\Carbon::parse($slider->updated_at)->format(config('settings.format.datetime')) }} </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection