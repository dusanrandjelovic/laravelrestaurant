<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
  protected $fillable = array('title', 'ingredients', 'description', 'photo');
    
public function user(){
  return $this->belongsTo('App\User');
}



}