<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Module extends Model
{
    use HasFactory;
    protected $table = "modules";
    protected $guarded = [];

    /**
     * The userModules that belong to the Module
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function userModules()
    {
        return $this->belongsToMany(User::class, 'user_modules')->where('is_active', 1);
    }
}
