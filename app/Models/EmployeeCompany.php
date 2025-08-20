<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeCompany extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = "employee_company";


}
