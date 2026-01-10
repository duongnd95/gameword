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
                {!! Form::label('name', trans('booking::customers.name'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name', null, ['class' => 'form-control input-sm customer_name', 'required' => 'required']) !!}
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('email') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('email', trans('booking::customers.email'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::email('email', null, ['class' => 'form-control input-sm ', 'id' => 'customer_email']) !!}
                <span id="email_auto" style="position: absolute;right: 20px;top: 5px;"></span>
                {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('phone') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('phone', trans('booking::customers.phone'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('phone', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('address') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('address', trans('booking::customers.address'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('address', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('permanent_address') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('permanent_address', trans('booking::customers.permanent_address'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('permanent_address', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('permanent_address', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('gender') ? ' has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('gender', __('booking::customers.gender'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <label for="boy">
                    {!! Form::radio('gender', 1, isset($booking->customer) && $booking->customer->gender===1?true:false, ['class' => '', 'id' => 'boy']) !!}
                    {{ __('message.user.gender_male') }}
                </label>&nbsp;
                <label for="girl">
                    {!! Form::radio('gender', 0, isset($booking->customer) && $booking->customer->gender===0?true:false, ['class' => '', 'id' => 'girl']) !!}
                    {{ __('message.user.gender_female') }}
                </label>
                {!! $errors->first('gender', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('facebook') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('facebook', trans('booking::customers.facebook'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('facebook', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('facebook', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('zalo') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('zalo', trans('booking::customers.zalo'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('zalo', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('zalo', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
    </table>
</div>
<div class="box-footer">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ url('/bookings/customers') }}" class="btn btn-default">{{ __('message.close') }}</a>
     {{--{!! Form::submit(isset($submitButtonText) ? $submitButtonText." ".__('message.and_add') : __('message.save')." ".__('message.and_add'), ['class' => 'btn btn-success', 'name' => "btn-add"]) !!}--}}
</div>
@section('scripts-footer')
    <script type="text/javascript">
        $(function () {
            $('#add-field').click(function () {
                if($('#phone-other-add').find('.phone-other-wrap').length === 0) {
                    var new_div = document.createElement('div');
                    new_div.innerHTML = '<div class="col-md-11 phone-other-wrap" style="padding-top: 10px"><input class="form-control input-sm customer_phone_other" name="phone_other[]" type="text" id="phone_other"></div>';
                    $('#phone-other-add').append(new_div);
                }
            });

            $('.delete-field').click(function () {
                $(this).parent('div').parent('div').remove();
            })
        });
    </script>
@endsection