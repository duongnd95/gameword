@extends('adminlte::layouts.app')

@section('htmlheader_title')
	{{ __('message.dashboard') }}
@endsection
@section('contentheader_title')
	{{ __('message.dashboard') }}
	<small>{{ __('message.control_panel') }}</small>
@endsection
@section('breadcrumb')
	<ol class="breadcrumb">
		<li><i class="fa fa-home"></i> {{ __('message.dashboard') }}</li>
	</ol>
@endsection
@section('main-content')
	<div class="row">
		@isset($users)
			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-red">
					<div class="inner">
						<h3>{{ $users }}</h3>

						<p>{{ __('message.user.users') }}</p>
					</div>
					<div class="icon">
						<i class="fa fa-user-secret"></i>
					</div>
					<a href="{{ url('admin/users') }}" class="small-box-footer">{{ __('message.more_info') }} <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
		@endisset
		@isset($categories)
			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-green">
					<div class="inner">
						<h3>{{ $categories }}</h3>

						<p>{{ __('Danh mục') }}</p>
					</div>
					<div class="icon">
						<i class="fab fa-product-hunt"></i>
					</div>
					<a href="{{ url('/admin/categories') }}" class="small-box-footer">{{ __('message.more_info') }} <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
		@endisset
		@isset($news)
			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-aqua">
					<div class="inner">
						<h3>{{ $news }}</h3>

						<p>{{ __('theme::news.news') }}</p>
					</div>
					<div class="icon">
						<i class="fa fa-building-o"></i>
					</div>
					<a href="{{ url('/admin/news') }}" class="small-box-footer">{{ __('message.more_info') }} <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
		@endisset

	</div>
@endsection
