<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class AdminLoginController extends Controller
{
  public function __construct() {
   $this->middleware('guest:admin', ['except' => 'logout']);
  }
  public function showLoginForm(){
    return view('auth.admin-login');
  }
  public function login(Request $request){
   // return true;
   // validujem podatke iz Forme
  $this->validate($request, [
    'email' => 'required|email',
    'password' => 'required|min:6'
  ]);
   // logujem admina
   if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], 
   $request->remember)){
    //redirektujem gde treba
  return redirect()->intended(route('admin.dashboard'));
   }
   // ako neuspesno vracam na login
   return redirect()->back()->withInput($request->only('email', 'remember'));

  }
}