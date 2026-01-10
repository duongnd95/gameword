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
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __('Dashboard') }}</a></li>
        <li><a href="{{ url('/admin/contacts') }}">{{ __('theme::contacts.contact') }}</a></li>
        <li class="active">{{ __('message.detail') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __('message.detail') }}</h3>
            <div class="box-tools">
                <a href="{{ url('/admin/contacts') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> <span class="hidden-xs">{{ __('message.lists') }}</span></a>
                @can('ContactController@destroy')
                {!! Form::open([
                    'method'=>'DELETE',
                    'url' => ['admin/contacts', $contact->id],
                    'style' => 'display:inline'
                ]) !!}
                    {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> <span class="hidden-xs">'.__('message.delete').'</span>', array(
                            'type' => 'submit',
                            'class' => 'btn btn-danger btn-sm',
                            'title' => 'Xoá',
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
                    <th> {{ trans('theme::contacts.fullname') }} </th>
                    <td> {{ $contact->fullname }} </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::contacts.email') }} </th>
                    <td> {{ $contact->email }} </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::contacts.address') }} </th>
                    <td> {{ $contact->address }} </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::contacts.phone') }} </th>
                    <td> {{ $contact->phone }} </td>
                </tr>
                <tr>
                    <th> {{ trans('theme::contacts.message') }} </th>
                    <td> {{ $contact->message }} </td>
                </tr>
                <tr>
                    <th> {{ trans('message.updated_at') }} </th>
                    <td> {{ Carbon\Carbon::parse($contact->updated_at)->format(config('settings.format.datetime')) }} </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection