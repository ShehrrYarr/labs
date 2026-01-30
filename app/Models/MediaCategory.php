<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaCategory extends Model
{
    use HasFactory;
    public $guarded = [];
    public function media()
    {
        //specify foreign key that is creating a relation between media and media category
        $this->hasMany(Media::class, 'media_category_id');

    }
}
