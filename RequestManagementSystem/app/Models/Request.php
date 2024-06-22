<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'created_on',
        'location',
        'service',
        'status',
        'priority',
        'department',
        'requested_by',
        'assigned_to',
    ];

    // Define casts for fields
    protected $casts = [
        'created_on' => 'date',
        'status' => 'string',
        'priority' => 'string',
    ];

    // Define relationships

    // A request is created by one user
    public function creator()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    // A request is assigned to one user
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
