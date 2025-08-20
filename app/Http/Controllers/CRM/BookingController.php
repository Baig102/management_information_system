<?php

namespace App\Http\Controllers\crm;

use Exception;
use App\Models\b;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Type;
use App\Models\User;
use App\Models\Vendor;
use Faker\Core\Number;

use App\Models\airline;
use App\Models\airport;

use App\Models\Booking;
use App\Models\company;
use App\Models\Country;
use App\Models\Employee;
use App\Mail\InvoiceMail;
use App\Models\Passenger;
use App\Mail\InvoiceEmail;
use App\Models\bookingLog;
use App\Models\bookingPnr;
use App\Models\TypeDetail;
use App\Models\bookingVisa;
use Illuminate\Support\Arr;
use App\Models\bookingHotel;
use App\Models\VisaCategory;
use Illuminate\Http\Request;
use App\Models\bookingFlight;
use App\Models\bookingRefund;
use App\Models\bookingPayment;

use App\Models\bookingPricing;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\bookingTransport;
use App\Models\BusinessCustomer;
use App\Models\CompanySmtpConfig;
use Illuminate\Support\Facades\DB;
use App\Models\bookingOtherCharges;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\ReportFilterService;
use App\Models\bookingInstallmentPlan;
use App\Models\bookingInternalComment;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\Response;
use App\Exports\CRM\Booking\BookingsExport;

use App\Exports\CRM\Booking\PaymentsExport;
use App\Http\Requests\CRM\BookingFormRequest;
use App\Http\Controllers\AMS\VendorController;
use Illuminate\Validation\ValidationException;
use App\Exports\CRM\Booking\BookingsReportExport;
use App\Http\Controllers\API\PNRConverterController;
use Illuminate\Support\Facades\Validator; // Ensure this is included at the top

class BookingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $reportFilterService;
    public function __construct(ReportFilterService $reportFilterService)
    {
        $this->middleware('auth');
        $this->reportFilterService = $reportFilterService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        if (view()->exists('modules.CRM.booking.create')) {

            // Get the logged-in user
            $user = Auth::user();
            //echo '<pre>'; print_r($user->employee); echo '</pre>'; //exit;
            if ($user->employee) {
                // Retrieve companies assigned to the employee
                $assignedCompanies = $user->employee->companies;

                //echo '<pre>'; print_r($assignedCompanies); echo '</pre>'; //exit;
            } else {
                //echo "ELSE";
                return abort(404);
            }

            return view('modules.CRM.booking.create', compact('assignedCompanies'));
        }
        return abort(404);
    }


    public function indexPnr(Request $request)
    {
        if (view()->exists('modules.CRM.booking.create-pnr')) {

            // Get the logged-in user
            $user = Auth::user();
            //echo '<pre>'; print_r($user->employee); echo '</pre>'; //exit;
            if ($user->employee) {
                // Retrieve companies assigned to the employee
                $assignedCompanies = $user->employee->companies;

                //echo '<pre>'; print_r($assignedCompanies); echo '</pre>'; //exit;
            } else {
                //echo "ELSE";
                return abort(404);
            }
            $businessCustomers = $user->businessCustomers;

            return view('modules.CRM.booking.create-pnr', compact('assignedCompanies', 'businessCustomers'));
        }
        return abort(404);
    }


    public function root()
    {
        return view('modules.CRM.index');
    }

    function addHotelModelShow($count)
    {

        return view('modules.CRM.booking.modals.add-hotel', compact('count'));
    }

    function addTransportModelShow($count)
    {
        return view('modules.CRM.booking.modals.add-transport', compact('count'));
    }

    function getAirports(Request $request)
    {
        $query = $request->get('query');
        $data = airport::where('name', 'like', '%' . $query . '%')->orWhere('iata', $query)->get();
        //$data = airport::where('iata', $query)->get();
        return response()->json($data);
    }

    function getAirlines(Request $request)
    {
        $query = $request->get('query');
        //$data = airline::where('name', 'like', '%' . $query . '%')->get();
        $data = airline::where('name', 'like', '%' . $query . '%')->orWhere('code', $query)->get();
        return response()->json($data);
    }

    function getCountries(Request $request)
    {
        $query = $request->get('query');
        $data = Country::where('name', 'like', '%' . $query . '%')->orWhere('iso', $query)->get();
        return response()->json($data);
    }

    function getVisaCategories(Request $request)
    {
        $query = $request->get('query');
        $data = VisaCategory::where('name', 'like', '%' . $query . '%')->get();
        //echo '<pre>'; print_r($data); echo '</pre>'; //exit;
        return response()->json($data);
    }

    function getVendors(Request $request)
    {
        $query = $request->get('query');
        $data = Vendor::where('name', 'like', '%' . $query . '%')->get();
        return response()->json($data);
    }

    public function flightsSearchView(Request $request)
    {
        if (view()->exists('modules.CRM.booking.flights-search')) {
            return view('modules.CRM.booking.flights-search');
        }
        return abort(404);
    }

    function saveBooking(BookingFormRequest $request)
    {


        DB::beginTransaction();
        try {

            $data = $request->all();
            //echo '<pre>'; print_r($data); echo '</pre>'; //exit;

            // Extract the first departure date
            $firstDepartureDate = $request->input('flights.1.departure_date');
            $ticketingDeadline = $request->input('ticket_deadline');

            $request->validate([
                'ticket_deadline' => 'required|date',
                'flights.1.departure_date' => [
                    'nullable',
                    'date',
                    function ($attribute, $value, $fail) use ($ticketingDeadline) {
                        if ($value && Carbon::parse($value)->lt(Carbon::parse($ticketingDeadline))) {
                            $fail('The first departure date must bse greater than or equal to the Ticketing Deadline.');
                        }
                    },
                ],
            ]);


            $booking_data = Arr::except($data, ['passenger', 'hotel', 'transport', 'flights', 'booking_pricing', 'hotel_count', 'transport_count', 'flight_count', 'passenger_count', 'card_type', 'bank_name', 'cc-name', 'cc-number', 'cc-expiration', 'cc-cvv', 'cc-ccc', 'receipt_voucher', 'pnr', 'pnr_json_data', 'pnr_count', 'pnrData', 'visa', 'visa_count', 'deposite_amount', 'balance_amount', 'deposit_date', 'card_type_type']);

            $passenger_data         = data_get($data, 'passenger');
            $hotel_data             = data_get($data, 'hotel');
            $transport_data         = data_get($data, 'transport');
            $flights_data           = data_get($data, 'flights');
            $booking_pricing_data   = data_get($data, 'booking_pricing');
            $pnrData                = data_get($data, 'pnrData');
            $visa_data              = data_get($data, 'visa');


            $booking_data['other_charges'] = ($request->payment_method == 'Credit Debit Card' && $request->has('cc-ccc')) ? $request['cc-ccc'] : "0.00";
            $booking_data['payment_status'] = ($booking_data['payment_type'] == 1) ? "2" : "3";



            $booking_data['is_full_package'] = $request->has('is_full_package') == true ? 1 : 0;
            $booking_data['is_pnr_booking'] = $request->has('pnrData') ? 1 : 0;
            //$booking_data['ticket_status'] = 3;

            $booking_data['deposite_amount'] = 0.00;
            $booking_data['balance_amount'] = $booking_data['total_sales_cost'];
            $booking_data['deposit_date'] = null;

            $booking_data['booking_status_on'] = now();
            $booking_data['booking_status_by'] = auth()->id();

            $booking = Booking::create($booking_data);
            //echo $booking->id;

            /**
             * UPDATED ON 02-11-2024 PNR */
            if ($request->has('pnrData')) {
                $pnr_db_data = array();
                foreach ($pnrData as $key => $pnrValue) {
                    $jsonData       = json_decode($pnrValue['pnr_response']);
                    $pnrResponse    = $jsonData->pnrResponse;
                    $pnrKey         = $jsonData->pnrKey;
                    $pnr            = $jsonData->pnr;
                    $supplier       = $pnrValue['supplier'];

                    $pnr_data_value = array(
                        'booking_id'        => $booking->id,
                        'supplier'          => $supplier,
                        'pnr'               => $pnr,
                        'pnr_key'           => $pnrKey,
                        'pnr_response'      => $pnrResponse,
                        'is_active'         => '1',
                        'created_by'        => auth()->id(),
                        'status'            => '1',
                    );
                    $bookingPnr = bookingPnr::create($pnr_data_value);
                    // Store the pnr_key and the last inserted ID in the associative array
                    $pnr_db_data[$pnrKey] = $bookingPnr->id; // Assuming 'id' is the primary ke
                }
            }
            /**
             * END
             */

            $required_data = array(
                'booking_id'       => $booking->id,
                'is_active'        => '1',
                'created_by'       => auth()->id(),
                'status'           => '1',
            );

            if ($request->has('passenger')) {
                foreach ($passenger_data as $pn_key => $pass_data_value) {
                    foreach ($required_data as $prd_key => $required_data_value) {
                        $pass_data_value = Arr::add($pass_data_value, $prd_key, $required_data_value);
                    }
                    /** Added on 30-10-2024 */
                    // Sanitize phone number and email by removing any spaces
                    if (isset($pass_data_value['mobile_number'])) {
                        $pass_data_value['mobile_number'] = str_replace(' ', '', $pass_data_value['mobile_number']);
                    }

                    if (isset($pass_data_value['email'])) {
                        $pass_data_value['email'] = str_replace(' ', '', $pass_data_value['email']);
                    }
                    /** END */
                    $pass_data_value['date_of_birth'] = ($pass_data_value['date_of_birth'] != null) ? (Carbon::parse($pass_data_value['date_of_birth'])->format('Y-m-d')) : null;
                    $pass_data_value['age'] = ($pass_data_value['date_of_birth'] != null) ? calculateAge($pass_data_value['date_of_birth']) : null;

                    if (isset($pass_data_value['pnr_key'])) {
                        $pnr_key = $pass_data_value['pnr_key'];
                        $pass_data_value['pnr_id'] = $pnr_db_data[$pnr_key];
                    }else{
                        $pass_data_value['pnr_id'] = null;
                    }

                    Passenger::create($pass_data_value);
                }
            } else {
                throw new Exception("Passenger Details Are Missing", 1);
            }

            if ($request->has('hotel')) {
                foreach ($hotel_data as $hd_key => $hotel_data_value) {
                    foreach ($required_data as $rd_key => $required_data_value) {
                        $hotel_data_value = Arr::add($hotel_data_value, $rd_key, $required_data_value);
                    }
                    bookingHotel::create($hotel_data_value);
                }
            }
            if ($request->has('transport')) {
                foreach ($transport_data as $td_key => $transport_data_value) {
                    foreach ($required_data as $rd_key => $required_data_value) {
                        $transport_data_value = Arr::add($transport_data_value, $rd_key, $required_data_value);
                    }
                    $transport_data_value['transport_type'] = $transport_data_value['type'];

                    $transport_data_value = Arr::except($transport_data_value, ['type']);
                    bookingTransport::create($transport_data_value);
                }
            }
            if ($request->has('visa')) {
                foreach ($visa_data as $vd_key => $visa_data_value) {
                    foreach ($required_data as $rd_key => $required_data_value) {
                        $visa_data_value = Arr::add($visa_data_value, $rd_key, $required_data_value);
                    }
                    bookingVisa::create($visa_data_value);
                }
            }
            if ($request->has('flights')) {
                foreach ($flights_data as $fd_key => $flights_data_value) {
                    foreach ($required_data as $rd_key => $required_data_value) {
                        $flights_data_value = Arr::add($flights_data_value, $rd_key, $required_data_value);
                    }

                    $flights_data_value['departure_date'] = (Carbon::parse($flights_data_value['departure_date'])->format('Y-m-d'));
                    $flights_data_value['arrival_date'] = (Carbon::parse($flights_data_value['arrival_date'])->format('Y-m-d'));

                    if (isset($flights_data_value['pnr_key'])) {
                        $pnr_key = $flights_data_value['pnr_key'];
                        $flights_data_value['pnr_id'] = $pnr_db_data[$pnr_key];
                    }else{
                        $flights_data_value['pnr_id'] = null;
                    }

                    bookingFlight::create($flights_data_value);
                }
            }

            if ($request->has('booking_pricing')) {
                foreach ($booking_pricing_data as $pd_key => $booking_pricing_data_value) {
                    foreach ($required_data as $rd_key => $required_data_value) {
                        $booking_pricing_data_value = Arr::add($booking_pricing_data_value, $rd_key, $required_data_value);
                    }
                    $booking_pricing_data_value['booking_type'] = $booking_pricing_data_value['type'];
                    $booking_pricing_data_value['sale_cost'] = $booking_pricing_data_value['flight_price'];
                    $booking_pricing_data_value['net_cost'] = $booking_pricing_data_value['flight_net_price'];

                    $booking_pricing_data_value = Arr::except($booking_pricing_data_value, ['type', 'flight_price', 'flight_net_price']);
                    bookingPricing::create($booking_pricing_data_value);
                }
            }
            if ($request->hasFile('receipt_voucher')) {
                $file = $request->file('receipt_voucher');
                $receipt_voucher = date('ymd') . time() . $file->getClientOriginalName();
                $file->move('images/uploads/ReceiptVouchers/', $receipt_voucher);
                $receipt_voucher_data = $receipt_voucher;
            } else {
                //throw new Exception("Receipt Voucher Required", 1);
                $receipt_voucher_data = null;
            }

            // Create booking_payments
            $booking_payment = new bookingPayment([
                'booking_id'            => $booking->id,
                'payment_type'          => $booking_data['payment_type'],
                'installment_id'        => null,
                'installment_no'        => '0',
                'total_amount'          => $booking_data['total_sales_cost'],
                'reciving_amount'       => $data['deposite_amount'],
                'remaining_amount'      => $data['balance_amount'],
                'payment_method'        => $booking_data['payment_method'],
                'bank_name'             => $request->has('bank_name') ? $request['bank_name'] : null,
                'bank_branch'           => $request->has('bank_branch') ? $request['bank_branch'] : null,
                'office_date'           => $request->has('office_date') ? $request['office_date'] : null,
                'card_type_type'        => ($request->payment_method == 'Credit Debit Card' && $request->has('card_type_type')) ? $request['card_type_type'] : null,
                'card_type'             => ($request->payment_method == 'Credit Debit Card' && $request->has('card_type')) ? $request['card_type'] : null,
                'card_holder_name'      => ($request->payment_method == 'Credit Debit Card' && $request->has('cc-name')) ? $request['cc-name'] : null,
                'card_number'           => ($request->payment_method == 'Credit Debit Card' && $request->has('cc-number')) ? $request['cc-number'] : null,
                'card_expiry_date'      => ($request->payment_method == 'Credit Debit Card' && $request->has('cc-expiration')) ? $request['cc-expiration'] : null,
                'card_cvc'              => ($request->payment_method == 'Credit Debit Card' && $request->has('cc-cvv')) ? $request['cc-cvv'] : null,
                'cc_charges'            => ($request->payment_method == 'Credit Debit Card' && $request->has('cc-ccc')) ? $request['cc-ccc'] : '0.00',
                'deposit_date'          => $data['deposit_date'],
                'due_date'              => null,
                'payment_on'            => $data['deposit_date'],
                'receipt_voucher'       => $receipt_voucher_data,
                'is_active'             => 1,
                'created_by'            => Auth::id(),
                'status'                => 1,
            ]);

            $booking_payment->save();

            if ($request->payment_method == 'Credit Debit Card') {
                $other_charges = new bookingOtherCharges([
                    'booking_id'        => $booking->id,
                    'payment_id'        => $booking_payment->id,
                    'charges_type'      => $request->has('cc-ccc') ? 'CC Charges' : "",
                    'amount'            => $request->has('cc-ccc') ? $request['cc-ccc'] : "",
                    'reciving_amount'   => $request->has('cc-ccc') ? $request['cc-ccc'] : "",
                    'remaining_amount'  => 0.00,
                    'comments'          => $request->has('cc-ccc') ? 'Crdit / Debit Card Charges' : "",
                    'payment_status'    => 1,
                    'is_active'         => 1,
                    'created_by'        => Auth::id(),
                    'status'            => 1,
                ]);

                $other_charges->save();
            }


            // Log the event
            bookingLog::create([
                'booking_id' => $booking->id,
                'action' => 'Booking Created',
                'description' => 'Booking created by user ' . Auth::user()->name.' having booking # '.$booking->booking_prefix . $booking->booking_number.' with selected payment method: '.$booking_data['payment_method'] .' and the payment type is: '.$booking_data['payment_type'],
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            return redirect()->route('crm.create-booking')->with('success', 'Booking Generated Successfully With Booking Number: ' . $booking->booking_prefix . $booking->booking_number);
        } catch (Exception $e) {
            DB::rollback();
            //$message = "";
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            //echo '<pre>'; print_r($message); echo '</pre>'; //exit;
            return redirect()->route('crm.create-booking')->with('error', $message)->withInput();
        }
    }

    public function bookingList(Request $request)
    {

        // $types = Type::with('details')->get();
        $ticket_status = Type::where('id', 1)->with('details')->first();
        $booking_status = Type::where('id', 2)->with('details')->first();
        $payment_status = Type::where('id', 3)->with('details')->first();

        if (view()->exists('modules.CRM.booking.booking-list')) {
            // Get the logged-in user
            $user = Auth::user();
            //echo $user->employee->id;
            if ($user->employee) {
                // Retrieve companies assigned to the employee
                $assignedCompanies = $user->employee->companies;
            } else {
                //echo "ELSE";
                return abort(404);
            }

            $user_role = $user->role;
            //echo '<pre>'; print_r($user); echo '</pre>'; //exit;
            if ($user_role == 1 || $user_role == 2) {

                //$agents = User::where('role','>','2')->get();
                $agents = User::all();
            } else if ($user_role == 3 || $user_role == 4) {

                // Retrieve company IDs of the logged-in user
                $employeeCompanyIds = $assignedCompanies->pluck('id')->toArray();

                // Retrieve users who have the same company IDs based on their employee IDs


                // Check if user is role 4 (Team Lead)
                if ($user_role == 4) {

                    // Retrieve the employees (users) assigned to the team lead (team_lead_id in employees table)
                    // We will match the logged-in user's employee ID to the team_lead_id
                    $teamLeadEmployeeIds = Employee::where('team_lead_id', $user->id)->pluck('id')->toArray();

                    // Get all users with role = 5 whose employee_id matches the retrieved employee IDs
                    $agents = User::where(function ($query) use ($teamLeadEmployeeIds, $user) {
                        // Include users (agents) with employee_id from the list
                        $query->whereIn('employee_id', $teamLeadEmployeeIds)
                            ->orWhere('id', $user->id);  // Include the team lead's own bookings
                    })
                    //->where('role', '=', 5)  // Only include users with role = 5 (agents)
                    ->where('is_active', 1)
                    ->get();

                } else {
                    // For role 3, get all agents related to their company
                    $agents = User::whereHas('employee.companies', function ($query) use ($employeeCompanyIds) {
                        $query->whereIn('companies.id', $employeeCompanyIds);
                    })->where('role', '>', 2)->get();
                }
            } else {
                $agents[] = $user;
            }

            $businessCustomers = BusinessCustomer::where('is_active', 1)->get();

            //echo '<pre>'; print_r($agents); echo '</pre>'; //exit;
            return view('modules.CRM.booking.booking-list', compact('assignedCompanies', 'agents', 'ticket_status', 'booking_status', 'payment_status', 'businessCustomers'));
        }
        return abort(404);
    }
    /**
     * Search bookings based on various parameters.
     *
     * @param Request $request
     * @param string $return_type
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */

    public function bookingSearch(Request $request, $return_type ='view')
    {
        // dd($request->all());
        // Remove all blank values from the request
        $searchParams = array_filter($request->all());
        $searchParams = Arr::except($searchParams, ['_token']);
        //echo '<pre>'; print_r($searchParams); echo '</pre>'; //exit;

        if (empty($searchParams)) {
            //echo "empty search";
            return Redirect::back()->with('error', 'Please provide at least one search parameter.');
        }
        //dd($request->all());
        $user = Auth::user();
        if ($user->employee) {
            $assignedCompanies = $user->employee->companies;
        }

       $user_role = $user->role;

        if ($user_role == 1 || $user_role == 2) {
            $agents = User::all();
        } elseif ($user_role == 3 || $user_role == 4) {
            $employeeCompanyIds = $assignedCompanies->pluck('id')->toArray();

            if ($user_role == 4) {
                //echo $user->id;
                //$teamLeadEmployeeIds = Employee::where('team_lead_id', $user->employee->id)->pluck('id')->toArray();
                $teamLeadEmployeeIds = Employee::where('team_lead_id', $user->id)->pluck('id')->toArray();

                $agents = User::where(function ($query) use ($teamLeadEmployeeIds, $user) {
                    $query->whereIn('employee_id', $teamLeadEmployeeIds)
                        ->orWhere('id', $user->id);  // Include the team lead's own bookings
                })
                //->where('role', '=', 5)
                ->where('is_active', 1)->get();
                //echo '<pre>'; print_r($teamLeadEmployeeIds); echo '</pre>'; //exit;

            } else {
                $agents = User::whereHas('employee.companies', function ($query) use ($employeeCompanyIds) {
                    $query->whereIn('companies.id', $employeeCompanyIds);
                })->where('role', '>', 2)->get();
            }
        } else {
            $agents[] = $user;
        }

        $searchAgents = $agents;

        $businessCustomers = BusinessCustomer::where('is_active', 1)->get();
        //echo '<pre>'; print_r($agents->pluck('id')); echo '</pre>'; //exit;
        $ticket_status = Type::where('id', 1)->with('details')->first();
        $booking_status = Type::where('id', 2)->with('details')->first();
        $payment_status = Type::where('id', 3)->with('details')->first();

        // Check if 'booking_number' is present in the search parameters
        $hasBookingNumber = !empty($searchParams['booking_number']);

        // Check if any other search fields are provided (except booking_from_date and booking_to_date)
        $hasOtherSearchFields = collect($searchParams)
            ->except(['booking_from_date', 'booking_to_date'])
            ->filter()
            ->isNotEmpty();

            // Check if flight-related fields exist in search parameters
            $hasFlightFilters = collect($searchParams)->only([
                'air_line_name', 'ticket_no', 'departure_airport', 'arrival_airport', 'departure_date', 'arrival_date', 'booking_class'
            ])->filter()->isNotEmpty();

        // Create a query for the booking table
        $bookings = Booking::with(['passengers', 'flights', 'hotels', 'transports', 'otherCharges'])

            ->whereHas('passengers', function ($query) use ($searchParams) {
                foreach ($searchParams as $field => $value) {
                    if ($field === 'first_name' && $value) {
                        $query->where(function ($query) use ($value) {
                            $query->where('first_name', 'like', '%' . $value . '%')
                                  ->orWhere('middle_name', 'like', '%' . $value . '%')
                                  ->orWhere('last_name', 'like', '%' . $value . '%');
                        });
                    }
                    if ($field === 'mobile_number') {
                        $query->where('mobile_number', 'like', '%' . $value . '%');
                    }
                    if ($field === 'email') {
                        $query->where('email', 'like', '%' . $value . '%');
                    }
                }
            })

            // Apply the main booking search conditions
            ->where(function ($query) use ($searchParams, $hasBookingNumber, $hasOtherSearchFields, $searchAgents) {

                // Ensure the current logged-in user's ID is included in $agents


                // Check if `created_by` is not posted or empty, and handle it
                if (!isset($searchParams['created_by']) || empty($searchParams['created_by'])) {
                    $query->whereIn('created_by', $searchAgents->pluck('id')->toArray());
                }
                // echo '<pre>'; print_r($agents->pluck('id')->toArray()); echo '</pre>'; //exit;
                foreach ($searchParams as $field => $value) {

                    // Skip date filters if booking_number or any other field is provided

                    if ($field === 'booking_from_date') {
                        $query->whereDate('booking_date', '>=', $value);
                    }
                    if ($field === 'booking_to_date') {
                        $query->whereDate('booking_date', '<=', $value);
                    }

                    if (in_array($field, [
                        'trip_type',
                        'booking_status',
                        'payment_status',
                        'ticket_status'
                    ])) {
                        $query->where($field, 'like', '%' . $value . '%');
                    }

                    if (in_array($field, ['company_id', 'business_customer_id','booking_number', 'created_by'])) {
                        $query->where($field, '=', $value);
                    }

                }
            })

            // Add condition to include bookings even if they don't have associated flights
            ->where(function ($query) use ($searchParams, $hasFlightFilters) {
                //$query->whereDoesntHave('flights'); // Include bookings with no flights
                // If no flight-related filters are passed, include bookings without flights
                if (!$hasFlightFilters) {
                    $query->whereDoesntHave('flights');
                }
                $query->orWhereHas('flights', function ($query) use ($searchParams) {
                    foreach ($searchParams as $field => $value) {
                        if (in_array($field, ['air_line_name', 'ticket_no', 'departure_airport', 'arrival_airport', 'departure_date', 'arrival_date', 'booking_class'])) {
                            $query->where($field, 'like', '%' . $value . '%');
                        }
                    }
                });
            })

            ->orderBy('created_at', 'DESC')
            ->get();

        if ($return_type == 'json') {
            return response()->json(compact('bookings'));
        } elseif ($return_type == 'compact') {
            // Return the filtered data
            return $bookings;
        } elseif ($return_type == 'view') {
            //echo '<pre>'; print_r($agents); echo '</pre>'; //exit;
            return view('modules.CRM.booking.booking-list', compact('bookings', 'assignedCompanies', 'searchParams', 'agents', 'ticket_status', 'booking_status', 'payment_status', 'businessCustomers'));
        }
    }

    function viewBooking($id)
    {

        if (view()->exists('modules.CRM.booking.modals.view')) {
            $booking = Booking::with('passengers', 'hotels', 'transports', 'flights', 'prices', 'otherCharges', 'installmentPlan', 'payments', 'refunds', 'internalComments')->findOrFail($id);

            $ticket_status = TypeDetail::where('type_id', 1)->where('detail_number', $booking->ticket_status)->first();
            $booking_status = TypeDetail::where('type_id', 2)->where('detail_number', $booking->booking_status)->first();
            $payment_status = TypeDetail::where('type_id', 3)->where('detail_number', $booking->payment_status)->first();


            // Log the event
            bookingLog::create([
                'booking_id' => $booking->id,
                'action' => 'Booking Details Viewed',
                'description' => 'Booking Details Viewed user ' . Auth::user()->name.' ON '.Carbon::now()->format('Y-m-d H:i:s'),
                'created_by' => auth()->id(),
            ]);

            return view('modules.CRM.booking.modals.view', compact('booking', 'ticket_status', 'booking_status', 'payment_status'));
        }
        return abort(404);
    }

    function viewBookingInvoice(int $id, $preview_type="default")
    {

        $user = Auth::user();
        $user_role = $user->role;

        if ($user_role == 1 || $user_role == 2) {
            $preview_type = $preview_type;
        } else {
            $preview_type="default";
        }

        $booking = Booking::with('passengers', 'hotels', 'transports', 'flights', 'prices', 'otherCharges', 'installmentPlan', 'payments', 'company', 'refunds', 'visas')->findOrFail($id);
        //echo '<pre>'; print_r($booking); echo '</pre>'; //exit;
        // return view('modules.CRM.booking.invoices.invoice', compact('booking'));

        // Log the event
        bookingLog::create([
            'booking_id' => $booking->id,
            'action' => 'Booking Invoice Viewed',
            'description' => 'Booking Invoice Viewed user ' . Auth::user()->name.' ON '.Carbon::now()->format('Y-m-d H:i:s'),
            'created_by' => auth()->id(),
        ]);

        if ($preview_type == "default") {
            return view('modules.CRM.booking.invoices.invoice', compact('booking'));
        }else {
            return view('modules.CRM.booking.invoices.invoice-'.$preview_type, compact('booking'));
        }
    }

    function viewBookingEticket(int $id)
    {

        $user = Auth::user();
        $user_role = $user->role;

        $booking = Booking::with('passengers', 'hotels', 'transports', 'flights', 'prices', 'otherCharges', 'installmentPlan', 'payments', 'company', 'refunds', 'visas')->findOrFail($id);

        // Log the event
        bookingLog::create([
            'booking_id' => $booking->id,
            'action' => 'Booking eTicket Viewed',
            'description' => 'Booking eTicket Viewed by user ' . Auth::user()->name.' ON '.Carbon::now()->format('Y-m-d H:i:s'),
            'created_by' => auth()->id(),
        ]);

        return view('modules.CRM.booking.invoices.e-ticket', compact('booking'));

    }

    function generateBookingInvoice(int $id, $pdf_type=null)
    {

        $booking = Booking::with('passengers', 'hotels', 'transports', 'flights', 'prices', 'otherCharges', 'installmentPlan', 'payments', 'company', 'refunds', 'visas')->findOrFail($id);
        $data = [
            'booking' => $booking
        ];


        $dompdf = new Dompdf();

        // Get HTML content from Blade views for each page
        $page1 = View::make('modules.CRM.booking.invoices.invoice', $data)->render();


        // Load combined HTML content into Dompdf
        $dompdf->loadHtml($page1);


        // Set paper size and orientation for the PDF
        $dompdf->setPaper('A4', 'portrate'); // Adjust paper size and orientation as needed


        // (Optional) Set options if needed
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);


        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'Arial, Helvetica, sans-serif');
        $options->set('dpi', 160);
        $options->set('isFontSubsettingEnabled', true);

        // $options->set('debugCss', true);
        // $options->set('debugLayout', true);

        $dompdf->setOptions($options);


        // Render the PDF
        $dompdf->render();

        // Get the generated PDF content
        $pdfContent = $dompdf->output();

        // Generate a unique file name
        $fileName = 'Invoice_' . $booking->booking_prefix . $booking->booking_number . '-' . Carbon::now()->format('m-d-Y') . '.pdf';

        // Log the event
        bookingLog::create([
            'booking_id' => $booking->id,
            'action' => 'Booking PDF Invoice Viewed / Generated',
            'description' => 'Booking PDF Invoice Viewed / Generated by user ' . Auth::user()->name.' ON '.Carbon::now()->format('Y-m-d H:i:s'),
            'created_by' => auth()->id(),
        ]);

        // Return the PDF content in the response with appropriate headers
        if ($pdf_type == null) {
            return Response::make($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]);

        }elseif($pdf_type == 'email'){
            // Save the PDF to a temporary location
            $filePath = storage_path('app/public/invoices/' . $fileName);
            Storage::put('public/invoices/' . $fileName, $pdfContent);

            return $filePath;
        }

    }

    function editBooking(int $id)
    {
        if (view()->exists('modules.CRM.booking.edit')) {
            $booking = Booking::with('hotels', 'transports', 'flights', 'prices', 'otherCharges', 'installmentPlan', 'payments', 'refunds')->findOrFail($id);
            // Check if the user is authorized to edit the booking - booking policy
            $this->authorize('edit', $booking);
            return view('modules.CRM.booking.edit', compact('booking'));
        }
        return abort(404);
    }

    function addInstallmentPlan(int $id)
    {
        if (view()->exists('modules.CRM.booking.modals.add-installment-plan')) {
            $booking = Booking::with('installmentPlan', 'payments')->findOrFail($id);
            return view('modules.CRM.booking.modals.add-installment-plan', compact('booking'));
        }
        return abort(404);
    }

    function saveInstallmentPlan(Request $request)
    {
        //response()->json($request->all());
        DB::beginTransaction();
        try {

            $booking_id = $request->booking_id;

            $installment = bookingInstallmentPlan::where('booking_id', $booking_id)->get();
            //echo '<pre>'; print_r($installment); echo '</pre>'; //exit;
            if (count($installment) > 0) {
                throw new Exception("Installment Plan Already Created", 1);
            }
            foreach ($request->installment as $key => $instl_value) {
                //echo '<pre>'; print_r ($instl_value['installment_number']); echo '</pre>'; //exit;
                $booking_installment = new bookingInstallmentPlan([
                    'booking_id'            => $booking_id,
                    'installment_number'    => $instl_value['installment_number'],
                    'due_date'              => $instl_value['due_date'],
                    'amount'                => $instl_value['amount'],
                    'is_active'             => 1,
                    'created_by'            => Auth::id(),
                    'status'                => 1,
                ]);

                $booking_installment->save();
            }

            // Log the event
            bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Booking Installment Plan',
                'description' => 'Booking Installment Plan created by user ' . Auth::user()->name,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Installment plan added successfully']);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message]);
        }
    }

    function editInstallmentPlan(int $id)
    {
        if (view()->exists('modules.CRM.booking.modals.edit-installment-plan')) {
            $booking = Booking::with('installmentPlan', 'payments')->findOrFail($id);
            return view('modules.CRM.booking.modals.edit-installment-plan', compact('booking'));
        }
        return abort(404);
    }

    function updateInstallmentPlan(Request $request)
    {
        //echo '<pre>'; print_r($request->all()); echo '</pre>'; //exit;

        DB::beginTransaction();
        try {

            $booking_id = $request->booking_id;

            $installment = bookingInstallmentPlan::where('booking_id', $booking_id)->get();
            //echo '<pre>'; print_r($installment); echo '</pre>'; //exit;
            if (count($installment) <= 0) {
                throw new Exception("Please First Create Installment Plan", 1);
            }
            foreach ($request->installment as $key => $instl_value) {
                $instal_data = bookingInstallmentPlan::find($key);

                if (!$instal_data) {
                    throw new Exception("Installment Not Found...", 1);
                }
                if ($instal_data->is_received == 0) {
                    $instal_data->installment_number        = $instl_value['installment_number'];
                    $instal_data->due_date                  = $instl_value['due_date'];
                    $instal_data->amount                    = $instl_value['amount'];
                    $instal_data->status                    = 1;
                    $instal_data->updated_by                = Auth::user()->id;
                    $instal_data->update();
                }
            }

            // Log the event
            bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Booking Installment Plan Update',
                'description' => 'Booking Installment Plan updated by user ' . auth()->id(),
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Installment plan updated successfully']);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message]);
        }
    }

    function addPayment(int $id)
    {
        if (view()->exists('modules.CRM.booking.modals.add-payment')) {
            //$booking = Booking::with('installmentPlan', 'payments')->findOrFail($id);
            $booking = Booking::with('payments')->findOrFail($id);
            //$totalInstallmentAmount = $booking->installmentPlan()->sum('amount');
            $totalInstallmentAmount = $booking->installmentPlan()->where('is_received', 0)->sum('amount');
            $totalInstallemts = $booking->installmentPlan()->count();
            // Fetch installment plans with pending payments for the booking
            $pendingInstallment = $booking->installmentPlan()
                ->where('is_received', 0) // Condition for pending payments
                ->take(1)
                ->get();
               //echo "<pre>"; print_r($booking->installmentPlan()->get()); echo "</pre>";
            return view('modules.CRM.booking.modals.add-payment', compact('booking', 'pendingInstallment','totalInstallmentAmount', 'totalInstallemts'));
        }
        return abort(404);
    }

    function savePayment(Request $request)
    {

        DB::beginTransaction();
        try {

            if (Auth::user()->role > 2) {
                throw new Exception("UnAuthorized Action", 1);
            }

            $booking_id = $request->booking_id;
            $installment_id = $request->installment_id;
            // Fetch the related Booking record
            $booking = Booking::findOrFail($booking_id);

            // Count the number of rows in the booking_installments table for the booking
            $totalBookingInstallments = bookingInstallmentPlan::where('booking_id', $booking_id)->count();

            // Check if the total_installments in the booking table matches the number of rows in booking_installments
            if ($booking->total_installment != $totalBookingInstallments) {
                // Return a JSON response indicating an error
                throw new Exception("Total installments do not match the install plan. Please verify the installment plan", 1);
            }

            //$installment = bookingInstallmentPlan::where('id', $installment_id)->where('is_received', 0)->get();
            $installment = bookingInstallmentPlan::where('id', $installment_id)->where('is_received', 0)->first();


            if (!$installment) {
                throw new Exception("Payment Already Added Against This Installment Number", 1);
            }
            //echo '<pre>'; print_r ($instl_value['installment_number']); echo '</pre>'; //exit;
            $remaining_amount = $request->total_balance_amount - $request->installment_amount;

            if ($request->hasFile('receipt_voucher')) {
                $file = $request->file('receipt_voucher');
                $receipt_voucher = date('ymd') . time() . $file->getClientOriginalName();
                $file->move('images/uploads/ReceiptVouchers/', $receipt_voucher);
                $receipt_voucher_data = $receipt_voucher;
            } else {
                //throw new Exception("Receipt Voucher Required", 1);
                $receipt_voucher_data = null;
            }

            $booking_payment = new bookingPayment([
                'booking_id'            => $booking_id,
                'payment_type'          => 2,
                'installment_id'        => $installment_id,
                'installment_no'        => $request->installment_number,
                'total_amount'          => $request->total_sales_cost,
                'reciving_amount'       => $request->installment_amount,
                'remaining_amount'      => $remaining_amount,
                'payment_method'        => $request->payment_method,

                'bank_name'             => $request->has('bank_name') ? $request['bank_name'] : null,
                'bank_branch'           => $request->has('bank_branch') ? $request['bank_branch'] : null,
                'office_date'           => $request->has('office_date') ? $request['office_date'] : null,
                'card_type_type'        => ($request->payment_method == 'Credit Debit Card' && $request->has('card_type_type')) ? $request['card_type_type'] : null,
                'card_type'             => ($request->payment_method == 'Credit Debit Card' && $request->has('card_type')) ? $request['card_type'] : null,
                'card_holder_name'      => ($request->payment_method == 'Credit Debit Card' && $request->has('cc-name')) ? $request['cc-name'] : null,
                'card_number'           => ($request->payment_method == 'Credit Debit Card' && $request->has('cc-number')) ? $request['cc-number'] : null,
                'card_expiry_date'      => ($request->payment_method == 'Credit Debit Card' && $request->has('cc-expiration')) ? $request['cc-expiration'] : null,
                'card_cvc'              => ($request->payment_method == 'Credit Debit Card' && $request->has('cc-cvv')) ? $request['cc-cvv'] : null,
                'cc_charges'            => ($request->payment_method == 'Credit Debit Card' && $request->has('cc-ccc')) ? $request['cc-ccc'] : null,
                'deposit_date'          => $request->received_on,

                'due_date'              => $request->due_date,
                'comments'              => $request->comments,
                'receipt_voucher'       => $receipt_voucher_data,
                'payment_on'            => Carbon::now(),
                'is_active'             => 1,
                'created_by'            => Auth::id(),
                'status'                => 1,
            ]);

            $booking_payment->save();

            if ($request->payment_method == 'Credit Debit Card') {
                $other_charges = new bookingOtherCharges([
                    'booking_id'        => $booking_id,
                    'payment_id'        => $booking_payment->id,
                    'charges_type'      => $request->has('cc-ccc') ? 'CC Charges' : "",
                    'amount'            => $request->has('cc-ccc') ? $request['cc-ccc'] : "0.00",
                    'reciving_amount'   => $request->has('cc-ccc') ? $request['cc-ccc'] : "0.00",
                    'remaining_amount'  => '0.00', //$request->has('cc-ccc') ? $request['cc-ccc'] : "",
                    'comments'          => $request->has('cc-ccc') ? 'Crdit / Debit Card Charges' : "",
                    'is_active'         => 1,
                    'payment_status'    => 1,
                    'created_by'        => Auth::id(),
                    'status'            => 1,
                ]);

                $other_charges->save();
            }


            // Log the event
            bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Booking Payment Received',
                'description' => 'Booking Payment Received of amount ' . $request->installment_amount . ' against installment : ' . $installment_id . ' & installment number ' . $request->installment_number . ' by user ' . Auth::user()->name,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            // Return success response
            return response()->json(['code' => 200, 'title' => 'Congratulations', 'icon' => 'success', 'message' => 'Payment added successfully']);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['code' => 400, 'title' => 'Warning', 'icon' => 'warning', 'message' => $message]);
        }
    }

    function editPayment(int $booking_id, int $payment_id)
    {
        if (view()->exists('modules.CRM.booking.modals.edit-payment')) {
            $booking = Booking::with('payments')->findOrFail($booking_id);
            // Fetch installment plans with pending payments for the booking
            $installment = bookingPayment::findOrFail($payment_id);
            return view('modules.CRM.booking.modals.edit-payment', compact('booking', 'installment'));
        }
        return abort(404);
    }

    function softDeletePayment(int $id)
    {

        DB::beginTransaction();
        try {
            // Find the payment record by ID
            $payment = BookingPayment::findOrFail($id);

            $booking_id = $payment->booking_id;
            $installment_id = $payment->installment_id;
            $total_amount = $payment->total_amount;
            $reciving_amount = $payment->reciving_amount;
            $remaining_amount = $payment->remaining_amount;

            $other_charges_id = $payment->other_charges_id;

            $booking = Booking::findOrFail($booking_id);

            // Check if the logged-in user is an admin or super admin
            $user = Auth::user(); // Get the currently logged-in user

            // You can define the roles in your application, for example:
            $isAuthorized = $user->role === 1 || $user->role === 2; //|| $user->role === 3;

            if (!$isAuthorized) {
                // Return an error response if the user is not authorized
                //return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
                throw new Exception("Unauthorized access", 1);

            }

            if ($other_charges_id != null) {
                //$booking_other_charges = bookingOtherCharges::where('booking_id', $booking_id)->where('payment_id', $id)->first(); //->get();
                $booking_other_charges = bookingOtherCharges::find($other_charges_id);
                if ($booking_other_charges) {
                    $booking->other_charges -= $reciving_amount;

                    $booking_other_charges->reciving_amount -= $reciving_amount;
                    $booking_other_charges->remaining_amount += $reciving_amount;
                    $booking_other_charges->payment_status = 0;
                    $booking_other_charges->save();
                    $booking_other_charges->delete();
                }
            }else{

                if ($payment->is_approved == 1) {
                    $booking->deposite_amount -= $reciving_amount;
                    $booking->balance_amount += $reciving_amount;
                }

                if ($installment_id != null) {
                    $installment_plan = bookingInstallmentPlan::findOrFail($installment_id);
                    $installment_plan->is_received = 0;
                    $installment_plan->received_by = null;
                    $installment_plan->received_on = null;
                    $installment_plan->save();
                }

                $payment_other_charges = bookingOtherCharges::where('payment_id', $id)->first();
                if ($payment_other_charges) {

                    // $booking->other_charges -= $payment->cc_charges;

                    $booking->other_charges = max(0, $booking->other_charges - $payment->cc_charges);

                    $payment_other_charges->reciving_amount = 0;
                    $payment_other_charges->remaining_amount = $payment->cc_charges;
                    $payment_other_charges->payment_status = 0;
                    $payment_other_charges->deleted_by = Auth::user()->id;
                    $payment_other_charges->save();
                    $payment_other_charges->delete();

                }
            }

            //$booking->payment_status = 1;
            $booking->payment_type = 2;
            $booking->payment_status = 3;

            // Save the updated Booking record
            $booking->save();

            // Soft delete the payment record
            $payment->deleted_by = Auth::user()->id;//Auth::id();
            $payment->save();
            $payment->delete();

            // Log the event
            bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Booking Payment Deleted',
                'description' => 'Booking Payment Deleted of amount ' . $reciving_amount . ' against installment / other charges : ' . $installment_id . ' by user ' . Auth::user()->name,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            // Return a JSON response indicating success
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Payment record deleted successfully'], 200);

        } catch (Exception $e) {
            DB::rollback();

            $message = $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    function approvePayment(int $booking_id, int $payment_id) {
        DB::beginTransaction();
        try {

            // Check if the logged-in user is an admin or super admin
            $user = Auth::user(); // Get the currently logged-in user

            // You can define the roles in your application, for example:
            $isAuthorized = $user->role === 1 || $user->role === 2; //|| $user->role === 3;

            if (!$isAuthorized) {
                throw new Exception("Unauthorized access", 1);
            }

            $booking = Booking::findOrFail($booking_id);

            $payment = bookingPayment::findOrFail($payment_id);

            if ($payment->is_approved == 1) {
                throw new Exception("Payment already approved", 1);
            }

            $installment_id = $payment->installment_id;

            $reciving_amount = $payment->reciving_amount;
            $remaining_amount = $payment->remaining_amount;
            $deposit_date = $payment->deposit_date;


            $booking->deposite_amount += $reciving_amount;
            $booking->balance_amount = $remaining_amount;
            $booking->deposit_date = $deposit_date;


            if ($payment->other_charges_id === null) {
                $booking->payment_status = ($remaining_amount === 0.00) ? 2 : 3;
            }

            /**
             * UPDATED ON 27-05-2025
             */
            $booking->other_charges += ($payment->payment_method == 'Credit Debit Card') ? $payment['cc_charges'] : "0.00";

            $booking->save();


            if ($installment_id != null) {
                $installment_plan = bookingInstallmentPlan::findOrFail($installment_id);
                $installment_plan->is_received        = 1;
                $installment_plan->received_on        = Carbon::now();
                $installment_plan->received_by        = Auth::user()->id;
                $installment_plan->save();
            }


            $payment->is_approved = 1;
            $payment->approved_by = auth()->id();
            $payment->approved_on = now();
            $payment->save();


            $longText = "Booking Payment Approved of amount $reciving_amount against installment : $installment_id by user ".Auth::user()->name;
             // Log the event
             bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Booking Payment Approved',
                'description' => $longText,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            // Return a JSON response indicating success
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => $longText.' successfully'], 200);

        } catch (Exception $e) {
            DB::rollback();
            $message = $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }


    function addOtherCharges(int $booking_id)
    {
        if (view()->exists('modules.CRM.booking.modals.add-other-charges')) {
            //$booking = Booking::with('installmentPlan', 'payments')->findOrFail($id);
            $booking = Booking::with('payments')->findOrFail($booking_id);
            // Fetch installment plans with pending payments for the booking
            $pendingInstallment = $booking->installmentPlan()
                ->where('is_received', 0) // Condition for pending payments
                ->take(1)
                ->get();
            return view('modules.CRM.booking.modals.add-other-charges', compact('booking', 'pendingInstallment'));
        }
        return abort(404);
    }

    function saveOtherCharges(Request $request)
    {

        // return response()->json($request, 200);
        DB::beginTransaction();
        try {
            $booking_id = $request->booking_id;
            $amount = $request->amount;
            $charges_at = $request->charges_at;
            $charges_type = $request->charges_type;
            $comments = $request->comments;

            $booking = Booking::findOrFail($booking_id);

            $other_charges = new bookingOtherCharges([
                'booking_id'        => $booking_id,
                'payment_id'        => null,
                'charges_type'      => $charges_type,
                'amount'            => $amount,
                'reciving_amount'   => 0.00,
                'remaining_amount'  => $amount,
                'comments'          => $comments,
                'charges_at'        => $charges_at,
                'is_active'         => 1,
                'payment_status'    => 0,
                'created_by'        => Auth::id(),
                'status'            => 1,
            ]);

            $other_charges->save();
            if ($charges_type == 'Date Change') {
                $booking->is_date_change = 1;
                $booking->update();
            }
            // Log the event
            bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Booking Other Charges Added',
                'description' => 'Booking Other Charges Added of amount ' . $amount . ' having comments : ' . $comments . ' by user ' . Auth::user()->name,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Other Charges Added Successfully'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    function addOtherChargesPayment(int $booking_id, int $other_charges_id)
    {

        if (view()->exists('modules.CRM.booking.modals.add-oc-payment')) {
            $booking = Booking::findOrFail($booking_id);
            $otherChargesPayments = bookingPayment::where('booking_id', $booking_id)->where('other_charges_id', $other_charges_id)->get();
            $otherCharges = bookingOtherCharges::where('id', $other_charges_id)->where('payment_status', 0)->first();

            return view('modules.CRM.booking.modals.add-oc-payment', compact('booking', 'otherCharges', 'otherChargesPayments'));
        }
        return abort(404);
    }

    function saveOtherChargesPayment(Request $request)
    {

        DB::beginTransaction();
        try {

            $booking_id = $request->booking_id;
            $other_charges_id = $request->other_charges_id;

            $booking_other_charges = 0.00;

            // Fetch the related Booking record
            $booking = Booking::findOrFail($booking_id);

            $p_other_charges = bookingOtherCharges::findOrFail($other_charges_id);

            if ($p_other_charges->payment_status == 1) {
                throw new Exception("Payment Already Received", 1);
            }

            if ($p_other_charges->remaining_amount < $request->reciving_amount) {
                throw new Exception("Entered amount is exceeding remaining amount", 1);
            }
            $remaining_amount = $p_other_charges->remaining_amount - $request->reciving_amount;
            //echo '<pre>'; print_r ($instl_value['installment_number']); echo '</pre>'; //exit;
            $booking_payment = new bookingPayment([
                'booking_id'            => $booking_id,
                'payment_type'          => 2,
                'installment_id'        => null,
                'installment_no'        => null,
                'other_charges_id'      => $request->other_charges_id,
                'total_amount'          => $request->amount,
                'reciving_amount'       => $request->reciving_amount,
                'remaining_amount'      => $remaining_amount,
                'payment_method'        => $request->payment_method,

                'bank_name'             => $request->has('bank_name') ? $request['bank_name'] : null,
                'bank_branch'           => $request->has('bank_branch') ? $request['bank_branch'] : null,
                'office_date'           => $request->has('office_date') ? $request['office_date'] : null,
                'card_type_type'        => ($request->payment_method == 'Credit Debit Card' && $request->has('card_type_type')) ? $request['card_type_type'] : null,
                'card_type'             => ($request->payment_method == 'Credit Debit Card' && $request->has('card_type')) ? $request['card_type'] : null,
                'card_holder_name'      => ($request->payment_method == 'Credit Debit Card' && $request->has('cc-name')) ? $request['cc-name'] : null,
                'card_number'           => ($request->payment_method == 'Credit Debit Card' && $request->has('cc-number')) ? $request['cc-number'] : null,
                'card_expiry_date'      => ($request->payment_method == 'Credit Debit Card' && $request->has('cc-expiration')) ? $request['cc-expiration'] : null,
                'card_cvc'              => ($request->payment_method == 'Credit Debit Card' && $request->has('cc-cvv')) ? $request['cc-cvv'] : null,
                'cc_charges'            => ($request->payment_method == 'Credit Debit Card' && $request->has('cc-ccc')) ? $request['cc-ccc'] : null,
                'deposit_date'          => $request->received_on,

                'due_date'              => $request->due_date,
                'comments'              => $request->comments,
                'is_approved'           => 1,
                'approved_on'           => Carbon::now(),
                'payment_on'            => Carbon::now(),
                'is_active'             => 1,
                'created_by'            => Auth::id(),
                'status'                => 1,
            ]);

            $booking_payment->save();

            $p_other_charges->reciving_amount += $request->reciving_amount;
            $p_other_charges->remaining_amount -= $request->reciving_amount;

            if ($remaining_amount == 0) {
                $p_other_charges->payment_status = 1;
            }

            $p_other_charges->updated_by = Auth::id();
            $p_other_charges->save();


            if ($request->payment_method == 'Credit Debit Card') {
                $other_charges = new bookingOtherCharges([
                    'booking_id'        => $booking_id,
                    'payment_id'        => $booking_payment->id,
                    'charges_type'      => $request->has('cc-ccc') ? 'CC Charges' : "",
                    'amount'            => $request->has('cc-ccc') ? $request['cc-ccc'] : "0.00",
                    'reciving_amount'   => $request->has('cc-ccc') ? $request['cc-ccc'] : "0.00",
                    'remaining_amount'  => '0.00',
                    'comments'          => $request->has('cc-ccc') ? 'Crdit / Debit Card Charges' : "",
                    'is_active'         => 1,
                    'payment_status'    => 1,
                    'created_by'        => Auth::id(),
                    'status'            => 1,
                ]);

                $other_charges->save();

                $booking_other_charges += ($request->has('cc-ccc')) ? $request['cc-ccc'] : "0.00";
            }

            $booking_other_charges += $request->reciving_amount;

            // Update the total_deposit and total_balance columns in the Booking record
            $booking->other_charges += $booking_other_charges;
            $booking->deposit_date = Carbon::now();
            // Save the updated Booking record
            $booking->save();

            // Log the event
            bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Booking Other Charges Payment',
                'description' => 'Booking Other Charges Payment Received of amount ' . $request->reciving_amount . ' having other charges id : ' . $request->other_charges_id . ' by user ' . auth()->id(),
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Payment added successfully'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    function softDeleteOtherCharges(int $id)
    {

        DB::beginTransaction();
        try {
            // Check if the logged-in user is an admin or super admin
            $user = Auth::user(); // Get the currently logged-in user

            // You can define the roles in your application, for example:
            $isAuthorized = $user->role === 1 || $user->role === 2; //|| $user->role === 3;

            if (!$isAuthorized) {
                throw new Exception("Unauthorized access", 1);
            }

            $booking_other_charges = bookingOtherCharges::find($id);

            if ($booking_other_charges->reciving_amount > 0) {
                throw new Exception("Payment already added, please first delete the payment then try to delete the other charges", 1);
            }
            $booking_id = $booking_other_charges->booking_id;

            // Soft delete the payment record
            $booking_other_charges->deleted_by = Auth::user()->id;//Auth::id();
            $booking_other_charges->save();
            $booking_other_charges->delete();

            // Log the event
            bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Booking Other Charges Deleted',
                'description' => 'Booking Other Charges Deleted of amount ' . $booking_other_charges->amount . ' by user ' . Auth::user()->name,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Other Charges record deleted successfully'], 200);

        } catch (Exception $e) {
            DB::rollback();
            $message = $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    function updateTicketStatus(int $id)
    {
        if (view()->exists('modules.CRM.booking.modals.update-ticket-status')) {
            $booking = Booking::findOrFail($id);
            $ticketStatus = Type::with(['details' => function ($query) {
                $query->orderBy('details');
            }])->where('title', 'Ticket Status')->first()->details;

            $flightSuppliers = Vendor::all();

            return view('modules.CRM.booking.modals.update-ticket-status', compact('booking', 'ticketStatus', 'flightSuppliers'));
        }
        return abort(404);
    }


    function saveTicketStatus(Request $request)
    {

        // return $request->all();

        DB::beginTransaction();
        try {

            $booking_id         = $request->booking_id;
            // Fetch the related Booking record
            $booking = Booking::findOrFail($booking_id);
            $logMessage = 'Booking Ticket Status Changed to ' . $request->ticket_status;
            if ($request->ticket_supplier !== null) {

                $ticket_supplier    = explode("__", $request->ticket_supplier);
                $booking->ticket_supplier_id    = $ticket_supplier[0];
                $booking->ticket_supplier       = $ticket_supplier[1];

                $logMessage .=' & selected ticket vendor is: ' . $ticket_supplier[1];
            }

            $logMessage .= ' by user ' . Auth::user()->name;

            // Update the total_deposit and total_balance columns in the Booking record
            $booking->ticket_status         = $request->ticket_status;

            $booking->booking_status_on      = now();
            $booking->booking_status_by      = auth()->id();

            // Save the updated Booking record
            $booking->save();

            // Log the event
            bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Booking Ticket Status Changed',
                'description' => $logMessage,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Ticket status updated successfully'], 200);
            //return redirect()->route('crm.create-booking')->with('success', 'Booking Generated Successfully With Booking Number:');
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            //$message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            //echo '<pre>'; print_r($message); echo '</pre>'; //exit;
            //return redirect()->route('crm.create-booking')->with('error', $message)->withInput();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    function updateBookingStatus(int $id)
    {
        if (view()->exists('modules.CRM.booking.modals.update-booking-status')) {
            //$booking = Booking::with('installmentPlan', 'payments')->findOrFail($id);
            $booking = Booking::findOrFail($id);
            $bookingStatus = Type::where('title', 'Booking Status')->first()->details;
            //dd($bookingStatus);
            return view('modules.CRM.booking.modals.update-booking-status', compact('booking', 'bookingStatus'));
        }
        return abort(404);
    }

    function saveBookingStatus(Request $request)
    {

        //return $request->all();

        DB::beginTransaction();
        try {

            $booking_id = $request->booking_id;
            // Fetch the related Booking record
            $booking = Booking::findOrFail($booking_id);

            /** UPDATE ON 02-08-2025 */
            $oldData = Booking::where('id', $booking_id)->get()->map(function ($booking) {
                return clone $booking;
            });
            /** END */


            // Update the total_deposit and total_balance columns in the Booking record
            $booking->booking_status = $request->booking_status;


            // Save the updated Booking record
            $booking->save();

            /** UPDATE ON 02-08-2025 */
            $newData = Booking::where('id', $booking_id)->get();
            $booking_number = $booking->booking_prefix . $booking->booking_number;
            sendBookingUpdateMail($oldData, $newData, $booking_number, 'Booking Status Changed');
            /** END */

            // Log the event
            bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Booking Status Changed',
                'description' => 'Booking Status Changed to ' . $request->booking_status . ' by user ' . auth()->id(),
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Booking status updated successfully'], 200);
            //return redirect()->route('crm.create-booking')->with('success', 'Booking Generated Successfully With Booking Number:');
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            //$message = " Error Code: " . $e->getCode();
            //$message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            //echo '<pre>'; print_r($message); echo '</pre>'; //exit;
            //return redirect()->route('crm.create-booking')->with('error', $message)->withInput();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    function addRefund(int $booking_id)
    {
        if (view()->exists('modules.CRM.booking.modals.add-refund')) {
            $booking = Booking::with('refunds')->findOrFail($booking_id);
            return view('modules.CRM.booking.modals.add-refund', compact('booking'));
        }
        return abort(404);
    }

    function saveRefund(Request $request)
    {

        DB::beginTransaction();
        try {
            $data = $request->all();

            $bank_name = $request->bank_name;
            $booking_id = $request->booking_id;
            $card_type = $request->card_type;
            $ccc = $request->ccc;
            $cvv = $request->cvv;
            $expiration = $request->expiration;
            $name = $request->name;
            $number = $request->number;
            $comments = $request->comments;
            $paid_amount = $request->paid_amount;
            $payment_method = $request->payment_method;
            $refund_requeseted_on = $request->refund_requeseted_on;
            $refundable_amount = $request->refundable_amount;
            $refunded_amount = $request->refunded_amount;
            $service_charges = $request->service_charges;
            $refund_type = $request->refund_type;

            // Fetch the related Booking record
            $booking = Booking::findOrFail($booking_id);

            $booking_deposite_amount = $booking->deposite_amount;

            $balance_check = $refunded_amount + $service_charges;

            if ($refund_type == 'fullRefund' || $refund_type == 'bookingFlight') {
                if ($booking->booking_payment_term === 1) {
                    // Return a JSON response indicating an error
                    throw new Exception("This Booking is non-refundable", 1);
                }
            }

            $booking_refund = bookingRefund::where('booking_id', $booking_id)->where('refund_status', 0)->count();
            if ($booking_refund > 0) {
                throw new Exception("Refund Request Already Initilized, please first take action against previous generated refund request...", 1);
            }



            if (isset($data['refund_type']) && ($data['refund_type'] === 'fullRefund')) {
                $this->processRefunds('bookingFlight', null, $booking_id, true);
                $this->processRefunds('bookingHotel', null, $booking_id, true);
                $this->processRefunds('bookingTransport', null, $booking_id, true);
            } else {
                if (isset($data[$data['refund_type']])) {
                    $this->processRefunds($data['refund_type'], $data[$data['refund_type']], null, true);
                } /* else {
                    $processRefunds = "NULL";
                } */
            }

            if ($balance_check > $booking_deposite_amount) {
                throw new Exception("Refund amount must be less then or equal to deposit amount", 1);
            }


            $booking_refund = new bookingRefund([
                'booking_id'            => $booking_id,
                'paid_amount'           => $paid_amount,
                'refunded_amount'       => $refunded_amount,
                'service_charges'       => $service_charges,
                'remaining_amount'      => $paid_amount - ($refunded_amount + $service_charges),
                'refund_type'           => $refund_type,

                'payment_method'        => $payment_method,

                'bank_name'             => $request->has('bank_name') ? $request['bank_name'] : null,
                'bank_branch'           => $request->has('bank_branch') ? $request['bank_branch'] : null,
                'office_date'           => $request->has('office_date') ? $request['office_date'] : null,
                'card_type'             => ($request->payment_method == 'Credit Debit Card' && $request->has('card_type')) ? $request['card_type'] : null,
                'card_holder_name'      => ($request->payment_method == 'Credit Debit Card' && $request->has('cc-name')) ? $request['cc-name'] : null,
                'card_number'           => ($request->payment_method == 'Credit Debit Card' && $request->has('cc-number')) ? $request['cc-number'] : null,
                'card_expiry_date'      => ($request->payment_method == 'Credit Debit Card' && $request->has('cc-expiration')) ? $request['cc-expiration'] : null,
                'card_cvc'              => ($request->payment_method == 'Credit Debit Card' && $request->has('cc-cvv')) ? $request['cc-cvv'] : null,
                'cc_charges'            => ($request->payment_method == 'Credit Debit Card' && $request->has('cc-ccc')) ? $request['cc-ccc'] : null,
                'refund_requeseted_on'  => $refund_requeseted_on,

                'refund_status'         => '0',
                'comments'              => $comments,
                //'payment_on'            => Carbon::now(),
                'is_active'             => 1,
                //'is_instant_refund'     => ($request->is_instant_refund == true) ? 1 : 0,
                'created_by'            => Auth::id(),
                'status'                => 1,
            ]);

            $booking_refund->save();

            // Log the event
            bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Booking refund request initiated',
                'description' => 'Booking refund request of ' . $data['refund_type'] . ' initiated of amount ' . $request->refunded_amount . ' against booking number : ' . $booking->booking_prefix . $booking->booking_number . ' by user ' . Auth::user()->name . ' having refund id ' . $booking_refund->id,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            // Return success response
            return response()->json(['code' => 200, 'title' => 'Congratulations', 'icon' => 'success', 'message' => 'Refund Request added successfully']);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['code' => 400, 'title' => 'Warning', 'icon' => 'warning', 'message' => $message]);
        }
    }

    function processRefunds($model_name, $refund_data = null, $booking_id = null, $process_refund = false)
    {
        //return $refund_data;
        $modelClass = $model_name;
        $modelClass = "App\\Models\\{$modelClass}";
        $modelInstance = resolve($modelClass);

        if ($refund_data != null) {
            $modelClassData = $refund_data;
        } elseif ($booking_id != null) {
            $modelClassData = $modelInstance::where('booking_id', $booking_id)->get();
        }

        if ($process_refund == false) {
            return $modelClassData;
        } else {
            /** UPDATE REFUND STATUS IN TABLE */
            foreach ($modelClassData as $data_key => $modelClassD) {
                $record = $modelInstance::find($modelClassD['id']);
                $record->is_refunded = 1;
                $record->save();
            }
            return true;
        }
    }

    function editRefund(int $booking_id, int $refund_id)
    {
        if (view()->exists('modules.CRM.booking.modals.edit-refund')) {
            $booking = Booking::with('payments', 'refunds')->findOrFail($booking_id);
            $refund = bookingRefund::findOrFail($refund_id);
            return view('modules.CRM.booking.modals.edit-refund', compact('booking', 'refund'));
        }
        return abort(404);
    }

    function approveRefund(int $booking_id, int $refund_id)
    {

        //return response()->json([$booking_id , $refund_id], 200);

        DB::beginTransaction();
        try {
            // Find the payment record by ID
            $booking = Booking::findOrFail($booking_id);
            $bookingRefund = bookingRefund::findOrFail($refund_id);

            $refund_status = $bookingRefund->refund_status;

            $refunded_remaining_amount = $bookingRefund->remaining_amount;

            if ($booking->ticket_status != 18) {
                throw new Exception("Refund still in process, please complete the refund process first...", 1);
            }

            if ($refund_status > 0) {
                throw new Exception("This refund Cant be approved becuase its already approved or rejected", 1);
            }

            $bookingRefund->refund_status = 1;
            $bookingRefund->refunded_on = Carbon::now();
            $bookingRefund->updated_by = Auth::user()->id;

            // Save the updated Booking record
            $bookingRefund->save();

            $booking->refunded_amount += $bookingRefund->refunded_amount;
            $booking->ticket_status = 6; // updated on 2025-04-17
            $booking->booking_status = 3;
            $booking->save();


            // Log the event
            bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Booking Refund Approved',
                'description' => 'Booking refund of amount ' . $bookingRefund->refunded_amount . ' has been approved against booking number : ' . $booking->booking_prefix . $booking->booking_number . ' by user ' . auth()->id() . ' / ' . Auth::user()->name . ' on ' . Carbon::now(),
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            // Return a JSON response indicating success
            return response()->json(['success' => true, 'message' => 'Refund approved successfully.'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "\n Error Message: " . $e->getMessage();
            return response()->json(['success' => false, 'message' => $message], 400);
        }
    }

    function rejectRefund(int $booking_id, int $refund_id)
    {

        //return response()->json([$booking_id , $refund_id], 200);

        DB::beginTransaction();
        try {
            // Find the payment record by ID
            $booking = Booking::findOrFail($booking_id);
            $bookingRefund = bookingRefund::findOrFail($refund_id);

            $refund_status = $bookingRefund->refund_status;

            $refunded_remaining_amount = $bookingRefund->remaining_amount;

            if ($refund_status > 0) {
                throw new Exception("This refund can't be rejected becuase its already approved or rejected", 1);
            }

            $bookingRefund->refund_status = 2;
            //$bookingRefund->refunded_on = Carbon::now();
            $bookingRefund->updated_by = Auth::user()->id;

            // Save the updated Booking record
            $bookingRefund->save();

            // Log the event
            bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Booking Refund Rejected',
                'description' => 'Booking refund of amount ' . $bookingRefund->refunded_amount . ' has been rejected against booking number : ' . $booking->booking_prefix . $booking->booking_number . ' by user ' . auth()->id() . ' / ' . Auth::user()->name . ' on ' . Carbon::now(),
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            // Return a JSON response indicating success
            return response()->json(['success' => true, 'message' => 'Refund rejected successfully.'], 200);
        } catch (Exception $e) {
            DB::rollback();

            $message = "\n Error Message: " . $e->getMessage();
            return response()->json(['success' => false, 'message' => $message], 400);
        }
    }

    function addHotelRefund(int $booking_id)
    {
        if (view()->exists('modules.CRM.booking.modals.add-hotel-refund')) {
            $booking = Booking::with('refunds', 'hotels', 'prices')->findOrFail($booking_id);
            return view('modules.CRM.booking.modals.add-hotel-refund', compact('booking'));
        }
        return abort(404);
    }

    function getDataForRefund(Request $request)
    {

        $modalClassName = $request->refund_type;
        $booking_id = $request->booking_id;
        $f_data = array();
        $pricing = array();
        if ($modalClassName !== 'fullRefund' && $modalClassName !== 'bookingVisa') {
            $data  = $this->processRefunds($modalClassName, null, $booking_id, false);
            //echo '<pre>'; print_r($data); echo '</pre>'; //exit;

            //$f_data = array();

            foreach ($data as $key => $value) {
                $f_data[] = array(
                    'id' => $value->id,
                    'booking_id' => $value->booking_id,
                    'name' => ($modalClassName == 'bookingHotel') ? $value->hotel_name : (($modalClassName == 'bookingTransport') ? $value->transport_type : (($modalClassName == 'bookingFlight') ? $value->departure_airport . " - " . $value->arrival_airport : '')),
                    'data_model' => $modalClassName,
                    'raw_data' => json_encode($value)
                );
            }
            $pricing = bookingPricing::where('booking_id', $booking_id)->where('pricing_type', $modalClassName)->get();
        } else if($modalClassName == 'bookingVisa') {
            $pricing = bookingPricing::where('booking_id', $booking_id)->where('pricing_type', $modalClassName)->get();
        }

        //return response()->json([$request->all(), $data], 200);
        return response()->json([$f_data, $pricing]);
    }

    function editPassenger(int $booking_id)
    {

        if (view()->exists('modules.CRM.booking.modals.edit-passenger')) {
            //$booking = Booking::with('refunds')->findOrFail($booking_id);
            $passengers = Passenger::where('booking_id', $booking_id)->get();
            return view('modules.CRM.booking.modals.edit-passenger', compact('passengers', 'booking_id'));
        }
        return abort(404);
    }

    function updatePassenger(Request $request)
    {
        //return response()->json([$request->all()], 400);
        DB::beginTransaction();
        try {

            $booking_id = $request->booking_id;
            $passengers = $request->input('passenger');
            $booking = Booking::findOrFail($booking_id);
            /** UPDATE ON 02-08-2025 */
            $oldData = Passenger::where('booking_id', $booking_id)->get()->map(function ($passenger) {
                return clone $passenger;
            });
            /** END */

            foreach ($passengers as $key => $passengerData) {

                if (isset($passengerData['id'])) {
                    //return response()->json([$passengers,$passengerData['id']]);

                    // Update existing passenger
                    $passenger = Passenger::find($passengerData['id']);
                    if ($passenger) {

                        $passenger->booking_id          = $booking_id;
                        $passenger->title               = $passengerData['title'];
                        $passenger->first_name          = $passengerData['first_name'];
                        $passenger->middle_name         = $passengerData['middle_name'];
                        $passenger->last_name           = $passengerData['last_name'];

                        $passenger->date_of_birth       = ($passengerData['date_of_birth'] != null) ? (Carbon::parse($passengerData['date_of_birth'])->format('Y-m-d')) : null;
                        $passenger->age                 = ($passengerData['date_of_birth'] != null) ? calculateAge($passengerData['date_of_birth']) : null;
                        $passenger->nationality         = $passengerData['nationality'];
                        $passenger->mobile_number       = $passengerData['mobile_number'];
                        $passenger->email               = $passengerData['email'];
                        $passenger->address             = $passengerData['address'];
                        $passenger->post_code           = $passengerData['post_code'];
                        $passenger->ticket_number       = $passengerData['ticket_number'];
                        $passenger->pnr_code            = $passengerData['pnr_code'];
                        $passenger->is_active           = 1;
                        $passenger->status              = 1;
                        $passenger->updated_by          = Auth::user()->id;
                        $passenger->update();

                        //$passenger->update($passengerData);
                    }
                } else {
                    // Create new passenger
                    $passengerData['booking_id'] = $booking_id;
                    $passengerData['date_of_birth'] = ($passengerData['date_of_birth'] != null) ? (Carbon::parse($passengerData['date_of_birth'])->format('Y-m-d')) : null;
                    $passengerData['age'] = ($passengerData['date_of_birth'] != null) ? calculateAge($passengerData['date_of_birth']) : null;
                    $passengerData['created_by'] = Auth::user()->id;
                    Passenger::create($passengerData);
                }
            }

            /** UPDATE ON 02-08-2025 */
            $newData = Passenger::where('booking_id', $booking_id)->get();
            $booking_number = $booking->booking_prefix . $booking->booking_number;
            sendBookingUpdateMail($oldData, $newData, $booking_number, 'Passenger');
            /** END */

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Passengers updated successfully'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    public function deletePassenger(int $passenger_id)
    {
        DB::beginTransaction();
        try {
            $passengerId = $passenger_id;
            $passenger = Passenger::findOrFail($passengerId);
            $booking = Booking::findOrFail($passenger->booking_id);
            /** UPDATE ON 02-08-2025 */
            $oldData = Passenger::where('id', $passengerId)->get()->map(function ($passenger) {
                return clone $passenger;
            });
            /** END */

            $activePassengerCount = Passenger::where('booking_id', $passenger->booking_id)->where('id', '!=', $passengerId)
            ->count();

            if ($activePassengerCount <= 0) {
                throw new Exception("Cannot delete the passanger for this booking because booking dont have other passangers details.", 1);
            }

            $passenger->delete();

            /** UPDATE ON 02-08-2025 */
            $newData = Passenger::where('id', $passengerId)->get();
            $booking_number = $booking->booking_prefix . $booking->booking_number;
            sendBookingUpdateMail($oldData, $newData, $booking_number, 'Passenger Removed');
            /** END */

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Passenger removed successfully'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    function editBookingInformation(int $booking_id)
    {

        if (view()->exists('modules.CRM.booking.modals.edit-booking-information')) {
            $booking = Booking::with('company')->findOrFail($booking_id);
            return view('modules.CRM.booking.modals.edit-booking-information', compact('booking'));
        }
        return abort(404);
    }

    function updateBookingInformation(Request $request)
    {
        //return response()->json($request->all(), 200);
        DB::beginTransaction();
        try {

            $booking_id             = $request->booking_id;
            //$company_id             = $request->company_id;
            $booking_date           = $request->booking_date;
            $ticket_deadline        = $request->ticket_deadline;
            $booking_payment_term   = $request->booking_payment_term;
            $is_full_package        = $request->is_full_package;
            $total_installment      = $request->total_installment;
            $currency               = $request->currency;

            // Update existing passenger
            $booking = Booking::findOrFail($booking_id);

            //$booking->company_id            = $company_id;
            $booking->booking_date          = $booking_date;
            $booking->ticket_deadline       = $ticket_deadline;
            $booking->booking_payment_term  = $booking_payment_term;
            $booking->currency              = $currency;
            //$booking->total_installment     = $total_installment;
            $booking->total_installment     = ($request->has('total_installment') && $booking->payment_type == 2) ? $total_installment : null;
            $booking->is_full_package       = $request->has('is_full_package') == true ? 1 : 0;
            //$booking->is_active             = 1;
            //$booking->status                = 1;
            $booking->updated_by            = Auth::user()->id;
            $booking->update();
            if ($booking->payment_type == 2) {
                // Get the current number of installment plans
                $currentInstallmentsCount = $booking->installmentPlan->count();

                if ($total_installment > $currentInstallmentsCount) {
                    // Add extra rows if total_installments is increased
                    $installmentsToAdd = $total_installment - $currentInstallmentsCount;
                    for ($i = 1; $i <= $installmentsToAdd; $i++) {
                        $booking->installmentPlan()->create([
                            'booking_id'            => $booking_id, // Default amount
                            'installment_number'    => $currentInstallmentsCount + $i, // Default amount
                            'due_date'              => now(), //now()->addMonths($currentInstallmentsCount + $i), // Example due date
                            'amount'                => 0.00, // Default amount
                            'is_received'           => 0, // Default amount
                            'is_active'             => 1, // Default amount
                            'created_by'            => Auth::user()->id, // Default amount
                            'status'                => 1, // Default status
                        ]);
                    }
                } elseif ($total_installment < $currentInstallmentsCount) {
                    // Remove extra rows if total_installments is decreased
                    $installmentsToRemove = $currentInstallmentsCount - $total_installment;
                    $booking->installmentPlan()
                        ->where('is_received', 0)
                        //->latest()
                        ->orderBy('id', 'desc')
                        ->take($installmentsToRemove)
                        ->delete();
                }
            }
            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Booking Informaion updated successfully'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    function editHotelDetails(int $booking_id)
    {

        $vendors = Vendor::all();

        if (view()->exists('modules.CRM.booking.modals.edit-hotel')) {
            $booking = Booking::with('hotels')->findOrFail($booking_id);
            return view('modules.CRM.booking.modals.edit-hotel', compact('booking', 'vendors'));
        }
        return abort(404);
    }

    function updateHotelDetails(Request $request)
    {

        DB::beginTransaction();
        try {

            $booking_id = $request->booking_id;

            $booking = Booking::findOrFail($booking_id);

            $request_data = $request->input('hotel');
            $quantity = count($request_data);

            $total_sales_cost_old = 0;
            $total_net_cost_old = 0;

            $total_sales_cost = 0;
            $total_net_cost = 0;
            /** UPDATE ON 25-07-2025 */
            $oldData = bookingHotel::where('booking_id', $booking_id)->get()->map(function ($hotel) {
                return clone $hotel;
            });
            /** END */
            foreach ($request_data as $key => $data) {

                $total_sales_cost += $data['sale_cost'];
                $total_net_cost += $data['net_cost'];

                if (isset($data['id'])) {
                    //return response()->json([$passengers,$data['id']]);

                    // Update existing passenger
                    $db_data = bookingHotel::find($data['id']);

                    if ($db_data) {

                        $total_sales_cost_old += $db_data->sale_cost;
                        $total_net_cost_old += $db_data->net_cost;


                        $db_data->supplier                      = $data['supplier'];
                        $db_data->hotel_name                    = $data['hotel_name'];
                        $db_data->check_in_date                 = $data['check_in_date'];
                        $db_data->check_out_date                = $data['check_out_date'];
                        $db_data->total_nights                  = $data['total_nights'];
                        $db_data->meal_type                     = $data['meal_type'];
                        $db_data->room_type                     = $data['room_type'];
                        $db_data->hotel_confirmation_number     = $data['hotel_confirmation_number'];
                        $db_data->sale_cost                     = $data['sale_cost'];
                        $db_data->net_cost                      = $data['net_cost'];
                        $db_data->deadline                      = $data['deadline'];
                        $db_data->updated_by                    = Auth::user()->id;
                        $db_data->update();

                    }
                } else {
                    // Create new passenger
                    $data['booking_id'] = $booking_id;
                    $data['is_refunded'] = 0;
                    $data['is_active'] = 1;
                    $data['created_by'] = Auth::user()->id;
                    bookingHotel::create($data);
                }
            }

            $booking_pricing = bookingPricing::where('booking_id', $booking_id)->where('booking_type', 'Hotel Price')->first();
            $booking_pricing->sale_cost = $total_sales_cost;
            $booking_pricing->net_cost = $total_net_cost;

            $booking_pricing->total = $total_sales_cost;
            $booking_pricing->net_total = $total_net_cost;
            $booking_pricing->quantity = $quantity;
            $booking_pricing->save();

            $booking->total_sales_cost -= $total_sales_cost_old;
            $booking->total_net_cost -= $total_net_cost_old;
            $booking->margin = $booking->total_sales_cost - $booking->total_net_cost;

            $booking->total_sales_cost += $total_sales_cost;
            $booking->total_net_cost += $total_net_cost;
            $booking->margin = $booking->total_sales_cost - $booking->total_net_cost;

            $booking->balance_amount = $booking->total_sales_cost - $booking->deposite_amount;

            if ($booking->balance_amount > 0)  {
                $booking->payment_status = 3;
            }

            $booking->save();

            $bookingPayments = bookingPayment::where('booking_id', $booking_id)->whereNull('other_charges_id')->get();
            $remainingAmount = $booking->total_sales_cost; // Start with the total sales cost
            foreach ($bookingPayments as $payment) {
                $payment->total_amount = $booking->total_sales_cost;
                //$remainingAmount = $totalSalesCost - $payment->reciving_amount;
                // Subtract the receiving amount from the remaining amount for each iteration
                $remainingAmount -= $payment->reciving_amount;
                // Assign the remaining amount to the payment
                $payment->remaining_amount = $remainingAmount;
                $payment->save();
            }
            /** UPDATE ON 25-07-2025 */
            $newData = bookingHotel::where('booking_id', $booking_id)->get();
            $booking_number = $booking->booking_prefix . $booking->booking_number;
            sendBookingUpdateMail($oldData, $newData, $booking_number, 'Hotel');
            /** END */
            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Hotel updated successfully'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    public function deleteHotelDetails(int $hotel_id)
    {
        DB::beginTransaction();
        try {

            $hotel = bookingHotel::findOrFail($hotel_id);
            $booking_id = $hotel->booking_id;

            $booking = Booking::findOrFail($booking_id);

            if ($hotel->is_refunded == 1) {
                throw new Exception("This hotel is refunded", 1);
            }

            /** UPDATE ON 02-08-2025 */
            $oldData = bookingHotel::where('id', $hotel_id)->get()->map(function ($hotel) {
                return clone $hotel;
            });
            /** END */

            $sale_cost = $hotel->sale_cost;
            $net_cost = $hotel->net_cost;


            $booking->total_sales_cost -= $sale_cost;
            $booking->total_net_cost -= $net_cost;
            $booking->margin = $booking->total_sales_cost - $booking->total_net_cost;
            $booking->save();


            $booking_pricing = bookingPricing::where('booking_id', $booking_id)->where('booking_type', 'Hotel Price')->first();
            $booking_pricing->sale_cost -= $sale_cost;
            $booking_pricing->net_cost -= $net_cost;

            $booking_pricing->total -= $sale_cost;
            $booking_pricing->net_total -= $net_cost;
            $booking_pricing->quantity -= 1;
            $booking_pricing->save();

            $hotel->delete();

            /** UPDATE ON 02-08-2025 */
            $newData = bookingHotel::where('id', $hotel_id)->get();
            $booking_number = $booking->booking_prefix . $booking->booking_number;
            sendBookingUpdateMail($oldData, $newData, $booking_number, 'Hotel');
            /** END */

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Hotel removed successfully'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    function addHotelActualNet(int $booking_id)
    {

        $vendors = Vendor::all();

        if (view()->exists('modules.CRM.booking.modals.add-hotel-actual-net')) {
            $booking = Booking::with('hotels')->findOrFail($booking_id);
            return view('modules.CRM.booking.modals.add-hotel-actual-net', compact('booking', 'vendors'));
        }
        return abort(404);
    }

    function updateHotelActualNet(Request $request)
    {

        DB::beginTransaction();
        try {

            $booking_id = $request->booking_id;

            $booking = Booking::findOrFail($booking_id);

            /** UPDATE ON 02-08-2025 */
            $oldData = bookingHotel::where('booking_id', $booking->id)->get()->map(function ($hotel) {
                return clone $hotel;
            });
            /** END */

            $request_data = $request->input('hotel');

            $actual_net_cost = 0;

            foreach ($request_data as $key => $data) {

                $actual_net_cost += $data['actual_net_cost'];

                if (isset($data['id'])) {

                    $db_data = bookingHotel::find($data['id']);

                    if ($db_data) {

                        $db_data->supplier                      = $data['supplier'];
                        $db_data->actual_net_cost               = $data['actual_net_cost'];
                        $db_data->actual_net_on                 = Carbon::now()->format('Y-m-d');
                        $db_data->actual_net_by                 = Auth::user()->id;
                        $db_data->updated_by                    = Auth::user()->id;
                        $db_data->update();

                    }
                }
            }

            bookingPricing::where('booking_id', $booking_id)->where('pricing_type', 'bookingHotel')->update(['actual_net_total' => $actual_net_cost]);

            $totalActualNet = bookingPricing::where('booking_id', $booking_id)->sum('actual_net_total');

            // Calculate actual_margin (assuming it's based on some formula)
            //$actualMargin = $this->total_cost - $totalActualNet; // Adjust formula as needed

            $actual_margin = $booking->total_sales_cost - $totalActualNet;
            // $booking->actual_net_cost += $actual_net_cost;
            $booking->actual_net_cost = $totalActualNet;
            $booking->actual_margin = $actual_margin;



            $booking->save();

            /** UPDATE ON 02-08-2025 */
            $newData = bookingHotel::where('booking_id', $booking->id)->get();
            $booking_number = $booking->booking_prefix . $booking->booking_number;
            sendBookingUpdateMail($oldData, $newData, $booking_number, 'Hotel Actual Net');
            /** END */

            // Log the event
            bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Hotel Actual Net Added / Updated',
                'description' => 'Booking Hotel Aactual net updated by user ' . Auth::user()->name.'. new total actual net is:'. $actual_net_cost.'  & actual margin is:'. $actual_margin,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Hotel actual net updated successfully'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }


    function editTransportDetails(int $booking_id)
    {

        $vendors = Vendor::all();
        $airports = airport::all();
        if (view()->exists('modules.CRM.booking.modals.edit-transport')) {
            $booking = Booking::with('transports')->findOrFail($booking_id);
            return view('modules.CRM.booking.modals.edit-transport', compact('booking', 'vendors', 'airports'));
        }
        return abort(404);
    }

    function updateTransportDetails(Request $request)
    {
        //return response()->json($request->all());
        DB::beginTransaction();
        try {

            $booking_id = $request->booking_id;

            $booking = Booking::findOrFail($booking_id);

            /** UPDATE ON 02-08-2025 */
            $oldData = bookingTransport::where('booking_id', $booking->id)->get()->map(function ($transport) {
                return clone $transport;
            });
            /** END */

            $request_data = $request->input('transport');
            $quantity = count($request_data);

            $total_sales_cost_old = 0;
            $total_net_cost_old = 0;

            $total_sales_cost = 0;
            $total_net_cost = 0;

            foreach ($request_data as $key => $data) {

                $total_sales_cost += $data['sale_cost'];
                $total_net_cost += $data['net_cost'];

                if (isset($data['id'])) {
                    //return response()->json([$passengers,$data['id']]);

                    // Update existing passenger
                    $db_data = bookingTransport::find($data['id']);

                    if ($db_data) {

                        $total_sales_cost_old += $db_data->sale_cost;
                        $total_net_cost_old += $db_data->net_cost;


                        $db_data->supplier               = $data['supplier'];
                        $db_data->reference_no           = $data['reference_no'];
                        $db_data->transport_type         = $data['transport_type'];
                        $db_data->airport                = $data['airport'];
                        $db_data->location               = $data['location'];
                        $db_data->transport_date         = $data['transport_date'];
                        $db_data->time                   = $data['time'];
                        $db_data->car_type               = $data['car_type'];
                        $db_data->sale_cost              = $data['sale_cost'];
                        $db_data->net_cost               = $data['net_cost'];
                        $db_data->updated_by             = Auth::user()->id;
                        $db_data->update();
                    }
                } else {
                    // Create new passenger
                    $data['booking_id'] = $booking_id;
                    //$data['transport_type'] = $data['type'];
                    $data['is_refunded'] = 0;
                    $data['is_active'] = 1;
                    $data['created_by'] = Auth::user()->id;
                    bookingTransport::create($data);
                }
            }

            $booking_pricing = bookingPricing::where('booking_id', $booking_id)->where('booking_type', 'Transport Price')->first();
            $booking_pricing->sale_cost = $total_sales_cost;
            $booking_pricing->net_cost = $total_net_cost;

            $booking_pricing->total = $total_sales_cost;
            $booking_pricing->net_total = $total_net_cost;
            $booking_pricing->quantity = $quantity;
            $booking_pricing->save();

            $booking->total_sales_cost -= $total_sales_cost_old;
            $booking->total_net_cost -= $total_net_cost_old;
            $booking->margin = $booking->total_sales_cost - $booking->total_net_cost;

            $booking->total_sales_cost += $total_sales_cost;
            $booking->total_net_cost += $total_net_cost;
            $booking->margin = $booking->total_sales_cost - $booking->total_net_cost;
            $booking->balance_amount = $booking->total_sales_cost - $booking->deposite_amount;

            if ($booking->balance_amount > 0)  {
                $booking->payment_status = 3;
            }

            $booking->save();


            $bookingPayments = bookingPayment::where('booking_id', $booking_id)->whereNull('other_charges_id')->get();
            $remainingAmount = $booking->total_sales_cost; // Start with the total sales cost
            foreach ($bookingPayments as $payment) {
                $payment->total_amount = $booking->total_sales_cost;
                //$remainingAmount = $totalSalesCost - $payment->reciving_amount;
                // Subtract the receiving amount from the remaining amount for each iteration
                $remainingAmount -= $payment->reciving_amount;
                // Assign the remaining amount to the payment
                $payment->remaining_amount = $remainingAmount;
                $payment->save();
            }

            /** UPDATE ON 02-08-2025 */
            $newData = bookingTransport::where('booking_id', $booking_id)->get();
            $booking_number = $booking->booking_prefix . $booking->booking_number;
            sendBookingUpdateMail($oldData, $newData, $booking_number, 'Transport');
            /** END */

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Transport updated successfully'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    public function deleteTransportDetails(int $transport_id)
    {
        DB::beginTransaction();
        try {

            $transport = bookingTransport::findOrFail($transport_id);
            $booking_id = $transport->booking_id;

            $booking = Booking::findOrFail($booking_id);

            if ($transport->is_refunded == 1) {
                throw new Exception("This transport is refunded", 1);
            }

            /** UPDATE ON 02-08-2025 */
            $oldData = bookingTransport::where('id', $transport_id)->get()->map(function ($transport) {
                return clone $transport;
            });
            /** END */

            $sale_cost = $transport->sale_cost;
            $net_cost = $transport->net_cost;


            $booking->total_sales_cost -= $sale_cost;
            $booking->total_net_cost -= $net_cost;
            $booking->margin = $booking->total_sales_cost - $booking->total_net_cost;
            $booking->save();


            $booking_pricing = bookingPricing::where('booking_id', $booking_id)->where('booking_type', 'Transport Price')->first();
            $booking_pricing->sale_cost -= $sale_cost;
            $booking_pricing->net_cost -= $net_cost;

            $booking_pricing->total -= $sale_cost;
            $booking_pricing->net_total -= $net_cost;
            $booking_pricing->quantity -= 1;
            $booking_pricing->save();

            $transport->delete();

            /** UPDATE ON 02-08-2025 */
            $newData = bookingTransport::where('id', $transport_id)->get();
            $booking_number = $booking->booking_prefix . $booking->booking_number;
            sendBookingUpdateMail($oldData, $newData, $booking_number, 'Transport Deleted');
            /** END */

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Transport removed successfully'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    function addTransportActualNet(int $booking_id)
    {

        $vendors = Vendor::all();

        if (view()->exists('modules.CRM.booking.modals.add-transport-actual-net')) {
            $booking = Booking::with('transports')->findOrFail($booking_id);
            return view('modules.CRM.booking.modals.add-transport-actual-net', compact('booking', 'vendors'));
        }
        return abort(404);
    }

    function updateTransportActualNet(Request $request)
    {

        DB::beginTransaction();
        try {

            $booking_id = $request->booking_id;

            $booking = Booking::findOrFail($booking_id);

            /** UPDATE ON 02-08-2025 */
            $oldData = bookingTransport::where('booking_id', $booking->id)->get()->map(function ($transport) {
                return clone $transport;
            });
            /** END */

            $request_data = $request->input('transport');

            $actual_net_cost = 0;

            foreach ($request_data as $key => $data) {

                $actual_net_cost += $data['actual_net_cost'];

                if (isset($data['id'])) {

                    $db_data = bookingTransport::find($data['id']);

                    if ($db_data) {

                        $db_data->supplier                      = $data['supplier'];
                        $db_data->actual_net_cost               = $data['actual_net_cost'];
                        $db_data->actual_net_on                 = Carbon::now()->format('Y-m-d');
                        $db_data->actual_net_by                 = Auth::user()->id;
                        $db_data->updated_by                    = Auth::user()->id;
                        $db_data->update();

                    }
                }
            }

            bookingPricing::where('booking_id', $booking_id)->where('pricing_type', 'bookingTransport')->update(['actual_net_total' => $actual_net_cost]);

            $totalActualNet = bookingPricing::where('booking_id', $booking_id)->sum('actual_net_total');

            // Calculate actual_margin (assuming it's based on some formula)
            //$actualMargin = $this->total_cost - $totalActualNet; // Adjust formula as needed

            $actual_margin = $booking->total_sales_cost - $totalActualNet;
            // $booking->actual_net_cost += $actual_net_cost;
            $booking->actual_net_cost = $totalActualNet;
            $booking->actual_margin = $actual_margin;

            $booking->save();

            /** UPDATE ON 02-08-2025 */
            $newData = bookingTransport::where('booking_id', $booking->id)->get();
            $booking_number = $booking->booking_prefix . $booking->booking_number;
            sendBookingUpdateMail($oldData, $newData, $booking_number, 'Transport Actual Net');
            /** END */

            // Log the event
            bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Transport Actual Net Added / Updated',
                'description' => 'Booking Transport Actual net updated by user ' . Auth::user()->name.'. new total actual net is:'. $actual_net_cost.'  & actual margin is:'. $actual_margin,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Transport actual net updated successfully'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    function editVisaDetails(int $booking_id)
    {

        $vendors = Vendor::all();
        $visaCategories = visaCategory::all();
        $countries = country::all();

        if (view()->exists('modules.CRM.booking.modals.edit-visa')) {
            $booking = Booking::with('visas')->findOrFail($booking_id);
            return view('modules.CRM.booking.modals.edit-visa', compact('booking', 'vendors', 'visaCategories', 'countries'));
        }
        return abort(404);
    }

    function updateVisaDetails(Request $request)
    {
        //return response()->json($request->all());
        DB::beginTransaction();
        try {

            $booking_id = $request->booking_id;

            $booking = Booking::findOrFail($booking_id);

            /** UPDATE ON 02-08-2025 */
            $oldData = bookingVisa::where('booking_id', $booking->id)->get()->map(function ($visa) {
                return clone $visa;
            });
            /** END */

            $request_data = $request->input('visa');
            $quantity = count($request_data);

            $total_sales_cost_old = 0;
            $total_net_cost_old = 0;

            $total_sales_cost = 0;
            $total_net_cost = 0;

            foreach ($request_data as $key => $data) {

                $total_sales_cost += $data['sale_cost'];
                $total_net_cost += $data['net_cost'];

                if (isset($data['id'])) {
                    //return response()->json([$passengers,$data['id']]);

                    // Update existing passenger
                    $db_data = bookingVisa::find($data['id']);

                    if ($db_data) {

                        $total_sales_cost_old += $db_data->sale_cost;
                        $total_net_cost_old += $db_data->net_cost;


                        $db_data->supplier              = $data['supplier'];
                        $db_data->visa_category         = $data['visa_category'];
                        $db_data->visa_country          = $data['visa_country'];
                        $db_data->no_of_pax             = $data['no_of_pax'];
                        $db_data->nationality           = $data['nationality'];
                        $db_data->sale_cost             = $data['sale_cost'];
                        $db_data->net_cost              = $data['net_cost'];
                        $db_data->remarks               = $data['remarks'];
                        $db_data->updated_by            = Auth::user()->id;
                        $db_data->update();
                    }
                } else {
                    // Create new Visa
                    $data['booking_id'] = $booking_id;
                    $data['is_active'] = 1;
                    $data['created_by'] = Auth::user()->id;
                    bookingVisa::create($data);
                }
            }

            $booking_pricing = bookingPricing::where('booking_id', $booking_id)->where('booking_type', 'Visa')->first();
            $booking_pricing->sale_cost = $total_sales_cost;
            $booking_pricing->net_cost = $total_net_cost;

            $booking_pricing->total = $total_sales_cost;
            $booking_pricing->net_total = $total_net_cost;
            $booking_pricing->quantity = $quantity;
            $booking_pricing->save();

            $booking->total_sales_cost -= $total_sales_cost_old;
            $booking->total_net_cost -= $total_net_cost_old;
            $booking->margin = $booking->total_sales_cost - $booking->total_net_cost;

            $booking->total_sales_cost += $total_sales_cost;
            $booking->total_net_cost += $total_net_cost;
            $booking->margin = $booking->total_sales_cost - $booking->total_net_cost;
            $booking->balance_amount = $booking->total_sales_cost - $booking->deposite_amount;

            if ($booking->balance_amount > 0)  {
                $booking->payment_status = 3;
            }

            $booking->save();

            $bookingPayments = bookingPayment::where('booking_id', $booking_id)->whereNull('other_charges_id')->get();
            $remainingAmount = $booking->total_sales_cost; // Start with the total sales cost
            foreach ($bookingPayments as $payment) {
                $payment->total_amount = $booking->total_sales_cost;
                //$remainingAmount = $totalSalesCost - $payment->reciving_amount;
                // Subtract the receiving amount from the remaining amount for each iteration
                $remainingAmount -= $payment->reciving_amount;
                // Assign the remaining amount to the payment
                $payment->remaining_amount = $remainingAmount;
                $payment->save();
            }

            /** UPDATE ON 02-08-2025 */
            $newData = bookingVisa::where('booking_id', $booking->id)->get();
            $booking_number = $booking->booking_prefix . $booking->booking_number;
            sendBookingUpdateMail($oldData, $newData, $booking_number, 'Visa');
            /** END */

            // Log the event
            bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Visa Details Updated',
                'description' => 'Booking Visa details updated by user ' . Auth::user()->name.'. new total visa cost is:'. $total_sales_cost.'  & net cost is:'. $total_net_cost,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Visa updated successfully'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    public function deleteVisaDetails(int $visa_id)
    {
        DB::beginTransaction();
        try {

            $visa = bookingVisa::findOrFail($visa_id);
            $booking_id = $visa->booking_id;

            $booking = Booking::findOrFail($booking_id);

            /* if ($visa->is_refunded == 1) {
                throw new Exception("This visa is refunded", 1);
            } */

            /** UPDATE ON 02-08-2025 */
            $oldData = bookingVisa::where('id', $visa_id)->get()->map(function ($visa) {
                return clone $visa;
            });
            /** END */

            $sale_cost = $visa->sale_cost;
            $net_cost = $visa->net_cost;


            $booking->total_sales_cost -= $sale_cost;
            $booking->total_net_cost -= $net_cost;
            $booking->margin = $booking->total_sales_cost - $booking->total_net_cost;
            $booking->save();


            $booking_pricing = bookingPricing::where('booking_id', $booking_id)->where('booking_type', 'Visa')->first();
            $booking_pricing->sale_cost -= $sale_cost;
            $booking_pricing->net_cost -= $net_cost;

            $booking_pricing->total -= $sale_cost;
            $booking_pricing->net_total -= $net_cost;
            $booking_pricing->quantity -= 1;
            $booking_pricing->save();

            $visa->delete();

            /** UPDATE ON 02-08-2025 */
            $newData = bookingVisa::where('id', $visa_id)->get();
            $booking_number = $booking->booking_prefix . $booking->booking_number;
            sendBookingUpdateMail($oldData, $newData, $booking_number, 'Visa Deleted');
            /** END */

            // Log the event
            bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Visa Details Deleted',
                'description' => 'Booking Visa details deleted by user ' . Auth::user()->name.'. having visa sales cost is:'. $sale_cost.'  & net cost is:'. $net_cost,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Visa removed successfully'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    function addVisaActualNet(int $booking_id)
    {

        $vendors = Vendor::all();

        if (view()->exists('modules.CRM.booking.modals.add-visa-actual-net')) {
            $booking = Booking::with('visas')->findOrFail($booking_id);
            return view('modules.CRM.booking.modals.add-visa-actual-net', compact('booking', 'vendors'));
        }
        return abort(404);
    }

    function updateVisaActualNet(Request $request)
    {

        DB::beginTransaction();
        try {

            $booking_id = $request->booking_id;

            $booking = Booking::findOrFail($booking_id);

            /** UPDATE ON 02-08-2025 */
            $oldData = bookingVisa::where('booking_id', $booking->id)->get()->map(function ($visa) {
                return clone $visa;
            });
            /** END */

            $request_data = $request->input('visa');

            $actual_net_cost = 0;

            foreach ($request_data as $key => $data) {

                $actual_net_cost += $data['actual_net_cost'];

                if (isset($data['id'])) {

                    $db_data = bookingVisa::find($data['id']);

                    if ($db_data) {

                        $db_data->supplier                      = $data['supplier'];
                        $db_data->actual_net_cost               = $data['actual_net_cost'];
                        $db_data->actual_net_on                 = Carbon::now()->format('Y-m-d');
                        $db_data->actual_net_by                 = Auth::user()->id;
                        $db_data->updated_by                    = Auth::user()->id;
                        $db_data->update();

                    }
                }
            }

            bookingPricing::where('booking_id', $booking_id)->where('pricing_type', 'bookingVisa')->update(['actual_net_total' => $actual_net_cost]);

            $totalActualNet = bookingPricing::where('booking_id', $booking_id)->sum('actual_net_total');

            // Calculate actual_margin (assuming it's based on some formula)
            //$actualMargin = $this->total_cost - $totalActualNet; // Adjust formula as needed

            $actual_margin = $booking->total_sales_cost - $totalActualNet;
            // $booking->actual_net_cost += $actual_net_cost;
            $booking->actual_net_cost = $totalActualNet;
            $booking->actual_margin = $actual_margin;

            $booking->save();

            /** UPDATE ON 02-08-2025 */
            $newData = bookingVisa::where('booking_id', $booking->id)->get();
            $booking_number = $booking->booking_prefix . $booking->booking_number;
            sendBookingUpdateMail($oldData, $newData, $booking_number, 'Visa Actual Net');
            /** END */

            // Log the event
            bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Visa Actual Net Added / Updated',
                'description' => 'Booking Visa Actual net updated by user ' . Auth::user()->name.'. new total actual net is:'. $actual_net_cost.'  & actual margin is:'. $actual_margin,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Visa actual net updated successfully'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }


    function editFlightDetails(int $booking_id)
    {

        if (view()->exists('modules.CRM.booking.modals.edit-flight')) {
            $booking = Booking::with('flights')->findOrFail($booking_id);
            $airports = airport::all();
            $airlines = airline::all();
            return view('modules.CRM.booking.modals.edit-flight', compact('booking', 'airports', 'airlines'));
        }
        return abort(404);
    }

    function updateFlightDetails(Request $request)
    {
        //return response()->json($request->all());

        DB::beginTransaction();
        try {

            $booking_id = $request->booking_id;
            $booking = Booking::findOrFail($booking_id);

            /** UPDATE ON 02-08-2025 */
            $oldData = bookingFlight::where('booking_id', $booking->id)->get()->map(function ($flight) {
                return clone $flight;
            });
            /** END */

            $request_data = $request->input('flights');

            foreach ($request_data as $key => $data) {


                if (isset($data['id'])) {
                    //return response()->json([$passengers,$data['id']]);

                    // Update existing passenger
                    $db_data = bookingFlight::find($data['id']);

                    if ($db_data){

                        $db_data->gds_no                    = $data['gds_no'];
                        $db_data->airline_locator           = $data['airline_locator'];
                        $db_data->ticket_no                 = $data['ticket_no'];
                        $db_data->flight_number             = $data['flight_number'];
                        $db_data->departure_airport         = $data['departure_airport'];
                        $db_data->departure_date            = $data['departure_date'];
                        $db_data->departure_time            = $data['departure_time'];
                        $db_data->arrival_airport           = $data['arrival_airport'];
                        $db_data->arrival_date              = $data['arrival_date'];
                        $db_data->arrival_time              = $data['arrival_time'];
                        $db_data->air_line_name             = $data['air_line_name'];
                        $db_data->booking_class             = $data['booking_class'];
                        $db_data->number_of_baggage         = $data['number_of_baggage'];
                        $db_data->updated_by                = Auth::user()->id;

                        $db_data->update();
                    }
                } else {
                    // Create new passenger
                    $data['booking_id'] = $booking_id;
                    $data['is_refunded'] = 0;
                    $data['is_active'] = 1;
                    $data['created_by'] = Auth::user()->id;
                    bookingFlight::create($data);
                }
            }

            $booking->trip_type = $request->trip_type;
            $booking->flight_type = $request->flight_type;
            $booking->is_date_change = false;
            $booking->update();

            /** UPDATE ON 02-08-2025 */
            $newData = bookingFlight::where('booking_id', $booking->id)->get();
            $booking_number = $booking->booking_prefix . $booking->booking_number;
            sendBookingUpdateMail($oldData, $newData, $booking_number, 'Flight');
            /** END */

             // Log the event
             bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Booking Flight Details Updated',
                'description' => 'Booking Flight Details Updated by user ' . Auth::user()->name,
                'created_by' => auth()->id(),
            ]);


            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Flight details updated successfully'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    public function deleteFlightDetails(int $flight_id)
    {
        //return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Passenger removed successfully'], 200);
        DB::beginTransaction();
        try {
            $flightId = $flight_id;
            $flight = bookingFlight::findOrFail($flightId);

            $booking_id = $flight->booking_id;

            $booking = Booking::findOrFail($booking_id);

            /** UPDATE ON 02-08-2025 */
            $oldData = bookingFlight::where('booking_id', $booking_id)->get()->map(function ($flight) {
                return clone $flight;
            });
            /** END */

            // Count the total number of flights associated with the booking_id
            $totalFlights = BookingFlight::where('booking_id', $booking_id)->count();

            if ($totalFlights <= 1) {
                throw new Exception("You can not delete the flight details...", 1);
            }

            $flight->delete();

            /** UPDATE ON 02-08-2025 */
            $newData = bookingFlight::where('booking_id', $booking_id)->get();
            $booking_number = $booking->booking_prefix . $booking->booking_number;
            sendBookingUpdateMail($oldData, $newData, $booking_number, 'Flight Deleted');
            /** END */

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Flight removed successfully'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    function addFlightActualNet(int $booking_id)
    {

        $vendors = Vendor::all();

        if (view()->exists('modules.CRM.booking.modals.add-flight-actual-net')) {
            //$booking = Booking::with('flights')->findOrFail($booking_id);
            $booking = Booking::with('pnrs')->findOrFail($booking_id);
            return view('modules.CRM.booking.modals.add-flight-actual-net', compact('booking', 'vendors'));
        }
        return abort(404);
    }

    function updateFlightActualNet(Request $request)
    {

        DB::beginTransaction();
        try {

            $booking_id = $request->booking_id;

            $booking = Booking::findOrFail($booking_id);

            /** UPDATE ON 02-08-2025 */
            $oldData = bookingPnr::where('booking_id', $booking->id)->get()->map(function ($pnr) {
                return clone $pnr;
            });
            /** END */

            $request_data = $request->input('flight');

            $actual_net_cost = 0;
            $aviation_fee_total = 0;

            foreach ($request_data as $key => $data) {

                // $actual_net_cost += $data['actual_net_cost'];

                if (isset($data['id'])) {

                    //$db_data = bookingTransport::find($data['id']);
                    $db_data = bookingPnr::find($data['id']);
                    if ($db_data) {

                        $actual_net_cost += $data['actual_net_cost'];
                        $aviation_fee_total += $data['aviation_fee'];

                        $db_data->aviation_fee_supplier         = $data['aviation_fee_supplier'];
                        $db_data->aviation_fee                  = $data['aviation_fee'];
                        $db_data->supplier                      = $data['supplier'];
                        $db_data->actual_net_cost               = $data['actual_net_cost'];
                        $db_data->actual_net_on                 = Carbon::now()->format('Y-m-d');
                        $db_data->actual_net_by                 = Auth::user()->id;
                        $db_data->updated_by                    = Auth::user()->id;
                        $db_data->update();

                    }
                }
            }

            // bookingPricing::where('booking_id', $booking_id)->where('pricing_type', 'bookingFlight')->first()->update(['actual_net_total' => $actual_net_cost]);

            bookingPricing::where('booking_id', $booking_id)
                ->where('pricing_type', 'bookingFlight')
                ->first()
                ->update([
                    'actual_net_total' => $actual_net_cost,
                    'aviation_fee_total' => $aviation_fee_total
                ]);

            $totalActualNet = bookingPricing::where('booking_id', $booking_id)->sum('actual_net_total');

            // Calculate actual_margin (assuming it's based on some formula)
            //$actualMargin = $this->total_cost - $totalActualNet; // Adjust formula as needed

            $actual_margin = $booking->total_sales_cost - $totalActualNet;
            // $booking->actual_net_cost += $actual_net_cost;
            $booking->actual_net_cost = $totalActualNet;
            $booking->actual_margin = $actual_margin;

            $booking->save();

            /** UPDATE ON 02-08-2025 */
            $newData = bookingPnr::where('booking_id', $booking_id)->get();
            $booking_number = $booking->booking_prefix . $booking->booking_number;
            sendBookingUpdateMail($oldData, $newData, $booking_number, 'Flight PNR Actual Net');
            /** END */

            // Log the event
            bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Flight Actual Net Added / Updated',
                'description' => 'Booking Flight Actual net updated by user ' . Auth::user()->name.'. new total actual net is:'. $actual_net_cost.'  & actual margin is:'. $actual_margin,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Flight actual net updated successfully'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    function editPricingDetails(int $booking_id)
    {
        //echo "OKOK";
        if (view()->exists('modules.CRM.booking.modals.edit-pricing')) {
            $booking = Booking::with('prices')->findOrFail($booking_id);

            return view('modules.CRM.booking.modals.edit-pricing', compact('booking'));
        }
        return abort(404);
    }

    function updatePricingDetails(Request $request)
    {
        //return response()->json($request->all());
        DB::beginTransaction();
        try {

            $data = $request->all();
            $booking_id = $data['booking_id'];
            $booking_pricing = $data['booking_pricing'];
            $total_sales_cost = $data['total_sales_cost'];
            $totalNetCost = 0;
            $totalSalesCost = 0;
            // Update the Booking record
            $booking = Booking::findOrFail($booking_id);
            if ($total_sales_cost < $booking->deposite_amount) {
                throw new Exception("Error! You can not reduce the invoice amount from deposite amount.", 1);
            }

            foreach ($booking_pricing as $key => $pricing) {

                if (isset($pricing['id'])) {
                    $bookingPricing = bookingPricing::find($pricing['id']);

                    if ($bookingPricing) {
                        $bookingPricing->sale_cost          = $pricing['flight_price'];
                        $bookingPricing->net_cost           = $pricing['flight_net_price'];
                        $bookingPricing->quantity           = $pricing['quantity'];
                        $bookingPricing->total              = $pricing['total'];
                        $bookingPricing->net_total          = $pricing['net_total'];

                        $bookingPricing->save();

                        // Calculate total net cost and total sales cost
                        $totalNetCost += $bookingPricing->net_total;
                        $totalSalesCost += $bookingPricing->total;
                    }
                } else {
                    // Create new passenger
                    $pricingg['booking_id'] = $booking_id;
                    $pricingg['booking_type'] = $pricing['type'];
                    $pricingg['pricing_type'] = $pricing['pricing_type'];
                    $pricingg['sale_cost'] = $pricing['flight_price'];
                    $pricingg['net_cost'] = $pricing['flight_net_price'];
                    $pricingg['quantity'] = $pricing['quantity'];
                    $pricingg['total'] = $pricing['total'];
                    $pricingg['net_total'] = $pricing['net_total'];
                    $pricingg['is_active'] = 1;
                    $pricingg['created_by'] = Auth::user()->id;
                    $pricingg['status'] = 1;

                    bookingPricing::create($pricingg);
                    // Calculate total net cost and total sales cost
                    $totalNetCost += $pricing['net_total'];
                    $totalSalesCost += $pricing['total'];
                }
            }

            // Calculate margin
            $margin = $totalSalesCost - $totalNetCost;


            if ($booking) {
                $booking->payment_status = ($total_sales_cost > $booking->total_sales_cost) ? 1 : $booking->payment_status;
                $booking->payment_type = $booking->balance_amount > 0 ? 2 : 1;
                $booking->total_net_cost = $totalNetCost;
                $booking->total_sales_cost = $totalSalesCost;
                $booking->margin = $margin;
                $booking->balance_amount = $totalSalesCost - $booking->deposite_amount;
                $booking->save();
            }

            // Update BookingPayments records
            $bookingPayments = bookingPayment::where('booking_id', $booking_id)->whereNull('other_charges_id')->get();
            $remainingAmount = $totalSalesCost; // Start with the total sales cost
            foreach ($bookingPayments as $payment) {
                $payment->total_amount = $totalSalesCost;
                //$remainingAmount = $totalSalesCost - $payment->reciving_amount;
                // Subtract the receiving amount from the remaining amount for each iteration
                $remainingAmount -= $payment->reciving_amount;
                // Assign the remaining amount to the payment
                $payment->remaining_amount = $remainingAmount;
                $payment->save();
            }

            // Log the event
            bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Booking prcing update',
                'description' => 'Booking pricing updated by user ' . Auth::user()->name.'. new total sales cost is:'. $totalSalesCost.' & total net cost is:'. $totalNetCost.' & total margin is:'. $margin,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Flight details updated successfully'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    function sendInvoiceEmailView($booking_id) {
        if (view()->exists('modules.CRM.booking.modals.send-invoice-email-template')) {
            $booking = Booking::with('passengers', 'hotels', 'transports', 'flights', 'prices', 'otherCharges', 'installmentPlan', 'payments', 'company', 'refunds', 'user', 'visas')->findOrFail($booking_id);
            return view('modules.CRM.booking.modals.send-invoice-email-template', compact('booking'));
        }
        return abort(404);
    }


    // Export filtered bookings to Excel
    public function exportToExcel(Request $request)
    {
        $user = Auth::user();
        if ($user->role > 3) {
            return response()->json('Inavlid Request', 400);

        }

        // Retrieve the 'is_pax' value and store it in a variable
        $isPax = $request->input('is_pax');

        // Remove the 'is_pax' field from the request
        $request->offsetUnset('is_pax');

        // Call the bookingSearch method to get the filtered bookings
        $filteredBookings = $this->bookingSearch($request, 'compact');


        // Generate a temporary file name
        $fileName = 'bookings_report_'.Auth::user()->id.'_'. \Carbon\Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';

        // Store the file in the storage/app/public directory
        Excel::store(new BookingsExport($filteredBookings, $isPax), 'public/ExcelBookings/' . $fileName);

        // Generate the public URL for the stored file
        $downloadUrl = Storage::url('public/ExcelBookings/'.$fileName);

        // Return the download URL as a JSON response
        return response()->json(['url' => $downloadUrl]);
    }



    public static function closedTravelingBookings(Request $request, $return_type = 'view', $days = null)
    {
        $user = Auth::user();

        $ticket_status = Type::where('id', 1)->with('details')->first();

        $assignedCompanies = $user->employee?->companies;
        $user_role = $user->role;

        // Determine agents based on user role
        if ($user_role == 1 || $user_role == 2) {
            $agents = User::all();
        } elseif ($user_role == 3 || $user_role == 4) {
            $employeeCompanyIds = $assignedCompanies?->pluck('id')->toArray() ?? [];

            if ($user_role == 4) {
                $teamLeadEmployeeIds = Employee::where('team_lead_id', $user->employee->id)->pluck('user_id')->toArray();
                $agents = User::where(function ($query) use ($teamLeadEmployeeIds, $user) {
                    $query->whereIn('id', $teamLeadEmployeeIds)
                        ->orWhere('id', $user->id);
                })->get();
            } else {
                $agents = User::whereHas('employee.companies', function ($query) use ($employeeCompanyIds) {
                    $query->whereIn('companies.id', $employeeCompanyIds);
                })->where('role', '>', 2)->get();
            }
        } else {
            $agents = collect([$user]);
        }

        $agentIds = $agents->pluck('id')->toArray();

        // Filters
        $booking_number = $request->input('booking_number');
        $today = $request->input('from_date') ?? Carbon::today();
        $nextDate = $request->input('to_date') ?? Carbon::today()->addMonth();
        $company_id = $request->input('company_id');
        $filter_created_by = $request->input('created_by'); // <-- new filter
        $filter_ticket_status = $request->input('ticket_status');

        // Base query
        $query = Booking::with([
            'flights' => function ($query) {
                $query->orderBy('departure_date', 'asc');
            },
            'passengers',
            'internalComments' => function ($query) {
                $query->where('title', 'close traveling')->latest();;
            }
        ]);

        // Apply filters based on booking_number
        if ($booking_number) {
            $query->where('booking_number', $booking_number);
        } else {
            $query->whereHas('flights', function ($q) use ($today, $nextDate) {
                $q->whereBetween('departure_date', [$today, $nextDate]);
            });
        }

        // Apply user_id filter if provided and valid
        if ($filter_created_by && in_array($filter_created_by, $agentIds)) {
            $query->where('created_by', $filter_created_by);
        }

        if ($filter_ticket_status) {
            $query->where('ticket_status', $filter_ticket_status);
        }

        // Role-based data access
        if ($user_role == 5) {
            $query->where('created_by', $user->id);
        } elseif ($user_role == 4 && $assignedCompanies) {
            $companyIds = $assignedCompanies->pluck('id');
            if ($companyIds->isNotEmpty()) {
                $query->whereHas('company', function ($q) use ($companyIds) {
                    $q->whereIn('id', $companyIds);
                });
            } else {
                return response()->json(['message' => 'No assigned companies found.'], 404);
            }
        }

        // Apply company filter
        if ($company_id) {
            $query->where('company_id', $company_id);
        }

        // Filter payment status if compact return
        $query->when($return_type === 'compact', function ($q) {
            $q->where('payment_status', '!=', 2);
        });

        $bookings = $query->get();

        // If not searching by booking number, apply in-memory departure filter
        if (!$booking_number) {
            $bookings = $bookings->filter(function ($booking) use ($today, $nextDate) {
                $firstDepartureDate = $booking->flights->first()->departure_date ?? null;
                return $firstDepartureDate && Carbon::parse($firstDepartureDate)->between($today, $nextDate);
            });
        }

        $sortedBookings = $bookings->sortBy(function ($booking) {
            return Carbon::parse($booking->flights->first()->departure_date ?? null);
        });

        // Return response based on return_type
        if ($return_type === 'json') {
            return response()->json(compact('sortedBookings', 'today'));
        } elseif ($return_type === 'compact') {
            // return compact('sortedBookings', 'today');
            return $sortedBookings;
        } else {
            return view('modules.CRM.booking.reports.close-traveling-bookings', compact('sortedBookings', 'today', 'assignedCompanies', 'agents', 'ticket_status'));
        }
    }

    public function closedTravelingBookingsExportToExcel(Request $request)
    {
        $user = Auth::user();
        if ($user->role > 3) {
            return response()->json('Inavlid Request', 400);

        }
        // Call the bookingSearch method to get the filtered bookings
        // Pass 'compact' as the return_type to get the data
        $filteredBookings = $this->closedTravelingBookings($request, 'compact');

        // Generate a temporary file name
        $fileName = 'close_traveling_bookings_'.Auth::user()->id.'_'. \Carbon\Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';

        // Store the file in the storage/app/public directory
        Excel::store(new BookingsReportExport($filteredBookings, 'close-traveling-bookings'), 'public/ExcelBookingsReport/' . $fileName);

        // Generate the public URL for the stored file
        $downloadUrl = Storage::url('public/ExcelBookingsReport/'.$fileName);

        // Return the download URL as a JSON response
        return response()->json(['url' => $downloadUrl]);
    }

    public static function ticketDeadlineBookings(request $request, $return_type = 'view', $days = null) {

        $today = now()->startOfDay();
        // Determine the date range based on the number of days passed, defaulting to 1 month
        $nextDate = $days ? Carbon::today()->addDays($days) : Carbon::today()->addMonth();


        $user = Auth::user();
        $userRole = $user->role;
        $employee = Employee::find($user->employee_id);
        // Base query
        $query = Booking::with(['passengers', 'flights'])
        ->where('ticket_status', 1)
        ->whereBetween('ticket_deadline', [$today, $nextDate])
        ->orderBy('ticket_deadline', 'ASC')
        ->limit(30);

        // Filter by user role
        if ($userRole === 5) {
            $query->where('bookings.created_by', $user->id);
        } elseif ($userRole === 4) {
            // Assuming there's a method to get assigned company IDs for the user
            //$assignedCompanyIds = $user->assignedCompanies()->pluck('id');
            $assignedCompanyIds = $employee->companies()->pluck('companies.id');
            $query->whereIn('bookings.company_id', $assignedCompanyIds);
        }

        // Get results
        $ticketDeadlineBookings = $query->get();

        if ($return_type == 'json') {
            return response()->json(compact('ticketDeadlineBookings', 'today', 'nextDate'));
        } elseif($return_type == 'compact'){
            return compact('ticketDeadlineBookings', 'today', 'nextDate');
        } elseif ($return_type == 'view') {
            return view('modules.CRM.booking.reports.ticket-deadline-bookings', compact('ticketDeadlineBookings', 'today', 'nextDate'));
        }
    }

    public static function pendingInstallmentBookings(Request $request, $return_type ='view', $days = null)
    {
        $today = Carbon::today();
        //$threeDaysFromNow = Carbon::today()->addDays(3);
        $nextDate = $days ? Carbon::today()->addDays($days) : Carbon::today()->addMonth();

        $user = Auth::user();
        $userRole = $user->role;

        // Access the employee's assigned companies
        $employee = $user->employee; // Assuming a 'hasOne' or 'belongsTo' relation between User and Employee

        // Base query to get bookings with pending installments
        $query = Booking::with(['installmentPlan' => function ($q) use ($today, $nextDate) {
            $q->where('is_received', 0) // Assuming 'status' column indicates payment status
            ->whereBetween('due_date', [$today, $nextDate]);
        }])
        ->whereHas('installmentPlan', function ($q) use ($today, $nextDate) {
            $q->where('is_received', 0)
            ->whereBetween('due_date', [$today, $nextDate]);
        });

        // Apply role-based restrictions
        if ($userRole === 5) {
            $query->where('created_by', $user->id);
        } elseif ($userRole === 4) {
            /* $assignedCompanyIds = $user->assignedCompanies->pluck('id');
            $query->whereIn('company_id', $assignedCompanyIds); */

            if ($employee && $employee->companies) {
                // Get the list of assigned company IDs
                $companyIds = $employee->companies->pluck('id');

                if ($companyIds->isNotEmpty()) {
                    // Apply the restriction based on company IDs
                    $query->whereHas('company', function ($q) use ($companyIds) {
                        $q->whereIn('id', $companyIds);
                    });
                } else {
                    // Handle case where no companies are assigned
                    return response()->json(['message' => 'No assigned companies found for this employee.'], 404);
                }
            } else {
                // Handle case where employee or companies is null
                return response()->json(['message' => 'Employee or assigned companies not found.'], 404);
            }
        }

        $pendingInstallmentBookings = $query->get();

        // Handle return type
        if ($return_type == 'json') {
            return response()->json(compact('pendingInstallmentBookings', 'today', 'nextDate'));
        } elseif ($return_type == 'compact') {
            return compact('pendingInstallmentBookings', 'today', 'nextDate');
        } elseif ($return_type == 'view') {
            return view('auth-404-alt'); //view('modules.CRM.booking.reports.ticket-deadline-bookings', compact('ticketDeadlineBookings', 'today', 'nextDate'));
        }
    }

    public function sendInvoiceEmail($booking_id)
    {
        try {
            $user = Auth::user();
            /* if ($user->id != 1) {
                throw new Exception("This functionality still in development mode, please try again..", 1);
            } */
            //$booking = Booking::with(['company', 'passengers'])->findOrFail($booking_id);
            $booking = Booking::with(['company', 'passengers', 'flights', 'hotels', 'transports', 'prices', 'otherCharges', 'installmentPlan', 'payments', 'refunds'])->findOrFail($booking_id);

            /**
             * UPDATED ON 16-12-2024
             */
            $company_id = $booking->company_id;

            // if ($company_id != 1) {
            //     throw new Exception("This functionality still in development mode, please try again..", 1);
            // }

            // Get the first passenger
            $firstPassenger = $booking->passengers->first();

            // Ensure the email exists and is valid
            $passengerEmail = $firstPassenger->email ?? null;

            if (is_null($passengerEmail)) {
                throw new Exception('The first passenger email address is null.');
            }

            // Validate the email
            $validator = Validator::make(['email' => $passengerEmail], [
                'email' => 'required|email'
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

             /**
              * END
              */

            $companySmtpConfig = CompanySmtpConfig::where('company_id', $booking->company_id)->firstOrFail();
            if (!$companySmtpConfig) {
                throw new Exception("Email Settings Are Missing", 1);
            }

            $signature = "Best Regards UMAIR";

            // Update mail configuration dynamically
            config([
                'mail.mailers.smtp.host' => $companySmtpConfig->smtp_host,
                'mail.mailers.smtp.port' => $companySmtpConfig->smtp_port,
                'mail.mailers.smtp.username' => $companySmtpConfig->smtp_username,
                'mail.mailers.smtp.password' => $companySmtpConfig->smtp_password,
                'mail.mailers.smtp.encryption' => $companySmtpConfig->smtp_encryption,
            ]);

            // Generate PDF and get the path
            $pdfPath = $this->generateBookingInvoice($booking_id, 'email');
            //return $pdfPath;

            // Email content
            $body = "Dear Customer, here is your invoice for booking ID: {$booking->id}.";
            //$footer = "Best regards, {$user->name}\nSignature: {$user->signature}";
            $footer = "Best regards, {$user->name}\nSignature: {$signature}";

            // Set dynamic from address and name
            $fromAddress = $companySmtpConfig->smtp_username;
            $fromName = $booking->company->name;
            $ccFromEmail = $user->email;
            $ccFromName = $user->name;

            // Send email

            Mail::mailer('smtp')->to('usmanrathore507@gmail.com')->send(new InvoiceEmail($booking, $body, $footer, $pdfPath, $fromAddress,$fromName, $ccFromEmail, $ccFromName));

            //return response()->json(['message' => 'Invoice email sent successfully!']);
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Invoice email sent successfully to customer having email: '.$passengerEmail], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function viewBookingLog($booking_id){

        if (view()->exists('modules.CRM.booking.modals.view-log')) {
            //$logs = bookingLog::where('booking_id', $booking_id)->get();

            $booking = Booking::with('logs')->findOrFail($booking_id);

            return view('modules.CRM.booking.modals.view-log', compact('booking'));
        }
        return abort(404);
    }

    public function editPnr($booking_id){

        if (view()->exists('modules.CRM.booking.modals.edit-pnr')) {
            //$logs = bookingLog::where('booking_id', $booking_id)->get();

            $booking = Booking::findOrFail($booking_id);

            return view('modules.CRM.booking.modals.edit-pnr', compact('booking'));
        }
        return abort(404);
    }

    function updatePnr(Request $request)
    {
        //return response()->json($request->all());

        DB::beginTransaction();
        try {

            $booking_id = $request->booking_id;
            $flight_pnr = $request->flight_pnr;

            $booking = Booking::findOrFail($booking_id);


            $old_pnr                    = $booking->flight_pnr;
            $booking->flight_pnr        = $flight_pnr;
            $booking->update();

            // Log the event
            bookingLog::create([
                'booking_id' => $booking->id,
                'action' => 'Booking PNR Updated',
                'description' => 'Booking having booking # '.$booking->booking_prefix . $booking->booking_number.' Updated PNR. Old PNR Was:'.$old_pnr.' By User: '.Auth::user()->name,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'PNR updated successfully'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    public function editBookingComments($booking_id) {

        if (view()->exists('modules.CRM.booking.modals.edit-booking-comments')) {
            //$logs = bookingLog::where('booking_id', $booking_id)->get();

            $booking = Booking::findOrFail($booking_id);

            return view('modules.CRM.booking.modals.edit-booking-comments', compact('booking'));
        }
        return abort(404);
    }

    function updateBookingComments(Request $request)
    {
        //return response()->json($request->all());

        DB::beginTransaction();
        try {

            $booking_id = $request->booking_id;
            $comments = $request->comments;

            $booking = Booking::findOrFail($booking_id);
            $old_comments = $booking->comments;
            $booking->comments             = $comments;
            $booking->update();

            // Log the event
            bookingLog::create([
                'booking_id' => $booking->id,
                'action' => 'Booking Comments Updated',
                'description' => 'Booking having booking # '.$booking->booking_prefix . $booking->booking_number.' Updated Booking Comments. Old Comments was:'.$old_comments.' By User: '.Auth::user()->name,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Booking comments updated successfully.'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    public function addBookingInternalComments($booking_id) {

        if (view()->exists('modules.CRM.booking.modals.add-booking-internal-comments')) {
            //$logs = bookingLog::where('booking_id', $booking_id)->get();

            $booking = Booking::findOrFail($booking_id);

            return view('modules.CRM.booking.modals.add-booking-internal-comments', compact('booking'));
        }
        return abort(404);
    }

    function saveBookingInternalComments(Request $request)
    {
        //return response()->json($request->all());

        DB::beginTransaction();
        try {

            $booking_id = $request->booking_id;
            $comments = $request->comments;
            $title = $request->title;

            $booking = Booking::findOrFail($booking_id);

            // Log the event
            bookingInternalComment::create([
                'booking_id'    => $booking->id,
                'title'         => $title,
                'comments'      => $comments,
                'is_active'     => 1,
                'created_by'    => auth()->id(),
                'status'        => 1,
            ]);

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Booking internal comments added successfully.'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    public function bookingsReceivedPayments__old(Request $request, $return_type ='view', $pagination = true)
    {
        $user = Auth::user();

        if ($user->employee) {
            // Retrieve companies assigned to the employee
            $assignedCompanies = $user->employee->companies;
        }

        $user_role = $user->role;
        //echo '<pre>'; print_r($user); echo '</pre>'; //exit;
        if ($user_role == 1 || $user_role == 2) {

            //$agents = User::where('role','>','2')->get();
            $agents = User::all();
        } else if ($user_role == 3 || $user_role == 4) {

            // Retrieve company IDs of the logged-in user
            $employeeCompanyIds = $assignedCompanies->pluck('id')->toArray();

            // Check if user is role 4 (Team Lead)
            if ($user_role == 4) {
                // Retrieve the list of employees where the team_lead_id is the logged-in user
                $teamLeadEmployeeIds = Employee::where('team_lead_id', $user->employee->id)->pluck('id')->toArray();

                // Get users (agents) that are assigned to this team lead and role > 2
                $agents = User::where(function ($query) use ($teamLeadEmployeeIds, $user) {
                    // Include the team lead's own record (if needed)
                    $query->whereIn('id', $teamLeadEmployeeIds)
                        ->orWhere('id', $user->id);  // Include the team lead's own bookings
                }) //->where('role', '=', 5) // Only retrieve users with role == 5
                ->get();
            } else {
                // For role 3, get all agents related to their company
                $agents = User::whereHas('employee.companies', function ($query) use ($employeeCompanyIds) {
                    $query->whereIn('companies.id', $employeeCompanyIds);
                })->where('role', '>', 2)->get();
            }

        } else {
            $agents[] = $user;
        }
        // Extract agent IDs to use in whereIn
        $agentIds = $agents->pluck('id')->toArray();

        // Default date range: First day of the current month to today's date
        $fromDate = $request->input('payment_from_date') ?? Carbon::now()->startOfMonth()->toDateString();
        $toDate = $request->input('payment_to_date') ?? Carbon::now()->toDateString();
        // Default date range: First day of the current month to today's date
        $company_id = $request->input('company_id') ?? null;
        $booking_number = $request->input('booking_number') ?? null;

        $payments = bookingPayment::with(['booking'])
                    ->where(function($query) use($fromDate, $toDate, $booking_number){
                        if($booking_number == null){
                            // Filter by payment date range
                            // $query->whereBetween('payment_on', [$fromDate, $toDate]);
                            $query->whereDate('payment_on', '>=', $fromDate)->whereDate('payment_on', '<=', $toDate);
                        }
                    })
                    ->whereHas('booking', function($query) use ($company_id, $booking_number, $agentIds, $user){
                        if($company_id != null){
                            $query->where('company_id', $company_id);
                        }
                        if($booking_number != null){
                            $query->where('booking_number', $booking_number);
                        }
                        $query->whereIn('created_by', $agentIds)->orWhere('created_by', $user->id);
                    })->orderBy('payment_on', 'DESC')
                    // ->paginate(20)
                    // ->appends($request->only(['company_id', 'booking_number', 'payment_from_date', 'payment_to_date', 'agent_id', 'order_by']))
                    ->get();


        //->get();
        if ($return_type == 'json') {
            return response()->json(compact('bookings'));
        } elseif ($return_type == 'compact') {
            // Return the filtered data
            return $payments;
        } elseif($return_type == 'view'){
            return view('modules.CRM.booking.reports.bookings-received-payments', [
                'payments' => $payments,
                'assignedCompanies' => $assignedCompanies,
                'agents' => $agents,
                'filters' => $request->only(['company_id', 'booking_number', 'payment_from_date', 'payment_to_date', 'agent_id', 'order_by'])
            ]);
        }
    }

    public function bookingsReceivedPayments(Request $request, $return_type = 'view', $pagination = true, $report_type = 'received_payments')
    {

        $user = Auth::user();

        if ($user->employee) {
            // Retrieve companies assigned to the employee
            $assignedCompanies = $user->employee->companies;
        }

        $user_role = $user->role;
        //echo '<pre>'; print_r($user); echo '</pre>'; //exit;
        if ($user_role == 1 || $user_role == 2) {

            //$agents = User::where('role','>','2')->get();
            $agents = User::all();
        } else if ($user_role == 3 || $user_role == 4) {

            // Retrieve company IDs of the logged-in user
            $employeeCompanyIds = $assignedCompanies->pluck('id')->toArray();

            // Check if user is role 4 (Team Lead)
            if ($user_role == 4) {
                // Retrieve the list of employees where the team_lead_id is the logged-in user
                $teamLeadEmployeeIds = Employee::where('team_lead_id', $user->employee->id)->pluck('id')->toArray();

                // Get users (agents) that are assigned to this team lead and role > 2
                $agents = User::where(function ($query) use ($teamLeadEmployeeIds, $user) {
                    // Include the team lead's own record (if needed)
                    $query->whereIn('id', $teamLeadEmployeeIds)
                        ->orWhere('id', $user->id);  // Include the team lead's own bookings
                }) //->where('role', '=', 5) // Only retrieve users with role == 5
                ->get();
            } else {
                // For role 3, get all agents related to their company
                $agents = User::whereHas('employee.companies', function ($query) use ($employeeCompanyIds) {
                    $query->whereIn('companies.id', $employeeCompanyIds);
                })->where('role', '>', 2)->get();
            }

        } else {
            $agents[] = $user;
        }
        // Extract agent IDs to use in whereIn
        $agentIds = $agents->pluck('id')->toArray();

        // Default date range: First day of the current month to today's date
        $fromDate = $request->input('payment_from_date') ?? Carbon::now()->toDateString(); //Carbon::now()->startOfMonth()->toDateString();
        $toDate = $request->input('payment_to_date') ?? Carbon::now()->toDateString();
        // Default date range: First day of the current month to today's date
        $company_id = $request->input('company_id') ?? null;
        $booking_number = $request->input('booking_number') ?? null;

        $payments = bookingPayment::with(['booking'])
                    ->where(function($query) use($fromDate, $toDate, $booking_number){
                        if($booking_number == null){
                            // Filter by payment date range
                            // $query->whereBetween('payment_on', [$fromDate, $toDate]);
                            $query->whereDate('payment_on', '>=', $fromDate)->whereDate('payment_on', '<=', $toDate);
                        }
                    })
                    ->whereHas('booking', function($query) use ($company_id, $booking_number, $agentIds, $user){
                        if($company_id != null){
                            $query->where('company_id', $company_id);
                        }
                        if($booking_number != null){
                            $query->where('booking_number', $booking_number);
                        }
                        $query->whereIn('created_by', $agentIds)->orWhere('created_by', $user->id);
                    })->orderBy('payment_on', 'DESC')
                    // ->paginate(20)
                    // ->appends($request->only(['company_id', 'booking_number', 'payment_from_date', 'payment_to_date', 'agent_id', 'order_by']))
                    ->get();

        // Additional Data for Daily Sales Report
        $newSalesCount = 0;
        $totalSalesAmount = 0;
        $receivedPaymentsSum = 0;
        $totalBalance = 0;

        if ($report_type == 'daily_sales') {


            $salesResponse = $this->getBookingStatistics($request);

            // Convert JSON response to an array
            $salesData = $salesResponse->getData(true);
            // echo '<pre>'; print_r($salesData); echo '</pre>'; //exit;
            // Assign results
            $total_booking = $salesData['total_bookings'];
            $total_sales_amount = $salesData['total_sales_amount'];
            $total_received_payments = $salesData['total_received_payments'];
            $total_balance = $salesData['total_balance'];
        }

        if ($return_type == 'json') {
            return response()->json(compact('bookings'));
        } elseif ($return_type == 'compact') {
            // Return the filtered data
            return $payments;
        } elseif($return_type == 'view'){

            if ($report_type == 'daily_sales') {

                return view('modules.CRM.booking.reports.daily-sales-report', [
                    'payments'                  => $payments,
                    'assignedCompanies'         => $assignedCompanies,
                    'agents'                    => $agents,
                    'total_booking'             => $total_booking,
                    'total_sales_amount'        => $total_sales_amount,
                    'total_received_payments'   => $total_received_payments,
                    'total_balance'             => $total_balance,
                    'filters'                   => $request->only(['company_id', 'booking_number', 'payment_from_date', 'payment_to_date', 'agent_id', 'order_by'])
                ]);

            } else {
                return view('modules.CRM.booking.reports.bookings-received-payments', [
                    'payments' => $payments,
                    'assignedCompanies' => $assignedCompanies,
                    'agents' => $agents,
                    'filters' => $request->only(['company_id', 'booking_number', 'payment_from_date', 'payment_to_date', 'agent_id', 'order_by'])
                ]);

            }

        }

    }

    public function paymentsExportToExcel(Request $request)
    {
        $user = Auth::user();
        if ($user->role > 3) {
            return response()->json('Inavlid Request', 400);

        }
        // Call the bookingSearch method to get the filtered bookings
        // Pass 'compact' as the return_type to get the data
        $filteredBookings = $this->bookingsReceivedPayments($request, 'compact');


        // Generate a temporary file name
        $fileName = 'payments_report_'.Auth::user()->id.'_'. \Carbon\Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';

        // Store the file in the storage/app/public directory
        Excel::store(new PaymentsExport($filteredBookings), 'public/ExcelBookings/' . $fileName);

        // Generate the public URL for the stored file
        $downloadUrl = Storage::url('public/ExcelBookings/'.$fileName);

        // Return the download URL as a JSON response
        return response()->json(['url' => $downloadUrl]);
    }



    public function bookingsPendingPayments(Request $request, $return_type ='view', $pagination = true)
    {
        $user = Auth::user();

        if ($user->employee) {
            // Retrieve companies assigned to the employee
            $assignedCompanies = $user->employee->companies;
        }

        $user_role = $user->role;
        //echo '<pre>'; print_r($user); echo '</pre>'; //exit;
        if ($user_role == 1 || $user_role == 2) {

            //$agents = User::where('role','>','2')->get();
            $agents = User::all();
        } else if ($user_role == 3 || $user_role == 4) {

            // Retrieve company IDs of the logged-in user
            $employeeCompanyIds = $assignedCompanies->pluck('id')->toArray();

            // Check if user is role 4 (Team Lead)
            if ($user_role == 4) {
                // Retrieve the list of employees where the team_lead_id is the logged-in user
                $teamLeadEmployeeIds = Employee::where('team_lead_id', $user->employee->id)->pluck('id')->toArray();

                // Get users (agents) that are assigned to this team lead and role > 2
                $agents = User::where(function ($query) use ($teamLeadEmployeeIds, $user) {
                    // Include the team lead's own record (if needed)
                    $query->whereIn('id', $teamLeadEmployeeIds)
                        ->orWhere('id', $user->id);  // Include the team lead's own bookings
                }) //->where('role', '=', 5) // Only retrieve users with role == 5
                ->get();
            } else {
                // For role 3, get all agents related to their company
                $agents = User::whereHas('employee.companies', function ($query) use ($employeeCompanyIds) {
                    $query->whereIn('companies.id', $employeeCompanyIds);
                })->where('role', '>', 2)->get();
            }

        } else {
            $agents[] = $user;
        }
        // Extract agent IDs to use in whereIn
        $agentIds = $agents->pluck('id')->toArray();

        // Default date range: First day of the current month to today's date
        $fromDate = $request->input('due_from_date'); //?? Carbon::now()->startOfMonth()->toDateString();
        $toDate = $request->input('due_to_date'); //?? Carbon::now()->toDateString();
        // Default date range: First day of the current month to today's date
        $company_id = $request->input('company_id') ?? null;
        $booking_number = $request->input('booking_number') ?? null;

        $hasDateFilters = $request->has('due_from_date') || $request->has('due_to_date');


        $pendingPayments = bookingInstallmentPlan::with(['booking'])
                    ->where(function($query) use($fromDate, $toDate, $booking_number, $hasDateFilters){
                        /* if($booking_number == null && $hasDateFilters ){
                            // Filter by payment date range
                            $query->whereDate('due_date', '>=', $fromDate)->whereDate('due_date', '<=', $toDate);
                        } */

                        if ($booking_number == null && $hasDateFilters) {
                            // Apply conditions based on the available date filters
                            if ($fromDate) {
                                $query->whereDate('due_date', '>=', \Carbon\Carbon::parse($fromDate)->format('Y-m-d'));
                            }
                            if ($toDate) {
                                $query->whereDate('due_date', '<=', \Carbon\Carbon::parse($toDate)->format('Y-m-d'));
                            }
                        }
                    })
                    ->whereHas('booking', function($query) use ($company_id, $booking_number, $agentIds, $user){
                        if($company_id != null){
                            $query->where('company_id', $company_id);
                        }
                        if($booking_number != null){
                            $query->where('booking_number', $booking_number);
                        }
                        $query->whereIn('ticket_status',[1,2,3,4,5,9,10]);
                        // $query->where('payment_status', 1); // Commented on 22-04-2025 by umair
                        $query->whereIn('created_by', $agentIds)->orWhere('created_by', $user->id);
                    })
                    ->where('amount', '>', 0)
                    ->where('is_received', 0)
                    ->orderBy('due_date', 'ASC')
                    ->get();


        //->get();
        if ($return_type == 'json') {
            return response()->json(compact('bookings'));
        } elseif ($return_type == 'compact') {
            // Return the filtered data
            return $pendingPayments;
        } elseif($return_type == 'view'){
            return view('modules.CRM.booking.reports.bookings-pending-payments', [
                'pendingPayments' => $pendingPayments,
                'assignedCompanies' => $assignedCompanies,
                'agents' => $agents,
                'filters' => $request->only(['company_id', 'booking_number', 'due_from_date', 'due_to_date', 'agent_id', 'order_by'])
            ]);
        }

    }

    public function pendingPaymentsExportToExcel(Request $request)
    {
        $user = Auth::user();
        if ($user->role > 3) {
            return response()->json('Inavlid Request', 400);

        }
        // Call the bookingSearch method to get the filtered bookings
        // Pass 'compact' as the return_type to get the data
        $filteredBookings = $this->bookingsPendingPayments($request, 'compact');

        // Generate a temporary file name
        $fileName = 'installment_report_'.Auth::user()->id.'_'. \Carbon\Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';

        // Store the file in the storage/app/public directory
        Excel::store(new BookingsReportExport($filteredBookings, 'installment-report'), 'public/ExcelBookingsReport/' . $fileName);

        // Generate the public URL for the stored file
        $downloadUrl = Storage::url('public/ExcelBookingsReport/'.$fileName);

        // Return the download URL as a JSON response
        return response()->json(['url' => $downloadUrl]);
    }

    function deletePNRApi(int $pnr_id)
    {
        //return response()->json($pnr_id, 200);
        DB::beginTransaction();
        try {
            // Find the payment record by ID
            // $payment = BookingPayment::findOrFail($id);
            $bookingPnr = bookingPnr::findOrFail($pnr_id);
            $pnr_id = $bookingPnr->id;
            $booking_id = $bookingPnr->booking_id;

            $booking = Booking::findOrFail($booking_id);

            /** UPDATE ON 02-08-2025 */
            $oldData = bookingPnr::where('id', $pnr_id)->get()->map(function ($pnr) {
                return clone $pnr;
            });
            /** END */

            // Check if the logged-in user is an admin or super admin
            $user = Auth::user(); // Get the currently logged-in user

            // You can define the roles in your application, for example:
            $isAuthorized = $user->role === 1 || $user->role === 2; //|| $user->role === 3;


            // Check if there are other active PNRs for this booking
            $activePnrCount = bookingPnr::where('booking_id', $booking_id)
            ->whereNull('deleted_at') // Ensure we only count non-deleted PNRs
            ->count();

            // If there is only 1 active PNR, we should not allow deletion
            if ($activePnrCount <= 1) {
                throw new Exception("Cannot delete the last active PNR for this booking.", 1);
            }

            $activePassengerCount = Passenger::where('booking_id', $booking_id)
            ->where(function ($query) use ($pnr_id) {
                $query->where('pnr_id', '!=', $pnr_id)
                    ->orWhereNull('pnr_id');
            })
            ->count();

            // If there is only 1 active PNR, we should not allow deletion
            if ($activePassengerCount <= 0) {
                throw new Exception("Cannot delete the PNR for this booking because booking dont have other passangers details.", 1);
            }

            $passengers = Passenger::where('booking_id', $booking_id)->where('pnr_id', $pnr_id)->delete();  // Delete all matching records directly from the database;

            $flights = bookingFlight::where('booking_id', $booking_id)->where('pnr_id', $pnr_id)->delete();  // Delete all matching records directly from the database

            // Soft delete the payment record
            $bookingPnr->is_active = 0;//Auth::id();
            $bookingPnr->deleted_by = Auth::user()->id;//Auth::id();
            $bookingPnr->save();
            $bookingPnr->delete();

            /** UPDATE ON 02-08-2025 */
            $newData = bookingPnr::where('id', $pnr_id)->get();
            $booking_number = $booking->booking_prefix . $booking->booking_number;
            sendBookingUpdateMail($oldData, $newData, $booking_number, 'PNR Deleted');
            /** END */

            // Log the event
            bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Booking PNR Deleted',
                'description' => 'Booking PNR Deleted by user ' . Auth::user()->name,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            // Return a JSON response indicating success
            //return response()->json(['success' => true, 'message' => 'Payment record deleted successfully.'], 200);
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'PNR record deleted successfully'], 200);

        } catch (Exception $e) {
            DB::rollback();

            $message = $e->getMessage();
            //return response()->json(['success' => false, 'message' => $message], 400);
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    function addPnr(int $booking_id) {
        if (view()->exists('modules.CRM.booking.modals.add-pnr')) {

            $booking = Booking::findOrFail($booking_id);

            return view('modules.CRM.booking.modals.add-pnr', compact('booking'));
        }
        return abort(404);
    }

    function savePnr(Request $request){

        DB::beginTransaction();
        try {

            $pnr            = $request->pnr;
            $booking_id     = $request->booking_id;
            $supplier       = $request->supplier;

            $api_response = PNRConverterController::pnrConvertPnrExpert($request);
            $response = json_decode($api_response->original['pnrResponse']);

            $passengers = $response->data->passengers;
            $flights = $response->data->flights;

            /**
             * UPDATED ON 02-11-2024 PNR */

            $jsonData       = $api_response->original['pnrResponse'];
            $pnrResponse    = $response;
            $pnrKey         = $api_response->original['pnrKey'];

            $pnr_data_value = array(
                'booking_id'        => $booking_id,
                'supplier'          => $supplier,
                'pnr'               => $pnr,
                'pnr_key'           => $api_response->original['pnrKey'],
                'pnr_response'      => $api_response->original['pnrResponse'],
                'is_active'         => '1',
                'created_by'        => auth()->id(),
                'status'            => '1',
            );
            $bookingPnr = bookingPnr::create($pnr_data_value);

            foreach ($passengers as $pn_key => $pass_data_value) {

                $name = $pass_data_value->name;

                // Get the name and split it by space

                $nameParts = explode('/', $name);

                $lastName = $nameParts[0];  // Last name
                $firstMiddleName = $nameParts[1];  // First and middle name (if available)

                // Initialize first name, middle name, and title
                $firstName = '';
                $middleName = '';
                $title = '';

                // Split the first/middle name part into words
                $nameWords = explode(' ', $firstMiddleName);
                $lastWord = ucfirst(strtolower(end($nameWords)));  // Get the last word (potential title)

                // Check if the last word is a title
                if (in_array($lastWord, ['Mr', 'Ms', 'Mrs', 'Miss', 'Mstr'])) {
                    $title = $lastWord;
                    // Remove the title from the name words
                    array_pop($nameWords);
                }

                // The first word is the first name
                $firstName = $nameWords[0];

                // The rest are considered as middle name
                $middleName = implode(' ', array_slice($nameWords, 1));

                $pass_data_value = array(
                    'booking_id'        => $booking_id,
                    'pnr_id'            => $bookingPnr->id,
                    'pnr_key'           => $api_response->original['pnrKey'],
                    'title'             => $title,
                    'first_name'        => $firstName,
                    'middle_name'       => $middleName,
                    'last_name'         => $lastName,
                    'date_of_birth'     => null,
                    'age'               => null,
                    'mobile_number'     => null,
                    'email'             => null,
                    'is_active'         => '1',
                    'created_by'        => auth()->id(),
                    'status'            => '1',
                );
                Passenger::create($pass_data_value);

                // Passenger::create($pass_data_value);
            }

            foreach ($flights as $fl_key => $flight) {
                $departure_details = $flight->departingFrom;
                $arrival_details = $flight->arrivingAt;

                // Parse the string using Carbon
                $departure_datetime = Carbon::parse($departure_details->time);

                // Get the date and time in separate variables
                $departure_date = $departure_datetime->toDateString(); // This will give '2025-10-16'
                $departure_time = $departure_datetime->toTimeString(); // This will give '09:45:00'

                // Parse the string using Carbon
                $arrival_datetime = Carbon::parse($arrival_details->time);

                // Get the date and time in separate variables
                $arrival_date = $arrival_datetime->toDateString(); // This will give '2025-10-16'
                $arrival_time = $arrival_datetime->toTimeString(); // This will give '09:45:00'

                $flights_data_value = array(
                    'booking_id'            => $booking_id,
                    'pnr_id'                => $bookingPnr->id,
                    'pnr_key'               => $api_response->original['pnrKey'],
                    'supplier'              => $supplier,
                    'gds_no'                => null,
                    'airline_locator'       => null,
                    'ticket_no'             => null,
                    'flight_number'         => $flight->flightNumber,
                    'departure_airport'     => $departure_details->airportName,
                    'departure_date'        => $departure_date,
                    'departure_time'        => $departure_time,
                    'arrival_airport'       => $arrival_details->airportName,
                    'arrival_date'          => $arrival_date,
                    'arrival_time'          => $arrival_time,
                    'air_line_name'         => $flight->airlineName,
                    'booking_class'         => $flight->cabin,
                    'number_of_baggage'     => null,
                    'is_active'             => '1',
                    'created_by'            => auth()->id(),
                    'status'                => '1',
                );

                bookingFlight::create($flights_data_value);
            }


            /**
             * END
             */

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'New PNR into Booking has been added successfully.'], 200);
        } catch (Exception $e) {
            DB::rollback();
            $message = "";
            $message = " Error Code: " . $e->getCode();
            $message .= " \n Line No: " . $e->getLine();
            $message .= "\n Error Message: " . $e->getMessage();
            //echo '<pre>'; print_r($message); echo '</pre>'; //exit;
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    function approveBooking($booking_id) {
        //return response()->json($pnr_id, 200);
        DB::beginTransaction();
        try {
            //$booking = Booking::findorfail($booking_id);
            $booking = Booking::where('id', $booking_id)->where('status', '!=', '2')->first();
            if (!$booking) {
                throw new Exception("This Booking Already Approved", 1);
            }
            // Update the status column to 2
            $booking->status = 2;
            // Save the changes to the database
            $booking->save();

            // Log the event
            bookingInternalComment::create([
                'booking_id'    => $booking->id,
                'title'         => 'Booking Approved',
                'comments'      => 'Booking Approved Successfully',
                'is_active'     => 1,
                'created_by'    => auth()->id(),
                'status'        => 1,
            ]);

            // Log the event
            bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Booking Approved',
                'description' => 'Booking Approved by user ' . Auth::user()->name,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            // Return a JSON response indicating success
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Booking Approved successfully'], 200);
         } catch (Exception $e) {
            DB::rollback();
            $message = $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    function rejectBooking(int $booking_id) {
        if (view()->exists('modules.CRM.booking.modals.reject-booking')) {

            $booking = Booking::findOrFail($booking_id);

            return view('modules.CRM.booking.modals.reject-booking', compact('booking'));
        }
        return abort(404);
    }

    function saveRejectBooking(Request $request){

        DB::beginTransaction();
        try {

            //return response()->json($request->all(), 200);

           $booking_id = $request->booking_id;
           $comments = $request->comments;

           $booking = Booking::where('id', $booking_id)->where('status', '1')->first();
           if (!$booking) {
               throw new Exception("This Booking Already Approved or Rejected", 1);
           }
           // Update the status column to 2
           $booking->status = 0;
           // Save the changes to the database
           $booking->save();

            // Log the event
            bookingInternalComment::create([
                'booking_id'    => $booking->id,
                'title'         => 'Booking Rejected',
                'comments'      => $comments,
                'is_active'     => 1,
                'created_by'    => auth()->id(),
                'status'        => 1,
            ]);

            // Log the event
            bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Booking Rejected',
                'description' => 'Booking Rejected by user ' . Auth::user()->name.' due to: '.$comments,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            // Return success response
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Booking rejected successfully.'], 200);
        } catch (Exception $e) {
            DB::rollback();
            // $message = "";
            // $message = " Error Code: " . $e->getCode();
            // $message .= " \n Line No: " . $e->getLine();
            $message = $e->getMessage();
            //echo '<pre>'; print_r($message); echo '</pre>'; //exit;
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    function changeBookingOwnerShip(int $booking_id) {
        if (view()->exists('modules.CRM.booking.modals.change-booking-ownership')) {

            $booking = Booking::findOrFail($booking_id);
            $activeUsers = User::whereHas('employee.companies', function ($query) {
                $query->where('employee_company.company_id', 1)->where('users.is_active', 1);
            })->get();

            //echo '<pre>'; print_r($activeUsers); echo '</pre>'; //exit;

            return view('modules.CRM.booking.modals.change-booking-ownership', compact('booking', 'activeUsers'));
        }
        return abort(404);
    }

    function saveBookingOwnerShip(Request $request){
        //return response()->json($pnr_id, 200);
        DB::beginTransaction();
        try {
            $booking_id = $request->booking_id;
            $comments = $request->comments;

            $booking = Booking::findorfail($booking_id);

            if (Auth::user()->role > 2) {
                throw new Exception("Error! you are not authorised to perform this action...", 1);
            }

            if ($booking->created_by == $request->created_by) {
                throw new Exception("Error Processing Request, You can not reassign the booking to same owner...", 1);
            }

            $dbUser = User::findOrFail($request->created_by);
            $previous_owner = $booking->user->name;
            $booking->is_ownership_changed = 1;
            $booking->ownership_changed_by = auth()->id();
            $booking->ownership_changed_on = Carbon::now();
            $booking->ownership_change_reason = $comments;
            $booking->created_by = $request->created_by;
            $booking->update();

            // Log the event
            bookingLog::create([
                'booking_id' => $booking_id,
                'action' => 'Booking Ownership Changed',
                'description' => 'Booking Ownership Changed by user ' . Auth::user()->name.' due to: '.$comments.' to user having id: '.$request->created_by.' and name: '.$dbUser->name.' previous owner was: '.$previous_owner,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            // Return a JSON response indicating success
            //return response()->json(['success' => true, 'message' => 'Payment record deleted successfully.'], 200);
            return response()->json(['title' => 'Congratulations', 'icon' => 'success', 'message' => 'Booking ownership successfully changed'], 200);
         } catch (Exception $e) {
            DB::rollback();
            $message = $e->getMessage();
            return response()->json(['title' => 'Warning', 'icon' => 'warning', 'message' => $message], 400);
        }
    }

    public function getBookingStatistics(Request $request, $onlyBooking = true)
    {
        // echo '<pre>'; print_r($request->all()); echo '</pre>'; //exit;
        // Get date range filters
        $fromDate = $request->input('from_date') ?? Carbon::now()->toDateString();//Carbon::now()->startOfMonth()->toDateString();
        $toDate = $request->input('to_date') ?? Carbon::now()->toDateString();

        // Get optional filters
        $companyId = $request->input('company_id');
        $bookingNumber = $request->input('booking_number');

        // Query bookings created within the specified date range
        // $bookingsQuery = Booking::whereBetween('created_at', [$fromDate, $toDate]);
        $bookingsQuery = Booking::whereDate('created_at', '>=', $fromDate)->whereDate('created_at', '<=', $toDate);
        if ($companyId) {
            $bookingsQuery->where('company_id', $companyId);
        }
        if ($bookingNumber) {
            $bookingsQuery->where('booking_number', $bookingNumber);
        }

        // Get the total number of bookings
        $totalBookings = $bookingsQuery->count();

        // Sum of total booking sales cost within the date range
        $totalSalesAmount = $bookingsQuery->sum('total_sales_cost');

        // Sum of total balance amount from bookings
        $totalBalance = $bookingsQuery->sum('balance_amount');

        // Query received payments from booking_payments
        $receivedPaymentsQuery = BookingPayment::whereDate('deposit_date', '>=', $fromDate)
        ->whereDate('deposit_date', '<=', $toDate);

        // If $onlyBooking is true and booking_number is not provided, filter payments based on both booking's created_at and payment's deposit_date
        if ($onlyBooking) {
        if ($bookingNumber) {
        // If booking_number is provided, only fetch payments for that specific booking
        $receivedPaymentsQuery->whereHas('booking', function ($query) use ($bookingNumber) {
        $query->where('booking_number', $bookingNumber);
        });
        } else {
        // If booking_number is not provided, fetch payments of bookings created between the dates, and payments' deposit_date is between the dates
        $receivedPaymentsQuery->whereHas('booking', function ($query) use ($fromDate, $toDate) {
        $query->whereDate('created_at', '>=', $fromDate)
        ->whereDate('created_at', '<=', $toDate);
        });
        }
        }

        // If $onlyBooking is false, fetch all payments within the date range regardless of booking dates
        if (!$onlyBooking) {
        // Just get payments within the specified deposit_date range
        $receivedPaymentsQuery->whereDate('deposit_date', '>=', $fromDate)
        ->whereDate('deposit_date', '<=', $toDate);
        }

        // Sum of total received payments
        $totalReceivedPayments = $receivedPaymentsQuery->sum('reciving_amount');

        return response()->json([
            'total_bookings' => $totalBookings,
            'total_sales_amount' => $totalSalesAmount,
            'total_received_payments' => $totalReceivedPayments,
            'total_balance' => $totalBalance,
        ]);
    }


    public function dailySalesReport(Request $request, $return_type = 'view') {

        // Set default dates if not provided
        $fromDate = $request->input('from_date') ?? Carbon::now()->toDateString();//Carbon::now()->startOfDay()->toDateString();
        $toDate = $request->input('to_date') ?? Carbon::now()->toDateString();

        // Get agent filter if available
        $agentIds = $request->input('agent_id') ?? [];

        // Get company and booking number filters if available
        $companyId = $request->input('company_id') ?? null;
        $bookingNumber = $request->input('booking_number') ?? null;

        // Get assigned companies and agents
        $agentData = $this->reportFilterService->getAssignedCompaniesAndAgents($agentIds);
        $assignedCompanies = $agentData['assignedCompanies'];
        $agents = $agentData['agents'];

        // Get the bookings based on the filters
        $bookingsQuery = Booking::query();

        // Apply filters
        if ($companyId) {
            $bookingsQuery->where('company_id', $companyId);
        }
        if ($bookingNumber) {
            $bookingsQuery->where('booking_number', 'LIKE', "%$bookingNumber%");
        }
        if ($agentIds) {
            $bookingsQuery->whereIn('created_by', $agentIds);
        }

        // Get the bookings with related payments within the date range
        // Get the bookings with related payments, applying the date range for both created_at and deposit_date
        $bookings = $bookingsQuery
        ->with(['payments' => function($query) use ($fromDate, $toDate) {
            // Apply the date range filter for payments
            $query->whereDate('deposit_date', '>=', $fromDate)
                ->whereDate('deposit_date', '<=', $toDate);
        }])
        ->whereDate('created_at', '>=', $fromDate)  // Filter bookings based on created_at
        ->whereDate('created_at', '<=', $toDate)    // Filter bookings based on created_at
        ->get();


        // Now, call the `getBookingStatistics` method to calculate the totals
        $statistics = $this->getBookingStatistics($request); // Pass the same request here

        // Convert JSON response to an array
        $salesData = $statistics->getData(true);
        // echo '<pre>'; print_r($salesData); echo '</pre>'; //exit;
        // Assign results
        $total_booking = $salesData['total_bookings'];
        $total_sales_amount = $salesData['total_sales_amount'];
        $total_received_payments = $salesData['total_received_payments'];
        $total_balance = $salesData['total_balance'];


        if ($return_type == 'json') {
            return response()->json(compact('bookings'));
        } elseif ($return_type == 'compact') {
            // Return the filtered data
            return $bookings;
        } elseif($return_type == 'view'){

            // Return the view with the filtered data
            return view('modules.CRM.booking.reports.daily-sales-report', [
                'bookings' => $bookings,
                'assignedCompanies' => $assignedCompanies,
                'agents' => $agents,
                'total_booking' => $total_booking,
                'total_sales_amount' => $total_sales_amount,
                'total_received_payments' => $total_received_payments,
                'total_balance' => $total_balance,
                'filters' => $request->only(['company_id', 'booking_number', 'from_date', 'to_date', 'agent_id', 'order_by'])
            ]);

        }

    }

    public function dailySalesExportToExcel(Request $request)
    {
        $user = Auth::user();
        if ($user->role > 3) {
            return response()->json('Inavlid Request', 400);

        }
        // Call the bookingSearch method to get the filtered bookings
        // Pass 'compact' as the return_type to get the data
        $filteredBookings = $this->dailySalesReport($request, 'compact');


        // Generate a temporary file name
        $fileName = 'daily_sales_'.Auth::user()->id.'_'. \Carbon\Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';

        // Store the file in the storage/app/public directory
        Excel::store(new BookingsReportExport($filteredBookings, 'daily-sales-report'), 'public/ExcelBookingsReport/' . $fileName);

        // Generate the public URL for the stored file
        $downloadUrl = Storage::url('public/ExcelBookingsReport/'.$fileName);

        // Return the download URL as a JSON response
        return response()->json(['url' => $downloadUrl]);
    }


    public function dailyIssuanceReport(Request $request, $return_type = 'view') {

        $userr = Auth::user(); // Get the currently logged-in user
        $isAuthorized = $userr->role === 1 || $userr->role === 2; //|| $user->role === 3;
        if (!$isAuthorized) {
            abort(403, 'Unauthorized action.');

        }

        // Remove all blank values from the request
        $searchParams = array_filter($request->all());
        $searchParams = Arr::except($searchParams, ['_token']);

        // Set default dates if not provided
        $fromDate = $request->input('from_date') ?? Carbon::now()->toDateString();//Carbon::now()->startOfDay()->toDateString();
        $toDate = $request->input('to_date') ?? Carbon::now()->toDateString();

       // Get agent filter if available
       $agentIds = $request->input('agent_id') ?? [];

       // Get company and booking number filters if available
       $companyId = $request->input('company_id') ?? null;
       $bookingNumber = $request->input('booking_number') ?? null;

       // Get assigned companies and agents
       $agentData = $this->reportFilterService->getAssignedCompaniesAndAgents($agentIds);
       $assignedCompanies = $agentData['assignedCompanies'];
       $agents = $agentData['agents'];


        // Query each table for the required conditions and add 'type' attribute
        $transports = BookingTransport::whereNotNull('actual_net_cost')
        ->where('actual_net_cost', '>', 0)
        ->whereBetween('actual_net_on', [$fromDate, $toDate])
        ->with('booking') // Eager load the booking relationship
        ->get()
        ->map(function ($item) {
            $item->record_type = 'Transport'; // Adding 'type' attribute for identification
            return $item;
        });

        $hotels = BookingHotel::whereNotNull('actual_net_cost')
        ->where('actual_net_cost', '>', 0)
        ->whereBetween('actual_net_on', [$fromDate, $toDate])
        ->with('booking') // Eager load the booking relationship
        ->get()
        ->map(function ($item) {
            $item->record_type = 'Hotel'; // Adding 'type' attribute for identification
            return $item;
        });

        $pnrs = BookingPnr::whereNotNull('actual_net_cost')
        ->where('actual_net_cost', '>', 0)
        ->where('is_active', 1)
        ->whereBetween('actual_net_on', [$fromDate, $toDate])
        ->with(['booking', 'flights']) // Eager load the booking and flights relationship
        ->get()
        ->map(function ($item) {
            $item->record_type = 'Flight'; // Adding 'type' attribute for identification
            // Get the first related flight's gds_no, or null if not exists
            $item->gds_no = optional($item->flights->first())->gds_no;
            return $item;
        });

        $visas = BookingVisa::whereNotNull('actual_net_cost')
        ->where('actual_net_cost', '>', 0)
        ->whereBetween('actual_net_on', [$fromDate, $toDate])
        ->with('booking') // Eager load the booking relationship
        ->get()
        ->map(function ($item) {
            $item->record_type = 'Visa'; // Adding 'type' attribute for identification
            return $item;
        });

        // Merge all the results into a single collection
        $records = $transports->merge($hotels)->merge($pnrs)->merge($visas);

        // Optionally, you can sort the merged records by the date (if required)
        $records = $records->sortBy('actual_net_on');

        // echo '<pre>'; print_r($pnrs); echo '</pre>'; //exit;

        if ($return_type == 'json') {
            return response()->json(compact('records'));
        } elseif ($return_type == 'compact') {
            // Return the filtered data
            return $records;
        } elseif($return_type == 'view'){

            return view('modules.CRM.booking.reports.daily-issuance-report', compact('assignedCompanies', 'records', 'searchParams'));

        }
    }

    public function dailyIssuanceExportToExcel(Request $request)
    {

        $user = Auth::user();
        if ($user->role > 3) {
            return response()->json('Inavlid Request', 400);

        }
        // Call the bookingSearch method to get the filtered bookings
        // Pass 'compact' as the return_type to get the data
        $filteredBookings = $this->dailyIssuanceReport($request, 'compact');


        // Generate a temporary file name
        $fileName = 'daily_issuance_report_'.Auth::user()->id.'_'. \Carbon\Carbon::now()->format('Y-m-d_H-i-s') . '.xlsx';

        // Store the file in the storage/app/public directory
        Excel::store(new BookingsReportExport($filteredBookings, 'daily-issuance-report'), 'public/ExcelBookingsReport/' . $fileName);

        // Generate the public URL for the stored file
        $downloadUrl = Storage::url('public/ExcelBookingsReport/'.$fileName);

        // Return the download URL as a JSON response
        return response()->json(['url' => $downloadUrl]);
    }

    public function statusBasedBookingReport(Request $request, $status) {

        $userr = Auth::user(); // Get the currently logged-in user
        $isAuthorized = $userr->role === 1 || $userr->role === 2; //|| $user->role === 3;
        if (!$isAuthorized) {
            abort(403, 'Unauthorized action.');
        }

        if (!$status) {
            abort(404, 'Status not found.');
        }

        $typeDetails = TypeDetail::where('type_id', 1)->where('detail_number', $status)->first();


        $fromDate = $request->input('from_date') ?? null;
        $toDate = $request->input('to_date') ?? null;

       // Get agent filter if available
       $agentIds = $request->input('agent_id') ?? [];

       // Get company and booking number filters if available
       $companyId = $request->input('company_id') ?? null;
       $bookingNumber = $request->input('booking_number') ?? null;

       // This array will be passed to the view for repopulating filters
        $searchParams = [
            'from_date' => $fromDate,
            'to_date' => $toDate,
            'company_id' => $companyId,
        ];


       // Get assigned companies and agents
       $agentData = $this->reportFilterService->getAssignedCompaniesAndAgents($agentIds);
       $assignedCompanies = $agentData['assignedCompanies'];
       $agents = $agentData['agents'];


        // $bookings = Booking::with(['passengers', 'payments', 'flights'])->where('ticket_status', $status)->get();

        // Fetch bookings with filters
        $bookings = Booking::with(['passengers', 'payments', 'flights'])
        ->where('ticket_status', $status)
        ->when($companyId, fn($q) => $q->where('company_id', $companyId))
        // ->when($bookingNumber, fn($q) => $q->where('booking_number', $bookingNumber))
        ->when($fromDate && $toDate, function ($q) use ($fromDate, $toDate) {
            $q->whereBetween('booking_status_on', [$fromDate, $toDate]);
        })
        ->when($fromDate && !$toDate, function ($q) use ($fromDate) {
            $q->where('booking_status_on', '>=', $fromDate);
        })
        ->when(!$fromDate && $toDate, function ($q) use ($toDate) {
            $q->where('booking_status_on', '<=', $toDate);
        })
        ->get();
        // echo Carbon::now()->startOfMonth()->toDateString();

        return view('modules.CRM.booking.reports.booking-status-report', compact('assignedCompanies', 'bookings', 'typeDetails', 'searchParams'));
    }
}
