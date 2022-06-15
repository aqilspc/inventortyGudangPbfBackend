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
        //return response()->json($this->database->getReference('user')->getValue());
        return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "data" => $this->database->getReference('user')->getValue()]
                );
    }

     public function detail($id){
        $data = $this->database->getReference('user')->getValue();
        return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "data" => $data[$id]]
                );
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

        return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "message" => 'user has been added']
                );
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

        return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "message" => 'user has been updated']
                );
    }

    public function delete($id){
        $this->database
        ->getReference('user/' . $id)
        ->remove();
        return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "message" => 'user has been deleted']
                );
        //return response()->json('user has been deleted');
    }

    public function loginApps(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        $data = $this->database->getReference('user')->getValue();
        $resultUsername = false;
        $resultPassword = false;
        $arrUsername = [];
        $arrPassword = [];
        //$arr = [];
        foreach ($data as $key => $value) {
            if($username == $value['username']){
                $resultUsername = true;
                $arrUsername[] = $value;
            }
            if($password == $value['password']){
                $resultPassword = true;
                $arrPassword[] = $value;
            }
        }

        if(!$resultUsername)
        {
            return response()->json(
                [
                    "status" => "failed"
                    , "success" =>false
                    , "message" => "Username not available"]
                );
        }
        

        if(!$resultPassword)
        {
            return response()->json(
                [
                     "status" => "failed"
                    , "success" =>false
                    , "message" => "Password not match"]
                );
        }
        

        if($resultPassword && $resultUsername)
        {
            return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "data" => $arrPassword]
                );
        }
        
    }

}
