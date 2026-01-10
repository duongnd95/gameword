@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('theme::products.product') }}
@endsection
@section('contentheader_title')
    {{ __('theme::products.product') }}
@endsection
@section('contentheader_description')

@endsection
@section('main-content')
<div class="box">
    <div class="box-header">
        <h3 class="box-title">{{ __('message.lists') }}</h3>
        <div class="box-tools">
            {!! Form::open(['method' => 'GET', 'url' => '/admin/products', 'class' => 'pull-left', 'role' => 'search']) !!}

            <div class="input-group input-group-sm hidden-xs" style="display: flex;">
                <select name="category" class="form-control input-sm select2">
                        <option value="">--{{ __('theme::categories.category') }}--</option>
                        @foreach(\Modules\Product\Entities\CategoryProduct::pluck('name', 'id') as $key => $value)
                            <option {{ Request::get('category') == $key ? "selected" : "" }} value="{{ $key }}"> {{ $value }}</option>
                        @endforeach
                </select>
                <input type="text" value="{{\Request::get('search')}}" class="form-control input-sm" name="search" style="width: 100px"
                    placeholder="{{ __('message.search_keyword') }}">
                <span class="input-group-btn">
                    <button class="btn btn-info btn-sm" type="submit">
                        <i class="fa fa-search"></i> {{ __('message.search') }}
                    </button>
                </span>
            </div>
            {!! Form::close() !!}
            <a href="{{ url('/admin/products/create') }}" class="btn btn-success btn-sm" name="{{ __('message.new_add') }}" style="margin-left: 50px;">
                <i class="fa fa-plus" aria-hidden="true"></i> <span class="hidden-xs"></span>
            </a>
        </div>
    </div>
    @php($index = ($product->currentPage()-1)*$product->perPage())
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover table-bordered">
            <tbody>
                <tr class="bg-info">
                    <th class="text-center">{{ trans('message.index') }}</th>
                    <th class="text-center">{{ trans('theme::products.image') }}</th>
                    <th width="15%">@sortablelink('name',trans('theme::products.name'))</th>
                    <th width="10%">@sortablelink('category_id',trans('theme::products.category'))</th>
                    <th width="10%">{{ trans('theme::products.description') }}</th>
                    <th width="10%">@sortablelink('price',trans('theme::products.price'))</th>
                    <th class="text-center">{{ trans('theme::products.active') }}</th>
                    <th width="15%">@sortablelink('updated_at',trans('theme::products.updated_at'))</th>
                    <th></th>
                </tr>
                @foreach($product as $item)
                <tr>
                    <td class="text-center" style="width: 3.5%">{{ ++$index }}</td>
                    <td class="text-center">{!! $item->image ? '<img width="40" height="40" src="'.asset($item->image).'">' : '' !!}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ optional($item->category)->name }}</td>
                    <td>{!! Str::limit($item->description, 50) !!}</td>
                    <td>{{ number_format($item->price) }}{!! !empty($item->price_compare) ? '(<i class="fas fa-bahai text-danger" data-toggle="tooltip" data-placement="top" title="Giá so sánh '.number_format($item->price_compare).'"></i>)' : '' !!}</td>
                    <td class="text-center">{!! $item->active == config('settings.active') ? '<i class="fa fa-check text-primary"></i>' : ''  !!}</td>
                    <td>{{ Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') }}</td>
                    <td style="display: flex">
                        @can('ProductController@show')
                        {!! Form::open(['method' => 'GET', 'url' => '/admin/products/' . $item->id, 'class' => 'pd-2']) !!}
                        <input type="hidden" name="back_url" value="{{ url()->full() }}">
                        {!! Form::button('<i class="fa fa-eye" aria-hidden="true"></i> ', array(
                        'type' => 'submit',
                        'class' => 'btn btn-info btn-xs',
                        'name' => __('message.view')
                        )) !!}
                        {!! Form::close() !!}
                        @endcan
                        @can('ProductController@update')
                        {!! Form::open(['method' => 'GET', 'url' => '/admin/products/'. $item->id . '/edit', 'class' => 'pd-2']) !!}
                        <input type="hidden" name="back_url" value="{{ url()->full() }}">
                        {!! Form::button('<i class="fas fa-pencil-alt" aria-hidden="true"></i> ',
                        array(
                        'type' => 'submit',
                        'class' => 'btn btn-primary btn-xs',
                        'name' => __('message.edit')
                        )) !!}
                        {!! Form::close() !!}
                        @endcan
                        @can('ProductController@destroy')
                        {!! Form::open([
                        'method'=>'DELETE',
                        'url' => ['/admin/products', $item->id],
                        'class' => 'pd-2'
                        ]) !!}
                        {!! Form::button('<i class="fas fa-trash-alt" aria-hidden="true"></i> ',
                        array(
                        'type' => 'submit',
                        'class' => 'btn btn-danger btn-xs',
                        'name' => __('message.delete'),
                        'onclick'=>'return confirm("'.__('message.confirm_delete').'")'
                        )) !!}
                        {!! Form::close() !!}
                        @endcan
                    </td>
                </tr>
                @endforeach
                @if($product->count() == 0)
                <tr>
                    <td class="text-center" colspan="9">No Products</td>
                </tr>
                @endif
            </tbody>
        </table>
        <div class="box-footer clearfix">
            {!! $product->appends(\Request::except('page'))->render() !!}
        </div>
    </div>
</div>
@endsection
