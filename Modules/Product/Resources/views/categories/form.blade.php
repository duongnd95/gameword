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
                    {!! Form::label('name', trans('product::categories.meta_title'), ['class' => 'control-label label-required'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::text('name', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                    {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
            <tr class="row {{ $errors->has('name') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('name', trans('product::categories.level'), ['class' => 'control-label'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    <select name="parent_id" class="form-control input-sm select2">
                        <option value="0">--Vui lòng chọn level thể loại--</option>
                        {{ \Modules\Product\Entities\CategoryProduct::showCategories($categories, isset($category) ? $category->parent_id : '') }}
                    </select>
                </td>
            </tr>
            @if(isset($category))
            <tr class="row {{ $errors->has('slug') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('slug', trans('product::categories.slug'), ['class' => 'control-label'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::text('slug', null, ['class' => 'form-control input-sm']) !!}
                    {!! $errors->first('slug', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
            @endif
            <tr class="row {{ $errors->has('image') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('image', trans('product::categories.image'), ['class' => 'control-label'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    <div class="input-group inputfile-wrap ">
                        {!! Form::text('image',null,['class' => 'form-control input-sm', 'required' => 'required', 'readonly' => 'readonly', 'id' => 'ckfinder-input']) !!}
                        <div class="input-group-btn">
                            <button type="button" class="btn btn-danger btn-sm" id="ckfinder-modal"><i class=" fa fa-upload"></i></button>
                        </div>
                        {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="clearfix"></div>
                    <div id="show-image" class="imgprev-wrap" style="display:{{ !empty($category->image)?'block':'none' }}">
                        <img class="img-preview" src="{{ !empty($category->image)?asset($category->image):'' }}" alt="{{ trans('product::categories.image') }}"/>
                        <i class="fa fa-trash text-danger"></i>
                    </div>
                </td>
            </tr>
            <tr class="row {{ $errors->has('keywords') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('keywords', trans('product::categories.keywords'), ['class' => 'control-label'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::text('keywords', null, ['class' => 'form-control input-sm']) !!}
                    {!! $errors->first('keywords', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
            <tr class="row {{ $errors->has('description') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('description', trans('product::categories.meta_description'), ['class' => 'control-label'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::textarea('description', null, ['class' => 'form-control input-sm', 'rows' => 5]) !!}
                    {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
            <tr class="row {{ $errors->has('active') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('active', trans('product::categories.active'), ['class' => 'control-label'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    <div class="col-md" style="padding-top: 5px;">
                        {!! Form::checkbox('active', 1, (isset($category) && $category->active===1)?true:false, ['class' => 'flat-blue', 'id' => 'active']) !!}
                    </div>
                    {!! $errors->first('active', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
        </table>

    {{--    @php
        $requiredImage = [];
        if(!isset($category->image) || empty($category->image)){
            $requiredImage = ['required' => 'required'];
        }
    @endphp--}}
</div>
<div class="box-footer">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-primary'])
    !!}
    <a href="{{ url('/category-products') }}" class="btn btn-default">{{ __('message.close') }}</a>
</div>
@section('scripts-footer')
<script type="text/javascript" src="{{ asset('js/ckfinder/ckfinder.js') }}" ></script>
<script>CKFinder.config( { connectorPath: '/ckfinder/connector' } );</script>
<script type="text/javascript">
$(function() {
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
            $('.imgprev-wrap').find('input[type=text]').val('');
        }
    });

    $('.imgprev-wrap .fa-trash').click(function() {
        var preview = document.querySelector('img.img-preview');

        if (confirm('Bạn muốn xóa hình ảnh này ?')) {
            $('#image').val('').attr('required', 'required');
            $('.imgprev-wrap').find('input[type=text]').val('');
            preview.src = '';
            $('.imgprev-wrap').css('display', 'none');
            $('.inputfile-wrap').find('input[type=text]').val('');
        }
    })
});

var btnModal = document.getElementById('ckfinder-modal');
        btnModal.onclick = function () {
            selectFileWithCKFinder('ckfinder-input');
        };
        function selectFileWithCKFinder( elementId ) {
            CKFinder.modal( {
                chooseFiles: true,
                width: 1000,
                height: 600,
                onInit: function( finder ) {
                    finder.on( 'files:choose', function( evt ) {
                        var file = evt.data.files.first();
                        var output = document.getElementById( elementId );
                        output.value = file.getUrl();
                        var preview = document.querySelector('img.img-preview');
                        var showImage = document.getElementById('show-image');
                        showImage.style.display = "block";
                        preview.src=output.value;
                    } );

                    finder.on( 'file:choose:resizedImage', function( evt ) {
                        var output = document.getElementById( elementId );
                        output.value = evt.data.resizedUrl;
                    } );
                }
            } );
        }
</script>
@endsection