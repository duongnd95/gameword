@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __("agents.agent") }}
@endsection
@section('contentheader_title')
    {{ __("agents.agent") }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li class="active">{{ __("agents.agent") }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __('message.lists') }}</h3>
            <div class="box-tools">
                {!! Form::open(['method' => 'GET', 'url' => '/agents', 'class' => 'pull-left form-inline', 'role' => 'search'])  !!}
                {{--@if(\Auth::user()->isAdminCompany())
                <select class="form-control input-sm select2" name="searchCompany">
                    <option value="">{{  __('message.please_select') }}</option>
                    @foreach($companies as $item)
                        <option {{ Request::get('searchCompany') == $item->id ? "selected" : ""  }} value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
                @endif--}}
                <div class="input-group" style="width: auto;">
                    <div class="form-group">
                        <input type="text" value="{{\Request::get('search')}}" class="form-control input-sm" name="search" placeholder="{{ __('message.search_keyword') }}">
                    </div>
                    <span class="input-group-btn">
                        <button class="btn btn-default btn-sm" type="submit">
                            <i class="fa fa-search"></i> {{ __('message.search') }}
                        </button>
                    </span>
                </div>
                {!! Form::close() !!}
                @can('AgentsController@store')
                <a href="{{ url('/agents/create') }}" class="btn btn-success btn-sm" title="{{ __('message.new_add') }}">
                    <i class="fa fa-plus" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.new_add') }}</span>
                </a>
                @endcan
            </div>
        </div>
        @php($index = ($agents->currentPage()-1)*$agents->perPage())
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th class="text-center">{{ trans('message.index') }}</th>
                        <th>@sortablelink('name', trans('agents.name'))</th>
                        <th>@sortablelink('phone', trans('agents.phone'))</th>
                        <th>@sortablelink('email', trans('agents.email'))</th>
                        <th>@sortablelink('address', trans('agents.address'))</th>
                        <th>@sortablelink('birthday', trans('agents.birthday'))</th>
                        <th>{{ trans('agents.logo') }}</th>
                        {{--@if(\Auth::user()->isAdminCompany())
                        <th>@sortablelink('company.name', trans('agents.company'))</th>
                        @endif--}}
                        <th>@sortablelink('updated_at', trans('agents.updated'))</th>
                        <th></th>
                    </tr>
                @foreach($agents as $item)
                    <tr>
                        <td class="text-center">{{ ++$index }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->phone }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->address }}</td>
                        <td>{{ isset($item->birthday)?Carbon\Carbon::parse($item->birthday)->format(config('settings.format.date')): "" }}</td>
                        <td>@if(!empty($item->logo))
                                <img style="width: 60px; height: 60px" src="{{ asset(Storage::url($item->logo)) }}">
                            @endif
                        </td>
                        {{--@if(\Auth::user()->isAdminCompany())
                        <td>{{ optional($item->company)->name }}</td>
                        @endif--}}
                        <td>{{ Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.datetime')) }}</td>
                        <td>
                            @can('AgentsController@show')
                            <a href="{{ url('/agents/' . $item->id) }}" title="{{ __('message.view') }}"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> {{ __('message.view') }}</button></a>
                            @endcan
                            @can('AgentsController@update')
                            <a href="{{ url('/agents/' . $item->id . '/edit') }}" title="{{ __('message.edit') }}"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{ __('message.edit') }}</button></a>
                            @endcan
                            @can('AgentsController@destroy')
                            {!! Form::open([
                                'method'=>'DELETE',
                                'url' => ['/agents', $item->id],
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
            <div class="box-footer clearfix">
                {!! $agents->appends(\Request::except('page'))->render() !!}
            </div>
        </div>
    </div>

@endsection
