<?php

namespace App\Http\Controllers\AMS;

use App\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\BusinessCustomer;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class BusinessCustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $businessCustomers = BusinessCustomer::all();
        return view('modules.AMS.businessCustomer.index', compact('businessCustomers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('modules.AMS.businessCustomer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Trim input data
            $data = $request->only(['name', 'email', 'phone', 'website', 'address', 'is_active']);
            $data = array_map('trim', $data);

            // Validation rules
            $rules = [
            'name' => ['required', 'string', 'max:255', 'unique:business_customers,name'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('business_customers', 'email'),
            ],
            'phone' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable'],
            ];

            $validated = $request->validate($rules);

            // Prepare data for saving
            $businessCustomer = new BusinessCustomer();
            $businessCustomer->name = $data['name'];
            $businessCustomer->email = $data['email'] ?? null;
            $businessCustomer->phone = $data['phone'] ?? null;
            $businessCustomer->website = $data['website'] ?? null;
            $businessCustomer->address = $data['address'] ?? null;
            $businessCustomer->is_active = isset($data['is_active']) && $data['is_active'] === 'on' ? 1 : 0;
            $businessCustomer->created_by = auth()->id() ?? 1; // Assuming 1 is the default user ID if not authenticated
            $businessCustomer->save();
            DB::commit();

            $account = new ChartOfAccount();
            $account->account_head = $businessCustomer->name;
            $account->main_group = 'Balance Sheet';
            $account->sub_group_1 = 'Assets';
            $account->sub_group_2 = 'Current Assets';
            $account->detailed_group = 'Trade Debtors';
            $account->created_by = auth()->id() ?? 1;
            $account->business_customer_id = $businessCustomer->id;
            $account->save();

            return redirect()->route('ams.businessCustomer.index')->with('success', 'Business customer created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            // return redirect()->route('ams.businessCustomer.index')->with('error', $message);
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create business customer: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        echo "Business Customer Show Page for ID: " . $id;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $businessCustomer = BusinessCustomer::findOrFail($id);
        return view('modules.AMS.businessCustomer.edit', compact('businessCustomer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('business_customers')->ignore($id)],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('business_customers')->ignore($id),
            ],
            'phone' => ['nullable', 'string', 'max:50'],
            'website' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable'],
        ]);

        // dd($request->all());
        DB::beginTransaction();
        try {
            $businessCustomer = BusinessCustomer::findOrFail($id);

            // Ensure is_active is stored as integer (0 or 1)
            $validated['is_active'] = isset($validated['is_active']) && $validated['is_active'] === 'on' ? 1 : 0;
            $validated['updated_by'] = auth()->id();

            $businessCustomer->update($validated);
            DB::commit();
            return redirect()->route('ams.businessCustomer.index')->with('success', 'Business customer updated successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            // return redirect()->route('ams.businessCustomer.index')->with('error', $message);
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create business customer: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        echo "Business Customer Data Deleted for ID: " . $id;
    }
}
