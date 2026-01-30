<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
     use HasFactory;

    protected $fillable = [
        'test_order_id',
        'subtotal',
        'discount_type',
        'discount_value',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'status',
        'invoice_no',
    ];

    public function order()
    {
        return $this->belongsTo(\App\Models\TestOrder::class, 'test_order_id');
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class);
    }
}
