<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InquiryLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the inquiry that owns the InquiryLog
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class);
    }
}
