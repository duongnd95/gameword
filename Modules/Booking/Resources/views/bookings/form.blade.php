<div class="box-body">
    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p><i class="fa fa-fw fa-check"></i> {{ $error }}</p>
            @endforeach
        </div>
        @endif
    <!--CUSTOMER INFO-->
    <div>
        <input type="hidden" name="back_url" value="{{ !empty($backUrl) ? $backUrl : '' }}">
    </div>
    {!! Form::hidden('customer[id]', null, ['class' => 'form-control input-sm customer_id']) !!}
    <table class="table table-bordered table-condensed">
        <tr class="row">
            <th colspan="2">
                {{ __('booking::bookings.customer_info') }}
            </th>
        </tr>
        <tr class="row {{ $errors->has('customer.phone') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('customer[phone]', trans('booking::customers.phone'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('customer[phone]', isset($phone) && !empty($phone) ? $phone->phone : null, ['class' => 'form-control input-sm', 'required' => 'required', 'autocomplete' => 'off', 'id' => 'customer_phone']) !!}
                {!! $errors->first('customer.phone', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('customer.name') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('customer[name]', trans('booking::customers.name'), ['class' => 'control-label label-required']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('customer[name]', null, ['class' => 'form-control input-sm customer_name', 'required' => 'required']) !!}
                {!! $errors->first('customer.name', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('customer.email') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('customer[email]', trans('booking::customers.email'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::email('customer[email]', null, ['class' => 'form-control input-sm ', 'id' => 'customer_email']) !!}
                <span id="email_auto" style="position: absolute;right: 20px;top: 5px;"></span>
                {!! $errors->first('customer.email', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('customer.address') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('customer[address]', trans('booking::customers.address'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('customer[address]', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('customer.address', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('customer.permanent_address') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('customer[permanent_address]', trans('booking::customers.permanent_address'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('customer[permanent_address]', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('customer.permanent_address', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('customer.gender') ? ' has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('customer[gender]', __('booking::customers.gender'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <label for="boy">
                    {!! Form::radio('customer[gender]', 1, isset($booking->customer) && $booking->customer->gender===1?true:false, ['class' => '', 'id' => 'boy']) !!}
                    {{ __('message.user.gender_male') }}
                </label>&nbsp;
                <label for="girl">
                    {!! Form::radio('customer[gender]', 0, isset($booking->customer) && $booking->customer->gender===0?true:false, ['class' => '', 'id' => 'girl']) !!}
                    {{ __('message.user.gender_female') }}
                </label>
                {!! $errors->first('customer.gender', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('customer.facebook') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('customer[facebook]', trans('booking::customers.facebook'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('customer[facebook]', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('customer.facebook', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <tr class="row {{ $errors->has('customer.zalo') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('customer[zalo]', trans('booking::customers.zalo'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::text('customer[zalo]', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('customer.zalo', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        <!--BOOKING INFO-->
        <tr class="row">
            <th colspan="2">
                {{ __('booking::bookings.booking_info') }}
            </th>
        </tr>
        <tr class="row {{ $errors->has('note') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('', $serviceInfo['label'], ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                <select id="product-js" class="form-control input-sm select2" onchange="changeProduct(this)">
                    @foreach($services as $key => $val)
                        <option value="{{ $key }}">{{ $val }}</option>
                    @endforeach
                </select>
                <div id="products-js" style="display: {{ !empty($booking->services) ? 'block' : 'none' }}; margin-top: 5px;">
                    <table class="table table-bordered" id="products-table-js">
                        <thead>
                        <tr class="row">
                            <th class="col-md-6">
                                Tên sản phẩm
                            </th>
                            <th class="col-md-2">
                                Giá (đ)
                            </th>
                            <th class="col-md-2">
                                Số lượng
                            </th>
                            <th class="col-md-2">
                                Thành tiền (đ)
                            </th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($booking->services))
                            @foreach($booking->services as $item)
                                <tr id="result{{ $item->id }}" class="row">
                                    <td class="col-md-6">
                                        {{ $item->name }}
                                    </td>
                                    <td class="col-md-2">
                                        {{ number_format($item->price) }}
                                        <input type="hidden" name="product[{{ $item->id }}][price]" value="{{ $item->price }}">
                                    </td>
                                    <td class="col-md-2">
                                        <input type="number" data-id="{{ $item->id }}" data-price="{{ $item->price }}" class="form-control form-control-sm btn-js"
                                               min="1" onkeyup="updateQty(this,{{ $item->id }},{{ $item->price }})" value="{{ $item->pivot->quantity }}" name="product[{{ $item->id }}][quantity]" onclick="updateQty(this,{{ $item->id }},{{ $item->price }})"/>
                                        <input type="hidden" name="qty-js-{{ $item->id }}" value="{{ $item->pivot->quantity }}">
                                    </td>
                                    <td class="col-md-2" id="total-price{{ $item->id }}">
                                        {{ number_format($item->price*$item->pivot->quantity) }} đ
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="delProduct({{ $item->id }})">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        </button>
                                        <input type="hidden" name="price-js-{{ $item->id }}" value="{{ $item->price*$item->pivot->quantity }}">
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        <tr class="row">
                            <th colspan="3" class="col-md-10">Tổng</th>
                            <th colspan="2" class="col-md-2" id="total-js">{{ !empty($booking->total_price) ? number_format($booking->total_price) : 0 }} đ</th>
                            <input type="hidden" name="total_price" value="{{ !empty($booking->total_price) ? $booking->total_price : 0 }}"/>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
        <tr class="row {{ $errors->has('note') ? 'has-error' : ''}}">
            <td class="col-md-4 col-lg-3">
                {!! Form::label('note', trans('booking::bookings.note'), ['class' => 'control-label']) !!}
            </td>
            <td class="col-md-8 col-lg-9">
                {!! Form::textarea('note', null, ['class' => 'form-control input-sm']) !!}
                {!! $errors->first('note', '<p class="help-block">:message</p>') !!}
            </td>
        </tr>
        @if(\Auth::user()->roleBelongToCompany())
            <tr class="row {{ $errors->has('approve_id') ? 'has-error' : ''}}">
                <td class="col-md-4 col-lg-3">
                    {!! Form::label('approve_id', trans('booking::bookings.approve'), ['class' => 'control-label']) !!}
                </td>
                <td class="col-md-8 col-lg-9">
                    {!! Form::select('approve_id', !empty($approves) ? $approves : null, null, ['class' => 'form-control input-sm select2']) !!}
                    {!! $errors->first('approve_id', '<p class="help-block">:message</p>') !!}
                </td>
            </tr>
        @endif
    </table>
</div>
<div class="box-footer">
    {{--<button type="submit" name="approved" value="{{ config('settings.approved.tmp') }}" class='btn btn-warning'>{{ __('booking::bookings.btn_tmp') }}</button>--}}
    <button type="submit" name="approved" class='btn btn-primary'>{{ __('message.save')  }}</button>
    <a href="{{ !empty($backUrl) ? $backUrl : url('/bookings/'.Route::input('module')) }}" class="btn btn-default">{{ __('message.close') }}</a>
    {{--{!! Form::submit(isset($submitButtonText) ? $submitButtonText." ".__('message.and_add') : __('message.save')." ".__('message.and_add'), ['class' => 'btn btn-success', 'name' => "btn-add"]) !!}--}}
</div>
@section('scripts-footer')
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
    <script type="text/javascript" src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}" ></script>
    <link rel="stylesheet" href="{{ asset('plugins/autocomplete/jquery.autocomplete.min.css') }}">
    <script type="text/javascript" src="{{ asset('plugins/autocomplete/jquery.autocomplete.min.js') }}" ></script>
    <script type="text/javascript">
        const updateCustomerInfo = (customer = null) => {
            let isReset = false;
            if(customer == null) {
                isReset = true;
                customer = {
                    id: '',
                    name: '',
                    phone: '',
                    email: '',
                    phone_other: [],
                    address: '',
                    permanent_address: '',
                    facebook: '',
                    zalo: '',
                    gender: ''
                };
                $('#phone_auto').html('');
                $('#email_auto').html('');
            }
            $("[name='customer[id]']").val(customer.id);
            $("[name='customer[name]']").val(customer.name);
            $("[name='customer[email]']").val(customer.email);
            $("[name='customer[phone]']").val(customer.phone);
            $("[name='customer[address]']").val(customer.address);
            $("[name='customer[permanent_address]']").val(customer.permanent_address);
            $("[name='customer[facebook]']").val(customer.facebook);
            $("[name='customer[zalo]']").val(customer.zalo);
            if (customer.gender === 1) {
                $("#boy").prop("checked", true);
            } else if (customer.gender === 0) {
                $("#girl").prop("checked", true);
            }else{
                $("#boy").prop("checked", false);
                $("#girl").prop("checked", false);
            }
        };
        $(function(){
            var nameFile = {!! isset($nameFile) ? json_encode($nameFile) : "''" !!};
            $.each(nameFile, function (key, name) {
                $("[name='properties["+name+"]']").change(function (e) {
                    var fileInput = this;
                    if (fileInput.files[0]){
                        var reader = new FileReader();
                        reader.readAsDataURL(fileInput.files[0]);
                    }
                });
            });

            $("[name='services[]']").change(function () {
                var idServices = $(this).val();
                var moduleName = '{{ $serviceInfo['moduleName'] }}';
                var nameElement = {!! isset($nameSelect) ? json_encode($nameSelect) : "''" !!};
                if (nameElement){
                    $.each(nameElement, function (key, item) {
                        $("[name='"+ item +"']").html('<option value="">{{ __('message.loading') }}</option>');
                    });
                    $.ajax({
                        url: '{{ url('bookings/ajax/getDataService') }}',
                        type: "get",
                        dataType: "json",
                        data: {
                            idServices: idServices,
                            moduleName: moduleName
                        },
                        success: function (data) {
                            $.each(nameElement, function (key, item) {
                                $("[name='"+ item +"']").html('<option value="">{{ __('message.please_select') }}</option>');
                            });
                            $.each(data, function (key, item) {
                                $.each(nameElement, function (keyName, valueName) {
                                    if (key == valueName){
                                        $.each(item, function (itemKey, itemValue) {
                                            $("[name='"+ valueName +"']").append('<option value="'+ itemKey +'">'+ itemValue +'</option>');
                                        });
                                    }
                                });
                            });
                        }
                    })
                }
            });
            @if(!Auth::user()->roleBelongToCustomer())
            $('#customer_phone').autocomplete({
                paramName: 'phone',
                minChars: 3,
                dataType: 'json',
                serviceUrl: '{{ url('bookings/ajax/getCustomerByPhone') }}',
                transformResult: function(response) {
                    return {
                        suggestions: $.map(response, function(item) {
                            return { value: item.phone , data: item.id , item: item };
                        })
                    };
                },
                onSelect: function (suggestion) {
                    $('#phone_auto').html(deleteCustomer());
                    $('#email_auto').html('');
                    updateCustomerInfo(suggestion.item);
                }
            });
            $('#customer_email').autocomplete({
                paramName: 'email',
                minChars: 3,
                dataType: 'json',
                serviceUrl: '{{ url('bookings/ajax/getCustomerByEmail') }}',
                transformResult: function(response) {
                    return {
                        suggestions: $.map(response, function(item) {
                            return { value: item.email , data: item.id , item: item };
                        })
                    };
                },
                onSelect: function (suggestion) {
                    $('#phone_auto').html('');
                    $('#email_auto').html(deleteCustomer());
                    updateCustomerInfo(suggestion.item);
                }
            });
            @endif

            function deleteCustomer() {
                return '<a href="javascript:void(0)" onclick="updateCustomerInfo()" class="magic-customer"><i class="fa fa-trash text-danger"></i></a>';
            }
            $('.datepicker').datepicker({
                autoclose: true,
                language: '{{ app()->getLocale() }}',
                format: '{{ config('settings.format.date_js') }}'
            });
        });
    </script>

    <script type="text/javascript">
        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
        }
        function changeProduct(ob) {
            let productId = ob.value;
            $('#products-js').slideDown();
            var totalPrice = $('[name="total_price"]').val();
            axios.get('{{ url('bookings/ajax/getProduct?id=') }}' + productId)
            .then((res) => {
                let product = res.data;
                let html = '';
                html += `<tr id="result`+product.id+`" class="row">`
                        +`<td class="col-md-6">`+product.name+`</td>`
                        +`<td class="col-md-2">`+formatNumber(product.price)
                            + `<input type="hidden" name="product[`+product.id+`][price]" value="`+product.price+`">`
                        +`</td>`
                        +`<td class="col-md-2">`
                            + `<input type="number" data-id="`+product.id+`" data-price="`+product.price+`" class="form-control form-control-sm btn-js"
                                min="1" onkeyup="updateQty(this,`+product.id+`,`+product.price+`)" value="1" name="product[`+product.id+`][quantity]" onclick="updateQty(this,`+product.id+`,`+product.price+`)"/>`
                            + `<input type="hidden" name="qty-js-`+product.id+`" value="1">`
                        +`</td>`
                        +`<td class="col-md-2" id="total-price`+product.id+`">`+formatNumber(product.price)+`</td>`
                        +`<td>`
                            + `<button type="button" class="btn btn-sm btn-danger" onclick="delProduct(`+product.id+`)">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </button>`
                            + `<input type="hidden" name="price-js-`+product.id+`" value="`+product.price+`">`
                        +`</td>`
                     + `</tr>`;
                $('#products-table-js tbody').prepend(html);
                let total = parseInt(totalPrice) + parseInt(product.price);
                $('#total-js').html(formatNumber(total) + '&nbsp;₫');
                $('[name="total_price"]').attr('value', total);
            })
            .catch((err) => {
                console.log("err", err);
                alert('Có lỗi xảy ra. Vui lòng kiểm tra lại!');
            })
        }
        function updateQty(ob,id,price){
            var qty = ob.value;
            if(qty < 1) return alert('Vui lòng nhập số lượng lớn hơn 0!');
            changeQtyPrice(id,qty,price);
        }
        function changeQtyPrice(id,qty,price){
            var total = $('[name="total_price"]').val();
            var hidQty = $('[name="qty-js-'+id+'"]').val();
            var totalPrice = qty*price;
            $('[name="qty-js-'+id+'"]').attr('value',qty);
            qty = qty - hidQty;
            total = parseInt(total) + parseInt(price*qty);
            $('#total-price'+id).html(formatNumber(totalPrice) + '&nbsp;₫');
            $('#total-js').html(formatNumber(total) + '&nbsp;₫');
            $('[name="price-js-'+id+'"]').attr('value', totalPrice);
            $('[name="total_price"]').attr('value', total);
        }
        function delProduct(id) {
            var total = $('[name="total_price"]').val();
            var totalPrice = $('[name="price-js-'+id+'"]').val();
            $('#result' + id).remove();
            $('#total-js').html(formatNumber(total-totalPrice) + '&nbsp;₫');
            $('[name="total_price"]').attr('value', total-totalPrice);
        }
    </script>
@endsection
