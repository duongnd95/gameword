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
        <li><a href="/home"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li><a href="{{ url('/admin/users') }}">{{ __('message.user.users') }}</a></li>
        <li class="active">{{ __('message.detail') }}</li>
    </ol>
@endsection

@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __('message.detail') }}</h3>
            <div class="box-tools">
                <a href="{{ url('/admin/users') }}" title="Back" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.lists') }}</span></a>
                @can('UsersController@update')
                <a href="{{ url('/admin/users/' . $user->id . '/edit') }}" class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.edit') }}</span></a>
                @endcan
                @can('UsersController@destroy')
                {!! Form::open([
                    'method' => 'DELETE',
                    'url' => ['/admin/users', $user->id],
                    'style' => 'display:inline'
                ]) !!}
                    {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> <span class="hidden-xs">'. __('message.delete') .'</span>', array(
                            'type' => 'submit',
                            'class' => 'btn btn-danger btn-sm',
                            'title' => 'Xoá',
                            'onclick'=>'return confirm("'. __('message.confirm_delete') .'?")'
                    ))!!}
                {!! Form::close() !!}
                @endcan
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th>{{ __('message.user.active') }}</th>
                        <td>{{ $user->active==Config("settings.active")?__('message.yes'):__('message.no') }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('message.user.avatar') }}</th>
                        <td>{!! $user->showAvatar() !!}</td>
                    </tr>

                    <tr>
                        <th>{{ __('message.user.name') }}</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('message.user.username') }}</th>
                        <td>{{ $user->username }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('message.user.email') }}</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('message.user.role') }}</th>
                        <td>
                            @foreach ($user->roles as $index=>$role)
                                <span class="badge label-{{ $role->color }}">{{ $role->label }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>{{ __('message.user.gender') }}</th>
                        <td>{{ isset($user->profile)?$user->profile->textGender:'' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('message.user.phone') }}</th>
                        <td>{{ isset($user->profile)?$user->profile->phone:'' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('message.user.address') }}</th>
                        <td>{{ isset($user->profile)?$user->profile->address:'' }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('message.user.birthday') }}</th>
                        <td>{{ isset($user->profile)?Carbon\Carbon::parse($user->profile->birthday)->format(config('settings.format.date')):"" }}</td>
                    </tr>
                    <tr>
                        <th>{{ __('message.user.position') }}</th>
                        <td>{{ isset($user->profile)?$user->profile->position:'' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection