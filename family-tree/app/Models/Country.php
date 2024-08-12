<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'country_flag',
        'country_land',
    ];

    public function people()
{
    return $this->belongsToMany(People::class, 'country_people');
}
}
