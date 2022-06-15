<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
class MaterialController extends Controller
{

    private $database;

    public function __construct()
    {
        $this->database = \App\Services\FirebaseService::connect();
    }

    public function get(){
        //return response()->json();
        return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "data" => $this->database->getReference('material')->getValue()]
                );
    }

     public function detail($id){
        $data = $this->database->getReference('material')->getValue();
        //$data[$id];
        return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "data" => $data[$id]]
                );
       // return response()->json();
    }


    public function insert(Request $request){
        $unique = strtotime(date('Y-m-d H:i:s'));
        $this->database
        ->getReference('material/' . $unique)
        ->set([
            'id'=>$unique,
            'name' => $request->name,
            'stock'=>0,
            'jenis_material' => $request->jenis_material,
            'jumlah_material' => $request->jumlah_material
        ]);
        return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "message" => 'materials has been added']
                );
    }

    public function update(Request $request){
        $this->database
        ->getReference('material/' . $request->id)
        ->update([
           // 'id_material'=>$unique,
            'name' => $request->name,
            //'stock'=>0,
            'jenis_material' => $request->jenis_material,
            'jumlah_material' => $request->jumlah_material
        ]);

        return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "message" => 'materials has been updated']
                );
    }

    public function delete($id){
        $this->database
        ->getReference('material/' . $id)
        ->remove();

        return response()->json(
                [
                     "status" => "success"
                    , "success" =>true
                    , "message" => 'materials has been deleted']
                );
    }

}
