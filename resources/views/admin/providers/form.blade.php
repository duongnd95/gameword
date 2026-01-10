<div class="box-body">
    @if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
        <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
        @endforeach
    </div>
    @endif
        <table class="table table-bordered table-condensed">
            <tr class="row {{ $errors->has('name') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('name', trans('theme::providers.name'), ['class' => 'control-label label-required'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::text('name', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
            @if(isset($providers))
            <tr class="row {{ $errors->has('slug') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('name', trans('theme::providers.name'), ['class' => 'control-label'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::text('slug', null, ['class' => 'form-control input-sm']) !!}
                    {!! $errors->first('slug', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
            @endif
            <tr class="row {{ $errors->has('origin') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('origin', trans('theme::providers.origin'), ['class' => 'control-label'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::text('origin', null, ['class' => 'form-control input-sm']) !!}
                    {!! $errors->first('origin', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
            <tr class="row {{ $errors->has('production') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('production', trans('theme::providers.production'), ['class' => 'control-label'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::text('production', null, ['class' => 'form-control input-sm']) !!}
                    {!! $errors->first('production', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
            <tr class="row {{ $errors->has('active') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('active', trans('theme::categories.active'), ['class' => 'control-label'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    <div class="col-md" style="padding-top: 5px;">
                    {!! Form::checkbox('active', 1, (isset($providers) && $provider->active===1)?true:false, ['class' => 'flat-blue', 'id' => 'active']) !!}
                    {!! $errors->first('active', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
        </table>
</div>
<div class="box-footer">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-primary'])
    !!}
    <a href="{{ url('/group-products') }}" class="btn btn-default">{{ __('message.close') }}</a>
</div>
