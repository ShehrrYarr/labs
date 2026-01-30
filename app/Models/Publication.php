<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use HasFactory;
    public $guarded=[];
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_publications', 'user_id', 'publication_id');
    }
}
