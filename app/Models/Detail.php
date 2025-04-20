<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'dob', 'country_id', 'state_id', 'city_id', 'income', 'gender', 'address', 'phone'
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with the Country model
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    // Define the relationship with the State model
    public function state()
    {
        return $this->belongsTo(State::class);
    }

    // Define the relationship with the City model
    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
