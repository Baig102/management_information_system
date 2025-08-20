<?php

namespace App\Models;

use App\Models\bookingRefund;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(company::class);
    }

    /**
     * Get all of the passengers for the Booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function passengers()
    {
        return $this->hasMany(Passenger::class);
    }

    /**
     * Get all of the flights for the Booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function flights()
    {
        return $this->hasMany(bookingFlight::class);
    }

    /**
     * Get all of the hotels for the Booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hotels()
    {
        return $this->hasMany(bookingHotel::class);
    }

    /**
     * Get all of the transports for the Booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transports()
    {
        return $this->hasMany(bookingTransport::class);
    }

    /**
     * Get all of the visas for the Booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function visas()
    {
        return $this->hasMany(bookingVisa::class);
    }

    /**
     * Get all of the prices for the Booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function prices()
    {
        return $this->hasMany(bookingPricing::class);
    }

    /**
     * Get all of the otherCharges for the Booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function otherCharges()
    {
        return $this->hasMany(bookingOtherCharges::class);
    }

    /**
     * Get all of the installmentPlan for the Booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function installmentPlan()
    {
        return $this->hasMany(bookingInstallmentPlan::class);
    }


    /**
     * Get all of the payments for the Booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        // return $this->hasMany(bookingPayment::class);
        return $this->hasMany(bookingPayment::class);//->orderBy('created_at', 'desc');
    }

    /**
     * Get all of the refunds for the Booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function refunds()
    {
        return $this->hasMany(bookingRefund::class);
    }

    /**
     * Get the user that owns the Booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all of the pnrs for the Booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pnrs()
    {
        return $this->hasMany(bookingPnr::class);
    }

    /**
     * Get the stausDetails associated with the Booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function stausDetails($typeId, $column = 'booking_status')
    {
        return $this->hasOne(TypeDetail::class, 'detail_number', $column)->where('type_id', $typeId);
    }

    /**
     * Get all of the internalComments for the Booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function internalComments()
    {
        return $this->hasMany(bookingInternalComment::class);
    }

    /**
     * Get all of the logs for the Booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany(bookingLog::class);
    }

    /**
     * Get the businessCustomer that owns the Booking
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function businessCustomer()
    {
        return $this->belongsTo(BusinessCustomer::class, 'business_customer_id', 'id');
    }

}
