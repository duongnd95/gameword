@extends('adminlte::layouts.app')
@section('htmlheader_title')
{{ __('product::attributes.attribute') }}
@endsection
@section('contentheader_title')
{{ __('product::attributes.attribute') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{ url("home") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
    <li><a href="{{ url('/attribute-products') }}">{{ __('product::attributes.attribute') }}</a></li>
    <li class="active">{{ __("message.detail") }}</li>
</ol>
@endsection
@section('main-content')
<div class="box">
    <div class="box-header">
        <h3 class="box-name">{{ __("message.detail") }}</h3>
        <div class="box-tools">
            <a href="{{ url('/attribute-products') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left"
                    aria-hidden="true"></i> <span class="hidden-xs">{{ trans('message.lists') }}</span></a>
            
            <a href="{{ url('/attribute-products/' . $attrs->id . '/edit') }}" class="btn btn-primary btn-sm"><i
                    class="fa fa-pencil-square-o" aria-hidden="true"></i> <span
                    class="hidden-xs">{{ __('message.edit') }}</span></a>
           
            {!! Form::open([
            'method'=>'DELETE',
            'url' => ['attrs-products', $attrs->id],
            'style' => 'display:inline'
            ]) !!}
            {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> <span
                class="hidden-xs">'.__('message.delete').'</span>', array(
            'type' => 'submit',
            'class' => 'btn btn-danger btn-sm',
            'name' => __('message.delete'),
            'onclick'=>'return confirm("'.__('message.confirm_delete').'")'
            ))!!}
            {!! Form::close() !!}
         
        </div>
    </div>
    <div class="box-body table-responsive no-padding">
        <table class="table table-striped">
            <tbody>
                <tr>
                    <th> {{ trans('product::attributes.type') }} </th>
                    <td> {{ $attrs->key }} </td>
                </tr>
                <tr>
                    <th> {{ trans('product::attributes.name') }} </th>
                    <td>  
                        @php($values = json_decode($attrs->value))
                        @foreach($values as $val)
                            {{ $val . ',' }}
                        @endforeach 
                    </td>
                </tr>
                <tr>
                    <th> {{ trans('product::attributes.updated_at') }} </th>
                    <td> {{ Carbon\Carbon::parse($attrs->updated_at)->format(config('settings.format.datetime')) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection