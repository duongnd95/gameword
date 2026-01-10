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
        <tr class="row {{ $errors->has('fullname') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('fullname', trans('theme::contacts.fullname'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('fullname', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('fullname', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

        <tr class="row {{ $errors->has('email') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('email', trans('theme::contacts.email'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::email('email', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('address') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('address', trans('theme::contacts.address'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('address', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('phone') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('phone', trans('theme::contacts.phone'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('phone', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
            
        <tr class="row {{ $errors->has('message') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('message', trans('theme::contacts.message'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('message', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('message', '<p class="help-block">:message</p>') !!}
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
        CKEDITOR.replace('message');
    </script>
@endsection