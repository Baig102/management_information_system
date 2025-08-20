<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function supplies()
    {
        return $this->hasMany(VendorSupply::class);
    }

    /**
     * Get all of the comments for the Vendor
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contactPersons()
    {
        return $this->hasMany(VendorContactPerson::class);
    }
}
