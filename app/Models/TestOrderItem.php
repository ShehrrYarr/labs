<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestOrderItem extends Model
{
      use HasFactory;

    protected $fillable = [
        'test_order_id',
        'test_type_id',
        'type_price_snapshot',

        'lab_test_id',
        'lab_sub_test_id',
        'test_category_id',

        'assigned_by_user_id',
        'item_kind',

        'test_name_snapshot',
        'test_code_snapshot',
        'unit_snapshot',
        'reference_range_snapshot',
        'sort_order_snapshot',

        'result_status',
        'result_text',
        'result_file',
        'result_posted_at',
        'result_posted_by_user_id',
    ];

    protected $casts = [
        'type_price_snapshot' => 'float',
        'result_posted_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(TestOrder::class, 'test_order_id');
    }

    public function testType()
    {
        return $this->belongsTo(TestType::class);
    }

    public function labTest()
    {
        return $this->belongsTo(LabTest::class);
    }

    public function subTest()
    {
        return $this->belongsTo(LabSubTest::class, 'lab_sub_test_id');
    }

    public function resultPostedByUser()
    {
        return $this->belongsTo(User::class, 'result_posted_by_user_id');
    }
}
