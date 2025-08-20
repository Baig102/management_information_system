<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class company extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_company', 'company_id', 'employee_id');
    }

    /**
     * Get all of the banks for the company
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bankDetails()
    {
        return $this->hasMany(CompanyBankDetail::class);
    }
}
