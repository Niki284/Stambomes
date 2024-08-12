<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'people_id',
        'description',
        'start_school',
        'end_school',
        'start_spouse',
        'end_spouse'
    ];

    public function people()
    {
        return $this->belongsTo(People::class);
    }
}
