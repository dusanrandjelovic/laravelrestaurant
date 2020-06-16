<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Recipe;
use App\User;
use Illuminate\Support\Facades\DB;
use Auth;

class RecipesController extends Controller
{
       /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['allRecipes', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
     
     $user_id = Auth::user()->id;
          $user = User::find($user_id);
     
         $recipes = $user->recipes('recipes')->paginate(6);
     
              return view('recipes.index', compact('recipes'));
    }

    public function allRecipes()
    {
      //   $recipes = Recipe::where('odobren', '=', '1')->paginate(6);  radi dobro
      $recipes = DB::table('recipes')
      ->where('odobren', '=', '1')
      ->orderBy('created_at', 'desc')
      ->paginate(6);
       return view('allrecipes', compact('recipes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('recipes.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      //validujemo
 //validujemo
 $this->validate($request, [
  'title' => 'required',
  'ingredients' => 'required',
  'description' => 'required',
  'photo' => 'required'
]);

$cover = $request->file('photo');
    $extension = $cover->getClientOriginalExtension();
    Storage::disk('public')->put($cover->getFilename().'.'.$extension,  File::get($cover));
 


// upisujemo u bazu
$recipe = new Recipe;
$recipe->user_id = auth()->user()->id;
$recipe->title = $request->input('title');  // vracamo ime iz input
$recipe->ingredients = $request->input('ingredients'); 
$recipe->description = $request->input('description');
$recipe->photo = $cover->getFilename().'.'.$extension;

// cuvamo poruku
$recipe->save();

return redirect('/dashboard')->with('success', 'Dodat recept! Bice prikazan kada ga odobri admin.');
// moze redirect i u('/contacts/create') da ostanem na istu stranu
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $recipe = Recipe::find($id);
       
        return view('recipes.show')->with('recipe', $recipe);
      
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
        $recipe = Recipe::find($id);
       
        return view('recipes.edit')->with('recipe', $recipe);
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
      'title' => 'required',
      'ingredients' => 'required',
      'description' => 'required',
      'photo' => 'required'
    ]);

      // upisujemo u bazu
      $recipe = Recipe::find($id);
      $recipe->user_id = auth()->user()->id;
      $recipe->title = $request->input('title');  // vracamo ime iz input
      $recipe->ingredients = $request->input('ingredients'); 
      $recipe->description = $request->input('description');
      $recipe->photo = $request->input('photo');
      
      // cuvamo poruku
      $recipe->save();

       return redirect('/dashboard')->with('success', 'Izmenjen recept!');
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
        $recipe = Recipe::find($id);


   $recipe->delete();
   return redirect('/dashboard')->with('success', 'Uspesno obrisano');
    }
}
