<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Userreservation;
use App\Userorder;
use Auth;
use App\User;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard');
    }
    
       public function reservationSubmit(Request $request)
    {
      //validujemo
  $this->validate($request, [
      'userbrmesta' => 'required',
      'userdatum' => 'required',
    ]);

  // upisujemo u bazu
  $userreservation = new Userreservation;

  $userreservation->brmesta = $request->input('userbrmesta');
  $userreservation->datum = $request->input('userdatum');
  $userreservation->user_id = auth()->user()->id;
  // cuvamo poruku
  $userreservation->save();

   return redirect('/reservation')->with('success', 'Rezervacija napravljena!');
   // moze redirect i u('/contacts/create') da ostanem na istu stranu
    }
    
      public function getUserReservations(){
   //     $userreservations = Userreservation::orderBy('created_at','desc')->paginate(6);

    //     $user_id = auth()->user()->id;
  // $user = User::find($user_id);

     $user_id = auth()->user()->id;
     $user = User::find($user_id);

    $userreservations = $user->userreservations('userreservations')->paginate(6);

         return view('user-reservations.index', compact('userreservations'));
    }
    
    
    public function orderSubmit(Request $request)
    {
      //validujemo
  $this->validate($request, [
      'product' => 'required'
    ]);
  
  // upisujemo u bazu
  $userorder = new Userorder;
  
   $productString = implode(",", $request->get('product'));

  $userorder->ordered = $productString;
  $userorder->user_id = auth()->user()->id;
  // cuvamo poruku
  $userorder->save();

   return redirect('/checkout')->with('success', 'Napravljena porudžbina!');
   // moze redirect i u('/contacts/create') da ostanem na istu stranu
    }

    public function getUserOrders(){

     
          $user_id = Auth::user()->id;
          $user = User::find($user_id);
     
         $userorders = $user->userorders('userorders')->paginate(6);
     
              return view('user-orders.index', compact('userorders'));
         }
    
}
