<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\DB;

class AdminProductsController extends Controller
{
    public function __construct(){  
  $this->middleware('auth:admin');
}

 /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $adminproducts = Product::orderBy('created_at', 'desc')->paginate(8);
        return view('admin-products.index')->with('adminproducts', $adminproducts);
    }
    
     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $adminproduct = Product::find($id);   // traxi id iz show i smesta u note
       return view('admin-products.show')->with('adminproduct', $adminproduct);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin-products.create');

    }

/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
      $this->validate($request, [
        'name' => 'required',
        'description' => 'required',
        'longdesc' => 'required',
        'details' => 'required',
        'code' => 'required',
        'photo' => 'required',
        'price' => 'required',
        'category' => 'required',
        'link' => 'required'
      ]);
      $adminproduct = new Product;

      $adminproduct->name = $request->input('name');  // vracamo ime iz input
    $adminproduct->description = $request->input('description');
    $adminproduct->longdesc = $request->input('longdesc');
    $adminproduct->details = $request->input('details');
    $adminproduct->code = $request->input('code');
    $adminproduct->photo = $request->input('photo');  
    $adminproduct->price = $request->input('price');
    $adminproduct->category = $request->input('category');
    $adminproduct->link = $request->input('link');
   
    // cuvamo poruku
    $adminproduct->save();

    return redirect('/admin')->with('success', 'Dodat proizvod!');
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $adminproduct = Product::find($id);
       
        return view('admin-products.edit')->with('adminproduct', $adminproduct);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        //validujemo
    $this->validate($request, [
        'name' => 'required',
        'description' => 'required',
        'longdesc' => 'required',
        'details' => 'required',
        'code' => 'required',
        'photo' => 'required',
        'price' => 'required',
        'category' => 'required',
        'link' => 'required'
      ]);

        // upisujemo u bazu
        $adminproduct = Product::find($id);
      
        $adminproduct->name = $request->input('name');  // vracamo ime iz input
        $adminproduct->description = $request->input('description');
        $adminproduct->longdesc = $request->input('longdesc');
        $adminproduct->details = $request->input('details');
        $adminproduct->code = $request->input('code');
        $adminproduct->photo = $request->input('photo');  
        $adminproduct->price = $request->input('price');
        $adminproduct->category = $request->input('category');
        $adminproduct->link = $request->input('link');
        // cuvamo poruku
        $adminproduct->save();

         return redirect('/admin')->with('success', 'Azuriran artikal!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $adminproduct = Product::find($id);

   $adminproduct->delete();
   return redirect('/admin')->with('success', 'Uspesno obrisano');
    }

  
    
}
