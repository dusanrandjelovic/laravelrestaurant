<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Admin;
use App\Blog;
use App\User;
use App\Order;
use App\Userorder;
use App\Recipe;
use Illuminate\Support\Facades\DB;
use Auth;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()  // ne dozvoljava pristup u dashboard ako nisi logovan
    {
        $this->middleware('auth:admin');  // gadjam admin guard
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admin_id =  Auth::guard('admin')->user()->id;

     $admin = Admin::find($admin_id);
     
      $blogs = $admin->blogs('blogs')->paginate(6);

        return view('admin')->with('blogs', $blogs);
      
    }

    public function getTables()
    {
       // return view('admin');
       
      
       $admin_id =  Auth::guard('admin')->user()->id;

     $admin = Admin::find($admin_id);

        return view('tables.index')->with('tables', $admin->tables);
    }
    
    public function getUsers(){
  //  $users = User::count();
  $users = User::orderBy('created_at', 'desc')->paginate(6);
    return view('users.index', compact('users'));
}

public function getOrders(){
  //  $users = User::count();
  $orders = Order::orderBy('created_at', 'desc')->paginate(6);
    return view('orders.index', compact('orders'));
}

public function getUserOrders()
{
  $userorders = Userorder::orderBy('created_at', 'desc')->paginate(7);
    return view('user-orders.userorders', compact('userorders'));
}




}




