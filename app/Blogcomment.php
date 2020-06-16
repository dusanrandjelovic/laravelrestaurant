<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blogcomment extends Model
{
    //
public function blog(){
  return $this->belongsTo('App\Blog');
}



}