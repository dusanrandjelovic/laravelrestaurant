<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Userreservation extends Model
{
    //
public function user(){
  return $this->belongsTo('App\User');
}



}