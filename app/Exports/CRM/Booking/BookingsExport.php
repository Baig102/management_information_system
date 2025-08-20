<?php

namespace App\Exports\CRM\Booking;

use App\Models\Booking;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BookingsExport implements FromView, ShouldAutoSize
{
    protected $filteredData;
    protected $view;

    // Accept filtered data via the constructor
    public function __construct($filteredData, int $isPax)
    {
        $this->filteredData = $filteredData;
        $this->view = $isPax;
    }

    // Return the view with the filtered data
    public function view(): View
    {
        if ($this->view == 0) {
            return view('modules.CRM.booking.exports.bookings', [
                'bookings' => $this->filteredData
            ]);
        }elseif($this->view == 1){
            return view('modules.CRM.booking.exports.paxBookings', [
                'bookings' => $this->filteredData
            ]);
        }else{
            return view('modules.CRM.booking.exports.bookings', [
                'bookings' => $this->filteredData
            ]);
        }

    }
}
