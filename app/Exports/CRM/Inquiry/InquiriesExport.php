<?php

namespace App\Exports\CRM\Inquiry;

use App\Models\Inquiry;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class InquiriesExport implements FromView, ShouldAutoSize
{

    protected $filteredData;

    // Accept filtered data via the constructor
    public function __construct($filteredData)
    {
        $this->filteredData = $filteredData;
    }

    // Return the view with the filtered data
    public function view(): View
    {

        return view('modules.CRM.inquiry.exports.inquiries', [
            'inquiries' => $this->filteredData
        ]);
    }
}
