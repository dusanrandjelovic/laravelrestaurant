<?php

namespace App\Http\Controllers\Auth;

use App\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class AdminRegisterController extends Controller
{
  public function showRegistrationForm()
  {
      return view('auth.admin-register');
  }

    public function __construct()
    {
        $this->middleware('guest:admin');
    }

  /*  public function register(Request $request){

            $this->validate($request, [
              'name' => 'required',
              'email' => 'required',
              'password' => 'required',
              'password_confirmation' => 'required|same:password'
            ]);
          $admin = new Admin;
          $admin->name = $request->input('name');  // vracamo ime iz input
          $admin->email = $request->input('email');
          $admin->password = $request->input('password');
          $admin->save();

          return redirect()->intended(route('admin.login'));

        }*/


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
  /*  protected function create(array $data)
    {
        return Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }*/

    protected function create(Request $request){
      $this->validator($request->all())->validate();
      Admin::create([
          'name' => $request->name,
          'email' => $request->email,
          'password' => Hash::make($request->password),
      ]);
      return redirect()->intended(route('admin.login'));
    }
}
