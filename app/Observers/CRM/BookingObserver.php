<?php

namespace App\Observers\CRM;

use App\Models\Booking;

class BookingObserver
{
    /**
     * Handle the employee "creating" event.
     */
    public function creating(Booking $booking)
    {
        // Get the company associated with the booking
        $company = $booking->company;

        $booking->booking_prefix        = $company->invoice_prefix;
        $booking->booking_number        = $company->booking_number+1;
        //$booking->age                   = calculateAge($booking->date_of_birth);
        $booking->flight_pnr_ajax       = json_encode($booking->flight_pnr);
        $booking->ticket_status         = '3';
        $booking->payment_status        = ($booking->balance_amount == 0) ? 2 : ($booking->deposite_amount == 0 ? 1 : 3);
        $booking->is_active             = '1';
        $booking->status                = '1';
        $booking->created_by            = auth()->id();

        // Increment the booking_number column of the company by 1
        $company->increment('booking_number');
    }

    /**
     * Handle the Booking "created" event.
     */
    public function created(Booking $booking): void
    {
        //

    }

    /**
     * Handle the employee "updated" event.
     */
    public function updating(Booking $booking)
    {
        $booking->updated_by  = auth()->id();
    }

    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "deleted" event.
     */
    public function deleted(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "restored" event.
     */
    public function restored(Booking $booking): void
    {
        //
    }

    /**
     * Handle the Booking "force deleted" event.
     */
    public function forceDeleted(Booking $booking): void
    {
        //
    }
}
