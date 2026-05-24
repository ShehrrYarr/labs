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
        'age',
        'gender',
        'is_active',
        'ref_by'
    ];

    public function getDisplayAgeAttribute(): string
    {
        $val = trim((string)($this->age ?? ''));
        if ($val === '') return '-';

        // Plain integer → show as-is
        if (ctype_digit($val)) return $val . ' Years';

        // D/M/Y format (e.g. 15/03/1990)
        try {
            return \Carbon\Carbon::createFromFormat('d/m/Y', $val)->age . ' Years';
        } catch (\Throwable $e) {}

        // Any other parseable date (Y-m-d, d-m-Y, etc.)
        try {
            return \Carbon\Carbon::parse($val)->age . ' Years';
        } catch (\Throwable $e) {}

        return $val;
    }

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
