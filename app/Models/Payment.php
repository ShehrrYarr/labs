<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
     use HasFactory;

    protected $fillable = [
        'invoice_id',
        'amount',
        'method',
        'received_by_user_id',
        'paid_at',
        'notes',
    ];
protected $casts = [
    'paid_at' => 'datetime',
];
    public function invoice()
    {
        return $this->belongsTo(\App\Models\Invoice::class);
    }

    public function receivedByUser()
    {
        return $this->belongsTo(\App\Models\User::class, 'received_by_user_id');
    }
}
