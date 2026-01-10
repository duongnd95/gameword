@extends('theme::front-end.master')
@section('title')
    <title>{{ $page->name . " | " . $settings['meta_title'] }}</title>
    <meta name="description"
          content="{{ !empty($page->description) ? \Illuminate\Support\Str::limit($page->description, 200) : $settings['meta_description'] }}"/>
    <meta name="keywords" content="{{ !empty($page->keywords) ? $page->keywords : $settings['meta_keyword'] }}"/>
@endsection
@section('schema')
    <script type="application/ld+json">
    {
        "@context": "http://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [{
                "@type": "ListItem",
                "position": 1,
                "item": {
                    "@id": "{{ url('/')}}",
                    "name": "{{ trans('theme::frontend.home.home') }}"
                }
            },
            {
                "@type": "ListItem",
                "position": 2,
                "item": {
                    "@id": "{{ Request::fullUrl() }}",
                    "name": "{{ $page->name }}"
                }
            }
        ]
    }



    </script>
@endsection
@section('content')
    <div class="container">
        <div class="bread__crumb clearfix">
            <a href="{{ url('/')}}"><i class="fa fa-home"></i></a> / <strong> {{ $menu->title }}</strong>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-md-8">
                <div class="text-center">
                    <h5 class="contact__title">{{ _("Liên hệ") }}</h5>
                    <p>Điện thoại: <strong>{{ $settings['company_phone'] }}</strong></p>
                    <p>Email: <strong>{{ $settings['company_email'] }}</strong></p>
                    <p>Địa chỉ: <strong>{{ $settings['company_address'] }}</strong></p>
                </div>
                {!! Form::open(['method' => 'POST', 'url' => '', 'role' => 'contact', 'id' => 'contact'])  !!}
                <div class="form__contact">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            {!! Form::text('fullname', null, ['class' => 'form-control input-sm', 'placeholder' => "Họ và tên (*)", 'required'=>true]) !!}
                            {!! $errors->first('fullname', '<p class="help-block">:message</p>') !!}
                        </div>
                        <div class="col-md-6">
                            {!! Form::text('address', null, ['class' => 'form-control input-sm', 'placeholder' => "Địa chỉ (*)", 'required'=>true]) !!}
                            {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6">
                            {!! Form::text('phone', null, ['class' => 'form-control input-sm', 'placeholder' => "Số điện thoại (*)", 'required'=>true]) !!}
                            {!! $errors->first('phone', '<p class="help-block">:message</p>') !!}
                        </div>
                        <div class="col-md-6">
                            {!! Form::email('email', null, ['class' => 'form-control input-sm', 'placeholder' => "Email (*)", 'required'=>true]) !!}
                            {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            {!! Form::textarea('message', null, ['class' => 'form-control input-sm', 'placeholder' => "Yêu cầu của bạn (*)", 'required'=>true, 'rows' => 4]) !!}
                            {!! $errors->first('message', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                    <div class="g-recaptcha" id="feedback-recaptcha"
                         data-sitekey="6LcSa-wcAAAAAIO9Px9-Ni_SMmMAcyKhVjjzN9kM"></div>

                    <div class="mt-2 d-flex justify-content-center">
                        {!! Form::submit(__('message.send'), ['class' => 'btn btn-md btn-success']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@section('script')
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <script type="text/javascript" src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/axios.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('#contact').submit(function(e){
                e.preventDefault();
                axios.post('{{ url('/lien-he/ajax') }}', $(this).serialize())
                    .then(function (response) {
                        const data = response.data;
                        if (data.success == "ok"){
                            $('#contact')[0].reset();
                            toastr.success('Thêm liên hệ thành công !');
                        }else{
                            let err = data.errors;
                            let mess = err.join("<br/>");
                            toastr.error(mess);
                        }
                    })
                    .catch(function (error){
                        alert("{{ __('frontend.error') }}");
                    });
            });
        })
    </script>
@endsection
