<div class="box-body">
    @if ($errors->any())
	    <div class="alert alert-danger">
	        @foreach ($errors->all() as $error)
	            <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
	        @endforeach
	    </div>
	@endif
	<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    {!! Form::label('name', trans('cities.name'), ['class' => 'col-md-3 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('name', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>
</div>
<div class="box-footer">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ url('/cities') }}" class="btn btn-default">{{ __('message.close') }}</a>
     {{--{!! Form::submit(isset($submitButtonText) ? $submitButtonText." ".__('message.and_add') : __('message.save')." ".__('message.and_add'), ['class' => 'btn btn-success', 'name' => "btn-add"]) !!}--}}
</div>