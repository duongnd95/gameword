<div class="box-body">
    @if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
        <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
        @endforeach
    </div>
    @endif
        <table class="table table-bordered table-condensed">
            <tr class="row {{ $errors->has('key') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('key', trans('product::attributes.type'), ['class' => 'control-label label-required'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::text('key', null, ['class' => 'form-control input-sm required']) !!}
                </td>
            </tr>
            <tr class="row {{ $errors->has('value') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('value', trans('product::attributes.name'), ['class' => 'control-label label-required'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    <select name="value[]" id="" class="form-control input-sm required js-select" multiple>
                        @isset($att)
                            @foreach ($att as $val)
                                <option value="{{$val}}" selected>{{$val}}</option>
                            @endforeach
                        @endisset
                    </select>
                    {{--  {!! Form::select('value[]', isset($att) ? $att : [],null , ['class' => 'form-control input-sm select2 required js-select', 'multiple'=>'multiple']) !!}  --}}
                    {!! $errors->first('value', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
            <tr class="row {{ $errors->has('active') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('active', trans('product::categories.active'), ['class' => 'control-label'])
                        !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    <div class="col-md" style="padding-top: 5px;">
                    {!! Form::checkbox('active', 1, (isset($attr) && $attr->active===1)?true:false, ['class' => 'flat-blue', 'id' => 'active']) !!}
                    {!! $errors->first('active', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
        </table>
<div class="box-footer">
    {!! Form::submit(isset($submitButtonText) ? $submitButtonText : __('message.save'), ['class' => 'btn btn-primary'])
    !!}
    <a href="{{ url('/attribute-products') }}" class="btn btn-default">{{ __('message.close') }}</a>
</div>
@section('scripts-footer')
<script  type="text/javascript" >
    $(document).ready(function() {
        $('.js-select').select2({
            placeholder: "",
            tags: true,
            allowClear: true,
            theme: "classic"
        });
    }); 
</script>
@endsection