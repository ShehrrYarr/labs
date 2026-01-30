<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestType extends Model
{
    use HasFactory;
      protected $fillable = [
        'name',
        'code',
        'price',
        'description',
        'is_active',
    ];

     protected $casts = [
        'price' => 'float',
        'is_active' => 'boolean',
    ];

    public function labTests()
{
    return $this->hasMany(\App\Models\LabTest::class, 'test_type_id');
}


    public function subTests()
    {
        return $this->hasMany(LabSubTest::class);
    }
}
