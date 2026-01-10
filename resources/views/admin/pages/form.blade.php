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
                {!! Form::label('name', trans('theme::pages.meta_title'), ['class' => 'control-label label-required'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('name') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name', trans('theme::pages.level'), ['class' => 'control-label'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <select name="parent_id" class="form-control input-sm select2">
                    <option value="0">--Vui lòng chọn level trang--</option>
                    {{ \App\Page::showPages($pages, isset($page) ? $page->parent_id : '') }}
                </select>
            </td>
        </tr>
        @if(isset($page))
            <tr class="row {{ $errors->has('slug') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('slug', trans('theme::pages.slug'), ['class' => 'control-label'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::text('slug', null, ['class' => 'form-control input-sm']) !!}
                    {!! $errors->first('slug', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
        @endif

        <tr class="row {{ $errors->has('avatar') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('avatar', trans('theme::categories.avatar'), ['class' => 'control-label']) !!}
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
                            {!! Form::file('avatar', array_merge(['id'=>'image', 'class' => 'form-control input-sm', "accept" => "image/*"])) !!}
                        </div>
                        {!! $errors->first('avatar', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="clearfix"></div>
                    <div class="imgprev-wrap" style="display:{{ !empty($page->avatar)?'block':'none' }}">
                        <input type="hidden" value="" name="img-hidden" />
                        <img class="img-preview" src="{{ !empty($page->avatar)?asset($page->avatar):'' }}" alt="{{ trans('theme::pages.avatar') }}" />
                        <i class="fa fa-trash text-danger"></i>
                    </div>
                </div>
            </td>
        </tr>
        
        <tr class="row {{ $errors->has('keywords') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('keywords', trans('theme::pages.keywords'), ['class' => 'control-label'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('keywords', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('keywords', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('description') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('keywords', trans('theme::pages.meta_description'), ['class' => 'control-label'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('description', null, ['class' => 'form-control input-sm ','rows' => 5]) !!}
                {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('content') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('content', trans('theme::pages.content'), ['class' => 'control-label'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9 form-content">
                {!! Form::textarea('content', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('content', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('postion') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('postion', trans('theme::ads.postion'), ['class' => 'control-label'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('postion', $postion, null, ['class' => 'form-control input-sm select2 required']) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('active') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('active', trans('theme::pages.active'), ['class' => 'control-label'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <div class="col-md" style="padding-top: 5px;">
                {!! Form::checkbox('active', 1, (isset($page) && $page->active===1)?true:false, ['class' => 'flat-blue', 'id' => 'active']) !!}
                {!! $errors->first('active', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
    </table>
</div>
<div class="box-footer">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ url('admin/pages') }}" class="btn btn-default">{{ __('message.close') }}</a>
</div>
@section('scripts-footer')
    <script type="text/javascript" src="{{ asset('plugins/ckeditor_full/ckeditor.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('js/ckfinder/ckfinder.js') }}" ></script>
    <script>CKFinder.config( { connectorPath: '/ckfinder/connector' } );</script>
    <script>
        CKEDITOR.replace('content');
    </script>
    <script type="text/javascript">
        $(function(){
            $('#show-image .fa-trash').click(function(){
                var preview = document.querySelector('img.img-preview');

                if(confirm('{{ __('message.confirm_delete') }}')){
                    preview.src = '';
                    $('#show-image').css('display','none');
                    $('.inputfile-wrap').find('input[type=text]').val('');
                }
            });
            $('#show-image-js .fa-trash').click(function(){
                var preview = document.querySelector('img.img-preview-js');

                if(confirm('{{ __('message.confirm_delete') }}')){
                    preview.src = '';
                    $('#show-image-js').css('display','none');
                    $('.inputfile-wrap').find('input[type=text]').val('');
                }
            });
        });
        
        $('#image').change(function() {
            var preview = document.querySelector('img.img-preview');
            var file = document.querySelector('#image').files[0];
            var reader = new FileReader();

            if (/\.(jpe?g|png|gif)$/i.test(file.name)) {

                reader.addEventListener("load", function() {
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
    </script>
@endsection