<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'created_by_branch_id',
        'created_by_user_id',
        'phone',
        'address',
        'dob',
        'gender',
        'is_active',
        'ref_by'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function createdByBranch()
    {
        return $this->belongsTo(\App\Models\Branch::class, 'created_by_branch_id');
    }

    public function createdByUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by_user_id');
    }
    public function testOrders()
{
    return $this->hasMany(\App\Models\TestOrder::class);
}
}
