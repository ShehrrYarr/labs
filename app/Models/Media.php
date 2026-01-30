<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;
    public $guarded = [];
    public function category()
    {
        //specify foreign key that is creating a relation between media and media categoryss
        return $this->belongsTo(MediaCategory::class, 'media_category_id');
    }
}
