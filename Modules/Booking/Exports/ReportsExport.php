<?php

namespace Modules\Booking\Exports;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Modules\Booking\Entities\Booking;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportsExport implements FromView, ShouldAutoSize
{

    public function view(): View
    {

        return view('booking::reports.booking_line_reports', [
            'bookings' => Booking::all(),
        ]);
    }

}
