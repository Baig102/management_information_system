<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeDetail extends Model
{
    use HasFactory;

    protected $table = "type_details";
    protected $guarded = [];

    /**
     * Get the type that owns the TypeDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(type::class);
    }

}
