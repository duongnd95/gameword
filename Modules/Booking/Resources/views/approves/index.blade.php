@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('booking::approves.approves') }}
@endsection
@section('contentheader_title')
    {{ __('booking::approves.approves') }}
@endsection
@section('contentheader_description')
    
@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li class="active">{{ __('booking::approves.approves') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __('message.lists') }}</h3>
            <div class="box-tools">
                {!! Form::open(['method' => 'GET', 'url' => '/bookings/approves', 'class' => 'pull-left', 'role' => 'search'])  !!}
                <div class="input-group" style="width: 200px;">
                    <input type="text" value="{{\Request::get('search')}}" class="form-control input-sm" name="search" placeholder="{{ __('message.search_keyword') }}">
                    <span class="input-group-btn">
                        <button class="btn btn-default btn-sm" type="submit">
                            <i class="fa fa-search"></i>  {{ __('message.search') }}
                        </button>
                    </span>
                </div>
                {!! Form::close() !!}
                @can('ApproveController@store')
                <a href="{{ url('/bookings/approves/create') }}" class="btn btn-success btn-sm" title="{{ __('message.new_add') }}">
                    <i class="fa fa-plus" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.new_add') }}</span>
                </a>
                @endcan
            </div>
        </div>
        @php($index = ($approves->currentPage()-1)*$approves->perPage())
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <th class="text-center">{{ trans('message.index') }}</th>
                    <th>@sortablelink('name', trans('booking::approves.name'))</th>
                    <th>@sortablelink('number', trans('booking::approves.number'))</th>
                    <th>@sortablelink('color', trans('booking::approves.color'))</th>
                    <th>@sortablelink('updated_at', trans('message.updated_at'))</th>
                    <th></th>
                </tr>
                @foreach($approves as $item)
                    <tr>
                        <td class="text-center">{{ ++$index }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->number }}</td>
                        <td><span class="label" style="background-color: {{ $item->color }}">{{ $item->name }}</span></td>
                        <td>{{ \Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.datetime')) }}</td>
                        <td>
                            @can('ApproveController@show')
                            <a href="{{ url('/bookings/approves/' . $item->id) }}" title="{{ __('message.view') }}"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> {{ __('message.view') }}</button></a>
                            @endcan
                            @can('ApproveController@update')
                            <a href="{{ url('/bookings/approves/' . $item->id . '/edit') }}" title="{{ __('message.edit') }}"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ __('message.edit') }}</button></a>
                            @endcan
                            @can('ApproveController@destroy')
                            {!! Form::open([
                                'method'=>'DELETE',
                                'url' => ['/bookings/approves', $item->id],
                                'style' => 'display:inline'
                            ]) !!}
                                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> '.__('message.delete'), array(
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
            <div class="box-comment">
                <p style="padding: 8px 20px; margin-bottom: 0; border-top: 1px solid #f9f9f9"><span style="color: #f44336;">{{ trans('booking::approves.note') }}*:</span> Trạng thái với số thứ tự nhỏ nhất sẽ được dùng là trạng thái mặc định ban đầu của các booking.</p>
            </div>
            <div class="box-footer clearfix">
                {!! $approves->appends(\Request::except('page'))->render() !!}
            </div>
        </div>
    </div>

@endsection
