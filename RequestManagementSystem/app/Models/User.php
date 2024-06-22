<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Define fillable fields
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // Define hidden fields
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Define casts
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Define relationships

    // A user can create many requests
    public function createdRequests()
    {
        return $this->hasMany(Request::class, 'requested_by');
    }

    // A user can be assigned to many requests
    public function assignedRequests()
    {
        return $this->hasMany(Request::class, 'assigned_to');
    }
}
