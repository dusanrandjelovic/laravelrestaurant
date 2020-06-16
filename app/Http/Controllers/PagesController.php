<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    //
    public function getHome() {
return view('home');
}
public function getAbout() {
return view('about');
}

public function getMenu() {
  return view('menu');
  }

  public function getGallery() {
return view('gallery');
}
public function getContact() {
return view('contact');
}
public function getReservation() {
  return view('reservation');
  }
}
