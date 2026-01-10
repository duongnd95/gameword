<?php

namespace Modules\Booking\Console;

use Modules\Booking\Entities\Booking;
use Illuminate\Console\Command;

class FindBookingTmp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:findTmp';
    //macos: while true; do php artisan schedule:run; sleep 60; done

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find Booking tmp - Tìm booking tạm';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Booking::notifyBookingTmp();
    }
}
