@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('theme::contacts.contact') }}
@endsection
@section('contentheader_title')
    {{ __('theme::contacts.contact') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li class="active">{{ __('theme::contacts.contact') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __('message.lists') }}</h3>
            <div class="box-tools">
                {!! Form::open(['method' => 'GET', 'url' => '/admin/contacts', 'class' => 'pull-left', 'role' => 'search'])  !!}
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
                @can('NewsController@store')
                    <a href="{{ url('/admin/contacts/create') }}" class="btn btn-success btn-sm"
                       title="{{ __('message.new_add') }}">
                        <i class="fa fa-plus" aria-hidden="true"></i> <span
                                class="hidden-xs">{{ __('message.new_add') }}</span>
                    </a>
                @endcan
            </div>
        </div>
        @php($index = ($contacts->currentPage()-1)*$contacts->perPage())
        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr class="bg-info">
                    <th class="text-center">{{ trans('message.index') }}</th>
                    <th>@sortablelink('fullname',trans('theme::contacts.fullname'))</th>
                    <th>@sortablelink('email',trans('theme::contacts.email'))</th>
                    <th>{{ trans('theme::contacts.message') }}</th>
                    <th>@sortablelink('updated_at',trans('theme::contacts.updated_at'))</th>
                    <th></th>
                </tr>
                @foreach($contacts as $item)
                    <tr>
                        <td class="text-center">{{ ++$index }}</td>
                        <td>{{ $item->fullname }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{!! Str::limit($item->message, 50) !!}</td>
                        <td>{{ Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.datetime')) }}</td>
                        <td>
                            @can('NewsController@show')
                                <a href="{{ url('/admin/contacts/' . $item->id) }}" title="{{ __('message.view') }}">
                                    <button class="btn btn-info btn-xs"><i class="fa fa-eye"
                                                                           aria-hidden="true"></i>
                                    </button>
                                </a>
                            @endcan
                            @can('NewsController@update')
                                {!! Form::open(['method' => 'GET', 'url' => '/admin/contacts/'. $item->id . '/edit', 'class' => 'pd-2'])  !!}
                                <input type="hidden" name="back_url" value="{{ url()->full() }}">
                                {!! Form::button('<i class="fa fa-pencil-square-o" aria-hidden="true"></i> ', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-primary btn-xs',
                                    'title' => __('message.edit')
                            )) !!}
                                {!! Form::close() !!}
                            @endcan
                            @can('NewsController@destroy')
                                {!! Form::open([
                                    'method'=>'DELETE',
                                    'url' => ['/admin/contacts', $item->id],
                                    'style' => 'display:inline'
                                ]) !!}
                                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> ', array(
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
                {!! $contacts->appends(\Request::except('page'))->render() !!}
            </div>
        </div>
    </div>
@endsection
