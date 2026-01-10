@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('theme::newsletters.newsletter') }}
@endsection
@section('contentheader_title')
    {{ __('theme::newsletters.newsletter') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li class="active">{{ __('theme::newsletters.newsletter') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __('message.lists') }}</h3>
            <div class="box-tools">
                {!! Form::open(['method' => 'GET', 'url' => 'admin/newsletters', 'class' => 'pull-left', 'role' => 'search'])  !!}
                <div class="input-group" style="width: 200px;">
                    <input type="text" value="{{\Request::get('search')}}" class="form-control input-sm" name="search" placeholder="{{ __('message.search_keyword') }}">
                    <span class="input-group-btn">
                        <button class="btn btn-default btn-sm" type="submit">
                            <i class="fa fa-search"></i> {{ __('message.search') }}
                        </button>
                    </span>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
        @php($index = ($newsletters->currentPage()-1)*$newsletters->perPage())
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <tbody>
                <tr>
                    <th class="text-center">{{ trans('message.index') }}</th>
                    <th>@sortablelink('email',trans('theme::newsletters.email'))</th>
                    <th>@sortablelink('created_at',trans('theme::newsletters.created_at'))</th>
                    <th></th>
                </tr>
                @foreach($newsletters as $item)
                    <tr>
                        <td class="text-center">{{ ++$index }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ Carbon\Carbon::parse($item->created_at)->format(config('settings.format.datetime')) }}</td>
                        <td>
                            {{--@can('NewsletterController@destroy')--}}
                                {!! Form::open([
                                    'method'=>'DELETE',
                                    'url' => ['admin/newsletters', $item->id],
                                    'style' => 'display:inline'
                                ]) !!}
                                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> '.__('message.delete'), array(
                                        'type' => 'submit',
                                        'class' => 'btn btn-danger btn-xs',
                                        'title' => __('message.delete'),
                                        'onclick'=>'return confirm("'.__('message.confirm_delete').'")'
                                )) !!}
                                {!! Form::close() !!}
                            {{--@endcan--}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="box-footer clearfix">
                {!! $newsletters->appends(\Request::except('page'))->render() !!}
            </div>
        </div>
    </div>
@endsection
