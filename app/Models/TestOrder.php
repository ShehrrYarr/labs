<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestOrder extends Model
{
   use HasFactory;

    protected $fillable = [
        'customer_id',
        'branch_id',
        'created_by_user_id',
        'status',
        'visited_at',
        'notes',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    // ✅ flat list (main tests and sub tests both)
    public function items()
{
    return $this->hasMany(TestOrderItem::class)->orderBy('sort_order_snapshot')->orderBy('id');
}

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
