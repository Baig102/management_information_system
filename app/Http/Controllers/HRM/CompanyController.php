<?php

namespace App\Http\Controllers\HRM;

use Exception;
use App\Models\company;
use Illuminate\Http\Request;
use App\Models\CompanyBankDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorecompanyRequest;
use App\Http\Requests\UpdatecompanyRequest;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$employees = Employee::where('is_active', 1)->get();
        $companies = company::orderBy('created_at', 'DESC')->get();
        if (view()->exists('modules.HRM.company.index')) {
            return view('modules.HRM.company.index', compact('companies'));
        }
        return abort(404);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (view()->exists('modules.HRM.company.create')) {
            return view('modules.HRM.company.create');
        }
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorecompanyRequest $request)
    {
        // dd($request->all());

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255|unique:companies,name', // company name should be unique
            'invoice_prefix' => 'required|string|max:255',
            'booking_number' => 'required|numeric',
            'phone' => 'required|numeric|unique:companies,phone', // phone number should be unique
            'email' => 'required|email|unique:companies,email', // email should be unique
            'website' => 'nullable|url',
            'address' => 'nullable|string|max:255',
            //'is_b2b' => 'nullable|boolean',
            //'is_active' => 'nullable|boolean',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // optional logo with image validation
        ]);

        DB::beginTransaction();
        try {

            // If validation passes, check for existing company records
            $existingCompany = Company::where('name', $request->name)
            ->orWhere('phone', $request->phone)
            ->orWhere('email', $request->email)
            ->first();

            if ($existingCompany) {
                // If a duplicate exists, handle it here
                return redirect()->route('hrm.create-company')
                    ->with('error', 'A company with the same name, phone number, or email already exists.')
                    ->withInput();
            }

            // Handle file upload and create the company record if no errors
            $data = $request->except('_token', 'bank');

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $logo = date('ymd') . time() . $file->getClientOriginalName();
                $file->move('images/companyLogos/', $logo);
                $logo_data = $logo;
            } else {
                //throw new Exception("Receipt Voucher Required", 1);
                $logo_data = null;
            }

            $data['logo'] = $logo_data;
            $data['is_active'] = (isset($data['is_active']) && $data['is_active']) == true ? 1 : 0;
            $data['is_b2b'] = (isset($data['is_b2b']) && $data['is_b2b']) == true ? 1 : 0;
            $data['secondary_email'] = $request->email;
            $data['created_by'] = auth()->user()->id;

            $company = company::create($data);

            // Save the associated bank details
            if ($request->has('bank')) {
                foreach ($request->bank as $bank) {
                    $company->bankDetails()->create([
                        'bank_name' => $bank['bank_name'],
                        'account_number' => $bank['account_number'],
                        'account_holder_name' => $bank['account_holder_name'],
                        'sort_code' => $bank['sort_code'],
                        'branch_name' => $bank['branch_name'],
                        'bank_address' => $bank['bank_address'],
                        'remarks' => $bank['remarks'],
                        'is_active' => isset($bank['is_active']) && $bank['is_active'] == 'on' ? 1 : 0,
                        'created_by' => auth()->user()->id
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('hrm.create-company')->with('success', 'Company Created Successfully');
        } catch (Exception $e) {
            DB::rollback();
            //$message = "";
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            //echo '<pre>'; print_r($message); echo '</pre>'; //exit;
            return redirect()->route('hrm.create-company')->with('error', $message)->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $companyId)
    {
        $company = company::with('bankDetails')->findOrFail($companyId);

        if (view()->exists('modules.HRM.company.edit')) {
            return view('modules.HRM.company.edit', compact('company'));
        }
        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // dd($request->all());

        DB::beginTransaction();  // Start the transaction

        try {

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $logo = date('ymd') . time() . $file->getClientOriginalName();
                $file->move('images/companyLogos/', $logo);
                $logo_data = $logo;
            } else {
                //throw new Exception("Receipt Voucher Required", 1);
                $logo_data = $request->old_logo; // Use the old logo if no new file is uploaded
            }

            // Update or create the Company record
            $company = company::updateOrCreate(
                ['id' => $request->id], // Find company by ID (if provided)
                [
                    'name' => $request->name,
                    'invoice_prefix' => $request->invoice_prefix,
                    // 'booking_number' => $request->booking_number,
                    'logo' => $logo_data,
                    'phone' => $request->phone,
                    'email' => $request->email,
                    'website' => $request->website,
                    'address' => $request->address,
                    'is_active' => (isset($request->is_active) && $request->is_active) == true ? 1 : 0,
                    'is_b2b' => (isset($request->is_b2b) && $request->is_b2b) == true ? 1 : 0,
                    'invoice_terms_conditions' => $request->invoice_terms_conditions,
                    // Only set updated_by on updates, and don't modify created_by on update
                    'updated_by' => $request->id ? auth()->user()->id : null, // Only update updated_by when updating
                ]
            );

            // If the company was created or updated, handle its bank details
            if ($company) {
                foreach ($request->bank as $bankData) {
                    // echo '<pre>'; print_r($bankData); echo '</pre>'; //exit;
                    // Find the bank record if it exists
                    $existingBank = isset($bankData['id']) ? CompanyBankDetail::find($bankData['id']) : null;
                    // Check if the bank record has an ID (to update existing or create a new one)
                    $bank = CompanyBankDetail::updateOrCreate(
                        ['id' => $bankData['id'] ?? null, 'company_id' => $company->id], // Update by ID or create new bank record
                        [
                            'bank_name'             => $bankData['bank_name'],
                            'account_number'        => $bankData['account_number'],
                            'account_holder_name'   => $bankData['account_holder_name'],
                            'sort_code'             => $bankData['sort_code'] ?? null,
                            'branch_name'           => $bankData['branch_name'] ?? null,
                            'bank_address'          => $bankData['bank_address'] ?? null,
                            'remarks'               => $bankData['remarks'] ?? null,
                            'is_active'             => (isset($bankData['is_active']) && $bankData['is_active']) == true ? 1 : 0,
                            'updated_by'            => isset($bankData['id']) ? auth()->user()->id : null, // Only update updated_by when updating
                            'created_by'            => isset($bankData['id']) ? ($existingBank->created_by ?? auth()->user()->id) : auth()->user()->id
                        ]
                    );
                }
            }

            DB::commit();  // Commit the transaction

            // Return response
            return redirect()->route('hrm.company-list')->with('success', 'Company and Bank details updated successfully!');

        } catch (Exception $e) {
            DB::rollBack();  // Rollback the transaction in case of error
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();

            Log::error('Error updating company and bank details: ' . $message);

            // echo '<pre>'; print_r($message); echo '</pre>'; //exit;
            return redirect()->route('hrm.company-edit', $request->id)->with('error', $message)->withInput();

        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(company $company)
    {
        //
    }
}
