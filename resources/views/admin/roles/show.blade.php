@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{trans('message.role.roles')}}
@endsection
@section('contentheader_title')
    {{trans('message.role.roles')}}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url('home') }}"><i class="fa fa-home"></i> {{trans('message.dashboard')}}</a></li>
        <li><a href="{{ url('/admin/roles') }}">{{trans('message.role.roles')}}</a></li>
        <li class="active">{{trans('message.detail')}}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">{{trans('message.detail')}}</h3>
            <div class="box-tools">
                <a href="{{ url('/admin/roles') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> <span class="hidden-xs">{{trans('message.lists')}}</span></a>
                <a href="{{ url('/admin/roles/' . $role->id . '/edit') }}" title="Edit Role"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> {{trans('message.edit_title')}}</button></a>
                {!! Form::open([
                    'method' => 'DELETE',
                    'url' => ['/admin/roles', $role->id],
                    'style' => 'display:inline'
                ]) !!}
                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> '.trans('message.delete'), array(
                        'type' => 'submit',
                        'class' => 'btn btn-danger btn-sm',
                        'title' => 'Delete Role',
                        'onclick'=>'return confirm("'.trans('message.role.confirm_delete').'")'
                ))!!}
                {!! Form::close() !!}
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>{{trans('message.role.id')}}</th> <th>{{trans('message.role.name')}}</th><th>{{trans('message.role.label')}}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{ $role->id }}</td> <td> {{ $role->name }} </td><td> {{ $role->label }} </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection