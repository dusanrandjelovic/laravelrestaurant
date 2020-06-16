<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Admin;
use App\Recipe;
use Illuminate\Support\Facades\DB;
use Auth;

class AdminRecipesController extends Controller
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

    public function index(){
        //  $users = User::count();
     //   $recipes = Recipe::orderBy('created_at', 'desc')->paginate(6);
         // return view('recipes.admin-recipes', compact('recipes'));
         
         $recipes = new Recipe;

          if(request()->has('odobren')){
          $recipes = $recipes->where('odobren', request('odobren'));
          }


            $recipes = $recipes->orderBy('created_at', 'desc')->paginate(8)->appends([
              'odobren' => request('odobren')
            ]);

        return view('recipes.admin-recipes', compact('recipes'));
      }
      
      public function show($id)
    {
        
        $recipe = Recipe::find($id);   // traxi id iz show i smesta u note
       return view('recipes.admin-show')->with('recipe', $recipe);
    }
      
      public function edit($id)
      {
          //
          $recipe = Recipe::find($id);
         
          return view('recipes.admin-edit')->with('recipe', $recipe);
      }
      
      public function update(Request $request, $id)
      {
          //
             //validujemo
      $this->validate($request, [
        'odobren' => 'required'
      ]);
      
        // upisujemo u bazu
        $recipe = Recipe::find($id);
        $recipe->odobren = $request->input('odobren');
        // cuvamo poruku
        $recipe->save();
      
         return redirect('/admin-recipes')->with('success', 'Izmenjen recept!');
      }
      
      public function destroy($id)
      {
          //
          $recipe = Recipe::find($id);
      
      
      $recipe->delete();
      return redirect('/admin')->with('success', 'Uspesno obrisano');
      }
}