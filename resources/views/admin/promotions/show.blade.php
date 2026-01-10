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
        <li><a href="{{ url('/promotions') }}">{{ __('theme::promotions.promotions') }}</a></li>
        <li class="active">{{ __("message.detail") }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __("message.detail") }}</h3>
            <div class="box-tools">
                <a href="{{ url('/promotions') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> <span class="hidden-xs">{{ trans('message.lists') }}</span></a>
                @can('PromotionController@update')
                    <a href="{{ url('/promotions/' . $promotion->id . '/edit') }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.edit') }}</span></a>
                @endcan
                @can('PromotionController@destroy')
                    {!! Form::open([
                        'method'=>'DELETE',
                        'url' => ['promotions', $promotion->id],
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
                    <th> {{ trans('theme::promotions.title') }} </th>
                    <td> {{ $promotion->title }} </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::promotions.url') }} </th>
                    <td> <a href="{{ url('khuyen-mai') }}/{{ $promotion->slug }}.html">{{ url('khuyen-mai') }}/{{ $promotion->slug }}.html</a> </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::promotions.avatar') }} </th>
                    <td>
                        @if(!empty($promotion->avatar))
                            <img width="100" src="{{ asset(\Storage::url($promotion->avatar)) }}" alt="anh"/>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::promotions.date_start') }} </th>
                    <td> {{ Carbon\Carbon::parse($promotion->date_start)->format(config('settings.format.date')) }} </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::promotions.date_end') }} </th>
                    <td> {{ Carbon\Carbon::parse($promotion->date_end)->format(config('settings.format.date')) }} </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::promotions.content') }} </th>
                    <td>{!! $promotion->content !!}</td>
                </tr>
                <tr>
                    <th> {{ trans('theme::promotions.updated_at') }} </th>
                    <td> {{ Carbon\Carbon::parse($promotion->updated_at)->format(config('settings.format.datetime')) }} </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection