@extends('adminlte::layouts.app')

@section('htmlheader_title')
    {{ __('theme::products.product') }}
@endsection
@section('contentheader_title')
    {{ __('theme::products.product') }}
@endsection

@section('contentheader_description')

@endsection
@section('css')
    <style>
        .gallery {
            position: relative;
            display: block;
            overflow: hidden;
            padding-bottom: 60%;
            margin-bottom: 10px;
        }

        .gallery img {
            position: absolute;
            top: 0;
            left: 0;
            object-fit: cover;
        }
    </style>
@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("home") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li><a href="{{ url('/admin/products') }}">{{ __('theme::products.product') }}</a></li>
        <li class="active">{{ __("message.detail") }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __("message.detail") }}</h3>
            <div class="box-tools">
                <a href="{{ !empty($backUrl) ? $backUrl : url('/admin/products') }}" class="btn btn-warning btn-sm"><i
                            class="fa fa-arrow-left" aria-hidden="true"></i> <span
                            class="hidden-xs">{{ trans('message.lists') }}</span></a>
                @can('ProductController@update')
                    <a href="{{ url('/admin/products/' . $product->id . '/edit') }}" class="btn btn-primary btn-sm"><i
                                class="fas fa-pencil-alt" aria-hidden="true"></i> <span
                                class="hidden-xs">{{ __('message.edit') }}</span></a>
                @endcan
                @can('ProductController@destroy')
                    {!! Form::open([
                    'method'=>'DELETE',
                    'url' => ['products', $product->id],
                    'style' => 'display:inline'
                    ]) !!}
                    {!! Form::button('<i class="fas fa-trash-alt" aria-hidden="true"></i> <span
                        class="hidden-xs">'.__('message.delete').'</span>', array(
                    'type' => 'submit',
                    'class' => 'btn btn-danger btn-sm',
                    'name' => __('message.delete'),
                    'onclick'=>'return confirm("'.__('message.confirm_delete').'")'
                    ))!!}
                    {!! Form::close() !!}
                @endcan
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <tbody>
                <tr class="row">
                    <th class="col-md-3"> {{ trans('theme::products.name') }} </th>
                    <td class="col-md-9"> {{ $product->name }} </td>
                </tr>
                <tr class="row">
                    <th class="col-md-3"> {{ trans('theme::products.url') }} </th>
                    <td class="col-md-9">
                        <a href="{{ url(optional($product->category)->slug . '/' .$product->slug) }}.html"
                           target="_blank">{{ url(optional($product->category)->slug . '/' .$product->slug) }}.html</a>
                    </td>
                </tr>
                <tr class="row">
                    <th class="col-md-3"> {{ trans('product::products.category') }} </th>
                    <td class="col-md-9"> {{ optional($product->category)->name }} </td>
                </tr>
                <tr class="row">
                    <th class="col-md-3"> {{ trans('product::providers.provider') }} </th>
                    <td class="col-md-9"> {{ optional($product->provider)->name }} </td>
                </tr>
                <tr class="row">
                    <th class="col-md-3"> {{ trans('product::products.image') }} </th>
                    <td class="col-md-9">
                        @if(!empty($product->image))
                            <img width="100" height="100px" src="{{ asset($product->image) }}"
                                 alt="{{ $product->name }}"/>
                        @endif
                    </td>
                </tr>
                <tr class="row">
                    <th class="col-md-3"> {{ trans('product::products.gallery') }} </th>
                    <td class="col-md-9">
                        @foreach($product->gallery as $item)
                            <img width="80" height="80px" src="{{ asset($item->image) }}"
                                 alt="{{ $item->name }}" style="padding: 5px;"/>
                        @endforeach
                    </td>
                </tr>
                <tr class="row">
                    <th class="col-md-3"> {{ trans('product::products.gift') }} </th>
                    <td class="col-md-9">
                        @foreach($product->gift as $item)
                            <img width="80" height="80px" src="{{ asset($item->image) }}"
                                 alt="{{ $item->name }}" style="padding: 5px;" data-toggle="tooltip"
                                 data-placement="top" title="{{ $item->name }}-Giá: {{ number_format($item->price)}}đ"/>
                        @endforeach
                    </td>
                </tr>
                <tr class="row">
                    <th class="col-md-3"> {{ trans('product::products.color') }} </th>
                    <td class="col-md-9">
                        @foreach($product->color as $item)
                            <span style="font-weight: bold;">{{ $item->name }}</span>
                            @foreach($item->images as $i)
                                <img width="80" height="80px" src="{{ asset($i->image) }}"
                                     style="padding: 5px;" data-toggle="tooltip"
                                     data-placement="top" title="{{ $item->name }}"/>
                            @endforeach
                        @endforeach
                    </td>
                </tr>
                <tr class="row">
                    <th class="col-md-3"> {{ trans('product::products.description') }} </th>
                    <td class="col-md-9">{!! str_limit($product->description, 240) !!}</td>
                </tr>
                <tr class="row">
                    <th class="col-md-3"> {{ trans('product::products.content') }} </th>
                    <td class="col-md-9">{!! str_limit($product->content, 2000) !!}</td>
                </tr>
                <tr class="row">
                    <th class="col-md-3"> {{ trans('product::products.price') }} </th>
                    <td class="col-md-9">{{ number_format($product->price) }}</td>
                </tr>
                <tr class="row">
                    <th class="col-md-3"> {{ trans('product::products.price_compare') }} </th>
                    <td class="col-md-9">{{ number_format($product->price_compare) }}</td>
                </tr>
                <tr class="row">
                    <th class="col-md-3"> {{ trans('product::products.created_at') }} </th>
                    <td class="col-md-9"> {{ Carbon\Carbon::parse($product->created_at)->format('d/m/Y H:i') }} </td>
                </tr>
                <tr class="row">
                    <th class="col-md-3"> {{ trans('product::products.updated_at') }} </th>
                    <td class="col-md-9"> {{ Carbon\Carbon::parse($product->updated_at)->format('d/m/Y H:i') }} </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
