<?php

namespace App\Http\Controllers;
use App\Contact;
use App\Mail\MessageReceived;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class ContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Contact::all();

       return view('contacts.index', ['contacts' => $contacts]);
         }

    public function create()
    {  
     $contact = new  Contact;
       return view('contacts.create', ['contact' => $contact]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $options = [
           'name'  => $request->nombre,
           'apellido'  => $request->apellido,
           'email'  => $request->correo,
           'numbercontact'  => $request->numbercontact
       ];

       if(Contact::create($options)) {
           Mail::to($request->correo)->queue(new MessageReceived($options));
       	return redirect('/');
      
       }else{

       	return view('contacts.create');
       }
  
}




    public function show($id)
    {
        $contact = Contact::find($id);
      return view("contacts.show",['contact' => $contact]);
    }


    public function edit($id)
    {
      $contact = Contact::find($id);
      return view("contacts.edit",['contact' => $contact]);
    }


    public function update(Request $request, $id)
    {
            $contact = Contact::find($id);

            $contact->name =  $request->nombre;
            $contact->apellido =  $request->apellido;
            $contact->email =  $request->correo;
            $contact->numbercontact =  $request->numbercontact;

            if($contact->save()){

                return redirect('/');
            }else{

      	return view('contacts.create');
       }
    }

    
    public function destroy($id)
    {
       Contact::destroy($id);
      return redirect('/contacts');
    }
}
