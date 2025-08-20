<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class bookingOtherCharges extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

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
}
