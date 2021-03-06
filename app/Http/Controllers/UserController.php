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
       $data = $this->database->getReference('user')->getValue();
       $arr = [];
        $no=0;
        foreach ($data as $key => $value) {

            $arr[$no]['id'] = $value['id'];
            $arr[$no]['name'] = $value['name'];
            $arr[$no]['username'] = $value['username'];
            $arr[$no]['password'] = $value['password'];
            $no++;
        }
        if($data != null){
            return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "data" => $arr]
                );
        }else{
            return response()->json(
                [
                    "status" => "failed"
                    , "success" =>false
                    , "message" => "data not available"]
                );
        }
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
            'id'=>$unique,
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
        ->getReference('user/' . $request->id)
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

    public function getHtmlOption()
    {
        $data = $this->database->getReference('user')->getValue();
        $result = [];
        if($data != null)
        {
            $no = 0;
            foreach ($data as $key => $value) {
                $result[$no]['value'] = $value['id'];
                $result[$no]['text'] = $value['name'];
                $no++; 
            }
        }
        return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "data" => $result]
                );
    }

}
