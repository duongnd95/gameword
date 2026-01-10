<div class="box-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif
    <div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
        {!! Form::label('title', trans('theme::promotions.title'), ['class' => 'col-md-3 control-label label-required']) !!}
        <div class="col-md-6">
            {!! Form::text('title', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
            {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    @if(isset($promotion))
        <div class="form-group {{ $errors->has('slug') ? 'has-error' : ''}}">
            {!! Form::label('slug', trans('theme::promotions.slug'), ['class' => 'col-md-3 control-label']) !!}
            <div class="col-md-6">
                {!! Form::text('slug', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('slug', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    @endif
    <div class="form-group{{ $errors->has('avatar') ? ' has-error' : ''}}">
        {!! Form::label('avatar', trans('theme::promotions.avatar'), ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-6">
            <div class="input-group inputfile-wrap inputfile-wrap-avatar">
                <input type="text" class="form-control input-sm" readonly>
                <div class="input-group-btn">
                    <button type="button" class="btn btn-danger btn-sm">
                        <i class=" fa fa-upload"></i>
                        {{ __('message.upload') }}
                    </button>
                    {!! Form::file('avatar', array_merge(['class' => 'form-control input-sm', "accept" => "image/*"])) !!}
                </div>
                {!! $errors->first('avatar', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="clearfix"></div>
            <div class="imgprev-wrap imgprev-wrap-avatar" style="display:{{ !empty($promotion->avatar)?'block':'none' }}">
                <img class="img-preview img-preview-avatar" src="{{ !empty($promotion->avatar)?asset(\Storage::url($promotion->avatar)):'' }}" alt="ảnh avatar"/>
                <i class="fa fa-trash text-danger"></i>
            </div>
        </div>
    </div>
    <div class="form-group{{ $errors->has('banner') ? ' has-error' : ''}}">
        {!! Form::label('banner', trans('theme::promotions.banner'), ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-6">
            <div class="input-group inputfile-wrap inputfile-wrap-banner">
                <input type="text" class="form-control input-sm" readonly>
                <div class="input-group-btn">
                    <button type="button" class="btn btn-danger btn-sm">
                        <i class=" fa fa-upload"></i>
                        {{ __('message.upload') }}
                    </button>
                    {!! Form::file('banner', array_merge(['class' => 'form-control input-sm', "accept" => "image/*"])) !!}
                </div>
                {!! $errors->first('banner', '<p class="help-block">:message</p>') !!}
            </div>
            <div class="clearfix"></div>
            <div class="imgprev-wrap imgprev-wrap-banner" style="display:{{ !empty($promotion->banner)?'block':'none' }}">
                <img class="img-preivew img-preview-banner" src="{{ !empty($promotion->banner)?asset(\Storage::url($promotion->banner)):'' }}" alt="ảnh banner" width="20%"/>
                <i class="fa fa-trash text-danger"></i>
            </div>
        </div>
    </div>
    <div class="form-group {{ $errors->has('date_start') ? 'has-error' : ''}}">
        {!! Form::label('date_start', trans('theme::promotions.date_start'), ['class' => 'col-md-3 control-label label-required']) !!}
        <div class="col-md-6">
            {!! Form::date('date_start', null, ['class' => 'form-control input-sm','required' => 'required']) !!}
            {!! $errors->first('date_start', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('date_end') ? 'has-error' : ''}}">
        {!! Form::label('date_end', trans('theme::promotions.date_end'), ['class' => 'col-md-3 control-label label-required']) !!}
        <div class="col-md-6">
            {!! Form::date('date_end', null, ['class' => 'form-control input-sm','required' => 'required']) !!}
            {!! $errors->first('date_end', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group{{ $errors->has('positive') ? ' has-error' : ''}}">
        {!! Form::label('positive', trans('theme::promotions.positive'), ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-6">
            {!! Form::select('positive', config('promotion.positive'), null, ['class' => 'form-control input-sm select2']) !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
        {!! Form::label('content', trans('theme::promotions.content'), ['class' => 'col-md-3 control-label']) !!}
        <div class="col-md-7">
            {!! Form::textarea('content', null, ['class' => 'form-control input-sm ckeditor']) !!}
            {!! $errors->first('content', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

<div class="box-footer">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ url('promotions') }}" class="btn btn-default">{{ __('message.close') }}</a>
</div>
@section('scripts-footer')
    <script type="text/javascript" src="{{ asset('plugins/ckeditor/ckeditor.js') }}" ></script>
    <script>
        CKEDITOR.replace( 'content', ckeditor_options);
    </script>
    <script type="text/javascript">
        $(function(){
            $('#avatar').change(function () {
                var preview = document.querySelector('img.img-preview-avatar');
                var file    = document.querySelector('#avatar').files[0];
                var reader  = new FileReader();

                if ( /\.(jpe?g|png|gif)$/i.test(file.name) ) {

                    reader.addEventListener("load", function () {
                        preview.src = reader.result;
                        $('.imgprev-wrap-avatar').css('display','block');
                        $('.inputfile-wrap-avatar').find('input[type=text]').val(file.name);
                    }, false);

                    if (file) {
                        reader.readAsDataURL(file);
                    }
                }else{
                    document.querySelector('#avatar').value = '';
                    $('.imgprev-wrap-avatar').find('input[type=text]').val('');
                }
            });

            $('.imgprev-wrap-avatar .fa-trash').click(function () {
                var preview = document.querySelector('img.img-preview-avatar');

                if(confirm('{{ __('message.confirm_delete') }}')){
                    //$('#avatar').val('').attr('required', 'required');
                    $('.imgprev-wrap-avatar').find('input[type=text]').val('');
                    preview.src = '';
                    $('.imgprev-wrap-avatar').css('display','none');
                    $('.inputfile-wrap-avatar').find('input[type=text]').val('');
                }
            });

            $('#banner').change(function () {
                var preview = document.querySelector('img.img-preview-banner');
                var file    = document.querySelector('#banner').files[0];
                var reader  = new FileReader();

                if ( /\.(jpe?g|png|gif)$/i.test(file.name) ) {

                    reader.addEventListener("load", function () {
                        preview.src = reader.result;
                        $('.imgprev-wrap-banner').css('display','block');
                        $('.inputfile-wrap-banner').find('input[type=text]').val(file.name);
                    }, false);

                    if (file) {
                        reader.readAsDataURL(file);
                    }
                }else{
                    document.querySelector('#banner').value = '';
                    $('.imgprev-wrap-banner').find('input[type=text]').val('');
                }
            });

            $('.imgprev-wrap-banner .fa-trash').click(function () {
                var preview = document.querySelector('img.img-preview-banner');

                if(confirm('{{ __('message.confirm_delete') }}')){
                    //$('#banner').val('').attr('required', 'required');
                    $('.imgprev-wrap-banner').find('input[type=text]').val('');
                    preview.src = '';
                    $('.imgprev-wrap-banner').css('display','none');
                    $('.inputfile-wrap-banner').find('input[type=text]').val('');
                }
            });

        });
    </script>
@endsection