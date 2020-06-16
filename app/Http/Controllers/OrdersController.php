<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public function store(Request $request)
    {
      //validujemo
  $this->validate($request, [
      'name' => 'required|max:30|regex:/^[A-Za-za-žA-Ž ]*$/i',
      'telefon' => 'required|regex:/^[0-9\-\/ ]*$/i',
      'adresa' => 'required|regex:/^[A-Za-za-žA-Ž0-9\-\/ ]*$/i',
      'product' => 'required'
    ]);
  
  // upisujemo u bazu
  $order = new Order;
  
   $productString = implode(",", $request->get('product'));

  $order->name = $request->input('name');  // vracamo ime iz input
  $order->phone = $request->input('telefon');
  $order->address = $request->input('adresa');
  $order->ordered = $productString;
  // cuvamo poruku
  $order->save();

   return redirect('/checkout')->with('success', 'Napravljena porudžbina!');
   // moze redirect i u('/contacts/create') da ostanem na istu stranu
    }
    
}