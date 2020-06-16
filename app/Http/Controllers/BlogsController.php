<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Blog;
use Auth;

class BlogsController extends Controller
{

public function __construct(){  // zabranio sam sve osim index (contacts.blade) i show (showcontact.blade) strane
  $this->middleware('auth:admin', ['except' => ['index', 'show']]);
}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    $blogs = Blog::orderBy('created_at', 'desc')->paginate(6);
        return view('blogs.index')->with('blogs', $blogs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      //
      return view('blogs.create');

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
      $this->validate($request, [
        'naslov' => 'required',
        'tekst' => 'required',
        'datum' => 'required'
      ]);
    // upisujemo u bazu
    $blog = new Blog;
    $blog->admin_id = Auth::guard('admin')->user()->id;
    $blog->text = $request->input('naslov');  // vracamo ime iz input
    $blog->body = $request->input('tekst');
    $blog->due = $request->input('datum');
    // cuvamo poruku
    $blog->save();

     return redirect('/admin')->with('success', 'Kreirana vest!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $blog = Blog::find($id);
       return view('blogs.show')->with('blog', $blog);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
          $blog = Blog::find($id);
      //  if(Auth::guard('admin')->user()->id !== $blog->admin_id){
     //     return redirect('blogs.index')->with('error', 'Neautorizovan pristup');
      //  }
        return view('blogs.edit')->with('blog', $blog);
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
           $this->validate($request, [
        'naslov' => 'required',
        'tekst' => 'required',
        'datum' => 'required'
      ]);
    // upisujemo u bazu
    $blog = Blog::find($id);
    $blog->admin_id = Auth::guard('admin')->user()->id;
    $blog->text = $request->input('naslov');  // vracamo ime iz input
    $blog->body = $request->input('tekst');
    $blog->due = $request->input('datum');
    // cuvamo poruku
    $blog->save();

     return redirect('/admin')->with('success', 'Izmenjena vest!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blog = Blog::find($id);

      //  if(Auth::guard('admin')->user()->id !== $blog->admin_id){
     //     return redirect('/blogs.index')->with('error', 'Neautorizovan pristup');
     //   }

   $blog->delete();
   return redirect('/admin')->with('success', 'Uspesno obrisano');
    }
}
