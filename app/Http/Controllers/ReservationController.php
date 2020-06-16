<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reservation;
use App\Userreservation;
use Auth;

class ReservationController extends Controller
{
    
     public function __construct(){  
        $this->middleware('auth:admin', ['except' => 'submit']);
      }
  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $reservations = Reservation::orderBy('created_at', 'desc')->paginate(7);
        return view('reservations.index', compact('reservations'));
    }

  
    public function submit(Request $request)
    {
      //validujemo
  $this->validate($request, [
      'name' => 'required|min:4|max:30|regex:/^[A-Za-za-žA-Ž ]*$/i',
      'email' => 'required|email',
      'telefon' => 'required|min:5|max:12|regex:/^[0-9-\/ ]*$/i',
      'brmesta' => 'required',
      'datum' => 'required',
      'sektor' => 'required'
    ]);

  // upisujemo u bazu
  $reservation = new Reservation;
  $reservation->ime = $request->input('name');  // vracamo ime iz input
  $reservation->email = $request->input('email');
  $reservation->telefon = $request->input('telefon');
  $reservation->brmesta = $request->input('brmesta');
  $reservation->datum = $request->input('datum');
  $reservation->sektor = $request->input('sektor');
  // cuvamo poruku
  $reservation->save();

   return redirect('/reservation')->with('success', 'Rezervacija napravljena!');
   // moze redirect i u('/contacts/create') da ostanem na istu stranu
    }
    
     public function getUserReservations()
    {
      $userreservations = Userreservation::orderBy('created_at', 'desc')->paginate(7);
        return view('user-reservations.userreservations', compact('userreservations'));
    }
    
}