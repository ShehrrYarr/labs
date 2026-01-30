<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabSubTest extends Model
{
    use HasFactory;
     protected $fillable = [
        'lab_test_id',
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
protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
    public function labTest()
    {
        return $this->belongsTo(LabTest::class, 'lab_test_id');
    }
     public function parentTest()
    {
        return $this->labTest();
    }

    public function testType()
    {
        return $this->belongsTo(TestType::class);
    }

    public function testCategory()
    {
        return $this->belongsTo(TestCategory::class, 'test_category_id');
    }

    public function requiredEquipment()
    {
        return $this->belongsTo(Equipment::class, 'required_equipment_id');
    }

    public function orderResults()
{
    return $this->hasMany(\App\Models\TestOrderItemResult::class, 'lab_sub_test_id');
}
}
