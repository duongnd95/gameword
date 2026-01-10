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
        <li class="active">{{trans('message.role.roles')}}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{trans('message.lists')}}</h3>
            <div class="box-tools">
                <a href="{{ url('/admin/roles/create') }}" class="btn btn-success btn-sm" title="Add New Role"><i class="fa fa-plus" aria-hidden="true"></i> {{trans('message.new_add')}}
                </a>
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered table-hover">
                <tbody>
                    <tr class="bg-info">
                        <th>{{trans('message.role.id')}}</th><th>{{trans('message.role.name')}}</th><th>{{trans('message.role.label')}}</th><th></th>
                    </tr>
                @php($stt = ($roles->currentPage()-1)*$roles->perPage())
                @foreach($roles as $item)
                    <tr>
                        <td>{{ ++$stt }}</td>
                        <td><a href="{{ url('/admin/roles', $item->id) }}">{{ $item->name }}</a></td><td>{{ $item->label }}</td>
                        <td>
                            <a href="{{ url('/admin/roles/' . $item->id) }}" title="View Role"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i></button></a>
                            <a href="{{ url('/admin/roles/' . $item->id . '/edit') }}" title="Edit Role"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></a>
                            {!! Form::open([
                                'method' => 'DELETE',
                                'url' => ['/admin/roles', $item->id],
                                'style' => 'display:inline'
                            ]) !!}
                                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> ', array(
                                        'type' => 'submit',
                                        'class' => 'btn btn-danger btn-xs',
                                        'title' => 'Delete Role',
                                        'onclick'=>'return confirm("'.trans('message.role.confirm_delete').'")'
                                )) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="box-footer clearfix">
                {!! $roles->appends(['search' => Request::get('search')])->render() !!}
            </div>
        </div>
    </div>
@endsection
