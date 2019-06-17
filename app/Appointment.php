<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Patient;

class Appointment extends Model
{
    protected $fillable = ['patient_id','date','hour','status'];

    public function patient(){

    	return $this->belongsTo(Patient::class);
    }
}
