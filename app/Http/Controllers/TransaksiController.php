<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
class TransaksiController extends Controller
{

    private $database;

    public function __construct()
    {
        $this->database = \App\Services\FirebaseService::connect();
    }

    public function get(){
        return response()->json($this->database->getReference('material')->getValue());
    }

     public function detail($id){
        $data = $this->database->getReference('material')->getValue();
        //$data[$id];
        return response()->json($data[$id]);
    }


    public function insert(Request $request){
        $unique = strtotime(date('Y-m-d H:i:s'));
        $this->database
        ->getReference('material/' . $unique)
        ->set([
            'id_material'=>$unique,
            'name' => $request->name,
            'jenis_material' => $request->jenis_material,
            'jumlah_material' => $request->jumlah_material
        ]);

        return response()->json('materials has been added');
    }

    public function update(Request $request){
        $this->database
        ->getReference('material/' . $request->id_material)
        ->update([
           // 'id_material'=>$unique,
            'name' => $request->name,
            'jenis_material' => $request->jenis_material,
            'jumlah_material' => $request->jumlah_material
        ]);

        return response()->json('material has been updated');
    }

    public function delete(Request $request){
        $this->database
        ->getReference('material/' . $request->id_material)
        ->remove();

        return response()->json('material has been deleted');
    }

}
