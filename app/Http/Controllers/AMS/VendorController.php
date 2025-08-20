<?php

namespace App\Http\Controllers\AMS;

use Exception;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class VendorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /** */
    public function getVendors(Request $request)
    {
        $query = $request->get('query');
        $data = Vendor::where('name', 'like', '%' . $query . '%')->get();
        return response()->json($data);
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (view()->exists('modules.AMS.vendor.index')) {
            $vendors = Vendor::with(['supplies', 'contactPersons'])->get();
            $data = [];
            foreach ($vendors as $vendor) {
                $data[] = [
                    'id' => $vendor->id,
                    'name' => $vendor->name,
                    'email' => $vendor->email,
                    'phone' => $vendor->phone,
                    'website' => $vendor->website,
                    'address' => $vendor->address,
                    'is_active' => $vendor->is_active,
                    'created_by' => $vendor->created_by,
                    'created_at' => $vendor->created_at,
                    'supplies' => $vendor->supplies,
                    'contactPersons' => $vendor->contactPersons,
                ];
            }
            $vendors = collect($data);
            $vendors = $vendors->sortBy('name');
            $vendors = $vendors->values()->all();
            // $vendors = collect($vendors)->paginate(10);
            return view('modules.AMS.vendor.index', compact('vendors'));
        }
        return abort(404);
    }

     /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (view()->exists('modules.AMS.vendor.create')) {

            return view('modules.AMS.vendor.create');
        }
        return abort(404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        DB::beginTransaction();
        try {

            $data = $request->all();
            $request->validate([
                'name' => 'required|string|unique:vendors,name',
                'email' => 'nullable|email',
                'phone' => 'nullable|string',
                'website' => 'nullable|url',
                'address' => 'nullable|string',
                'supplies' => 'required|array',
                'contact' => 'nullable|array',

            ]);

            $vendor = Vendor::create([
                'name'          => $data['name'],
                'email'         => $data['email'] ?? null,
                'phone'         => $data['phone'] ?? null,
                'website'       => $data['website'] ?? null,
                'address'       => $data['address'] ?? null,
                'is_active'     => $request->has('is_active') && $request->is_active === 'on' ? 1 : 0,
                'created_by'    => auth()->id(),
                'created_at'    => now(),
                'updated_at'    => now(),
                'status'        => 1
            ]);

            if ($request->has('supplies')) {
                foreach ($data['supplies'] as $supply) {
                    $vendor->supplies()->create([
                        'supplies'          => $supply,
                        'is_active'         => 1,
                        'created_by'        => auth()->id(),
                        'created_at'        => now(),
                        'updated_at'        => now(),
                        'status'            => 1
                    ]);
                }
            }

            if ($request->has('contact') && is_array($data['contact'])) {
                foreach ($data['contact'] as $contact) {
                    if (is_array($contact)) {
                        $vendor->contactPersons()->create([
                            'name'         => $contact['name'] ?? null,
                            'designation'  => $contact['designation'] ?? null,
                            'email'        => $contact['email'] ?? null,
                            'phone'        => $contact['phone'] ?? null,
                            'whatsapp'     => $contact['whatsapp'] ?? null,
                            'is_active'    => 1,
                            'created_by'   => auth()->id(),
                            'updated_by'   => auth()->id(),
                            'status'       => 1
                        ]);
                    }
                }
            }
            DB::commit();
            return redirect()->route('ams.vendor.index')->with('success', 'Vendor Added Successfully');
        } catch (Exception $e) {
            DB::rollback();
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            return redirect()->route('ams.vendor.add')->with('error', $message)->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        DB::beginTransaction();
        try {

        } catch (Exception $e) {

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {

    }




}
