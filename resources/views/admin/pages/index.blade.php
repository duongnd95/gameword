@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('theme::pages.page') }}
@endsection
@section('contentheader_title')
    {{ __('theme::pages.page') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("home") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li class="active">{{ __('theme::pages.page') }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __('message.lists') }}</h3>
            <div class="box-tools">
                {!! Form::open(['method' => 'GET', 'url' => 'admin/pages', 'class' => 'pull-left', 'role' => 'search'])  !!}
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
                @can('PageController@store')
                    <a href="{{ url('/admin/pages/create') }}" class="btn btn-success btn-flat btn-sm"
                       title="{{ __('message.new_add') }}">
                        <i class="fa fa-plus" aria-hidden="true"></i> <span
                                class="hidden-xs"></span>
                    </a>
                @endcan
            </div>
        </div>
        @php($index = ($pages->currentPage()-1)*$pages->perPage())
        <div class="box-body table-responsive no-padding">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr class="bg-info">
                    <th class="text-center">{{ trans('message.index') }}</th>
                    <th class="text-center">{{ trans('theme::pages.avatar') }}</th>
                    <th>@sortablelink('title',trans('theme::pages.name'))</th>
                    <th>{{ trans('theme::pages.slug') }}</th>
                    <th>{{ trans('theme::pages.description') }}</th>
                    <th>{{ trans('theme::ads.postion') }}</th>
                    <th>{{ trans('theme::pages.active') }}</th>
                    <th>@sortablelink('updated_at',trans('theme::pages.updated_at'))</th>
                    <th></th>
                </tr>
                @foreach($pages as $item)
                    @if(empty($item->parent_id))
                        @php($children = \App\Page::with('parent')->where('parent_id',$item->id)->get())
                        <tr>
                            <td class="text-center">{{ ++$index }}</td>
                            <td class="text-center">{!! $item->banner ? '<img width="40" height="40" src="'.asset($item->banner).'">' : '' !!}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->slug }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($item->description, 50) }}</td>
                            @empty(!$item->postion)
                                <td>{{ $postion[$item->postion] }}</td>
                            @else
                                <td>Main Page</td>
                            @endempty
                            <td class="text-center">{!! $item->active == config('settings.active') ? '<i class="fa fa-check text-primary"></i>' : ''  !!}</td>
                            <td>{{ Carbon\Carbon::parse($item->updated_at)->format(config('settings.format.datetime')) }}</td>
                            <td>
                                @can('PageController@show')
                                    <a href="{{ url('/admin/pages/' . $item->id) }}" title="{{ __('message.view') }}">
                                        <button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i>
                                        </button>
                                    </a>
                                @endcan
                                @can('PageController@update')
                                    <a href="{{ url('/admin/pages/' . $item->id . '/edit') }}"
                                       title="{{ __('message.edit') }}">
                                        <button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o"
                                                                                  aria-hidden="true"></i></button>
                                    </a>
                                @endcan
                                @can('PageController@destroy')
                                    {!! Form::open([
                                        'method'=>'DELETE',
                                        'url' => ['/admin/pages', $item->id],
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
                        @foreach($children as $itemChildren)
                            <tr>
                                <td class="text-center">{{ ++$index }}</td>
                                <td class="text-center">{!! $itemChildren->banner ? '<img width="40" height="40" src="'.$itemChildren->banner.'">' : '' !!}</td>
                                <td>--{{ $itemChildren->name }}</td>
                                <td>{{ $itemChildren->slug }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($itemChildren->description, 50) }}</td>
                                @empty(!$itemChildren->postion)
                                    <td>{{ $postion[$itemChildren->postion] }}</td>
                                @else
                                    <td>Main Page</td>
                                @endempty
                                <td class="text-center">{!! $item->active == config('settings.active') ? '<i class="fa fa-check text-primary"></i>' : ''  !!}</td>
                                <td>{{ Carbon\Carbon::parse($itemChildren->updated_at)->format(config('settings.format.datetime')) }}</td>
                                <td>
                                    @can('PageController@show')
                                        <a href="{{ url('/pages/' . $itemChildren->id) }}"
                                           title="{{ __('message.view') }}">
                                            <button class="btn btn-info btn-xs"><i class="fa fa-eye"
                                                                                   aria-hidden="true"></i></button>
                                        </a>
                                    @endcan
                                    @can('PageController@update')
                                        <a href="{{ url('/admin/pages/' . $itemChildren->id . '/edit') }}"
                                           title="{{ __('message.edit') }}">
                                            <button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o"
                                                                                      aria-hidden="true"></i></button>
                                        </a>
                                    @endcan
                                    @can('PageController@destroy')
                                        {!! Form::open([
                                            'method'=>'DELETE',
                                            'url' => ['/admin/pages', $itemChildren->id],
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
                    @endif
                @endforeach
                </tbody>
            </table>
            <div class="box-footer clearfix">
                {!! $pages->appends(\Request::except('page'))->render() !!}
            </div>
        </div>
    </div>
@endsection
