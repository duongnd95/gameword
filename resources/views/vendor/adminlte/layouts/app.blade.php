<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="{{ app()->getLocale() }}">

@section('htmlheader')
    @include('adminlte::layouts.partials.htmlheader')
@show
<body class="skin-yellow sidebar-mini">
<div id="app">
    <div class="wrapper">
    @include('adminlte::layouts.partials.mainheader')
    @include('adminlte::layouts.partials.sidebar')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" id="react-app">
        @include('adminlte::layouts.partials.contentheader')
        <!-- Main content -->
        <section class="content">
            @if (Session::has('flash_message'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="fa fa-fw fa-check"></i> {{ Session::get('flash_message') }}
                </div>
            @endif
            @if (Session::has('flash_info'))
                <div class="alert alert-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="fa fa-fw fa-check"></i> {{ Session::get('flash_info') }}
                </div>
            @endif
            <!-- Your Page Content Here -->
            @yield('main-content')

        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    {{--@include('adminlte::layouts.partials.controlsidebar')--}}
    @include('adminlte::layouts.partials.footer')

</div><!-- ./wrapper -->
</div>

@section('scripts')
    @include('adminlte::layouts.partials.scripts')
@show
<!--scripts-footer-->
@section('scripts-footer')
    @toastr_js
    @toastr_render
@show
</body>
</html>
