<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the user associated with the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'employee_id', 'id');
    }

    /**
     * Get the teamLead associated with the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function teamLead()
    {
        return $this->hasOne(User::class, 'id', 'team_lead_id');
    }


    public function companies()
    {
        return $this->belongsToMany(company::class, 'employee_company', 'employee_id', 'company_id');
    }

    /**
     * Get the department associated with the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function department()
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }
}
