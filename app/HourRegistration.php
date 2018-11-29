<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HourRegistration extends Model
{
    public function nodes()
    {
        return $this->belongsToMany('App\node');
    }

    public function duration(DateTime $date1, DateTime $date2, int $break)
    {
        $diff = $date1->diff($date2);
        return (((($diff->days * 24) + $diff->h) * 60) + $diff->i) - $break;
    }
}
