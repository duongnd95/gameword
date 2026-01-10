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
    <table class="table table-bordered table-condensed">
        <tr class="row {{ $errors->has('title') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('title', trans('theme::categories.meta_title'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('title', null, ['class' => 'form-control input-sm', 'required' => 'required']) !!}
                {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        @if(isset($news))
        <tr class="row {{ $errors->has('slug') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('slug', trans('theme::categories.slug'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('slug', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('slug', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        @endif
        <tr class="row {{ $errors->has('parent_id') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('parent_id', trans('theme::categories.parent'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::select('parent_id', $categories, null, ['class' => 'form-control input-sm select2']) !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('active') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('active', trans('theme::news.active'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::checkbox('active', 1, (isset($category) && $category->active===1)?true:false, ['class' => 'flat-blue', 'id' => 'active']) !!}
            </td>
        </tr>
    </table>
</div>
<div class="box-footer">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-md btn-info']) !!}
    <a href="{{ url('/admin/categories') }}" class="btn btn-default" style="margin: 5px;">{{ __('message.close') }}</a>
</div>
@section('scripts-footer')
<script type="text/javascript" src="{{ asset('plugins/ckeditor_full/ckeditor.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/ckfinder/ckfinder.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/dropzone/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/dropzone/dropzone.min.js') }}"></script>
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
@if(!isset($category->gallery))
<script>
    function deleteFile(ob) {
        if (confirm('{{ __('Bạn có muốn xóa file này không?') }}')) {
            $(ob).closest('.imgprev-wrap').remove();
        }
        return false;
    }
</script>
@endif
<script>
    $('body').on('click', '.remove-gallery', function() {
        var galleryId = $(this).data("id");
        axios.get('{{ url('/ajax/deleteGallery?id=') }}' + galleryId)
            .then((response) => {
                if (response.data.success === 'ok') {
                    toastr.success("Xóa hình ảnh thành công");
                    $('.item-gallery-' + galleryId).remove();
                }
            })
            .catch((error) => {
                console.log(error);
            })
    });

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