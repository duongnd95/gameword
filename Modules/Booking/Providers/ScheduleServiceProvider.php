<?php
namespace Modules\Booking\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class ScheduleServiceProvider extends ServiceProvider
{
	public function boot()
	{
		$this->app->booted(function () {
			$schedule = $this->app->make(Schedule::class);
			//5;10;15;30;60;
			//0 */2 * * * : vao 0 phut moi 2h
			//*/50 * * * *: vao moi 50'
//			$schedule->command('booking:findTmp')->withoutOverlapping()->everyMinute()->between('08:00', '17:00');
		});
	}

	public function register()
	{
	}
}