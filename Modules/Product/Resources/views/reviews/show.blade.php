@extends('adminlte::layouts.app')
@section('htmlheader_title')
    {{ __('product::reviews.title') }}
@endsection
@section('contentheader_title')
    {{ __('product::reviews.title') }}
@endsection
@section('contentheader_description')

@endsection
@section('breadcrumb')
    <ol class="breadcrumb">
        <li><a href="{{ url("admin") }}"><i class="fa fa-home"></i> {{ __("message.dashboard") }}</a></li>
        <li><a href="{{ url('/reviews') }}">{{ __('product::reviews.title') }}</a></li>
        <li class="active">{{ __("message.detail") }}</li>
    </ol>
@endsection
@section('main-content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">{{ __("message.detail") }}</h3>
            <div class="box-tools">
                <a href="{{ !empty($backUrl) ? $backUrl : url('/reviews') }}" class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> <span class="hidden-xs">{{ trans('message.lists') }}</span></a>
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th> {{ trans('product::reviews.product') }} </th>
                        <td> {{ optional($review->product)->name }}</td>
                    </tr>
                <tr>
                    <th> {{ trans('product::reviews.name') }} </th>
                    <td> {{ $review->name }} </td>
                </tr>
                <tr>
                    <th> {{ trans('product::reviews.email') }} </th>
                    <td>{{ $review->email }}</td>
                </tr>
                <tr>
                    <th> {{ trans('product::reviews.rating') }} </th>
                    <td>
                        @for ($i = 0; $i < $review->rating; $i++)
                          <i class="far fa-star"></i>      
                        @endfor
                    </td>   
                </tr>
                <tr>
                    <th> {{ trans('product::reviews.review_title') }} </th>
                    <td>{{ $review->title }}</td>
                </tr>
                <tr>
                    <th> {{ trans('product::reviews.review') }} </th>
                    <td>{!! $review->review !!}</td>
                </tr>
                <tr>
                    <th> {{ trans('theme::news.updated_at') }} </th>
                    <td> {{ Carbon\Carbon::parse($review->updated_at)->format(config('settings.format.datetime')) }} </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

@endsection