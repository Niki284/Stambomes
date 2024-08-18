<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    use HasFactory;

    protected $fillable = [
        'beheerder_id',
        'first_name',
        'last_name',
        'avatar',
        'gender',
        'is_alive',
        'birth_date',
        'death_date',
        'mother_id',
        'father_id',
        'spouse_id',
        'paternal_grandfather_id',
        'paternal_grandmother_id',
        'maternal_grandfather_id',
        'maternal_grandmother_id'
    ];

    public function mother()
    {
        return $this->belongsTo(People::class, 'mother_id');
    }

    public function father()
    {
        return $this->belongsTo(People::class, 'father_id');
    }

    public function spouse()
    {
        return $this->belongsTo(People::class, 'spouse_id');
    }

    public function children()
    {
        return $this->hasMany(People::class, 'father_id')->orWhere('mother_id', $this->id);
    }
    public function beheerder()
    {
        return $this->belongsTo(User::class, 'beheerder_id');
    }
    public function paternalGrandfather()
    {
        return $this->belongsTo(People::class, 'paternal_grandfather_id');
    }

    public function paternalGrandmother()
    {
        return $this->belongsTo(People::class, 'paternal_grandmother_id');
    }

    public function maternalGrandfather()
    {
        return $this->belongsTo(People::class, 'maternal_grandfather_id');
    }

    public function maternalGrandmother()
    {
        return $this->belongsTo(People::class, 'maternal_grandmother_id');
    }

   
    

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'country_people');
    }

    public function history()
    {
        return $this->hasOne(History::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }
}
