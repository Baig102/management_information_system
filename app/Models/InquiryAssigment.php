<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InquiryAssigment extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Scope for filtering by agent_id
    public function scopeForAgent($query, $agent_id)
    {
        return $query->where('agent_id', $agent_id);
    }

    // Scope for filtering by status
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function inquiry()
    {
        return $this->belongsTo(Inquiry::class, 'inquiry_id');
    }

    public function actions()
    {
        return $this->hasMany(InquiryAssigmentAction::class, 'inquiries_assignment_id');
    }
}

/* // Get all assignments for a specific agent
$assignments = InquiryAssigment::forAgent($agent_id)->get();

// Get all assignments with a specific status
$assignments = InquiryAssigment::withStatus($status)->get();

// Get all assignments for a specific agent and status
$assignments = InquiryAssigment::forAgent($agent_id)->withStatus($status)->get(); */
