<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = ['date','rule_id','patient_id','user_id','recipe'];

    public function rules()
    {
        return $this->hasMany('App\Rule', 'id', 'rule_id' );
    }

    public function patients()
    {
        return $this->hasMany('App\Patient', 'id', 'patient_id');
    }

    public function users()
    {
        return $this->hasMany('App\User', 'id', 'user_id');
    }

}
