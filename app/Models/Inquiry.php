<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inquiry extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(company::class, 'company_id');
    }

    /**
     * Get all of the inquiryAssigments for the Inquiry
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inquiryAssigments()
    {
        //return $this->hasMany(InquiryAssigment::class, 'inquiry_id', 'id');
        return $this->hasOne(InquiryAssigment::class, 'inquiry_id', 'id');
    }

    // Method to get assignments with conditions
    public function filteredInquiryAssigments($agent_id = null, $status = 2)
    {
        $query = $this->inquiryAssigments();

        if (!is_null($agent_id)) {
            $query->forAgent($agent_id); // calling scopeForAgent defined in InquiryAssignment Model
        }

        return $query->withStatus($status)->get(); // calling scopewithStatus defined in InquiryAssignment Model
    }

    /**
     * Get all of the logs for the Inquiry
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany(InquiryLog::class);
    }

    public function scopeAssignedPooledInquiriesCount($query, $userId)
    {
        return $query->where('is_pooled', 0)
                    ->whereDate('is_pooled_at', Carbon::today())
                    ->where('inquiry_assigned_to', $userId)
                    ->count();
    }

}
