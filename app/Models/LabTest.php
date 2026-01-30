<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabTest extends Model
{
    use HasFactory;
    protected $fillable = [
        'test_type_id',
        'test_category_id',
        'required_equipment_id',
        'test_name',
        'test_code',
        'test_case_image',
        'description',
        'unit',
        'reference_range',
        'reporting_time',
        'test_instruction',
        'additional_notes',
        'is_active',
        'sort_order',
    ];

    public function testType()
    {
        return $this->belongsTo(TestType::class);
    }

    public function testCategory()
    {
        return $this->belongsTo(TestCategory::class, 'test_category_id');
    }
public function equipment()
    {
        return $this->belongsToMany(
            \App\Models\Equipment::class,
            'equipment_lab_test',   // pivot table name (see Step 2)
            'lab_test_id',
            'equipment_id'
        )->withTimestamps();
    }
    public function requiredEquipment()
    {
        return $this->belongsTo(Equipment::class, 'required_equipment_id');
    }

  

public function subTests()
{
    return $this->hasMany(\App\Models\LabSubTest::class, 'lab_test_id');
}
}
