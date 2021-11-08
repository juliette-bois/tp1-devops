<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public function users(){
        return $this->belongsToMany('App\User');
    }

    public function budgets(){
        return $this->hasMany('App\Budget');
    }

}
