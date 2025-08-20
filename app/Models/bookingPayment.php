<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class bookingPayment extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];
    // Specify the column that stores the ID of the user who deleted the record
    //protected $fillable = ['deleted_by'];

    /* // Override the performDeleteOnModel method from the SoftDeletes trait
    protected function performDeleteOnModel()
    {
        // Set the deleted_by column to the currently authenticated user's ID
        $this->deleted_by = Auth::id();

        // Save the record before soft deleting
        $this->save();

        // Call the parent method to perform the actual soft delete
        parent::performDeleteOnModel();
    } */

    /**
     * Get the user that owns the bookingPayment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // In BookingPayment model
    public function otherCharges()
    {
        return $this->belongsTo(bookingOtherCharges::class, 'other_charges_id');
    }

    /**
     * Get the booking that owns the bookingPayment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

}
