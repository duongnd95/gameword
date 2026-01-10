<div class="box-body">
    @if ($errors->any())
	    <div class="alert alert-danger">
	        @foreach ($errors->all() as $error)
	            <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
	        @endforeach
	    </div>
	@endif
    {{--@isset($profile)
    <div class="form-group">
        {!! Form::label('name-company', trans('agents.company'), ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-6">
            {!! Form::text('name-company', $company->name, ['class' => 'form-control input-sm', 'disabled' => 'disabled']) !!}
        </div>
    </div>
    @else
        @if(Auth::user()->isAdminCompany())
        <div class="form-group {{ $errors->has('company_id') ? 'has-error' : '' }}">
            {!! Form::label('company_id', trans('agents.company'), ['class' => 'col-md-3 control-label label-required']) !!}
            <div class="col-md-6">
                {!! Form::select('company_id', $company, null, ['class' => 'form-control input-sm']) !!}
            </div>
        </div>
        @endif
    @endisset--}}
    <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
        {!! Form::label('name', trans('agents.name'), ['class' => 'col-md-3 control-label label-required']) !!}
        <div class="col-md-6">
            {!! Form::text('name', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('phone') ? 'has-error' : ''}}">
        {!! Form::label('phone', trans('agents.phone'), ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-6">
            {!! Form::text('phone', null, ['class' => 'form-control input-sm']) !!}
            {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
        {!! Form::label('email', trans('agents.email'), ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-6">
            {!! Form::email('email', null, ['class' => 'form-control input-sm']) !!}
            {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('address') ? 'has-error' : ''}}">
        {!! Form::label('address', trans('agents.address'), ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-6">
            {!! Form::text('address', null, ['class' => 'form-control input-sm']) !!}
            {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('birthday') ? 'has-error' : ''}}">
        {!! Form::label('birthday', trans('agents.birthday'), ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-6">
            {!! Form::text('birthday', null, ['class' => 'form-control input-sm datepicker']) !!}
            {!! $errors->first('birthday', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('logo') ? 'has-error' : ''}}">
        {!! Form::label('logo', trans('agents.logo'), ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-6">
            {!! Form::file('logo', null, ['class' => 'form-control input-sm']) !!}
            {!! $errors->first('logo', '<p class="help-block">:message</p>') !!}
        <p style="margin-bottom: 0"><img src="{{ !empty($agent->logo)?asset(Storage::url($agent->logo)): null }}" id="image-review"></p>
        </div>
    </div>
</div>
<div class="box-footer">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-primary']) !!}
    @if(!isset($profile))
    <a href="{{ url('/agents') }}" class="btn btn-default">{{ __('message.close') }}</a>
    @endif
     {{--{!! Form::submit(isset($submitButtonText) ? $submitButtonText." ".__('message.and_add') : __('message.save')." ".__('message.and_add'), ['class' => 'btn btn-success', 'name' => "btn-add"]) !!}--}}
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
    <script type="text/javascript">
        $(document).ready(function () {
            $("#logo").change(function (e) {
                var fileInput = this;
                if (fileInput.files[0]){
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $('#image-review').attr('src',e.target.result);
                        $('#image-review').css({"width":"100px","height":"100px","border":"1px solid #333"});
                    }
                    reader.readAsDataURL(fileInput.files[0]);
                }
            })
        })
    </script>
@endsection