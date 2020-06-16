<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;

class MessagesController extends Controller
{
    public function __construct(){  // zabranio sam sve osim index (contacts.blade) i show (showcontact.blade) strane
  $this->middleware('auth:admin', ['except' => 'submit']);
}

    //
    public function submit(Request $request){  // request od gore i varijabla koja skuplja request
//  return $request->input('name');  // -> operator objekta  pristupa svojstvima i metodama objekta
 $this->validate($request, [
   'name' => 'required|min:2|max:30|regex:/^[A-Za-za-žA-Ž ]*$/i', // required|max:255|regex:[A-Za-z ]  // alpha samo slova
   'email' => 'required|email',
   'poruka' => 'required|min:2|max:600|regex:/^[A-Za-za-žA-Ž0-9-.,:;!?\s ]*$/i'
 ]);
//  return 'Vrati uspesno ako su ime i mail upisani';
// kreiramo novu poruku posle submit
$message = new Message;
$message->name = $request->input('name');  // vracamo ime iz input
$message->email = $request->input('email');
$message->message = $request->input('poruka');
// cuvamo poruku
$message->save();
// redirektujemo na home page
//  return redirect('/contact');
return redirect('/contact')->with('success', 'Poruka je poslata!');
}

public function getMessages() {
  // elokvent metod, sve iz modela Message premestamo u view. Vuku se podaci iz baze
  $messages = Message::orderBy('created_at', 'desc')->paginate(6);
  return view('messages.index')->with('messages', $messages);
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
        $message = Message::find($id);  
       return view('messages.show')->with('message', $message);
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
        $message = Message::find($id);

   $message->delete();
   return redirect('/messages.index')->with('success', 'Uspesno obrisano');
    }

}
