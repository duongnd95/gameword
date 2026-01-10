{{--<label for="me">--}}
{{--    {{ __('booking::bookings.me') }}--}}
{{--    <input type="checkbox" {{ Request::has('me') ? "checked" : "" }} name="me" value="1" id="me" class="flat-blue"/>--}}
{{--</label>--}}
{{--<input type="text" value="{{\Request::get('departure_date')}}" class="form-control input-sm datepicker" name="departure_date" placeholder="{{ __('booking::bookings.departure_date') }}" autocomplete="off">--}}
@isset($list_services)
<select name="service_id" class="form-control input-sm" style="width: 150px;">
    <option value="">--{{ __('booking::bookings.'.Route::input('module')) }}--</option>
    @foreach($list_services as $id => $name)
        <option {{ Request::get('service_id') == $id ? "selected" : "" }} value="{{ $id }}"> {{ $name }}</option>
    @endforeach
</select>
@endisset
<select name="approve_id" class="form-control input-sm">
    <option value="">--{{ __('booking::bookings.approved') }}--</option>
    @foreach(\Modules\Booking\Entities\Approve::pluck('name', 'id') as $key => $value)
        <option {{ Request::get('approve_id') == $key ? "selected" : "" }} value="{{ $key }}"> {{ $value }}</option>
    @endforeach
</select>
{{--<div class="input-group">--}}
{{--    <button type="button" class="btn btn-default btn-sm pull-right" id="daterange-btn">--}}
{{--        <span>--}}
{{--          <i class="fa fa-calendar"></i> {{ __('message.created_at') }}--}}
{{--        </span>--}}
{{--        <i class="fa fa-caret-down"></i>--}}
{{--    </button>--}}
{{--    <input type="hidden" name="from" value="{{ Request::get('from') }}"/>--}}
{{--    <input type="hidden" name="to" value="{{ Request::get('to') }}"/>--}}
{{--</div>--}}
{{--<select name="service_id" class="form-control input-sm" style="width: 150px;">--}}
{{--    <option value="">--{{ __('booking::bookings.'.Route::input('module')) }}--</option>--}}
{{--    @foreach(\Modules\Product\Entities\Product::pluck('name', 'id') as $id => $name)--}}
{{--        <option {{ Request::get('service_id') == $id ? "selected" : "" }} value="{{ $id }}"> {{ $name }}</option>--}}
{{--    @endforeach--}}
{{--</select>--}}
<div class="input-group">
    <input type="text" value="{{\Request::get('search')}}" class="form-control input-sm" name="search" placeholder="{{ __('message.search_keyword') }}">
    <span class="input-group-btn">
        <button class="btn btn-info btn-sm" type="submit">
            <i class="fa fa-search"></i>  {{ __('message.search') }}
        </button>
    </span>
</div>