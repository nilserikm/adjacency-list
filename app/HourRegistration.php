<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HourRegistration extends Model
{
    public function nodes()
    {
        return $this->belongsToMany('App\node');
    }
}
