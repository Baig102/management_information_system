<?php

use App\Http\Controllers\AMS\ChartOfAccountController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HRM\AclController;
use App\Http\Controllers\AMS\VendorController;
use App\Http\Controllers\AMS\AmsHomeController;
use App\Http\Controllers\CRM\BookingController;
use App\Http\Controllers\CRM\CrmHomeController;
use App\Http\Controllers\CRM\InquiryController;
use App\Http\Controllers\HRM\CompanyController;
use App\Http\Controllers\HRM\HrmHomeController;
use App\Http\Controllers\HRM\EmployeeController;
use App\Http\Controllers\CRM\DashboardController;
use App\Http\Controllers\API\FacebookLeadsController;
use App\Http\Controllers\API\PNRConverterController;
use App\Http\Controllers\AMS\BusinessCustomerController;
use App\Http\Controllers\SABRE\SabrePnrConverterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');
Route::get('/pages-profile', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

//Update User Details
Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');
/* Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index'); */

//Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');

Route::get('/register', function () {
    return view('auth-404-cover');
});
Route::get('/password/reset', function () {
    return view('auth-404-cover');
});

Route::get('/pnr/{pnr_number}', [SabrePnrConverterController::class, 'getPassengerData']);
// Route::post('/convert-pnr', [PNRConverterController::class, 'pnrConvert'])->name('convert-pnr');

Route::middleware(['auth'])->group(function () {

    /* Route::prefix('hrm')->middleware(['module.access:hrm'])->name('hrm')->group(function () {

    }); */

    Route::prefix('hrm')->name('hrm')->group(function () {
        // Add your new route for fetching modules by user here:
        Route::get('get-modules-by-user/{userId}', [AclController::class, 'getModulesByUser'])->name('.get-modules-by-user');

        // New route to fetch routes by module ID
        Route::get('get-routes-by-module/{moduleId}/{userId?}/{roleId?}', [AclController::class, 'getRoutesByModule'])->name('.get-routes-by-module');

        Route::middleware(['module.access:hrm'])->group(function () {
            Route::get('/', [HrmHomeController::class, 'index'])->name('.index');
            Route::get('employee-register', [EmployeeController::class, 'create'])->name('.employee-register');
            Route::post('employee-save', [EmployeeController::class, 'store'])->name('.employee-save');

            Route::get('employees', [EmployeeController::class, 'index'])->name('.all-emp');

            Route::get('view-employee-details/{id}', [EmployeeController::class, 'view'])->name('.view-employee-details');
            Route::get('employee-user-register/{id}', [EmployeeController::class, 'createEmployeeUser'])->name('.employee-user-register');
            Route::post('employee-user-register', [EmployeeController::class, 'saveEmployeeUser'])->name('.employee-user-register');

            Route::get('edit-employee-details/{id}', [EmployeeController::class, 'edit'])->name('.edit-employee-details');
            Route::post('employee-update', [EmployeeController::class, 'update'])->name('.employee-update');

            /**
             * UPDATED ON 21-06-2024
             */

            Route::get('employee-role', [EmployeeController::class, 'employeeRole'])->name('.employee-role'); // e.g., /vendor — List vendors
            Route::post('save-employee-role', [EmployeeController::class, 'saveEmployeeRole'])->name('.save-employee-role'); // e.g., /vendor — List vendors

            Route::get('role-acl-list/{role_id}', [EmployeeController::class, 'roleAclList'])->name('.role-acl-list');
            Route::post('save-role-acl', [EmployeeController::class, 'saveRoleAcl'])->name('.save-role-acl');
            /**
             * END UPDATED ON 21-06-2024
             */

            Route::controller(CompanyController::class)->group(function () {
                Route::get('create-company', 'create')->name('.create-company');
                Route::post('save-company', 'store')->name('.save-company');
                Route::get('company-list', 'index')->name('.company-list');
                Route::get('company-edit/{id}', 'edit')->name('.company-edit');
                Route::post('company-update', 'update')->name('.company-update');
            });

        });
    });

    Route::prefix('ams')->middleware(['module.access:ams'])->name('ams.')->group(function () {
        Route::get('/', [AmsHomeController::class, 'index'])->name('index');
        Route::get('get-vendors/{type?}', [VendorController::class, 'getVendors'])->name('get-vendors');

        /* Route::get('add-vendor', [VendorController::class, 'create'])->name('.add-vendor');
        Route::post('vendor-save', [VendorController::class, 'store'])->name('.vendor-save');
        Route::post('vendor-list', [VendorController::class, 'list'])->name('.vendor-list'); */

        Route::prefix('vendor')->controller(VendorController::class)->name('vendor.')->group(function () {
            Route::get('/', 'index')->name('index'); // e.g., /vendor — List vendors
            Route::get('add', 'create')->name('add'); // e.g., /vendor/add — Show create form
            Route::post('save', 'store')->name('save'); // e.g., /vendor/save — Handle form submission
            // Route::get('list', 'list')->name('list');
        });

        Route::prefix('businessCustomer')->controller(BusinessCustomerController::class)->name('businessCustomer.')->group(function () {
            Route::get('/', 'index')->name('index'); // e.g., /businessCustomer — List businesses
            Route::get('add', 'create')->name('add'); // e.g., /businessCustomer/add — Show create form
            Route::post('save', 'store')->name('save'); // e.g., /businessCustomer/save — Handle form submission
            Route::get('edit/{id}', 'edit')->name('edit'); // e.g., /businessCustomer/edit/{id} — Show edit form
            Route::post('update/{id}', 'update')->name('update'); // e.g., /businessCustomer/update/{id} — Handle form submission
            // Route::get('list', 'list')->name('list');
        });

        Route::prefix('chartOfAccounts')->controller(ChartOfAccountController::class)->name('chartOfAccounts.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('add', 'create')->name('add');
            Route::post('save', 'store')->name('save');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('update', 'update')->name('update');
            Route::delete('delete/{id}', 'delete')->name('delete');
            Route::post('export', 'export')->name('export');
        });
    });

    Route::prefix('crm')->middleware(['module.access:crm'])->name('crm')->group(function () {

        Route::get('/', [CrmHomeController::class, 'index'])->name('.index');
        // Route::get('dashboard', [CrmHomeController::class, 'dashboard'])->name('.dashboard');
        //Route::get('create-booking', [BookingController::class, 'index'])->name('.create-booking');
        Route::get('create-booking', [BookingController::class, 'indexPnr'])->name('.create-booking');
        Route::get('create-booking-pnr', [BookingController::class, 'indexPnr'])->name('.create-booking-pnr');

        /* Route::middleware('edit.booking.permission')->group(function(){

            Route::get('edit-booking/{id}', [BookingController::class, 'editBooking'])->name('.edit-booking');
        }); */

        //Route::get('edit-booking/{id}', [BookingController::class, 'editBooking'])->name('.edit-booking');
        Route::get('edit-booking/{id}', function () {
            return view('auth-500');
        })->name('.edit-booking');

        Route::get('flights-search-view', [BookingController::class, 'flightsSearchView'])->name('.flights-search-view');
        Route::get('get-vendors/{type?}', [BookingController::class, 'getVendors'])->name('.get-vendors');
        //Route::get('add-hotel-details-model/{count}', [BookingController::class, 'addHotelModelShow'])->name('.add-hotel-details-model');
        //Route::get('add-transport-details-model/{count}', [BookingController::class, 'addTransportModelShow'])->name('.add-transport-details-model');

        Route::get('get-airports', [BookingController::class, 'getAirports'])->name('.get-airports');
        Route::get('get-airlines', [BookingController::class, 'getAirlines'])->name('.get-airlines');
        Route::get('get-countries', [BookingController::class, 'getCountries'])->name('.get-countries');
        Route::get('get-visa-categories', [BookingController::class, 'getVisaCategories'])->name('.get-visa-categories');

        Route::post('save-booking', [BookingController::class, 'saveBooking'])->name('.save-booking');
        Route::get('booking-list', [BookingController::class, 'bookingList'])->name('.booking-list');
        Route::post('booking-list', [BookingController::class, 'bookingSearch'])->name('.booking-list');
        Route::get('view-booking/{id}', [BookingController::class, 'viewBooking'])->name('.view-booking');
        //Route::get('view-booking-invoice/{id}', [BookingController::class, 'viewBookingInvoice'])->name('.view-booking-invoice');
        Route::get('view-booking-invoice/{id}/{preview_type?}', [BookingController::class, 'viewBookingInvoice'])->name('.view-booking-invoice');
        Route::get('generate-booking-invoice/{id}', [BookingController::class, 'generateBookingInvoice'])->name('.generate-booking-invoice');

        Route::get('view-booking-eticket/{id}', [BookingController::class, 'viewBookingEticket'])->name('.view-booking-eticket');

        //Route::get('add-hotel-details-model/{modal}/{count}', [BookingController::class, 'addHotelModelShow'])->name('.add-hotel-details-model');
        Route::get('add-installment-plan/{id}', [BookingController::class, 'addInstallmentPlan'])->name('.add-installment-plan');
        Route::post('save-installment-plan', [BookingController::class, 'saveInstallmentPlan'])->name('.save-installment-plan');
        Route::get('edit-installment-plan/{id}', [BookingController::class, 'editInstallmentPlan'])->name('.edit-installment-plan');
        Route::post('update-installment-plan', [BookingController::class, 'updateInstallmentPlan'])->name('.update-installment-plan');

        Route::get('add-payment/{id}', [BookingController::class, 'addPayment'])->name('.add-payment');
        Route::post('save-payment', [BookingController::class, 'savePayment'])->name('.save-payment');
        Route::get('edit-payment/{booking_id}/{payment_id}', [BookingController::class, 'editPayment'])->name('.edit-payment');

        Route::delete('delete-payments/{id}', [BookingController::class, 'softDeletePayment'])->name('.soft-delete-payment');

        Route::get('approve_payment/{booking_id}/{payment_id}', [BookingController::class, 'approvePayment'])->name('.approve_payment');

        Route::get('add-other-charges/{id}', [BookingController::class, 'addOtherCharges'])->name('.add-other-charges');
        Route::post('save-other-charges', [BookingController::class, 'saveOtherCharges'])->name('.save-other-charges');
        Route::delete('delete-other-charges/{id}', [BookingController::class, 'softDeleteOtherCharges'])->name('.soft-delete-other-charges');

        Route::get('add-oc-payment/{booking_id}/{other_charges_id}', [BookingController::class, 'addOtherChargesPayment'])->name('.add-oc-payment');
        Route::post('save-oc-payment', [BookingController::class, 'saveOtherChargesPayment'])->name('.save-oc-payment');
        Route::get('edit-other-charges/{id}', [BookingController::class, 'editOtherCharges'])->name('.edit-other-charges');

        Route::get('update-ticket-status/{booking_id}', [BookingController::class, 'updateTicketStatus'])->name('.update-ticket-status');
        Route::post('save-ticket-status', [BookingController::class, 'saveTicketStatus'])->name('.save-ticket-status');
        Route::get('update-booking-status/{booking_id}', [BookingController::class, 'updateBookingStatus'])->name('.update-booking-status');
        Route::post('save-booking-status', [BookingController::class, 'saveBookingStatus'])->name('.save-booking-status');


        Route::get('add-refund/{id}', [BookingController::class, 'addRefund'])->name('.add-refund');
        Route::post('save-refund', [BookingController::class, 'saveRefund'])->name('.save-refund');
        Route::get('edit-refund/{booking_id}/{refund_id}', [BookingController::class, 'editRefund'])->name('.edit-refund');

        Route::post('approve-refund/{booking_id}/{refund_id}', [BookingController::class, 'approveRefund'])->name('.approve-refund');
        Route::post('reject-refund/{booking_id}/{refund_id}', [BookingController::class, 'rejectRefund'])->name('.reject-refund');

        Route::get('add-hotel-refund/{id}', [BookingController::class, 'addHotelRefund'])->name('.add-hotel-refund');
        Route::post('save-hotel-refund', [BookingController::class, 'saveHotelRefund'])->name('.save-hotel-refund');

        Route::post('get-data-for-refund', [BookingController::class, 'getDataForRefund'])->name('.get-data-for-refund');

        Route::get('edit-passenger/{booking_id}', [BookingController::class, 'editPassenger'])->name('.edit-passenger');
        Route::post('update-passenger', [BookingController::class, 'updatePassenger'])->name('.update-passenger');
        Route::delete('delete-passenger/{passenger_id}', [BookingController::class, 'deletePassenger'])->name('.delete-passenger');

        Route::get('edit-booking-information/{booking_id}', [BookingController::class, 'editBookingInformation'])->name('.edit-booking-information');
        Route::post('update-booking-information', [BookingController::class, 'updateBookingInformation'])->name('.update-booking-information');

        Route::get('edit-hotel-details/{booking_id}', [BookingController::class, 'editHotelDetails'])->name('.edit-hotel-details');
        Route::post('update-hotel-details', [BookingController::class, 'updateHotelDetails'])->name('.update-hotel-details');
        Route::delete('delete-hotel-details/{hotel_id}', [BookingController::class, 'deleteHotelDetails'])->name('.delete-hotel-details');

        Route::get('hotel-actual-net/{booking_id}', [BookingController::class, 'addHotelActualNet'])->name('.hotel-actual-net');
        Route::post('update-hotel-actual-net', [BookingController::class, 'updateHotelActualNet'])->name('.update-hotel-actual-net');

        Route::get('edit-transport-details/{booking_id}', [BookingController::class, 'editTransportDetails'])->name('.edit-transport-details');
        Route::post('update-transport-details', [BookingController::class, 'updateTransportDetails'])->name('.update-transport-details');
        Route::delete('delete-transport-details/{transport_id}', [BookingController::class, 'deleteTransportDetails'])->name('.delete-transport-details');

        Route::get('transport-actual-net/{booking_id}', [BookingController::class, 'addTransportActualNet'])->name('.transport-actual-net');
        Route::post('update-transport-actual-net', [BookingController::class, 'updateTransportActualNet'])->name('.update-transport-actual-net');

        Route::get('edit-visa-details/{booking_id}', [BookingController::class, 'editVisaDetails'])->name('.edit-visa-details');
        Route::post('update-visa-details', [BookingController::class, 'updateVisaDetails'])->name('.update-visa-details');
        Route::delete('delete-visa-details/{transport_id}', [BookingController::class, 'deleteVisaDetails'])->name('.delete-visa-details');

        Route::get('visa-actual-net/{booking_id}', [BookingController::class, 'addVisaActualNet'])->name('.visa-actual-net');
        Route::post('update-visa-actual-net', [BookingController::class, 'updateVisaActualNet'])->name('.update-visa-actual-net');

        Route::get('edit-flight-details/{booking_id}', [BookingController::class, 'editFlightDetails'])->name('.edit-flight-details');
        Route::post('update-flight-details', [BookingController::class, 'updateFlightDetails'])->name('.update-flight-details');
        Route::delete('delete-flight-details/{flight_id}', [BookingController::class, 'deleteFlightDetails'])->name('.delete-flight-details');

        Route::get('flight-actual-net/{booking_id}', [BookingController::class, 'addFlightActualNet'])->name('.flight-actual-net');
        Route::post('update-flight-actual-net', [BookingController::class, 'updateFlightActualNet'])->name('.update-flight-actual-net');

        Route::get('edit-pricing/{booking_id}', [BookingController::class, 'editPricingDetails'])->name('.edit-pricing');
        Route::post('update-pricing-details', [BookingController::class, 'updatePricingDetails'])->name('.update-pricing-details');

        Route::get('send-invoice-email-view/{booking_id}', [BookingController::class, 'sendInvoiceEmailView'])->name('.send-invoice-email-view');

        Route::post('send-invoice-email/{booking_id}', [BookingController::class, 'sendInvoiceEmail'])->name('.send-invoice-email');

        Route::post('export-booking-to-excel', [BookingController::class, 'exportToExcel'])->name('.export-booking-to-excel');

        Route::get('view-booking-log/{booking_id}', [BookingController::class, 'viewBookingLog'])->name('.view-booking-log');

        Route::get('edit-pnr/{booking_id}', [BookingController::class, 'editPnr'])->name('.edit-pnr');
        Route::post('update-pnr', [BookingController::class, 'updatePnr'])->name('.update-pnr');

        Route::get('edit-booking-comments/{booking_id}', [BookingController::class, 'editBookingComments'])->name('.edit-booking-comments');
        Route::post('update-booking-comments', [BookingController::class, 'updateBookingComments'])->name('.update-booking-comments');

        Route::get('add-booking-internal-comments/{booking_id}', [BookingController::class, 'addBookingInternalComments'])->name('.add-booking-internal-comments');
        Route::post('save-booking-internal-comments', [BookingController::class, 'saveBookingInternalComments'])->name('.save-booking-internal-comments');

        Route::post('approve-booking/{booking_id}', [BookingController::class, 'approveBooking'])->name('.approve-booking');
        Route::get('reject-booking/{booking_id}', [BookingController::class, 'rejectBooking'])->name('.reject-booking');
        Route::post('reject-booking', [BookingController::class, 'saveRejectBooking'])->name('.reject-booking');
        /** */
        Route::delete('delete-pnr/{id}', [BookingController::class, 'deletePNRApi'])->name('.delete-pnr');
        Route::get('add-pnr/{booking_id}', [BookingController::class, 'addPnr'])->name('.add-pnr');
        Route::post('save-pnr', [BookingController::class, 'savePnr'])->name('.save-pnr');
        /** */

        Route::get('change-booking-ownership/{booking_id}', [BookingController::class, 'changeBookingOwnerShip'])->name('.change-booking-ownership');
        Route::post('change-booking-ownership', [BookingController::class, 'saveBookingOwnerShip'])->name('.change-booking-ownership');

        Route::prefix('reports')->group(function () {
            Route::any('closed-traveling-bookings', [BookingController::class, 'closedTravelingBookings'])->name('.close-traveling-bookings');
            Route::post('export-closed-traveling-bookings-to-excel', [BookingController::class, 'closedTravelingBookingsExportToExcel'])->name('.export-closed-traveling-bookings-to-excel');

            Route::get('ticket-deadline-bookings', [BookingController::class, 'ticketDeadlineBookings'])->name('.ticket-deadline-bookings');
            Route::any('bookings-received-payments', [BookingController::class, 'bookingsReceivedPayments'])->name('.bookings-received-payments');
            Route::post('export-bookings-received-payments-to-excel', [BookingController::class, 'paymentsExportToExcel'])->name('.export-bookings-received-payments-to-excel');
            Route::any('bookings-pending-payments', [BookingController::class, 'bookingsPendingPayments'])->name('.bookings-pending-payments');
            Route::any('export-bookings-pending-payments-to-excel', [BookingController::class, 'pendingPaymentsExportToExcel'])->name('.export-bookings-pending-payments-to-excel');

            Route::any('daily-sales-report', [BookingController::class, 'dailySalesReport'])->name('.daily-sales-report');
            Route::post('export-daily-sales-report-to-excel', [BookingController::class, 'dailySalesExportToExcel'])->name('.export-daily-sales-report-to-excel');

            Route::any('daily-issuance-report', [BookingController::class, 'dailyIssuanceReport'])->name('.daily-issuance-report');
            Route::post('export-daily-issuance-report-to-excel', [BookingController::class, 'dailyIssuanceExportToExcel'])->name('.export-daily-issuance-report-to-excel');

            Route::any('booking-status-report/{status}', [BookingController::class, 'statusBasedBookingReport'])->name('.booking-status-report');
        });

        Route::controller(DashboardController::class)->group(function () {
            Route::get('dashboard', 'index')->name('.dashboard');
        });

        Route::controller(InquiryController::class)->group(function () {
            Route::get('create-inquiry', 'createInquiry')->name('.create-inquiry');
            Route::post('save-inquiry', 'saveInquiry')->name('.save-inquiry');
            //Route::get('inquiry-list', 'inquiryList')->name('.inquiry-list');
            Route::get('inquiry-list', 'inquiryList')->name('.inquiry-list');
            Route::any('pool-inquiry-list', 'poolInquiryList')->name('.pool-inquiry-list');
            Route::any('inquiry-search', 'inquirySearch')->name('.inquiry-search');
            Route::get('view-inquiry/{inquiry_id}', 'viewInquiry')->name('.view-inquiry');
            Route::post('pickup-inquiry/{inquiry_id}', 'pickupInquiry')->name('.pickup-inquiry');

            Route::get('assign-inquiry/{inquiry_id}', 'viewInquiryAssignment')->name('.assign-inquiry');
            Route::post('save-assign-inquiry', 'saveInquiryAssignment')->name('.save-assign-inquiry');

            Route::get('re-assign-inquiry/{inquiry_id}', 'viewReInquiryAssignment')->name('.re-assign-inquiry');
            Route::post('save-re-assign-inquiry', 'saveReInquiryAssignment')->name('.save-re-assign-inquiry');

            Route::post('save-pool-assign-inquiry/{inquiry_id}', 'poolPickupInquiry')->name('.save-pool-assign-inquiry');

            Route::get('bulk-assign-inquiries/{inquiry_ids}', 'viewBulkInquiryAssignment')->name('.bulk-assign-inquiries');
            Route::post('save-bulk-assign-inquiry', 'saveBulkInquiryAssignment')->name('.save-bulk-assign-inquiry');

            Route::get('view-inquiry-communicate/{inquiry_id}/{assignment_id}', 'viewInquiryCommunicate')->name('.view-inquiry-communicate');
            Route::post('save-inquiry-assign-action', 'saveInquiryAssignmentAction')->name('.save-inquiry-assign-action');
            Route::delete('delete-inquiry/{inquiry_id}', 'deleteInquiry')->name('.delete-inquiry');

            Route::post('export-inquiry-to-excel', 'exportToExcel')->name('.export-inquiry-to-excel');

        });

        /** Facebook Leads Routes */
        Route::any('fetch-facebook-leads', [FacebookLeadsController::class, 'fetchLeads'])->name('.fetch.facebook.leads');

    });


    // Route::get('/test-notification-mail', function () {
    //     $old = collect([['hotel_name' => 'Old Hotel']]);
    //     $new = collect([['hotel_name' => 'New Hotel']]);
    //     $num = 'BK-1001';

    //     sendBookingUpdateMail($old, $new, $num, 'Testing By Umair');

    //     return 'Mail attempted — check inbox/spam or storage/logs/laravel.log';
    // });

});
