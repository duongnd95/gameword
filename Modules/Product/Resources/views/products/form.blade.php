@section('css')
    <link href="{{ asset('plugins/dropzone/dropzone.min.css') }}">
    <style>
        .quote-imgs-thumbs {
            background: #eee;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
            margin: 1.5rem 0;
            padding: 0.75rem;
        }

        .quote-imgs-thumbs--hidden {
            display: none;
        }

        .img-preview-thumb {
            background: #fff;
            border: 1px solid #777;
            border-radius: 0.25rem;
            box-shadow: 0.125rem 0.125rem 0.0625rem rgba(0, 0, 0, 0.12);
            margin-right: 1rem;
            max-width: 100px;
            max-height: 100px;
            padding: 0.25rem;
        }

        .galleries {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
        }

        .galleries .gallery img {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            -o-object-fit: cover;
            object-fit: cover;
        }

        .imgprev-wrap {
            background: #f7f7f7;
            margin-top: 10px;
            padding: 5px;
            border: 1px dashed #ccc;
            border-radius: 1px;
            width: 100%;
            position: relative;
        }

        .galleries .gallery {
            -ms-flex: 0 0 25%;
            flex: 0 0 25%;
            max-width: 25%;
            padding-bottom: 15%;
        }
    </style>
@endsection
<div class="box-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif
    <div>
        <input type="hidden" name="back_url" value="{{ !empty($backUrl) ? $backUrl : '' }}">
    </div>
    <table class="table table-bordered table-condensed">
        <tr class="row {{ $errors->has('name') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('name', trans('product::products.meta_title'), ['class' => 'control-label label-required'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('name', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        @if(isset($product))
            <tr class="row {{ $errors->has('slug') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('slug', trans('theme::products.slug'), ['class' => 'control-label'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::text('slug', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                    {!! $errors->first('slug', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
        @endif
        <tr class="row {{ $errors->has('category_id') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('category_id', trans('theme::products.category'), ['class' => 'control-label'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('category_id', $categories, null, ['class' => 'form-control input-sm select2']) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('provider_id') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('provider_id', trans('product::providers.provider'), ['class' => 'control-label'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('provider_id', $providers, null, ['class' => 'form-control input-sm select2']) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('image') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('image', trans('theme::products.image'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <div>
                    <div class="input-group inputfile-wrap" id="inputfile-wrap-1">
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
                    <div class="imgprev-wrap" style="display:{{ !empty($product->image)?'block':'none' }}">
                        <input type="hidden" value="" name="img-hidden"/>
                        <img class="img-preview" src="{{ !empty($product->image)?asset($product->image):'' }}"
                             alt="{{ trans('events.image') }}"/>
                        <i class="fa fa-trash text-danger"></i>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="row {{ $errors->has('description') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('description', trans('theme::products.meta_description'), ['class' => 'control-label'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('description', null, ['class' => 'form-control input-sm required', 'rows' => 5]) !!}
                {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('content') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('content', trans('theme::products.content'), ['class' => 'control-label'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9 form-content">
                {!! Form::textarea('content', null, ['id' => 'text', 'class' => 'form-control input-sm required']) !!}
                {!! $errors->first('content', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        {{--Giá sản phẩm--}}
        <tr class="row {{ $errors->has('price') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('price', trans('theme::products.price'), ['class' => 'control-label'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('price', isset($product) && !empty($product->price) ? number_format($product->price) : null, ['class' => 'form-control input-sm ', 'required' => 'required', 'id' =>
        'price']) !!}
                {!! $errors->first('price', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        {{--Giá so sánh--}}
        <tr class="row {{ $errors->has('price_compare') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('price_compare', trans('theme::products.price_compare'), ['class' => 'control-label'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('price_compare', isset($product) && !empty($product->price_compare) ? number_format($product->price_compare) : null, ['class' => 'form-control input-sm', 'id' => 'price_compare']) !!}
                {!! $errors->first('price_compare', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        @php($products_other = \Modules\Product\Entities\Product::with('category')->where('id','<>', $product->id??"")->pluck('name','id'))
        <tr class="row {{ $errors->has('related') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('related', trans('product::products.product_related'), ['class' => 'control-label'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('related[]', $products_other, isset($related)?$related:null, ['class' => 'form-control input-sm select2', 'multiple' => 'multiple']) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('together') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('related', trans('product::products.product_together'), ['class' => 'control-label'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('together[]', $products_other,  isset($together)?$together:null, ['class' => 'form-control input-sm select2', 'multiple' => 'multiple']) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('images') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('images', trans('theme::products.gallery'), ['class' => 'control-label']) !!}
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
                            {!! Form::file('images[]', array_merge(['id'=>'image_gallery', 'class' => 'form-control input-sm', "accept" => "image/*", 'multiple'=> 'multiple'])) !!}
                        </div>
                        {!! $errors->first('images', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="clearfix"></div>
                    <div id="previews" class="galleries">
                        @isset($product->gallery)
                            @if(!empty($product->gallery))
                                @foreach(\Modules\Product\Entities\GalleryProduct::where('product_id', $product->id)->get() as $file)
                                    <div class="gallery imgprev-wrap imgprev-wrap-gallery" style="display:block">
                                        <input type="hidden" name="images[]" value="{{ $file->image }}">
                                        <img class="img-preview" src="{{ asset($file->image) }}" alt="">
                                        <i class="fa fa-trash text-danger" onclick="return deleteFile(this)"></i>
                                    </div>
                                @endforeach
                            @endif
                        @endisset
                    </div>
                </div>
            </td>
        </tr>
        <tr class="row {{ $errors->has('gift') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('gift', trans('product::products.gift'), ['class' => 'control-label'])
                    !!}
                <i class="btn btn-sm btn-danger fa fa-plus" id="add_gifts" style="margin-left: 10px;"
                   title="Thêm quà tặng"></i>
            </td>
            <td class="col-md-8 col-lg-9" id="div_gifts">
            </td>
        </tr>
        <tr class="row {{ $errors->has('color') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('color', trans('product::products.color'), ['class' => 'control-label'])
                    !!}
                <i class="btn btn-sm btn-danger fa fa-plus" id="add_colors" style="margin-left: 15px;"
                    title="Thêm màu sắc"></i>
            </td>
            <td class="col-md-8 col-lg-9" id="div_colors">
            </td>
        </tr>
        <tr class="row {{ $errors->has('arrange') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('arrange', trans('theme::sliders.arrange'), ['class' => 'control-label'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::number('arrange', isset($product) ? $product->arrange : $arrange, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('arrange', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('hot') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('hot', trans('product::products.hot'), ['class' => 'control-label'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <div class="col-md" style="padding-top: 5px;">
                    {!! Form::checkbox('hot', 1, (isset($product) && $product->hot===1)?true:false, ['class' => 'flat-blue', 'id' => 'hot']) !!}
                </div>
                {!! $errors->first('hot', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('new') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('new', trans('product::products.new'), ['class' => 'control-label'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <div class="col-md" style="padding-top: 5px;">
                    {!! Form::checkbox('new', 1, (isset($product) && $product->new===1)?true:false, ['class' => 'flat-blue', 'id' => 'new']) !!}
                </div>
                {!! $errors->first('new', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('active') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('active', trans('product::products.active'), ['class' => 'control-label'])
                    !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <div class="col-md" style="padding-top: 5px;">
                    {!! Form::checkbox('active', 1, (isset($product) && $product->active===1)?true:false, ['class' => 'flat-blue', 'id' => 'active']) !!}
                </div>
                {!! $errors->first('active', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
    </table>

    <div class="box-footer">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-primary'])!!}
        <a href="{{ !empty($backUrl) ? $backUrl : url('products') }}"
           class="btn btn-default">{{ __('message.close') }}</a>
    </div>

    @section('scripts-footer')
        <script type="text/javascript" src="{{asset('/js/sweetalert2.min.js')}}"></script>
        <script type="text/javascript" src="{{ asset('plugins/ckeditor_full/ckeditor.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/ckfinder/ckfinder.js') }}"></script>
        <script>CKFinder.config({connectorPath: '/ckfinder/connector'});</script>
        <script type="text/javascript" src="{{ asset('plugins/dropzone/jquery-ui.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('plugins/dropzone/dropzone.min.js') }}"></script>
        <script type="text/javascript">
            let i = -1;
            $("#add_gifts").click(function () {
                ++i;
                $("#div_gifts").append('<div class="row">' +
                    '<div class="col-lg-3">' +
                    '<input type="text" name="gifts[' + i + '][name]" placeholder="Tên sản phẩm" class="form-control input-sm" />' +
                    '</div>' +
                    '<div class="col-lg-3">' +
                    '<input type="number" name="gifts[' + i + '][price]" placeholder="Giá sản phẩm" class="form-control input-sm" />' +
                    '</div>' +
                    '<div class="col-lg-3">' +
                    '<input type="file" name="gifts[' + i + '][image]" accept="image/*" >' +
                    '</div>' +
                    '<div class="col-lg-3">' +
                    '<i class="fa fa-times-circle btn btn-xs btn-danger remove-tr"></i>' +
                    '</div>' +
                    '</div>');
            });
            $(document).on('click', '.remove-tr', function () {
                $(this).parents('div.row').remove();
            });
        </script>
        <script type="text/javascript">
            let x = -1;
            $("#add_colors").click(function () {
                ++x;
                $("#div_colors").append('<div class="row">' +
                    '<div class="col-lg-3">' +
                    '<input type="text" name="colors[' + x + '][name]" placeholder="Màu sắc" class="form-control input-sm" />' +
                    '</div>' +
                    '<div class="col-lg-3">' +
                    '<input type="file" name="colors[' + x + '][image][]" accept="image/*" multiple>' +
                    '</div>' +
                    '<div class="col-lg-3">' +
                    '<i class="fa fa-times-circle btn btn-xs btn-danger remove-tr"></i>' +
                    '</div>' +
                    '</div>');
            });
            $(document).on('click', '.remove-tr', function () {
                $(this).parents('div.row').remove();
            });
        </script>
        <script type="text/javascript">
            var PreviewMultipleImage = function (input) {
                if (input.files) {
                    var filesAmount = input.files.length;
                    for (var i = 0; i < filesAmount; i++) {
                        var html = "";
                        html += '<div class="gallery imgprev-wrap imgprev-wrap-gallery" style="display:block">'
                            + '<input type="hidden" name="images[]" class="form-control input-sm" readonly value="' + event.target.files[i] + '" />'
                            + '<img class="img-preview" src="' + URL.createObjectURL(event.target.files[i]) + '"/>'
                            + '<i class="fa fa-trash text-danger" onclick="return deleteFile(this)"></i>'
                            + '</div>';
                        $('#previews').append(html);
                    }
                }
            };
            $('#image_gallery').on('change', function () {
                PreviewMultipleImage(this);
            });
        </script>
        <script type="text/javascript">
            function deleteFile(ob) {
                if (confirm('{{ __('Bạn có muốn xóa file này không?') }}')) {
                    $(ob).closest('.imgprev-wrap').remove();
                }
                return false;
            }

            $(function () {
                $("#previews,#files-list").sortable({
                    items: '.gallery',
                    cursor: 'move',
                    opacity: 0.5,
                    containment: '#previews,#files-list',
                    distance: 20,
                    tolerance: 'pointer',
                });
                $("#previews,#files-list").disableSelection();
            });

        </script>
        <script>
            CKEDITOR.replace('content');

            $(function () {

                $('#image').change(function () {
                    var preview = document.querySelector('img.img-preview');
                    var file = document.querySelector('#image').files[0];
                    var reader = new FileReader();

                    if (/\.(jpe?g|png|gif)$/i.test(file.name)) {

                        reader.addEventListener("load", function () {
                            preview.src = reader.result;
                            $('.imgprev-wrap').css('display', 'block');
                            $('#inputfile-wrap-1').find('input[type=text]').val(file.name);
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

                    if (confirm('{{ __('message.confirm_delete') }}')) {
                        preview.src = '';
                        $('.imgprev-wrap').css('display', 'none');
                        $('.inputfile-wrap').find('input[type=text]').val('');
                    }
                })
            });
        </script>
        <script type="text/javascript">
            $(function () {
                $('#show-image .fa-trash').click(function () {
                    var preview = document.querySelector('img.img-preview');
                    if (confirm('Bạn muốn xóa hình ảnh này ?')) {
                        $('#image').val('').attr('required', 'required');
                        preview.src = '';
                        $('.imgprev-wrap').css('display', 'none');
                        $('.inputfile-wrap').find('input[type=text]').val('');
                    }
                })
            });
        </script>
        <script>
            function changePrice(idObj) {
                idObj.addEventListener('keyup', function () {
                    var n = parseInt(this.value.replace(/\D/g, ''), 10);
                    idObj.value = Number.isNaN(n) ? 0 : n.toLocaleString('en');
                }, false);
            }

            changePrice(document.getElementById("price"));
            changePrice(document.getElementById("price_compare"));
        </script>
        <script>
            function deleteFile(ob) {
                if (confirm('{{ __('Bạn có muốn xóa file này không?') }}')) {
                    $(ob).closest('.imgprev-wrap').remove();
                }
                return false;
            }

            $(function () {
                $("#previews").sortable({
                    items: '.gallery',
                    cursor: 'move',
                    opacity: 0.5,
                    containment: '#previews,#multi-gallery',
                    distance: 20,
                    tolerance: 'pointer',
                });
                $("#previews").disableSelection();
            });
        </script>
@endsection