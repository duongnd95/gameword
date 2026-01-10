<?php

namespace Modules\Booking\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Booking\Events\BookingEvent;
use Modules\Booking\Listeners\BookingListenerNotification;

class EventServiceProvider extends ServiceProvider
{
	/**
	 * The event listener mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		'Modules\Booking\Events\BookingEvent' => [
			'Modules\Booking\Listeners\BookingListenerNotification',
		],
//        BookingEvent::class => [
//            BookingListenerNotification::class
//        ],
	];

	/**
	 * Register any events for your application.
	 *
	 * @return void
	 */
	public function boot()
	{
		parent::boot();

		//
	}
}