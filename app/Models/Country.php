<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $table = 'countries';

    // function start 
    function getState(){
        return $this->hasMany(State::class);
    }
    // functin end 

    // function getCountry(){
    //     return $this->belongsTo(User::class,'id');
    // } 


}
