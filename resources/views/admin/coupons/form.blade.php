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
                {!! Form::label('name', trans('coupons.name'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('image') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('image', trans('notifications.avatar'), ['class' => 'control-label']) !!}
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
                    <div class="imgprev-wrap" style="display:{{ !empty($coupons->image)?'block':'none' }}">
                        <input type="hidden" value="" name="img-hidden"/>
                        <img class="img-preview" src="{{ !empty($coupons->image)?asset($coupons->image):'' }}"
                             alt="{{ trans('notifications.avatar') }}"/>
                        <i class="fa fa-trash text-danger"></i>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="row {{ $errors->has('description') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('description', trans('notifications.meta_description'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('description', null, ['class' => 'form-control input-sm ','rows' => 5]) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('content') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('content', trans('theme::news.content'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9 form-content">
                {!! Form::textarea('content', null, ['class' => 'form-control input-sm required']) !!}
                {!! $errors->first('content', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('apply_target') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('apply_target', trans('coupons.apply_target'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('apply_target', $apply_target, null, ['class' => 'form-control input-sm select2', 'id' => 'apply_target']) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('user_id') ? 'has-error' : ''}}" id="user_id">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('user_id', trans('coupons.user_id'), ['class' => 'control-label'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('user_id', $user,  null, ['class' => 'form-control input-sm select2']) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('type') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('type', trans('coupons.type'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('type', $type, null, ['class' => 'form-control input-sm select2', 'id' => 'type']) !!}
            </td>
        </tr>
        <tr class="row apply_type_1_show" style="display: none;">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('percent_off', trans('coupons.percent_off'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::number('percent_off', null, ['class' => 'form-control input-sm', 'min' => "0", 'max'=>"100"]) !!}
            </td>
        </tr>
        <tr class="row apply_type_1_show" style="display: none;">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('max_sale', trans('coupons.max_sale'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::number('max_sale', null, ['class' => 'form-control input-sm']) !!}
            </td>
        </tr>
        <tr class="row apply_type_2_show" style="display: none;">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('sale_price', trans('coupons.sale_price'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::number('sale_price', null, ['class' => 'form-control input-sm']) !!}
            </td>
        </tr>
        <tr class="row">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('expires_at', trans('coupons.expires_at'), ['class' => 'control-label']) !!}
            </td>
            <td>
                <div class="{{ $errors->has('expires_at') ? ' has-error' : ''}}">
                    {!! Form::text('expires_at', null, ['class' => 'form-control input-sm datepicker', 'required' => 'required']) !!}
                    {!! $errors->first('expires_at', '<p class="help-block">:message</p>') !!}
                </div>
            </td>
        </tr>
        <tr class="row {{ $errors->has('active') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('active', trans('coupons.active'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::checkbox('active', 1, (isset($coupons) && $coupons->active == 1)?true:false, ['class' => 'flat-blue', 'id' => 'enable']) !!}
            </td>
        </tr>
    </table>
</div>
<div class="box-footer">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-md btn-info']) !!}
    <a href="{{ url('/admin/coupons') }}" class="btn btn-default">{{ __('message.close') }}</a>
</div>
@section('scripts-footer')
    <script type="text/javascript" src="{{ asset('plugins/ckeditor_full/ckeditor.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/ckfinder/ckfinder.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/bootstrap-datepicker.min.css') }}">
    <script type="text/javascript" src="{{ asset('plugins/datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(function () {
            var date = new Date();
            date.setDate(date.getDate());

            $('.datepicker').datepicker({
                autoclose: true,
                language: '{{ app()->getLocale() }}',
                format: 'yyyy-mm-dd',
                startDate: date
            });
            if ($("#apply_target").val() == 2)
                $("#user_id").show();
            else
                $("#user_id").hide();
            $("#apply_target").on('change', function () {
                let type_val = $(this).val();
                if (type_val == 2) {
                    $("#user_id").show();

                } else {
                    $("#user_id").hide();
                }
            });
            $("#type").on('change', function () {
                let type_val = $(this).val();
                if (type_val == 1) {
                    $(".apply_type_1_show").css('display', 'revert');
                    $(".apply_type_2_show").css('display', 'none');
                } else if (type_val == 2) {
                    $(".apply_type_2_show").css('display', 'revert');
                    $(".apply_type_1_show").css('display', 'none');
                } else {
                    $(".apply_type_2_show").css('display', 'none');
                    $(".apply_type_1_show").css('display', 'none');
                }
            });
        })
    </script>
    <script type="text/javascript">
        $(function () {
            CKEDITOR.replace('content');
            $('#image').change(function () {
                var preview = document.querySelector('img.img-preview');
                var file = document.querySelector('#image').files[0];
                var reader = new FileReader();

                if (/\.(jpe?g|png|gif)$/i.test(file.name)) {

                    reader.addEventListener("load", function () {
                        preview.src = reader.result;
                        $('.imgprev-wrap').css('display', 'block');
                        $('.inputfile-wrap').find('input[type=text]').val(file.name);
                    }, false);

                    if (file) {
                        reader.readAsDataURL(file);
                    }
                } else {
                    document.querySelector('#image').value = '';
                    $('.imgprev-wrap').find('input[type=hidden]').val('');
                }
            });

            $('.imgprev-wrap .fa-trash').click(function () {
                var preview = document.querySelector('img.img-preview');

                if (confirm('Bạn muốn xóa hình ảnh này không?')) {
                    preview.src = '';
                    $('.imgprev-wrap').css('display', 'none');
                    $('.inputfile-wrap').find('input[type=text]').val('');
                }
            })
        });
    </script>
@endsection