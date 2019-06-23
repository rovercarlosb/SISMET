<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'name', 'surname', 'address', 'city', 'email','country', 'comment', 'birthdate', 'image',
    ];

}
