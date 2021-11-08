<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    public function spents(){
        return $this->hasMany('App\Spent');
    }

    public function group(){
        return $this->belongsTo('App\Group');
    }

    public function parent(){
        return $this->belongsTo('App\Budget', 'parent');
    }


}
