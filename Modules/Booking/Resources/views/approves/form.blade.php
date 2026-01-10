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
                {!! Form::label('name', trans('booking::approves.name'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('number') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('number', trans('booking::approves.number'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('number', isset($approve) ? $approve->number : $number, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('number', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('color') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('color', trans('booking::approves.color'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::color('color',isset($approve) ? $approve->color : '#ff0000',['class' => 'form-control', 'id' => 'color']) !!}
                {!! $errors->first('color', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
    </table>
</div>
<div class="box-footer">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ url('/bookings/approves') }}" class="btn btn-default">{{ __('message.close') }}</a>
     {{--{!! Form::submit(isset($submitButtonText) ? $submitButtonText." ".__('message.and_add') : __('message.save')." ".__('message.and_add'), ['class' => 'btn btn-success', 'name' => "btn-add"]) !!}--}}
</div>