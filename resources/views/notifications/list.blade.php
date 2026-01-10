<ul class="menu products-list">
    @if(count($notifications) === 0)
        <li class="item">
            <a href="#">{{ __('notifications.no_notification') }}</a>
        </li>
    @endif
    @foreach($notifications as $item)
        {{--@dd($notifications)--}}
        {{--@dump($item)--}}
        <li class="item">
            @if(empty($item->data['phone']))
                @php
                    //$link = $item->data['booking_type'];
                    $avatar = optional(optional($item->data['user'])['profile'])['avatar'];
                    if(!$avatar) $avatar = asset(config('settings.avatar_default'));
                    else $avatar = asset(Storage::url($avatar));
                @endphp
                <a href="{{ url('bookings/bus/'.optional($item->data['booking'])['id']) }}" style="color: #444444;white-space:normal;">
                    <div class="pull-left">
                        <img class="direct-chat-img" src="{{ $avatar }}" alt="avatar">
                    </div>
                    <div class="product-info">
                        {!! $item->read_at ? '' : '<i class="fa fa-circle text-primary pull-right"></i>' !!}
                            {!! __('booking::bookings.notification_body_'.$item->data['type'], [
                             'user' => '<b>'. (!empty($item->data['user']) ? optional($item->data['user'])['name'] : __('frontend.customer')).'</b>',
                             'item' =>  '<b>'.optional(optional(optional($item->data['booking'])['detail'])['bookingable'])['name'].'</b>',
                             'code' => optional($item->data['booking'])['code'],
                             'phone' => optional(optional($item->data['booking'])['customer'])['phone'],
                             'name' => optional(optional($item->data['booking'])['customer'])['name'],
                         ])  !!}<br>
                        <small class="direct-chat-timestamp">
                            <i class="fa fa-clock-o"></i> {{ \Carbon\Carbon::parse($item->created_at)->gt(\Carbon\Carbon::now()->subDay()) ? \Carbon\Carbon::parse($item->created_at)->diffForHumans() : \Carbon\Carbon::parse($item->created_at)->format(config('settings.format.datetime')) }}
                        </small>
                    </div>
                </a>
            @else
                <a href="{{ url('phone-calls') }}" style="color: #444444;white-space:normal;">
                    <div class="pull-left">
                        <img class="direct-chat-img" src="{{ asset(config('settings.avatar_default')) }}" alt="avatar">
                    </div>
                    <div class="product-info">
                        {!! $item->read_at ? '' : '<i class="fa fa-circle text-primary pull-right"></i>' !!}
                        {!! __('notifications.phone_booking',[
                            'phone' => '<b>'.optional($item->data['phone'])['phone'].'</b>'
                        ]) !!}<br>
                        <small class="direct-chat-timestamp">
                            <i class="fa fa-clock-o"></i> {{ \Carbon\Carbon::parse($item->created_at)->gt(\Carbon\Carbon::now()->subDay()) ? \Carbon\Carbon::parse($item->created_at)->diffForHumans() : \Carbon\Carbon::parse($item->created_at)->format(config('settings.format.datetime')) }}
                        </small>
                    </div>
                </a>
            @endif
        </li>
    @endforeach
</ul>