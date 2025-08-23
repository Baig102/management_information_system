<?php

namespace App\Exports\AMS;

use App\Models\ChartOfAccount;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ChartOfAccountsExport implements FromView
{
    protected $ids;

    public function __construct($ids)
    {
        $this->ids = $ids;
    }

    public function view(): View
    {
        $accounts = ChartOfAccount::whereIn('id', $this->ids)->with('customer', 'vendor')->get();

        return view('modules.AMS.chatOfAccount.exports.chart_of_accounts', [
            'accounts' => $accounts
        ]);
    }
}
