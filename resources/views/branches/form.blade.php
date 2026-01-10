<div class="box-body">
    @if ($errors->any())
	    <div class="alert alert-danger">
	        @foreach ($errors->all() as $error)
	            <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
	        @endforeach
	    </div>
	@endif
    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
        {!! Form::label('name', trans('branches.name'), ['class' => 'col-md-3 control-label label-required']) !!}
        <div class="col-md-6">
            {!! Form::text('name', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
        <div class="form-group {{ $errors->has('city_id') ? 'has-error' : ''}}">
            {!! Form::label('city_id', trans('branches.city'), ['class' => 'col-md-3 control-label label-required']) !!}
            <div class="col-md-6">
                {!! Form::select('city_id', $cities, null, ['class' => 'form-control input-sm select2', 'required' => 'required']) !!}
                {!! $errors->first('city_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    <div class="form-group {{ $errors->has('phone') ? 'has-error' : ''}}">
        {!! Form::label('phone', trans('branches.phone'), ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-6">
            {!! Form::text('phone', null, ['class' => 'form-control input-sm']) !!}
            {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
        {!! Form::label('email', trans('branches.email'), ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-6">
            {!! Form::email('email', null, ['class' => 'form-control input-sm']) !!}
            {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('address') ? 'has-error' : ''}}">
        {!! Form::label('address', trans('branches.address'), ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-6">
            {!! Form::text('address', null, ['class' => 'form-control input-sm']) !!}
            {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('birthday') ? 'has-error' : ''}}">
        {!! Form::label('birthday', trans('branches.birthday'), ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-6">
            {!! Form::text('birthday', null, ['class' => 'form-control input-sm datepicker']) !!}
            {!! $errors->first('birthday', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="box-footer">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-primary']) !!}
    @if(!isset($profile))
    <a href="{{ url('/branches') }}" class="btn btn-default">{{ __('message.close') }}</a>
    @endif
</div>
@section('scripts-footer')
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
    <script type="text/javascript" src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}" ></script>
    <script type="text/javascript">
        $(function(){
            //Date range picker with time picker
            $('.datepicker').datepicker({
                autoclose: true,
                language: '{{ app()->getLocale() }}',
                format: '{{ config('settings.format.date_js') }}'
            });
        });
    </script>
@endsection