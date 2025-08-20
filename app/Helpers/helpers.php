<?php
// app/helpers.php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Booking;
use App\Models\bookingFlight;
use Illuminate\Support\Facades\Mail;

if (!function_exists('calculateAge')) {
    function calculateAge($dob)
    {
        // Create a Carbon instance for the date of birth
        $birthdate = Carbon::parse($dob);

        // Get the current date
        $currentDate = Carbon::now();

        // Calculate the difference in years
        $age = $currentDate->diffInYears($birthdate);

        return $age;
    }
}

if (!function_exists('userDetails')) {
    function userDetails($user_id)
    {

        $user = User::select('id', 'employee_id', 'name', 'email', 'role')->findOrFail($user_id);

        return $user;
    }
}

if (!function_exists('sendBookingUpdateMail')) {
    function sendBookingUpdateMail($oldData, $newData, $bookingNumber, $bookingService = null, $toEmail = 'notifications@bestumrahpackagesuk.com')
    {
        Mail::to($toEmail)->send(new \App\Mail\BookingNotificationMail($oldData, $newData, $bookingNumber, $bookingService));
    }

}

if (!function_exists('resolveFlightAirportsByTripType')) {
    /**
     * Get flight details based on trip type and flight type using booking ID.
     *
     * @param int $bookingId
     * @return array|null
     */
    function resolveFlightAirportsByTripType(int $bookingId): ?array
    {
        // Fetch the booking record
        $booking = Booking::find($bookingId);

        if (!$booking) {
            return null; // Return null if the booking doesn't exist
        }

        // Fetch the flight records related to the booking
        $flights = bookingFlight::where('booking_id', $bookingId)->get();
        echo "<pre>"; print_r($flights->toArray()); echo "</pre>";
        if ($flights->isEmpty()) {
            return null; // Return null if no flight records exist
        }

        $flightDetails = [];

        if ($booking->trip_type == 1) { // Oneway trip
            if ($booking->flight_type == 1) { // Direct
                // First row departure and arrival airports
                $flightDetails['departure_airport'] = $flights->first()->departure_airport;
                $flightDetails['arrival_airport'] = $flights->first()->arrival_airport;
            } elseif ($booking->flight_type == 2) { // Indirect
                // First row departure and last row arrival airports
                $flightDetails['departure_airport'] = $flights->first()->departure_airport;
                $flightDetails['arrival_airport'] = $flights->last()->arrival_airport;
            }
        } elseif ($booking->trip_type == 2) { // Return trip
            if ($booking->flight_type == 1) { // Direct
                // Combine all departure and arrival airports, separated by "-"
                $flightDetails['departure_airport'] = $flights->pluck('departure_airport')->implode(' - ');
                $flightDetails['arrival_airport'] = $flights->pluck('arrival_airport')->implode(' - ');
            } elseif ($booking->flight_type == 2) { // Indirect
                // First row departure and third row arrival for departure_airport
                $flightDetails['departure_airport'] =
                    $flights->first()->departure_airport . ' - ' .
                    optional($flights->get(1))->arrival_airport;

                // Third row departure and fourth row arrival for arrival_airport
                $flightDetails['arrival_airport'] =
                    optional($flights->get(2))->departure_airport . ' - ' .
                    optional($flights->get(3))->arrival_airport;
            }
        }

        return $flightDetails;
    }
}
?>
