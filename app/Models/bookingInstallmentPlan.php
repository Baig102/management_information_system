<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bookingInstallmentPlan extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the booking that owns the bookingInstallmentPlan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
