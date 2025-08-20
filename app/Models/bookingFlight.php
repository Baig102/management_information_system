<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bookingFlight extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the airline associated with the bookingFlight
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function airline()
    {
        return $this->belongsTo(airline::class);
    }

    /**
     * Get the pnr that owns the bookingFlight
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pnr()
    {
        return $this->belongsTo(bookingPnr::class);
    }
}
