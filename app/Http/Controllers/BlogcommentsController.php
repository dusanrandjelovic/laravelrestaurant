<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Blog;
use App\Blogcomment;
//use DB;

class BlogcommentsController extends Controller
{
     public function __construct(){  // zabranio sam sve osim index (contacts.blade) i show (showcontact.blade) strane
    $this->middleware('auth:admin', ['except' => 'store']);
  }
  
      /**
       * Display a listing of the resource.
       *
       * @return \Illuminate\Http\Response
       */
      public function index()
      {
      $blogcomments = Blogcomment::orderBy('created_at', 'desc')->paginate(6);
          return view('blogcomments.index')->with('blogcomments', $blogcomments);
      }

    
     public function store(Request $request, $blog_id){
         
        //validujemo
       $this->validate($request, [
           'ime' => 'required|min:2|max:25|regex:/^[A-Za-za-žA-Ž ]*$/i',
        'komentar' => 'required|min:2|max:200|regex:/^[A-Za-za-žA-Ž0-9.,:;!? ]*$/i',
        'my_name' => 'max:0',
        'my_time'   => 'required'
      ]);
    $blog = Blog::find($blog_id);
   
    // upisujemo u bazu
    $blogcomment = new Blogcomment;
    $blogcomment->blog()->associate($blog);
    $blogcomment->name = $request->input('ime');
    $blogcomment->body = $request->input('komentar');
    // cuvamo poruku
    $blogcomment->save();

     return redirect('/news')->with('success', 'Komentar ce vam biti objavljen kada ga administrator odobri!');
}

  /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
          $blogcomment = Blogcomment::find($id);
      //  if(Auth::guard('admin')->user()->id !== $blog->admin_id){
     //     return redirect('blogs.index')->with('error', 'Neautorizovan pristup');
      //  }
        return view('blogcomments.edit')->with('blogcomment', $blogcomment);
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
        'name' => 'required',
        'body' => 'required',
        'odobren' => 'required'
      ]);
    // upisujemo u bazu
    $blogcomment = Blogcomment::find($id);
    $blogcomment->name = $request->input('name');  // vracamo ime iz input
    $blogcomment->blog_id = $request->input('blog_id');
    $blogcomment->body = $request->input('body');
    $blogcomment->odobren = $request->input('odobren');
    // cuvamo poruku
    $blogcomment->save();

     return redirect('/admin')->with('success', 'Odobren komentar');
    }
    
       /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $blogcomment = Blogcomment::find($id);

   $blogcomment->delete();
   return redirect('/admin')->with('success', 'Uspesno obrisano');
    }



}