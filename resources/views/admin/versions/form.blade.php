<div class="box-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif
    <div>
        <input type="hidden" name="back_url" value="{{ !empty($backUrl) ? $backUrl : '' }}">
    </div>
    <table class="table table-bordered table-condensed">
        <tr class="row {{ $errors->has('version') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('version', trans('versions.version'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('version', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('version', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('version_number') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('version_number', trans('versions.version_number'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('version_number', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('version_number', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('version_ios') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('version_ios', trans('versions.version_ios'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('version_ios', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('version_ios', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('version_number_ios') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('version_number_ios', trans('versions.version_number_ios'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('version_number_ios', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('version_number_ios', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('required_version_android') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('required_version_android', trans('versions.required_version_android'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('required_version_android', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('required_version_android', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('required_version_ios') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('required_version_ios', trans('versions.required_version_ios'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('required_version_ios', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('required_version_ios', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('store_android') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('store_android', trans('versions.store_android'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('store_android', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('store_android', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('store_ios') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('store_ios', trans('versions.store_ios'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('store_ios', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('store_ios', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('enable_ads') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('enable_ads', trans('versions.enable_ads'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::checkbox('enable_ads', 1, (isset($versions) && $versions->enable_ads == 1)?true:false, ['class' => 'flat-blue', 'id' => 'enable']) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('contact') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('contact', trans('versions.contact'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('contact', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('contact', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('content') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('content', trans('versions.content'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('content', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('content', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
    </table>
</div>
<div class="box-footer">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-md btn-info']) !!}
    <a href="{{ !empty($backUrl) ? $backUrl : url('admin/versions') }}"
       class="btn btn-default">{{ __('message.close') }}</a>
</div>
@section('scripts-footer')
    <script type="text/javascript" src="{{ asset('plugins/ckeditor_full/ckeditor.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('js/ckfinder/ckfinder.js') }}" ></script>
    <script>
        CKEDITOR.replace('contact');
        CKEDITOR.replace('content');
    </script>
@endsection