<?php

namespace App\Exports\CRM\Booking;

use App\Models\Booking;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BookingsReportExport implements FromView, ShouldAutoSize
{
    protected $filteredData;
    protected $viewFile;

    // Accept filtered data via the constructor
    public function __construct($filteredData, $viewFile)
    {
        $this->filteredData = $filteredData;
        $this->viewFile = $viewFile;
    }

    // Return the view with the filtered data
    public function view(): View
    {
        return view('modules.CRM.booking.exports.'.$this->viewFile, [
            'bookings' => $this->filteredData
        ]);

    }
}
