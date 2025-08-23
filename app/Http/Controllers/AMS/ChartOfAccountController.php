<?php

namespace App\Http\Controllers\AMS;

use App\Http\Controllers\Controller;
use App\Models\BusinessCustomer;
use App\Models\ChartOfAccount;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Exports\AMS\ChartOfAccountsExport;
use Maatwebsite\Excel\Facades\Excel;
use Exception;

class ChartOfAccountController extends Controller
{
    public function index()
    {
        $accounts = ChartOfAccount::with('customer', 'vendor')->latest()->get();
        return view('modules.AMS.chatOfAccount.index', compact('accounts'));
    }

    public function create()
    {
        $vendors = Vendor::all();
        $customers = BusinessCustomer::all();
        return view('modules.AMS.chatOfAccount.create', compact('vendors', 'customers'));
    }

    public function store(Request $request)
    {
        try {
            $account = ChartOfAccount::where('account_head', $request->account_head)->first();
            if ($account) {
                return redirect()->back()->with('error', 'Account Head Is Already Exist. It Must Be Unique');
            }
            $account = new ChartOfAccount();
            $account->account_head = $request->account_head;
            $account->main_group = $request->main_group;
            $account->sub_group_1 = $request->sub1_group;
            $account->sub_group_2 = $request->sub2_group;
            $account->detailed_group = $request->detailed_group;
            $account->created_by = Auth::user()->id;
            $account->vendor_id = $request->vendor_id;
            $account->business_customer_id = $request->customer_id;
            $account->save();
            return redirect()->route('ams.chartOfAccounts.index')->with('success', 'Chart Of Account Add Successfully');
        } catch (Exception $e) {
            Log::error('Error while storing Chart of Account: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function edit($id)
    {
        $account = ChartOfAccount::findOrFail($id);
        $vendors = Vendor::all();
        $customers = BusinessCustomer::all();
        return view('modules.AMS.chatOfAccount.edit', compact('vendors', 'customers', 'account'));
    }

    public function update(Request $request)
    {
        try {
            $account = ChartOfAccount::where('account_head', $request->account_head)->where('id', '!=', $request->id)->first();
            if ($account) {
                return redirect()->back()->with('error', 'Account Head Is Already Exist. It Must Be Unique');
            }
            $account = ChartOfAccount::where('id', $request->id)->first();
            $account->account_head = $request->account_head;
            $account->main_group = $request->main_group;
            $account->sub_group_1 = $request->sub1_group;
            $account->sub_group_2 = $request->sub2_group;
            $account->detailed_group = $request->detailed_group;
            $account->vendor_id = $request->detailed_group == 'Trade Creditors' ? $request->vendor_id : null;
            $account->business_customer_id = $request->detailed_group == 'Trade Debtors' ? $request->customer_id : null;
            $account->save();
            return redirect()->route('ams.chartOfAccounts.index')->with('success', 'Chart Of Account Update Successfully');
        } catch (Exception $e) {
            Log::error('Error while storing Chart of Account: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function delete($id)
    {
        $account = ChartOfAccount::findOrFail($id);
        $account->delete();
        return response()->json([
            'success' => true,
            'message' => 'Chart of Account deleted successfully.'
        ]);
    }

    public function export(Request $request)
    {
        $ids = $request->input('account_ids');
        
        return Excel::download(new ChartOfAccountsExport($ids), 'chart_of_accounts.xlsx');
    }

}
