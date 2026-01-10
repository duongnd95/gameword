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
                {!! Form::label('name', trans('booking::payments.name'), ['class' => 'control-label label-required'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('Bank Type', 'Bank Type', ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <select name="type" class="form-control input-sm select2" required>
                    @foreach(config('settings.bank_type') as $key => $val)
                        <option {{ (isset($payment) && $payment->type == $key ? "selected" : "") }} value="{{ $key }}"> {{ $val }}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr class="row {{ $errors->has('image') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('image', trans('booking::payments.image'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <div>
                    <div class="input-group inputfile-wrap ">
                        <input type="text" class="form-control input-sm" readonly>
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-danger btn-sm">
                                <i class=" fa fa-upload"></i>
                                {{ __('message.upload') }}
                            </button>
                            {!! Form::file('image', array_merge(['id'=>'image', 'class' => 'form-control input-sm', "accept" => "image/*"])) !!}
                        </div>
                        {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="clearfix"></div>
                    <div class="imgprev-wrap" style="display:{{ !empty($payment->image)?'block':'none' }}">
                        <input type="hidden" value="" name="img-hidden"/>
                        <img class="img-preview" src="{{ !empty($payment->image)?asset($payment->image):'' }}" alt="{{ trans('events.image') }}"/>
                        <i class="fa fa-trash text-danger"></i>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="row {{ $errors->has('arrange') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('arrange', trans('theme::sliders.arrange'), ['class' => 'control-label'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::number('arrange', isset($payment) ? $payment->arrange : $arrange, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('arrange', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('active') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('active', trans('theme::products.active'), ['class' => 'control-label'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <div class="col-md" style="padding-top: 5px;">
                    {!! Form::checkbox('active', 1, (isset($payment) && $payment->active===1)?true:false, ['class' => 'flat-blue', 'id' => 'active']) !!}
                </div>
                {!! $errors->first('active', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>

    </table>
</div>
<div class="box-footer">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-primary'])
    !!}
    <a href="{{ url('/bookings/payments') }}" class="btn btn-default">{{ __('message.close') }}</a>
</div>
@section('scripts-footer')
    <script type="text/javascript">
        $(function(){

            $('#image').change(function () {
                var preview = document.querySelector('img.img-preview');
                var file    = document.querySelector('#image').files[0];
                var reader  = new FileReader();

                if ( /\.(jpe?g|png|gif)$/i.test(file.name) ) {

                    reader.addEventListener("load", function () {
                        preview.src = reader.result;
                        $('.imgprev-wrap').css('display','block');
                        $('.inputfile-wrap').find('input[type=text]').val(file.name);
                    }, false);

                    if (file) {
                        reader.readAsDataURL(file);
                    }
                }else{
                    document.querySelector('#image').value = '';
                    $('.imgprev-wrap').find('input[type=hidden]').val('');
                }
            });

            $('.imgprev-wrap .fa-trash').click(function () {
                var preview = document.querySelector('img.img-preview');

                if(confirm('{{ __('message.confirm_delete') }}')){
                    preview.src = '';
                    $('.imgprev-wrap').css('display','none');
                    $('.inputfile-wrap').find('input[type=text]').val('');
                }
            })
        });
    </script>
@endsection