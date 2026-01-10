@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('message.user.users') }}
@endsection
@section('contentheader_title')
    {{ __('message.user.users') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('admin') }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li class="active">{{ __('message.user.users') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __('message.lists') }}</h3>
            <div class="box-tools">
                {!! Form::open(['method' => 'GET', 'url' => '/admin/users', 'class' => 'pull-left', 'role' => 'search'])  !!}
                <div class="input-group" style="width: 300px;">
                    <input type="text"  value="{{\Request::get('search')}}" class="form-control input-sm" name="search" placeholder="{{ __('message.user.search_keyword') }}" style="width: 250px;">
                    <span class="input-group-btn">
                        <button class="btn btn-info btn-sm" type="submit">
                            <i class="fa fa-search"></i> {{ __('message.search') }}
                        </button>
                    </span>
                </div>
                {!! Form::close() !!}
                @can('UsersController@store')
                <a href="{{ url('/admin/users/create') }}" class="btn btn-success btn-flat btn-sm" title="{{ __('message.new_add') }}">
                    <i class="fa fa-plus" aria-hidden="true"></i> <span class="hidden-xs"></span>
                </a>
                @endcan
            </div>
        </div>
        @php($stt = ($users->currentPage()-1)*$users->perPage())
        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr class="bg-info">
                    <th>{{ trans('message.index') }}</th>
                    <th>{{ trans('message.created_at') }}</th>
                    <th>@sortablelink('name', __('message.user.name'))</th>
                    <th>@sortablelink('username', __('message.user.username'))</th>
                    @if(Auth::user()->isAdminCompany())
                    <th>@sortablelink('agent.name', __('message.user.agent'))</th>
                    @endif
                    <th>@sortablelink('email', __('message.user.email'))</th>
                    <th>{{ __('message.user.role') }}</th>
                    <th>@sortablelink('active', __('message.user.active'))</th>
                    <th></th>
                </tr>
                @foreach($users as $item)
                    <tr>
                        <td>{{ ++$stt }}</td>
                        <td>{{ Carbon\Carbon::parse($item->created_at)->format(config('settings.format.datetime')) }}</td>
                        <td><a href="{{ url('/admin/users', $item->id) }}">{{ $item->name }}</a></td>
                        <td><a href="{{ url('/admin/users', $item->id) }}">{{ $item->username }}</a></td>
                        @if(Auth::user()->isAdminCompany())
                        <td>{{ optional($item->agent)->name }}</td>
                        @endif
                        <td>{{ $item->email }}</td>
                        <td>
                            @foreach ($item->roles as $index=>$role)
                                <span class="badge label-{{ $role->color }}">{{ $role->label }}</span>
                            @endforeach
                        </td>
                        <td>{{ $item->active==Config("settings.active")?__('message.yes'):__('message.no') }}</td>
                        <td>
                            @can('UsersController@show')
                            <a href="{{ url('/admin/users/' . $item->id) }}" title="View User"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                            @endcan
                            @can('UsersController@update')
                            <a href="{{ url('/admin/users/' . $item->id . '/edit') }}" title="Edit User"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                            @endcan
                            @can('UsersController@destroy')
                            {!! Form::open([
                                'method' => 'DELETE',
                                'url' => ['/admin/users', $item->id],
                                'style' => 'display:inline'
                            ]) !!}
                            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> ', array(
                                    'type' => 'submit',
                                    'class' => 'btn btn-danger btn-xs',
                                    'title' => 'Delete User',
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
                {!! $users->appends(\Request::except('page'))->render() !!}
            </div>
        </div>
    </div>
@endsection