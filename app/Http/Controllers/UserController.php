<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
class UserController extends Controller
{

    private $database;

    public function __construct()
    {
        $this->database = \App\Services\FirebaseService::connect();
    }

    public function get(){
        return response()->json($this->database->getReference('user')->getValue());
    }

     public function detail($id){
        $data = $this->database->getReference('user')->getValue();
        //$data[$id];
        return response()->json($data[$id]);
    }


    public function insert(Request $request){
        $unique = strtotime(date('Y-m-d H:i:s'));
        $this->database
        ->getReference('user/' . $unique)
        ->set([
            'id_user'=>$unique,
            'name' => $request->name,
            'username' => $request->username,
            'password' => $request->password
            
        ]);

        return response()->json('users has been added');
    }

    public function update(Request $request){
        $this->database
        ->getReference('user/' . $request->id_user)
        ->update([
           // 'id_user'=>$unique,
           'name' => $request->name,
           'username' => $request->username,
           'password' => $request->password
        ]);

        return response()->json('user has been updated');
    }

    public function delete(Request $request){
        $this->database
        ->getReference('user/' . $request->id_user)
        ->remove();

        return response()->json('user has been deleted');
    }

}
