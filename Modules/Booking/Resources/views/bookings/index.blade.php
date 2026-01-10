@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('booking::bookings.booking') }}
@endsection
@section('contentheader_title')
    {{ __('booking::bookings.booking') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li class="active">{{ __('booking::bookings.booking') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            @can('BookingController@store')
                <a href="{{ url('/bookings/'.Route::input('module').'/create') }}" class="btn btn-success btn-sm" title="{{ __('message.new_add') }}">
                    <i class="fa fa-plus" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.new_add') }}</span>
                </a>
            @endcan
            <div class="pull-right">
                {!! Form::open(['method' => 'GET', 'url' => '/bookings/'.Route::input('module'), 'class' => 'pull-left form-inline', 'role' => 'search' , 'style' => 'margin-right: 4px'])  !!}
                @include('booking::bookings.search-input')
                {!! Form::close() !!}
                <a class="btn btn-info btn-sm" href="{{ url('bookings/product/export') }}">
                    <i class="fas fa-file-export"></i>&nbsp;Export
                </a>
                <a class="btn btn-info btn-sm" href="javascript:;" data-toggle="modal" data-target="#modalImportExcel">
                    <i class="fas fa-file-import"></i>&nbsp;Import
                </a>
                <div id="modalImportExcel" class="modal fade" tabindex="-1" role="dialog" style=".file-preview:display:none;">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body text-center">
                                    {!! Form::open(['url' => 'bookings/product/import', 'class' => 'form-inline', 'files' => true]) !!}
                                    {!! Form::file('file', ['id' => 'input-b6', 'class' => 'form-control file', 'accept' => '.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel',
                                      ]) !!}
                                    <br>
                                    {!! Form::submit('Import Excel', ['class' => 'btn btn-info btn-sm'])!!}
                                    {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @php($index = ($bookings->currentPage()-1)*$bookings->perPage())
        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr class="bg-info">
                    <th class="text-center">{{ trans('message.index') }}</th>
                    <th class="col-md-1">{{ trans('booking::bookings.code') }}</th>
                    <th class="col-md-1">{{ trans('booking::bookings.approve') }}</th>
                    <th class="col-md-2">@sortablelink('customer.name', __('booking::bookings.customer'))</th>
                    <th class="col-md-2">{{ trans('booking::customers.phone') }}</th>
                    <th class="col-md-2">{{ trans('booking::bookings.total_price') }}</th>
                    <th class="col-md-2">{{ trans('booking::bookings.creator_id') }}</th>
                    <th class="col-md-2">@sortablelink('created_at', trans('message.created_at'))</th>
                    <th></th>
                </tr>

                @foreach($bookings as $item)
                    <tr>
                        <td class="text-center">{{ ++$index }}</td>
                        <td>{{ $item->code }}</td>
                        <td id="btn-status{{ $item->id }}">
                            <button type="button" class="label btn btn-status{{ $item->id }}" style="background-color: {{ optional($item->approve)->color }};"
                                onclick="showModalStatus( '{{ $item->approve_id }}','{{ $item->id }}' )">{{ optional($item->approve)->name }}</button>
                        </td>
                        <td>
                            {{ optional($item->customer)->name }}
                            @if(!empty($item->note))
                                <small class="label label-warning" data-toggle="tooltip" data-placement="top" title="{{ $item->note }}"><i class="fa fa-comment-o"></i></small>
                            @endif
                        </td>
                        <td>{{ optional($item->customer)->phone }}</td>
                        <td class="text-bold text-danger">{{ number_format($item->total_price) }}</td>
                        <td>{{ optional($item->creator)->name }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->created_at)->format(config('settings.format.datetime')) }}</td>
                        <td style="display: flex">
                            @can('BookingController@show')
                                {!! Form::open(['method' => 'GET', 'url' => '/bookings/'.Route::input('module').'/' . $item->id, 'class' => 'pd-2'])  !!}
                                <input type="hidden" name="back_url" value="{{ url()->full() }}">
                                {!! Form::button('<i class="fa fa-eye" aria-hidden="true"></i> ', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-info btn-xs',
                                    'title' => __('message.view')
                                )) !!}
                                {!! Form::close() !!}
                            @endcan
                            @can('BookingController@update')
                                    {!! Form::open(['method' => 'GET', 'url' => '/bookings/'.Route::input('module').'/' . $item->id . '/edit', 'class' => 'pd-2'])  !!}
                                    <input type="hidden" name="back_url" value="{{ url()->full() }}">
                                    {!! Form::button('<i class="fa fa-pencil-square-o" aria-hidden="true"></i> ', array(
                                        'type' => 'submit',
                                        'class' => 'btn btn-primary btn-xs',
                                        'title' => __('message.edit')
                                    )) !!}
                                    {!! Form::close() !!}
                            @endcan
                            @can('BookingController@destroy')
                            {!! Form::open([
                                'method'=>'DELETE',
                                'url' => ['/bookings/'.Route::input('module'), $item->id],
                                'style' => 'display:inline',
                                'class' => 'pd-2'
                            ]) !!}
                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i>', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-xs',
                                    'title' => __('message.delete'),
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
                {!! $bookings->appends(\Request::except('page'))->render() !!}
            </div>
        </div>
    </div>
    <div id="get-status"></div>
    <input type="hidden" value="{{ trans('booking::bookings.approve') }}" id="txt-approve">
@endsection
@include('booking::bookings.index-script')