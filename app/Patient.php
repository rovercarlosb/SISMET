<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\History;
use App\Appointment;

class Patient extends Model
{
    protected $fillable = [
        'name', 'surname', 'address', 'city','status','email','country', 'comment', 'birthdate', 'image',
    ];

    public function history(){

    	return $this->hasMany(History::class);
    }

    public function appointments(){

    	return $this->hasMany(Appointment::class);

    }
}
