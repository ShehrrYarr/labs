<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;
     protected $table = 'equipment';

    protected $fillable = ['name', 'is_active'];

    public function labTests()
{
    return $this->belongsToMany(\App\Models\LabTest::class, 'equipment_lab_test')
        ->withTimestamps();
}
}
