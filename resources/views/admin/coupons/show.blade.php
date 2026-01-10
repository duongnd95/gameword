@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('coupons.coupon') }}
@endsection
@section('contentheader_title')
    {{ __('coupons.coupon') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li><a href="{{ url('/admin/coupons') }}">{{ __('coupons.coupon') }}</a></li>
        <li class="active">{{ __("message.detail") }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __("message.detail") }}</h3>
            <div class="box-tools">
                <a href="{{ url('/admin/coupons') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> <span class="hidden-xs">{{ trans('message.lists') }}</span></a>
                @can('CategoryController@update')
                    <a href="{{ url('/admin/coupons/' . $coupons->id . '/edit') }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.edit') }}</span></a>
                @endcan
                @can('CategoryController@destroy')
                    {!! Form::open([
                        'method'=>'DELETE',
                        'url' => ['admin/coupons', $coupons->id],
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
                    <th> {{ trans('coupons.name') }} </th>
                    <td> {{ $coupons->name }} </td>
                </tr>
                <tr>
                    <th> {{ trans('coupons.image') }} </th>
                    <td>
                        @if(!empty($coupons->image))
                            <img width="100" src="{{ asset($coupons->image) }}" alt="anh"/>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th> {{ trans('coupons.description') }} </th>
                    <td>{{ $coupons->description }}</td>
                </tr>
                <tr>
                    <th> {{ trans('coupons.content') }} </th>
                    <td>{!! $coupons->content !!}</td>
                </tr>
                <tr>
                    <th> {{ trans('coupons.percent_off') }} </th>
                    <td> {{ $coupons->percent_off }} </td>
                </tr>
                <tr>
                    <th> {{ trans('coupons.max_sale') }} </th>
                    <td> {{ $coupons->max_sale }} </td>
                </tr>
                <tr>
                    <th> {{ trans('coupons.sale_price') }} </th>
                    <td> {{ number_format($coupons->sale_price) }} </td>
                </tr>
                <tr>
                    <th> {{ trans('coupons.invoice_status') }} </th>
                    <td>{!! $coupons->invoice_status == config('settings.active') ? '<i class="fa fa-check text-primary"></i>' : ''  !!}</td>
                </tr>
                <tr>
                    <th> {{ trans('coupons.active') }} </th>
                    <td>{!! $coupons->active == config('settings.active') ? '<i class="fa fa-check text-primary"></i>' : ''  !!}</td>
                </tr>
                <tr>
                    <th> {{ trans('coupons.expires_at') }} </th>
                    <td> {{ Carbon\Carbon::parse($coupons->expires_at)->format(config('settings.format.date')) }} </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::categories.updated_at') }} </th>
                    <td> {{ Carbon\Carbon::parse($coupons->updated_at)->format(config('settings.format.datetime')) }} </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection