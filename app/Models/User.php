<?php

namespace App\Models;

use App\Models\Module;
use App\Models\UserModules;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'employee_id',
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'created_by',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get all of the modules for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function modules()
    {
        //return $this->hasMany(UserModules::class, 'user_id', 'id');
        //return $this->hasMany(UserModules::class);
        return $this->belongsToMany(Module::class, 'user_modules')->where('modules.is_active', 1);
    }

    /**
     * Get all of the businessCustomers for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function businessCustomers()
    {
        return $this->belongsToMany(BusinessCustomer::class, 'user_business_customers')->where('user_business_customers.is_active', 1);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    /**
     * Get all of the userModulesData for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    /* public function userModulesData()
    {
        return $this->hasManyThrough(
            Module::class, // Final Destination Class
            UserModules::class, // Middle Class
            'module_id', // Foreigh key of middle class
            'user_id', //
            'id',
            'id');
    } */
}
