<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'people_id'
    ];

    public function people()
    {
        return $this->belongsTo(People::class);
    }
}


